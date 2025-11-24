<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CVController extends Controller
{
    /**
     * Показать страницу конструктора CV
     */
    public function index()
    {
        $cv = session()->get('cv', [
            'personal' => [
                'name' => '',
                'email' => '',
                'phone' => '',
                'city' => ''
            ],
            'experience' => [],
            'education' => [],
            'skills' => []
        ]);

        return view('cv.index', compact('cv'));
    }

    /**
     * Сохранить данные CV
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validator = $request->validate([
            'personal.name' => 'required|string|max:100',
            'personal.email' => 'required|email|max:100',
            'personal.phone' => 'nullable|string|max:20',
            'personal.city' => 'required|string|max:100',
            'summary' => 'nullable|string|max:500',
            'title' => 'nullable|string|max:100',
        ], [
            'personal.name.required' => 'Имя обязательно',
            'personal.email.required' => 'Email обязателен',
            'personal.email.email' => 'Email должен быть действительным',
            'personal.city.required' => 'Город обязателен',
        ]);

        $data = $request->all();

        // Санитизация данных (защита от XSS)
        $cv = [
            'personal' => [
                'name' => strip_tags($data['personal']['name'] ?? ''),
                'email' => filter_var($data['personal']['email'] ?? '', FILTER_SANITIZE_EMAIL),
                'phone' => strip_tags($data['personal']['phone'] ?? ''),
                'city' => strip_tags($data['personal']['city'] ?? '')
            ],
            'experience' => $this->sanitizeArray($data['experience'] ?? []),
            'education' => $this->sanitizeArray($data['education'] ?? []),
            'skills' => $this->sanitizeArray($data['skills'] ?? []),
            'summary' => strip_tags($data['summary'] ?? ''),
            'languages' => $this->sanitizeArray($data['languages'] ?? []),
            'links' => $this->sanitizeLinks($data['links'] ?? [])
        ];

        // Дополнительная проверка на пустоту
        if (empty($cv['personal']['name']) || empty($cv['personal']['email'])) {
            return redirect()->route('cv.index')->withErrors(['personal.name' => 'Имя и email обязательны'])->withInput();
        }

        session()->put('cv', $cv);

        // If user requested saving to SQLite library, persist CV there
        if (!empty($data['save_db'])) {
            try {
                $dbDir = $GLOBALS['basePath'] . DIRECTORY_SEPARATOR . 'database';
                if (!is_dir($dbDir)) {
                    @mkdir($dbDir, 0755, true);
                }
                $dbFile = $dbDir . DIRECTORY_SEPARATOR . 'database.sqlite';
                if (!file_exists($dbFile)) {
                    @touch($dbFile);
                }

                $pdo = new \PDO('sqlite:' . $dbFile);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $pdo->exec("CREATE TABLE IF NOT EXISTS cvs (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, data TEXT, created_at TEXT)");

                $title = trim($data['title'] ?? ($cv['personal']['name'] ?? 'Untitled'));
                $stmt = $pdo->prepare('INSERT INTO cvs (title, data, created_at) VALUES (:title, :data, :created_at)');
                $stmt->execute([
                    ':title' => $title,
                    ':data' => json_encode($cv, JSON_UNESCAPED_UNICODE),
                    ':created_at' => date('c'),
                ]);
            } catch (\Throwable $e) {
                // ignore persistence errors
            }
        }

        // If user requested immediate download, generate PDF now
        if (!empty($data['do_download'])) {
            return $this->download();
        }

        return redirect()->route('cv.preview');
    }

    /**
     * Показать превью CV
     */
    public function preview()
    {
        $cv = session()->get('cv', [
            'personal' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+7 (999) 123-45-67',
                'city' => 'Moscow'
            ],
            'experience' => [],
            'education' => [],
            'skills' => []
        ]);

        return view('cv.preview', compact('cv'));
    }

    /**
     * Список сохранённых резюме
     */
    public function list()
    {
        $items = [];
        try {
            $dbFile = $GLOBALS['basePath'] . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';
            if (file_exists($dbFile)) {
                $pdo = new \PDO('sqlite:' . $dbFile);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->query('SELECT id, title, created_at FROM cvs ORDER BY id DESC');
                $items = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\Throwable $e) {
            $items = [];
        }

        return view('cv.list', compact('items'));
    }

    /**
     * Загрузить CV из базы в сессию и перейти к редактированию
     */
    public function load($id)
    {
        try {
            $dbFile = $GLOBALS['basePath'] . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';
            if (file_exists($dbFile)) {
                $pdo = new \PDO('sqlite:' . $dbFile);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->prepare('SELECT data FROM cvs WHERE id = :id LIMIT 1');
                $stmt->execute([':id' => (int) $id]);
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($row && !empty($row['data'])) {
                    $cv = json_decode($row['data'], true);
                    if (is_array($cv)) {
                        session()->put('cv', $cv);
                        return redirect()->route('cv.index')->with('success', 'Резюме загружено из библиотеки');
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->route('cv.list')->withErrors(['load' => 'Не удалось загрузить резюме']);
    }

    /**
     * Скачать CV как текст
     */
    public function download()
    {
        $cv = session()->get('cv', []);

        // Try to generate PDF server-side using Dompdf
        try {
            $html = view('cv.print', compact('cv'))->render();

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();

            return response($output, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="resume.pdf"',
            ]);
        } catch (\Throwable $e) {
            // Fallback: show printable HTML that triggers browser print
            return view('cv.print', compact('cv'));
        }
    }

    /**
     * Генерировать текстовое представление CV
     */
    private function generateTextCV($cv)
    {
        $text = "╔════════════════════════════════════════════════╗\n";
        $text .= "║                   МОЕ РЕЗЮМЕ                   ║\n";
        $text .= "╚════════════════════════════════════════════════╝\n\n";

        // Личная информация
        $text .= "👤 ЛИЧНАЯ ИНФОРМАЦИЯ\n";
        $text .= str_repeat('-', 48) . "\n";
        $text .= "Имя: " . ($cv['personal']['name'] ?? 'Не указано') . "\n";
        $text .= "Email: " . ($cv['personal']['email'] ?? 'Не указано') . "\n";
        $text .= "Телефон: " . ($cv['personal']['phone'] ?? 'Не указано') . "\n";
        $text .= "Город: " . ($cv['personal']['city'] ?? 'Не указано') . "\n\n";

        // Опыт работы
        $text .= "💼 ОПЫТ РАБОТЫ\n";
        $text .= str_repeat('-', 48) . "\n";
        if (!empty($cv['experience'])) {
            foreach ($cv['experience'] as $exp) {
                $text .= "Компания: " . ($exp['company'] ?? 'N/A') . "\n";
                $text .= "Должность: " . ($exp['position'] ?? 'N/A') . "\n";
                $text .= "Период: " . ($exp['period'] ?? 'N/A') . "\n";
                $text .= "Описание: " . ($exp['description'] ?? 'N/A') . "\n\n";
            }
        } else {
            $text .= "Не указано\n\n";
        }

        // Образование
        $text .= "🎓 ОБРАЗОВАНИЕ\n";
        $text .= str_repeat('-', 48) . "\n";
        if (!empty($cv['education'])) {
            foreach ($cv['education'] as $edu) {
                $text .= "Учреждение: " . ($edu['institution'] ?? 'N/A') . "\n";
                $text .= "Специальность: " . ($edu['specialization'] ?? 'N/A') . "\n";
                $text .= "Год: " . ($edu['year'] ?? 'N/A') . "\n\n";
            }
        } else {
            $text .= "Не указано\n\n";
        }

        // Навыки
        $text .= "🛠️ НАВЫКИ\n";
        $text .= str_repeat('-', 48) . "\n";
        if (!empty($cv['skills'])) {
            foreach ($cv['skills'] as $skill) {
                $text .= "• " . ($skill['name'] ?? 'N/A') . " - " . ($skill['level'] ?? 'N/A') . "\n";
            }
        } else {
            $text .= "Не указано\n";
        }

        return $text;
    }

    /**
     * Санитизация массива данных (защита от XSS)
     */
    private function sanitizeArray($array)
    {
        $sanitized = [];
        foreach (array_values($array ?? []) as $idx => $item) {
            if (is_array($item)) {
                $sanitized[$idx] = array_map(function($val) {
                    return is_string($val) ? strip_tags($val) : $val;
                }, $item);
            } else {
                $sanitized[$idx] = is_string($item) ? strip_tags($item) : $item;
            }
        }
        return $sanitized;
    }

    /**
     * Санитизация ссылок (проверка валидности URL)
     */
    private function sanitizeLinks($links)
    {
        $sanitized = [];
        foreach (array_values($links ?? []) as $link) {
            if (!empty($link)) {
                // Проверяем что это действительный URL
                if (filter_var($link, FILTER_VALIDATE_URL)) {
                    $sanitized[] = strip_tags($link);
                }
            }
        }
        return $sanitized;
    }
}

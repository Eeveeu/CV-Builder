<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Сохранённые CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <style>
        body{font-family:Inter,Arial;background:#0f1724;color:#e6eef8;padding:24px}
        .box{max-width:900px;margin:0 auto;background:linear-gradient(180deg,#071021,#071726);padding:20px;border-radius:10px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-bottom:1px solid rgba(255,255,255,0.03)}
        a{color:#c7d2fe;text-decoration:none}
        .actions{margin-top:12px}
    </style>
</head>
<body>
    <div class="box">
        <h1>Библиотека резюме</h1>
        @if(empty($items))
            <p>Резюме ещё не сохранены.</p>
        @else
            <table>
                <thead><tr><th>ID</th><th>Название</th><th>Дата</th><th>Действия</th></tr></thead>
                <tbody>
                    @foreach($items as $it)
                        <tr>
                            <td>{{ $it['id'] }}</td>
                            <td>{{ $it['title'] }}</td>
                            <td>{{ $it['created_at'] }}</td>
                            <td><a href="{{ route('cv.load', ['id' => $it['id']]) }}">Загрузить</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="actions">
            <a href="{{ route('cv.index') }}">← Вернуться к редактору</a>
        </div>
    </div>
</body>
</html>
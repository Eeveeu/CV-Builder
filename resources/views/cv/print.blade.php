<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Печать CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <style>
        /* For Dompdf use DejaVu Sans (supports Cyrillic) as primary; browser will use Inter if available */
        body { font-family: 'DejaVu Sans', 'Inter', Arial, Helvetica, sans-serif; color: #222; margin: 20px; }
        .paper { max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 10px; }
        .name { font-size: 28px; font-weight: 700; }
        .contact { font-size: 14px; color: #555; margin-bottom: 20px; }
        .section { margin-bottom: 16px; }
        .section-title { font-weight: 700; color: #333; margin-bottom: 8px; }
        .job { margin-bottom: 8px; }
        .company { font-weight: 600; color: #333; }
        .period { color: #666; font-size: 13px; }
        .description { margin-top: 6px; color: #333; }
        .skills { display:flex; gap:8px; flex-wrap:wrap; }
        .skill { background:#333;color:#fff;padding:6px 10px;border-radius:12px;font-size:13px }
        @media print { body { margin:0 } .paper { box-shadow:none; } }
    </style>
</head>
<body>
    <div class="paper">
        <div class="header">
            <div class="name">{{ trim(($cv['personal']['surname'] ?? '') . ' ' . ($cv['personal']['name'] ?? '') . ' ' . ($cv['personal']['patronymic'] ?? '')) ?: 'Имя не указано' }}</div>
            <div class="contact">📧 {{ $cv['personal']['email'] ?? '' }} • 📱 {{ $cv['personal']['phone'] ?? '' }} • 📍 {{ $cv['personal']['city'] ?? '' }}</div>
        </div>
        @if(!empty($cv['summary']))
            <div style="margin-bottom:12px; font-style:italic; color:#444">{{ $cv['summary'] }}</div>
        @endif

        @if(!empty($cv['experience']))
            <div class="section">
                <div class="section-title">Опыт работы</div>
                @foreach($cv['experience'] as $exp)
                    <div class="job">
                        <div class="company">{{ $exp['company'] ?? '' }} — <strong>{{ $exp['role'] ?? $exp['position'] ?? '' }}</strong></div>
                        <div class="period">{{ $exp['period'] ?? '' }}</div>
                        @if(!empty($exp['description']))
                            <div class="description">{{ $exp['description'] }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($cv['education']))
            <div class="section">
                <div class="section-title">Образование</div>
                @foreach($cv['education'] as $edu)
                    <div class="job">
                        <div class="company">{{ $edu['school'] ?? $edu['institution'] ?? '' }} — <strong>{{ $edu['degree'] ?? $edu['specialization'] ?? '' }}</strong></div>
                        <div class="period">{{ $edu['year'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($cv['skills']))
            <div class="section">
                <div class="section-title">Навыки</div>
                <div class="skills">
                    @foreach($cv['skills'] as $skill)
                        @if(is_array($skill))
                            <div class="skill">{{ $skill['name'] ?? 'Навык' }}</div>
                        @else
                            <div class="skill">{{ $skill }}</div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($cv['languages']))
            <div class="section">
                <div class="section-title">Языки</div>
                <div>{{ implode(', ', (array) $cv['languages']) }}</div>
            </div>
        @endif

        @if(!empty($cv['links']))
            <div class="section">
                <div class="section-title">Ссылки</div>
                <div>
                    @foreach((array) $cv['links'] as $ln)
                        <div>{{ $ln }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        // Auto open print dialog for convenience (user can choose Save as PDF)
        window.addEventListener('load', function(){
            setTimeout(function(){ window.print(); }, 300);
        });
    </script>
</body>
</html>

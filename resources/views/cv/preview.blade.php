<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Предпросмотр CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg,#0b1220 0%, #0f1724 100%);
            padding: 20px;
            color: #e6eef8;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .preview-wrapper {
            background: linear-gradient(180deg,#071021 0%, #071726 100%);
            padding: 40px;
            box-shadow: 0 8px 50px rgba(2,6,23,0.7);
            border-radius: 10px;
            line-height: 1.6;
            color: #dbeafe;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            padding-bottom: 20px;
        }
        
        .name {
            font-size: 2.2em;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 6px;
        }
        
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 24px;
            font-size: 0.95em;
            color: #9aa9c7;
            flex-wrap: wrap;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 1.15em;
            font-weight: 700;
            color: #c7d2fe;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        
        .experience-item,
        .education-item,
        .skill-item {
            margin-bottom: 15px;
        }
        
        .job-title {
            font-weight: 700;
            color: #e6eef8;
        }
        
        .company {
            color: #9aa9c7;
            font-weight: 600;
        }
        
        .period {
            color: #999;
            font-size: 0.95em;
        }
        
        .description {
            color: #cbd5e1;
            margin-top: 8px;
            padding-left: 12px;
            border-left: 3px solid rgba(255,255,255,0.02);
        }
        
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .skill-badge {
            background: rgba(255,255,255,0.06);
            color: #e6eef8;
            padding: 8px 14px;
            border-radius: 14px;
            font-size: 0.95em;
        }
        
        .button-group {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }
        
        a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background: rgba(255,255,255,0.04);
            color: #dbeafe;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s;
            border: 1px solid rgba(255,255,255,0.03);
        }
        
        a:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .button-group {
                display: none;
            }
            
            .preview-wrapper {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="preview-wrapper">
            @if (!empty($cv['personal']))
                <div class="header-section">
                    <div class="name">{{ $cv['personal']['name'] ?? 'Имя не указано' }}</div>
                    <div class="contact-info">
                        <span>📧 {{ $cv['personal']['email'] ?? 'email@example.com' }}</span>
                        <span>📱 {{ $cv['personal']['phone'] ?? '+7 (000) 000-00-00' }}</span>
                        <span>📍 {{ $cv['personal']['city'] ?? 'Город не указан' }}</span>
                    </div>
                    @if(!empty($cv['summary']))
                        <div style="margin-top:12px;text-align:center;color:#cbd5e1;">{{ $cv['summary'] }}</div>
                    @endif
                </div>
            @endif

            @if (!empty($cv['experience']))
                <div class="section">
                    <div class="section-title">💼 Опыт работы</div>
                    @foreach ($cv['experience'] as $exp)
                        <div class="experience-item">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <div>
                                    <div class="job-title">{{ $exp['role'] ?? $exp['position'] ?? 'Должность не указана' }}</div>
                                    <div class="company">{{ $exp['company'] ?? 'Компания не указана' }}</div>
                                </div>
                                <div class="period">{{ $exp['period'] ?? 'Период не указан' }}</div>
                            </div>
                            @if (!empty($exp['description']))
                                <div class="description">{{ $exp['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if (!empty($cv['education']))
                <div class="section">
                    <div class="section-title">🎓 Образование</div>
                    @foreach ($cv['education'] as $edu)
                        <div class="education-item">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <div>
                                    <div class="job-title">{{ $edu['degree'] ?? $edu['specialization'] ?? 'Специальность не указана' }}</div>
                                    <div class="company">{{ $edu['school'] ?? $edu['institution'] ?? 'Учебное заведение не указано' }}</div>
                                </div>
                                <div class="period">{{ $edu['year'] ?? 'Год не указан' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (!empty($cv['skills']))
                <div class="section">
                    <div class="section-title">🛠️ Навыки</div>
                    <div class="skills-list">
                        @foreach ($cv['skills'] as $skill)
                            <div class="skill-badge">
                                @if(is_array($skill))
                                    {{ $skill['name'] ?? $skill['title'] ?? 'Навык' }}
                                    @if (!empty($skill['level']))
                                        ({{ $skill['level'] }})
                                    @endif
                                @else
                                    {{ $skill }}
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($cv['languages']))
                <div class="section">
                    <div class="section-title">🌐 Языки</div>
                    <div>{{ implode(', ', (array) $cv['languages']) }}</div>
                </div>
            @endif

            @if (!empty($cv['links']))
                <div class="section">
                    <div class="section-title">🔗 Ссылки</div>
                    <div>
                        @foreach((array) $cv['links'] as $ln)
                            <div><a href="{{ $ln }}" style="color:#c7d2fe">{{ $ln }}</a></div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="button-group">
                <a href="{{ route('cv.index') }}">← Вернуться к редактированию</a>
                <a href="{{ route('cv.download') }}">⬇️ Скачать CV</a>
                <a href="javascript:window.print()">🖨️ Печать</a>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Builder - Конструктор резюме</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(180deg, #0f1724 0%, #1f2937 100%);
            min-height: 100vh;
            padding: 20px;
            color: #e6eef8;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .form-wrapper {
            background: linear-gradient(180deg,#0b1220 0%, #0f1724 100%);
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(2,6,23,0.7);
            padding: 40px;
            margin-bottom: 30px;
            color: #dbeafe;
            border: 1px solid rgba(255,255,255,0.03);
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section h2 {
            font-size: 1.5em;
            color: #c7d2fe;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid rgba(99,102,241,0.18);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #9aa9c7;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea,
        select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.03);
            color: #e6eef8;
            border-radius: 6px;
            font-size: 0.98em;
            transition: border-color 0.25s, box-shadow 0.25s;
            font-family: inherit;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .row.full {
            grid-template-columns: 1fr;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        button {
            padding: 12px 30px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: rgba(255,255,255,0.04);
            color: #dbeafe;
            flex: 1;
            border: 1px solid rgba(255,255,255,0.03);
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-remove {
            padding: 6px 12px;
            font-size: 0.85em;
            border: 1px solid rgba(255,64,129,0.3);
            background: rgba(255,64,129,0.05);
            color: #ff4081;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }

        .btn-remove:hover {
            background: rgba(255,64,129,0.15);
            border-color: rgba(255,64,129,0.6);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .form-wrapper {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📄 CV Builder</h1>
            <p>Создайте своё профессиональное резюме за 5 минут</p>
        </div>

        <div class="form-wrapper">
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Ошибки валидации:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="cv-form" action="{{ route('cv.store') }}" method="POST" onsubmit="document.getElementById('do_download').value=''; document.getElementById('save_db').value='';">
                @csrf
                <input type="hidden" name="do_download" id="do_download" value="">
                <input type="hidden" name="save_db" id="save_db" value="">

                <!-- Личная информация -->
                <div class="form-section">
                    <h2>👤 Личная информация</h2>
                    <div class="row full">
                        <div class="form-group">
                            <label>Название резюме (для сохранения)</label>
                            <input type="text" name="title" value="{{ old('title', $cv['personal']['name'] ?? '') }}" placeholder="Например: Резюме — Иванов">
                        </div>
                    </div>

                    <div class="row full">
                        <div class="form-group">
                            <label>Краткое описание / Summary</label>
                            <textarea name="summary" placeholder="Кратко о себе">{{ $cv['summary'] ?? '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <label for="surname">Фамилия *</label>
                            <input type="text" id="surname" name="personal[surname]" value="{{ $cv['personal']['surname'] ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Имя *</label>
                            <input type="text" id="name" name="personal[name]" value="{{ $cv['personal']['name'] ?? '' }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="patronymic">Отчество</label>
                            <input type="text" id="patronymic" name="personal[patronymic]" value="{{ $cv['personal']['patronymic'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="personal[email]" value="{{ $cv['personal']['email'] ?? '' }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="phone">Телефон *</label>
                            <input type="tel" id="phone" name="personal[phone]" value="{{ $cv['personal']['phone'] ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="city">Город *</label>
                            <input type="text" id="city" name="personal[city]" value="{{ $cv['personal']['city'] ?? '' }}" required>
                        </div>
                    </div>
                </div>

                <!-- Опыт работы -->
                <div class="form-section">
                    <h2>💼 Опыт работы</h2>
                    <div id="experience-list">
                        @php $exList = $cv['experience'] ?? [] @endphp
                        @if(empty($exList))
                            <div class="exp-item">
                                <div class="row full">
                                    <div class="form-group"><label>Компания</label><input type="text" name="experience[0][company]" placeholder="Название компании"></div>
                                </div>
                                <div class="row">
                                    <div class="form-group"><label>Должность</label><input type="text" name="experience[0][role]" placeholder="Должность"></div>
                                    <div class="form-group"><label>Период работы</label><input type="text" name="experience[0][period]" placeholder="2020 - 2024"></div>
                                </div>
                                <div class="row full"><div class="form-group"><label>Описание должности</label><textarea name="experience[0][description]" placeholder="Опишите ваши достижения"></textarea></div></div>
                            </div>
                        @else
                            @foreach($exList as $i => $exp)
                                <div class="exp-item">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                        <span style="color:#9aa9c7">Опыт работы #{{ $i + 1 }}</span>
                                        <button type="button" class="btn-remove" onclick="removeExperience(this)">❌ Удалить</button>
                                    </div>
                                    <div class="row full">
                                        <div class="form-group"><label>Компания</label><input type="text" name="experience[{{ $i }}][company]" value="{{ $exp['company'] ?? '' }}" placeholder="Название компании"></div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group"><label>Должность</label><input type="text" name="experience[{{ $i }}][role]" value="{{ $exp['role'] ?? $exp['position'] ?? '' }}" placeholder="Должность"></div>
                                        <div class="form-group"><label>Период работы</label><input type="text" name="experience[{{ $i }}][period]" value="{{ $exp['period'] ?? '' }}" placeholder="2020 - 2024"></div>
                                    </div>
                                    <div class="row full"><div class="form-group"><label>Описание должности</label><textarea name="experience[{{ $i }}][description]" placeholder="Опишите ваши достижения">{{ $exp['description'] ?? '' }}</textarea></div></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div style="margin-top:10px">
                        <button type="button" class="btn-secondary" onclick="addExperience()">➕ Добавить опыт</button>
                    </div>
                </div>

                <!-- Образование -->
                <div class="form-section">
                    <h2>🎓 Образование</h2>
                    <div id="education-list">
                        @php $edList = $cv['education'] ?? [] @endphp
                        @if(empty($edList))
                            <div class="edu-item">
                                <div class="row full"><div class="form-group"><label>Учебное заведение</label><input type="text" name="education[0][school]" placeholder="Название университета"></div></div>
                                <div class="row"><div class="form-group"><label>Специальность</label><input type="text" name="education[0][degree]" placeholder="Специальность"></div><div class="form-group"><label>Год окончания</label><input type="text" name="education[0][year]" placeholder="2024"></div></div>
                            </div>
                        @else
                            @foreach($edList as $i => $edu)
                                <div class="edu-item">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                        <span style="color:#9aa9c7">Образование #{{ $i + 1 }}</span>
                                        <button type="button" class="btn-remove" onclick="removeEducation(this)">❌ Удалить</button>
                                    </div>
                                    <div class="row full"><div class="form-group"><label>Учебное заведение</label><input type="text" name="education[{{ $i }}][school]" value="{{ $edu['school'] ?? $edu['institution'] ?? '' }}" placeholder="Название университета"></div></div>
                                    <div class="row"><div class="form-group"><label>Специальность</label><input type="text" name="education[{{ $i }}][degree]" value="{{ $edu['degree'] ?? $edu['specialization'] ?? '' }}" placeholder="Специальность"></div><div class="form-group"><label>Год окончания</label><input type="text" name="education[{{ $i }}][year]" value="{{ $edu['year'] ?? '' }}" placeholder="2024"></div></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div style="margin-top:10px">
                        <button type="button" class="btn-secondary" onclick="addEducation()">➕ Добавить образование</button>
                    </div>
                </div>

                <!-- Навыки -->
                <div class="form-section">
                    <h2>🛠️ Навыки</h2>
                    <div id="skills-list">
                        @php $skillsList = $cv['skills'] ?? [] @endphp
                        @if(empty($skillsList))
                            <div class="skill-item">
                                <div class="row full">
                                    <div class="form-group"><label>Навык</label><input type="text" name="skills[0]" placeholder="Например: PHP, Laravel, SQL"></div>
                                </div>
                            </div>
                        @else
                            @foreach($skillsList as $i => $skill)
                                <div class="skill-item">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                        <span style="color:#9aa9c7">Навык #{{ $i + 1 }}</span>
                                        @if($i > 0)
                                            <button type="button" class="btn-remove" onclick="removeSkill(this)">❌ Удалить</button>
                                        @endif
                                    </div>
                                    <div class="row full">
                                        <div class="form-group"><label>Навык</label><input type="text" name="skills[{{ $i }}]" value="{{ $skill }}" placeholder="Например: PHP, Laravel, SQL"></div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div style="margin-top:10px">
                        <button type="button" class="btn-secondary" onclick="addSkill()">➕ Добавить навык</button>
                    </div>
                </div>

                <!-- Языки -->
                <div class="form-section">
                    <h2>🌐 Языки</h2>
                    <div id="languages-list">
                        @php $langList = $cv['languages'] ?? [] @endphp
                        @if(empty($langList))
                            <div class="lang-item">
                                <div class="row full">
                                    <div class="form-group"><label>Язык</label><input type="text" name="languages[0]" placeholder="Например: Русский, Английский, Немецкий"></div>
                                </div>
                            </div>
                        @else
                            @foreach($langList as $i => $lang)
                                <div class="lang-item">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                        <span style="color:#9aa9c7">Язык #{{ $i + 1 }}</span>
                                        @if($i > 0)
                                            <button type="button" class="btn-remove" onclick="removeLanguage(this)">❌ Удалить</button>
                                        @endif
                                    </div>
                                    <div class="row full">
                                        <div class="form-group"><label>Язык</label><input type="text" name="languages[{{ $i }}]" value="{{ $lang }}" placeholder="Например: Русский, Английский, Немецкий"></div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div style="margin-top:10px">
                        <button type="button" class="btn-secondary" onclick="addLanguage()">➕ Добавить язык</button>
                    </div>
                </div>

                <div class="form-section">
                    <h2>🔗 Ссылки</h2>
                    <div id="links-list">
                        @php $linkList = $cv['links'] ?? [] @endphp
                        @if(empty($linkList))
                            <div class="link-item">
                                <div class="row full">
                                    <div class="form-group"><label>Ссылка</label><input type="text" name="links[0]" placeholder="https://github.com/your-profile"></div>
                                </div>
                            </div>
                        @else
                            @foreach($linkList as $i => $link)
                                <div class="link-item">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                        <span style="color:#9aa9c7">Ссылка #{{ $i + 1 }}</span>
                                        @if($i > 0)
                                            <button type="button" class="btn-remove" onclick="removeLink(this)">❌ Удалить</button>
                                        @endif
                                    </div>
                                    <div class="row full">
                                        <div class="form-group"><label>Ссылка</label><input type="text" name="links[{{ $i }}]" value="{{ $link }}" placeholder="https://..."></div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div style="margin-top:10px">
                        <button type="button" class="btn-secondary" onclick="addLink()">➕ Добавить ссылку</button>
                    </div>
                </div>

                <!-- Кнопки -->
                <div class="button-group">
                    <button type="submit" class="btn-primary">💾 Сохранить CV</button>
                    <button type="button" class="btn-secondary" style="display:flex;align-items:center;justify-content:center;" onclick="previewNow()">👁️ Предпросмотр</button>
                    <button type="button" class="btn-secondary" style="display:flex;align-items:center;justify-content:center;" onclick="downloadNow()">⬇️ Скачать</button>
                    <button type="button" class="btn-secondary" style="display:flex;align-items:center;justify-content:center;" onclick="saveToLibrary()">📚 Сохранить в библиотеку</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewNow(){
            // ensure do_download is cleared and submit, store() will redirect to preview
            document.getElementById('do_download').value = '';
            document.getElementById('cv-form').submit();
        }

        function downloadNow(){
            // set flag so controller will generate PDF response after saving session
            document.getElementById('do_download').value = '1';
            document.getElementById('cv-form').submit();
        }

        function saveToLibrary(){
            document.getElementById('save_db').value = '1';
            document.getElementById('cv-form').submit();
        }

        function addExperience(){
            const list = document.getElementById('experience-list');
            const idx = list.querySelectorAll('.exp-item').length;
            const wrapper = document.createElement('div'); wrapper.className='exp-item';
            wrapper.innerHTML = `
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <span style="color:#9aa9c7">Опыт работы #${idx + 1}</span>
                    <button type="button" class="btn-remove" onclick="removeExperience(this)">❌ Удалить</button>
                </div>
                <div class="row full"><div class="form-group"><label>Компания</label><input type="text" name="experience[${idx}][company]"></div></div>
                <div class="row"><div class="form-group"><label>Должность</label><input type="text" name="experience[${idx}][role]"></div><div class="form-group"><label>Период работы</label><input type="text" name="experience[${idx}][period]"></div></div>
                <div class="row full"><div class="form-group"><label>Описание должности</label><textarea name="experience[${idx}][description]"></textarea></div></div>
            `;
            list.appendChild(wrapper);
        }

        function removeExperience(btn){
            btn.closest('.exp-item').remove();
        }

        function addEducation(){
            const list = document.getElementById('education-list');
            const idx = list.querySelectorAll('.edu-item').length;
            const wrapper = document.createElement('div'); wrapper.className='edu-item';
            wrapper.innerHTML = `
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <span style="color:#9aa9c7">Образование #${idx + 1}</span>
                    <button type="button" class="btn-remove" onclick="removeEducation(this)">❌ Удалить</button>
                </div>
                <div class="row full"><div class="form-group"><label>Учебное заведение</label><input type="text" name="education[${idx}][school]"></div></div>
                <div class="row"><div class="form-group"><label>Специальность</label><input type="text" name="education[${idx}][degree]"></div><div class="form-group"><label>Год окончания</label><input type="text" name="education[${idx}][year]"></div></div>
            `;
            list.appendChild(wrapper);
        }

        function removeEducation(btn){
            btn.closest('.edu-item').remove();
        }

        function addLanguage(){
            const list = document.getElementById('languages-list');
            const idx = list.querySelectorAll('.lang-item').length;
            const wrapper = document.createElement('div'); wrapper.className='lang-item';
            wrapper.innerHTML = `
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <span style="color:#9aa9c7">Язык #${idx + 1}</span>
                    <button type="button" class="btn-remove" onclick="removeLanguage(this)">❌ Удалить</button>
                </div>
                <div class="row full"><div class="form-group"><label>Язык</label><input type="text" name="languages[${idx}]" placeholder="Например: Русский, Английский, Немецкий"></div></div>
            `;
            list.appendChild(wrapper);
        }

        function removeLanguage(btn){
            btn.closest('.lang-item').remove();
        }

        function addSkill(){
            const list = document.getElementById('skills-list');
            const idx = list.querySelectorAll('.skill-item').length;
            const wrapper = document.createElement('div'); wrapper.className='skill-item';
            wrapper.innerHTML = `
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <span style="color:#9aa9c7">Навык #${idx + 1}</span>
                    <button type="button" class="btn-remove" onclick="removeSkill(this)">❌ Удалить</button>
                </div>
                <div class="row full"><div class="form-group"><label>Навык</label><input type="text" name="skills[${idx}]" placeholder="Например: PHP, Laravel, SQL"></div></div>
            `;
            list.appendChild(wrapper);
        }

        function removeSkill(btn){
            btn.closest('.skill-item').remove();
        }

        function addLink(){
            const list = document.getElementById('links-list');
            const idx = list.querySelectorAll('.link-item').length;
            const wrapper = document.createElement('div'); wrapper.className='link-item';
            wrapper.innerHTML = `
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <span style="color:#9aa9c7">Ссылка #${idx + 1}</span>
                    <button type="button" class="btn-remove" onclick="removeLink(this)">❌ Удалить</button>
                </div>
                <div class="row full"><div class="form-group"><label>Ссылка</label><input type="text" name="links[${idx}]" placeholder="https://..."></div></div>
            `;
            list.appendChild(wrapper);
        }

        function removeLink(btn){
            btn.closest('.link-item').remove();
        }
    </script>
</body>
</html>

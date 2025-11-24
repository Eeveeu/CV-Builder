from flask import Flask, render_template, request, jsonify
import json
import os
from datetime import datetime

app = Flask(__name__)

# Путь для сохранения CV
CV_FILE = 'cv_data.json'

def load_cv():
    """Загружает данные CV из файла"""
    if os.path.exists(CV_FILE):
        with open(CV_FILE, 'r', encoding='utf-8') as f:
            return json.load(f)
    return {
        'personal': {},
        'experience': [],
        'education': [],
        'skills': []
    }

def save_cv(data):
    """Сохраняет данные CV в файл"""
    with open(CV_FILE, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

@app.route('/')
def index():
    cv_data = load_cv()
    return render_template('index.html', cv=cv_data)

@app.route('/api/cv', methods=['GET'])
def get_cv():
    return jsonify(load_cv())

@app.route('/api/cv', methods=['POST'])
def update_cv():
    data = request.json
    save_cv(data)
    return jsonify({'status': 'success', 'message': 'CV сохранено'})

@app.route('/api/export', methods=['GET'])
def export_cv():
    cv_data = load_cv()
    cv_text = generate_text_cv(cv_data)
    return jsonify({
        'status': 'success',
        'content': cv_text
    })

def generate_text_cv(cv):
    """Генерирует текстовое представление CV"""
    text = f"""
╔════════════════════════════════════════════════╗
║                   МОЕ РЕЗЮМЕ                   ║
╚════════════════════════════════════════════════╝

👤 ЛИЧНАЯ ИНФОРМАЦИЯ
{'-' * 48}
Имя: {cv['personal'].get('name', 'Не указано')}
Email: {cv['personal'].get('email', 'Не указано')}
Телефон: {cv['personal'].get('phone', 'Не указано')}
Город: {cv['personal'].get('city', 'Не указано')}

💼 ОПЫТ РАБОТЫ
{'-' * 48}
"""
    for exp in cv['experience']:
        text += f"""
Компания: {exp.get('company', 'N/A')}
Должность: {exp.get('position', 'N/A')}
Период: {exp.get('period', 'N/A')}
Описание: {exp.get('description', 'N/A')}
"""
    
    text += f"""
🎓 ОБРАЗОВАНИЕ
{'-' * 48}
"""
    for edu in cv['education']:
        text += f"""
Учреждение: {edu.get('institution', 'N/A')}
Специальность: {edu.get('specialization', 'N/A')}
Год: {edu.get('year', 'N/A')}
"""
    
    text += f"""
🛠️ НАВЫКИ
{'-' * 48}
"""
    for skill in cv['skills']:
        text += f"• {skill.get('name', 'N/A')} - {skill.get('level', 'N/A')}\n"
    
    return text

if __name__ == '__main__':
    app.run(debug=True, port=5000)

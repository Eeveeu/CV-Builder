# Гайд по развёртыванию CV Builder на Heroku, VPS и сохранению в GitHub

## 1️⃣ Подготовка к GitHub

### Инициализируйте Git репозиторий (если ещё не инициализирован)

```bash
cd c:\OSPanel\home\cv-builder
git init
git add .
git commit -m "Initial commit: CV Builder project"
```

### Создайте репозиторий на GitHub

1. Перейдите на https://github.com/new
2. Введите имя: **cv-builder**
3. Добавьте описание: "CV Builder - конструктор резюме на Laravel"
4. Выберите **Public** (чтобы другие могли видеть)
5. Нажмите **Create repository**

### Залейте код на GitHub

```bash
git remote add origin https://github.com/YOUR_USERNAME/cv-builder.git
git branch -M main
git push -u origin main
```

Замените `YOUR_USERNAME` на ваше имя пользователя GitHub.

---

## 2️⃣ Развёртывание на Heroku

### Требования
- Heroku CLI (скачайте с https://devcenter.heroku.com/articles/heroku-cli)
- GitHub аккаунт

### Шаги

#### 1. Установите Heroku CLI и авторизуйтесь

```bash
heroku login
```

#### 2. Создайте приложение Heroku

```bash
heroku create YOUR_APP_NAME
# Например: heroku create my-cv-builder
```

#### 3. Проверьте, что `Procfile` в корне проекта

Файл должен содержать:
```
web: vendor/bin/heroku-php-nginx public/
```

#### 4. Установите buildpack для PHP

```bash
heroku buildpacks:add heroku/php
```

#### 5. Добавьте переменные окружения

```bash
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_KEY=base64:YOUR_16_BYTE_KEY
```

Замените `YOUR_16_BYTE_KEY` на значение из вашего `.env`.

#### 6. Залейте код на Heroku

```bash
git push heroku main
```

#### 7. Откройте приложение

```bash
heroku open
```

Или перейдите на `https://YOUR_APP_NAME.herokuapp.com/cv`.

---

## 3️⃣ Развёртывание на VPS (Ubuntu 20.04+)

### Требования
- VPS с Ubuntu 20.04+ (например, DigitalOcean, Linode, Vultr)
- SSH доступ
- Домен (опционально, но рекомендуется)

### Шаги

#### 1. Подключитесь к VPS

```bash
ssh root@YOUR_VPS_IP
```

#### 2. Обновите систему

```bash
apt update && apt upgrade -y
```

#### 3. Установите необходимые пакеты

```bash
apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-sqlite3 php8.3-mbstring \
    php8.3-xml php8.3-curl php8.3-gd php8.3-zip composer nginx git curl
```

#### 4. Создайте пользователя для приложения

```bash
useradd -m -s /bin/bash cv-app
su - cv-app
```

#### 5. Клонируйте репозиторий

```bash
cd /home/cv-app
git clone https://github.com/YOUR_USERNAME/cv-builder.git
cd cv-builder
```

#### 6. Установите зависимости

```bash
composer install --no-dev --optimize-autoloader
```

#### 7. Создайте необходимые директории

```bash
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p database
chmod -R 755 storage database
```

#### 8. Настройте права доступа

```bash
exit  # выходим от cv-app
sudo chown -R cv-app:cv-app /home/cv-app/cv-builder
sudo chmod -R 755 /home/cv-app/cv-builder/storage
sudo chmod -R 755 /home/cv-app/cv-builder/database
```

#### 9. Создайте конфиг Nginx

```bash
sudo nano /etc/nginx/sites-available/cv-builder
```

Вставьте:

```nginx
server {
    listen 80;
    server_name YOUR_DOMAIN.com www.YOUR_DOMAIN.com;
    root /home/cv-app/cv-builder/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Замените `YOUR_DOMAIN.com` на ваш домен.

#### 10. Включите сайт

```bash
sudo ln -s /etc/nginx/sites-available/cv-builder /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### 11. Установите SSL (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d YOUR_DOMAIN.com -d www.YOUR_DOMAIN.com
```

#### 12. Автообновление кода с GitHub

Создайте скрипт для автообновления (опционально):

```bash
sudo nano /home/cv-app/pull-updates.sh
```

```bash
#!/bin/bash
cd /home/cv-app/cv-builder
git pull origin main
composer install --no-dev --optimize-autoloader
sudo systemctl restart php8.3-fpm
```

```bash
chmod +x /home/cv-app/pull-updates.sh
```

Используйте GitHub Actions или cron для автоматических обновлений.

---

## 4️⃣ GitHub Actions для CI/CD (опционально)

Создайте файл `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Heroku

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Deploy to Heroku
        env:
          HEROKU_API_KEY: ${{ secrets.HEROKU_API_KEY }}
          HEROKU_APP_NAME: ${{ secrets.HEROKU_APP_NAME }}
        run: |
          git remote add heroku https://git.heroku.com/$HEROKU_APP_NAME.git
          git push heroku main
```

Затем добавьте secrets в GitHub (Settings → Secrets):
- `HEROKU_API_KEY` — ваш API ключ Heroku
- `HEROKU_APP_NAME` — имя приложения на Heroku

---

## 5️⃣ Проверка работы

После развёртывания откройте приложение и проверьте:

1. ✅ Форма загружается (http://YOUR_SITE/cv)
2. ✅ Заполнение и сохранение данных работают
3. ✅ Предпросмотр отображает текущие данные
4. ✅ Скачивание PDF работает корректно
5. ✅ Сохранение в библиотеку работает
6. ✅ Список резюме доступен (http://YOUR_SITE/cv/list)

---

## 🆘 Решение проблем

### Heroku: "Application error"
```bash
heroku logs --tail
# Проверьте логи для ошибок
```

### VPS: "502 Bad Gateway"
```bash
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
sudo tail -f /var/log/php8.3-fpm.log
```

### PDF показывает "????" (кириллица)
- Убедитесь, что PHP 8.2+ установлен
- Проверьте Dompdf логи
- Используйте шрифт DejaVu Sans (встроен в Dompdf)

---

## 📝 Примечания

- **Heroku** — простой способ, бесплатный первый месяц, потом платно
- **VPS** — полный контроль, дешевле на длительный срок, нужна базовая администрирование
- **GitHub Pages** — только для статики, не подходит для этого проекта

Выбирайте в зависимости от ваших потребностей!

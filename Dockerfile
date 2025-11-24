FROM php:8.3-cli

# Установи необходимые расширения
RUN apt-get update && apt-get install -y \
    curl \
    sqlite3 \
    git \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Скачай Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установи директорию работы
WORKDIR /app

# Скопируй файлы
COPY . .

# Установи зависимости Composer
RUN composer install --no-dev --optimize-autoloader

# Создай необходимые директории, включая bootstrap cache
RUN mkdir -p storage/framework/{cache,sessions,views} database bootstrap/cache && \
    chmod -R 755 storage database bootstrap/cache

# Добавим entrypoint, который гарантирует наличие .env и APP_KEY и запустит сервер
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Открой порт
EXPOSE 10000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

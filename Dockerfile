FROM php:8.3-fpm

# Установи необходимые расширения
RUN apt-get update && apt-get install -y \
    composer \
    curl \
    sqlite3 \
    && rm -rf /var/lib/apt/lists/*

# Установи директорию работы
WORKDIR /app

# Скопируй файлы
COPY . .

# Установи зависимости Composer
RUN composer install --no-dev --optimize-autoloader

# Создай необходимые директории
RUN mkdir -p storage/framework/{cache,sessions,views} database && \
    chmod -R 755 storage database

# Открой порт
EXPOSE 10000

# Запусти приложение
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]

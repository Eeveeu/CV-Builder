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

# Создай необходимые директории
RUN mkdir -p storage/framework/{cache,sessions,views} database && \
    chmod -R 755 storage database

# Открой порт
EXPOSE 10000

# Запусти приложение
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]

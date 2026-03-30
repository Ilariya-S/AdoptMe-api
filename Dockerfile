FROM php:8.3-cli

# Встановлюємо системні пакети (додав кілька додаткових для надійності)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Задаємо робочу директорію
WORKDIR /app

# Копіюємо всі файли проєкту
COPY . .

# МАГІЯ ТУТ: додаємо прапорці, щоб ігнорувати жорсткі вимоги до платформи і не запускати зайві скрипти під час збірки
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs --no-scripts

# Вказуємо порт
EXPOSE 10000

# Запускаємо наш скрипт з міграціями і стартуємо сервер
CMD sh render-build.sh && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
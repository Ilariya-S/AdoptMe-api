FROM php:8.2-cli

# Встановлюємо системні пакети, потрібні для підключення до MySQL (Aiven)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Задаємо робочу директорію
WORKDIR /app

# Копіюємо всі файли проєкту
COPY . .

# Встановлюємо PHP-залежності
RUN composer install --optimize-autoloader --no-dev

# Вказуємо порт (Render сам підставить потрібний через змінну $PORT)
EXPOSE 10000

# Запускаємо наш скрипт з міграціями і стартуємо сервер
CMD sh render-build.sh && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
FROM php:8.2-cli

# Встановлюємо лише найнеобхідніше для підключення до бази Aiven
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

WORKDIR /app

# Копіюємо весь проєкт РАЗОМ із папкою vendor
COPY . .

EXPOSE 10000

# Запускаємо міграції та сервер напряму, без збірки
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
#!/usr/bin/env bash
# Зупиняємо скрипт при будь-якій помилці
set -e

echo "Встановлюємо залежності..."
composer install --optimize-autoloader --no-dev

echo "Кешуємо конфіги..."
php artisan config:cache
php artisan route:cache

echo "Запускаємо міграції..."
php artisan migrate --force
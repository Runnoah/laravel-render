#!/usr/bin/env bash

echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Clearing route cache..."
php artisan route:clear

echo "Running migrations..."
php artisan migrate --seed --force

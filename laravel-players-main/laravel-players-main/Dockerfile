FROM php:8.2-fpm 

# 1. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \ 
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev libpq-dev \ 
    nodejs npm \ 
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl gd intl \ 
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www 

# OPTIMIZACIÓN: Copiar primero archivos de dependencias para usar caché de capas
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

COPY package.json package-lock.json ./
RUN npm install

# Ahora sí, copiar el resto del proyecto
COPY . . 

# Finalizar instalaciones
RUN composer dump-autoload --optimize
RUN npm run build 

# Permisos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000 

# 6. COMANDO DE ARRANQUE ROBUSTO
# Esperamos 5-10 segundos a que la DB despierte, luego migramos y servimos.
CMD bash -c "sleep 10 && \
    php artisan config:clear && \
    php artisan migrate:fresh --seed --force && \
    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=8000"
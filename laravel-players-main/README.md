# DESPLIEGUE APLICACION EN LARAVEL/PHP

## Indice

- [Despliegue en local](#Despliegue-en-local)
- [Despliegue Dev](#Despliegue-Dev)
- [Despliegue en Render](#Despliegue-en-Render)

## Despliegue en local

### Descripción general

Aplicación levantada en local mediante el servidor local que usa laravel y base de datos desplegada en docker.

### Docker-compose.local.yml

```yml
services:
  db:
    image: postgres:16
    container_name: postgres_players
    restart: always
    environment:
      POSTGRES_USER: user
      POSTGRES_PASSWORD: 1234
      POSTGRES_DB: laravel
    ports:
      - "5434:5432"
```

### Funcionamiento

Asegurarse de al descargar el repositorio añadir las variables de entorno mediante un .env para conectar con la base de datos.
Utilizar los siguientes comandos:

```cmd
docker-compose -f docker-compose.local.yml up --build
```
Para desplegar el contenedor de la base de datos

```cmd
composer install
```

Para instalar las dependencias del proyecto

```cmd
php artisan migrate
```
o
```cmd
php artisan migrate:fresh --seed
```
Para inicializar las tablas y los seeders en la base de datos ya levantada.

```cmd
php artisan serve
```
Y este último, para levantar el servidor en local de la aplicación.

## Despliegue Dev

### Descripción general

No hace falta usar los anteriores comandos, de eso se ocupa el Dockerfile. Aplicación levantada en docker completamente.

### Dockerfile

```Dockerfile
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
```

### Docker-compose.dev.yml

```yml
services:
  app:
    build: .
    ports:
      - "8000:8000"
    environment:
      - DB_CONNECTION=pgsql
      - DB_HOST=postgres_players_dev
      - DB_PORT=5432
      - DB_DATABASE=laravel
      - DB_USERNAME=user
      - DB_PASSWORD=1234
      - APP_URL=http://localhost:8000
      - APP_ENV=local
      - APP_DEBUG=true
      - APP_KEY=base64:nC/0qBorpeSiEX2e++XFKfixa1Srke0PBjNH/zf9abY=
      - SESSION_DRIVER=database
      - CACHE_STORE=database
      - LOG_CHANNEL=stderr
    depends_on:
      - db  
  
  db:
    image: postgres:16
    container_name: postgres_players_dev
    restart: always
    environment:
      POSTGRES_USER: user
      POSTGRES_PASSWORD: 1234
      POSTGRES_DB: laravel
    ports:
      - "5434:5432"
```

### Funcionamiento

Directamente en este apartado hay que lanzar en la consola el siguiente comando para levantar los dos contenedores y automáticamente el Dockerfile hará las migraciones y los seeders en la base de datos.

```cmd
docker-compose -f docker-compose.dev.yml up --build
```

## Despliegue en Render

### Descripción general

Aplicación levantada en la nube mediante servicios web de Render, la base de datos estará levantada en un servicio postgre y la aplicación en un servicio web.

### Dockerfile

```Dockerfile
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
```
Se usa el mismo que en el dev

### Funcionamiento

Se añaden las variables de entorno necesarias mediante la url interna que nos da render.

#### Servicio postgre de base de datos

[BD](https://dashboard.render.com/d/dpg-d5jtomp5pdvs73a1irr0-a)

#### Servicio web de la aplicacion

[API](https://laravel-players.onrender.com/api/players)

[WEB](https://laravel-players.onrender.com/players)

Con mucho love, jose ❤️.

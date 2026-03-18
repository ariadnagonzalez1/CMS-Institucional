FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

# 🔥 LIMPIAR CACHE (CLAVE)
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan view:clear

# 🔥 CREAR SQLITE
RUN touch database/database.sqlite

# 🔥 PERMISOS
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
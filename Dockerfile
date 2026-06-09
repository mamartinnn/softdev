# Railway force rebuild - v2
FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    nodejs npm git curl \
    libpng-dev libxml2-dev libzip-dev \
    oniguruma-dev freetype-dev libjpeg-turbo-dev \
    zip unzip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_mysql mbstring xml zip bcmath ctype fileinfo dom gd opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

COPY . .
RUN composer run-script post-autoload-dump || true
RUN npm ci && npm run build

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

RUN printf '#!/bin/sh\nphp artisan config:cache\nphp artisan route:cache\nphp artisan view:cache\nphp artisan migrate --force\nexec php artisan serve --host=0.0.0.0 --port=80\n' > /start.sh \
    && chmod +x /start.sh

ENTRYPOINT []
CMD ["/bin/sh", "/start.sh"]
EXPOSE 80   
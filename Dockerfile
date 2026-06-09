FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    zip \
    unzip

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

# Nginx - log to stdout/stderr
RUN printf 'server {\n\
    listen 80;\n\
    root /var/www/public;\n\
    index index.php;\n\
    client_max_body_size 20M;\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location ~ \\.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
    location ~ /\\.ht { deny all; }\n\
}\n' > /etc/nginx/http.d/default.conf

# Redirect nginx logs to stdout/stderr
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# php-fpm config
RUN printf '[www]\nuser = www-data\ngroup = www-data\nlisten = 127.0.0.1:9000\npm = dynamic\npm.max_children = 5\npm.start_servers = 2\npm.min_spare_servers = 1\npm.max_spare_servers = 3\n' \
    > /usr/local/etc/php-fpm.d/www.conf

# Main nginx.conf - run as root so it can bind port 80
RUN printf 'user root;\n\
worker_processes auto;\n\
error_log /dev/stderr warn;\n\
pid /tmp/nginx.pid;\n\
events { worker_connections 1024; }\n\
http {\n\
    include /etc/nginx/mime.types;\n\
    default_type application/octet-stream;\n\
    access_log /dev/stdout;\n\
    sendfile on;\n\
    include /etc/nginx/http.d/*.conf;\n\
}\n' > /etc/nginx/nginx.conf

RUN printf '#!/bin/sh\n\
set -e\n\
echo "==> Caching config..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
echo "==> Running migrations..."\n\
php artisan migrate --force\n\
echo "==> Starting php-fpm..."\n\
php-fpm -D\n\
sleep 2\n\
echo "==> Starting nginx..."\n\
exec nginx -g "daemon off;"\n' > /start.sh && chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]
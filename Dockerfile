FROM php:8.3-fpm AS php

RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql zip gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

FROM node:20 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php AS final
WORKDIR /var/www/html
COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN apt-get install -y nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000
CMD ["/start.sh"]
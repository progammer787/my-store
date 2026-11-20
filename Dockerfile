FROM node:22-alpine AS frontend
WORKDIR /var/www/html
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.3-fpm-alpine

RUN apk add --no-cache bash nginx supervisor icu-libs libpq libzip oniguruma \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev libpq-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install bcmath intl mbstring opcache pdo_pgsql \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
COPY --from=frontend /var/www/html/public/build ./public/build
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint

RUN chmod +x /usr/local/bin/app-entrypoint \
    && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 80
CMD ["/usr/local/bin/app-entrypoint"]

# syntax=docker/dockerfile:1.7
ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-fpm-alpine AS runtime

RUN apk add --no-cache \
        bash \
        curl \
        git \
        nginx \
        nodejs \
        npm \
        supervisor \
        tzdata \
        icu-data-full \
        icu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        freetype-dev \
        oniguruma-dev \
        libxml2-dev \
        libpq-dev \
        postgresql-dev \
        gettext \
        shadow

RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        zip \
        opcache \
        gd

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/nginx.conf.template /etc/nginx/templates/nginx.conf.template
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY . .

RUN chown -R www-data:www-data /var/www/html

RUN if [ -f composer.json ]; then \
        composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader; \
    fi

RUN if [ -f package.json ]; then \
        npm install --no-progress; \
        npm run build --if-present; \
        rm -rf node_modules; \
    fi

RUN if [ -f artisan ]; then \
        php artisan optimize || true; \
    fi

ENV APP_PORT=8080 \
    PORT=8080

EXPOSE 8080

ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

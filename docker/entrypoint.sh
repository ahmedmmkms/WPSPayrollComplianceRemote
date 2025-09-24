#!/bin/sh
set -e

APP_PORT=${PORT:-8080}
export APP_PORT

if [ -f /etc/nginx/templates/default.conf ]; then
    mkdir -p /etc/nginx/conf.d
    envsubst '${APP_PORT}' < /etc/nginx/templates/default.conf > /etc/nginx/conf.d/default.conf
fi

# Ensure storage directories are writable when the framework is present
if [ -d storage ] && [ -d bootstrap/cache ]; then
    chmod -R ug+rwX storage bootstrap/cache || true
fi

if [ -f artisan ]; then
    php artisan config:cache >/dev/null 2>&1 || true
    php artisan route:cache >/dev/null 2>&1 || true
    php artisan view:cache >/dev/null 2>&1 || true

    if [ "${RUN_MIGRATIONS:-0}" = "1" ]; then
        php artisan migrate --force || true
    fi
fi

exec "$@"

#!/usr/bin/env bash
set -e

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

mkdir -p \
    bootstrap/cache \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views

chmod -R ug+rw storage bootstrap/cache || true

if [ -f composer.json ] && [ ! -f vendor/autoload.php ]; then
    composer install --prefer-dist --no-interaction
fi

exec "$@"

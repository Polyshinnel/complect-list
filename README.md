# Complect KidsBerry

В проект добавлен локальный `docker-compose` для разработки с двумя основными сервисами:

- `php`: контейнер с PHP 8.2 и Composer для запуска Laravel-команд.
- `db`: отдельный MySQL 8.4 с постоянным volume для данных.

Конфигурация подобрана так, чтобы не конфликтовать с уже работающими на машине контейнерами:

- HTTP приложения по умолчанию проброшен на `8082`
- MySQL проекта по умолчанию проброшен на `3308`

## Первый запуск

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate
```

После запуска приложение будет доступно по адресу `http://localhost:8082`.

## Полезные команды

```bash
docker compose up -d
docker compose down
docker compose exec php composer install
docker compose exec php php artisan migrate
docker compose exec php php artisan test
docker compose exec db mysql -ucomplect -psecret complect
```

## Переменные окружения

Основные настройки вынесены в `.env`:

- `APP_PORT=8082` - порт приложения на хосте
- `FORWARD_DB_PORT=3308` - порт MySQL на хосте
- `DB_DATABASE=complect`
- `DB_USERNAME=complect`
- `DB_PASSWORD=secret`
- `DB_ROOT_PASSWORD=root`
- `WWWUSER=1000`
- `WWWGROUP=1000`

Если на машине уже заняты `8082` или `3308`, достаточно поменять значения в `.env` и заново выполнить `docker compose up -d`.

## Как устроен запуск

- Контейнер `php` стартует с `php artisan serve --host=0.0.0.0 --port=8000`.
- При первом запуске контейнер автоматически создаёт `.env` из `.env.example`, если файла ещё нет.
- Если папка `vendor` отсутствует, контейнер автоматически запускает `composer install`.
- Для Laravel внутри контейнера база доступна по хосту `db:3306`.
- Для подключения с хоста можно использовать `127.0.0.1:${FORWARD_DB_PORT}`.

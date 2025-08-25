# REST API для заявок на курсы

## Технологии
- PHP 8.2
- Laravel 10
- MySQL
- PHPUnit

## Установка

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Токен
Установите API_TOKEN в .env. По умолчанию: your-secure-api-token


## Запуск тестов
```bash
php artisan test
```

## API
см. файл api-docs.md (https://github.com/AlexKriak/CourseEnrollmentApi/blob/master/api-docs.md)

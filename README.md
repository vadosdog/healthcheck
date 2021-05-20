# Пакет проверки здоровья приложения
## Установка

Ставим пакет
```
composer require vadosdog/healthcheck
```

Копируем файлы конфигураций и миграции
```
 php artisan vendor:publish --tag=config
 php artisan vendor:publish --tag=migrations
 ```
 
 Если есть необходимость вносим изменения в файл `config/healthcheck.php`. Если внесли изменения, то обновляем кэши если надо
 ```
 php artisan config:cache
 php artisan route:cache
 ```
 
 Запускаем миграцию
 ```
 php artisan migrate
 ```
 
 Если все прошло гладко, то при обращении к путю `/api/health` (по умолчанию) мы должны увидеть
 ```
 {
    "result": {
        "database": true,
        "redis": true,
        "redispersist": true,
        "database_locks": true,
        "database_activity": true,
        "graylog": true
    },
    "success": true
}
 ```

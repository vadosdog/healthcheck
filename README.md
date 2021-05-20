# Пакет проверки здоровья приложения
## Установка

Ставим пакет
```
composer require vadosdog/healthcheck
```

Если необходимо настраиваем .env. Скорее всего захочется поменять как минимум HEALTHCHECK_API_TOKEN или полностью изменить авторизацию токена
```
HEALTHCHECK_ENDPOINT=/api/health
HEALTHCHECK_MIDDLEWARE=healthcheck.auth
HEALTHCHECK_API_TOKEN=health_api_token
HEALTHCHECK_MAX_EXECUTION_TIME=30
HEALTHCHECK_REDIS_CHECK_KEY=redis-health-status
HEALTHCHECK_DB_TABLE=healthcheck
```

Если были внесены изменения в конфиги, сбрасываем кэш 
```
php artisan config:cache
```

Копируем файлы конфигураций и миграции
```
php artisan vendor:publish --tag=migrations
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



Возможно если что-то пойдет не так, необходимо будет сбросить кэш роутов
```
php artisan route:cache
```

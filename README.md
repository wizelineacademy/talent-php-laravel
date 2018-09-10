# talent-php-laravel

### Run environment.
Copy the env file and make sure to set your `EB_TOKEN`
```bash
cp lumen/.env.example lumen/.env
```

Build and start Docker containers
```bash
docker-compose build && docker-compose up -d
```

Install the Composer dependencies
```bash
docker exec -it -w /app v3_php_1 composer install
````

### Import Events
Get inside the PHP container

```bash
docker exec -it -w /app v3_php_1 bash
php artisan import:events {LOCATION}
php artisan queue:work
```
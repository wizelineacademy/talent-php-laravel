# Talent PHP Laravel

## Setup
Make sure to set your environment variables
`cp lumen/.env.example lumen/.env`
Build and start Docker containers
`docker-compose build && docker-compose up -d`
Install the Composer dependencies
`docker exec -it -w /app talent-php-laravel_php_1 composer install`

## Import Events
Get inside the PHP container
`docker exec -it -w /app talent-php-laravel_php_1 bash`
`php artisan import:events {LOCATION}`
`php artisan queue:work`
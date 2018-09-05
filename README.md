# talent-php-laravel

### Run environment.

Create .env.
```
cd lumen
cp .env.example .env
```
Run containers.
```
/* Run this command to rebuild the php image with the mongo extension */
docker-compose build
docker-compose up
```

### Required Env Variables.

```
MONGODB_HOST=
```
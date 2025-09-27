# MSL_API

## About

## Laravel Sail / Docker

The project contains a Docker Compose setup build using Laravel Sail. An vs code devcontainer setup is also included. If you are setting up the project where no PHP/Composer is available you can use the following command to install Laravel sail to run the containers. 

```
sudo docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

## Project setup

Add an .env file for the application. You can use the example as a base

`cp .env.example .env`

Create the application key:

`php artisan key:generate`

Run database migrations:

`php artisan migrate`

Run database seeders:

`php artisan db:seed`

## Queue processor

## Env settings

## Tests

The projects tests are written to be used with [PHPUnit](https://phpunit.de/). The PHPUnit configuration can be found in the `phpunit.xml` file which resides in the root of the project. Within this file you can also find the env variables that are specific for testing purposes.

To run the tests use the following command:

`php artisan test`

Make sure that the config used by the application is not cached. If cached the env variables from the application itself will be used instead of the test specific settings. Causing the application database to be emptied.

To disable the config cache use the following command:

`php artisan config:clear`

Tests using the RefreshDatabase trait will make sure the test database is fully migrated and reset after each individual test.

The tests assume a separate database is available for testing purposes. Laravel Sail will set this up by default. The connection settings can be configured in the PHPUnit configuration file.

If you manually create a database for testing its name, user credentials etc. have to be specified in the PHPUnit configuration.

More information about testing in Laravel can be found [here](https://laravel.com/docs/12.x/testing).

## Frontend

### Developement

### Builds

## Composer



open wsl
cd /mnt/c/projects/msl_api

./vendor/bin/sail root-shell
npm run dev
npm run build

php artisan queue:work  --rest=1 --tries=3 --timeout=300


Originally assigned keywords -> msl_tags
Corresponding MSL vocabulary keywords -> msl_original_keywords
MSL enriched keywords -> msl_enriched_keywords
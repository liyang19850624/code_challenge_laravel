# Pre-requisite

- PHP
- Composer
- WSL2/Ubuntu(for Windows only)

# Set-up
## install laravel sail
- composer require laravel/sail --dev
- php artisan sail:install
- cp .env.example .env
- ./vendor/bin/sail up

## install packages
- go to docker container and run following:
- composer install --ignore-platform-reqs
- npm install

## database migration
- if database hasn't been updated yet you need to run: 
- php artisan migrate:refresh

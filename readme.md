## Dependencies installationg
`composer install`

## Initial database setup
`php artisan migrate:fresh`

## Setting up laravel passport
`php artisan passport:install`

## Set required env variables.
ACCESS_CLIENT_ID, ACCESS_CLIENT_SECRET, GRANT_CLIENT_ID, and GRANT_CLIENT_SECRET should be set according to the data laravel passport has inserted.

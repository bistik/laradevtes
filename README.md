
## Usage

If not yet installed, please install docker compatible to your OS.

1. Build containers and run `docker-compose up -d --build site`.
2. Install laravel and its dependencies `docker-compose run --rm composer install`
3. Create .env `cp src/.env.example src/.env`
4. Migrate database `docker-compose run --rm artisan migrate`
5. Seed user data `docker-compose run --rm artisan db:seed`



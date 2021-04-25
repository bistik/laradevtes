
## Usage

If not yet installed, please install docker compatible to your OS.

1. Build containers and run `docker-compose up -d --build site`.
2. Install laravel and its dependencies `docker-compose run --rm composer install`
3. Create .env `cp src/.env.example src/.env`
4. Migrate database `docker-compose run --rm artisan migrate`
5. Seed user data `docker-compose run --rm artisan db:seed`

If everything goes smoothly you should be able to access in `localhost:80`. Mailhog is at `localhost:8025`

## Testing

Postman collection is at `postman` directory together with an environment file and sample avatar.

Test `06 - Update Profile` will need to set filepath to your local avatar file.

Reset db as needed `docker-compose run --rm artisan migrate:refresh --seed`
## Installation steps
1. run `docker compose up -d --build`
2. copy `.env.example` file and rename into `.env`
3. run `docker exec backend composer install`
4. run `docker exec backend php artisan key:generate`
5. run `docker exec backend php artisan migrate`

## Steps to enable xdebug
1. copy `docker-compose.override.yml.example` and rename into `docker-compose.override.yml`
2. if you use Linux you need to change XDEBUG's client_host to a proper ip
3. `PHP_IDE_CONFIG: "serverName=app"` this is refers to phpStorm settings 


P.S. csrf token verification is temporarily disabled for the web middleware group

Possible improvements:
* Email validation is weak
* Name format TBD
* Hash the auth token (developer key) for more security
* Use separate db for tests
* Add PHP Code sniffer
* Add IDE helper barryvdh/laravel-ide-helper

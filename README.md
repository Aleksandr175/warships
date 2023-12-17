# Browser-based online game Warships

### Requirements
- php v8.2^
- get composer
- Docker Desktop

### First Installation
Install vendor and everything for laravel
> composer install

Install frontend packages
> npm install

Set actual config data for DB and app key in .env:
> DB_CONNECTION=mysql \
DB_HOST=mysql \
DB_PORT=3306 \
DB_DATABASE=warships \
DB_USERNAME=sail \
DB_PASSWORD=password

Add database and user to docker container "mysql"
> Open mysql container with terminal in Docker Desktop

> CREATE DATABASE warships;

> CREATE USER 'sail'@'%' IDENTIFIED BY 'password';

> GRANT ALL PRIVILEGES ON warships.* TO 'sail'@'%';

> FLUSH PRIVILEGES;

> SHOW GRANTS FOR 'sail'@'%';

### Commands
start laravel server on http://localhost (http://0.0.0.0:80)
> ./vendor/bin/sail up

start React
> npm run watch


#### WebSockets in docker container
start websockets on 127.0.0.1:6001 (for synchronize fleets and etc.) / Admin form: http://localhost/laravel-websockets

Enter container: for what???
> docker-compose exec -ti laravel.test bash

Run:

> php artisan websockets:serve

#### Database
update your database
> ./vendor/bin/sail artisan migrate:fresh --seed

#### Makefile:

- **make up**: run container
- **make down**: stop container
- **make php**: enter container php's console

### Start Job:
> ./vendor/bin/sail artisan queue:work

start job (default, fleet, resource)
> ./vendor/bin/sail artisan queue:work --queue=default

> ./vendor/bin/sail artisan queue:work --queue=fleet

> ./vendor/bin/sail artisan queue:work --queue=resource

> ./vendor/bin/sail artisan queue:work --queue=attack

> ./vendor/bin/sail artisan queue:work --queue=warshipQueue

> ./vendor/bin/sail artisan queue:work --queue=battle

All together
> ./vendor/bin/sail artisan queue:work --queue=default,fleet,resource,attack,warshipQueue,battle

### Start first jobs
> http://localhost/server-start

### Test

Run PHPUnit tests with command:
> ./vendor/bin/sail artisan test

### Docs

All docs are in docs folder

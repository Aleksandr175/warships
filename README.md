# Browser based online game Warships

### Requirements
- Docker Desktop

### Commands
start laravel server on http://localhost (http://0.0.0.0:80)
> ./vendor/bin/sail up

start React
> npm run watch


#### WebSockets
start websockets on 127.0.0.1:6001 (for synchronize fleets and etc.) / Admin form: http://localhost/laravel-websockets
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

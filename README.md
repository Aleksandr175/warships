# Browser based online game Warships

### Commands
start laravel server on 127.0.0.1:8000
> php artisan serve

start React
> npm run watch


#### WebSockets
start websockets on 127.0.0.1:6001 (for synchronize fleets and etc.) / Admin form: http://127.0.0.1:8000/laravel-websockets
> php artisan websockets:serve

start job (default, fleet, resource)
> php artisan queue:work --queue=default

> php artisan queue:work --queue=fleet

> php artisan queue:work --queue=resource

> php artisan queue:work --queue=attack

#### Database
update your database
> php artisan migrate:fresh --seed


#### Makefile:

- **make up**: run container
- **make down**: stop container
- **make php**: enter container php's console

Start Job:
> php artisan queue:work

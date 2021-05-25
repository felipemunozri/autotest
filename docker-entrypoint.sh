#!/bin/bash

php artisan migrate

service supervisor start
supervisorctl reread 
supervisorctl update
supervisorctl start laravel-worker:*
* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1
php artisan storage:link

exec "$@"
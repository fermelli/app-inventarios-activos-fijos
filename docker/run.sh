#!/bin/sh

cd /var/www

php artisan cache:clear
php artisan route:cache
# php artisan storage:link

/usr/bin/supervisord -c /etc/supervisord.conf
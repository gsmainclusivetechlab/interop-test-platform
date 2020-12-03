#!/bin/sh
set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$role" = "app" ]; then
	exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf;

elif [ "$role" = "queue" ]; then
    php /var/www/html/artisan queue:work --verbose --tries=3 --timeout=90

elif [ "$role" = "scheduler" ]; then
	while [ true ]
    do
    	php /var/www/html/artisan schedule:run --verbose --no-interaction &
    	sleep 300
    done


else
    echo "Could not match the container role \"$role\""
    exit 1
fi

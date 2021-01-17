#!/bin/sh
set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ ! -f /etc/nginx/ssl/client-certs/CA.crt ]; then
    cp /etc/nginx/ssl/default-certs/RootCA.crt /etc/nginx/ssl/client-certs/CA.crt
fi
#if [ ! -f /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/fullchain.pem ]; then
#    mkdir -p /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/ || true
#    cp /etc/nginx/ssl/default-certs/localhost.crt /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/fullchain.pem
#fi
#if [ ! -f /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/privkey.pem ]; then
#    mkdir -p /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/ || true
#    cp  /etc/nginx/ssl/default-certs/localhost.key /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/privkey.pem
#fi

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

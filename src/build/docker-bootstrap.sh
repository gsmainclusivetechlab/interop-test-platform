#!/bin/sh
set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$role" = "app" ]; then
    mkdir -p /etc/nginx/ssl/default-certs || true
    cd /etc/nginx/ssl/default-certs

    if [ ! -f RootCA.crt ]; then
        openssl req -x509 -nodes -new -sha256 -days 1024 -newkey rsa:2048 -keyout RootCA.key -out RootCA.crt -subj "/C=UK/CN=localhost-CA"
        openssl x509 -outform pem -in RootCA.crt -out RootCA.crt
        openssl req -new -nodes -newkey rsa:2048 -keyout localhost.key -out localhost.csr -subj "/C=UK/ST=State/L=City/O=Default-Localhost-Certificates/CN=${PROJECT_DOMAIN}"
        openssl x509 -req -sha256 -days 365 -in localhost.csr -CA RootCA.crt -CAkey RootCA.key -CAcreateserial -out localhost.crt
        if [ ! -d /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN} ]; then
            mkdir -p /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/
            cp localhost.crt /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/fullchain.pem
            cp localhost.key /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/privkey.pem
            chmod +xr /etc/nginx/ssl/default-certs/RootCA.crt
            chmod +xr /etc/nginx/ssl/default-certs/RootCA.key
        fi
    else
        FILE_CERT_EXP_DATE=$(cat RootCA.crt  | openssl x509 -noout -dates | grep notAfter | sed -e 's?.*=??g;s?GMT.*??;s? $??g')
        CURRENT_DATE=$(date +%s)
        if [ "$CURRENT_DATE" -gt "$FILE_CERT_EXP_DATE" ]; then
            openssl req -x509 -nodes -new -sha256 -days 1024 -newkey rsa:2048 -keyout RootCA.key -out RootCA.crt -subj "/C=UK/CN=localhost-CA"
            openssl x509 -outform pem -in RootCA.crt -out RootCA.crt &&\
            openssl req -new -nodes -newkey rsa:2048 -keyout localhost.key -out localhost.csr -subj "/C=UK/ST=State/L=City/O=Default-Localhost-Certificates/CN=${PROJECT_DOMAIN}"
            openssl x509 -req -sha256 -days 365 -in localhost.csr -CA RootCA.crt -CAkey RootCA.key -CAcreateserial -out localhost.crt
            if [ ! -d /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN} ]; then
                mkdir -p /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/
                cp localhost.crt /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/fullchain.pem
                cp localhost.key /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/privkey.pem
                chmod +xr /etc/nginx/ssl/default-certs/RootCA.crt
                chmod +xr /etc/nginx/ssl/default-certs/RootCA.key
            fi
        fi
    fi

    if [ ! -f ClientCA.pem ]; then
        cp RootCA.crt ClientCA.pem
    fi

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

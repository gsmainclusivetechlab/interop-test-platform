# Build frontend assets
FROM node:14.5.0-stretch AS frontend
WORKDIR /usr/src/app
COPY package.json ./package.json
COPY package-lock.json ./package-lock.json
RUN npm ci
COPY webpack.mix.js webpack.mix.js
COPY resources resources
RUN npm run prod


# Download PHP dependencies
FROM composer:2.0.7 AS composer
COPY composer* ./
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --no-autoloader
COPY . .
RUN composer dump-autoload --optimize && composer update


# Set up final app container image
FROM php:7-fpm-alpine3.12
ENV WAIT_VERSION 2.7.2
ENV PHPREDIS_VERSION 5.2.2
ARG PHP_INI_TEMPLATE
ARG APP_ENV=prod

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/$WAIT_VERSION/wait /wait

COPY build/docker-bootstrap.sh /usr/local/bin/start

# Download phpredis sources to a dir that docker-php-ext-install will look in and make it aware it's there.
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 && \
    echo 'redis' >>/usr/src/php-available-exts && \
    apk add --no-cache supervisor freetype-dev libjpeg-turbo-dev libzip-dev libpng-dev postgresql-dev nginx libxml2-dev libxslt-dev && \
    rm /etc/nginx/conf.d/default.conf && \
    docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli opcache redis pcntl gd bcmath posix zip xmlrpc sockets soap xsl

# Install composer
RUN if [ "${APP_ENV}" = "development" ]; then \
        php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer; \
    fi;

# Use custom user to ensure correct permissions
ARG HOST_UID=www-data:www-data
RUN chmod +x /wait /usr/local/bin/start && \
    chown -R ${HOST_UID} /var/www /var/lib/nginx /var/log/ /var/log/ /etc/nginx  /run

USER ${HOST_UID}

WORKDIR /var/www/html
RUN mkdir /var/www/logs

# Copy in code and configuration files
COPY --chown=${HOST_UID} build/nginx-server.conf /etc/nginx/nginx.conf
COPY --chown=${HOST_UID} build/php-fpm.conf /etc/php7/php-fpm.d/www.conf
COPY --chown=${HOST_UID} build/php-ini-${PHP_INI_TEMPLATE}.ini $PHP_INI_DIR/php.ini
COPY --chown=${HOST_UID} build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY --from=composer --chown=${HOST_UID} /app /var/www/html
COPY --from=frontend --chown=${HOST_UID} /usr/src/app/public/assets /var/www/html/public/assets

# Configure nginx to use the custom user
RUN echo -e "user ${HOST_UID};\n\n$(cat /etc/nginx/nginx.conf)" > /etc/nginx/nginx.conf

# Setup IDE helpers
RUN if [ "${APP_ENV}" = "development" ]; then \
        composer update; \
    fi;

# Setup laravel environment
RUN chmod +x artisan && \
    php artisan dusk:chrome-driver 81 && \
    chmod -R 777 storage bootstrap/cache

# Apply certbot SSL config if HTTPS_SSL_CERT=certbot
ARG HTTPS_SSL_CERT=selfsigned
ARG PROJECT_DOMAIN=itp-test-control.test

# Generate self signet certificates cnd create dummy certs for certbot config
RUN mkdir -p /etc/nginx/ssl/default-certs && \
    cd /etc/nginx/ssl/default-certs && \
    openssl req -x509 -nodes -new -sha256 -days 1024 -newkey rsa:2048 -keyout RootCA.key -out RootCA.crt -subj "/C=UK/CN=localhost-CA" &&\
    openssl x509 -outform pem -in RootCA.crt -out RootCA.crt &&\
    openssl req -new -nodes -newkey rsa:2048 -keyout localhost.key -out localhost.csr -subj "/C=UK/ST=State/L=City/O=Default-Localhost-Certificates/CN=${PROJECT_DOMAIN}" &&\
    openssl x509 -req -sha256 -days 365 -in localhost.csr -CA RootCA.crt -CAkey RootCA.key -CAcreateserial -out localhost.crt &&\
    mkdir -p /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/ &&\
    cp localhost.crt /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/fullchain.pem &&\
    cp localhost.key /etc/nginx/ssl/letsencrypt/live/${PROJECT_DOMAIN}/privkey.pem &&\
    mkdir -p /etc/nginx/ssl/client-certs &&\
    cp RootCA.crt /etc/nginx/ssl/client-certs/CA.crt && \
    chmod +xr /etc/nginx/ssl/default-certs/RootCA.crt && \
    chmod +xr /etc/nginx/ssl/default-certs/RootCA.key

COPY build/client-CA-nginx-renew.sh /etc/periodic/hourly/client-CA-nginx-renew.sh
COPY build/certbot-cert-nginx-reload.sh /etc/periodic/hourly/certbot-cert-nginx-reload.sh
COPY build/nginx-includes /etc/nginx/includes

USER root

RUN chmod +x /etc/periodic/hourly/client-CA-nginx-renew.sh && \
    chmod +x /etc/periodic/hourly/certbot-cert-nginx-reload.sh

RUN if [ "${HTTPS_SSL_CERT}" = 'certbot' ]; then \
        sed 's?include /etc/nginx/includes/selfsigned-https.conf;?include /etc/nginx/includes/certbot-https.conf;?' -i /etc/nginx/nginx.conf; \
        sed 's?example\.com?'${PROJECT_DOMAIN}'?' -i /etc/nginx/includes/certbot-https.conf; \
        sed 's?.*server_name.*?server_name '${PROJECT_DOMAIN}';?' -i /etc/nginx/nginx.conf; \
    fi;

# Remove recidect drom http to https in dev mode
RUN if [ "${APP_ENV}" = "development" ]; then \
        sed 's?include /etc/nginx/includes/http-to-http-redirect.conf;??g' -i /etc/nginx/nginx.conf; \
    fi;


# Expose the port nginx is reachable on
EXPOSE 8080

CMD ["/usr/local/bin/start"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/health

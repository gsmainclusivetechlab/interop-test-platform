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
FROM php:fpm-alpine3.12
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

# Expose the port nginx is reachable on
EXPOSE 8080

CMD ["/usr/local/bin/start"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/health

# Set up final app container image
FROM php:7-fpm-alpine3.12 AS execution_environment
ENV WAIT_VERSION 2.7.2
ENV PHPREDIS_VERSION 5.2.2
WORKDIR /var/www/html

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/$WAIT_VERSION/wait /wait

# Download phpredis sources to a dir that docker-php-ext-install will look in and make it aware it's there.
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 && \
    echo 'redis' >>/usr/src/php-available-exts && \
    apk add --no-cache supervisor freetype-dev libjpeg-turbo-dev libzip-dev libpng-dev postgresql-dev nginx libxml2-dev libxslt-dev && \
    rm /etc/nginx/conf.d/default.conf && \
    docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli opcache redis pcntl gd bcmath posix zip xmlrpc sockets soap xsl

# Use custom user to ensure correct permissions
RUN addgroup --gid 1024 interopdevs
RUN adduser --disabled-password --gecos "" --ingroup interopdevs interopdev

RUN mkdir /var/www/logs

# Use custom user to ensure correct permissions
RUN chmod +x /wait && \
    mkdir -p /etc/nginx/ssl/default-certs && \
    mkdir -p /etc/nginx/ssl/letsencrypt && \
    chown -R interopdev /var/www /var/lib/nginx /var/log/ /var/log/ /etc/nginx /run && \
    chmod -R 777 /var/lib/nginx

# Expose the port nginx is reachable on
EXPOSE 8080

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/health

CMD ["/usr/local/bin/start"]

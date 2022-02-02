FROM daxium/v8js:9.2.159 as v8build
RUN tar cvzf /tmp/libv8.tar.gz -C /usr/local/v8 .

# Set up final app container image
FROM php:7-fpm-alpine3.12 AS execution_environment
ENV WAIT_VERSION 2.7.2
ENV PHPREDIS_VERSION 5.2.2
WORKDIR /var/www/html

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/$WAIT_VERSION/wait /wait

COPY --from=v8build /tmp/libv8.tar.gz /tmp/libv8.tar.gz

# Download phpredis sources to a dir that docker-php-ext-install will look in and make it aware it's there.
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 && \
    echo 'redis' >>/usr/src/php-available-exts && \
    apk add --no-cache $PHPIZE_DEPS supervisor freetype-dev libjpeg-turbo-dev libzip-dev libpng-dev postgresql-dev nginx libxml2-dev libxslt-dev zlib-dev g++ make git && \
    rm /etc/nginx/conf.d/default.conf && \
    docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli opcache redis pcntl gd bcmath posix zip xmlrpc sockets soap xsl
RUN export V8_DIR=/usr/local/v8 \
	&& mkdir -p $V8_DIR \
	&& tar xzvf /tmp/libv8.tar.gz -C $V8_DIR \
    && git clone -b php7 --depth 1 https://github.com/phpv8/v8js.git /tmp/v8js \
    && cd /tmp/v8js \
	&& phpize \
	&& ./configure --with-v8js=/usr/local/v8 LDFLAGS="-lstdc++" \
	# on Embedder-vs-V8 build configuration mismatch. On embedder side pointer compression is ENABLED while on V8 side it's DISABLED. add flag
	#&& ./configure --with-v8js=/usr/local/v8 LDFLAGS="-lstdc++" CPPFLAGS="-DV8_COMPRESS_POINTERS" \
	&& make \
	&& make install \
	&& docker-php-ext-enable v8js \
	&& rm -rf /tmp/libv8.tar.gz

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

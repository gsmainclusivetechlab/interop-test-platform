# V8JS
FROM daxium/v8js:9.2.159 as v8build
RUN tar cvzf /tmp/libv8.tar.gz -C /usr/local/v8 .

# Download PHP dependencies
FROM composer:2.0.8 AS composer
COPY --from=v8build /tmp/libv8.tar.gz /tmp/libv8.tar.gz
RUN apk add --no-cache make g++ autoconf
RUN export V8_DIR=/usr/local/v8 \
    && mkdir -p $V8_DIR \
    && tar xzvf /tmp/libv8.tar.gz -C $V8_DIR \
    && git clone -b php7 --depth 1 https://github.com/phpv8/v8js.git /tmp/v8js \
    && cd /tmp/v8js \
    && phpize \
    && ./configure --with-v8js=/usr/local/v8 LDFLAGS="-lstdc++" \
    && make \
    && make install \
    && docker-php-ext-enable v8js \
    && rm -rf /tmp/libv8.tar.gz
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


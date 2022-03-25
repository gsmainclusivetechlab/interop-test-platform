# Download PHP dependencies
FROM composer:2.0.8 AS composer
COPY composer* ./
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --no-autoloader
COPY . .
RUN composer dump-autoload --optimize
RUN composer update


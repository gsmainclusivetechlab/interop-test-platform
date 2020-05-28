FROM gsmainclusivetechlab/interop-php-fpm:latest

COPY ./build/php-ini-${PHP_INI_TEMPLATE}.ini /usr/local/etc/php/conf.d/zz-force-conf.ini
COPY ./build/php-fpm.conf /usr/local/etc/php-fpm.d/zz-www.conf
COPY ./build/crontab /usr/local/etc/crontab
COPY ./build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./build/docker-bootstrap.sh /usr/local/bin/docker-bootstrap.sh

# Add docker-compose-wait tool -------------------
ENV WAIT_VERSION 2.7.2
ADD https://github.com/ufoscout/docker-compose-wait/releases/download/$WAIT_VERSION/wait /wait
RUN chmod +x /wait

COPY . /var/www/html

RUN composer install
RUN php artisan key:generate
RUN php artisan dusk:chrome-driver 81

RUN chmod -R 777 storage bootstrap/cache
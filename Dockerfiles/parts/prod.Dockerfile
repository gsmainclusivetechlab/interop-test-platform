# Production
FROM execution_environment

# Copy in code and configuration files
COPY --chown=interopdev:interopdevs build/nginx-server.conf /etc/nginx/nginx.conf
COPY --chown=interopdev:interopdevs build/php-fpm.conf /etc/php7/php-fpm.d/www.conf
COPY --chown=interopdev:interopdevs build/php-ini-production.ini $PHP_INI_DIR/php.ini
COPY --chown=interopdev:interopdevs build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY --chown=interopdev:interopdevs build/client-CA-nginx-renew.sh /etc/periodic/hourly/client-CA-nginx-renew.sh
COPY --chown=interopdev:interopdevs build/certbot-cert-nginx-reload.sh /etc/periodic/hourly/certbot-cert-nginx-reload.sh
COPY --chown=interopdev:interopdevs build/docker-bootstrap.sh /usr/local/bin/start

COPY --from=composer --chown=interopdev:interopdevs /app /var/www/html
COPY --from=frontend --chown=interopdev:interopdevs /usr/src/app/public/assets /var/www/html/public/assets

RUN mkdir -p storage bootstrap/cache && chmod -R 777 storage bootstrap/cache
RUN chmod +x artisan \
    /etc/periodic/hourly/client-CA-nginx-renew.sh \
    /etc/periodic/hourly/certbot-cert-nginx-reload.sh \
    /usr/local/bin/start


USER interopdev


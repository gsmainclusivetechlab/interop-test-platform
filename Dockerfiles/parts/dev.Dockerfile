# Development
FROM execution_environment 
# Install composer and npm
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer;
RUN apk add --update npm git
# N.B. we don't install dependencies in the image, instead they will be loaded via volume from the host

# RUN php artisan dusk:chrome-driver 81


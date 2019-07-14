FROM php:7.3.7-apache

EXPOSE 8080

WORKDIR /srv

COPY 4.php 5.php index.php config.php /var/www/html/

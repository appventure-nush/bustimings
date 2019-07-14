FROM php:7.3.7-apache

EXPOSE 80 8080

ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf
ADD ports.conf /etc/apache2/ports.conf

WORKDIR /srv

COPY 4.php 5.php index.php config.php /var/www/html/

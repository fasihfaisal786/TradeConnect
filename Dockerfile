FROM php:8.2-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY tradeconnect/public/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD ["bash", "-lc", "set -eux; a2dismod mpm_event mpm_worker || true; rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true; a2enmod mpm_prefork; exec apache2-foreground"]

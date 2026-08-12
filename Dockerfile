FROM shinsenter/php:8.2-fpm-apache
COPY tradeconnect/public/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80

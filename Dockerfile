FROM php:7.4-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN a2enmod rewrite headers

RUN echo "<Directory /var/www/html>\n\tAllowOverride All\n\tRequire all granted\n\tHeader add Access-Control-Allow-Origin \"*\"\n\tHeader add Access-Control-Allow-Methods \"*\"\n</Directory>" > /etc/apache2/conf-available/custom.conf && \
    echo "AddType application/x-httpd-php .phtml" >> /etc/apache2/conf-available/custom.conf && \
    a2enconf custom

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html
RUN chmod 777 /var/www/html/labs/uploads
RUN chmod 777 /var/www/html/capstone/assets
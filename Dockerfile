FROM php:7.1.8-apache

RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql

RUN a2enmod rewrite

RUN apt-get update
RUN apt-get install -y curl git zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer require youshido/graphql

COPY . /var/www/html

RUN chmod -R -f 751 /var/www/html
RUN chown -R -f www-data /var/www/html
RUN chgrp -R -f www-data /var/www/html

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

COPY apache-config.conf /etc/apache2/sites-enabled/000-default.conf

RUN rm -rf /var/www/html/Application/Temp/

#CMD /usr/sbin/apache2ctl -D FOREGROUND
CMD ./run.sh

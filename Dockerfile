FROM php:8.3-apache
 
RUN a2enmod rewrite
 
RUN apt-get update \
  && apt-get install -y libzip-dev git wget zlib1g-dev libpng-dev libxml2-dev libsodium-dev --no-install-recommends \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
 
RUN docker-php-ext-install pdo mysqli sodium pdo_mysql zip gd xml;
 
RUN wget https://getcomposer.org/download/2.0.9/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer
 
COPY ./apache.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./entrypoint.sh /entrypoint.sh

COPY . /var/www
COPY /compta/prod.decrypt.private.php /var/www/config/secrets/prod/prod.decrypt.private.php
 
WORKDIR /var/www
RUN chmod -R 777 /var/www
 
RUN chmod +x /entrypoint.sh

CMD ["apache2-foreground"]

ENTRYPOINT ["/entrypoint.sh"]

FROM php:8.3-apache

ARG UID

# install all the dependencies and enable PHP modules
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
      certbot \
      python3-certbot-apache \
      procps \
      nano \
      git \
      unzip \
      libicu-dev \
      zlib1g-dev \
      libxml2 \
      libxml2-dev \
      libreadline-dev \
      supervisor \
      cron \
      sudo \
      libzip-dev \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
      pdo_mysql \
      sockets \
      intl \
      opcache \
      zip \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.6.6

RUN mkdir -p /var/www/html/bootstrap/cache && chmod -R 777 /var/www/html/bootstrap/cache \
  && mkdir -p /var/www/html/storage/framework/cache && chmod -R 777 /var/www/html/storage/framework/cache \
  && mkdir -p /var/www/html/storage/framework/sessions && chmod -R 777 /var/www/html/storage/framework/sessions \
  && mkdir -p /var/www/html/storage/framework/testing && chmod -R 777 /var/www/html/storage/framework/testing \
  && mkdir -p /var/www/html/storage/framework/views && chmod -R 777 /var/www/html/storage/framework/views \
  && mkdir -p /var/www/html/storage/domains && chmod -R 777 /var/www/html/storage/domains \
  && mkdir -p /var/www/html/storage/logs && chmod -R 777 /var/www/html/storage/logs \
  && mkdir -p /var/www/html/storage/debugbar && chmod -R 777 /var/www/html/storage/debugbar

# disable default site and delete all default files inside APP_HOME
RUN a2dissite 000-default.conf

# put apache and php config for Laravel, enable sites
COPY ./application.conf /etc/apache2/sites-available/application.conf
RUN a2ensite application.conf

COPY ./php.ini /usr/local/etc/php/php.ini

# enable apache modules
RUN a2enmod rewrite
RUN a2enmod ssl

# WORKDIR /var/www/html

# Change user and group id to 1000 that is equal with geenral linux default user id.
# It requires to have synced directories and files permissions.
# RUN deluser www-data
RUN adduser -D -H -u $UID -s /bin/bash www-data

# Use www-data user for entrypoint login
# to be the same user as http-server process run user.
USER www-data

FROM php:7.4-apache

# Install packages
RUN apt-get update && apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    libpq-dev \
    g++ \
    vim \
    supervisor

# NodeJS up to date
RUN curl -fsSL https://deb.nodesource.com/setup_14.x | sudo -E bash -
RUN apt-get install -y \
    nodejs

# Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite headers

# Postgres php
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

# Common PHP Extensions
RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    pdo \
    pdo_pgsql \
    gd

# Ensure PHP logs are captured by the container
ENV LOG_CHANNEL=stderr

# Set a volume mount point for your code
#VOLUME /var/www/html

# Set working directory
WORKDIR /var/www/html

# Copy code and run composer, npm
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --chown=www-data:www-data . /var/www/html
COPY .env.example_dev /var/www/html/.env
RUN composer install
RUN composer dump-autoload
RUN apt-get update
RUN npm install
RUN npm run dev

COPY laravel-worker.conf /etc/supervisor/conf.d/

#RUN cd /var/www/tmp && npm install && npm run production

# Ensure the entrypoint file can be run
#RUN chmod +x /var/www/html/docker-entrypoint.sh
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]

# The default apache run command
CMD ["apache2-foreground"]
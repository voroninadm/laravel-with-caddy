FROM php:8.3.11-fpm

ARG NODE_VERSION=22

# Update package list and install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    default-mysql-client \
    libonig-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


# Удаляем старые nodejs/npm, устанавливаем curl и добавляем NodeSource 22.x
RUN apt-get update && apt-get purge -y nodejs npm \
    && apt-get install -y curl gnupg \
    && curl -fsSL https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install required PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd bcmath zip pcntl\
    && pecl install redis \
    && docker-php-ext-enable redis


# Установим dockerize
RUN apt-get update && apt-get install -y wget \
  && wget https://github.com/jwilder/dockerize/releases/download/v0.6.1/dockerize-linux-amd64-v0.6.1.tar.gz \
  && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-v0.6.1.tar.gz \
  && rm dockerize-linux-amd64-v0.6.1.tar.gz

WORKDIR /srv

# Copy the codebase
COPY . ./

# Run composer install for production and give permissions
RUN sed 's_@php artisan package:discover_/bin/true_;' -i composer.json \
    && composer install --ignore-platform-req=php --no-dev --optimize-autoloader \
    && composer clear-cache \
    && php artisan package:discover --ansi \
    && chmod -R 775 storage \
    && chown -R www-data:www-data storage \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache

# Copy entrypoint
COPY ./scripts/php-fpm-entrypoint /usr/local/bin/php-entrypoint

# Give permissions to everything in bin/
RUN chmod a+x /usr/local/bin/*

ENTRYPOINT ["/usr/local/bin/php-entrypoint"]

CMD ["php-fpm"]

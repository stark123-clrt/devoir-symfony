FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    zip \
 && docker-php-ext-install pdo pdo_mysql intl mbstring zip opcache \
 && pecl install apcu && docker-php-ext-enable apcu \
 && rm -rf /var/lib/apt/lists/*

# Install Composer (copy from official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Install PHP dependencies if composer.json present (non-fatal)
RUN if [ -f composer.json ]; then composer install --no-interaction --no-ansi --no-progress || true; fi

RUN chown -R www-data:www-data /var/www/html || true

EXPOSE 9000

CMD ["php-fpm"]

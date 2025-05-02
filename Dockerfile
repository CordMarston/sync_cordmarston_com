# Use PHP 8.2
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    curl \
    ca-certificates \
    gnupg2 \
    lsb-release \
    supervisor \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql

# Install Node.js (v18 LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/app

# Copy app source
COPY . /var/www/app

# Install PHP dependencies
RUN composer install --prefer-dist --no-interaction --no-scripts --no-progress

# Install npm dependencies
RUN npm install

# Install and configure Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set default command to run supervisor
CMD ["/usr/bin/supervisord"]
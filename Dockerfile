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

# Copy composer files first to leverage Docker layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies before copying the rest of the source
RUN composer install --prefer-dist --no-interaction --no-scripts --no-progress

# Now copy the rest of the app
COPY . .

# Re-run npm install after copying full app
RUN npm install && npm run build

# Copy Supervisor config
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set permissions (optional but helpful)
RUN chown -R www-data:www-data /var/www/app \
    && chmod -R 755 /var/www/app

# Default command
CMD ["/usr/bin/supervisord"]

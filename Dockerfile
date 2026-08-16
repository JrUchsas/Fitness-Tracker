FROM php:8.2-cli

# Install system dependencies, libzip & SQLite/MySQL extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js for frontend asset compilation (Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

WORKDIR /var/www/html

# Copy application files
COPY . .

# Set environment variables for build
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP & JS dependencies and build production assets
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-progress
RUN npm install && npm run build

EXPOSE 8000

CMD ["sh", "-c", "mkdir -p /var/data && (test -f /var/data/database.sqlite || (test -f database/database.sqlite && cp database/database.sqlite /var/data/database.sqlite) || touch /var/data/database.sqlite) && php artisan migrate --force && php -S 0.0.0.0:${PORT:-8000} -t public/"]

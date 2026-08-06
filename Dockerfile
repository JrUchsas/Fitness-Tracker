FROM php:8.2-cli

# Install system dependencies & SQLite/MySQL extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js for frontend asset compilation (Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP & JS dependencies and build production assets
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

EXPOSE 8000

CMD ["sh", "-c", "mkdir -p /var/data && touch /var/data/database.sqlite && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"]

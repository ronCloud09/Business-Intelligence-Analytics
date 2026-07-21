FROM php:8.4-cli


RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

RUN npm install && npm run build

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs 

# No cron/supervisor is installed in this image, so the Laravel scheduler
# (sync:* commands, cache:warm-dashboard, etc. defined in routes/console.php)
# never runs unless something calls `schedule:run` on a loop. The background
# subshell below does that once a minute, same as a standard cron entry
# would, while the foreground process keeps serving requests.
CMD ["sh", "-c", "php artisan migrate --force && (while true; do php artisan schedule:run >> /dev/null 2>&1; sleep 60; done &) && php artisan serve --host=0.0.0.0 --port=$PORT"]
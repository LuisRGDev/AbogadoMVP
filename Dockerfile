FROM ghcr.io/dunglas/frankenphp:latest-php8.3

COPY . /app
WORKDIR /app

RUN apt-get update && apt-get install -y unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader --no-interaction && \
    npm install && npm run build && \
    php artisan config:cache && php artisan route:cache && php artisan view:cache && \
    chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 80

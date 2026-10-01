FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    public/uploads

RUN cp .env.example .env \
    && composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts \
    && php artisan key:generate --force \
    && chown -R www-data:www-data storage bootstrap/cache public/uploads \
    && chmod -R 775 storage bootstrap/cache public/uploads

RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf

RUN cat > /etc/apache2/sites-available/000-default.conf <<'EOF'
<VirtualHost *:10000>
    ServerName localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
        Options FollowSymLinks
        DirectoryIndex index.php
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

RUN cat > /usr/local/bin/start-laravel.sh <<'EOF'
#!/bin/sh
set -e

php artisan optimize:clear
php artisan package:discover --ansi
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache

exec apache2-foreground
EOF

RUN chmod +x /usr/local/bin/start-laravel.sh

EXPOSE 10000
CMD ["/usr/local/bin/start-laravel.sh"]

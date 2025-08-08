FROM unit:1.34.1-php8.3

# Install dependencies
RUN apt update && apt install -y \
    curl unzip git libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libssl-dev tzdata \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pcntl opcache pdo pdo_mysql intl zip gd exif ftp bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis

# Set timezone to Mexico City
ENV TZ=America/Mexico_City
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata

# Configure PHP
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit_buffer_size=256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "date.timezone=America/Mexico_City" >> /usr/local/etc/php/conf.d/custom.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R unit:unit /var/www/html

# Copy package files first for better caching
COPY package*.json ./
RUN npm install

# Copy application files (including artisan)
COPY . .

# Install composer dependencies (after copying artisan)
# Skip post-install scripts to avoid errors during build
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

# Run post-install scripts separately with error handling
RUN php artisan package:discover --ansi || true \
    && php artisan config:clear || true \
    && php artisan cache:clear || true

# Build assets
RUN npm run build

# Set final permissions
RUN chown -R unit:unit /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Copy unit configuration
COPY unit.json /docker-entrypoint.d/unit.json

EXPOSE 8000

CMD ["unitd", "--no-daemon"]
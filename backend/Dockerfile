# This Dockerfile defines a multi-stage build for a PHP application with a Node.js frontend.
#  * It consists of three stages: 
#  * 1. The first stage builds the PHP application and installs dependencies using Composer.
#  * 2. The second stage builds the Node.js frontend assets.
#  * 3. The third stage sets up the production environment with PHP-FPM and includes a health check script.
#  *
#  * Each stage is optimized to reduce the final image size by only including necessary files and dependencies.


# 1. Build stage
FROM php:8.5.5-cli AS php-base-builder
# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    pcntl \
    pgsql \
    intl \
    zip \
    bcmath \
    soap \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www
COPY composer.json composer.lock ./
# Install composer
COPY --from=composer:2.9.7 /usr/bin/composer /usr/bin/composer

# 2. Production build stage for PHP
FROM php-base-builder AS php-prod-builder
# Install PHP dependencies
RUN --mount=type=cache,target=/tmp/composer-cache \
    composer install \
        --no-dev \
        --optimize-autoloader \
        --prefer-dist \
        --no-scripts
# Copy the application source code
COPY . .
RUN php artisan package:discover --ansi

# 3. Development build stage for PHP
FROM php-base-builder AS php-dev-builder
# Install PHP dependencies with dev packages
RUN --mount=type=cache,target=/tmp/composer-cache \
    composer install \
        --optimize-autoloader \
        --prefer-dist \
        --no-scripts
COPY . .
RUN php artisan package:discover --ansi

# 2. Node Build stage
FROM node:24.15.0-alpine3.23 AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --no-audit --no-fund
COPY . .
RUN npm run build


# 3. Production stage
FROM php:8.5.5-fpm AS production
# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libfcgi-bin \
    procps \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Download and install php-fpm health check script
RUN curl -L -o /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck
# Copy Entry point script
COPY ./docker/production/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy Intial Storage structure
COPY ./storage /var/www/storage-init

# Copy PHP Extensions and libraries from the builder stage
COPY --from=php-prod-builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=php-prod-builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=php-prod-builder /usr/local/bin/docker-php-ext-* /usr/local/bin/

# Use the recommended production PHP configuration
# -----------------------------------------------------------
# PHP provides development and production configurations.
# Here, we replace the default php.ini with the production
# version to apply settings optimized for performance and
# security in a live environment.
# -----------------------------------------------------------
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Keep the image-provided FPM global config intact and add pool overrides for our application
COPY ./docker/common/php-fpm/conf.d/*.conf /usr/local/etc/php-fpm.d/

# Copy the built application from the builder stage
COPY --from=php-prod-builder /var/www /var/www
# Copy the built frontend assets from the node builder stage
COPY --from=node-builder /app/public/build /var/www/public/build

# Set the working directory
WORKDIR /var/www

# Ensure Correct Permissions 
RUN chown -R www-data:www-data /var/www

# Switch to the www-data user for better security
USER www-data

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]

# Expose the port that PHP-FPM listens on
EXPOSE 9000

CMD ["php-fpm"]

# 4. Backend Nginx Stage
FROM dhi.io/nginx:1.30.0-debian13 AS backend-web
COPY ./docker/production/nginx/backend.conf /etc/nginx/nginx.conf
COPY --from=php-prod-builder /var/www/public /var/www/public
COPY --from=node-builder /app/public/build /var/www/public/build
CMD [ "-g", "daemon off;" ]

# 5 Development stage
FROM php-dev-builder AS development
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     curl \
#     libzip-dev \
#     libicu-dev \
#     procps \
#     && docker-php-ext-install \
#     pdo_mysql \
#     intl \
#     zip \
#     bcmath \
#     && pecl install redis \
#     && docker-php-ext-enable redis
# WORKDIR /var/www
# COPY --from=compose:2.9.7 /usr/bin/composer /usr/bin/composer
COPY ./docker/development/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
WORKDIR /var/www
EXPOSE 8000
ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]
CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=8000" ]



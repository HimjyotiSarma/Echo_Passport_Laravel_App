FROM php:8.5.5-cli

ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    bash \
    default-mysql-client \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    intl \
    zip \
    bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer (pinned)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Node (LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs \
    && node -v && npm -v

# User mapping
RUN groupadd -g ${GID} www || true \
 && useradd -m -u ${UID} -g ${GID} -s /bin/bash www

USER www
WORKDIR /var/www

ENTRYPOINT []
CMD ["bash"]
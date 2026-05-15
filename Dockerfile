# ============================================
# Stage 1 — Composer dependencies
# ============================================
FROM composer:2.7 AS composer-deps

WORKDIR /app

# Copy only dependency files first (better caching)
COPY composer.json composer.lock ./

# Install PHP deps without scripts (faster)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs \
    --prefer-dist

# Copy app then generate optimized autoloader
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ============================================
# Stage 2 — Node/Vite asset build
# ============================================
FROM node:20-alpine AS node-deps

WORKDIR /app

# Copy only package files first (better caching)
COPY package.json package-lock.json ./
RUN npm ci --quiet

# Copy source files and build
COPY resources/ ./resources/
COPY vite.config.ts ./
COPY tsconfig.json ./
RUN npm run build

# ============================================
# Stage 3 — Final production image
# ============================================
FROM php:8.2-fpm-alpine AS production

# Runtime libraries kept permanently in the image
RUN apk add --no-cache \
        libpng \
        libzip \
        oniguruma \
        libpq \
        nginx \
        supervisor \
        gettext \
        bash

# Build dependencies installed as a virtual group, then removed after compile
RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        libpng-dev \
        libzip-dev \
        oniguruma-dev \
        postgresql-dev \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        bcmath \
        gd \
        zip \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

WORKDIR /var/www/html

# Copy vendor from composer stage
COPY --from=composer-deps /app/vendor ./vendor
COPY --from=composer-deps /app/composer.json ./

# Copy built assets from node stage
COPY --from=node-deps /app/public/build ./public/build

# Copy application code
COPY . .

# Copy nginx, supervisord, and entrypoint configs
COPY docker/nginx.conf.template /etc/nginx/nginx.conf.template
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Permissions, runtime dirs, entrypoint executable
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && mkdir -p /run/nginx

# Render injects $PORT at runtime; 10000 is the default for local runs
ENV PORT=10000
EXPOSE 10000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
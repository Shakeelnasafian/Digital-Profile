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

# Install build deps + runtime deps
RUN apk add --no-cache \
        libpng-dev \
        libzip-dev \
        oniguruma-dev \
        autoconf \
        gcc \
        g++ \
        make \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    # Remove build tools after compilation
    && apk del --no-cache \
        autoconf \
        gcc \
        g++ \
        make \
        libpng-dev \
        libzip-dev \
        oniguruma-dev

WORKDIR /var/www/html

# Copy vendor from composer stage
COPY --from=composer-deps /app/vendor ./vendor
COPY --from=composer-deps /app/composer.json ./

# Copy built assets from node stage
COPY --from=node-deps /app/public/build ./public/build

# Copy application code
COPY . .

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache
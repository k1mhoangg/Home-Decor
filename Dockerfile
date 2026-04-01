# ============================================================
# Stage 1: Composer dependencies
# ============================================================
FROM composer:2.7 AS composer_stage

WORKDIR /app

# Copy composer files first for better layer caching
COPY composer.json composer.lock* ./

# Install dependencies without dev packages for production
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

# ============================================================
# Stage 2: PHP-FPM Production Image
# ============================================================
FROM php:8.3-fpm-alpine AS production

# Labels
LABEL maintainer="your-email@example.com"
LABEL version="1.0"
LABEL description="Optimized PHP-FPM with PHPMailer support"

# ────────────────────────────────────────────────────────────
# System dependencies & PHP extensions
# ────────────────────────────────────────────────────────────
RUN apk add --no-cache \
    # Runtime libraries
    libpng \
    libjpeg-turbo \
    libwebp \
    freetype \
    icu-libs \
    libzip \
    oniguruma \
    # Build dependencies (removed after install)
    && apk add --no-cache --virtual .build-deps \
    autoconf \
    gcc \
    g++ \
    make \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    # Configure & install GD
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    # Install PHP extensions
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    gd \
    zip \
    mbstring \
    intl \
    opcache \
    bcmath \
    exif \
    # Install PECL extensions
    && pecl install redis-6.0.2 \
    && docker-php-ext-enable redis \
    # Cleanup build deps
    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/*

# ────────────────────────────────────────────────────────────
# PHP Configuration
# ────────────────────────────────────────────────────────────
COPY config/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY config/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY config/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# ────────────────────────────────────────────────────────────
# Application setup
# ────────────────────────────────────────────────────────────
# Create non-root user for security
RUN addgroup -g 1000 -S appgroup && \
    adduser -u 1000 -S appuser -G appgroup

WORKDIR /var/www/html

# Copy vendor from composer stage
COPY --from=composer_stage /app/vendor ./vendor

# Copy application source
COPY --chown=appuser:appgroup src/ ./

# Set correct permissions
RUN chown -R appuser:appgroup /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/storage 2>/dev/null || true && \
    chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || true

USER appuser

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD php-fpm -t || exit 1

EXPOSE 9000

CMD ["php-fpm", "--nodaemonize"]
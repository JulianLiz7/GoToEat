FROM php:8.4-fpm

# ── Dependencias del sistema ────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    libzip-dev libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# ── Extensiones PHP ─────────────────────────────────────────────────
RUN docker-php-ext-install \
    pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# ── Redis vía PECL ──────────────────────────────────────────────────
RUN pecl install redis && docker-php-ext-enable redis

# ── OPcache + JIT optimizado para Docker Windows ─────────────────────
COPY docker/zz-opcache-prod.ini /usr/local/etc/php/conf.d/zz-opcache-prod.ini

# ── Node.js 20 (para compilar assets Vite en el servidor si hace falta)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# ── Composer ────────────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar package files primero para aprovechar cache de Docker layers
COPY package*.json ./
RUN npm ci --omit=dev 2>/dev/null || npm install --omit=dev

# Copiar el resto del proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction 2>/dev/null || true

# Compilar assets si no vienen pre-compilados (public/build no existe)
RUN [ -d "public/build" ] || npm run build

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

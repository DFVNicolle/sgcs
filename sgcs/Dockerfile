# ======================================
# Dockerfile para SGCS - Sistema de Gestión de Configuración de Software
# Optimizado para Render.com
# ======================================

# Etapa 1: Build de dependencias Node.js (Frontend)
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copiar archivos de configuración de Node
COPY package*.json ./

# Instalar dependencias de Node
RUN npm ci --only=production

# Copiar archivos necesarios para el build
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources

# Build de assets con Vite
RUN npm run build

# ======================================
# Etapa 2: Imagen final con PHP y Nginx
# ======================================
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones PHP
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client \
    postgresql-dev \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    && rm -rf /var/cache/apk/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario para la aplicación
RUN addgroup -g 1000 www && \
    adduser -D -u 1000 -G www www

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de la aplicación
COPY --chown=www:www . .

# Copiar assets compilados desde la etapa de Node
COPY --from=node-builder --chown=www:www /app/public/build ./public/build

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Configurar permisos
RUN chown -R www:www /var/www/html && \
    chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

# Copiar configuraciones de Nginx y Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Crear directorios necesarios
RUN mkdir -p /var/log/supervisor && \
    mkdir -p /var/log/nginx && \
    mkdir -p /run/nginx && \
    chown -R www:www /var/log/supervisor

# Script de entrada
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exponer puerto
EXPOSE 8080

# Comando de inicio
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

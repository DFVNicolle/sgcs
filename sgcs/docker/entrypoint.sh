#!/bin/sh

set -e

echo "🚀 Iniciando SGCS - Sistema de Gestión de Configuración de Software"

# Esperar a que la base de datos esté disponible
if [ -n "$DB_HOST" ]; then
    echo "⏳ Esperando conexión a la base de datos..."
    
    until nc -z -v -w30 $DB_HOST ${DB_PORT:-3306} > /dev/null 2>&1; do
        echo "⏳ Esperando a que la base de datos esté lista..."
        sleep 2
    done
    
    echo "✅ Base de datos conectada!"
fi

# Crear directorio de storage si no existe
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Configurar permisos
chown -R www:www /var/www/html/storage
chown -R www:www /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Cache de configuración de Laravel
echo "📦 Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones (solo si la variable está configurada)
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "🔄 Ejecutando migraciones..."
    php artisan migrate --force
fi

# Ejecutar seeders (solo si la variable está configurada)
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "🌱 Ejecutando seeders..."
    php artisan db:seed --force
fi

# Crear storage link
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "🔗 Creando storage link..."
    php artisan storage:link
fi

echo "✅ Aplicación lista!"
echo ""

# Ejecutar el comando principal
exec "$@"

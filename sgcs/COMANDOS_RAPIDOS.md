# ⚡ COMANDOS RÁPIDOS - SGCS Docker

## 🚀 DESARROLLO LOCAL

```bash
# Iniciar todo
docker-compose up -d

# Ver logs
docker-compose logs -f app

# Detener
docker-compose stop

# Eliminar todo (incluyendo BD)
docker-compose down -v
```

## 🔧 MANTENIMIENTO

```bash
# Entrar al contenedor
docker-compose exec app sh

# Migraciones
docker-compose exec app php artisan migrate

# Seeders
docker-compose exec app php artisan db:seed

# Limpiar cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Optimizar
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan optimize

# Ver info
docker-compose exec app php artisan about

# Listar rutas
docker-compose exec app php artisan route:list

# Tinker (REPL)
docker-compose exec app php artisan tinker
```

## 🗄️ BASE DE DATOS

```bash
# MySQL CLI
docker-compose exec db mysql -u sgcs_user -psgcs_password sgcs

# Mostrar tablas
docker-compose exec db mysql -u sgcs_user -psgcs_password sgcs -e "SHOW TABLES;"

# Backup BD
docker-compose exec db mysqldump -u sgcs_user -psgcs_password sgcs > backup.sql

# Restaurar BD
cat backup.sql | docker-compose exec -T db mysql -u sgcs_user -psgcs_password sgcs
```

## 🏗️ BUILD

```bash
# Construir imagen
docker build -t sgcs:latest .

# Construir sin cache
docker build --no-cache -t sgcs:latest .

# Reconstruir con docker-compose
docker-compose build --no-cache
docker-compose up -d --build
```

## 🌐 GIT + RENDER

```bash
# Subir a Git
git add .
git commit -m "Ready for Render deployment"
git push origin main

# Generar APP_KEY
php artisan key:generate --show

# O con Docker
docker run --rm -v ${PWD}:/app php:8.2-cli php /app/artisan key:generate --show
```

## 🐛 DEBUG

```bash
# Ver todos los logs
docker-compose logs

# Ver logs de un servicio específico
docker-compose logs app
docker-compose logs db

# Seguir logs en tiempo real
docker-compose logs -f app

# Ver últimas 100 líneas
docker-compose logs --tail=100 app

# Ver logs desde hace 1 hora
docker-compose logs --since 1h app

# Ver procesos en el contenedor
docker-compose exec app ps aux

# Ver uso de recursos
docker stats
```

## 🔍 VERIFICACIÓN

```bash
# Verificar que funcione
curl http://localhost:8080

# Verificar BD
docker-compose exec db mysql -u sgcs_user -psgcs_password sgcs -e "SELECT COUNT(*) FROM usuarios;"

# Verificar permisos storage
docker-compose exec app ls -la /var/www/html/storage

# Verificar PHP info
docker-compose exec app php -i

# Verificar extensiones PHP
docker-compose exec app php -m

# Verificar Composer packages
docker-compose exec app composer show

# Verificar variables de entorno
docker-compose exec app env | grep APP
```

## 🧹 LIMPIEZA

```bash
# Limpiar contenedores detenidos
docker container prune -f

# Limpiar imágenes sin usar
docker image prune -a -f

# Limpiar volúmenes sin usar
docker volume prune -f

# Limpiar todo
docker system prune -a --volumes -f

# Ver uso de disco Docker
docker system df
```

## 📦 COMPOSER & NPM

```bash
# Composer install
docker-compose exec app composer install

# Composer update
docker-compose exec app composer update

# Composer require
docker-compose exec app composer require vendor/package

# NPM install (si tienes Node en container)
docker-compose exec app npm install

# NPM build
docker-compose exec app npm run build
```

## 🔐 SEGURIDAD

```bash
# Generar nueva APP_KEY
docker-compose exec app php artisan key:generate

# Limpiar sessions
docker-compose exec app php artisan session:flush

# Ver permisos
docker-compose exec app ls -la storage/

# Arreglar permisos
docker-compose exec app chown -R www:www /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

## 🧪 TESTING

```bash
# Ejecutar tests
docker-compose exec app php artisan test

# Tests específicos
docker-compose exec app php artisan test --filter=NombreTest

# Con coverage
docker-compose exec app php artisan test --coverage

# PHPUnit directo
docker-compose exec app vendor/bin/phpunit
```

## 📊 MONITOREO

```bash
# Ver uso de CPU/RAM en tiempo real
docker stats

# Ver logs de Nginx
docker-compose exec app tail -f /var/log/nginx/access.log
docker-compose exec app tail -f /var/log/nginx/error.log

# Ver logs de Laravel
docker-compose exec app tail -f storage/logs/laravel.log

# Ver procesos de Supervisor
docker-compose exec app supervisorctl status
```

## 🎯 SHORTCUTS CON ALIAS (Opcional)

Agrega a tu `.bashrc` o `.zshrc`:

```bash
# Docker Compose shortcuts
alias dc='docker-compose'
alias dcu='docker-compose up -d'
alias dcd='docker-compose down'
alias dcl='docker-compose logs -f'
alias dce='docker-compose exec app'

# Laravel shortcuts
alias art='docker-compose exec app php artisan'
alias tinker='docker-compose exec app php artisan tinker'
alias migrate='docker-compose exec app php artisan migrate'
alias seed='docker-compose exec app php artisan db:seed'

# Uso:
# dc up -d
# art migrate
# tinker
```

## 📱 ACCESOS RÁPIDOS

```
🌐 Aplicación:     http://localhost:8080
🗄️  phpMyAdmin:    http://localhost:8081
   Usuario:       sgcs_user
   Contraseña:    sgcs_password
```

## 🆘 EMERGENCIA - RESET TOTAL

```bash
# ADVERTENCIA: Borra TODO
docker-compose down -v
docker system prune -a -f --volumes
docker-compose up -d --build
docker-compose exec app php artisan migrate:fresh --seed
```

---

**Tip:** Usa `docker-helper.bat` (Windows) o `docker-helper.sh` (Linux/Mac) para comandos más fáciles!

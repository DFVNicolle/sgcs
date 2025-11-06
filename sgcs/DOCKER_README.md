# 🐳 Docker Quick Start - SGCS

## 🚀 Inicio Rápido

### Desarrollo Local con Docker Compose

```bash
# 1. Levantar todo (BD + App + phpMyAdmin)
docker-compose up -d

# 2. Ver logs en tiempo real
docker-compose logs -f app

# 3. Acceder a la aplicación
#    http://localhost:8080

# 4. Acceder a phpMyAdmin
#    http://localhost:8081
#    Usuario: sgcs_user
#    Contraseña: sgcs_password
```

### Comandos Útiles

```bash
# Ver estado de contenedores
docker-compose ps

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Ejecutar seeders
docker-compose exec app php artisan db:seed

# Limpiar cache
docker-compose exec app php artisan cache:clear

# Acceder a la consola del contenedor
docker-compose exec app sh

# Detener todo
docker-compose down

# Detener y eliminar volúmenes (⚠️ BORRA LA BD)
docker-compose down -v
```

## 📦 Build Manual

```bash
# Construir imagen
docker build -t sgcs:latest .

# Ejecutar contenedor
docker run -d \
  -p 8080:8080 \
  -e APP_KEY=base64:tu-key-aqui \
  -e DB_HOST=host.docker.internal \
  sgcs:latest
```

## 🌐 Despliegue en Render

Ver archivo completo: **DEPLOY_RENDER.md**

### Pasos Rápidos

1. Sube tu código a GitHub
2. Crea cuenta en Render.com
3. New Web Service → Docker
4. Configura variables de entorno
5. Deploy!

## 🐛 Troubleshooting

### Contenedor no inicia

```bash
docker-compose logs app
```

### Resetear todo

```bash
docker-compose down -v
docker-compose up -d --build
```

### Permisos de storage

```bash
docker-compose exec app chown -R www:www /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

## 📚 Documentación Completa

- **DEPLOY_RENDER.md** - Guía completa de despliegue en Render
- **docker-compose.yml** - Configuración para desarrollo local
- **render.yaml** - Blueprint para Render (despliegue automático)

## 🎯 Archivos Docker

```
├── Dockerfile                  # Imagen principal
├── .dockerignore              # Archivos excluidos
├── docker-compose.yml         # Orquestación local
├── render.yaml                # Configuración Render
└── docker/
    ├── nginx.conf             # Servidor web
    ├── supervisord.conf       # Gestor de procesos
    ├── php-fpm.conf           # PHP FastCGI
    ├── php.ini                # Configuración PHP
    └── entrypoint.sh          # Script de inicio
```

## ✅ Verificación

```bash
# La app está corriendo si ves:
curl http://localhost:8080
# Debería devolver HTML de Laravel

# Verificar BD
docker-compose exec db mysql -u sgcs_user -psgcs_password sgcs -e "SHOW TABLES;"
```

---

**¿Necesitas ayuda?** Revisa **DEPLOY_RENDER.md** para más detalles.

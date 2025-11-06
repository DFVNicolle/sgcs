# 🚀 GUÍA DE DESPLIEGUE EN RENDER

Esta guía te ayudará a desplegar el SGCS en Render.com usando Docker.

---

## 📋 REQUISITOS PREVIOS

1. **Cuenta en Render.com** - [Regístrate gratis](https://render.com)
2. **Repositorio Git** - Tu código debe estar en GitHub, GitLab o Bitbucket
3. **Archivos Docker** - Ya están incluidos en este proyecto ✅

---

## 📦 ARCHIVOS DOCKER INCLUIDOS

```
sgcs/
├── Dockerfile                  # Imagen Docker optimizada
├── .dockerignore              # Archivos a excluir
├── docker-compose.yml         # Para desarrollo local
├── render.yaml                # Blueprint de Render (opcional)
└── docker/
    ├── nginx.conf             # Configuración Nginx
    ├── supervisord.conf       # Supervisor (PHP-FPM + Nginx + Workers)
    ├── php-fpm.conf           # Configuración PHP-FPM
    ├── php.ini                # Configuración PHP
    └── entrypoint.sh          # Script de inicio
```

---

## 🚀 MÉTODO 1: DESPLIEGUE AUTOMÁTICO CON BLUEPRINT

### Paso 1: Sube tu código a GitHub

```bash
git add .
git commit -m "Preparado para despliegue en Render"
git push origin main
```

### Paso 2: Importa el Blueprint en Render

1. Ve a [Render Dashboard](https://dashboard.render.com)
2. Click en **"New +"** → **"Blueprint"**
3. Conecta tu repositorio de GitHub
4. Render detectará automáticamente el archivo `render.yaml`
5. Click en **"Apply"**

### Paso 3: Configura variables adicionales

En el dashboard de tu servicio web, agrega:

- **APP_KEY**: Genera con `php artisan key:generate --show`
- **APP_URL**: Tu URL de Render (ej: `https://sgcs.onrender.com`)

### Paso 4: Primera ejecución

Para poblar la base de datos la primera vez:

1. Ve a **Environment** en tu servicio
2. Cambia `RUN_SEEDERS` a `true`
3. Guarda y espera el redespliegue
4. Después de la primera ejecución, cámbialo de vuelta a `false`

---

## 🔧 MÉTODO 2: DESPLIEGUE MANUAL

### Paso 1: Crear Base de Datos

1. En Render Dashboard, click **"New +"** → **"PostgreSQL"**
2. Nombre: `sgcs-db`
3. Plan: Free (o superior)
4. Región: Elige la más cercana
5. Click **"Create Database"**
6. **Guarda las credenciales** que se muestran

### Paso 2: Crear Web Service

1. Click **"New +"** → **"Web Service"**
2. Conecta tu repositorio de GitHub
3. Configuración:
   - **Name**: `sgcs-app`
   - **Region**: La misma que la BD
   - **Environment**: `Docker`
   - **Plan**: Free (o superior)

### Paso 3: Configurar Variables de Entorno

Agrega estas variables en la sección **Environment**:

```bash
# Aplicación
APP_NAME=SGCS
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:TU_KEY_GENERADA_AQUI
APP_URL=https://tu-app.onrender.com

# Base de Datos (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=tu-host-postgres.render.com
DB_PORT=5432
DB_DATABASE=sgcs
DB_USERNAME=sgcs_user
DB_PASSWORD=tu-password-de-render

# Cache y Queue
QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Seguridad
SESSION_SECURE_COOKIE=true

# Migraciones y Seeders
RUN_MIGRATIONS=true
RUN_SEEDERS=true  # Solo la primera vez
```

### Paso 4: Agregar Disco Persistente (Opcional pero recomendado)

1. En tu Web Service, ve a **"Disks"**
2. Click **"Add Disk"**
3. Configuración:
   - **Name**: `sgcs-storage`
   - **Mount Path**: `/var/www/html/storage`
   - **Size**: 1 GB
4. Click **"Save"**

### Paso 5: Desplegar

1. Click **"Create Web Service"**
2. Render comenzará a construir y desplegar tu aplicación
3. Espera 5-10 minutos para el primer build

---

## 🧪 PRUEBA LOCAL CON DOCKER

Antes de desplegar, puedes probar localmente:

### Con Docker Compose (Recomendado)

```bash
# Construir y levantar todos los servicios
docker-compose up -d

# Ver logs
docker-compose logs -f app

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Ejecutar seeders
docker-compose exec app php artisan db:seed

# Acceder a la aplicación
# http://localhost:8080

# Acceder a phpMyAdmin
# http://localhost:8081
```

### Con Docker solo

```bash
# Construir imagen
docker build -t sgcs:latest .

# Ejecutar contenedor
docker run -d \
  -p 8080:8080 \
  -e APP_KEY=base64:your-key-here \
  -e DB_HOST=host.docker.internal \
  -e DB_DATABASE=sgcs \
  -e DB_USERNAME=root \
  -e DB_PASSWORD=password \
  --name sgcs-app \
  sgcs:latest

# Ver logs
docker logs -f sgcs-app

# Detener
docker stop sgcs-app

# Eliminar
docker rm sgcs-app
```

---

## 🔑 GENERAR APP_KEY

```bash
# Localmente con PHP
php artisan key:generate --show

# Con Docker
docker run --rm sgcs:latest php artisan key:generate --show
```

---

## 🗄️ CONFIGURACIÓN DE BASE DE DATOS

### Para MySQL (Si usas tu propia BD)

```env
DB_CONNECTION=mysql
DB_HOST=tu-host-mysql
DB_PORT=3306
DB_DATABASE=sgcs
DB_USERNAME=tu-usuario
DB_PASSWORD=tu-password
```

### Para PostgreSQL (Recomendado en Render)

```env
DB_CONNECTION=pgsql
DB_HOST=tu-host-postgres
DB_PORT=5432
DB_DATABASE=sgcs
DB_USERNAME=tu-usuario
DB_PASSWORD=tu-password
```

Render proporciona PostgreSQL gratuito. Las credenciales aparecen automáticamente después de crear la base de datos.

---

## 🔧 COMANDOS ÚTILES EN RENDER

### Acceder a la consola

En tu Web Service, ve a **"Shell"** para ejecutar comandos:

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ejecutar migraciones
php artisan migrate --force

# Ejecutar seeders
php artisan db:seed --force

# Ver información de la aplicación
php artisan about

# Crear usuario administrador
php artisan tinker
>>> \App\Models\Usuario::create(['correo' => 'admin@sgcs.com', ...]);
```

---

## 🎯 OPTIMIZACIÓN PARA PRODUCCIÓN

### 1. Plan Recomendado

- **Free**: Para pruebas (se suspende después de 15 min de inactividad)
- **Starter ($7/mes)**: Para producción ligera
- **Standard ($25/mes)**: Para producción con más tráfico

### 2. Base de Datos

- **Free**: 1 GB, bueno para empezar
- **Starter ($7/mes)**: 10 GB, backups automáticos
- **Standard**: Más capacidad y rendimiento

### 3. Variables de Optimización

```env
# En producción
APP_DEBUG=false
LOG_LEVEL=warning

# Opcache (ya configurado en php.ini)
# Mejora significativamente el rendimiento
```

---

## 🐛 TROUBLESHOOTING

### Error: "Migraciones no se ejecutan"

```bash
# Asegúrate de que RUN_MIGRATIONS=true
# Verifica conexión a BD en los logs
```

### Error: "500 Internal Server Error"

```bash
# Verifica APP_KEY esté configurada
# Revisa los logs: Render Dashboard → Logs
# Verifica permisos de storage
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

```bash
# Verifica que las credenciales de BD sean correctas
# Asegúrate de que la BD esté en la misma región
# Verifica DB_HOST, DB_PORT, DB_DATABASE
```

### La aplicación tarda mucho en cargar

```bash
# En plan Free, Render suspende después de inactividad
# Considera upgrade a Starter plan
# Habilita "Auto-Deploy" en Settings
```

### Assets no cargan (CSS/JS)

```bash
# Verifica que el build de Vite se ejecutó correctamente
# Asegúrate de que APP_URL esté configurada
# Revisa que public/build exista en la imagen
```

---

## 📊 MONITOREO

### Logs en Tiempo Real

1. Ve a tu Web Service en Render
2. Click en **"Logs"**
3. Puedes filtrar por tipo de log

### Métricas

- CPU y Memoria: En el Dashboard de Render
- Errores de aplicación: `storage/logs/laravel.log`
- Errores de Nginx: `/var/log/nginx/error.log`

---

## 🔄 ACTUALIZAR LA APLICACIÓN

### Despliegue Automático

Render detecta automáticamente nuevos commits:

```bash
git add .
git commit -m "Nuevas funcionalidades"
git push origin main
# Render desplegará automáticamente
```

### Despliegue Manual

En Render Dashboard:
1. Ve a tu Web Service
2. Click en **"Manual Deploy"**
3. Selecciona el branch
4. Click **"Deploy"**

---

## 🔒 SEGURIDAD

### Recomendaciones

1. ✅ **APP_DEBUG=false** en producción
2. ✅ **APP_KEY** única y segura
3. ✅ **SESSION_SECURE_COOKIE=true** con HTTPS
4. ✅ Actualiza dependencias regularmente: `composer update`
5. ✅ Usa contraseñas fuertes para la BD
6. ✅ Habilita autenticación de dos factores en Render

### Variables Sensibles

- Nunca commits el archivo `.env` a Git
- Usa variables de entorno en Render
- Rota credenciales periódicamente

---

## 📧 SOPORTE

### Recursos Útiles

- **Documentación Render**: https://render.com/docs
- **Foro Render**: https://community.render.com
- **Laravel Docs**: https://laravel.com/docs

### Logs de Error

Si algo falla, revisa:
1. Logs de Render (Dashboard → Logs)
2. `storage/logs/laravel.log`
3. `/var/log/nginx/error.log`

---

## ✅ CHECKLIST FINAL

Antes de desplegar:

- [ ] Código subido a GitHub/GitLab/Bitbucket
- [ ] APP_KEY generada
- [ ] Variables de entorno configuradas
- [ ] Base de datos creada en Render
- [ ] `RUN_MIGRATIONS=true` configurado
- [ ] `RUN_SEEDERS=true` para primera ejecución
- [ ] Disco persistente configurado (opcional)
- [ ] APP_URL apunta a tu dominio de Render

Después del primer despliegue:

- [ ] Cambiar `RUN_SEEDERS=false`
- [ ] Verificar que la aplicación carga correctamente
- [ ] Probar login con usuarios de prueba
- [ ] Configurar dominio personalizado (opcional)

---

## 🎉 ¡LISTO!

Tu aplicación SGCS debería estar corriendo en:
**https://tu-app-name.onrender.com**

**Usuarios de Prueba:**
- Gestor de Configuración: `scm.manager@sgcs.com` / `scm123`
- Product Owner (Scrum): `po.scrum@sgcs.com` / `po123`
- Líder de Proyecto (Cascada): `pm.cascada@sgcs.com` / `pm123`

**¡Disfruta tu despliegue! 🚀**

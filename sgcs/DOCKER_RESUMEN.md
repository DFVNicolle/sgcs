# 🐳 DOCKER + RENDER - CONFIGURACIÓN COMPLETA

## ✅ ARCHIVOS CREADOS

```
sgcs/
├── 📄 Dockerfile                  ✅ Imagen Docker optimizada
├── 📄 .dockerignore              ✅ Exclusiones de build
├── 📄 docker-compose.yml         ✅ Desarrollo local (MySQL + phpMyAdmin)
├── 📄 render.yaml                ✅ Blueprint Render (despliegue automático)
├── 📄 .env.render                ✅ Variables de ejemplo para Render
├── 📄 .gitignore                 ✅ Archivos a excluir de Git
├── 📄 docker-helper.sh           ✅ Script helper Linux/Mac
├── 📄 docker-helper.bat          ✅ Script helper Windows
│
├── 📚 DOCKER_README.md           ✅ Guía rápida Docker
├── 📚 DEPLOY_RENDER.md           ✅ Guía completa despliegue Render
├── 📚 RENDER_CHECKLIST.md        ✅ Checklist paso a paso
│
└── docker/
    ├── nginx.conf                ✅ Configuración Nginx
    ├── supervisord.conf          ✅ Supervisor (PHP-FPM + Nginx + Workers)
    ├── php-fpm.conf              ✅ Configuración PHP-FPM
    ├── php.ini                   ✅ Configuración PHP
    └── entrypoint.sh             ✅ Script de inicialización
```

---

## 🚀 INICIO RÁPIDO

### 1️⃣ Desarrollo Local

```bash
# Windows
docker-helper.bat start

# Linux/Mac
chmod +x docker-helper.sh
./docker-helper.sh start
```

**Acceso:**
- 🌐 App: http://localhost:8080
- 🗄️ phpMyAdmin: http://localhost:8081

### 2️⃣ Despliegue en Render

1. **Sube tu código a GitHub**
   ```bash
   git add .
   git commit -m "Docker ready for Render"
   git push origin main
   ```

2. **Genera APP_KEY**
   ```bash
   php artisan key:generate --show
   ```

3. **En Render.com:**
   - New + → Blueprint
   - Conecta tu repo
   - Configura variables (ver `.env.render`)
   - Deploy!

📖 **Guía detallada:** `RENDER_CHECKLIST.md`

---

## 📂 DOCUMENTACIÓN

| Archivo | Descripción |
|---------|-------------|
| `DOCKER_README.md` | Guía rápida Docker y comandos útiles |
| `DEPLOY_RENDER.md` | Guía completa de despliegue en Render |
| `RENDER_CHECKLIST.md` | Checklist paso a paso para Render |
| `.env.render` | Template de variables de entorno |

---

## 🔑 COMANDOS ÚTILES

### Desarrollo Local

```bash
# Iniciar
docker-helper.bat start           # Windows
./docker-helper.sh start          # Linux/Mac

# Ver logs
docker-helper.bat logs

# Ejecutar migraciones
docker-helper.bat migrate

# Ejecutar seeders
docker-helper.bat seed

# Limpiar cache
docker-helper.bat cache-clear

# Shell del contenedor
docker-helper.bat shell

# Detener
docker-helper.bat stop
```

### Render Shell

```bash
# Limpiar cache
php artisan cache:clear

# Ver info
php artisan about

# Ejecutar migrations
php artisan migrate --force
```

---

## 🌐 VARIABLES DE ENTORNO PARA RENDER

```env
# APLICACIÓN
APP_NAME=SGCS
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:TU_KEY_AQUI
APP_URL=https://tu-app.onrender.com

# BASE DE DATOS (PostgreSQL de Render)
DB_CONNECTION=pgsql
DB_HOST=dpg-xxxxx.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=sgcs
DB_USERNAME=sgcs_user
DB_PASSWORD=tu-password-render

# CACHE & SESSION
QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true

# MIGRACIONES
RUN_MIGRATIONS=true
RUN_SEEDERS=true  # Solo primera vez, luego cambiar a false
```

---

## 🧪 PROBAR ANTES DE DESPLEGAR

```bash
# 1. Construir imagen
docker build -t sgcs-test .

# 2. Verificar configuración
docker-helper.bat render-test

# 3. Probar localmente con docker-compose
docker-helper.bat start
```

---

## 📊 ESTRUCTURA DOCKER

```
┌─────────────────────────────────────┐
│     Render / Docker Container       │
│                                     │
│  ┌────────────────────────────┐   │
│  │       SUPERVISOR           │   │
│  │  (Gestor de procesos)      │   │
│  │                            │   │
│  │  ┌──────────────────────┐ │   │
│  │  │    PHP-FPM           │ │   │
│  │  │  (Laravel app)       │ │   │
│  │  └──────────────────────┘ │   │
│  │                            │   │
│  │  ┌──────────────────────┐ │   │
│  │  │    NGINX             │ │   │
│  │  │  (Web server)        │ │   │
│  │  └──────────────────────┘ │   │
│  │                            │   │
│  │  ┌──────────────────────┐ │   │
│  │  │  Laravel Queue       │ │   │
│  │  │  Worker              │ │   │
│  │  └──────────────────────┘ │   │
│  └────────────────────────────┘   │
│                                     │
│  Puerto 8080 → Internet             │
└─────────────────────────────────────┘
            ↓
    ┌──────────────────┐
    │   PostgreSQL     │
    │  (Render DB)     │
    └──────────────────┘
```

---

## 🔧 OPTIMIZACIONES INCLUIDAS

### PHP (php.ini)
- ✅ Opcache habilitado
- ✅ Upload máximo: 100MB
- ✅ Memoria: 512MB
- ✅ Tiempo ejecución: 600s

### Nginx
- ✅ Gzip compression
- ✅ Cache de assets estáticos
- ✅ Optimización de buffers
- ✅ Client max body: 100MB

### Laravel
- ✅ Config cache en build
- ✅ Route cache en build
- ✅ View cache en build
- ✅ Optimización de autoloader

---

## 🐛 TROUBLESHOOTING RÁPIDO

| Problema | Solución |
|----------|----------|
| Build falla | Verifica `composer.json` y `package.json` |
| 500 Error | Verifica APP_KEY y revisa logs |
| DB Connection refused | Verifica credenciales y región de BD |
| Assets no cargan | Verifica APP_URL y `npm run build` |
| App lenta (Free plan) | Normal, upgrade a Starter |

📖 **Más detalles:** `DEPLOY_RENDER.md` sección Troubleshooting

---

## ✅ CHECKLIST RÁPIDO

### Antes de subir a Git
- [ ] `.env` en .gitignore (no subir)
- [ ] `composer.lock` incluido
- [ ] `package-lock.json` incluido
- [ ] Todos los archivos Docker creados

### Antes de desplegar
- [ ] APP_KEY generada
- [ ] Base de datos creada en Render
- [ ] Variables de entorno configuradas
- [ ] `RUN_MIGRATIONS=true`
- [ ] `RUN_SEEDERS=true` (primera vez)

### Después del primer despliegue
- [ ] App accesible
- [ ] Login funciona
- [ ] Cambiar `RUN_SEEDERS=false`
- [ ] Actualizar `APP_URL`

---

## 👥 USUARIOS DE PRUEBA

```
Gestor Configuración:
📧 scm.manager@sgcs.com
🔑 scm123

Product Owner (Scrum):
📧 po.scrum@sgcs.com
🔑 po123

Líder Proyecto (Cascada):
📧 pm.cascada@sgcs.com
🔑 pm123

Desarrollador:
📧 dev1.scrum@sgcs.com
🔑 dev123
```

---

## 💰 COSTOS ESTIMADOS

### Plan Free
- Web Service: $0
- PostgreSQL: $0
- **Total: $0/mes**
- ⚠️ Se suspende después de 15 min inactividad

### Plan Producción (Recomendado)
- Web Service Starter: $7/mes
- PostgreSQL Starter: $7/mes
- **Total: $14/mes**
- ✅ Sin suspensión
- ✅ Mejor rendimiento

---

## 📚 RECURSOS ADICIONALES

- **Render Docs**: https://render.com/docs
- **Laravel Deployment**: https://laravel.com/docs/deployment
- **Docker Best Practices**: https://docs.docker.com/develop/dev-best-practices/

---

## 🎉 ¡TODO LISTO!

Tu proyecto SGCS está configurado con:
- ✅ Docker optimizado para producción
- ✅ Scripts helper para desarrollo
- ✅ Configuración lista para Render
- ✅ Documentación completa

### Próximos pasos:

1. **Desarrollo local:** `docker-helper.bat start`
2. **Subir a Git:** `git push origin main`
3. **Desplegar en Render:** Seguir `RENDER_CHECKLIST.md`

**¡Buena suerte con tu despliegue! 🚀**

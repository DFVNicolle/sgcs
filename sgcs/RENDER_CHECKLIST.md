# 🚀 CHECKLIST DE DESPLIEGUE EN RENDER

## ✅ Pre-Despliegue

### 1. Preparar Repositorio
- [ ] Código subido a GitHub/GitLab/Bitbucket
- [ ] Todos los archivos Docker están en el repo
- [ ] `.env` NO está en el repo (debe estar en .gitignore)
- [ ] `composer.lock` y `package-lock.json` están en el repo

### 2. Archivos Docker Creados
- [ ] `Dockerfile` - Imagen principal
- [ ] `.dockerignore` - Exclusiones
- [ ] `docker-compose.yml` - Para desarrollo local
- [ ] `render.yaml` - Blueprint de Render
- [ ] `docker/nginx.conf` - Configuración Nginx
- [ ] `docker/supervisord.conf` - Supervisor
- [ ] `docker/php-fpm.conf` - PHP-FPM
- [ ] `docker/php.ini` - PHP config
- [ ] `docker/entrypoint.sh` - Script de inicio

### 3. Generar APP_KEY
```bash
php artisan key:generate --show
# O con Docker:
docker run --rm -v ${PWD}:/app php:8.2-cli php /app/artisan key:generate --show
```
Copia el valor generado: `base64:xxx...`

---

## 🌐 En Render.com

### 1. Crear Base de Datos PostgreSQL

#### Opción A: Con Blueprint (Automático)
- Va a **Dashboard** → **New +** → **Blueprint**
- Conecta tu repo
- Render detectará `render.yaml` y creará todo automáticamente

#### Opción B: Manual
1. Click **New +** → **PostgreSQL**
2. Configuración:
   - **Name**: `sgcs-db`
   - **Database**: `sgcs`
   - **User**: `sgcs_user`
   - **Region**: Selecciona la más cercana
   - **Plan**: Free (o superior)
3. Click **Create Database**
4. **⚠️ GUARDAR credenciales mostradas**

### 2. Crear Web Service

1. Click **New +** → **Web Service**
2. Conecta tu repositorio
3. Configuración básica:
   - **Name**: `sgcs-app`
   - **Region**: **LA MISMA que la base de datos**
   - **Branch**: `main` (o tu branch principal)
   - **Environment**: `Docker`
   - **Plan**: Free (o superior)

### 3. Configurar Variables de Entorno

En la sección **Environment**, agrega TODAS estas variables:

```bash
# APLICACIÓN
APP_NAME=SGCS
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:TU_KEY_GENERADA_AQUI
APP_URL=https://tu-app-name.onrender.com

# LOG
LOG_CHANNEL=stack
LOG_LEVEL=info

# BASE DE DATOS (Copiar desde la BD creada en Render)
DB_CONNECTION=pgsql
DB_HOST=dpg-xxxxx.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=sgcs
DB_USERNAME=sgcs_user
DB_PASSWORD=password_de_render_muy_largo

# CACHE & QUEUE
QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=database
SESSION_LIFETIME=120

# SEGURIDAD
SESSION_SECURE_COOKIE=true

# MIGRACIONES
RUN_MIGRATIONS=true
RUN_SEEDERS=true   # ⚠️ Solo para la primera vez
```

**📝 Notas:**
- Copia `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` de la sección "Connections" de tu base de datos PostgreSQL
- El `APP_URL` lo obtendrás después del primer despliegue

### 4. Disco Persistente (Opcional pero Recomendado)

1. En tu Web Service, ve a **"Disks"**
2. Click **"Add Disk"**
3. Configuración:
   - **Name**: `sgcs-storage`
   - **Mount Path**: `/var/www/html/storage`
   - **Size**: 1 GB (Free tier)
4. Click **"Save"**

### 5. Configuración Avanzada (Opcional)

- **Health Check Path**: `/` (por defecto)
- **Docker Command**: (dejar vacío, usa el del Dockerfile)
- **Auto-Deploy**: Activado (para despliegues automáticos con git push)

---

## 🚀 Despliegue

### 1. Iniciar Primer Despliegue

1. Revisa todas las configuraciones
2. Click **"Create Web Service"**
3. Render comenzará a:
   - Clonar tu repositorio
   - Construir la imagen Docker (5-10 min)
   - Iniciar el contenedor
   - Ejecutar migraciones (si `RUN_MIGRATIONS=true`)
   - Ejecutar seeders (si `RUN_SEEDERS=true`)

### 2. Monitorear el Build

1. Ve a la pestaña **"Logs"**
2. Observa el progreso:
   ```
   Building... (esto puede tardar)
   ✅ Base de datos conectada!
   📦 Optimizando aplicación...
   🔄 Ejecutando migraciones...
   🌱 Ejecutando seeders...
   ✅ Aplicación lista!
   ```

### 3. Verificar Despliegue Exitoso

Cuando veas en los logs:
```
✅ Aplicación lista!
```

Tu app estará disponible en:
**https://tu-app-name.onrender.com**

---

## 🔧 Post-Despliegue

### 1. Actualizar APP_URL

1. Copia tu URL de Render
2. Ve a **Environment**
3. Actualiza `APP_URL` con tu URL real
4. Guarda (esto reiniciará el servicio)

### 2. Desactivar Seeders

**⚠️ IMPORTANTE:** Después del primer despliegue exitoso:

1. Ve a **Environment**
2. Cambia `RUN_SEEDERS=false`
3. Guarda

Esto evita que los seeders se ejecuten en cada despliegue.

### 3. Probar la Aplicación

Accede a tu URL y prueba:
- [ ] La página principal carga
- [ ] Login funciona
- [ ] Assets (CSS/JS) cargan correctamente
- [ ] Base de datos funciona (lista de proyectos, usuarios, etc.)

### 4. Usuarios de Prueba

```
Gestor de Configuración:
Email: scm.manager@sgcs.com
Password: scm123

Product Owner (Scrum):
Email: po.scrum@sgcs.com
Password: po123

Líder de Proyecto (Cascada):
Email: pm.cascada@sgcs.com
Password: pm123
```

---

## 📊 Monitoreo y Mantenimiento

### Ver Logs en Tiempo Real

1. Dashboard → Tu servicio → **Logs**
2. Los logs muestran:
   - Requests HTTP
   - Errores de aplicación
   - Errores de Nginx
   - Salida de Supervisor

### Acceder a Shell

1. Dashboard → Tu servicio → **Shell**
2. Ejecutar comandos:
```bash
php artisan about
php artisan cache:clear
php artisan route:list
```

### Métricas

- **CPU y Memoria**: En el dashboard principal
- **Requests**: En la sección "Metrics"
- **Uptime**: Mostrado en el dashboard

---

## 🔄 Actualizar la Aplicación

### Despliegue Automático (Recomendado)

Si **Auto-Deploy** está activado:

```bash
# En tu máquina local
git add .
git commit -m "Nueva funcionalidad"
git push origin main

# Render detectará el push y desplegará automáticamente
```

### Despliegue Manual

1. Dashboard → Tu servicio
2. Click **"Manual Deploy"**
3. Selecciona el branch
4. Click **"Deploy latest commit"**

---

## 🐛 Troubleshooting

### Build Falla

**Error**: `failed to solve: process "/bin/sh -c composer install..."`

**Solución**:
1. Verifica que `composer.json` y `composer.lock` estén en el repo
2. Revisa los logs completos
3. Prueba el build localmente: `docker build -t sgcs-test .`

### Migraciones no se ejecutan

**Solución**:
1. Verifica `RUN_MIGRATIONS=true`
2. Revisa conexión a BD en los logs
3. Verifica credenciales de BD
4. Ejecuta manual: Shell → `php artisan migrate --force`

### Error 500

**Solución**:
1. Verifica que `APP_KEY` esté configurada
2. Revisa logs: Dashboard → Logs
3. Verifica permisos de storage
4. Limpia cache: Shell → `php artisan cache:clear`

### Assets no cargan (CSS/JS)

**Solución**:
1. Verifica que `APP_URL` sea correcto
2. Asegúrate de que el build de Vite se ejecutó: `npm run build`
3. Verifica que `public/build` exista en la imagen
4. Reconstruye: Manual Deploy → Clear build cache

### Base de Datos Connection Refused

**Solución**:
1. Verifica que BD y App estén en la **misma región**
2. Copia las credenciales exactas desde la BD en Render
3. Verifica formato PostgreSQL:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=dpg-xxxxx.oregon-postgres.render.com
   DB_PORT=5432
   ```

### App se suspende (Plan Free)

**Comportamiento normal**: En plan Free, Render suspende el servicio después de 15 minutos de inactividad.

**Soluciones**:
- **Upgrade a plan Starter** ($7/mes) - No se suspende
- **Keep-alive**: Usa un servicio de ping externo (ej: UptimeRobot)
- **Acepta la latencia**: Primera request tardará ~30 seg

---

## 💰 Costos

### Plan Free
- ✅ Web Service: Gratis
- ✅ PostgreSQL: Gratis (1 GB)
- ✅ Disco: 1 GB gratis
- ⚠️ Se suspende después de inactividad
- ⚠️ 750 horas/mes de ejecución

### Plan Starter ($7/mes por servicio)
- ✅ Sin suspensión
- ✅ 512 MB RAM
- ✅ Mejor rendimiento
- ✅ PostgreSQL más grande

### Recomendación para Producción
- **Web Service**: Starter ($7/mes)
- **Base de Datos**: Starter ($7/mes)
- **Total**: ~$14/mes

---

## ✅ CHECKLIST FINAL

Antes de marcar como completo:

- [ ] Base de datos PostgreSQL creada en Render
- [ ] Web Service creado y configurado
- [ ] Todas las variables de entorno configuradas
- [ ] APP_KEY generada y configurada
- [ ] Build completado exitosamente
- [ ] Migraciones ejecutadas
- [ ] Seeders ejecutados (primera vez)
- [ ] `RUN_SEEDERS` cambiado a `false`
- [ ] APP_URL actualizada con URL real
- [ ] Aplicación accesible y funcional
- [ ] Login funciona
- [ ] Assets cargan correctamente
- [ ] Disco persistente configurado (opcional)
- [ ] Auto-Deploy activado

---

## 🎉 ¡FELICITACIONES!

Tu aplicación SGCS está desplegada y funcionando en:
**https://tu-app.onrender.com**

### Próximos Pasos

1. **Dominio personalizado** (opcional):
   - Settings → Custom Domain → Agregar tu dominio

2. **SSL/HTTPS**:
   - Ya está habilitado automáticamente por Render ✅

3. **Monitoreo**:
   - Configura alertas en Render
   - Usa logs para debug

4. **Backups**:
   - Plan Starter de PostgreSQL incluye backups automáticos

---

**¿Necesitas ayuda?**
- 📚 Docs Render: https://render.com/docs
- 💬 Community: https://community.render.com
- 📧 Soporte: support@render.com

**¡Disfruta tu despliegue! 🚀**

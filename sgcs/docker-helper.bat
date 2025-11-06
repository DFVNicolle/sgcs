@echo off
REM Script de ayuda para Docker en Windows - SGCS

setlocal enabledelayedexpansion

set "command=%~1"

if "%command%"=="" (
    call :show_help
    exit /b 0
)

if "%command%"=="start" call :start_containers
if "%command%"=="stop" call :stop_containers
if "%command%"=="restart" call :restart_containers
if "%command%"=="logs" call :show_logs
if "%command%"=="status" call :show_status
if "%command%"=="build" call :build_image
if "%command%"=="rebuild" call :rebuild_image
if "%command%"=="migrate" call :run_migrations
if "%command%"=="seed" call :run_seeders
if "%command%"=="fresh" call :fresh_database
if "%command%"=="cache-clear" call :cache_clear
if "%command%"=="optimize" call :optimize_app
if "%command%"=="shell" call :shell_access
if "%command%"=="artisan" call :run_artisan %*
if "%command%"=="composer" call :run_composer %*
if "%command%"=="test" call :run_tests
if "%command%"=="reset" call :reset_all
if "%command%"=="render-test" call :test_render_config
if "%command%"=="key-generate" call :generate_key
if "%command%"=="help" call :show_help
if "%command%"=="-h" call :show_help
if "%command%"=="--help" call :show_help

exit /b 0

:show_help
echo ================================================
echo       Docker Helper - SGCS (Windows)
echo ================================================
echo.
echo Uso: docker-helper.bat [comando]
echo.
echo Comandos disponibles:
echo.
echo   start          Iniciar todos los contenedores
echo   stop           Detener todos los contenedores
echo   restart        Reiniciar todos los contenedores
echo   logs           Ver logs en tiempo real
echo   status         Ver estado de contenedores
echo.
echo   build          Construir imagen Docker
echo   rebuild        Reconstruir imagen desde cero
echo.
echo   migrate        Ejecutar migraciones
echo   seed           Ejecutar seeders
echo   fresh          Migracion fresh + seeders (BORRA DATOS)
echo.
echo   cache-clear    Limpiar todos los caches
echo   optimize       Optimizar aplicacion
echo.
echo   shell          Acceder a shell del contenedor
echo   artisan        Ejecutar comando artisan
echo   composer       Ejecutar comando composer
echo.
echo   test           Ejecutar tests
echo   reset          Resetear todo (BORRA TODO)
echo.
echo   render-test    Probar configuracion para Render
echo   key-generate   Generar APP_KEY
echo.
echo Ejemplos:
echo   docker-helper.bat start
echo   docker-helper.bat artisan migrate
echo   docker-helper.bat composer install
echo.
exit /b 0

:start_containers
echo Iniciando contenedores...
docker-compose up -d
echo.
echo Contenedores iniciados
echo.
echo Aplicacion: http://localhost:8080
echo phpMyAdmin: http://localhost:8081
exit /b 0

:stop_containers
echo Deteniendo contenedores...
docker-compose stop
echo Contenedores detenidos
exit /b 0

:restart_containers
echo Reiniciando contenedores...
docker-compose restart
echo Contenedores reiniciados
exit /b 0

:show_logs
echo Mostrando logs (Ctrl+C para salir)...
docker-compose logs -f app
exit /b 0

:show_status
echo Estado de contenedores:
echo.
docker-compose ps
exit /b 0

:build_image
echo Construyendo imagen Docker...
docker build -t sgcs:latest .
echo Imagen construida
exit /b 0

:rebuild_image
echo Reconstruyendo desde cero...
docker-compose build --no-cache
docker-compose up -d
echo Imagen reconstruida y contenedores reiniciados
exit /b 0

:run_migrations
echo Ejecutando migraciones...
docker-compose exec app php artisan migrate
echo Migraciones ejecutadas
exit /b 0

:run_seeders
echo Ejecutando seeders...
docker-compose exec app php artisan db:seed
echo Seeders ejecutados
exit /b 0

:fresh_database
echo ADVERTENCIA: Esto borrara todos los datos
set /p confirm="Estas seguro? (S/N): "
if /i "%confirm%"=="S" (
    echo Ejecutando migrate:fresh --seed...
    docker-compose exec app php artisan migrate:fresh --seed
    echo Base de datos reseteada y poblada
) else (
    echo Cancelado
)
exit /b 0

:cache_clear
echo Limpiando caches...
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
echo Caches limpiados
exit /b 0

:optimize_app
echo Optimizando aplicacion...
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
echo Aplicacion optimizada
exit /b 0

:shell_access
echo Accediendo a shell (exit para salir)...
docker-compose exec app sh
exit /b 0

:run_artisan
shift
echo Ejecutando artisan %*
docker-compose exec app php artisan %*
exit /b 0

:run_composer
shift
echo Ejecutando composer %*
docker-compose exec app composer %*
exit /b 0

:run_tests
echo Ejecutando tests...
docker-compose exec app php artisan test
exit /b 0

:reset_all
echo ADVERTENCIA: Esto eliminara TODO (contenedores, volumenes, datos)
set /p confirm="Estas REALMENTE seguro? (S/N): "
if /i "%confirm%"=="S" (
    echo Eliminando todo...
    docker-compose down -v
    echo Todo eliminado
    echo.
    echo Para volver a empezar: docker-helper.bat start
) else (
    echo Cancelado
)
exit /b 0

:test_render_config
echo Verificando configuracion para Render...
echo.
echo Verificando archivos...
if exist "Dockerfile" (echo [OK] Dockerfile) else (echo [FALTA] Dockerfile)
if exist ".dockerignore" (echo [OK] .dockerignore) else (echo [FALTA] .dockerignore)
if exist "render.yaml" (echo [OK] render.yaml) else (echo [FALTA] render.yaml)
if exist "docker\nginx.conf" (echo [OK] docker\nginx.conf) else (echo [FALTA] docker\nginx.conf)
if exist "docker\entrypoint.sh" (echo [OK] docker\entrypoint.sh) else (echo [FALTA] docker\entrypoint.sh)
echo.
echo Construyendo imagen de prueba...
docker build -t sgcs-test:latest .
if %errorlevel% equ 0 (
    echo Imagen construida exitosamente
) else (
    echo Error al construir imagen
)
exit /b 0

:generate_key
echo Generando APP_KEY...
echo.
if exist ".env" (
    php artisan key:generate
) else (
    echo APP_KEY generada:
    docker run --rm sgcs:latest php artisan key:generate --show
)
exit /b 0

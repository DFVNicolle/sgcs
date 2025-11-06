#!/bin/bash

# Script de ayuda para Docker - SGCS
# Uso: ./docker-helper.sh [comando]

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

show_help() {
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${GREEN}🐳 Docker Helper - SGCS${NC}"
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo ""
    echo "Uso: ./docker-helper.sh [comando]"
    echo ""
    echo "Comandos disponibles:"
    echo ""
    echo -e "  ${GREEN}start${NC}          Iniciar todos los contenedores"
    echo -e "  ${GREEN}stop${NC}           Detener todos los contenedores"
    echo -e "  ${GREEN}restart${NC}        Reiniciar todos los contenedores"
    echo -e "  ${GREEN}logs${NC}           Ver logs en tiempo real"
    echo -e "  ${GREEN}status${NC}         Ver estado de contenedores"
    echo ""
    echo -e "  ${GREEN}build${NC}          Construir imagen Docker"
    echo -e "  ${GREEN}rebuild${NC}        Reconstruir imagen desde cero"
    echo ""
    echo -e "  ${GREEN}migrate${NC}        Ejecutar migraciones"
    echo -e "  ${GREEN}seed${NC}           Ejecutar seeders"
    echo -e "  ${GREEN}fresh${NC}          Migración fresh + seeders (⚠️  BORRA DATOS)"
    echo ""
    echo -e "  ${GREEN}cache-clear${NC}    Limpiar todos los caches"
    echo -e "  ${GREEN}optimize${NC}       Optimizar aplicación"
    echo ""
    echo -e "  ${GREEN}shell${NC}          Acceder a shell del contenedor"
    echo -e "  ${GREEN}artisan${NC}        Ejecutar comando artisan"
    echo -e "  ${GREEN}composer${NC}       Ejecutar comando composer"
    echo ""
    echo -e "  ${GREEN}test${NC}           Ejecutar tests"
    echo -e "  ${GREEN}reset${NC}          Resetear todo (⚠️  BORRA TODO)"
    echo ""
    echo -e "  ${GREEN}render-test${NC}    Probar configuración para Render"
    echo -e "  ${GREEN}key-generate${NC}   Generar APP_KEY"
    echo ""
    echo "Ejemplos:"
    echo "  ./docker-helper.sh start"
    echo "  ./docker-helper.sh artisan migrate"
    echo "  ./docker-helper.sh composer install"
}

check_docker() {
    if ! command -v docker &> /dev/null; then
        echo -e "${RED}❌ Docker no está instalado${NC}"
        exit 1
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        echo -e "${RED}❌ Docker Compose no está instalado${NC}"
        exit 1
    fi
}

start_containers() {
    echo -e "${GREEN}🚀 Iniciando contenedores...${NC}"
    docker-compose up -d
    echo ""
    echo -e "${GREEN}✅ Contenedores iniciados${NC}"
    echo ""
    echo -e "🌐 Aplicación: ${BLUE}http://localhost:8080${NC}"
    echo -e "🗄️  phpMyAdmin: ${BLUE}http://localhost:8081${NC}"
}

stop_containers() {
    echo -e "${YELLOW}⏹️  Deteniendo contenedores...${NC}"
    docker-compose stop
    echo -e "${GREEN}✅ Contenedores detenidos${NC}"
}

restart_containers() {
    echo -e "${YELLOW}🔄 Reiniciando contenedores...${NC}"
    docker-compose restart
    echo -e "${GREEN}✅ Contenedores reiniciados${NC}"
}

show_logs() {
    echo -e "${BLUE}📋 Mostrando logs (Ctrl+C para salir)...${NC}"
    docker-compose logs -f app
}

show_status() {
    echo -e "${BLUE}📊 Estado de contenedores:${NC}"
    echo ""
    docker-compose ps
}

build_image() {
    echo -e "${GREEN}🏗️  Construyendo imagen Docker...${NC}"
    docker build -t sgcs:latest .
    echo -e "${GREEN}✅ Imagen construida${NC}"
}

rebuild_image() {
    echo -e "${YELLOW}🔨 Reconstruyendo desde cero...${NC}"
    docker-compose build --no-cache
    docker-compose up -d
    echo -e "${GREEN}✅ Imagen reconstruida y contenedores reiniciados${NC}"
}

run_migrations() {
    echo -e "${GREEN}🔄 Ejecutando migraciones...${NC}"
    docker-compose exec app php artisan migrate
    echo -e "${GREEN}✅ Migraciones ejecutadas${NC}"
}

run_seeders() {
    echo -e "${GREEN}🌱 Ejecutando seeders...${NC}"
    docker-compose exec app php artisan db:seed
    echo -e "${GREEN}✅ Seeders ejecutados${NC}"
}

fresh_database() {
    echo -e "${RED}⚠️  ADVERTENCIA: Esto borrará todos los datos${NC}"
    read -p "¿Estás seguro? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${YELLOW}🔄 Ejecutando migrate:fresh --seed...${NC}"
        docker-compose exec app php artisan migrate:fresh --seed
        echo -e "${GREEN}✅ Base de datos reseteada y poblada${NC}"
    else
        echo -e "${YELLOW}Cancelado${NC}"
    fi
}

cache_clear() {
    echo -e "${YELLOW}🧹 Limpiando caches...${NC}"
    docker-compose exec app php artisan cache:clear
    docker-compose exec app php artisan config:clear
    docker-compose exec app php artisan route:clear
    docker-compose exec app php artisan view:clear
    echo -e "${GREEN}✅ Caches limpiados${NC}"
}

optimize_app() {
    echo -e "${GREEN}⚡ Optimizando aplicación...${NC}"
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    echo -e "${GREEN}✅ Aplicación optimizada${NC}"
}

shell_access() {
    echo -e "${BLUE}🖥️  Accediendo a shell (exit para salir)...${NC}"
    docker-compose exec app sh
}

run_artisan() {
    shift
    echo -e "${BLUE}🎨 Ejecutando artisan $@${NC}"
    docker-compose exec app php artisan "$@"
}

run_composer() {
    shift
    echo -e "${BLUE}📦 Ejecutando composer $@${NC}"
    docker-compose exec app composer "$@"
}

run_tests() {
    echo -e "${GREEN}🧪 Ejecutando tests...${NC}"
    docker-compose exec app php artisan test
}

reset_all() {
    echo -e "${RED}⚠️  ADVERTENCIA: Esto eliminará TODO (contenedores, volúmenes, datos)${NC}"
    read -p "¿Estás REALMENTE seguro? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${YELLOW}🗑️  Eliminando todo...${NC}"
        docker-compose down -v
        echo -e "${GREEN}✅ Todo eliminado${NC}"
        echo ""
        echo -e "${BLUE}Para volver a empezar: ./docker-helper.sh start${NC}"
    else
        echo -e "${YELLOW}Cancelado${NC}"
    fi
}

test_render_config() {
    echo -e "${BLUE}🔍 Verificando configuración para Render...${NC}"
    echo ""
    
    # Verificar archivos necesarios
    files=("Dockerfile" ".dockerignore" "render.yaml" "docker/nginx.conf" "docker/entrypoint.sh")
    for file in "${files[@]}"; do
        if [ -f "$file" ]; then
            echo -e "${GREEN}✅${NC} $file"
        else
            echo -e "${RED}❌${NC} $file (falta)"
        fi
    done
    
    echo ""
    echo -e "${BLUE}Construyendo imagen de prueba...${NC}"
    docker build -t sgcs-test:latest . && \
    echo -e "${GREEN}✅ Imagen construida exitosamente${NC}" || \
    echo -e "${RED}❌ Error al construir imagen${NC}"
}

generate_key() {
    echo -e "${GREEN}🔑 Generando APP_KEY...${NC}"
    echo ""
    
    if [ -f ".env" ]; then
        php artisan key:generate
    else
        echo "APP_KEY generada:"
        docker run --rm sgcs:latest php artisan key:generate --show
    fi
}

# Main
check_docker

case "${1:-help}" in
    start)
        start_containers
        ;;
    stop)
        stop_containers
        ;;
    restart)
        restart_containers
        ;;
    logs)
        show_logs
        ;;
    status)
        show_status
        ;;
    build)
        build_image
        ;;
    rebuild)
        rebuild_image
        ;;
    migrate)
        run_migrations
        ;;
    seed)
        run_seeders
        ;;
    fresh)
        fresh_database
        ;;
    cache-clear)
        cache_clear
        ;;
    optimize)
        optimize_app
        ;;
    shell)
        shell_access
        ;;
    artisan)
        run_artisan "$@"
        ;;
    composer)
        run_composer "$@"
        ;;
    test)
        run_tests
        ;;
    reset)
        reset_all
        ;;
    render-test)
        test_render_config
        ;;
    key-generate)
        generate_key
        ;;
    help|--help|-h)
        show_help
        ;;
    *)
        echo -e "${RED}❌ Comando desconocido: $1${NC}"
        echo ""
        show_help
        exit 1
        ;;
esac

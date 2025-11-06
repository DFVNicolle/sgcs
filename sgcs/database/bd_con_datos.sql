-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para sgcs
CREATE DATABASE IF NOT EXISTS `sgcs` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `sgcs`;

-- Volcando estructura para tabla sgcs.accesos
CREATE TABLE IF NOT EXISTS `accesos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` char(36) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `recurso` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `accesos_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `accesos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.accesos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.ajustes_cronograma
CREATE TABLE IF NOT EXISTS `ajustes_cronograma` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proyecto_id` varchar(255) NOT NULL,
  `tipo_ajuste` enum('manual','automatico','solicitud_cambio') NOT NULL DEFAULT 'automatico',
  `estado` enum('propuesto','aprobado','aplicado','rechazado','revertido') NOT NULL DEFAULT 'propuesto',
  `desviaciones_detectadas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`desviaciones_detectadas`)),
  `ruta_critica` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ruta_critica`)),
  `recursos_sobrecargados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`recursos_sobrecargados`)),
  `estrategia` varchar(50) DEFAULT NULL,
  `ajustes_propuestos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ajustes_propuestos`)),
  `ajustes_aplicados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ajustes_aplicados`)),
  `dias_recuperados` int(11) NOT NULL DEFAULT 0,
  `recursos_afectados` int(11) NOT NULL DEFAULT 0,
  `score_solucion` decimal(5,2) DEFAULT NULL,
  `costo_adicional_estimado` decimal(8,2) DEFAULT NULL,
  `aprobado_por` char(36) DEFAULT NULL,
  `aprobado_en` timestamp NULL DEFAULT NULL,
  `motivo_ajuste` text DEFAULT NULL,
  `notas_rechazo` text DEFAULT NULL,
  `creado_por` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ajustes_cronograma_aprobado_por_foreign` (`aprobado_por`),
  KEY `ajustes_cronograma_creado_por_foreign` (`creado_por`),
  KEY `ajustes_cronograma_proyecto_id_estado_index` (`proyecto_id`,`estado`),
  KEY `ajustes_cronograma_created_at_index` (`created_at`),
  CONSTRAINT `ajustes_cronograma_aprobado_por_foreign` FOREIGN KEY (`aprobado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ajustes_cronograma_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ajustes_cronograma_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.ajustes_cronograma: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.auditorias
CREATE TABLE IF NOT EXISTS `auditorias` (
  `id` char(36) NOT NULL,
  `tipo_entidad` varchar(100) DEFAULT NULL,
  `entidad_id` char(36) DEFAULT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `usuario_id` char(36) DEFAULT NULL,
  `detalles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles`)),
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `auditorias_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `auditorias_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.auditorias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.comite_cambios
CREATE TABLE IF NOT EXISTS `comite_cambios` (
  `id` char(36) NOT NULL,
  `proyecto_id` char(36) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `quorum` int(11) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `comite_cambios_proyecto_id_foreign` (`proyecto_id`),
  CONSTRAINT `comite_cambios_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.comite_cambios: ~2 rows (aproximadamente)
INSERT INTO `comite_cambios` (`id`, `proyecto_id`, `nombre`, `quorum`, `creado_en`) VALUES
	('2c38e780-704c-4a57-b92f-b940541a4403', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'Comité de Control de Cambios E-Commerce', 3, '2025-11-05 02:28:32'),
	('eabf219d-3ff1-4ba7-92a0-8b79386fa428', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'Comité de Control de Cambios ERP', 4, '2025-11-05 02:28:32');

-- Volcando estructura para tabla sgcs.commits_repositorio
CREATE TABLE IF NOT EXISTS `commits_repositorio` (
  `id` char(36) NOT NULL,
  `url_repositorio` text NOT NULL,
  `hash_commit` text NOT NULL,
  `ec_id` char(36) DEFAULT NULL,
  `autor` text DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha_commit` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commits_repositorio_ec_id_index` (`ec_id`),
  KEY `commits_repositorio_hash_commit_index` (`hash_commit`(768)),
  CONSTRAINT `commits_repositorio_ec_id_foreign` FOREIGN KEY (`ec_id`) REFERENCES `elementos_configuracion` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.commits_repositorio: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.elementos_configuracion
CREATE TABLE IF NOT EXISTS `elementos_configuracion` (
  `id` char(36) NOT NULL,
  `codigo_ec` varchar(50) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `proyecto_id` char(36) NOT NULL,
  `tipo` enum('DOCUMENTO','CODIGO','SCRIPT_BD','CONFIGURACION','OTRO') NOT NULL DEFAULT 'OTRO',
  `version_actual_id` char(36) DEFAULT NULL,
  `creado_por` char(36) DEFAULT NULL,
  `estado` enum('PENDIENTE','BORRADOR','EN_REVISION','APROBADO','LIBERADO','OBSOLETO') NOT NULL DEFAULT 'PENDIENTE',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `elementos_configuracion_codigo_ec_unique` (`codigo_ec`),
  KEY `elementos_configuracion_proyecto_id_foreign` (`proyecto_id`),
  KEY `elementos_configuracion_creado_por_foreign` (`creado_por`),
  KEY `elementos_configuracion_version_actual_id_foreign` (`version_actual_id`),
  CONSTRAINT `elementos_configuracion_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `elementos_configuracion_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `elementos_configuracion_version_actual_id_foreign` FOREIGN KEY (`version_actual_id`) REFERENCES `versiones_ec` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.elementos_configuracion: ~27 rows (aproximadamente)
INSERT INTO `elementos_configuracion` (`id`, `codigo_ec`, `titulo`, `descripcion`, `proyecto_id`, `tipo`, `version_actual_id`, `creado_por`, `estado`, `creado_en`, `actualizado_en`) VALUES
	('0d9744c0-3c10-4350-812a-0b440efbb825', 'ECOM-DOC-002', 'Sprint Backlog - Sprint 1', 'Planificación del primer sprint con 8 historias de usuario', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'DOCUMENTO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'EN_REVISION', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('1f5193bf-5144-423d-aead-428c599a41ba', 'ERP-CODE-001', 'Repositorio Git - ERP', 'Código fuente con estructura modular por subsistemas', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'CODIGO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('370d6b3b-2922-46ca-a6d0-8e1fd0f40805', 'ERP-DOC-006', 'Suite de Casos de Prueba', '350+ casos de prueba categorizados por módulo y prioridad', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'DOCUMENTO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'EN_REVISION', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('43fad3b8-c3b4-4da1-867d-156a5b4bc38a', 'ECOM-CODE-003', 'Módulo de Gestión de Productos', 'CRUD completo de productos, categorías y variantes', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'CODIGO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('4af6c6a8-0eb9-440a-bfb4-047a26b85cbf', 'ECOM-DOC-003', 'Documentación API REST', 'Especificación OpenAPI 3.0 de endpoints del backend', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'DOCUMENTO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'EN_REVISION', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('4d4cc3a9-e6c6-4e78-af68-87951936fc8b', 'ECOM-CODE-004', 'Módulo de Carrito de Compras', 'Sistema de carrito con persistencia y cálculo de totales', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'CODIGO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'EN_REVISION', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('782e5849-4a51-4078-b3c8-7c33e8bb95cb', 'ECOM-DB-001', 'Esquema de Base de Datos', 'Scripts de migración y modelo de datos del e-commerce', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'SCRIPT_BD', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('7ae31d4e-a880-45b3-bc85-04c26edc268a', 'ERP-DOC-002', 'Plan de Gestión del Proyecto', 'Plan maestro según PMBOK con cronograma, presupuesto y recursos', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'DOCUMENTO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('82f32d84-4bdc-45b9-9ce7-887a0e2e711c', 'ERP-CODE-005', 'Módulo de Compras', 'Gestión de proveedores, órdenes de compra y recepciones', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'CODIGO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('88f38235-d2f1-4d52-b121-49243ab1fc3d', 'ERP-CODE-006', 'Módulo de Ventas', 'Gestión de clientes, cotizaciones, pedidos y facturación', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'CODIGO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'EN_REVISION', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('8a783bd9-f43f-46c4-a295-567fd8e584dc', 'ERP-DB-001', 'Scripts DDL y Migraciones', 'Scripts SQL para creación de esquema y datos iniciales', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'SCRIPT_BD', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('8f8aefb0-94b6-48ed-be5c-2b0c0c531202', 'ERP-CODE-004', 'Módulo de Inventario', 'Control de stock, kardex y valoración de inventarios', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'CODIGO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'LIBERADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('91e81c3b-7dbb-4473-8c0b-d833b8ed67f3', 'ECOM-CODE-006', 'Suite de Tests Automatizados', 'Tests unitarios y de integración con PHPUnit y Jest', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'CODIGO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'EN_REVISION', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('964e584c-3819-440c-9250-4289c0502e3d', 'ECOM-CODE-001', 'Repositorio Git - E-Commerce', 'Código fuente del proyecto con branches main, develop y features', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'CODIGO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('a27b6db5-5061-4b02-8678-4bebb7137c96', 'ERP-DOC-008', 'Plan de Despliegue a Producción', 'Procedimientos de instalación, configuración y rollback', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'DOCUMENTO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'BORRADOR', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('a7daff61-3ba3-4cb3-ab84-d47cacc3c263', 'ECOM-DOC-001', 'Product Backlog', 'Repositorio central de historias de usuario y features del producto', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'DOCUMENTO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('ab28bc0b-5e68-4977-86e0-9a6474e80a7a', 'ERP-DOC-003', 'Documento de Arquitectura de Software (SAD)', 'Arquitectura en capas con patrones MVC, Repository y Factory', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'DOCUMENTO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('b0069233-b5e4-4362-8029-1d7859e5898f', 'ERP-DOC-005', 'Plan Maestro de Pruebas', 'Estrategia de testing con casos de prueba funcionales y de integración', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'DOCUMENTO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('b5d13842-42ab-43fa-9861-9532efe82e7f', 'ERP-DOC-004', 'Modelo Entidad-Relación', 'Diagrama ER normalizado con 85 tablas y relaciones', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'DOCUMENTO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('b816c7e8-d52c-455f-ab55-03f0d0f9077f', 'ERP-CODE-002', 'Módulo de Contabilidad', 'Sistema contable con libro mayor, balance y estados financieros', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'CODIGO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'EN_REVISION', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('c1ed5969-658b-453b-9850-b49c13b26a55', 'ECOM-DOC-004', 'Definition of Done (DoD)', 'Criterios de aceptación y estándares de calidad del equipo', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'DOCUMENTO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('cfe92f02-368d-4ae0-a6c7-cbd4bd2d2105', 'ECOM-CODE-002', 'Módulo de Autenticación JWT', 'Sistema de autenticación con tokens JWT y refresh tokens', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'CODIGO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'LIBERADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('d7e4d0e8-834d-48fd-9e4d-51adc5d0a2ef', 'ECOM-CONFIG-001', 'Pipeline CI/CD', 'Configuración de GitHub Actions para integración y despliegue continuo', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'CONFIGURACION', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('d8bd69e4-c1f2-4eb7-8f41-4c42fc510ef4', 'ECOM-CODE-005', 'Integración Pasarela de Pagos', 'Integración con Stripe y PayPal para procesamiento de pagos', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'CODIGO', NULL, '84ceb2dd-6946-4210-8619-90145ce184ec', 'PENDIENTE', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('dbc57546-fa72-4ec6-a61f-489a5a68f7c2', 'ERP-CODE-003', 'Módulo de Recursos Humanos', 'Gestión de empleados, nómina y evaluaciones de desempeño', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'CODIGO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('de590347-ba5f-436f-b711-955b7074c08d', 'ERP-DOC-007', 'Manual de Usuario Final', 'Guía completa de uso del sistema con capturas de pantalla', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'DOCUMENTO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'PENDIENTE', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('f501dfee-8341-4f2a-a73a-ee2b3ee5b856', 'ERP-DOC-001', 'Especificación de Requisitos del Sistema (SRS)', 'Documento IEEE 830 con requisitos funcionales y no funcionales del ERP', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'DOCUMENTO', NULL, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'APROBADO', '2025-11-05 02:28:32', '2025-11-05 02:28:32');

-- Volcando estructura para tabla sgcs.equipos
CREATE TABLE IF NOT EXISTS `equipos` (
  `id` char(36) NOT NULL,
  `proyecto_id` char(36) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `lider_id` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `equipos_proyecto_id_foreign` (`proyecto_id`),
  KEY `equipos_lider_id_foreign` (`lider_id`),
  CONSTRAINT `equipos_lider_id_foreign` FOREIGN KEY (`lider_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `equipos_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.equipos: ~2 rows (aproximadamente)
INSERT INTO `equipos` (`id`, `proyecto_id`, `nombre`, `lider_id`) VALUES
	('7d79d583-7357-4319-97fe-459236e3e459', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'ERP Implementation Team', 'f666bc2f-4c90-47d6-a5d0-64b315d42e10'),
	('b5a47517-ed55-447c-8772-336f0671b68c', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'E-Commerce Development Team', '30bb99c3-9df3-4097-a9b7-66ee907bc99f');

-- Volcando estructura para tabla sgcs.fases_metodologia
CREATE TABLE IF NOT EXISTS `fases_metodologia` (
  `id_fase` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_metodologia` bigint(20) unsigned NOT NULL,
  `nombre_fase` varchar(100) NOT NULL,
  `orden` int(11) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_fase`),
  KEY `fases_metodologia_id_metodologia_foreign` (`id_metodologia`),
  CONSTRAINT `fases_metodologia_id_metodologia_foreign` FOREIGN KEY (`id_metodologia`) REFERENCES `metodologias` (`id_metodologia`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.fases_metodologia: ~12 rows (aproximadamente)
INSERT INTO `fases_metodologia` (`id_fase`, `id_metodologia`, `nombre_fase`, `orden`, `descripcion`) VALUES
	(1, 1, 'Product Backlog', 1, 'Repositorio de historias de usuario pendientes'),
	(2, 1, 'Sprint Planning', 2, 'Planificación y selección de historias para el sprint'),
	(3, 1, 'In Progress', 3, 'Tareas en desarrollo activo durante el sprint'),
	(4, 1, 'In Review', 4, 'Revisión de código y validación de criterios de aceptación'),
	(5, 1, 'Done', 5, 'Historias completadas y aceptadas'),
	(6, 2, 'Requisitos', 1, 'Recolección y definición de requerimientos del sistema'),
	(7, 2, 'Análisis', 2, 'Análisis detallado de requisitos y especificaciones'),
	(8, 2, 'Diseño', 3, 'Diseño arquitectónico y detallado del sistema'),
	(9, 2, 'Implementación', 4, 'Codificación y desarrollo del sistema'),
	(10, 2, 'Pruebas', 5, 'Testing, validación y verificación del sistema'),
	(11, 2, 'Despliegue', 6, 'Implementación y puesta en producción'),
	(12, 2, 'Mantenimiento', 7, 'Soporte y mantenimiento post-despliegue');

-- Volcando estructura para tabla sgcs.historial_ajustes_tareas
CREATE TABLE IF NOT EXISTS `historial_ajustes_tareas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ajuste_id` bigint(20) unsigned NOT NULL,
  `tarea_id` bigint(20) unsigned NOT NULL,
  `fecha_inicio_anterior` date DEFAULT NULL,
  `fecha_fin_anterior` date DEFAULT NULL,
  `duracion_anterior` int(11) DEFAULT NULL,
  `responsable_anterior` char(36) DEFAULT NULL,
  `horas_estimadas_anterior` decimal(8,2) DEFAULT NULL,
  `fecha_inicio_nueva` date DEFAULT NULL,
  `fecha_fin_nueva` date DEFAULT NULL,
  `duracion_nueva` int(11) DEFAULT NULL,
  `responsable_nuevo` char(36) DEFAULT NULL,
  `horas_estimadas_nueva` decimal(8,2) DEFAULT NULL,
  `tipo_cambio` varchar(50) NOT NULL,
  `impacto_estimado` text DEFAULT NULL,
  `aplicado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historial_ajustes_tareas_responsable_anterior_foreign` (`responsable_anterior`),
  KEY `historial_ajustes_tareas_responsable_nuevo_foreign` (`responsable_nuevo`),
  KEY `historial_ajustes_tareas_ajuste_id_index` (`ajuste_id`),
  KEY `historial_ajustes_tareas_tarea_id_index` (`tarea_id`),
  CONSTRAINT `historial_ajustes_tareas_ajuste_id_foreign` FOREIGN KEY (`ajuste_id`) REFERENCES `ajustes_cronograma` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historial_ajustes_tareas_responsable_anterior_foreign` FOREIGN KEY (`responsable_anterior`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `historial_ajustes_tareas_responsable_nuevo_foreign` FOREIGN KEY (`responsable_nuevo`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `historial_ajustes_tareas_tarea_id_foreign` FOREIGN KEY (`tarea_id`) REFERENCES `tareas_proyecto` (`id_tarea`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.historial_ajustes_tareas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.impedimentos
CREATE TABLE IF NOT EXISTS `impedimentos` (
  `id_impedimento` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_proyecto` char(36) NOT NULL,
  `id_sprint` bigint(20) unsigned DEFAULT NULL,
  `id_usuario_reporta` char(36) NOT NULL,
  `id_usuario_asignado` char(36) DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('baja','media','alta','critica') NOT NULL DEFAULT 'media',
  `estado` enum('abierto','en_progreso','resuelto','cerrado') NOT NULL DEFAULT 'abierto',
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_resolucion` timestamp NULL DEFAULT NULL,
  `solucion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_impedimento`),
  KEY `impedimentos_id_proyecto_foreign` (`id_proyecto`),
  KEY `impedimentos_id_usuario_reporta_foreign` (`id_usuario_reporta`),
  KEY `impedimentos_id_usuario_asignado_foreign` (`id_usuario_asignado`),
  CONSTRAINT `impedimentos_id_proyecto_foreign` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id`),
  CONSTRAINT `impedimentos_id_usuario_asignado_foreign` FOREIGN KEY (`id_usuario_asignado`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `impedimentos_id_usuario_reporta_foreign` FOREIGN KEY (`id_usuario_reporta`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.impedimentos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.items_cambio
CREATE TABLE IF NOT EXISTS `items_cambio` (
  `id` char(36) NOT NULL,
  `solicitud_cambio_id` char(36) NOT NULL,
  `ec_id` char(36) NOT NULL,
  `version_actual_ec_id` char(36) DEFAULT NULL,
  `version_propuesta` varchar(50) DEFAULT NULL,
  `nota` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_cambio_solicitud_cambio_id_foreign` (`solicitud_cambio_id`),
  KEY `items_cambio_ec_id_foreign` (`ec_id`),
  KEY `items_cambio_version_actual_ec_id_foreign` (`version_actual_ec_id`),
  CONSTRAINT `items_cambio_ec_id_foreign` FOREIGN KEY (`ec_id`) REFERENCES `elementos_configuracion` (`id`),
  CONSTRAINT `items_cambio_solicitud_cambio_id_foreign` FOREIGN KEY (`solicitud_cambio_id`) REFERENCES `solicitudes_cambio` (`id`) ON DELETE CASCADE,
  CONSTRAINT `items_cambio_version_actual_ec_id_foreign` FOREIGN KEY (`version_actual_ec_id`) REFERENCES `versiones_ec` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.items_cambio: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.items_liberacion
CREATE TABLE IF NOT EXISTS `items_liberacion` (
  `id` char(36) NOT NULL,
  `liberacion_id` char(36) NOT NULL,
  `ec_id` char(36) DEFAULT NULL,
  `version_ec_id` char(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_liberacion_liberacion_id_foreign` (`liberacion_id`),
  KEY `items_liberacion_ec_id_foreign` (`ec_id`),
  KEY `items_liberacion_version_ec_id_foreign` (`version_ec_id`),
  CONSTRAINT `items_liberacion_ec_id_foreign` FOREIGN KEY (`ec_id`) REFERENCES `elementos_configuracion` (`id`),
  CONSTRAINT `items_liberacion_liberacion_id_foreign` FOREIGN KEY (`liberacion_id`) REFERENCES `liberaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `items_liberacion_version_ec_id_foreign` FOREIGN KEY (`version_ec_id`) REFERENCES `versiones_ec` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.items_liberacion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.liberaciones
CREATE TABLE IF NOT EXISTS `liberaciones` (
  `id` char(36) NOT NULL,
  `proyecto_id` char(36) NOT NULL,
  `etiqueta` varchar(50) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_liberacion` date DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `liberaciones_proyecto_id_foreign` (`proyecto_id`),
  CONSTRAINT `liberaciones_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.liberaciones: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.metodologias
CREATE TABLE IF NOT EXISTS `metodologias` (
  `id_metodologia` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_metodologia`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.metodologias: ~2 rows (aproximadamente)
INSERT INTO `metodologias` (`id_metodologia`, `nombre`, `tipo`, `descripcion`) VALUES
	(1, 'Scrum', 'ágil', 'Framework ágil basado en sprints, roles definidos y entregas iterativas'),
	(2, 'Cascada', 'secuencial', 'Metodología tradicional con fases secuenciales y entregables por etapa');

-- Volcando estructura para tabla sgcs.miembros_ccb
CREATE TABLE IF NOT EXISTS `miembros_ccb` (
  `ccb_id` char(36) NOT NULL,
  `usuario_id` char(36) NOT NULL,
  `rol_en_ccb` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ccb_id`,`usuario_id`),
  KEY `miembros_ccb_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `miembros_ccb_ccb_id_foreign` FOREIGN KEY (`ccb_id`) REFERENCES `comite_cambios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `miembros_ccb_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.miembros_ccb: ~11 rows (aproximadamente)
INSERT INTO `miembros_ccb` (`ccb_id`, `usuario_id`, `rol_en_ccb`) VALUES
	('2c38e780-704c-4a57-b92f-b940541a4403', '23b7c7f0-c7e1-44d3-b4da-8c43308643e5', 'Presidente'),
	('2c38e780-704c-4a57-b92f-b940541a4403', '30bb99c3-9df3-4097-a9b7-66ee907bc99f', 'Scrum Master'),
	('2c38e780-704c-4a57-b92f-b940541a4403', '345b2330-021e-4e07-a283-6e7bfbd3b8de', 'QA Lead'),
	('2c38e780-704c-4a57-b92f-b940541a4403', '84ceb2dd-6946-4210-8619-90145ce184ec', 'Product Owner'),
	('2c38e780-704c-4a57-b92f-b940541a4403', 'b1779bba-0f71-47b2-98f2-580bd5481363', 'Líder Técnico'),
	('eabf219d-3ff1-4ba7-92a0-8b79386fa428', '03d8263c-05e1-4c1e-9058-fea40ecff86d', 'Líder Técnico'),
	('eabf219d-3ff1-4ba7-92a0-8b79386fa428', '3d774bcc-7259-4816-9cb7-551c0e12e245', 'Auditor de Configuración'),
	('eabf219d-3ff1-4ba7-92a0-8b79386fa428', '58e54daa-c48a-465f-ac00-c9374cb0934e', 'Presidente CCB'),
	('eabf219d-3ff1-4ba7-92a0-8b79386fa428', '88cf8fd5-1552-4b57-aeec-56d609e21cb6', 'Arquitecto de Software'),
	('eabf219d-3ff1-4ba7-92a0-8b79386fa428', 'e57092bc-73ce-46a1-9023-97a25e5e9e1a', 'QA Manager'),
	('eabf219d-3ff1-4ba7-92a0-8b79386fa428', 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'Líder de Proyecto');

-- Volcando estructura para tabla sgcs.miembros_equipo
CREATE TABLE IF NOT EXISTS `miembros_equipo` (
  `equipo_id` char(36) NOT NULL,
  `usuario_id` char(36) NOT NULL,
  `rol_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`equipo_id`,`usuario_id`,`rol_id`),
  KEY `miembros_equipo_usuario_id_foreign` (`usuario_id`),
  KEY `miembros_equipo_rol_id_foreign` (`rol_id`),
  CONSTRAINT `miembros_equipo_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`),
  CONSTRAINT `miembros_equipo_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `miembros_equipo_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.miembros_equipo: ~15 rows (aproximadamente)
INSERT INTO `miembros_equipo` (`equipo_id`, `usuario_id`, `rol_id`) VALUES
	('7d79d583-7357-4319-97fe-459236e3e459', '03d8263c-05e1-4c1e-9058-fea40ecff86d', 6),
	('7d79d583-7357-4319-97fe-459236e3e459', '17cfb7ec-e02c-45e2-9e7c-107075bbf955', 7),
	('7d79d583-7357-4319-97fe-459236e3e459', '5e38971d-4865-4fdd-a827-53bcd8a583af', 4),
	('7d79d583-7357-4319-97fe-459236e3e459', '6518a51c-f98d-4cfe-b129-4c57cef801e9', 7),
	('7d79d583-7357-4319-97fe-459236e3e459', '88cf8fd5-1552-4b57-aeec-56d609e21cb6', 10),
	('7d79d583-7357-4319-97fe-459236e3e459', 'e57092bc-73ce-46a1-9023-97a25e5e9e1a', 8),
	('7d79d583-7357-4319-97fe-459236e3e459', 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 3),
	('7d79d583-7357-4319-97fe-459236e3e459', 'f8aa59c5-eb00-4b41-8d51-9b0d4032277b', 9),
	('b5a47517-ed55-447c-8772-336f0671b68c', '30bb99c3-9df3-4097-a9b7-66ee907bc99f', 5),
	('b5a47517-ed55-447c-8772-336f0671b68c', '345b2330-021e-4e07-a283-6e7bfbd3b8de', 8),
	('b5a47517-ed55-447c-8772-336f0671b68c', '5f14f5f2-e2b7-410f-8632-01f94d251555', 9),
	('b5a47517-ed55-447c-8772-336f0671b68c', '84ceb2dd-6946-4210-8619-90145ce184ec', 4),
	('b5a47517-ed55-447c-8772-336f0671b68c', 'b1779bba-0f71-47b2-98f2-580bd5481363', 6),
	('b5a47517-ed55-447c-8772-336f0671b68c', 'c7e1a190-44e5-4d57-9ad2-d1e66e5d1ce7', 7),
	('b5a47517-ed55-447c-8772-336f0671b68c', 'fa7967d4-5b8c-4661-8f2b-b4acba38ec4e', 7);

-- Volcando estructura para tabla sgcs.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.migrations: ~15 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '2024_01_01_000001_create_roles_table', 1),
	(3, '2024_01_01_000002_create_proyectos_table', 1),
	(4, '2024_01_01_000003_create_elementos_configuracion_table', 1),
	(5, '2024_01_01_000004_create_liberaciones_table', 1),
	(6, '2024_01_01_000005_create_solicitudes_cambio_table', 1),
	(7, '2024_01_01_000006_create_auditorias_table', 1),
	(8, '2025_10_16_000001_add_fields_to_tareas_proyecto', 1),
	(9, '2025_10_30_000001_create_ajustes_cronograma_table', 1),
	(10, '2025_10_30_000002_create_historial_ajustes_tareas_table', 1),
	(11, '2025_10_30_000003_add_cronograma_inteligente_fields_to_tareas_proyecto', 1),
	(12, '2025_10_30_174538_add_impedimento_base_to_solicitudes_cambio_table', 1),
	(13, '2025_10_30_174732_create_impedimentos_table', 1),
	(14, '2025_11_04_000001_create_commits_repositorio_table', 1),
	(15, '2025_11_04_000002_add_commit_url_to_tareas_proyecto_table', 1);

-- Volcando estructura para tabla sgcs.notificaciones
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` char(36) NOT NULL,
  `usuario_id` char(36) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `datos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos`)),
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `notificaciones_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `notificaciones_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.notificaciones: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.plantillas_ec
CREATE TABLE IF NOT EXISTS `plantillas_ec` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `metodologia_id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo` enum('DOCUMENTO','CODIGO','SCRIPT_BD','CONFIGURACION','OTRO') NOT NULL DEFAULT 'DOCUMENTO',
  `descripcion` text DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 1,
  `es_recomendado` tinyint(1) NOT NULL DEFAULT 1,
  `tarea_nombre` varchar(255) DEFAULT NULL,
  `tarea_descripcion` text DEFAULT NULL,
  `porcentaje_inicio` decimal(5,2) NOT NULL DEFAULT 0.00,
  `porcentaje_fin` decimal(5,2) NOT NULL DEFAULT 100.00,
  `relaciones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`relaciones`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plantillas_ec_metodologia_id_foreign` (`metodologia_id`),
  CONSTRAINT `plantillas_ec_metodologia_id_foreign` FOREIGN KEY (`metodologia_id`) REFERENCES `metodologias` (`id_metodologia`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.plantillas_ec: ~14 rows (aproximadamente)
INSERT INTO `plantillas_ec` (`id`, `metodologia_id`, `nombre`, `tipo`, `descripcion`, `orden`, `es_recomendado`, `tarea_nombre`, `tarea_descripcion`, `porcentaje_inicio`, `porcentaje_fin`, `relaciones`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Product Backlog', 'DOCUMENTO', 'Repositorio central de historias de usuario y requisitos del producto', 1, 1, 'Crear historias de usuario iniciales', 'Definir las primeras historias de usuario del proyecto basadas en los requisitos del Product Owner', 0.00, 20.00, NULL, '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(2, 1, 'Sprint Backlog', 'DOCUMENTO', 'Planificación y seguimiento de sprints del proyecto', 2, 1, 'Planificar primer sprint', 'Seleccionar historias de usuario del Product Backlog y planificar el primer sprint', 20.00, 40.00, '[{"nombre":"Product Backlog","tipo":"DEPENDE_DE"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(3, 1, 'Repositorio de Código', 'CODIGO', 'Control de versiones del código fuente del proyecto', 3, 1, 'Configurar repositorio Git', 'Inicializar repositorio Git, configurar branches (main, develop) y establecer reglas de commit', 0.00, 10.00, NULL, '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(4, 1, 'Documentación Técnica', 'DOCUMENTO', 'Documentación del sistema, APIs y guías de desarrollo', 4, 1, 'Crear README y documentación inicial', 'Documentar configuración del proyecto, estructura de carpetas y guías para desarrolladores', 40.00, 60.00, '[{"nombre":"Repositorio de C\\u00f3digo","tipo":"REFERENCIA"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(5, 1, 'Definition of Done (DoD)', 'DOCUMENTO', 'Criterios de aceptación y definición de "terminado" para el equipo', 5, 0, 'Definir criterios de DoD del equipo', 'Establecer los criterios que debe cumplir una historia de usuario para considerarse terminada', 0.00, 15.00, '[{"nombre":"Product Backlog","tipo":"REFERENCIA"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(6, 1, 'Retrospectivas', 'DOCUMENTO', 'Registro de retrospectivas y acciones de mejora del equipo', 6, 0, 'Preparar template de retrospectivas', 'Crear formato estándar para documentar retrospectivas de cada sprint', 60.00, 80.00, '[{"nombre":"Sprint Backlog","tipo":"REFERENCIA"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(7, 2, 'Documento de Requisitos (SRS)', 'DOCUMENTO', 'Especificación de requisitos del sistema (Software Requirements Specification)', 1, 1, 'Recopilar y documentar requisitos', 'Realizar entrevistas con stakeholders y documentar todos los requisitos funcionales y no funcionales', 0.00, 15.00, NULL, '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(8, 2, 'Diseño de Arquitectura', 'DOCUMENTO', 'Diseño técnico y arquitectónico del sistema', 2, 1, 'Diseñar arquitectura del sistema', 'Crear diagramas UML, arquitectura de componentes, diseño de base de datos y especificaciones técnicas', 15.00, 30.00, '[{"nombre":"Documento de Requisitos (SRS)","tipo":"DEPENDE_DE"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(9, 2, 'Código Fuente', 'CODIGO', 'Implementación del código fuente del sistema', 3, 1, 'Configurar estructura del proyecto', 'Inicializar proyecto, configurar dependencias y establecer estructura de carpetas según diseño', 30.00, 60.00, '[{"nombre":"Dise\\u00f1o de Arquitectura","tipo":"DEPENDE_DE"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(10, 2, 'Plan de Pruebas', 'DOCUMENTO', 'Estrategia de testing, casos de prueba y matriz de trazabilidad', 4, 1, 'Elaborar plan de pruebas', 'Definir estrategia de testing, diseñar casos de prueba y crear matriz de trazabilidad de requisitos', 50.00, 70.00, '[{"nombre":"Documento de Requisitos (SRS)","tipo":"REFERENCIA"},{"nombre":"C\\u00f3digo Fuente","tipo":"DEPENDE_DE"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(11, 2, 'Manual de Usuario', 'DOCUMENTO', 'Documentación para usuarios finales del sistema', 5, 1, 'Redactar manual de usuario', 'Crear guías de uso, tutoriales y documentación para usuarios finales del sistema', 70.00, 85.00, '[{"nombre":"C\\u00f3digo Fuente","tipo":"REFERENCIA"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(12, 2, 'Scripts de Base de Datos', 'SCRIPT_BD', 'Scripts SQL de creación y migración de base de datos', 6, 1, 'Crear scripts de base de datos', 'Desarrollar scripts DDL/DML para creación de tablas, procedimientos y carga inicial de datos', 25.00, 35.00, '[{"nombre":"Dise\\u00f1o de Arquitectura","tipo":"DERIVADO_DE"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(13, 2, 'Plan de Despliegue', 'DOCUMENTO', 'Estrategia y procedimientos para despliegue en producción', 7, 0, 'Elaborar plan de despliegue', 'Documentar procedimientos de instalación, configuración y despliegue en ambientes productivos', 85.00, 95.00, '[{"nombre":"C\\u00f3digo Fuente","tipo":"REFERENCIA"},{"nombre":"Scripts de Base de Datos","tipo":"REFERENCIA"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	(14, 2, 'Acta de Aceptación', 'DOCUMENTO', 'Documento formal de aceptación del proyecto por el cliente', 8, 0, 'Preparar acta de aceptación', 'Preparar documento formal para firma de aceptación del cliente', 95.00, 100.00, '[{"nombre":"Plan de Pruebas","tipo":"REQUERIDO_POR"},{"nombre":"Manual de Usuario","tipo":"REQUERIDO_POR"}]', '2025-11-05 02:28:29', '2025-11-05 02:28:29');

-- Volcando estructura para tabla sgcs.proyectos
CREATE TABLE IF NOT EXISTS `proyectos` (
  `id` char(36) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_metodologia` bigint(20) unsigned NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `link_repositorio` varchar(255) DEFAULT NULL,
  `creado_por` char(36) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `proyectos_codigo_unique` (`codigo`),
  KEY `proyectos_id_metodologia_foreign` (`id_metodologia`),
  KEY `proyectos_creado_por_foreign` (`creado_por`),
  CONSTRAINT `proyectos_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `proyectos_id_metodologia_foreign` FOREIGN KEY (`id_metodologia`) REFERENCES `metodologias` (`id_metodologia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.proyectos: ~5 rows (aproximadamente)
INSERT INTO `proyectos` (`id`, `codigo`, `nombre`, `descripcion`, `id_metodologia`, `fecha_inicio`, `fecha_fin`, `link_repositorio`, `creado_por`, `creado_en`, `actualizado_en`) VALUES
	('1d9a2752-8c6e-4616-a063-044be53e89ab', 'API-2024', 'API Gateway Empresarial', 'Gateway de APIs para integración de servicios', 1, '2025-09-04', '2026-04-04', 'https://github.com/sgcs-demo/api-2024', '84ceb2dd-6946-4210-8619-90145ce184ec', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('2cb0add2-b488-4e0e-9fcb-898ef0844638', 'MOB-2024', 'App Móvil Bancaria', 'Aplicación móvil para banca digital', 1, '2025-09-04', '2026-04-04', 'https://github.com/sgcs-demo/mob-2024', '30bb99c3-9df3-4097-a9b7-66ee907bc99f', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('bb23c398-7adc-455e-bff0-e8c32db418ba', 'WEB-2024', 'Portal Institucional', 'Sitio web corporativo institucional', 2, '2025-08-04', '2026-05-04', 'https://github.com/sgcs-demo/web-2024', 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('e3e4e061-fe34-4f6f-8931-7add34e1b60e', 'ECOM-2024', 'E-Commerce Platform', 'Plataforma de comercio electrónico con gestión de inventario, carrito de compras, pasarela de pagos y dashboard de analytics. Desarrollado con metodología ágil Scrum.', 1, '2025-09-04', '2026-03-04', 'https://github.com/sgcs-demo/ecommerce-platform', '84ceb2dd-6946-4210-8619-90145ce184ec', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('f1340fe6-1bf4-413e-b910-361fa0acd0dd', 'ERP-2024', 'Sistema ERP Corporativo', 'Sistema integral de planificación de recursos empresariales (ERP) con módulos de contabilidad, RRHH, inventario, compras y ventas. Implementado con metodología tradicional en cascada.', 2, '2025-08-04', '2026-08-04', 'https://github.com/sgcs-demo/erp-corporativo', 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', '2025-11-05 02:28:32', '2025-11-05 02:28:32');

-- Volcando estructura para tabla sgcs.relaciones_ec
CREATE TABLE IF NOT EXISTS `relaciones_ec` (
  `id` char(36) NOT NULL,
  `desde_ec` char(36) NOT NULL,
  `hacia_ec` char(36) NOT NULL,
  `tipo_relacion` enum('DEPENDE_DE','DERIVADO_DE','REFERENCIA','REQUERIDO_POR') NOT NULL,
  `nota` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `relaciones_ec_desde_ec_foreign` (`desde_ec`),
  KEY `relaciones_ec_hacia_ec_foreign` (`hacia_ec`),
  CONSTRAINT `relaciones_ec_desde_ec_foreign` FOREIGN KEY (`desde_ec`) REFERENCES `elementos_configuracion` (`id`) ON DELETE CASCADE,
  CONSTRAINT `relaciones_ec_hacia_ec_foreign` FOREIGN KEY (`hacia_ec`) REFERENCES `elementos_configuracion` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.relaciones_ec: ~22 rows (aproximadamente)
INSERT INTO `relaciones_ec` (`id`, `desde_ec`, `hacia_ec`, `tipo_relacion`, `nota`) VALUES
	('05970ac8-e7dd-49de-a180-c14754866cf5', 'cfe92f02-368d-4ae0-a6c7-cbd4bd2d2105', '964e584c-3819-440c-9250-4289c0502e3d', 'DEPENDE_DE', 'El módulo de autenticación requiere el repositorio de código'),
	('14d2e523-11cd-49bf-bd31-43948d80fcd4', '8f8aefb0-94b6-48ed-be5c-2b0c0c531202', '1f5193bf-5144-423d-aead-428c599a41ba', 'DEPENDE_DE', 'Módulo requiere el repositorio de código base'),
	('16a2e43e-5bd7-468b-84fa-a66430c4af8c', '8f8aefb0-94b6-48ed-be5c-2b0c0c531202', '8a783bd9-f43f-46c4-a295-567fd8e584dc', 'DEPENDE_DE', 'Módulo requiere la base de datos'),
	('19302a0b-955e-4cda-89bc-328f617df9ff', '0d9744c0-3c10-4350-812a-0b440efbb825', 'a7daff61-3ba3-4cb3-ab84-d47cacc3c263', 'DEPENDE_DE', 'El Sprint Backlog se deriva del Product Backlog'),
	('3fb726a8-fba1-4157-b47d-ed9ba809ab88', '82f32d84-4bdc-45b9-9ce7-887a0e2e711c', '1f5193bf-5144-423d-aead-428c599a41ba', 'DEPENDE_DE', 'Módulo requiere el repositorio de código base'),
	('465f1392-4889-4472-a1f4-3addc8b03b10', '88f38235-d2f1-4d52-b121-49243ab1fc3d', '8f8aefb0-94b6-48ed-be5c-2b0c0c531202', 'REQUERIDO_POR', 'Las ventas requieren consultar el inventario disponible'),
	('5c18635a-f0e1-4c60-b691-53308286a835', '43fad3b8-c3b4-4da1-867d-156a5b4bc38a', 'cfe92f02-368d-4ae0-a6c7-cbd4bd2d2105', 'DEPENDE_DE', 'Gestión de productos requiere autenticación de usuarios'),
	('64e93adc-e76a-4578-b021-ff89c8085132', 'b816c7e8-d52c-455f-ab55-03f0d0f9077f', '1f5193bf-5144-423d-aead-428c599a41ba', 'DEPENDE_DE', 'Módulo requiere el repositorio de código base'),
	('6de62674-0597-4125-94ea-ea4bfef7763f', '8a783bd9-f43f-46c4-a295-567fd8e584dc', 'b5d13842-42ab-43fa-9861-9532efe82e7f', 'DERIVADO_DE', 'Los scripts implementan el modelo de datos'),
	('6faea83d-25a7-42dc-b4b0-77e5221c43ae', 'dbc57546-fa72-4ec6-a61f-489a5a68f7c2', '1f5193bf-5144-423d-aead-428c599a41ba', 'DEPENDE_DE', 'Módulo requiere el repositorio de código base'),
	('7052bd0a-82af-4f02-a018-f890cde35c4a', 'b5d13842-42ab-43fa-9861-9532efe82e7f', 'ab28bc0b-5e68-4977-86e0-9a6474e80a7a', 'DERIVADO_DE', 'El modelo de datos se deriva del diseño arquitectónico'),
	('71f2afcc-14e6-40b1-a727-e94e6279e81a', '4d4cc3a9-e6c6-4e78-af68-87951936fc8b', '43fad3b8-c3b4-4da1-867d-156a5b4bc38a', 'DEPENDE_DE', 'El carrito necesita el módulo de productos'),
	('7ca9fee7-5f87-43bb-aea6-69ace1552578', 'ab28bc0b-5e68-4977-86e0-9a6474e80a7a', 'f501dfee-8341-4f2a-a73a-ee2b3ee5b856', 'DERIVADO_DE', 'La arquitectura se deriva de los requisitos del SRS'),
	('8185f108-a8e2-467c-a915-9b6ea5d11a7d', '88f38235-d2f1-4d52-b121-49243ab1fc3d', '1f5193bf-5144-423d-aead-428c599a41ba', 'DEPENDE_DE', 'Módulo requiere el repositorio de código base'),
	('88c12ac3-bb99-4929-9a36-8ce49d48d10a', '370d6b3b-2922-46ca-a6d0-8e1fd0f40805', 'b0069233-b5e4-4362-8029-1d7859e5898f', 'DERIVADO_DE', 'Los casos de prueba implementan el plan maestro de pruebas'),
	('a22a284d-0713-4a92-9fb5-3eaddc5a1f8c', 'b816c7e8-d52c-455f-ab55-03f0d0f9077f', '8a783bd9-f43f-46c4-a295-567fd8e584dc', 'DEPENDE_DE', 'Módulo requiere la base de datos'),
	('b63e8c4e-0147-43a6-9366-dfeb1e5fe837', 'dbc57546-fa72-4ec6-a61f-489a5a68f7c2', '8a783bd9-f43f-46c4-a295-567fd8e584dc', 'DEPENDE_DE', 'Módulo requiere la base de datos'),
	('caaec0ca-2f1e-417a-9a6a-4e95809cb1a4', '88f38235-d2f1-4d52-b121-49243ab1fc3d', '8a783bd9-f43f-46c4-a295-567fd8e584dc', 'DEPENDE_DE', 'Módulo requiere la base de datos'),
	('cedaf331-0715-4f1e-904c-71ad77c32ccc', '82f32d84-4bdc-45b9-9ce7-887a0e2e711c', '8a783bd9-f43f-46c4-a295-567fd8e584dc', 'DEPENDE_DE', 'Módulo requiere la base de datos'),
	('d4425e78-95ef-4cb7-948e-858f2b39cfa9', '91e81c3b-7dbb-4473-8c0b-d833b8ed67f3', '4af6c6a8-0eb9-440a-bfb4-047a26b85cbf', 'REFERENCIA', 'Los tests validan la API documentada'),
	('e4cebebc-aa27-4949-b67b-c280aa74e3a6', 'd8bd69e4-c1f2-4eb7-8f41-4c42fc510ef4', '4d4cc3a9-e6c6-4e78-af68-87951936fc8b', 'DEPENDE_DE', 'Los pagos procesan items del carrito'),
	('f88cf788-6d17-4706-981d-609e4dc41abd', 'cfe92f02-368d-4ae0-a6c7-cbd4bd2d2105', '782e5849-4a51-4078-b3c8-7c33e8bb95cb', 'DEPENDE_DE', 'El módulo de autenticación requiere la base de datos');

-- Volcando estructura para tabla sgcs.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.roles: ~12 rows (aproximadamente)
INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 'Gestor de Configuración', 'Responsable de la gestión de configuración del software (SCM Manager)', NULL, NULL),
	(2, 'Administrador CCB', 'Administrador del Comité de Control de Cambios (CCB Administrator)', NULL, NULL),
	(3, 'Líder de Proyecto', 'Líder técnico y gestor del proyecto (Project Leader)', NULL, NULL),
	(4, 'Product Owner', 'Dueño del producto, define prioridades y requisitos (PO)', NULL, NULL),
	(5, 'Scrum Master', 'Facilitador del proceso Scrum y eliminador de impedimentos', NULL, NULL),
	(6, 'Desarrollador Senior', 'Desarrollador con experiencia y capacidad de revisión de código', NULL, NULL),
	(7, 'Desarrollador', 'Desarrollador de software con acceso a elementos de configuración', NULL, NULL),
	(8, 'Analista QA', 'Analista de aseguramiento de calidad (Quality Assurance)', NULL, NULL),
	(9, 'Tester', 'Responsable de pruebas y validación de software', NULL, NULL),
	(10, 'Arquitecto de Software', 'Diseñador de arquitectura y decisiones técnicas estratégicas', NULL, NULL),
	(11, 'Auditor de Configuración', 'Auditor de cumplimiento de procesos de gestión de configuración', NULL, NULL),
	(12, 'Release Manager', 'Gestor de liberaciones y despliegues a producción', NULL, NULL);

-- Volcando estructura para tabla sgcs.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `usuario_id` char(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_usuario_id_index` (`usuario_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.sessions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.solicitudes_cambio
CREATE TABLE IF NOT EXISTS `solicitudes_cambio` (
  `id` char(36) NOT NULL,
  `proyecto_id` char(36) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion_cambio` text DEFAULT NULL,
  `motivo_cambio` text DEFAULT NULL,
  `prioridad` enum('BAJA','MEDIA','ALTA','CRITICA') NOT NULL DEFAULT 'MEDIA',
  `estado` enum('ABIERTA','EN_REVISION','APROBADA','RECHAZADA','IMPLEMENTADA','CERRADA') NOT NULL DEFAULT 'ABIERTA',
  `solicitante_id` char(36) DEFAULT NULL,
  `origen_cambio` varchar(255) DEFAULT NULL,
  `resumen_impacto` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `solicitudes_cambio_proyecto_id_foreign` (`proyecto_id`),
  KEY `solicitudes_cambio_solicitante_id_foreign` (`solicitante_id`),
  CONSTRAINT `solicitudes_cambio_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`),
  CONSTRAINT `solicitudes_cambio_solicitante_id_foreign` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.solicitudes_cambio: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sgcs.tareas_proyecto
CREATE TABLE IF NOT EXISTS `tareas_proyecto` (
  `id_tarea` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `prioridad` int(11) NOT NULL DEFAULT 3,
  `story_points` int(11) DEFAULT NULL COMMENT 'Solo para Scrum',
  `sprint` varchar(50) DEFAULT NULL COMMENT 'Solo para Scrum',
  `horas_estimadas` decimal(8,2) DEFAULT NULL COMMENT 'Más usado en Cascada',
  `entregable` varchar(255) DEFAULT NULL COMMENT 'Específico de Cascada',
  `criterios_aceptacion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`criterios_aceptacion`)),
  `notas` text DEFAULT NULL,
  `commit_url` text DEFAULT NULL,
  `commit_id` char(36) DEFAULT NULL,
  `creado_por` char(36) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_proyecto` char(36) NOT NULL,
  `id_fase` bigint(20) unsigned NOT NULL,
  `id_ec` char(36) DEFAULT NULL,
  `responsable` char(36) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `duracion_minima` int(11) DEFAULT NULL COMMENT 'Duración mínima posible en días',
  `es_ruta_critica` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indica si la tarea está en la ruta crítica',
  `holgura_dias` int(11) NOT NULL DEFAULT 0 COMMENT 'Días de holgura (slack) - 0 para ruta crítica',
  `fecha_inicio_original` date DEFAULT NULL COMMENT 'Fecha original antes de ajustes automáticos',
  `fecha_fin_original` date DEFAULT NULL COMMENT 'Fecha original antes de ajustes automáticos',
  `puede_paralelizarse` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indica si la tarea puede ejecutarse en paralelo con otras',
  `dependencias` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'IDs de tareas de las que depende esta tarea' CHECK (json_valid(`dependencias`)),
  `progreso_real` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Porcentaje de progreso real (0-100)',
  PRIMARY KEY (`id_tarea`),
  KEY `tareas_proyecto_id_proyecto_foreign` (`id_proyecto`),
  KEY `tareas_proyecto_id_fase_foreign` (`id_fase`),
  KEY `tareas_proyecto_responsable_foreign` (`responsable`),
  KEY `tareas_proyecto_id_ec_foreign` (`id_ec`),
  KEY `tareas_proyecto_creado_por_foreign` (`creado_por`),
  KEY `tareas_proyecto_es_ruta_critica_index` (`es_ruta_critica`),
  KEY `tareas_proyecto_fecha_inicio_fecha_fin_index` (`fecha_inicio`,`fecha_fin`),
  KEY `tareas_proyecto_commit_id_foreign` (`commit_id`),
  CONSTRAINT `tareas_proyecto_commit_id_foreign` FOREIGN KEY (`commit_id`) REFERENCES `commits_repositorio` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tareas_proyecto_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tareas_proyecto_id_ec_foreign` FOREIGN KEY (`id_ec`) REFERENCES `elementos_configuracion` (`id`),
  CONSTRAINT `tareas_proyecto_id_fase_foreign` FOREIGN KEY (`id_fase`) REFERENCES `fases_metodologia` (`id_fase`),
  CONSTRAINT `tareas_proyecto_id_proyecto_foreign` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id`),
  CONSTRAINT `tareas_proyecto_responsable_foreign` FOREIGN KEY (`responsable`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.tareas_proyecto: ~19 rows (aproximadamente)
INSERT INTO `tareas_proyecto` (`id_tarea`, `nombre`, `descripcion`, `prioridad`, `story_points`, `sprint`, `horas_estimadas`, `entregable`, `criterios_aceptacion`, `notas`, `commit_url`, `commit_id`, `creado_por`, `creado_en`, `actualizado_en`, `id_proyecto`, `id_fase`, `id_ec`, `responsable`, `fecha_inicio`, `fecha_fin`, `estado`, `duracion_minima`, `es_ruta_critica`, `holgura_dias`, `fecha_inicio_original`, `fecha_fin_original`, `puede_paralelizarse`, `dependencias`, `progreso_real`) VALUES
	(1, 'US-001: Registro de usuarios', 'Tarea: US-001: Registro de usuarios', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 1, NULL, 'c7e1a190-44e5-4d57-9ad2-d1e66e5d1ce7', '2025-10-16', '2025-10-23', 'Done', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(2, 'US-002: Login con JWT', 'Tarea: US-002: Login con JWT', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 1, NULL, 'b1779bba-0f71-47b2-98f2-580bd5481363', '2025-10-11', '2025-10-18', 'Done', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(3, 'US-003: CRUD de productos', 'Tarea: US-003: CRUD de productos', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 2, NULL, 'fa7967d4-5b8c-4661-8f2b-b4acba38ec4e', '2025-10-21', '2025-11-05', 'Done', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(4, 'US-004: Carrito de compras', 'Tarea: US-004: Carrito de compras', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 3, NULL, 'c7e1a190-44e5-4d57-9ad2-d1e66e5d1ce7', '2025-10-22', '2025-10-31', 'In Progress', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(5, 'US-005: Cálculo de totales', 'Tarea: US-005: Cálculo de totales', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 3, NULL, 'fa7967d4-5b8c-4661-8f2b-b4acba38ec4e', '2025-10-22', '2025-11-05', 'In Progress', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(6, 'US-006: Integración con Stripe', 'Tarea: US-006: Integración con Stripe', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 4, NULL, 'b1779bba-0f71-47b2-98f2-580bd5481363', '2025-10-06', '2025-10-11', 'In Review', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(7, 'US-007: Dashboard de analytics', 'Tarea: US-007: Dashboard de analytics', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'e3e4e061-fe34-4f6f-8931-7add34e1b60e', 2, NULL, NULL, '2025-10-17', '2025-10-22', 'To Do', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(8, 'Recopilación de requisitos funcionales', 'Tarea: Recopilación de requisitos funcionales', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 6, NULL, '5e38971d-4865-4fdd-a827-53bcd8a583af', '2025-09-25', '2025-10-17', 'completada', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(9, 'Análisis de factibilidad técnica', 'Tarea: Análisis de factibilidad técnica', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 7, NULL, '88cf8fd5-1552-4b57-aeec-56d609e21cb6', '2025-09-07', '2025-09-30', 'completada', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(10, 'Definición de casos de uso', 'Tarea: Definición de casos de uso', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 7, NULL, '5e38971d-4865-4fdd-a827-53bcd8a583af', '2025-10-25', '2025-11-23', 'completada', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(11, 'Diseño de arquitectura del sistema', 'Tarea: Diseño de arquitectura del sistema', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 8, NULL, '88cf8fd5-1552-4b57-aeec-56d609e21cb6', '2025-10-05', '2025-11-04', 'completada', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(12, 'Diseño de interfaces de usuario', 'Tarea: Diseño de interfaces de usuario', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 8, NULL, '03d8263c-05e1-4c1e-9058-fea40ecff86d', '2025-10-01', '2025-10-12', 'completada', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(13, 'Desarrollo módulo de Contabilidad', 'Tarea: Desarrollo módulo de Contabilidad', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 9, NULL, '03d8263c-05e1-4c1e-9058-fea40ecff86d', '2025-09-14', '2025-10-04', 'en_progreso', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(14, 'Desarrollo módulo de RRHH', 'Tarea: Desarrollo módulo de RRHH', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 9, NULL, '17cfb7ec-e02c-45e2-9e7c-107075bbf955', '2025-11-02', '2025-11-16', 'en_progreso', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(15, 'Desarrollo módulo de Inventario', 'Tarea: Desarrollo módulo de Inventario', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 9, NULL, '6518a51c-f98d-4cfe-b129-4c57cef801e9', '2025-10-21', '2025-11-19', 'completada', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(16, 'Elaboración de plan de pruebas', 'Tarea: Elaboración de plan de pruebas', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 10, NULL, 'e57092bc-73ce-46a1-9023-97a25e5e9e1a', '2025-10-04', '2025-10-16', 'completada', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(17, 'Ejecución de pruebas unitarias', 'Tarea: Ejecución de pruebas unitarias', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 10, NULL, 'f8aa59c5-eb00-4b41-8d51-9b0d4032277b', '2025-10-12', '2025-10-25', 'en_progreso', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(18, 'Preparación de ambiente de producción', 'Tarea: Preparación de ambiente de producción', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 11, NULL, '03d8263c-05e1-4c1e-9058-fea40ecff86d', '2025-09-21', '2025-10-12', 'pendiente', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00),
	(19, 'Planificación de soporte post-lanzamiento', 'Tarea: Planificación de soporte post-lanzamiento', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 21:28:32', '2025-11-04 21:28:32', 'f1340fe6-1bf4-413e-b910-361fa0acd0dd', 12, NULL, NULL, '2025-10-22', '2025-11-07', 'pendiente', NULL, 0, 0, NULL, NULL, 0, NULL, 0.00);

-- Volcando estructura para tabla sgcs.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` char(36) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `correo_verificado_en` timestamp NULL DEFAULT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `google2fa_secret` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_correo_unique` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.usuarios: ~19 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `correo`, `correo_verificado_en`, `nombre_completo`, `contrasena_hash`, `activo`, `remember_token`, `google2fa_secret`, `creado_en`, `actualizado_en`) VALUES
	('03d8263c-05e1-4c1e-9058-fea40ecff86d', 'dev.senior.cascada@sgcs.com', '2025-11-05 02:28:31', 'Andrés Ortiz', '$2y$12$d1Gy24txIkK5Uk2izDUA3Ot2op/o8ramOl1mtUi99Pwna.5Oj8lDS', 1, NULL, NULL, '2025-11-05 02:28:31', '2025-11-05 02:28:31'),
	('17cfb7ec-e02c-45e2-9e7c-107075bbf955', 'dev1.cascada@sgcs.com', '2025-11-05 02:28:31', 'Sofía Gutiérrez', '$2y$12$EQshx6k3fFAehLHsLvYNFOtQ5S5NOiOs52/3fjaOd0ppGWhFw7dWO', 1, NULL, NULL, '2025-11-05 02:28:31', '2025-11-05 02:28:31'),
	('23b7c7f0-c7e1-44d3-b4da-8c43308643e5', 'scm.manager@sgcs.com', '2025-11-05 02:28:29', 'Carlos Méndez', '$2y$12$aJdlMbrxYL00cfudIGsqs.q.q7wvjA/gqnQaocqKaq8n7yIxvY77S', 1, NULL, NULL, '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	('30bb99c3-9df3-4097-a9b7-66ee907bc99f', 'sm.scrum@sgcs.com', '2025-11-05 02:28:29', 'Roberto Castillo', '$2y$12$5kiP43JlHhFZJUn8RyI7F.xEEljdwv0MqZudGHgH5Hpe3k/hp3VZS', 1, NULL, NULL, '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	('345b2330-021e-4e07-a283-6e7bfbd3b8de', 'qa.scrum@sgcs.com', '2025-11-05 02:28:30', 'Patricia Vega', '$2y$12$vUqnCNvcA2Ojhmdw.vW7T.7Ck0xLG06oHLHHQ1NPoIWDN0tQR9Bbe', 1, NULL, NULL, '2025-11-05 02:28:30', '2025-11-05 02:28:30'),
	('3d774bcc-7259-4816-9cb7-551c0e12e245', 'auditor@sgcs.com', '2025-11-05 02:28:32', 'Lic. Javier Campos', '$2y$12$mPm7I6sd49qFGOJFFgNLHOUIDBLj.G5w6.rHJlk0JKBrNJ4ZkNW2a', 1, NULL, NULL, '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('58e54daa-c48a-465f-ac00-c9374cb0934e', 'ccb.admin@sgcs.com', '2025-11-05 02:28:29', 'Ana Patricia López', '$2y$12$5JryMTDgklE4slIQP5Ps2u2g2IPX4J7NBkXw5c3T7E5Mb/S6eIjTC', 1, NULL, NULL, '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	('5e38971d-4865-4fdd-a827-53bcd8a583af', 'analyst.cascada@sgcs.com', '2025-11-05 02:28:31', 'Laura Martínez', '$2y$12$VZ5o/bEyql6qt0xxbYUr9eYXemwW3PaepjImeGVqcw3zVMXLiAcIm', 1, NULL, NULL, '2025-11-05 02:28:31', '2025-11-05 02:28:31'),
	('5f14f5f2-e2b7-410f-8632-01f94d251555', 'tester.scrum@sgcs.com', '2025-11-05 02:28:30', 'Jorge Ramírez', '$2y$12$5omO43o4Y5DfzQ5ujxf2feUnZYrqe3hggH4jq5ZJSVWlK6JeoQjxu', 1, NULL, NULL, '2025-11-05 02:28:30', '2025-11-05 02:28:30'),
	('6518a51c-f98d-4cfe-b129-4c57cef801e9', 'dev2.cascada@sgcs.com', '2025-11-05 02:28:31', 'Miguel Ángel Torres', '$2y$12$rZgPW1zz8k/xoyC3nxltS.lxFLcCPGp4acFdSrCgPMpr130keL5Ym', 1, NULL, NULL, '2025-11-05 02:28:31', '2025-11-05 02:28:31'),
	('84ceb2dd-6946-4210-8619-90145ce184ec', 'po.scrum@sgcs.com', '2025-11-05 02:28:29', 'María González', '$2y$12$b9ia/rSSw1dG6ydc4jdSBuTpd9CcLIy.klC6JcDndaPSPlOdZVynC', 1, NULL, NULL, '2025-11-05 02:28:29', '2025-11-05 02:28:29'),
	('88cf8fd5-1552-4b57-aeec-56d609e21cb6', 'architect.cascada@sgcs.com', '2025-11-05 02:28:31', 'Dr. Alberto Jiménez', '$2y$12$DUIcyhPru/m6qJnfk8W//O/t7SaU/CtIqeedI7G2OT4jqC/LauZGm', 1, NULL, NULL, '2025-11-05 02:28:31', '2025-11-05 02:28:31'),
	('b1779bba-0f71-47b2-98f2-580bd5481363', 'dev.senior.scrum@sgcs.com', '2025-11-05 02:28:30', 'Luis Hernández', '$2y$12$RlUH.Tn7iE5SQt34JpqvdOGlRcpxShnZaAhI5Eq4cJJ5rdBuqu4ge', 1, NULL, NULL, '2025-11-05 02:28:30', '2025-11-05 02:28:30'),
	('bd68f0db-06c2-4236-9bdf-99369dcb72ef', 'release.manager@sgcs.com', '2025-11-05 02:28:32', 'Elena Vargas', '$2y$12$d1MRxXHSZHrHPkhuDaFJHu.sY8TEARuXJUou4V2MgahHJs9yxT35e', 1, NULL, NULL, '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('c7e1a190-44e5-4d57-9ad2-d1e66e5d1ce7', 'dev1.scrum@sgcs.com', '2025-11-05 02:28:30', 'Carmen Ruiz', '$2y$12$qbo4W8LFDOG8NCektO1zm.vHDXtTejGTJxSmJxAVcmQ/tB1dx3HXG', 1, NULL, NULL, '2025-11-05 02:28:30', '2025-11-05 02:28:30'),
	('e57092bc-73ce-46a1-9023-97a25e5e9e1a', 'qa.cascada@sgcs.com', '2025-11-05 02:28:31', 'Gabriela Rojas', '$2y$12$B/biLep48QulO4/sSRjyKOEZ1G8sLgukgKJ1/6K3ERlN/05MkEtw2', 1, NULL, NULL, '2025-11-05 02:28:31', '2025-11-05 02:28:31'),
	('f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'pm.cascada@sgcs.com', '2025-11-05 02:28:30', 'Fernando Sánchez', '$2y$12$x/h3l9KyAdnO7LS91uPw6.kOVPldmUDaagy7XXFJBcmh0/90dmsN.', 1, NULL, NULL, '2025-11-05 02:28:30', '2025-11-05 02:28:30'),
	('f8aa59c5-eb00-4b41-8d51-9b0d4032277b', 'tester.cascada@sgcs.com', '2025-11-05 02:28:32', 'Ricardo Pérez', '$2y$12$gG5EVbRDyc64OoMnV6HEjeRBa69760wUYw2MizBOq6ZhJEO5RkFaG', 1, NULL, NULL, '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('fa7967d4-5b8c-4661-8f2b-b4acba38ec4e', 'dev2.scrum@sgcs.com', '2025-11-05 02:28:30', 'Diego Morales', '$2y$12$WnYB2RPZh019VZYqXWMFeOW4q7yBgYvv5OL3DiEQ0lOanHgzkpQXu', 1, NULL, NULL, '2025-11-05 02:28:30', '2025-11-05 02:28:30');

-- Volcando estructura para tabla sgcs.usuarios_roles
CREATE TABLE IF NOT EXISTS `usuarios_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` char(36) NOT NULL,
  `rol_id` bigint(20) unsigned NOT NULL,
  `proyecto_id` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarios_roles_usuario_id_foreign` (`usuario_id`),
  KEY `usuarios_roles_rol_id_foreign` (`rol_id`),
  KEY `usuarios_roles_proyecto_id_foreign` (`proyecto_id`),
  CONSTRAINT `usuarios_roles_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`),
  CONSTRAINT `usuarios_roles_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `usuarios_roles_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.usuarios_roles: ~15 rows (aproximadamente)
INSERT INTO `usuarios_roles` (`id`, `usuario_id`, `rol_id`, `proyecto_id`) VALUES
	(1, '30bb99c3-9df3-4097-a9b7-66ee907bc99f', 5, 'e3e4e061-fe34-4f6f-8931-7add34e1b60e'),
	(2, '84ceb2dd-6946-4210-8619-90145ce184ec', 4, 'e3e4e061-fe34-4f6f-8931-7add34e1b60e'),
	(3, 'b1779bba-0f71-47b2-98f2-580bd5481363', 6, 'e3e4e061-fe34-4f6f-8931-7add34e1b60e'),
	(4, 'c7e1a190-44e5-4d57-9ad2-d1e66e5d1ce7', 7, 'e3e4e061-fe34-4f6f-8931-7add34e1b60e'),
	(5, 'fa7967d4-5b8c-4661-8f2b-b4acba38ec4e', 7, 'e3e4e061-fe34-4f6f-8931-7add34e1b60e'),
	(6, '345b2330-021e-4e07-a283-6e7bfbd3b8de', 8, 'e3e4e061-fe34-4f6f-8931-7add34e1b60e'),
	(7, '5f14f5f2-e2b7-410f-8632-01f94d251555', 9, 'e3e4e061-fe34-4f6f-8931-7add34e1b60e'),
	(8, 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 3, 'f1340fe6-1bf4-413e-b910-361fa0acd0dd'),
	(9, '88cf8fd5-1552-4b57-aeec-56d609e21cb6', 10, 'f1340fe6-1bf4-413e-b910-361fa0acd0dd'),
	(10, '5e38971d-4865-4fdd-a827-53bcd8a583af', 4, 'f1340fe6-1bf4-413e-b910-361fa0acd0dd'),
	(11, '03d8263c-05e1-4c1e-9058-fea40ecff86d', 6, 'f1340fe6-1bf4-413e-b910-361fa0acd0dd'),
	(12, '17cfb7ec-e02c-45e2-9e7c-107075bbf955', 7, 'f1340fe6-1bf4-413e-b910-361fa0acd0dd'),
	(13, '6518a51c-f98d-4cfe-b129-4c57cef801e9', 7, 'f1340fe6-1bf4-413e-b910-361fa0acd0dd'),
	(14, 'e57092bc-73ce-46a1-9023-97a25e5e9e1a', 8, 'f1340fe6-1bf4-413e-b910-361fa0acd0dd'),
	(15, 'f8aa59c5-eb00-4b41-8d51-9b0d4032277b', 9, 'f1340fe6-1bf4-413e-b910-361fa0acd0dd');

-- Volcando estructura para tabla sgcs.versiones_ec
CREATE TABLE IF NOT EXISTS `versiones_ec` (
  `id` char(36) NOT NULL,
  `ec_id` char(36) NOT NULL,
  `version` varchar(50) NOT NULL,
  `registro_cambios` text DEFAULT NULL,
  `commit_id` char(36) DEFAULT NULL,
  `metadatos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadatos`)),
  `estado` enum('PENDIENTE','BORRADOR','REVISION','APROBADO','LIBERADO','DEPRECADO') NOT NULL DEFAULT 'PENDIENTE',
  `creado_por` char(36) DEFAULT NULL,
  `aprobado_por` char(36) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `aprobado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `versiones_ec_ec_id_foreign` (`ec_id`),
  KEY `versiones_ec_creado_por_foreign` (`creado_por`),
  KEY `versiones_ec_aprobado_por_foreign` (`aprobado_por`),
  CONSTRAINT `versiones_ec_aprobado_por_foreign` FOREIGN KEY (`aprobado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `versiones_ec_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `versiones_ec_ec_id_foreign` FOREIGN KEY (`ec_id`) REFERENCES `elementos_configuracion` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.versiones_ec: ~3 rows (aproximadamente)
INSERT INTO `versiones_ec` (`id`, `ec_id`, `version`, `registro_cambios`, `commit_id`, `metadatos`, `estado`, `creado_por`, `aprobado_por`, `creado_en`, `aprobado_en`) VALUES
	('26dfe3a1-5a37-425d-9eae-8746f274c07a', 'f501dfee-8341-4f2a-a73a-ee2b3ee5b856', '2.1', 'Revisión aprobada con 125 requisitos funcionales y 38 no funcionales', NULL, NULL, 'APROBADO', 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', 'f666bc2f-4c90-47d6-a5d0-64b315d42e10', '2025-11-05 02:28:32', '2025-09-05 02:28:32'),
	('8541e663-0db2-4819-921a-ad4c71013890', '964e584c-3819-440c-9250-4289c0502e3d', '0.1.0', 'Configuración inicial del proyecto: Laravel 10, Vue 3, Tailwind CSS', NULL, NULL, 'APROBADO', '84ceb2dd-6946-4210-8619-90145ce184ec', '84ceb2dd-6946-4210-8619-90145ce184ec', '2025-11-05 02:28:32', '2025-11-05 02:28:32'),
	('b7842cc0-9aad-49a1-811f-f5fe33943dc1', 'a7daff61-3ba3-4cb3-ab84-d47cacc3c263', '1.0', 'Versión inicial con 45 historias de usuario priorizadas', NULL, NULL, 'APROBADO', '84ceb2dd-6946-4210-8619-90145ce184ec', '84ceb2dd-6946-4210-8619-90145ce184ec', '2025-11-05 02:28:32', '2025-11-05 02:28:32');

-- Volcando estructura para tabla sgcs.votos_ccb
CREATE TABLE IF NOT EXISTS `votos_ccb` (
  `id` char(36) NOT NULL,
  `ccb_id` char(36) NOT NULL,
  `solicitud_cambio_id` char(36) NOT NULL,
  `usuario_id` char(36) NOT NULL,
  `voto` enum('APROBAR','RECHAZAR','ABSTENERSE') NOT NULL,
  `comentario` text DEFAULT NULL,
  `votado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `votos_ccb_ccb_id_foreign` (`ccb_id`),
  KEY `votos_ccb_solicitud_cambio_id_foreign` (`solicitud_cambio_id`),
  KEY `votos_ccb_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `votos_ccb_ccb_id_foreign` FOREIGN KEY (`ccb_id`) REFERENCES `comite_cambios` (`id`),
  CONSTRAINT `votos_ccb_solicitud_cambio_id_foreign` FOREIGN KEY (`solicitud_cambio_id`) REFERENCES `solicitudes_cambio` (`id`),
  CONSTRAINT `votos_ccb_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sgcs.votos_ccb: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

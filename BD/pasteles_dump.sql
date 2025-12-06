-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: pasteles
-- ------------------------------------------------------
-- Server version	8.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ingrediente`
--

DROP TABLE IF EXISTS `ingrediente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingrediente` (
  `ID_ingrediente` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Fecha_ingreso` date DEFAULT NULL,
  `Fecha_Vencimiento` date DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID_ingrediente`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `ingrediente_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingrediente`
--

LOCK TABLES `ingrediente` WRITE;
/*!40000 ALTER TABLE `ingrediente` DISABLE KEYS */;
INSERT INTO `ingrediente` VALUES (1,'Harina de Trigo','Saco de 100 lbs marca Gallo','2023-12-04','2024-12-04',1,'2025-12-04 13:01:40','2025-12-04 15:30:05',NULL,0),(2,'Leche Entera','Leche entera marca Trebolac','2025-12-04','2026-01-04',1,'2025-12-04 16:10:15','2025-12-04 16:10:15',NULL,0),(3,'Huevos','Huevos de granja Santa Lucia','2025-12-04','2026-01-04',1,'2025-12-04 16:11:16','2025-12-04 16:11:16',NULL,0),(4,'Azúcar Glass','Bolsa de 50 lbs','2025-12-01','2026-06-01',2,'2025-12-04 18:20:00','2025-12-04 18:20:00',NULL,0),(5,'Esencia de Vainilla','Botella 1 Litro','2025-11-20','2026-11-20',3,'2025-12-04 18:22:00','2025-12-04 18:22:00',NULL,0);
/*!40000 ALTER TABLE `ingrediente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pastel`
--

DROP TABLE IF EXISTS `pastel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pastel` (
  `ID_pastel` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Preparado_por` int NOT NULL,
  `Fecha_creacion` date DEFAULT NULL,
  `Fecha_Vencimiento` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID_pastel`),
  KEY `Preparado_por` (`Preparado_por`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `pastel_fk_repostero` FOREIGN KEY (`Preparado_por`) REFERENCES `reposteros` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `pastel_fk_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pastel`
--

LOCK TABLES `pastel` WRITE;
/*!40000 ALTER TABLE `pastel` DISABLE KEYS */;
INSERT INTO `pastel` VALUES (1,'Pastel de Chocolate Editado','Nueva descripción rica',1,'2023-12-05','2023-12-15','2025-12-04 16:18:45',1,'2025-12-04 16:53:40',NULL,0),(2,'Tres Leches','Clásico pastel húmedo',2,'2025-12-04','2025-12-10','2025-12-04 18:00:00',1,'2025-12-04 18:00:00',NULL,0),(3,'Red Velvet','Terciopelo rojo con queso crema',3,'2025-12-04','2025-12-12','2025-12-04 18:05:00',2,'2025-12-04 18:05:00',NULL,0),(4,'Cheesecake de Fresa','Base de galleta y queso',4,'2025-12-03','2025-12-08','2025-12-04 18:10:00',1,'2025-12-04 18:10:00',NULL,0),(5,'Mil Hojas','Capas crujientes con arequipe',5,'2025-12-01','2025-12-06','2025-12-04 18:15:00',3,'2025-12-04 18:15:00',NULL,0);
/*!40000 ALTER TABLE `pastel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pastel_ingredientes`
--

DROP TABLE IF EXISTS `pastel_ingredientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pastel_ingredientes` (
  `ID_Pastel_Ingrediente` int NOT NULL AUTO_INCREMENT,
  `ID_pastel` int NOT NULL,
  `ID_ingrediente` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Pastel_Ingrediente`),
  KEY `ID_pastel` (`ID_pastel`),
  KEY `ID_ingrediente` (`ID_ingrediente`),
  CONSTRAINT `pastel_ingredientes_ibfk_1` FOREIGN KEY (`ID_pastel`) REFERENCES `pastel` (`ID_pastel`) ON DELETE CASCADE,
  CONSTRAINT `pastel_ingredientes_ibfk_2` FOREIGN KEY (`ID_ingrediente`) REFERENCES `ingrediente` (`ID_ingrediente`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pastel_ingredientes`
--

LOCK TABLES `pastel_ingredientes` WRITE;
/*!40000 ALTER TABLE `pastel_ingredientes` DISABLE KEYS */;
INSERT INTO `pastel_ingredientes` VALUES (3,1,2,'2025-12-04 16:53:40'),(4,1,3,'2025-12-04 16:53:40'),(5,2,2,'2025-12-04 18:30:00'),(6,2,4,'2025-12-04 18:30:00'),(7,3,1,'2025-12-04 18:31:00'),(8,3,3,'2025-12-04 18:31:00'),(9,4,5,'2025-12-04 18:32:00');
/*!40000 ALTER TABLE `pastel_ingredientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reposteros`
--

DROP TABLE IF EXISTS `reposteros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reposteros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reposteros`
--

LOCK TABLES `reposteros` WRITE;
/*!40000 ALTER TABLE `reposteros` DISABLE KEYS */;
INSERT INTO `reposteros` VALUES (1,'Gordon','Ramsey','2025-12-04 17:00:00','2025-12-04 17:00:00',NULL,0),(2,'Buddy','Valastro','2025-12-04 17:05:00','2025-12-04 17:05:00',NULL,0),(3,'Doña','Lety','2025-12-04 17:10:00','2025-12-04 17:10:00',NULL,0),(4,'Pierre','Hermé','2025-12-04 17:15:00','2025-12-04 17:15:00',NULL,0),(5,'Maru','Botana','2025-12-04 17:20:00','2025-12-04 17:20:00',NULL,0);
/*!40000 ALTER TABLE `reposteros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_avatar` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_provider` enum('local','google') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `google_id` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'juan@example.com','Juan Pérez','$2y$10$HqsOMOVYLOpB/gnCiVDmteNOnr7jFohq45Qnz2xMTd40wnIUKwk06',NULL,NULL,'local','2025-12-03 21:58:31','2025-12-03 21:58:31',NULL,0),(2,'maria@example.com','Maria Lopez','$2y$10$dummyhashExample123456',NULL,NULL,'local','2025-12-04 10:00:00','2025-12-04 10:00:00',NULL,0),(3,'admin@pasteles.com','Admin General','$2y$10$dummyhashExample123456',NULL,NULL,'local','2025-12-04 10:05:00','2025-12-04 10:05:00',NULL,0),(5,'lucia@example.com','Lucia Ventas','$2y$10$dummyhashExample123456',NULL,NULL,'local','2025-12-04 11:30:00','2025-12-04 11:30:00',NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-04 23:40:08

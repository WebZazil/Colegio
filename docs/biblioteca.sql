-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: localhost    Database: biblio_1775418046
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Admin`
--

DROP TABLE IF EXISTS `Admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoAdmin` varchar(2) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `estatus` varchar(10) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apaterno` varchar(100) NOT NULL,
  `amaterno` varchar(100) NOT NULL,
  `claveAdmin` varchar(45) NOT NULL,
  `idContacto` int(11) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Admin_Contacto1_idx` (`idContacto`),
  CONSTRAINT `fk_Admin_Contacto1` FOREIGN KEY (`idContacto`) REFERENCES `Contacto` (`idContacto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Admin`
--

LOCK TABLES `Admin` WRITE;
/*!40000 ALTER TABLE `Admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `Admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Autor`
--

DROP TABLE IF EXISTS `Autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Autor` (
  `idAutor` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(2) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `autores` text NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idAutor`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Autor`
--

LOCK TABLES `Autor` WRITE;
/*!40000 ALTER TABLE `Autor` DISABLE KEYS */;
INSERT INTO `Autor` VALUES (1,'IN','Indefinido','Indefinido','Indefinido','2017-10-20 11:57:21'),(2,'UN','Gabriel','García Marquez ','','2017-05-12 01:51:50'),(3,'UN','Carlos Humberto ','Durand Alcántara','','2017-06-06 11:15:53'),(4,'UN','Paul A. ','Samuelson','','2017-06-14 10:22:16'),(6,'UN','Erich Auerbach','','','2017-06-26 09:58:04'),(7,'UN','Daniel ','Cosio Villegas','','2017-10-17 11:31:00'),(8,'UN','LED C.','GAINOR,','','2017-10-17 01:23:24'),(9,'UN','JUAN BAUTISTA','POQUELIN','','2017-10-17 05:09:17'),(10,'UN','LEON XIII','','','2017-10-18 05:57:07'),(11,'UN','MICHELE FEDERICO','SCIACCA','','2017-10-19 04:52:52'),(12,'UN','JUAN ENRIQUE','PESTALOZZI','','2017-10-19 05:08:23'),(13,'UN','MARIA LUISA','LUCA DE TENA','','2017-10-19 05:18:34'),(14,'UN','FÉLIX LOPE DE ','VEGA CARPIO','','2017-10-19 05:41:43'),(15,'UN','FERNANDO DE','ROJAS','','2017-10-19 05:55:42'),(16,'VR','','','ERNST KLEE, WILLIAM DRESSEN, VOLKER RIESS,','2017-10-19 10:25:11'),(17,'VR','','','JORGE SANS VILA, RAMON MARIA SANS VILA','2017-10-19 10:41:20'),(19,'UN','TIRSO DE','MOLINA','','2017-10-19 11:49:39'),(20,'UN','ENRIQUE','GIL Y CARRASCO','','2017-10-19 11:53:19'),(21,'UN','FERNANDO MA.','PALMES','','2017-10-19 03:10:07'),(22,'UN','REGIS','JOLIVET','','2017-10-19 03:20:21'),(23,'UN','JUAN','BROM','','2017-10-20 10:50:37'),(24,'UN','MARGARITA','NERUTSU','','2017-10-20 11:01:35'),(26,'UN','JUAN','RUIZ DE ALARCON Y MENDOZA','','2017-10-20 11:04:09'),(27,'UN','JULIO','BONATTO','','2017-10-20 11:05:06'),(28,'UN','AURORA M','OCAMPO DE GOMEZ','','2017-10-20 11:06:24'),(29,'UN','EDUARDO','OSPINA','','2017-10-20 11:07:27'),(30,'UN','PALOMA','ROQUE LATORRE','','2017-11-30 01:14:42'),(31,'UN','PEDRO','CHAVEZ CALDERON','','2017-10-23 10:15:10'),(32,'UN','KURT','FRIEBERGER','','2017-10-23 10:23:52'),(33,'UN','JULIAN','CORTES CAVANILLAS','','2017-10-23 10:36:22'),(34,'UN','JACOBO','CAMARIÑAS','','2017-10-23 10:50:37'),(35,'UN','HENRY','RAKOFF','','2017-10-23 10:57:58'),(36,'UN','MICHELANGELO','MURARO','','2017-10-23 11:07:44'),(38,'UN','KARL','ADAM','','2017-10-23 11:19:11'),(39,'UN','BERCEO','GONZALO','','2017-10-23 11:25:46'),(40,'UN','GREGORIO','LOPEZ Y FUENTES','','2017-10-23 11:31:42'),(41,'UN','JOSE DE','ESPRONCEDA DELGADO Y LARA','','2017-10-23 11:36:43'),(42,'UN','JORGE','PAPASOGLI','','2017-10-23 11:44:44'),(43,'UN','MARIO','RODRIGUEZ PINTO','','2017-11-14 11:13:38'),(44,'UN','CARLA GABRIELA','MICHEL ROJAS','','2017-11-19 05:51:06'),(45,'UN','ILIAN','ARTEAGA MENA','','2017-11-19 06:05:01'),(46,'UN','JULIO','VERNE','','2017-11-19 06:15:02'),(47,'UN','BEATRIZ','CALZADA ALVAREZ','','2017-11-19 06:25:35'),(48,'UN','LAURA VIRGINIA','FRIAS LEON','','2017-11-19 06:37:03'),(49,'UN','BLANCA ESTELA','LOPEZ PIÑA','','2017-11-19 06:51:07'),(50,'UN','ARIADNE','RUIZ ARTHUR','','2017-11-19 06:55:35'),(51,'UN','JUDITH','VIORST','','2017-11-20 01:40:03'),(52,'UN','CELESTINO','TESTORE','','2017-11-20 01:52:31'),(53,'UN','ENRIQUE','BAGUE','','2017-11-30 12:56:53'),(54,'UN','F.','DEGALLI','','2018-03-05 11:14:12'),(55,'UN','PEDRO','CALDERON DE LA BARCA','','2018-03-05 11:34:51');
/*!40000 ALTER TABLE `Autor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Clasificacion`
--

DROP TABLE IF EXISTS `Clasificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Clasificacion` (
  `idClasificacion` int(11) NOT NULL AUTO_INCREMENT,
  `clasificacion` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `codigo` varchar(45) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idClasificacion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Clasificacion`
--

LOCK TABLES `Clasificacion` WRITE;
/*!40000 ALTER TABLE `Clasificacion` DISABLE KEYS */;
INSERT INTO `Clasificacion` VALUES (1,'PREADOLESCENTE','MAYORES DE 15 AÑOS','c','2017-05-09 13:42:02'),(2,'ADOLESCENTE','ADOLESCENTES Y ADULTOS','d','2017-05-09 13:44:53'),(3,'ADULTOS','CLASIFICACION B','e','2017-05-09 13:46:29'),(4,'GENERAL','PARA TODO PUBLICO','g','2017-05-09 13:48:26');
/*!40000 ALTER TABLE `Clasificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Coleccion`
--

DROP TABLE IF EXISTS `Coleccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Coleccion` (
  `idColeccion` int(11) NOT NULL AUTO_INCREMENT,
  `coleccion` varchar(200) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idColeccion`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Coleccion`
--

LOCK TABLES `Coleccion` WRITE;
/*!40000 ALTER TABLE `Coleccion` DISABLE KEYS */;
INSERT INTO `Coleccion` VALUES (1,'PELICULAS DVD','SD','DVD','2017-05-09 13:58:15'),(2,'COLECCIÓN ESPECIAL','SD','CE','2017-05-09 13:59:35'),(3,'CONSULTA INFANTIL','SD','CI','2017-05-09 13:59:59'),(4,'INFANTIL','SD','I','2017-05-09 14:00:17'),(5,'ACERVO GENERAL','SD','AC','2017-05-09 14:00:39'),(6,'TESIS','SD','T','2017-05-09 14:01:02'),(7,'FOLLETOS','SD','F','2017-05-09 14:02:10'),(8,'JUVENIL','SD','J','2017-05-09 14:02:28'),(9,'CONSULTA','SD','C','2017-05-09 14:02:59');
/*!40000 ALTER TABLE `Coleccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Contacto`
--

DROP TABLE IF EXISTS `Contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contacto` (
  `idContacto` int(11) NOT NULL AUTO_INCREMENT,
  `telefonos` text NOT NULL,
  `emails` text NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idContacto`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Contacto`
--

LOCK TABLES `Contacto` WRITE;
/*!40000 ALTER TABLE `Contacto` DISABLE KEYS */;
INSERT INTO `Contacto` VALUES (1,'Sin Telefonos','Sin Emails','2017-12-27 14:00:23'),(2,'59324318','yess@gmail.com','2018-01-02 17:19:37'),(3,'59324318','yess@gmail.com','2018-01-02 17:21:00'),(4,'59568932','kareen@gmail.com','2018-01-02 17:22:51'),(5,'5589632156','javier@gmail.com','2018-01-02 17:25:39'),(6,'59856321','leo@gmail.com','2018-01-02 17:27:07'),(7,'45852145','bren@gmail.com','2018-01-02 17:27:43'),(8,'85214569','estrella@gmail.com','2018-01-02 17:28:18'),(9,'36598545','naye@gmail.com','2018-01-02 17:28:54'),(10,'95854572','montse@gmail.com','2018-01-02 17:29:47'),(11,'45152365','ise@gmail.com','2018-01-02 17:32:16'),(12,'45871236','blanca@gmail.com','2018-01-02 17:34:10'),(13,'32569878','viri@gmail.com','2018-01-02 17:34:46'),(14,'85654125','paola@gmail.com','2018-01-02 17:36:25'),(15,'65321478','pame@gmail.com','2018-01-02 17:37:08');
/*!40000 ALTER TABLE `Contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DimensionesEjemplar`
--

DROP TABLE IF EXISTS `DimensionesEjemplar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DimensionesEjemplar` (
  `idDimensionesEjemplar` int(11) NOT NULL AUTO_INCREMENT,
  `alto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `largo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ancho` decimal(10,2) NOT NULL DEFAULT '0.00',
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idDimensionesEjemplar`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DimensionesEjemplar`
--

LOCK TABLES `DimensionesEjemplar` WRITE;
/*!40000 ALTER TABLE `DimensionesEjemplar` DISABLE KEYS */;
INSERT INTO `DimensionesEjemplar` VALUES (1,150.00,100.00,11.00,'2018-01-16 17:40:33'),(2,150.00,100.00,11.00,'2018-01-16 17:42:12'),(3,50.00,100.00,30.00,'2018-02-02 13:05:08'),(4,0.00,175.00,0.00,'2018-02-02 13:10:06'),(5,50.00,80.00,30.00,'2018-02-02 13:15:53'),(6,30.00,100.00,20.00,'2018-02-02 13:19:50'),(7,30.00,80.00,10.00,'2018-02-02 13:21:16'),(8,0.00,320.00,0.00,'2018-02-09 16:45:33'),(9,0.00,320.00,0.00,'2018-02-09 16:47:41'),(10,155.00,0.00,0.00,'2018-02-12 11:35:52'),(11,180.00,0.00,0.00,'2018-02-12 13:10:10'),(12,190.00,0.00,0.00,'2018-02-12 14:27:39'),(13,220.00,0.00,0.00,'2018-02-12 14:41:36'),(14,175.00,0.00,0.00,'2018-02-12 17:09:45'),(15,175.00,0.00,0.00,'2018-02-12 17:29:21'),(16,175.00,0.00,0.00,'2018-02-12 17:36:26'),(17,175.00,0.00,0.00,'2018-02-13 09:23:18'),(18,185.00,0.00,0.00,'2018-02-13 09:39:56'),(19,205.00,0.00,0.00,'2018-02-13 09:48:18'),(20,205.00,0.00,0.00,'2018-02-13 09:49:49'),(21,225.00,0.00,0.00,'2018-02-13 10:08:10'),(22,165.00,0.00,0.00,'2018-02-14 11:06:26'),(23,220.00,0.00,0.00,'2018-02-14 11:13:02'),(24,200.00,0.00,0.00,'2018-02-14 11:24:09'),(25,210.00,0.00,0.00,'2018-02-14 11:32:33'),(26,270.00,0.00,0.00,'2018-02-14 11:41:27'),(27,220.00,0.00,0.00,'2018-02-14 13:04:01'),(28,220.00,0.00,0.00,'2018-02-14 13:04:11'),(29,220.00,0.00,0.00,'2018-02-14 13:09:50'),(30,220.00,0.00,0.00,'2018-02-14 13:10:23'),(31,240.00,0.00,0.00,'2018-02-14 14:45:14'),(32,210.00,0.00,0.00,'2018-02-14 18:02:55'),(33,225.00,0.00,0.00,'2018-02-14 18:10:09'),(34,220.00,0.00,0.00,'2018-02-14 18:15:43'),(35,220.00,0.00,0.00,'2018-02-15 16:36:21'),(36,220.00,0.00,0.00,'2018-02-15 16:36:58'),(37,280.00,0.00,0.00,'2018-02-15 16:52:42'),(38,280.00,0.00,0.00,'2018-02-15 16:52:50'),(39,330.00,0.00,0.00,'2018-02-15 16:59:30'),(40,220.00,0.00,0.00,'2018-02-15 17:05:32'),(41,180.00,0.00,0.00,'2018-02-15 17:56:29'),(42,220.00,0.00,0.00,'2018-02-15 18:02:33'),(43,180.00,0.00,0.00,'2018-02-15 18:06:57'),(44,220.00,0.00,0.00,'2018-02-15 18:30:37'),(45,190.00,0.00,0.00,'2018-02-16 05:43:45'),(46,170.00,0.00,0.00,'2018-02-16 05:55:49');
/*!40000 ALTER TABLE `DimensionesEjemplar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Editorial`
--

DROP TABLE IF EXISTS `Editorial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Editorial` (
  `idEditorial` int(11) NOT NULL AUTO_INCREMENT,
  `editorial` varchar(200) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idEditorial`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Editorial`
--

LOCK TABLES `Editorial` WRITE;
/*!40000 ALTER TABLE `Editorial` DISABLE KEYS */;
INSERT INTO `Editorial` VALUES (1,'Sin Editorial','2017-11-10 12:23:27'),(2,'PEARSON educación','2017-05-15 03:43:45'),(3,'SECRETARIADO \"MARTIN DE PORRES\"','2017-05-22 09:56:43'),(4,'PORRÚA','2017-06-22 10:07:04'),(5,'EL COLEGIO DE MEXICO','2017-10-17 01:13:19'),(6,'AGUILAR','2017-10-17 05:35:31'),(7,'IMPRENTA DEL \"ASILO PATRICIO SANZ\"','2017-10-18 06:00:52'),(8,'PLANETA','2017-10-18 06:50:30'),(9,'SIGUEME','2017-10-19 04:26:35'),(10,'LUIS MIRACLE','2017-10-19 04:53:18'),(11,'LUIS FERNANDEZ G.','2017-10-19 05:08:40'),(12,'IMPRENTA M. NAVARRO','2017-10-19 05:18:54'),(13,'EBRO','2017-10-19 05:41:55'),(14,'BALMES','2017-10-19 03:10:47'),(15,'DESCLEE DE BROUWER','2017-10-19 03:21:43'),(16,'DIACOSMITIKI','2017-10-20 11:13:33'),(18,'DIFUSION','2017-10-20 11:24:16'),(19,'UNAM Centro de Estudios Literarios','2017-10-20 11:29:54'),(20,'PAX','2017-10-20 11:34:30'),(21,'EDIZIONI ECCLESIA','2017-10-20 11:39:22'),(22,'LIBRAIRIE LAROUSSE PARIS','2017-10-20 11:46:50'),(23,'GRIJALBO','2017-10-23 04:27:36'),(24,'GRUPO PATRIA CULTURAL','2017-10-23 10:16:55'),(25,'DINOR','2017-10-23 10:27:31'),(26,'JUVENTUD','2017-10-23 10:39:32'),(27,'FERMA','2017-10-23 10:53:00'),(28,'LIMUSA','2017-10-23 10:58:14'),(29,'SKIRA','2017-10-23 11:08:02'),(30,'HERDER','2017-10-23 11:19:29'),(31,'ESPASA-CALPE','2017-10-23 11:27:09'),(33,'ESPASA-CALPE ARGENTINA','2017-10-23 11:37:25'),(34,'STVDIVM','2017-10-23 11:46:44'),(35,'SUSAETA','2017-10-25 03:16:15'),(36,'LECTORUM','2017-11-13 01:50:14'),(37,'PATRIMONIO NACIONAL','2017-11-13 02:39:50'),(38,'PROGRESO','2017-11-14 11:17:53'),(39,'S.E.P.','2017-11-19 05:53:16'),(40,'EMECE','2017-11-20 01:40:35'),(41,'REVISTA CATOLICA','2017-11-20 01:53:35'),(42,'PRO ARTE','2017-11-30 12:21:50'),(43,'TEIDE','2017-11-30 01:00:51');
/*!40000 ALTER TABLE `Editorial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ejemplar`
--

DROP TABLE IF EXISTS `Ejemplar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Ejemplar` (
  `idEjemplar` int(11) NOT NULL AUTO_INCREMENT,
  `idRecurso` int(11) NOT NULL,
  `idTipoLibro` int(11) NOT NULL,
  `publicado` int(11) NOT NULL,
  `idEditorial` int(11) NOT NULL,
  `paginas` int(11) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `issn` varchar(20) NOT NULL,
  `idIdioma` int(11) NOT NULL,
  `idPais` int(11) NOT NULL,
  `noClasif` varchar(15) NOT NULL,
  `noItem` varchar(10) NOT NULL,
  `noEdicion` varchar(10) NOT NULL,
  `idSeriesEjemplar` int(11) NOT NULL,
  `idDimensionesEjemplar` int(11) NOT NULL,
  `volumen` varchar(10) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idEjemplar`),
  KEY `idPaisPub_idx` (`idPais`),
  KEY `idIdioma_idx` (`idIdioma`),
  KEY `idRecurso_idx` (`idRecurso`),
  KEY `idEditorial` (`idEditorial`),
  KEY `fk_Libro_TipoLibro1_idx` (`idTipoLibro`),
  KEY `fk_Ejemplar_SeriesEjemplar1_idx` (`idSeriesEjemplar`),
  KEY `fk_Ejemplar_DimensionesEjemplar1_idx` (`idDimensionesEjemplar`),
  CONSTRAINT `fk_Ejemplar_DimensionesEjemplar1` FOREIGN KEY (`idDimensionesEjemplar`) REFERENCES `DimensionesEjemplar` (`idDimensionesEjemplar`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ejemplar_SeriesEjemplar1` FOREIGN KEY (`idSeriesEjemplar`) REFERENCES `SeriesEjemplar` (`idSeriesEjemplar`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Libro_TipoLibro1` FOREIGN KEY (`idTipoLibro`) REFERENCES `TipoLibro` (`idTipoLibro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idEditorial` FOREIGN KEY (`idEditorial`) REFERENCES `Editorial` (`idEditorial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idIdioma` FOREIGN KEY (`idIdioma`) REFERENCES `Idioma` (`idIdioma`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idPaisPub` FOREIGN KEY (`idPais`) REFERENCES `Pais` (`idPais`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idRecurso` FOREIGN KEY (`idRecurso`) REFERENCES `Recurso` (`idRecurso`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ejemplar`
--

LOCK TABLES `Ejemplar` WRITE;
/*!40000 ALTER TABLE `Ejemplar` DISABLE KEYS */;
INSERT INTO `Ejemplar` VALUES (1,44,1,1973,1,252,'978-968-6769-11-0','-',1,35,'-','-','2',1,2,'1','2018-01-16 17:42:12'),(2,45,1,157,1,157,'970-666-649-4','',1,35,'--','--','--',1,3,'--','2018-02-02 13:05:08'),(3,46,3,1960,13,218,'970-07-2099-3','',1,17,'860.8','CE89,90','2a ed.',2,4,'--','2018-02-02 13:10:06'),(4,47,1,1997,1,794,'0-03-098238-3','',2,19,'--','--','--',1,5,'--','2018-02-02 13:15:53'),(6,48,1,1995,1,371,'968-23-1966-8','',1,35,'--','--','--',1,7,'1','2018-02-02 13:21:16'),(7,27,1,1910,22,195,'','',3,21,'910.492','H843I','',1,9,'','2018-02-09 16:47:41'),(8,6,1,1962,3,133,'','',1,18,'922.22','G522M','',1,10,'','2018-02-12 11:35:52'),(9,7,1,1961,6,1153,'','',1,18,'F842.4','P700O','',1,11,'','2018-02-12 13:10:10'),(10,8,1,1924,7,200,'','',1,35,'262.91','E551R','2 ED.',1,12,'','2018-02-12 14:27:39'),(11,9,1,1957,10,292,'','',1,18,'270.82','S743I','',3,13,'12','2018-02-12 14:41:36'),(12,11,3,1962,13,135,'','',1,18,'860.8','CE52','6a ed.',2,14,'052','2018-02-12 17:09:45'),(13,13,3,1957,13,136,'','',1,18,'860.8','CE91','',2,15,'091','2018-02-12 17:29:21'),(14,14,3,1959,13,144,'','',1,18,'860.8','CE92','',2,16,'092','2018-02-12 17:36:26'),(15,15,3,1960,13,319,'','',1,18,'860.8','CE93','',2,17,'093','2018-02-13 09:23:18'),(16,16,1,1956,14,125,'','',1,18,'371.5','P525C','2 ED.',1,18,'','2018-02-13 09:39:56'),(18,17,1,1959,15,426,'','',1,4,'100','J842C','3 ED.',1,20,'','2018-02-13 09:49:49'),(19,19,1,1988,23,376,'970-05-0937-0','',1,35,'972','B262E','',1,21,'','2018-02-13 10:08:10'),(20,21,1,1962,16,60,'','',1,24,'F 913.495 12','N369S','',1,22,'','2018-02-14 11:06:26'),(21,22,1,2000,4,207,'970-07-1550-7','',1,35,'M862.1','R677C','22 ED.',4,23,'10','2018-02-14 11:13:02'),(22,23,1,1942,18,135,'','',1,4,'270','B163H','',1,24,'','2018-02-14 11:24:09'),(23,26,1,1954,21,108,'','',4,33,'709.024','S724P','',1,25,'','2018-02-14 11:32:33'),(24,29,1,1963,19,454,'','',1,35,'C M803','G643D','',1,26,'','2018-02-14 11:41:27'),(28,30,1,1952,20,669,'','',1,10,'111.85','O8E','',1,30,'III','2018-02-14 13:10:23'),(29,31,1,2000,24,335,'968-439-200-1','',1,35,'160','CH359L','',1,31,'','2018-02-14 14:45:14'),(30,32,3,1954,25,546,'','',1,18,'808.833','F663S','',5,32,'','2018-02-14 18:02:55'),(31,33,3,1961,26,255,'','',1,18,'923.1','C245A','',6,33,'','2018-02-14 18:10:09'),(32,34,3,1961,26,199,'','',1,18,'923.1','C245M','',6,34,'','2018-02-14 18:15:43'),(34,35,1,1962,27,176,'','',1,18,'922.21','C127J','',1,36,'','2018-02-15 16:36:58'),(36,36,1,1988,28,890,'968-18-0018-4','',1,35,'547','R524Q','',1,38,'','2018-02-15 16:52:50'),(37,37,1,1963,29,236,'','',1,45,'C 708.453','M486T','',1,39,'','2018-02-15 16:59:30'),(38,38,1,1957,30,304,'','',1,18,'232.9','A563J','',1,40,'','2018-02-15 17:05:32'),(39,39,3,1947,31,148,'','',1,18,'82','CA0716','',7,41,'0716','2018-02-15 17:56:29'),(40,40,3,1974,4,125,'','',1,35,'82','SC218','',4,42,'218','2018-02-15 18:02:33'),(41,42,3,1949,33,148,'','',1,4,'82','CA0917','',8,43,' 0917','2018-02-15 18:06:57'),(42,43,1,1957,34,457,'','',1,18,'922.22','P532S','',1,44,'','2018-02-15 18:30:37'),(43,55,3,1961,9,398,'','',1,18,'248.892','P654Q','',9,45,'3','2018-02-16 05:43:45'),(44,56,3,1955,11,299,'','',1,35,'371.3','P567C','',10,46,'XIII','2018-02-16 05:55:49');
/*!40000 ALTER TABLE `Ejemplar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EstatusEjemplar`
--

DROP TABLE IF EXISTS `EstatusEjemplar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EstatusEjemplar` (
  `idEstatusEjemplar` int(11) NOT NULL AUTO_INCREMENT,
  `estatusEjemplar` varchar(45) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `tipoUsuario` varchar(2) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idEstatusEjemplar`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EstatusEjemplar`
--

LOCK TABLES `EstatusEjemplar` WRITE;
/*!40000 ALTER TABLE `EstatusEjemplar` DISABLE KEYS */;
INSERT INTO `EstatusEjemplar` VALUES (1,'ALMACEN','Recurso guardado en almacen, recien adquirido, no disponible en biblioteca','SB','2018-01-16 17:54:44'),(2,'DISPONIBLE','Recurso disponible para prestamo','US','2018-01-16 17:54:44'),(3,'NO DISPONIBLE','Recurso en prestamo, maltratado o fuera de circulación','US','2018-01-16 17:54:44'),(4,'RESERVA','Recurso en reserva, solo para consulta en biblioteca','US','2018-01-16 17:54:44');
/*!40000 ALTER TABLE `EstatusEjemplar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EstatusMulta`
--

DROP TABLE IF EXISTS `EstatusMulta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EstatusMulta` (
  `idEstatusMulta` int(11) NOT NULL AUTO_INCREMENT,
  `estatus` varchar(45) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idEstatusMulta`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EstatusMulta`
--

LOCK TABLES `EstatusMulta` WRITE;
/*!40000 ALTER TABLE `EstatusMulta` DISABLE KEYS */;
INSERT INTO `EstatusMulta` VALUES (1,'ACTIVA','La multa aun no se paga','2018-01-30 14:04:22'),(2,'PAGADA','La multa se ha pagado','2018-01-30 14:04:22');
/*!40000 ALTER TABLE `EstatusMulta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EstatusPrestamo`
--

DROP TABLE IF EXISTS `EstatusPrestamo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EstatusPrestamo` (
  `idEstatusPrestamo` int(11) NOT NULL AUTO_INCREMENT,
  `estatusPrestamo` varchar(200) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idEstatusPrestamo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EstatusPrestamo`
--

LOCK TABLES `EstatusPrestamo` WRITE;
/*!40000 ALTER TABLE `EstatusPrestamo` DISABLE KEYS */;
INSERT INTO `EstatusPrestamo` VALUES (1,'PRESTAMO','Copia en prestamo','2018-01-18 14:00:23'),(2,'RENOVACION','Copia en renovacion','2018-01-18 14:02:14'),(3,'VENCIMIENTO','Copia en vencimiento','2018-01-18 14:06:17'),(4,'ENTREGADO','Prestamo entregado correctamente','2018-01-31 13:37:12');
/*!40000 ALTER TABLE `EstatusPrestamo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Idioma`
--

DROP TABLE IF EXISTS `Idioma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Idioma` (
  `idIdioma` int(11) NOT NULL AUTO_INCREMENT,
  `idioma` varchar(100) NOT NULL,
  `english` varchar(100) NOT NULL,
  `codigo` varchar(3) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idIdioma`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Idioma`
--

LOCK TABLES `Idioma` WRITE;
/*!40000 ALTER TABLE `Idioma` DISABLE KEYS */;
INSERT INTO `Idioma` VALUES (1,'ESPAÑOL','SPANISH','spa','2017-05-15 11:53:20'),(2,'INGLES','ENGLISH','eng','2017-05-15 11:59:46'),(3,'FRANCES','FRENCH','fre','2017-05-15 12:02:47'),(4,'ITALIANO','ITALIAN','ita','2017-05-15 12:03:23');
/*!40000 ALTER TABLE `Idioma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Inventario`
--

DROP TABLE IF EXISTS `Inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inventario` (
  `idInventario` int(11) NOT NULL AUTO_INCREMENT,
  `idEjemplar` int(11) NOT NULL,
  `idEstatusEjemplar` int(11) NOT NULL,
  `copia` int(11) NOT NULL,
  `prestamos` int(11) NOT NULL,
  `codigoBarras` varchar(45) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idInventario`),
  KEY `fk_Inventario_Ejemplar1_idx` (`idEjemplar`),
  KEY `fk_Inventario_EstatusEjemplar1_idx` (`idEstatusEjemplar`),
  CONSTRAINT `fk_Inventario_Ejemplar1` FOREIGN KEY (`idEjemplar`) REFERENCES `Ejemplar` (`idEjemplar`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Inventario_EstatusEjemplar1` FOREIGN KEY (`idEstatusEjemplar`) REFERENCES `EstatusEjemplar` (`idEstatusEjemplar`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Inventario`
--

LOCK TABLES `Inventario` WRITE;
/*!40000 ALTER TABLE `Inventario` DISABLE KEYS */;
INSERT INTO `Inventario` VALUES (3,1,3,1,0,'9789686769111','2018-01-17 13:11:55'),(4,1,2,2,0,'9893145781368','2018-02-02 12:37:03'),(6,1,1,3,0,'9685331254899','2018-02-02 12:57:23'),(7,2,2,1,0,'9891236548562','2018-02-02 13:06:12'),(8,2,3,2,0,'9891145654859','2018-02-02 13:06:52'),(9,2,2,3,0,'9456961435787','2018-02-02 13:07:48'),(10,3,2,1,0,'8961234568976','2018-02-02 13:10:37'),(11,3,2,2,0,'5612375469822','2018-02-02 13:11:11'),(12,3,2,3,0,'9612345630128','2018-02-02 13:11:41'),(13,4,2,1,0,'8961234569720','2018-02-02 13:16:22'),(15,4,2,2,0,'1236984563128','2018-02-02 13:16:50'),(16,4,2,3,0,'5641239617543','2018-02-02 13:17:22'),(18,6,2,1,0,'7561234896218','2018-02-02 13:21:58'),(19,6,2,2,0,'6213497862110','2018-02-02 13:22:36'),(20,6,2,3,0,'4567820316484','2018-02-02 13:23:03'),(21,16,2,1,0,'','2018-02-16 12:05:55');
/*!40000 ALTER TABLE `Inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LibrosTema`
--

DROP TABLE IF EXISTS `LibrosTema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LibrosTema` (
  `idTema` int(11) NOT NULL,
  `idRecurso` int(11) NOT NULL,
  PRIMARY KEY (`idTema`),
  KEY `fk_LibrosTema_Recurso1_idx` (`idRecurso`),
  CONSTRAINT `fk_LibrosTema_Recurso1` FOREIGN KEY (`idRecurso`) REFERENCES `Recurso` (`idRecurso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_LibrosTema_Tema1` FOREIGN KEY (`idTema`) REFERENCES `Tema` (`idTema`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LibrosTema`
--

LOCK TABLES `LibrosTema` WRITE;
/*!40000 ALTER TABLE `LibrosTema` DISABLE KEYS */;
/*!40000 ALTER TABLE `LibrosTema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Material`
--

DROP TABLE IF EXISTS `Material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Material` (
  `idMaterial` int(11) NOT NULL AUTO_INCREMENT,
  `material` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idMaterial`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Material`
--

LOCK TABLES `Material` WRITE;
/*!40000 ALTER TABLE `Material` DISABLE KEYS */;
INSERT INTO `Material` VALUES (1,'LIBRO','SD','BK','2017-05-09 15:14:03'),(2,'MATERIAL VISUAL','SD','VM','2017-05-09 15:16:45');
/*!40000 ALTER TABLE `Material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Multa`
--

DROP TABLE IF EXISTS `Multa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Multa` (
  `idMulta` int(11) NOT NULL AUTO_INCREMENT,
  `idPrestamo` int(11) NOT NULL,
  `idTipoMulta` int(11) NOT NULL,
  `idEstatusMulta` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idMulta`),
  KEY `fk_Multa_Prestamo1_idx` (`idPrestamo`),
  KEY `fk_Multa_TipoMulta1_idx` (`idTipoMulta`),
  KEY `fk_Multa_EstatusMulta1_idx` (`idEstatusMulta`),
  CONSTRAINT `fk_Multa_EstatusMulta1` FOREIGN KEY (`idEstatusMulta`) REFERENCES `EstatusMulta` (`idEstatusMulta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Multa_Prestamo1` FOREIGN KEY (`idPrestamo`) REFERENCES `Prestamo` (`idPrestamo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Multa_TipoMulta1` FOREIGN KEY (`idTipoMulta`) REFERENCES `TipoMulta` (`idTipoMulta`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Multa`
--

LOCK TABLES `Multa` WRITE;
/*!40000 ALTER TABLE `Multa` DISABLE KEYS */;
/*!40000 ALTER TABLE `Multa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Pais`
--

DROP TABLE IF EXISTS `Pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Pais` (
  `idPais` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `english` varchar(100) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idPais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pais`
--

LOCK TABLES `Pais` WRITE;
/*!40000 ALTER TABLE `Pais` DISABLE KEYS */;
INSERT INTO `Pais` VALUES (1,'Sin Pais','--','--','2017-05-16 12:45:29'),(2,'ALEMANIA','GERMANY','gw','2017-05-16 12:45:29'),(3,'ANDORRA','ANDORRA','an','2017-05-16 12:45:29'),(4,'ARGENTINA','ARGENTINA','ag','2017-05-16 12:45:29'),(5,'BELGICA','BELGIUM','be','2017-05-16 12:45:29'),(6,'BRASIL','BRAZIL','bl','2017-05-16 12:45:29'),(7,'CANADÁ','CANADA','xxc','2017-05-16 12:45:29'),(8,'CHILE','CHILE','cl','2017-05-16 12:45:30'),(9,'CHINA','CHINA','cc','2017-05-16 12:45:30'),(10,'COLOMBIA','COLOMBIA','ck','2017-05-16 12:45:30'),(11,'COREA DEL SUR','South Korea','ko','2017-05-16 12:45:30'),(12,'COSTA RICA','COSTA RICA','cr','2017-05-16 12:45:30'),(13,'CUBA','CUBA','cu','2017-05-16 12:45:30'),(14,'DINAMARCA','DENMARK','dk','2017-05-16 12:45:30'),(15,'DUBAI','DUBAI','dub','2017-05-16 12:45:30'),(16,'ECUADOR','ECUADOR','ec','2017-05-16 12:45:30'),(17,'EGIPTO','EGYPT','ua','2017-05-16 12:45:30'),(18,'ESPAÑA','SPAIN','sp','2017-05-16 12:45:30'),(19,'ESTADOS UNIDOS','UNITED STATES','us','2017-05-16 12:45:30'),(20,'FILIPINAS','PHILIPPINES','ph','2017-05-16 12:45:30'),(21,'FRANCIA','FRANCE','fr','2017-05-16 12:45:30'),(22,'FRIBURGO, BRISGOVIA','FRIBURGO, BRISGOVIA','--','2017-05-16 12:45:30'),(23,'GRAN BRETAÑA','UNITED KINGDOM','xxk','2017-05-16 12:45:30'),(24,'GRECIA','GREECE','gr','2017-05-16 12:45:30'),(25,'GUATEMALA','GUATEMALA','gt','2017-05-16 12:45:30'),(26,'HOLANDA','NETHERLANDS','ne','2017-05-16 12:45:30'),(27,'HONDURAS','HONDURAS','ho','2017-05-16 12:45:30'),(28,'HONG KONG','HONG KONG','hk','2017-05-16 12:45:30'),(29,'INDIA','INDIA','ii','2017-05-16 12:45:30'),(30,'INGLATERRA','ENGLAND','enk','2017-05-16 12:45:30'),(31,'IRLANDA','IRELAND','ie','2017-05-16 12:45:31'),(32,'ISRAEL','ISRAEL','is','2017-05-16 12:45:31'),(33,'ITALIA','ITALY','it','2017-05-16 12:45:31'),(34,'JAPON','JAPAN','ja','2017-05-16 12:45:31'),(35,'MEXICO','MEXICO','mx','2017-05-16 12:45:31'),(36,'NICARAGUA','NICARAGUA','nq','2017-05-16 12:45:31'),(37,'NUEVA ZELANDA','NEW ZEALAND','nz','2017-05-16 12:45:31'),(38,'PANAMA','PANAMA','pn','2017-05-16 12:45:31'),(39,'PERU','PERU','pe','2017-05-16 12:45:31'),(40,'PORTUGAL','PORTUGAL','po','2017-05-16 12:45:31'),(41,'PUERTO RICO','PUERTO RICO','pr','2017-05-16 12:45:31'),(42,'RUSIA','RUSSIA','ru','2017-05-16 12:45:31'),(43,'SINGAPUR','SINGAPORE','si','2017-05-16 12:45:31'),(44,'SUECIA','SWEDEN','sw','2017-05-16 12:45:31'),(45,'SUIZA','SWITZERLAND','sz','2017-05-16 12:45:31'),(46,'URUGUAY','URUGUAY','uy','2017-05-16 12:45:31'),(47,'VENEZUELA','VENEZUELA','ve','2017-05-16 12:45:31');
/*!40000 ALTER TABLE `Pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Prestamo`
--

DROP TABLE IF EXISTS `Prestamo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Prestamo` (
  `idPrestamo` int(11) NOT NULL AUTO_INCREMENT,
  `idInventario` int(11) NOT NULL,
  `idEstatusPrestamo` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `fechaPrestamo` datetime NOT NULL,
  `fechaDevolucion` datetime NOT NULL,
  `fechaVencimiento` datetime NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idPrestamo`),
  KEY `fk_Prestamo_Usuario1_idx` (`idUsuario`),
  KEY `fk_Prestamo_Inventario1_idx` (`idInventario`),
  KEY `fk_Prestamo_EstatusPrestamo1_idx` (`idEstatusPrestamo`),
  CONSTRAINT `fk_Prestamo_EstatusPrestamo1` FOREIGN KEY (`idEstatusPrestamo`) REFERENCES `EstatusPrestamo` (`idEstatusPrestamo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Prestamo_Inventario1` FOREIGN KEY (`idInventario`) REFERENCES `Inventario` (`idInventario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Prestamo_Usuario1` FOREIGN KEY (`idUsuario`) REFERENCES `Usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Prestamo`
--

LOCK TABLES `Prestamo` WRITE;
/*!40000 ALTER TABLE `Prestamo` DISABLE KEYS */;
INSERT INTO `Prestamo` VALUES (1,8,4,1,'2018-02-09 00:00:00','2018-02-16 00:00:00','2018-02-09 00:00:00','2018-02-09 01:10:13');
/*!40000 ALTER TABLE `Prestamo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Recurso`
--

DROP TABLE IF EXISTS `Recurso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Recurso` (
  `idRecurso` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `subtitulo` varchar(200) NOT NULL,
  `idAutor` int(11) NOT NULL,
  `idMaterial` int(11) NOT NULL,
  `idColeccion` int(11) NOT NULL,
  `idClasificacion` int(11) NOT NULL,
  `codBarrOrigen` varchar(100) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idRecurso`),
  KEY `idMaterial0_idx` (`idMaterial`),
  KEY `idColeccion_idx` (`idColeccion`),
  KEY `idClasificacion_idx` (`idClasificacion`),
  KEY `fk_Recurso_Autor1_idx` (`idAutor`),
  CONSTRAINT `fk_Recurso_Autor1` FOREIGN KEY (`idAutor`) REFERENCES `Autor` (`idAutor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idClasificacion` FOREIGN KEY (`idClasificacion`) REFERENCES `Clasificacion` (`idClasificacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idColeccion` FOREIGN KEY (`idColeccion`) REFERENCES `Coleccion` (`idColeccion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idMaterial0` FOREIGN KEY (`idMaterial`) REFERENCES `Material` (`idMaterial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Recurso`
--

LOCK TABLES `Recurso` WRITE;
/*!40000 ALTER TABLE `Recurso` DISABLE KEYS */;
INSERT INTO `Recurso` VALUES (1,'Derecho indÃƒÂ­gena','SST',1,1,2,4,'SCB','2017-06-13 15:05:01'),(2,'ECONOMÃƒÂA','SST',1,1,2,4,'SCB','2017-06-14 10:25:10'),(3,'MIMESIS','SST',1,1,1,1,'SCB','2017-07-07 01:12:32'),(6,'SAN MARTIN DE PORRES','SST',8,1,5,4,'SCB','2017-10-17 01:25:19'),(7,'OBRAS COMPLETAS','MOLIERE',9,1,5,4,'SCB','2017-10-17 05:31:40'),(8,'ENCICLICAS \"RERUM NOVARUM\" LEON XIII Y LA CUESTION SOCIAL','SST',1,1,5,4,'SCB','2017-10-18 05:59:43'),(9,'IGLESIA Y MUNDO MODERNO','SST',11,1,5,4,'SCB','2017-10-19 09:57:50'),(11,'PERIBAÑEZ Y EL COMENDADOR DE OCAÑA','SST',14,1,5,4,'SCB','2017-10-19 10:17:53'),(13,'EL CARDENAL DE BELEN','SST',14,1,5,4,'SCB','2017-10-20 09:52:38'),(14,'DON GIL DE LAS CALZAS VERDES','SST',19,1,5,4,'SCB','2017-10-20 10:02:18'),(15,'EL SEÑOR DE BEMBIBRE','SST',20,1,5,4,'SCB','2017-10-20 10:12:09'),(16,'COMO EDUCAR. AUTORIDAD Y DISCIPLINA','SST',21,1,5,4,'SCB','2017-10-20 10:28:34'),(17,'CURSO DE FILOSOFIA','SST',22,1,5,4,'SCB','2017-10-20 10:33:26'),(19,'ESBOZO DE HISTORIA DE MEXICO','DOLORES DUVAL H, COAUT.',23,1,5,4,'SCB','2017-10-20 10:51:41'),(21,'SEAN BIENVENIDOS EN EL PAIS DE LOS DIOSES. LA MEJOR GUIA EN ESPAÑOL DE ATENAS Y DE LA ACROPOLIS','SST',24,1,7,4,'SCB','2017-10-20 11:11:34'),(22,'CUATRO COMEDIAS: LAS PAREDES OYEN. LA VERDAD SOSPECHOSA. LOS PECHOS PRIVILEGIADOS. GANAR AMIGOS','SST',26,1,5,4,'SCB','2017-10-20 11:18:07'),(23,'HISTORIA DE LA IGLESIA','SST',27,1,5,4,'SCB','2017-10-20 11:23:00'),(26,'SAN PIETRO IN VATICANO. NOVA GUIDA PRACTICA ILLUSTRATA','SST',1,1,5,4,'SCB','2017-10-20 11:40:02'),(27,'LA HOLLANDE ILLUSTREE','SST',1,1,2,4,'SCB','2017-10-20 11:45:34'),(29,'Diccionario de Escritos Mexicanos','ERNESTO PRADO VELAZQUEZ, COAUT. PANORAMA DE LA LITERATURA MEXICANA POR MA. DEL CARMEN MILLAN',28,1,9,4,'SCB','2017-10-23 10:01:17'),(30,'ESCRITOS BREVES. VOLUMEN III: ESTETICA Y ARTE','SST',29,1,5,4,'SCB','2017-10-23 10:08:17'),(31,'LOGICA','INTRODUCCION A LA CIENCIA DEL RAZONAMIENTO',31,1,5,4,'SCB','2017-10-23 10:16:11'),(32,'SIMON PEDRO, PESCADOR: NOVELA','KURT FRIEBERGER. VERS. ESPAÑOLA DE J. JESUS GOMEZ DE SEGURA',32,1,8,4,'SCB','2017-10-23 10:26:47'),(33,'ALFONSO XII. EL REY ROMANTICO','SST',33,1,5,4,'SCB','2017-10-23 10:37:58'),(34,'MARIA CRISTINA DE AUSTRIA. MADRE DE ALFONSO XIII','SST',33,1,5,4,'SCB','2017-10-23 10:43:49'),(35,'JUAN XXIII','SST',34,1,5,4,'SCB','2017-10-23 10:51:54'),(36,'QUIMICA ORGANICA FUNDAMENTAL','SST',35,1,5,4,'SCB','2017-10-23 10:59:08'),(37,'LOS TESOROS DE VENECIA. LA BASILICA DE SAN MARCOS. EL TESORO DE SAN MARCOS. EL PALACIO DUCAL. LAS GALERIAS DE LA ACADEMIA. ARQUITECTURA Y MONUMENTOS','ANDRE GRABAR, COAUT.',36,1,9,4,'SCB','2017-10-23 11:11:08'),(38,'JESUCRISTO','SST',38,1,5,4,'SCB','2017-10-23 11:20:51'),(39,'MILAGROS DE NUESTRA SEÑORA','SST',39,1,5,4,'SCB','2017-10-23 11:26:48'),(40,'EL INDIO','SST',40,1,5,4,'SCB','2017-10-23 11:32:46'),(42,'POESIAS LIRICAS. EL ESTUDIANTE DE SALAMANCA','SST',41,1,5,4,'SCB','2017-10-23 11:40:06'),(43,'SANTA TERESA DE AVILA','SST',42,1,5,4,'SCB','2017-10-23 11:45:34'),(44,'El Decameron','SST',1,1,5,3,'9789686769111','2017-12-27 19:00:23'),(45,'El amor y la ReligiÃ³n','SST',1,1,5,4,'9789706666499','2018-01-03 13:26:01'),(46,'LA CELESTINA','SST',15,1,5,4,'9789700720999','2018-01-03 13:26:57'),(47,'EconomÃ­a','SST',1,1,9,4,'9788448123147','2018-01-03 13:29:43'),(48,'Tiempo y NarraciÃ³n','SST',1,1,9,4,'9789682319662','2018-01-03 13:30:26'),(55,'POR QUE ME HICE SACERDOTE. ENCUESTA DIRIGIDA POR JORGE Y RAMON MARIA SANS VILA','',17,1,5,4,'','2018-02-16 05:35:29'),(56,'COMO GERTRUDIS ENSEÑA A SUS HIJOS','',12,1,5,4,'','2018-02-16 05:50:57');
/*!40000 ALTER TABLE `Recurso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SeriesEjemplar`
--

DROP TABLE IF EXISTS `SeriesEjemplar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SeriesEjemplar` (
  `idSeriesEjemplar` int(11) NOT NULL AUTO_INCREMENT,
  `nombreSerie` varchar(200) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idSeriesEjemplar`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SeriesEjemplar`
--

LOCK TABLES `SeriesEjemplar` WRITE;
/*!40000 ALTER TABLE `SeriesEjemplar` DISABLE KEYS */;
INSERT INTO `SeriesEjemplar` VALUES (1,'Sin Serie','Representa una serie vacía','2018-01-11 16:50:44'),(2,'CLASICOS EBRO','CLASICOS','2018-02-12 05:15:00'),(3,'BIBLIOTECA FILOSOFICA','FILOSOFIA','2018-02-12 14:35:00'),(4,'SEPAN CUANTOS','CUENTOS','2018-02-14 11:10:00'),(5,'PRISMA SERIE GRIS, NOVELAS HISTORICAS','NOVELAS','2018-02-14 16:37:00'),(6,'GRANDES BIOGRAFIAS','BIOGRAFIAS','2018-02-14 18:06:00'),(7,'AUSTRAL GRIS','RELIGION','2018-02-15 17:52:00'),(8,'AUSTRAL VIOLETA','RELIGION','2018-02-15 18:05:00'),(9,'HINNENI','RELIGION','2018-02-16 05:40:00'),(10,'ENSAYOS PEDAGOGICOS','PEDAGOGÍA','2018-02-16 05:51:00');
/*!40000 ALTER TABLE `SeriesEjemplar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Subdivision`
--

DROP TABLE IF EXISTS `Subdivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Subdivision` (
  `idSubdivision` int(11) NOT NULL,
  `subdivision` varchar(100) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idSubdivision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Subdivision`
--

LOCK TABLES `Subdivision` WRITE;
/*!40000 ALTER TABLE `Subdivision` DISABLE KEYS */;
/*!40000 ALTER TABLE `Subdivision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SubdivisionesLibro`
--

DROP TABLE IF EXISTS `SubdivisionesLibro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SubdivisionesLibro` (
  `idRecurso` int(11) NOT NULL,
  `idSubdivision` int(11) NOT NULL,
  KEY `fk_SubdivisionesLibro_Recurso1_idx` (`idRecurso`),
  KEY `fk_SubdivisionesLibro_Subdivision1_idx` (`idSubdivision`),
  CONSTRAINT `fk_SubdivisionesLibro_Recurso1` FOREIGN KEY (`idRecurso`) REFERENCES `Recurso` (`idRecurso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_SubdivisionesLibro_Subdivision1` FOREIGN KEY (`idSubdivision`) REFERENCES `Subdivision` (`idSubdivision`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SubdivisionesLibro`
--

LOCK TABLES `SubdivisionesLibro` WRITE;
/*!40000 ALTER TABLE `SubdivisionesLibro` DISABLE KEYS */;
/*!40000 ALTER TABLE `SubdivisionesLibro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tema`
--

DROP TABLE IF EXISTS `Tema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tema` (
  `idTema` int(11) NOT NULL AUTO_INCREMENT,
  `tema` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idTema`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tema`
--

LOCK TABLES `Tema` WRITE;
/*!40000 ALTER TABLE `Tema` DISABLE KEYS */;
INSERT INTO `Tema` VALUES (1,'Sin Tema','--','2017-06-02 10:52:16'),(2,'CUENTOS','01','2017-06-02 10:52:16'),(3,'Anatomía ','A0','2017-06-13 03:30:11'),(4,'ADOLESCENCIA','A1','2017-06-14 09:29:53'),(5,'SANTOS','SA','2017-10-17 03:17:19'),(6,'BIOGRAFIAS','BG','2017-10-17 03:21:18'),(7,'TEATRO FRANCES','TF','2017-10-17 05:12:47'),(8,'LITERATURA FRANCESA','LF','2017-10-17 05:13:17'),(9,'ENCICLICAS','EC','2017-10-18 06:28:23'),(10,'CAMPOS DE CONCENTRACION','CO','2017-10-18 06:52:50'),(11,'GENOCIDIO','GE','2017-10-18 06:53:24'),(12,'HOLOCAUSTO','HO','2017-10-18 06:53:51'),(13,'VOCACION RELIGIOSA','VR','2017-10-19 04:35:34'),(14,'IGLESIA','I0','2017-10-19 05:00:15'),(15,'CIVILIZACION MODERNA','M5','2017-10-19 05:04:26'),(16,'METODOS DE ENSEÑANZA','MZ','2017-10-19 05:10:27'),(17,'BIBLIA','BI','2017-10-19 05:20:25'),(18,'LITERATURA ESPAÑOLA','LS','2017-10-19 05:51:12'),(19,'TEATRO ESPAÑOL','TS','2017-10-19 05:52:42'),(20,'TRAGICOMEDIA ESPAÑOLA','TE','2017-10-19 05:57:22'),(21,'NOVELA ESPAÑOLA','NS','2017-10-19 12:52:00'),(22,'DESCRIPCION Y VIAJES','DV','2017-10-19 03:06:28'),(23,'EDUCACION','EU','2017-10-19 03:14:17'),(24,'DISCIPLINA ESCOLAR','DE','2017-10-19 03:14:57'),(25,'FILOSOFIA','FI','2017-10-19 03:23:03'),(26,'PINTORES ESPAÑOLES','PE','2017-10-25 03:19:05'),(27,'ARTE','AR','2017-10-25 03:19:36'),(28,'HISTORIA DE MEXICO','HX','2017-10-25 05:16:51'),(29,'CIVILIZACION','CZ','2017-10-25 05:39:04'),(30,'TEATRO MEXICANO','TM','2017-10-25 05:46:38'),(31,'VATICANO ','VC','2017-10-25 05:53:10'),(32,'SAN PIETRO IN VATICANO (BASILICA)','SV','2017-10-25 05:57:09'),(33,'IGLESIA CATOLICA','IG','2017-10-25 06:15:29'),(34,'LITERATURA MEXICANA','LM','2017-11-09 09:18:49'),(35,'ENCICLOPEDIAS Y DICCIONARIOS','ED','2017-11-09 09:35:01'),(36,'ESTETICA','ES','2017-11-09 10:00:48'),(37,'LOGICA','LO','2017-11-13 12:31:19'),(38,'NOVELA CONTEMPORANEA','N8','2017-11-13 01:07:13'),(39,'NOVELA PICARESCA','NP','2017-11-13 01:53:43'),(40,'LITERATURA','L0','2017-11-13 01:54:17'),(41,'QUIMICA ORGANICA','QO','2017-11-13 02:35:33'),(42,'ARTE ESPAÑOL','AS','2017-11-13 02:45:42'),(43,'FISIOLOGIA HUMANA','FH','2017-11-14 11:38:29'),(44,'CRISTIANISMO','CT','2017-11-15 09:37:32'),(45,'JESUCRISTO','JE','2017-11-15 09:39:37'),(46,'RELIGION EN LA LITERATURA','RT','2017-11-15 01:58:06'),(47,'POESIA ESPAÑOLA','PA','2017-11-15 01:58:39'),(48,'NOVELA MEXICANA','NM','2017-11-18 05:55:07'),(49,'CIENCIAS SOCIALES','C5','2017-11-19 05:59:49'),(50,'ENSEÑANZA','EZ','2017-11-19 06:09:53'),(51,'METODOS EDUCATIVOS','MV','2017-11-19 06:10:46'),(52,'NOVELA','N0','2017-11-19 06:20:07'),(53,'PROCESO DEL PENSAMIENTO','PW','2017-11-19 06:33:25'),(54,'DESARROLLO EMOCIONAL','DM','2017-11-19 06:59:11'),(55,'PSICOLOGIA','PD','2017-11-20 01:48:17'),(56,'RELACIONES HUMANAS','R5','2017-11-20 01:49:39'),(57,'OPERA','OP','2017-11-30 12:30:04'),(58,'HISTORIA UNIVERSAL','HU','2017-11-30 01:12:44'),(59,'EPIDEMIOLOGIA','E4','2017-11-30 01:21:00');
/*!40000 ALTER TABLE `Tema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TemasSubdivision`
--

DROP TABLE IF EXISTS `TemasSubdivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TemasSubdivision` (
  `idTema` int(11) NOT NULL,
  `idSubdivision` int(11) NOT NULL,
  PRIMARY KEY (`idTema`),
  KEY `idTema_idx` (`idTema`),
  KEY `fk_TemasSubdivision_Subdivision1_idx` (`idSubdivision`),
  CONSTRAINT `fk_TemasSubdivision_Subdivision1` FOREIGN KEY (`idSubdivision`) REFERENCES `Subdivision` (`idSubdivision`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idTema` FOREIGN KEY (`idTema`) REFERENCES `Tema` (`idTema`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TemasSubdivision`
--

LOCK TABLES `TemasSubdivision` WRITE;
/*!40000 ALTER TABLE `TemasSubdivision` DISABLE KEYS */;
/*!40000 ALTER TABLE `TemasSubdivision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TipoLibro`
--

DROP TABLE IF EXISTS `TipoLibro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TipoLibro` (
  `idTipoLibro` int(11) NOT NULL AUTO_INCREMENT,
  `tipoLibro` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idTipoLibro`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TipoLibro`
--

LOCK TABLES `TipoLibro` WRITE;
/*!40000 ALTER TABLE `TipoLibro` DISABLE KEYS */;
INSERT INTO `TipoLibro` VALUES (1,'Ejemplar Simple','Ejemplar unico que no es parte de una enciclopedia o serie','2018-01-10 15:06:07'),(2,'Enciclopedia','Ejemplar que forma parte de una enciclopedia','2018-01-10 15:06:56'),(3,'Serie','Ejemplar que forma parte de una serie','2018-01-10 15:07:16');
/*!40000 ALTER TABLE `TipoLibro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TipoMulta`
--

DROP TABLE IF EXISTS `TipoMulta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TipoMulta` (
  `idTipoMulta` int(11) NOT NULL AUTO_INCREMENT,
  `tipoMulta` varchar(150) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `importeUnitario` decimal(10,2) NOT NULL,
  `unidad` varchar(50) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idTipoMulta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TipoMulta`
--

LOCK TABLES `TipoMulta` WRITE;
/*!40000 ALTER TABLE `TipoMulta` DISABLE KEYS */;
/*!40000 ALTER TABLE `TipoMulta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuario`
--

DROP TABLE IF EXISTS `Usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoUsuario` varchar(2) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `estatus` varchar(10) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apaterno` varchar(100) NOT NULL,
  `amaterno` varchar(100) NOT NULL,
  `claveUsuario` varchar(45) NOT NULL,
  `idContacto` int(11) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Usuario_Contacto1_idx` (`idContacto`),
  CONSTRAINT `fk_Usuario_Contacto1` FOREIGN KEY (`idContacto`) REFERENCES `Contacto` (`idContacto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuario`
--

LOCK TABLES `Usuario` WRITE;
/*!40000 ALTER TABLE `Usuario` DISABLE KEYS */;
INSERT INTO `Usuario` VALUES (1,'AL','yess','12345','ACTIVO','Yessica','Diaz','Martinez','1234567890',3,'2018-01-02 17:21:00'),(2,'DO','Karen','12345','ACTIVO','Karen','Garcia','Lopez','7894561230',4,'2018-01-02 17:22:51'),(3,'DO','Javier','12345','ACTIVO','Javier','Iñiguez','Gonzalez','5612304789',5,'2018-01-02 17:25:39'),(4,'DO','leo','12345','ACTIVO','Leonel','Hernandez','Rivera','7412589630',6,'2018-01-02 17:27:07'),(5,'AL','bren','12345','ACTIVO','Brenda','Neeri','Miranda','6541237890',7,'2018-01-02 17:27:43'),(6,'AL','estrella','12345','ACTIVO','Estrella','Nava','Espinosa','9874563210',8,'2018-01-02 17:28:18'),(7,'AL','naye','12345','ACTIVO','Nayeli','Santos','Gonzalez','962587401',9,'2018-01-02 17:28:54'),(8,'AL','montse','12345','ACTIVO','Montse','Castillo','Gonzalez','9512364780',10,'2018-01-02 17:29:47'),(9,'EX','ise','12345','ACTIVO','Isela','Diaz','Martinez','3574102986',11,'2018-01-02 17:32:16'),(10,'EX','blanca','12345','ACTIVO','Blanca','Canales','Lima','8521470369',12,'2018-01-02 17:34:10'),(11,'EX','viri','12345','ACTIVO','Viridiana','Montoya','Martinez','7621489503',13,'2018-01-02 17:34:46'),(12,'EX','pao','12345','ACTIVO','Paola','Viveros','Rea','7946138520',14,'2018-01-02 17:36:25'),(13,'EX','pame','12345','ACTIVO','Pamela','Contreras','Rios','3614980752',15,'2018-01-02 17:37:08');
/*!40000 ALTER TABLE `Usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-20 18:45:17

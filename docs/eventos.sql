-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: localhost    Database: evento_org_1775418046
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
-- Table structure for table `Asistente`
--

DROP TABLE IF EXISTS `Asistente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Asistente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apaterno` varchar(100) NOT NULL,
  `amaterno` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `clave` varchar(60) NOT NULL,
  `archivo` text NOT NULL,
  `mailist` varchar(1) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Asistente`
--

LOCK TABLES `Asistente` WRITE;
/*!40000 ALTER TABLE `Asistente` DISABLE KEYS */;
INSERT INTO `Asistente` VALUES (1,'Dalia Asucena','Martínez','Martínez','giovanni.rodriguez@zazil.net','4a53927937a72c5b02bc63398debb6be','/var/www/html/colegio/public/qr/modules/evento/1775418046/evts/usr/4a53927937a72c5b02bc63398debb6be.png','S','2017-11-28 15:27:19'),(2,'Ana Carolina','MARTINEZ','MARTINEZ','ana.martinez@zazil.net','94daab25fdbd17ffac9c1b18fe0ed06c','/var/www/html/colegio/public/qr/modules/evento/1775418046/evts/usr/94daab25fdbd17ffac9c1b18fe0ed06c.png','S','2017-11-28 15:37:59'),(3,'ANA','MARTINEZ','MARTINEZ','cangelinib@sagradocorazonmexico.edu.mx','39b8b02aa54021e92a9c4a254c274643','/var/www/html/colegio/public/qr/modules/evento/1775418046/evts/usr/39b8b02aa54021e92a9c4a254c274643.png','S','2018-02-16 16:00:49'),(4,'ana','mart','mat','ana.martinez@zazil.net','2134186517620d770ef0b5c7aacf4af3','/var/www/html/colegio/public/qr/modules/evento/1775418046/evts/usr/2134186517620d770ef0b5c7aacf4af3.png','S','2018-02-16 16:08:03'),(5,'claudia','angelini','2','cangelinib@sagradocorazonmexico.edu.mx','d5caf53daae76929e3a4cfdcbec0bde0','/var/www/html/colegio/public/qr/modules/evento/1775418046/evts/usr/d5caf53daae76929e3a4cfdcbec0bde0.png','S','2018-02-16 16:09:01'),(6,'dalia','martinez','mart','dalia.martinez@zazil.net','78ece0b5ab31b8463b14af9f00e58981','/var/www/html/colegio/public/qr/modules/evento/1775418046/evts/usr/78ece0b5ab31b8463b14af9f00e58981.png','S','2018-02-16 16:09:23'),(7,'claudia','angelini','3','claan11@hotmail.com','161dc871045dd06e5502899dded46edc','/var/www/html/colegio/public/qr/modules/evento/1775418046/evts/usr/161dc871045dd06e5502899dded46edc.png','S','2018-02-16 16:10:19'),(8,'Dalia','Martinez','Martinez','dalia.martinez@zazil.net','ed704d232635a9bddc5154794f265776','ed704d232635a9bddc5154794f265776.png','S','2018-03-09 08:07:30'),(9,'Hector','Rodriguez','Ramos','giovanni.rodriguez@zazil.net','4994ea48e69f10e2b9ea0e4a9f548aca','4994ea48e69f10e2b9ea0e4a9f548aca.png','S','2018-03-09 08:08:56');
/*!40000 ALTER TABLE `Asistente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AsistentesConfirmados`
--

DROP TABLE IF EXISTS `AsistentesConfirmados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AsistentesConfirmados` (
  `idEvento` int(11) NOT NULL,
  `idAsistente` int(11) NOT NULL,
  `entrada` datetime NOT NULL,
  PRIMARY KEY (`idEvento`,`idAsistente`),
  KEY `fk_AsistentesConfirmados_Asistente1_idx` (`idAsistente`),
  CONSTRAINT `fk_AsistentesConfirmados_Asistente1` FOREIGN KEY (`idAsistente`) REFERENCES `Asistente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_AsistentesConfirmados_Evento1` FOREIGN KEY (`idEvento`) REFERENCES `Evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AsistentesConfirmados`
--

LOCK TABLES `AsistentesConfirmados` WRITE;
/*!40000 ALTER TABLE `AsistentesConfirmados` DISABLE KEYS */;
INSERT INTO `AsistentesConfirmados` VALUES (1,1,'2017-11-28 15:28:12');
/*!40000 ALTER TABLE `AsistentesConfirmados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AsistentesEvento`
--

DROP TABLE IF EXISTS `AsistentesEvento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AsistentesEvento` (
  `idEvento` int(11) NOT NULL,
  `idAsistente` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `creacion` text NOT NULL,
  PRIMARY KEY (`idEvento`,`idAsistente`),
  KEY `fk_AsistentesEvento_Asistente1_idx` (`idAsistente`),
  CONSTRAINT `fk_AsistentesEvento_Asistente1` FOREIGN KEY (`idAsistente`) REFERENCES `Asistente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_AsistentesEvento_Evento1` FOREIGN KEY (`idEvento`) REFERENCES `Evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AsistentesEvento`
--

LOCK TABLES `AsistentesEvento` WRITE;
/*!40000 ALTER TABLE `AsistentesEvento` DISABLE KEYS */;
INSERT INTO `AsistentesEvento` VALUES (1,1,'4a53927937a72c5b02bc63398debb6be','2017-11-28 15:27:19'),(1,2,'94daab25fdbd17ffac9c1b18fe0ed06c','2017-11-28 15:37:59'),(1,4,'2134186517620d770ef0b5c7aacf4af3','2018-02-16 16:08:03'),(1,5,'d5caf53daae76929e3a4cfdcbec0bde0','2018-02-16 16:09:01'),(1,6,'78ece0b5ab31b8463b14af9f00e58981','2018-02-16 16:09:23'),(1,7,'161dc871045dd06e5502899dded46edc','2018-02-16 16:10:19'),(2,3,'39b8b02aa54021e92a9c4a254c274643','2018-02-16 16:00:50'),(4,8,'ed704d232635a9bddc5154794f265776','2018-03-09 08:07:30'),(4,9,'4994ea48e69f10e2b9ea0e4a9f548aca','2018-03-09 08:08:56');
/*!40000 ALTER TABLE `AsistentesEvento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Evento`
--

DROP TABLE IF EXISTS `Evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `feInicio` datetime NOT NULL,
  `feTermino` datetime NOT NULL,
  `creacion` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Evento`
--

LOCK TABLES `Evento` WRITE;
/*!40000 ALTER TABLE `Evento` DISABLE KEYS */;
INSERT INTO `Evento` VALUES (1,'Evento Uno','Cafeteria del Centro','2017-11-28 00:00:00','2017-11-29 00:00:00','2017-11-28 15:26:53'),(2,'Comida fin de año','Cafeteria','2017-12-22 00:00:00','2017-12-22 00:00:00','2017-11-28 15:37:24'),(3,'1','1','2018-02-16 00:00:00','2018-02-16 00:00:00','2018-02-16 16:07:31'),(4,'INTERSAC','CSC MEXICO','2018-04-26 00:00:00','2018-04-28 00:00:00','2018-03-09 08:02:27');
/*!40000 ALTER TABLE `Evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OrganizacionEvento`
--

DROP TABLE IF EXISTS `OrganizacionEvento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrganizacionEvento` (
  `idEvento` int(11) NOT NULL,
  `idsOrganizadores` text NOT NULL,
  `idsStaff` text NOT NULL,
  PRIMARY KEY (`idEvento`),
  CONSTRAINT `fk_OrganizadoresEvento_Evento1` FOREIGN KEY (`idEvento`) REFERENCES `Evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrganizacionEvento`
--

LOCK TABLES `OrganizacionEvento` WRITE;
/*!40000 ALTER TABLE `OrganizacionEvento` DISABLE KEYS */;
/*!40000 ALTER TABLE `OrganizacionEvento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Organizador`
--

DROP TABLE IF EXISTS `Organizador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Organizador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apaterno` varchar(100) NOT NULL,
  `amaterno` varchar(100) NOT NULL,
  `responsabilidad` text NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Organizador`
--

LOCK TABLES `Organizador` WRITE;
/*!40000 ALTER TABLE `Organizador` DISABLE KEYS */;
/*!40000 ALTER TABLE `Organizador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Staff`
--

DROP TABLE IF EXISTS `Staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apaterno` varchar(100) NOT NULL,
  `amaterno` varchar(100) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Staff`
--

LOCK TABLES `Staff` WRITE;
/*!40000 ALTER TABLE `Staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `Staff` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-20 18:45:53

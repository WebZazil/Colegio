-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: localhost    Database: z_base
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
-- Table structure for table `Modulo`
--

DROP TABLE IF EXISTS `Modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Modulo` (
  `idModulo` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(200) NOT NULL,
  `clave` varchar(20) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idModulo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Modulo`
--

LOCK TABLES `Modulo` WRITE;
/*!40000 ALTER TABLE `Modulo` DISABLE KEYS */;
INSERT INTO `Modulo` VALUES (1,'biblioteca','MOD_BIBLIOTECA','2016-11-29 20:54:17'),(2,'encuesta','MOD_ENCUESTA','2016-11-29 20:54:17'),(3,'evento','MOD_EVENTO','2016-11-29 20:54:17'),(4,'soporte','MOD_SOPORTE','2016-11-29 20:54:17');
/*!40000 ALTER TABLE `Modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ModuloOrganizacion`
--

DROP TABLE IF EXISTS `ModuloOrganizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ModuloOrganizacion` (
  `idOrganizacion` int(11) NOT NULL,
  `idModulo` int(11) NOT NULL,
  `activo` varchar(1) NOT NULL,
  `pdf_dir` text NOT NULL,
  `image_dir` text NOT NULL,
  `qr_dir` text NOT NULL,
  `file_dir` text NOT NULL,
  PRIMARY KEY (`idOrganizacion`,`idModulo`),
  KEY `fk_ModuloOrganizacion_Organizacion1_idx` (`idOrganizacion`),
  KEY `fk_ModuloOrganizacion_Modulo1` (`idModulo`),
  CONSTRAINT `fk_ModuloOrganizacion_Modulo1` FOREIGN KEY (`idModulo`) REFERENCES `Modulo` (`idModulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ModuloOrganizacion_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`idOrganizacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ModuloOrganizacion`
--

LOCK TABLES `ModuloOrganizacion` WRITE;
/*!40000 ALTER TABLE `ModuloOrganizacion` DISABLE KEYS */;
INSERT INTO `ModuloOrganizacion` VALUES (1,1,'S','','','',''),(1,2,'S','','','',''),(1,3,'S','','','',''),(1,4,'S','','','','');
/*!40000 ALTER TABLE `ModuloOrganizacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Organizacion`
--

DROP TABLE IF EXISTS `Organizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Organizacion` (
  `idOrganizacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `claveOrganizacion` varchar(60) NOT NULL,
  `idsModulos` varchar(200) DEFAULT NULL,
  `directorio` varchar(20) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idOrganizacion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Organizacion`
--

LOCK TABLES `Organizacion` WRITE;
/*!40000 ALTER TABLE `Organizacion` DISABLE KEYS */;
INSERT INTO `Organizacion` VALUES (1,'Colegio Sagrado Corazón','colsagcor16','1,2,3,4','1775418046','2016-11-29 20:54:06');
/*!40000 ALTER TABLE `Organizacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Rol`
--

DROP TABLE IF EXISTS `Rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Rol` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(45) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Rol`
--

LOCK TABLES `Rol` WRITE;
/*!40000 ALTER TABLE `Rol` DISABLE KEYS */;
INSERT INTO `Rol` VALUES (1,'guest','2016-11-29 20:54:17'),(2,'test','2016-11-29 20:54:17'),(3,'operation','2016-11-29 20:54:17'),(4,'admin','2016-11-29 20:54:17'),(5,'system','2016-11-29 20:54:17');
/*!40000 ALTER TABLE `Rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Subscripcion`
--

DROP TABLE IF EXISTS `Subscripcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Subscripcion` (
  `idSubscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `idOrganizacion` int(11) NOT NULL,
  `idModulo` int(11) NOT NULL,
  `idRol` int(11) NOT NULL,
  `adapter` varchar(45) NOT NULL DEFAULT 'pdo_mysql',
  `host` varchar(60) NOT NULL DEFAULT 'localhost',
  `tipo` varchar(1) NOT NULL,
  `dbname` varchar(100) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(40) NOT NULL,
  `enctype` varchar(10) NOT NULL,
  `charset` varchar(45) NOT NULL DEFAULT 'utf8',
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idSubscripcion`),
  KEY `fk_Subscripcion_Organizacion_idx` (`idOrganizacion`),
  KEY `fk_Subscripcion_Modulo1_idx` (`idModulo`),
  KEY `fk_Subscripcion_Rol1_idx` (`idRol`),
  CONSTRAINT `fk_Subscripcion_Modulo1` FOREIGN KEY (`idModulo`) REFERENCES `Modulo` (`idModulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Subscripcion_Organizacion` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`idOrganizacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Subscripcion_Rol1` FOREIGN KEY (`idRol`) REFERENCES `Rol` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Subscripcion`
--

LOCK TABLES `Subscripcion` WRITE;
/*!40000 ALTER TABLE `Subscripcion` DISABLE KEYS */;
INSERT INTO `Subscripcion` VALUES (1,1,1,2,'pdo_mysql','localhost','O','biblio_1775418046','server_utest','Zazil_3017_*+','NONE','utf8','2016-11-29 20:54:11'),(2,1,2,2,'pdo_mysql','localhost','O','enc_org_1775418046','server_utest','Zazil_3017_*+','NONE','utf8','2016-11-29 20:54:11'),(3,1,4,2,'pdo_mysql','localhost','O','soporte_org_1775418046','server_utest','Zazil_3017_*+','NONE','utf8','2016-11-29 20:54:11'),(4,1,3,2,'pdo_mysql','localhost','O','evento_org_1775418046','server_utest','Zazil_3017_*+','NONE','utf8','2016-11-29 20:54:11'),(5,1,3,4,'pdo_mysql','localhost','O','evento_org_1775418046','server_uadmin','Zazil5017_*+','NONE','utf8','2016-11-29 20:54:11'),(6,1,1,5,'pdo_mysql','localhost','Q','biblio_1775418046','server_usystem','Zazil6017_*+','NONE','utf8','2016-11-29 20:54:11'),(7,1,2,5,'pdo_mysql','localhost','O','enc_org_1775418046','server_usystem','Zazil6017_*+','NONE','utf8','2016-11-29 20:54:11'),(8,1,2,4,'pdo_mysql','localhost','O','enc_org_1775418046','server_uadmin','Zazil5017_*+','NONE','utf8','2017-12-04 14:07:11'),(9,1,3,5,'pdo_mysql','localhost','O','evento_org_1775418046','server_usystem','Zazil6017_*+','NONE','utf8','2016-11-29 20:54:11');
/*!40000 ALTER TABLE `Subscripcion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuario`
--

DROP TABLE IF EXISTS `Usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `idRol` int(11) NOT NULL,
  `idOrganizacion` int(11) NOT NULL,
  `nickname` varchar(60) NOT NULL,
  `password` varchar(50) NOT NULL,
  `creacion` datetime NOT NULL,
  PRIMARY KEY (`idUsuario`),
  KEY `fk_Usuario_Organizacion1_idx` (`idOrganizacion`),
  KEY `fk_Usuario_Rol1_idx` (`idRol`),
  CONSTRAINT `fk_Usuario_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`idOrganizacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Usuario_Rol1` FOREIGN KEY (`idRol`) REFERENCES `Rol` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuario`
--

LOCK TABLES `Usuario` WRITE;
/*!40000 ALTER TABLE `Usuario` DISABLE KEYS */;
INSERT INTO `Usuario` VALUES (1,2,1,'test','9b2263cf1682f661c32de57b13439446be633c45','2016-11-29 20:54:22'),(2,5,1,'gio','bdf59c0eac38312e007471111a64d8b2478ac0f7','2016-11-29 20:54:22'),(3,5,1,'ana','9b2263cf1682f661c32de57b13439446be633c45','2016-11-29 20:54:22'),(4,4,1,'areli','9b2263cf1682f661c32de57b13439446be633c45','2016-11-29 20:54:22'),(5,5,1,'alizon','9b2263cf1682f661c32de57b13439446be633c45','2016-11-29 20:54:22'),(6,4,1,'ale','9b2263cf1682f661c32de57b13439446be633c45','2016-11-29 20:54:22'),(7,4,1,'juanmc','9b2263cf1682f661c32de57b13439446be633c45','2016-11-29 20:54:22'),(8,4,1,'admin','bdf59c0eac38312e007471111a64d8b2478ac0f7','2016-11-29 20:54:22'),(9,5,1,'system','bdf59c0eac38312e007471111a64d8b2478ac0f7','2016-11-29 20:54:22');
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

-- Dump completed on 2018-08-20 18:39:06

-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema enc_org_1775418046
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema enc_org_1775418046
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `enc_org_1775418046` DEFAULT CHARACTER SET utf8 ;
USE `enc_org_1775418046` ;

-- -----------------------------------------------------
-- Table `enc_org_1775418046`.`Evaluador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `enc_org_1775418046`.`Evaluador` (
  `idEvaluador` INT NOT NULL AUTO_INCREMENT,
  `nombres` VARCHAR(200) NOT NULL,
  `apellidos` VARCHAR(200) NOT NULL,
  `tipo` VARCHAR(4) NOT NULL,
  `creacion` DATETIME NOT NULL,
  PRIMARY KEY (`idEvaluador`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `enc_org_1775418046`.`ConjuntoEvaluador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `enc_org_1775418046`.`ConjuntoEvaluador` (
  `idConjuntoEvaluador` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(200) NOT NULL,
  `idsEvaluadores` TEXT NOT NULL,
  `creacion` DATETIME NOT NULL,
  PRIMARY KEY (`idConjuntoEvaluador`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

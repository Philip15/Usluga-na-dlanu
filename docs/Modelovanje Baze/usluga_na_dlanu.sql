-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`kategorije`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`kategorije` ;

CREATE TABLE IF NOT EXISTS `mydb`.`kategorije` (
  `idKategorije` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idKategorije`),
  UNIQUE INDEX `naziv_UNIQUE` (`naziv` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`korisnici`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`korisnici` ;

CREATE TABLE IF NOT EXISTS `mydb`.`korisnici` (
  `idKorisnika` INT NOT NULL AUTO_INCREMENT,
  `korisnickoIme` VARCHAR(45) NOT NULL,
  `lozinka` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `ime` VARCHAR(45) NOT NULL,
  `prezime` VARCHAR(45) NOT NULL,
  `profilnaSlika` LONGBLOB NULL,
  `opis` VARCHAR(400) NULL,
  `pruzalac` TINYINT NOT NULL DEFAULT 0,
  `adresa` VARCHAR(100) NULL,
  `idKategorije` INT NULL,
  `administrator` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`idKorisnika`),
  UNIQUE INDEX `korisnickoIme_UNIQUE` (`korisnickoIme` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  INDEX `idKategorije_idx` (`idKategorije` ASC) VISIBLE,
  CONSTRAINT `idKategorije`
    FOREIGN KEY (`idKategorije`)
    REFERENCES `mydb`.`kategorije` (`idKategorije`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`zahtevi`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`zahtevi` ;

CREATE TABLE IF NOT EXISTS `mydb`.`zahtevi` (
  `idZahteva` INT NOT NULL AUTO_INCREMENT,
  `idKorisnika` INT NOT NULL,
  `idPruzaoca` INT NOT NULL,
  `stanje` TINYINT NOT NULL,
  `opis` VARCHAR(400) NOT NULL,
  `hitno` TINYINT NOT NULL,
  `cena` DOUBLE NULL,
  `komentar` VARCHAR(400) NULL,
  `ocena` INT NULL,
  `recenzija` VARCHAR(400) NULL,
  PRIMARY KEY (`idZahteva`),
  INDEX `idPruzaoca_idx` (`idPruzaoca` ASC) VISIBLE,
  INDEX `idKorisnika_idx` (`idKorisnika` ASC) VISIBLE,
  CONSTRAINT `idKorisnika`
    FOREIGN KEY (`idKorisnika`)
    REFERENCES `mydb`.`korisnici` (`idKorisnika`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `idPruzaoca`
    FOREIGN KEY (`idPruzaoca`)
    REFERENCES `mydb`.`korisnici` (`idKorisnika`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`termini`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`termini` ;

CREATE TABLE IF NOT EXISTS `mydb`.`termini` (
  `idTermina` INT NOT NULL AUTO_INCREMENT,
  `idPruzaoca` INT NOT NULL,
  `datumVremePocetka` DATETIME NOT NULL,
  `trajanje` INT NOT NULL,
  `idZahteva` INT NULL,
  PRIMARY KEY (`idTermina`),
  INDEX `idPruzaoca_idx` (`idPruzaoca` ASC) VISIBLE,
  INDEX `idZahteva_idx` (`idZahteva` ASC) VISIBLE,
  CONSTRAINT `idPruzaoca`
    FOREIGN KEY (`idPruzaoca`)
    REFERENCES `mydb`.`korisnici` (`idKorisnika`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `idZahteva`
    FOREIGN KEY (`idZahteva`)
    REFERENCES `mydb`.`zahtevi` (`idZahteva`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

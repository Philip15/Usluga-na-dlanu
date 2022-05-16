-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema usluga_na_dlanu
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `usluga_na_dlanu` ;

-- -----------------------------------------------------
-- Schema usluga_na_dlanu
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `usluga_na_dlanu` DEFAULT CHARACTER SET utf8 ;
USE `usluga_na_dlanu` ;

-- -----------------------------------------------------
-- Table `mydb`.`kategorije`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usluga_na_dlanu`.`kategorije` ;

CREATE TABLE IF NOT EXISTS `usluga_na_dlanu`.`kategorije` (
  `idKategorije` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idKategorije`),
  UNIQUE INDEX `naziv_UNIQUE` (`naziv` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `usluga_na_dlanu`.`korisnici`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usluga_na_dlanu`.`korisnici` ;

CREATE TABLE IF NOT EXISTS `usluga_na_dlanu`.`korisnici` (
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
  `lat` FLOAT NULL,
  `lon` FLOAT NULL,
  `idKategorije` INT NULL,
  `administrator` TINYINT NOT NULL DEFAULT 0,
  `korisnicicol` VARCHAR(45) NULL,
  PRIMARY KEY (`idKorisnika`),
  UNIQUE INDEX `korisnickoIme_UNIQUE` (`korisnickoIme` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  INDEX `idKategorije_idx` (`idKategorije` ASC) VISIBLE,
  CONSTRAINT `idKategorije`
    FOREIGN KEY (`idKategorije`)
    REFERENCES `usluga_na_dlanu`.`kategorije` (`idKategorije`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `usluga_na_dlanu`.`zahtevi`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usluga_na_dlanu`.`zahtevi` ;

CREATE TABLE IF NOT EXISTS `usluga_na_dlanu`.`zahtevi` (
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
    REFERENCES `usluga_na_dlanu`.`korisnici` (`idKorisnika`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `idPruzaoca2`
    FOREIGN KEY (`idPruzaoca`)
    REFERENCES `usluga_na_dlanu`.`korisnici` (`idKorisnika`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `usluga_na_dlanu`.`termini`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usluga_na_dlanu`.`termini` ;

CREATE TABLE IF NOT EXISTS `usluga_na_dlanu`.`termini` (
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
    REFERENCES `usluga_na_dlanu`.`korisnici` (`idKorisnika`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `idZahteva`
    FOREIGN KEY (`idZahteva`)
    REFERENCES `usluga_na_dlanu`.`zahtevi` (`idZahteva`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

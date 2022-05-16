-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 16, 2022 at 06:37 PM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `usluga_na_dlanu`
--
CREATE DATABASE IF NOT EXISTS `usluga_na_dlanu` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `usluga_na_dlanu`;

-- --------------------------------------------------------

--
-- Table structure for table `kategorije`
--

DROP TABLE IF EXISTS `kategorije`;
CREATE TABLE IF NOT EXISTS `kategorije` (
  `idKategorije` int NOT NULL AUTO_INCREMENT,
  `naziv` varchar(45) NOT NULL,
  PRIMARY KEY (`idKategorije`),
  UNIQUE KEY `naziv_UNIQUE` (`naziv`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `kategorije`
--

INSERT INTO `kategorije` (`idKategorije`, `naziv`) VALUES
(4, 'bravar'),
(3, 'električar'),
(2, 'moler'),
(1, 'vodoinstalater');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

DROP TABLE IF EXISTS `korisnici`;
CREATE TABLE IF NOT EXISTS `korisnici` (
  `idKorisnika` int NOT NULL AUTO_INCREMENT,
  `korisnickoIme` varchar(45) NOT NULL,
  `lozinka` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `ime` varchar(45) NOT NULL,
  `prezime` varchar(45) NOT NULL,
  `profilnaSlika` longblob,
  `opis` varchar(400) DEFAULT NULL,
  `pruzalac` tinyint NOT NULL DEFAULT '0',
  `adresa` varchar(100) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `idKategorije` int DEFAULT NULL,
  `administrator` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`idKorisnika`),
  UNIQUE KEY `korisnickoIme_UNIQUE` (`korisnickoIme`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `idKategorije_idx` (`idKategorije`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`idKorisnika`, `korisnickoIme`, `lozinka`, `email`, `ime`, `prezime`, `profilnaSlika`, `opis`, `pruzalac`, `adresa`, `lat`, `lon`, `idKategorije`, `administrator`) VALUES
(1, 'lazar', 'qwerty', 'pl190091d@student.etf.bg.ac.rs', 'Lazar', 'Premović', NULL, NULL, 0, NULL, NULL, NULL, NULL, 1),
(2, 'mika', 'mika123', 'mika@gmail.com', 'Mika', 'Mikić', NULL, 'Najbolji vodoinstalater u gradu.', 1, 'Ulica 1', -35.7274, -28.2477, 1, 0),
(3, 'pera', 'pera123', 'pera@gmail.com', 'Pera', 'Perić', NULL, 'Najbolji moler u gradu.', 1, 'Ulica 2', -54.9029, -36.0484, 2, 0),
(4, 'zoki', 'zoki123', 'zoki@gmail.com', 'Zoki', 'Zokić', NULL, 'Najbolji električar u gradu.', 1, 'Ulica 3', 15.2273, 178.892, 3, 0),
(5, 'misko', 'misko123', 'misko@gmail.com', 'Miško', 'Mišković', NULL, 'Pomoćnik najboljeg električara u gradu.', 1, 'Ulica 4', 30.0326, -109.302, 3, 0),
(6, 'luka', 'luka123', 'luka@gmail.com', 'Luka', 'Luković', NULL, 'Jednom sam sebi okrečio sobu.', 1, 'Ulica 5', -39.7139, -46.4114, 2, 0),
(7, 'zika', 'zika123', 'zika@gmail.com', 'Žika', 'Žikić', NULL, 'Umem da otpušim wc šolju.', 1, 'Ulica 6', 2.80141, -1.42037, 1, 0),
(8, 'marko', 'marko123', 'marko@gmail.com', 'Marko', 'Marković', NULL, 'Najbolji bravar u gradu.', 1, 'Ulica 7', 18.6576, 114.699, 4, 0),
(9, 'slavko', 'slavko123', 'slavko@gmail.com', 'Slavko', 'Slavković', NULL, 'Ne sinko, ne radi se to tako.', 1, 'Ulica 7', 2.51104, 33.5089, 3, 0),
(10, 'mirko', 'mirko123', 'mirko@gmail.com', 'Mirko', 'Mirković', NULL, 'Ni tile mi nije ravan.', 1, 'Ulica 8', -17.5943, -17.5943, 4, 0),
(11, 'zaki', 'zaki123', 'zaki@gmail.com', 'Zaki', 'Radivojević', NULL, 'DOBAR DAN!', 1, 'Ulica 9', 22.4755, -161.464, 4, 0),
(12, 'ivan', 'ivan123', 'ivan@gmail.com', 'Ivan', 'Ivanović', NULL, 'A je l\' ste možda čuli za EESTEC?', 1, 'Ulica 10', -16.4401, -159.081, 1, 0),
(13, 'goran', 'goran123', 'goran@gmail.com', 'Goran', 'Goranović', NULL, 'Nemoj Gorane, ići ćeš u zatvor.', 1, 'Ulica 11', 7.33367, -83.1155, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `termini`
--

DROP TABLE IF EXISTS `termini`;
CREATE TABLE IF NOT EXISTS `termini` (
  `idTermina` int NOT NULL AUTO_INCREMENT,
  `idPruzaoca` int NOT NULL,
  `datumVremePocetka` datetime NOT NULL,
  `trajanje` int NOT NULL,
  `idZahteva` int DEFAULT NULL,
  PRIMARY KEY (`idTermina`),
  KEY `idPruzaoca_idx` (`idPruzaoca`),
  KEY `idZahteva_idx` (`idZahteva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `zahtevi`
--

DROP TABLE IF EXISTS `zahtevi`;
CREATE TABLE IF NOT EXISTS `zahtevi` (
  `idZahteva` int NOT NULL AUTO_INCREMENT,
  `idKorisnika` int NOT NULL,
  `idPruzaoca` int NOT NULL,
  `stanje` tinyint NOT NULL,
  `opis` varchar(400) NOT NULL,
  `hitno` tinyint NOT NULL,
  `cena` double DEFAULT NULL,
  `komentar` varchar(400) DEFAULT NULL,
  `ocena` int DEFAULT NULL,
  `recenzija` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`idZahteva`),
  KEY `idPruzaoca_idx` (`idPruzaoca`),
  KEY `idKorisnika_idx` (`idKorisnika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD CONSTRAINT `idKategorije` FOREIGN KEY (`idKategorije`) REFERENCES `kategorije` (`idKategorije`);

--
-- Constraints for table `termini`
--
ALTER TABLE `termini`
  ADD CONSTRAINT `idPruzaoca` FOREIGN KEY (`idPruzaoca`) REFERENCES `korisnici` (`idKorisnika`),
  ADD CONSTRAINT `idZahteva` FOREIGN KEY (`idZahteva`) REFERENCES `zahtevi` (`idZahteva`);

--
-- Constraints for table `zahtevi`
--
ALTER TABLE `zahtevi`
  ADD CONSTRAINT `idKorisnika` FOREIGN KEY (`idKorisnika`) REFERENCES `korisnici` (`idKorisnika`),
  ADD CONSTRAINT `idPruzaoca2` FOREIGN KEY (`idPruzaoca`) REFERENCES `korisnici` (`idKorisnika`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

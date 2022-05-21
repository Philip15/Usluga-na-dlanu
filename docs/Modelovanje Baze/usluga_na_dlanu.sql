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
  `lozinka` varchar(255) NOT NULL,
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
(1, 'lazar', '$2y$10$kg1Az2VKJz/D8WLIDqEPbuxAnIHteh3zFrvy20U.v4Zr96qMOipTe', 'pl190091d@student.etf.bg.ac.rs', 'Lazar', 'Premović', NULL, NULL, 0, NULL, NULL, NULL, NULL, 1),										/*pass:qwerty*/
(2, 'mika',  '$2y$10$OpcvPi5xznpqYvZxiMG9OOvBG3by9/OFB8fOQtDTIY9QFKUMtZ2tK', 'mika@gmail.com', 'Mika', 'Mikić', NULL, 'Najbolji vodoinstalater u gradu.', 1, 'Ulica 1', -35.7274, -28.2477, 1, 0),					/*pass:mika123*/
(3, 'pera',  '$2y$10$z0SYNvcWknERoG0ABIuD7Op7kWz7MF7n9/lMUyFKHu12eeXQ.WaY2', 'pera@gmail.com', 'Pera', 'Perić', NULL, 'Najbolji moler u gradu.', 1, 'Ulica 2', -54.9029, -36.0484, 2, 0),							/*pass:pera123*/
(4, 'zoki',  '$2y$10$eP.L1nBI7VxsmETAv.yZcuKqN1d2Zu.xGsjXjzHTbKMbvjw2v.Czi', 'zoki@gmail.com', 'Zoki', 'Zokić', NULL, 'Najbolji električar u gradu.', 1, 'Ulica 3', 15.2273, 178.892, 3, 0),						/*pass:zoki123*/
(5, 'misko', '$2y$10$2nL8uS6pT6q9LnfVj5tb2OLncfFjIKVorEXddEt93s1ThtU63w4t6', 'misko@gmail.com', 'Miško', 'Mišković', NULL, 'Pomoćnik najboljeg električara u gradu.', 1, 'Ulica 4', 30.0326, -109.302, 3, 0),		/*pass:misko123*/
(6, 'luka',  '$2y$10$9Y1KKwTdfVDmhN0fno5UYuadvi54lLbWaEwsz0p2yV5FtUAdcEr0G', 'luka@gmail.com', 'Luka', 'Luković', NULL, 'Jednom sam sebi okrečio sobu.', 1, 'Ulica 5', -39.7139, -46.4114, 2, 0),					/*pass:luka123*/
(7, 'zika',  '$2y$10$SbMPB.TeoOimcxgl3fgoYOOD8O5LvoT.GnEVXiEfnXBG4pVww53iS', 'zika@gmail.com', 'Žika', 'Žikić', NULL, 'Umem da otpušim wc šolju.', 1, 'Ulica 6', 2.80141, -1.42037, 1, 0),							/*pass:zika123*/
(8, 'marko', '$2y$10$7rtS5WyEpETSks6uAd7YCOIdyF7c2/X08WevBtBmevacM0PEl4MoG', 'marko@gmail.com', 'Marko', 'Marković', NULL, 'Najbolji bravar u gradu.', 1, 'Ulica 7', 18.6576, 114.699, 4, 0),						/*pass:marko123*/
(9, 'slavko','$2y$10$ECNny3Yx5n6Vm99KT9aLTO94DHNTIDcGJv7UGRdHgLelaa9Rw6GEi', 'slavko@gmail.com', 'Slavko', 'Slavković', NULL, 'Ne sinko, ne radi se to tako.', 1, 'Ulica 7', 2.51104, 33.5089, 3, 0),				/*pass:slavko123*/
(10, 'mirko','$2y$10$Fuim4w03mCd07J.LHPHIQ.KbKEx1/1NyBc.MnrjbFFs8pqw3mX57i', 'mirko@gmail.com', 'Mirko', 'Mirković', NULL, 'Ni tile mi nije ravan.', 1, 'Ulica 8', -17.5943, -17.5943, 4, 0),						/*pass:mirko123*/
(11, 'zaki', '$2y$10$6JB.FydTPuTpSdsjBMe7aOWIvREp8F1isPUoCb4zcPLBFUBI1rmkC', 'zaki@gmail.com', 'Zaki', 'Radivojević', NULL, 'DOBAR DAN!', 1, 'Ulica 9', 22.4755, -161.464, 4, 0),									/*pass:zaki123*/
(12, 'ivan', '$2y$10$Zk94IH1kaoiFEO7ilpBKuuRYxvh.5QsXyUumVOrkVSzGcxpR43ifu', 'ivan@gmail.com', 'Ivan', 'Ivanović', NULL, 'A je l ste možda čuli za EESTEC?', 1, 'Ulica 10', -16.4401, -159.081, 1, 0),			/*pass:ivan123*/
(13, 'goran','$2y$10$kJn32AgYcL3HEmL/YEXuW.TlHvM6ntZRp5c5q5sdCL.n8dAwUKC8G', 'goran@gmail.com', 'Goran', 'Goranović', NULL, 'Nemoj Gorane, ići ćeš u zatvor.', 1, 'Ulica 11', 7.33367, -83.1155, 2, 0);			/*pass:goran123*/

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

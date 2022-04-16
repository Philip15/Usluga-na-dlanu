-- MySQL dump 10.13  Distrib 8.0.27, for Win64 (x86_64)
--
-- Host: localhost    Database: usluga_na_dlanu
-- ------------------------------------------------------
-- Server version	8.0.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `idAdm` int NOT NULL AUTO_INCREMENT,
  `korisnicko_ime` varchar(45) NOT NULL,
  `lozinka` varchar(45) NOT NULL,
  PRIMARY KEY (`idAdm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategorija`
--

DROP TABLE IF EXISTS `kategorija`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategorija` (
  `idKat` int NOT NULL,
  `naziv` varchar(45) NOT NULL,
  PRIMARY KEY (`idKat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategorija`
--

LOCK TABLES `kategorija` WRITE;
/*!40000 ALTER TABLE `kategorija` DISABLE KEYS */;
/*!40000 ALTER TABLE `kategorija` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `korisnik`
--

DROP TABLE IF EXISTS `korisnik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `korisnik` (
  `idKor` int NOT NULL AUTO_INCREMENT,
  `korsnicko_ime` varchar(45) NOT NULL,
  `lozinka` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `ime` varchar(45) NOT NULL,
  `prezime` varchar(45) NOT NULL,
  `profilna_slika` longblob,
  `opis` varchar(400) DEFAULT NULL,
  `pruzalac` tinyint NOT NULL DEFAULT '0',
  `adresa` varchar(100) DEFAULT NULL,
  `idKategorija` int DEFAULT NULL,
  PRIMARY KEY (`idKor`),
  KEY `FK_idKategorija_korisnik_idx` (`idKategorija`),
  CONSTRAINT `FK_idKategorija_korisnik` FOREIGN KEY (`idKategorija`) REFERENCES `kategorija` (`idKat`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `korisnik`
--

LOCK TABLES `korisnik` WRITE;
/*!40000 ALTER TABLE `korisnik` DISABLE KEYS */;
/*!40000 ALTER TABLE `korisnik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recenzija`
--

DROP TABLE IF EXISTS `recenzija`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recenzija` (
  `idRec` int NOT NULL AUTO_INCREMENT,
  `idKorisnik` int NOT NULL,
  `idPruzalac` int NOT NULL,
  `ostavljena` tinyint NOT NULL DEFAULT '0',
  `ocena` int NOT NULL,
  `komentar` varchar(400) NOT NULL,
  `datum` date NOT NULL,
  PRIMARY KEY (`idRec`),
  KEY `FK_idKorisnik_idx` (`idKorisnik`),
  KEY `FK_idPruzalac_idx` (`idPruzalac`),
  CONSTRAINT `FK_idKorisnik_recenzija` FOREIGN KEY (`idKorisnik`) REFERENCES `korisnik` (`idKor`) ON UPDATE CASCADE,
  CONSTRAINT `FK_idPruzalac_recenzija` FOREIGN KEY (`idPruzalac`) REFERENCES `korisnik` (`idKor`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recenzija`
--

LOCK TABLES `recenzija` WRITE;
/*!40000 ALTER TABLE `recenzija` DISABLE KEYS */;
/*!40000 ALTER TABLE `recenzija` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `termin`
--

DROP TABLE IF EXISTS `termin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `termin` (
  `idTer` int NOT NULL AUTO_INCREMENT,
  `idZahtev` int DEFAULT NULL,
  `datum_vreme_pocetak` datetime NOT NULL,
  `trajanje` int NOT NULL,
  PRIMARY KEY (`idTer`),
  KEY `FK_idZah_termin_idx` (`idZahtev`),
  CONSTRAINT `FK_idZah_termin` FOREIGN KEY (`idZahtev`) REFERENCES `zahtev` (`idZah`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `termin`
--

LOCK TABLES `termin` WRITE;
/*!40000 ALTER TABLE `termin` DISABLE KEYS */;
/*!40000 ALTER TABLE `termin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zahtev`
--

DROP TABLE IF EXISTS `zahtev`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zahtev` (
  `idZah` int NOT NULL AUTO_INCREMENT,
  `idKorisnik` int NOT NULL,
  `idPruzalac` int NOT NULL,
  `cena` double DEFAULT NULL,
  `komentar` varchar(400) DEFAULT NULL,
  `stanje` int NOT NULL DEFAULT '0',
  `opis` varchar(400) NOT NULL,
  `hitno` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`idZah`),
  KEY `FK_idKorisnik_zahtev_idx` (`idKorisnik`),
  KEY `FK_idPruzalac_zahtev_idx` (`idPruzalac`),
  CONSTRAINT `FK_idKorisnik_zahtev` FOREIGN KEY (`idKorisnik`) REFERENCES `korisnik` (`idKor`) ON UPDATE CASCADE,
  CONSTRAINT `FK_idPruzalac_zahtev` FOREIGN KEY (`idPruzalac`) REFERENCES `korisnik` (`idKor`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zahtev`
--

LOCK TABLES `zahtev` WRITE;
/*!40000 ALTER TABLE `zahtev` DISABLE KEYS */;
/*!40000 ALTER TABLE `zahtev` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-16 18:29:59

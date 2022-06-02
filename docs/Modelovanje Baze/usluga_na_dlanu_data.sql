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
-- Table structure for table `kategorije`
--

DROP TABLE IF EXISTS `kategorije`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategorije` (
  `idKategorije` int NOT NULL AUTO_INCREMENT,
  `naziv` varchar(45) NOT NULL,
  PRIMARY KEY (`idKategorije`),
  UNIQUE KEY `naziv_UNIQUE` (`naziv`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategorije`
--

LOCK TABLES `kategorije` WRITE;
/*!40000 ALTER TABLE `kategorije` DISABLE KEYS */;
INSERT INTO `kategorije` VALUES (4,'bravar'),(3,'električar'),(2,'moler'),(1,'vodoinstalater');
/*!40000 ALTER TABLE `kategorije` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `korisnici`
--

DROP TABLE IF EXISTS `korisnici`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `korisnici` (
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
  KEY `idKategorije_idx` (`idKategorije`),
  CONSTRAINT `idKategorije` FOREIGN KEY (`idKategorije`) REFERENCES `kategorije` (`idKategorije`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `korisnici`
--

LOCK TABLES `korisnici` WRITE;
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT INTO `korisnici` VALUES (1,'lazar','$2y$10$kg1Az2VKJz/D8WLIDqEPbuxAnIHteh3zFrvy20U.v4Zr96qMOipTe','pl190091d@student.etf.bg.ac.rs','Lazar','Premović',NULL,NULL,0,NULL,NULL,NULL,NULL,1),(2,'mika','$2y$10$OpcvPi5xznpqYvZxiMG9OOvBG3by9/OFB8fOQtDTIY9QFKUMtZ2tK','mika@gmail.com','Mika','Mikić',NULL,'Najbolji vodoinstalater u gradu.',1,'Ulica 1',-35.7274,-28.2477,1,0),(3,'pera','$2y$10$z0SYNvcWknERoG0ABIuD7Op7kWz7MF7n9/lMUyFKHu12eeXQ.WaY2','pera@gmail.com','Pera','Perić',NULL,'Najbolji moler u gradu.',1,'Ulica 2',-54.9029,-36.0484,2,0),(4,'zoki','$2y$10$eP.L1nBI7VxsmETAv.yZcuKqN1d2Zu.xGsjXjzHTbKMbvjw2v.Czi','zoki@gmail.com','Zoki','Zokić',NULL,'Najbolji električar u gradu.',1,'Ulica 3',15.2273,178.892,3,0),(5,'misko','$2y$10$2nL8uS6pT6q9LnfVj5tb2OLncfFjIKVorEXddEt93s1ThtU63w4t6','misko@gmail.com','Miško','Mišković',NULL,'Pomoćnik najboljeg električara u gradu.',1,'Ulica 4',30.0326,-109.302,3,0),(6,'luka','$2y$10$9Y1KKwTdfVDmhN0fno5UYuadvi54lLbWaEwsz0p2yV5FtUAdcEr0G','luka@gmail.com','Luka','Luković',NULL,'Jednom sam sebi okrečio sobu.',1,'Ulica 5',-39.7139,-46.4114,2,0),(7,'zika','$2y$10$SbMPB.TeoOimcxgl3fgoYOOD8O5LvoT.GnEVXiEfnXBG4pVww53iS','zika@gmail.com','Žika','Žikić',NULL,'Umem da otpušim wc šolju.',1,'Ulica 6',2.80141,-1.42037,1,0),(8,'marko','$2y$10$7rtS5WyEpETSks6uAd7YCOIdyF7c2/X08WevBtBmevacM0PEl4MoG','marko@gmail.com','Marko','Marković',NULL,'Najbolji bravar u gradu.',1,'Ulica 7',18.6576,114.699,4,0),(9,'slavko','$2y$10$ECNny3Yx5n6Vm99KT9aLTO94DHNTIDcGJv7UGRdHgLelaa9Rw6GEi','slavko@gmail.com','Slavko','Slavković',NULL,'Ne sinko, ne radi se to tako.',1,'Ulica 7',2.51104,33.5089,3,0),(10,'mirko','$2y$10$Fuim4w03mCd07J.LHPHIQ.KbKEx1/1NyBc.MnrjbFFs8pqw3mX57i','mirko@gmail.com','Mirko','Mirković',NULL,'Ni tile mi nije ravan.',1,'Ulica 8',-17.5943,-17.5943,4,0),(11,'zaki','$2y$10$6JB.FydTPuTpSdsjBMe7aOWIvREp8F1isPUoCb4zcPLBFUBI1rmkC','zaki@gmail.com','Zaki','Radivojević',NULL,'DOBAR DAN!',1,'Ulica 9',22.4755,-161.464,4,0),(12,'ivan','$2y$10$Zk94IH1kaoiFEO7ilpBKuuRYxvh.5QsXyUumVOrkVSzGcxpR43ifu','ivan@gmail.com','Ivan','Ivanović',NULL,'A je l ste možda čuli za EESTEC?',1,'Ulica 10',-16.4401,-159.081,1,0),(13,'goran','$2y$10$kJn32AgYcL3HEmL/YEXuW.TlHvM6ntZRp5c5q5sdCL.n8dAwUKC8G','goran@gmail.com','Goran','Goranović',NULL,'Nemoj Gorane, ići ćeš u zatvor.',1,'Ulica 11',7.33367,-83.1155,2,0),(14,'test','$2y$10$EAMWDh5sLQyhcVy5A4Jo6OWaJBkSTTMfZZ0FMG5jDc6BShiW/bC5G','test@test.test','Test','Korisnik',NULL,NULL,0,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `korisnici` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `termini`
--

DROP TABLE IF EXISTS `termini`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `termini` (
  `idTermina` int NOT NULL AUTO_INCREMENT,
  `idPruzaoca` int NOT NULL,
  `datumVremePocetka` datetime NOT NULL,
  `trajanje` int NOT NULL,
  `idZahteva` int DEFAULT NULL,
  PRIMARY KEY (`idTermina`),
  KEY `idPruzaoca_idx` (`idPruzaoca`),
  KEY `idZahteva_idx` (`idZahteva`),
  CONSTRAINT `idPruzaoca` FOREIGN KEY (`idPruzaoca`) REFERENCES `korisnici` (`idKorisnika`),
  CONSTRAINT `idZahteva` FOREIGN KEY (`idZahteva`) REFERENCES `zahtevi` (`idZahteva`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `termini`
--

LOCK TABLES `termini` WRITE;
/*!40000 ALTER TABLE `termini` DISABLE KEYS */;
INSERT INTO `termini` VALUES (1,2,'2022-06-02 11:00:00',120,1),(2,3,'2022-06-04 14:00:00',240,2),(3,4,'2022-06-15 12:00:00',30,3),(5,2,'2022-06-02 14:00:00',300,NULL),(6,11,'2022-06-05 09:30:00',30,5),(7,12,'2022-06-11 13:00:00',240,6),(10,9,'2022-06-17 12:00:00',30,9);
/*!40000 ALTER TABLE `termini` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zahtevi`
--

DROP TABLE IF EXISTS `zahtevi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zahtevi` (
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
  KEY `idKorisnika_idx` (`idKorisnika`),
  CONSTRAINT `idKorisnika` FOREIGN KEY (`idKorisnika`) REFERENCES `korisnici` (`idKorisnika`),
  CONSTRAINT `idPruzaoca2` FOREIGN KEY (`idPruzaoca`) REFERENCES `korisnici` (`idKorisnika`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zahtevi`
--

LOCK TABLES `zahtevi` WRITE;
/*!40000 ALTER TABLE `zahtevi` DISABLE KEYS */;
INSERT INTO `zahtevi` VALUES (1,1,2,1,'Zahtev1',0,NULL,NULL,NULL,NULL),(2,1,3,2,'Zahtev2',1,123,'test',NULL,NULL),(3,1,4,3,'test',0,456,'t',NULL,NULL),(4,1,4,7,'Jos jedan zahtev',0,789,'opis',NULL,NULL),(5,14,11,4,'stanje 4',1,8,'',NULL,NULL),(6,14,12,5,'stanje 5',0,7,'',4,'komentar'),(7,14,13,6,'stanje 6',0,NULL,NULL,NULL,NULL),(8,14,7,8,'jos jedan zahtev',0,NULL,NULL,NULL,NULL),(9,14,9,5,'jos drugi zahtev',0,99,'',NULL,NULL);
/*!40000 ALTER TABLE `zahtevi` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-02 12:22:06

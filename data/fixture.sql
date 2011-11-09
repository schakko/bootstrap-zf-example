-- MySQL dump 10.13  Distrib 5.1.49, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: bootstrap_zf_example
-- ------------------------------------------------------
-- Server version	5.1.49-1ubuntu8.1

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
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` (`id`, `comment`, `id_user`, `comment_type`, `created_on`) VALUES (1,'First comment of P. Panzer',1,0,'2011-11-08 14:51:12'),(2,'Second comment of P. Panzer',1,0,'2011-11-08 14:51:21'),(3,'Third comment of P. Panzer',1,1,'2011-11-08 14:51:29'),(6,'First comment of Harald Schmidt',2,0,'2011-11-08 14:52:32'),(7,'Second comment of Harald Schmidt',2,0,'2011-11-08 14:52:38'),(8,'Third comment of Harald Schmidt',2,1,'2011-11-08 14:52:47'),(9,'test 1',3,1,'2011-11-08 14:53:14'),(10,'test 2',3,1,'2011-11-08 14:53:17'),(11,'test 3',3,0,'2011-11-08 14:53:22'),(12,'test 4',3,0,'2011-11-08 14:53:27');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `firstname`, `surname`, `username`, `description`, `password`) VALUES (1,'Paul','Panzer','paul_the_tank','This is the very special profile of P. Panzer',''),(2,'Harald','Schmidt','hdschmit','Harald Schmidts profile',''),(3,'test-surname','test-firstname','test','test profile','9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-11-09 14:00:41

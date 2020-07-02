-- MariaDB dump 10.17  Distrib 10.4.6-MariaDB, for Android (armv7-a)
--
-- Host: localhost    Database: school
-- ------------------------------------------------------
-- Server version	10.4.6-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `exam`
--

DROP TABLE IF EXISTS `exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL,
  `class` varchar(20) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `opt_a` text NOT NULL,
  `opt_b` text NOT NULL,
  `opt_c` text NOT NULL,
  `opt_d` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam`
--

LOCK TABLES `exam` WRITE;
/*!40000 ALTER TABLE `exam` DISABLE KEYS */;
INSERT INTO `exam` VALUES (1,'','Primary 6','Noun is ____.,Time waits for ____','c,a','qualify a pronoun,for no man','is about the study of star,those who wait','used to qualify place or thing,those who movies','none of the above,those who sleeps'),(2,'','Primary 6','Multiply 500 by 500,calculate the product of 246880 by 0.','a,b','250000,4680954','50000','2500000,578928996','550000,246897');
/*!40000 ALTER TABLE `exam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `time` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `score`
--

DROP TABLE IF EXISTS `score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `score` int(11) NOT NULL,
  `session` int(11) NOT NULL,
  `class` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `score`
--

LOCK TABLES `score` WRITE;
/*!40000 ALTER TABLE `score` DISABLE KEYS */;
/*!40000 ALTER TABLE `score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first` varchar(100) NOT NULL,
  `last` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `picture` text DEFAULT NULL,
  `age` int(11) NOT NULL,
  `dob` varchar(20) NOT NULL,
  `joined` varchar(10) NOT NULL,
  `class` varchar(20) NOT NULL,
  `hadd` text NOT NULL,
  `p_first` varchar(100) DEFAULT NULL,
  `p_last` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `student_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (1,'Sheriff','Mowe','Male','assets/img/student/4_G.jpg',11,'2008-06-05','08, 2019','Primary 6','No 16 ogunrun, mowe, ogun','Mama','Alajo','Nifemi@gmail.com','b569709bd980fc0393047df579ff506c02680cbefe9c4e2729ecc8f709cae097','Sheriff Mowe',996336801);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `class` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject`
--

LOCK TABLES `subject` WRITE;
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
INSERT INTO `subject` VALUES (1,'Mathmatics','Primary 6'),(2,'Mathmatics','Jss 1'),(3,'Mathmatics','Jss 2'),(4,'Mathmatics','Jss 3'),(5,'Mathmatics','Sss 1'),(6,'Mathmatics','Sss 2'),(7,'Mathmatics','Sss 3'),(8,'English','Primary 6'),(9,'English','Jss 1'),(10,'English','Jss 2'),(11,'English','Jss 3'),(12,'English','Sss 1'),(13,'English','Sss 2'),(14,'English','Sss 3'),(15,'Social studies','Primary 6'),(16,'Social studies','Jss 1'),(17,'Social studies','Jss 2'),(18,'Social studies','Jss 3'),(19,'Basic science','Primary 6'),(20,'Basic science','Jss 1'),(21,'Basic science','Jss 2'),(22,'Basic science','Jss 3'),(23,'Agriculture','Primary 6'),(24,'Agriculture','Jss 1'),(25,'Agriculture','Jss 2'),(26,'Agriculture','Jss 3'),(27,'Agriculture','Sss 1'),(28,'Agriculture','Sss 2'),(29,'Agriculture','Sss 3'),(30,'Physics','Sss 1'),(31,'Physics','Sss 2'),(32,'Physics','Sss 3'),(33,'Chemistry','Sss 1'),(34,'Chemistry','Sss 2'),(35,'Chemistry','Sss 3'),(36,'Geography','Sss 1'),(37,'Geography','Sss 2'),(38,'Geography','Sss 3'),(39,'Economics','Jss 1'),(40,'Economics','Jss 2'),(41,'Economics','Jss 3'),(42,'Economics','Sss 1'),(43,'Economics','Sss 2'),(44,'Economics','Sss 3'),(45,'History','Sss 1'),(46,'History','Sss 2'),(47,'History','Sss 3'),(48,'Biology','Sss 1'),(49,'Biology','Sss 2'),(50,'Biology','Sss 3'),(51,'Home economics','Primary 6'),(52,'Hadith','Primary 6'),(53,'Hadith','Jss 1'),(54,'Hadith','Jss 2'),(55,'Hadith','Jss 3'),(56,'Hadith','Sss 1'),(57,'Hadith','Sss 2'),(58,'Hadith','Sss 3');
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pre` varchar(5) NOT NULL,
  `first` varchar(100) NOT NULL,
  `last` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `status` varchar(20) NOT NULL,
  `picture` text DEFAULT NULL,
  `level` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `dob` varchar(20) NOT NULL,
  `class` varchar(20) NOT NULL,
  `hadd` text NOT NULL,
  `password` varchar(64) DEFAULT 'default0000',
  `fullname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher`
--

LOCK TABLES `teacher` WRITE;
/*!40000 ALTER TABLE `teacher` DISABLE KEYS */;
INSERT INTO `teacher` VALUES (1,'','Abdul-Fatai','Abdul-Azeez','Aaaa@gmail.com','Married','assets/img/teacher/admin.svg',1,40,'12-06-1979','','No 10, adekunle street ayobo','e7cf3ef4f17c3999a94f2c6f612e8a888e5b1026878e4e19398b23bd38ec221a','Abdul-Fatai Abdul-Azeez'),(8,'Mr','Muhammad-turyeeb','Bello','Tee@gmail.com','Single','assets/img/teacher/heart.png',0,24,'1995-06-18','Primary 6','No 15, majiyagbe ayobo lagos','e7cf3ef4f17c3999a94f2c6f612e8a888e5b1026878e4e19398b23bd38ec221a','Muhammad-turyeeb Bello');
/*!40000 ALTER TABLE `teacher` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-08-14 10:50:17

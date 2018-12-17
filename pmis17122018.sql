-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: localhost    Database: Yii2pmis
-- ------------------------------------------------------
-- Server version	5.7.24-0ubuntu0.16.04.1

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
-- Table structure for table `BusinessRequests`
--

DROP TABLE IF EXISTS `BusinessRequests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BusinessRequests` (
  `idBR` int(11) NOT NULL AUTO_INCREMENT,
  `BRName` varchar(150) DEFAULT 'BR' COMMENT 'Наименование BR',
  `idProject` int(11) DEFAULT NULL,
  `BRLifeCycleType` int(11) DEFAULT NULL COMMENT 'Тип ЖЦ',
  `BRDeleted` int(11) DEFAULT '0',
  `BRNumber` int(11) DEFAULT NULL,
  `BRRoleModelType` int(11) DEFAULT NULL COMMENT 'тип ролевой модели',
  PRIMARY KEY (`idBR`),
  KEY `fk_BusinessRequests_1_idx` (`idProject`),
  KEY `fk_BusinessRequests_2_idx` (`BRLifeCycleType`),
  KEY `fk_BusinessRequests_6_idx` (`BRRoleModelType`),
  CONSTRAINT `fk_BusinessRequests_1` FOREIGN KEY (`idProject`) REFERENCES `Projects` (`idProject`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BusinessRequests_2` FOREIGN KEY (`BRLifeCycleType`) REFERENCES `LifeCycleType` (`idLifeCycleType`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_BusinessRequests_6` FOREIGN KEY (`BRRoleModelType`) REFERENCES `RoleModelType` (`idRoleModelType`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Список BR или  Фазы проекта — совокупность логически связанных операций\nпроекта, завершающихся достижением одного или ряда поставляемых результатов';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BusinessRequests`
--

LOCK TABLES `BusinessRequests` WRITE;
/*!40000 ALTER TABLE `BusinessRequests` DISABLE KEYS */;
INSERT INTO `BusinessRequests` VALUES (1,'test_estimate',1,1,0,4324324,1),(2,'Тестовая Br',1,1,1,54,1),(3,'Автоматизация закрытия карт. Вариант 1',1,1,0,14122,1),(4,'Автоматизация закрытия карт. Вариант 2',1,1,0,14122,1),(5,'тттт',1,1,1,NULL,1),(6,'Тестовая Br2',1,1,0,222,1),(7,'Тестовая Br2',1,1,0,222,1),(8,'434к4а',1,1,0,4334,1),(9,'test091118',1,1,0,91118,1),(10,'121218BR',1,1,0,123,1),(11,'121218BR',1,1,0,123,1),(12,'121218BR',1,1,0,123,1),(13,'121218BR',1,1,0,123,1),(14,'13122018BR1',1,1,0,4324324,1);
/*!40000 ALTER TABLE `BusinessRequests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EnumSettings`
--

DROP TABLE IF EXISTS `EnumSettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EnumSettings` (
  `idEnumSettings` int(11) NOT NULL AUTO_INCREMENT,
  `id_param` int(11) DEFAULT NULL,
  `enm_num_value` decimal(10,2) DEFAULT '0.00',
  `enm_str_value` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`idEnumSettings`),
  KEY `fk_EnumSetings_1_idx` (`id_param`),
  CONSTRAINT `fk_EnumSetings_1` FOREIGN KEY (`id_param`) REFERENCES `settings` (`id_param`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EnumSettings`
--

LOCK TABLES `EnumSettings` WRITE;
/*!40000 ALTER TABLE `EnumSettings` DISABLE KEYS */;
INSERT INTO `EnumSettings` VALUES (16,5,0.00,'http://mantis.it-spectrum.ru/vtb24-mantis/view.php?id='),(21,5,0.00,'http://192.168.20.55/mantis/'),(22,5,0.00,'http://192.168.20.55/mantisbt-2.3.1/view.php?id=');
/*!40000 ALTER TABLE `EnumSettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EstimateWorkPackages`
--

DROP TABLE IF EXISTS `EstimateWorkPackages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EstimateWorkPackages` (
  `idEstimateWorkPackages` int(11) NOT NULL AUTO_INCREMENT,
  `dataEstimate` date DEFAULT NULL,
  `EstimateName` varchar(250) DEFAULT NULL COMMENT 'Наименование оценки',
  `idBR` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  `finished` int(11) DEFAULT '0' COMMENT 'по оценке отправлена стоимость в банк',
  PRIMARY KEY (`idEstimateWorkPackages`),
  KEY `fk_EstimateWorkPackages_1_idx` (`idBR`),
  CONSTRAINT `fk_EstimateWorkPackages_1` FOREIGN KEY (`idBR`) REFERENCES `BusinessRequests` (`idBR`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='Оценки работ,  которые необходимо выполнить для получения результата';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EstimateWorkPackages`
--

LOCK TABLES `EstimateWorkPackages` WRITE;
/*!40000 ALTER TABLE `EstimateWorkPackages` DISABLE KEYS */;
INSERT INTO `EstimateWorkPackages` VALUES (1,'2018-09-12','Предварительная оценка',1,0,0),(2,'2018-09-07','Оценка по экспертизе',1,0,0),(3,'2018-09-19','Предварительная оценка',2,0,0),(4,'2018-09-18','Оценка по экспертизе',2,0,0),(5,'2018-07-12','Оценка по экспертизе',3,0,0),(6,'2018-09-19','оценка по БФТЗ',3,0,0),(7,'2018-09-20','Предварительная оценка',4,0,1),(8,'2018-10-02','Окончательная оценка',4,0,0),(9,'2018-10-30','Предварительная оценка',7,0,0),(10,'2018-10-30','Предварительная оценка',8,0,0),(11,'2018-10-31','Предварительная оценка 222',8,0,0),(12,'2018-09-12','Предварительная оценкакопия ',1,0,0),(13,'2018-09-12','Предварительная оценка копия ',1,0,0),(14,'2018-09-12','Предварительная оценка копия ',1,0,0),(15,'2018-09-12','Предварительная оценка копия ',1,0,0),(16,'2018-09-12','Предварительная оценка копия ',1,0,0),(17,'2018-09-12','Предварительная оценка копия ',1,0,0),(18,'2018-09-12','Предварительная оценка копия ',1,0,0),(19,'2018-09-12','Предварительная оценка копия ',1,0,0),(20,'2018-09-12','Предварительная оценка копия ',1,0,0),(21,'2018-09-12','Предварительная оценка копия ',1,0,0),(22,'2018-09-12','Предварительная оценка копия ',1,0,0),(23,'2018-09-12','Предварительная оценка копия ',1,0,0),(24,'2018-07-12','Оценка по экспертизе копия ',3,0,1),(25,'2018-12-07','оценка по БФТЗ копия ',3,0,0),(26,'2018-12-06','оценка по БФТЗ копия 4444',3,0,1),(27,'2018-12-06','оценка по БФТЗ копия 4444 копия ',3,0,0),(28,'2018-12-14','Предварительная оценка',14,0,0);
/*!40000 ALTER TABLE `EstimateWorkPackages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LifeCycleStages`
--

DROP TABLE IF EXISTS `LifeCycleStages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LifeCycleStages` (
  `idStage` int(11) NOT NULL AUTO_INCREMENT,
  `StageName` varchar(145) DEFAULT NULL,
  `StageOrder` int(11) DEFAULT NULL,
  `idlifeCycleType` int(11) DEFAULT NULL,
  `LCS_parent_id` int(11) NOT NULL COMMENT 'id родителя(для второго уровня wbs)',
  `LCS_comment` varchar(250) DEFAULT NULL,
  `idResultType` int(11) DEFAULT NULL,
  PRIMARY KEY (`idStage`),
  KEY `fk_LifeCircle_1_idx` (`idlifeCycleType`),
  KEY `fk_LifeCycleStages_1_idx` (`idResultType`),
  CONSTRAINT `fk_LifeCircle_1` FOREIGN KEY (`idlifeCycleType`) REFERENCES `LifeCycleType` (`idLifeCycleType`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_LifeCycleStages_1` FOREIGN KEY (`idResultType`) REFERENCES `ResultType` (`idResultType`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='Шаблон WBS.\nЖизненный цикл фазы(BR) -  этапы работ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LifeCycleStages`
--

LOCK TABLES `LifeCycleStages` WRITE;
/*!40000 ALTER TABLE `LifeCycleStages` DISABLE KEYS */;
INSERT INTO `LifeCycleStages` VALUES (1,'Предварительная оценка',1,1,0,NULL,4),(2,'Экспертиза',2,1,0,NULL,1),(3,'БФТЗ',3,1,0,NULL,2),(4,'Реализация и внутреннее тестирование функционала',4,1,0,NULL,3),(5,'Протестировано по методикам банка',5,1,0,NULL,4);
/*!40000 ALTER TABLE `LifeCycleStages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LifeCycleType`
--

DROP TABLE IF EXISTS `LifeCycleType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LifeCycleType` (
  `idLifeCycleType` int(11) NOT NULL AUTO_INCREMENT,
  `LifeCycleTypeName` varchar(45) DEFAULT NULL,
  `LifeCycleTypeComent` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`idLifeCycleType`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Тип  жизненного цикла';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LifeCycleType`
--

LOCK TABLES `LifeCycleType` WRITE;
/*!40000 ALTER TABLE `LifeCycleType` DISABLE KEYS */;
INSERT INTO `LifeCycleType` VALUES (1,'ВТБ ЖЦПО',NULL),(2,'ВТБ предварительная оценка',NULL),(3,'ВТБ согласование МТ',NULL);
/*!40000 ALTER TABLE `LifeCycleType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Organization`
--

DROP TABLE IF EXISTS `Organization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Organization` (
  `idOrganization` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerName` varchar(120) DEFAULT NULL,
  `ShortName` varchar(45) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`idOrganization`),
  UNIQUE KEY `ShortName_UNIQUE` (`ShortName`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Клиенты - Юридические лица';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Organization`
--

LOCK TABLES `Organization` WRITE;
/*!40000 ALTER TABLE `Organization` DISABLE KEYS */;
INSERT INTO `Organization` VALUES (1,'ЗАО Спектр','Спектр',0),(2,'ПАО ВТБ','ВТБ',0);
/*!40000 ALTER TABLE `Organization` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `People`
--

DROP TABLE IF EXISTS `People`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `People` (
  `idHuman` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL DEFAULT '_',
  `Family` varchar(100) NOT NULL DEFAULT '_',
  `idOrganization` int(11) DEFAULT NULL COMMENT 'организация,  сотрудником которой является человек',
  `Humandeleted` int(11) DEFAULT '0',
  `patronymic` varchar(100) NOT NULL DEFAULT '_' COMMENT 'отчество',
  `phone_number` varchar(45) DEFAULT NULL,
  `internal_phone_number` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idHuman`),
  KEY `fk_People_Organization_idx` (`idOrganization`),
  CONSTRAINT `fk_People_Organization` FOREIGN KEY (`idOrganization`) REFERENCES `Organization` (`idOrganization`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='Физические лица';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `People`
--

LOCK TABLES `People` WRITE;
/*!40000 ALTER TABLE `People` DISABLE KEYS */;
INSERT INTO `People` VALUES (-1,'Nobody',' ',1,1,' ',NULL,NULL,NULL),(1,'Тимур','Перелыгин',1,0,'Вадимович','8(495)616-30-63','7004','peelygin@it-spectrum.ru'),(2,'Александр','Озеров',2,0,'_',NULL,NULL,NULL),(3,'Вера','Гончар',1,0,'Павловна',NULL,NULL,NULL),(4,'Андрей','Москалев',1,0,'Васильевич',NULL,NULL,NULL),(5,'Олег','Цыганок',1,0,'Владимирович',NULL,NULL,NULL),(6,'Владимир','Савельев',1,0,'Александрович',NULL,NULL,NULL),(7,'Денис','Ларин',1,0,'Юрьевич',NULL,NULL,NULL),(8,'Светлана','Рыкалова',1,0,'Андреевна',NULL,NULL,NULL),(9,'Мария','Киреева',1,0,'Валерьевна',NULL,NULL,NULL),(10,'Игорь','Ольвовский',1,0,'Александрович',NULL,NULL,NULL),(19,'Сергей','Новиков',1,1,'_',NULL,NULL,NULL),(20,'Новый','Новиков',NULL,0,'_',NULL,NULL,NULL),(21,'Новый','Новиков',NULL,0,'_',NULL,NULL,NULL);
/*!40000 ALTER TABLE `People` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProjectCommand`
--

DROP TABLE IF EXISTS `ProjectCommand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ProjectCommand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `idBR` int(11) NOT NULL,
  `idRole` int(11) NOT NULL,
  `idHuman` int(11) NOT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_idx` (`parent_id`,`idBR`,`idRole`,`idHuman`),
  KEY `fk_ProjectCommand_1_idx` (`idHuman`),
  KEY `fk_ProjectCommand_2_idx` (`idBR`),
  KEY `fk_ProjectCommand_3_idx` (`idRole`),
  CONSTRAINT `fk_ProjectCommand_1` FOREIGN KEY (`idHuman`) REFERENCES `People` (`idHuman`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ProjectCommand_2` FOREIGN KEY (`idBR`) REFERENCES `BusinessRequests` (`idBR`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ProjectCommand_3` FOREIGN KEY (`idRole`) REFERENCES `RoleModel` (`idRole`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ProjectCommand`
--

LOCK TABLES `ProjectCommand` WRITE;
/*!40000 ALTER TABLE `ProjectCommand` DISABLE KEYS */;
INSERT INTO `ProjectCommand` VALUES (1,0,1,1,-1,0),(2,0,1,2,-1,0),(3,0,1,3,-1,0),(4,0,1,4,-1,0),(5,0,1,5,-1,0),(6,0,1,6,-1,0),(7,0,1,7,-1,0),(8,2,1,2,1,0),(9,2,1,2,2,0),(10,6,1,6,2,0),(11,0,2,1,-1,0),(12,0,2,2,-1,0),(13,0,2,3,-1,0),(14,0,2,4,-1,0),(15,0,2,5,-1,0),(16,0,2,6,-1,0),(17,0,2,7,-1,0),(18,11,2,1,1,0),(19,12,2,2,2,0),(20,0,3,1,-1,0),(21,0,3,2,-1,0),(22,0,3,3,-1,0),(23,0,3,4,-1,0),(24,0,3,5,-1,0),(25,0,3,6,-1,0),(26,0,3,7,-1,0),(27,21,3,2,1,0),(28,22,3,3,3,0),(29,24,3,5,4,0),(30,25,3,6,5,0),(31,25,3,6,6,0),(32,25,3,6,7,0),(33,23,3,4,8,0),(34,26,3,7,1,0),(35,23,3,4,9,0),(36,23,3,4,10,0),(37,0,4,1,-1,0),(38,0,4,2,-1,0),(39,0,4,3,-1,0),(40,0,4,4,-1,0),(41,0,4,5,-1,0),(42,0,4,6,-1,0),(43,0,4,7,-1,0),(44,38,4,2,1,0),(45,39,4,3,3,0),(46,40,4,4,8,0),(47,40,4,4,9,0),(48,40,4,4,10,0),(49,41,4,5,4,0),(50,42,4,6,6,0),(51,42,4,6,5,0),(52,43,4,7,1,0),(53,20,3,1,2,0),(54,37,4,1,2,0),(55,0,5,1,-1,0),(56,0,5,2,-1,0),(57,0,5,3,-1,0),(58,0,5,4,-1,0),(59,0,5,5,-1,0),(60,0,5,6,-1,0),(61,0,5,7,-1,0),(62,0,6,1,-1,0),(63,0,6,2,-1,0),(64,0,6,3,-1,0),(65,0,6,4,-1,0),(66,0,6,5,-1,0),(67,0,6,6,-1,0),(68,0,6,7,-1,0),(69,0,7,1,-1,0),(70,0,7,2,-1,0),(71,0,7,3,-1,0),(72,0,7,4,-1,0),(73,0,7,5,-1,0),(74,0,7,6,-1,0),(75,0,7,7,-1,0),(76,70,7,2,3,0),(77,71,7,3,8,0),(78,73,7,5,2,0),(79,0,8,1,-1,0),(80,0,8,2,-1,0),(81,0,8,3,-1,0),(82,0,8,4,-1,0),(83,0,8,5,-1,0),(84,0,8,6,-1,0),(85,0,8,7,-1,0),(86,79,8,1,3,0),(87,81,8,3,5,0),(88,84,8,6,10,0),(89,0,9,1,-1,0),(90,0,9,2,-1,0),(91,0,9,3,-1,0),(92,0,9,4,-1,0),(93,0,9,5,-1,0),(94,0,9,6,-1,0),(95,0,9,7,-1,0),(96,89,9,1,5,0),(97,91,9,3,3,0),(98,0,3,8,-1,0),(99,98,3,8,21,0),(100,98,3,8,2,0),(101,0,10,1,-1,0),(102,0,10,2,-1,0),(103,0,10,3,-1,0),(104,0,10,4,-1,0),(105,0,10,5,-1,0),(106,0,10,6,-1,0),(107,0,10,7,-1,0),(108,0,10,8,-1,0),(109,0,11,1,-1,0),(110,0,11,2,-1,0),(111,0,11,3,-1,0),(112,0,11,4,-1,0),(113,0,11,5,-1,0),(114,0,11,6,-1,0),(115,0,11,7,-1,0),(116,0,11,8,-1,0),(117,0,12,1,-1,0),(118,0,12,2,-1,0),(119,0,12,3,-1,0),(120,0,12,4,-1,0),(121,0,12,5,-1,0),(122,0,12,6,-1,0),(123,0,12,7,-1,0),(124,0,12,8,-1,0),(125,0,13,1,-1,0),(126,0,13,2,-1,0),(127,0,13,3,-1,0),(128,0,13,4,-1,0),(129,0,13,5,-1,0),(130,0,13,6,-1,0),(131,0,13,7,-1,0),(132,0,13,8,-1,0),(133,0,14,1,-1,0),(134,0,14,2,-1,0),(135,0,14,3,-1,0),(136,0,14,4,-1,0),(137,0,14,5,-1,0),(138,0,14,6,-1,0),(139,0,14,7,-1,0),(140,0,14,8,-1,0),(141,136,14,4,3,0);
/*!40000 ALTER TABLE `ProjectCommand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Projects`
--

DROP TABLE IF EXISTS `Projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Projects` (
  `idProject` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(100) DEFAULT NULL,
  `DataBegin` date DEFAULT NULL,
  `DataEnd` date DEFAULT NULL,
  `idOrganization` int(11) DEFAULT NULL,
  PRIMARY KEY (`idProject`),
  KEY `fk_Projects_Organization1_idx` (`idOrganization`),
  CONSTRAINT `fk_Projects_Organization1` FOREIGN KEY (`idOrganization`) REFERENCES `Organization` (`idOrganization`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='проекты';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Projects`
--

LOCK TABLES `Projects` WRITE;
/*!40000 ALTER TABLE `Projects` DISABLE KEYS */;
INSERT INTO `Projects` VALUES (1,'ВТБ-фронт',NULL,NULL,1),(2,'Ростбанк',NULL,NULL,NULL);
/*!40000 ALTER TABLE `Projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Prv_estimate`
--

DROP TABLE IF EXISTS `Prv_estimate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Prv_estimate` (
  `idPrv_stm` int(11) NOT NULL AUTO_INCREMENT,
  `Prv_stm_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idPrv_stm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Предварительные оценки';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Prv_estimate`
--

LOCK TABLES `Prv_estimate` WRITE;
/*!40000 ALTER TABLE `Prv_estimate` DISABLE KEYS */;
/*!40000 ALTER TABLE `Prv_estimate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ResultEvents`
--

DROP TABLE IF EXISTS `ResultEvents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ResultEvents` (
  `idResultEvents` int(11) NOT NULL AUTO_INCREMENT,
  `idwbs` int(11) DEFAULT NULL,
  `ResultEventsDate` datetime DEFAULT NULL,
  `ResultEventsDescription` varchar(1000) DEFAULT NULL,
  `ResultEventsName` varchar(100) DEFAULT NULL,
  `ResultEventsMantis` varchar(45) DEFAULT NULL,
  `ResultEventResponsible` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`idResultEvents`),
  KEY `fk_ResultEvents_1_idx` (`idwbs`),
  KEY `fk_ResultEvents_2_idx` (`ResultEventResponsible`),
  CONSTRAINT `fk_ResultEvents_1` FOREIGN KEY (`idwbs`) REFERENCES `wbs` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_ResultEvents_2` FOREIGN KEY (`ResultEventResponsible`) REFERENCES `ProjectCommand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='События по результатам';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ResultEvents`
--

LOCK TABLES `ResultEvents` WRITE;
/*!40000 ALTER TABLE `ResultEvents` DISABLE KEYS */;
INSERT INTO `ResultEvents` VALUES (11,60,'2018-09-27 18:45:00','','Новое событие',NULL,8,1),(12,60,'2018-09-28 00:00:00','','Новое событие',NULL,8,1),(13,60,'2018-09-28 10:48:15','','Новое событие',NULL,8,1),(14,60,'2018-09-27 10:55:04','','Новое событие',NULL,8,0),(15,60,'2018-09-28 17:23:05','','Новое событие',NULL,9,0),(16,60,'2018-09-28 17:23:21','<p>ddddd</p>','Новое событие',NULL,8,0),(17,60,'2018-09-26 17:38:18','','Новое событие',NULL,10,0),(19,60,'2018-09-28 17:50:49','','ЖППЧШ444',NULL,8,0),(20,82,'2018-09-28 17:54:06','','Новое событие 1',NULL,27,0),(21,82,'2018-09-27 17:54:15','','Новое событие',NULL,28,0),(22,82,'2018-09-25 17:54:26','','Новое событие 3',NULL,31,0),(23,82,'2018-09-28 18:03:42','','Новое событие',NULL,53,0),(24,82,'2018-09-28 18:37:32','','Новое событие',NULL,27,0),(25,60,'2018-10-08 12:11:35','','Новое событие',NULL,8,0),(26,84,'2018-10-15 11:46:51',NULL,'Новое событие',NULL,27,0),(27,158,'2018-11-09 13:32:32','','Новое событие',NULL,97,0),(28,150,'2018-11-09 13:40:13','','Новое событие',NULL,88,0),(29,82,'2018-12-06 19:47:42','','Новое событие',NULL,27,0);
/*!40000 ALTER TABLE `ResultEvents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ResultStatus`
--

DROP TABLE IF EXISTS `ResultStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ResultStatus` (
  `idResultStatus` int(11) NOT NULL,
  `ResultStatusName` varchar(45) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`idResultStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ResultStatus`
--

LOCK TABLES `ResultStatus` WRITE;
/*!40000 ALTER TABLE `ResultStatus` DISABLE KEYS */;
INSERT INTO `ResultStatus` VALUES (1,'В ожидании',0),(2,'В работе',0),(3,'Выполнен',0),(4,'Приостановлен',0);
/*!40000 ALTER TABLE `ResultStatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ResultType`
--

DROP TABLE IF EXISTS `ResultType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ResultType` (
  `idResultType` int(11) NOT NULL AUTO_INCREMENT,
  `ResultTypeName` varchar(100) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`idResultType`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Тип результата';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ResultType`
--

LOCK TABLES `ResultType` WRITE;
/*!40000 ALTER TABLE `ResultType` DISABLE KEYS */;
INSERT INTO `ResultType` VALUES (1,'Экспертиза',0),(2,'БФТЗ',0),(3,'Программное обеспечение',0),(4,'Прочее',0);
/*!40000 ALTER TABLE `ResultType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RoleModel`
--

DROP TABLE IF EXISTS `RoleModel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RoleModel` (
  `idRole` int(11) NOT NULL AUTO_INCREMENT,
  `idRoleModelType` int(11) DEFAULT NULL,
  `RoleName` varchar(45) DEFAULT NULL,
  `RoleComment` varchar(300) DEFAULT NULL,
  `idTariff` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRole`),
  KEY `fk_RoleModel_1_idx` (`idRoleModelType`),
  KEY `fk_RoleModel_2_idx` (`idTariff`),
  CONSTRAINT `fk_RoleModel_1` FOREIGN KEY (`idRoleModelType`) REFERENCES `RoleModelType` (`idRoleModelType`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_RoleModel_2` FOREIGN KEY (`idTariff`) REFERENCES `Tariff` (`idTariff`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RoleModel`
--

LOCK TABLES `RoleModel` WRITE;
/*!40000 ALTER TABLE `RoleModel` DISABLE KEYS */;
INSERT INTO `RoleModel` VALUES (1,1,'Главный технолог','Выполняет разработку экспертизы вцелом',1),(2,1,'Технолог-участник','Выполняет разработку в части требований к конкретной системе',1),(3,1,'Аналитик','Пишет БФТЗ',1),(4,1,'Разработчик','',4),(5,1,'Архитектор',NULL,3),(6,1,'Инженер по тестированию','',6),(7,1,'Менеджер проекта',NULL,1),(8,1,'Технический писатель','Разрабатывает документацию',1);
/*!40000 ALTER TABLE `RoleModel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RoleModelType`
--

DROP TABLE IF EXISTS `RoleModelType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RoleModelType` (
  `idRoleModelType` int(11) NOT NULL AUTO_INCREMENT,
  `RoleModelTypeName` varchar(45) DEFAULT NULL,
  `RoleModelTypeComment` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`idRoleModelType`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RoleModelType`
--

LOCK TABLES `RoleModelType` WRITE;
/*!40000 ALTER TABLE `RoleModelType` DISABLE KEYS */;
INSERT INTO `RoleModelType` VALUES (1,'Ролевая модель ВТБ',NULL);
/*!40000 ALTER TABLE `RoleModelType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SystemVersions`
--

DROP TABLE IF EXISTS `SystemVersions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SystemVersions` (
  `idsystem_versions` int(11) NOT NULL AUTO_INCREMENT,
  `release_date` date DEFAULT NULL,
  `commit_ date` date DEFAULT NULL,
  `version_number` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  `released` int(11) DEFAULT '0' COMMENT 'версия выпущена\n',
  PRIMARY KEY (`idsystem_versions`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Поставляемые в  банк версии ПО';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SystemVersions`
--

LOCK TABLES `SystemVersions` WRITE;
/*!40000 ALTER TABLE `SystemVersions` DISABLE KEYS */;
INSERT INTO `SystemVersions` VALUES (1,'2018-11-30','2018-11-26',23,0,0),(2,'2019-01-18','2019-01-14',24,0,0);
/*!40000 ALTER TABLE `SystemVersions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Systemlog`
--

DROP TABLE IF EXISTS `Systemlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Systemlog` (
  `idSystemlog` int(11) NOT NULL AUTO_INCREMENT,
  `IdTypeObject` int(11) DEFAULT NULL COMMENT 'Тип объекта логирования \n1 - BR\n2 -  wbs\n3 - оценка трудозатрат\n4 - работа \n5 -  оценка работы ',
  `idObject` int(11) DEFAULT NULL,
  `SystemLogString` varchar(4500) DEFAULT NULL,
  `IdUser` int(11) DEFAULT NULL,
  `DataChange` datetime DEFAULT NULL,
  PRIMARY KEY (`idSystemlog`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Systemlog`
--

LOCK TABLES `Systemlog` WRITE;
/*!40000 ALTER TABLE `Systemlog` DISABLE KEYS */;
INSERT INTO `Systemlog` VALUES (1,4,180,'perelygin => Работа изменена: 180 воду носить id Br:60 id оценки: 1 mantisNumber 233333',1,'2018-12-14 17:09:08'),(2,4,180,'perelygin => Работа изменена: 180 воду носить id Br:60 id оценки: 1 mantisNumber 233333',1,'2018-12-14 17:09:55'),(3,4,180,'perelygin => Трудозатраты по работе 180 изменены: 3.00',1,'2018-12-14 17:09:55'),(4,4,181,'perelygin => Работа изменена: 181 Зубы лечить id Br:60 id оценки: 1 mantisNumber 4444',1,'2018-12-14 17:10:33'),(5,4,181,'perelygin => Работа изменена: 181 Зубы лечить id Br:60 id оценки: 1 mantisNumber 4444',1,'2018-12-14 17:11:02'),(6,4,181,'perelygin => Трудозатраты по работе 181 изменены: 0.00',1,'2018-12-14 17:11:02'),(7,4,181,'perelygin => Работа изменена: 181 Зубы лечить id Br:60 id оценки: 1 mantisNumber 4444',1,'2018-12-14 17:11:23'),(8,4,181,'perelygin => Трудозатраты по работе 181 изменены: 0.00',1,'2018-12-14 17:11:23'),(9,4,181,'perelygin => Работа изменена: 181 Зубы лечить id Br:60 id оценки: 1 mantisNumber 4444',1,'2018-12-14 17:12:06'),(10,4,181,'perelygin => Трудозатраты по работе 181 изменены: 16.00',1,'2018-12-14 17:12:06'),(11,4,157,'perelygin => Работа изменена: 157 Трубу шатать id Br:60 id оценки: 1 mantisNumber 25555',1,'2018-12-14 17:57:05'),(12,4,157,'perelygin => Трудозатраты по работе 157 изменены: 6.00',1,'2018-12-14 17:57:05'),(13,4,181,'perelygin => Работа изменена: 181 Зубы лечить id Br:60 id оценки: 1 mantisNumber 4444',1,'2018-12-14 18:06:51'),(14,4,181,'perelygin => Трудозатраты по работе 181 изменены: 16.00',1,'2018-12-14 18:06:51'),(15,4,157,'perelygin => Работа изменена: 157 Трубу шатать id Br:60 id оценки: 1 mantisNumber 25555',1,'2018-12-14 18:17:13'),(16,4,157,'perelygin => Трудозатраты по работе 157 изменены: 6.00',1,'2018-12-14 18:17:13'),(17,4,157,'perelygin => Работа изменена: 157 Трубу шатать id Br:60 id оценки: 1 mantisNumber 25555',1,'2018-12-14 18:17:33'),(18,4,157,'perelygin => Трудозатраты по работе 157 изменены: 84.00',1,'2018-12-14 18:17:33'),(19,4,157,'perelygin => Работа изменена: 157 Трубу шатать id Br:60 id оценки: 1 mantisNumber 25555',1,'2018-12-14 18:17:58'),(20,4,157,'perelygin => Трудозатраты по работе 157 изменены: 84.00',1,'2018-12-14 18:17:58'),(21,4,157,'perelygin => Работа изменена: 157 Трубу шатать id Br:60 id оценки: 1 mantisNumber 25555',1,'2018-12-14 18:18:27'),(22,4,157,'perelygin => Трудозатраты по работе 157 изменены: 83.00',1,'2018-12-14 18:18:27'),(23,4,157,'perelygin => Работа изменена: 157 Трубу шатать id Br:60 id оценки: 1 mantisNumber 25555',1,'2018-12-14 18:21:21'),(24,4,157,'perelygin => Трудозатраты по работе 157 изменены: 83.00',1,'2018-12-14 18:21:21'),(25,4,181,'perelygin => Работа изменена: 181 Зубы лечить id Br:60 id оценки: 1 mantisNumber 4444',1,'2018-12-14 18:24:08'),(26,4,181,'perelygin => Трудозатраты по работе 181 изменены: 16.00',1,'2018-12-14 18:24:08'),(27,4,177,'perelygin => Работа изменена: 177 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:24:39'),(28,4,177,'perelygin => Работа изменена: 177 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:24:44'),(29,4,179,'perelygin => Работа изменена: 179 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:26:59'),(30,4,179,'perelygin => Работа изменена: 179 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:27:15'),(31,4,186,'perelygin => Работа добавлена: 186 Название работы id Br:182 id оценки: 28 mantisNumber ',1,'2018-12-14 18:27:46'),(32,4,186,'perelygin => Работа изменена: 186 Название работы id Br:182 id оценки: 28 mantisNumber ',1,'2018-12-14 18:27:49'),(33,4,186,'perelygin => Работа изменена: 186 Название работы id Br:182 id оценки: 28 mantisNumber ',1,'2018-12-14 18:28:00'),(34,4,177,'perelygin => Работа изменена: 177 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:28:52'),(35,4,177,'perelygin => Трудозатраты по работе 177 изменены: 0.00',1,'2018-12-14 18:28:52'),(36,4,179,'perelygin => Работа изменена: 179 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:28:58'),(37,4,179,'perelygin => Трудозатраты по работе 179 изменены: 0.00',1,'2018-12-14 18:28:58'),(38,4,177,'perelygin => Работа изменена: 177 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:31:19'),(39,4,177,'perelygin => Трудозатраты по работе 177 изменены: 44.00',1,'2018-12-14 18:31:19'),(40,4,177,'perelygin => Работа изменена: 177 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:35:17'),(41,4,177,'perelygin => Трудозатраты по работе 177 изменены: 44.00',1,'2018-12-14 18:35:17'),(42,4,178,'perelygin => Работа изменена: 178 Название работы id Br:179 id оценки: 28 mantisNumber ',1,'2018-12-14 18:35:26');
/*!40000 ALTER TABLE `Systemlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tariff`
--

DROP TABLE IF EXISTS `Tariff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tariff` (
  `idTariff` int(11) NOT NULL AUTO_INCREMENT,
  `TariffName` varchar(70) DEFAULT NULL,
  `TariffRate` decimal(10,0) DEFAULT '0',
  `idProject` int(11) DEFAULT NULL,
  PRIMARY KEY (`idTariff`),
  KEY `fk_Tariff_1_idx` (`idProject`),
  CONSTRAINT `fk_Tariff_1` FOREIGN KEY (`idProject`) REFERENCES `Projects` (`idProject`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Ставки ролей';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tariff`
--

LOCK TABLES `Tariff` WRITE;
/*!40000 ALTER TABLE `Tariff` DISABLE KEYS */;
INSERT INTO `Tariff` VALUES (1,'Ведущий бизнес аналитик',20000,1),(2,'Бизнес аналитик',18800,1),(3,'Системный архитектор',20000,1),(4,'Ведущий разработчик',20000,1),(5,'Разработчик',18800,1),(6,'Инженер по тестированию ПО',15600,1),(7,'Специалист службы сопровождения и поддержки',18800,1);
/*!40000 ALTER TABLE `Tariff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `WorkEffort`
--

DROP TABLE IF EXISTS `WorkEffort`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `WorkEffort` (
  `idLaborExpenditures` int(11) NOT NULL AUTO_INCREMENT,
  `idWorksOfEstimate` int(11) DEFAULT NULL,
  `idTeamMember` int(11) DEFAULT NULL COMMENT 'id члена команды',
  `workEffort` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`idLaborExpenditures`),
  KEY `fk_LaborExpenditures_1_idx` (`idWorksOfEstimate`),
  KEY `fk_LaborExpenditures_2_idx` (`idTeamMember`),
  CONSTRAINT `fk_LaborExpenditures_1` FOREIGN KEY (`idWorksOfEstimate`) REFERENCES `WorksOfEstimate` (`idWorksOfEstimate`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_LaborExpenditures_2` FOREIGN KEY (`idTeamMember`) REFERENCES `ProjectCommand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8 COMMENT='Трудозатраты';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `WorkEffort`
--

LOCK TABLES `WorkEffort` WRITE;
/*!40000 ALTER TABLE `WorkEffort` DISABLE KEYS */;
INSERT INTO `WorkEffort` VALUES (3,4,9,9.00),(5,4,10,98.00),(6,13,18,7.00),(7,14,18,0.00),(8,14,18,0.00),(9,15,18,6.00),(10,15,19,5.00),(11,16,19,55.00),(12,16,19,44.00),(14,12,18,14.00),(15,12,18,15.00),(16,18,18,16.00),(17,18,18,17.00),(19,19,19,19.00),(20,19,18,20.00),(21,19,18,21.00),(23,21,18,44.00),(24,21,18,67.00),(25,21,18,399.00),(26,21,19,77.00),(27,22,19,1.00),(28,22,19,3.00),(29,22,18,2.00),(30,22,18,0.00),(31,24,19,0.00),(32,24,18,12.00),(33,23,19,11111.00),(34,23,18,333333.00),(38,3,8,222.00),(39,3,8,1.00),(41,9,8,5.00),(43,28,28,1.00),(44,29,27,6.00),(45,30,27,4.00),(48,31,27,2.00),(49,31,28,2.00),(50,31,29,1.00),(51,33,28,4.00),(52,34,28,2.00),(53,35,28,1.00),(54,36,31,2.00),(56,37,31,3.00),(57,38,32,1.00),(59,39,30,3.00),(61,40,30,2.00),(63,41,28,1.00),(64,42,33,2.00),(65,42,30,2.00),(66,43,36,2.00),(67,44,33,1.00),(68,44,30,0.50),(69,45,36,2.00),(70,45,30,1.00),(71,46,28,0.50),(72,46,30,0.50),(73,40,33,4.00),(114,53,33,1.50),(115,53,30,1.00),(116,54,35,0.50),(117,54,30,0.50),(118,55,36,5.00),(119,56,36,10.00),(120,57,35,0.50),(121,57,30,0.50),(122,58,45,1.00),(123,59,44,4.00),(124,60,44,2.00),(125,60,45,3.00),(126,59,45,5.00),(127,61,45,4.00),(128,62,45,2.00),(129,63,45,1.00),(130,64,50,2.00),(131,65,46,4.00),(132,65,51,2.00),(133,66,45,1.00),(134,67,45,2.00),(135,67,51,2.00),(136,68,48,2.00),(138,69,48,1.50),(139,69,51,1.00),(140,70,45,0.50),(143,71,48,0.50),(145,72,48,0.50),(146,73,46,0.50),(147,73,51,0.50),(148,74,8,0.00),(149,75,48,55.00),(150,75,47,44.00),(152,76,44,6.00),(153,76,44,77.00),(154,77,31,4.00),(155,77,27,2.00),(156,78,32,4.00),(157,79,27,0.60),(158,81,44,3.00),(159,40,27,0.00),(160,44,35,7.00),(161,44,31,5.00),(162,29,27,3.00),(163,29,30,3.00),(164,29,32,1.00),(166,85,87,1.00),(167,86,88,1.00),(168,87,87,555.00),(169,88,88,5.00),(170,89,8,0.00),(171,74,9,0.00),(172,90,10,0.00),(173,91,8,0.00),(174,4,9,9.00),(175,4,10,98.00),(177,4,9,9.00),(178,4,10,98.00),(179,4,9,9.00),(180,4,10,98.00),(184,4,9,9.00),(185,4,10,98.00),(186,4,9,9.00),(187,4,10,98.00),(188,4,9,9.00),(189,4,10,98.00),(190,4,9,9.00),(191,4,10,98.00),(199,99,8,222.00),(200,99,8,1.00),(202,100,8,5.00),(203,102,8,0.00),(204,102,9,0.00),(206,103,8,0.00),(207,104,10,0.00),(208,105,8,0.00),(209,106,28,1.00),(210,107,27,6.00),(211,107,27,3.00),(212,107,30,3.00),(213,107,32,1.00),(217,108,27,4.00),(218,109,27,2.00),(219,109,28,2.00),(220,109,29,1.00),(221,110,28,4.00),(222,111,28,2.00),(223,112,28,1.00),(224,113,31,2.00),(225,114,31,3.00),(226,115,32,1.00),(227,116,30,3.00),(228,117,30,2.00),(229,117,33,4.00),(230,117,27,0.00),(231,118,28,1.00),(232,119,33,2.00),(233,119,30,2.00),(235,120,36,2.00),(236,121,33,1.00),(237,121,30,0.50),(238,121,35,7.00),(239,121,31,5.00),(243,122,36,2.00),(244,122,30,1.00),(246,123,28,0.50),(247,123,30,0.50),(249,124,33,1.50),(250,124,30,1.00),(252,125,35,0.50),(253,125,30,0.50),(255,126,36,5.00),(256,127,36,10.00),(257,128,35,0.50),(258,128,30,0.50),(259,129,27,0.30),(260,130,30,0.31),(262,158,31,4.00),(263,158,27,2.00),(265,159,32,4.00),(266,160,27,0.60),(267,162,27,0.30),(268,163,30,0.31),(269,164,31,4.00),(270,164,27,2.00),(272,165,32,4.00),(273,166,27,0.60),(274,168,27,0.30),(275,169,30,0.31),(276,170,31,4.00),(277,170,27,2.00),(279,171,32,4.00),(280,172,27,0.60),(281,174,27,0.30),(282,175,30,0.31),(283,179,141,0.00),(284,157,9,2.00),(285,157,10,3.00),(286,180,8,1.00),(287,180,8,2.00),(288,180,8,0.00),(289,181,9,3.00),(290,181,10,5.00),(291,181,8,8.00),(292,157,8,78.00),(293,177,141,44.00),(294,177,141,0.00),(295,178,141,0.00);
/*!40000 ALTER TABLE `WorkEffort` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `WorksOfEstimate`
--

DROP TABLE IF EXISTS `WorksOfEstimate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `WorksOfEstimate` (
  `idWorksOfEstimate` int(11) NOT NULL AUTO_INCREMENT,
  `idEstimateWorkPackages` int(11) NOT NULL,
  `WorkName` varchar(250) NOT NULL,
  `idWbs` int(11) DEFAULT NULL,
  `WorkDescription` varchar(1000) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  `mantisNumber` varchar(45) DEFAULT NULL COMMENT 'номер  инцидента в мантис',
  PRIMARY KEY (`idWorksOfEstimate`),
  KEY `fk_WorksOfEstimate_1_idx` (`idEstimateWorkPackages`),
  KEY `fk_WorksOfEstimate_2_idx` (`idWbs`),
  CONSTRAINT `fk_WorksOfEstimate_1` FOREIGN KEY (`idEstimateWorkPackages`) REFERENCES `EstimateWorkPackages` (`idEstimateWorkPackages`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_WorksOfEstimate_2` FOREIGN KEY (`idWbs`) REFERENCES `wbs` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 COMMENT='Работы, включенные в оценку';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `WorksOfEstimate`
--

LOCK TABLES `WorksOfEstimate` WRITE;
/*!40000 ALTER TABLE `WorksOfEstimate` DISABLE KEYS */;
INSERT INTO `WorksOfEstimate` VALUES (3,1,'Название работы 3',65,'Описание работы',0,NULL),(4,2,'Название работы 4',65,'Описание работы',0,NULL),(5,2,'Название работы 5',65,'Описание работы',0,NULL),(7,2,'Название работы',65,'Описание работы',0,NULL),(8,2,'Название работы',65,'Описание работы',0,NULL),(9,1,'Название работы',65,'Описание работы',0,NULL),(10,1,'Название работы',65,'Описание работы',0,NULL),(11,2,'Название работы',65,'Описание работы',0,NULL),(12,3,'полить цветы молоком',76,'<p>Описание работы</p>',0,NULL),(13,3,'Название работы',71,'Описание работы',0,NULL),(14,3,'Название работы',71,'Описание работы',0,NULL),(15,3,'Раздать ЦУ',77,'<p>Описание работы</p>',0,NULL),(16,3,'Название работы',77,'Описание работы',0,NULL),(17,3,'Название работы',77,'Описание работы',0,NULL),(18,3,'Название работы',76,'Описание работы',0,NULL),(19,3,'Прополоть огород',76,'<p>Описание работы</p>',0,NULL),(20,3,'поклеить  обои',78,'<ul><li>Сильно поклеить</li></ul>',0,NULL),(21,4,'пильть комить',76,'<p>Описание работы</p>',0,NULL),(22,4,'солить долбить',76,'<p>Описание работы</p>',0,NULL),(23,4,'Название работы для удаления',77,'<p>Описание работы</p>',0,NULL),(24,4,'Приволочь мамонта',77,'<p>Описание работы</p>',0,NULL),(25,4,'Название работы',77,'Описание работы',0,NULL),(26,4,'Название работы',77,'<p>Описание работы</p>',0,NULL),(27,4,'Название работы',77,'Описание работы',0,NULL),(28,5,'Анализ требований на основании материалов установочной встречи',82,'<p>Описание работы</p>',0,'17648'),(29,5,'Разработка экспертизы в части требований к Спектуруму',84,'<p>Описание работы</p>',0,'33'),(30,5,'Согласование экспертизы с главным технологом по заявке',84,'<p>Описание работы</p>',0,NULL),(31,5,'Согласование экспертизы и устранение замечаний',85,'<p>Описание работы</p>',0,NULL),(33,5,'Написание БФТЗ',87,'<p>Описание работы</p>',0,NULL),(34,5,'Согласование БФТЗ по Спектруму',88,'<p>Описание работы</p>',0,NULL),(35,5,'Согласование БФТЗ по Siebel',88,'<p>Описание работы</p>',0,NULL),(36,5,'Согласование методики тестирования от банка',90,'<p>Описание работы</p>',0,NULL),(37,5,'Тестирование по методике банка',90,'<p>Описание работы</p>',0,NULL),(38,5,'Сборка версии',91,'<p>Описание работы</p>',0,NULL),(39,5,'Тестирование функционала в составе версии',91,'<p>Описание работы</p>',0,NULL),(40,5,'Разработка новой формы',89,'<p><em>Описание работы</em></p>',0,NULL),(41,5,'Документирование функционала',89,'<p>Описание работы</p>',0,NULL),(42,5,'Настройки',89,'<ul><li>2 кода операций, </li><li>функция системы + добавление к ролям</li><li>2 шаблона назначения платежа</li><li>настройка НЕ выгрузки операции в отчет по платежным картам</li><li>настройка нового синт.счета</li><li>настройка НЕ выгрузки в Z-front</li></ul>',0,NULL),(43,5,'Доработка интеграции Зибель-Спектрум (wsdl)',89,'<p>Описание работы</p>',0,NULL),(44,5,'Доработки взаимодействия с Бисквит',89,'<p>Согласование xsd схемы для выгрузки в Бисквит (через SCCreateDocs).</p><p>Доработка клиента для выгрузки в Бисквит. </p><p> Доработка метода выгрузки в Бисквит</p>',0,NULL),(45,5,'Разработка нового ИП',89,'<p>Описание работы</p>',0,NULL),(46,5,'Доработка Аналитической выгрузки',89,'<p>Описание работы</p>',0,NULL),(53,5,'Доработка метода сохранения операции (сохранение id зачисления, номера сводного счета списания)',89,'<p>Описание работы</p>',0,NULL),(54,5,'Настройка нового пакета печати (РКО+справка СНИВ)',89,'<p>Описание работы</p>',0,NULL),(55,5,'Разработка нового метода Поиск/создание лицевого счета для зачисления',89,'<p>Описание работы</p>',0,NULL),(56,5,'Разработка нового метода по ведению резерва остатка лицевых счетов',89,'<p>Описание работы</p>',0,NULL),(57,5,'Доработка отчетов',89,'<p>Описание работы</p>',0,NULL),(58,7,'Выполнение предварительной оценки требований',93,'<p>Описание работы</p>',0,''),(59,7,'Разработка экспертизы',95,'<p>Описание работы</p>',0,NULL),(60,7,'Согласование экспертизы',96,'<p>Описание работы</p>',0,NULL),(61,7,'Написание  БФТЗ',98,'<p>Описание работы</p>',0,NULL),(62,7,'Согласование БФТЗ по Спектруму',99,'<p>Описание работы</p>',0,NULL),(63,7,'Согласование БФТЗ по Siebel',99,'<p>Описание работы</p>',0,NULL),(64,7,'Тестирование по методикам банка',101,'<p>Описание работы</p>',0,NULL),(65,7,'Разработка новой формы',104,'<p>Описание работы</p>',0,NULL),(66,7,'Документирование функционала',104,'<p>Описание работы</p>',0,NULL),(67,7,'Настройки',107,'<p>- 2 кода операций, <br></p><p>- функция системы + добавление к ролям</p><p>- 2 шаблона назначения платежа</p><p>- настройка НЕ выгрузки операции в отчет по платежным картам</p><p>- настройка нового синт.счета</p><p>- настройка НЕ выгрузки в Z-front</p>',0,NULL),(68,7,'Доработка интеграции Зибель-Спектрум (wsdl)',103,'<p>Описание работы</p>',0,NULL),(69,7,'Доработка метода сохранения операции (сохранение id зачисления, номера сводного счета списания)',103,'<p>Описание работы</p>',0,NULL),(70,7,'Согласование xsd схемы для выгрузки в Бисквит (через SCCreateDocs)',105,'<p>Описание работы</p>',0,NULL),(71,7,'Доработка клиента для выгрузки в Бисквит ',105,'<p>Описание работы</p>',0,NULL),(72,7,'Доработка метода выгрузки в Бисквит',105,'<p>Описание работы</p>',0,NULL),(73,7,'Доработка Аналитической выгрузки',108,'<p>Описание работы</p>',0,NULL),(74,1,'Название работы',62,'<p>Описание работы</p>',0,'17648'),(75,8,'песок носить',93,'<p>Описание работы</p>',0,NULL),(76,8,'На воду дуть',93,'<p>Описание работы</p>',0,NULL),(77,6,'Название работы 1 тест',125,'<p>Описание работы</p>',0,NULL),(78,6,'Название работы 2 тест',125,'<p>Описание работы</p>',0,NULL),(79,6,'Название работы',84,'<p>Описание работы</p>',0,''),(80,6,'Название работы',84,'<p>Описание работы</p>',0,NULL),(81,8,'Название работы 1',98,'<p>Описание работы</p>',0,NULL),(84,9,'Название работы',138,'Описание работы',0,NULL),(85,10,'Название работы  33',148,'<p>Описание работы</p>',0,NULL),(86,10,'Название работывм в ',148,'<p>Описание работы</p>',0,NULL),(87,11,'экспертиза',148,'<p>Описание работы</p>',0,NULL),(88,11,'Хуиза',148,'<p>Описание работы</p>',0,NULL),(89,1,'Название работы  33',66,'<p>Описание работы</p>',0,NULL),(90,1,'Косить и забивать',62,'<p>Описание работы</p>',0,'34567'),(91,1,'Название работы',62,'<p>Описание работы</p>',0,''),(92,22,'Название работы 3',65,'Описание работы',0,NULL),(93,22,'Название работы',65,'Описание работы',0,NULL),(94,22,'Название работы',65,'Описание работы',0,NULL),(95,22,'Название работы',62,'<p>Описание работы</p>',0,'17648'),(96,22,'Название работы  33',66,'<p>Описание работы</p>',0,NULL),(97,22,'Косить и забивать',62,'<p>Описание работы</p>',0,'34567'),(98,22,'Название работы',62,'<p>Описание работы</p>',0,''),(99,23,'Название работы 3',65,'Описание работы',0,NULL),(100,23,'Название работы',65,'Описание работы',0,NULL),(101,23,'Название работы',65,'Описание работы',0,NULL),(102,23,'Название работы',62,'<p>Описание работы</p>',0,'17648'),(103,23,'Название работы  33',66,'<p>Описание работы</p>',0,NULL),(104,23,'Косить и забивать',62,'<p>Описание работы</p>',0,'34567'),(105,23,'Название работы',62,'<p>Описание работы</p>',0,''),(106,24,'Анализ требований на основании материалов установочной встречи',82,'<p>Описание работы</p>',0,'17648'),(107,24,'Разработка экспертизы в части требований к Спектуруму',84,'<p>Описание работы</p>',0,'33'),(108,24,'Согласование экспертизы с главным технологом по заявке',84,'<p>Описание работы</p>',0,NULL),(109,24,'Согласование экспертизы и устранение замечаний',85,'<p>Описание работы</p>',0,NULL),(110,24,'Написание БФТЗ',87,'<p>Описание работы</p>',0,NULL),(111,24,'Согласование БФТЗ по Спектруму',88,'<p>Описание работы</p>',0,NULL),(112,24,'Согласование БФТЗ по Siebel',88,'<p>Описание работы</p>',0,NULL),(113,24,'Согласование методики тестирования от банка',90,'<p>Описание работы</p>',0,NULL),(114,24,'Тестирование по методике банка',90,'<p>Описание работы</p>',0,NULL),(115,24,'Сборка версии',91,'<p>Описание работы</p>',0,NULL),(116,24,'Тестирование функционала в составе версии',91,'<p>Описание работы</p>',0,NULL),(117,24,'Разработка новой формы',89,'<p><em>Описание работы</em></p>',0,NULL),(118,24,'Документирование функционала',89,'<p>Описание работы</p>',0,NULL),(119,24,'Настройки',89,'<ul><li>2 кода операций, </li><li>функция системы + добавление к ролям</li><li>2 шаблона назначения платежа</li><li>настройка НЕ выгрузки операции в отчет по платежным картам</li><li>настройка нового синт.счета</li><li>настройка НЕ выгрузки в Z-front</li></ul>',0,NULL),(120,24,'Доработка интеграции Зибель-Спектрум (wsdl)',89,'<p>Описание работы</p>',0,NULL),(121,24,'Доработки взаимодействия с Бисквит',89,'<p>Согласование xsd схемы для выгрузки в Бисквит (через SCCreateDocs).</p><p>Доработка клиента для выгрузки в Бисквит. </p><p> Доработка метода выгрузки в Бисквит</p>',0,NULL),(122,24,'Разработка нового ИП',89,'<p>Описание работы</p>',0,NULL),(123,24,'Доработка Аналитической выгрузки',89,'<p>Описание работы</p>',0,NULL),(124,24,'Доработка метода сохранения операции (сохранение id зачисления, номера сводного счета списания)',89,'<p>Описание работы</p>',0,NULL),(125,24,'Настройка нового пакета печати (РКО+справка СНИВ)',89,'<p>Описание работы</p>',0,NULL),(126,24,'Разработка нового метода Поиск/создание лицевого счета для зачисления',89,'<p>Описание работы</p>',0,NULL),(127,24,'Разработка нового метода по ведению резерва остатка лицевых счетов',89,'<p>Описание работы</p>',0,NULL),(128,24,'Доработка отчетов',89,'<p>Описание работы</p>',0,NULL),(129,6,'воду носить',89,'<p>Описание работы</p>',0,''),(130,6,'Землю копать',89,'<p>Описание работы</p>',0,''),(157,1,'Трубу шатать',60,'<p>Описание работывввв</p>',0,'25555'),(158,25,'Название работы 1 тест',125,'<p>Описание работы</p>',0,NULL),(159,25,'Название работы 2 тест',125,'<p>Описание работы</p>',0,NULL),(160,25,'Название работы',84,'<p>Описание работы</p>',0,''),(161,25,'Название работы',84,'<p>Описание работы</p>',0,NULL),(162,25,'воду носить',89,'<p>Описание работы</p>',0,''),(163,25,'Землю копать',89,'<p>Описание работы</p>',0,''),(164,26,'Название работы 1 тест',125,'<p>Описание работы</p>',0,NULL),(165,26,'Название работы 2 тест',125,'<p>Описание работы</p>',0,NULL),(166,26,'Название работы',84,'<p>Описание работы</p>',0,''),(167,26,'Название работы',84,'<p>Описание работы</p>',0,NULL),(168,26,'воду носить',89,'<p>Описание работы</p>',0,''),(169,26,'Землю копать',89,'<p>Описание работы</p>',0,''),(170,27,'Название работы 1 тест',125,'<p>Описание работы</p>',0,NULL),(171,27,'Название работы 2 тест',125,'<p>Описание работы</p>',0,NULL),(172,27,'Название работы',84,'<p>Описание работы</p>',0,''),(173,27,'Название работы',84,'<p>Описание работы</p>',0,NULL),(174,27,'воду носить',89,'<p>Описание работы</p>',0,''),(175,27,'Землю копать',89,'<p>Описание работы</p>',0,''),(177,28,'Название работы',179,'<p>Описание работы</p>',0,''),(178,28,'Название работы',179,'<p>Описание работы</p>',0,''),(179,28,'Название работы',179,'<p>Описание работы</p>',0,''),(180,1,'воду носить',60,'<p>Описание работы вввв</p>',0,'233333'),(181,1,'Зубы лечить',60,'<p>Описание работы</p>',0,'4444'),(183,1,'Название работы',60,'Описание работы',0,NULL),(184,1,'Название работы',60,'Описание работы',0,NULL),(186,28,'Название работы',182,'<p>Описание работы</p>',0,'');
/*!40000 ALTER TABLE `WorksOfEstimate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_user_id_idx` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('admin','1',1539788854),('analyst','1',1539352659),('analyst','2',1539588495),('projectmanager','1',1539352659);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('admin',1,'Администратор',NULL,NULL,1539269564,1539269564),('analyst',1,'Аналитик',NULL,NULL,1539269564,1539269564),('BRCreate',2,'Регистрация BR',NULL,NULL,1539269564,1539269564),('BRDelete',2,'Удаление BR',NULL,NULL,1539269564,1539269564),('BRJournalView',2,'Просмотр журнала BR',NULL,NULL,1539269564,1539269564),('ManageUserRole',2,'Управление ролями',NULL,NULL,1539269564,1539269564),('projectmanager',1,'Менеджер проекта',NULL,NULL,1539269564,1539269564),('WBSDeleteNode',2,'Удаление узла wbs',NULL,NULL,1544206169,1544206169);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('admin','analyst'),('projectmanager','analyst'),('projectmanager','BRCreate'),('projectmanager','BRDelete'),('analyst','BRJournalView'),('admin','ManageUserRole'),('projectmanager','WBSDeleteNode');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `code` int(11) NOT NULL,
  `name` varchar(52) NOT NULL,
  `population` int(11) DEFAULT '0',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m181107_150752_ResultType',1541605502),('m181107_152940_ResultStatus',1541605502),('m181112_151445_SystemVersionsAdd',1542037492),('m181113_155555_setings',1542125100),('m181113_155940_enum_setings',1542125100),('m181120_133010_tariff',1542722080),('m181120_140601_RoleModel_tariff',1542723178),('m181124_153720_newRole_documentator',1543223813),('m181213_145003_LifeCycleStages_update_resulttype',1544714544);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id_param` int(11) NOT NULL AUTO_INCREMENT,
  `Prm_name` varchar(100) DEFAULT NULL,
  `Prm_description` varchar(2000) DEFAULT NULL,
  `Prm_enum_id` int(11) DEFAULT '0' COMMENT 'id  значения параметра',
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id_param`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='таблица глобальных параметров';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (5,'Mantis_path','Путь к экземпляру багтрекера',22,0);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'sfforum@yandex.ru','perelygin','bV0xucZLwH1aHw2HUqj_Y4kRJsFWFwMW','$2y$13$M/GMDLNRkGvc/PRWBF5XheZuJHGn9WvSzMWGwVo4RwJ1jvF3O2dpy',NULL),(2,'sfforum@yandex.ru','test','PvGB_8iyXDCo1si3fD3Q5qnF0xgpKa61','$2y$13$OdT78pr2L87AhgNGv28W3uHi6m76cMOI54DI5pALxNInlMhVlUncK',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vw_ListOfBR`
--

DROP TABLE IF EXISTS `vw_ListOfBR`;
/*!50001 DROP VIEW IF EXISTS `vw_ListOfBR`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_ListOfBR` AS SELECT 
 1 AS `idBR`,
 1 AS `BRDeleted`,
 1 AS `BRnumber`,
 1 AS `BRName`,
 1 AS `ProjectName`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_ListOfPeople`
--

DROP TABLE IF EXISTS `vw_ListOfPeople`;
/*!50001 DROP VIEW IF EXISTS `vw_ListOfPeople`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_ListOfPeople` AS SELECT 
 1 AS `fio`,
 1 AS `idHuman`,
 1 AS `CustomerName`,
 1 AS `idOrganization`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_ListOfWorkEffort`
--

DROP TABLE IF EXISTS `vw_ListOfWorkEffort`;
/*!50001 DROP VIEW IF EXISTS `vw_ListOfWorkEffort`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_ListOfWorkEffort` AS SELECT 
 1 AS `idWorksOfEstimate`,
 1 AS `WorkName`,
 1 AS `mantisNumber`,
 1 AS `idEstimateWorkPackages`,
 1 AS `idWbs`,
 1 AS `idLaborExpenditures`,
 1 AS `workEffort`,
 1 AS `idTeamMember`,
 1 AS `idRole`,
 1 AS `RoleName`,
 1 AS `idHuman`,
 1 AS `team_member`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_ProjectCommand`
--

DROP TABLE IF EXISTS `vw_ProjectCommand`;
/*!50001 DROP VIEW IF EXISTS `vw_ProjectCommand`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_ProjectCommand` AS SELECT 
 1 AS `id`,
 1 AS `idBR`,
 1 AS `idHuman`,
 1 AS `idRole`,
 1 AS `parent_id`,
 1 AS `team_member`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_ResultEvents`
--

DROP TABLE IF EXISTS `vw_ResultEvents`;
/*!50001 DROP VIEW IF EXISTS `vw_ResultEvents`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_ResultEvents` AS SELECT 
 1 AS `idResultEvents`,
 1 AS `ResultEventsDate`,
 1 AS `ResultEventsName`,
 1 AS `ResultEventsMantis`,
 1 AS `idwbs`,
 1 AS `deleted`,
 1 AS `team_member`,
 1 AS `responsible`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_report1`
--

DROP TABLE IF EXISTS `vw_report1`;
/*!50001 DROP VIEW IF EXISTS `vw_report1`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_report1` AS SELECT 
 1 AS `BRNumber`,
 1 AS `BRName`,
 1 AS `id`,
 1 AS `idBr`,
 1 AS `idOrgResponsible`,
 1 AS `name`,
 1 AS `idResultStatus`,
 1 AS `mantis`,
 1 AS `ResultStatusName`,
 1 AS `fio`,
 1 AS `idHuman`,
 1 AS `CustomerName`,
 1 AS `version_number`,
 1 AS `idsystem_versions`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_settings`
--

DROP TABLE IF EXISTS `vw_settings`;
/*!50001 DROP VIEW IF EXISTS `vw_settings`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_settings` AS SELECT 
 1 AS `id_param`,
 1 AS `Prm_name`,
 1 AS `Prm_description`,
 1 AS `Prm_enum_id`,
 1 AS `enm_num_value`,
 1 AS `enm_str_value`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `wbs`
--

DROP TABLE IF EXISTS `wbs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wbs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mantis` varchar(150) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `idBr` int(11) NOT NULL,
  `idResultType` int(11) DEFAULT NULL,
  `idResultStatus` int(11) DEFAULT NULL,
  `idOrgResponsible` int(11) DEFAULT NULL,
  `idPeopleResponsible` int(11) DEFAULT NULL,
  `idsystem_versions` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wbs_1_idx` (`idBr`),
  KEY `fk_wbs_2_idx` (`idResultType`),
  KEY `fk_wbs_3_idx` (`idResultStatus`),
  KEY `fk_wbs_4_idx` (`idOrgResponsible`),
  KEY `fk_wbs_5_idx` (`idsystem_versions`),
  CONSTRAINT `fk_wbs_1` FOREIGN KEY (`idBr`) REFERENCES `BusinessRequests` (`idBR`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_wbs_2` FOREIGN KEY (`idResultType`) REFERENCES `ResultType` (`idResultType`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_wbs_3` FOREIGN KEY (`idResultStatus`) REFERENCES `ResultStatus` (`idResultStatus`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_wbs_4` FOREIGN KEY (`idOrgResponsible`) REFERENCES `Organization` (`idOrganization`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_wbs_5` FOREIGN KEY (`idsystem_versions`) REFERENCES `SystemVersions` (`idsystem_versions`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wbs`
--

LOCK TABLES `wbs` WRITE;
/*!40000 ALTER TABLE `wbs` DISABLE KEYS */;
INSERT INTO `wbs` VALUES (59,59,1,16,0,'BR 4324324','www.mantis.com',NULL,1,NULL,NULL,NULL,NULL,NULL),(60,59,2,3,1,'Предварительная оценка требований','www.mantis.com','',1,1,1,1,NULL,2),(61,59,4,9,1,'Экспертиза','www.mantis.com',NULL,1,NULL,NULL,NULL,NULL,NULL),(62,59,5,6,2,'Экспертиза разработана','www.mantis.com','',1,1,1,NULL,NULL,1),(63,59,7,8,2,'Экспертиза согласована','www.mantis.com',NULL,1,NULL,NULL,NULL,NULL,NULL),(64,59,10,15,1,'БФТЗ','www.mantis.com',NULL,1,NULL,NULL,NULL,NULL,NULL),(65,59,11,12,2,'БФТЗ разработано','www.mantis.com',NULL,1,NULL,NULL,NULL,NULL,NULL),(66,59,13,14,2,'БФТЗ согласовано','www.mantis.com','',1,1,1,NULL,NULL,NULL),(70,70,1,20,0,'BR 54','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(71,70,2,3,1,'Предварительная оценка','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(72,70,4,9,1,'Экспертиза','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(73,70,5,6,2,'Экспертиза разработана','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(74,70,7,8,2,'Экспертиза согласована','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(75,70,10,15,1,'БФТЗ','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(76,70,11,12,2,'БФТЗ разработано','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(77,70,13,14,2,'БФТЗ согласовано','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(78,70,16,17,1,'Функционал','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(79,70,18,19,1,'Версия','www.mantis.com',NULL,2,NULL,NULL,NULL,NULL,NULL),(81,81,1,26,0,'BR 14122','www.mantis.com',NULL,3,NULL,NULL,NULL,NULL,NULL),(82,81,2,3,1,'Предварительная оценка требований','www.mantis.com','',3,1,1,1,1,1),(83,81,4,9,1,'Экспертиза','www.mantis.com','',3,1,NULL,NULL,NULL,NULL),(84,81,5,6,2,'Экспертиза разработана','www.mantis.com','',3,1,1,1,NULL,1),(85,81,7,8,2,'Экспертиза согласована','www.mantis.com',NULL,3,NULL,NULL,NULL,NULL,NULL),(86,81,10,15,1,'БФТЗ','www.mantis.com',NULL,3,NULL,NULL,NULL,NULL,NULL),(87,81,11,12,2,'БФТЗ разработано','www.mantis.com',NULL,3,NULL,NULL,NULL,NULL,NULL),(88,81,13,14,2,'БФТЗ согласовано','www.mantis.com',NULL,3,NULL,NULL,NULL,NULL,NULL),(89,81,16,17,1,'Реализация и внутреннее тестирование  функционала','www.mantis.com','',3,1,1,NULL,NULL,1),(90,81,18,19,1,'Протестировано по методикам банка','www.mantis.com','',3,NULL,NULL,NULL,NULL,NULL),(91,81,20,21,1,'Версия подготовлена и протестирована','www.mantis.com','',3,NULL,NULL,NULL,NULL,NULL),(92,92,1,46,0,'BR 14122','www.mantis.com',NULL,4,NULL,NULL,NULL,NULL,NULL),(93,92,2,3,1,'Предварительная оценка','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(94,92,4,9,1,'Экспертиза','www.mantis.com',NULL,4,NULL,NULL,NULL,NULL,NULL),(95,92,5,6,2,'Экспертиза разработана','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(96,92,7,8,2,'Экспертиза согласована','www.mantis.com',NULL,4,NULL,NULL,NULL,NULL,NULL),(97,92,10,15,1,'БФТЗ','www.mantis.com',NULL,4,NULL,NULL,NULL,NULL,NULL),(98,92,11,12,2,'БФТЗ разработано','www.mantis.com',NULL,4,NULL,NULL,NULL,NULL,NULL),(99,92,13,14,2,'БФТЗ согласовано','www.mantis.com',NULL,4,NULL,NULL,NULL,NULL,NULL),(100,92,16,41,1,'Реализация и внутреннее тестирование функционала','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(101,92,42,43,1,'Протестировано по методикам банка','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(102,92,44,45,1,'Версия подготовлена и протестирована','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(103,92,17,18,2,'4.2.6.1  Интеграция с Зибель ','www.mantis.com','<p>Доработать интеграцию с Зибель в части получения обработки и сохраненияследующих реквизитов:</p>  <ul><li>Информация оклиенте(В рамках данной задачи полная и частичная выплата 3-м лицам в системе ЕФР не прорабатывается) </li><li>Номер закрытойкарты(для отражения в назначении платежа)</li><li>Сумма зачисления</li><li>Валюта зачисления</li><li>Id зачисления(номер карты)</li><li>Номер сводного счета списания(47422) </li><li>Система ведения сводного счета списания</li><li>Филиал сводного счета списания.</li></ul>',4,1,2,NULL,NULL,1),(104,92,19,20,2,'4.2.6.2. Форма для выполнения  операции','www.mantis.com','<p>На форме Спектрума должны быть отражены следующие параметры операции:</p>  <ul><li>Информация о клиенте – недоступно для изменения.</li><li>Счет списания(47422) - не доступно для изменения. Из контекста операции.</li><li>Сумма зачисления– недоступно для изменения. Из контекста операции.</li><li>Сумма списания - возможно редактирование в пределах суммы зачисления. При запуске формы= Сумме зачисления.</li><li>Валюта списания - не доступно для изменения. Из контекста операции.</li><li>Получаемая сумма – сумма списания по курсу покупки в валюте получения.</li><li>Валюта получения –Доступно для изменения: Валюта зачисления или Рубли РФ.</li><li>Курс конвертации – не доступно для изменения. Курс наличной покупки в валюте получения.</li><li>Назначение платежа -Доступно для редактирования</li></ul>                    <p><br></p>',4,1,2,NULL,NULL,1),(105,92,21,22,2,'4.2.6.3. Доработать интеграцию с Бисквит (механизм SCcreateDocs).','www.mantis.com','<p>Спектрум, в он-лайне, создает в БИСквит, через существующий механизм SCCreateDocs в филиале проведения операции банковский ордер (проводка: Дт 47422(сводный счет зачисленияв бисквит)-Кт 20202). Номер счета 47422, предназначенный для учета зачислений по закрытым картам, приходит в контексте операции из Зибеля и не имеет отличительных особенностей в номере счета от других счетов 47422 в банке. Номер счета 20202 определяется как счет кассы ТП пользователя. При этом,в Бисквит должен быть передан id зачисления для учета частичных выплат со сводного счета 47422 по данному зачислению.</p><p><span></span>Так как проведение операции выплаты в кассе выполняется только внутри одного филиала, то рассчитывать и передавать в Биквит КБО не нужно.</p>  <p><br></p>',4,1,2,NULL,NULL,1),(106,92,23,24,2,'4.2.6.4. выгрузка в Агрегатор ','www.mantis.com','<p>В связи с тем,что операция не предполагает экстерриториальности, выгрузка в Агрегатор по данной операции не производится.</p>',4,NULL,NULL,NULL,NULL,NULL),(107,92,25,26,2,'​4.2.6.5. Новые коды операции для учета операций перевода остатков с закрытых карт Way4,','www.mantis.com','<p>Настроить новые коды операции для учета операций перевода остатков с закрытых карт Way4,в Спектруме: «Выдача наличных с ПК (закрытые карты Way4) в рублях»,«Выдача наличных с ПК (закрытые карты Way4) в валюте»). </p>',4,NULL,NULL,NULL,NULL,NULL),(108,92,27,28,2,'​4.2.6.6. Алгоритм аналитической выгрузки.','www.mantis.com','<p>4.2.6.6. Внести изменения в алгоритм аналитической выгрузки. Предполагается,  что данные операции   должны попасть  в отчет «ПК, ВОО и Платежи» аналитической выгрузки (CR8889 и CR9767). С какими типами раздела  \"ПК, ВОО и Платежи\" должны попадать новые операции в аналитическую выгрузку будет уточнено на этапе БФТЗ</p>',4,NULL,NULL,NULL,NULL,NULL),(109,92,29,30,2,'4.2.6.7. Шаблон назначения платежа.','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(110,92,31,32,2,'4.2.6.8. Реестр операций с НИВ ','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(111,92,33,34,2,'4.2.6.9. Отчет по операциям с платежными картами','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(112,92,35,36,2,'4.2.6.10 Системная функция для вызова новой экранной формы.','www.mantis.com','',4,NULL,NULL,NULL,NULL,NULL),(113,92,37,38,2,'4.2.6.11 Выплата 3-му лицу/по доверенности','www.mantis.com','<p> Операция выплаты по закрытой карте не предполагает выплаты 3-му лицу/по доверенности</p>',4,NULL,NULL,NULL,NULL,NULL),(114,92,39,40,2,'4.2.6.12 Выгрузкуа для z-front (BR-8506).','www.mantis.com','<p>Конверсионная операция не должна попадать в выгрузку для z-front (BR-8506).</p>',4,NULL,NULL,NULL,NULL,NULL),(115,115,1,20,0,'BR ','www.mantis.com',NULL,5,NULL,NULL,NULL,NULL,NULL),(116,115,2,3,1,'Предварительная оценка','www.mantis.com','',5,NULL,NULL,NULL,NULL,NULL),(117,115,4,9,1,'Экспертиза','www.mantis.com','',5,NULL,NULL,NULL,NULL,NULL),(118,115,5,6,2,'Экспертиза разработана','www.mantis.com',NULL,5,NULL,NULL,NULL,NULL,NULL),(119,115,7,8,2,'Экспертиза согласована','www.mantis.com',NULL,5,NULL,NULL,NULL,NULL,NULL),(120,115,10,15,1,'БФТЗ','www.mantis.com',NULL,5,NULL,NULL,NULL,NULL,NULL),(121,115,11,12,2,'БФТЗ разработано','www.mantis.com',NULL,5,NULL,NULL,NULL,NULL,NULL),(122,115,13,14,2,'БФТЗ согласовано','www.mantis.com',NULL,5,NULL,NULL,NULL,NULL,NULL),(123,115,16,17,1,'Функционал','www.mantis.com',NULL,5,NULL,NULL,NULL,NULL,NULL),(124,115,18,19,1,'Версия','www.mantis.com',NULL,5,NULL,NULL,NULL,NULL,NULL),(125,81,22,25,1,'Тест 1 ','www.mantis.com','',3,NULL,NULL,NULL,NULL,NULL),(126,81,23,24,2,'новый узел вложенный','www.mantis.com','',3,NULL,NULL,NULL,NULL,NULL),(127,127,1,20,0,'BR 222','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(128,127,2,3,1,'Предварительная оценка','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(129,127,4,9,1,'Экспертиза','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(130,127,5,6,2,'Экспертиза разработана','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(131,127,7,8,2,'Экспертиза согласована','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(132,127,10,15,1,'БФТЗ','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(133,127,11,12,2,'БФТЗ разработано','www.mantis.com','',6,1,2,NULL,NULL,NULL),(134,127,13,14,2,'БФТЗ согласовано','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(135,127,16,17,1,'Функционал','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(136,127,18,19,1,'Версия','www.mantis.com',NULL,6,NULL,NULL,NULL,NULL,NULL),(137,137,1,20,0,'BR 222','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(138,137,2,3,1,'Предварительная оценка','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(139,137,4,9,1,'Экспертиза','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(140,137,5,6,2,'Экспертиза разработана','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(141,137,7,8,2,'Экспертиза согласована','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(142,137,10,15,1,'БФТЗ','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(143,137,11,12,2,'БФТЗ разработано','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(144,137,13,14,2,'БФТЗ согласовано','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(145,137,16,17,1,'Функционал','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(146,137,18,19,1,'Версия','www.mantis.com',NULL,7,NULL,NULL,NULL,NULL,NULL),(147,147,1,20,0,'BR 4334','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(148,147,2,3,1,'Предварительная оценка','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(149,147,4,9,1,'Экспертиза','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(150,147,5,6,2,'Экспертиза разработана','www.mantis.com','',8,1,2,1,10,NULL),(151,147,7,8,2,'Экспертиза согласована','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(152,147,10,15,1,'БФТЗ','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(153,147,11,12,2,'БФТЗ разработано','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(154,147,13,14,2,'БФТЗ согласовано','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(155,147,16,17,1,'Функционал','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(156,147,18,19,1,'Версия','www.mantis.com',NULL,8,NULL,NULL,NULL,NULL,NULL),(157,157,1,20,0,'BR 091118','www.mantis.com',NULL,9,NULL,NULL,NULL,NULL,NULL),(158,157,2,3,1,'Предварительная оценка','www.mantis.com','',9,1,2,1,3,NULL),(159,157,4,9,1,'Экспертиза','www.mantis.com','',9,1,1,NULL,NULL,NULL),(160,157,5,6,2,'Экспертиза разработана','www.mantis.com','',9,1,2,NULL,NULL,NULL),(161,157,7,8,2,'Экспертиза согласована','www.mantis.com','',9,1,2,NULL,NULL,NULL),(162,157,10,15,1,'БФТЗ','www.mantis.com',NULL,9,NULL,NULL,NULL,NULL,NULL),(163,157,11,12,2,'БФТЗ разработано','www.mantis.com',NULL,9,NULL,NULL,NULL,NULL,NULL),(164,157,13,14,2,'БФТЗ согласовано','www.mantis.com',NULL,9,NULL,NULL,NULL,NULL,NULL),(165,157,16,17,1,'Функционал','www.mantis.com',NULL,9,NULL,NULL,NULL,NULL,NULL),(166,157,18,19,1,'Версия','www.mantis.com',NULL,9,NULL,NULL,NULL,NULL,NULL),(167,167,1,2,0,'BR 123','www.mantis.com',NULL,10,NULL,NULL,NULL,NULL,NULL),(168,168,1,2,0,'BR 123','www.mantis.com',NULL,11,NULL,NULL,NULL,NULL,NULL),(169,169,1,2,0,'BR 123','www.mantis.com',NULL,12,NULL,NULL,NULL,NULL,NULL),(170,170,1,12,0,'BR 123','www.mantis.com',NULL,13,NULL,NULL,NULL,NULL,NULL),(171,170,2,3,1,'Предварительная оценка','www.mantis.com',NULL,13,4,NULL,NULL,NULL,NULL),(172,170,4,5,1,'Экспертиза','www.mantis.com','',13,1,1,NULL,NULL,1),(173,170,6,7,1,'БФТЗ','www.mantis.com','',13,2,1,NULL,NULL,1),(174,170,8,9,1,'Реализация и внутреннее тестирование функционала','www.mantis.com','',13,3,1,NULL,NULL,1),(175,170,10,11,1,'Протестировано по методикам банка','www.mantis.com',NULL,13,4,NULL,NULL,NULL,NULL),(176,176,1,18,0,'BR 4324324','www.mantis.com',NULL,14,NULL,NULL,NULL,NULL,NULL),(177,176,2,5,1,'Предварительная оценка','www.mantis.com','',14,4,1,NULL,NULL,1),(178,176,6,9,1,'Экспертиза','www.mantis.com',NULL,14,1,NULL,NULL,NULL,NULL),(179,176,10,11,1,'БФТЗ','www.mantis.com',NULL,14,2,NULL,NULL,NULL,NULL),(180,176,12,13,1,'Реализация и внутреннее тестирование функционала','www.mantis.com',NULL,14,3,NULL,NULL,NULL,NULL),(181,176,14,17,1,'Протестировано по методикам банка','www.mantis.com','',14,4,1,NULL,NULL,1),(182,176,15,16,2,'nnnnn','www.mantis.com','',14,3,1,NULL,NULL,1),(183,176,7,8,2,'новый узел','www.mantis.com','',14,1,1,NULL,NULL,1),(184,176,3,4,2,'новый узел','www.mantis.com','',14,4,1,NULL,NULL,1);
/*!40000 ALTER TABLE `wbs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `vw_ListOfBR`
--

/*!50001 DROP VIEW IF EXISTS `vw_ListOfBR`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ListOfBR` AS select `br`.`idBR` AS `idBR`,`br`.`BRDeleted` AS `BRDeleted`,`br`.`BRNumber` AS `BRnumber`,`br`.`BRName` AS `BRName`,`prj`.`ProjectName` AS `ProjectName` from (`BusinessRequests` `br` left join `Projects` `prj` on((`prj`.`idProject` = `br`.`idProject`))) where (`br`.`BRDeleted` <> 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_ListOfPeople`
--

/*!50001 DROP VIEW IF EXISTS `vw_ListOfPeople`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ListOfPeople` AS select concat(`ppl`.`Family`,' ',`ppl`.`Name`,' ',`ppl`.`patronymic`) AS `fio`,`ppl`.`idHuman` AS `idHuman`,`org`.`CustomerName` AS `CustomerName`,`org`.`idOrganization` AS `idOrganization` from (`People` `ppl` left join `Organization` `org` on((`ppl`.`idOrganization` = `org`.`idOrganization`))) where (`ppl`.`Humandeleted` <> 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_ListOfWorkEffort`
--

/*!50001 DROP VIEW IF EXISTS `vw_ListOfWorkEffort`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ListOfWorkEffort` AS select `wos`.`idWorksOfEstimate` AS `idWorksOfEstimate`,`wos`.`WorkName` AS `WorkName`,`wos`.`mantisNumber` AS `mantisNumber`,`wos`.`idEstimateWorkPackages` AS `idEstimateWorkPackages`,`wos`.`idWbs` AS `idWbs`,`wef`.`idLaborExpenditures` AS `idLaborExpenditures`,`wef`.`workEffort` AS `workEffort`,`wef`.`idTeamMember` AS `idTeamMember`,`pc`.`idRole` AS `idRole`,`rlm`.`RoleName` AS `RoleName`,`pc`.`idHuman` AS `idHuman`,concat(`rlm`.`RoleName`,' ',`ppl`.`Family`,' ',`ppl`.`Name`) AS `team_member` from ((((`WorksOfEstimate` `wos` left join `WorkEffort` `wef` on((`wos`.`idWorksOfEstimate` = `wef`.`idWorksOfEstimate`))) left join `ProjectCommand` `pc` on((`wef`.`idTeamMember` = `pc`.`id`))) left join `RoleModel` `rlm` on((`pc`.`idRole` = `rlm`.`idRole`))) left join `People` `ppl` on((`pc`.`idHuman` = `ppl`.`idHuman`))) order by `wos`.`idWorksOfEstimate`,`wef`.`idLaborExpenditures` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_ProjectCommand`
--

/*!50001 DROP VIEW IF EXISTS `vw_ProjectCommand`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ProjectCommand` AS select `pc`.`id` AS `id`,`pc`.`idBR` AS `idBR`,`pc`.`idHuman` AS `idHuman`,`pc`.`idRole` AS `idRole`,`pc`.`parent_id` AS `parent_id`,concat(`rlm`.`RoleName`,' ',`ppl`.`Family`,' ',`ppl`.`Name`) AS `team_member` from ((`ProjectCommand` `pc` left join `People` `ppl` on((`ppl`.`idHuman` = `pc`.`idHuman`))) left join `RoleModel` `rlm` on((`rlm`.`idRole` = `pc`.`idRole`))) where ((`pc`.`deleted` <> 1) and (`pc`.`parent_id` <> 0)) order by `pc`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_ResultEvents`
--

/*!50001 DROP VIEW IF EXISTS `vw_ResultEvents`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ResultEvents` AS select `rsl`.`idResultEvents` AS `idResultEvents`,`rsl`.`ResultEventsDate` AS `ResultEventsDate`,`rsl`.`ResultEventsName` AS `ResultEventsName`,`rsl`.`ResultEventsMantis` AS `ResultEventsMantis`,`rsl`.`idwbs` AS `idwbs`,`rsl`.`deleted` AS `deleted`,concat(`rlm`.`RoleName`,' ',`ppl`.`Family`,' ',`ppl`.`Name`) AS `team_member`,concat(`ppl`.`Family`,'-',`org`.`ShortName`) AS `responsible` from ((((`ResultEvents` `rsl` left join `ProjectCommand` `prc` on((`prc`.`id` = `rsl`.`ResultEventResponsible`))) left join `People` `ppl` on((`ppl`.`idHuman` = `prc`.`idHuman`))) left join `RoleModel` `rlm` on((`rlm`.`idRole` = `prc`.`idRole`))) left join `Organization` `org` on((`org`.`idOrganization` = `ppl`.`idOrganization`))) where (`rsl`.`deleted` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_report1`
--

/*!50001 DROP VIEW IF EXISTS `vw_report1`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_report1` AS select `br`.`BRNumber` AS `BRNumber`,`br`.`BRName` AS `BRName`,`wbs`.`id` AS `id`,`wbs`.`idBr` AS `idBr`,`wbs`.`idOrgResponsible` AS `idOrgResponsible`,`wbs`.`name` AS `name`,`wbs`.`idResultStatus` AS `idResultStatus`,`wbs`.`mantis` AS `mantis`,`rst`.`ResultStatusName` AS `ResultStatusName`,concat(`ppl`.`Family`,' ',`ppl`.`Name`,' ',`ppl`.`patronymic`) AS `fio`,`ppl`.`idHuman` AS `idHuman`,`org`.`CustomerName` AS `CustomerName`,`sv`.`version_number` AS `version_number`,`sv`.`idsystem_versions` AS `idsystem_versions` from (((((`wbs` left join `ResultStatus` `rst` on((`wbs`.`idResultStatus` = `rst`.`idResultStatus`))) left join `BusinessRequests` `br` on((`wbs`.`idBr` = `br`.`idBR`))) left join `People` `ppl` on((`wbs`.`idPeopleResponsible` = `ppl`.`idHuman`))) left join `Organization` `org` on((`wbs`.`idOrgResponsible` = `org`.`idOrganization`))) left join `SystemVersions` `sv` on((`wbs`.`idsystem_versions` = `sv`.`idsystem_versions`))) where ((`wbs`.`rgt` - `wbs`.`lft`) <= 1) order by `wbs`.`idBr` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_settings`
--

/*!50001 DROP VIEW IF EXISTS `vw_settings`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_settings` AS select `stt`.`id_param` AS `id_param`,`stt`.`Prm_name` AS `Prm_name`,`stt`.`Prm_description` AS `Prm_description`,`stt`.`Prm_enum_id` AS `Prm_enum_id`,`enm`.`enm_num_value` AS `enm_num_value`,`enm`.`enm_str_value` AS `enm_str_value` from (`settings` `stt` left join `EnumSettings` `enm` on((`enm`.`id_param` = `stt`.`id_param`))) where ((`enm`.`idEnumSettings` = `stt`.`Prm_enum_id`) and (`stt`.`deleted` = 0)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-12-17 12:42:16

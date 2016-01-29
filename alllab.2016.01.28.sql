-- MySQL dump 10.15  Distrib 10.0.16-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: alllab
-- ------------------------------------------------------
-- Server version	10.0.16-MariaDB-1

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
-- Table structure for table `clinician`
--

DROP TABLE IF EXISTS `clinician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clinician` (
  `clinician` varchar(30) NOT NULL,
  `code` varchar(30) NOT NULL,
  PRIMARY KEY (`clinician`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinician`
--

LOCK TABLES `clinician` WRITE;
/*!40000 ALTER TABLE `clinician` DISABLE KEYS */;
INSERT INTO `clinician` VALUES ('ART','ART'),('Dentistry','DENT'),('Emergency Medicine','EMED'),('ENT','ENT'),('Medicine','MED'),('OG','OG'),('Opthalmology','OPTH'),('Orthopaedics','ORTHO'),('Paediatrics','PED'),('Plastic Surgery','PLASTIC'),('Psychiatry','PSYC'),('Skin','SKIN'),('Surgery','SUR'),('TB-Chest','TBCH'),('Unspecified','UNKN');
/*!40000 ALTER TABLE `clinician` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examination`
--

DROP TABLE IF EXISTS `examination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `examination` (
  `result` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_of_examination` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL,
  `referance_range` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `preservative` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `method_of_analysis` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sample_id` bigint(12) NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NABL_Accredited` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `section` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sample_id`,`id`),
  CONSTRAINT `examination_ibfk_1` FOREIGN KEY (`sample_id`) REFERENCES `sample` (`sample_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examination`
--

LOCK TABLES `examination` WRITE;
/*!40000 ALTER TABLE `examination` DISABLE KEYS */;
INSERT INTO `examination` VALUES ('','mg/dl','Glucose',1,'F:70-110 PP:<140 R:<200','Blood(Serum,Plasma)','Fluoride','GOD-POD endpoint',100002,'GLC','','Yes','BIO'),('','mg/dl','Creatinine',4,'0.6-1.6','Blood(Serum,Plasma)','None','Jaffe two point',100003,'CR','','Yes','BIO'),('','mg/dl','Urea',6,'15-45','Blood(Serum,Plasma)','None','Urease  GLDH',100003,'URE','','Yes','BIO'),('','mg/dl','Bilirubin Direct',7,'0-0.3','Blood(Serum,Plasma)','None','Diazo Reaction in water',100003,'DBIL','','Yes','BIO'),('','mg/dl','Bilirubin Total',8,'0-2.0','Blood(Serum,Plasma)','None','Diazo Reaction with caffine',100003,'TBIL','','Yes','BIO'),('','mg/dl','Billirubin Indirect',9,'0-2.0','Blood(Serum,Plasma)','None','Calculation',100003,'IBIL','','Yes','BIO'),('','mg/dl','Glucose',1,'F:70-110 PP:<140 R:<200','Blood(Serum,Plasma)','Fluoride','GOD-POD endpoint',100004,'GLC','','Yes','BIO'),('','mg/dl','Creatinine',4,'0.6-1.6','Blood(Serum,Plasma)','None','Jaffe two point',100005,'CR','','Yes','BIO'),('','mg/dl','Urea',6,'15-45','Blood(Serum,Plasma)','None','Urease  GLDH',100005,'URE','','Yes','BIO'),('','','Urine Examination',602,'','Urine','None','',250001,'','','','CP'),('','','Stool',601,'','Stool','None','Physical, Chemical,Microscopic',250002,'STUL','','no','CP'),('','','Urine Examination',602,'','Urine','None','',250003,'UE','','','CP'),('','','Semen Analysis',603,'','Semen','None','',250004,'SMN','','','CP'),('','','Stool',601,'','Stool','None','Physical, Chemical,Microscopic',250005,'STUL','','no','CP'),('','','Urine Examination',602,'','Urine','None','',250006,'UE','','','CP'),('','','Semen Analysis',603,'','Semen','None','',250007,'SMN','','','CP'),('','','Urine Examination',602,'','Urine','None','',250008,'UE','','','CP'),('','','Reticulocyte Count',701,'','Blood(Whole)','EDTA','',350001,'RET','','','HE'),('','','CBC',709,'','Blood(Whole)','EDTA','',350001,'CBC','','','HE'),('','','Haemoglobin',710,'','Blood(Whole)','EDTA','',350001,'HB','','','HE'),('','','CBC',709,'','Blood(Whole)','EDTA','',350002,'CBC','','','HE'),('','','PSMP',711,'','Blood(Whole)','EDTA','',350002,'PSMP','','','HE'),('','','ESR',712,'','Blood(Whole)','EDTA','',350002,'ESR','','','HE'),('','','CBC',709,'','Blood(Whole)','EDTA','',350003,'CBC','','','HE'),('','','PSMP',711,'','Blood(Whole)','EDTA','',350003,'PSMP','','','HE'),('','','CBC',709,'','Blood(Whole)','EDTA','',350004,'CBC','','','HE'),('','','Absolue Eosinophil Count',713,'','Blood(Whole)','EDTA','',350004,'AEC','','','HE'),('','','Urine culture and sensitivity',1101,'','Urine','None','',650001,'','','','BEC'),('','','Urine culture and sensitivity',1101,'','Urine','None','',650002,'URCS','','','BEC'),('','','HBsAg',1201,'','Blood(Serum,Plasma)','None','',650003,'HBsAg','','','SER'),('','','VDRL',1202,'','Blood(Serum,Plasma)','None','',650003,'VDRL','','','SER'),('','','CRP',1203,'','Blood(Serum,Plasma)','None','',650003,'CRP','','','SER'),('','','Urine culture and sensitivity',1101,'','Urine','None','',650004,'URCS','','','BEC'),('','','HBsAg',1201,'','Blood(Serum,Plasma)','None','',650005,'HBsAg','','','SER'),('','','VDRL',1202,'','Blood(Serum,Plasma)','None','',650005,'VDRL','','','SER'),('','','CRP',1203,'','Blood(Serum,Plasma)','None','',650005,'CRP','','','SER'),('','','HBsAg',1201,'','Blood(Serum,Plasma)','None','',650006,'HBsAg','','','SER'),('','','Sickling Test',1401,'','Blood(Whole)','EDTA','',750001,'SKL','','','SK'),('','','Sickling Test',1401,'','Blood(Whole)','EDTA','',750002,'SKL','','','SK'),('','','ICT/DCT',1305,'','Blood(Whole)','EDTA','',850001,'','','','IHBT'),('','','Blood group',1301,'','Blood(Whole)','EDTA','',850002,'BLGR','','','IHBT'),('','','ICT',1304,'','Blood(Serum,Plasma)','None','',850003,'ICT','','','IHBT'),('','','DCT',1306,'','Blood(Serum,Plasma)','None','',850003,'DCT','','','IHBT'),('','','Blood group',1301,'','Blood(Whole)','EDTA','',850004,'BLGR','','','IHBT'),('','','ICT',1304,'','Blood(Serum,Plasma)','None','',850005,'ICT','','','IHBT'),('','','DCT',1306,'','Blood(Serum,Plasma)','None','',850005,'DCT','','','IHBT'),('','','ICT',1305,'','Blood(Serum,Plasma)','EDTA','',850006,'ICT','','','IHBT'),('','','DCT',1307,'','Blood(Serum,Plasma)','EDTA','',850006,'DCT','','','IHBT');
/*!40000 ALTER TABLE `examination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `location` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES ('E0(506)'),('E1(507)'),('E2(508)'),('E4(510)'),('F0(511)'),('F1(512)'),('F2(513)'),('F3(514)'),('F4(515)'),('G0(516)'),('G1(517)'),('G2(518)'),('G3(519)'),('G4(520)'),('H0(521)'),('H1(522)'),('H2(523)'),('H3(524)'),('H4(525)'),('NOW(311)'),('MOW(310)'),('FOW'),('Hemodialysis UNIT(741)'),('SICU(478)'),('MICU(500-2)'),('F3N(503)'),('Trauma Center(472-87)'),('Casualty(446)'),('Unspecified-'),('OPD'),('SwineFluWard(529)'),('LeptoWard(506)'),('E3(509)'),('PrisonerWard(310)'),('G0MICU(500)'),('Special Ward(570-71)'),('J1(527)'),('J2(528)'),('J3(529)'),('J4(530)'),('OBICU');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preservative`
--

DROP TABLE IF EXISTS `preservative`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preservative` (
  `preservative` varchar(30) NOT NULL,
  `str` varchar(2) NOT NULL,
  PRIMARY KEY (`preservative`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preservative`
--

LOCK TABLES `preservative` WRITE;
/*!40000 ALTER TABLE `preservative` DISABLE KEYS */;
INSERT INTO `preservative` VALUES ('30 ml 6M HCl','HC'),('Citrate','CT'),('EDTA','ED'),('Fluoride','FL'),('Heparin','HP'),('None','PL');
/*!40000 ALTER TABLE `preservative` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `profile` varchar(50) NOT NULL,
  `T1` int(11) DEFAULT NULL,
  `T2` int(11) DEFAULT NULL,
  `T3` int(11) DEFAULT NULL,
  `T4` int(11) DEFAULT NULL,
  `T5` int(11) DEFAULT NULL,
  `T6` varchar(50) NOT NULL,
  `T7` varchar(50) NOT NULL,
  `T8` varchar(50) NOT NULL,
  `T9` varchar(50) NOT NULL,
  `T10` varchar(10) NOT NULL,
  `sample_type` varchar(30) DEFAULT NULL,
  `preservative` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` VALUES ('24_Hrs_Urinary_Calcium',36,83,38,NULL,NULL,'','','','','','Urine','30 ml 6M HCl'),('ALB+TP',11,15,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('ALT+CRE',31,4,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('ALT_only',31,NULL,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('BIL_NICU',7,9,8,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('Calcium_creat_ratio',84,5,62,NULL,NULL,'','','','','','Urine','None'),('CSF_Special',2,17,NULL,NULL,NULL,'','','','','','CSF','None'),('Electrolyte',28,27,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('Glu_only',1,NULL,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma) ','None'),('L+R+E',4,7,8,9,NULL,'28','31','27','','','Blood(Serum,Plasma)','None'),('LDH_only',68,NULL,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('LFT',7,8,9,31,NULL,'','','','','','Blood(Serum,Plasma)','None'),('LFT-RFT',4,7,8,9,31,'','','','','','Blood(Serum,Plasma)','None'),('Lipid',13,10,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('MLFT',11,14,15,23,NULL,'','','','','','Blood(Serum,Plasma)','None'),('Protein_Electrophoresis',11,15,64,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('RFT',4,NULL,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('UA_only',21,NULL,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('urea_only',6,NULL,NULL,NULL,NULL,'','','','','','Blood(Serum,Plasma)','None'),('Z_Critical_Alert',1005,NULL,NULL,NULL,NULL,'','','','','',NULL,NULL),('Z_Examination_Interference',1003,NULL,NULL,NULL,NULL,'','','','','',NULL,NULL),('Z_Examination_Rejection',1002,NULL,NULL,NULL,NULL,'','','','','',NULL,NULL),('Z_Remark',1007,NULL,NULL,NULL,NULL,'','','','','',NULL,NULL),('Z_Sample_Rejection',1001,NULL,NULL,NULL,NULL,'','','','','',NULL,NULL),('Z_Telephonic_Reporting',1004,NULL,NULL,NULL,NULL,'','','','','',NULL,NULL),('Z_Withdrawn_Report',1006,NULL,NULL,NULL,NULL,'','','','','',NULL,NULL);
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sample`
--

DROP TABLE IF EXISTS `sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sample` (
  `sample_id` bigint(12) NOT NULL AUTO_INCREMENT,
  `patient_id` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `patient_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clinician` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `preservative` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_details` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urgent` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sex_age` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_receipt_time` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_collection_time` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `diagnosis` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `section` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `request_id` int(11) NOT NULL,
  `extra` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sample_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1510100005 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sample`
--

LOCK TABLES `sample` WRITE;
/*!40000 ALTER TABLE `sample` DISABLE KEYS */;
INSERT INTO `sample` VALUES (100002,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Blood(Serum,Plasma)','Fluoride','Random','N','entered',NULL,'2016-01-28 13:00:09','2016-01-28 13:00:09',NULL,'BIO',1,'bharuch'),(100003,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Blood(Serum,Plasma)','None','Random','N','entered',NULL,'2016-01-28 13:00:09','2016-01-28 13:00:09',NULL,'BIO',1,'bharuch'),(100004,'SUR/16/00000123','roma','Unspecified','-','OPD','Blood(Serum,Plasma)','Fluoride','Random','N','entered',NULL,'2016-01-28 13:05:33','2016-01-28 13:05:33',NULL,'BIO',2,'surat'),(100005,'SUR/16/00000123','roma','Unspecified','-','OPD','Blood(Serum,Plasma)','None','Random','N','entered',NULL,'2016-01-28 13:05:34','2016-01-28 13:05:34',NULL,'BIO',2,'surat'),(250001,'SUR/16/00111111','viresh','ENT','2','OPD','Urine','None','Fasting','N','entered',NULL,'2016-01-28 11:51:50','2016-01-28 11:51:50',NULL,'CP',111,''),(250002,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Stool','None','Random','N','entered',NULL,'2016-01-28 13:00:09','2016-01-28 13:00:09',NULL,'CP',1,'bharuch'),(250003,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Urine','None','Random','N','entered',NULL,'2016-01-28 13:00:09','2016-01-28 13:00:09',NULL,'CP',1,'bharuch'),(250004,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Semen','None','Random','N','entered',NULL,'2016-01-28 13:00:09','2016-01-28 13:00:09',NULL,'CP',1,'bharuch'),(250005,'SUR/16/00000123','roma','Unspecified','-','OPD','Stool','None','Random','N','entered',NULL,'2016-01-28 13:05:34','2016-01-28 13:05:34',NULL,'CP',2,'surat'),(250006,'SUR/16/00000123','roma','Unspecified','-','OPD','Urine','None','Random','N','entered',NULL,'2016-01-28 13:05:34','2016-01-28 13:05:34',NULL,'CP',2,'surat'),(250007,'SUR/16/00000123','roma','Unspecified','-','OPD','Semen','None','Random','N','entered',NULL,'2016-01-28 13:05:34','2016-01-28 13:05:34',NULL,'CP',2,'surat'),(250008,'SUR/16/23048499','devraj','Medicine','2','OPD','Urine','None','Random','N','entered',NULL,'2016-01-28 13:23:24','2016-01-28 13:23:24',NULL,'CP',4996,'SURAT'),(350001,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Blood(Whole)','EDTA','Random','N','entered',NULL,'2016-01-28 13:00:10','2016-01-28 13:00:10',NULL,'HE',1,'bharuch'),(350002,'SUR/16/00000123','roma','Unspecified','-','OPD','Blood(Whole)','EDTA','Random','N','entered',NULL,'2016-01-28 13:05:35','2016-01-28 13:05:35',NULL,'HE',2,'surat'),(350003,'SUR/16/00001234','aesha','Unspecified','-','OPD','Blood(Whole)','EDTA','Random','N','entered',NULL,'2016-01-28 13:08:47','2016-01-28 13:08:47',NULL,'HE',3,'surat'),(350004,'SUR/16/23048499','devraj','Medicine','2','OPD','Blood(Whole)','EDTA','Random','N','entered',NULL,'2016-01-28 13:23:24','2016-01-28 13:23:24',NULL,'HE',4996,'SURAT'),(650001,'SUR/16/00111111','viresh','ENT','2','OPD','Urine','None','Fasting','N','entered',NULL,'2016-01-28 11:51:50','2016-01-28 11:51:50',NULL,'BEC',111,''),(650002,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Urine','None','Random','N','entered',NULL,'2016-01-28 13:00:08','2016-01-28 13:00:08',NULL,'BEC',1,'bharuch'),(650003,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Blood(Serum,Plasma)','None','Random','N','entered',NULL,'2016-01-28 13:00:10','2016-01-28 13:00:10',NULL,'SER',1,'bharuch'),(650004,'SUR/16/00000123','roma','Unspecified','-','OPD','Urine','None','Random','N','entered',NULL,'2016-01-28 13:05:33','2016-01-28 13:05:33',NULL,'BEC',2,'surat'),(650005,'SUR/16/00000123','roma','Unspecified','-','OPD','Blood(Serum,Plasma)','None','Random','N','entered',NULL,'2016-01-28 13:05:35','2016-01-28 13:05:35',NULL,'SER',2,'surat'),(650006,'SUR/16/00000234','mita','Unspecified','-','OPD','Blood(Serum,Plasma)','None','Random','N','entered',NULL,'2016-01-28 13:10:36','2016-01-28 13:10:36',NULL,'SER',4,'sutra'),(750001,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Blood(Whole)','EDTA','Random','N','entered',NULL,'2016-01-28 13:00:11','2016-01-28 13:00:11',NULL,'SK',1,'bharuch'),(750002,'SUR/16/00000123','roma','Unspecified','-','OPD','Blood(Whole)','EDTA','Random','N','entered',NULL,'2016-01-28 13:05:36','2016-01-28 13:05:36',NULL,'SK',2,'surat'),(850001,'SUR/16/00111111','viresh','ENT','2','OPD','Blood(Whole)','EDTA','Fasting','N','entered',NULL,'2016-01-28 11:51:50','2016-01-28 11:51:50',NULL,'IHBT',111,''),(850002,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Blood(Whole)','EDTA','Random','N','entered',NULL,'2016-01-28 13:00:10','2016-01-28 13:00:10',NULL,'IHBT',1,'bharuch'),(850003,'SUR/16/00000786','MANISHA','Unspecified','-','OPD','Blood(Serum,Plasma)','None','Random','N','entered',NULL,'2016-01-28 13:00:10','2016-01-28 13:00:10',NULL,'IHBT',1,'bharuch'),(850004,'SUR/16/00000123','roma','Unspecified','-','OPD','Blood(Whole)','EDTA','Random','N','entered',NULL,'2016-01-28 13:05:35','2016-01-28 13:05:35',NULL,'IHBT',2,'surat'),(850005,'SUR/16/00000123','roma','Unspecified','-','OPD','Blood(Serum,Plasma)','None','Random','N','entered',NULL,'2016-01-28 13:05:35','2016-01-28 13:05:35',NULL,'IHBT',2,'surat'),(850006,'SUR/16/00000123','roma','Unspecified','-','OPD','Blood(Serum,Plasma)','EDTA','Random','N','entered',NULL,'2016-01-28 13:05:35','2016-01-28 13:05:35',NULL,'IHBT',2,'surat');
/*!40000 ALTER TABLE `sample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sample_details`
--

DROP TABLE IF EXISTS `sample_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sample_details` (
  `sample_details` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sample_details`
--

LOCK TABLES `sample_details` WRITE;
/*!40000 ALTER TABLE `sample_details` DISABLE KEYS */;
INSERT INTO `sample_details` VALUES ('Random'),('Fasting'),('Post_Prendial'),('Leuteal'),('GTT1'),('GTT3');
/*!40000 ALTER TABLE `sample_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sample_type`
--

DROP TABLE IF EXISTS `sample_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sample_type` (
  `sample_type` varchar(30) NOT NULL,
  `str` varchar(11) NOT NULL,
  PRIMARY KEY (`sample_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sample_type`
--

LOCK TABLES `sample_type` WRITE;
/*!40000 ALTER TABLE `sample_type` DISABLE KEYS */;
INSERT INTO `sample_type` VALUES ('Ascitic fluid','FL'),('Blood(Serum,Plasma)','BL'),('Blood(Whole)','BL'),('CSF','FL'),('Other','OT'),('Semen','SN'),('Stool','ST'),('Tissue','TS'),('Urine','UR');
/*!40000 ALTER TABLE `sample_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scope`
--

DROP TABLE IF EXISTS `scope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scope` (
  `result` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_of_examination` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL,
  `referance_range` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `preservative` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `method_of_analysis` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sample_id` int(11) DEFAULT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Available` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `NABL_Accredited` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `TAT` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `instruction_for_patient_preparation` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instruction_for_patient_collected_sample` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `patient_consent` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `required_patient_and_family_info` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `preexamination_storage_requirement` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `maximum_duration_of_sample_collection` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `time_limit_to_request_as_additional_examination` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `section` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scope`
--

LOCK TABLES `scope` WRITE;
/*!40000 ALTER TABLE `scope` DISABLE KEYS */;
INSERT INTO `scope` VALUES (NULL,'mg/dl','Glucose',1,'F:70-110 PP:<140 R:<200','Blood(Serum,Plasma)','Fluoride','GOD-POD endpoint',NULL,'GLC','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Glucose',2,'40-70','CSF','Fluoride','GOD-POD endpoint',NULL,'GLC','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Glucose',3,'40-70','CSF','None','GOD-POD endpoint',NULL,'GLC','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Creatinine',4,'0.6-1.6','Blood(Serum,Plasma)','None','Jaffe two point',NULL,'CR','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Creatinine',5,'N/A','Urine','None','Jaffe two point',NULL,'CR','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Urea',6,'15-45','Blood(Serum,Plasma)','None','Urease  GLDH',NULL,'URE','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Bilirubin Direct',7,'0-0.3','Blood(Serum,Plasma)','None','Diazo Reaction in water',NULL,'DBIL','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Bilirubin Total',8,'0-2.0','Blood(Serum,Plasma)','None','Diazo Reaction with caffine',NULL,'TBIL','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Billirubin Indirect',9,'0-2.0','Blood(Serum,Plasma)','None','Calculation',NULL,'IBIL','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Triglycerides',10,'<150','Blood(Serum,Plasma)','None','Lipase  GO POD',NULL,'TG','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'gm/dl','Albumin',11,'3.5-5.5','Blood(Serum,Plasma)','None','BCG',NULL,'ALB','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','CK-MB',12,'0-24','Blood(Serum,Plasma)','None','Immunoinhibition  kinetic',NULL,'CKMB','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Cholesterol Total',13,'<200','Blood(Serum,Plasma)','None','CHO  Peroxidase',NULL,'CHO','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','Alkaline Phosphatase',14,'42-128','Blood(Serum,Plasma)','None','pNPP,AMP',NULL,'ALP','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'gm/dl','Total Protein',15,'6.5-8.5','Blood(Serum,Plasma)','None','Biuret',NULL,'TP','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Total Protein',16,'15-40','CSF','None','pyrogallol red',NULL,'MPR','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Total Protein',17,'15-40','CSF','Fluoride','pyrogallol red',NULL,'MPR','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Total Protein',18,'N/A','Other','None','pyrogallol red',NULL,'MPR','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'gm/dl','Total Protein',19,'N/A','Other','None','Biuret',NULL,'TP','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Calcium',20,'8.5-11.0','Blood(Serum,Plasma)','None','Arsenazo III',NULL,'CAL','yes','No','Ward: 12 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Uric Acid',21,'2.6-7.2','Blood(Serum,Plasma)','None','Uricase  Paroxidase',NULL,'UA','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','Amylase',22,'N/A','Other','None','CNP-maltotriose',NULL,'AMY','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','Amylase',23,'28-100','Blood(Serum,Plasma)','None','CNP-maltotriose',NULL,'AMY','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','HDL-Cholesterol',24,'>35','Blood(Serum,Plasma)','None','DS-Mg2+',NULL,'CHOH','yes','No','Ward: 24 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','VLDL - Cholesterol',25,'<30','Blood(Serum,Plasma)','None','Calculation',NULL,'CHOV','yes','Yes','Ward: 12 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','LDL - Cholesterol',26,'<130','Blood(Serum,Plasma)','None','Calculation',NULL,'CHOL','yes','Yes','Ward: 12 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mmol/l','Na+',27,'135-145','Blood(Serum,Plasma)','None','Ion-selective electrode',NULL,'NA','yes','Yes','Ward: 2 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 2 hours of collection','Request as additional examination within 2 hours of collection','BIO'),(NULL,'mmol/l','K+',28,'3.5-5.5','Blood(Serum,Plasma)','None','Ion-selective electrode',NULL,'K','yes','Yes','Ward: 2 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 2 hours of collection','Request as additional examination within 2 hours of collection','BIO'),(NULL,'mmol/l','Li+',29,'0.6-1.2','Blood(Serum,Plasma)','None','Ion-selective electrode',NULL,'LI','yes','Yes','Ward: 2 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 2 hours of collection','Request as additional examination within 2 hours of collection','BIO'),(NULL,'N/A','Ketones',30,'N/A','Blood(Serum,Plasma)','None','Qualitative  nitroprusside',NULL,'KTO','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','ALT',31,'<45','Blood(Serum,Plasma)','None','L-Alanine  LDH  UV Kinetic',NULL,'ALT','yes','Yes','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Total Protein',32,'N/A','Urine','None','pyrogallol red',NULL,'MPR','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Glucose',33,'N/A','Other','None','GOD-POD endpoint',NULL,'GLC','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'microU/ml','TSH',34,'0.5-5.0','Blood(Serum,Plasma)','None','ELISA',NULL,'TSH','yes','No','7 days','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','Protein/Creatinine',35,'< 0.1','Urine','None','Calculation',NULL,'PRCR','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Calcium',36,'N/A','Urine','30 ml 6M HCl','Arsenazo III',NULL,'CAL','yes','No','Ward: 12 hours, OPD:24 hours','Send a plastic container with around 4 liters volume. It will be washed by the laboratory and approproate preservative will be added and returned for sample collection.','Do not throw away preservative liquid. Discard 8.00 am urine. There after collect urine in the container till 8 am next morning. Include last 8 am urine in container. Keep in refrigerator in between','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'ml/day','Urine Output',37,'N/A','Urine','None','Volumetric measurement',NULL,'UOUT','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','Discard 8.00 am urine. collect till 8.00 am next day. Include last urine at 8.00 am next day. store in refrigerator in between collection.','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'ml/day','Urine Output',38,'N/A','Urine','30 ml 6M HCl','Volumetric measurement',NULL,'UOUT','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','There is preservative inside.Donot discard it. Discard 8.00 am urine. collect till 8.00 am next day. Include last urine at 8.00 am next day. store in refrigerator in between collection.','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/day','Total Protein',40,'<150','Urine','None','Calculation',NULL,'TPDAY','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','Lipase',43,'N/A','Other','None','Turbidometry',NULL,'LIP','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Triglycerides',48,'N/A','Others','None','Lipase  GO POD',NULL,'TG','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'pmol/L','Free T4',53,'10.3-34.7','Blood(Serum,Plasma)','None','ELISA',NULL,'FT4','yes','No','7 days','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'IU/ml','TPOAb',58,'<30','Blood(Serum,Plasma)','None','ELISA',NULL,'TPO','No','No','7 days','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'gm/dl','Total Protein',60,NULL,'Urine','None','Biuret',NULL,'TP','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mmol/l','Cl-',61,'98-107','Blood(Serum,Plasma)','None','ISE',NULL,'CL','No','Yes','Ward: 2 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','Calcium/Creatinine Ratio',62,'<0.2(Adults)','Urine','None','Calculation',NULL,'CCR','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','Ketones',63,'N/A','Urine','None','Nitroprusside',NULL,'KTO','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','Protein Electrophoresis',64,'N/A','Blood(Serum,Plasma)','None','Agarose pH 8.6 Veronal Buffer',NULL,'PRE','yes','No','72 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','Lipase',65,'0-5','Blood(Serum,Plasma)','None','Turbidometry',NULL,'LIP','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/ml','Adenosine Deaminase',66,'<15','Blood(Serum,Plasma)','None','Berthelot',NULL,'ADA','yes','No','24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/ml','Adenosine Deaminase',67,'N/A','Other','None','Berthelot',NULL,'ADA','yes','No','24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','LDH',68,'250-450','Blood(Serum,Plasma)','None','LDH UV Kinetic',NULL,'LDH','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/L','CRP',69,'<6','Blood(Serum,Plasma)','None','Latex turbidimetry',NULL,'CRPT','No','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/L','CRP',70,'N/A','Other','None','Latex turbidimetry',NULL,'CRPT','No','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','LDH',71,'N/A','Other','None','LDH UV Kinetic',NULL,'LDH','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','Bence Jones protein',74,'N/A','Urine','None','Heat Precipitation',NULL,'BJP','yes','No','24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','HB Electrophoresis',75,'N/A','Blood(Whole)','EDTA','agarose gel PH 8.6,TEB buffer',NULL,'HE','yes','No','72 hours','No specific instruction','No specific instruction','No written consent required','Sometimes results of same examination in parents and siblings is requested by the laboratory ','transport and store at ambient temperature if can be examined within 4 hours. Else store at 2-8 degree C. Do not freeze. Examined within 3 days.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','Dithionite Test for HbS',76,'N/A','Blood(Whole)','EDTA','Dithionite',NULL,'DTS','yes','No','72 hours','No specific instruction','No specific instruction','No written consent required','Sometimes results of same examination in parents and siblings is requested by the laboratory ','transport and store at ambient temperature if can be examined within 4 hours. Else store at 2-8 degree C. Do not freeze. Examined within 3 days.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Cholesterol Total',78,'N/A','Other','None','CHO  Peroxidase',NULL,'CHO','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/ml','Adenosine Deaminase',79,NULL,'CSF','None','Berthelot',NULL,'ADA','yes','No','24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','LDH',80,'N/A','CSF','None','LDH UV Kinetic',NULL,'LDH','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','Benedict\'s Test',81,'N/A','Urine','None','Benedict\'s reaction',NULL,'BDT','yes','No','24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','paper chromatography',82,'N/A','Blood(Serum,Plasma)','None','butenol,acetic acid,water',NULL,'PCHRO','yes','No','72 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/day','Calcium',83,'<200','Urine','N/A','Calculation',NULL,'DCAL','yes','No','Ward: 12 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'ng/mL','Ferritin',87,NULL,'Blood(Serum,Plasma)','None','ELISA',NULL,'FT','No','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Glucose',89,'N/A','Other','Fluoride','GOD-POD endpoint',NULL,'GLC','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','Cholinesterase',90,'3700-13000','Blood(Serum,Plasma)','None','Butyrylcholinesterase',NULL,'CHE','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'mg/dl','Creatinine',91,'0.6-1.6','Other','None','Jaffe two point',NULL,'CR','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'ng/ml','Ferritin',92,'32-500','Blood(Serum,Plasma)','None','ELISA',NULL,'FRT','No','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','paper chromatography',98,'N/A','Urine','None','butenol,acetic acid,water',NULL,'PCHRO','yes','No','72 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'%','HbA1c',99,'<6.5','Blood(Whole)','EDTA','Latex immunoturbidimetry',NULL,'HbA1c','No','no','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature if can be examined within 4 hours. Else store at 2-8 degree C. Do not freeze. Examined within 3 days.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'N/A','Protein Electrophoresis',102,'N/A','Urine','None','Agarose pH 8.6 Veronal Buffer',NULL,'PRE','yes','No','72 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'µg/dl','Iron',103,'50-175','Blood(Serum,Plasma)','None','Ferrozine',NULL,'IRON','yes','No','24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'µg/dl','TIBC',104,'250-450','Blood(Serum,Plasma)','None','Ferrozine,MgCO3',NULL,'TIBC','yes','No','24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'gm/dl','Total Protein',105,'0.015-0.040','CSF','None','Biuret',NULL,'TP','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'gm/dl','Total Protein',106,'0.015-0.040','CSF','Fluoride','Biuret',NULL,'TP','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'U/L','AST',107,'<38','Blood(Serum,Plasma)','None','L-Aspartate  MDH  UV Kinetic',NULL,'AST','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'gm/dl','Albumin',108,'N/A','Other','None','BCG',NULL,'ALB','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,'gm/dl','Albumin',109,'N/A','Ascitic fluid','None','BCG',NULL,'ALB','yes','No','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Stool',601,NULL,'Stool','None','Physical, Chemical,Microscopic',NULL,'STUL','yes','no','',NULL,'','','','','','','CP'),(NULL,NULL,'Urine Examination',602,NULL,'Urine','None','',NULL,'UE','yes','','',NULL,'','','','','','','CP'),(NULL,NULL,'Semen Analysis',603,NULL,'Semen','None','',NULL,'SMN','yes','','',NULL,'','','','','','','CP'),(NULL,NULL,'Reticulocyte Count',701,NULL,'Blood(Whole)','EDTA','',NULL,'RET','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Platelet Count',702,NULL,'Blood(Whole)','EDTA','',NULL,'PLTC','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Haematocrit',703,NULL,'Blood(Whole)','EDTA','',NULL,'HCT','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Fetal Hemoglobin',704,NULL,'Blood(Whole)','EDTA','',NULL,'HBF','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Prothombin Time',705,NULL,'Blood(Serum,Plasma)','Citrate','',NULL,'PT','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'G6PD',706,NULL,'Blood(Whole)','EDTA','',NULL,'G6PD','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'LE Cell',707,NULL,'Blood(Whole)','EDTA','',NULL,'LE','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Osmotic Fragility',708,NULL,'Blood(Whole)','Heparin','',NULL,'OF','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'CBC',709,NULL,'Blood(Whole)','EDTA','',NULL,'CBC','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Haemoglobin',710,NULL,'Blood(Whole)','EDTA','',NULL,'HB','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'PSMP',711,NULL,'Blood(Whole)','EDTA','',NULL,'PSMP','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'ESR',712,NULL,'Blood(Whole)','EDTA','',NULL,'ESR','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Absolue Eosinophil Count',713,NULL,'Blood(Whole)','EDTA','',NULL,'AEC','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Activated Partial Trhomboplastin Time',714,NULL,'Blood(Serum,Plasma)','Citrate','',NULL,'APTT','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'FDP',715,NULL,'Blood(Serum,Plasma)','Citrate','',NULL,'FDP','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Factor-8',716,NULL,'Blood(Serum,Plasma)','Citrate','',NULL,'F-8','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Factor-9',717,NULL,'Blood(Serum,Plasma)','Citrate','',NULL,'F-9','yes','','',NULL,'','','','','','','HE'),(NULL,NULL,'Z_Sample_Rejection',1001,NULL,'','','',NULL,'SR','yes','','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Z_Examination_Rejection',1002,NULL,'','','',NULL,'ER','yes','','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Z_Examination_Interference',1003,NULL,'','','',NULL,'EI','yes','','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Z_Telephonic_Reporting',1004,NULL,'','','',NULL,'TR','yes','','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Z_Critical_Alert',1005,NULL,'','','',NULL,'CRT','yes','','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Z_Withdrawn_Report',1006,NULL,'','','',NULL,'WR','yes','','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Z_Remark',1007,NULL,'','','',NULL,'RMR','yes','','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Z_Attachment',1008,NULL,'','','',NULL,'ATC','yes','','Ward: 4 hours, OPD:24 hours','No specific instruction','No specific instruction','No written consent required','None','transport and store at ambient temperature. Store separated serum/plasma at <(-20 degree C) if can not be examined within maximum duration of sample collection.','Send sample within 4 hours of collection','Request as additional examination within 4 hours of collection','BIO'),(NULL,NULL,'Urine culture and sensitivity',1101,NULL,'Urine','None','',NULL,'URCS','yes','','',NULL,'','','','','','','BEC'),(NULL,NULL,'HBsAg',1201,NULL,'Blood(Serum,Plasma)','None','',NULL,'HBsAg','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'VDRL',1202,NULL,'Blood(Serum,Plasma)','None','',NULL,'VDRL','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'CRP',1203,NULL,'Blood(Serum,Plasma)','None','',NULL,'CRP','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'ASO',1204,NULL,'Blood(Serum,Plasma)','None','',NULL,'ASO','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'RA',1205,NULL,'Blood(Serum,Plasma)','None','',NULL,'RA','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'HCV',1206,NULL,'Blood(Serum,Plasma)','None','',NULL,'HCV','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'Antigen for Malaria parasite',1207,NULL,'Blood(Serum,Plasma)','None','',NULL,'AGMP','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'WIDAL',1208,NULL,'Blood(Serum,Plasma)','None','',NULL,'WIDAL','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'IgM antibody to Dangue',1209,NULL,'Blood(Serum,Plasma)','None','',NULL,'IGGDN','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'IgG antibody to Dangue',1210,NULL,'Blood(Serum,Plasma)','None','',NULL,'IGMDN','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'Enterocheck-WB',1211,NULL,'Blood(Serum,Plasma)','None','',NULL,'ENTCHK','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'NS-1 Antigen Dangue',1212,NULL,'Blood(Serum,Plasma)','None','',NULL,'NS-1','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'IgM antibody to chickenguniya',1213,NULL,'Blood(Serum,Plasma)','None','',NULL,'IGMCHK','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'Vit-D3',1214,NULL,'Blood(Serum,Plasma)','None','',NULL,'D-3','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'Anti Nuclear Antibody',1215,NULL,'Blood(Serum,Plasma)','None','',NULL,'ANA','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'Measles',1216,NULL,'Blood(Serum,Plasma)','None','',NULL,'MSLS','yes','','',NULL,'','','','','','','SER'),(NULL,NULL,'Blood group',1301,NULL,'Blood(Whole)','EDTA','',NULL,'BLGR','yes','','',NULL,'','','','','','','IHBT'),(NULL,NULL,'Blood group',1302,NULL,'Blood(Serum,Plasma)','None','',NULL,'BLGR','yes','','',NULL,'','','','','','','IHBT'),(NULL,NULL,'ICT',1304,NULL,'Blood(Serum,Plasma)','None','',NULL,'ICT','yes','','',NULL,'','','','','','','IHBT'),(NULL,NULL,'ICT',1305,NULL,'Blood(Serum,Plasma)','EDTA','',NULL,'ICT','yes','','',NULL,'','','','','','','IHBT'),(NULL,NULL,'DCT',1306,NULL,'Blood(Serum,Plasma)','None','',NULL,'DCT','yes','','',NULL,'','','','','','','IHBT'),(NULL,NULL,'DCT',1307,NULL,'Blood(Serum,Plasma)','EDTA','',NULL,'DCT','yes','','',NULL,'','','','','','','IHBT'),(NULL,NULL,'Sickling Test',1401,NULL,'Blood(Whole)','EDTA','',NULL,'SKL','yes','','',NULL,'','','','','','','SK'),(NULL,NULL,'HB Electrophoresis',1402,NULL,'Blood(Whole)','EDTA','',NULL,'HE','yes','','',NULL,'','','','','','','SK');
/*!40000 ALTER TABLE `scope` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `section` varchar(5) NOT NULL,
  `department` varchar(5) NOT NULL,
  PRIMARY KEY (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` VALUES ('BEC','MICRO'),('BIO','BIO'),('CP','PATH'),('CY','PATHO'),('HE','PATHO'),('HP','PATHO'),('IHBT','IHBT'),('SER','MICRO'),('SK','PATHO');
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (''),('verified'),('entered'),('centrifuged'),('analysed'),('verification failed');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit`
--

DROP TABLE IF EXISTS `unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unit` (
  `unit` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit`
--

LOCK TABLES `unit` WRITE;
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
INSERT INTO `unit` VALUES ('1'),('2'),('3'),('4'),('5'),('6'),('A'),('B'),('-');
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `urgent`
--

DROP TABLE IF EXISTS `urgent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `urgent` (
  `urgent` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `urgent`
--

LOCK TABLES `urgent` WRITE;
/*!40000 ALTER TABLE `urgent` DISABLE KEYS */;
INSERT INTO `urgent` VALUES ('N'),('Y');
/*!40000 ALTER TABLE `urgent` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-28 18:33:50

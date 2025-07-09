/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: radius
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-0+deb12u1

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('admin@gmail.com|127.0.0.1','i:1;',1749300149),
('admin@gmail.com|127.0.0.1:timer','i:1749300149;',1749300149),
('carloskael32@gmail.com|127.0.0.1','i:2;',1748918063),
('carloskael32@gmail.com|127.0.0.1:timer','i:1748918063;',1748918063),
('test@gmail.com|127.0.0.1','i:1;',1749300155),
('test@gmail.com|127.0.0.1:timer','i:1749300155;',1749300155);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nas`
--

DROP TABLE IF EXISTS `nas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nasname` varchar(128) NOT NULL,
  `shortname` varchar(32) DEFAULT NULL,
  `type` varchar(30) DEFAULT 'other',
  `ports` int(5) DEFAULT NULL,
  `secret` varchar(60) NOT NULL DEFAULT 'secret',
  `server` varchar(64) DEFAULT NULL,
  `community` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT 'RADIUS Client',
  PRIMARY KEY (`id`),
  KEY `nasname` (`nasname`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nas`
--

LOCK TABLES `nas` WRITE;
/*!40000 ALTER TABLE `nas` DISABLE KEYS */;
INSERT INTO `nas` VALUES
(1,'10.2.2.1','mikrotik','mikrotik',NULL,'test123',NULL,NULL,'RADIUS Client');
/*!40000 ALTER TABLE `nas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nasreload`
--

DROP TABLE IF EXISTS `nasreload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nasreload` (
  `nasipaddress` varchar(15) NOT NULL,
  `reloadtime` datetime NOT NULL,
  PRIMARY KEY (`nasipaddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nasreload`
--

LOCK TABLES `nasreload` WRITE;
/*!40000 ALTER TABLE `nasreload` DISABLE KEYS */;
/*!40000 ALTER TABLE `nasreload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radacct`
--

DROP TABLE IF EXISTS `radacct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radacct` (
  `radacctid` bigint(21) NOT NULL AUTO_INCREMENT,
  `acctsessionid` varchar(64) NOT NULL DEFAULT '',
  `acctuniqueid` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(64) NOT NULL DEFAULT '',
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `realm` varchar(64) DEFAULT '',
  `nasipaddress` varchar(15) NOT NULL DEFAULT '',
  `nasportid` varchar(32) DEFAULT NULL,
  `nasporttype` varchar(32) DEFAULT NULL,
  `acctstarttime` datetime DEFAULT NULL,
  `acctupdatetime` datetime DEFAULT NULL,
  `acctstoptime` datetime DEFAULT NULL,
  `acctinterval` int(12) DEFAULT NULL,
  `acctsessiontime` int(12) unsigned DEFAULT NULL,
  `acctauthentic` varchar(32) DEFAULT NULL,
  `connectinfo_start` varchar(50) DEFAULT NULL,
  `connectinfo_stop` varchar(50) DEFAULT NULL,
  `acctinputoctets` bigint(20) DEFAULT NULL,
  `acctoutputoctets` bigint(20) DEFAULT NULL,
  `calledstationid` varchar(50) NOT NULL DEFAULT '',
  `callingstationid` varchar(50) NOT NULL DEFAULT '',
  `acctterminatecause` varchar(32) NOT NULL DEFAULT '',
  `servicetype` varchar(32) DEFAULT NULL,
  `framedprotocol` varchar(32) DEFAULT NULL,
  `framedipaddress` varchar(15) NOT NULL DEFAULT '',
  `framedipv6address` varchar(45) NOT NULL DEFAULT '',
  `framedipv6prefix` varchar(45) NOT NULL DEFAULT '',
  `framedinterfaceid` varchar(44) NOT NULL DEFAULT '',
  `delegatedipv6prefix` varchar(45) NOT NULL DEFAULT '',
  `class` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`radacctid`),
  UNIQUE KEY `acctuniqueid` (`acctuniqueid`),
  KEY `username` (`username`),
  KEY `framedipaddress` (`framedipaddress`),
  KEY `framedipv6address` (`framedipv6address`),
  KEY `framedipv6prefix` (`framedipv6prefix`),
  KEY `framedinterfaceid` (`framedinterfaceid`),
  KEY `delegatedipv6prefix` (`delegatedipv6prefix`),
  KEY `acctsessionid` (`acctsessionid`),
  KEY `acctsessiontime` (`acctsessiontime`),
  KEY `acctstarttime` (`acctstarttime`),
  KEY `acctinterval` (`acctinterval`),
  KEY `acctstoptime` (`acctstoptime`),
  KEY `nasipaddress` (`nasipaddress`),
  KEY `bulk_close` (`acctstoptime`,`nasipaddress`,`acctstarttime`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radacct`
--

LOCK TABLES `radacct` WRITE;
/*!40000 ALTER TABLE `radacct` DISABLE KEYS */;
INSERT INTO `radacct` VALUES
(1,'81100013','fe133c99cb1e83ced44b720094dff1ba','demo_user','','','10.2.2.1','LAN','Ethernet','2025-03-19 10:41:44','2025-03-19 10:41:44','2025-03-19 10:42:53',NULL,68,'RADIUS','','',6004,853,'PPPoE_Server','08:00:27:76:69:24','User-Request','Framed-User','PPP','10.2.2.15','','','','',NULL),
(2,'81100014','33cb2bddd522b6345cd8d5c033419671','demo_user','','','10.2.2.1','LAN','Ethernet','2025-03-19 10:43:45','2025-03-19 10:43:45','2025-03-19 10:44:54',NULL,68,'RADIUS','','',5745,835,'PPPoE_Server','08:00:27:76:69:24','User-Request','Framed-User','PPP','10.2.2.15','','','','',NULL),
(3,'81100015','182c93c48c37240bf4aa9d4aa0a5ae9e','test','','','10.2.2.1','LAN','Ethernet','2025-03-19 10:45:34','2025-03-19 10:45:34','2025-03-19 10:46:42',NULL,68,'RADIUS','','',7523,913,'PPPoE_Server','08:00:27:76:69:24','User-Request','Framed-User','PPP','10.2.2.253','','','','',NULL),
(4,'81f00007','c7889f17263f5d2ac50ab18f1b22da41','user1','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:08:44','2025-04-06 19:08:44','2025-04-06 19:10:20',NULL,96,'RADIUS','','',510,148,'PPPoe_server','C0:25:67:05:EA:98','Lost-Carrier','Framed-User','PPP','10.2.2.254','','','','',NULL),
(5,'81f00008','ea5b0aa95a2eee28b278bbf6265bbe41','user1','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:10:25','2025-04-06 19:10:25','2025-04-06 19:14:05',NULL,221,'RADIUS','','',304038,567195,'PPPoe_server','C0:25:67:05:EA:98','User-Request','Framed-User','PPP','10.2.2.254','','','','',NULL),
(6,'81f00009','ffd3817648a9fc57dca46a1d35c392ec','user2','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:14:15','2025-04-06 19:14:15','2025-04-06 19:15:25',NULL,70,'RADIUS','','',97944,364240,'PPPoe_server','C0:25:67:05:EA:98','User-Request','Framed-User','PPP','10.2.2.27','','','','',NULL),
(7,'81f0000a','3eb9c5d66b2c79bc930b2fe6e65259b2','demo_user','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:15:39','2025-04-06 19:15:39',NULL,NULL,0,'RADIUS','','',0,0,'PPPoe_server','C0:25:67:05:EA:98','','Framed-User','PPP','10.2.2.15','','','','',NULL),
(8,'81f0000e','a486857ea22b06d4c1b378d60872876b','demo_user','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:24:22','2025-04-06 19:24:22','2025-04-06 19:28:13',NULL,231,'RADIUS','','',205648,224014,'PPPoe_server','C0:25:67:05:EA:98','User-Request','Framed-User','PPP','10.2.2.15','','','','',NULL);
/*!40000 ALTER TABLE `radacct` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radcheck`
--

DROP TABLE IF EXISTS `radcheck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radcheck` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '==',
  `value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `username` (`username`(32))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radcheck`
--

LOCK TABLES `radcheck` WRITE;
/*!40000 ALTER TABLE `radcheck` DISABLE KEYS */;
INSERT INTO `radcheck` VALUES
(1,'demo_user','Cleartext-Password',':=','12345'),
(2,'test','Cleartext-Password',':=','54321'),
(4,'sven','Cleartext-Password',':=','123456'),
(5,'pepe','Cleartext-Password',':=','3124'),
(6,'miguel','Cleartext-Password',':=','3124');
/*!40000 ALTER TABLE `radcheck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radgroupcheck`
--

DROP TABLE IF EXISTS `radgroupcheck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radgroupcheck` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '==',
  `value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `groupname` (`groupname`(32))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radgroupcheck`
--

LOCK TABLES `radgroupcheck` WRITE;
/*!40000 ALTER TABLE `radgroupcheck` DISABLE KEYS */;
INSERT INTO `radgroupcheck` VALUES
(1,'premiun1','Rate-Limit','=','512k/128k'),
(3,'premiun2','Rate-Limit','=','10m/15m');
/*!40000 ALTER TABLE `radgroupcheck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radgroupreply`
--

DROP TABLE IF EXISTS `radgroupreply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radgroupreply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '=',
  `value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `groupname` (`groupname`(32))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radgroupreply`
--

LOCK TABLES `radgroupreply` WRITE;
/*!40000 ALTER TABLE `radgroupreply` DISABLE KEYS */;
INSERT INTO `radgroupreply` VALUES
(1,'Premium','Mikrotik-Rate-Limit',':=','2M/5M');
/*!40000 ALTER TABLE `radgroupreply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radpostauth`
--

DROP TABLE IF EXISTS `radpostauth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radpostauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `pass` varchar(64) NOT NULL DEFAULT '',
  `reply` varchar(32) NOT NULL DEFAULT '',
  `authdate` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `class` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radpostauth`
--

LOCK TABLES `radpostauth` WRITE;
/*!40000 ALTER TABLE `radpostauth` DISABLE KEYS */;
INSERT INTO `radpostauth` VALUES
(1,'demo','12345','Access-Accept','2025-02-04 03:41:13.548264',''),
(2,'test','54321','Access-Accept','2025-02-04 03:41:47.253384',''),
(3,'test','54321','Access-Accept','2025-02-04 03:55:12.282786',''),
(4,'demo','12345','Access-Accept','2025-03-13 18:53:23.579035',''),
(5,'carlos','carlos','Access-Reject','2025-03-13 19:02:36.309925',''),
(6,'test','54321','Access-Accept','2025-03-13 19:03:12.157430',''),
(7,'test','54321','Access-Accept','2025-03-13 19:04:09.339401',''),
(8,'test','54321','Access-Accept','2025-03-13 19:04:12.481963',''),
(9,'test','54321','Access-Accept','2025-03-13 19:04:23.760083',''),
(10,'carlos','carlos','Access-Reject','2025-03-13 20:36:32.740773',''),
(11,'carlos','carlos','Access-Reject','2025-03-13 20:36:34.701302',''),
(12,'carlos','carlos','Access-Reject','2025-03-13 20:37:43.840729',''),
(13,'carlos','carlos','Access-Reject','2025-03-13 20:38:52.959987',''),
(14,'carlos','carlos','Access-Reject','2025-03-13 20:40:02.079629',''),
(15,'carlos','carlos','Access-Reject','2025-03-13 20:41:10.197847',''),
(16,'Demo','12345','Access-Accept','2025-03-13 20:42:14.606539',''),
(17,'Demo','12345','Access-Accept','2025-03-13 20:43:23.725620',''),
(18,'Demo','12345','Access-Accept','2025-03-13 20:44:32.834772',''),
(19,'Demo','12345','Access-Accept','2025-03-13 20:45:41.932773',''),
(20,'Demo','12345','Access-Accept','2025-03-13 20:46:51.064165',''),
(21,'Demo','12345','Access-Accept','2025-03-13 20:48:00.198891',''),
(22,'Demo','0x01908497d48dad00696770cd48e264cc13','Access-Accept','2025-03-13 20:49:11.503681',''),
(23,'Demo','0x0112f55066b03360d77a408db396cd5969','Access-Accept','2025-03-13 20:50:19.931948',''),
(24,'Sven','0x01a5a3f5b83e5dff796f7a42c9eb674061','Access-Reject','2025-03-13 20:50:48.357580',''),
(25,'Sven','0x0182673902a3e85e092539d5a45c3806a7','Access-Reject','2025-03-13 20:51:57.489357',''),
(26,'Sven','','Access-Reject','2025-03-13 20:53:06.671674',''),
(27,'Sven','','Access-Reject','2025-03-13 20:54:14.792403',''),
(28,'Sven','','Access-Reject','2025-03-13 20:55:23.911605',''),
(29,'Sven','','Access-Reject','2025-03-13 20:56:33.032377',''),
(30,'Sven','','Access-Reject','2025-03-13 20:56:44.255987',''),
(31,'Sven','','Access-Reject','2025-03-13 20:57:53.376208',''),
(32,'demo','','Access-Accept','2025-03-13 20:59:39.071750',''),
(33,'demo','','Access-Accept','2025-03-13 21:00:48.197766',''),
(34,'demo','0x018417442c52d2ad86cfc18e90cb04a8f0','Access-Accept','2025-03-13 21:01:29.384971',''),
(35,'demo','','Access-Accept','2025-03-13 21:02:15.634767',''),
(36,'demo','','Access-Accept','2025-03-13 21:02:49.821457',''),
(37,'demo','12345','Access-Accept','2025-03-13 21:03:58.977054',''),
(38,'demo','12345','Access-Accept','2025-03-13 21:05:08.150184',''),
(39,'demo','12345','Access-Accept','2025-03-13 21:06:17.258926',''),
(40,'demo','12345','Access-Accept','2025-03-13 21:07:26.388481',''),
(41,'demo','12345','Access-Accept','2025-03-13 21:08:34.508747',''),
(42,'demo','12345','Access-Reject','2025-03-13 21:09:42.618722',''),
(43,'demo','12345','Access-Reject','2025-03-13 21:10:51.909041',''),
(44,'demo_user','12345','Access-Accept','2025-03-13 21:11:10.253131',''),
(45,'demo_user','12345','Access-Accept','2025-03-13 21:12:19.372784',''),
(46,'demo_user','12345','Access-Accept','2025-03-13 21:13:28.493356',''),
(47,'demo_user','12345','Access-Accept','2025-03-13 21:14:37.613171',''),
(48,'demo_user','12345','Access-Accept','2025-03-13 21:15:45.836876',''),
(49,'demo_user','12345','Access-Accept','2025-03-13 21:16:55.053915',''),
(50,'demo_user','12345','Access-Accept','2025-03-13 21:19:11.804300',''),
(51,'demo_user','12345','Access-Accept','2025-03-13 21:20:20.157705',''),
(52,'demo_user','12345','Access-Accept','2025-03-13 21:21:29.464879',''),
(53,'demo_user','12345','Access-Accept','2025-03-13 21:22:38.585530',''),
(54,'demo_user','12345','Access-Accept','2025-03-13 21:23:47.805112',''),
(55,'demo_user','12345','Access-Accept','2025-03-13 21:24:55.915433',''),
(56,'demo_user','12345','Access-Accept','2025-03-13 21:26:05.034792',''),
(57,'demo_user','12345','Access-Accept','2025-03-13 21:27:14.248926',''),
(58,'demo_user','12345','Access-Accept','2025-03-13 21:28:23.395027',''),
(59,'demo_user','12345','Access-Accept','2025-03-13 21:29:32.575269',''),
(60,'demo_user','12345','Access-Accept','2025-03-13 21:30:41.685244',''),
(61,'demo_user','12345','Access-Accept','2025-03-13 21:31:50.796443',''),
(62,'demo_user','12345','Access-Accept','2025-03-13 21:32:59.903639',''),
(63,'demo_user','12345','Access-Accept','2025-03-13 21:34:08.013613',''),
(64,'demo_user','12345','Access-Accept','2025-03-13 21:35:17.132861',''),
(65,'demo_user','12345','Access-Accept','2025-03-13 21:36:26.332101',''),
(66,'demo_user','','Access-Accept','2025-03-13 21:37:39.886342',''),
(67,'demo_user','','Access-Accept','2025-03-13 21:38:48.014751',''),
(68,'demo_user','','Access-Accept','2025-03-13 21:39:56.143971',''),
(69,'demo_user','','Access-Accept','2025-03-13 21:41:05.318033',''),
(70,'demo_user','','Access-Accept','2025-03-13 21:42:14.484042',''),
(71,'demo_user','','Access-Accept','2025-03-13 21:43:23.622557',''),
(72,'demo_user','','Access-Accept','2025-03-13 21:44:32.782560',''),
(73,'demo_user','','Access-Accept','2025-03-13 21:49:26.769446',''),
(74,'demo_user','','Access-Accept','2025-03-13 21:50:35.894793',''),
(75,'demo_user','','Access-Accept','2025-03-13 21:51:44.184963',''),
(76,'demo_user','','Access-Accept','2025-03-13 21:52:52.314197',''),
(77,'demo_user','','Access-Accept','2025-03-13 21:54:01.433881',''),
(78,'demo_user','','Access-Accept','2025-03-13 21:55:10.596796',''),
(79,'demo_user','','Access-Accept','2025-03-13 21:56:19.733315',''),
(80,'demo_user','','Access-Accept','2025-03-13 21:57:28.863009',''),
(81,'demo_user','','Access-Accept','2025-03-13 21:58:37.993126',''),
(132,'demo_user','','Access-Accept','2025-03-13 22:57:37.919379',''),
(158,'carlos','','Access-Reject','2025-03-14 20:31:23.434032',''),
(159,'test','','Access-Accept','2025-03-14 20:31:51.489889',''),
(160,'test','0x01966f60c43c0ecefaecb4ae1e873bbc6d','Access-Accept','2025-03-14 20:35:50.656491',''),
(161,'test','0x0175d9e7705766c440a8bbf4bd560b5cc4','Access-Accept','2025-03-14 20:36:25.300913',''),
(162,'test','','Access-Accept','2025-03-14 20:39:24.424202',''),
(163,'test','','Access-Accept','2025-03-14 20:39:27.085688',''),
(164,'test','','Access-Accept','2025-03-14 20:40:47.820001',''),
(165,'test_user','','Access-Reject','2025-03-14 20:44:51.292666',''),
(166,'demo_user','','Access-Accept','2025-03-14 20:45:31.534457',''),
(167,'demo_user','','Access-Accept','2025-03-14 20:50:23.532095',''),
(168,'demo_user','','Access-Accept','2025-03-14 20:51:25.609411',''),
(169,'demo_user','','Access-Accept','2025-03-14 20:51:32.672618',''),
(170,'demo_user','','Access-Accept','2025-03-14 21:21:52.506863',''),
(171,'demo_user','0x017e9bd9b61d9b546968b0b29e67cb5be3','Access-Accept','2025-03-14 21:24:55.036296',''),
(172,'demo_user','0x018c2e65ef47cda7d9833ad75685bc23c5','Access-Accept','2025-03-14 21:26:50.327129',''),
(173,'demo_user','','Access-Accept','2025-03-19 18:41:40.416960',''),
(174,'demo_user','0x01d082050d24f646f88eacd843652f4a2b','Access-Accept','2025-03-19 18:43:41.359547',''),
(175,'test','0x018cb079012a05470f22f2941a95823465','Access-Accept','2025-03-19 18:45:29.888181',''),
(176,'user1','','Access-Accept','2025-04-06 23:08:42.051407',''),
(177,'user1','','Access-Accept','2025-04-06 23:10:23.751817',''),
(178,'user2','','Access-Accept','2025-04-06 23:14:13.461091',''),
(179,'demo_user','','Access-Accept','2025-04-06 23:15:37.381831',''),
(180,'demo_user','','Access-Accept','2025-04-06 23:24:20.990587',''),
(181,'cmamani','','Access-Reject','2025-04-06 23:28:38.377167','');
/*!40000 ALTER TABLE `radpostauth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radreply`
--

DROP TABLE IF EXISTS `radreply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radreply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '=',
  `value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `username` (`username`(32))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radreply`
--

LOCK TABLES `radreply` WRITE;
/*!40000 ALTER TABLE `radreply` DISABLE KEYS */;
INSERT INTO `radreply` VALUES
(3,'demo_user','Framed-IP-Address',':=','10.2.2.15'),
(4,'demo_user','Mikrotik-Rate-Limit',':=','512k/1M'),
(5,'demo_user','Framed-Protocol',':=','PPP');
/*!40000 ALTER TABLE `radreply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radusergroup`
--

DROP TABLE IF EXISTS `radusergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radusergroup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `priority` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `username` (`username`(32))
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radusergroup`
--

LOCK TABLES `radusergroup` WRITE;
/*!40000 ALTER TABLE `radusergroup` DISABLE KEYS */;
INSERT INTO `radusergroup` VALUES
(59,'test','premiun1',1),
(60,'demo_user','premiun1',1),
(61,'sven','premiun1',1);
/*!40000 ALTER TABLE `radusergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('FqHf8vASZ08ALXM6RbNaV6OQPwqYgbJ1FfeBeQGr',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGh1S0o5bElxQWJRRGIzZTdiSUlIVklGellMczFnTVFGSXB6TXlzaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ydXNlcmcvcHJlbWl1bjEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1749958909);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Test User','test@example.com','2025-06-03 02:33:05','$2y$12$bmqO5AoN/UqGablg.b4HrudPZEHOTCkbnyeOcALkBSwEaUeiqrLje','7L8qGAaI2e5VybVtp4p4vM4k7Bdv1i9eEcYvOsxVyEOcIreA96LhQT4bJ1Ss','2025-06-03 02:33:05','2025-06-03 02:33:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-09  0:26:26

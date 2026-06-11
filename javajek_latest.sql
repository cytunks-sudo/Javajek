-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: javajek
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'JavaJek',
  `login_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `merchant_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_map_icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#f97316',
  `secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#fb923c',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_driver_radius` int NOT NULL DEFAULT '5',
  `driver_min_balance` decimal(15,2) NOT NULL DEFAULT '20000.00',
  `food_price_markup_percent` decimal(8,2) NOT NULL DEFAULT '10.00',
  `food_driver_commission_percent` decimal(8,2) NOT NULL DEFAULT '20.00',
  `ride_driver_commission_percent` decimal(8,2) NOT NULL DEFAULT '20.00',
  `car_driver_commission_percent` decimal(8,2) NOT NULL DEFAULT '20.00',
  `ride_search_radius` int NOT NULL DEFAULT '10',
  `merchant_radius` int NOT NULL DEFAULT '20',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_settings`
--

LOCK TABLES `app_settings` WRITE;
/*!40000 ALTER TABLE `app_settings` DISABLE KEYS */;
INSERT INTO `app_settings` VALUES (1,'JavaJek',NULL,NULL,NULL,NULL,NULL,NULL,'#ffa185','#f97316',0,'2026-05-30 10:28:07','2026-06-05 07:26:36',5,10000.00,10.00,10.00,10.00,10.00,5,20);
/*!40000 ALTER TABLE `app_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `chat_type` enum('customer_merchant','customer_driver','merchant_driver') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `sender_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_order_id_foreign` (`order_id`),
  KEY `chat_messages_sender_id_foreign` (`sender_id`),
  CONSTRAINT `chat_messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
INSERT INTO `chat_messages` VALUES (31,6,'customer_driver',3,'driver','hallo kk',NULL,1,'2026-06-03 08:32:22','2026-06-03 08:32:42'),(32,6,'customer_driver',3,'driver','halo',NULL,1,'2026-06-03 08:45:13','2026-06-03 08:46:04'),(33,6,'customer_driver',3,'driver','hai',NULL,1,'2026-06-03 08:45:21','2026-06-03 08:46:04'),(34,6,'customer_driver',5,'customer','ya kk',NULL,1,'2026-06-03 08:58:34','2026-06-03 08:59:24'),(35,6,'customer_driver',5,'customer','','chat-images/H31elFoERcFYNdQbJgoD2JfBfICfCWw8IpylT7EQ.png',1,'2026-06-03 09:11:42','2026-06-03 09:11:46'),(36,6,'customer_driver',3,'driver','','chat-images/OzYV3rsn85tLe4FPASnXJFedQvNPlO7GJb6ENKl0.jpg',1,'2026-06-03 09:17:06','2026-06-03 09:23:49'),(37,6,'customer_driver',3,'driver','ini','chat-images/zrL6u8Z6GR3psLVJ3cP5hzbqRQ5bhDffjA5gNkdP.jpg',1,'2026-06-03 10:03:44','2026-06-03 15:12:05');
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_settings`
--

DROP TABLE IF EXISTS `delivery_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delivery_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `base_fee` int NOT NULL DEFAULT '3000',
  `per_km_fee` int NOT NULL DEFAULT '2000',
  `minimum_fee` int NOT NULL DEFAULT '5000',
  `max_driver_radius_km` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_settings`
--

LOCK TABLES `delivery_settings` WRITE;
/*!40000 ALTER TABLE `delivery_settings` DISABLE KEYS */;
INSERT INTO `delivery_settings` VALUES (1,3000,2500,8000,20,'2026-05-30 10:15:55','2026-06-04 13:48:40');
/*!40000 ALTER TABLE `delivery_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_applications`
--

DROP TABLE IF EXISTS `driver_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sim_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `driver_applications_user_id_foreign` (`user_id`),
  CONSTRAINT `driver_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_applications`
--

LOCK TABLES `driver_applications` WRITE;
/*!40000 ALTER TABLE `driver_applications` DISABLE KEYS */;
INSERT INTO `driver_applications` VALUES (1,3,'08984545978','motor','k 5522 uu','tayu',NULL,NULL,-6.5380055,111.0459620,'approved','2026-05-30 10:39:21','2026-05-30 11:12:57'),(2,5,'08984545978','motor','k 6949 uu','Tayukulon','driver-applications/mvPW7kWWZBHrRhG2NY3ideQEX3FLnAqjyukoGHGM.jpg','driver-applications/L3SzenwUMclAEOswNBGsVc9bUXIc67LmxRhU4jY4.jpg',-6.5380055,111.0459620,'approved','2026-05-30 15:06:04','2026-05-30 15:38:02');
/*!40000 ALTER TABLE `driver_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_vehicles`
--

DROP TABLE IF EXISTS `driver_vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` bigint unsigned NOT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'motor',
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `driver_vehicles_driver_id_foreign` (`driver_id`),
  CONSTRAINT `driver_vehicles_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_vehicles`
--

LOCK TABLES `driver_vehicles` WRITE;
/*!40000 ALTER TABLE `driver_vehicles` DISABLE KEYS */;
INSERT INTO `driver_vehicles` VALUES (1,1,'motor','K 4444 DA','vario','putih',1,'2026-05-30 11:29:19','2026-05-30 11:29:24');
/*!40000 ALTER TABLE `driver_vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_wallet_transactions`
--

DROP TABLE IF EXISTS `driver_wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_wallet_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_before` decimal(15,2) NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_wallet_transactions`
--

LOCK TABLES `driver_wallet_transactions` WRITE;
/*!40000 ALTER TABLE `driver_wallet_transactions` DISABLE KEYS */;
INSERT INTO `driver_wallet_transactions` VALUES (1,1,'topup',50000.00,0.00,50000.00,'Topup saldo manual admin',NULL,1,'2026-06-03 18:19:19','2026-06-03 18:19:19'),(2,1,'adjustment',-50000.00,50000.00,0.00,'Withdraw saldo driver via admin',NULL,1,'2026-06-03 18:19:51','2026-06-03 18:19:51'),(3,2,'topup',20000.00,0.00,20000.00,'Topup saldo manual admin',NULL,1,'2026-06-03 18:34:19','2026-06-03 18:34:19'),(4,1,'topup',20000.00,0.00,20000.00,'Topup saldo manual admin',NULL,1,'2026-06-03 18:34:29','2026-06-03 18:34:29'),(5,1,'commission',-3700.00,20000.00,16300.00,'Komisi admin OJEK order JR040626015879',8,NULL,'2026-06-03 18:58:27','2026-06-03 18:58:27'),(6,1,'commission',-2050.00,16300.00,14250.00,'Komisi admin OJEK order JR040626020099',9,NULL,'2026-06-03 19:01:02','2026-06-03 19:01:02'),(7,1,'commission',-50650.00,14250.00,-36400.00,'Komisi admin OJEK order JR040626020469',10,NULL,'2026-06-03 19:06:50','2026-06-03 19:06:50'),(8,1,'topup',50000.00,-36400.00,13600.00,'Topup saldo manual admin',NULL,1,'2026-06-03 19:10:22','2026-06-03 19:10:22'),(9,1,'commission',-3550.00,13600.00,10050.00,'Komisi admin OJEK order JR040626021140',11,NULL,'2026-06-03 19:11:21','2026-06-03 19:11:21'),(10,1,'commission',-3500.00,10050.00,6550.00,'Komisi admin OJEK order JR040626021120',12,NULL,'2026-06-03 19:11:59','2026-06-03 19:11:59'),(11,1,'topup',10000.00,6550.00,16550.00,'Topup saldo manual admin',NULL,1,'2026-06-04 01:57:47','2026-06-04 01:57:47'),(12,1,'commission',-55000.00,16550.00,-38450.00,'Komisi admin FOOD order JF040626090766',14,NULL,'2026-06-04 02:07:45','2026-06-04 02:07:45'),(13,1,'topup',200000.00,-38450.00,161550.00,'Topup saldo manual admin',NULL,1,'2026-06-04 02:50:27','2026-06-04 02:50:27'),(14,1,'commission',-6050.00,161550.00,155500.00,'Komisi admin FOOD order JF040626095448',15,NULL,'2026-06-04 02:55:05','2026-06-04 02:55:05'),(15,1,'commission',-2500.00,155500.00,153000.00,'Komisi admin FOOD order JF040626121070',16,NULL,'2026-06-04 05:11:04','2026-06-04 05:11:04'),(16,1,'commission',-3000.00,153000.00,150000.00,'Komisi admin FOOD order JF040626123997',17,NULL,'2026-06-04 07:44:31','2026-06-04 07:44:31'),(17,1,'commission',-3000.00,150000.00,147000.00,'Komisi admin FOOD order JF040626144555',18,NULL,'2026-06-04 07:45:43','2026-06-04 07:45:43');
/*!40000 ALTER TABLE `driver_wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drivers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('offline','online','busy') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'offline',
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `last_location_update` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `penalty_until` timestamp NULL DEFAULT NULL,
  `penalty_reason` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `drivers_user_id_foreign` (`user_id`),
  CONSTRAINT `drivers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` VALUES (1,3,'motor','k 5522 uu','online',147000.00,-7.4324680,109.3395000,'2026-06-05 04:19:21','2026-05-30 11:12:57','2026-06-05 04:19:21',NULL,NULL),(2,5,'motor','k 6949 uu','online',20000.00,-6.5380055,111.0459620,'2026-06-01 00:12:55','2026-05-30 15:38:02','2026-06-03 19:01:56',NULL,NULL);
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `foods`
--

DROP TABLE IF EXISTS `foods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `foods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','soldout') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foods_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `foods_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foods`
--

LOCK TABLES `foods` WRITE;
/*!40000 ALTER TABLE `foods` DISABLE KEYS */;
INSERT INTO `foods` VALUES (1,1,'ayam geprek','pedas enak',15000.00,'foods/ABoVFw9SyKb7L8xFs6UH7GkGa1ahgHhhXOOKfHFN.png','available','2026-06-03 00:22:05','2026-06-03 00:22:05'),(2,1,'Roti Pizza','Roti yang enak',20000.00,NULL,'available','2026-06-03 15:32:27','2026-06-03 15:32:27');
/*!40000 ALTER TABLE `foods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_16_064030_add_role_to_users_table',1),(5,'2026_05_16_070406_create_restaurants_table',1),(6,'2026_05_16_072541_create_food_table',1),(7,'2026_05_16_073443_create_orders_table',1),(8,'2026_05_16_073557_create_order_items_table',1),(9,'2026_05_16_084407_create_drivers_table',1),(10,'2026_05_16_120637_add_driver_id_to_orders_table',1),(11,'2026_05_17_054852_add_restaurant_id_to_orders_table',1),(12,'2026_05_17_055743_add_delivery_fields_to_restaurants_table',1),(13,'2026_05_17_055919_add_owner_id_to_restaurants_table',1),(14,'2026_05_17_061914_create_user_roles_table',1),(15,'2026_05_17_063501_create_driver_applications_table',1),(16,'2026_05_17_065533_add_penalty_to_drivers_table',1),(17,'2026_05_17_072440_add_profile_fields_to_users_table',1),(18,'2026_05_17_153859_add_order_flow_columns_to_orders_table',1),(19,'2026_05_18_002634_add_merchant_detail_columns_to_restaurants_table',1),(20,'2026_05_18_005113_change_status_enum_on_restaurants_table',1),(21,'2026_05_18_014248_add_open_days_to_restaurants_table',1),(22,'2026_05_18_020432_add_manual_closed_to_restaurants_table',1),(23,'2026_05_18_152125_change_status_enum_on_orders_table',1),(24,'2026_05_19_063253_add_notif_sound_to_users_table',1),(25,'2026_05_28_123046_add_tracking_and_delivery_fields_to_orders_and_drivers',1),(26,'2026_05_28_160445_create_delivery_settings_table',1),(27,'2026_05_29_075600_create_ride_settings_table',1),(28,'2026_05_29_080721_add_order_type_to_orders_table',1),(29,'2026_05_29_081332_add_ojek_fields_to_orders_table',1),(30,'2026_05_29_105608_create_app_settings_table',1),(31,'2026_05_29_114046_add_radius_fields_to_app_settings_table',1),(32,'2026_05_30_040510_create_driver_vehicles_table',1),(33,'2026_05_30_061911_add_location_columns_to_orders_table',1),(34,'2026_05_30_062731_add_address_to_orders_table',1),(35,'2026_05_30_064640_add_order_number_to_orders_table',1),(36,'2026_05_30_072109_add_car_fee_columns_to_ride_settings_table',1),(37,'2026_05_30_162259_fix_username_to_users_table',1),(38,'2026_05_30_174432_add_photo_and_sim_to_driver_applications_table',2),(39,'2026_06_03_064400_create_chat_messages_table',3),(40,'2026_06_03_070237_add_chat_type_to_chat_messages_table',4),(41,'2026_06_03_150939_add_chat_status_to_users',5),(42,'2026_06_03_160508_add_image_to_chat_messages_table',6),(43,'2026_06_03_213850_add_balance_to_drivers_table',7),(44,'2026_06_03_213911_create_driver_wallet_transactions_table',7),(45,'2026_06_03_214950_add_driver_wallet_settings_to_app_settings_table',8),(46,'2026_06_04_011837_add_created_by_to_driver_wallet_transactions_table',9),(47,'2026_06_04_014748_update_order_status_enum',10),(48,'2026_06_04_084350_add_food_commission_fields_to_orders_table',11),(49,'2026_06_05_093659_create_order_ratings_table',12),(50,'2026_06_05_142046_create_vouchers_table',13),(51,'2026_06_05_145540_add_voucher_fields_to_orders_table',14),(52,'2026_06_05_190710_add_image_to_vouchers_table',15),(53,'2026_06_05_215710_add_service_type_to_vouchers_table',16),(54,'2026_06_06_005730_create_voucher_usages_table',17);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `food_id` bigint unsigned NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_food_id_foreign` (`food_id`),
  CONSTRAINT `order_items_food_id_foreign` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (3,6,1,1,15000.00,'2026-06-03 08:31:59','2026-06-03 08:31:59'),(4,13,1,1,16500.00,'2026-06-04 01:55:56','2026-06-04 01:55:56'),(5,13,2,1,22000.00,'2026-06-04 01:55:56','2026-06-04 01:55:56'),(6,14,1,3,16500.00,'2026-06-04 02:07:29','2026-06-04 02:07:29'),(7,15,1,3,16500.00,'2026-06-04 02:54:51','2026-06-04 02:54:51'),(8,16,2,1,22000.00,'2026-06-04 05:10:00','2026-06-04 05:10:00'),(9,17,2,1,22000.00,'2026-06-04 05:39:34','2026-06-04 05:39:34'),(10,18,2,1,22000.00,'2026-06-04 07:45:27','2026-06-04 07:45:27'),(11,19,2,4,22000.00,'2026-06-05 08:27:02','2026-06-05 08:27:02'),(12,19,1,1,16500.00,'2026-06-05 08:27:02','2026-06-05 08:27:02');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_ratings`
--

DROP TABLE IF EXISTS `order_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned DEFAULT NULL,
  `restaurant_id` bigint unsigned DEFAULT NULL,
  `driver_rating` tinyint DEFAULT NULL,
  `driver_review` text COLLATE utf8mb4_unicode_ci,
  `merchant_rating` tinyint DEFAULT NULL,
  `merchant_review` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_ratings_order_id_user_id_unique` (`order_id`,`user_id`),
  KEY `order_ratings_user_id_foreign` (`user_id`),
  KEY `order_ratings_driver_id_foreign` (`driver_id`),
  KEY `order_ratings_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `order_ratings_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_ratings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_ratings_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_ratings`
--

LOCK TABLES `order_ratings` WRITE;
/*!40000 ALTER TABLE `order_ratings` DISABLE KEYS */;
INSERT INTO `order_ratings` VALUES (1,18,1,1,1,5,'Ramah',NULL,NULL,'2026-06-05 03:10:34','2026-06-05 03:10:34'),(2,17,1,1,1,2,NULL,NULL,NULL,'2026-06-05 03:11:25','2026-06-05 03:11:25'),(3,15,1,1,1,5,NULL,4,NULL,'2026-06-05 03:39:23','2026-06-05 03:39:23'),(4,14,1,1,1,5,NULL,5,NULL,'2026-06-05 04:07:35','2026-06-05 04:07:35'),(5,12,1,1,NULL,5,NULL,NULL,NULL,'2026-06-05 04:07:43','2026-06-05 04:07:43'),(6,16,1,1,1,5,NULL,5,NULL,'2026-06-05 04:07:51','2026-06-05 04:07:51');
/*!40000 ALTER TABLE `order_ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `food_original_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `food_markup_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `delivery_commission_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `admin_commission_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','waiting_response','searching_driver','merchant_rejected','driver_to_pickup','driver_to_destination','driver_to_merchant','dalam_pengiriman','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `merchant_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `driver_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `driver_reject_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `driver_id` bigint unsigned DEFAULT NULL,
  `customer_latitude` decimal(10,7) DEFAULT NULL,
  `customer_longitude` decimal(10,7) DEFAULT NULL,
  `merchant_latitude` decimal(10,7) DEFAULT NULL,
  `merchant_longitude` decimal(10,7) DEFAULT NULL,
  `driver_latitude` decimal(10,7) DEFAULT NULL,
  `driver_longitude` decimal(10,7) DEFAULT NULL,
  `distance_km` decimal(8,2) NOT NULL DEFAULT '0.00',
  `delivery_fee` int NOT NULL DEFAULT '0',
  `grand_total` int NOT NULL DEFAULT '0',
  `voucher_id` bigint unsigned DEFAULT NULL,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_discount` decimal(12,0) NOT NULL DEFAULT '0',
  `restaurant_id` bigint unsigned DEFAULT NULL,
  `pickup_address` text COLLATE utf8mb4_unicode_ci,
  `destination_address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `pickup_latitude` decimal(10,7) DEFAULT NULL,
  `pickup_longitude` decimal(10,7) DEFAULT NULL,
  `destination_latitude` decimal(10,7) DEFAULT NULL,
  `destination_longitude` decimal(10,7) DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_driver_id_foreign` (`driver_id`),
  KEY `orders_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `orders_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (6,'JF030626153180',5,NULL,444000.00,0.00,0.00,0.00,0.00,'cancelled','cancelled','cancelled',0,'2026-06-03 08:31:59','2026-06-03 19:01:56',2,NULL,NULL,NULL,NULL,NULL,NULL,213.00,429000,0,NULL,NULL,0,1,NULL,NULL,-7.4324680,109.3395000,NULL,NULL,NULL,NULL,'Rabak, Purbalingga, Central Java, Java, 53371, Indonesia'),(7,'JR040626014064',1,'ojek',13000.00,0.00,0.00,0.00,0.00,'completed','accepted','accepted',0,'2026-06-03 18:40:42','2026-06-03 18:51:08',1,NULL,NULL,NULL,NULL,NULL,NULL,3.00,0,0,NULL,NULL,0,NULL,'Rabak, Purbalingga, Central Java, Java, 53371, Indonesia','Gambarsari, Purbalingga, Central Java, Java, 53381, Indonesia',NULL,NULL,-7.4324680,109.3395000,-7.4334485,109.3670339,NULL),(8,'JR040626015879',1,'ojek',18500.00,0.00,0.00,0.00,0.00,'completed','accepted','accepted',0,'2026-06-03 18:58:04','2026-06-03 18:58:27',1,NULL,NULL,NULL,NULL,NULL,NULL,5.30,0,0,NULL,NULL,0,NULL,'Rabak, Purbalingga, Central Java, Java, 53371, Indonesia','Sokaraja, Sokaraja Kidul, Sokaraja, Banyumas, Central Java, Java, 53191, Indonesia',NULL,NULL,-7.4324680,109.3395000,-7.4601560,109.2999559,NULL),(9,'JR040626020099',1,'ojek',20500.00,0.00,0.00,0.00,0.00,'completed','accepted','accepted',0,'2026-06-03 19:00:38','2026-06-03 19:01:02',1,NULL,NULL,NULL,NULL,NULL,NULL,6.20,0,0,NULL,NULL,0,NULL,'Puskesmas Kalimanah, Jalan Mayor Jenderal Sungkono, Komp. PT Pertani Purbalingga, Planjan, Selabaya, Purbalingga, Central Java, Java, 53371, Indonesia','Sokaraja Wetan, Sokaraja, Banyumas, Central Java, Java, 53181, Indonesia',NULL,NULL,-7.4140378,109.3419838,-7.4545497,109.3038667,NULL),(10,'JR040626020469',1,'ojek',506500.00,0.00,0.00,0.00,0.00,'completed','accepted','accepted',0,'2026-06-03 19:04:00','2026-06-03 19:06:50',1,NULL,NULL,NULL,NULL,NULL,NULL,200.40,0,0,NULL,NULL,0,NULL,'Rabak, Purbalingga, Central Java, Java, 53371, Indonesia','Sulo, Cluwak, Pati, Central Java, Java, Indonesia',NULL,NULL,-7.4324680,109.3395000,-6.5657066,110.9317036,NULL),(11,'JR040626021140',1,'ojek',35500.00,0.00,0.00,0.00,0.00,'completed','accepted','accepted',0,'2026-06-03 19:11:03','2026-06-03 19:11:21',1,NULL,NULL,NULL,NULL,NULL,NULL,12.10,0,0,NULL,NULL,0,NULL,'Rabak, Purbalingga, Central Java, Java, 53371, Indonesia','Klampok, Banjarnegara, Central Java, Java, 53475, Indonesia',NULL,NULL,-7.4324680,109.3395000,-7.4618581,109.4448715,NULL),(12,'JR040626021120',1,'ojek',35000.00,0.00,0.00,0.00,0.00,'completed','accepted','accepted',0,'2026-06-03 19:11:41','2026-06-03 19:11:59',1,NULL,NULL,NULL,NULL,NULL,NULL,11.90,0,0,NULL,NULL,0,NULL,'Pagutan, Mrebet, Purbalingga, Central Java, Java, 53362, Indonesia','Jompo, Purbalingga, Central Java, Java, 53371, Indonesia',NULL,NULL,-7.3345030,109.3573868,-7.4400497,109.3388434,NULL),(13,'JF040626085569',1,NULL,46000.00,0.00,0.00,0.00,0.00,'completed','accepted','accepted',0,'2026-06-04 01:55:56','2026-06-04 01:58:27',1,NULL,NULL,NULL,NULL,NULL,NULL,2.10,7500,0,NULL,NULL,0,1,NULL,NULL,-6.5571243,111.0447496,NULL,NULL,NULL,NULL,'Ketapang, Tayu, Pati, Central Java, Java, Indonesia'),(14,'JF040626090766',1,NULL,54500.00,0.00,54500.00,500.00,55000.00,'completed','accepted','accepted',0,'2026-06-04 02:07:29','2026-06-04 02:07:45',1,NULL,NULL,NULL,NULL,NULL,NULL,0.50,5000,0,NULL,NULL,0,1,NULL,NULL,-6.5392453,111.0498678,NULL,NULL,NULL,NULL,'Kembang, Tayu, Pati, Central Java, Java, Indonesia'),(15,'JF040626095448',1,NULL,65000.00,45000.00,4500.00,1550.00,6050.00,'completed','accepted','accepted',0,'2026-06-04 02:54:51','2026-06-04 02:55:05',1,NULL,NULL,NULL,NULL,NULL,NULL,6.10,15500,0,NULL,NULL,0,1,NULL,NULL,-6.5927832,111.0414799,NULL,NULL,NULL,NULL,'Centong, Margoyoso, Pati, Central Java, Java, 59154, Indonesia'),(16,'JF040626121070',1,NULL,27000.00,20000.00,2000.00,500.00,2500.00,'completed','accepted','accepted',0,'2026-06-04 05:10:00','2026-06-04 05:11:04',1,NULL,NULL,NULL,NULL,NULL,NULL,0.40,5000,0,NULL,NULL,0,1,NULL,NULL,-6.5393122,111.0493114,NULL,NULL,NULL,NULL,'Kembang, Tayu, Pati, Central Java, Java, Indonesia'),(17,'JF040626123997',1,NULL,32000.00,20000.00,2000.00,1000.00,3000.00,'completed','accepted','accepted',0,'2026-06-04 05:39:34','2026-06-04 07:44:31',1,NULL,NULL,NULL,NULL,NULL,NULL,3.30,10000,0,NULL,NULL,0,1,NULL,NULL,-6.5163817,111.0260407,NULL,NULL,NULL,NULL,'Tayu, Pati, Central Java, Java, Indonesia'),(18,'JF040626144555',1,NULL,32000.00,20000.00,2000.00,1000.00,3000.00,'completed','accepted','accepted',0,'2026-06-04 07:45:27','2026-06-04 07:45:43',1,NULL,NULL,NULL,NULL,NULL,NULL,3.30,10000,0,NULL,NULL,0,1,NULL,NULL,-6.5226804,111.0207951,NULL,NULL,NULL,NULL,'Jering, Tayu, Pati, Central Java, Java, Indonesia'),(19,'JF050626152795',1,NULL,112500.00,0.00,0.00,0.00,0.00,'waiting_response','pending','pending',0,'2026-06-05 08:27:02','2026-06-05 17:41:19',2,NULL,NULL,NULL,NULL,NULL,NULL,0.20,8000,0,NULL,NULL,0,1,NULL,NULL,-6.5367931,111.0467484,NULL,NULL,NULL,NULL,'Gares, Tayu, Pati, Central Java, Java, Indonesia'),(20,'JR060626012456',1,'ojek',44550.00,0.00,0.00,0.00,0.00,'searching_driver','accepted','pending',0,'2026-06-05 18:24:17','2026-06-05 18:24:17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,17.70,0,0,3,'OJEK JAVA',4950,NULL,'Rabak, Purbalingga, Central Java, Java, 53371, Indonesia','Kejobong, Purbalingga, Central Java, Java, Indonesia',NULL,NULL,-7.4324680,109.3395000,-7.3917056,109.4947220,NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `restaurants`
--

DROP TABLE IF EXISTS `restaurants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `open_days` json DEFAULT NULL,
  `manual_closed` tinyint(1) NOT NULL DEFAULT '0',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` enum('pending','active','rejected','open','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `delivery_radius` int NOT NULL DEFAULT '5',
  `delivery_fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `minimum_order` decimal(12,2) NOT NULL DEFAULT '0.00',
  `owner_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `restaurants_owner_id_foreign` (`owner_id`),
  CONSTRAINT `restaurants_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurants`
--

LOCK TABLES `restaurants` WRITE;
/*!40000 ALTER TABLE `restaurants` DISABLE KEYS */;
INSERT INTO `restaurants` VALUES (1,'UP Star','Tayukulon','merchants/pA4HoHtHzSYLK1G3BmP9NjfifUsaur6q4F9PsIQN.png','Cafe',NULL,NULL,'{\"Rabu\": {\"open\": \"09:00\", \"close\": \"21:00\"}, \"Jumat\": {\"open\": \"09:00\", \"close\": \"21:00\"}, \"Kamis\": {\"open\": \"09:00\", \"close\": \"21:00\"}, \"Sabtu\": {\"open\": \"09:00\", \"close\": \"21:00\"}, \"Senin\": {\"open\": \"09:00\", \"close\": \"21:00\"}, \"Selasa\": {\"open\": \"09:00\", \"close\": \"21:00\"}}',0,'08984545978',NULL,-6.5383991,111.0458565,'active','2026-05-30 10:29:22','2026-06-04 14:51:22',5,0.00,0.00,2);
/*!40000 ALTER TABLE `restaurants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ride_settings`
--

DROP TABLE IF EXISTS `ride_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ride_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `base_fee` decimal(10,0) NOT NULL DEFAULT '5000',
  `per_km_fee` decimal(10,0) NOT NULL DEFAULT '2500',
  `minimum_fee` decimal(10,0) NOT NULL DEFAULT '8000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `car_base_fee` decimal(10,0) NOT NULL DEFAULT '10000',
  `car_per_km_fee` decimal(10,0) NOT NULL DEFAULT '4000',
  `car_minimum_fee` decimal(10,0) NOT NULL DEFAULT '15000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ride_settings`
--

LOCK TABLES `ride_settings` WRITE;
/*!40000 ALTER TABLE `ride_settings` DISABLE KEYS */;
INSERT INTO `ride_settings` VALUES (1,5000,2500,8000,'2026-05-30 10:15:55','2026-05-30 10:15:55',10000,4000,15000);
/*!40000 ALTER TABLE `ride_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('1UZUdCivEbLJbioaIGF2zTBfCO2cbJCQp2vcIgCM',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJmc2U3ZlJ2NGF4N3RzYlJlVURlOEdwTTdPZG9iN3Nwc0NQSXV4a3N2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3Jlc3RhdXJhbnRzIiwicm91dGUiOm51bGx9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=',1780686380),('CUXwYcp9SdrDedqbMxvg6G4yjNJ5VdCAX79Fd2XL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJwQTlaSnZWOTFDdFRKV2ExbXB6b1RWZnpDZ2Q5a05RSjFkNXBlbEowIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==',1780465835),('JG0zyZko5phsCB9P9DEQlw7y1inKLdbnbNbnt3Vq',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','eyJfdG9rZW4iOiJUSFowZzN0UjA1Z2JsSDdFUUVSNDVkTVlxNEdVWmMxZnZpaUVmY2Q0IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvbm90aWZpY2F0aW9uc1wvY291bnQifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jYXIiLCJyb3V0ZSI6ImNhci5wYWdlIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo2LCJvamVrX2RhdGEiOnsicGlja3VwX2xhdGl0dWRlIjoiLTcuNDMyNDY4IiwicGlja3VwX2xvbmdpdHVkZSI6IjEwOS4zMzk1IiwiZGVzdGluYXRpb25fbGF0aXR1ZGUiOiItNy40MjU3OTM4NzM0ODQ1NjMiLCJkZXN0aW5hdGlvbl9sb25naXR1ZGUiOiIxMDkuNTg4NjIzMDQ2ODc1MDEiLCJwaWNrdXBfYWRkcmVzcyI6IlJhYmFrLCBQdXJiYWxpbmdnYSwgQ2VudHJhbCBKYXZhLCBKYXZhLCA1MzM3MSwgSW5kb25lc2lhIiwiZGVzdGluYXRpb25fYWRkcmVzcyI6Ikd1bWl3YW5nLCBCYW5qYXJuZWdhcmEsIENlbnRyYWwgSmF2YSwgSmF2YSwgNTM0NzIsIEluZG9uZXNpYSIsImRpc3RhbmNlX2ttIjoyNy41LCJmYXJlX2JlZm9yZSI6NzQwMDAsImZhcmUiOjc0MDAwLCJ2b3VjaGVyX2lkIjpudWxsLCJ2b3VjaGVyX2NvZGUiOm51bGwsInZvdWNoZXJfZGlzY291bnQiOjB9fQ==',1780677783),('Ki1lDPha5ff0ZlFUFOPaxkw54FbEX15LNa2KvAki',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','eyJfdG9rZW4iOiJNbEhrSzE4cUp6SDhGTUJIOXVVNDEwemxrVlYwZTJoT0RCaTRaWTNNIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2RyaXZlclwvYWN0aXZlLWxvY2F0aW9ucyIsInJvdXRlIjpudWxsfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjV9',1780272775),('XLUX89MTikTmTOFpnjnBrf4RyhIedDvjTKsjRJlL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJuckJBM2ZYeW5DVFNzeWRYemY4eDJyYmZKalhiUVk3WXR6VktUQzBNIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvbWVyY2hhbnRcL25vdGlmLWNvdW50In0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn19',1780447229),('yGei2xiD4SHtUnLiWAv0pIksYORTiSNLFbJzOH8y',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','eyJfdG9rZW4iOiI5aUhJbUxidk9wM2ZtUlY5aTJROXRkNXFnbVRRTEdrN1pZMmdzRXZBIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==',1780624068);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role` enum('customer','driver','merchant','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_roles_user_id_role_unique` (`user_id`,`role`),
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (1,3,'driver','approved','2026-05-30 10:39:21','2026-05-30 11:12:57'),(2,5,'driver','approved','2026-05-30 15:06:04','2026-05-30 15:38:02');
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notif_sound_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_hp',
  `notif_sound_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','admin@javajek.local',NULL,'$2y$12$B9lWFKgOYMF8FFMI5d5NLOMTSUj.inCOZXGDv6NHFAeStt87An4Mq','default_hp',NULL,'H1d9NpPd9jyJK02Nb4x2CEeb8gLk90WYrqVzRWYKIPKDyfpZwUqpf5Or3du5','2026-05-30 10:15:56','2026-06-05 19:06:20','admin','080000000001','JavaJek Admin',-7.4324680,109.3395000,NULL,'2026-06-05 19:06:20'),(2,'Merchant Demo','merchant','merchant@javajek.local',NULL,'$2y$12$uHYjKlrg1L7Hr2ymeZCL6.orNH14JwVE6.beGyjL8j6Ci5unuAIn6','default_hp',NULL,NULL,'2026-05-30 10:15:57','2026-06-05 08:27:53','merchant','080000000002','JavaJek Merchant',-7.4324680,109.3395000,NULL,'2026-06-05 08:27:53'),(3,'Driver Demo','driver','driver@javajek.local',NULL,'$2y$12$F8iLc5YULezfRgk3RWWnROSfeIyFszIw7IgWifCPRqkctHZO51rOi','default_hp',NULL,NULL,'2026-05-30 10:15:57','2026-06-05 04:19:21','driver','080000000003','JavaJek Driver',-7.4324680,109.3395000,NULL,'2026-06-05 04:19:21'),(4,'Customer Demo','customer','customer@javajek.local',NULL,'$2y$12$m.8VqT7xM8gko0FPvsHune79u9mHB1uiG5a.rMcTXrR/o9ewSxm8K','default_hp',NULL,NULL,'2026-05-30 10:15:57','2026-05-30 10:15:57','customer','080000000004','JavaJek Customer',NULL,NULL,NULL,NULL),(5,'Edho Januar Syahrizal','edho','edho@cvlmedia.com',NULL,'$2y$12$ahKI/3gWIbab/AG/6K.iAuJDD/Flk/wewGpMVJ1MEK75nDgqK8Gvm','default_hp',NULL,NULL,'2026-05-30 11:35:05','2026-06-03 15:12:46','customer','08984545978','tayu',-7.4324680,109.3395000,NULL,'2026-06-03 15:12:46'),(6,'rio','rio','rio@cvlmedia.com',NULL,'$2y$12$UijvwFy.HMBcbwo7HP18vu8NwgMVFSXtAywNTD0GzaLxLTr7GP5QW','default_hp',NULL,NULL,'2026-06-05 08:28:54','2026-06-05 16:43:03','customer','08984545979','Ds. Tayukulon RT:04 / RW:01\r\nKec. Tayu',-7.4324680,109.3395000,NULL,'2026-06-05 16:43:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher_usages`
--

DROP TABLE IF EXISTS `voucher_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voucher_usages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `voucher_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'food',
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `voucher_usages_voucher_id_index` (`voucher_id`),
  KEY `voucher_usages_user_id_index` (`user_id`),
  KEY `voucher_usages_order_id_index` (`order_id`),
  KEY `voucher_usages_service_type_index` (`service_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher_usages`
--

LOCK TABLES `voucher_usages` WRITE;
/*!40000 ALTER TABLE `voucher_usages` DISABLE KEYS */;
INSERT INTO `voucher_usages` VALUES (1,3,1,20,'OJEK JAVA','ojek',4950.00,'2026-06-05 18:24:17','2026-06-05 18:24:17');
/*!40000 ALTER TABLE `voucher_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vouchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('fixed','percent','free_delivery') COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_type` enum('all','food','ojek','car') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `value` decimal(12,0) NOT NULL DEFAULT '0',
  `minimum_order` decimal(12,0) NOT NULL DEFAULT '0',
  `maximum_discount` decimal(12,0) DEFAULT NULL,
  `quota` int NOT NULL DEFAULT '0',
  `used_count` int NOT NULL DEFAULT '0',
  `is_new_user_only` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vouchers_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vouchers`
--

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` VALUES (1,'NEW_JAVAJEK','Javajek Baru',NULL,'percent','all',10,25000,10000,998,0,1,1,'2026-06-05','2026-12-31','2026-06-05 07:35:35','2026-06-05 15:09:14'),(2,'JAVAJEK_LAGI','Javajek Terus','vouchers/CxkmfudDwa4qKxsHyi8Cdk0RZ7dc8HpqqjKrBSvU.png','free_delivery','all',5000,50000,5000,100,0,0,1,'2026-06-05','2026-06-30','2026-06-05 07:53:29','2026-06-05 15:08:57'),(3,'OJEK JAVA','ojek java','vouchers/pFb8uXGFKuafG1pFUlrSMZHLMRLJVCWBRd9Zu05i.png','percent','all',10,15000,5000,100,1,0,1,'2026-06-05','2026-06-30','2026-06-05 15:10:11','2026-06-05 18:24:17');
/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-06  7:19:37

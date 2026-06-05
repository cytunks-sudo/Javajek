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
INSERT INTO `app_settings` VALUES (1,'JavaJek',NULL,NULL,NULL,NULL,NULL,NULL,'#f97316','#fb923c',0,'2026-05-30 10:28:07','2026-05-30 14:31:20',5,5,20);
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
INSERT INTO `delivery_settings` VALUES (1,3000,2000,5000,20,'2026-05-30 10:15:55','2026-05-30 15:32:54');
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
INSERT INTO `drivers` VALUES (1,3,'motor','k 5522 uu','online',-6.5380055,111.0459620,'2026-05-30 11:34:20','2026-05-30 11:12:57','2026-05-30 15:52:05',NULL,NULL),(2,5,'motor','k 6949 uu','online',-6.5380055,111.0459620,'2026-06-01 00:12:55','2026-05-30 15:38:02','2026-06-01 00:12:55',NULL,NULL);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foods`
--

LOCK TABLES `foods` WRITE;
/*!40000 ALTER TABLE `foods` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_16_064030_add_role_to_users_table',1),(5,'2026_05_16_070406_create_restaurants_table',1),(6,'2026_05_16_072541_create_food_table',1),(7,'2026_05_16_073443_create_orders_table',1),(8,'2026_05_16_073557_create_order_items_table',1),(9,'2026_05_16_084407_create_drivers_table',1),(10,'2026_05_16_120637_add_driver_id_to_orders_table',1),(11,'2026_05_17_054852_add_restaurant_id_to_orders_table',1),(12,'2026_05_17_055743_add_delivery_fields_to_restaurants_table',1),(13,'2026_05_17_055919_add_owner_id_to_restaurants_table',1),(14,'2026_05_17_061914_create_user_roles_table',1),(15,'2026_05_17_063501_create_driver_applications_table',1),(16,'2026_05_17_065533_add_penalty_to_drivers_table',1),(17,'2026_05_17_072440_add_profile_fields_to_users_table',1),(18,'2026_05_17_153859_add_order_flow_columns_to_orders_table',1),(19,'2026_05_18_002634_add_merchant_detail_columns_to_restaurants_table',1),(20,'2026_05_18_005113_change_status_enum_on_restaurants_table',1),(21,'2026_05_18_014248_add_open_days_to_restaurants_table',1),(22,'2026_05_18_020432_add_manual_closed_to_restaurants_table',1),(23,'2026_05_18_152125_change_status_enum_on_orders_table',1),(24,'2026_05_19_063253_add_notif_sound_to_users_table',1),(25,'2026_05_28_123046_add_tracking_and_delivery_fields_to_orders_and_drivers',1),(26,'2026_05_28_160445_create_delivery_settings_table',1),(27,'2026_05_29_075600_create_ride_settings_table',1),(28,'2026_05_29_080721_add_order_type_to_orders_table',1),(29,'2026_05_29_081332_add_ojek_fields_to_orders_table',1),(30,'2026_05_29_105608_create_app_settings_table',1),(31,'2026_05_29_114046_add_radius_fields_to_app_settings_table',1),(32,'2026_05_30_040510_create_driver_vehicles_table',1),(33,'2026_05_30_061911_add_location_columns_to_orders_table',1),(34,'2026_05_30_062731_add_address_to_orders_table',1),(35,'2026_05_30_064640_add_order_number_to_orders_table',1),(36,'2026_05_30_072109_add_car_fee_columns_to_ride_settings_table',1),(37,'2026_05_30_162259_fix_username_to_users_table',1),(38,'2026_05_30_174432_add_photo_and_sim_to_driver_applications_table',2);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
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
  `status` enum('pending','waiting_response','searching_driver','merchant_rejected','driver_to_merchant','dalam_pengiriman','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'JR300526223765',5,'ojek',9500.00,'cancelled','cancelled','cancelled',0,'2026-05-30 15:37:37','2026-05-30 15:52:05',1,NULL,NULL,NULL,NULL,NULL,NULL,1.70,0,0,NULL,'Kembang, Tayu, Pati, Central Java, Java, Indonesia','207, Jaten, Tayu, Pati, Central Java, Java, 59154, Indonesia',NULL,NULL,-6.5380055,111.0459620,-6.5532150,111.0451913,NULL),(2,'JC300526225518',5,'car',35500.00,'searching_driver','accepted','pending',0,'2026-05-30 15:55:15','2026-05-30 15:55:15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,6.40,0,0,NULL,'Pejaten, Margoyoso, Pati, Central Java, Java, 59154, Indonesia','Gares, Tayu, Pati, Central Java, Java, Indonesia',NULL,NULL,-6.5940149,111.0475731,-6.5370560,111.0427666,NULL),(3,'JR300526225651',5,'ojek',16000.00,'waiting_response','accepted','pending',0,'2026-05-30 15:56:01','2026-05-30 16:10:11',1,NULL,NULL,NULL,NULL,NULL,NULL,4.20,0,0,NULL,'Kembang, Tayu, Pati, Central Java, Java, Indonesia','Belahan, Tayu, Pati, Central Java, Java, 59154, Indonesia',NULL,NULL,-6.5380055,111.0459620,-6.5735514,111.0596752,NULL);
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
INSERT INTO `restaurants` VALUES (1,'UP Star','Tayukulon','merchants/pA4HoHtHzSYLK1G3BmP9NjfifUsaur6q4F9PsIQN.png','Cafe','09:00:00','20:00:00',NULL,0,'08984545978',NULL,-6.5383991,111.0458565,'active','2026-05-30 10:29:22','2026-06-02 08:15:59',5,0.00,0.00,2);
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
INSERT INTO `sessions` VALUES ('Ki1lDPha5ff0ZlFUFOPaxkw54FbEX15LNa2KvAki',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','eyJfdG9rZW4iOiJNbEhrSzE4cUp6SDhGTUJIOXVVNDEwemxrVlYwZTJoT0RCaTRaWTNNIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2RyaXZlclwvYWN0aXZlLWxvY2F0aW9ucyIsInJvdXRlIjpudWxsfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjV9',1780272775),('ums0PtwvOwMT4cUr8cUWyEKdL0zAeleHMV5aLxO9',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJieXg0ZXRNN3BmdlNtbGUwTllKWGliM0lYc0RjNldaQkFtMDdZd2ttIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvb3JkZXJzIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJvamVrX2RhdGEiOnsicGlja3VwX2xhdGl0dWRlIjoiLTcuNDMyNDY4IiwicGlja3VwX2xvbmdpdHVkZSI6IjEwOS4zMzk1IiwiZGVzdGluYXRpb25fbGF0aXR1ZGUiOiItNy40MTg4OTQ1MjA5NDEwNTQiLCJkZXN0aW5hdGlvbl9sb25naXR1ZGUiOiIxMDkuMzkzOTA0NDgxNjczOCIsInBpY2t1cF9hZGRyZXNzIjoiUmFiYWssIFB1cmJhbGluZ2dhLCBDZW50cmFsIEphdmEsIEphdmEsIDUzMzcxLCBJbmRvbmVzaWEiLCJkZXN0aW5hdGlvbl9hZGRyZXNzIjoiVG95YXJlamEsIFB1cmJhbGluZ2dhLCBDZW50cmFsIEphdmEsIEphdmEsIDUzMzgxLCBJbmRvbmVzaWEiLCJkaXN0YW5jZV9rbSI6Ni4yLCJmYXJlIjoyMDUwMH19',1780388165);
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','admin@javajek.local',NULL,'$2y$12$B9lWFKgOYMF8FFMI5d5NLOMTSUj.inCOZXGDv6NHFAeStt87An4Mq','default_hp',NULL,'jkCil5csgoEjsoRjAGw5sDlzOmiu86ZOq0GVRw332pH36CDuxHt6ZOaul7nG','2026-05-30 10:15:56','2026-05-30 10:15:56','admin','080000000001','JavaJek Admin',NULL,NULL,NULL),(2,'Merchant Demo','merchant','merchant@javajek.local',NULL,'$2y$12$uHYjKlrg1L7Hr2ymeZCL6.orNH14JwVE6.beGyjL8j6Ci5unuAIn6','default_hp',NULL,NULL,'2026-05-30 10:15:57','2026-05-30 10:15:57','merchant','080000000002','JavaJek Merchant',NULL,NULL,NULL),(3,'Driver Demo','driver','driver@javajek.local',NULL,'$2y$12$F8iLc5YULezfRgk3RWWnROSfeIyFszIw7IgWifCPRqkctHZO51rOi','default_hp',NULL,NULL,'2026-05-30 10:15:57','2026-05-30 10:15:57','driver','080000000003','JavaJek Driver',NULL,NULL,NULL),(4,'Customer Demo','customer','customer@javajek.local',NULL,'$2y$12$m.8VqT7xM8gko0FPvsHune79u9mHB1uiG5a.rMcTXrR/o9ewSxm8K','default_hp',NULL,NULL,'2026-05-30 10:15:57','2026-05-30 10:15:57','customer','080000000004','JavaJek Customer',NULL,NULL,NULL),(5,'Edho Januar Syahrizal','edho','edho@cvlmedia.com',NULL,'$2y$12$ahKI/3gWIbab/AG/6K.iAuJDD/Flk/wewGpMVJ1MEK75nDgqK8Gvm','default_hp',NULL,NULL,'2026-05-30 11:35:05','2026-05-30 11:35:05','customer','08984545978','tayu',-6.5380055,111.0459620,NULL);
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

-- Dump completed on 2026-06-02 15:18:36

-- MySQL dump 10.13  Distrib 8.0.18, for macos10.14 (x86_64)
--
-- Host: localhost    Database: TCI_CLEAN
-- ------------------------------------------------------
-- Server version	8.0.18

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
-- Table structure for table `admin_menu_items`
--

DROP TABLE IF EXISTS `admin_menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `class` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu` int(10) unsigned NOT NULL,
  `depth` int(11) NOT NULL DEFAULT '0',
  `hide_in_header` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_menu_items_menu_foreign` (`menu`),
  CONSTRAINT `admin_menu_items_menu_foreign` FOREIGN KEY (`menu`) REFERENCES `admin_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_menu_items`
--

LOCK TABLES `admin_menu_items` WRITE;
/*!40000 ALTER TABLE `admin_menu_items` DISABLE KEYS */;
INSERT INTO `admin_menu_items` VALUES (1,'Courses','courses',1,1,NULL,1,0,NULL,'2020-11-13 21:14:58','2020-11-13 21:14:58'),(2,'Instructors','teachers',2,2,NULL,1,0,NULL,'2020-11-13 21:14:58','2020-11-13 21:14:58'),(3,'Workshops','workshops',3,3,NULL,1,0,NULL,'2020-11-13 21:14:58','2020-11-13 21:14:58');
/*!40000 ALTER TABLE `admin_menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_menus`
--

DROP TABLE IF EXISTS `admin_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_menus`
--

LOCK TABLES `admin_menus` WRITE;
/*!40000 ALTER TABLE `admin_menus` DISABLE KEYS */;
INSERT INTO `admin_menus` VALUES (1,'nav-menu','2020-08-21 01:04:01','2020-08-21 01:04:01');
/*!40000 ALTER TABLE `admin_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assignments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assignment_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `full_text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `position` int(10) unsigned DEFAULT NULL,
  `free_lesson` tinyint(4) DEFAULT '1',
  `published` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_key` (`lesson_id`),
  KEY `lessons_deleted_at_index` (`deleted_at`),
  CONSTRAINT `lesson_key` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignments`
--

LOCK TABLES `assignments` WRITE;
/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_comments`
--

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blogs_category_id_foreign` (`category_id`),
  CONSTRAINT `blogs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,1,1,'50mm Lens Prime-Time Revival','50mm-lens-prime-time-revival','<p>If you been into photography for a while, chances are the first lens you owned was a 50mm prime. My first slr came with one - an f1.8 on the front of a Minolta SRT 101. It was the only lens I owned for years and I put it to work for everything - sports, people and landscapes. It\'s what got me started, back in high school days.<p>A lot of kit, brand flip-flops and thousands of images later I, like many amateurs and professionals, allowed my \"50\" to fall from the radar. The lens that had been the workhorse of photographers like Cartier Bresson was no longer en vogue. It\'s perspective similar to that of the human eye became seen as \"boring\" and \"uninspiring\" - too short for a conservative telephoto, too long to be a mild wide-angle.</p><p>Long story cut short, I came back. For the last few years among the digital bodies and zooms regularly carried by me about the world, again a prime 50mm is along for the ride. There\'s good reason why.</p><p><img alt=\"50\" src=\"http://thecompellingimage.wordpress.com/files/2009/06/502.jpg?w=300\" style=\"height:249px; width:375px\" title=\"50\">The lens is light in weight and compact. My Canon f1.4 isn\'t built like a tank, but it\'s been bumped aboard helicopters and rattled in the back of trucks, sprayed with dust in the south of Afghanistan and continues to work without a glitch. I can shoot portraits from a comfortable, yet intimate distance, get the bokeh (soft, out-of-focus area of a photo) I want and still be able to work candidly like the proverbial \"fly on the wall.\" Best thing though, I can shoot in the lowest of light - naturally, without flash - very cool, indeed!</p><p>What else? Well - plenty! Great optical performance is another plus for this \"normal\" focal length. Since the 1930s the lens has been the mainstay of 35mm photography. Its pedigree carries one of the best understood and most highly corrected optical designs in the history of optical technology. Even the cheapest 50mm lens will be superior to any of the current crop of moderate-aperture, consumer zooms. In fact, 50mm lenses are often the sharpest optics in a manufacturer\'s line.</p><p>Best of all though, using a \"standard\" 50mm prime is good training for seeing effectively with the camera. \"Zoom with your feet\" is the Modus operandi here - moving closer to eliminate the unwanted, backing away to include more context for the subject.</p><p>With a prime lens like a 50mm, a photographer becomes much more aware of the viewfinder as a compositional frame. In fact, after using such a lens exclusively for a while, pictures then made with a zoom will improve, since the former instills a much better understanding of just how focal length affects composition.</p><p>The 50mm lens is the one many a beginning photographer starts on the road to creativity&nbsp;with. &nbsp;And in this and many other regards, the best online photography courses for beginners&nbsp;starts rights here - at The Compelling Image.</p><p>Photojournalist and TCI instructor,&nbsp;<a href=\"https://www.thecompellingimage.com/administrator/blogs/instructors/52-david-bathgate\">David Bathgate,</a>&nbsp;teaches online&nbsp;photography classes&nbsp;at the&nbsp;<a href=\"https://www.thecompellingimage.com/administrator/blogs/\">The Compelling Image</a>&nbsp;- online school of photography and there\'s one focused specifically&nbsp;on the stellar qualities of the&nbsp;<a href=\"https://www.thecompellingimage.com/administrator/blogs/courses/47-one-camera-one-50mm-lens\">50mm lens</a>.</p></p>\n',NULL,0,'50mm Lens Prime-Time Revival','Photographing with just one camera and the 50mm lens','best online photography courses for beginners,best online classes for learning photography, 50mm lens, photography, digital photography, online photography classes, online photography school, standard lens, camera, David Bathgate','2021-01-04 03:03:14','2021-01-04 03:03:14',NULL);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bundle_courses`
--

DROP TABLE IF EXISTS `bundle_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bundle_courses` (
  `bundle_id` int(10) unsigned NOT NULL,
  `course_id` int(10) unsigned NOT NULL,
  KEY `bundle_courses_bundle_id_foreign` (`bundle_id`),
  KEY `bundle_courses_course_id_foreign` (`course_id`),
  CONSTRAINT `bundle_courses_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bundle_courses_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bundle_courses`
--

LOCK TABLES `bundle_courses` WRITE;
/*!40000 ALTER TABLE `bundle_courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `bundle_courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bundle_student`
--

DROP TABLE IF EXISTS `bundle_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bundle_student` (
  `bundle_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `rating` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `bundle_student_bundle_id_foreign` (`bundle_id`),
  KEY `bundle_student_user_id_foreign` (`user_id`),
  CONSTRAINT `bundle_student_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bundle_student_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bundle_student`
--

LOCK TABLES `bundle_student` WRITE;
/*!40000 ALTER TABLE `bundle_student` DISABLE KEYS */;
/*!40000 ALTER TABLE `bundle_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bundles`
--

DROP TABLE IF EXISTS `bundles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bundles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) DEFAULT NULL,
  `course_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `featured` int(11) DEFAULT '0',
  `trending` int(11) DEFAULT '0',
  `popular` int(11) DEFAULT '0',
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `published` tinyint(4) DEFAULT '0',
  `free` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bundles_user_id_foreign` (`user_id`),
  KEY `bundles_deleted_at_index` (`deleted_at`),
  CONSTRAINT `bundles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bundles`
--

LOCK TABLES `bundles` WRITE;
/*!40000 ALTER TABLE `bundles` DISABLE KEYS */;
/*!40000 ALTER TABLE `bundles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  UNIQUE KEY `cache_key_unique` (`key`)
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
-- Table structure for table `cart_storage`
--

DROP TABLE IF EXISTS `cart_storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_storage` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cart_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_storage_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_storage`
--

LOCK TABLES `cart_storage` WRITE;
/*!40000 ALTER TABLE `cart_storage` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_storage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 - disabled, 1 - enabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Photography','photography','fas fa-camera-retro',1,'2021-01-04 01:27:31','2021-01-04 01:27:31',NULL),(2,'Video-production','video-production','fas fa-video',1,'2021-01-04 01:27:50','2021-01-04 01:27:50',NULL),(3,'Multimedia','multimedia','far fa-file-video',1,'2021-01-04 01:28:23','2021-01-04 01:28:23',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `course_id` int(10) unsigned DEFAULT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-Generated 0-Not Generated',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certificates_user_id_foreign` (`user_id`),
  KEY `certificates_course_id_foreign` (`course_id`),
  CONSTRAINT `certificates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chapter_students`
--

DROP TABLE IF EXISTS `chapter_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapter_students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `course_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chapter_students_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `chapter_students_user_id_foreign` (`user_id`),
  KEY `chapter_students_course_id_foreign` (`course_id`),
  CONSTRAINT `chapter_students_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chapter_students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapter_students`
--

LOCK TABLES `chapter_students` WRITE;
/*!40000 ALTER TABLE `chapter_students` DISABLE KEYS */;
/*!40000 ALTER TABLE `chapter_students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thread_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_participants`
--

DROP TABLE IF EXISTS `chat_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_participants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thread_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `last_read` timestamp NULL DEFAULT NULL,
  `starred` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chat_participants_thread_id_user_id_unique` (`thread_id`,`user_id`),
  KEY `chat_participants_user_id_foreign` (`user_id`),
  CONSTRAINT `chat_participants_thread_id_foreign` FOREIGN KEY (`thread_id`) REFERENCES `chat_messages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chat_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_participants`
--

LOCK TABLES `chat_participants` WRITE;
/*!40000 ALTER TABLE `chat_participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_threads`
--

DROP TABLE IF EXISTS `chat_threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Unique slug for social media sharing. MD5 hashed string',
  `max_participants` int(11) DEFAULT NULL COMMENT 'Max number of participants allowed',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Profile picture for the conversation',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_threads`
--

LOCK TABLES `chat_threads` WRITE;
/*!40000 ALTER TABLE `chat_threads` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_threads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatter_categories`
--

DROP TABLE IF EXISTS `chatter_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatter_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatter_categories`
--

LOCK TABLES `chatter_categories` WRITE;
/*!40000 ALTER TABLE `chatter_categories` DISABLE KEYS */;
INSERT INTO `chatter_categories` VALUES (1,NULL,1,'Introductions','#3498DB','introductions',NULL,NULL),(2,NULL,2,'General','#2ECC71','general',NULL,NULL),(3,NULL,3,'Feedback','#9B59B6','feedback',NULL,NULL),(4,NULL,4,'Random','#E67E22','random',NULL,NULL),(5,1,1,'Rules','#227ab5','rules',NULL,NULL),(6,5,1,'Basics','#195a86','basics',NULL,NULL),(7,5,2,'Contribution','#195a86','contribution',NULL,NULL),(8,1,2,'About','#227ab5','about',NULL,NULL);
/*!40000 ALTER TABLE `chatter_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatter_discussion`
--

DROP TABLE IF EXISTS `chatter_discussion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatter_discussion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chatter_category_id` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `answered` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#232629',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_reply_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chatter_discussion_slug_unique` (`slug`),
  KEY `chatter_discussion_chatter_category_id_foreign` (`chatter_category_id`),
  KEY `chatter_discussion_user_id_foreign` (`user_id`),
  CONSTRAINT `chatter_discussion_chatter_category_id_foreign` FOREIGN KEY (`chatter_category_id`) REFERENCES `chatter_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chatter_discussion_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatter_discussion`
--

LOCK TABLES `chatter_discussion` WRITE;
/*!40000 ALTER TABLE `chatter_discussion` DISABLE KEYS */;
INSERT INTO `chatter_discussion` VALUES (3,1,'Hello Everyone, This is my Introduction',1,0,0,0,'2016-08-18 06:27:56','2016-08-18 06:27:56','hello-everyone-this-is-my-introduction','#239900',NULL,'2020-08-21 06:49:26'),(6,2,'Login Information for Chatter',1,0,0,0,'2016-08-18 06:39:36','2016-08-18 06:39:36','login-information-for-chatter','#1a1067',NULL,'2020-08-21 06:49:26'),(7,3,'Leaving Feedback',1,0,0,0,'2016-08-18 06:42:29','2016-08-18 06:42:29','leaving-feedback','#8e1869',NULL,'2020-08-21 06:49:26'),(8,4,'Just a random post',1,0,0,0,'2016-08-18 06:46:38','2016-08-18 06:46:38','just-a-random-post','',NULL,'2020-08-21 06:49:26'),(9,2,'Welcome to the Chatter Laravel Forum Package',1,0,0,0,'2016-08-18 06:59:37','2016-08-18 06:59:37','welcome-to-the-chatter-laravel-forum-package','',NULL,'2020-08-21 06:49:26');
/*!40000 ALTER TABLE `chatter_discussion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatter_post`
--

DROP TABLE IF EXISTS `chatter_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatter_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chatter_discussion_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `markdown` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chatter_post_chatter_discussion_id_foreign` (`chatter_discussion_id`),
  KEY `chatter_post_user_id_foreign` (`user_id`),
  CONSTRAINT `chatter_post_chatter_discussion_id_foreign` FOREIGN KEY (`chatter_discussion_id`) REFERENCES `chatter_discussion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chatter_post_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatter_post`
--

LOCK TABLES `chatter_post` WRITE;
/*!40000 ALTER TABLE `chatter_post` DISABLE KEYS */;
INSERT INTO `chatter_post` VALUES (1,3,1,'<p>My name is Tony and I\'m a developer at <a href=\"https://devdojo.com\" target=\"_blank\">https://devdojo.com</a> and I also work with an awesome company in PB called The Control Group: <a href=\"http://www.thecontrolgroup.com\" target=\"_blank\">http://www.thecontrolgroup.com</a></p>\n        <p>You can check me out on twitter at <a href=\"http://www.twitter.com/tnylea\" target=\"_blank\">http://www.twitter.com/tnylea</a></p>\n        <p>or you can subscribe to me on YouTube at <a href=\"http://www.youtube.com/devdojo\" target=\"_blank\">http://www.youtube.com/devdojo</a></p>','2016-08-18 06:27:56','2016-08-18 06:27:56',0,0,NULL),(5,6,1,'<p>Hey!</p>\n        <p>Thanks again for checking out chatter. If you want to login with the default user you can login with the following credentials:</p>\n        <p><strong>email address</strong>: tony@hello.com</p>\n        <p><strong>password</strong>: password</p>\n        <p>You\'ll probably want to delete this user, but if for some reason you want to keep it... Go ahead :)</p>','2016-08-18 06:39:36','2016-08-18 06:39:36',0,0,NULL),(6,7,1,'<p>If you would like to leave some feedback or have any issues be sure to visit the github page here: <a href=\"https://github.com/thedevdojo/chatter\" target=\"_blank\">https://github.com/thedevdojo/chatter</a>&nbsp;and I\'m sure I can help out.</p>\n        <p>Let\'s make this package the go to Laravel Forum package. Feel free to contribute and share your ideas :)</p>','2016-08-18 06:42:29','2016-08-18 06:42:29',0,0,NULL),(7,8,1,'<p>This is just a random post to show you some of the formatting that you can do in the WYSIWYG editor. You can make your text <strong>bold</strong>, <em>italic</em>, or <span style=\"text-decoration: underline;\">underlined</span>.</p>\n        <p style=\"text-align: center;\">Additionally, you can center align text.</p>\n        <p style=\"text-align: right;\">You can align the text to the right!</p>\n        <p>Or by default it will be aligned to the left.</p>\n        <ul>\n        <li>We can also</li>\n        <li>add a bulleted</li>\n        <li>list</li>\n        </ul>\n        <ol>\n        <li><span style=\"line-height: 1.6;\">or we can</span></li>\n        <li><span style=\"line-height: 1.6;\">add a numbered list</span></li>\n        </ol>\n        <p style=\"padding-left: 30px;\"><span style=\"line-height: 1.6;\">We can choose to indent our text</span></p>\n        <p><span style=\"line-height: 1.6;\">Post links: <a href=\"https://devdojo.com\" target=\"_blank\">https://devdojo.com</a></span></p>\n        <p><span style=\"line-height: 1.6;\">and add images:</span></p>\n        <p><span style=\"line-height: 1.6;\"><img src=\"https://media.giphy.com/media/o0vwzuFwCGAFO/giphy.gif\" alt=\"\" width=\"300\" height=\"300\" /></span></p>','2016-08-18 06:46:38','2016-08-18 06:46:38',0,0,NULL),(8,8,1,'<p>Haha :) Cats!</p>\n        <p><img src=\"https://media.giphy.com/media/5Vy3WpDbXXMze/giphy.gif\" alt=\"\" width=\"250\" height=\"141\" /></p>\n        <p><img src=\"https://media.giphy.com/media/XNdoIMwndQfqE/200.gif\" alt=\"\" width=\"200\" height=\"200\" /></p>','2016-08-18 06:55:42','2016-08-18 07:45:13',0,0,NULL),(9,9,1,'<p>Hey There!</p>\n        <p>My name is Tony and I\'m the creator of this package that you\'ve just installed. Thanks for checking out it out and if you have any questions or want to contribute be sure to checkout the repo here: <a href=\"https://github.com/thedevdojo/chatter\" target=\"_blank\">https://github.com/thedevdojo/chatter</a></p>\n        <p>Happy programming!</p>','2016-08-18 06:59:37','2016-08-18 06:59:37',0,0,NULL),(10,9,1,'<p>Hell yeah Bro Sauce!</p>\n        <p><img src=\"https://media.giphy.com/media/j5QcmXoFWl4Q0/giphy.gif\" alt=\"\" width=\"366\" height=\"229\" /></p>','2016-08-18 07:01:25','2016-08-18 07:01:25',0,0,NULL);
/*!40000 ALTER TABLE `chatter_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chatter_user_discussion`
--

DROP TABLE IF EXISTS `chatter_user_discussion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatter_user_discussion` (
  `user_id` int(10) unsigned NOT NULL,
  `discussion_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`discussion_id`),
  KEY `chatter_user_discussion_user_id_index` (`user_id`),
  KEY `chatter_user_discussion_discussion_id_index` (`discussion_id`),
  CONSTRAINT `chatter_user_discussion_discussion_id_foreign` FOREIGN KEY (`discussion_id`) REFERENCES `chatter_discussion` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chatter_user_discussion_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatter_user_discussion`
--

LOCK TABLES `chatter_user_discussion` WRITE;
/*!40000 ALTER TABLE `chatter_user_discussion` DISABLE KEYS */;
/*!40000 ALTER TABLE `chatter_user_discussion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `reviewable_id` int(11) NOT NULL,
  `reviewable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configs`
--

DROP TABLE IF EXISTS `configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configs`
--

LOCK TABLES `configs` WRITE;
/*!40000 ALTER TABLE `configs` DISABLE KEYS */;
INSERT INTO `configs` VALUES (1,'theme_layout','5','2020-08-21 01:04:01','2020-10-17 07:37:55'),(2,'font_color','default','2020-08-21 01:04:01','2020-08-21 01:04:01'),(3,'layout_type','wide-layout','2020-08-21 01:04:01','2020-12-03 02:05:30'),(4,'layout_1','{\"search_section\":{\"title\":\"Search Section\",\"status\":1},\"popular_courses\":{\"title\":\"Popular Courses\",\"status\":1},\"reasons\":{\"title\":\"Reasons why choose School of Permaculture\",\"status\":1},\"testimonial\":{\"title\":\"Testimonial\",\"status\":1},\"latest_news\":{\"title\":\"Latest News, Courses\",\"status\":1},\"sponsors\":{\"title\":\"Sponsors\",\"status\":1},\"featured_courses\":{\"title\":\"Featured Courses\",\"status\":1},\"teachers\":{\"title\":\"Teachers\",\"status\":1},\"faq\":{\"title\":\"Frequently Asked Questions\",\"status\":1},\"course_by_category\":{\"title\":\"Course By Category\",\"status\":1},\"contact_us\":{\"title\":\"Contact us \\/ Get in Touch\",\"status\":1}}','2020-08-21 01:04:01','2020-11-07 00:06:53'),(5,'layout_2','{\"sponsors\":{\"title\":\"Sponsors\",\"status\":1},\"popular_courses\":{\"title\":\"Popular Courses\",\"status\":1},\"search_section\":{\"title\":\"Search Section\",\"status\":1},\"latest_news\":{\"title\":\"Latest News, Courses\",\"status\":1},\"featured_courses\":{\"title\":\"Featured Courses\",\"status\":1},\"faq\":{\"title\":\"Frequently Asked Questions\",\"status\":1},\"course_by_category\":{\"title\":\"Course By Category\",\"status\":1},\"testimonial\":{\"title\":\"Testimonial\",\"status\":1},\"teachers\":{\"title\":\"Teachers\",\"status\":1},\"contact_us\":{\"title\":\"Contact us \\/ Get in Touch\",\"status\":1}}','2020-08-21 01:04:01','2020-08-21 01:04:01'),(6,'layout_3','{\"counters\":{\"title\":\"Counters\",\"status\":1},\"latest_news\":{\"title\":\"Latest News, Courses\",\"status\":1},\"popular_courses\":{\"title\":\"Popular Courses\",\"status\":1},\"reasons\":{\"title\":\"Reasons why choose School of Permaculture\",\"status\":1},\"featured_courses\":{\"title\":\"Featured Courses\",\"status\":1},\"teachers\":{\"title\":\"Teachers\",\"status\":1},\"faq\":{\"title\":\"Frequently Asked Questions\",\"status\":1},\"testimonial\":{\"title\":\"Testimonial\",\"status\":1},\"sponsors\":{\"title\":\"Sponsors\",\"status\":1},\"course_by_category\":{\"title\":\"Course By Category\",\"status\":1},\"contact_us\":{\"title\":\"Contact us \\/ Get in Touch\",\"status\":1}}','2020-08-21 01:04:01','2020-10-12 05:00:15'),(7,'layout_4','{\"counters\":{\"title\":\"Counters\",\"status\":1},\"popular_courses\":{\"title\":\"Popular Courses\",\"status\":1},\"reasons\":{\"title\":\"Reasons why choose School of Permaculture\",\"status\":1},\"featured_courses\":{\"title\":\"Featured Courses\",\"status\":1},\"course_by_category\":{\"title\":\"Course By Category\",\"status\":1},\"teachers\":{\"title\":\"Teachers\",\"status\":1},\"latest_news\":{\"title\":\"Latest News, Courses\",\"status\":1},\"search_section\":{\"title\":\"Search Section\",\"status\":1},\"faq\":{\"title\":\"Frequently Asked Questions\",\"status\":1},\"testimonial\":{\"title\":\"Testimonial\",\"status\":1},\"sponsors\":{\"title\":\"Sponsors\",\"status\":1},\"contact_form\":{\"title\":\"Contact Form\",\"status\":1},\"contact_us\":{\"title\":\"Contact us \\/ Get in Touch\",\"status\":1}}','2020-08-21 01:04:01','2020-10-12 05:00:15'),(8,'counter','1','2020-08-21 01:04:01','2020-08-21 01:04:01'),(9,'total_students','1M+','2020-08-21 01:04:01','2020-08-21 01:04:01'),(10,'total_courses','1K+','2020-08-21 01:04:01','2020-08-21 01:04:01'),(11,'total_teachers','200+','2020-08-21 01:04:01','2020-08-21 01:04:01'),(12,'logo_b_image','1609763449-logopng','2020-08-21 01:04:01','2021-01-04 04:30:49'),(13,'logo_w_image','1609763449-logopng','2020-08-21 01:04:01','2021-01-04 04:30:49'),(14,'logo_white_image','1609763449-logopng','2020-08-21 01:04:01','2021-01-04 04:30:49'),(15,'logo_popup','1609763449-logopng','2020-08-21 01:04:01','2021-01-04 04:30:49'),(16,'favicon_image','1609763449-logopng','2020-08-21 01:04:01','2021-01-04 04:30:49'),(17,'contact_data','[{\"name\":\"short_text\",\"value\":\"Welcome to our Website. We are glad to have you around.\",\"status\":1},{\"name\":\"primary_address\",\"value\":\"Surburban Site - 3928 Dickens Dr. Plano, TX 75023 Farm Site - Ben Franklin, TX\",\"status\":1},{\"name\":\"secondary_address\",\"value\":\"Surburban Site - 3928 Dickens Dr. Plano, TX 75023 Farm Site - Ben Franklin, TX\",\"status\":1},{\"name\":\"primary_phone\",\"value\":\"(1) 214 856 8477\",\"status\":1},{\"name\":\"secondary_phone\",\"value\":\"(1) 214 856 8477\",\"status\":1},{\"name\":\"primary_email\",\"value\":\"learn@schoolofpermaculture.com\",\"status\":1},{\"name\":\"secondary_email\",\"value\":\"learn@schoolofpermaculture.com\",\"status\":1},{\"name\":\"location_on_map\",\"value\":\"<iframe src=\'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3344.275801757153!2d-96.70378628547405!3d33.04920507736546!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c19b3dc02ccf3%3A0xdce1e7c649ea28d1!2s3928%20Dickens%20Dr%2C%20Plano%2C%20TX%2075023%2C%20USA!5e0!3m2!1sen!2sin!4v1603719892272!5m2!1sen!2sin\' width=\'100%\' height=\'100%\' frameborder=\'0\' style=\'border:0;\' allowfullscreen=\'\' aria-hidden=\'false\' tabindex=\'0\'></iframe>\",\"status\":1}]','2020-08-21 01:04:01','2020-12-10 04:56:24'),(18,'footer_data','{\"short_description\":{\"text\":\"\",\"status\":1},\"section1\":{\"type\":\"1\",\"status\":1},\"section2\":{\"type\":\"1\",\"status\":1},\"section3\":{\"type\":\"1\",\"status\":1},\"social_links\":{\"status\":\"1\",\"links\":[{\"icon\":\"fab fa-facebook-f\",\"link\":\"#\"},{\"icon\":\"fab fa-instagram\",\"link\":\"#\"},{\"icon\":\"fab fa-twitter\",\"link\":\"#\"},{\"icon\":\"fab fa-pinterest\",\"link\":\"#\"},{\"icon\":\"fab fa-youtube\",\"link\":\"#\"}]},\"newsletter_form\":{\"status\":1},\"bottom_footer\":{\"status\":1},\"copyright_text\":{\"text\":\"\",\"status\":\"1\"},\"bottom_footer_links\":{\"status\":\"1\",\"links\":[]}}','2020-08-21 01:04:01','2020-12-03 02:03:32'),(19,'app.locale','en','2020-08-21 01:04:01','2020-08-21 01:04:01'),(20,'app.display_type','ltr','2020-08-21 01:04:01','2020-08-21 01:04:01'),(21,'app.currency','USD','2020-08-21 01:04:01','2020-08-21 01:04:01'),(22,'lesson_timer','0','2020-08-21 01:04:01','2020-08-21 01:04:01'),(23,'show_offers','0','2020-08-21 01:04:01','2020-12-03 02:02:50'),(24,'access.captcha.registration','0','2020-08-21 01:04:01','2020-08-21 01:04:01'),(25,'sitemap.chunk','500','2020-08-21 01:04:01','2020-08-21 01:04:01'),(26,'one_signal','0','2020-08-21 01:04:01','2020-08-21 01:04:01'),(27,'nav_menu','1','2020-08-21 01:04:01','2020-08-21 01:04:01'),(28,'commission_rate','0','2020-08-21 01:04:02','2020-08-21 01:04:02'),(29,'layout_5','{\"sponsors\":{\"title\":\"Sponsors\",\"status\":1},\"popular_courses\":{\"title\":\"Popular Courses\",\"status\":1},\"search_section\":{\"title\":\"Search Section\",\"status\":1},\"latest_news\":{\"title\":\"Latest News, Courses\",\"status\":1},\"featured_courses\":{\"title\":\"Featured Courses\",\"status\":1},\"faq\":{\"title\":\"Frequently Asked Questions\",\"status\":1},\"course_by_category\":{\"title\":\"Course By Category\",\"status\":1},\"testimonial\":{\"title\":\"Testimonial\",\"status\":1},\"teachers\":{\"title\":\"Teachers\",\"status\":1},\"contact_us\":{\"title\":\"Contact us / Get in Touch\",\"status\":1}}','2020-10-12 05:00:15','2020-12-03 02:05:30'),(30,'app.name','The Compelling Image','2020-10-12 05:00:15','2021-01-04 04:31:46'),(31,'services.facebook.active','1','2020-10-12 05:23:25','2021-01-01 21:53:28'),(32,'services.facebook.client_id','225490219088718','2020-10-12 05:23:25','2021-01-01 22:11:21'),(33,'services.facebook.client_secret','bc8952cdfb401a7823ea391c4390083c','2020-10-12 05:23:25','2021-01-01 22:11:21'),(34,'services.google.active','0','2020-10-12 05:23:25','2021-01-01 21:53:28'),(35,'services.google.client_id','803901822917-jhkpp3vsihkq178u08v2npd6f49vp480.apps.googleusercontent.com','2020-10-12 05:23:25','2020-10-12 05:50:49'),(36,'services.google.client_secret','qF-GjkZpT9syMAXwRGE02scY','2020-10-12 05:23:25','2020-10-12 05:50:49'),(37,'services.twitter.client_id',NULL,'2020-10-12 05:23:25','2020-10-12 05:23:25'),(38,'services.twitter.client_secret',NULL,'2020-10-12 05:23:25','2020-10-12 05:23:25'),(39,'services.linkedin.client_id',NULL,'2020-10-12 05:23:25','2020-10-12 05:23:25'),(40,'services.linkedin.client_secret',NULL,'2020-10-12 05:23:25','2020-10-12 05:23:25'),(41,'services.twitter.active','0','2020-10-12 05:23:25','2020-10-12 05:23:25'),(42,'services.linkedin.active','0','2020-10-12 05:23:25','2020-10-12 05:23:25'),(43,'services.github.active','0','2020-10-12 05:23:25','2020-10-12 05:23:25'),(44,'services.bitbucket.active','0','2020-10-12 05:23:25','2020-10-12 05:23:25'),(45,'app.url','http://127.0.0.1:8000','2020-10-12 05:51:57','2020-10-12 05:51:57'),(46,'google_analytics_id',NULL,'2020-10-12 05:51:57','2020-10-12 05:51:57'),(47,'no-captcha.sitekey','no-captcha-sitekey','2020-10-12 05:51:57','2020-10-12 05:51:57'),(48,'no-captcha.secret','no-captcha-secret','2020-10-12 05:51:57','2020-10-12 05:51:57'),(49,'onesignal_data',NULL,'2020-10-12 05:51:57','2020-10-12 05:51:57'),(50,'access.users.registration_mail','0','2020-10-12 05:51:57','2020-12-03 02:02:50'),(51,'custom_css',NULL,'2020-10-12 05:51:57','2020-10-12 05:51:57'),(52,'custom_js',NULL,'2020-10-12 05:51:57','2020-10-12 05:51:57'),(53,'mail.from.name','TCI LMS','2020-10-12 05:51:57','2021-01-04 04:32:16'),(54,'mail.from.address','bstech5796@gmail.com','2020-10-12 05:51:57','2020-10-12 07:14:26'),(55,'mail.driver','smtp','2020-10-12 05:51:57','2020-10-12 05:51:57'),(56,'mail.host','smtp.gmail.com','2020-10-12 05:51:57','2020-10-12 07:14:26'),(57,'mail.port','587','2020-10-12 05:51:57','2020-10-12 07:14:26'),(58,'mail.username','bstech5796@gmail.com','2020-10-12 05:51:57','2020-10-12 07:14:26'),(59,'mail.password','love4eveR0210','2020-10-12 05:51:57','2020-10-12 07:14:26'),(60,'mail.encryption','tls','2020-10-12 05:51:57','2020-10-12 05:51:57'),(61,'services.stripe.key',NULL,'2020-10-12 05:51:57','2020-10-12 05:51:57'),(62,'services.stripe.secret',NULL,'2020-10-12 05:51:57','2020-10-12 05:51:57'),(63,'paypal.settings.mode','sandbox','2020-10-12 05:51:57','2020-10-12 05:51:57'),(64,'paypal.client_id','AfBBqdape_ebCCIZkSjNtEHAbk6Wfs699fzPpeBvOIYOMUFbSgxZfGmE3S0bBKWR3Xeh_1-v8lEQnuwn','2020-10-12 05:51:57','2020-10-16 05:29:27'),(65,'paypal.secret','ECIvpDym13W5faziYNfRkkBFH6ixjGf_7tfotLQ1dUobQfuus0juHojUhP0EPKgD6kYgIA5r64qoVx_i','2020-10-12 05:51:57','2020-10-16 05:29:27'),(66,'payment_offline_instruction',NULL,'2020-10-12 05:51:57','2020-10-12 05:51:57'),(67,'registration_fields','[]','2020-10-12 05:51:57','2020-10-12 05:51:57'),(68,'myTable_length','10','2020-10-12 05:51:57','2020-10-12 05:51:57'),(69,'access_registration','0','2020-10-12 05:51:57','2021-01-04 04:30:49'),(70,'mailchimp_double_opt_in','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(71,'access_users_change_email','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(72,'access_users_confirm_email','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(73,'access_captcha_registration','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(74,'access_users_requires_approval','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(75,'services.stripe.active','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(76,'paypal.active','0','2020-10-12 05:51:57','2020-12-03 02:02:50'),(77,'payment_offline_active','0','2020-10-12 05:51:57','2020-10-17 06:54:01'),(78,'backup.status','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(79,'retest','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(80,'onesignal_status','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(81,'access.users.order_mail','0','2020-10-12 05:51:57','2020-10-12 05:51:57'),(82,'zoom.api_key','du2DVhBkT8CIjLyF0-5Blw','2020-10-17 05:25:10','2020-10-17 05:25:10'),(83,'zoom.api_secret','HPdeBchZSls7tzlAvpWXcQdSZn4ejcd4bSiE','2020-10-17 05:25:10','2020-10-17 05:25:10'),(84,'zoom.approval_type','0','2020-10-17 05:25:10','2020-10-17 05:25:10'),(85,'zoom.audio','both','2020-10-17 05:25:10','2020-10-17 05:25:10'),(86,'zoom.auto_recording','none','2020-10-17 05:25:10','2020-10-17 05:25:10'),(87,'zoom.timezone','UTC','2020-10-17 05:25:10','2020-10-17 05:25:10'),(88,'zoom.host_video','1','2020-10-17 05:25:10','2020-10-17 05:25:10'),(89,'zoom.mute_upon_entry','1','2020-10-17 05:25:10','2020-10-17 05:25:10'),(90,'zoom.waiting_room','1','2020-10-17 05:25:10','2020-10-17 05:25:10'),(91,'zoom.join_before_host','0','2020-10-17 05:25:10','2020-10-17 05:25:10'),(92,'zoom.participant_video','0','2020-10-17 05:25:10','2020-10-17 05:25:10'),(93,'section1','1','2020-12-03 02:02:50','2020-12-03 02:02:50'),(94,'section2','1','2020-12-03 02:02:50','2020-12-03 02:02:50'),(95,'section3','1','2020-12-03 02:02:50','2020-12-03 02:02:50'),(96,'icon','fab fa-youtube','2020-12-03 02:02:50','2020-12-03 02:03:32');
/*!40000 ALTER TABLE `configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` bigint(20) DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Discount, 2 - Flat Amount',
  `amount` double(8,2) NOT NULL COMMENT 'Percentage or Amount',
  `min_price` double(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Minimum price to allow coupons',
  `expires_at` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `per_user_limit` int(11) NOT NULL DEFAULT '1' COMMENT '0 - Unlimited',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Disabled, 1 - Enabled, 2 - Expired',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_student`
--

DROP TABLE IF EXISTS `course_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_student` (
  `course_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `rating` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `course_student_course_id_foreign` (`course_id`),
  KEY `course_student_user_id_foreign` (`user_id`),
  CONSTRAINT `course_student_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_student_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_student`
--

LOCK TABLES `course_student` WRITE;
/*!40000 ALTER TABLE `course_student` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_timeline`
--

DROP TABLE IF EXISTS `course_timeline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_timeline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_timeline_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_timeline`
--

LOCK TABLES `course_timeline` WRITE;
/*!40000 ALTER TABLE `course_timeline` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_timeline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_user`
--

DROP TABLE IF EXISTS `course_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_user` (
  `course_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  KEY `fk_p_54418_54417_user_cou_596eece522b73` (`course_id`),
  KEY `fk_p_54417_54418_course_u_596eece522bee` (`user_id`),
  CONSTRAINT `fk_p_54417_54418_course_u_596eece522bee` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_p_54418_54417_user_cou_596eece522b73` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_user`
--

LOCK TABLES `course_user` WRITE;
/*!40000 ALTER TABLE `course_user` DISABLE KEYS */;
INSERT INTO `course_user` VALUES (1,2),(2,2),(3,2),(4,2),(5,2);
/*!40000 ALTER TABLE `course_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) DEFAULT NULL,
  `price_skype` decimal(15,2) DEFAULT NULL,
  `course_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `duration` int(11) DEFAULT '0',
  `skill_level` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `beginner` int(11) DEFAULT '0',
  `intermediate` int(11) DEFAULT '0',
  `advance` int(11) DEFAULT '0',
  `featured` int(11) DEFAULT '0',
  `trending` int(11) DEFAULT '0',
  `popular` int(11) DEFAULT '0',
  `portfolio_review` int(11) DEFAULT '0',
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `published` tinyint(4) DEFAULT '0',
  `free` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `courses_deleted_at_index` (`deleted_at`),
  KEY `courses_category_id_foreign` (`category_id`),
  CONSTRAINT `courses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,1,'50mm is All You Need','50mm-is-all-you-need','<p>A 50mm lens once came &quot;standard&quot; with every 35mm SLR camera purchase.&nbsp; Then this inconspicuous, low-light performer fell from &quot;vogue,&quot; replaced by medium-telephoto, medium-aperture, variable zoom &quot;kit&quot; lenses.&nbsp; Lost was the flexibility and control this simple and effective optical design had to offer.&nbsp; These four online and interactive photography lessons, with practical hands-on assignments, will have you taking a second and enthusiastic look at what this compact powerhouse of a lens has to offer.&nbsp;&nbsp;</p>\r\n\r\n<p>The 50mm focal length has been the keystone of 35mm photography since the 1930s. Renowned master Henri Cartier-Bresson reportedly used&nbsp;<em>just</em>&nbsp;the &quot;50&quot; for everything from landscapes to portraiture. Fact is - even the cheapest of 50mm lenses for the 35mm camera proves optically superior to any of the current crop of consumer zooms. And from a creative perspective, this is the lens you should be pulling a lot more frequently from your camera bag - and this exciting online photography course taught by award-winning photographer,&nbsp;<a href=\"https://www.thecompellingimage.com/instructors/52-david-bathgate\">David Bathgate</a>, will show you why and how.</p>\r\n\r\n<p><strong>Upon completion of this course, you will be able to:</strong></p>\r\n\r\n<ul>\r\n	<li>Fully know the characteristics and capabilities of the versatile 50mm &quot;standard&quot; lens</li>\r\n	<li>Choose aperture and perspective to simulate mild wide-angle and tele-photo effects</li>\r\n	<li>Work comfortably and effectively at wide apertures in low light situations</li>\r\n	<li>Have confidence in carrying just one lens to &quot;do it all&quot;</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>What you will need:</strong></p>\r\n\r\n<ul>\r\n	<li>50mm prime lens or&nbsp;mirrorless camera with a fixed 50mm lens or equivalent</li>\r\n	<li>Computer with photo-processing software (e.g. Adobe Lightroom, Photoshop, etc.)</li>\r\n	<li>Willingness to go &quot;simple&quot; and &quot;light&quot;</li>\r\n	<li>Self-motivation to get the most from this instructor-interactive course</li>\r\n</ul>',149.00,169.00,'1609755513-course-img-2.jpg',NULL,60,NULL,0,1,1,0,0,0,0,'50mm Prime Lens Online and Interactive Photography Course','Learn creative use of your 50mm prime lens. It\'s no more just a \"standard lens\"!','photography, 50mm prime lens, online-interactive course | The Compelling Image',1,0,'2021-01-04 02:18:33','2021-01-04 02:18:33',NULL),(2,1,'People Photography - with Confidence!','people-photography-with-confidence','<p>Photographing people with a long lens from across the street is one thing; capturing them at closer distances is a whole new level of photographic expression filled with impact, emotion, and a whole lot more. Fact is, many people behind the camera fear close encounters with strangers.&nbsp; Shyness and fear of rejection are a part of it. Not knowing just how to handle the situation successfully once you&#39;re in it, is another. This online photography course, taught by professional photographer&nbsp;<a href=\"https://www.thecompellingimage.com/instructors/52-david-bathgate\">David Bathgate</a>, aims to remove those obstacles - bringing close-in photography of people - total strangers included - well within your reach. The result is nothing short of creative, confident and powerful!</p>\r\n\r\n<p><strong>Upon completion of this course, you will be able to:</strong></p>\r\n\r\n<ul>\r\n	<li>Work confidently and decisively with a 50mm to medium telephoto lens</li>\r\n	<li>Establish rapport when approaching strangers for a photograph</li>\r\n	<li>Make stunning impromptu and environmental portraits in all types of light</li>\r\n	<li>See people picture possibilities amid the crowd</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>What you will need:</strong></p>\r\n\r\n<ul>\r\n	<li>Digital SLR or compact mirrorless camera (film is also fine, but you&#39;ll need to scan images for upload to the TCI website)</li>\r\n	<li>A prime lens or zoom in the 50mm to 105mm range</li>\r\n	<li>Computer with photo-processing software (e.g. Lightroom or Photoshop)</li>\r\n	<li>Self-motivation to get the most from this instructor-interactive course</li>\r\n</ul>',149.00,169.00,'1609755774-course-3.jpg',NULL,60,NULL,0,1,1,0,0,0,0,'Telephoto Online Photography Course - Photos at a Distance','An innovative online photography school with interactive e-courses. World-renowned instructors & personalized feedback will transform your photography. Convenient & affordable.','photography,people,candid,portraits,Online Photography Course, confidence,online,interactive,course,David Bathgate',1,0,'2021-01-04 02:22:54','2021-01-04 02:22:54',NULL),(3,1,'Telling the Story in Pictures','telling-the-story-in-pictures','<p>The main factor that distinguishes documentary photography from photojournalism is - time. Photojournalists work for the moment - capturing events in the moment, and portraying them factually to encapulate what has happened in one or several powerful images. Where news moves quickly in these digital times, accuracy and speed are the winners in the race to publication.</p>\r\n\r\n<p>Documentary photography takes a different turn. While facts are still of paramount importance, time takes a backseat. Documentary stories can range over day, weeks, even sometimnes years. The goal here is to dive beneath the proverbial &quot;tip of the iceberg,&quot; to tell the story in deeper, more comprehensive visual detail, and often with artistic vision.</p>\r\n\r\n<p>In this six-lesson, six-assignment online and interactive course we&#39;ll explore and practice both genres. From&nbsp;capturing in a&nbsp;photo the&nbsp;joy of your child at play, the passion of a public speaker the podium, to an exploration of a subject or situation of personal interest, you&#39;ll work with professional professional documentary photographer and photojournalist, David Bathgate, in an interactive way, throughout the course.</p>\r\n\r\n<p>Whether you travel the world with your camera, or simply explore your own hometown, neighborhood, or family life at home, photojournalistic moments and documentary stories are everywhere. In this insightful, inspiring, and interactive online course we will cover and practice it all.</p>\r\n\r\n<p><strong>Upon completion of this course, you will be able to:</strong></p>\r\n\r\n<ul>\r\n	<li>Capture and communicate the the essence of an activity or event in a single photojournalistic image.</li>\r\n	<li>Find story subjects that are interesting, and (optionally) marketable to print and online outlets</li>\r\n	<li>Establish rapport and feel at ease photographing outside your comfort zone</li>\r\n	<li>Shoot variety in the types of images needed to construct an engaging story narrative</li>\r\n	<li>Add an artistic touch to the documentary images you capture</li>\r\n	<li>Edit your documentary work into a tight, flowing and powerfully interesting visual presentation</li>\r\n	<li>Accurately caption and keyword your work for search effectiveness at home and&nbsp;beyond</li>\r\n	<li>(Optionally) display, or even market your work, to a broader audience</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>What you will need:</strong></p>\r\n\r\n<ul>\r\n	<li>Digital SLR or compact mirrorless camera (film is also fine, but you&#39;ll&nbsp;be required to scan images for upload to the TCI website)</li>\r\n	<li>Preferably a selection of lenses ranging from wide-angle to telephoto</li>\r\n	<li>Computer with photo-processing software (e.g. Adobe Lightroom or Photoshop)</li>\r\n	<li>A light but study tripod would be beneficial</li>\r\n	<li>An unyielding curiosity about the world around you and willingness to venture into</li>\r\n	<li>Self-motivation and discipline to get the most from this instructor-interactive, e-learning course</li>\r\n</ul>',159.00,179.00,'1609756171-course-3.jpg',NULL,90,NULL,0,1,1,0,0,0,0,'Interactive Photojournalism Online Photography Course','An innovative online photography school with interactive e-courses. World-renowned instructors & personalized feedback will transform your photography. Convenient & affordable.','photojournalism,documentary photography,learn photography online and interactively,Interactive Photojournalism Online Photography Course, online photography courses,online photography classes,online photography school,David Bathgate',1,0,'2021-01-04 02:29:31','2021-01-04 02:31:18',NULL),(4,1,'Understanding Composition in Photography','understanding-composition-in-photography','<p>A photograph is powerful for not just&nbsp;<em>WHAT</em>&nbsp;subject is depicted, but&nbsp;<em>HOW</em>&nbsp;that subject is presented to the viewer.&nbsp; Not at all new, this idea of creative composition is rooted in centuries of artistic thinking.&nbsp; For many just starting out in photography, the topic of composition can be a confusing one. But not anymore . Let award-winning professional photographer,&nbsp;<a href=\"https://www.davidbathgate.com/\">David Bathgate</a>,&nbsp;show you how.&nbsp;&nbsp;</p>\r\n\r\n<p>For moving beyond &ldquo;<em>snap-shots</em>&rdquo; to creating &ldquo;<em>Photographs</em>,&rdquo; this 4-lesson interactive online photography course will give you hands-on experience with just how to compose photographs that&nbsp;<em>speak out,</em>&nbsp;and do so in your &quot;<em>voice</em>.&quot;&nbsp; We&rsquo;ll cover the &ldquo;rules&rdquo; of photographic composition, when and why the rules should be ignored and how this all can be creatively applied to your own unique vision with the camera.&nbsp; This is a foundation photography course that you just can&rsquo;t afford to pass up.<br />\r\n<br />\r\n<strong>Upon completion of these online photography classes, you will be able to:</strong></p>\r\n\r\n<ul>\r\n	<li>Identify meaningful compositional elements</li>\r\n	<li>Incorporate the rules of composition into your own photography</li>\r\n	<li>Recognize how you as a photographer can evoke mood and emotion</li>\r\n	<li>Seek out avenues for photographic inspiration</li>\r\n	<li>Shoot with intention</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>What you will need:</strong></p>\r\n\r\n<ul>\r\n	<li>Camera of any kind (mobile phone cameras count!). Scanning will be necessary if using film.</li>\r\n	<li>Computer (or mobile phone) with post-processing software, such as Photoshop or Lightroom (mobile alternatives would be Snapseed, VSCO, etc.</li>\r\n	<li>Self-motivation to get the most from this instructor-interactive course</li>\r\n</ul>',149.00,169.00,'1609756252-course-3.jpg',NULL,60,NULL,1,1,0,0,0,0,0,'Learn Photo Composition - Online Photography Masterclass','An innovative online photography school with interactive e-courses. World-renowned instructors & personalized feedback will transform your photography. Convenient & affordable.','composition for photographers,Learn Photo Composition, photographic composition,composition in photography,learn photography online,online photography courses,online photography classes,online photography school,Pei Ketron',1,0,'2021-01-04 02:30:52','2021-01-04 02:32:26',NULL),(5,1,'Focus on Wide Angle Photography','focus-on-wide-angle-photography','<p>Wide angle lenses are for getting close, not for &quot;fitting it all in.&quot;&nbsp; They&#39;re for putting you and the viewer right in the middle of the situation - face-to-face with street demonstrators or the excited faces of children at a birthday celebration.&nbsp; Used correctly, wide-angle lenses grab the viewer and put him or her right in the heart of the situation - right there where you were when the picture was made.&nbsp; In this exciting 4-lesson online photography course instructed by award-winning photographer&nbsp;<a href=\"https://www.thecompellingimage.com/instructors/52-david-bathgate\">David Bathgate</a>, you&#39;ll come to feel comfortable getting right in there with the subject and using wide angle lenses to powerful and dramatic effect.&nbsp;</p>\r\n\r\n<p>A lot of photographers believe wide angle lenses are only for the &quot;Big Scene,&quot; or for jockeying in cramped and crowded quarters. Truth is - there&#39;s expansively more to these powerful focal lengths than that.&nbsp; Wide angles are not just a means to a practical end, they&#39;re tools for exploration and dramatic expression that should have a priorty place right at the top of your camera bag.&nbsp; These online photography classes will put you to reorganizing that kit and moving your wide angle lens to where it belongs - on top of the picture!<br />\r\n<br />\r\n<strong>Upon completion of this course, you will be able to:</strong></p>\r\n\r\n<ul>\r\n	<li>Select the best wide-angle focal length to effectively express how you see and feel about a particular subject or situation</li>\r\n	<li>Establish strong foregrounds in your photos - &quot;hallmarks&quot; of powerful wide-angle photographs</li>\r\n	<li>Create &quot;layers&quot; of interest in your wide-angle photographs</li>\r\n	<li>Skillfully handle an expansive perspective and be in control of the frame - front to back</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>What you will need:</strong></p>\r\n\r\n<ul>\r\n	<li>Digital SLR or compact mirrorless camera (film is also fine, but you&#39;ll need to scan images for upload to the TCI website)</li>\r\n	<li>Prime or zoom lens(es) in the 16-24mm range</li>\r\n	<li>Computer with photo-processing software (e.g. Photoshop or Adobe Lightroom)</li>\r\n</ul>',149.00,169.00,'1609757633-course-1.jpg',NULL,60,NULL,0,1,1,0,0,0,0,'Online Wide Angle Lens Photography Course - Take Focused Photos','An innovative online photography school with interactive e-courses. World-renowned instructors & personalized feedback will transform your photography. Convenient & affordable.','Wide Angle Lens Photography Course, photography,online,interactive,school,courses,classes,wide angle lenses,wide angle perspective,David Bathgate',1,0,'2021-01-04 02:53:53','2021-01-04 02:53:53',NULL);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `earnings`
--

DROP TABLE IF EXISTS `earnings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `earnings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned DEFAULT NULL,
  `course_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `course_price` decimal(8,2) NOT NULL,
  `amount` decimal(5,2) NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `earnings_course_id_foreign` (`course_id`),
  KEY `earnings_user_id_foreign` (`user_id`),
  CONSTRAINT `earnings_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `earnings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `earnings`
--

LOCK TABLES `earnings` WRITE;
/*!40000 ALTER TABLE `earnings` DISABLE KEYS */;
/*!40000 ALTER TABLE `earnings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
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
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 - disbaled, 1 - enabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faqs_category_id_foreign` (`category_id`),
  CONSTRAINT `faqs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `discount_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_count` int(11) DEFAULT '0',
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `published` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `lesson_slot_bookings`
--

DROP TABLE IF EXISTS `lesson_slot_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_slot_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `live_lesson_slot_id` int(10) unsigned DEFAULT NULL,
  `lesson_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_slot_bookings_live_lesson_slot_id_foreign` (`live_lesson_slot_id`),
  KEY `lesson_slot_bookings_lesson_id_foreign` (`lesson_id`),
  KEY `lesson_slot_bookings_user_id_foreign` (`user_id`),
  CONSTRAINT `lesson_slot_bookings_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_slot_bookings_live_lesson_slot_id_foreign` FOREIGN KEY (`live_lesson_slot_id`) REFERENCES `live_lesson_slots` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_slot_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_slot_bookings`
--

LOCK TABLES `lesson_slot_bookings` WRITE;
/*!40000 ALTER TABLE `lesson_slot_bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson_slot_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lessons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `full_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `position` int(10) unsigned DEFAULT NULL,
  `free_lesson` tinyint(4) DEFAULT '1',
  `published` tinyint(4) DEFAULT '0',
  `live_lesson` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `54419_596eedbb6686e` (`course_id`),
  KEY `lessons_deleted_at_index` (`deleted_at`),
  CONSTRAINT `54419_596eedbb6686e` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `live_lesson_slots`
--

DROP TABLE IF EXISTS `live_lesson_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `live_lesson_slots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int(10) unsigned DEFAULT NULL,
  `meeting_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'agenda',
  `start_at` datetime NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'minutes',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'meeting password',
  `student_limit` int(11) DEFAULT NULL,
  `start_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `join_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `live_lesson_slots_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `live_lesson_slots_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `live_lesson_slots`
--

LOCK TABLES `live_lesson_slots` WRITE;
/*!40000 ALTER TABLE `live_lesson_slots` DISABLE KEYS */;
/*!40000 ALTER TABLE `live_lesson_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locales`
--

DROP TABLE IF EXISTS `locales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ltr' COMMENT 'ltr - Left to right, rtl - Right to Left',
  `is_default` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locales`
--

LOCK TABLES `locales` WRITE;
/*!40000 ALTER TABLE `locales` DISABLE KEYS */;
INSERT INTO `locales` VALUES (1,'English','en','ltr',1,'2020-08-21 01:03:58','2020-08-21 01:03:58'),(2,'Spanish','es','ltr',0,'2020-08-21 01:03:58','2021-01-04 04:32:16'),(3,'French','fr','ltr',0,'2020-08-21 01:03:58','2021-01-04 04:32:16'),(4,'Arabic','ar','rtl',0,'2020-08-21 01:03:58','2021-01-04 04:32:16');
/*!40000 ALTER TABLE `locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ltm_translations`
--

DROP TABLE IF EXISTS `ltm_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ltm_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `locale` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `group` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9282 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ltm_translations`
--

LOCK TABLES `ltm_translations` WRITE;
/*!40000 ALTER TABLE `ltm_translations` DISABLE KEYS */;
INSERT INTO `ltm_translations` VALUES (7940,0,'fr','menus','language-picker.langs.teo_UG','Teso (Ouganda)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7941,0,'fr','menus','language-picker.langs.teo','Teso','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7942,0,'fr','menus','language-picker.langs.th_TH','Thaïlandais (Thaïlande)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7943,0,'fr','menus','language-picker.langs.bo_CN','Tibétain (Chine)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7944,0,'fr','menus','language-picker.langs.bo_IN','Tibétain (Inde)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7945,0,'fr','menus','language-picker.langs.bo','Tibétain','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7946,0,'fr','menus','language-picker.langs.ti_ER','Tigrinya (Érythrée)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7947,0,'fr','menus','language-picker.langs.ti_ET','Tigrinya (Ethiopie)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7948,0,'fr','menus','language-picker.langs.ti','Tigrinya','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7949,0,'fr','menus','language-picker.langs.to_TO','Tonga (Tonga)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7950,0,'fr','menus','language-picker.langs.to','Tonga','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7951,0,'fr','menus','language-picker.langs.tr_TR','Turc (Turquie)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7952,0,'fr','menus','language-picker.langs.uk_UA','Ukrainien (Ukraine)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7953,0,'fr','menus','language-picker.langs.uk','ukrainien','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7954,0,'fr','menus','language-picker.langs.ur_IN','Urdu (Inde)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7955,0,'fr','menus','language-picker.langs.ur_PK','Urdu (Pakistan)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7956,0,'fr','menus','language-picker.langs.ur','Ourdou','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7957,0,'fr','menus','language-picker.langs.uz_Arab','Ouzbek (arabe)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7958,0,'fr','menus','language-picker.langs.uz_Arab_AF','Ouzbek (arabe, Afghanistan)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7959,0,'fr','menus','language-picker.langs.uz_Cyrl','Ouzbek (cyrillique)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7960,0,'fr','menus','language-picker.langs.uz_Cyrl_UZ','Ouzbek (cyrillique, Ouzbékistan)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7961,0,'fr','menus','language-picker.langs.uz_Latn','Ouzbek (latin)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7962,0,'fr','menus','language-picker.langs.uz_Latn_UZ','Ouzbek (latin, Ouzbékistan)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7963,0,'fr','menus','language-picker.langs.uz','Ouzbek','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7964,0,'fr','menus','language-picker.langs.vi_VN','Vietnamien (Vietnam)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7965,0,'fr','menus','language-picker.langs.vi','vietnamien','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7966,0,'fr','menus','language-picker.langs.vun_TZ','Vunjo (Tanzanie)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7967,0,'fr','menus','language-picker.langs.vun','Vunjo','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7968,0,'fr','menus','language-picker.langs.cy_GB','Gallois (Royaume-Uni)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7969,0,'fr','menus','language-picker.langs.cy','gallois','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7970,0,'fr','menus','language-picker.langs.yo_NG','Yoruba (Nigéria)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7971,0,'fr','menus','language-picker.langs.yo','Yoruba','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7972,0,'fr','menus','language-picker.langs.zu_ZA','Zoulou (Afrique du Sud)','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7973,0,'fr','menus','language-picker.langs.zu','zoulou','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7974,0,'fr','navs','general.home','Accueil','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7975,0,'fr','navs','general.logout','Connectez - Out','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7976,0,'fr','navs','general.login','S\'identifier','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7977,0,'fr','navs','general.account','Compte','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7978,0,'fr','navs','general.messages','messages','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7979,0,'fr','navs','general.no_messages','Pas de messages','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7980,0,'fr','navs','general.profile','Profil','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7981,0,'fr','navs','frontend.contact','Contact','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7982,0,'fr','navs','frontend.dashboard','Tableau de bord','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7983,0,'fr','navs','frontend.login','S\'identifier','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7984,0,'fr','navs','frontend.macros','Les macros','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7985,0,'fr','navs','frontend.register','registre','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7986,0,'fr','navs','frontend.user.account','Mon compte','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7987,0,'fr','navs','frontend.user.administration','Administration','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7988,0,'fr','navs','frontend.user.change_password','Changer le mot de passe','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7989,0,'fr','navs','frontend.user.my_information','Mon information','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7990,0,'fr','navs','frontend.user.profile','Profil','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7991,0,'fr','navs','frontend.user.edit_account','Modifier le compte','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7992,0,'fr','navs','frontend.forums','Les forums','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7993,0,'fr','navs','frontend.courses','Cours','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7994,0,'fr','pagination','previous','& laquo; précédent','2020-08-21 01:04:58','2020-10-12 05:00:40'),(7995,0,'fr','pagination','next','Suivant & raquo;','2020-08-21 01:04:59','2020-10-12 05:00:40'),(7996,0,'fr','passwords','password','Les mots de passe doivent comporter au moins six caractères et correspondre à la confirmation.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(7997,0,'fr','passwords','reset','Votre mot de passe a été réinitialisé!','2020-08-21 01:04:59','2020-10-12 05:00:40'),(7998,0,'fr','passwords','sent','Nous avons envoyé votre lien de réinitialisation de mot de passe par e-mail!','2020-08-21 01:04:59','2020-10-12 05:00:40'),(7999,0,'fr','passwords','token','Ce jeton de réinitialisation de mot de passe n\'est pas valide.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8000,0,'fr','passwords','user','Nous ne pouvons pas trouver un utilisateur avec cette adresse e-mail.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8001,0,'fr','roles','administrator','Administrateur','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8002,0,'fr','roles','user','Utilisateur','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8003,0,'fr','strings','backend.access.users.delete_user_confirm','Êtes-vous sûr de vouloir supprimer cet utilisateur de manière permanente? Toute erreur dans l’application faisant référence à l’identifiant de cet utilisateur sera la plus probable. Procédez à vos risques et périls. Ça ne peut pas être annulé.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8004,0,'fr','strings','backend.access.users.if_confirmed_off','(Si confirmé est éteint)','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8005,0,'fr','strings','backend.access.users.restore_user_confirm','Restaurer cet utilisateur à son état d\'origine?','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8006,0,'fr','strings','backend.access.users.no_deactivated','Il n\'y a pas d\'utilisateurs désactivés.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8007,0,'fr','strings','backend.access.users.no_deleted','Il n\'y a pas d\'utilisateurs supprimés.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8008,0,'fr','strings','backend.dashboard.title','Tableau de bord','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8009,0,'fr','strings','backend.dashboard.welcome','Bienvenue','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8010,0,'fr','strings','backend.dashboard.my_courses','Mes cours','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8011,0,'fr','strings','backend.general.all_rights_reserved','Tous les droits sont réservés.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8012,0,'fr','strings','backend.general.are_you_sure','Es-tu sûr de vouloir faire ça?','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8013,0,'fr','strings','backend.general.boilerplate_link','JThemes Studio','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8014,0,'fr','strings','backend.general.continue','Continuer','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8015,0,'fr','strings','backend.general.member_since','Membre depuis','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8016,0,'fr','strings','backend.general.minutes','minutes','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8017,0,'fr','strings','backend.general.search_placeholder','Chercher...','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8018,0,'fr','strings','backend.general.timeout','Vous avez été automatiquement déconnecté pour des raisons de sécurité, car vous n’avez exercé aucune activité dans','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8019,0,'fr','strings','backend.general.see_all.messages','Voir tous les messages','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8020,0,'fr','strings','backend.general.see_all.notifications','Voir tout','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8021,0,'fr','strings','backend.general.see_all.tasks','Voir toutes les tâches','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8022,0,'fr','strings','backend.general.status.online','En ligne','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8023,0,'fr','strings','backend.general.status.offline','Hors ligne','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8024,0,'fr','strings','backend.general.you_have.messages','{0} Vous n\'avez pas de message | {1} Vous avez 1 message | [2, Inf] Vous avez: nombre de messages','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8025,0,'fr','strings','backend.general.you_have.notifications','{0} Vous n\'avez pas de notifications | {1} Vous avez 1 notification | [2, Inf] Vous avez: notifications de numéro','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8026,0,'fr','strings','backend.general.you_have.tasks','{0} Vous n\'avez pas de tâches | {1} vous avez 1 tâche | [2, Inf] Vous avez: nombre de tâches','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8027,0,'fr','strings','backend.general.actions','actes','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8028,0,'fr','strings','backend.general.all','Tout','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8029,0,'fr','strings','backend.general.app_add','Ajouter','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8030,0,'fr','strings','backend.general.app_add_new','Ajouter un nouveau','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8031,0,'fr','strings','backend.general.app_are_you_sure','Êtes-vous sûr?','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8032,0,'fr','strings','backend.general.app_back_to_list','Retour à la liste','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8033,0,'fr','strings','backend.general.app_create','Créer','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8034,0,'fr','strings','backend.general.app_dashboard','Tableau de bord','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8035,0,'fr','strings','backend.general.app_delete','Effacer','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8036,0,'fr','strings','backend.general.app_edit','modifier','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8037,0,'fr','strings','backend.general.app_list','liste','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8038,0,'fr','strings','backend.general.app_logout','Connectez - Out','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8039,0,'fr','strings','backend.general.app_no_entries_in_table','Aucune entrée dans la table','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8040,0,'fr','strings','backend.general.app_permadel','Effacé définitivement','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8041,0,'fr','strings','backend.general.app_restore','Restaurer','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8042,0,'fr','strings','backend.general.app_save','sauvegarder','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8043,0,'fr','strings','backend.general.app_update','Mettre à jour','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8044,0,'fr','strings','backend.general.app_view','Vue','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8045,0,'fr','strings','backend.general.custom_controller_index','Index du contrôleur personnalisé.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8046,0,'fr','strings','backend.general.trashed','Mis à la poubelle','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8047,0,'fr','strings','backend.search.empty','Veuillez entrer un terme de recherche.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8048,0,'fr','strings','backend.search.incomplete','Vous devez écrire votre propre logique de recherche pour ce système.','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8049,0,'fr','strings','backend.search.title','Résultats de la recherche','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8050,0,'fr','strings','backend.search.results','Résultats de recherche pour: requête','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8051,0,'fr','strings','backend.welcome','Bienvenue sur le tableau de bord','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8052,0,'fr','strings','backend.menu_manager.Category','Catégorie','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8053,0,'fr','strings','backend.menu_manager.add_to_menu','Ajouter au menu','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8054,0,'fr','strings','backend.menu_manager.assigned_menu','Menu assigné','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8055,0,'fr','strings','backend.menu_manager.auto_add_pages','Ajout automatique de pages','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8056,0,'fr','strings','backend.menu_manager.cancel','Annuler','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8057,0,'fr','strings','backend.menu_manager.categories','Les catégories','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8058,0,'fr','strings','backend.menu_manager.choose','Choisir','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8059,0,'fr','strings','backend.menu_manager.class','Classe CSS (optionnel)','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8060,0,'fr','strings','backend.menu_manager.create_menu','Créer un menu','2020-08-21 01:04:59','2020-10-12 05:00:40'),(8061,0,'fr','strings','backend.menu_manager.create_new','Créer un nouveau menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8062,0,'fr','strings','backend.menu_manager.currently','Actuellement réglé sur','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8063,0,'fr','strings','backend.menu_manager.custom_link','Lien personnalisé','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8064,0,'fr','strings','backend.menu_manager.delete','Effacer','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8065,0,'fr','strings','backend.menu_manager.delete_menu','Supprimer le menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8066,0,'fr','strings','backend.menu_manager.display','Afficher','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8067,0,'fr','strings','backend.menu_manager.drag_instruction_1','Placez chaque article dans l\'ordre que vous préférez. Cliquez sur la flèche à droite de l\'élément pour afficher plus d\'options de configuration.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8068,0,'fr','strings','backend.menu_manager.drag_instruction_2','Veuillez entrer le nom et sélectionner le bouton \"Créer un menu\"','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8069,0,'fr','strings','backend.menu_manager.edit','modifier','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8070,0,'fr','strings','backend.menu_manager.edit_menus','Editer les menus','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8071,0,'fr','strings','backend.menu_manager.footer_menu','Menu de pied de page','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8072,0,'fr','strings','backend.menu_manager.label','Étiquette','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8073,0,'fr','strings','backend.menu_manager.link','Lien','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8074,0,'fr','strings','backend.menu_manager.locations','Emplacements','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8075,0,'fr','strings','backend.menu_manager.menu_creation','Création de menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8076,0,'fr','strings','backend.menu_manager.menu_settings','Paramètres du menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8077,0,'fr','strings','backend.menu_manager.menu_structure','Structure du menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8078,0,'fr','strings','backend.menu_manager.move','Bouge toi','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8079,0,'fr','strings','backend.menu_manager.move_down','Descendre','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8080,0,'fr','strings','backend.menu_manager.move_left','Se déplacer à gauche','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8081,0,'fr','strings','backend.menu_manager.move_right','Déplacer vers la droite','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8082,0,'fr','strings','backend.menu_manager.move_up','Déplacer vers le haut','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8083,0,'fr','strings','backend.menu_manager.name','prénom','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8084,0,'fr','strings','backend.menu_manager.or','Ou','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8085,0,'fr','strings','backend.menu_manager.page','Page','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8086,0,'fr','strings','backend.menu_manager.pages','Des pages','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8087,0,'fr','strings','backend.menu_manager.post','Poster','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8088,0,'fr','strings','backend.menu_manager.posts','Des postes','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8089,0,'fr','strings','backend.menu_manager.save_changes','Sauvegarder les modifications','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8090,0,'fr','strings','backend.menu_manager.save_menu','Enregistrer le menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8091,0,'fr','strings','backend.menu_manager.screen_reader_text','Appuyez sur Entrée ou Entrée pour développer','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8092,0,'fr','strings','backend.menu_manager.select_all','Tout sélectionner','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8093,0,'fr','strings','backend.menu_manager.select_to_edit','Sélectionnez le menu que vous souhaitez éditer','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8094,0,'fr','strings','backend.menu_manager.sub_menu','Sous menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8095,0,'fr','strings','backend.menu_manager.theme_location','Lieu thématique','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8096,0,'fr','strings','backend.menu_manager.title','Gestionnaire de menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8097,0,'fr','strings','backend.menu_manager.top','Haut','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8098,0,'fr','strings','backend.menu_manager.top_menu','Menu principal','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8099,0,'fr','strings','backend.menu_manager.update_item','Mettre à jour l\'élément','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8100,0,'fr','strings','backend.menu_manager.url','URL','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8101,0,'fr','strings','backend.menu_manager.welcome','Bienvenue','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8102,0,'fr','strings','backend.menu_manager.auto_add_pages_desc','Ajouter automatiquement de nouvelles pages de premier niveau à ce menu','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8103,0,'fr','strings','emails.auth.account_confirmed','Votre compte a été confirmé.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8104,0,'fr','strings','emails.auth.error','Oups!','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8105,0,'fr','strings','emails.auth.greeting','salut!','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8106,0,'fr','strings','emails.auth.regards','Cordialement,','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8107,0,'fr','strings','emails.auth.trouble_clicking_button','Si vous ne parvenez pas à cliquer sur le bouton \": action_text\", copiez et collez l’URL ci-dessous dans votre navigateur Web:','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8108,0,'fr','strings','emails.auth.thank_you_for_using_app','Merci d\'utiliser notre application!','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8109,0,'fr','strings','emails.auth.password_reset_subject','réinitialiser le mot de passe','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8110,0,'fr','strings','emails.auth.password_cause_of_email','Vous recevez cet email car nous avons reçu une demande de réinitialisation du mot de passe pour votre compte.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8111,0,'fr','strings','emails.auth.password_if_not_requested','Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune autre action n\'est requise.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8112,0,'fr','strings','emails.auth.reset_password','Cliquez ici pour réinitialiser votre mot de passe','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8113,0,'fr','strings','emails.auth.click_to_confirm','Cliquez ici pour confirmer votre compte:','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8114,0,'fr','strings','emails.contact.email_body_title','Vous avez une nouvelle demande de formulaire de contact: Voici les détails:','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8115,0,'fr','strings','emails.contact.subject','Une nouvelle soumission de formulaire de contact: app_name!','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8116,0,'fr','strings','emails.offline_order.subject','Concernant votre commande récente sur: app_name','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8117,0,'fr','strings','frontend.test','Tester','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8118,0,'fr','strings','frontend.tests.based_on.permission','Basé sur la permission -','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8119,0,'fr','strings','frontend.tests.based_on.role','Basé sur les rôles -','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8120,0,'fr','strings','frontend.tests.js_injected_from_controller','Javascript injecté à partir d\'un contrôleur','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8121,0,'fr','strings','frontend.tests.using_blade_extensions','Utilisation des extensions de lame','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8122,0,'fr','strings','frontend.tests.using_access_helper.array_permissions','Utilisation d\'Access Helper avec un tableau de noms de permission ou d\'identifiants où l\'utilisateur doit tout posséder.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8123,0,'fr','strings','frontend.tests.using_access_helper.array_permissions_not','Utilisation d\'Access Helper avec un tableau de noms de permission ou d\'identifiants pour lesquels l\'utilisateur ne doit pas nécessairement tout posséder.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8124,0,'fr','strings','frontend.tests.using_access_helper.array_roles','Utiliser Access Helper avec un tableau de noms de rôles ou d’ID où l’utilisateur doit tout posséder.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8125,0,'fr','strings','frontend.tests.using_access_helper.array_roles_not','Utiliser Access Helper avec un tableau de noms de rôles ou d’ID où l’utilisateur n’a pas à tout posséder','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8126,0,'fr','strings','frontend.tests.using_access_helper.permission_id','Utilisation d\'Access Helper avec un ID de permission','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8127,0,'fr','strings','frontend.tests.using_access_helper.permission_name','Utilisation d\'Access Helper avec le nom de permission','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8128,0,'fr','strings','frontend.tests.using_access_helper.role_id','Utilisation d\'Access Helper avec l\'ID de rôle','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8129,0,'fr','strings','frontend.tests.using_access_helper.role_name','Utilisation d\'Access Helper avec un nom de rôle','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8130,0,'fr','strings','frontend.tests.view_console_it_works','Voir la console, vous devriez voir \'ça marche!\' qui vient de FrontendController @ index','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8131,0,'fr','strings','frontend.tests.you_can_see_because','Vous pouvez voir cela parce que vous avez le rôle de \': rôle\'!','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8132,0,'fr','strings','frontend.tests.you_can_see_because_permission','Vous pouvez voir cela parce que vous avez la permission de \': permission\'!','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8133,0,'fr','strings','frontend.general.joined','Rejoint','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8134,0,'fr','strings','frontend.user.change_email_notice','Si vous modifiez votre adresse e-mail, vous serez déconnecté jusqu\'à ce que vous confirmiez votre nouvelle adresse e-mail.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8135,0,'fr','strings','frontend.user.email_changed_notice','Vous devez confirmer votre nouvelle adresse e-mail avant de pouvoir vous reconnecter.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8136,0,'fr','strings','frontend.user.profile_updated','Profil mis à jour avec succès.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8137,0,'fr','strings','frontend.user.password_updated','Mot de passe mis à jour avec succès.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8138,0,'fr','strings','frontend.welcome_to','Bienvenue sur: place','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8139,0,'fr','validation','accepted','L\'attribut: doit être accepté.','2020-08-21 01:05:00','2020-10-12 05:00:40'),(8140,0,'fr','validation','active_url','L\'attribut: n\'est pas une URL valide.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8141,0,'fr','validation','after','L\'attribut: doit être une date après: date.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8142,0,'fr','validation','after_or_equal','L\'attribut: doit être une date après ou égale à: date.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8143,0,'fr','validation','alpha','L\'attribut: ne peut contenir que des lettres.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8144,0,'fr','validation','alpha_dash','L\'attribut: ne peut contenir que des lettres, des chiffres, des tirets et des traits de soulignement.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8145,0,'fr','validation','alpha_num','L\'attribut: ne peut contenir que des lettres et des chiffres.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8146,0,'fr','validation','array','L\'attribut: doit être un tableau.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8147,0,'fr','validation','before','L\'attribut: doit être une date antérieure à: date.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8148,0,'fr','validation','before_or_equal','L\'attribut: doit être une date antérieure ou égale à: date.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8149,0,'fr','validation','between.numeric','L\'attribut: doit être compris entre: min et: max.','2020-08-21 01:05:01','2020-10-12 05:00:40'),(8150,0,'fr','validation','between.file','L\'attribut: doit être compris entre: min et: max kilo-octets.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8151,0,'fr','validation','between.string','L\'attribut: doit être compris entre: min et: max caractères.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8152,0,'fr','validation','between.array','L\'attribut: doit avoir entre: min et: max items.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8153,0,'fr','validation','boolean','Le champ d\'attribut: doit être vrai ou faux.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8154,0,'fr','validation','confirmed','La confirmation d\'attribut ne correspond pas.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8155,0,'fr','validation','date','L\'attribut: n\'est pas une date valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8156,0,'fr','validation','date_format','L\'attribut: ne correspond pas au format: format.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8157,0,'fr','validation','different','L\'attribut: et: autre doivent être différents.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8158,0,'fr','validation','digits','L\'attribut: doit être: digits digits.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8159,0,'fr','validation','digits_between','L\'attribut: doit être compris entre: min et: max digits.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8160,0,'fr','validation','dimensions','L\'attribut: a des dimensions d\'image non valides.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8161,0,'fr','validation','distinct','Le champ d\'attribut: a une valeur en double.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8162,0,'fr','validation','email','L\'attribut: doit être une adresse email valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8163,0,'fr','validation','exists','L\'attribut sélectionné: n\'est pas valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8164,0,'fr','validation','file','L\'attribut: doit être un fichier.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8165,0,'fr','validation','filled','Le champ d\'attribut: doit avoir une valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8166,0,'fr','validation','gt.numeric','L\'attribut: doit être supérieur à: valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8167,0,'fr','validation','gt.file','L\'attribut: doit être supérieur à: valeur kilo-octets.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8168,0,'fr','validation','gt.string','L\'attribut: doit être supérieur à: caractères de valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8169,0,'fr','validation','gt.array','L\'attribut: doit avoir plus de: éléments de valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8170,0,'fr','validation','gte.numeric','L\'attribut: doit être supérieur ou égal à: valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8171,0,'fr','validation','gte.file','L\'attribut: doit être supérieur ou égal à: valeur en kilo-octets.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8172,0,'fr','validation','gte.string','L\'attribut: doit être supérieur ou égal à: caractères de valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8173,0,'fr','validation','gte.array','L\'attribut: doit avoir: éléments de valeur ou plus.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8174,0,'fr','validation','image','L\'attribut: doit être une image.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8175,0,'fr','validation','in','L\'attribut sélectionné: n\'est pas valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8176,0,'fr','validation','in_array','Le champ: attribut n\'existe pas dans: autre.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8177,0,'fr','validation','integer','L\'attribut: doit être un entier.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8178,0,'fr','validation','ip','L\'attribut: doit être une adresse IP valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8179,0,'fr','validation','ipv4','L\'attribut: doit être une adresse IPv4 valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8180,0,'fr','validation','ipv6','L\'attribut: doit être une adresse IPv6 valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8181,0,'fr','validation','json','L\'attribut: doit être une chaîne JSON valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8182,0,'fr','validation','lt.numeric','L\'attribut: doit être inférieur à: valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8183,0,'fr','validation','lt.file','L\'attribut: doit être inférieur à: valeur kilo-octets.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8184,0,'fr','validation','lt.string','L\'attribut: doit être inférieur à: caractères de valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8185,0,'fr','validation','lt.array','L\'attribut: doit avoir moins de: éléments de valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8186,0,'fr','validation','lte.numeric','L\'attribut: doit être inférieur ou égal à: valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8187,0,'fr','validation','lte.file','L\'attribut: doit être inférieur ou égal à: valeur kilo-octets.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8188,0,'fr','validation','lte.string','L\'attribut: doit être inférieur ou égal à: caractères de valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8189,0,'fr','validation','lte.array','L\'attribut: ne doit pas avoir plus de: éléments de valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8190,0,'fr','validation','max.numeric','L\'attribut: ne peut pas être supérieur à: max.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8191,0,'fr','validation','max.file','L\'attribut: ne peut pas être supérieur à: max kilo-octets.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8192,0,'fr','validation','max.string','L\'attribut: ne peut pas être supérieur à: max caractères.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8193,0,'fr','validation','max.array','L\'attribut: ne peut avoir plus de: max articles.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8194,0,'fr','validation','mimes','L\'attribut: doit être un fichier de type:attribute valeurs.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8195,0,'fr','validation','mimetypes','L\'attribut: doit être un fichier de type:attribute valeurs.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8196,0,'fr','validation','min.numeric','L\'attribut: doit être au moins: min.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8197,0,'fr','validation','min.file','L\'attribut: doit être au moins: min kilo-octets.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8198,0,'fr','validation','min.string','L\'attribut: doit être au moins: min caractères.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8199,0,'fr','validation','min.array','L\'attribut: doit avoir au moins: min items.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8200,0,'fr','validation','not_in','L\'attribut sélectionné: n\'est pas valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8201,0,'fr','validation','not_regex','Le format d\'attribut est invalide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8202,0,'fr','validation','numeric','L\'attribut: doit être un nombre.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8203,0,'fr','validation','present','Le champ d\'attribut: doit être présent.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8204,0,'fr','validation','regex','Le format d\'attribut est invalide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8205,0,'fr','validation','required','Le champ d\'attribut: est requis.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8206,0,'fr','validation','required_if','Le champ d\'attribut: est requis lorsque: autre est: valeur.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8207,0,'fr','validation','required_unless','Le champ: attribut est obligatoire sauf si: autre est dans: valeurs.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8208,0,'fr','validation','required_with','Le champ: attribut est requis lorsque: values ​​est présent.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8209,0,'fr','validation','required_with_all','Le champ: attribut est requis lorsque: des valeurs sont présentes.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8210,0,'fr','validation','required_without','Le champ: attribut est requis lorsque: values ​​n\'est pas présent.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8211,0,'fr','validation','required_without_all','Le champ d\'attribut: est requis lorsqu\'aucune des valeurs suivantes n\'est présente.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8212,0,'fr','validation','same','L\'attribut: et: other doivent correspondre.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8213,0,'fr','validation','size.numeric','L\'attribut: doit être: taille.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8214,0,'fr','validation','size.file','L\'attribut: doit être: taille kilo-octets.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8215,0,'fr','validation','size.string','L\'attribut: doit être: caractères de taille.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8216,0,'fr','validation','size.array','L\'attribut: doit contenir: les éléments de taille.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8217,0,'fr','validation','string','L\'attribut: doit être une chaîne.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8218,0,'fr','validation','timezone','L\'attribut: doit être une zone valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8219,0,'fr','validation','unique','L\'attribut: a déjà été pris.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8220,0,'fr','validation','uploaded','L\'attribut: n\'a pas pu être téléchargé.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8221,0,'fr','validation','url','Le format d\'attribut est invalide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8222,0,'fr','validation','uuid','L\'attribut: doit être un UUID valide.','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8223,0,'fr','validation','custom.attribute-name.rule-name','message personnalisé','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8224,0,'fr','validation','attributes.backend.access.permissions.associated_roles','Rôles associés','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8225,0,'fr','validation','attributes.backend.access.permissions.dependencies','Les dépendances','2020-08-21 01:05:01','2020-10-12 05:00:41'),(8226,0,'fr','validation','attributes.backend.access.permissions.display_name','Afficher un nom','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8227,0,'fr','validation','attributes.backend.access.permissions.group','Groupe','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8228,0,'fr','validation','attributes.backend.access.permissions.group_sort','Tri du groupe','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8229,0,'fr','validation','attributes.backend.access.permissions.groups.name','Nom de groupe','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8230,0,'fr','validation','attributes.backend.access.permissions.name','prénom','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8231,0,'fr','validation','attributes.backend.access.permissions.first_name','Prénom','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8232,0,'fr','validation','attributes.backend.access.permissions.last_name','Nom de famille','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8233,0,'fr','validation','attributes.backend.access.permissions.system','Système','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8234,0,'fr','validation','attributes.backend.access.roles.associated_permissions','Autorisations associées','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8235,0,'fr','validation','attributes.backend.access.roles.name','prénom','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8236,0,'fr','validation','attributes.backend.access.roles.sort','Trier','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8237,0,'fr','validation','attributes.backend.access.users.active','actif','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8238,0,'fr','validation','attributes.backend.access.users.associated_roles','Rôles associés','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8239,0,'fr','validation','attributes.backend.access.users.confirmed','Confirmé','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8240,0,'fr','validation','attributes.backend.access.users.email','Adresse électronique','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8241,0,'fr','validation','attributes.backend.access.users.name','prénom','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8242,0,'fr','validation','attributes.backend.access.users.first_name','Prénom','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8243,0,'fr','validation','attributes.backend.access.users.last_name','Nom de famille','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8244,0,'fr','validation','attributes.backend.access.users.other_permissions','Autres autorisations','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8245,0,'fr','validation','attributes.backend.access.users.password','Mot de passe','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8246,0,'fr','validation','attributes.backend.access.users.password_confirmation','Confirmation mot de passe','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8247,0,'fr','validation','attributes.backend.access.users.send_confirmation_email','Envoyer un e-mail de confirmation','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8248,0,'fr','validation','attributes.backend.access.users.language','La langue','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8249,0,'fr','validation','attributes.backend.access.users.timezone','Fuseau horaire','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8250,0,'fr','validation','attributes.backend.settings.general_settings.app_locale','App Locale','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8251,0,'fr','validation','attributes.backend.settings.general_settings.app_name','Nom de l\'application','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8252,0,'fr','validation','attributes.backend.settings.general_settings.app_timezone','Fuseau horaire de l\'application','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8253,0,'fr','validation','attributes.backend.settings.general_settings.app_url','URL de l\'application','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8254,0,'fr','validation','attributes.backend.settings.general_settings.change_email','Changer l\'e-mail','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8255,0,'fr','validation','attributes.backend.settings.general_settings.confirm_email','Confirmez votre e-mail','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8256,0,'fr','validation','attributes.backend.settings.general_settings.enable_registration','Activer l\'enregistrement','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8257,0,'fr','validation','attributes.backend.settings.general_settings.font_color','Couleur de la police','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8258,0,'fr','validation','attributes.backend.settings.general_settings.theme_layout','Mise en page du thème','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8259,0,'fr','validation','attributes.backend.settings.general_settings.requires_approval','Nécessite une approbation','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8260,0,'fr','validation','attributes.backend.settings.general_settings.password_history','Historique du mot de passe','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8261,0,'fr','validation','attributes.backend.settings.general_settings.password_expires_days','Mot de passe expire jours','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8262,0,'fr','validation','attributes.backend.settings.general_settings.mail_username','Mail Nom d\'utilisateur','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8263,0,'fr','validation','attributes.backend.settings.general_settings.mail_port','Port de messagerie','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8264,0,'fr','validation','attributes.backend.settings.general_settings.mail_password','Mot de passe mail','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8265,0,'fr','validation','attributes.backend.settings.general_settings.mail_host','Mail Host','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8266,0,'fr','validation','attributes.backend.settings.general_settings.mail_from_name','Mail De Nom','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8267,0,'fr','validation','attributes.backend.settings.general_settings.mail_from_address','Mail De Adresse','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8268,0,'fr','validation','attributes.backend.settings.general_settings.mail_driver','Pilote de courrier','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8269,0,'fr','validation','attributes.backend.settings.general_settings.layout_type','Type de disposition','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8270,0,'fr','validation','attributes.backend.settings.general_settings.homepage','Sélectionner la page d\'accueil','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8271,0,'fr','validation','attributes.backend.settings.general_settings.captcha_site_key','Captcha Key','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8272,0,'fr','validation','attributes.backend.settings.general_settings.captcha_site_secret','Captcha Secret','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8273,0,'fr','validation','attributes.backend.settings.general_settings.captcha_status','Statut Captcha','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8274,0,'fr','validation','attributes.backend.settings.general_settings.retest_status','Re-tester','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8275,0,'fr','validation','attributes.backend.settings.general_settings.google_analytics','Code Google Analytics','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8276,0,'fr','validation','attributes.backend.settings.general_settings.lesson_timer','Leçon Minuterie','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8277,0,'fr','validation','attributes.backend.settings.general_settings.one_signal_push_notification','Configuration OneSignal','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8278,0,'fr','validation','attributes.backend.settings.general_settings.onesignal_code','Collez le code de script OneSignal ici','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8279,0,'fr','validation','attributes.backend.settings.general_settings.show_offers','Afficher les offres','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8280,0,'fr','validation','attributes.backend.settings.social_settings.twitter.redirect','URL de redirection','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8281,0,'fr','validation','attributes.backend.settings.social_settings.twitter.label','État de connexion Twitter','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8282,0,'fr','validation','attributes.backend.settings.social_settings.twitter.client_secret','Secret du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8283,0,'fr','validation','attributes.backend.settings.social_settings.twitter.client_id','identité du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8284,0,'fr','validation','attributes.backend.settings.social_settings.linkedin.redirect','URL de redirection','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8285,0,'fr','validation','attributes.backend.settings.social_settings.linkedin.client_secret','Secret du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8286,0,'fr','validation','attributes.backend.settings.social_settings.linkedin.client_id','identité du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8287,0,'fr','validation','attributes.backend.settings.social_settings.linkedin.label','État de connexion LinkedIn','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8288,0,'fr','validation','attributes.backend.settings.social_settings.google.redirect','URL de redirection','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8289,0,'fr','validation','attributes.backend.settings.social_settings.google.label','État de connexion Google','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8290,0,'fr','validation','attributes.backend.settings.social_settings.google.client_secret','Secret du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8291,0,'fr','validation','attributes.backend.settings.social_settings.google.client_id','identité du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8292,0,'fr','validation','attributes.backend.settings.social_settings.github.client_secret','Secret du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8293,0,'fr','validation','attributes.backend.settings.social_settings.github.client_id','identité du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8294,0,'fr','validation','attributes.backend.settings.social_settings.github.redirect','URL de redirection','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8295,0,'fr','validation','attributes.backend.settings.social_settings.github.label','Statut de connexion Github','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8296,0,'fr','validation','attributes.backend.settings.social_settings.facebook.client_secret','Secret du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8297,0,'fr','validation','attributes.backend.settings.social_settings.facebook.client_id','identité du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8298,0,'fr','validation','attributes.backend.settings.social_settings.facebook.redirect','URL de redirection','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8299,0,'fr','validation','attributes.backend.settings.social_settings.facebook.label','État de connexion Facebook','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8300,0,'fr','validation','attributes.backend.settings.social_settings.bitbucket.redirect','URL de redirection','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8301,0,'fr','validation','attributes.backend.settings.social_settings.bitbucket.client_secret','Secret du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8302,0,'fr','validation','attributes.backend.settings.social_settings.bitbucket.client_id','identité du client','2020-08-21 01:05:02','2020-10-12 05:00:41'),(8303,0,'fr','validation','attributes.backend.settings.social_settings.bitbucket.label','État de connexion Bitbucket','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8304,0,'fr','validation','attributes.frontend.avatar','Localisation de l\'avatar','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8305,0,'fr','validation','attributes.frontend.email','Adresse électronique','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8306,0,'fr','validation','attributes.frontend.last_name','Nom de famille','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8307,0,'fr','validation','attributes.frontend.first_name','Prénom','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8308,0,'fr','validation','attributes.frontend.name','Nom complet','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8309,0,'fr','validation','attributes.frontend.password','Mot de passe','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8310,0,'fr','validation','attributes.frontend.password_confirmation','Confirmation mot de passe','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8311,0,'fr','validation','attributes.frontend.phone','Téléphone','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8312,0,'fr','validation','attributes.frontend.message','Message','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8313,0,'fr','validation','attributes.frontend.new_password','nouveau mot de passe','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8314,0,'fr','validation','attributes.frontend.new_password_confirmation','Confirmation du nouveau mot de passe','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8315,0,'fr','validation','attributes.frontend.old_password','ancien mot de passe','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8316,0,'fr','validation','attributes.frontend.timezone','Fuseau horaire','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8317,0,'fr','validation','attributes.frontend.upload','Télécharger','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8318,0,'fr','validation','attributes.frontend.language','La langue','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8319,0,'fr','validation','attributes.frontend.gravatar','Gravatar','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8320,0,'fr','validation','attributes.frontend.captcha','Captcha requis','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8321,0,'fr','validation','attributes.frontend.female','Femelle','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8322,0,'fr','validation','attributes.frontend.male','Mâle','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8323,0,'fr','validation','attributes.frontend.other','Autre','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8324,0,'fr','validation','attributes.frontend.payment_information','Informations de paiement','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8325,0,'fr','validation','attributes.frontend.personal_information','Informations personnelles','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8326,0,'fr','validation','attributes.frontend.social_information','Informations sociales','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8327,0,'ar','vendor/backup','exception_message','رسالة استثناء: :message','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8328,0,'ar','vendor/backup','exception_trace','تتبع الاستثناء: :trace','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8329,0,'ar','vendor/backup','exception_message_title','رسالة استثناء','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8330,0,'ar','vendor/backup','exception_trace_title','تتبع استثناء','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8331,0,'ar','vendor/backup','backup_failed_subject','فشل النسخ الاحتياطي من application_name:','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8332,0,'ar','vendor/backup','backup_failed_body','Important: حدث خطأ أثناء النسخ الاحتياطي :application_name','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8333,0,'ar','vendor/backup','backup_successful_subject','نسخة احتياطية جديدة ناجحة من :application_name','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8334,0,'ar','vendor/backup','backup_successful_subject_title','نسخة احتياطية جديدة ناجحة!','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8335,0,'ar','vendor/backup','backup_successful_body','خبر رائع ، تم إنشاء نسخة احتياطية جديدة من application_name: بنجاح على القرص المسمى disk_name:.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8336,0,'ar','vendor/backup','cleanup_failed_subject','فشل تنظيف النسخ الاحتياطية لـ :application_name.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8337,0,'ar','vendor/backup','cleanup_failed_body','حدث خطأ أثناء تنظيف النسخ الاحتياطية لـ :application_name','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8338,0,'ar','vendor/backup','cleanup_successful_subject','تنظيف: النسخ الاحتياطية :application_name ناجحة','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8339,0,'ar','vendor/backup','cleanup_successful_subject_title','تنظيف النسخ الاحتياطية الناجحة!','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8340,0,'ar','vendor/backup','cleanup_successful_body','تم تنظيف النسخ الاحتياطية لـ :application_name على القرص المسمى :disk_name.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8341,0,'ar','vendor/backup','healthy_backup_found_subject','النسخ الاحتياطية لـ :application_name على القرص :disk_name سليمة','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8342,0,'ar','vendor/backup','healthy_backup_found_subject_title','النسخ الاحتياطية لـ :application_name صحية','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8343,0,'ar','vendor/backup','healthy_backup_found_body','تعتبر النسخ الاحتياطية لـ :application_name صحية. عمل جيد!','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8344,0,'ar','vendor/backup','unhealthy_backup_found_subject','هام: النسخ الاحتياطية لـ :application_name غير صحية','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8345,0,'ar','vendor/backup','unhealthy_backup_found_subject_title','Important: النسخ الاحتياطية لـ :application_name غير صحية. : problem','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8346,0,'ar','vendor/backup','unhealthy_backup_found_body','النسخ الاحتياطية لـ :application_name على القرص :disk_name غير صحية.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8347,0,'ar','vendor/backup','unhealthy_backup_found_not_reachable','لا يمكن الوصول إلى وجهة النسخ الاحتياطي. :error','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8348,0,'ar','vendor/backup','unhealthy_backup_found_empty','لا توجد نسخ احتياطية لهذا التطبيق على الإطلاق.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8349,0,'ar','vendor/backup','unhealthy_backup_found_old','أحدث نسخة احتياطية صنعت في :date تعتبر قديمة جدًا.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8350,0,'ar','vendor/backup','unhealthy_backup_found_unknown','آسف ، لا يمكن تحديد السبب الدقيق.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8351,0,'ar','vendor/backup','unhealthy_backup_found_full','النسخ الاحتياطية تستخدم الكثير من التخزين. الاستخدام الحالي هو :disk_usage وهو أعلى من الحد المسموح به وهو :disk_limit.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8352,0,'en','vendor/backup','exception_message','Exception message: :message','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8353,0,'en','vendor/backup','exception_trace','Exception trace: :trace','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8354,0,'en','vendor/backup','exception_message_title','Exception message','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8355,0,'en','vendor/backup','exception_trace_title','Exception trace','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8356,0,'en','vendor/backup','backup_failed_subject','Failed backup of :application_name','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8357,0,'en','vendor/backup','backup_failed_body','Important: An error occurred while backing up :application_name','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8358,0,'en','vendor/backup','backup_successful_subject','Successful new backup of :application_name','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8359,0,'en','vendor/backup','backup_successful_subject_title','Successful new backup!','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8360,0,'en','vendor/backup','backup_successful_body','Great news, a new backup of :application_name was successfully created on the disk named :disk_name.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8361,0,'en','vendor/backup','cleanup_failed_subject','Cleaning up the backups of :application_name failed.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8362,0,'en','vendor/backup','cleanup_failed_body','An error occurred while cleaning up the backups of :application_name','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8363,0,'en','vendor/backup','cleanup_successful_subject','Clean up of :application_name backups successful','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8364,0,'en','vendor/backup','cleanup_successful_subject_title','Clean up of backups successful!','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8365,0,'en','vendor/backup','cleanup_successful_body','The clean up of the :application_name backups on the disk named :disk_name was successful.','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8366,0,'en','vendor/backup','healthy_backup_found_subject','The backups for :application_name on disk :disk_name are healthy','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8367,0,'en','vendor/backup','healthy_backup_found_subject_title','The backups for :application_name are healthy','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8368,0,'en','vendor/backup','healthy_backup_found_body','The backups for :application_name are considered healthy. Good job!','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8369,0,'en','vendor/backup','unhealthy_backup_found_subject','Important: The backups for :application_name are unhealthy','2020-08-21 01:05:03','2020-10-12 05:00:41'),(8370,0,'en','vendor/backup','unhealthy_backup_found_subject_title','Important: The backups for :application_name are unhealthy. :problem','2020-08-21 01:05:03','2020-10-12 05:00:42'),(8371,0,'en','vendor/backup','unhealthy_backup_found_body','The backups for :application_name on disk :disk_name are unhealthy.','2020-08-21 01:05:03','2020-10-12 05:00:42'),(8372,0,'en','vendor/backup','unhealthy_backup_found_not_reachable','The backup destination cannot be reached. :error','2020-08-21 01:05:03','2020-10-12 05:00:42'),(8373,0,'en','vendor/backup','unhealthy_backup_found_empty','There are no backups of this application at all.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8374,0,'en','vendor/backup','unhealthy_backup_found_old','The latest backup made on :date is considered too old.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8375,0,'en','vendor/backup','unhealthy_backup_found_unknown','Sorry, an exact reason cannot be determined.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8376,0,'en','vendor/backup','unhealthy_backup_found_full','The backups are using too much storage. Current usage is :disk_usage which is higher than the allowed limit of :disk_limit.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8377,0,'es','vendor/backup','exception_message','Mensaje de la excepción: :message','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8378,0,'es','vendor/backup','exception_trace','Traza de la excepción: :trace','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8379,0,'es','vendor/backup','exception_message_title','Mensaje de la excepción','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8380,0,'es','vendor/backup','exception_trace_title','Traza de la excepción','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8381,0,'es','vendor/backup','backup_failed_subject','Copia de seguridad de :application_name fallida','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8382,0,'es','vendor/backup','backup_failed_body','Importante: Ocurrió un error al realizar la copia de seguridad de :application_name','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8383,0,'es','vendor/backup','backup_successful_subject','Se completó con éxito la copia de seguridad de :application_name','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8384,0,'es','vendor/backup','backup_successful_subject_title','¡Nueva copia de seguridad creada con éxito!','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8385,0,'es','vendor/backup','backup_successful_body','Buenas noticias, una nueva copia de seguridad de :application_name fue creada con éxito en el disco llamado :disk_name.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8386,0,'es','vendor/backup','cleanup_failed_subject','La limpieza de copias de seguridad de :application_name falló.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8387,0,'es','vendor/backup','cleanup_failed_body','Ocurrió un error mientras se realizaba la limpieza de copias de seguridad de :application_name','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8388,0,'es','vendor/backup','cleanup_successful_subject','La limpieza de copias de seguridad de :application_name se completó con éxito','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8389,0,'es','vendor/backup','cleanup_successful_subject_title','!Limpieza de copias de seguridad completada con éxito!','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8390,0,'es','vendor/backup','cleanup_successful_body','La limpieza de copias de seguridad de :application_name en el disco llamado :disk_name se completo con éxito.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8391,0,'es','vendor/backup','healthy_backup_found_subject','Las copias de seguridad de :application_name en el disco :disk_name están en buen estado','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8392,0,'es','vendor/backup','healthy_backup_found_subject_title','Las copias de seguridad de :application_name están en buen estado','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8393,0,'es','vendor/backup','healthy_backup_found_body','Las copias de seguridad de :application_name se consideran en buen estado. ¡Buen trabajo!','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8394,0,'es','vendor/backup','unhealthy_backup_found_subject','Importante: Las copias de seguridad de :application_name están en mal estado','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8395,0,'es','vendor/backup','unhealthy_backup_found_subject_title','Importante: Las copias de seguridad de :application_name están en mal estado. :problem','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8396,0,'es','vendor/backup','unhealthy_backup_found_body','Las copias de seguridad de :application_name en el disco :disk_name están en mal estado.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8397,0,'es','vendor/backup','unhealthy_backup_found_not_reachable','No se puede acceder al destino de la copia de seguridad. :error','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8398,0,'es','vendor/backup','unhealthy_backup_found_empty','No existe ninguna copia de seguridad de esta aplicación.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8399,0,'es','vendor/backup','unhealthy_backup_found_old','La última copia de seguriad hecha en :date es demasiado antigua.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8400,0,'es','vendor/backup','unhealthy_backup_found_unknown','Lo siento, no es posible determinar la razón exacta.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8401,0,'es','vendor/backup','unhealthy_backup_found_full','Las copias de seguridad  están ocupando demasiado espacio. El espacio utilizado actualmente es :disk_usage el cual es mayor que el límite permitido de :disk_limit.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8402,0,'fr','vendor/backup','exception_message','Message de l\'exception : :message','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8403,0,'fr','vendor/backup','exception_trace','Trace de l\'exception : :trace','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8404,0,'fr','vendor/backup','exception_message_title','Message de l\'exception','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8405,0,'fr','vendor/backup','exception_trace_title','Trace de l\'exception','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8406,0,'fr','vendor/backup','backup_failed_subject','Échec de la sauvegarde de :application_name','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8407,0,'fr','vendor/backup','backup_failed_body','Important : Une erreur est survenue lors de la sauvegarde de :application_name','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8408,0,'fr','vendor/backup','backup_successful_subject','Succès de la sauvegarde de :application_name','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8409,0,'fr','vendor/backup','backup_successful_subject_title','Sauvegarde créée avec succès !','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8410,0,'fr','vendor/backup','backup_successful_body','Bonne nouvelle, une nouvelle sauvegarde de :application_name a été créée avec succès sur le disque nommé :disk_name.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8411,0,'fr','vendor/backup','cleanup_failed_subject','Le nettoyage des sauvegardes de :application_name a echoué.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8412,0,'fr','vendor/backup','cleanup_failed_body','Une erreur est survenue lors du nettoyage des sauvegardes de :application_name','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8413,0,'fr','vendor/backup','cleanup_successful_subject','Succès du nettoyage des sauvegardes de :application_name','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8414,0,'fr','vendor/backup','cleanup_successful_subject_title','Sauvegardes nettoyées avec succès !','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8415,0,'fr','vendor/backup','cleanup_successful_body','Le nettoyage des sauvegardes de :application_name sur le disque nommé :disk_name a été effectué avec succès.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8416,0,'fr','vendor/backup','healthy_backup_found_subject','Les sauvegardes pour :application_name sur le disque :disk_name sont saines','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8417,0,'fr','vendor/backup','healthy_backup_found_subject_title','Les sauvegardes pour :application_name sont saines','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8418,0,'fr','vendor/backup','healthy_backup_found_body','Les sauvegardes pour :application_name sont considérées saines. Bon travail !','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8419,0,'fr','vendor/backup','unhealthy_backup_found_subject','Important : Les sauvegardes pour :application_name sont corrompues','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8420,0,'fr','vendor/backup','unhealthy_backup_found_subject_title','Important : Les sauvegardes pour :application_name sont corrompues. :problem','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8421,0,'fr','vendor/backup','unhealthy_backup_found_body','Les sauvegardes pour :application_name sur le disque :disk_name sont corrompues.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8422,0,'fr','vendor/backup','unhealthy_backup_found_not_reachable','La destination de la sauvegarde n\'est pas accessible. :error','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8423,0,'fr','vendor/backup','unhealthy_backup_found_empty','Il n\'y a aucune sauvegarde pour cette application.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8424,0,'fr','vendor/backup','unhealthy_backup_found_old','La dernière sauvegarde du :date est considérée trop vieille.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8425,0,'fr','vendor/backup','unhealthy_backup_found_unknown','Désolé, une raison exacte ne peut être déterminée.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8426,0,'fr','vendor/backup','unhealthy_backup_found_full','Les sauvegardes utilisent trop d\'espace disque. L\'utilisation actuelle est de :disk_usage alors que la limite autorisée est de :disk_limit.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8427,0,'ar','vendor/chatter','success.title','أحسنت!','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8428,0,'ar','vendor/chatter','success.reason.submitted_to_post','تم تقديم الرد بنجاح للمناقشة. discussion','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8429,0,'ar','vendor/chatter','success.reason.updated_post','Discussion تم تحديث المناقشة بنجاح.','2020-08-21 01:05:04','2020-10-12 05:00:42'),(8430,0,'ar','vendor/chatter','success.reason.destroy_post','تم حذف الرد والمناقشة بنجاح. discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8431,0,'ar','vendor/chatter','success.reason.destroy_from_discussion','تم حذف الرد بنجاح من المناقشة. discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8432,0,'ar','vendor/chatter','success.reason.created_discussion','تم إنشاء مناقشة جديدة بنجاح. discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8433,0,'ar','vendor/chatter','info.title','انتباه!','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8434,0,'ar','vendor/chatter','warning.title','ووه أوه!','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8435,0,'ar','vendor/chatter','danger.title','يا سناب!','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8436,0,'ar','vendor/chatter','danger.reason.errors','يرجى تصحيح الأخطاء التالية:','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8437,0,'ar','vendor/chatter','danger.reason.prevent_spam','لمنع البريد العشوائي ، يرجى السماح على الأقل :minutes بين إرسال المحتوى.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8438,0,'ar','vendor/chatter','danger.reason.trouble','عذرًا ، يبدو أنه كانت هناك مشكلة في إرسال ردك.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8439,0,'ar','vendor/chatter','danger.reason.update_post','آه آه آه ... لا يمكن تحديث ردكم. تأكد من أنك لا تفعل أي شيء شادي.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8440,0,'ar','vendor/chatter','danger.reason.destroy_post','آه آه آه ... لا يمكن حذف الرد. تأكد من أنك لا تفعل أي شيء شادي.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8441,0,'ar','vendor/chatter','danger.reason.create_discussion','عفوًا :( يبدو أن هناك مشكلة في إنشاء مناقشتك. discussion. :(','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8442,0,'ar','vendor/chatter','danger.reason.title_required','يرجى كتابة العنوان','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8443,0,'ar','vendor/chatter','danger.reason.title_min','يجب أن يكون العنوان على الأقل: أحرف :min.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8444,0,'ar','vendor/chatter','danger.reason.title_max','يجب ألا يزيد العنوان عن: أحرف :max.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8445,0,'ar','vendor/chatter','danger.reason.content_required','يرجى كتابة بعض المحتوى','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8446,0,'ar','vendor/chatter','danger.reason.content_min','يجب أن يحتوي المحتوى على الأقل: أحرف :min','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8447,0,'ar','vendor/chatter','danger.reason.category_required','يرجى اختيار فئة','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8448,0,'ar','vendor/chatter','preheader','أردت فقط أن أخبرك أن شخصًا ما قد استجاب لنشر منتدى.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8449,0,'ar','vendor/chatter','greeting','مرحبا،,','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8450,0,'ar','vendor/chatter','body','أردت فقط أن أخبركم بأن شخصًا ما قد استجاب لنشر منتدى في discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8451,0,'ar','vendor/chatter','view_discussion','عرض المناقشة. discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8452,0,'ar','vendor/chatter','farewell','أتمنى لك يوما عظيما!.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8453,0,'ar','vendor/chatter','unsuscribe.message','إذا لم تعد ترغب في أن يتم إعلامك عندما يستجيب شخص ما لهذا المنشور ، فتأكد من إلغاء تحديد إعداد الإشعارات في أسفل الصفحة :)','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8454,0,'ar','vendor/chatter','unsuscribe.action','لا أحب هذه رسائل البريد الإلكتروني؟','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8455,0,'ar','vendor/chatter','unsuscribe.link','إلغاء الاشتراك في هذه المناقشة. discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8456,0,'ar','vendor/chatter','titles.discussion','نقاش','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8457,0,'ar','vendor/chatter','titles.discussions','مناقشات','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8458,0,'ar','vendor/chatter','headline','مرحبا بكم في الثرثرة','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8459,0,'ar','vendor/chatter','description','حزمة منتدى بسيطة لتطبيق Laravel الخاص بك.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8460,0,'ar','vendor/chatter','words.cancel','إلغاء','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8461,0,'ar','vendor/chatter','words.delete','حذف','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8462,0,'ar','vendor/chatter','words.edit','تصحيح','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8463,0,'ar','vendor/chatter','words.yes','نعم فعلا','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8464,0,'ar','vendor/chatter','words.no','لا','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8465,0,'ar','vendor/chatter','words.minutes','1 دقيقة | :count دقيقة','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8466,0,'ar','vendor/chatter','discussion.new','مناقشة جديدة discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8467,0,'ar','vendor/chatter','discussion.all','كل مناقشة discussions','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8468,0,'ar','vendor/chatter','discussion.create','إنشاء مناقشة discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8469,0,'ar','vendor/chatter','discussion.posted_by','منشور من طرف','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8470,0,'ar','vendor/chatter','discussion.head_details','نشر في الفئة','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8471,0,'ar','vendor/chatter','response.confirm','هل أنت متأكد أنك تريد حذف هذا الرد؟','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8472,0,'ar','vendor/chatter','response.yes_confirm','نعم احذفها','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8473,0,'ar','vendor/chatter','response.no_confirm','لا شكرا','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8474,0,'ar','vendor/chatter','response.submit','إرسال الرد','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8475,0,'ar','vendor/chatter','response.update','تحديث الرد','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8476,0,'ar','vendor/chatter','editor.title','عنوان المناقشةdiscussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8477,0,'ar','vendor/chatter','editor.select','اختر تصنيف','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8478,0,'ar','vendor/chatter','editor.tinymce_placeholder','اكتب محادثتك هنا ... discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8479,0,'ar','vendor/chatter','editor.select_color_text','اختر لونًا لهذه المناقشة (اختياري) discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8480,0,'ar','vendor/chatter','email.notify','أعلمني عندما يرد شخص ما discussion','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8481,0,'ar','vendor/chatter','auth','يرجى <a href=\"/:home/login\"> تسجيل الدخول </a>\n                أو <a href=\"/:home/register\"> التسجيل </a>\n                لترك الرد.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8482,0,'en','vendor/chatter','success.title','Well done!','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8483,0,'en','vendor/chatter','success.reason.submitted_to_post','Response successfully submitted to discussion.','2020-08-21 01:05:05','2020-10-12 05:00:42'),(8484,0,'en','vendor/chatter','success.reason.updated_post','Successfully updated the discussion.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8485,0,'en','vendor/chatter','success.reason.destroy_post','Successfully deleted the response and discussion.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8486,0,'en','vendor/chatter','success.reason.destroy_from_discussion','Successfully deleted the response from the discussion.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8487,0,'en','vendor/chatter','success.reason.created_discussion','Successfully created a new discussion.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8488,0,'en','vendor/chatter','info.title','Heads Up!','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8489,0,'en','vendor/chatter','warning.title','Wuh Oh!','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8490,0,'en','vendor/chatter','danger.title','Oh Snap!','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8491,0,'en','vendor/chatter','danger.reason.errors','Please fix the following errors:','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8492,0,'en','vendor/chatter','danger.reason.prevent_spam','In order to prevent spam, please allow at least :minutes in between submitting content.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8493,0,'en','vendor/chatter','danger.reason.trouble','Sorry, there seems to have been a problem submitting your response.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8494,0,'en','vendor/chatter','danger.reason.update_post','Nah ah ah... Could not update your response. Make sure you\'re not doing anything shady.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8495,0,'en','vendor/chatter','danger.reason.destroy_post','Nah ah ah... Could not delete the response. Make sure you\'re not doing anything shady.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8496,0,'en','vendor/chatter','danger.reason.create_discussion','Whoops :( There seems to be a problem creating your discussion.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8497,0,'en','vendor/chatter','danger.reason.title_required','Please write a title','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8498,0,'en','vendor/chatter','danger.reason.title_min','The title has to have at least :min characters.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8499,0,'en','vendor/chatter','danger.reason.title_max','The title has to have no more than :max characters.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8500,0,'en','vendor/chatter','danger.reason.content_required','Please write some content','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8501,0,'en','vendor/chatter','danger.reason.content_min','The content has to have at least :min characters','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8502,0,'en','vendor/chatter','danger.reason.category_required','Please choose a category','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8503,0,'en','vendor/chatter','preheader','Just wanted to let you know that someone has responded to a forum post.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8504,0,'en','vendor/chatter','greeting','Hi there,','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8505,0,'en','vendor/chatter','body','Just wanted to let you know that someone has responded to a forum post at','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8506,0,'en','vendor/chatter','view_discussion','View the discussion.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8507,0,'en','vendor/chatter','farewell','Have a great day!','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8508,0,'en','vendor/chatter','unsuscribe.message','If you no longer wish to be notified when someone responds to this form post be sure to uncheck the notification setting at the bottom of the page :)','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8509,0,'en','vendor/chatter','unsuscribe.action','Don\'t like these emails?','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8510,0,'en','vendor/chatter','unsuscribe.link','Unsubscribe to this discussion.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8511,0,'en','vendor/chatter','titles.discussion','Discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8512,0,'en','vendor/chatter','titles.discussions','Discussions','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8513,0,'en','vendor/chatter','headline','Welcome to Chatter','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8514,0,'en','vendor/chatter','description','A simple forum package for your Laravel app.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8515,0,'en','vendor/chatter','words.cancel','Cancel','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8516,0,'en','vendor/chatter','words.delete','Delete','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8517,0,'en','vendor/chatter','words.edit','Edit','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8518,0,'en','vendor/chatter','words.yes','Yes','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8519,0,'en','vendor/chatter','words.no','No','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8520,0,'en','vendor/chatter','words.minutes','1 minute| :count minutes','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8521,0,'en','vendor/chatter','discussion.new','New Discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8522,0,'en','vendor/chatter','discussion.all','All Discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8523,0,'en','vendor/chatter','discussion.create','Create Discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8524,0,'en','vendor/chatter','discussion.posted_by','Posted by','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8525,0,'en','vendor/chatter','discussion.head_details','Posted in Category','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8526,0,'en','vendor/chatter','response.confirm','Are you sure you want to delete this response?','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8527,0,'en','vendor/chatter','response.yes_confirm','Yes Delete It','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8528,0,'en','vendor/chatter','response.no_confirm','No Thanks','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8529,0,'en','vendor/chatter','response.submit','Submit response','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8530,0,'en','vendor/chatter','response.update','Update Response','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8531,0,'en','vendor/chatter','editor.title','Title of Discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8532,0,'en','vendor/chatter','editor.select','Select a Category','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8533,0,'en','vendor/chatter','editor.tinymce_placeholder','Type Your Discussion Here...','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8534,0,'en','vendor/chatter','editor.select_color_text','Select a Color for this Discussion (optional)','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8535,0,'en','vendor/chatter','email.notify','Notify me when someone replies','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8536,0,'en','vendor/chatter','auth','Please <a href=\"/:home/login\">login</a>\n                or <a href=\"/:home/register\">register</a>\n                to leave a response.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8537,0,'es','vendor/chatter','success.title','¡Bien hecho!','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8538,0,'es','vendor/chatter','success.reason.submitted_to_post','Respuesta enviada correctamente a la discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8539,0,'es','vendor/chatter','success.reason.updated_post','Discussion actualizada correctamente.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8540,0,'es','vendor/chatter','success.reason.destroy_post','Se ha borrado correctamente la respuesta y la discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8541,0,'es','vendor/chatter','success.reason.destroy_from_discussion','Se ha borrado correctamente la respuesta de la discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8542,0,'es','vendor/chatter','success.reason.created_discussion','Se ha creado correctamente una nueva discussion','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8543,0,'es','vendor/chatter','info.title','¡Aviso!','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8544,0,'es','vendor/chatter','warning.title','¡Precaución!','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8545,0,'es','vendor/chatter','danger.title','¡Ha ocurrido un error!','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8546,0,'es','vendor/chatter','danger.reason.errors','Por favor corrige los siguientes errores:','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8547,0,'es','vendor/chatter','danger.reason.prevent_spam','Con el fin de prevenir el SPAM, podrás volver a enviar el contenido nuevamente en :minutes','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8548,0,'es','vendor/chatter','danger.reason.trouble','Parece que ha ocurrido un problema al intentar enviar la respuesta, vuelve a intentarlo más tarde.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8549,0,'es','vendor/chatter','danger.reason.update_post','¡Oh! No se ha podido actualizar la respuesta.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8550,0,'es','vendor/chatter','danger.reason.destroy_post','¡Oh! No se ha podido borrar la respuesta.','2020-08-21 01:05:06','2020-10-12 05:00:42'),(8551,0,'es','vendor/chatter','danger.reason.create_discussion','¡Ups! Parece que hay un problema al crear la discussion. :(','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8552,0,'es','vendor/chatter','danger.reason.title_required','Por favor escribe un título','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8553,0,'es','vendor/chatter','danger.reason.title_min','El título debe tener al menos :min caracteres.','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8554,0,'es','vendor/chatter','danger.reason.title_max','El título no debe superar los :max caracteres.','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8555,0,'es','vendor/chatter','danger.reason.content_required','Es necesario escribir algo en el contenido','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8556,0,'es','vendor/chatter','danger.reason.content_min','El contenido debe tener al menos :min caracteres','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8557,0,'es','vendor/chatter','danger.reason.category_required','Por favor selecciona una categoría','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8558,0,'es','vendor/chatter','preheader','Este texto es como un encabezado. Algunos clientes muestran este texto como una vista previa.','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8559,0,'es','vendor/chatter','greeting','Hola,','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8560,0,'es','vendor/chatter','body','Te informamos que alguien ha respondido a una discussion publicada en','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8561,0,'es','vendor/chatter','view_discussion','Ver la discussion','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8562,0,'es','vendor/chatter','farewell','Que tengas un buen día.','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8563,0,'es','vendor/chatter','unsuscribe.message','Si ya no deseas ser notificado cuando alguien más responda, asegurate de desmarcar la configuración de notificación al final de la página :)','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8564,0,'es','vendor/chatter','unsuscribe.action','¿No le gustan estos correos electrónicos?','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8565,0,'es','vendor/chatter','unsuscribe.link','Anular la suscripción a la discussion','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8566,0,'es','vendor/chatter','titles.discussion','Discusión','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8567,0,'es','vendor/chatter','titles.discussions','Discusiones','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8568,0,'es','vendor/chatter','headline','Bienvenidos a Chatter','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8569,0,'es','vendor/chatter','description','Un foro con un simple librería para Laravel','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8570,0,'es','vendor/chatter','words.cancel','Cancelar','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8571,0,'es','vendor/chatter','words.delete','Borrar','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8572,0,'es','vendor/chatter','words.edit','Editar','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8573,0,'es','vendor/chatter','words.yes','Si','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8574,0,'es','vendor/chatter','words.no','No','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8575,0,'es','vendor/chatter','words.minutes','1 minuto| :count minutos','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8576,0,'es','vendor/chatter','discussion.new','Nueva discussion','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8577,0,'es','vendor/chatter','discussion.all','Todas las discussions','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8578,0,'es','vendor/chatter','discussion.create','Crear una discussion','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8579,0,'es','vendor/chatter','discussion.posted_by','Publicado por','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8580,0,'es','vendor/chatter','discussion.head_details','Publicado en categoria','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8581,0,'es','vendor/chatter','response.confirm','¿Estás seguro de que quieres borrar la respuesta?','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8582,0,'es','vendor/chatter','response.yes_confirm','Si, borrar','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8583,0,'es','vendor/chatter','response.no_confirm','No gracias','2020-08-21 01:05:07','2020-10-12 05:00:42'),(8584,0,'es','vendor/chatter','response.submit','Enviar respuesta','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8585,0,'es','vendor/chatter','response.update','Actualizar respuesta','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8586,0,'es','vendor/chatter','editor.title','Titulo de la discussion','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8587,0,'es','vendor/chatter','editor.select','Selecciona una categoria','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8588,0,'es','vendor/chatter','editor.tinymce_placeholder','Agrega el contenido para la discussion aquí...','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8589,0,'es','vendor/chatter','editor.select_color_text','Selecciona un color para la discussion (opcional)','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8590,0,'es','vendor/chatter','email.notify','Notificarme cuando alguien conteste en la discussion','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8591,0,'es','vendor/chatter','auth','Por favor <a href=\"/:home/login\">Inicia sesión</a>\n                o <a href=\"/:home/register\">Regístrate</a>\n                para dejar una respuesta.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8592,0,'fr','vendor/chatter','success.title','¡Bien hecho!','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8593,0,'fr','vendor/chatter','success.reason.submitted_to_post','Respuesta enviada correctamente a la discussion','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8594,0,'fr','vendor/chatter','success.reason.updated_post','Discussion actualizada correctamente.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8595,0,'fr','vendor/chatter','success.reason.destroy_post','Se ha borrado correctamente la respuesta y la discussion','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8596,0,'fr','vendor/chatter','success.reason.destroy_from_discussion','Se ha borrado correctamente la respuesta de la discussion','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8597,0,'fr','vendor/chatter','success.reason.created_discussion','Se ha creado correctamente una nueva discussion','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8598,0,'fr','vendor/chatter','info.title','¡Aviso!','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8599,0,'fr','vendor/chatter','warning.title','¡Precaución!','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8600,0,'fr','vendor/chatter','danger.title','¡Ha ocurrido un error!','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8601,0,'fr','vendor/chatter','danger.reason.errors','Por favor corrige los siguientes errores:','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8602,0,'fr','vendor/chatter','danger.reason.prevent_spam','Con el fin de prevenir el SPAM, podrás volver a enviar el contenido nuevamente en :minutes','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8603,0,'fr','vendor/chatter','danger.reason.trouble','Parece que ha ocurrido un problema al intentar enviar la respuesta, vuelve a intentarlo más tarde.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8604,0,'fr','vendor/chatter','danger.reason.update_post','¡Oh! No se ha podido actualizar la respuesta.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8605,0,'fr','vendor/chatter','danger.reason.destroy_post','¡Oh! No se ha podido borrar la respuesta.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8606,0,'fr','vendor/chatter','danger.reason.create_discussion','¡Ups! Parece que hay un problema al crear la discussion. :(','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8607,0,'fr','vendor/chatter','danger.reason.title_required','Por favor escribe un título','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8608,0,'fr','vendor/chatter','danger.reason.title_min','El título debe tener al menos :min caracteres.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8609,0,'fr','vendor/chatter','danger.reason.title_max','El título no debe superar los :max caracteres.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8610,0,'fr','vendor/chatter','danger.reason.content_required','Es necesario escribir algo en el contenido','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8611,0,'fr','vendor/chatter','danger.reason.content_min','El contenido debe tener al menos :min caracteres','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8612,0,'fr','vendor/chatter','danger.reason.category_required','Por favor selecciona una categoría','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8613,0,'fr','vendor/chatter','preheader','Este texto es como un encabezado. Algunos clientes muestran este texto como una vista previa.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8614,0,'fr','vendor/chatter','greeting','Hola,','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8615,0,'fr','vendor/chatter','body','Te informamos que alguien ha respondido a una discussion publicada en','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8616,0,'fr','vendor/chatter','view_discussion','Ver la discussion','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8617,0,'fr','vendor/chatter','farewell','Que tengas un buen día.','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8618,0,'fr','vendor/chatter','unsuscribe.message','Si ya no deseas ser notificado cuando alguien más responda, asegurate de desmarcar la configuración de notificación al final de la página :)','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8619,0,'fr','vendor/chatter','unsuscribe.action','¿No le gustan estos correos electrónicos?','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8620,0,'fr','vendor/chatter','unsuscribe.link','Anular la suscripción a la discussion','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8621,0,'fr','vendor/chatter','titles.discussion','Discusión','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8622,0,'fr','vendor/chatter','titles.discussions','Discusiones','2020-08-21 01:05:07','2020-10-12 05:00:43'),(8623,0,'fr','vendor/chatter','headline','Bienvenidos a Chatter','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8624,0,'fr','vendor/chatter','description','Un foro con un simple librería para Laravel','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8625,0,'fr','vendor/chatter','words.cancel','Cancelar','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8626,0,'fr','vendor/chatter','words.delete','Borrar','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8627,0,'fr','vendor/chatter','words.edit','Editar','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8628,0,'fr','vendor/chatter','words.yes','Si','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8629,0,'fr','vendor/chatter','words.no','No','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8630,0,'fr','vendor/chatter','words.minutes','1 minuto| :count minutos','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8631,0,'fr','vendor/chatter','discussion.new','Nueva discussion','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8632,0,'fr','vendor/chatter','discussion.all','Todas las discussions','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8633,0,'fr','vendor/chatter','discussion.create','Crear una discussion','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8634,0,'fr','vendor/chatter','discussion.posted_by','Publicado por','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8635,0,'fr','vendor/chatter','discussion.head_details','Publicado en categoria','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8636,0,'fr','vendor/chatter','response.confirm','¿Estás seguro de que quieres borrar la respuesta?','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8637,0,'fr','vendor/chatter','response.yes_confirm','Si, borrar','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8638,0,'fr','vendor/chatter','response.no_confirm','No gracias','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8639,0,'fr','vendor/chatter','response.submit','Enviar respuesta','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8640,0,'fr','vendor/chatter','response.update','Actualizar respuesta','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8641,0,'fr','vendor/chatter','editor.title','Titulo de la discussion','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8642,0,'fr','vendor/chatter','editor.select','Selecciona una categoria','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8643,0,'fr','vendor/chatter','editor.tinymce_placeholder','Agrega el contenido para la discussion aquí...','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8644,0,'fr','vendor/chatter','editor.select_color_text','Selecciona un color para la discussion (opcional)','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8645,0,'fr','vendor/chatter','email.notify','Notificarme cuando alguien conteste en la discussion','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8646,0,'fr','vendor/chatter','auth','Por favor <a href=\"/:home/login\">Inicia sesión</a>\n                o <a href=\"/:home/register\">Regístrate</a>\n                para dejar una respuesta.','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8647,0,'ar','vendor/cookieConsent','message','سيتم تحسين تجربتك في هذا الموقع من خلال السماح بملفات تعريف الارتباط.','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8648,0,'ar','vendor/cookieConsent','agree','السماح للكوكيز','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8649,0,'en','vendor/cookieConsent','message','Your experience on this site will be improved by allowing cookies.','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8650,0,'en','vendor/cookieConsent','agree','Allow cookies','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8651,0,'es','vendor/cookieConsent','message','Su experiencia en este sitio será mejorada con el uso de cookies.','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8652,0,'es','vendor/cookieConsent','agree','Aceptar','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8653,0,'fr','vendor/cookieConsent','message','Ce site nécessite l\'autorisation de cookies pour fonctionner correctement.','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8654,0,'fr','vendor/cookieConsent','agree','Accepter','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8655,0,'ar','vendor/log-viewer','all','الكل','2020-08-21 01:05:08','2020-08-21 01:05:08'),(8656,0,'ar','vendor/log-viewer','date','تاريخ','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8657,0,'ar','vendor/log-viewer','emergency','حالة طوارئ','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8658,0,'ar','vendor/log-viewer','alert','محزر','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8659,0,'ar','vendor/log-viewer','critical','حرج','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8660,0,'ar','vendor/log-viewer','error','خطأ','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8661,0,'ar','vendor/log-viewer','warning','تحذير','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8662,0,'ar','vendor/log-viewer','notice','تنويه','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8663,0,'ar','vendor/log-viewer','info','معلومات','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8664,0,'ar','vendor/log-viewer','debug','التصحيح','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8665,0,'en','vendor/log-viewer','all','All','2020-08-21 01:05:08','2020-08-21 01:05:08'),(8666,0,'en','vendor/log-viewer','date','Date','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8667,0,'en','vendor/log-viewer','emergency','Emergency','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8668,0,'en','vendor/log-viewer','alert','Alert','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8669,0,'en','vendor/log-viewer','critical','Critical','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8670,0,'en','vendor/log-viewer','error','Error','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8671,0,'en','vendor/log-viewer','warning','Warning','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8672,0,'en','vendor/log-viewer','notice','Notice','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8673,0,'en','vendor/log-viewer','info','Info','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8674,0,'en','vendor/log-viewer','debug','Debug','2020-08-21 01:05:08','2020-10-12 05:00:43'),(8675,0,'es','vendor/log-viewer','all','Todos','2020-08-21 01:05:09','2020-08-21 01:05:09'),(8676,0,'es','vendor/log-viewer','date','Fecha','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8677,0,'es','vendor/log-viewer','emergency','Emergencia','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8678,0,'es','vendor/log-viewer','alert','Alerta','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8679,0,'es','vendor/log-viewer','critical','Criticos','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8680,0,'es','vendor/log-viewer','error','Errores','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8681,0,'es','vendor/log-viewer','warning','Advertencia','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8682,0,'es','vendor/log-viewer','notice','Aviso','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8683,0,'es','vendor/log-viewer','info','Info','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8684,0,'es','vendor/log-viewer','debug','Debug','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8685,1,'fr','vendor/log-viewer','all','Tous','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8686,0,'fr','vendor/log-viewer','date','Date','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8687,0,'fr','vendor/log-viewer','emergency','Urgence','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8688,0,'fr','vendor/log-viewer','alert','Alerte','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8689,0,'fr','vendor/log-viewer','critical','Critique','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8690,0,'fr','vendor/log-viewer','error','Erreur','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8691,0,'fr','vendor/log-viewer','warning','Avertissement','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8692,0,'fr','vendor/log-viewer','notice','Notice','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8693,0,'fr','vendor/log-viewer','info','Info','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8694,0,'fr','vendor/log-viewer','debug','Debug','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8695,0,'ar','vendor/read-time','reads_left_to_right','1','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8696,0,'ar','vendor/read-time','min','دقيقة','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8697,0,'ar','vendor/read-time','minute','اللحظة','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8698,0,'ar','vendor/read-time','sec','ثانية','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8699,0,'ar','vendor/read-time','second','ثانيا','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8700,0,'ar','vendor/read-time','read','اقرأ','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8701,0,'en','vendor/read-time','reads_left_to_right','1','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8702,0,'en','vendor/read-time','min','min','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8703,0,'en','vendor/read-time','minute','minute','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8704,0,'en','vendor/read-time','sec','sec','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8705,0,'en','vendor/read-time','second','second','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8706,0,'en','vendor/read-time','read','read','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8707,0,'es','vendor/read-time','reads_left_to_right','1','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8708,0,'es','vendor/read-time','min','min','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8709,0,'es','vendor/read-time','minute','minuto','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8710,0,'es','vendor/read-time','sec','seg','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8711,0,'es','vendor/read-time','second','segundo','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8712,0,'es','vendor/read-time','read','leer','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8713,0,'fr','vendor/read-time','reads_left_to_right','1','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8714,0,'fr','vendor/read-time','min','min','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8715,0,'fr','vendor/read-time','minute','minute','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8716,0,'fr','vendor/read-time','sec','sec','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8717,0,'fr','vendor/read-time','second','seconde','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8718,0,'fr','vendor/read-time','read','lire','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8719,0,'en','vendor/self-diagnosis','app_key_is_set.message','The application key is not set. Call \"php artisan key:generate\" to create and set one.','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8720,0,'en','vendor/self-diagnosis','app_key_is_set.name','App key is set','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8721,0,'en','vendor/self-diagnosis','composer_with_dev_dependencies_is_up_to_date.message','Your composer dependencies are not up to date. Call \"composer install\" to update them. :more','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8722,0,'en','vendor/self-diagnosis','composer_with_dev_dependencies_is_up_to_date.name','Composer dependencies (including dev) are up to date','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8723,0,'en','vendor/self-diagnosis','composer_without_dev_dependencies_is_up_to_date.message','Your composer dependencies are not up to date. Call \"composer install\" to update them. :more','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8724,0,'en','vendor/self-diagnosis','composer_without_dev_dependencies_is_up_to_date.name','Composer dependencies (without dev) are up to date','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8725,0,'en','vendor/self-diagnosis','configuration_is_cached.message','Your configuration should be cached in production for better performance. Call \"php artisan config:cache\" to create the configuration cache.','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8726,0,'en','vendor/self-diagnosis','configuration_is_cached.name','Configuration is cached','2020-08-21 01:05:09','2020-10-12 05:00:43'),(8727,0,'en','vendor/self-diagnosis','configuration_is_not_cached.message','Your configuration files should not be cached during development. Call \"php artisan config:clear\" to clear the configuration cache.','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8728,0,'en','vendor/self-diagnosis','configuration_is_not_cached.name','Configuration is not cached','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8729,0,'en','vendor/self-diagnosis','correct_php_version_is_installed.message','You do not have the required PHP version installed.\nRequired: :required\nUsed: :used','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8730,0,'en','vendor/self-diagnosis','correct_php_version_is_installed.name','The correct PHP version is installed','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8731,0,'en','vendor/self-diagnosis','database_can_be_accessed.message','The database can not be accessed: :error','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8732,0,'en','vendor/self-diagnosis','database_can_be_accessed.name','The database can be accessed','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8733,0,'en','vendor/self-diagnosis','debug_mode_is_not_enabled.message','You should not use debug mode in production. Set \"APP_DEBUG\" in the .env file to \"false\".','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8734,0,'en','vendor/self-diagnosis','debug_mode_is_not_enabled.name','Debug mode is not enabled','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8735,0,'en','vendor/self-diagnosis','directories_have_correct_permissions.message','The following directories are not writable:\n:directories','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8736,0,'en','vendor/self-diagnosis','directories_have_correct_permissions.name','All directories have the correct permissions','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8737,0,'en','vendor/self-diagnosis','env_file_exists.message','The .env file does not exist. Please copy your .env.example file as .env and adjust accordingly.','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8738,0,'en','vendor/self-diagnosis','env_file_exists.name','The environment file exists','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8739,0,'en','vendor/self-diagnosis','example_environment_variables_are_set.message','These environment variables are missing in your .env file, but are defined in your .env.example:\n:variables','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8740,0,'en','vendor/self-diagnosis','example_environment_variables_are_set.name','The example environment variables are set','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8741,0,'en','vendor/self-diagnosis','migrations_are_up_to_date.message.need_to_migrate','You need to update your database. Call \"php artisan migrate\" to run migrations.','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8742,0,'en','vendor/self-diagnosis','migrations_are_up_to_date.message.unable_to_check','Unable to check for migrations: :reason','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8743,0,'en','vendor/self-diagnosis','migrations_are_up_to_date.name','The migrations are up to date','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8744,0,'en','vendor/self-diagnosis','php_extensions_are_disabled.message','The following extensions are still enabled:\n:extensions','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8745,0,'en','vendor/self-diagnosis','php_extensions_are_disabled.name','Unwanted PHP extensions are disabled','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8746,0,'en','vendor/self-diagnosis','php_extensions_are_installed.message','The following extensions are missing:\n:extensions','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8747,0,'en','vendor/self-diagnosis','php_extensions_are_installed.name','The required PHP extensions are installed','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8748,0,'en','vendor/self-diagnosis','routes_are_cached.message','Your routes should be cached in production for better performance. Call \"php artisan route:cache\" to create the route cache.','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8749,0,'en','vendor/self-diagnosis','routes_are_cached.name','Routes are cached','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8750,0,'en','vendor/self-diagnosis','routes_are_not_cached.message','Your routes should not be cached during development. Call \"php artisan route:clear\" to clear the route cache.','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8751,0,'en','vendor/self-diagnosis','routes_are_not_cached.name','Routes are not cached','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8752,0,'en','vendor/self-diagnosis','storage_directory_is_linked.message','The storage directory is not linked. Use \"php artisan storage:link\" to create a symbolic link.','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8753,0,'en','vendor/self-diagnosis','storage_directory_is_linked.name','The storage directory is linked','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8754,0,'en','vendor/self-diagnosis','self_diagnosis.common_checks','Common Checks','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8755,0,'en','vendor/self-diagnosis','self_diagnosis.environment_specific_checks','Environment Specific Checks (:environment)','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8756,0,'en','vendor/self-diagnosis','self_diagnosis.failed_checks','The following checks failed:','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8757,0,'en','vendor/self-diagnosis','self_diagnosis.running_check','<fg=yellow>Running check :current/:max:</fg=yellow> :name...  ','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8758,0,'en','vendor/self-diagnosis','self_diagnosis.success','Good job, looks like you are all set up!','2020-08-21 01:05:10','2020-10-12 05:00:43'),(8759,0,'en','custom-menu','nav-menu.courses-events','Courses & Events','2020-10-12 05:00:15','2020-10-12 05:00:20'),(8760,0,'en','custom-menu','nav-menu.article-videos','Article & Videos','2020-10-12 05:00:15','2020-10-12 05:00:20'),(8761,0,'ar','labels','frontend.contact.send','إرسال رسالة الآن','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8762,0,'ar','labels','frontend.contact.name','Nom','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8763,0,'ar','labels','frontend.contact.email','Email','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8764,0,'ar','labels','frontend.contact.address','Adresse','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8765,0,'ar','labels','frontend.home.search','بحث دورة','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8766,0,'ar','labels','frontend.layouts.partials.get_in_touch_text','ابقى على تواصل','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8767,0,'ar','labels','frontend.layouts.partials.subscribe','إشترك الآن','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8768,0,'ar','labels','frontend.modal.login','تسجيل الدخول الآن','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8769,0,'ar','labels','frontend.modal.login_with','Login With','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8770,0,'ar','labels','frontend.modal.signup_with','Sign Up With','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8771,0,'ar','labels','frontend.modal.signup','Sign Up','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8772,1,'ar','labels','frontend.modal.keep_signin','Kepp me Signed in','2020-10-12 05:00:17','2020-10-12 05:00:17'),(8773,0,'ar','labels','frontend.modal.enter_y_username','Enter your username','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8774,0,'ar','labels','frontend.modal.enter_y_password','Enter your password','2020-10-12 05:00:17','2020-11-26 06:00:23'),(8775,0,'ar','navs','general.register','تسجيل','2020-10-12 05:00:19','2020-11-26 06:00:24'),(8776,0,'en','alerts','frontend.course.slot_booking','Live lesson slot booking successfully','2020-10-12 05:00:20','2020-11-26 06:00:24'),(8777,0,'en','labels','backend.general_settings.admin_registration_mail','Registration Mail','2020-10-12 05:00:21','2020-11-26 06:00:25'),(8778,0,'en','labels','backend.general_settings.admin_registration_mail_note','Enable / Disable if admin will be able to received new registration user mail','2020-10-12 05:00:21','2020-11-26 06:00:25'),(8779,0,'en','labels','backend.general_settings.admin_order_mail','Order Mail','2020-10-12 05:00:21','2020-11-26 06:00:25'),(8780,0,'en','labels','backend.general_settings.admin_order_mail_note','Enable / Disable if admin will be able to received new order mail','2020-10-12 05:00:21','2020-11-26 06:00:25'),(8781,0,'en','labels','backend.live_lessons.title','Live Lesson','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8782,0,'en','labels','backend.live_lessons.create','Create Live Lesson','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8783,0,'en','labels','backend.live_lessons.edit','Edit Live Lesson','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8784,0,'en','labels','backend.live_lessons.view','View Lesson','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8785,0,'en','labels','backend.live_lessons.select_course','Select Course','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8786,0,'en','labels','backend.live_lessons.short_description_placeholder','Input short description of live lesson','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8787,0,'en','labels','backend.live_lessons.fields.course','Course','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8788,0,'en','labels','backend.live_lessons.fields.title','Title','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8789,0,'en','labels','backend.live_lessons.fields.short_text','Sort Description','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8790,0,'en','labels','backend.live_lesson_slots.title','Live Lesson Slots','2020-10-12 05:00:22','2020-11-26 06:00:25'),(8791,0,'en','labels','backend.live_lesson_slots.select_lesson','Select Lesson','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8792,0,'en','labels','backend.live_lesson_slots.create','Create Live Lesson Slot','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8793,0,'en','labels','backend.live_lesson_slots.edit','Edit Live Lesson Slot','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8794,0,'en','labels','backend.live_lesson_slots.view','View Slot','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8795,0,'en','labels','backend.live_lesson_slots.slot','Slot','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8796,0,'en','labels','backend.live_lesson_slots.short_description_placeholder','Input short description of slot','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8797,0,'en','labels','backend.live_lesson_slots.start_url','Start URL','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8798,0,'en','labels','backend.live_lesson_slots.slot_booked_student_list','Slot Booked Student List','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8799,0,'en','labels','backend.live_lesson_slots.student_name','Student Name','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8800,0,'en','labels','backend.live_lesson_slots.student_email','Student Email','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8801,0,'en','labels','backend.live_lesson_slots.closed','Closed','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8802,0,'en','labels','backend.live_lesson_slots.type.select_type','Select Type','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8803,0,'en','labels','backend.live_lesson_slots.type.daily','Daily','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8804,0,'en','labels','backend.live_lesson_slots.type.weekly','Weekly','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8805,0,'en','labels','backend.live_lesson_slots.type.monthly','Monthly','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8806,0,'en','labels','backend.live_lesson_slots.fields.lesson','Lesson','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8807,0,'en','labels','backend.live_lesson_slots.fields.topic','Topic','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8808,0,'en','labels','backend.live_lesson_slots.fields.short_text','Sort Description','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8809,0,'en','labels','backend.live_lesson_slots.fields.date_of_slot','Date','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8810,0,'en','labels','backend.live_lesson_slots.fields.duration','Duration(in minutes)','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8811,0,'en','labels','backend.live_lesson_slots.fields.meeting_id','Meeting ID','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8812,0,'en','labels','backend.live_lesson_slots.fields.date','Date','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8813,0,'en','labels','backend.live_lesson_slots.fields.password','Password','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8814,0,'en','labels','backend.live_lesson_slots.fields.change_default_setting','Change Default Setting','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8815,0,'en','labels','backend.live_lesson_slots.fields.student_limit','Student Limit','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8816,0,'en','labels','backend.zoom_setting.management','Zoom Setting','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8817,0,'en','labels','backend.zoom_setting.audio_options.both','Both','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8818,0,'en','labels','backend.zoom_setting.audio_options.voip','VoIP','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8819,0,'en','labels','backend.zoom_setting.audio_options.telephony','Telephony','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8820,0,'en','labels','backend.zoom_setting.meeting_approval_options.automatically','Automatically','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8821,0,'en','labels','backend.zoom_setting.meeting_approval_options.manually','Manually','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8822,0,'en','labels','backend.zoom_setting.meeting_approval_options.no_registration_required','No Registration Required','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8823,0,'en','labels','backend.zoom_setting.auto_recording_options.none','None','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8824,0,'en','labels','backend.zoom_setting.auto_recording_options.local','Local','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8825,0,'en','labels','backend.zoom_setting.auto_recording_options.cloud','Cloud','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8826,0,'en','labels','backend.zoom_setting.fields.api_key','API Key','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8827,0,'en','labels','backend.zoom_setting.fields.api_secret','Secret Key','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8828,0,'en','labels','backend.zoom_setting.fields.join_before_host','Join Before Host','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8829,0,'en','labels','backend.zoom_setting.fields.host_video','Host Video','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8830,0,'en','labels','backend.zoom_setting.fields.participant_video','Participant Video','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8831,0,'en','labels','backend.zoom_setting.fields.participant_mic_mute','Participant Mic Mute','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8832,0,'en','labels','backend.zoom_setting.fields.waiting_room','Waiting Room','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8833,0,'en','labels','backend.zoom_setting.fields.audio_option','Audio Option','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8834,0,'en','labels','backend.zoom_setting.fields.meeting_join_approval','Meeting Join Approval','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8835,0,'en','labels','backend.zoom_setting.fields.auto_recording','Auto Recording','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8836,0,'en','labels','backend.zoom_setting.fields.timezone','Timezone','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8837,0,'en','labels','frontend.contact.send','Send','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8838,0,'en','labels','frontend.contact.name','Name','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8839,0,'en','labels','frontend.contact.email','Email','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8840,0,'en','labels','frontend.contact.address','Address','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8841,0,'en','labels','frontend.course.live_lesson','Live Lessons','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8842,0,'en','labels','frontend.course.slot','Slot','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8843,0,'en','labels','frontend.course.available_slots','Available Slots','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8844,0,'en','labels','frontend.course.date','Date','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8845,0,'en','labels','frontend.course.live_lesson_join_url','Join URL','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8846,0,'en','labels','frontend.course.live_lesson_meeting_password','Password','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8847,0,'en','labels','frontend.course.live_lesson_meeting_date','Meeting Date','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8848,0,'en','labels','frontend.course.live_lesson_meeting_id','Meeting ID','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8849,0,'en','labels','frontend.course.live_lesson_meeting_duration','Durations','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8850,0,'en','labels','frontend.course.book_slot','Book a Slot','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8851,0,'en','labels','frontend.course.full_slot','Slot is full','2020-10-12 05:00:22','2020-11-26 06:00:26'),(8852,0,'en','labels','frontend.home.search','Search','2020-10-12 05:00:22','2020-11-26 06:00:27'),(8853,0,'en','labels','frontend.layouts.partials.get_in_touch_text','Get In <span>Touch</span>','2020-10-12 05:00:22','2020-11-26 06:00:27'),(8854,0,'en','labels','frontend.layouts.partials.subscribe','Subscribe','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8855,0,'en','labels','frontend.modal.login','Login','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8856,0,'en','labels','frontend.modal.login_with','Login With','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8857,0,'en','labels','frontend.modal.signup_with','Sign Up With','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8858,0,'en','labels','frontend.modal.signup','Sign Up','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8859,1,'en','labels','frontend.modal.keep_signin','Kepp me Signed in','2020-10-12 05:00:23','2020-10-12 05:00:23'),(8860,0,'en','labels','frontend.modal.enter_y_username','Enter your username','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8861,0,'en','labels','frontend.modal.enter_y_password','Enter your password','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8862,0,'en','menus','backend.sidebar.settings.zoom_setting','Zoom Setting','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8863,0,'en','menus','backend.sidebar.live_lessons.title','Live Lessons','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8864,0,'en','menus','backend.sidebar.live_lesson_slots.title','Live Lesson Slots','2020-10-12 05:00:23','2020-11-26 06:00:27'),(8865,0,'en','navs','general.register','Register','2020-10-12 05:00:24','2020-11-26 06:00:28'),(8866,0,'es','labels','frontend.contact.send','Enviar','2020-10-12 05:00:28','2020-11-26 06:00:31'),(8867,0,'es','labels','frontend.contact.name','Nombre','2020-10-12 05:00:28','2020-11-26 06:00:31'),(8868,0,'es','labels','frontend.contact.email','Correo electrónico','2020-10-12 05:00:28','2020-11-26 06:00:31'),(8869,0,'es','labels','frontend.contact.address','Adresse','2020-10-12 05:00:28','2020-11-26 06:00:31'),(8870,0,'es','labels','frontend.home.search','Buscar','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8871,0,'es','labels','frontend.layouts.partials.get_in_touch_text','Estar en <span>contacto</span>','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8872,0,'es','labels','frontend.layouts.partials.subscribe','Suscríbase','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8873,0,'es','labels','frontend.modal.login','Inicia sesión','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8874,0,'es','labels','frontend.modal.login_with','Inicia sesión With','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8875,0,'es','labels','frontend.modal.signup_with','Regístrate With','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8876,0,'es','labels','frontend.modal.signup','Regístrate','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8877,1,'es','labels','frontend.modal.keep_signin','Kepp me Signed in','2020-10-12 05:00:29','2020-10-12 05:00:29'),(8878,0,'es','labels','frontend.modal.enter_y_username','Enter your username','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8879,0,'es','labels','frontend.modal.enter_y_password','Enter your password','2020-10-12 05:00:29','2020-11-26 06:00:32'),(8880,0,'es','navs','general.register','Registro','2020-10-12 05:00:31','2020-11-26 06:00:33'),(8881,0,'fr','labels','frontend.contact.send','Envoyer','2020-10-12 05:00:36','2020-11-26 06:00:37'),(8882,0,'fr','labels','frontend.contact.name','Nom','2020-10-12 05:00:36','2020-11-26 06:00:37'),(8883,0,'fr','labels','frontend.contact.email','Email','2020-10-12 05:00:36','2020-11-26 06:00:37'),(8884,0,'fr','labels','frontend.contact.address','Adresse','2020-10-12 05:00:36','2020-11-26 06:00:37'),(8885,0,'fr','labels','frontend.home.search','Recherche','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8886,0,'fr','labels','frontend.layouts.partials.get_in_touch_text','Entrer en <span>contact</span>','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8887,0,'fr','labels','frontend.layouts.partials.subscribe','Abonnez-vous','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8888,0,'fr','labels','frontend.modal.login','Connecte-toi','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8889,0,'fr','labels','frontend.modal.login_with','Connecte-toi With','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8890,0,'fr','labels','frontend.modal.signup_with','Inscrire With','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8891,0,'fr','labels','frontend.modal.signup','Inscrire','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8892,1,'fr','labels','frontend.modal.keep_signin','Kepp me Signed in','2020-10-12 05:00:37','2020-10-12 05:00:37'),(8893,0,'fr','labels','frontend.modal.enter_y_username','Enter your username','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8894,0,'fr','labels','frontend.modal.enter_y_password','Enter your password','2020-10-12 05:00:37','2020-11-26 06:00:38'),(8895,1,'fr','navs','general.register','Registre','2020-10-12 05:00:40','2020-10-12 05:00:40'),(8896,0,'en','custom-menu','nav-menu.long-term-programs','Long Term Programs','2020-11-13 21:14:58','2020-11-26 06:00:22'),(8897,0,'en','custom-menu','nav-menu.store','Store','2020-11-13 21:14:58','2020-11-26 06:00:22'),(8898,0,'en','custom-menu','nav-menu.course','Course','2020-11-26 06:00:22','2020-11-26 06:00:24'),(8899,1,'ar','labels','backend.courses.fields.duration','Duration','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8900,1,'ar','labels','backend.courses.fields.skill_level','Skill Level','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8901,1,'ar','labels','backend.items.title','Store Items','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8902,1,'ar','labels','backend.items.fields.published','Published','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8903,1,'ar','labels','backend.items.fields.unpublished','Not Published','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8904,1,'ar','labels','backend.items.fields.featured','Featured','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8905,1,'ar','labels','backend.items.fields.free','Free','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8906,1,'ar','labels','backend.items.fields.trending','Trending','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8907,1,'ar','labels','backend.items.fields.popular','Popular','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8908,1,'ar','labels','backend.items.fields.category','Category','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8909,1,'ar','labels','backend.items.fields.title','Title','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8910,1,'ar','labels','backend.items.fields.slug','Slug','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8911,1,'ar','labels','backend.items.fields.description','Description','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8912,1,'ar','labels','backend.items.fields.price','Price','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8913,1,'ar','labels','backend.items.fields.discount','Discount','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8914,1,'ar','labels','backend.items.fields.discount_type','Discount Type','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8915,1,'ar','labels','backend.items.fields.stock_count','Stock Count','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8916,1,'ar','labels','backend.items.fields.item_image','Item Image','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8917,1,'ar','labels','backend.items.fields.start_date','Start Date','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8918,1,'ar','labels','backend.items.fields.duration','Duration','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8919,1,'ar','labels','backend.items.fields.meta_title','Meta Title','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8920,1,'ar','labels','backend.items.fields.meta_description','Meta Description','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8921,1,'ar','labels','backend.items.fields.meta_keywords','Meta Keywords','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8922,1,'ar','labels','backend.items.fields.sidebar','Add Sidebar','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8923,1,'ar','labels','backend.items.fields.status','Status','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8924,1,'ar','labels','backend.items.fields.quantity','Quantity','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8925,1,'ar','labels','backend.items.add_categories','Add Categories','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8926,1,'ar','labels','backend.items.slug_placeholder','Input slug or it will be generated automatically','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8927,1,'ar','labels','backend.items.select_category','Select Category','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8928,1,'ar','labels','backend.items.create','Create Item','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8929,1,'ar','labels','backend.items.edit','Edit Item','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8930,1,'ar','labels','backend.items.view','View Item','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8931,1,'ar','labels','backend.items.category','Category','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8932,1,'ar','labels','backend.dashboard.buy_item_now','Buy item now','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8933,1,'ar','labels','backend.dashboard.my_items','My Items','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8934,1,'ar','labels','backend.bundles.fields.duration','Duration','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8935,1,'ar','labels','backend.bundles.fields.skill_level','Skill Level','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8936,1,'ar','labels','general.no_search_results','No search results.','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8937,1,'ar','labels','frontend.article_video.share_this_news','Share this news','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8938,1,'ar','labels','frontend.article_video.related_news','<span>Related</span> News','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8939,1,'ar','labels','frontend.article_video.post_comments','Post <span>Comments.</span>','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8940,1,'ar','labels','frontend.article_video.write_a_comment','Write a Comment','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8941,1,'ar','labels','frontend.article_video.add_comment','Add Comment','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8942,1,'ar','labels','frontend.article_video.by','By','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8943,1,'ar','labels','frontend.article_video.title','Article & Videos','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8944,1,'ar','labels','frontend.article_video.search','Search','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8945,1,'ar','labels','frontend.article_video.categories','<span>Categories.</span>','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8946,1,'ar','labels','frontend.article_video.popular_tags','Popular <span>Tags.</span>','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8947,1,'ar','labels','frontend.article_video.featured_course','Featured <span>Course.</span>','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8948,1,'ar','labels','frontend.article_video.login_to_post_comment','Login to Post a Comment','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8949,1,'ar','labels','frontend.article_video.no_comments_yet','No comments yet, Be the first to comment.','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8950,1,'ar','labels','frontend.contact.phone','Phone','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8951,1,'ar','labels','frontend.course.teacher','Teacher','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8952,1,'ar','labels','frontend.course.ratings_reviews','Ratings & Reviews','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8953,1,'ar','labels','frontend.course.course_features','Course Features','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8954,1,'ar','labels','frontend.course.curriculum','Curriculum','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8955,1,'ar','labels','frontend.course.no_lesson','No lessons added for this course.','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8956,1,'ar','labels','frontend.course.lectures','Lectures','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8957,1,'ar','labels','frontend.course.quizzes','Quizzes','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8958,1,'ar','labels','frontend.course.duration','Duration','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8959,1,'ar','labels','frontend.course.skill_level','Skill Level','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8960,1,'ar','labels','frontend.course.language','Language','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8961,1,'ar','labels','frontend.course.certificate','Certificate','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8962,1,'ar','labels','frontend.course.assessments','Assessments','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8963,1,'ar','labels','frontend.course.past','Past','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8964,1,'ar','labels','frontend.course.upcoming','Upcoming','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8965,1,'ar','labels','frontend.course.recent_blog_view','Recent <span>Blogs</span>','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8966,1,'ar','labels','frontend.course.popular_course','Popular <span>Courses</span>','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8967,1,'ar','labels','frontend.course.filter_by','Filter By','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8968,1,'ar','menus','backend.sidebar.items.title','Store Item','2020-11-26 06:00:23','2020-11-26 06:00:23'),(8969,1,'ar','strings','backend.dashboard.my_items','My Items','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8970,1,'en','labels','backend.courses.fields.duration','Duration','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8971,1,'en','labels','backend.courses.fields.skill_level','Skill Level','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8972,1,'en','labels','backend.items.title','Store Items','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8973,1,'en','labels','backend.items.fields.published','Published','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8974,1,'en','labels','backend.items.fields.unpublished','Not Published','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8975,1,'en','labels','backend.items.fields.featured','Featured','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8976,1,'en','labels','backend.items.fields.free','Free','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8977,1,'en','labels','backend.items.fields.trending','Trending','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8978,1,'en','labels','backend.items.fields.popular','Popular','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8979,1,'en','labels','backend.items.fields.category','Category','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8980,1,'en','labels','backend.items.fields.title','Title','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8981,1,'en','labels','backend.items.fields.slug','Slug','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8982,1,'en','labels','backend.items.fields.description','Description','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8983,1,'en','labels','backend.items.fields.price','Price','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8984,1,'en','labels','backend.items.fields.discount','Discount','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8985,1,'en','labels','backend.items.fields.discount_type','Discount Type','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8986,1,'en','labels','backend.items.fields.stock_count','Stock Count','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8987,1,'en','labels','backend.items.fields.item_image','Item Image','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8988,1,'en','labels','backend.items.fields.start_date','Start Date','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8989,1,'en','labels','backend.items.fields.duration','Duration','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8990,1,'en','labels','backend.items.fields.meta_title','Meta Title','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8991,1,'en','labels','backend.items.fields.meta_description','Meta Description','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8992,1,'en','labels','backend.items.fields.meta_keywords','Meta Keywords','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8993,1,'en','labels','backend.items.fields.sidebar','Add Sidebar','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8994,1,'en','labels','backend.items.fields.status','Status','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8995,1,'en','labels','backend.items.fields.quantity','Quantity','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8996,1,'en','labels','backend.items.add_categories','Add Categories','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8997,1,'en','labels','backend.items.slug_placeholder','Input slug or it will be generated automatically','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8998,1,'en','labels','backend.items.select_category','Select Category','2020-11-26 06:00:24','2020-11-26 06:00:24'),(8999,1,'en','labels','backend.items.create','Create Item','2020-11-26 06:00:24','2020-11-26 06:00:24'),(9000,1,'en','labels','backend.items.edit','Edit Item','2020-11-26 06:00:24','2020-11-26 06:00:24'),(9001,1,'en','labels','backend.items.view','View Item','2020-11-26 06:00:24','2020-11-26 06:00:24'),(9002,1,'en','labels','backend.items.category','Category','2020-11-26 06:00:24','2020-11-26 06:00:24'),(9003,1,'en','labels','backend.messages.recent','Recent','2020-11-26 06:00:25','2020-11-26 06:00:25'),(9004,1,'en','labels','backend.dashboard.buy_item_now','Buy item now','2020-11-26 06:00:25','2020-11-26 06:00:25'),(9005,1,'en','labels','backend.dashboard.my_items','My Items','2020-11-26 06:00:25','2020-11-26 06:00:25'),(9006,1,'en','labels','backend.bundles.fields.duration','Duration','2020-11-26 06:00:25','2020-11-26 06:00:25'),(9007,1,'en','labels','backend.bundles.fields.skill_level','Skill Level','2020-11-26 06:00:25','2020-11-26 06:00:25'),(9008,1,'en','labels','general.no_search_results','No search results.','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9009,1,'en','labels','frontend.article_video.share_this_news','Share this news','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9010,1,'en','labels','frontend.article_video.related_news','<span>Related</span> News','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9011,1,'en','labels','frontend.article_video.post_comments','Post <span>Comments.</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9012,1,'en','labels','frontend.article_video.write_a_comment','Write a Comment','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9013,1,'en','labels','frontend.article_video.add_comment','Add Comment','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9014,1,'en','labels','frontend.article_video.by','By','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9015,1,'en','labels','frontend.article_video.title','Article & Videos','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9016,1,'en','labels','frontend.article_video.search','Search','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9017,1,'en','labels','frontend.article_video.categories','<span>Categories.</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9018,1,'en','labels','frontend.article_video.popular_tags','Popular <span>Tags.</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9019,1,'en','labels','frontend.article_video.featured_course','Featured <span>Course.</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9020,1,'en','labels','frontend.article_video.login_to_post_comment','Login to Post a Comment','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9021,1,'en','labels','frontend.article_video.no_comments_yet','No comments yet, Be the first to comment.','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9022,1,'en','labels','frontend.cart.your_cart','Your Cart','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9023,1,'en','labels','frontend.contact.phone','Phone','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9024,1,'en','labels','frontend.course.teacher','Teacher','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9025,1,'en','labels','frontend.course.curriculum','Curriculum','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9026,1,'en','labels','frontend.course.no_lesson','No lessons added for this course.','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9027,1,'en','labels','frontend.course.ratings_reviews','Ratings & Reviews','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9028,1,'en','labels','frontend.course.course_features','Course Features','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9029,1,'en','labels','frontend.course.lectures','Lectures','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9030,1,'en','labels','frontend.course.quizzes','Quizzes','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9031,1,'en','labels','frontend.course.duration','Duration','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9032,1,'en','labels','frontend.course.skill_level','Skill Level','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9033,1,'en','labels','frontend.course.language','Language','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9034,1,'en','labels','frontend.course.certificate','Certificate','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9035,1,'en','labels','frontend.course.assessments','Assessments','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9036,1,'en','labels','frontend.course.recent_blog_view','Recent <span>Blogs</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9037,1,'en','labels','frontend.course.popular_course','Popular <span>Courses</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9038,1,'en','labels','frontend.course.filter_by','Filter By','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9039,1,'en','labels','frontend.course.past','Past','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9040,1,'en','labels','frontend.course.upcoming','Upcoming','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9041,1,'en','labels','frontend.program.ratings','Ratings','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9042,1,'en','labels','frontend.program.stars','Stars','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9043,1,'en','labels','frontend.program.by','By','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9044,1,'en','labels','frontend.program.no_reviews_yet','No reviews yet.','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9045,1,'en','labels','frontend.program.add_to_cart','Add To Cart','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9046,1,'en','labels','frontend.program.buy_note','Only Students Can Buy Course','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9047,1,'en','labels','frontend.program.continue_course','Continue Course','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9048,1,'en','labels','frontend.program.enrolled','Enrolled','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9049,1,'en','labels','frontend.program.chapters','Chapters','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9050,1,'en','labels','frontend.program.category','Category','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9051,1,'en','labels','frontend.program.author','Author','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9052,1,'en','labels','frontend.program.teacher','Teacher','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9053,1,'en','labels','frontend.program.courses','Courses','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9054,1,'en','labels','frontend.program.students','Students','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9055,1,'en','labels','frontend.program.give_test_again','Give Test Again','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9056,1,'en','labels','frontend.program.submit_results','Submit Results','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9057,1,'en','labels','frontend.program.chapter_videos','Chapter Videos','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9058,1,'en','labels','frontend.program.download_files','Download Files','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9059,1,'en','labels','frontend.program.mb','MB','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9060,1,'en','labels','frontend.program.prev','PREV','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9061,1,'en','labels','frontend.program.test','Test','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9062,1,'en','labels','frontend.program.completed','Completed','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9063,1,'en','labels','frontend.program.title','Long Term Programs','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9064,1,'en','labels','frontend.program.course_details','<span>Course Details.</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9065,1,'en','labels','frontend.program.course_detail','Course Details','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9066,1,'en','labels','frontend.program.course_timeline','Course <b>Timeline:</b>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9067,1,'en','labels','frontend.program.curriculum','Curriculum','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9068,1,'en','labels','frontend.program.no_lesson','No lessons added for this course.','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9069,1,'en','labels','frontend.program.go','Go','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9070,1,'en','labels','frontend.program.course_reviews','Course <span>Reviews:</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9071,1,'en','labels','frontend.program.ratings_reviews','Ratings & Reviews','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9072,1,'en','labels','frontend.program.course_features','Course Features','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9073,1,'en','labels','frontend.program.lectures','Lectures','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9074,1,'en','labels','frontend.program.quizzes','Quizzes','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9075,1,'en','labels','frontend.program.duration','Duration','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9076,1,'en','labels','frontend.program.skill_level','Skill Level','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9077,1,'en','labels','frontend.program.language','Language','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9078,1,'en','labels','frontend.program.certificate','Certificate','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9079,1,'en','labels','frontend.program.assessments','Assessments','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9080,1,'en','labels','frontend.program.average_rating','Average Rating','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9081,1,'en','labels','frontend.program.details','Details','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9082,1,'en','labels','frontend.program.add_reviews','Add <span>Reviews.</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9083,1,'en','labels','frontend.program.your_rating','Your Rating','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9084,1,'en','labels','frontend.program.message','Message','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9085,1,'en','labels','frontend.program.add_review_now','Add Review Now','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9086,1,'en','labels','frontend.program.price','Price','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9087,1,'en','labels','frontend.program.added_to_cart','Added To Cart','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9088,1,'en','labels','frontend.program.buy_now','Buy Now','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9089,1,'en','labels','frontend.program.get_now','Get Now','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9090,1,'en','labels','frontend.program.recent_blog_view','Recent <span>Blogs</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9091,1,'en','labels','frontend.program.popular_course','Popular <span>Courses</span>','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9092,1,'en','labels','frontend.program.recent_news','<span>Recent  </span>News.','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9093,1,'en','labels','frontend.program.view_all_news','View All News','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9094,1,'en','labels','frontend.program.featured_course','<span>Featured</span> Course.','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9095,1,'en','labels','frontend.program.sort_by','<b>Sort</b> By','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9096,1,'en','labels','frontend.program.filter_by','Filter By','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9097,1,'en','labels','frontend.program.past','Past','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9098,1,'en','labels','frontend.program.upcoming','Upcoming','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9099,1,'en','labels','frontend.program.popular','Popular','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9100,1,'en','labels','frontend.program.none','None','2020-11-26 06:00:26','2020-11-26 06:00:26'),(9101,1,'en','labels','frontend.program.trending','Trending','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9102,1,'en','labels','frontend.program.featured','Featured','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9103,1,'en','labels','frontend.program.course_name','Course Name','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9104,1,'en','labels','frontend.program.course_type','Course Type','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9105,1,'en','labels','frontend.program.starts','Starts','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9106,1,'en','labels','frontend.program.full_text','FULL TEXT','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9107,1,'en','labels','frontend.program.find_courses','FIND COURSES','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9108,1,'en','labels','frontend.program.your_test_score','Your Test Score','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9109,1,'en','labels','frontend.program.find_your_course','<span>Find </span>Your Course.','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9110,1,'en','labels','frontend.program.next','NEXT','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9111,1,'en','labels','frontend.program.progress','Progress','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9112,1,'en','labels','frontend.program.finish_course','Finish Course','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9113,1,'en','labels','frontend.program.certified','You\'re Certified for this course','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9114,1,'en','labels','frontend.program.course','Course','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9115,1,'en','labels','frontend.program.bundles','Bundles','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9116,1,'en','labels','frontend.program.bundle_detail','Bundle Details','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9117,1,'en','labels','frontend.program.bundle_reviews','Bundle <span>Reviews:</span>','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9118,1,'en','labels','frontend.program.available_in_bundles','Also available in Bundles','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9119,1,'en','labels','frontend.program.complete_test','Please Complete Test','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9120,1,'en','labels','frontend.program.looking_for','Looking For?','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9121,1,'en','labels','frontend.program.not_attempted','Not Attempted','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9122,1,'en','labels','frontend.program.explanation','Explanation','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9123,1,'en','labels','frontend.program.find_your_bundle','<span>Find</span> your Bundle','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9124,1,'en','labels','frontend.program.select_category','Select Category','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9125,1,'en','labels','frontend.program.live_lesson','Live Lessons','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9126,1,'en','labels','frontend.program.slot','Slot','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9127,1,'en','labels','frontend.program.available_slots','Available Slots','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9128,1,'en','labels','frontend.program.date','Date','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9129,1,'en','labels','frontend.program.live_lesson_join_url','Join URL','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9130,1,'en','labels','frontend.program.live_lesson_meeting_password','Password','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9131,1,'en','labels','frontend.program.live_lesson_meeting_date','Meeting Date','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9132,1,'en','labels','frontend.program.live_lesson_meeting_id','Meeting ID','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9133,1,'en','labels','frontend.program.live_lesson_meeting_duration','Durations','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9134,1,'en','labels','frontend.program.book_slot','Book a Slot','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9135,1,'en','labels','frontend.program.full_slot','Slot is full','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9136,1,'en','labels','frontend.layouts.partials.browse_our','<span>Browse Our</span>','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9137,1,'en','labels','frontend.layouts.partials.featured_courses','Featured <span>Courses</span>','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9138,1,'en','labels','frontend.store.store','Store','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9139,1,'en','menus','backend.sidebar.items.title','Store Item','2020-11-26 06:00:27','2020-11-26 06:00:27'),(9140,1,'en','strings','backend.dashboard.my_items','My Items','2020-11-26 06:00:28','2020-11-26 06:00:28'),(9141,1,'es','labels','general.no_search_results','No search results.','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9142,1,'es','labels','backend.courses.fields.duration','Duration','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9143,1,'es','labels','backend.courses.fields.skill_level','Skill Level','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9144,1,'es','labels','backend.items.title','Store Items','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9145,1,'es','labels','backend.items.fields.published','Published','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9146,1,'es','labels','backend.items.fields.unpublished','Not Published','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9147,1,'es','labels','backend.items.fields.featured','Featured','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9148,1,'es','labels','backend.items.fields.free','Free','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9149,1,'es','labels','backend.items.fields.trending','Trending','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9150,1,'es','labels','backend.items.fields.popular','Popular','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9151,1,'es','labels','backend.items.fields.category','Category','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9152,1,'es','labels','backend.items.fields.title','Title','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9153,1,'es','labels','backend.items.fields.slug','Slug','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9154,1,'es','labels','backend.items.fields.description','Description','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9155,1,'es','labels','backend.items.fields.price','Price','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9156,1,'es','labels','backend.items.fields.discount','Discount','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9157,1,'es','labels','backend.items.fields.discount_type','Discount Type','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9158,1,'es','labels','backend.items.fields.stock_count','Stock Count','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9159,1,'es','labels','backend.items.fields.item_image','Item Image','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9160,1,'es','labels','backend.items.fields.start_date','Start Date','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9161,1,'es','labels','backend.items.fields.duration','Duration','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9162,1,'es','labels','backend.items.fields.meta_title','Meta Title','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9163,1,'es','labels','backend.items.fields.meta_description','Meta Description','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9164,1,'es','labels','backend.items.fields.meta_keywords','Meta Keywords','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9165,1,'es','labels','backend.items.fields.sidebar','Add Sidebar','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9166,1,'es','labels','backend.items.fields.status','Status','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9167,1,'es','labels','backend.items.fields.quantity','Quantity','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9168,1,'es','labels','backend.items.add_categories','Add Categories','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9169,1,'es','labels','backend.items.slug_placeholder','Input slug or it will be generated automatically','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9170,1,'es','labels','backend.items.select_category','Select Category','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9171,1,'es','labels','backend.items.create','Create Item','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9172,1,'es','labels','backend.items.edit','Edit Item','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9173,1,'es','labels','backend.items.view','View Item','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9174,1,'es','labels','backend.items.category','Category','2020-11-26 06:00:29','2020-11-26 06:00:29'),(9175,1,'es','labels','backend.dashboard.buy_item_now','Buy item now','2020-11-26 06:00:30','2020-11-26 06:00:30'),(9176,1,'es','labels','backend.dashboard.my_items','My Items','2020-11-26 06:00:30','2020-11-26 06:00:30'),(9177,1,'es','labels','backend.bundles.fields.duration','Duration','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9178,1,'es','labels','backend.bundles.fields.skill_level','Skill Level','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9179,1,'es','labels','frontend.contact.phone','Phone','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9180,1,'es','labels','frontend.article_video.share_this_news','Share this news','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9181,1,'es','labels','frontend.article_video.related_news','<span>Related</span> News','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9182,1,'es','labels','frontend.article_video.post_comments','Post <span>Comments.</span>','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9183,1,'es','labels','frontend.article_video.write_a_comment','Write a Comment','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9184,1,'es','labels','frontend.article_video.add_comment','Add Comment','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9185,1,'es','labels','frontend.article_video.by','By','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9186,1,'es','labels','frontend.article_video.title','Article & Videos','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9187,1,'es','labels','frontend.article_video.search','Search','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9188,1,'es','labels','frontend.article_video.categories','<span>Categories.</span>','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9189,1,'es','labels','frontend.article_video.popular_tags','Popular <span>Tags.</span>','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9190,1,'es','labels','frontend.article_video.featured_course','Featured <span>Course.</span>','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9191,1,'es','labels','frontend.article_video.login_to_post_comment','Login to Post a Comment','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9192,1,'es','labels','frontend.article_video.no_comments_yet','No comments yet, Be the first to comment.','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9193,1,'es','labels','frontend.course.teacher','Teacher','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9194,1,'es','labels','frontend.course.ratings_reviews','Ratings & Reviews','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9195,1,'es','labels','frontend.course.course_features','Course Features','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9196,1,'es','labels','frontend.course.curriculum','Curriculum','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9197,1,'es','labels','frontend.course.no_lesson','No lessons added for this course.','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9198,1,'es','labels','frontend.course.lectures','Lectures','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9199,1,'es','labels','frontend.course.quizzes','Quizzes','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9200,1,'es','labels','frontend.course.duration','Duration','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9201,1,'es','labels','frontend.course.skill_level','Skill Level','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9202,1,'es','labels','frontend.course.language','Language','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9203,1,'es','labels','frontend.course.certificate','Certificate','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9204,1,'es','labels','frontend.course.assessments','Assessments','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9205,1,'es','labels','frontend.course.past','Past','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9206,1,'es','labels','frontend.course.upcoming','Upcoming','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9207,1,'es','labels','frontend.course.recent_blog_view','Recent <span>Blogs</span>','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9208,1,'es','labels','frontend.course.popular_course','Popular <span>Courses</span>','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9209,1,'es','labels','frontend.course.filter_by','Filter By','2020-11-26 06:00:31','2020-11-26 06:00:31'),(9210,1,'es','menus','backend.sidebar.items.title','Store Item','2020-11-26 06:00:32','2020-11-26 06:00:32'),(9211,1,'es','strings','backend.dashboard.my_items','My Items','2020-11-26 06:00:33','2020-11-26 06:00:33'),(9212,1,'fr','labels','general.no_search_results','No search results.','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9213,1,'fr','labels','backend.courses.fields.duration','Duration','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9214,1,'fr','labels','backend.courses.fields.skill_level','Skill Level','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9215,1,'fr','labels','backend.items.title','Store Items','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9216,1,'fr','labels','backend.items.fields.published','Published','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9217,1,'fr','labels','backend.items.fields.unpublished','Not Published','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9218,1,'fr','labels','backend.items.fields.featured','Featured','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9219,1,'fr','labels','backend.items.fields.free','Free','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9220,1,'fr','labels','backend.items.fields.trending','Trending','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9221,1,'fr','labels','backend.items.fields.popular','Popular','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9222,1,'fr','labels','backend.items.fields.category','Category','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9223,1,'fr','labels','backend.items.fields.title','Title','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9224,1,'fr','labels','backend.items.fields.slug','Slug','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9225,1,'fr','labels','backend.items.fields.description','Description','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9226,1,'fr','labels','backend.items.fields.price','Price','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9227,1,'fr','labels','backend.items.fields.discount','Discount','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9228,1,'fr','labels','backend.items.fields.discount_type','Discount Type','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9229,1,'fr','labels','backend.items.fields.stock_count','Stock Count','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9230,1,'fr','labels','backend.items.fields.item_image','Item Image','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9231,1,'fr','labels','backend.items.fields.start_date','Start Date','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9232,1,'fr','labels','backend.items.fields.duration','Duration','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9233,1,'fr','labels','backend.items.fields.meta_title','Meta Title','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9234,1,'fr','labels','backend.items.fields.meta_description','Meta Description','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9235,1,'fr','labels','backend.items.fields.meta_keywords','Meta Keywords','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9236,1,'fr','labels','backend.items.fields.sidebar','Add Sidebar','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9237,1,'fr','labels','backend.items.fields.status','Status','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9238,1,'fr','labels','backend.items.fields.quantity','Quantity','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9239,1,'fr','labels','backend.items.add_categories','Add Categories','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9240,1,'fr','labels','backend.items.slug_placeholder','Input slug or it will be generated automatically','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9241,1,'fr','labels','backend.items.select_category','Select Category','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9242,1,'fr','labels','backend.items.create','Create Item','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9243,1,'fr','labels','backend.items.edit','Edit Item','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9244,1,'fr','labels','backend.items.view','View Item','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9245,1,'fr','labels','backend.items.category','Category','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9246,1,'fr','labels','backend.dashboard.buy_item_now','Buy item now','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9247,1,'fr','labels','backend.dashboard.my_items','My Items','2020-11-26 06:00:35','2020-11-26 06:00:35'),(9248,1,'fr','labels','backend.bundles.fields.duration','Duration','2020-11-26 06:00:37','2020-11-26 06:00:37'),(9249,1,'fr','labels','backend.bundles.fields.skill_level','Skill Level','2020-11-26 06:00:37','2020-11-26 06:00:37'),(9250,1,'fr','labels','frontend.contact.phone','Phone','2020-11-26 06:00:37','2020-11-26 06:00:37'),(9251,1,'fr','labels','frontend.article_video.share_this_news','Share this news','2020-11-26 06:00:37','2020-11-26 06:00:37'),(9252,1,'fr','labels','frontend.article_video.related_news','<span>Related</span> News','2020-11-26 06:00:37','2020-11-26 06:00:37'),(9253,1,'fr','labels','frontend.article_video.post_comments','Post <span>Comments.</span>','2020-11-26 06:00:37','2020-11-26 06:00:37'),(9254,1,'fr','labels','frontend.article_video.write_a_comment','Write a Comment','2020-11-26 06:00:37','2020-11-26 06:00:37'),(9255,1,'fr','labels','frontend.article_video.add_comment','Add Comment','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9256,1,'fr','labels','frontend.article_video.by','By','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9257,1,'fr','labels','frontend.article_video.title','Article & Videos','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9258,1,'fr','labels','frontend.article_video.search','Search','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9259,1,'fr','labels','frontend.article_video.categories','<span>Categories.</span>','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9260,1,'fr','labels','frontend.article_video.popular_tags','Popular <span>Tags.</span>','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9261,1,'fr','labels','frontend.article_video.featured_course','Featured <span>Course.</span>','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9262,1,'fr','labels','frontend.article_video.login_to_post_comment','Login to Post a Comment','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9263,1,'fr','labels','frontend.article_video.no_comments_yet','No comments yet, Be the first to comment.','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9264,1,'fr','labels','frontend.course.teacher','Teacher','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9265,1,'fr','labels','frontend.course.ratings_reviews','Ratings & Reviews','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9266,1,'fr','labels','frontend.course.course_features','Course Features','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9267,1,'fr','labels','frontend.course.curriculum','Curriculum','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9268,1,'fr','labels','frontend.course.no_lesson','No lessons added for this course.','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9269,1,'fr','labels','frontend.course.lectures','Lectures','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9270,1,'fr','labels','frontend.course.quizzes','Quizzes','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9271,1,'fr','labels','frontend.course.duration','Duration','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9272,1,'fr','labels','frontend.course.skill_level','Skill Level','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9273,1,'fr','labels','frontend.course.language','Language','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9274,1,'fr','labels','frontend.course.certificate','Certificate','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9275,1,'fr','labels','frontend.course.assessments','Assessments','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9276,1,'fr','labels','frontend.course.past','Past','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9277,1,'fr','labels','frontend.course.upcoming','Upcoming','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9278,1,'fr','labels','frontend.course.recent_blog_view','Recent <span>Blogs</span>','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9279,1,'fr','labels','frontend.course.popular_course','Popular <span>Courses</span>','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9280,1,'fr','labels','frontend.course.filter_by','Filter By','2020-11-26 06:00:38','2020-11-26 06:00:38'),(9281,1,'fr','menus','backend.sidebar.items.title','Store Item','2020-11-26 06:00:39','2020-11-26 06:00:39');
/*!40000 ALTER TABLE `ltm_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'App\\Models\\Course',4,'Understanding Composition in Photography - video','23745055','vimeo','23745055',0,'2021-01-04 02:38:17','2021-01-04 02:38:17');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_04_02_193005_create_translations_table',1),(2,'2014_10_12_000000_create_users_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(4,'2016_06_01_000001_create_oauth_auth_codes_table',1),(5,'2016_06_01_000002_create_oauth_access_tokens_table',1),(6,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(7,'2016_06_01_000004_create_oauth_clients_table',1),(8,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(9,'2016_07_29_171118_create_chatter_categories_table',1),(10,'2016_07_29_171118_create_chatter_discussion_table',1),(11,'2016_07_29_171118_create_chatter_post_table',1),(12,'2016_07_29_171128_create_foreign_keys',1),(13,'2016_08_02_183143_add_slug_field_for_discussions',1),(14,'2016_08_03_121747_add_color_row_to_chatter_discussions',1),(15,'2017_01_16_121747_add_markdown_and_lock_to_chatter_posts',1),(16,'2017_01_16_121747_create_chatter_user_discussion_pivot_table',1),(17,'2017_05_28_062751_create_categories_table',1),(18,'2017_07_19_082347_create_1500441827_courses_table',1),(19,'2017_07_19_082723_create_1500442043_lessons_table',1),(20,'2017_07_19_082929_create_1500442169_questions_table',1),(21,'2017_07_19_083047_create_1500442247_questions_options_table',1),(22,'2017_07_19_083236_create_1500442356_tests_table',1),(23,'2017_07_19_120808_create_596eece522a6e_course_user_table',1),(24,'2017_07_19_121657_create_596eeef709839_question_test_table',1),(25,'2017_08_07_165345_add_chatter_soft_deletes',1),(26,'2017_08_11_073824_create_menus_wp_table',1),(27,'2017_08_11_074006_create_menu_items_wp_table',1),(28,'2017_08_14_085956_create_course_students_table',1),(29,'2017_08_17_051131_create_tests_results_table',1),(30,'2017_08_17_051254_create_tests_results_answers_table',1),(31,'2017_08_18_060324_add_rating_to_course_student_table',1),(32,'2017_09_03_144628_create_permission_tables',1),(33,'2017_09_11_174816_create_social_accounts_table',1),(34,'2017_09_26_140332_create_cache_table',1),(35,'2017_09_26_140528_create_sessions_table',1),(36,'2017_09_26_140609_create_jobs_table',1),(37,'2017_10_10_221227_add_chatter_last_reply_at_discussion',1),(38,'2017_11_08_063801_create_threads_table',1),(39,'2017_11_08_063907_create_messages_table',1),(40,'2017_11_08_063923_create_participants_table',1),(41,'2017_11_08_063956_add_softdeletes_to_participants_table',1),(42,'2017_11_08_064015_add_softdeletes_to_threads_table',1),(43,'2017_11_08_064031_add_softdeletes_to_messages_table',1),(44,'2018_04_08_033256_create_password_histories_table',1),(45,'2018_06_27_072626_create_blog_module',1),(46,'2019_01_15_103052_create_media_table',1),(47,'2019_01_16_105633_create_video_progresses_table',1),(48,'2019_01_24_113831_create_invoices_table',1),(49,'2019_01_24_115120_create_cart_storage_table',1),(50,'2019_01_24_120730_create_orders_table',1),(51,'2019_01_24_120745_create_order_items_table',1),(52,'2019_01_29_052953_create_configs_table',1),(53,'2019_02_06_115555_create_course_timeline_table',1),(54,'2019_02_08_052619_create_sliders_table',1),(55,'2019_02_12_051827_create_sponsors_table',1),(56,'2019_02_12_101125_create_testimonials_table',1),(57,'2019_02_13_111625_create_faqs_table',1),(58,'2019_02_15_115610_create_reviews_table',1),(59,'2019_02_19_061120_create_reasons_table',1),(60,'2019_03_01_055054_create_chapter_students_table',1),(61,'2019_03_06_093703_create_contacts_table',1),(62,'2019_03_07_043443_create_pages_table',1),(63,'2019_04_25_095421_create_locales_table',1),(64,'2019_05_08_053815_create_certificates_table',1),(65,'2019_05_30_044853_create_bundles_table',1),(66,'2019_05_30_090702_create_bundle_courses_table',1),(67,'2019_05_31_055427_create_bundle_student_table',1),(68,'2019_05_31_120554_update_order_items_with_morph',1),(69,'2019_06_03_074229_add_foreign_key_to_courses',1),(70,'2019_06_03_074251_add_foreign_key_to_faqs',1),(71,'2019_06_03_074323_add_foreign_key_to_blogs',1),(72,'2019_06_07_073739_add_columns_in_users_table',1),(73,'2019_07_22_105142_add_free_column_in_courses',1),(74,'2019_07_22_105658_add_free_column_in_bundles',1),(75,'2019_07_30_055917_create_coupons_table',1),(76,'2019_07_30_061713_create_taxes_table',1),(77,'2019_07_30_063204_add_coupon_tax_id_in_orders_table',1),(78,'2019_08_19_054926_add_explanation_column_in_question_options',1),(79,'2019_09_10_061608_add_remarks_in_orders_table',1),(80,'2019_09_10_092512_create_teacher_profiles_table',1),(81,'2019_09_12_054932_create_earnings_table',1),(82,'2019_09_12_085707_create_withdraws_table',1),(83,'2019_09_18_210948_move_starred_column_from_threads_table_to_participants_table',1),(84,'2020_04_13_063958_add_description_column_in_teacher_profiles',1),(85,'2020_07_10_111516_create_failed_jobs_table',1),(86,'2020_08_08_092346_create_live_lesson_slots_table',2),(87,'2020_08_14_105647_add_live_lesson_column_lessons_table',2),(88,'2020_08_17_115140_create_lesson_slot_bookings_table',2),(89,'2020_08_19_050813_add_column_provider_oauth_clients_table',2),(90,'2020_10_18_064017_add_duration_level_in_courses_table',3),(91,'2020_10_31_124103_create_store_item_table',4),(92,'2020_11_01_043309_add_save_address_to_users_table',4),(93,'2020_11_01_043951_add_save_address_to_orders_table',4),(94,'2020_11_08_034602_add_hide_flag_admin_menu_items',4),(95,'2020_11_14_053959_update_items_desc_type',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\Auth\\User',1),(2,'App\\Models\\Auth\\User',2),(3,'App\\Models\\Auth\\User',3),(3,'App\\Models\\Auth\\User',4),(2,'App\\Models\\Auth\\User',5),(2,'App\\Models\\Auth\\User',6),(3,'App\\Models\\Auth\\User',7),(3,'App\\Models\\Auth\\User',8),(3,'App\\Models\\Auth\\User',9),(3,'App\\Models\\Auth\\User',10),(3,'App\\Models\\Auth\\User',11);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `item_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `payer_id` int(10) NOT NULL,
  `reference_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `payment_type` int(11) NOT NULL DEFAULT '0' COMMENT '1-stripe/card, 2 - Paypal, 3 - Offline',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 - in progress, 1 - Completed',
  `transaction_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `coupon_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sidebar` int(11) NOT NULL DEFAULT '0',
  `meta_title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `published` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_user_id_foreign` (`user_id`),
  CONSTRAINT `pages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,1,'About Us','about-us','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et urna eu risus ultrices sagittis. In tortor turpis, lobortis a tincidunt non, congue a lorem. Quisque imperdiet congue blandit. Cras quis tortor quis nunc porttitor pulvinar id id lacus. Curabitur dapibus augue orci. Praesent varius, dolor ut ultricies faucibus, ante nunc fringilla nulla, vitae egestas lorem erat eu libero. Praesent cursus tortor non gravida elementum. Praesent et arcu molestie, faucibus ligula sed, euismod urna. Praesent vitae orci metus. Nulla varius diam nec iaculis pulvinar. Sed mi enim, cursus nec urna a, interdum lobortis nisi.<br><br>\n\nMauris posuere sem at arcu commodo lobortis. Suspendisse sollicitudin dapibus congue. Etiam sit amet lacinia eros. In dictum lacinia tortor, nec mattis eros vulputate vel. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec posuere odio eget risus aliquam, quis ornare urna bibendum. Integer iaculis massa magna, et vehicula dui placerat a. Vestibulum ultricies mauris nunc, ut facilisis orci lobortis nec. Fusce cursus eget quam in elementum. Donec ipsum dui, semper ut commodo in, congue in urna.<br><br>\nimperdiet congue blandit. Cras quis tortor quis nunc porttitor pulvinar id id lacus. Curabitur dapibus augue orci. Praesent varius, dolor ut ultricies faucibus, ante nunc fringilla nulla, vitae egestas lorem erat eu libero. Praesent cursus tortor non gravida elementum. Praesent et arcu molestie, faucibus ligula sed, euismod urna. Praesent vitae orci metus. Nulla varius diam nec iaculis pulvinar. Sed mi enim, cursus nec urna a, interdum lobortis nisi.',NULL,0,NULL,NULL,NULL,1,'2020-08-21 01:04:01','2020-10-12 05:00:15',NULL),(2,1,'Privacy Policy','privacy-policy','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et urna eu risus ultrices sagittis. In tortor turpis, lobortis a tincidunt non, congue a lorem. Quisque imperdiet congue blandit. Cras quis tortor quis nunc porttitor pulvinar id id lacus. Curabitur dapibus augue orci. Praesent varius, dolor ut ultricies faucibus, ante nunc fringilla nulla, vitae egestas lorem erat eu libero. Praesent cursus tortor non gravida elementum. Praesent et arcu molestie, faucibus ligula sed, euismod urna. Praesent vitae orci metus. Nulla varius diam nec iaculis pulvinar. Sed mi enim, cursus nec urna a, interdum lobortis nisi.<br><br>\n\nMauris posuere sem at arcu commodo lobortis. Suspendisse sollicitudin dapibus congue. Etiam sit amet lacinia eros. In dictum lacinia tortor, nec mattis eros vulputate vel. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec posuere odio eget risus aliquam, quis ornare urna bibendum. Integer iaculis massa magna, et vehicula dui placerat a. Vestibulum ultricies mauris nunc, ut facilisis orci lobortis nec. Fusce cursus eget quam in elementum. Donec ipsum dui, semper ut commodo in, congue in urna.<br><br>\nimperdiet congue blandit. Cras quis tortor quis nunc porttitor pulvinar id id lacus. Curabitur dapibus augue orci. Praesent varius, dolor ut ultricies faucibus, ante nunc fringilla nulla, vitae egestas lorem erat eu libero. Praesent cursus tortor non gravida elementum. Praesent et arcu molestie, faucibus ligula sed, euismod urna. Praesent vitae orci metus. Nulla varius diam nec iaculis pulvinar. Sed mi enim, cursus nec urna a, interdum lobortis nisi.',NULL,0,NULL,NULL,NULL,1,'2020-08-21 01:04:01','2020-10-12 05:00:15',NULL);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_histories`
--

DROP TABLE IF EXISTS `password_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `password_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_histories`
--

LOCK TABLES `password_histories` WRITE;
/*!40000 ALTER TABLE `password_histories` DISABLE KEYS */;
INSERT INTO `password_histories` VALUES (1,1,'$2y$10$7trTyIhs13VGgw3YzRA2k.kA7Jy9dAkgcLgjSLhpyPcyA4cdrP6I2','2020-11-26 06:00:22','2020-11-26 06:00:22'),(2,2,'$2y$10$Uyupide.sMRfvGhaFLAsjOBPsaYD7cJ9lnNFoH2SVsq7Xz7bkh/Au','2020-11-26 06:00:22','2020-11-26 06:00:22'),(3,3,'$2y$10$DA0jnTNoBF075NIyvJo3dODqRkTfjIhsswjuCG9xEs4HXcZufsNly','2020-11-26 06:00:22','2020-11-26 06:00:22'),(6,2,'$2y$10$L9Y8hqRmUzo8rF8auBUDDOG4bEPc8l3IYxvfLbY10qUHmG1DSEqv.','2020-12-28 08:14:24','2020-12-28 08:14:24'),(7,2,'$2y$10$ZWCXRYwf6hkHw2ZnRyZm2eAsRWCZpcsWfw2hv23as.0QH9NprvXee','2020-12-28 08:37:42','2020-12-28 08:37:42'),(8,2,'$2y$10$7v3NbjyirMmDDoCrpErg5OqTrZKaZVSG.IzXqe/RjZf3OfomT09.O','2020-12-28 08:38:15','2020-12-28 08:38:15'),(24,2,'$2y$10$8BeoyEbfWsR0ocMmWt49Dusz/7X6lVX.VQZvjtfH.tsDlZ/L8nvAm','2021-01-04 01:31:51','2021-01-04 01:31:51'),(25,2,'$2y$10$4/JsgwuUkSdnn9nbxLPSmue0qa4dSR7LaDjAe2xgymJFwRE3CTvom','2021-01-04 02:48:06','2021-01-04 02:48:06');
/*!40000 ALTER TABLE `password_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'user_management_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(2,'user_management_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(3,'user_management_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(4,'user_management_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(5,'user_management_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(6,'permission_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(7,'permission_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(8,'permission_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(9,'permission_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(10,'permission_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(11,'role_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(12,'role_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(13,'role_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(14,'role_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(15,'role_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(16,'user_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(17,'user_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(18,'user_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(19,'user_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(20,'user_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(21,'course_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(22,'course_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(23,'course_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(24,'course_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(25,'course_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(26,'lesson_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(27,'lesson_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(28,'lesson_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(29,'lesson_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(30,'lesson_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(31,'question_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(32,'question_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(33,'question_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(34,'question_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(35,'question_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(36,'questions_option_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(37,'questions_option_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(38,'questions_option_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(39,'questions_option_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(40,'questions_option_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(41,'test_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(42,'test_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(43,'test_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(44,'test_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(45,'test_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(46,'order_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(47,'view backend','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(48,'category_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(49,'category_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(50,'category_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(51,'category_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(52,'category_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(53,'blog_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(54,'blog_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(55,'blog_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(56,'blog_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(57,'blog_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(58,'reason_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(59,'reason_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(60,'reason_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(61,'reason_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(62,'reason_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(63,'page_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(64,'page_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(65,'page_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(66,'page_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(67,'page_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(68,'bundle_access','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(69,'bundle_create','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(70,'bundle_edit','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(71,'bundle_view','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(72,'bundle_delete','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(73,'workshop_access','web','2020-12-30 13:23:58','2020-12-30 13:23:58'),(74,'workshop_create','web','2020-12-30 13:24:32','2020-12-30 13:24:32'),(75,'workshop_edit','web','2020-12-30 13:24:32','2020-12-30 13:24:32'),(76,'workshop_view','web','2020-12-30 13:24:32','2020-12-30 13:24:32'),(77,'workshop_delete','web','2020-12-30 13:24:32','2020-12-30 13:24:32');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question_test`
--

DROP TABLE IF EXISTS `question_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `question_test` (
  `question_id` int(10) unsigned DEFAULT NULL,
  `test_id` int(10) unsigned DEFAULT NULL,
  KEY `fk_p_54420_54422_test_que_596eeef70992f` (`question_id`),
  KEY `fk_p_54422_54420_question_596eeef7099af` (`test_id`),
  CONSTRAINT `fk_p_54420_54422_test_que_596eeef70992f` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_p_54422_54420_question_596eeef7099af` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_test`
--

LOCK TABLES `question_test` WRITE;
/*!40000 ALTER TABLE `question_test` DISABLE KEYS */;
/*!40000 ALTER TABLE `question_test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_user_id_foreign` (`user_id`),
  KEY `questions_deleted_at_index` (`deleted_at`),
  CONSTRAINT `questions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions_options`
--

DROP TABLE IF EXISTS `questions_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(10) unsigned DEFAULT NULL,
  `option_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `explanation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `correct` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `54421_596eee8745a1e` (`question_id`),
  KEY `questions_options_deleted_at_index` (`deleted_at`),
  CONSTRAINT `54421_596eee8745a1e` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions_options`
--

LOCK TABLES `questions_options` WRITE;
/*!40000 ALTER TABLE `questions_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `questions_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reasons`
--

DROP TABLE IF EXISTS `reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 - disabled, 1 - enabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reasons`
--

LOCK TABLES `reasons` WRITE;
/*!40000 ALTER TABLE `reasons` DISABLE KEYS */;
INSERT INTO `reasons` VALUES (1,'Exercitationem aspernatur voluptatem veritatis.','Est rerum in quia ab id. Deleniti optio animi debitis.','fas fa-address-book',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(2,'Et iusto exercitationem itaque est quia debitis.','Sed nihil id dignissimos cupiditate. Voluptatem quia error quae odit aperiam in labore.','far fa-address-card',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(3,'Omnis labore molestias aperiam.','Quasi fuga velit tenetur accusantium perspiciatis deleniti et. Nisi cumque ea inventore et ratione blanditiis reprehenderit.','fas fa-allergies',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(4,'Et alias cumque qui.','Repellendus quia ad velit debitis. Autem eos rerum voluptate accusantium quia eum illo. Ut ut dolore enim adipisci.','far fa-address-card',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(5,'Ad in inventore aut.','Officia libero delectus consequatur magnam. Cum sed a omnis voluptate et. Est tempora beatae error nihil dolorum vel explicabo omnis.','fab fa-accusoft',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(6,'Adipisci dolore et sequi dolores ea.','Est sit voluptate tempora. Velit ut explicabo eveniet corrupti et sapiente molestiae.','fas fa-ambulance',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(7,'Nemo sunt voluptas harum qui molestias dolore.','Cumque sapiente illum qui. Amet molestiae velit ut earum quo quo.','fab fa-adn',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(8,'Laudantium et laboriosam distinctio quam ab ad.','Perspiciatis rerum nostrum rerum sed provident. Iure eum magnam nihil est.','fas fa-ambulance',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(9,'Doloremque eius tempora voluptate.','Dolorem sint sequi accusantium adipisci. Numquam pariatur earum architecto explicabo exercitationem ut rerum consequatur. Qui ullam rerum similique eum.','fas fa-address-book',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(10,'Cumque rem recusandae necessitatibus.','Perferendis quo minima aperiam et. Dignissimos dolores dolorum aut beatae officiis dolore quasi. Culpa reiciendis aliquid repellendus iste quia.','fab fa-amazon',1,'2020-08-21 01:19:26','2020-08-21 01:19:26');
/*!40000 ALTER TABLE `reasons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `reviewable_id` int(11) NOT NULL,
  `reviewable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(1,2),(21,2),(22,2),(23,2),(24,2),(25,2),(26,2),(27,2),(28,2),(29,2),(30,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(37,2),(38,2),(39,2),(40,2),(41,2),(42,2),(43,2),(44,2),(45,2),(47,2),(48,2),(49,2),(51,2),(68,2),(69,2),(70,2),(71,2),(72,2),(73,2),(74,2),(75,2),(76,2),(77,2),(47,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'administrator','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(2,'teacher','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(3,'student','web','2020-11-26 06:00:22','2020-11-26 06:00:22'),(4,'user','web','2020-11-26 06:00:22','2020-11-26 06:00:22');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sliders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bg_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `overlay` int(11) DEFAULT '0',
  `sequence` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 - enabled, 0 - disabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,'Slide 1','{\"hero_text\":\"Inventive Solution for Education\",\"sub_text\":\"Education and Training Organization\",\"buttons\":[{\"label\":\"Our Courses\",\"link\":\"http://laravel-lms.test/courses\"}]}','slider-1.jpg',0,1,1,'2020-08-21 01:04:01','2020-08-21 01:04:01'),(2,'Slide 2','{\"hero_text\":\"Browse The Best Courses\",\"sub_text\":\"Education and Training Organization\",\"widget\":{\"type\":1}}','slider-2.jpg',0,2,1,'2020-08-21 01:04:01','2020-08-21 01:04:01'),(3,'Slide 3','{\"hero_text\":\"Mobile Application Experiences : Mobile App Design\",\"sub_text\":\"\",\"widget\":{\"type\":2,\"timer\":\"2019/02/15 11:01\"},\"buttons\":[{\"label\":\"About Us\",\"link\":\"http://laravel-lms.test/about-us\"},{\"label\":\"Contact Us\",\"link\":\"http://laravel-lms.test/contact-us\"}]}','slider-3.jpg',0,3,1,'2020-08-21 01:04:01','2020-08-21 01:04:01');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_accounts`
--

DROP TABLE IF EXISTS `social_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `provider` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_accounts_user_id_foreign` (`user_id`),
  CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_accounts`
--

LOCK TABLES `social_accounts` WRITE;
/*!40000 ALTER TABLE `social_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sponsors`
--

DROP TABLE IF EXISTS `sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sponsors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 - disabled, 1 - enabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sponsors`
--

LOCK TABLES `sponsors` WRITE;
/*!40000 ALTER TABLE `sponsors` DISABLE KEYS */;
/*!40000 ALTER TABLE `sponsors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taggables`
--

DROP TABLE IF EXISTS `taggables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taggables` (
  `tag_id` int(11) NOT NULL,
  `taggable_id` int(11) NOT NULL,
  `taggable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taggables`
--

LOCK TABLES `taggables` WRITE;
/*!40000 ALTER TABLE `taggables` DISABLE KEYS */;
INSERT INTO `taggables` VALUES (1,41,'App\\Models\\Blog',NULL,NULL,NULL);
/*!40000 ALTER TABLE `taggables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'nono','nono','2020-11-23 07:09:40','2020-11-23 07:09:40',NULL);
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxes`
--

DROP TABLE IF EXISTS `taxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taxes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxes`
--

LOCK TABLES `taxes` WRITE;
/*!40000 ALTER TABLE `taxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `taxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_profiles`
--

DROP TABLE IF EXISTS `teacher_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `facebook_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `twitter_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `linkedin_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `insta_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'paypal,bank',
  `payment_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `teacher_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `teacher_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_profiles`
--

LOCK TABLES `teacher_profiles` WRITE;
/*!40000 ALTER TABLE `teacher_profiles` DISABLE KEYS */;
INSERT INTO `teacher_profiles` VALUES (1,2,'http://considine.com/','http://www.gutmann.com/quasi-quod-aut-tempora-sit-mollitia','https://www.hane.org/voluptas-quia-ducimus-quibusdam-ut','http://considine.com/','paypal','{\"bank_name\":\"\",\"ifsc_code\":\"\",\"account_number\":\"\",\"account_name\":\"\",\"paypal_email\":\"adminteacher@demo.com\"}',NULL,'<p>David Bathgate is an award-winning documentary and travel photographer whose work appears in such publications as Time, Newsweek, The New York Times, Geo, Stern, The Guardian, The Times of London and the London Sunday Times.<br />\r\n<br />\r\nHolding doctoral and masters degrees in anthropology and journalism, respectively, David Bathgate works regularly in Asia, Africa, the Middle East and Europe, covering topics of social, political, cultural and environmental interest.<br />\r\n<br />\r\nDavid is Founder and President of The Compelling Image - online and interactive school of photography and multimedia storytelling, contributing editor to Photojournale and conducts photography and photojournalism workshops in India and Morocco. He is also featured Official Fuji X Photographer.<br />\r\n<br />\r\nVisit&nbsp;<a href=\"https://www.davidbathgate.com/index\" target=\"_blank\">David&#39;s website</a><br />\r\n<br />\r\nFollow David on&nbsp;<a href=\"https://www.instagram.com/davidbathgatephoto/\" target=\"_blank\">Instagram</a></p>','2020-08-21 01:04:02','2021-01-04 01:31:51',NULL),(2,2,'http://www.treutel.org/omnis-non-rerum-praesentium-cupiditate-quasi-rerum.html','https://bruen.info/unde-nesciunt-in-excepturi-numquam-numquam-vel-eum.html','http://kautzer.com/repudiandae-eligendi-nobis-maiores.html','http://considine.com/','paypal','{\"bank_name\":\"\",\"ifsc_code\":\"\",\"account_number\":\"\",\"account_name\":\"\",\"paypal_email\":\"adminteacher@demo.com\"}',NULL,NULL,'2020-10-12 05:00:16','2020-10-12 05:00:16',NULL);
/*!40000 ALTER TABLE `teacher_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 - disabled, 1 - enabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Dr. Dennis Zieme','Hotel Desk Clerk','Animi vitae animi ullam nihil voluptatem odit est. Et eligendi at neque voluptatibus tempore. Quia quia nemo et aut adipisci accusamus rerum.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(2,'Frederick Goldner','Floor Layer','Recusandae itaque occaecati quia qui quos sunt sint occaecati. Deserunt molestiae aliquid praesentium modi rerum.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(3,'Shaylee Mayert','Psychiatric Technician','Dicta non qui placeat cupiditate ut qui reprehenderit autem. Et quia ad dolorum odio. Mollitia id eius numquam rerum. Consectetur omnis ea placeat aliquam officiis omnis consectetur.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(4,'Mrs. Ashlynn Fisher II','Fishery Worker','Provident velit velit sunt corporis omnis. Doloribus officiis porro non nemo eaque. Doloremque voluptatibus ut labore sapiente.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(5,'Antone Koepp','Medical Assistant','Voluptatem ullam ipsa inventore commodi fugit. Id ullam aut quis saepe est corporis. Tempora incidunt provident dolor laboriosam impedit.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(6,'Dr. Santino Maggio','Fitness Trainer','Qui enim voluptates fuga quas inventore soluta non eos. Repellendus modi aut necessitatibus ratione quia. Magni numquam sunt sint aut laudantium. Voluptatem omnis itaque sint odit voluptatum.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(7,'Odie Sanford DDS','Fabric Pressers','Nihil aut delectus pariatur maxime eaque. Consequuntur eum ipsam ducimus et cum. Non mollitia odio accusantium sed vero.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(8,'Dr. Josiah Johns I','Air Traffic Controller','Sed eligendi unde asperiores consequatur. Ullam sint eaque nulla vel libero est et. Pariatur repellat ut totam qui aut. Officiis animi voluptas amet maiores.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(9,'Estella Reilly DVM','Extraction Worker','Quae nesciunt voluptatem consectetur. Dolores voluptatem labore ut unde ad et ex. Aut voluptates ad repellendus aut.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26'),(10,'Colten Thiel','Precision Printing Worker','Debitis aut autem accusamus laborum reprehenderit dolorum odio. Modi architecto iure sequi voluptas.',1,'2020-08-21 01:19:26','2020-08-21 01:19:26');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(10) unsigned DEFAULT NULL,
  `lesson_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `published` tinyint(4) DEFAULT '0',
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `54422_596eeef514d00` (`course_id`),
  KEY `54422_596eeef53411a` (`lesson_id`),
  KEY `tests_deleted_at_index` (`deleted_at`),
  CONSTRAINT `54422_596eeef514d00` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `54422_596eeef53411a` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests`
--

LOCK TABLES `tests` WRITE;
/*!40000 ALTER TABLE `tests` DISABLE KEYS */;
/*!40000 ALTER TABLE `tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests_results`
--

DROP TABLE IF EXISTS `tests_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tests_results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `test_result` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tests_results_test_id_foreign` (`test_id`),
  KEY `tests_results_user_id_foreign` (`user_id`),
  CONSTRAINT `tests_results_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tests_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests_results`
--

LOCK TABLES `tests_results` WRITE;
/*!40000 ALTER TABLE `tests_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `tests_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests_results_answers`
--

DROP TABLE IF EXISTS `tests_results_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tests_results_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tests_result_id` int(10) unsigned DEFAULT NULL,
  `question_id` int(10) unsigned DEFAULT NULL,
  `option_id` int(10) unsigned DEFAULT NULL,
  `correct` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tests_results_answers_tests_result_id_foreign` (`tests_result_id`),
  KEY `tests_results_answers_question_id_foreign` (`question_id`),
  KEY `tests_results_answers_option_id_foreign` (`option_id`),
  CONSTRAINT `tests_results_answers_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `questions_options` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tests_results_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tests_results_answers_tests_result_id_foreign` FOREIGN KEY (`tests_result_id`) REFERENCES `tests_results` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests_results_answers`
--

LOCK TABLES `tests_results_answers` WRITE;
/*!40000 ALTER TABLE `tests_results_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tests_results_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gravatar',
  `avatar_location` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `confirmation_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `timezone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `save_address_flag` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `saved_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'e05ae10e-39fe-457a-8708-3874e366e713','Admin','Istrator','admin@lms.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'gravatar',NULL,'$2y$10$7trTyIhs13VGgw3YzRA2k.kA7Jy9dAkgcLgjSLhpyPcyA4cdrP6I2',NULL,1,'0c1762b83b04d702f62b66357ece5e9f',1,NULL,'N',NULL,'2021-01-04 04:28:39','127.0.0.1','ag1RR6q5LfExqXaw1R1L1mnJcDZxG5aREQhFzFUDVQoicGfe8tMTnpkfgDdZ','2020-11-26 06:00:22','2021-01-04 04:28:39',NULL),(2,'6dd0f946-7de3-4eda-9bba-704cefde9590','David','Bathgate','teacher@lms.com',NULL,NULL,'male',NULL,NULL,NULL,NULL,NULL,NULL,'storage','avatars/2pTyuxduuTMexMqrbWVahTIYNjXXIwVw0aGvLzgW.jpeg','$2y$10$4/JsgwuUkSdnn9nbxLPSmue0qa4dSR7LaDjAe2xgymJFwRE3CTvom',NULL,1,'2cd7b56ca8c914b968e4112f5438803b',1,NULL,'N',NULL,'2020-12-30 05:40:42','127.0.0.1','MBV8s8W1VWb9JO2OkobvAcGgqWsN6t6TvBYzQ81GQUhSgzdiQ8HeMnWz7lYI','2020-11-26 06:00:22','2021-01-04 02:48:06',NULL),(3,'f4441ca3-df19-4381-89e4-a32d659fdf92','Student','User','student@lms.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'gravatar',NULL,'$2y$10$DA0jnTNoBF075NIyvJo3dODqRkTfjIhsswjuCG9xEs4HXcZufsNly',NULL,1,'70fe6044c56a5a509bb456f69d82dcd6',1,NULL,'N','{\"firstName\":\"Seow\",\"lastName\":\"Bing\",\"email\":\"ssbing99@gmail.com\",\"phone\":\"213123\",\"address\":\"ytqwet\",\"address2\":null,\"country\":\"United States\",\"state\":\"California\",\"zip\":\"12378\"}','2021-01-04 06:11:16','127.0.0.1','0GfNnGxX3YF4S9FDDIQcADLDmf0o0fpMCtEJAzmpdfAChv2gpG0Sr2M9JkUP','2020-11-26 06:00:22','2021-01-04 06:11:16',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_progresses`
--

DROP TABLE IF EXISTS `video_progresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `video_progresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `duration` double(8,2) NOT NULL,
  `progress` double(8,2) NOT NULL,
  `complete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0.Pending 1.Complete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `video_progresses_media_id_foreign` (`media_id`),
  KEY `video_progresses_user_id_foreign` (`user_id`),
  CONSTRAINT `video_progresses_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE,
  CONSTRAINT `video_progresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_progresses`
--

LOCK TABLES `video_progresses` WRITE;
/*!40000 ALTER TABLE `video_progresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `video_progresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdraws`
--

DROP TABLE IF EXISTS `withdraws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdraws` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_type` tinyint(4) DEFAULT NULL COMMENT '0=Bank, 1=Paypal,2=offline',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=pending,1=accepted,2=rejected',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `withdraws_user_id_foreign` (`user_id`),
  CONSTRAINT `withdraws_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraws`
--

LOCK TABLES `withdraws` WRITE;
/*!40000 ALTER TABLE `withdraws` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdraws` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workshop_student`
--

DROP TABLE IF EXISTS `workshop_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workshop_student` (
  `workshop_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `rating` int(10) unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `workshop_student_course_id_foreign` (`workshop_id`),
  KEY `workshop_student_user_id_foreign` (`user_id`),
  CONSTRAINT `workshop_student_course_id_foreign` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `workshop_student_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workshop_student`
--

LOCK TABLES `workshop_student` WRITE;
/*!40000 ALTER TABLE `workshop_student` DISABLE KEYS */;
/*!40000 ALTER TABLE `workshop_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workshop_user`
--

DROP TABLE IF EXISTS `workshop_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workshop_user` (
  `workshop_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  KEY `fk_p_54418_54417_user_workshop_596eece522b73` (`workshop_id`),
  KEY `fk_p_54417_54418_workshop_u_596eece522bee` (`user_id`),
  CONSTRAINT `fk_p_54417_54418_workshop_u_596eece522bee` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_p_54418_54417_user_workshop_596eece522b73` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workshop_user`
--

LOCK TABLES `workshop_user` WRITE;
/*!40000 ALTER TABLE `workshop_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `workshop_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workshops`
--

DROP TABLE IF EXISTS `workshops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workshops` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `enrolment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `upcoming_workshop` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) DEFAULT NULL,
  `deposit` decimal(15,2) DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT NULL,
  `single_supplement` decimal(15,2) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `workshop_date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `published` tinyint(4) DEFAULT '0',
  `free` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `workshops_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workshops`
--

LOCK TABLES `workshops` WRITE;
/*!40000 ALTER TABLE `workshops` DISABLE KEYS */;
/*!40000 ALTER TABLE `workshops` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-07 23:55:14
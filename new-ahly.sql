-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 17, 2025 at 11:28 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new-ahly`
--

-- --------------------------------------------------------

--
-- Table structure for table `academies`
--

DROP TABLE IF EXISTS `academies`;
CREATE TABLE IF NOT EXISTS `academies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_ur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academies_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academies`
--

INSERT INTO `academies` (`id`, `branch_id`, `name_en`, `name_ar`, `name_ur`, `description_en`, `description_ar`, `description_ur`, `contact_email`, `phone`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'sadada1', 'asdad', 'asdas', 'asd', NULL, NULL, 'asdasa@gmail.com', 'sada', 1, '2025-05-22 03:35:36', '2025-05-22 03:18:08', '2025-05-22 03:35:36'),
(2, 2, 'Sopoline Palmer', 'Lavinia Pratt', 'Cecilia Hart', 'Voluptatem ut non ra', 'Aliqua Dolor sint', 'Impedit ut necessit', 'weburi@mailinator.com', '+1 (934) 523-5097', 1, NULL, '2025-05-22 03:19:16', '2025-05-22 03:19:16'),
(3, 2, 'Lenore Perez', 'Amos Santana', 'Whoopi Fisher', 'Voluptatum velit ani', 'Nihil consequat Inc', 'Facere pariatur Mol', 'cydafob@mailinator.com', '+1 (771) 651-7064', 1, NULL, '2025-05-22 03:35:30', '2025-05-22 03:35:30'),
(4, 1, 'Doris Small', 'Gillian Pitts', 'Madaline Williams', 'Culpa dolorem fuga', 'Sint laudantium co', 'Perspiciatis aliqua', 'pyha@mailinator.com', '+1 (799) 475-9756', 1, NULL, '2025-05-26 06:04:59', '2025-05-26 06:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` bigint UNSIGNED NOT NULL,
  `system_id` bigint UNSIGNED NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branches_city_id_foreign` (`city_id`),
  KEY `branches_system_id_foreign` (`system_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `name_ar`, `name_ur`, `city_id`, `system_id`, `address`, `phone`, `is_active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Damon Moses', 'Hanna Shaw', 'Alyssa Meadows', 5, 1, 'Lorem quia assumenda', '+1 (908) 842-7498', 1, NULL, '2025-05-14 06:40:18', '2025-05-14 06:40:18'),
(2, 'Laurel Walter', 'Shellie Rodriquez', 'Lacey Ruiz', 54, 2, 'Minim officia ut tem', '+1 (779) 552-3811', 1, NULL, '2025-05-14 06:41:39', '2025-05-14 06:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `state_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_native` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_state_id_foreign` (`state_id`)
) ENGINE=MyISAM AUTO_INCREMENT=254 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`, `name_native`, `latitude`, `longitude`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 1, 'Abu Dhabi Island', NULL, NULL, NULL, 1, '2025-05-13 04:59:36', '2025-05-13 04:59:36'),
(5, 1, 'Al Reem Island', NULL, NULL, NULL, 1, '2025-05-13 04:59:44', '2025-05-13 04:59:44'),
(6, 1, 'Al Maryah Island', NULL, NULL, NULL, 1, '2025-05-13 04:59:55', '2025-05-13 04:59:55'),
(7, 1, 'Saadiyat Island', NULL, NULL, NULL, 1, '2025-05-13 05:00:03', '2025-05-13 05:00:03'),
(8, 1, 'Yas Island', NULL, NULL, NULL, 1, '2025-05-13 05:00:09', '2025-05-13 05:00:09'),
(9, 1, 'Khalifa City (A & B)', NULL, NULL, NULL, 1, '2025-05-13 05:00:16', '2025-05-13 05:00:16'),
(10, 1, 'Mohammed Bin Zayed City (MBZ City)', NULL, NULL, NULL, 1, '2025-05-13 05:00:22', '2025-05-13 05:00:22'),
(11, 1, 'Mussafah', NULL, NULL, NULL, 1, '2025-05-13 05:00:34', '2025-05-13 05:00:34'),
(12, 1, 'Al Mushrif', NULL, NULL, NULL, 1, '2025-05-13 05:00:40', '2025-05-13 05:00:40'),
(13, 1, 'Al Muroor', NULL, NULL, NULL, 1, '2025-05-13 05:00:46', '2025-05-13 05:00:46'),
(14, 1, 'Al Khalidiyah', NULL, NULL, NULL, 1, '2025-05-13 05:00:53', '2025-05-13 05:00:53'),
(15, 1, 'Al Bateen', NULL, NULL, NULL, 1, '2025-05-13 05:01:02', '2025-05-13 05:01:02'),
(16, 1, 'Al Shamkha', NULL, NULL, NULL, 1, '2025-05-13 05:01:15', '2025-05-13 05:01:15'),
(17, 1, 'Al Shawamekh', NULL, NULL, NULL, 1, '2025-05-13 05:01:22', '2025-05-13 05:01:22'),
(18, 1, 'Al Bahia', NULL, NULL, NULL, 1, '2025-05-13 05:01:29', '2025-05-13 05:01:29'),
(19, 1, 'Al Falah', NULL, NULL, NULL, 1, '2025-05-13 05:01:36', '2025-05-13 05:01:36'),
(20, 1, 'Al Wathba', NULL, NULL, NULL, 1, '2025-05-13 05:01:42', '2025-05-13 05:01:42'),
(21, 1, 'Al Maqtaa', NULL, NULL, NULL, 1, '2025-05-13 05:01:49', '2025-05-13 05:01:49'),
(22, 1, 'Al Rawdah', NULL, NULL, NULL, 1, '2025-05-13 05:01:59', '2025-05-13 05:01:59'),
(23, 1, 'Al Nahyan', NULL, NULL, NULL, 1, '2025-05-13 05:02:06', '2025-05-13 05:02:06'),
(24, 1, 'Al Zahiyah (Tourist Club Area)', NULL, NULL, NULL, 1, '2025-05-13 05:02:12', '2025-05-13 05:02:12'),
(25, 1, 'Al Danah', NULL, NULL, NULL, 1, '2025-05-13 05:02:21', '2025-05-13 05:02:21'),
(26, 1, 'Al Raha (Al Raha Beach & Gardens)', NULL, NULL, NULL, 1, '2025-05-13 05:02:27', '2025-05-13 05:02:27'),
(27, 1, 'Al Manaseer', NULL, NULL, NULL, 1, '2025-05-13 05:02:32', '2025-05-13 05:02:32'),
(28, 1, 'Al Qurm', NULL, NULL, NULL, 1, '2025-05-13 05:02:39', '2025-05-13 05:02:39'),
(29, 1, 'Al Hudayriat Island', NULL, NULL, NULL, 1, '2025-05-13 05:02:46', '2025-05-13 05:02:46'),
(30, 1, 'Al Ain City', NULL, NULL, NULL, 1, '2025-05-13 05:04:36', '2025-05-13 05:04:36'),
(31, 1, 'Al Jimi', NULL, NULL, NULL, 1, '2025-05-13 05:04:50', '2025-05-13 05:04:50'),
(32, 1, 'Al Towayya', NULL, NULL, NULL, 1, '2025-05-13 05:04:56', '2025-05-13 05:04:56'),
(33, 1, 'Al Hili', NULL, NULL, NULL, 1, '2025-05-13 05:05:04', '2025-05-13 05:05:04'),
(34, 1, 'Al Bateen (Al Ain)', NULL, NULL, NULL, 1, '2025-05-13 05:05:11', '2025-05-13 05:05:11'),
(35, 1, 'Al Maqam', NULL, NULL, NULL, 1, '2025-05-13 05:05:18', '2025-05-13 05:05:18'),
(36, 1, 'Al Muwaiji', NULL, NULL, NULL, 1, '2025-05-13 05:05:26', '2025-05-13 05:05:26'),
(37, 1, 'Al Yahar', NULL, NULL, NULL, 1, '2025-05-13 05:05:33', '2025-05-13 05:05:33'),
(38, 1, 'Al Foah', NULL, NULL, NULL, 1, '2025-05-13 05:05:39', '2025-05-13 05:05:39'),
(39, 1, 'Zakher', NULL, NULL, NULL, 1, '2025-05-13 05:05:44', '2025-05-13 05:05:44'),
(40, 1, 'Al Qattara', NULL, NULL, NULL, 1, '2025-05-13 05:05:56', '2025-05-13 05:05:56'),
(41, 1, 'Al Ain Industrial Area', NULL, NULL, NULL, 1, '2025-05-13 05:06:02', '2025-05-13 05:06:02'),
(42, 1, 'Al Ain Oasis', NULL, NULL, NULL, 1, '2025-05-13 05:06:08', '2025-05-13 05:06:08'),
(43, 1, 'Madinat Zayed', NULL, NULL, NULL, 1, '2025-05-13 05:06:29', '2025-05-13 05:06:29'),
(44, 1, 'Ruwais', NULL, NULL, NULL, 1, '2025-05-13 05:06:35', '2025-05-13 05:06:35'),
(45, 1, 'Ghayathi', NULL, NULL, NULL, 1, '2025-05-13 05:06:40', '2025-05-13 05:06:40'),
(46, 1, 'Liwa', NULL, NULL, NULL, 1, '2025-05-13 05:06:47', '2025-05-13 05:06:47'),
(47, 1, 'Mirfa', NULL, NULL, NULL, 1, '2025-05-13 05:06:54', '2025-05-13 05:06:54'),
(48, 1, 'Sila', NULL, NULL, NULL, 1, '2025-05-13 05:07:00', '2025-05-13 05:07:00'),
(49, 1, 'Dalma Island', NULL, NULL, NULL, 1, '2025-05-13 05:07:05', '2025-05-13 05:07:05'),
(50, 1, 'Al Dhannah', NULL, NULL, NULL, 1, '2025-05-13 05:07:11', '2025-05-13 05:07:11'),
(51, 1, 'Tarif', NULL, NULL, NULL, 1, '2025-05-13 05:07:16', '2025-05-13 05:07:16'),
(52, 1, 'Bida Zayed', NULL, NULL, NULL, 1, '2025-05-13 05:07:23', '2025-05-13 05:07:23'),
(53, 2, 'Downtown Dubai', NULL, NULL, NULL, 1, '2025-05-13 05:10:34', '2025-05-13 05:10:34'),
(54, 2, 'Business Bay', NULL, NULL, NULL, 1, '2025-05-13 05:10:42', '2025-05-13 05:10:42'),
(55, 2, 'DIFC (Dubai International Financial Centre)', NULL, NULL, NULL, 1, '2025-05-13 05:10:50', '2025-05-13 05:10:50'),
(56, 2, 'Za’abeel', NULL, NULL, NULL, 1, '2025-05-13 05:11:01', '2025-05-13 05:11:01'),
(57, 2, 'Al Satwa', NULL, NULL, NULL, 1, '2025-05-13 05:11:08', '2025-05-13 05:11:08'),
(58, 2, 'Al Jafiliya', NULL, NULL, NULL, 1, '2025-05-13 05:11:16', '2025-05-13 05:11:16'),
(60, 2, 'Al Karama', NULL, NULL, NULL, 1, '2025-05-13 05:20:24', '2025-05-13 05:20:24'),
(61, 2, 'Al Mankhool', NULL, NULL, NULL, 1, '2025-05-13 05:20:31', '2025-05-13 05:20:31'),
(62, 2, 'Oud Metha', NULL, NULL, NULL, 1, '2025-05-13 05:20:37', '2025-05-13 05:20:37'),
(63, 2, 'Al Raffa', NULL, NULL, NULL, 1, '2025-05-13 05:20:44', '2025-05-13 05:20:44'),
(64, 2, 'Al Hudaiba', NULL, NULL, NULL, 1, '2025-05-13 05:20:56', '2025-05-13 05:20:56'),
(65, 2, 'Al Bada’a', NULL, NULL, NULL, 1, '2025-05-13 05:21:03', '2025-05-13 05:21:03'),
(66, 2, 'Bur Dubai', NULL, NULL, NULL, 1, '2025-05-13 05:21:17', '2025-05-13 05:21:17'),
(67, 2, 'Al Fahidi', NULL, NULL, NULL, 1, '2025-05-13 05:21:23', '2025-05-13 05:21:23'),
(68, 2, 'Al Seef', NULL, NULL, NULL, 1, '2025-05-13 05:21:30', '2025-05-13 05:21:30'),
(69, 2, 'Al Souk Al Kabir', NULL, NULL, NULL, 1, '2025-05-13 05:21:36', '2025-05-13 05:21:36'),
(70, 2, 'Deira', NULL, NULL, NULL, 1, '2025-05-13 05:21:43', '2025-05-13 05:21:43'),
(71, 2, 'Al Rigga', NULL, NULL, NULL, 1, '2025-05-13 05:21:56', '2025-05-13 05:21:56'),
(72, 2, 'Al Muraqqabat', NULL, NULL, NULL, 1, '2025-05-13 05:22:04', '2025-05-13 05:22:04'),
(73, 2, 'Al Baraha', NULL, NULL, NULL, 1, '2025-05-13 05:22:13', '2025-05-13 05:22:13'),
(74, 2, 'Naif', NULL, NULL, NULL, 1, '2025-05-13 05:22:23', '2025-05-13 05:22:23'),
(75, 2, 'Al Ras', NULL, NULL, NULL, 1, '2025-05-13 05:22:30', '2025-05-13 05:22:30'),
(76, 2, 'Hor Al Anz', NULL, NULL, NULL, 1, '2025-05-13 05:22:37', '2025-05-13 05:22:37'),
(77, 2, 'Port Saeed', NULL, NULL, NULL, 1, '2025-05-13 05:22:45', '2025-05-13 05:22:45'),
(78, 2, 'Dubai Marina', NULL, NULL, NULL, 1, '2025-05-13 05:22:54', '2025-05-13 05:22:54'),
(79, 2, 'Jumeirah Beach Residence (JBR)', NULL, NULL, NULL, 1, '2025-05-13 05:23:03', '2025-05-13 05:23:03'),
(80, 2, 'Jumeirah Lakes Towers (JLT)', NULL, NULL, NULL, 1, '2025-05-13 05:23:16', '2025-05-13 05:23:16'),
(81, 2, 'Bluewaters Island', NULL, NULL, NULL, 1, '2025-05-13 05:24:07', '2025-05-13 05:24:07'),
(82, 2, 'Palm Jumeirah', NULL, NULL, NULL, 1, '2025-05-13 05:24:14', '2025-05-13 05:24:14'),
(83, 2, 'The Greens', NULL, NULL, NULL, 1, '2025-05-13 05:24:22', '2025-05-13 05:24:22'),
(84, 2, 'The Views', NULL, NULL, NULL, 1, '2025-05-13 05:24:28', '2025-05-13 05:24:28'),
(85, 2, 'Emirates Hills', NULL, NULL, NULL, 1, '2025-05-13 05:24:34', '2025-05-13 05:24:34'),
(86, 2, 'The Meadows', NULL, NULL, NULL, 1, '2025-05-13 05:24:40', '2025-05-13 05:24:40'),
(87, 2, 'The Springs', NULL, NULL, NULL, 1, '2025-05-13 05:24:46', '2025-05-13 05:24:46'),
(88, 2, 'The Lakes', NULL, NULL, NULL, 1, '2025-05-13 05:24:53', '2025-05-13 05:24:53'),
(89, 2, 'Jumeirah 1, 2, 3', NULL, NULL, NULL, 1, '2025-05-13 05:25:02', '2025-05-13 05:25:02'),
(90, 2, 'Umm Suqeim 1, 2, 3', NULL, NULL, NULL, 1, '2025-05-13 05:25:11', '2025-05-13 05:25:11'),
(91, 2, 'Al Safa', NULL, NULL, NULL, 1, '2025-05-13 05:25:19', '2025-05-13 05:25:19'),
(92, 2, 'Al Wasl', NULL, NULL, NULL, 1, '2025-05-13 05:25:26', '2025-05-13 05:25:26'),
(93, 2, 'Al Manara', NULL, NULL, NULL, 1, '2025-05-13 05:25:33', '2025-05-13 05:25:33'),
(94, 2, 'Al Sufouh', NULL, NULL, NULL, 1, '2025-05-13 05:25:41', '2025-05-13 05:25:41'),
(95, 2, 'Al Quoz 1, 2, 3, 4', NULL, NULL, NULL, 1, '2025-05-13 05:25:47', '2025-05-13 05:25:47'),
(96, 2, 'Al Quoz Industrial Area', NULL, NULL, NULL, 1, '2025-05-13 05:25:53', '2025-05-13 05:25:53'),
(97, 2, 'Al Khail Gate', NULL, NULL, NULL, 1, '2025-05-13 05:26:00', '2025-05-13 05:26:00'),
(98, 2, 'Al Barsha 1, 2, 3', NULL, NULL, NULL, 1, '2025-05-13 05:26:07', '2025-05-13 05:26:07'),
(99, 2, 'Barsha Heights (Tecom)', NULL, NULL, NULL, 1, '2025-05-13 05:26:23', '2025-05-13 05:26:23'),
(100, 2, 'Dubai Hills Estate', NULL, NULL, NULL, 1, '2025-05-13 05:26:30', '2025-05-13 05:26:30'),
(101, 2, 'Arabian Ranches', NULL, NULL, NULL, 1, '2025-05-13 05:26:37', '2025-05-13 05:26:37'),
(102, 2, 'DAMAC Hills', NULL, NULL, NULL, 1, '2025-05-13 05:26:46', '2025-05-13 05:26:46'),
(103, 2, 'Tilal Al Ghaf', NULL, NULL, NULL, 1, '2025-05-13 05:26:53', '2025-05-13 05:26:53'),
(104, 2, 'Motor City', NULL, NULL, NULL, 1, '2025-05-13 05:27:00', '2025-05-13 05:27:00'),
(105, 2, 'Sports City', NULL, NULL, NULL, 1, '2025-05-13 05:27:06', '2025-05-13 05:27:06'),
(106, 2, 'Jumeirah Village Circle (JVC)', NULL, NULL, NULL, 1, '2025-05-13 05:27:14', '2025-05-13 05:27:14'),
(107, 2, 'Jumeirah Village Triangle (JVT)', NULL, NULL, NULL, 1, '2025-05-13 05:27:20', '2025-05-13 05:27:20'),
(108, 2, 'Discovery Gardens', NULL, NULL, NULL, 1, '2025-05-13 05:27:25', '2025-05-13 05:27:25'),
(109, 2, 'Al Furjan', NULL, NULL, NULL, 1, '2025-05-13 05:27:32', '2025-05-13 05:27:32'),
(110, 2, 'The Gardens', NULL, NULL, NULL, 1, '2025-05-13 05:27:39', '2025-05-13 05:27:39'),
(111, 2, 'Dubai South', NULL, NULL, NULL, 1, '2025-05-13 05:27:45', '2025-05-13 05:27:45'),
(112, 2, 'Dubai Investments Park (DIP)', NULL, NULL, NULL, 1, '2025-05-13 05:27:53', '2025-05-13 05:27:53'),
(113, 2, 'International City', NULL, NULL, NULL, 1, '2025-05-13 05:28:00', '2025-05-13 05:28:00'),
(114, 2, 'Dubai Silicon Oasis (DSO)', NULL, NULL, NULL, 1, '2025-05-13 05:28:06', '2025-05-13 05:28:06'),
(115, 2, 'Mirdif', NULL, NULL, NULL, 1, '2025-05-13 05:28:13', '2025-05-13 05:28:13'),
(116, 2, 'Al Warqa', NULL, NULL, NULL, 1, '2025-05-13 05:28:21', '2025-05-13 05:28:21'),
(117, 2, 'Al Mizhar', NULL, NULL, NULL, 1, '2025-05-13 05:28:31', '2025-05-13 05:28:31'),
(118, 2, 'Al Twar', NULL, NULL, NULL, 1, '2025-05-13 05:28:39', '2025-05-13 05:28:39'),
(119, 2, 'Muhaisnah', NULL, NULL, NULL, 1, '2025-05-13 05:28:46', '2025-05-13 05:28:46'),
(120, 3, 'Al Majaz', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(121, 3, 'Al Khan', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(122, 3, 'Al Qasba', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(123, 3, 'Al Qasimia', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(124, 3, 'Al Nabba', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(125, 3, 'Al Gharb', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(126, 3, 'Al Shuwaihean', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(127, 3, 'Al Layyeh', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(128, 3, 'Al Mujarrah', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(129, 3, 'Al Zahra’a', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(130, 3, 'Al Musalla', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(131, 3, 'Rolla Area', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(132, 3, 'Al Nahda', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(133, 3, 'Al Taawun', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(134, 3, 'Al Yarmook', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(135, 3, 'Al Fisht', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(136, 3, 'Al Jazzat', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(137, 3, 'Al Azra', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(138, 3, 'Al Falaj', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(139, 3, 'Al Ramla', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(140, 3, 'Al Ghubaiba', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(141, 3, 'Al Hazannah', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(142, 3, 'Al Shahba', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(143, 3, 'Al Qarayen 1', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(144, 3, 'Al Qarayen 2', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(145, 3, 'Al Qarayen 3', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(146, 3, 'Al Qarayen 4', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(147, 3, 'Al Qarayen 5', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(148, 3, 'Al Ramaqiya', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(149, 3, 'Al Suyoh', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(150, 3, 'Muwailih Commercial', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(151, 3, 'Al Rifa’a', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(152, 3, 'Al Mamzar', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(153, 3, 'Industrial Area 1', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(154, 3, 'Industrial Area 2', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(155, 3, 'Industrial Area 3', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(156, 3, 'Industrial Area 4', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(157, 3, 'Industrial Area 5', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(158, 3, 'Industrial Area 6', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(159, 3, 'Industrial Area 7', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(160, 3, 'Industrial Area 8', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(161, 3, 'Industrial Area 9', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(162, 3, 'Industrial Area 10', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(163, 3, 'Industrial Area 11', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(164, 3, 'Industrial Area 12', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(165, 3, 'Industrial Area 13', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(166, 3, 'Industrial Area 14', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(167, 3, 'Industrial Area 15', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(168, 3, 'Industrial Area 16', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(169, 3, 'Industrial Area 17', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(170, 3, 'Industrial Area 18', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(171, 3, 'University City', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(172, 3, 'Al Heerah Suburb', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(173, 3, 'Al Muntazah', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(174, 3, 'Al Saja’a Industrial Area', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(175, 3, 'Al Haray Area', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(176, 3, 'Al Zubair', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(177, 3, 'Al Dhaid', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(178, 3, 'Kalba', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(179, 3, 'Khor Fakkan', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(180, 3, 'Dibba Al-Hisn', NULL, NULL, NULL, 1, '2025-05-13 09:48:05', '2025-05-13 09:48:05'),
(181, 4, 'Ajman City', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(182, 4, 'Al Nuaimiya', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(183, 4, 'Al Rashidiya', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(184, 4, 'Al Mowaihat', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(185, 4, 'Al Jurf', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(186, 4, 'Al Rawda', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(187, 4, 'Al Hamidiya', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(188, 4, 'Al Zahra', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(189, 4, 'Al Bustan', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(190, 4, 'Al Rumailah', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(191, 4, 'Al Rashidya 1', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(192, 4, 'Al Rashidya 2', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(193, 4, 'Al Rashidya 3', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(194, 4, 'Al Nakhil', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(195, 4, 'Musherief', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(196, 4, 'Helio', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(197, 4, 'Al Bayt Metwahid', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(198, 4, 'Emirates City', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(199, 4, 'Al Manama', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(200, 4, 'Masfout', NULL, NULL, NULL, 1, '2025-05-13 09:49:16', '2025-05-13 09:49:16'),
(201, 5, 'Fujairah City', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(202, 5, 'Dibba Al-Fujairah', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(203, 5, 'Mirbah', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(204, 5, 'Qidfa', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(205, 5, 'Khor Kalba', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(206, 5, 'Gurfa', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(207, 5, 'Al Faseel', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(208, 5, 'Al Qurayyah', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(209, 5, 'Al Bidiyah', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(210, 5, 'Al Aqah', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(211, 5, 'Al Halah', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(212, 5, 'Wadi Mai', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(213, 5, 'Wadi Siji', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(214, 5, 'Wadi Al Helo', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(215, 5, 'Masafi (Fujairah Side)', NULL, NULL, NULL, 1, '2025-05-13 09:50:16', '2025-05-13 09:50:16'),
(216, 6, 'Ras Al Khaimah City', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(217, 6, 'Al Nakheel', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(218, 6, 'Al Mamourah', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(219, 6, 'Al Dhait North', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(220, 6, 'Al Dhait South', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(221, 6, 'Al Rams', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(222, 6, 'Al Jazeera Al Hamra', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(223, 6, 'Al Hamra Village', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(224, 6, 'Al Marjan Island', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(225, 6, 'Khuzam', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(226, 6, 'Seih Al Uraibi', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(227, 6, 'Al Qusaidat', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(228, 6, 'Al Uraibi', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(229, 6, 'Julphar', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(230, 6, 'Al Mairid', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(231, 6, 'Shamal', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(232, 6, 'Ghalilah', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(233, 6, 'Dafan Al Khor', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(234, 6, 'Digdagga', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(235, 6, 'Al Riffa', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(236, 6, 'Al Qir', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(237, 6, 'Masafi (RAK side)', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(238, 6, 'Wadi Shaam', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(239, 6, 'Asimah', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(240, 6, 'Rams', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(241, 6, 'Shaam', NULL, NULL, NULL, 1, '2025-05-13 09:51:05', '2025-05-13 09:51:05'),
(242, 7, 'Umm Al Quwain City', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(243, 7, 'Al Salamah', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(244, 7, 'Al Raas', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(245, 7, 'Al Dar Al Baida', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(246, 7, 'Al Khor', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(247, 7, 'Al Abraq', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(248, 7, 'Al Haditha', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(249, 7, 'Al Ramlah', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(250, 7, 'Falaj Al Mualla', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(251, 7, 'Al Neefa', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(252, 7, 'Al Shuaibah', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12'),
(253, 7, 'Al Harah', NULL, NULL, NULL, 1, '2025-05-13 09:52:12', '2025-05-13 09:52:12');

-- --------------------------------------------------------

--
-- Table structure for table `class_models`
--

DROP TABLE IF EXISTS `class_models`;
CREATE TABLE IF NOT EXISTS `class_models` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `program_id` bigint UNSIGNED NOT NULL,
  `academy_id` bigint UNSIGNED NOT NULL,
  `day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coach_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_models_program_id_foreign` (`program_id`),
  KEY `class_models_academy_id_foreign` (`academy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coach_evaluations`
--

DROP TABLE IF EXISTS `coach_evaluations`;
CREATE TABLE IF NOT EXISTS `coach_evaluations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `evaluation_id` bigint UNSIGNED NOT NULL,
  `coach_id` bigint UNSIGNED NOT NULL,
  `evaluator_type` enum('admin','student') COLLATE utf8mb4_unicode_ci NOT NULL,
  `evaluator_id` bigint UNSIGNED NOT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coach_evaluations_evaluation_id_foreign` (`evaluation_id`),
  KEY `coach_evaluations_coach_id_foreign` (`coach_id`),
  KEY `coach_evaluations_evaluator_id_foreign` (`evaluator_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coach_evaluations`
--

INSERT INTO `coach_evaluations` (`id`, `evaluation_id`, `coach_id`, `evaluator_type`, `evaluator_id`, `submitted_at`, `created_at`, `updated_at`) VALUES
(1, 7, 4, 'admin', 1, '2025-06-14 04:07:16', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(2, 7, 4, 'admin', 4, '2025-06-14 07:13:30', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(3, 1, 4, 'admin', 1, '2025-06-15 03:23:49', '2025-06-15 03:23:49', '2025-06-15 03:23:49');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_native` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso2` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso3` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_iso2_unique` (`iso2`),
  UNIQUE KEY `countries_iso3_unique` (`iso3`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `name_native`, `iso2`, `iso3`, `phone_code`, `currency`, `currency_symbol`, `flag`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'United Arab Emirates', 'الإمارات العربية المتحدة', 'AE', 'ARE', '971', 'AED', 'د.إ', 'countries/68189a99555fe.svg', 1, '2025-05-05 07:01:45', '2025-05-05 07:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(8,2) DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT NULL,
  `valid_from` date NOT NULL,
  `valid_until` date NOT NULL,
  `max_uses` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('general','internal','student') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluations_system_id_foreign` (`system_id`),
  KEY `evaluations_created_by_foreign` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `system_id`, `title`, `description`, `type`, `start_date`, `end_date`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'Quarterly Technical Evaluation - Q2 2025', 'This evaluation aims to assess coaches\' performance during the second quarter of 2025, focusing on technical execution, planning, and communication skills.', 'internal', '2025-04-01', '2025-06-30', 1, 1, '2025-06-12 05:21:01', '2025-06-12 05:26:25'),
(7, 2, 'Quarterly Technical Evaluation - Q1 2025', NULL, 'internal', '2025-01-01', '2025-04-30', 1, 1, '2025-06-12 05:31:10', '2025-06-12 06:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_criteria`
--

DROP TABLE IF EXISTS `evaluation_criteria`;
CREATE TABLE IF NOT EXISTS `evaluation_criteria` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `evaluation_id` bigint UNSIGNED NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input_type` enum('rating','text','yesno') COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` int NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluation_criteria_evaluation_id_foreign` (`evaluation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluation_criteria`
--

INSERT INTO `evaluation_criteria` (`id`, `evaluation_id`, `label`, `input_type`, `weight`, `order`, `required`, `created_at`, `updated_at`) VALUES
(1, 7, 'Lead Quality', 'rating', 5, 1, 1, '2025-06-12 05:45:15', '2025-06-12 05:45:15'),
(2, 7, 'Responsiveness', 'rating', 5, 2, 1, '2025-06-12 05:45:52', '2025-06-12 05:45:52'),
(3, 7, 'Communication Skills', 'rating', 5, 3, 1, '2025-06-12 05:56:25', '2025-06-12 05:56:25'),
(4, 7, 'Problem Solving', 'rating', 5, 4, 1, '2025-06-12 05:56:37', '2025-06-12 05:56:37'),
(5, 7, 'Time Management', 'rating', 5, 5, 1, '2025-06-12 05:56:55', '2025-06-12 05:56:55'),
(6, 7, 'Customer Satisfaction', 'rating', 5, 6, 1, '2025-06-12 05:57:08', '2025-06-12 05:57:08'),
(7, 7, 'Technical Knowledge', 'rating', 5, 7, 1, '2025-06-12 05:58:15', '2025-06-12 05:58:15'),
(8, 7, 'Attention to Detail', 'rating', 5, 8, 1, '2025-06-12 05:58:41', '2025-06-12 05:58:41'),
(9, 7, 'Adaptability', 'rating', 5, 9, 1, '2025-06-12 05:58:54', '2025-06-12 05:58:54'),
(10, 7, 'Task Completion Quality', 'rating', 5, 10, 1, '2025-06-12 05:59:06', '2025-06-12 05:59:06'),
(11, 7, 'Attended All Sessions', 'yesno', 1, 11, 1, '2025-06-12 06:29:18', '2025-06-12 06:29:18'),
(12, 7, 'Submitted All Assignments', 'yesno', 1, 12, 1, '2025-06-12 06:29:30', '2025-06-12 06:29:30'),
(13, 7, 'Met Deadlines', 'yesno', 1, 13, 1, '2025-06-12 06:29:45', '2025-06-12 06:29:45'),
(14, 7, 'Followed Instructions Properly', 'yesno', 1, 14, 1, '2025-06-12 06:29:57', '2025-06-12 06:29:57'),
(15, 7, 'Maintained Professionalism', 'yesno', 1, 15, 1, '2025-06-12 06:30:11', '2025-06-12 06:30:11'),
(16, 7, 'General Comments', 'text', 1, 16, 1, '2025-06-12 06:30:31', '2025-06-12 06:30:31'),
(17, 7, 'Suggestions for Improvement', 'text', 1, 17, 0, '2025-06-12 06:30:46', '2025-06-12 06:30:46'),
(18, 7, 'Strengths Observed', 'text', 1, 18, 0, '2025-06-12 06:31:02', '2025-06-12 06:31:02'),
(19, 7, 'Areas of Concern', 'text', 1, 19, 0, '2025-06-12 06:31:16', '2025-06-12 06:31:16'),
(20, 7, 'Notes from Evaluator', 'text', 1, 20, 0, '2025-06-12 06:31:30', '2025-06-12 06:31:30'),
(22, 7, 'Time Management', 'rating', 1, 0, 1, '2025-06-12 06:43:34', '2025-06-12 06:43:34'),
(25, 5, 'Problem Solving', 'rating', 1, 0, 1, '2025-06-12 07:06:41', '2025-06-12 07:06:41'),
(30, 1, 'Lead Quality', 'rating', 1, 0, 1, '2025-06-14 04:57:20', '2025-06-14 04:57:20'),
(31, 1, 'Responsiveness', 'rating', 1, 0, 1, '2025-06-14 04:57:28', '2025-06-14 04:57:28'),
(32, 1, 'Communication Skills', 'rating', 1, 0, 1, '2025-06-14 04:57:35', '2025-06-14 04:57:35'),
(33, 1, 'Problem Solving', 'rating', 1, 0, 1, '2025-06-14 04:57:46', '2025-06-14 04:57:46'),
(34, 1, 'Time Management', 'rating', 1, 0, 1, '2025-06-14 04:57:51', '2025-06-14 04:57:51'),
(35, 1, 'Customer Satisfaction', 'rating', 1, 0, 1, '2025-06-14 04:57:59', '2025-06-14 04:57:59'),
(36, 1, 'Technical Knowledge', 'rating', 1, 0, 1, '2025-06-14 04:58:05', '2025-06-14 04:58:05'),
(37, 1, 'Attention to Detail', 'rating', 1, 0, 1, '2025-06-14 04:58:11', '2025-06-14 04:58:11'),
(38, 1, 'Adaptability', 'rating', 1, 0, 1, '2025-06-14 04:58:18', '2025-06-14 04:58:18'),
(39, 1, 'Task Completion Quality', 'rating', 1, 0, 1, '2025-06-14 04:58:24', '2025-06-14 04:58:24'),
(40, 1, 'Attended All Sessions', 'yesno', 1, 11, 1, '2025-06-14 04:59:17', '2025-06-14 04:59:17'),
(41, 1, 'Attended All Sessions', 'rating', 1, 12, 1, '2025-06-14 04:59:51', '2025-06-14 04:59:51'),
(42, 1, 'Submitted All Assignments', 'yesno', 1, 13, 1, '2025-06-14 05:01:25', '2025-06-14 05:01:25'),
(43, 1, 'Met Deadlines', 'yesno', 1, 0, 1, '2025-06-14 05:01:38', '2025-06-14 05:01:38'),
(44, 1, 'Followed Instructions Properly', 'yesno', 1, 15, 1, '2025-06-14 05:01:52', '2025-06-14 05:01:52'),
(45, 1, 'Maintained Professionalism', 'yesno', 1, 16, 1, '2025-06-14 05:02:03', '2025-06-14 05:02:03'),
(46, 1, 'General Comments', 'text', 1, 17, 1, '2025-06-14 05:02:13', '2025-06-14 05:02:13'),
(47, 1, 'Suggestions for Improvement', 'rating', 1, 18, 1, '2025-06-14 05:02:28', '2025-06-14 05:03:54'),
(48, 1, 'Strengths Observed', 'rating', 1, 19, 1, '2025-06-14 05:02:34', '2025-06-14 05:03:52'),
(49, 1, 'Areas of Concern', 'rating', 1, 20, 1, '2025-06-14 05:02:41', '2025-06-14 05:03:50'),
(50, 1, 'Notes from Evaluator', 'rating', 1, 21, 1, '2025-06-14 05:02:47', '2025-06-14 05:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_responses`
--

DROP TABLE IF EXISTS `evaluation_responses`;
CREATE TABLE IF NOT EXISTS `evaluation_responses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `coach_evaluation_id` bigint UNSIGNED NOT NULL,
  `criteria_id` bigint UNSIGNED NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluation_responses_coach_evaluation_id_foreign` (`coach_evaluation_id`),
  KEY `evaluation_responses_criteria_id_foreign` (`criteria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluation_responses`
--

INSERT INTO `evaluation_responses` (`id`, `coach_evaluation_id`, `criteria_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 22, '5', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(2, 1, 1, '5', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(3, 1, 2, '5', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(4, 1, 3, '3', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(5, 1, 4, '1', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(6, 1, 5, '5', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(7, 1, 6, '4', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(8, 1, 7, '2', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(9, 1, 8, '4', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(10, 1, 9, '3', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(11, 1, 10, '5', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(12, 1, 11, 'no', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(13, 1, 12, 'yes', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(14, 1, 13, 'yes', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(15, 1, 14, 'yes', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(16, 1, 15, 'no', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(17, 1, 16, 'Velit consectetur v', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(18, 1, 17, 'Ut est minima provid', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(19, 1, 18, 'Ratione repellendus', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(20, 1, 19, 'Repellendus Ipsum', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(21, 1, 20, 'Nulla error molestia', '2025-06-14 04:07:16', '2025-06-14 04:07:16'),
(22, 2, 22, '4', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(23, 2, 1, '4', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(24, 2, 2, '3', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(25, 2, 3, '1', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(26, 2, 4, '3', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(27, 2, 5, '2', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(28, 2, 6, '2', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(29, 2, 7, '2', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(30, 2, 8, '5', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(31, 2, 9, '3', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(32, 2, 10, '4', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(33, 2, 11, 'no', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(34, 2, 12, 'no', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(35, 2, 13, 'no', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(36, 2, 14, 'no', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(37, 2, 15, 'no', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(38, 2, 16, 'Nostrud exercitation', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(39, 2, 17, 'Incidunt ipsum qui', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(40, 2, 18, 'Totam commodo id co', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(41, 2, 19, 'Exercitationem ad fu', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(42, 2, 20, 'Elit ex quis quo mo', '2025-06-14 07:13:30', '2025-06-14 07:13:30'),
(43, 3, 30, '4', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(44, 3, 31, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(45, 3, 32, '4', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(46, 3, 33, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(47, 3, 34, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(48, 3, 35, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(49, 3, 36, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(50, 3, 37, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(51, 3, 38, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(52, 3, 39, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(53, 3, 43, 'yes', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(54, 3, 40, 'yes', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(55, 3, 41, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(56, 3, 42, 'yes', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(57, 3, 44, 'no', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(58, 3, 45, 'yes', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(59, 3, 46, 'dsfsdfs', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(60, 3, 47, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(61, 3, 48, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(62, 3, 49, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49'),
(63, 3, 50, '3', '2025-06-15 03:23:49', '2025-06-15 03:23:49');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_id` bigint UNSIGNED DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_user_id_foreign` (`user_id`),
  KEY `logs_system_id_foreign` (`system_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `system_id`, `action`, `target_type`, `target_id`, `message`, `payload`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'deleted_criterion', 'App\\Models\\EvaluationCriteria', 24, 'Deleted evaluation criterion: asdsadsad', '{\"id\": 24, \"label\": \"asdsadsad\", \"order\": 0, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-12T11:00:46.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-12T11:00:46.000000Z\", \"evaluation_id\": 7}', '2025-06-12 07:01:21', '2025-06-12 07:01:21'),
(2, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 25, 'Created new evaluation criterion: Problem Solving', '{\"id\": 25, \"label\": \"Problem Solving\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-12T11:06:41.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-12T11:06:41.000000Z\", \"evaluation_id\": \"5\"}', '2025-06-12 07:06:41', '2025-06-12 07:06:41'),
(3, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 26, 'Created new evaluation criterion: sss', '{\"id\": 26, \"label\": \"sss\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-12T11:06:45.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-12T11:06:45.000000Z\", \"evaluation_id\": \"5\"}', '2025-06-12 07:06:45', '2025-06-12 07:06:45'),
(4, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 27, 'Created new evaluation criterion: Communication Skills', '{\"id\": 27, \"label\": \"Communication Skills\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-12T11:06:56.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-12T11:06:56.000000Z\", \"evaluation_id\": \"5\"}', '2025-06-12 07:06:56', '2025-06-12 07:06:56'),
(5, 1, NULL, 'updated_evaluation', 'App\\Models\\Evaluation', 5, 'Updated evaluation: Sunt voluptas quo do', NULL, '2025-06-12 07:06:57', '2025-06-12 07:06:57'),
(6, 1, NULL, 'deleted_evaluation', 'App\\Models\\Evaluation', 5, 'Deleted evaluation: Sunt voluptas quo do', NULL, '2025-06-12 07:07:14', '2025-06-12 07:07:14'),
(7, 1, NULL, 'created_evaluation', 'App\\Models\\Evaluation', 9, 'Created evaluation: Repellendus Aut dol', NULL, '2025-06-12 07:08:43', '2025-06-12 07:08:43'),
(8, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 28, 'Created new evaluation criterion: adasdsadasdas', '{\"id\": 28, \"label\": \"adasdsadasdas\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-12T11:08:56.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-12T11:08:56.000000Z\", \"evaluation_id\": \"9\"}', '2025-06-12 07:08:56', '2025-06-12 07:08:56'),
(9, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 29, 'Created new evaluation criterion: asdadasdas', '{\"id\": 29, \"label\": \"asdadasdas\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-12T11:08:58.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-12T11:08:58.000000Z\", \"evaluation_id\": \"9\"}', '2025-06-12 07:08:58', '2025-06-12 07:08:58'),
(10, 1, NULL, 'deleted_evaluation', 'App\\Models\\Evaluation', 9, 'Deleted evaluation: Repellendus Aut dol', NULL, '2025-06-12 07:09:17', '2025-06-12 07:09:17'),
(11, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 30, 'Created new evaluation criterion: Lead Quality', '{\"id\": 30, \"label\": \"Lead Quality\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:57:20.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:57:20.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:57:21', '2025-06-14 04:57:21'),
(12, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 31, 'Created new evaluation criterion: Responsiveness', '{\"id\": 31, \"label\": \"Responsiveness\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:57:28.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:57:28.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:57:28', '2025-06-14 04:57:28'),
(13, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 32, 'Created new evaluation criterion: Communication Skills', '{\"id\": 32, \"label\": \"Communication Skills\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:57:35.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:57:35.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:57:35', '2025-06-14 04:57:35'),
(14, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 33, 'Created new evaluation criterion: Problem Solving', '{\"id\": 33, \"label\": \"Problem Solving\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:57:46.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:57:46.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:57:46', '2025-06-14 04:57:46'),
(15, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 34, 'Created new evaluation criterion: Time Management', '{\"id\": 34, \"label\": \"Time Management\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:57:51.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:57:51.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:57:51', '2025-06-14 04:57:51'),
(16, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 35, 'Created new evaluation criterion: Customer Satisfaction', '{\"id\": 35, \"label\": \"Customer Satisfaction\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:57:59.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:57:59.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:57:59', '2025-06-14 04:57:59'),
(17, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 36, 'Created new evaluation criterion: Technical Knowledge', '{\"id\": 36, \"label\": \"Technical Knowledge\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:58:05.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:58:05.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:58:05', '2025-06-14 04:58:05'),
(18, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 37, 'Created new evaluation criterion: Attention to Detail', '{\"id\": 37, \"label\": \"Attention to Detail\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:58:11.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:58:11.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:58:11', '2025-06-14 04:58:11'),
(19, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 38, 'Created new evaluation criterion: Adaptability', '{\"id\": 38, \"label\": \"Adaptability\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:58:18.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:58:18.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:58:18', '2025-06-14 04:58:18'),
(20, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 39, 'Created new evaluation criterion: Task Completion Quality', '{\"id\": 39, \"label\": \"Task Completion Quality\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:58:24.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:58:24.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:58:24', '2025-06-14 04:58:24'),
(21, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 40, 'Created new evaluation criterion: Attended All Sessions', '{\"id\": 40, \"label\": \"Attended All Sessions\", \"order\": \"11\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:59:17.000000Z\", \"input_type\": \"yesno\", \"updated_at\": \"2025-06-14T08:59:17.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:59:17', '2025-06-14 04:59:17'),
(22, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 41, 'Created new evaluation criterion: Attended All Sessions', '{\"id\": 41, \"label\": \"Attended All Sessions\", \"order\": \"12\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T08:59:51.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T08:59:51.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 04:59:51', '2025-06-14 04:59:51'),
(23, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 42, 'Created new evaluation criterion: Submitted All Assignments', '{\"id\": 42, \"label\": \"Submitted All Assignments\", \"order\": \"13\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:01:25.000000Z\", \"input_type\": \"yesno\", \"updated_at\": \"2025-06-14T09:01:25.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:01:25', '2025-06-14 05:01:25'),
(24, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 43, 'Created new evaluation criterion: Met Deadlines', '{\"id\": 43, \"label\": \"Met Deadlines\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:01:38.000000Z\", \"input_type\": \"yesno\", \"updated_at\": \"2025-06-14T09:01:38.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:01:39', '2025-06-14 05:01:39'),
(25, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 44, 'Created new evaluation criterion: Followed Instructions Properly', '{\"id\": 44, \"label\": \"Followed Instructions Properly\", \"order\": \"15\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:01:52.000000Z\", \"input_type\": \"yesno\", \"updated_at\": \"2025-06-14T09:01:52.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:01:52', '2025-06-14 05:01:52'),
(26, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 45, 'Created new evaluation criterion: Maintained Professionalism', '{\"id\": 45, \"label\": \"Maintained Professionalism\", \"order\": \"16\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:02:03.000000Z\", \"input_type\": \"yesno\", \"updated_at\": \"2025-06-14T09:02:03.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:02:03', '2025-06-14 05:02:03'),
(27, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 46, 'Created new evaluation criterion: General Comments', '{\"id\": 46, \"label\": \"General Comments\", \"order\": \"17\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:02:13.000000Z\", \"input_type\": \"text\", \"updated_at\": \"2025-06-14T09:02:13.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:02:13', '2025-06-14 05:02:13'),
(28, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 47, 'Created new evaluation criterion: Suggestions for Improvement', '{\"id\": 47, \"label\": \"Suggestions for Improvement\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:02:28.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:02:28.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:02:28', '2025-06-14 05:02:28'),
(29, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 48, 'Created new evaluation criterion: Strengths Observed', '{\"id\": 48, \"label\": \"Strengths Observed\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:02:34.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:02:34.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:02:34', '2025-06-14 05:02:34'),
(30, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 49, 'Created new evaluation criterion: Areas of Concern', '{\"id\": 49, \"label\": \"Areas of Concern\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:02:41.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:02:41.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:02:41', '2025-06-14 05:02:41'),
(31, 1, NULL, 'created_criterion', 'App\\Models\\EvaluationCriteria', 50, 'Created new evaluation criterion: Notes from Evaluator', '{\"id\": 50, \"label\": \"Notes from Evaluator\", \"order\": \"0\", \"weight\": \"1\", \"required\": true, \"created_at\": \"2025-06-14T09:02:47.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:02:47.000000Z\", \"evaluation_id\": \"1\"}', '2025-06-14 05:02:47', '2025-06-14 05:02:47'),
(32, 1, NULL, 'updated_evaluation', 'App\\Models\\Evaluation', 1, 'Updated evaluation: Quarterly Technical Evaluation - Q2 2025', NULL, '2025-06-14 05:03:28', '2025-06-14 05:03:28'),
(33, 1, NULL, 'updated_criterion', 'App\\Models\\EvaluationCriteria', 50, 'Updated evaluation criterion: Notes from Evaluator', '{\"after\": {\"id\": 50, \"label\": \"Notes from Evaluator\", \"order\": 21, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-14T09:02:47.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:03:47.000000Z\", \"evaluation_id\": 1}, \"before\": {\"id\": 50, \"label\": \"Notes from Evaluator\", \"order\": 0, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-14T09:02:47.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:02:47.000000Z\", \"evaluation_id\": 1}}', '2025-06-14 05:03:47', '2025-06-14 05:03:47'),
(34, 1, NULL, 'updated_criterion', 'App\\Models\\EvaluationCriteria', 49, 'Updated evaluation criterion: Areas of Concern', '{\"after\": {\"id\": 49, \"label\": \"Areas of Concern\", \"order\": 20, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-14T09:02:41.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:03:50.000000Z\", \"evaluation_id\": 1}, \"before\": {\"id\": 49, \"label\": \"Areas of Concern\", \"order\": 0, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-14T09:02:41.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:02:41.000000Z\", \"evaluation_id\": 1}}', '2025-06-14 05:03:50', '2025-06-14 05:03:50'),
(35, 1, NULL, 'updated_criterion', 'App\\Models\\EvaluationCriteria', 48, 'Updated evaluation criterion: Strengths Observed', '{\"after\": {\"id\": 48, \"label\": \"Strengths Observed\", \"order\": 19, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-14T09:02:34.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:03:52.000000Z\", \"evaluation_id\": 1}, \"before\": {\"id\": 48, \"label\": \"Strengths Observed\", \"order\": 0, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-14T09:02:34.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:02:34.000000Z\", \"evaluation_id\": 1}}', '2025-06-14 05:03:52', '2025-06-14 05:03:52'),
(36, 1, NULL, 'updated_criterion', 'App\\Models\\EvaluationCriteria', 47, 'Updated evaluation criterion: Suggestions for Improvement', '{\"after\": {\"id\": 47, \"label\": \"Suggestions for Improvement\", \"order\": 18, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-14T09:02:28.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:03:54.000000Z\", \"evaluation_id\": 1}, \"before\": {\"id\": 47, \"label\": \"Suggestions for Improvement\", \"order\": 0, \"weight\": 1, \"required\": true, \"created_at\": \"2025-06-14T09:02:28.000000Z\", \"input_type\": \"rating\", \"updated_at\": \"2025-06-14T09:02:28.000000Z\", \"evaluation_id\": 1}}', '2025-06-14 05:03:54', '2025-06-14 05:03:54'),
(37, 1, NULL, 'updated_evaluation', 'App\\Models\\Evaluation', 1, 'Updated evaluation: Quarterly Technical Evaluation - Q2 2025', NULL, '2025-06-14 05:03:57', '2025-06-14 05:03:57');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_01_073052_create_countries_table', 1),
(5, '2025_05_12_101723_create_states_table', 2),
(6, '2025_05_12_101820_create_cities_table', 2),
(7, '2025_05_13_072625_create_systems_table', 3),
(8, '2025_05_13_084310_create_payment_methods_table', 4),
(10, '2025_05_14_100240_create_branches_table', 5),
(11, '2025_05_22_065141_create_academies_table', 6),
(13, '2025_05_22_082005_add_role_to_users_table', 7),
(14, '2025_05_25_094632_add_language_to_users_table', 8),
(20, '2025_05_27_100353_create_models_table', 10),
(21, '2025_05_27_100748_create_permissions_table', 11),
(19, '2025_05_26_100919_create_roles_table', 9),
(22, '2025_06_09_093022_create_programs_table', 12),
(23, '2025_06_09_093037_create_program_days_table', 12),
(24, '2025_06_09_093044_create_coupons_table', 12),
(25, '2025_06_09_093053_create_program_coupon_table', 12),
(26, '2025_06_09_093351_create_class_models_table', 12),
(27, '2025_06_10_092309_add_branch_and_academy_to_users_table', 13),
(28, '2025_06_12_083649_create_evaluations_table', 14),
(29, '2025_06_12_083812_create_evaluation_criteria_table', 14),
(30, '2025_06_12_083940_create_coach_evaluations_table', 14),
(31, '2025_06_12_084036_create_evaluation_responses_table', 14),
(32, '2025_06_12_090541_create_logs_table', 14),
(33, '2025_06_15_070128_add_profile_image_to_users_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

DROP TABLE IF EXISTS `models`;
CREATE TABLE IF NOT EXISTS `models` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `system_id` bigint UNSIGNED NOT NULL,
  `only_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `models_slug_system_id_unique` (`slug`,`system_id`),
  KEY `models_system_id_foreign` (`system_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id`, `name`, `slug`, `description`, `system_id`, `only_admin`, `created_at`, `updated_at`) VALUES
(6, 'Branch', 'branch', 'This model represents the physical or operational branches under the Ahly Sport system. Each branch is linked to its respective academy or location and helps manage region-specific data and activities.', 2, 0, '2025-05-29 04:17:23', '2025-05-29 04:18:48'),
(5, 'Academy', 'academy', 'This model represents sports academies within the Ahly Sport system. It includes details about the training centers, their structure, and administrative access, and is used to manage and organize academy-related data.', 2, 0, '2025-05-29 04:15:26', '2025-05-29 04:18:42'),
(7, 'City', 'city', 'This model defines the cities associated with the Ahly Sport system. It is used to categorize branches, academies, and other entities based on their geographical location for better organization and filtering.', 2, 1, '2025-05-29 04:18:20', '2025-05-29 04:18:20'),
(8, 'Country', 'country', 'This model stores all countries relevant to the Ahly Sport system. It serves as the top-level geographical classification, allowing for the organization of cities, states, branches, and other entities under their respective countries.', 2, 1, '2025-05-29 04:19:58', '2025-05-29 04:19:58'),
(9, 'Model Entity', 'model_entity', 'This model defines a general-purpose entity within the system, used to group or represent data objects that do not fall under predefined categories. It supports custom logic and structure, and is restricted to administrators for configuration and access control.', 2, 1, '2025-05-29 05:11:45', '2025-05-29 05:14:40'),
(10, 'Payment Method', 'payment_method', 'This model defines the available payment methods used across the system, such as cash, credit card, bank transfer, or digital wallets. It allows flexible configuration and reporting of transactions by method and is essential for tracking financial data accurately.', 2, 1, '2025-05-29 05:12:46', '2025-05-29 05:12:46'),
(11, 'Permission', 'permission', 'This model manages system permissions, defining access rights for specific actions or features within the application. It is used to control user capabilities, enforce role-based security, and ensure that only authorized users can perform sensitive operations.', 2, 1, '2025-05-29 05:14:15', '2025-05-29 05:14:22'),
(12, 'Role', 'role', 'This model defines user roles within the system, grouping a set of permissions to control access to features and modules. It enables role-based access control (RBAC), making it easier to manage user responsibilities and ensure proper authorization throughout the platform.', 2, 1, '2025-05-29 05:15:32', '2025-05-29 05:15:32'),
(13, 'State', 'state', 'This model represents the states or provinces within a country in the system\'s geographical hierarchy. It is used to organize cities, branches, and other entities under their respective regions, enabling location-based filtering and management.', 2, 1, '2025-05-29 05:16:33', '2025-05-29 05:16:33'),
(14, 'System', 'system', 'This model represents core system-level configurations or modules within the application. It is used to manage internal settings, feature toggles, or system metadata that influence global behavior, ensuring centralized and consistent control across the platform.', 2, 1, '2025-05-29 05:17:30', '2025-05-29 05:17:30'),
(15, 'User', 'user', 'This model represents all users within the system, including their personal information, login credentials, roles, and permissions. It is central to authentication and access control, enabling secure and personalized user experiences across the platform.', 2, 0, '2025-05-29 05:18:15', '2025-05-29 05:18:15');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `name_ar`, `name_ur`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cash', 'نقداً', 'نقد', 'Pay using physical cash.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(2, 'Credit Card', 'بطاقة ائتمان', 'کریڈٹ کارڈ', 'Visa, MasterCard and other credit cards.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(3, 'Debit Card', 'بطاقة خصم', 'ڈیبٹ کارڈ', 'Bank debit card payment.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(4, 'Bank Transfer', 'تحويل مصرفي', 'بینک ٹرانسفر', 'Direct bank-to-bank transfer.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(5, 'Apple Pay', 'أبل باي', 'ایپل پے', 'Secure payment via Apple devices.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(6, 'Google Pay', 'جوجل باي', 'گوگل پے', 'Pay using Google Pay app.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(7, 'PayPal', 'باي بال', 'پے پال', 'Secure online payment using PayPal.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(8, 'Cheque', 'شيك', 'چیک', 'Payment via issued cheque.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(9, 'Installments', 'أقساط', 'اقساط', 'Pay in scheduled installments.', 1, '2025-05-13 04:45:40', '2025-05-13 04:45:40', NULL),
(10, 'sdfs', 'عربي', 'عربي', 'سيبسيبسيبسي', 0, '2025-05-13 04:55:16', '2025-05-13 04:55:24', '2025-05-13 04:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_action_role_id_model_id_unique` (`action`,`role_id`,`model_id`),
  KEY `permissions_role_id_foreign` (`role_id`),
  KEY `permissions_model_id_foreign` (`model_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `action`, `role_id`, `model_id`, `created_at`, `updated_at`) VALUES
(1, 'view', 7, 5, '2025-05-29 05:31:46', '2025-05-29 05:31:46'),
(2, 'create', 7, 5, '2025-05-29 05:31:46', '2025-05-29 05:31:46'),
(3, 'update', 7, 5, '2025-05-29 05:31:46', '2025-05-29 05:31:46'),
(4, 'delete', 7, 5, '2025-05-29 05:31:46', '2025-05-29 05:31:46'),
(5, 'export', 7, 5, '2025-05-29 05:31:46', '2025-05-29 05:31:46'),
(7, 'view', 7, 15, '2025-05-29 07:24:03', '2025-05-29 07:24:03'),
(8, 'create', 7, 15, '2025-05-29 07:24:03', '2025-05-29 07:24:03'),
(9, 'update', 7, 15, '2025-05-29 07:24:03', '2025-05-29 07:24:03'),
(10, 'delete', 7, 15, '2025-05-29 07:24:03', '2025-05-29 07:24:03'),
(11, 'export', 7, 15, '2025-05-29 07:24:03', '2025-05-29 07:24:03'),
(12, 'download', 7, 15, '2025-05-29 07:24:03', '2025-05-29 07:24:03');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
CREATE TABLE IF NOT EXISTS `programs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED NOT NULL,
  `academy_id` bigint UNSIGNED NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_count` int NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `vat` decimal(5,2) NOT NULL DEFAULT '5.00',
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AED',
  `is_offer_active` tinyint(1) NOT NULL DEFAULT '0',
  `offer_price` decimal(8,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `programs_system_id_foreign` (`system_id`),
  KEY `programs_branch_id_foreign` (`branch_id`),
  KEY `programs_academy_id_foreign` (`academy_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `system_id`, `branch_id`, `academy_id`, `name_en`, `name_ar`, `name_ur`, `class_count`, `price`, `vat`, `currency`, `is_offer_active`, `offer_price`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 4, 'Shelby Curry', NULL, NULL, 71, 391.00, 66.00, 'USD', 0, NULL, 1, '2025-06-09 06:09:16', '2025-06-09 07:55:07', NULL),
(2, 2, 2, 3, 'Shelby Curry', NULL, NULL, 71, 391.00, 66.00, 'USD', 0, NULL, 1, '2025-06-09 06:09:34', '2025-06-09 06:29:08', '2025-06-09 06:29:08'),
(3, 1, 2, 4, 'Cynthia Vega', NULL, NULL, 81, 246.00, 47.00, 'SAR', 0, NULL, 0, '2025-06-09 06:19:42', '2025-06-09 07:28:13', '2025-06-09 07:28:13'),
(4, 2, 2, 2, 'Evan Carter', NULL, NULL, 74, 404.00, 84.00, 'SAR', 0, NULL, 1, '2025-06-09 07:24:34', '2025-06-09 07:28:10', '2025-06-09 07:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `program_coupon`
--

DROP TABLE IF EXISTS `program_coupon`;
CREATE TABLE IF NOT EXISTS `program_coupon` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `program_id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `program_coupon_program_id_foreign` (`program_id`),
  KEY `program_coupon_coupon_id_foreign` (`coupon_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_days`
--

DROP TABLE IF EXISTS `program_days`;
CREATE TABLE IF NOT EXISTS `program_days` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `program_id` bigint UNSIGNED NOT NULL,
  `day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `program_days_program_id_foreign` (`program_id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_days`
--

INSERT INTO `program_days` (`id`, `program_id`, `day`, `created_at`, `updated_at`) VALUES
(46, 1, 'Saturday', '2025-06-09 07:55:07', '2025-06-09 07:55:07'),
(45, 1, 'Thursday', '2025-06-09 07:55:07', '2025-06-09 07:55:07'),
(44, 1, 'Tuesday', '2025-06-09 07:55:07', '2025-06-09 07:55:07'),
(43, 1, 'Sunday', '2025-06-09 07:55:07', '2025-06-09 07:55:07'),
(5, 2, 'Sunday', '2025-06-09 06:09:34', '2025-06-09 06:09:34'),
(6, 2, 'Tuesday', '2025-06-09 06:09:34', '2025-06-09 06:09:34'),
(7, 2, 'Thursday', '2025-06-09 06:09:34', '2025-06-09 06:09:34'),
(8, 2, 'Saturday', '2025-06-09 06:09:34', '2025-06-09 06:09:34'),
(26, 3, 'Friday', '2025-06-09 06:29:03', '2025-06-09 06:29:03'),
(25, 3, 'Tuesday', '2025-06-09 06:29:03', '2025-06-09 06:29:03'),
(24, 3, 'Monday', '2025-06-09 06:29:03', '2025-06-09 06:29:03'),
(23, 3, 'Sunday', '2025-06-09 06:29:03', '2025-06-09 06:29:03'),
(27, 4, 'Monday', '2025-06-09 07:24:34', '2025-06-09 07:24:34'),
(28, 4, 'Tuesday', '2025-06-09 07:24:34', '2025-06-09 07:24:34'),
(29, 4, 'Thursday', '2025-06-09 07:24:34', '2025-06-09 07:24:34'),
(30, 4, 'Friday', '2025-06-09 07:24:34', '2025-06-09 07:24:34');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_system_id_unique` (`slug`,`system_id`),
  KEY `roles_system_id_foreign` (`system_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `system_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Full Admin', 'full_admin', NULL, 'Global full admin role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(2, 'System Admin', 'system_admin', 1, 'System admin role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(3, 'Branch Admin', 'branch_admin', 1, 'Branch admin role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(4, 'Coach', 'coach', 1, 'Coach role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(5, 'Player', 'player', 1, 'Player role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(6, 'System Admin', 'system_admin', 2, 'System admin role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(7, 'Branch Admin', 'branch_admin', 2, 'Branch admin role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(8, 'Coach', 'coach', 2, 'Coach role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(9, 'Player', 'player', 2, 'Player role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(28, 'HR Manager', 'hr_manager', 4, 'Oversees HR operations and staff', '2025-05-27 05:26:10', '2025-05-27 05:26:10'),
(29, 'Recruiter', 'recruiter', 4, 'Manages job postings and applicants', '2025-05-27 05:26:29', '2025-05-27 05:26:29'),
(30, 'Payroll Specialist', 'payroll_specialist', 4, 'Handles payroll and benefits', '2025-05-27 05:27:04', '2025-05-27 05:27:55'),
(14, 'System Admin', 'system_admin', 4, 'System admin role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(31, 'Training Officer', 'training_officer', 4, 'Manages staff training and sessions', '2025-05-27 05:28:19', '2025-05-27 05:28:19'),
(18, 'System Admin', 'system_admin', 5, 'System admin role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(32, 'HR Assistant', 'hr_assistant', 4, 'Assists with HR paperwork and tasks', '2025-05-27 05:28:42', '2025-05-27 05:28:42'),
(33, 'Leave Approver', 'leave_approver', 4, 'Reviews and approves leave requests', '2025-05-27 05:29:14', '2025-05-27 05:29:14'),
(22, 'System Admin', 'system_admin', 7, 'System admin role', '2025-05-27 05:05:58', '2025-05-27 05:05:58'),
(34, 'Employee', 'employee', 4, 'Can view personal info and apply leave', '2025-05-27 05:29:43', '2025-05-27 05:29:43'),
(35, 'Intern', 'intern', 4, 'Limited access for interns', '2025-05-27 05:30:06', '2025-05-27 05:30:06'),
(36, 'HR Auditor', 'hr_auditor', 4, 'Can view logs and audits', '2025-05-27 05:30:28', '2025-05-27 05:30:28'),
(37, 'Compliance Officer', 'compliance_officer', 4, 'Ensures legal HR compliance', '2025-05-27 05:30:52', '2025-05-27 05:30:52'),
(38, 'Accountant Manager', 'accountant_manager', 5, 'Oversees accounting operations and team', '2025-05-27 05:56:47', '2025-05-27 05:56:47'),
(39, 'Junior Accountant', 'junior_accountant', 5, 'Handles basic accounting tasks and entries', '2025-05-27 05:57:08', '2025-05-27 05:57:08'),
(40, 'Accounts Payable', 'accounts_payable', 5, 'Manages supplier invoices and outgoing payments', '2025-05-27 05:58:05', '2025-05-27 05:58:05'),
(41, 'Accounts Receivable', 'accounts_receivable', 5, 'Manages customer invoices and collections', '2025-05-27 05:58:56', '2025-05-27 05:58:56'),
(42, 'Financial Controller', 'financial_controller', 5, 'Oversees financial reporting and budget control', '2025-05-27 05:59:26', '2025-05-27 05:59:26'),
(43, 'Auditor', 'auditor', 5, 'View-only access for audits and financial checks', '2025-05-27 05:59:46', '2025-05-27 05:59:46'),
(44, 'Expense Reviewer', 'expense_reviewer', 5, 'Reviews and approves expense reports', '2025-05-27 06:00:09', '2025-05-27 06:00:09'),
(45, 'Payroll Accountant', 'payroll_accountant', 5, 'Manages payroll-related accounting', '2025-05-27 06:00:32', '2025-05-27 06:00:32'),
(46, 'Tax Specialist', 'tax_specialist', 5, 'Prepares and monitors tax submissions', '2025-05-27 06:00:51', '2025-05-27 06:00:51'),
(47, 'Budget Analyst', 'budget_analyst', 5, 'Reviews and maintains department budgets', '2025-05-27 06:01:21', '2025-05-27 06:01:21'),
(48, 'Academy Admin', 'academy_admin', 2, NULL, '2025-06-10 07:06:04', '2025-06-10 07:06:04');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('a2mnbd15WPnnACLeAJZue690lDxL9ZMIoY7dBrFn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSHZISGpCckV4ODBSRWZidzBLWWxPb2RIaDllYnZOTjZzNFB5bU1sciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoibG9jYWxlIjtzOjI6ImVuIjt9', 1748250986);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE IF NOT EXISTS `states` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_native` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `states_country_id_foreign` (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `name`, `name_native`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Abu Dhabi', 'أبو ظبي', 'AD', 1, '2025-05-12 06:20:59', '2025-05-12 07:01:44'),
(2, 1, 'Dubai', 'دبي', 'DU', 1, '2025-05-12 06:20:59', '2025-05-12 06:20:59'),
(3, 1, 'Sharjah', 'الشارقة', 'SH', 1, '2025-05-12 06:20:59', '2025-05-12 06:20:59'),
(4, 1, 'Ajman', 'عجمان', 'AJ', 1, '2025-05-12 06:20:59', '2025-05-12 06:20:59'),
(5, 1, 'Fujairah', 'الفجيرة', 'FU', 1, '2025-05-12 06:20:59', '2025-05-12 06:20:59'),
(6, 1, 'Ras Al Khaimah', 'رأس الخيمة', 'RK', 1, '2025-05-12 06:20:59', '2025-05-12 06:20:59'),
(7, 1, 'Umm Al Quwain', 'أم القيوين', 'UQ', 1, '2025-05-12 06:20:59', '2025-05-12 06:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `systems`
--

DROP TABLE IF EXISTS `systems`;
CREATE TABLE IF NOT EXISTS `systems` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `systems`
--

INSERT INTO `systems` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Green Sport', NULL, '2025-05-13 04:05:53', '2025-05-13 04:05:53'),
(2, 'Ahly Sport', NULL, '2025-05-13 04:07:11', '2025-05-13 04:07:11'),
(3, 'Drassa', NULL, '2025-05-13 04:07:32', '2025-05-13 04:07:32'),
(4, 'HR', NULL, '2025-05-13 04:07:38', '2025-05-13 04:07:38'),
(5, 'Accountant', NULL, '2025-05-13 04:08:12', '2025-05-13 04:08:12'),
(7, 'Drassa Plus', NULL, '2025-05-13 04:28:52', '2025-05-13 04:28:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'english',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('full_admin','system_admin','branch_admin','academy_admin','employee','coach','player') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'player',
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `academy_id` json DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_system_id_foreign` (`system_id`),
  KEY `users_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_image`, `language`, `email_verified_at`, `password`, `role`, `system_id`, `branch_id`, `academy_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Full Admin', 'fulladmin@example.com', 'profile_images/Rv9EZqOKxmSSmo7fd5vl9RqUwylJQ3wvmMknjmBt.jpg', 'en', NULL, '$2y$12$Uz9M8uHw6xM4u6p7xA7gIOUqFmrOn3gRbAGrUhcVMrTBTNJO/X60S', 'full_admin', NULL, NULL, NULL, NULL, '2025-05-22 04:42:11', '2025-06-15 03:27:22'),
(2, 'System Admin', 'sysadmin@example.com', NULL, 'english', NULL, '$2y$12$dU1SucyTqkFAb3P97ncUrOLE9T7rkxkkV0QfcxU8.O0uM.ZWFsM5.', 'system_admin', 1, NULL, NULL, NULL, '2025-05-22 04:42:11', '2025-05-22 04:42:11'),
(3, 'Branch Admin', 'branchadmin@example.com', NULL, 'english', NULL, '$2y$12$KUe3agJHGpjcSOAaCzH.meUccgaipWlpQxrS/OIgzR2c0YRj0cbUq', 'branch_admin', 1, NULL, NULL, NULL, '2025-05-22 04:42:12', '2025-05-22 04:42:12'),
(4, 'Coach John', 'coach@example.com', 'profile_images/5rFHRtbzfjnyVxEIXgBpNyHuqpmb0cf9RDGCSMUf.jpg', 'en', NULL, '$2y$12$SUiCA5Hal1Uoc0HWlWnLwO/hETydG2.54AYxkfY1PiJH6Hy18nb4i', 'coach', 2, NULL, NULL, NULL, '2025-05-22 04:42:12', '2025-06-15 05:17:21'),
(13, 'player', 'player@example.com', NULL, 'ar', NULL, '$2y$12$biFvHUwzdVkXivPFfsI.keKh1bWGSTWdytfBG/NqpN.CrjhCMnRFG', 'player', 2, NULL, NULL, NULL, '2025-06-12 04:18:49', '2025-06-12 04:19:09');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

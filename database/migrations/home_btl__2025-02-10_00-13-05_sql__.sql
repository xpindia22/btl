-- phpMyAdmin SQL Dump
-- version 5.2.1deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 10, 2025 at 12:13 AM
-- Server version: 11.4.3-MariaDB-1
-- PHP Version: 8.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `btl`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `field_changed` varchar(255) NOT NULL,
  `old_value` varchar(255) DEFAULT NULL,
  `new_value` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `age_group` varchar(255) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `type` enum('singles','doubles','mixed doubles') DEFAULT 'singles',
  `tournament_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_by`, `age_group`, `sex`, `type`, `tournament_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'U17BS', 1, 'Under 17', 'M', 'singles', 0, NULL, NULL, NULL),
(2, 'U15BS', 1, 'Under 15', 'M', 'singles', 0, NULL, NULL, NULL),
(3, 'U15BD', 1, 'Under 15', 'M', 'doubles', 0, NULL, NULL, NULL),
(4, 'U17BD', 2, 'Under 17', 'M', 'doubles', 0, NULL, NULL, NULL),
(6, 'U15GS', 4, 'Under 15', 'F', 'singles', 0, NULL, NULL, NULL),
(7, 'U15GD', 1, 'Under 15', 'F', 'doubles', 0, NULL, NULL, NULL),
(8, 'U13BS', 1, 'Under 13', 'M', 'singles', 0, NULL, NULL, NULL),
(11, 'Open BS', 4, 'Between 5 - 100', 'M', 'singles', 0, NULL, NULL, NULL),
(12, 'Open GS', 4, 'Between 5 - 100', 'F', 'singles', 0, NULL, NULL, NULL),
(13, 'Open XD', 4, 'Between 5 - 100', 'Mixed', 'mixed doubles', 0, NULL, NULL, NULL),
(14, 'Open BD', 4, 'Between 5 - 100', 'M', 'doubles', 0, NULL, NULL, NULL),
(15, 'Open GD', 4, 'Between 5 - 100', 'F', 'doubles', 0, NULL, NULL, NULL),
(16, 'U17GS', 4, 'Under 17', 'F', 'singles', 0, NULL, NULL, NULL),
(17, 'U17GD', 4, 'Under 17', 'F', 'doubles', 0, NULL, NULL, NULL),
(18, 'U17GD', 4, 'Under 17', 'F', 'doubles', 0, NULL, NULL, NULL),
(19, 'U13GS', 4, 'Under 13', 'F', 'singles', 0, NULL, NULL, NULL),
(20, 'Senior 40 Plus BS', 4, 'Over 40', 'M', 'singles', 0, NULL, NULL, NULL),
(21, 'Senior 40 Plus BD', 4, 'Over 40', 'M', 'doubles', 0, NULL, NULL, NULL),
(22, 'Senior 40 Plus GS', 4, 'Over 40', 'F', 'singles', 0, NULL, NULL, NULL),
(23, 'Senior 40 Plus GD', 4, 'Over 40', 'F', 'doubles', 0, '2025-02-01 23:34:54', NULL, '2025-02-01 23:34:54'),
(25, 'U19BS', 4, 'Under 19', 'M', 'singles', 0, NULL, NULL, NULL),
(26, 'U19BD', 4, 'Under 19', 'M', 'doubles', 0, NULL, NULL, NULL),
(27, 'U19GS', 4, 'Under 19', 'F', 'singles', 0, NULL, NULL, NULL),
(28, 'U19GD', 4, 'Under 19', 'F', 'doubles', 0, NULL, NULL, NULL),
(29, 'U19XD', 4, 'Under 19', 'Mixed', 'mixed doubles', 0, NULL, NULL, NULL),
(30, 'U17XD', 4, 'Under 17', 'Mixed', 'singles', 0, NULL, NULL, NULL),
(31, 'U15XD', 4, 'Under 15', 'Mixed', 'singles', 0, NULL, NULL, NULL),
(32, 'Senior 40 Plus XD', 4, 'Over 40', 'Mixed', 'singles', 0, NULL, NULL, NULL),
(33, 'LU17BS', 4, 'Under 17', 'M', 'singles', NULL, '2025-02-02 02:12:43', '2025-02-01 21:01:05', '2025-02-02 02:12:43');

-- --------------------------------------------------------

--
-- Table structure for table `category_access`
--

CREATE TABLE `category_access` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `pool` enum('A','B') DEFAULT NULL,
  `player1_id` int(11) DEFAULT NULL,
  `player2_id` int(11) DEFAULT NULL,
  `pre_quarter` tinyint(1) DEFAULT 0,
  `quarter` tinyint(1) DEFAULT 0,
  `semi` tinyint(1) DEFAULT 0,
  `final` tinyint(1) DEFAULT 0,
  `set1_player1_points` int(11) DEFAULT 0,
  `set1_player2_points` int(11) DEFAULT 0,
  `set2_player1_points` int(11) DEFAULT 0,
  `set2_player2_points` int(11) DEFAULT 0,
  `set3_player1_points` int(11) DEFAULT 0,
  `set3_player2_points` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `moderated_by` int(11) DEFAULT NULL,
  `stage` enum('Pre Quarter Finals','Quarter Finals','Semifinals','Finals','Preliminary') NOT NULL,
  `match_date` date DEFAULT NULL,
  `match_time` time DEFAULT NULL,
  `team1_player1_id` int(11) NOT NULL DEFAULT 0,
  `team1_player2_id` int(11) NOT NULL DEFAULT 0,
  `team2_player1_id` int(11) DEFAULT 0,
  `team2_player2_id` int(11) DEFAULT 0,
  `set1_team1_points` int(11) NOT NULL DEFAULT 0,
  `set1_team2_points` int(11) DEFAULT 0,
  `set2_team1_points` int(11) DEFAULT 0,
  `set2_team2_points` int(11) DEFAULT 0,
  `set3_team1_points` int(11) DEFAULT 0,
  `set3_team2_points` int(11) DEFAULT 0,
  `player3_id` int(11) DEFAULT NULL,
  `player4_id` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `tournament_id`, `category_id`, `pool`, `player1_id`, `player2_id`, `pre_quarter`, `quarter`, `semi`, `final`, `set1_player1_points`, `set1_player2_points`, `set2_player1_points`, `set2_player2_points`, `set3_player1_points`, `set3_player2_points`, `created_by`, `moderated_by`, `stage`, `match_date`, `match_time`, `team1_player1_id`, `team1_player2_id`, `team2_player1_id`, `team2_player2_id`, `set1_team1_points`, `set1_team2_points`, `set2_team1_points`, `set2_team2_points`, `set3_team1_points`, `set3_team2_points`, `player3_id`, `player4_id`, `deleted_at`) VALUES
(5, 3, 16, NULL, 1, 4, 0, 0, 0, 0, 21, 2, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(7, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(8, 1, 8, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(9, 1, 16, NULL, 1, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(10, 3, 20, NULL, 11, 12, 0, 0, 0, 0, 21, 11, 12, 21, 21, 16, 1, NULL, 'Pre Quarter Finals', '2025-01-02', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(13, 3, 11, NULL, 10, 6, 0, 0, 0, 0, 28, 26, 24, 26, 28, 2, 1, NULL, 'Pre Quarter Finals', '2025-01-03', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(14, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 1, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(15, 1, 11, NULL, 2, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(16, 3, 20, NULL, 10, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 1, NULL, 'Pre Quarter Finals', '2025-01-03', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(17, 3, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Preliminary', '2024-12-30', '00:00:00', 2, 10, 11, 12, 21, 0, 0, 21, 21, 4, NULL, NULL, NULL),
(18, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(19, 1, 11, NULL, 3, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-04', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(20, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-03', '00:00:00', 6, 10, 12, 13, 21, 12, 14, 21, 21, 15, NULL, NULL, NULL),
(21, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2024-12-30', '00:00:00', 6, 2, 13, 12, 21, 4, 4, 21, 21, 2, NULL, NULL, NULL),
(22, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 6, 2, 13, 12, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(23, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 10, 13, 12, 2, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(24, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 2, 3, 11, 12, 21, 2, 2, 21, 21, 3, NULL, NULL, NULL),
(25, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 2, 13, 12, 6, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(26, 1, 26, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-06', '00:00:00', 2, 11, 13, 12, 24, 22, 22, 24, 21, 1, NULL, NULL, NULL),
(27, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Preliminary', '2025-01-06', '00:00:00', 9, 6, 2, 4, 14, 21, 21, 12, 21, 14, NULL, NULL, NULL),
(28, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Preliminary', '2025-01-06', '00:00:00', 9, 6, 2, 4, 1, 21, 21, 2, 7, 21, NULL, NULL, NULL),
(29, 1, 26, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-06', '00:00:00', 12, 3, 6, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(30, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-06', '00:00:00', 13, 11, 2, 3, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(31, 1, 21, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-06', '00:00:00', 6, 11, 12, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(32, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Preliminary', '2025-01-06', '00:00:00', 4, 14, 1, 15, 21, 13, 12, 21, 19, 21, NULL, NULL, NULL),
(33, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-06', '00:00:00', 19, 9, 17, 15, 21, 12, 13, 21, 21, 2, NULL, NULL, NULL),
(34, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-07', '00:00:00', 1, 6, 21, 4, 24, 3, 4, 21, 21, 3, NULL, NULL, NULL),
(35, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-07', '00:00:00', 19, 17, 18, 13, 21, 3, 3, 21, 21, 2, NULL, NULL, NULL),
(36, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Finals', '2025-01-07', '00:00:00', 4, 9, 14, 19, 26, 24, 22, 24, 21, 2, NULL, NULL, NULL),
(37, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Finals', '2025-01-07', '00:00:00', 16, 9, 14, 1, 21, 3, 2, 21, 2, 21, NULL, NULL, NULL),
(38, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-11', '00:00:00', 21, 12, 6, 11, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(39, 14, 11, NULL, 3, 20, 0, 0, 0, 0, 21, 2, 2, 21, 21, 11, 1, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(40, 1, 26, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-15', '06:00:00', 2, 3, 3, 2, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(41, 14, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Semifinals', '2025-01-01', '00:00:00', 3, 21, 11, 20, 3, 21, 12, 2, 2, 21, NULL, NULL, NULL),
(42, 17, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Preliminary', '2025-01-16', '00:00:00', 19, 12, 14, 20, 21, 2, 2, 21, 21, 5, NULL, NULL, NULL),
(43, 17, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Preliminary', '2025-01-16', '00:00:00', 16, 18, 1, 19, 21, 2, 2, 21, 21, 3, NULL, NULL, NULL),
(44, 1, 20, NULL, 21, 12, 0, 0, 0, 0, 21, 2, 1, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(45, 1, 20, NULL, 2, 10, 0, 0, 0, 0, 21, 2, 21, 2, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(46, 1, 20, NULL, 3, 11, 0, 0, 0, 0, 21, 3, 3, 21, 12, 21, NULL, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(47, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(48, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(49, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(50, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(51, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(52, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(53, 1, 20, NULL, 21, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(54, 1, 20, NULL, 21, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(55, 1, 20, NULL, 2, 10, 0, 0, 0, 0, 21, 2, 21, 2, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(56, 1, 20, NULL, 2, 10, 0, 0, 0, 0, 21, 2, 21, 2, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(57, 1, 20, NULL, 2, 10, 0, 0, 0, 0, 21, 2, 21, 2, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(58, 1, 20, NULL, 20, 2, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(59, 1, 20, NULL, 20, 2, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(60, 1, 20, NULL, 2, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(61, 1, 20, NULL, 21, 2, 0, 0, 0, 0, 12, 21, 21, 18, 21, 19, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(62, 1, 20, NULL, 12, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(63, 1, 20, NULL, 2, 3, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(64, 18, 11, NULL, 2, 10, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(65, 18, 11, NULL, 2, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(66, 17, 11, NULL, 2, 21, 0, 0, 0, 0, 1, 3, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(67, 17, 11, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(68, 17, 11, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(69, 17, 11, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(70, 1, 11, NULL, 21, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(71, 1, 11, NULL, 21, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(72, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(73, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(74, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(75, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(76, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(79, 1, 11, NULL, 20, 2, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(80, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(81, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(82, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(83, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(84, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(85, 1, 20, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(86, 1, 20, NULL, 21, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 1, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(87, 1, 20, NULL, 21, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 1, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(88, 1, 20, NULL, 20, 12, 0, 0, 0, 0, 21, 2, 21, 2, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(89, 1, 20, NULL, 20, 12, 0, 0, 0, 0, 21, 2, 21, 2, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(90, 1, 11, NULL, 21, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(91, 1, 11, NULL, 21, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(92, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 1, 2, 21, 21, 1, NULL, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(93, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 1, 2, 21, 21, 1, NULL, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(94, 1, 11, NULL, 6, 11, 0, 0, 0, 0, 21, 2, 21, 2, 1, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-14', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(95, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(96, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(97, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(98, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(99, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(100, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(101, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(102, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(103, 1, 11, NULL, 20, 11, 0, 0, 0, 0, 21, 2, 2, 2, 2, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(104, 1, 11, NULL, 20, 11, 0, 0, 0, 0, 21, 2, 2, 2, 2, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(105, 1, 11, NULL, 20, 11, 0, 0, 0, 0, 21, 2, 2, 2, 2, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(106, 1, 11, NULL, 20, 11, 0, 0, 0, 0, 21, 2, 2, 2, 2, 2, NULL, NULL, 'Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(107, 1, 11, NULL, 2, 13, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(108, 1, 11, NULL, 2, 13, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(109, 1, 11, NULL, 2, 13, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(110, 1, 11, NULL, 2, 13, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(111, 1, 11, NULL, 11, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(112, 1, 11, NULL, 11, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(113, 1, 2, NULL, 3, 22, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(114, 1, 2, NULL, 3, 22, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(115, 1, 11, NULL, 3, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(116, 1, 11, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(117, 1, 11, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:03', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(118, 1, 11, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:11', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(119, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(120, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(121, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(122, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(123, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(124, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:03', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(125, 3, 11, NULL, 3, 11, 0, 0, 0, 0, 21, 1, 1, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:18', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(126, 1, 11, NULL, 6, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(127, 1, 11, NULL, 6, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(128, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(129, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(130, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '16:35:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(131, 1, 11, NULL, 3, 6, 0, 0, 0, 0, 3, 21, 21, 2, 12, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '16:35:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(132, 1, 11, NULL, 21, 20, 0, 0, 0, 0, 21, 19, 5, 21, 21, 5, NULL, NULL, 'Quarter Finals', '2025-01-22', '00:00:20', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(133, 1, 11, NULL, 12, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '21:12:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(146, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-24', '00:00:11', 11, 21, 3, 2, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(147, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-24', '00:00:11', 11, 21, 3, 2, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(148, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-24', '00:00:11', 11, 21, 3, 2, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(149, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '00:00:11', 2, 3, 12, 20, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(150, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '00:00:11', 13, 21, 2, 3, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(151, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '11:58:00', 2, 3, 11, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(152, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '00:00:11', 1, 4, 16, 18, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(153, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '11:59:00', 1, 4, 16, 18, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(154, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '00:00:12', 21, 9, 13, 19, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL),
(155, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '15:03:00', 21, 9, 4, 20, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `match_details`
--

CREATE TABLE `match_details` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `match_type` enum('singles','doubles','mixed') NOT NULL,
  `points_scored` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_28_120456_add_mobile_no_and_role_to_users_table', 2),
(5, '2025_02_01_122406_create_tournaments_table', 1),
(6, '2025_02_01_122407_create_matches_table', 1),
(7, '2025_02_01_122408_create_players_table', 1),
(8, '2025_02_01_122409_create_tournament_moderators_table', 1),
(9, '2025_02_01_160624_create_tournament_moderators_table', 1),
(10, '2025_02_01_170615_add_deleted_at_to_categories', 3),
(11, '2025_02_01_171342_add_deleted_at_to_categories', 4),
(12, '2025_02_02_022700_add_timestamps_to_categories', 5),
(13, '2025_02_02_023030_modify_tournament_id_in_categories', 6),
(14, '2025_02_07_081335_add_two_factor_columns_to_users_table', 7),
(15, '2025_02_09_081111_create_personal_access_tokens_table', 8),
(16, '2025_02_09_153144_add_created_by_to_users_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL,
  `sex` enum('M','F') NOT NULL,
  `uid` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `name`, `dob`, `age`, `sex`, `uid`, `password`, `created_by`, `updated_at`, `category_id`, `created_at`) VALUES
(1, 'Sreesha', '2008-01-01', 16, 'F', '3', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 1, '2025-01-17 04:14:30', 0, '2025-01-24 03:01:50'),
(2, 'Eric James', '2009-05-02', 15, 'M', '1', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 1, '2025-01-17 04:14:30', 0, '2025-01-24 03:01:50'),
(3, 'Akshaj Tiwari', '2012-01-01', 12, 'M', '2', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 1, '2025-01-17 04:14:30', 0, '2025-01-24 03:01:50'),
(4, 'Lakshmita', '2011-01-01', 13, 'F', '4', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 1, '2025-01-17 04:14:30', 0, '2025-01-24 03:01:50'),
(6, 'Lee Chong Wei', '1980-01-03', 44, 'M', '5', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 1, '2025-01-17 04:14:30', 0, '2025-01-24 03:01:50'),
(9, 'Lakshaya', '2010-01-01', 15, 'F', '10', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(10, 'Gokulan', '1990-01-01', 35, 'M', '9', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(11, 'Zanpear', '1978-05-01', 46, 'M', '6', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(12, 'Pandyraj', '1968-01-01', 57, 'M', '7', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 20, '2025-01-24 03:01:50'),
(13, 'Vijay', '1970-01-30', 54, 'M', '8', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(14, 'Tai Tzu Ying', '1998-01-01', 27, 'F', '11', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(15, 'An Se Young', '2000-01-01', 25, 'F', '13', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(16, 'Okuhara', '1998-01-01', 27, 'F', '14', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(17, 'Anitha Anthony', '2008-01-01', 17, 'F', '15', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(18, 'Carolina', '1995-06-06', 29, 'F', '16', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(19, 'PV Sindhu', '1995-06-07', 29, 'F', '12', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(20, 'Victor Axelsen', '1995-05-07', 29, 'M', '17', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(21, 'Lin Dan', '1986-02-06', 38, 'M', '18', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', 4, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50'),
(22, 'Player', '2025-01-17', 0, 'M', '19', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, '2025-01-17 04:14:30', 1, '2025-01-24 03:01:50');

-- --------------------------------------------------------

--
-- Table structure for table `player_access`
--

CREATE TABLE `player_access` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_access`
--

INSERT INTO `player_access` (`id`, `player_id`, `user_id`, `created_at`) VALUES
(2, 9, 4, '2025-01-01 10:50:08'),
(3, 10, 4, '2025-01-01 10:50:55'),
(4, 11, 4, '2025-01-01 10:51:56'),
(5, 12, 4, '2025-01-01 11:35:13'),
(6, 13, 4, '2025-01-01 12:06:34'),
(7, 14, 4, '2025-01-06 11:29:29'),
(8, 15, 4, '2025-01-06 11:30:03'),
(9, 16, 4, '2025-01-06 11:39:10'),
(10, 17, 4, '2025-01-06 11:39:50'),
(11, 18, 4, '2025-01-06 11:40:23'),
(12, 19, 4, '2025-01-06 11:41:22'),
(13, 20, 4, '2025-01-06 11:42:09'),
(14, 21, 4, '2025-01-06 11:42:29');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `year` int(11) NOT NULL DEFAULT year(curdate()),
  `moderated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `name`, `created_by`, `year`, `moderated_by`, `created_at`, `updated_at`) VALUES
(1, 'ABPL3', 1, 2024, 4, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(2, 'Super Series 2024', 2, 2024, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(3, 'Winter Series', 4, 2024, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(6, 'ACE Championship', 1, 2025, 6, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(7, 'xxxxsx', 1, 2025, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(13, 'uuuuh', 5, 2025, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(14, 'xxxxaa', 4, 2025, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(15, 'xxdds', 4, 2025, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(17, 'zzz', 7, 2025, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(18, 'zzzz', 7, 2025, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49'),
(20, 'zlara25', 7, 2025, NULL, '2025-02-08 19:38:45', '2025-02-08 19:38:45');

-- --------------------------------------------------------

--
-- Table structure for table `tournament_categories`
--

CREATE TABLE `tournament_categories` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournament_categories`
--

INSERT INTO `tournament_categories` (`id`, `tournament_id`, `category_id`) VALUES
(3, 2, 4),
(16, 6, 4),
(32, 3, 1),
(33, 3, 11),
(34, 3, 20),
(35, 2, 1),
(74, 1, 1),
(75, 1, 2),
(76, 1, 3),
(77, 1, 13),
(78, 1, 17),
(79, 1, 20),
(80, 1, 21),
(81, 1, 23),
(82, 1, 3),
(83, 1, 26),
(84, 1, 14),
(85, 1, 15),
(86, 1, 13),
(87, 1, 32),
(94, 7, 1),
(97, 13, 18),
(98, 14, 11),
(99, 15, 11),
(100, 14, 11),
(101, 14, 11),
(102, 14, 11),
(103, 14, 11),
(104, 14, 1),
(105, 14, 11),
(106, 14, 14),
(107, 17, 11),
(108, 17, 12),
(109, 17, 13),
(110, 17, 15),
(111, 17, 15),
(112, 18, 11),
(113, 1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `tournament_moderators`
--

CREATE TABLE `tournament_moderators` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tournament_moderators`
--

INSERT INTO `tournament_moderators` (`id`, `tournament_id`, `user_id`) VALUES
(18, 1, 4),
(1, 1, 6),
(17, 1, 7),
(7, 2, 6),
(5, 6, 4),
(3, 13, 6),
(10, 14, 7),
(15, 17, 7),
(16, 18, 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(66) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `last_login` datetime DEFAULT current_timestamp(),
  `profile_picture` varchar(999) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `role`, `created_by`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `mobile_no`, `last_login`, `profile_picture`) VALUES
(1, 'user', 'user@user.com', 'user', NULL, NULL, '$2y$12$MsyjI5qJ2gNqZcpOnWU4QOhq1N.7HU2GQbTW9SkqwaQAWZZ1jrpi.', NULL, NULL, NULL, NULL, '2024-12-28 22:20:28', '2025-02-09 10:42:57', '1111111111', NULL, 'uploads/profiles/1737177818_WhatsApp Image 2025-01-18 at 08.59.27.jpeg'),
(2, 'admin', 'admin@admin.com', 'admin', NULL, NULL, '$2y$10$Vzemd6vNZoJ7tsir9lxqKuBfkPhks/ZL3mB6YRRNKRLg3H8THFdba', NULL, NULL, NULL, NULL, '2024-12-28 22:41:42', NULL, '7432001215', NULL, 'default.png'),
(4, 'xxx', 'xxx@xxx.com', 'admin', NULL, NULL, '$2y$12$ragYOxZSmqDM7sE.rs6ELOe1T1tZZGVMA8gObnKXubnzot/EZHpQ6', NULL, NULL, NULL, NULL, '2024-12-29 06:40:35', '2025-02-01 01:57:28', '333', NULL, 'default.png'),
(5, 'user2', 'user2@jdjdj.com', 'user', NULL, NULL, '$2y$10$h2N1Jb3tCQ72X.KWuQaB8eUfBfJa61DULmbLDzMArIlUdtpj4im.m', NULL, NULL, NULL, NULL, '2024-12-31 09:58:19', NULL, '2222222222', NULL, 'default.png'),
(6, 'user1', 'asda@sd.asda', 'user', NULL, NULL, '$2y$10$630Wk4DbeWyToUcclXn66.2YMBCpUb8/ZwAvZwsbMU72PF3nNWdB2', NULL, NULL, NULL, NULL, '2025-01-10 00:25:38', NULL, '2222222222', NULL, 'default.png'),
(7, 'zzz', 'zzz@zzz.com', 'user', NULL, NULL, '$2y$12$VRCf6/po9K0NC4EMCrfQmunhjxbeXN2b.cmLhsHvDJcqlCCquC6H6', NULL, NULL, NULL, NULL, '2025-01-10 22:23:39', '2025-02-08 19:39:38', '1111111111', NULL, 'default.png'),
(8, 'usernnn', 'user@useqr.com', 'visitor', NULL, NULL, '$2y$10$iN/RiIEqGmxmgHcz.xHgAOBS051B5b9yS.K676o3U4KdgvqikeRfC', NULL, NULL, NULL, NULL, '2025-01-23 07:20:46', NULL, '1111111111', NULL, 'default.png'),
(9, 'qqq', 'qqq@qqq.com', 'visitor', NULL, NULL, '$2y$10$PrtZw4bPL/smtwHz3a.cDe32BW1SDYaeVg5pw..Bsi2e6HvrUXOWO', NULL, NULL, NULL, NULL, '2025-01-27 01:18:13', NULL, '1111111111', NULL, 'default.png'),
(14, 'nnn', 'nnn@nnn.com', 'visitor', NULL, NULL, '$2y$12$M1pGTW.gdl3LHVSUJPaD0ue4Gt4n4fJBo/SSOHGIM6c4czFgQfSC2', NULL, NULL, NULL, NULL, '2025-02-09 03:03:13', '2025-02-09 03:03:13', '3333333333', '2025-02-09 14:03:13', NULL),
(15, 'zxc', 'zxc@zxc.com', 'visitor', NULL, NULL, '$2y$12$0v0M3eqYj4joU7BUssufquppkYAsJ4Jaq6JlqAbgyMry5RQ1/Irn2', NULL, NULL, NULL, NULL, '2025-02-09 03:06:23', '2025-02-09 03:06:23', '2233223322', '2025-02-09 14:06:23', NULL),
(16, 'xxxx', 'xxxx@xxxx.com', 'visitor', NULL, NULL, '$2y$12$mAE8cI0ulrxTvX7bxryYeu368a121Om0m6ZkTiC2SufwCAdaZKKPG', NULL, NULL, NULL, NULL, '2025-02-09 03:15:54', '2025-02-09 03:15:54', '1111111111', '2025-02-09 14:15:54', NULL),
(17, 'ccx', 'ccx@ccx.com', 'visitor', NULL, NULL, '$2y$12$34I4kKKFtDUrBmeAcZBaeOWq8T0BDPVVy/iqovJk8IsijBV39pG6W', NULL, NULL, NULL, NULL, '2025-02-09 03:22:18', '2025-02-09 03:22:18', '1122332222', '2025-02-09 14:22:18', NULL),
(18, 'ddd', 'ddd@ddd.com', 'user', NULL, NULL, '$2y$12$uk6.hL9HSyp6IhBGKc8v0eCK9iv5nvp0orJM2GpHJQ0lhbUVlPco.', NULL, NULL, NULL, NULL, '2025-02-09 03:42:30', '2025-02-09 03:42:30', '2222222222', '2025-02-09 14:42:30', NULL),
(19, 'dddd', 'dddd@dddd.com', 'visitor', NULL, NULL, '$2y$12$FIJbBToBzJ1mt1TNvoi5f.c2MIAuRxgcoG93CAV8LeHPFXxNmyCHG', NULL, NULL, NULL, NULL, '2025-02-09 03:42:54', '2025-02-09 03:42:54', '2222222222', '2025-02-09 14:42:54', NULL),
(20, 'qww', 'qww@qww.com', 'user', NULL, NULL, '$2y$12$HrqCRaqCSdab1zAz6pA9U.OxQ4rYVnUUh8.CnaeroJcmqw2vp.fR6', NULL, NULL, NULL, NULL, '2025-02-09 04:32:44', '2025-02-09 04:32:44', '2222222222', '2025-02-09 15:32:44', NULL),
(21, 'ccc', 'ccc@ccc.com', 'user', NULL, NULL, '$2y$12$e76QH5HAWX0fB9uCitKayeBMpEYyu00m.6zSn8jtpJYb1Qd25cpu.', NULL, NULL, NULL, NULL, '2025-02-09 05:51:36', '2025-02-09 05:51:36', '2222222222', '2025-02-09 16:51:36', NULL),
(29, 'testuser', 'test@example.com', 'user', 1, NULL, '$2y$12$yqh8m0d9HEktSaSEyR7LGej3ljVCwPOWCyiTMQMYofPTjDficM9ZK', NULL, NULL, NULL, NULL, '2025-02-09 10:35:24', '2025-02-09 10:35:24', '9876543210', '2025-02-09 21:35:24', NULL),
(37, 'xxcc', 'xxcc@xxcc.com', 'user', NULL, NULL, '$2y$12$Z/gWKtir4DgoltBD.aBuBe8ekJ/kM1cR0KdLkhOpVSusigGEdXca2', NULL, NULL, NULL, NULL, '2025-02-09 17:19:10', '2025-02-09 17:19:10', '2222222222', '2025-02-09 22:49:10', NULL),
(38, 'xxxxx', 'xxxxx@xxxxx.com', 'user', NULL, NULL, '$2y$12$MODhG3uUv4c9wBz.4Iu5QeTzssjlBm8XbOqM7LMc/VAAWbkortB.q', NULL, NULL, NULL, NULL, '2025-02-09 17:23:37', '2025-02-09 17:23:37', '2222222222', '2025-02-09 22:53:37', NULL),
(39, 'xdd', 'xdd@xdd.com', 'user', NULL, NULL, '$2y$12$Zxij7dngbaeJRYDdyBksxOvnwHmAG/YwJMOKCRH17hJQJuHQMQasK', NULL, NULL, NULL, NULL, '2025-02-09 17:39:58', '2025-02-09 17:39:58', '2222222222', '2025-02-09 23:09:58', NULL),
(40, 'xxzz', 'xxzz@xxzz.com', 'user', NULL, NULL, '$2y$12$jBZao2JXqlaR9Ra62V3e/.rLHkCViI7gZvM3Mr70VvmlTB/7rtr6q', NULL, NULL, NULL, NULL, '2025-02-10 00:04:22', '2025-02-10 00:04:22', '2222222222', '2025-02-10 05:34:22', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `match_id` (`match_id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_access`
--
ALTER TABLE `category_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_id` (`category_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `player1_id` (`player1_id`),
  ADD KEY `player2_id` (`player2_id`),
  ADD KEY `fk_player3` (`player3_id`),
  ADD KEY `fk_player4` (`player4_id`),
  ADD KEY `idx_stage` (`stage`);

--
-- Indexes for table `match_details`
--
ALTER TABLE `match_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `player_access`
--
ALTER TABLE `player_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `fk_moderated_by` (`moderated_by`);

--
-- Indexes for table `tournament_categories`
--
ALTER TABLE `tournament_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tournament_moderators`
--
ALTER TABLE `tournament_moderators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tournament_id` (`tournament_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `category_access`
--
ALTER TABLE `category_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `match_details`
--
ALTER TABLE `match_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `player_access`
--
ALTER TABLE `player_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tournament_categories`
--
ALTER TABLE `tournament_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `tournament_moderators`
--
ALTER TABLE `tournament_moderators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `fk_player3` FOREIGN KEY (`player3_id`) REFERENCES `players` (`id`),
  ADD CONSTRAINT `fk_player4` FOREIGN KEY (`player4_id`) REFERENCES `players` (`id`),
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`player1_id`) REFERENCES `players` (`id`),
  ADD CONSTRAINT `matches_ibfk_4` FOREIGN KEY (`player2_id`) REFERENCES `players` (`id`);

--
-- Constraints for table `match_details`
--
ALTER TABLE `match_details`
  ADD CONSTRAINT `match_details_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`);

--
-- Constraints for table `tournament_categories`
--
ALTER TABLE `tournament_categories`
  ADD CONSTRAINT `tournament_categories_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tournament_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

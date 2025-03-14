-- phpMyAdmin SQL Dump
-- version 5.2.1deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 14, 2025 at 10:00 AM
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
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `reset_link` varchar(255) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_password_resets`
--

INSERT INTO `admin_password_resets` (`id`, `email`, `token`, `reset_link`, `expires_at`, `created_at`) VALUES
(1, 'xxx@xxx.com', '19644a299e23661d740a9d8737d71bd99e226aca8da090ad0c65d5bf387c67c1', 'http://localhost/btl/btl/reset-password/19644a299e23661d740a9d8737d71bd99e226aca8da090ad0c65d5bf387c67c1?email=xxx%40xxx.com', NULL, '2025-03-05 01:14:37');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_by`, `age_group`, `sex`, `type`, `tournament_id`, `deleted_at`, `created_at`, `updated_at`, `ip_address`) VALUES
(1, 'U17BS', 1, 'Under 17', 'M', 'singles', 0, NULL, NULL, NULL, NULL),
(2, 'U15BS', 1, 'Under 15', 'M', 'singles', 0, NULL, NULL, NULL, NULL),
(3, 'U15BD', 1, 'Under 15', 'M', 'doubles', 0, NULL, NULL, NULL, NULL),
(4, 'U17BD', 2, 'Under 17', 'M', 'doubles', 0, NULL, NULL, NULL, NULL),
(6, 'U15GS', 4, 'Under 15', 'F', 'singles', 0, NULL, NULL, NULL, NULL),
(7, 'U15GD', 1, 'Under 15', 'F', 'doubles', 0, NULL, NULL, NULL, NULL),
(8, 'U13BS', 1, 'Under 13', 'M', 'singles', 0, NULL, NULL, NULL, NULL),
(11, 'Open BS', 4, 'Between 5 - 100', 'M', 'singles', 0, NULL, NULL, NULL, NULL),
(12, 'Open GS', 4, 'Between 5 - 100', 'F', 'singles', 0, NULL, NULL, NULL, NULL),
(13, 'Open XD', 4, 'Between 5 - 100', 'Mixed', 'mixed doubles', 0, NULL, NULL, NULL, NULL),
(14, 'Open BD', 4, 'Between 5 - 100', 'M', 'doubles', 0, NULL, NULL, NULL, NULL),
(15, 'Open GD', 4, 'Between 5 - 100', 'F', 'doubles', 0, NULL, NULL, NULL, NULL),
(16, 'U17GS', 4, 'Under 17', 'F', 'singles', 0, NULL, NULL, NULL, NULL),
(17, 'U17GD', 4, 'Under 17', 'F', 'doubles', 0, NULL, NULL, NULL, NULL),
(18, 'U17GD', 4, 'Under 17', 'F', 'doubles', 0, NULL, NULL, NULL, NULL),
(19, 'U13GS', 4, 'Under 13', 'F', 'singles', 0, NULL, NULL, NULL, NULL),
(20, 'Senior 40 Plus BS', 4, 'Over 40', 'M', 'singles', 0, NULL, NULL, NULL, NULL),
(21, 'Senior 40 Plus BD', 4, 'Over 40', 'M', 'doubles', 0, NULL, NULL, NULL, NULL),
(22, 'Senior 40 Plus GS', 4, 'Over 40', 'F', 'singles', 0, NULL, NULL, NULL, NULL),
(23, 'Senior 40 Plus GD', 1, 'Over 40', 'F', 'doubles', 0, '2025-02-01 23:34:54', NULL, '2025-02-01 23:34:54', NULL),
(25, 'U19BS', 4, 'Under 19', 'M', 'singles', 0, NULL, NULL, NULL, NULL),
(26, 'U19BD', 4, 'Under 19', 'M', 'doubles', 0, NULL, NULL, NULL, NULL),
(27, 'U19GS', 4, 'Under 19', 'F', 'singles', 0, NULL, NULL, NULL, NULL),
(28, 'U19GD', 4, 'Under 19', 'F', 'doubles', 0, NULL, NULL, NULL, NULL),
(29, 'U19XD', 4, 'Under 19', 'Mixed', 'mixed doubles', 0, NULL, NULL, NULL, NULL),
(30, 'U17XD', 4, 'Under 17', 'Mixed', 'singles', 0, NULL, NULL, NULL, NULL),
(31, 'U15XD', 4, 'Under 15', 'Mixed', 'singles', 0, NULL, NULL, NULL, NULL),
(32, 'Senior 40 Plus XD', 4, 'Over 40', 'Mixed', 'singles', 0, NULL, NULL, NULL, NULL),
(33, 'LU17BS', 4, 'Under 17', 'M', 'singles', NULL, '2025-02-02 02:12:43', '2025-02-01 21:01:05', '2025-02-02 02:12:43', NULL),
(34, 'testLBSU17', 4, 'Under 17', 'M', 'singles', NULL, NULL, '2025-02-20 00:23:12', '2025-02-20 00:23:12', NULL);

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

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, '4c133dd9-ab49-47c6-875f-272c5031d408', 'database', 'default', '{\"uuid\":\"4c133dd9-ab49-47c6-875f-272c5031d408\",\"displayName\":\"App\\\\Mail\\\\PlayerPinnedNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\PlayerPinnedNotification\\\":2:{s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"xpindia@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 'InvalidArgumentException: View [view.name] not found. in /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/FileViewFinder.php:139\nStack trace:\n#0 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths()\n#1 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Factory.php(151): Illuminate\\View\\FileViewFinder->find()\n#2 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(445): Illuminate\\View\\Factory->make()\n#3 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(420): Illuminate\\Mail\\Mailer->renderView()\n#4 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(313): Illuminate\\Mail\\Mailer->addContent()\n#5 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(206): Illuminate\\Mail\\Mailer->send()\n#6 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(199): Illuminate\\Mail\\Mailable->withLocale()\n#8 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(83): Illuminate\\Mail\\Mailable->send()\n#9 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#10 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#11 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#12 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#13 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#14 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(126): Illuminate\\Container\\Container->call()\n#15 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#16 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#17 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then()\n#18 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#19 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#20 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#21 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(121): Illuminate\\Pipeline\\Pipeline->then()\n#22 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(69): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#23 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#24 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#25 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(392): Illuminate\\Queue\\Worker->process()\n#26 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(178): Illuminate\\Queue\\Worker->runJob()\n#27 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#28 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#29 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#30 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#31 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#32 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#33 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#34 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n#35 /var/www/html/btl/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#36 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n#37 /var/www/html/btl/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#38 /var/www/html/btl/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#39 /var/www/html/btl/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#40 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#41 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#42 /var/www/html/btl/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n#43 {main}', '2025-03-13 01:00:08'),
(2, 'f97a5bd7-f314-4b81-af34-4fd2a0f998ab', 'database', 'default', '{\"uuid\":\"f97a5bd7-f314-4b81-af34-4fd2a0f998ab\",\"displayName\":\"App\\\\Mail\\\\MatchUpdatedNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\MatchUpdatedNotification\\\":5:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:7:\\\"mariadb\\\";s:15:\\\"collectionClass\\\";N;}s:5:\\\"match\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Matches\\\";s:2:\\\"id\\\";i:242;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:7:\\\"mariadb\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"changes\\\";a:2:{s:5:\\\"stage\\\";s:14:\\\"Quarter Finals\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-03-13 06:28:38\\\";}s:2:\\\"to\\\";a:2:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"xpindia@gmail.com\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"jamesheartcare@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 'TypeError: Cannot access offset of type string on string in /var/www/html/btl/storage/framework/views/02bd2e5fe52515d411c8d51d9f342a07.php:28\nStack trace:\n#0 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php(123): require()\n#1 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#2 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(58): Illuminate\\Filesystem\\Filesystem->getRequire()\n#3 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(75): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#4 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(209): Illuminate\\View\\Engines\\CompilerEngine->get()\n#5 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(192): Illuminate\\View\\View->getContents()\n#6 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(161): Illuminate\\View\\View->renderContents()\n#7 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(445): Illuminate\\View\\View->render()\n#8 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(420): Illuminate\\Mail\\Mailer->renderView()\n#9 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(313): Illuminate\\Mail\\Mailer->addContent()\n#10 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(206): Illuminate\\Mail\\Mailer->send()\n#11 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#12 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(199): Illuminate\\Mail\\Mailable->withLocale()\n#13 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(83): Illuminate\\Mail\\Mailable->send()\n#14 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#15 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#16 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#17 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#18 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#19 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(126): Illuminate\\Container\\Container->call()\n#20 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#21 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#22 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then()\n#23 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#24 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#25 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#26 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(121): Illuminate\\Pipeline\\Pipeline->then()\n#27 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(69): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#28 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#29 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#30 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(392): Illuminate\\Queue\\Worker->process()\n#31 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(178): Illuminate\\Queue\\Worker->runJob()\n#32 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#33 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#34 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#35 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#37 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#38 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#39 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n#40 /var/www/html/btl/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#41 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n#42 /var/www/html/btl/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#43 /var/www/html/btl/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#44 /var/www/html/btl/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#45 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#46 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#47 /var/www/html/btl/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n#48 {main}\n\nNext Illuminate\\View\\ViewException: Cannot access offset of type string on string (View: /var/www/html/btl/resources/views/emails/match_updated.blade.php) in /var/www/html/btl/storage/framework/views/02bd2e5fe52515d411c8d51d9f342a07.php:28\nStack trace:\n#0 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(60): Illuminate\\View\\Engines\\CompilerEngine->handleViewException()\n#1 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(75): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#2 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(209): Illuminate\\View\\Engines\\CompilerEngine->get()\n#3 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(192): Illuminate\\View\\View->getContents()\n#4 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(161): Illuminate\\View\\View->renderContents()\n#5 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(445): Illuminate\\View\\View->render()\n#6 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(420): Illuminate\\Mail\\Mailer->renderView()\n#7 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(313): Illuminate\\Mail\\Mailer->addContent()\n#8 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(206): Illuminate\\Mail\\Mailer->send()\n#9 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#10 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(199): Illuminate\\Mail\\Mailable->withLocale()\n#11 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(83): Illuminate\\Mail\\Mailable->send()\n#12 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#13 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#15 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#16 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#17 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(126): Illuminate\\Container\\Container->call()\n#18 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#19 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#20 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then()\n#21 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#22 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#23 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#24 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(121): Illuminate\\Pipeline\\Pipeline->then()\n#25 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(69): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#26 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#27 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(392): Illuminate\\Queue\\Worker->process()\n#29 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(178): Illuminate\\Queue\\Worker->runJob()\n#30 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#31 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#32 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#33 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#34 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#35 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#36 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#37 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n#38 /var/www/html/btl/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#39 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n#40 /var/www/html/btl/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#41 /var/www/html/btl/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#42 /var/www/html/btl/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#43 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#44 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#45 /var/www/html/btl/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n#46 {main}', '2025-03-13 01:01:46'),
(3, '0d1072e5-097c-40c8-b199-c034182c19f7', 'database', 'default', '{\"uuid\":\"0d1072e5-097c-40c8-b199-c034182c19f7\",\"displayName\":\"App\\\\Mail\\\\MatchUpdatedNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\MatchUpdatedNotification\\\":5:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:7:\\\"mariadb\\\";s:15:\\\"collectionClass\\\";N;}s:5:\\\"match\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Matches\\\";s:2:\\\"id\\\";i:242;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:7:\\\"mariadb\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"changes\\\";a:2:{s:5:\\\"stage\\\";s:10:\\\"Semifinals\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-03-13 06:29:37\\\";}s:2:\\\"to\\\";a:2:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"xpindia@gmail.com\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"jamesheartcare@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 'TypeError: Cannot access offset of type string on string in /var/www/html/btl/storage/framework/views/02bd2e5fe52515d411c8d51d9f342a07.php:28\nStack trace:\n#0 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php(123): require()\n#1 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#2 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(58): Illuminate\\Filesystem\\Filesystem->getRequire()\n#3 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(75): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#4 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(209): Illuminate\\View\\Engines\\CompilerEngine->get()\n#5 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(192): Illuminate\\View\\View->getContents()\n#6 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(161): Illuminate\\View\\View->renderContents()\n#7 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(445): Illuminate\\View\\View->render()\n#8 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(420): Illuminate\\Mail\\Mailer->renderView()\n#9 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(313): Illuminate\\Mail\\Mailer->addContent()\n#10 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(206): Illuminate\\Mail\\Mailer->send()\n#11 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#12 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(199): Illuminate\\Mail\\Mailable->withLocale()\n#13 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(83): Illuminate\\Mail\\Mailable->send()\n#14 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#15 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#16 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#17 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#18 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#19 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(126): Illuminate\\Container\\Container->call()\n#20 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#21 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#22 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then()\n#23 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#24 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#25 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#26 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(121): Illuminate\\Pipeline\\Pipeline->then()\n#27 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(69): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#28 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#29 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#30 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(392): Illuminate\\Queue\\Worker->process()\n#31 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(178): Illuminate\\Queue\\Worker->runJob()\n#32 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#33 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#34 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#35 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#37 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#38 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#39 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n#40 /var/www/html/btl/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#41 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n#42 /var/www/html/btl/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#43 /var/www/html/btl/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#44 /var/www/html/btl/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#45 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#46 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#47 /var/www/html/btl/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n#48 {main}\n\nNext Illuminate\\View\\ViewException: Cannot access offset of type string on string (View: /var/www/html/btl/resources/views/emails/match_updated.blade.php) in /var/www/html/btl/storage/framework/views/02bd2e5fe52515d411c8d51d9f342a07.php:28\nStack trace:\n#0 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(60): Illuminate\\View\\Engines\\CompilerEngine->handleViewException()\n#1 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(75): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#2 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(209): Illuminate\\View\\Engines\\CompilerEngine->get()\n#3 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(192): Illuminate\\View\\View->getContents()\n#4 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(161): Illuminate\\View\\View->renderContents()\n#5 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(445): Illuminate\\View\\View->render()\n#6 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(420): Illuminate\\Mail\\Mailer->renderView()\n#7 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(313): Illuminate\\Mail\\Mailer->addContent()\n#8 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(206): Illuminate\\Mail\\Mailer->send()\n#9 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#10 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(199): Illuminate\\Mail\\Mailable->withLocale()\n#11 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(83): Illuminate\\Mail\\Mailable->send()\n#12 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#13 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#15 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#16 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#17 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(126): Illuminate\\Container\\Container->call()\n#18 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#19 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#20 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then()\n#21 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#22 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#23 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#24 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(121): Illuminate\\Pipeline\\Pipeline->then()\n#25 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(69): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#26 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#27 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(392): Illuminate\\Queue\\Worker->process()\n#29 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(178): Illuminate\\Queue\\Worker->runJob()\n#30 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#31 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#32 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#33 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#34 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#35 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#36 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#37 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n#38 /var/www/html/btl/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#39 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n#40 /var/www/html/btl/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#41 /var/www/html/btl/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#42 /var/www/html/btl/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#43 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#44 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#45 /var/www/html/btl/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n#46 {main}', '2025-03-13 01:01:51'),
(4, 'd482ef83-0115-4f93-88c0-75a1a7c60c01', 'database', 'default', '{\"uuid\":\"d482ef83-0115-4f93-88c0-75a1a7c60c01\",\"displayName\":\"App\\\\Mail\\\\PlayerPinnedNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\PlayerPinnedNotification\\\":2:{s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"xpindia@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 'InvalidArgumentException: View [view.name] not found. in /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/FileViewFinder.php:139\nStack trace:\n#0 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths()\n#1 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Factory.php(151): Illuminate\\View\\FileViewFinder->find()\n#2 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(445): Illuminate\\View\\Factory->make()\n#3 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(420): Illuminate\\Mail\\Mailer->renderView()\n#4 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(313): Illuminate\\Mail\\Mailer->addContent()\n#5 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(206): Illuminate\\Mail\\Mailer->send()\n#6 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(199): Illuminate\\Mail\\Mailable->withLocale()\n#8 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(83): Illuminate\\Mail\\Mailable->send()\n#9 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#10 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#11 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#12 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#13 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#14 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(126): Illuminate\\Container\\Container->call()\n#15 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#16 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#17 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then()\n#18 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#19 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#20 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#21 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(121): Illuminate\\Pipeline\\Pipeline->then()\n#22 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(69): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#23 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#24 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#25 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(392): Illuminate\\Queue\\Worker->process()\n#26 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(178): Illuminate\\Queue\\Worker->runJob()\n#27 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#28 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#29 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#30 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#31 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#32 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#33 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#34 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n#35 /var/www/html/btl/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#36 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n#37 /var/www/html/btl/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#38 /var/www/html/btl/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#39 /var/www/html/btl/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#40 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#41 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#42 /var/www/html/btl/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n#43 {main}', '2025-03-13 08:54:31');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(5, 'd475f174-f4c8-458b-a333-4b8536323d54', 'database', 'default', '{\"uuid\":\"d475f174-f4c8-458b-a333-4b8536323d54\",\"displayName\":\"App\\\\Mail\\\\MatchUpdatedNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\MatchUpdatedNotification\\\":5:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:7:\\\"mariadb\\\";s:15:\\\"collectionClass\\\";N;}s:5:\\\"match\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Matches\\\";s:2:\\\"id\\\";i:245;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:7:\\\"mariadb\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"changes\\\";a:4:{s:19:\\\"set2_player1_points\\\";s:1:\\\"3\\\";s:19:\\\"set3_player1_points\\\";s:1:\\\"3\\\";s:5:\\\"stage\\\";s:10:\\\"Semifinals\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-03-13 20:51:09\\\";}s:2:\\\"to\\\";a:6:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:15:\\\"admin@admin.com\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"xpindia@gmail.com\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"zzzuser@zzzuser.com\\\";}i:3;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"ejrb2020@gmail.com\\\";}i:4;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"jamesheartcare@gmail.com\\\";}i:5;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:12:\\\"asda@sd.asda\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 'TypeError: Cannot access offset of type string on string in /var/www/html/btl/storage/framework/views/02bd2e5fe52515d411c8d51d9f342a07.php:28\nStack trace:\n#0 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php(123): require()\n#1 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#2 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(58): Illuminate\\Filesystem\\Filesystem->getRequire()\n#3 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(75): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#4 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(209): Illuminate\\View\\Engines\\CompilerEngine->get()\n#5 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(192): Illuminate\\View\\View->getContents()\n#6 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(161): Illuminate\\View\\View->renderContents()\n#7 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(445): Illuminate\\View\\View->render()\n#8 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(420): Illuminate\\Mail\\Mailer->renderView()\n#9 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(313): Illuminate\\Mail\\Mailer->addContent()\n#10 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(206): Illuminate\\Mail\\Mailer->send()\n#11 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#12 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(199): Illuminate\\Mail\\Mailable->withLocale()\n#13 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(83): Illuminate\\Mail\\Mailable->send()\n#14 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#15 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#16 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#17 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#18 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#19 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(126): Illuminate\\Container\\Container->call()\n#20 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#21 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#22 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then()\n#23 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#24 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#25 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#26 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(121): Illuminate\\Pipeline\\Pipeline->then()\n#27 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(69): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#28 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#29 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#30 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(392): Illuminate\\Queue\\Worker->process()\n#31 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(178): Illuminate\\Queue\\Worker->runJob()\n#32 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#33 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#34 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#35 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#36 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#37 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#38 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#39 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n#40 /var/www/html/btl/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#41 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n#42 /var/www/html/btl/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#43 /var/www/html/btl/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#44 /var/www/html/btl/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#45 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#46 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#47 /var/www/html/btl/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n#48 {main}\n\nNext Illuminate\\View\\ViewException: Cannot access offset of type string on string (View: /var/www/html/btl/resources/views/emails/match_updated.blade.php) in /var/www/html/btl/storage/framework/views/02bd2e5fe52515d411c8d51d9f342a07.php:28\nStack trace:\n#0 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(60): Illuminate\\View\\Engines\\CompilerEngine->handleViewException()\n#1 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(75): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#2 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(209): Illuminate\\View\\Engines\\CompilerEngine->get()\n#3 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(192): Illuminate\\View\\View->getContents()\n#4 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/View/View.php(161): Illuminate\\View\\View->renderContents()\n#5 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(445): Illuminate\\View\\View->render()\n#6 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(420): Illuminate\\Mail\\Mailer->renderView()\n#7 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(313): Illuminate\\Mail\\Mailer->addContent()\n#8 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(206): Illuminate\\Mail\\Mailer->send()\n#9 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#10 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(199): Illuminate\\Mail\\Mailable->withLocale()\n#11 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Mail/SendQueuedMailable.php(83): Illuminate\\Mail\\Mailable->send()\n#12 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle()\n#13 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#15 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#16 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#17 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(126): Illuminate\\Container\\Container->call()\n#18 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#19 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#20 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then()\n#21 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#22 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#23 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#24 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(121): Illuminate\\Pipeline\\Pipeline->then()\n#25 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(69): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#26 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#27 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(392): Illuminate\\Queue\\Worker->process()\n#29 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(178): Illuminate\\Queue\\Worker->runJob()\n#30 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#31 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#32 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#33 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#34 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n#35 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#36 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Container/Container.php(696): Illuminate\\Container\\BoundMethod::call()\n#37 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n#38 /var/www/html/btl/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n#39 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n#40 /var/www/html/btl/vendor/symfony/console/Application.php(1094): Illuminate\\Console\\Command->run()\n#41 /var/www/html/btl/vendor/symfony/console/Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand()\n#42 /var/www/html/btl/vendor/symfony/console/Application.php(193): Symfony\\Component\\Console\\Application->doRun()\n#43 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#44 /var/www/html/btl/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#45 /var/www/html/btl/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n#46 {main}', '2025-03-13 15:21:12');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `favoritable_id` int(11) NOT NULL,
  `favoritable_type` enum('App\\Models\\Tournament','App\\Models\\Matches','App\\Models\\Category','App\\Models\\Player') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `favoritable_id`, `favoritable_type`, `created_at`) VALUES
(2, 1, 199, 'App\\Models\\Matches', '2025-03-09 06:30:49'),
(7, 4, 155, 'App\\Models\\Matches', '2025-03-09 08:12:25'),
(9, 4, 36, 'App\\Models\\Matches', '2025-03-09 08:15:04'),
(10, 4, 29, 'App\\Models\\Category', '2025-03-09 08:33:14'),
(11, 4, 36, 'App\\Models\\Player', '2025-03-09 08:37:21'),
(12, 4, 39, 'App\\Models\\Player', '2025-03-09 08:45:30'),
(13, 4, 39, 'App\\Models\\Player', '2025-03-09 08:45:30'),
(15, 4, 189, 'App\\Models\\Matches', '2025-03-10 05:00:10'),
(16, 4, 189, 'App\\Models\\Matches', '2025-03-10 05:00:10'),
(17, 4, 183, 'App\\Models\\Matches', '2025-03-10 05:00:12'),
(18, 4, 183, 'App\\Models\\Matches', '2025-03-10 05:00:12'),
(21, 4, 202, 'App\\Models\\Matches', '2025-03-12 04:18:58'),
(22, 4, 202, 'App\\Models\\Matches', '2025-03-12 04:18:58'),
(23, 4, 154, 'App\\Models\\Matches', '2025-03-12 04:28:15'),
(24, 4, 154, 'App\\Models\\Matches', '2025-03-12 04:28:15'),
(25, 4, 196, 'App\\Models\\Matches', '2025-03-12 04:49:14'),
(26, 4, 198, 'App\\Models\\Matches', '2025-03-12 04:49:16'),
(27, 4, 201, 'App\\Models\\Matches', '2025-03-12 04:49:17'),
(28, 4, 199, 'App\\Models\\Matches', '2025-03-12 04:51:33'),
(29, 4, 197, 'App\\Models\\Matches', '2025-03-12 04:51:35'),
(30, 4, 228, 'App\\Models\\Matches', '2025-03-12 10:35:41'),
(31, 4, 228, 'App\\Models\\Matches', '2025-03-12 10:35:41'),
(32, 4, 47, 'App\\Models\\Player', '2025-03-12 15:56:18'),
(33, 4, 227, 'App\\Models\\Matches', '2025-03-12 16:23:25'),
(34, 4, 231, 'App\\Models\\Matches', '2025-03-12 16:48:27'),
(36, 4, 236, 'App\\Models\\Matches', '2025-03-12 17:24:50'),
(37, 4, 236, 'App\\Models\\Matches', '2025-03-12 17:24:50'),
(38, 4, 220, 'App\\Models\\Matches', '2025-03-12 17:40:41'),
(39, 4, 220, 'App\\Models\\Matches', '2025-03-12 17:40:41'),
(40, 4, 221, 'App\\Models\\Matches', '2025-03-12 17:40:44'),
(41, 4, 221, 'App\\Models\\Matches', '2025-03-12 17:40:44'),
(42, 4, 235, 'App\\Models\\Matches', '2025-03-12 17:57:51'),
(43, 4, 235, 'App\\Models\\Matches', '2025-03-12 17:57:51'),
(45, 4, 234, 'App\\Models\\Matches', '2025-03-12 17:57:53'),
(46, 4, 234, 'App\\Models\\Matches', '2025-03-12 17:57:53'),
(47, 4, 239, 'App\\Models\\Matches', '2025-03-12 18:10:21'),
(48, 4, 239, 'App\\Models\\Matches', '2025-03-12 18:10:21'),
(49, 4, 238, 'App\\Models\\Matches', '2025-03-12 18:10:22'),
(50, 4, 238, 'App\\Models\\Matches', '2025-03-12 18:10:22'),
(53, 4, 237, 'App\\Models\\Matches', '2025-03-12 18:10:25'),
(54, 4, 237, 'App\\Models\\Matches', '2025-03-12 18:10:25'),
(55, 4, 240, 'App\\Models\\Matches', '2025-03-13 00:42:48'),
(56, 4, 241, 'App\\Models\\Matches', '2025-03-13 00:45:09'),
(57, 4, 241, 'App\\Models\\Matches', '2025-03-13 00:45:09'),
(58, 4, 242, 'App\\Models\\Matches', '2025-03-13 00:47:43'),
(59, 4, 48, 'App\\Models\\Player', '2025-03-13 08:51:12'),
(61, 4, 244, 'App\\Models\\Matches', '2025-03-13 08:58:53'),
(62, 4, 244, 'App\\Models\\Matches', '2025-03-13 08:58:53'),
(63, 4, 243, 'App\\Models\\Matches', '2025-03-13 15:15:24');

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
  `deleted_at` datetime DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `tournament_id`, `category_id`, `pool`, `player1_id`, `player2_id`, `pre_quarter`, `quarter`, `semi`, `final`, `set1_player1_points`, `set1_player2_points`, `set2_player1_points`, `set2_player2_points`, `set3_player1_points`, `set3_player2_points`, `created_by`, `moderated_by`, `stage`, `match_date`, `match_time`, `team1_player1_id`, `team1_player2_id`, `team2_player1_id`, `team2_player2_id`, `set1_team1_points`, `set1_team2_points`, `set2_team1_points`, `set2_team2_points`, `set3_team1_points`, `set3_team2_points`, `player3_id`, `player4_id`, `deleted_at`, `ip_address`, `created_at`, `updated_at`) VALUES
(5, 3, 16, NULL, 1, 4, 0, 0, 0, 0, 21, 10, 8, 21, 21, 18, 1, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2025-03-08 09:21:04'),
(7, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 3, 1, NULL, 'Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 3, 20, NULL, 11, 12, 0, 0, 0, 0, 21, 11, 12, 21, 21, 16, 1, NULL, 'Pre Quarter Finals', '2025-01-02', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 3, 11, NULL, 10, 6, 0, 0, 0, 0, 28, 26, 24, 26, 28, 2, 1, NULL, 'Pre Quarter Finals', '2025-01-03', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 1, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 1, 11, NULL, 2, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 3, 20, NULL, 10, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 1, NULL, 'Pre Quarter Finals', '2025-01-03', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 3, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Preliminary', '2024-12-30', '00:00:00', 2, 10, 11, 12, 21, 0, 0, 21, 21, 4, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 1, 11, NULL, 3, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-04', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-03', '00:00:00', 6, 10, 12, 13, 21, 12, 14, 21, 21, 15, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2024-12-30', '00:00:00', 6, 2, 13, 12, 21, 4, 4, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 6, 2, 13, 12, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 10, 13, 12, 2, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 2, 3, 11, 12, 21, 2, 2, 21, 21, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 2, 13, 12, 6, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 1, 26, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-06', '00:00:00', 2, 11, 13, 12, 24, 22, 22, 24, 21, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-06', '00:00:00', 9, 6, 2, 4, 14, 21, 21, 12, 21, 16, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Preliminary', '2025-01-06', '00:00:00', 9, 6, 2, 4, 1, 21, 21, 2, 7, 21, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 1, 26, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-06', '00:00:00', 12, 3, 6, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-06', '00:00:00', 13, 11, 2, 3, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 1, 21, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-06', '00:00:00', 6, 11, 12, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Preliminary', '2025-01-06', '00:00:00', 4, 14, 1, 15, 21, 13, 12, 21, 19, 21, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-06', '00:00:00', 19, 9, 17, 15, 21, 12, 13, 21, 21, 4, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-07', '00:00:00', 1, 6, 21, 4, 24, 3, 4, 21, 21, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-07', '00:00:00', 19, 17, 18, 13, 21, 3, 3, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Finals', '2025-01-07', '00:00:00', 4, 9, 14, 19, 26, 24, 22, 24, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Finals', '2025-01-07', '00:00:00', 16, 9, 14, 1, 21, 3, 2, 21, 2, 21, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Quarter Finals', '2025-01-11', '00:00:00', 21, 12, 6, 11, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 14, 11, NULL, 3, 20, 0, 0, 0, 0, 21, 2, 2, 21, 21, 11, 1, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 1, 26, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, 'Pre Quarter Finals', '2025-01-15', '06:00:00', 2, 3, 3, 2, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 14, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Semifinals', '2025-01-01', '00:00:00', 3, 21, 11, 20, 3, 21, 12, 2, 2, 21, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 17, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Preliminary', '2025-01-16', '00:00:00', 19, 12, 14, 20, 21, 2, 2, 21, 21, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 17, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Preliminary', '2025-01-16', '00:00:00', 16, 18, 1, 19, 21, 2, 2, 21, 21, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 1, 20, NULL, 21, 12, 0, 0, 0, 0, 21, 2, 1, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 1, 20, NULL, 2, 10, 0, 0, 0, 0, 21, 2, 21, 2, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 1, 20, NULL, 3, 11, 0, 0, 0, 0, 21, 3, 3, 21, 12, 21, NULL, NULL, 'Pre Quarter Finals', '2000-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 1, 20, NULL, 21, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 1, 20, NULL, 21, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 1, 20, NULL, 21, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 1, 20, NULL, 2, 10, 0, 0, 0, 0, 21, 2, 21, 2, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 1, 20, NULL, 2, 10, 0, 0, 0, 0, 21, 2, 21, 2, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '2025-03-05 03:45:38', NULL, NULL, '2025-03-04 22:15:38'),
(57, 1, 20, NULL, 2, 10, 0, 0, 0, 0, 21, 2, 21, 2, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 1, 20, NULL, 20, 2, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', NULL, '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 1, 20, NULL, 20, 2, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 1, 20, NULL, 2, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 1, 20, NULL, 21, 2, 0, 0, 0, 0, 12, 21, 21, 18, 21, 19, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 1, 20, NULL, 12, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 1, 20, NULL, 2, 3, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 18, 11, NULL, 2, 10, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 18, 11, NULL, 2, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 17, 11, NULL, 2, 21, 0, 0, 0, 0, 1, 3, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 17, 11, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 17, 11, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 17, 11, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 1, 11, NULL, 21, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 1, 11, NULL, 21, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 1, 11, NULL, 2, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 1, 11, NULL, 20, 2, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(84, 1, 20, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(85, 1, 20, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(86, 1, 20, NULL, 21, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 1, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 1, 20, NULL, 21, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 1, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(88, 1, 20, NULL, 20, 12, 0, 0, 0, 0, 21, 2, 21, 2, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(89, 1, 20, NULL, 20, 12, 0, 0, 0, 0, 21, 2, 21, 2, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(90, 1, 11, NULL, 21, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(91, 1, 11, NULL, 21, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(92, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 1, 2, 21, 21, 1, NULL, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(93, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 1, 2, 21, 21, 1, NULL, NULL, 'Pre Quarter Finals', '2025-01-01', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(94, 1, 11, NULL, 6, 11, 0, 0, 0, 0, 21, 2, 21, 2, 1, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-14', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(95, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(96, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(97, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(98, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(99, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(100, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(101, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(102, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(103, 1, 11, NULL, 20, 11, 0, 0, 0, 0, 21, 2, 2, 2, 2, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(104, 1, 11, NULL, 20, 11, 0, 0, 0, 0, 21, 2, 2, 2, 2, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(105, 1, 11, NULL, 20, 11, 0, 0, 0, 0, 21, 2, 2, 2, 2, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(106, 1, 11, NULL, 20, 11, 0, 0, 0, 0, 21, 2, 2, 2, 2, 2, NULL, NULL, 'Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(107, 1, 11, NULL, 2, 13, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(108, 1, 11, NULL, 2, 13, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(109, 1, 11, NULL, 2, 13, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(110, 1, 11, NULL, 2, 13, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(111, 1, 11, NULL, 11, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(112, 1, 11, NULL, 11, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(113, 1, 2, NULL, 3, 22, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(114, 1, 2, NULL, 3, 22, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 1, 11, NULL, 3, 11, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 1, 11, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 1, 11, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:03', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(118, 1, 11, NULL, 20, 21, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-20', '00:00:11', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(120, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(123, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(124, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:03', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(125, 3, 11, NULL, 3, 11, 0, 0, 0, 0, 21, 1, 1, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:18', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(126, 1, 11, NULL, 6, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(127, 1, 11, NULL, 6, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(128, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(129, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '00:00:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(130, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 2, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '16:35:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(131, 1, 11, NULL, 3, 6, 0, 0, 0, 0, 3, 21, 21, 2, 12, 21, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '16:35:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(132, 1, 11, NULL, 21, 20, 0, 0, 0, 0, 21, 19, 5, 21, 21, 5, NULL, NULL, 'Quarter Finals', '2025-01-22', '00:00:20', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(133, 1, 11, NULL, 12, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, NULL, NULL, 'Pre Quarter Finals', '2025-01-22', '21:12:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-24', '00:00:11', 11, 21, 3, 2, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-24', '00:00:11', 11, 21, 3, 2, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(148, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-24', '00:00:11', 11, 21, 3, 2, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(149, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '00:00:11', 2, 3, 12, 20, 21, 2, 2, 21, 21, 3, NULL, NULL, NULL, NULL, NULL, '2025-02-28 01:20:09'),
(150, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '00:00:11', 13, 21, 2, 3, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(151, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '11:58:00', 2, 3, 11, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(152, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '00:00:11', 1, 4, 16, 18, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(153, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '11:59:00', 1, 4, 16, 18, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(154, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Finals', '2025-01-24', '00:00:12', 21, 9, 13, 19, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, '2025-03-12 04:30:13'),
(155, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Pre Quarter Finals', '2025-01-24', '15:03:00', 21, 9, 4, 20, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(158, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-15', '09:45:00', 4, 12, 16, 11, 21, 12, 12, 21, 21, 13, NULL, NULL, '2025-02-28 11:46:41', NULL, NULL, '2025-02-28 06:16:41'),
(159, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-15', '11:55:00', 1, 3, 14, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(160, 1, 11, NULL, 6, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 4, NULL, NULL, 'Semifinals', '2025-02-20', '11:45:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(162, 1, 1, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 'Quarter Finals', '2025-02-20', '10:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(163, 1, 17, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-20', '17:43:00', 1, 4, 9, 17, 21, 2, 2, 21, 21, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(164, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-20', '17:47:00', 20, 21, 3, 11, 21, 2, 2, 21, 21, 21, NULL, NULL, NULL, NULL, NULL, NULL),
(165, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-20', '18:15:00', 18, 19, 1, 4, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(167, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 12, 21, 21, 11, 21, 1, 4, NULL, 'Pre Quarter Finals', '2025-02-21', '09:12:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(168, 1, 11, NULL, 20, 10, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 7, NULL, 'Pre Quarter Finals', '2025-02-21', '09:14:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-26', '16:58:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, '2025-02-26 06:09:06', '2025-02-28 05:54:21'),
(170, 1, 11, NULL, 13, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-26', '17:16:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-02-26 06:16:27', '2025-02-26 06:16:27'),
(171, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-26', '17:18:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-02-26 06:19:07', '2025-02-26 06:19:07'),
(172, 1, 11, NULL, 20, 12, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 4, NULL, 'Pre Quarter Finals', '2025-02-25', '17:22:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-02-26 06:23:11', '2025-02-26 06:23:11'),
(173, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-26', '17:45:00', 16, 12, 21, 9, 0, 0, 0, 0, 1, 0, NULL, NULL, NULL, NULL, '2025-02-26 06:45:58', '2025-02-28 05:31:21'),
(174, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 21, 11, 21, 12, 21, 2, 4, NULL, 'Pre Quarter Finals', '2025-02-27', '09:03:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-02-26 22:07:35', '2025-02-26 22:07:35'),
(175, 1, 12, NULL, 1, 16, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 4, NULL, 'Pre Quarter Finals', '2025-02-27', '11:29:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-02-27 00:29:21', '2025-02-27 00:29:21'),
(176, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-27', '11:40:00', 18, 21, 2, 1, 21, 1, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-02-27 00:41:05', '2025-02-28 05:35:38'),
(177, 1, 25, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 19, 4, NULL, 'Pre Quarter Finals', '2025-02-27', '16:29:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-02-27 05:29:36', '2025-02-27 05:30:52'),
(178, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-28', '11:11:00', 20, 1, 2, 16, 21, 8, 7, 21, 21, 12, NULL, NULL, NULL, NULL, NULL, '2025-02-28 06:32:03'),
(179, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 4, NULL, 'Pre Quarter Finals', '2025-02-28', '11:13:00', 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '2025-02-28 06:08:32'),
(180, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-28', '11:42:00', 2, 1, 10, 15, 21, 2, 2, 21, 21, 2, NULL, NULL, '2025-02-28 06:25:55', NULL, '2025-02-28 00:43:11', '2025-02-28 00:55:55'),
(181, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-02-28', '12:38:00', 2, 1, 6, 16, 21, 12, 12, 21, 21, 12, NULL, NULL, NULL, NULL, '2025-02-28 01:39:11', '2025-02-28 01:40:12'),
(182, 1, 11, NULL, 20, 6, 0, 0, 0, 0, 11, 21, 21, 11, 12, 21, 4, NULL, 'Pre Quarter Finals', '2025-02-28', '18:11:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-02-28 07:11:59', '2025-02-28 07:11:59'),
(183, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-03', '10:16:00', 40, 41, 15, 19, 21, 12, 13, 21, 21, 11, NULL, NULL, NULL, NULL, '2025-03-02 23:17:03', '2025-03-12 04:08:47'),
(184, 3, 16, NULL, 43, 1, 0, 0, 0, 0, 21, 12, 1, 21, 21, 11, 4, NULL, 'Pre Quarter Finals', '2025-03-04', '17:47:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-04 06:48:11', '2025-03-04 06:48:11'),
(185, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-04', '17:50:00', 38, 35, 6, 17, 21, 11, 12, 21, 21, 11, NULL, NULL, NULL, NULL, '2025-03-04 06:50:20', '2025-03-04 22:06:52'),
(186, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Semifinals', '2025-03-05', '08:20:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-04 21:20:52', '2025-03-04 21:20:52'),
(187, 1, 4, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-05', '09:24:00', 2, 36, 3, 2, 21, 2, 15, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-04 22:24:40', '2025-03-08 06:07:19'),
(188, 1, 27, NULL, 35, 40, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-05', '10:28:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-04 23:28:49', '2025-03-04 23:28:49'),
(189, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Semifinals', '2025-03-05', '10:29:00', 43, 18, 14, 15, 21, 2, 3, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-04 23:29:39', '2025-03-12 03:55:12'),
(190, 1, 12, NULL, 35, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '09:27:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 03:57:23', '2025-03-08 03:57:23'),
(191, 1, 20, NULL, 6, 11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '10:47:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 05:19:47', '2025-03-08 05:19:47'),
(192, 1, 20, NULL, 6, 11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '10:47:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 05:21:08', '2025-03-08 05:21:08'),
(193, 1, 20, NULL, 6, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '10:47:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 05:21:19', '2025-03-08 05:21:19'),
(194, 1, 20, NULL, 6, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '10:47:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 05:22:16', '2025-03-08 05:22:16'),
(195, 1, 17, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '11:37:00', 1, 4, 9, 43, 21, 11, 7, 21, 21, 12, NULL, NULL, NULL, NULL, '2025-03-08 06:08:14', '2025-03-09 14:39:35'),
(196, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '11:39:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 06:09:12', '2025-03-08 06:09:12'),
(197, 1, 27, NULL, 9, 43, 0, 0, 0, 0, 21, 10, 11, 21, 21, 13, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '11:46:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 06:16:06', '2025-03-11 04:14:12'),
(198, 3, 1, NULL, 3, 36, 0, 0, 0, 0, 28, 26, 26, 28, 20, 2, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '15:34:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 09:05:14', '2025-03-08 09:05:14'),
(199, 3, 12, NULL, 16, 19, 0, 0, 0, 0, 21, 2, 21, 11, 2, 21, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '14:52:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-08 09:22:10', '2025-03-12 04:42:48'),
(200, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-08', '14:58:00', 38, 43, 21, 39, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-08 09:29:02', '2025-03-08 09:29:02'),
(201, 1, 1, NULL, 2, 36, 0, 0, 0, 0, 21, 10, 18, 21, 21, 6, 4, NULL, 'Finals', '2025-03-01', '14:07:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-09 07:37:22', '2025-03-12 04:06:25'),
(202, 1, 4, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Quarter Finals', '2025-03-02', '10:07:00', 2, 3, 22, 36, 21, 6, 6, 21, 21, 3, NULL, NULL, NULL, NULL, '2025-03-09 16:37:35', '2025-03-12 17:53:20'),
(203, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:31:06', '2025-03-12 05:31:06'),
(204, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:32:19', '2025-03-12 05:32:19'),
(205, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:34:25', '2025-03-12 05:34:25'),
(206, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:36:38', '2025-03-12 05:36:38'),
(207, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:37:28', '2025-03-12 05:37:28'),
(208, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:37:47', '2025-03-12 05:37:47'),
(209, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:38:37', '2025-03-12 05:38:37'),
(210, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:39:09', '2025-03-12 05:39:09'),
(211, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:41:06', '2025-03-12 05:41:06'),
(212, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:42:23', '2025-03-12 05:42:23'),
(213, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:46:04', '2025-03-12 05:46:04'),
(214, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:47:32', '2025-03-12 05:47:32'),
(215, 1, 11, NULL, 13, 38, 0, 0, 0, 0, 21, 11, 12, 21, 21, 2, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:20:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:51:02', '2025-03-12 05:51:02'),
(216, 1, 27, NULL, 35, 17, 0, 0, 0, 0, 21, 2, 2, 21, 21, 2, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:29:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 05:59:15', '2025-03-12 05:59:15'),
(217, 1, 11, NULL, 10, 21, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:33:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 06:03:30', '2025-03-12 06:03:30'),
(218, 1, 16, NULL, 1, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:35:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 06:06:05', '2025-03-12 06:06:05'),
(219, 1, 27, NULL, 4, 42, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:39:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 06:09:20', '2025-03-12 06:09:20'),
(220, 1, 17, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:41:00', 1, 4, 9, 43, 21, 11, 16, 21, 21, 11, NULL, NULL, NULL, NULL, '2025-03-12 06:11:43', '2025-03-12 17:49:50'),
(221, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-13', '11:47:00', 31, 39, 36, 9, 21, 11, 16, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 06:18:06', '2025-03-12 17:49:59'),
(222, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:51:00', 13, 37, 36, 20, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 06:21:19', '2025-03-12 06:21:19'),
(223, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:54:00', 38, 19, 12, 4, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 06:25:08', '2025-03-12 06:25:08'),
(224, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '11:57:00', 31, 11, 21, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 06:27:34', '2025-03-12 06:27:34'),
(225, 1, 21, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '12:00:00', 6, 11, 12, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 06:30:13', '2025-03-12 06:30:13'),
(226, 1, 12, NULL, 35, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '16:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 10:30:14', '2025-03-12 10:30:14'),
(227, 1, 16, NULL, 1, 17, 0, 0, 0, 0, 1, 21, 21, 2, 7, 21, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '16:02:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 10:32:20', '2025-03-12 17:52:39'),
(228, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '16:03:00', 35, 9, 39, 42, 21, 2, 3, 21, 2, 21, NULL, NULL, NULL, NULL, '2025-03-12 10:33:22', '2025-03-12 16:27:00'),
(229, 1, 11, NULL, 21, 36, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '21:58:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 16:28:27', '2025-03-12 16:28:27'),
(230, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '21:59:00', 24, 40, 20, 9, 21, 2, 4, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 16:29:12', '2025-03-12 16:46:43'),
(231, 1, 1, NULL, 2, 22, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '22:17:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 16:47:46', '2025-03-12 16:49:02'),
(232, 1, 26, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '22:19:00', 2, 3, 22, 36, 21, 2, 9, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 16:50:01', '2025-03-13 09:02:31'),
(233, 1, 33, NULL, 36, 20, 0, 0, 0, 0, 0, 0, 0, 2, 2, 2, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '22:20:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-12 16:50:33', '2025-03-12 17:03:07'),
(234, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '22:51:00', 31, 35, 21, 9, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 17:21:45', '2025-03-12 17:21:45'),
(235, 1, 17, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '22:52:00', 1, 4, 9, 43, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 17:22:48', '2025-03-12 17:22:48'),
(236, 1, 21, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '22:53:00', 6, 11, 12, 13, 21, 2, 10, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 17:24:04', '2025-03-12 18:04:33'),
(237, 1, 21, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '23:35:00', 6, 11, 12, 13, 21, 2, 2, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 18:05:19', '2025-03-12 18:05:19'),
(238, 1, 21, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '23:35:00', 6, 11, 12, 13, 21, 2, 5, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-12 18:07:00', '2025-03-12 18:07:58'),
(239, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-12', '23:38:00', 6, 24, 22, 10, 21, 2, 3, 21, 21, 3, NULL, NULL, NULL, NULL, '2025-03-12 18:08:33', '2025-03-13 15:22:43'),
(240, 1, 11, NULL, 11, 20, 0, 0, 0, 0, 21, 2, 2, 12, 2, 2, 4, NULL, 'Pre Quarter Finals', '2025-03-13', '06:12:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-13 00:42:20', '2025-03-13 00:45:46'),
(241, 1, 14, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-13', '06:14:00', 3, 11, 38, 22, 21, 2, 3, 21, 21, 3, NULL, NULL, NULL, NULL, '2025-03-13 00:44:57', '2025-03-13 01:02:16'),
(242, 1, 27, NULL, 17, 9, 0, 0, 0, 0, 2, 21, 1, 2, 0, 2, 4, NULL, 'Semifinals', '2025-03-13', '06:17:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-13 00:47:33', '2025-03-13 01:01:41'),
(243, 2, 12, NULL, 42, 16, 0, 0, 0, 0, 21, 4, 0, 0, 0, 3, 4, NULL, 'Pre Quarter Finals', '2025-11-11', '11:11:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-13 08:53:12', '2025-03-13 15:18:40'),
(244, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-11-13', '11:11:00', 2, 4, 44, 41, 21, 2, 2, 2, 5, 2, NULL, NULL, NULL, NULL, '2025-03-13 08:57:38', '2025-03-13 08:59:05'),
(245, 2, 11, NULL, 2, 37, 0, 0, 0, 0, 21, 2, 3, 13, 3, 3, 4, NULL, 'Semifinals', '2025-11-11', '11:11:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-13 09:39:52', '2025-03-13 15:22:13'),
(246, 1, 4, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2000-11-11', '11:11:00', 2, 3, 22, 36, 12, 21, 21, 12, 5, 21, NULL, NULL, NULL, NULL, '2025-03-13 12:13:24', '2025-03-13 12:15:16'),
(247, 1, 15, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Semifinals', '2000-11-11', '11:11:00', 18, 9, 4, 14, 21, 15, 14, 21, 21, 11, NULL, NULL, NULL, NULL, '2025-03-13 12:29:17', '2025-03-13 15:14:22'),
(248, 1, 11, NULL, 37, 13, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 4, NULL, 'Pre Quarter Finals', '2000-11-11', '11:01:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-13 15:49:11', '2025-03-13 16:24:42'),
(249, 1, 13, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2000-11-11', '11:01:00', 38, 42, 12, 14, 21, 2, 11, 21, 21, 2, NULL, NULL, NULL, NULL, '2025-03-13 15:52:07', '2025-03-13 16:24:19'),
(250, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2000-11-11', '11:11:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-14 02:26:51', '2025-03-14 02:26:51'),
(251, 1, 1, NULL, 2, 3, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-14', '14:44:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-14 09:15:05', '2025-03-14 09:15:48'),
(252, 1, 4, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 'Pre Quarter Finals', '2025-03-14', '14:46:00', 2, 3, 22, 36, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-03-14 09:16:27', '2025-03-14 09:16:50');

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
(16, '2025_02_09_153144_add_created_by_to_users_table', 9),
(17, '2025_02_11_180851_add_ip_address_columns_to_tables', 10),
(18, '2025_02_26_113416_add_timestamps_to_matches', 11),
(19, '2025_03_04_045702_create_admin_password_resets_table', 12),
(20, '2025_03_05_054203_add_dob_to_users_table', 13),
(21, '2025_03_05_055838_add_dob_to_users_table', 14),
(22, '2025_03_05_060841_add_dob_and_sex_to_users_table', 15),
(23, '2025_03_05_064350_create_password_resets_table', 16),
(24, '2025_03_05_065136_add_expires_at_to_admin_password_resets', 17),
(25, '2025_03_06_111245_add_expires_at_to_password_resets_table', 18),
(26, '2025_03_07_114047_add_security_fields_to_users', 19),
(27, '2025_03_07_120108_add_security_fields_to_players', 20);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`, `expires_at`) VALUES
('xxx@xxx.com', '19644a299e23661d740a9d8737d71bd99e226aca8da090ad0c65d5bf387c67c1', '2025-03-05 01:14:37', NULL),
('zzz@zzz.com', '572a44af342e334193ba32d4ea6f5f3158c8dbd024c9e6f4b8c3c20a84956146', '2025-03-05 22:21:40', NULL),
('xpindia@gmail.com', 'd8dc0293c3d65d40b0b08175953ac839d1572f97cf534358b86af0076a7bdfcc', '2025-03-08 10:05:24', '2025-03-08 10:15:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('xxx@xxx.com', '$2y$12$DGivlyW4FtLcg/bLlRDeFOMpNOCmJM2qKoC0npXw/6N0MRbowYz/e', '2025-03-05 01:14:37'),
('zzz@zzz.com', '$2y$12$PeDELyqPWFy/wKlsmOC6rOSrb1ZV2t6OGgDbaWFwNtrPBvp19vaEW', '2025-03-05 22:21:40');

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
  `email` varchar(255) DEFAULT NULL,
  `secondary_email` varchar(255) DEFAULT NULL,
  `dob` date NOT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('M','F') NOT NULL,
  `uid` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `secret_question1` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `secret_question2` varchar(255) DEFAULT NULL,
  `secret_question3` varchar(255) DEFAULT NULL,
  `mobile` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `name`, `email`, `secondary_email`, `dob`, `age`, `sex`, `uid`, `password`, `secret_question1`, `created_by`, `ip_address`, `updated_at`, `category_id`, `created_at`, `secret_question2`, `secret_question3`, `mobile`) VALUES
(1, 'Sreesha', 'jamesheartcare@gmail.com', NULL, '2008-01-01', 16, 'F', '100000', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 1, NULL, '2025-03-12 12:22:05', 0, '2025-01-24 03:01:50', NULL, NULL, '999991'),
(2, 'Eric James', 'ejrb2020@gmail.com', NULL, '2009-05-02', 15, 'M', '100001', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 1, NULL, '2025-03-12 12:22:05', 0, '2025-01-24 03:01:50', NULL, NULL, '999992'),
(3, 'Akshaj Tiwari', 'towaripriyanka2005@gmail.com', NULL, '2013-01-22', 12, 'M', '100002', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 1, NULL, '2025-03-12 12:22:05', 0, '2025-01-24 03:01:50', NULL, NULL, '999993'),
(4, 'Lakshmita', 'jamesheartcare@gmail.com', NULL, '2011-01-01', 13, 'F', '100004', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 1, NULL, '2025-03-12 12:22:05', 0, '2025-01-24 03:01:50', NULL, NULL, '999994'),
(6, 'Lee Chong Wei', 'lee22kk@gmail.com', NULL, '1980-01-03', 44, 'M', '100005', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 1, NULL, '2025-03-12 12:22:05', 0, '2025-01-24 03:01:50', NULL, NULL, '999996'),
(9, 'Lakshaya', 'jamesheartcare@gmail.com', NULL, '2010-01-01', 15, 'F', '100006', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '999999'),
(10, 'Gokulan', 'alagargokulan@gmail.com', NULL, '1998-01-01', 35, 'M', '100007', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999910'),
(11, 'Zanpear', 'jamesheartcare@gmail.com', NULL, '1978-05-01', 46, 'M', '100008', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999911'),
(12, 'Pandyraj', 'jamesheartcare@gmail.com', NULL, '1968-01-01', 57, 'M', '100009', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 20, '2025-01-24 03:01:50', NULL, NULL, '9999912'),
(13, 'Vijay', 'jamesheartcare@gmail.com', NULL, '1970-01-30', 54, 'M', '100010', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999913'),
(14, 'Tai Tzu Ying', 'jamesheartcare@gmail.com', NULL, '1998-01-01', 27, 'F', '100011', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999914'),
(15, 'An Se Young', 'jamesheartcare@gmail.com', NULL, '2000-01-01', 25, 'F', '100012', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999915'),
(16, 'Okuhara', 'jamesheartcare@gmail.com', NULL, '1998-01-01', 27, 'F', '100013', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999916'),
(17, 'Anitha Anthony', 'jamesheartcare@gmail.com', NULL, '2008-01-01', 17, 'F', '100014', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999917'),
(18, 'Carolina Marine', 'jamesheartcare@gmail.com', NULL, '1995-06-06', 29, 'F', '100015', '$2y$12$oAMjJaLvBukk6dDh5aVmu.EgCwKqw.sVGYTkllW6bwqrgCLcKaGzC', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999918'),
(19, 'PV Sindhu', 'jamesheartcare@gmail.com', NULL, '1995-06-07', 29, 'F', '100016', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999919'),
(20, 'Victor Axelsen', 'jamesheartcare@gmail.com', NULL, '1995-05-07', 29, 'M', '100017', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999920'),
(21, 'Lin Dan', 'jamesheartcare@gmail.com', NULL, '1986-02-06', 38, 'M', '100018', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, 4, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999921'),
(22, 'Harsh', 'jamesheartcare@gmail.com', NULL, '2008-01-17', 0, 'M', '100019', '$2y$10$2lIVZTjOymQ2m2mqgVHd8OW4KDQmS.pdH5ysk7aGrsL1X6zHNh7Ea', NULL, NULL, NULL, '2025-03-12 12:22:05', 1, '2025-01-24 03:01:50', NULL, NULL, '9999922'),
(24, 'Prince', 'jamesheartcare@gmail.com', NULL, '2007-02-02', 26, 'M', '100020', '$2y$12$DZ/9nkVKOJW1Kk47jyqgseEtolyJR5OP/3xgRxTYSrrGTJFIz0j4e', NULL, NULL, NULL, '2025-03-12 12:22:05', 1, '2025-02-11 12:32:37', NULL, NULL, '9999924'),
(31, 'Sriman', 'jamesheartcare@gmail.com', NULL, '2009-01-01', 25, 'M', '100026', '$2y$12$VTqpdhQGOSrpNRZBdMhvk.1n64LLWf1lx0vlDv/iShGx7UqjKMJiG', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 05:24:10', NULL, NULL, '9999931'),
(34, 'threefemale', 'jamesheartcare@gmail.com', NULL, '2011-10-18', 13, 'F', '100027', '$2y$12$nq0UqrcoRxyH9kwssmhAk.p6spIDyNmC.yctssqMnaoInD6ZW7Jaa', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 05:57:13', NULL, NULL, '9999934'),
(35, 'Okuhara Japan', 'jamesheartcare@gmail.com', NULL, '2007-09-17', 17, 'F', '100021', '$2y$12$2QpE1/UMQCZG9WRbryeEceHuE6dlgdrpKAzQRgwgk5tTQeQ.xQTWS', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 05:58:54', NULL, NULL, '9999935'),
(36, 'Lakshaya Sen', 'jamesheartcare@gmail.com', NULL, '2010-02-01', 15, 'M', '100023', '$2y$12$jJ6dDbNjh0eqwrnFze25sOXI1.nuZik7ozFEkWiIaGjszsghmqYvG', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 06:01:51', NULL, NULL, '9999936'),
(37, 'Kadambi Srikant', 'jamesheartcare@gmail.com', NULL, '1996-01-30', 29, 'M', '100003', '$2y$12$QPaaOwvZ0W42mlLJ9QrmYe0xDfw4SwTPvgi2iCXyudeSUDoWex/gy', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 06:03:33', NULL, NULL, '9999937'),
(38, 'Sai Praneet', 'jamesheartcare@gmail.com', NULL, '1999-02-08', 26, 'M', '100024', '$2y$12$4sbwhyz4Ara48Wg/Kd.GGeSMv4pxC2D5rQWQ8sQWfXa914htbcija', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 06:10:59', NULL, NULL, '9999938'),
(39, 'Saina Nehwal', 'jamesheartcare@gmail.com', NULL, '1997-06-17', 27, 'F', '100025', '$2y$12$eomoYeQzV5/52LC7mq9c3O0LO4yxxlTt1n5BfzLi0E5bHyFKIQZT.', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 06:11:51', NULL, NULL, '9999939'),
(40, 'Adriana', 'jamesheartcare@gmail.com', NULL, '2007-01-30', 18, 'F', '100028', '$2y$12$vobra7ZhPtLgt9ZzrLTqmuba1ZmRswWjj5cOD0Y955z3dq0HVAzK6', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 06:27:05', NULL, NULL, '9999940'),
(41, 'Preeti Kaur', 'jamesheartcare@gmail.com', NULL, '2007-02-06', 18, 'F', '100029', '$2y$12$Ml1tQUtA67Ro0yfXPvAJke7RVkEmxBp8ZLoXrZHVFkuB7kmthTnWK', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-01 06:35:00', NULL, NULL, '9999941'),
(42, 'Adrina Thomas', 'jamesheartcare@gmail.com', NULL, '2007-02-01', 18, 'F', '100030', '$2y$12$pqR8B2l3D8x7vVNfiiVj3.QykGnFby38MOikTgqeBsGrcgl9Rt7mO', NULL, NULL, '::1', '2025-03-12 12:22:05', 1, '2025-03-04 11:50:31', NULL, NULL, '9999942'),
(43, 'Priya', 'jamesheartcare@gmail.com', NULL, '2008-12-29', 16, 'F', '100022', '$2y$12$Wi9dC.LWtfgrK..3SFAKQe89ofRnb/g1zivegd1j1u0y2u4g6sMHC', NULL, NULL, '127.0.0.1', '2025-03-12 12:22:05', 1, '2025-03-04 12:17:02', NULL, NULL, '9999943'),
(44, 'Bharat', 'jamesheartcare@gmail.com', NULL, '2000-01-11', 25, 'M', '100031', '$2y$12$ak68q4wxLRmDx.5k9PGE6uKrNu9AlB62KqL4s2lna/a7RbIRnfmvy', NULL, NULL, '::1', '2025-03-12 12:22:05', 1, '2025-03-05 05:15:53', NULL, NULL, '9999944'),
(46, 'Deepshikha', 'Deepshikha@dssda.com', NULL, '2009-01-17', NULL, 'F', '100033', '$2y$12$zRqNBfe7UgmsGm/fULlHZuLByan.fk1BfaWiYLi1uKbnDBVWo3Mg2', NULL, NULL, '::1', '2025-03-13 01:06:39', 1, '2025-03-12 14:37:36', NULL, NULL, '9998946'),
(47, 'Jessie James', 'jjames2k13@gmail.com', NULL, '1974-10-15', NULL, 'F', '100034', '$2y$12$nfkFTo/GvoRBNZu/p0y92eht/uJJBOO.jaRyC31bKbcukcuVlu9Ke', NULL, NULL, '::1', '2025-03-12 15:25:27', 1, '2025-03-12 15:25:27', NULL, NULL, '9815900702'),
(48, 'Shreena', 'jjj@dg.com', NULL, '2008-11-11', NULL, 'F', '100035', '$2y$12$GeQHTuJZMkWMNsDuB7aUve1BHUOVbRDm2JzCtzZNOUpGfo1PHjxDu', NULL, NULL, '::1', '2025-03-13 08:52:08', 1, '2025-03-13 08:50:25', NULL, NULL, '3444333333');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1cbLAJwAh7zJfPjKNdZzPG9QKSWJaAkYxcdCR4hY', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOWFQRGY4M0hFWWZJeUVNQ21BVlpFQ1lOdmtDZ3ZKZ2NXYmg1Y2wxdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3QvYnRsL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1741345512),
('1U5I6Ys7oGcFS3Ef0tyR19Oaos8INCC8lYCKj8lr', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibGl3bGZTNmkycmpkTlVCV2lhdWZ6Q0VNYlJrMDNzOXBqUEFNWUlWdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvYnRsL3BsYXllcnMvZG91Ymxlcy1yYW5raW5nIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDtzOjE3OiJsb2NrZWRfdG91cm5hbWVudCI7czoxOiIxIjt9', 1741543045),
('9y4LRRlZLIOLeIeRHCQBrlLvvptQc6YNT1lrbAPB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSklKODZWUWdSNkxJSFNBcmlrbFhqeWFISm5JdEM5UWxzdGprRTFyVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1741178619),
('aNilAeC1P2jP2RrzYy39i90JeaXLM5Jn1zEjY77d', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidDFMMmtsMlhkMWFRODFobGFjQXplVHV2MUhHblY0S0trN3B5dE9uVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1741178481),
('CWzNXQ0abUEi6a1BdjsWSurZndFlW0A3bEPX2TpD', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3N0NllpUlVUaWNTWHpmRFRDT3lQa3JzNXhPbDlxbUFCbjRWT0JSOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3QvYnRsL2xvZ2luIjt9fQ==', 1741500268),
('Dk0EAnKC0IDjh5vLGv6go7HvMKF2tqptLFukz8jv', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZzdlYkFXU0JkTFpFSnV1RDVzMHVXQWNrQ3JXa25wb0N4UTFIWTZpRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3QvYnRsL3BsYXllcnMvcmVnaXN0ZXIiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1741782709),
('EGefeV7l7LTYQljKa5OCx6rCQi9z0Ka1sNgGlhvx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia1NFMnRHUDZMT3VhbUk3bTNTTmxYRTAyWHFPdE1KZDRHTXZhZ0xjQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1741178261),
('fqKZuDWSE9TGTWiTPegA3MgMRlbLs1OT4xxurwl0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUk9SRzRKdHNKazB4UzdmR20zeExhajVSNVFsZklMbGFCejVqb3ZsRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1741178532),
('fWGYgKXOUZKUJN9KYGllINn8roKHVGGLjXelub3J', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUkV6eGdsdVBiN0RRNlI3N2pzUTRPNDQ1VE5VUDhkSkFKTnpXWm5PQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3QvYnRsL21hdGNoZXMvc2luZ2xlcy9jcmVhdGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O3M6Mjg6ImxvY2tlZF9zaW5nbGVzX3RvdXJuYW1lbnRfaWQiO3M6MToiMSI7czoxNzoibG9ja2VkX3RvdXJuYW1lbnQiO3M6MToiMSI7fQ==', 1741920021),
('I21kyFbIfkeW0jNHD2U1eqwitoY7ECO6lcRs5e1A', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2FyNXJXRU5kMXBzT25NNURQVzhFbEw5YXhVSTdSRFAzeEtjYVJtZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1741178616),
('j2Akqwv7EwuiGfz4A63LjnY8cxlhMDrtxgyEF5Nc', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2szVmNJalV4ZVJPQ2hBcE1zdGYwMVVINmhoMFc0M2p0SWlpdEFQOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTQ0OiJodHRwOi8vbG9jYWxob3N0L2J0bC9tYXRjaGVzL2RvdWJsZXM/ZmlsdGVyX2NhdGVnb3J5PVhEJmZpbHRlcl9kYXRlPSZmaWx0ZXJfcGxheWVyPWFsbCZmaWx0ZXJfcmVzdWx0cz1hbGwmZmlsdGVyX3N0YWdlPWFsbCZmaWx0ZXJfdG91cm5hbWVudD1hbGwiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1741176803),
('lM3vUrwhUEJFD3r5OlSjkzwHKECVClkwQlxL6Dpy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoic0VsZVdwUWRJSnB2VlphS2xVeVJVeVZySzd3dUhSSlViemZqT0FsWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1741178488),
('UtMVYlSIV5Bo7KYuHcazfcaJ9g0M1OcF2NwgLxfX', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYmRZaEpzOWZIeU1uZTY3Z3dKZVVZY0hLYks1WjdMMFJZWFdjSVM1SyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYXRjaGVzL3NpbmdsZXMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O3M6MTc6ImxvY2tlZF90b3VybmFtZW50IjtzOjE6IjEiO3M6Mjg6ImxvY2tlZF9zaW5nbGVzX3RvdXJuYW1lbnRfaWQiO3M6MToiMSI7fQ==', 1740746519),
('V2hs5yqLe4Edy4KR18PSsfWXsfj0ph1hWCdWTzko', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZE5QY2Q1VFF6WGRueFRFUFI3ZWMxZ2JRaHN4T3lSa3k5U2hTMEdINiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1741178261),
('V8XQvaXNTOxe3NvnJS62wMufxwp0Acd85vQlUXpQ', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiM0FoMVQ0ZWNxREIyd2ZhMUpnZndZUElJZzdjWDQzYTFRUzhJd1FDZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3QvYnRsL3VzZXJzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDtzOjI4OiJsb2NrZWRfc2luZ2xlc190b3VybmFtZW50X2lkIjtzOjE6IjEiO3M6MTc6ImxvY2tlZF90b3VybmFtZW50IjtzOjE6IjEiO30=', 1741945784),
('X78bPKEZ8r2hBNS5tHfYQuOkz9da1qBbP6f5HTjE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRzhSOFZTcGp5eXRNMEpxNThVR0VrSVVYMnlKSFd0SUx4dmVnaVd3NiI7czo3OiJzdWNjZXNzIjtzOjI1OiJZb3UgaGF2ZSBiZWVuIGxvZ2dlZCBvdXQuIjtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MTp7aTowO3M6Nzoic3VjY2VzcyI7fX19', 1740829639),
('YA5SuWevZZEEcKTqh4m7ItucJQ8Qib9qgI3uoA6h', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNG9RQ3RMUmlHQ0JvZzZPcVRJMWVhTHYyQ0hRUFlpZ2loSG40c1JjdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1741178488),
('YlJaV8NIoXoxqcdqfJjkj37CIDNgifvBxYisExfG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3FsMkNuT21HVVZTZFo0Y3BZRGRwWWdnTlBKQkV5dnZPNkhYSURsNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1741178533);

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `name`, `created_by`, `year`, `moderated_by`, `created_at`, `updated_at`, `ip_address`) VALUES
(1, 'ABPL3', 4, 2024, 4, '2025-02-08 11:57:49', '2025-02-23 22:41:04', NULL),
(2, 'Super Series 2024', 2, 2024, 4, '2025-02-08 11:57:49', '2025-02-22 06:42:40', NULL),
(3, 'Winter Series', 4, 2024, 4, '2025-02-08 11:57:49', '2025-02-23 22:41:04', NULL),
(6, 'ACE Championship', 1, 2025, 4, '2025-02-08 11:57:49', '2025-03-09 14:12:25', NULL),
(7, 'xxxxsx', 65, 2025, 1, '2025-02-08 11:57:49', '2025-03-08 10:02:09', NULL),
(13, 'uuuuh', 65, 2025, NULL, '2025-02-08 11:57:49', '2025-03-09 14:12:25', NULL),
(14, 'xxxxaa', 62, 2025, NULL, '2025-02-08 11:57:49', '2025-03-13 08:42:01', NULL),
(17, 'zzz', 62, 2025, NULL, '2025-02-08 11:57:49', '2025-03-08 10:02:09', NULL),
(18, 'zzzz', 7, 2025, NULL, '2025-02-08 11:57:49', '2025-02-08 11:57:49', NULL),
(20, 'zlara25', 7, 2025, NULL, '2025-02-08 19:38:45', '2025-02-08 19:38:45', NULL),
(22, 'Test 2025', 4, 2025, NULL, '2025-02-21 05:57:22', '2025-02-23 22:41:04', NULL),
(23, 'TestMarch2025', 4, 2025, NULL, '2025-03-09 14:54:22', '2025-03-09 14:54:22', NULL),
(26, 'Hello', 4, 2025, NULL, '2025-03-09 15:52:32', '2025-03-09 15:52:32', NULL),
(27, 'testfriday', 4, 2025, NULL, '2025-03-14 08:12:27', '2025-03-14 08:12:27', NULL);

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
(16, 6, 4),
(32, 3, 1),
(33, 3, 11),
(34, 3, 20),
(94, 7, 1),
(97, 13, 18),
(98, 14, 11),
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
(115, 18, 25),
(116, 18, 26),
(117, 18, 27),
(146, 22, 1),
(147, 22, 2),
(148, 22, 3),
(149, 22, 4),
(150, 22, 6),
(151, 22, 7),
(152, 22, 8),
(153, 22, 11),
(154, 22, 12),
(155, 22, 13),
(156, 22, 14),
(157, 22, 15),
(158, 22, 16),
(159, 22, 17),
(160, 22, 18),
(161, 22, 19),
(162, 22, 20),
(163, 22, 21),
(164, 22, 22),
(165, 22, 23),
(166, 22, 25),
(167, 22, 26),
(168, 22, 27),
(169, 22, 28),
(170, 22, 29),
(171, 22, 30),
(172, 22, 31),
(173, 22, 32),
(240, 2, 1),
(241, 2, 4),
(522, 26, 1),
(523, 26, 2),
(524, 26, 3),
(525, 26, 4),
(526, 26, 11),
(527, 26, 12),
(528, 26, 13),
(529, 26, 14),
(530, 26, 20),
(531, 26, 23),
(622, 1, 1),
(623, 1, 2),
(624, 1, 3),
(625, 1, 4),
(626, 1, 6),
(627, 1, 7),
(628, 1, 8),
(629, 1, 11),
(630, 1, 12),
(631, 1, 13),
(632, 1, 14),
(633, 1, 15),
(634, 1, 16),
(635, 1, 17),
(636, 1, 18),
(637, 1, 19),
(638, 1, 20),
(639, 1, 21),
(640, 1, 22),
(641, 1, 23),
(642, 1, 25),
(643, 1, 26),
(644, 1, 27),
(645, 1, 28),
(646, 1, 29),
(647, 1, 30),
(648, 1, 31),
(649, 1, 32),
(650, 1, 33),
(651, 1, 34),
(652, 27, 1),
(653, 27, 2),
(654, 27, 3),
(655, 27, 4);

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
(90, 1, 4),
(107, 1, 65),
(40, 2, 2),
(41, 2, 4),
(42, 2, 6),
(37, 6, 1),
(5, 6, 4),
(36, 7, 56),
(105, 7, 65),
(35, 13, 1),
(3, 13, 6),
(50, 13, 56),
(102, 13, 62),
(53, 13, 63),
(106, 13, 65),
(10, 14, 7),
(103, 14, 62),
(15, 17, 7),
(104, 17, 62),
(21, 18, 7),
(20, 18, 17),
(19, 21, 7),
(22, 22, 2),
(23, 22, 4),
(108, 25, 17),
(84, 26, 21),
(91, 27, 4),
(92, 27, 64);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(66) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `last_login` datetime DEFAULT current_timestamp(),
  `profile_picture` varchar(999) DEFAULT NULL,
  `secondary_email` varchar(255) DEFAULT NULL,
  `secret_question1` varchar(255) DEFAULT NULL,
  `secret_question2` varchar(255) DEFAULT NULL,
  `secret_question3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `dob`, `sex`, `role`, `created_by`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `ip_address`, `created_at`, `updated_at`, `mobile_no`, `last_login`, `profile_picture`, `secondary_email`, `secret_question1`, `secret_question2`, `secret_question3`) VALUES
(1, 'user', 'user@user.com', '2007-01-09', 'Male', 'user', 4, NULL, '$2y$12$MsyjI5qJ2gNqZcpOnWU4QOhq1N.7HU2GQbTW9SkqwaQAWZZ1jrpi.', NULL, NULL, NULL, NULL, NULL, '2024-12-28 22:20:28', '2025-03-09 14:12:25', '1111111111', NULL, 'uploads/profiles/1737177818_WhatsApp Image 2025-01-18 at 08.59.27.jpeg', NULL, NULL, NULL, NULL),
(2, 'admin', 'admin@admin.com', NULL, NULL, 'admin', 4, NULL, '$2y$10$Vzemd6vNZoJ7tsir9lxqKuBfkPhks/ZL3mB6YRRNKRLg3H8THFdba', NULL, NULL, NULL, NULL, NULL, '2024-12-28 22:41:42', NULL, '7432001215', NULL, 'default.png', NULL, NULL, NULL, NULL),
(4, 'Robert James', 'xpindia@gmail.com', '1967-06-08', 'Male', 'admin', 4, NULL, '$2y$12$TDdgHaXHdTld0QpkEFFnHOFZye4.vRNCuT7kuwqHrCqr1ThuRPg1i', NULL, NULL, NULL, 'og0dDkZwpxWUzekYog7V3AWasAt2z4G9io3dBwDURFpeOjpw8s6u2BLwUIiK', NULL, '2024-12-29 06:40:35', '2025-03-08 10:05:59', '3332222222', NULL, 'default.png', NULL, NULL, NULL, NULL),
(5, 'user2', 'user2@jdjdj.com', NULL, NULL, 'user', 4, NULL, '$2y$10$h2N1Jb3tCQ72X.KWuQaB8eUfBfJa61DULmbLDzMArIlUdtpj4im.m', NULL, NULL, NULL, NULL, NULL, '2024-12-31 09:58:19', NULL, '2222222222', NULL, 'default.png', NULL, NULL, NULL, NULL),
(6, 'user1', 'asda@sd.asda', NULL, NULL, 'user', 4, NULL, '$2y$10$630Wk4DbeWyToUcclXn66.2YMBCpUb8/ZwAvZwsbMU72PF3nNWdB2', NULL, NULL, NULL, NULL, NULL, '2025-01-10 00:25:38', NULL, '2222222222', NULL, 'default.png', NULL, NULL, NULL, NULL),
(7, 'Mr ZZZ', 'zzz@zzz.com', '1995-02-01', 'Male', 'user', 4, NULL, '$2y$12$uKs3Byi3YmAvEaEsd1erE.dCpyeg7cnGPf450qdbWyrsD4RJR.4n6', NULL, NULL, NULL, 'oKpZX7NIBNzSn03AXCF2xot10hFJ3aiqk4ysUGrg47r599eoIeSrbF7jIfYV', NULL, '2025-01-10 22:23:39', '2025-03-05 22:10:47', '1111111111', NULL, 'default.png', NULL, NULL, NULL, NULL),
(14, 'nnn', 'nnn@nnn.com', NULL, NULL, 'visitor', 4, NULL, '$2y$12$M1pGTW.gdl3LHVSUJPaD0ue4Gt4n4fJBo/SSOHGIM6c4czFgQfSC2', NULL, NULL, NULL, NULL, NULL, '2025-02-09 03:03:13', '2025-02-09 03:03:13', '3333333333', '2025-02-09 14:03:13', NULL, NULL, NULL, NULL, NULL),
(15, 'zxc', 'zxc@zxc.com', NULL, NULL, 'visitor', 4, NULL, '$2y$12$0v0M3eqYj4joU7BUssufquppkYAsJ4Jaq6JlqAbgyMry5RQ1/Irn2', NULL, NULL, NULL, NULL, NULL, '2025-02-09 03:06:23', '2025-02-09 03:06:23', '2233223322', '2025-02-09 14:06:23', NULL, NULL, NULL, NULL, NULL),
(16, 'xxxx', 'xxxx@xxxx.com', NULL, NULL, 'visitor', 4, NULL, '$2y$12$mAE8cI0ulrxTvX7bxryYeu368a121Om0m6ZkTiC2SufwCAdaZKKPG', NULL, NULL, NULL, NULL, NULL, '2025-02-09 03:15:54', '2025-02-09 03:15:54', '1111111111', '2025-02-09 14:15:54', NULL, NULL, NULL, NULL, NULL),
(17, 'ccx', 'ccx@ccx.com', NULL, NULL, 'visitor', 4, NULL, '$2y$12$34I4kKKFtDUrBmeAcZBaeOWq8T0BDPVVy/iqovJk8IsijBV39pG6W', NULL, NULL, NULL, NULL, NULL, '2025-02-09 03:22:18', '2025-02-09 03:22:18', '1122332222', '2025-02-09 14:22:18', NULL, NULL, NULL, NULL, NULL),
(18, 'ddd', 'ddd@ddd.com', NULL, NULL, 'user', 4, NULL, '$2y$12$uk6.hL9HSyp6IhBGKc8v0eCK9iv5nvp0orJM2GpHJQ0lhbUVlPco.', NULL, NULL, NULL, NULL, NULL, '2025-02-09 03:42:30', '2025-02-09 03:42:30', '2222222222', '2025-02-09 14:42:30', NULL, NULL, NULL, NULL, NULL),
(19, 'dddd', 'dddd@dddd.com', NULL, NULL, 'visitor', 4, NULL, '$2y$12$FIJbBToBzJ1mt1TNvoi5f.c2MIAuRxgcoG93CAV8LeHPFXxNmyCHG', NULL, NULL, NULL, NULL, NULL, '2025-02-09 03:42:54', '2025-02-09 03:42:54', '2222222222', '2025-02-09 14:42:54', NULL, NULL, NULL, NULL, NULL),
(20, 'qww', 'qww@qww.com', NULL, NULL, 'user', 4, NULL, '$2y$12$HrqCRaqCSdab1zAz6pA9U.OxQ4rYVnUUh8.CnaeroJcmqw2vp.fR6', NULL, NULL, NULL, NULL, NULL, '2025-02-09 04:32:44', '2025-02-09 04:32:44', '2222222222', '2025-02-09 15:32:44', NULL, NULL, NULL, NULL, NULL),
(21, 'ccc', 'ccc@ccc.com', NULL, NULL, 'user', 4, NULL, '$2y$12$e76QH5HAWX0fB9uCitKayeBMpEYyu00m.6zSn8jtpJYb1Qd25cpu.', NULL, NULL, NULL, NULL, NULL, '2025-02-09 05:51:36', '2025-02-09 05:51:36', '2222222222', '2025-02-09 16:51:36', NULL, NULL, NULL, NULL, NULL),
(41, 'xzzz', 'xzzz@xzzz.com', NULL, NULL, 'user', 4, NULL, '$2y$12$i6jS0uiVby0J/4SfunnYJuCr9hBY79GESqS4OKdU1CdMbw8MZ2INO', NULL, NULL, NULL, NULL, NULL, '2025-02-10 00:30:37', '2025-02-10 00:30:37', '1111111111', '2025-02-10 06:00:37', NULL, NULL, NULL, NULL, NULL),
(42, 'xxzz', 'xzxz@zxz.xx', NULL, NULL, 'user', 4, NULL, '$2y$12$xdrSrLDEYDQjD3Pgoj/sS.yCJlwVckXeOI1WDNF9A2.sD9JmuwyoC', NULL, NULL, NULL, NULL, NULL, '2025-02-10 00:37:44', '2025-02-10 00:37:44', '2222222222', '2025-02-10 06:07:44', NULL, NULL, NULL, NULL, NULL),
(43, 'xxzzxx', 'czxc@dad.com', NULL, NULL, 'user', 4, NULL, '$2y$12$9GeDrGRkOHpt/LEXNeBqDOPYWSjChdgyFpKusA61.z3aDR8mtpGA6', NULL, NULL, NULL, NULL, NULL, '2025-02-10 00:47:16', '2025-02-10 00:47:16', '2222222222', '2025-02-10 06:17:16', NULL, NULL, NULL, NULL, NULL),
(44, 'xxxz', 'xzczc@sdsd.com', NULL, NULL, 'user', 4, NULL, '$2y$12$dBQuM8XT4Wf7pjy0EPU7c.RmVjDSlS4dhKc6GprvyhbxKAXL6RkGm', NULL, NULL, NULL, NULL, NULL, '2025-02-10 01:01:34', '2025-02-10 01:01:34', '2222222222', '2025-02-10 06:31:34', NULL, NULL, NULL, NULL, NULL),
(46, 'testuser2', 'test2@example.com', NULL, NULL, 'user', 1, NULL, '$2y$12$4a/SZNzSxGL.7WkFY1.CDu8g/SW99b9eAeEWmy7e83PxYqrvBuG7C', NULL, NULL, NULL, NULL, NULL, '2025-02-10 01:06:34', '2025-02-10 01:06:34', '9876543210', '2025-02-10 06:36:34', NULL, NULL, NULL, NULL, NULL),
(49, 'testuser3', 'test3@example.com', NULL, NULL, 'user', 1, NULL, '$2y$12$/KZjcQja7hE7mhtQUDqide7rUfhHbI79FptKRwWuFAhK3HKj.SCD.', NULL, NULL, NULL, NULL, NULL, '2025-02-10 01:52:01', '2025-02-10 01:52:01', '9876543210', '2025-02-10 07:22:01', NULL, NULL, NULL, NULL, NULL),
(52, 'xxcdx', 'xxcdd@xxcdd.com', NULL, NULL, 'visitor', 4, NULL, '$2y$12$xmUUP0wxOs1CpHj6kWEhEuVy2rEWSSrzx0/OB9b41lKBq2O2QFgjm', NULL, NULL, NULL, NULL, NULL, '2025-02-10 07:47:29', '2025-02-21 05:42:14', '2211777777', '2025-02-10 13:17:29', NULL, NULL, NULL, NULL, NULL),
(53, 'zzzuserx', 'zzzuser@zzzuser.com', NULL, NULL, 'admin', 7, NULL, '$2y$12$bmIJs.jtPS3UgzobzsKrMu/Y6sV4jWvAIVfK5/SfWUpLtBJUyj.Pu', NULL, NULL, NULL, NULL, NULL, '2025-02-21 23:11:43', '2025-02-21 23:12:04', '1111111111', '2025-02-22 10:11:43', NULL, NULL, NULL, NULL, NULL),
(55, 'Mandeep', 'mmm@mmm.com', NULL, NULL, 'user', 4, NULL, '$2y$12$YEZ4K/s/aRcuhivS9IBJ5ODjjUYr5NqCRYZeyr8kt457NgE.7cH.O', NULL, NULL, NULL, NULL, NULL, '2025-03-04 23:33:42', '2025-03-04 23:33:42', '2222222222', '2025-03-05 10:33:42', NULL, NULL, NULL, NULL, NULL),
(56, 'TestightMarc', 'fdfsd@asasd.asd', '2008-01-29', 'Male', 'user', 4, NULL, '$2y$12$dHSUPrJSapPakivktxh/DuVlo6MtswK/uYzsgaFiKiwK1ahc3m2mK', NULL, NULL, NULL, NULL, NULL, '2025-03-08 05:30:07', '2025-03-13 01:39:44', '2222222200', '2025-03-08 11:00:07', NULL, NULL, NULL, NULL, NULL),
(57, 'helloaaa', 'dadas@sdfsdfs.com', '2000-01-29', 'Male', 'user', 4, NULL, '$2y$12$5W5/wVXuvp97L7TmnKW7Eefx.n41IU71qsw6D4Y8mxlQgVOJtAC0C', NULL, NULL, NULL, NULL, NULL, '2025-03-13 01:41:32', '2025-03-13 01:55:01', '2929292929', '2025-03-13 07:11:32', NULL, NULL, NULL, NULL, NULL),
(58, 'hhfhf', 'fghf@fgdfgd.fdsf', NULL, NULL, 'user', NULL, NULL, '$2y$12$d6wdJhG3WJMXYOl25m0rYuS05XdvG6ju6D6sIdeX2othKTrNeF2I.', NULL, NULL, NULL, NULL, NULL, '2025-03-13 01:55:51', '2025-03-13 01:55:51', '3344556677', '2025-03-13 07:25:51', NULL, NULL, NULL, NULL, NULL),
(59, 'dsdsdw', 'dsfs@dsfsd.dfds', NULL, NULL, 'user', NULL, NULL, '$2y$12$JiQ7/94MJA4JDqwXvOwD8OmmnWhYZtampbEBO81Z3d6j0fXaLGd7S', NULL, NULL, NULL, NULL, NULL, '2025-03-13 02:01:19', '2025-03-13 02:01:19', '3456789000', '2025-03-13 07:31:19', NULL, NULL, NULL, NULL, NULL),
(61, 'roots', 'as@sdsa.asd', '2000-11-11', 'Male', 'user', 4, NULL, '$2y$12$fOvCMR7CQsNmmN1219juc.9FLdSSHGHj8iyWIf4msHIVtFvFmSMri', NULL, NULL, NULL, NULL, NULL, '2025-03-13 07:59:47', '2025-03-13 07:59:47', '3344552345', '2025-03-13 13:29:47', NULL, NULL, NULL, NULL, NULL),
(64, 'Roman Beast', 'roman@asd.tt', '2000-11-11', 'Male', 'user', 4, NULL, '$2y$12$./oo3cQUaiR0mXa5GRFkIOulfaILtRwFB9NGggR1mnr/kRBtIFJxu', NULL, NULL, NULL, NULL, NULL, '2025-03-13 08:46:35', '2025-03-13 08:48:37', '1233212342', '2025-03-13 14:16:35', NULL, NULL, NULL, NULL, NULL),
(65, 'Peter GG', 'aqda@sda.sds', '2000-11-11', 'Male', 'user', 4, NULL, '$2y$12$Uq92rY.ZxX.IT0SH3Pz1ru2sG4Njt30y2Cxum7Uxyq/XZZDLl.SgK', NULL, NULL, NULL, NULL, NULL, '2025-03-14 04:01:14', '2025-03-14 08:27:23', '1344554312', '2025-03-14 09:31:14', NULL, NULL, NULL, NULL, NULL),
(66, 'Timothy', 'xxs@sls.cc', '2000-11-11', 'Male', 'user', 4, NULL, '$2y$12$Rv4bvGUuqrCm7bFK4P6WLOMC3HQLHziSwAzd5TMtmfYmYHvCJNB1O', NULL, NULL, NULL, NULL, NULL, '2025-03-14 08:57:40', '2025-03-14 09:02:08', '2222222211', '2025-03-14 14:27:40', NULL, NULL, NULL, NULL, NULL),
(67, 'Yin Mo', 'ssa@adaw.xx', '2009-11-11', 'Female', 'user', 4, NULL, '$2y$12$XGUHVsld.dVJxta3loVt7eYU9.lvOdjV/uW17ryWynUHK/GiTx8Ci', NULL, NULL, NULL, NULL, NULL, '2025-03-14 09:49:40', '2025-03-14 09:49:40', '2333333334', '2025-03-14 15:19:40', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_password_resets_token_unique` (`token`),
  ADD KEY `admin_password_resets_email_index` (`email`);

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
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_favorites_user` (`user_id`);

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

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
  ADD UNIQUE KEY `uid` (`uid`),
  ADD UNIQUE KEY `uid_2` (`uid`),
  ADD UNIQUE KEY `mobile` (`mobile`);

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
  ADD KEY `fk_created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `category_access`
--
ALTER TABLE `category_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;

--
-- AUTO_INCREMENT for table `match_details`
--
ALTER TABLE `match_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `player_access`
--
ALTER TABLE `player_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tournament_categories`
--
ALTER TABLE `tournament_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=656;

--
-- AUTO_INCREMENT for table `tournament_moderators`
--
ALTER TABLE `tournament_moderators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 26, 2022 at 03:45 AM
-- Server version: 8.0.27-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mv`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_portals`
--

CREATE TABLE `bank_portals` (
  `id` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cart` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shaba` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('yes','no','pending') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `title`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'فیلم', 'fylm', 'yes', '2022-03-30 22:38:22', '2022-05-09 16:23:17'),
(2, 0, 'سریال', 'sryal', 'yes', '2022-03-30 22:38:31', '2022-03-30 22:38:31'),
(4, 1, 'فیلم خارجی', 'khargy', 'yes', '2022-03-30 22:38:59', '2022-05-09 16:23:18'),
(8, 2, 'سریال خارجی', 'sryal-khargy', 'yes', '2022-03-30 22:40:50', '2022-03-30 22:40:50'),
(10, 1, 'انیمیشن خارجی', 'anymyshn-khargy', 'yes', '2022-05-20 19:22:31', '2022-05-20 19:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

CREATE TABLE `checkouts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `amount` int NOT NULL,
  `status` enum('paid','no','pending','cancel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `code` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`id`, `user_id`, `code`, `created_at`, `updated_at`) VALUES
(22, 4, 211138, '2022-05-13 17:11:17', '2022-05-13 17:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('yes','no','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `commentable_id` int NOT NULL,
  `commentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_answers`
--

CREATE TABLE `comment_answers` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('yes','no','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config_sites`
--

CREATE TABLE `config_sites` (
  `id` bigint UNSIGNED NOT NULL,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `copy_right` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telegram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `omdb_api` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `about_us` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_us` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `bc_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `front_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `vip` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alert` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `alert_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `config_sites`
--

INSERT INTO `config_sites` (`id`, `site_name`, `email`, `mobile`, `phone`, `logo`, `logo_mobile`, `copy_right`, `telegram`, `twitter`, `facebook`, `instagram`, `omdb_api`, `min_amount`, `max_amount`, `about_us`, `contact_us`, `bc_link`, `front_link`, `page`, `vip`, `alert`, `description`, `alert_link`, `created_at`, `updated_at`) VALUES
(1, 'آرا مووی', NULL, NULL, NULL, '/uploader/gallery/lg-logo.png', '/uploader/gallery/sm-logo.png', 'تمامی حقوق مربوط به سایت آرا مووی میباشد هرگونه کپی برداری و پخش غیر مجاز پیگرد قانونی دارد', NULL, NULL, NULL, NULL, NULL, '500000', '10000000', 'yes', 'yes', 'https://bc.irndevelopers.ir', 'https://irndevelopers.ir', 'yes', 'yes', 'این یک اعلان تستی است', 'تلاش خود را می‌کنیم تا جدیدترین و بروزترین سریال های خارجی را در اختیارتان قرار دهیم، میتوانید از طریق این بخش سریال مورد نظر خود را دانلود و سپس به اتفاق خانواده، آنرا تماشا کنید. سریال های سایت هکس دانلود بازبینی و فاقد صحنه غیر اخلاقی میباشد. برای دانلود سریال خارجی با ما همراه باشید.', NULL, '2022-04-20 11:37:24', '2022-05-09 17:28:15');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` int NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint UNSIGNED NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `likeable_id` int NOT NULL,
  `type` enum('like','dislike') COLLATE utf8mb4_unicode_ci NOT NULL,
  `likeable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `ip`, `likeable_id`, `type`, `likeable_type`, `created_at`, `updated_at`) VALUES
(1, '127.0.0.1', 1, 'dislike', 'App\\Models\\Comment', '2022-05-12 13:29:40', '2022-05-12 13:47:17'),
(2, '127.0.0.1', 1, 'like', 'App\\Models\\CommentAnswer', '2022-05-12 13:31:09', '2022-05-12 13:47:13'),
(3, '127.0.0.1', 2, 'dislike', 'App\\Models\\CommentAnswer', '2022-05-12 13:31:15', '2022-05-12 13:49:06'),
(4, '127.0.0.1', 3, 'like', 'App\\Models\\CommentAnswer', '2022-05-12 13:31:20', '2022-05-12 13:47:16'),
(5, '127.0.0.1', 1, 'like', 'App\\Models\\Movie', '2022-05-12 13:46:36', '2022-05-17 21:57:30'),
(6, '127.0.0.1', 2, 'dislike', 'App\\Models\\Movie', '2022-05-12 13:50:14', '2022-05-12 13:50:16');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` bigint UNSIGNED NOT NULL,
  `movie_link_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `movie_link_id`, `title`, `link`, `caption`, `subtitle`, `created_at`, `updated_at`) VALUES
(11, 7, 'BluRay x265 10bit 1080p', 'https://example.com', 'Encoder: PSA حجم: 1.8 GB', '/uploader/gallery/subtitles/CODA.2021.BluRay.Fa.srt', '2022-05-20 19:41:00', '2022-05-20 19:41:00'),
(12, 7, 'BluRay 1080p', 'https://example.com', 'Encoder: YTS حجم: 2.0 GB', '/uploader/gallery/subtitles/CODA.2021.BluRay.Fa.srt', '2022-05-20 19:41:00', '2022-05-20 19:41:00'),
(13, 7, 'BluRay x265 10bit 720p', 'https://example.com', 'Encoder: YTS حجم: 1.0 GB', '/uploader/gallery/subtitles/CODA.2021.BluRay.Fa.srt', '2022-05-20 19:41:00', '2022-05-20 19:41:00'),
(14, 8, 'WEB-DL 1080p', 'https://example.com', 'Encoder: YTS حجم: 1.7 GB', NULL, '2022-05-20 19:44:08', '2022-05-20 19:44:08'),
(15, 8, 'WEB-DL 720p', 'https://example.com', 'Encoder: YTS حجم: 860 MB', NULL, '2022-05-20 19:44:08', '2022-05-20 19:44:08'),
(16, 9, 'WEB-DL 720p', 'https://example.com', 'Encoder: YTS حجم: 860 MB', NULL, '2022-05-20 19:50:03', '2022-05-20 19:50:03'),
(17, 10, 'قسمت اول', 'https://example.com', 'Encoder: YTS حجم: 400 MB', NULL, '2022-05-20 19:53:06', '2022-05-20 19:53:06'),
(18, 10, 'قسمت دوم', 'https://example.com', 'Encoder: YTS حجم: 400 MB', NULL, '2022-05-20 19:53:06', '2022-05-20 19:53:06'),
(19, 10, 'قسمت سوم', 'https://example.com', 'Encoder: YTS حجم: 400 MB', NULL, '2022-05-20 19:53:06', '2022-05-20 19:53:06'),
(23, 12, 'WEB-DL 1080p Full HD', 'https://example.com', 'Encoder: انتخاب انکودر حجم: 4.7 GB', NULL, '2022-05-20 19:57:07', '2022-05-20 19:57:07'),
(24, 12, 'WEB-DL x265 10bit 720p', 'https://example.com', 'Encoder: PSA حجم: 910 MB', NULL, '2022-05-20 19:57:07', '2022-05-20 19:57:07'),
(25, 12, 'WEB-DL 480p', 'https://example.com', 'Encoder: Pahe حجم: 550 MB', NULL, '2022-05-20 19:57:07', '2022-05-20 19:57:07'),
(26, 13, 'BluRay 10bit HDR 4K 2160p', 'https://example.com', 'Encoder: PSA حجم: 4.8 GB', NULL, '2022-05-20 19:59:25', '2022-05-20 19:59:25'),
(27, 13, 'BluRay x265 10bit 720p', 'https://example.com', 'Encoder: PSA حجم: 1.2 GB', NULL, '2022-05-20 19:59:25', '2022-05-20 19:59:25'),
(28, 14, 'BluRay 1080p', 'https://example.com', 'Encoder: Pahe حجم: 3.1 GB', NULL, '2022-05-20 20:00:02', '2022-05-20 20:00:02'),
(29, 14, 'BluRay 720p', 'https://example.com', 'Encoder: Pahe حجم: 1.3 GB', NULL, '2022-05-20 20:00:02', '2022-05-20 20:00:02'),
(30, 15, 'BluRay 10bit HDR 4K 2160p', 'https://example.com', 'Encoder: PSA حجم: 4.6 GB', NULL, '2022-05-20 20:01:39', '2022-05-20 20:01:39'),
(31, 15, 'BluRay 480p', 'https://example.com', 'Encoder: Pahe حجم: 600 MB', NULL, '2022-05-20 20:01:39', '2022-05-20 20:01:39'),
(32, 16, 'قسمت اول', 'https://example.com', 'Encoder: PSA حجم: 300 MB', NULL, '2022-05-20 20:03:51', '2022-05-20 20:03:51'),
(33, 16, 'قسمت دوم', 'https://example.com', 'Encoder: PSA حجم: 300 MB', NULL, '2022-05-20 20:03:51', '2022-05-20 20:03:51'),
(34, 16, 'قسمت سوم', 'https://example.com', 'Encoder: PSA حجم: 300 MB', NULL, '2022-05-20 20:03:51', '2022-05-20 20:03:51'),
(38, 17, 'قسمت اول', 'https://example.com', 'Encoder: PSA حجم: 200 MB', NULL, '2022-05-20 20:06:52', '2022-05-20 20:06:52'),
(39, 11, 'قسمت اول', 'https://example.com', 'Encoder: PSA حجم: 200 MB', NULL, '2022-05-20 20:07:55', '2022-05-20 20:07:55'),
(40, 11, 'قسمت دوم', 'https://example.com', 'Encoder: PSA حجم: 200 MB', NULL, '2022-05-20 20:07:55', '2022-05-20 20:07:55'),
(41, 11, 'قسمت سوم', 'https://example.com', 'Encoder: PSA حجم: 200 MB', NULL, '2022-05-20 20:07:55', '2022-05-20 20:07:55');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_30_160937_create_categories_table', 2),
(6, '2022_03_31_071006_create_permission_tables', 3),
(7, '2022_03_31_130121_create_movies_table', 4),
(8, '2022_04_02_064032_create_movie_links_table', 5),
(9, '2022_04_02_064048_create_trailers_table', 5),
(10, '2022_04_02_064453_create_screen_shots_table', 5),
(11, '2022_04_02_064516_create_links_table', 5),
(12, '2022_04_02_131227_create_movie_tags_table', 6),
(13, '2022_04_02_174654_create_plans_table', 7),
(14, '2022_04_03_171749_create_discounts_table', 8),
(15, '2022_04_05_103050_create_codes_table', 9),
(16, '2022_04_06_151340_create_carts_table', 10),
(18, '2022_04_08_064513_create_bank_portals_table', 11),
(19, '2022_04_07_144307_create_wallet_transactions_table', 12),
(20, '2022_04_10_063405_create_wallets_table', 13),
(21, '2022_04_10_081118_create_checkouts_table', 14),
(22, '2022_04_10_111848_create_vip_transactions_table', 15),
(23, '2022_04_11_071013_create_comments_table', 16),
(24, '2022_04_11_072136_create_comment_answers_table', 16),
(25, '2022_04_11_113149_create_pages_table', 17),
(26, '2022_04_11_122837_create_advertisings_table', 18),
(27, '2022_04_12_182947_create_config_sites_table', 19),
(28, '2022_04_15_090503_create_likes_table', 20),
(29, '2022_04_16_134946_create_report_links_table', 21),
(30, '2022_04_17_180813_create_watch_lists_table', 22),
(31, '2022_04_20_064126_create_notifications_table', 23),
(32, '2022_05_11_053454_create_sliders_table', 24);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 1),
(10, 'App\\Models\\User', 1),
(11, 'App\\Models\\User', 1),
(12, 'App\\Models\\User', 1),
(13, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 1),
(15, 'App\\Models\\User', 1),
(16, 'App\\Models\\User', 1),
(17, 'App\\Models\\User', 1),
(18, 'App\\Models\\User', 1),
(19, 'App\\Models\\User', 1),
(20, 'App\\Models\\User', 1),
(21, 'App\\Models\\User', 1),
(22, 'App\\Models\\User', 1),
(23, 'App\\Models\\User', 1),
(24, 'App\\Models\\User', 1),
(25, 'App\\Models\\User', 1),
(26, 'App\\Models\\User', 1),
(27, 'App\\Models\\User', 1),
(28, 'App\\Models\\User', 1),
(29, 'App\\Models\\User', 1),
(30, 'App\\Models\\User', 1),
(31, 'App\\Models\\User', 1),
(32, 'App\\Models\\User', 1),
(33, 'App\\Models\\User', 1),
(34, 'App\\Models\\User', 1),
(35, 'App\\Models\\User', 1),
(36, 'App\\Models\\User', 1),
(37, 'App\\Models\\User', 1),
(38, 'App\\Models\\User', 1),
(39, 'App\\Models\\User', 1),
(40, 'App\\Models\\User', 1),
(41, 'App\\Models\\User', 1),
(42, 'App\\Models\\User', 1),
(43, 'App\\Models\\User', 1),
(44, 'App\\Models\\User', 1),
(45, 'App\\Models\\User', 1),
(46, 'App\\Models\\User', 1),
(47, 'App\\Models\\User', 1),
(48, 'App\\Models\\User', 1),
(49, 'App\\Models\\User', 1),
(50, 'App\\Models\\User', 1),
(51, 'App\\Models\\User', 1),
(52, 'App\\Models\\User', 1),
(53, 'App\\Models\\User', 1),
(54, 'App\\Models\\User', 1),
(55, 'App\\Models\\User', 1),
(56, 'App\\Models\\User', 1),
(57, 'App\\Models\\User', 1),
(58, 'App\\Models\\User', 1),
(59, 'App\\Models\\User', 1),
(60, 'App\\Models\\User', 1),
(61, 'App\\Models\\User', 1),
(62, 'App\\Models\\User', 1),
(63, 'App\\Models\\User', 1),
(64, 'App\\Models\\User', 1),
(65, 'App\\Models\\User', 1),
(66, 'App\\Models\\User', 1),
(67, 'App\\Models\\User', 1),
(68, 'App\\Models\\User', 1),
(69, 'App\\Models\\User', 1),
(70, 'App\\Models\\User', 1),
(71, 'App\\Models\\User', 1),
(72, 'App\\Models\\User', 1),
(73, 'App\\Models\\User', 1),
(74, 'App\\Models\\User', 1),
(75, 'App\\Models\\User', 1),
(76, 'App\\Models\\User', 1),
(77, 'App\\Models\\User', 1),
(78, 'App\\Models\\User', 1),
(79, 'App\\Models\\User', 1),
(80, 'App\\Models\\User', 1),
(81, 'App\\Models\\User', 1),
(82, 'App\\Models\\User', 1),
(83, 'App\\Models\\User', 1),
(84, 'App\\Models\\User', 1),
(85, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fa_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('serial','movie') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `director` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actor` text COLLATE utf8mb4_unicode_ci,
  `imdb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imdb_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `critics` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `play_status` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `broadcast_day` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `network` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dubbed` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_comment` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `soon` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `moreDescription` text COLLATE utf8mb4_unicode_ci,
  `keyword` text COLLATE utf8mb4_unicode_ci,
  `awards` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imdbId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `category_id`, `title`, `fa_title`, `slug`, `type`, `image`, `quality`, `genre`, `product`, `lang`, `published_at`, `time`, `director`, `actor`, `imdb`, `imdb_number`, `critics`, `rank`, `pg`, `play_status`, `broadcast_day`, `network`, `subtitle`, `dubbed`, `status`, `status_comment`, `soon`, `created_at`, `updated_at`, `description`, `moreDescription`, `keyword`, `awards`, `imdbId`, `short_link`) VALUES
(1, 8, 'The Endgame', 'پایان بازی', 'the-endgame', 'serial', '/uploader/gallery/MV5BYmFkYmYxYTQtMzljZS00Njc2LTg5Y2MtNDUxNzdjM2Y2YTY3XkEyXkFqcGdeQXVyODUxOTU0OTg@._V1_SX300.jpg', '480, 720, 1080', 'جنایی، درام', 'ایالات متحده', 'انگلیسی', '2022–', '40', 'N/A', 'مورنا باکارین، رایان میشل بات، کاستا رونین', '6.6 از 10 میانگین رای 3,302 نفر', '6.6', 'N/A از  100', NULL, 'TV-14', 'yes', 'سه شنبه', 'NBC', 'yes', 'no', 'yes', 'yes', 'no', '2022-04-20 16:29:11', '2022-04-20 16:29:11', 'یک دزدی سکسی و پیچیده در مورد اینکه برخی از مردم تا کجا برای عشق، عدالت و با ارزش ترین کالای دنیا پیش خواهند رفت: حقیقت.', NULL, 'The Endgame | پایان بازی', 'N/A', 'tt14507354', '48012200'),
(2, 8, 'Moon Knight', 'شوالیه ماه', 'moon-knight', 'serial', '/uploader/gallery/MV5BNGM2ZjJmZDQtNTI5MS00ZTI0LTkyNjktM2RlYWY0YTY5MTYzXkEyXkFqcGdeQXVyMTEyMjM2NDc2._V1_SX300.jpg', '480, 720, 1080', 'اکشن، ماجراجویی، درام', 'ایالات متحده', 'انگلیسی', '2022–', '45', 'N/A', 'اسکار آیزاک، اتان هاوک، می کالاماوی', '7.5 از 10 میانگین رای 34,305 نفر', '7.5', 'N/A از  100', NULL, 'TV-14', 'yes', 'چهارشنبه', 'Disney+', 'yes', 'yes', 'yes', 'yes', 'no', '2022-04-20 16:31:34', '2022-04-20 16:31:34', 'یک تفنگدار سابق آمریکایی که با اختلال هویت تجزیه ای دست و پنجه نرم می کند، قدرت خدای ماه مصری را دریافت می کند. اما به زودی متوجه می‌شود که این قدرت‌های تازه کشف شده می‌توانند هم یک برکت و هم یک نفرین برای زندگی پریشان او باشند.', NULL, 'Moon Knight | مون نایت | شوالیه ماه', 'N/A', 'tt10234724', '48012201'),
(3, 4, 'Spider-Man: No Way Home', 'مرد عنکبوتی: راهی به خانه نیست', 'spider-man-no-way-home', 'movie', '/uploader/gallery/MV5BZWMyYzFjYTYtNTRjYi00OGExLWE2YzgtOGRmYjAxZTU3NzBiXkEyXkFqcGdeQXVyMzQ0MzA0NTM@._V1_SX300.jpg', '720, Hd, 1080, 480', 'اکشن، ماجراجویی، فانتزی', 'ایالات متحده', 'انگلیسی', '17 دسامبر 2021', '148 دقیقه', 'جان واتس', 'تام هالند، زندایا، بندیکت کامبربچ', '8.5 از 10 میانگین رای 555922 نفر', '8.5', '71 از  100', NULL, 'PG-13', NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'no', '2022-04-20 16:34:51', '2022-04-20 16:34:51', 'با فاش شدن هویت مرد عنکبوتی، پیتر از دکتر استرنج کمک می خواهد. وقتی یک طلسم اشتباه می‌شود، دشمنان خطرناکی از دنیاهای دیگر ظاهر می‌شوند و پیتر را مجبور می‌کنند تا معنای واقعی مرد عنکبوتی بودن را کشف کند.', 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.', 'Spider-Man: No Way Home | Spider-Man: No Way Home 2021 | spider man | مرد عنکبوتی: راهی به خانه نیست | مرد عنکبوتی | راهی به خانه نیست', 'نامزد 1 اسکار. 15 برنده و 45 نامزدی در مجموع', 'tt10872600', '48012202'),
(4, 4, 'The Batman', 'بتمن', 'the-batman', 'movie', '/uploader/gallery/MV5BMDdmMTBiNTYtMDIzNi00NGVlLWIzMDYtZTk3MTQ3NGQxZGEwXkEyXkFqcGdeQXVyMzMwOTU5MDk@._V1_SX300.jpg', '720و1080', 'اکشن، جنایی، درام', 'ایالات متحده', 'انگلیسی', '04 مارس 2022', '176 دقیقه', 'مت ریوز', 'رابرت پتینسون، زوئه کراویتز، جفری رایت', '8.3 از 10 میانگین رای 323,448 نفر', '8.3', '72 از  100', NULL, 'PG-13', NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'no', '2022-04-20 16:36:32', '2022-04-20 16:36:32', 'زمانی که ریدلر، یک قاتل زنجیره‌ای سادیست، شروع به قتل شخصیت‌های سیاسی کلیدی در گاتهام می‌کند، بتمن مجبور می‌شود فساد پنهان شهر را بررسی کند و دخالت خانواده‌اش را زیر سوال ببرد.', 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.', 'The Batman | بتمن |', '2 برنده و 3 نامزدی', 'tt1877830', '48012203'),
(5, 4, 'Infinite Storm', 'طوفان بی نهایت', 'infinite-storm', 'movie', '/uploader/gallery/MV5BNzY2OWY4YWItYzI5MS00MzUzLWE2ZTYtODhkMzdmOTk2M2VlXkEyXkFqcGdeQXVyMDM2NDM2MQ@@._V1_SX300.jpg', '1080و720', 'درام، هیجان انگیز', 'بریتانیا، لهستان، استرالیا', 'انگلیسی', '27 مه 2022', '97 دقیقه', 'Malgorzata Szumowska', 'نائومی واتس، دنیس اوهر، بیلی هاول', '5.9 از 10 میانگین رای 398 نفر', '5.9', '56 از  100', NULL, 'R', NULL, NULL, NULL, 'no', 'no', 'yes', 'yes', 'yes', '2022-04-20 16:38:18', '2022-04-20 16:38:18', 'هنگامی که یک کوهنورد در یک کولاک گرفتار می شود، با یک غریبه سرگردان روبرو می شود و باید هر دو را قبل از شب از کوه پایین بیاورد.', NULL, 'Infinite Storm | طوفان بی نهایت', 'N/A', 'tt14060232', '48012204'),
(6, 4, 'Mrs Harris Goes to Paris', 'خانم هریس به پاریس می رود', 'mrs-harris-goes-to-paris', 'movie', '/uploader/gallery/MV5BMjM1ZjIyYzYtODRhYy00ZTc0LThhZjMtNzhmZGM3OWJkNzFkXkEyXkFqcGdeQXVyMjQ4NjAyOTY@._V1_SX300.jpg', '720و 480', 'نمایش', 'مجارستان، کانادا، فرانسه، ایالات متحده، بلژیک', 'انگلیسی', '15 ژوئیه 2022', '92 دقیقه', 'آنتونی فابیان', 'رز ویلیامز، جیسون آیزاکس، لوکاس براوو', 'N/A از 10 میانگین رای N/A نفر', 'N/A', 'N/A از  100', NULL, 'PG', NULL, NULL, NULL, 'no', 'no', 'yes', 'yes', 'yes', '2022-04-20 16:41:46', '2022-04-20 16:41:46', 'یک خانم نظافتچی بیوه در لندن در دهه 1950 دیوانه وار عاشق یک لباس مد لباس دیور می شود و تصمیم می گیرد که باید یکی از لباس های خودش را داشته باشد.', NULL, 'Mrs Harris Goes to Paris | خانم هریس به پاریس می رود', 'N/A', 'tt5151570', '48012205'),
(7, 10, 'Sonic the Hedgehog 2', 'سونیک خارپشت 2', 'sonic-the-hedgehog-2', 'movie', '/uploader/gallery/MV5BMGI1NjA1MjUtNGQxNC00NDYyLThjODgtZjFkZjQ4OGM0NDc5XkEyXkFqcGdeQXVyMTM0NTUzNDIy._V1_SX300.jpg', '720, Hd, 1080', 'اکشن، ماجراجویی، کمدی', 'ایالات متحده، ژاپن', 'انگلیسی، روسی', '08 آوریل 2022', '122 دقیقه', 'جف فاولر', 'جیمز مارسدن، جیم کری، بن شوارتز', '6.7 از 10 میانگین رای 31705 نفر', '6.7', '47 از  100', NULL, 'PG', NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'no', '2022-05-20 19:25:00', '2022-05-20 19:25:00', 'وقتی دکتر روباتنیک شیدایی با یک متحد جدید به زمین بازمی گردد، ناکلز اکیدنا، سونیک و دوست جدیدش تیلز همه چیزهایی هستند که در راه آنها قرار می گیرند.', NULL, 'سونیک خارپشت 2 | سونیک خارپشت |  Sonic the Hedgehog 2 | Sonic the Hedgehog |  انیمیشن | خارجی', 'N/A', 'tt12412888', '47299800'),
(8, 8, 'Under the Banner of Heaven', 'زیر پرچم بهشت', 'under-the-banner-of-heaven', 'serial', '/uploader/gallery/MV5BMjQxMThmMDUtYWU5ZS00ZTkyLThiZTktMGJlZWFjMTA4YTZiXkEyXkFqcGdeQXVyOTkxMzM5MTk@._V1_SX300.jpg', '720, 1080, 480', 'جنایی، درام، معمایی', 'ایالات متحده', 'انگلیسی', '2022–', 'N/A', 'N/A', 'اندرو گارفیلد، روهان مید، تیلور سنت پیر', 'N/A از 10 میانگین رای N/A نفر', 'N/A', 'N/A از  100', NULL, 'TV-MA', 'yes', 'پنج شنبه', 'HULU', 'yes', 'no', 'yes', 'no', 'no', '2022-05-20 19:26:49', '2022-05-20 19:26:49', 'ایمان یک کارآگاه مؤمن هنگام تحقیق درباره قتلی وحشیانه که ظاهراً به مارپیچ خانواده محترم یوتا به سمت بنیادگرایی LDS و بی اعتمادی آنها به دولت مرتبط است، مورد آزمایش قرار می گیرد.', NULL, 'Under the Banner of Heaven |  زیر پرچم بهشت', 'N/A', 'tt1998372', '19846300'),
(9, 4, 'Memory', 'حافظه', 'memory', 'movie', '/uploader/gallery/MV5BMTkyNDI2NTI2NV5BMl5BanBnXkFtZTcwNTQwMDMzMQ@@._V1_SX300.jpg', '40, 720', 'درام، ترسناک، معمایی', 'کانادا، ایالات متحده آمریکا', 'انگلیسی', '23 مارس 2007', '98 دقیقه', 'بنت داولین', 'دنیس هاپر، بیلی زین، تریشاهلفر', '5.2 از 10 میانگین رای 1903 نفر', '5.2', '26 از  100', NULL, 'R', NULL, NULL, NULL, 'yes', 'no', 'yes', 'yes', 'no', '2022-05-20 19:28:34', '2022-05-20 19:28:34', 'یک محقق پزشکی با یک پزشک بازنشسته همکاری می کند تا در خاطرات ژنتیکی ذخیره شده یک قاتل زنجیره ای ریشه یابی کنند.', NULL, 'Memory | فیلم خارجی', 'N/A', 'tt0418879', '52954800'),
(10, 4, 'Dual', 'دوگانه', 'dual', 'movie', '/uploader/gallery/MV5BZWE4ZWMwOTYtZDkyYi00NWEwLTgyNWYtNWNkNjhlMjBmZTJmXkEyXkFqcGdeQXVyMzQwMTY2Nzk@._V1_SX300.jpg', '1080, 720', 'علمی تخیلی، هیجان انگیز', 'فنلاند، ایالات متحده آمریکا', 'انگلیسی', '18 مارس 2022', '94 دقیقه', 'رایلی استرنز', 'کارن گیلان، آرون پل، بیلا کوال', '6.9 از 10 میانگین رای 525 نفر', '6.9', '62 از  100', NULL, 'R', NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'no', '2022-05-20 19:29:57', '2022-05-20 19:29:57', 'یک زن پس از دریافت تشخیص نهایی، روش شبیه‌سازی را انتخاب می‌کند، اما پس از بهبودی، تلاش‌هایش برای از کار انداختن کلونش با شکست مواجه می‌شود، که منجر به دوئل اجباری دادگاه و مرگ می‌شود.', NULL, 'Dual | دوگانه', '1 نامزدی', 'tt9005184', '61606900'),
(11, 4, 'CODA', 'CODA', 'coda', 'movie', '/uploader/gallery/MV5BYzkyNzNiMDItMGU1Yy00NmEyLWE4N2ItMjkzMDZmMmVhNDU4XkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_SX300.jpg', '720, 1080, HD', 'کمدی، درام، موسیقی', 'فرانسه، کانادا، ایالات متحده آمریکا', 'علامت آمریکایی، انگلیسی', '13 اوت 2021', '111 دقیقه', 'سیان هدر', 'امیلیا جونز، مارلی متلین، تروی کوتسور', '8.1 از 10 میانگین رای 104,869 نفر', '8.1', '74 از  100', NULL, 'PG-13', NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'no', '2022-05-20 19:32:48', '2022-05-20 19:32:48', 'روبی به عنوان یک CODA (کودک بزرگسالان ناشنوا) تنها فرد شنوا در خانواده ناشنوای خود است. هنگامی که تجارت ماهیگیری خانواده در معرض تهدید قرار می گیرد، روبی خود را بین تعقیب اشتیاق خود در کالج موسیقی برکلی و ترس از...', NULL, 'CODA', 'برنده 3 جایزه اسکار 61 برنده و 133 نامزدی در مجموع', 'tt10366460', '77921200');

-- --------------------------------------------------------

--
-- Table structure for table `movie_links`
--

CREATE TABLE `movie_links` (
  `id` bigint UNSIGNED NOT NULL,
  `movie_id` bigint UNSIGNED NOT NULL,
  `caption_movie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vip` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movie_links`
--

INSERT INTO `movie_links` (`id`, `movie_id`, `caption_movie`, `title`, `vip`, `created_at`, `updated_at`) VALUES
(7, 11, NULL, 'نسخه زیرنویس فارسی چسبیده', 'no', '2022-05-20 19:41:00', '2022-05-20 19:41:00'),
(8, 10, NULL, 'نسخه زیرنویس فارسی چسبیده', 'yes', '2022-05-20 19:44:08', '2022-05-20 19:44:08'),
(9, 9, NULL, 'نسخه زبان انگلیسی', 'no', '2022-05-20 19:50:03', '2022-05-20 19:50:03'),
(10, 2, NULL, 'فصل اول دوبله پارسی | کیفیت ۷۲۰', 'yes', '2022-05-20 19:53:06', '2022-05-20 19:53:06'),
(11, 2, 'فصل اول قسمت سوم اضافه شد', 'فصل اول زیرنویس پارسی چسبیده | کیفیت ۷۲۰', 'no', '2022-05-20 19:54:23', '2022-05-20 20:07:55'),
(12, 7, NULL, 'نسخه زیرنویس فارسی چسبیده', 'no', '2022-05-20 19:57:07', '2022-05-20 19:57:07'),
(13, 3, NULL, 'نسخه زیرنویس فارسی چسبیده', 'no', '2022-05-20 19:59:25', '2022-05-20 19:59:25'),
(14, 3, NULL, 'نسخه دوبله فارسی', 'yes', '2022-05-20 20:00:02', '2022-05-20 20:00:02'),
(15, 4, NULL, 'نسخه زیرنویس فارسی چسبیده', 'no', '2022-05-20 20:01:39', '2022-05-20 20:01:39'),
(16, 1, 'قسمت سوم فصل اول اضافه شد', 'فصل اول زبان انگلیسی | کیفیت ۷۲۰', 'no', '2022-05-20 20:03:51', '2022-05-20 20:03:51'),
(17, 8, 'فصل اول قسمت اول اضافه شد', 'فصل اول زیرنویس چسبیده | کیفیت ۷۲۰', 'no', '2022-05-20 20:06:52', '2022-05-20 20:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `movie_tags`
--

CREATE TABLE `movie_tags` (
  `id` bigint UNSIGNED NOT NULL,
  `movie_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movie_tags`
--

INSERT INTO `movie_tags` (`id`, `movie_id`, `title`, `link`, `created_at`, `updated_at`) VALUES
(1, 1, 'پایان بازی', 'http://google.com', '2022-04-20 17:05:42', '2022-04-20 17:05:42'),
(2, 1, 'the endgame', 'http://google.com', '2022-04-20 17:05:42', '2022-04-20 17:05:42');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'درباره ما', '<p class=\"ql-direction-rtl\">در باره ما</p><p class=\"ql-direction-rtl\">در باره ما</p><p class=\"ql-direction-rtl\">در باره ما</p>', 'yes', '2022-04-20 16:14:39', '2022-04-20 16:16:29'),
(2, 'تماس با ما', '<p class=\"ql-direction-rtl\">راه های ارتباط با ما</p><p class=\"ql-direction-rtl\"><br></p><p class=\"ql-direction-rtl\"><br></p>', 'yes', '2022-04-20 16:15:05', '2022-04-28 12:12:40'),
(3, 'اموزش دانلود از سایت', '<p class=\"ql-direction-rtl\">ااااااااااااااااااموووووووووووووووووووووووزشششششششششششششش </p>', 'yes', '2022-04-20 16:20:43', '2022-04-20 16:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_any_user', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(2, 'view_user', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(3, 'create_user', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(4, 'update_user', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(5, 'delete_user', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(6, 'view_any_category', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(7, 'view_category', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(8, 'create_category', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(9, 'update_category', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(10, 'delete_category', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(11, 'view_any_movie', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(12, 'view_movie', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(13, 'create_movie', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(14, 'update_movie', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(15, 'delete_movie', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(16, 'view_any_movielink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(17, 'view_movielink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(18, 'create_movielink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(19, 'update_movielink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(20, 'delete_movielink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(21, 'view_any_movietag', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(22, 'view_movietag', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(23, 'create_movietag', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(24, 'update_movietag', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(25, 'delete_movietag', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(26, 'view_any_plan', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(27, 'view_plan', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(28, 'create_plan', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(29, 'update_plan', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(30, 'delete_plan', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(31, 'view_any_discount', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(32, 'view_discount', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(33, 'create_discount', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(34, 'update_discount', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(35, 'delete_discount', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(36, 'view_any_cart', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(37, 'view_cart', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(38, 'create_cart', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(39, 'update_cart', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(40, 'delete_cart', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(41, 'view_any_bankportal', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(42, 'view_bankportal', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(43, 'create_bankportal', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(44, 'update_bankportal', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(45, 'delete_bankportal', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(46, 'view_any_wallettransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(47, 'view_wallettransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(48, 'create_wallettransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(49, 'update_wallettransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(50, 'delete_wallettransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(51, 'view_any_checkout', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(52, 'view_checkout', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(53, 'create_checkout', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(54, 'update_checkout', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(55, 'delete_checkout', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(56, 'view_any_viptransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(57, 'view_viptransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(58, 'create_viptransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(59, 'update_viptransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(60, 'delete_viptransaction', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(61, 'view_any_comment', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(62, 'view_comment', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(63, 'create_comment', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(64, 'update_comment', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(65, 'delete_comment', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(66, 'view_any_page', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(67, 'view_page', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(68, 'create_page', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(69, 'update_page', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(70, 'delete_page', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(71, 'view_any_configsite', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(72, 'view_configsite', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(73, 'create_configsite', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(74, 'update_configsite', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(75, 'delete_configsite', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(76, 'view_any_slider', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(77, 'view_slider', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(78, 'create_slider', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(79, 'update_slider', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(80, 'delete_slider', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(81, 'view_any_reportlink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(82, 'view_reportlink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(83, 'create_reportlink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(84, 'update_reportlink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17'),
(85, 'delete_reportlink', 'web', '2022-05-26 11:44:17', '2022-05-26 11:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(15, 'App\\Models\\User', 3, 'X11; Linux x86_64', 'd51e13a27945bad6cddc715d87c7e68213aea87524550d1751ee900bce12e66a', '[\"*\"]', '2022-04-28 12:52:34', '2022-04-28 12:52:27', '2022-04-28 12:52:34'),
(21, 'App\\Models\\User', 4, 'X11; Linux x86_64', 'e0de8220491fd94f53a985002d63e4d7b8cf8302e0cd90316a05f02415ea7a8e', '[\"*\"]', '2022-05-13 17:11:16', '2022-05-13 17:10:21', '2022-05-13 17:11:16'),
(23, 'App\\Models\\User', 1, 'X11; Linux x86_64', '0fd8c70fd5d2b371066e95a4190c5642667dbc1c623a809f9329032e3d942a06', '[\"*\"]', '2022-05-22 14:28:10', '2022-05-20 20:20:02', '2022-05-22 14:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `days` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `title`, `price`, `days`, `image`, `description`, `created_at`, `updated_at`) VALUES
(1, 'پلن طلایی', 1300000, 180, '/uploader/gallery/good.jpeg', 'یک پلن یک ساله است', '2022-04-20 16:01:10', '2022-04-20 16:10:09'),
(2, 'پلن نقره ای', 800000, 90, '/uploader/gallery/silver.jpg', NULL, '2022-04-20 16:06:09', '2022-04-20 16:10:30'),
(3, 'پلن برنزی', 60000, 30, '/uploader/gallery/bronz.jpg', NULL, '2022-04-20 16:09:15', '2022-04-20 16:09:15');

-- --------------------------------------------------------

--
-- Table structure for table `report_links`
--

CREATE TABLE `report_links` (
  `id` bigint UNSIGNED NOT NULL,
  `movie_id` bigint UNSIGNED NOT NULL,
  `movie_link_id` bigint UNSIGNED NOT NULL,
  `link_id` bigint UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `screen_shots`
--

CREATE TABLE `screen_shots` (
  `id` bigint UNSIGNED NOT NULL,
  `movie_link_id` bigint UNSIGNED NOT NULL,
  `screen_shot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `screen_shots`
--

INSERT INTO `screen_shots` (`id`, `movie_link_id`, `screen_shot`, `created_at`, `updated_at`) VALUES
(2, 7, '/uploader/gallery/MV5BYzkyNzNiMDItMGU1Yy00NmEyLWE4N2ItMjkzMDZmMmVhNDU4XkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_SX300.jpg', '2022-05-20 19:41:00', '2022-05-20 19:41:00'),
(3, 8, '/uploader/gallery/MV5BZWE4ZWMwOTYtZDkyYi00NWEwLTgyNWYtNWNkNjhlMjBmZTJmXkEyXkFqcGdeQXVyMzQwMTY2Nzk@._V1_SX300.jpg', '2022-05-20 19:44:08', '2022-05-20 19:44:08'),
(4, 10, '/uploader/gallery/MV5BNGM2ZjJmZDQtNTI5MS00ZTI0LTkyNjktM2RlYWY0YTY5MTYzXkEyXkFqcGdeQXVyMTEyMjM2NDc2._V1_SX300.jpg', '2022-05-20 19:53:06', '2022-05-20 19:53:06'),
(5, 13, '/uploader/gallery/MV5BZWMyYzFjYTYtNTRjYi00OGExLWE2YzgtOGRmYjAxZTU3NzBiXkEyXkFqcGdeQXVyMzQ0MzA0NTM@._V1_SX300.jpg', '2022-05-20 19:59:25', '2022-05-20 19:59:25'),
(6, 15, '/uploader/gallery/MV5BMDdmMTBiNTYtMDIzNi00NGVlLWIzMDYtZTk3MTQ3NGQxZGEwXkEyXkFqcGdeQXVyMzMwOTU5MDk@._V1_SX300.jpg', '2022-05-20 20:01:39', '2022-05-20 20:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint UNSIGNED NOT NULL,
  `movie_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `movie_id`, `created_at`, `updated_at`) VALUES
(2, 2, '2022-05-11 10:03:11', '2022-05-11 10:03:11'),
(3, 3, '2022-05-11 10:03:18', '2022-05-11 10:03:18'),
(5, 7, '2022-05-20 20:20:25', '2022-05-20 20:20:25'),
(6, 4, '2022-05-20 20:20:33', '2022-05-20 20:20:33'),
(7, 11, '2022-05-20 20:20:41', '2022-05-20 20:20:41');

-- --------------------------------------------------------

--
-- Table structure for table `trailers`
--

CREATE TABLE `trailers` (
  `id` bigint UNSIGNED NOT NULL,
  `movie_link_id` bigint UNSIGNED NOT NULL,
  `trailer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trailers`
--

INSERT INTO `trailers` (`id`, `movie_link_id`, `trailer`, `caption`, `created_at`, `updated_at`) VALUES
(2, 7, '/uploader/gallery/coda.mp4', 'کیفیت 480', '2022-05-20 19:41:00', '2022-05-20 19:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `access`, `email`, `mobile`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'مدیر سایت', 'admin', 'admin@gmail.com', '22222222222', NULL, '$2y$10$JKW13QCU/btvQsIWRtv1i.GiCw8bEqVdv/YnMbtPkfKoYDnPnyNzy', 'yes', NULL, '2022-04-20 15:47:13', '2022-05-13 17:02:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vip_transactions`
--

CREATE TABLE `vip_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cart` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `discount` int NOT NULL,
  `expire` timestamp NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('wallet','bank') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cart` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `watch_lists`
--

CREATE TABLE `watch_lists` (
  `id` bigint UNSIGNED NOT NULL,
  `movie_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_portals`
--
ALTER TABLE `bank_portals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_title_unique` (`title`);

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment_answers`
--
ALTER TABLE `comment_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_answers_comment_id_foreign` (`comment_id`),
  ADD KEY `comment_answers_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `config_sites`
--
ALTER TABLE `config_sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `links_movie_link_id_foreign` (`movie_link_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`),
  ADD UNIQUE KEY `fa_title` (`fa_title`),
  ADD KEY `movies_category_id_foreign` (`category_id`);

--
-- Indexes for table `movie_links`
--
ALTER TABLE `movie_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_links_movie_id_foreign` (`movie_id`);

--
-- Indexes for table `movie_tags`
--
ALTER TABLE `movie_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_tags_movie_id_foreign` (`movie_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_links`
--
ALTER TABLE `report_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_links_movie_id_foreign` (`movie_id`),
  ADD KEY `report_links_movie_link_id_foreign` (`movie_link_id`),
  ADD KEY `report_links_link_id_foreign` (`link_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `screen_shots`
--
ALTER TABLE `screen_shots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `screen_shots_movie_link_id_foreign` (`movie_link_id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sliders_movie_id_foreign` (`movie_id`);

--
-- Indexes for table `trailers`
--
ALTER TABLE `trailers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trailers_movie_link_id_foreign` (`movie_link_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`);

--
-- Indexes for table `vip_transactions`
--
ALTER TABLE `vip_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `watch_lists`
--
ALTER TABLE `watch_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `watch_lists_movie_id_foreign` (`movie_id`),
  ADD KEY `watch_lists_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_portals`
--
ALTER TABLE `bank_portals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comment_answers`
--
ALTER TABLE `comment_answers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_sites`
--
ALTER TABLE `config_sites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `movie_links`
--
ALTER TABLE `movie_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `movie_tags`
--
ALTER TABLE `movie_tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `report_links`
--
ALTER TABLE `report_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `screen_shots`
--
ALTER TABLE `screen_shots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trailers`
--
ALTER TABLE `trailers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vip_transactions`
--
ALTER TABLE `vip_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `watch_lists`
--
ALTER TABLE `watch_lists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_answers`
--
ALTER TABLE `comment_answers`
  ADD CONSTRAINT `comment_answers_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `links_movie_link_id_foreign` FOREIGN KEY (`movie_link_id`) REFERENCES `movie_links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `movie_links`
--
ALTER TABLE `movie_links`
  ADD CONSTRAINT `movie_links_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `movie_tags`
--
ALTER TABLE `movie_tags`
  ADD CONSTRAINT `movie_tags_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `report_links`
--
ALTER TABLE `report_links`
  ADD CONSTRAINT `report_links_link_id_foreign` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `report_links_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `report_links_movie_link_id_foreign` FOREIGN KEY (`movie_link_id`) REFERENCES `movie_links` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `screen_shots`
--
ALTER TABLE `screen_shots`
  ADD CONSTRAINT `screen_shots_movie_link_id_foreign` FOREIGN KEY (`movie_link_id`) REFERENCES `movie_links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sliders`
--
ALTER TABLE `sliders`
  ADD CONSTRAINT `sliders_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trailers`
--
ALTER TABLE `trailers`
  ADD CONSTRAINT `trailers_movie_link_id_foreign` FOREIGN KEY (`movie_link_id`) REFERENCES `movie_links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `watch_lists`
--
ALTER TABLE `watch_lists`
  ADD CONSTRAINT `watch_lists_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `watch_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

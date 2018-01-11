-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2018 at 04:53 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insta_affiliate`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_profile_directory`
--

CREATE TABLE `active_profile_directory` (
  `insta_username` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insta_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `follower_count` int(11) DEFAULT NULL,
  `posts_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `id` int(11) NOT NULL,
  `admin_email` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blacklisted_username`
--

CREATE TABLE `blacklisted_username` (
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `braintree_transactions`
--

CREATE TABLE `braintree_transactions` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(13,4) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `bt_cc_token` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plan_id` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_id` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `braintree_id` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comms_given` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clickfunnel_webhook_log`
--

CREATE TABLE `clickfunnel_webhook_log` (
  `id` int(11) NOT NULL,
  `log` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competition_updates`
--

CREATE TABLE `competition_updates` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` text,
  `type` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `create_insta_profile_log`
--

CREATE TABLE `create_insta_profile_log` (
  `log_id` int(11) NOT NULL,
  `insta_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insta_pw` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_msg` text COLLATE utf8mb4_unicode_ci,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `default_image_category`
--

CREATE TABLE `default_image_category` (
  `id` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'General'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `default_image_gallery`
--

CREATE TABLE `default_image_gallery` (
  `image_id` int(11) NOT NULL,
  `image_path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_error_log`
--

CREATE TABLE `dm_error_log` (
  `error_log_id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `error_log` text COLLATE utf8mb4_unicode_ci,
  `sender_username` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_username` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_logged` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_job`
--

CREATE TABLE `dm_job` (
  `job_id` int(11) NOT NULL,
  `insta_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_insta_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_fullname` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_to_send` datetime DEFAULT NULL,
  `fulfilled` int(11) DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci,
  `date_job_inserted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `follow_up_order` int(11) DEFAULT '0',
  `error_msg` text COLLATE utf8mb4_unicode_ci,
  `success_msg` text COLLATE utf8mb4_unicode_ci,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_job_archive`
--

CREATE TABLE `dm_job_archive` (
  `job_id` int(11) NOT NULL,
  `insta_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_insta_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_fullname` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_to_send` datetime DEFAULT NULL,
  `fulfilled` int(11) DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci,
  `date_job_inserted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `follow_up_order` int(11) DEFAULT '0',
  `error_msg` text COLLATE utf8mb4_unicode_ci,
  `success_msg` text COLLATE utf8mb4_unicode_ci,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_job_log`
--

CREATE TABLE `dm_job_log` (
  `log_id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `sender` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `log_resp` text COLLATE utf8mb4_unicode_ci,
  `login_log_resp` text COLLATE utf8mb4_unicode_ci,
  `date_logged` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_log`
--

CREATE TABLE `dm_log` (
  `log_id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `sender` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `log_resp` text COLLATE utf8mb4_unicode_ci,
  `login_log_resp` text COLLATE utf8mb4_unicode_ci,
  `error_handled` int(11) DEFAULT '0',
  `date_logged` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_thread`
--

CREATE TABLE `dm_thread` (
  `id` int(10) UNSIGNED NOT NULL,
  `thread_id` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `named` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_spam` blob,
  `muted` blob,
  `thread_type` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thread_title` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_pin` blob,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_thread_items`
--

CREATE TABLE `dm_thread_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `dm_thread_id` int(10) UNSIGNED DEFAULT NULL,
  `item_id` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_text` text COLLATE utf8mb4_unicode_ci,
  `user_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_thread_users`
--

CREATE TABLE `dm_thread_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `dm_thread_id` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engagement_group_job`
--

CREATE TABLE `engagement_group_job` (
  `media_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `engaged` tinyint(1) DEFAULT '0',
  `date_logged` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engagement_ineligible`
--

CREATE TABLE `engagement_ineligible` (
  `insta_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engagement_job_queue`
--

CREATE TABLE `engagement_job_queue` (
  `job_id` int(11) NOT NULL,
  `media_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `insta_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` int(11) NOT NULL DEFAULT '0',
  `fulfilled` int(11) NOT NULL DEFAULT '0',
  `date_to_work_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_sessions`
--

CREATE TABLE `instagram_sessions` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `settings` mediumblob,
  `cookies` mediumblob,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailing_list`
--

CREATE TABLE `mailing_list` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2017_11_09_033155_create_active_profile_directory_table', 1),
(2, '2017_11_09_033155_create_admin_log_table', 1),
(3, '2017_11_09_033155_create_clickfunnel_webhook_log_table', 2),
(4, '2017_11_09_033155_create_create_insta_profile_log_table', 2),
(5, '2017_11_09_033155_create_default_image_category_table', 2),
(6, '2017_11_09_033155_create_dm_error_log_table', 3),
(7, '2017_11_09_033155_create_dm_job_log_table', 4),
(8, '2017_11_09_033155_create_dm_log_table', 5),
(9, '2017_11_09_033155_create_dm_thread_items_table', 5),
(10, '2017_11_09_033155_create_dm_thread_table', 5),
(11, '2017_11_09_033155_create_dm_thread_users_table', 5),
(12, '2017_11_09_033155_create_failed_jobs_table', 6),
(13, '2017_11_09_033155_create_morfix_plans_table', 6),
(14, '2017_11_09_033155_create_morfix_qna_table', 6),
(15, '2017_11_09_033155_create_morfix_servers_table', 6),
(16, '2017_11_09_033155_create_morfix_settings_table', 6),
(17, '2017_11_09_033155_create_morfix_topics_table', 6),
(18, '2017_11_09_033155_create_morfix_watermark_table', 6),
(19, '2017_11_09_033155_create_niche_targets_hashtags_table', 6),
(20, '2017_11_09_033155_create_niches_table', 7),
(21, '2017_11_09_033155_create_paypal_charges_table', 8),
(22, '2017_11_09_033155_create_paypal_webhook_log_table', 8),
(23, '2017_11_09_033155_create_proxy_table', 8),
(24, '2017_11_09_033155_create_referral_ip_table', 8),
(25, '2017_11_09_033155_create_slave_node_connection_directory_table', 8),
(26, '2017_11_09_033155_create_stripe_payment_log_table', 8),
(27, '2017_11_09_033155_create_stripe_webhook_log_table', 8),
(28, '2017_11_09_033155_create_user_affiliate_table', 8),
(29, '2017_11_09_033155_create_user_feedback_table', 8),
(30, '2017_11_09_033155_create_user_interaction_failed_table', 9),
(31, '2017_11_09_033155_create_user_paypal_agreements_table', 9),
(32, '2017_11_09_033155_create_user_stripe_active_subscription_table', 9),
(33, '2017_11_09_033155_create_user_stripe_charges_table', 9),
(34, '2017_11_09_033155_create_user_stripe_details_table', 9),
(35, '2017_11_09_033155_create_user_stripe_invoice_table', 9),
(36, '2017_11_09_033215_add_foreign_keys_to_niche_targets_table', 10),
(37, '2017_11_09_033215_add_foreign_keys_to_user_stripe_active_subscription_table', 10),
(38, '2017_11_09_033215_add_foreign_keys_to_user_stripe_invoice_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `morfix_plans`
--

CREATE TABLE `morfix_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_id` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `morfix_qna`
--

CREATE TABLE `morfix_qna` (
  `id` int(11) NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic_id` int(11) NOT NULL DEFAULT '1',
  `written_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `author` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT 'Natalie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `morfix_servers`
--

CREATE TABLE `morfix_servers` (
  `ip` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `morfix_settings`
--

CREATE TABLE `morfix_settings` (
  `setting` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `morfix_topics`
--

CREATE TABLE `morfix_topics` (
  `id` int(11) NOT NULL,
  `topic` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `topic_url` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `morfix_watermark`
--

CREATE TABLE `morfix_watermark` (
  `watermark_id` int(11) NOT NULL,
  `dm_content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `niches`
--

CREATE TABLE `niches` (
  `niche_id` int(11) NOT NULL,
  `niche` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `niche_targets`
--

CREATE TABLE `niche_targets` (
  `id` int(11) NOT NULL,
  `niche_id` int(11) NOT NULL,
  `target_username` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `niche_targets_hashtags`
--

CREATE TABLE `niche_targets_hashtags` (
  `niche_hashtag_id` int(11) NOT NULL,
  `niche_id` int(11) DEFAULT NULL,
  `hashtag` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_log`
--

CREATE TABLE `payment_log` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exception_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paypal_charges`
--

CREATE TABLE `paypal_charges` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agreement_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(13,4) DEFAULT NULL,
  `subscription_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` text COLLATE utf8mb4_unicode_ci,
  `transaction_type` text COLLATE utf8mb4_unicode_ci,
  `referrer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_email` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_name` text COLLATE utf8mb4_unicode_ci,
  `time_stamp` datetime DEFAULT NULL,
  `commission_given` tinyint(1) DEFAULT '0',
  `commission_calc` tinyint(1) DEFAULT '0',
  `testing_commission_given_july` tinyint(1) DEFAULT '0',
  `testing_commission_given` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paypal_webhook_log`
--

CREATE TABLE `paypal_webhook_log` (
  `id` int(11) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proxy`
--

CREATE TABLE `proxy` (
  `proxy` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assigned` int(11) DEFAULT '0',
  `error` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_ip`
--

CREATE TABLE `referral_ip` (
  `id` int(11) NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referrer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slave_node_connection_directory`
--

CREATE TABLE `slave_node_connection_directory` (
  `id` int(11) NOT NULL,
  `connection` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charset` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT 'utf8mb4',
  `collation` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT 'utf8mb4_unicode_ci'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stripe_payment_log`
--

CREATE TABLE `stripe_payment_log` (
  `log_id` int(11) NOT NULL,
  `email` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exception_type` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` text COLLATE utf8mb4_unicode_ci,
  `date_logged` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stripe_webhook_log`
--

CREATE TABLE `stripe_webhook_log` (
  `stripe_log_id` int(11) NOT NULL,
  `log` text COLLATE utf8mb4_unicode_ci,
  `date_logged` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `error_log` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_acct` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `active` int(11) DEFAULT '1',
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_tier` int(11) DEFAULT '0',
  `premium_pro` int(11) DEFAULT '0',
  `biz_pro` int(11) DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_activation` int(11) DEFAULT '0',
  `trial_end_date` datetime DEFAULT NULL,
  `close_dm_tut` int(11) DEFAULT '0',
  `close_dashboard_tut` int(11) DEFAULT '0',
  `close_interaction_tut` int(11) DEFAULT '0',
  `close_profile_tut` int(11) DEFAULT '0',
  `close_scheduling_tut` int(11) DEFAULT '0',
  `ref_keyword` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_email` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `all_time_commission` decimal(13,4) DEFAULT '0.0000',
  `pending_commission` decimal(13,4) DEFAULT '0.0000',
  `tier` int(11) DEFAULT '1',
  `admin` tinyint(1) DEFAULT '0',
  `vip` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `engagement_quota` tinyint(1) DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pending_commission_payable` decimal(13,4) DEFAULT '0.0000',
  `paypal` tinyint(1) DEFAULT '0',
  `last_pay_out_date` datetime DEFAULT NULL,
  `partition` int(11) DEFAULT '0',
  `testing_pending_commission` decimal(13,4) DEFAULT '0.0000',
  `testing_pending_commission_payable` decimal(13,4) DEFAULT '0.0000',
  `testing_last_pay_out_date` datetime DEFAULT NULL,
  `paid_amount` decimal(13,4) DEFAULT NULL,
  `testing_all_time_commission` decimal(13,4) DEFAULT '0.0000',
  `reminder_igprofile` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_affiliate`
--

CREATE TABLE `user_affiliate` (
  `affiliate_id` int(11) NOT NULL,
  `referrer` int(11) DEFAULT NULL,
  `referred` int(11) DEFAULT NULL,
  `refunded_premium` tinyint(1) DEFAULT '0',
  `refunded_pro` tinyint(1) DEFAULT '0',
  `refunded_business` tinyint(1) DEFAULT '0',
  `refunded_mastermind` tinyint(1) DEFAULT '0',
  `active` int(11) DEFAULT '1',
  `referrer_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_feedback`
--

CREATE TABLE `user_feedback` (
  `feedback_id` int(11) NOT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `date_posted` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_images`
--

CREATE TABLE `user_images` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_follower_analysis`
--

CREATE TABLE `user_insta_follower_analysis` (
  `analysis_id` int(11) NOT NULL,
  `insta_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `follower_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_photo_post_schedule`
--

CREATE TABLE `user_insta_photo_post_schedule` (
  `schedule_id` int(11) NOT NULL,
  `insta_id` int(11) DEFAULT NULL,
  `insta_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_to_post` datetime DEFAULT NULL,
  `image_path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` text COLLATE utf8mb4_unicode_ci,
  `first_comment` text COLLATE utf8mb4_unicode_ci,
  `posted` int(11) DEFAULT '0',
  `log` text COLLATE utf8mb4_unicode_ci,
  `failure_msg` text COLLATE utf8mb4_unicode_ci,
  `actual_date_posted` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_profile`
--

CREATE TABLE `user_insta_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `insta_user_id` varchar(255) DEFAULT NULL,
  `insta_username` varchar(200) NOT NULL,
  `insta_pw` varchar(200) DEFAULT NULL,
  `profile_pic_url` varchar(200) DEFAULT NULL,
  `follower_count` int(11) DEFAULT NULL,
  `profile_full_name` varchar(200) DEFAULT NULL,
  `insta_new_follower_template` mediumtext,
  `follow_up_message` mediumtext,
  `num_posts` int(11) DEFAULT NULL,
  `recent_activity_timestamp` decimal(15,4) DEFAULT '0.0000',
  `auto_dm_new_follower` int(11) DEFAULT '0',
  `auto_dm_delay` bit(1) DEFAULT b'0',
  `last_sent_dm` datetime DEFAULT NULL,
  `temporary_ban` datetime DEFAULT NULL,
  `dm_probation` int(11) DEFAULT '0',
  `niche` int(11) DEFAULT '0',
  `speed` varchar(200) DEFAULT 'Slow',
  `next_follow_time` datetime DEFAULT NULL,
  `unfollow` int(11) DEFAULT '0',
  `login_log` text,
  `last_instagram_login` datetime DEFAULT NULL,
  `follow_cycle` int(11) DEFAULT '255',
  `follow_quota` int(11) DEFAULT '18',
  `unfollow_quota` int(11) DEFAULT '18',
  `like_quota` int(11) DEFAULT '20',
  `comment_quota` int(11) DEFAULT '6',
  `auto_interaction` int(11) DEFAULT '0',
  `gender_filter` int(11) DEFAULT '0',
  `auto_comment` int(11) DEFAULT '0',
  `auto_like` int(11) DEFAULT '0',
  `auto_follow` int(11) DEFAULT '0',
  `auto_follow_ban` int(11) DEFAULT '0',
  `auto_follow_ban_time` datetime DEFAULT NULL,
  `auto_unfollow` int(11) DEFAULT '0',
  `auto_unfollow_ban` int(11) DEFAULT '0',
  `auto_unfollow_ban_time` datetime DEFAULT NULL,
  `follow_max_followers` int(11) DEFAULT '0',
  `next_like_time` datetime DEFAULT NULL,
  `auto_like_ban` int(11) DEFAULT '0',
  `auto_like_ban_time` datetime DEFAULT NULL,
  `auto_comment_ban` int(11) DEFAULT '0',
  `auto_comment_ban_time` datetime DEFAULT NULL,
  `next_comment_time` datetime DEFAULT NULL,
  `unfollow_unfollowed` int(11) DEFAULT '0',
  `follow_min_followers` int(11) DEFAULT '0',
  `follow_unfollow_delay` int(11) DEFAULT '255',
  `follow_recent_engaged` int(11) DEFAULT '0',
  `checkpoint_required` int(11) DEFAULT '0',
  `account_disabled` int(11) DEFAULT '0',
  `invalid_user` int(11) DEFAULT '0',
  `incorrect_pw` int(11) DEFAULT '0',
  `invalid_proxy` int(11) DEFAULT '0',
  `feedback_required` int(11) DEFAULT '0',
  `comment_feedback_required` int(11) DEFAULT '0',
  `error_msg` text,
  `proxy` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `daily_likes` int(11) DEFAULT '0',
  `daily_comments` int(11) DEFAULT '0',
  `daily_follows` int(11) DEFAULT '0',
  `daily_unfollows` int(11) DEFAULT '0',
  `total_likes` int(11) DEFAULT '0',
  `total_comments` int(11) DEFAULT '0',
  `total_follows` int(11) DEFAULT '0',
  `total_unfollows` int(11) DEFAULT '0',
  `auto_interactions_working` int(11) DEFAULT '0',
  `auto_like_working` int(11) DEFAULT '0',
  `auto_follow_working` int(11) DEFAULT '0',
  `auto_comment_working` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_profile_comment`
--

CREATE TABLE `user_insta_profile_comment` (
  `comment_id` int(11) NOT NULL,
  `insta_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ig_profile_id` int(11) DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `general` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_profile_comment_log`
--

CREATE TABLE `user_insta_profile_comment_log` (
  `log_id` int(11) NOT NULL,
  `insta_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_insta_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` text COLLATE utf8mb4_unicode_ci,
  `date_commented` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_profile_follow_log`
--

CREATE TABLE `user_insta_profile_follow_log` (
  `log_id` int(11) NOT NULL,
  `insta_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `follower_username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `follower_id` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` text COLLATE utf8mb4_unicode_ci,
  `date_inserted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `added_delay` int(11) DEFAULT NULL,
  `follow` int(11) DEFAULT '1',
  `follow_success` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unfollowed` int(11) DEFAULT '0',
  `unfollow_log` text COLLATE utf8mb4_unicode_ci,
  `date_unfollowed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_profile_like_log`
--

CREATE TABLE `user_insta_profile_like_log` (
  `log_id` int(11) NOT NULL,
  `insta_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_media_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` text COLLATE utf8mb4_unicode_ci,
  `date_liked` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_profile_like_log_archive`
--

CREATE TABLE `user_insta_profile_like_log_archive` (
  `log_id` int(11) NOT NULL,
  `insta_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_media_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` text COLLATE utf8mb4_unicode_ci,
  `date_liked` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_profile_media`
--

CREATE TABLE `user_insta_profile_media` (
  `insta_username` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_target_hashtag`
--

CREATE TABLE `user_insta_target_hashtag` (
  `id` int(11) NOT NULL,
  `insta_id` int(11) DEFAULT NULL,
  `insta_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hashtag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_insta_target_username`
--

CREATE TABLE `user_insta_target_username` (
  `target_id` int(11) NOT NULL,
  `insta_id` int(11) DEFAULT NULL,
  `insta_username` varchar(255) DEFAULT NULL,
  `target_username` varchar(255) DEFAULT NULL,
  `invalid` bit(1) DEFAULT b'0',
  `insufficient_followers` bit(1) DEFAULT b'0',
  `last_checked` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_interaction_failed`
--

CREATE TABLE `user_interaction_failed` (
  `id` int(11) NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `insta_username` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tier` int(11) NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  `partition` tinyint(1) NOT NULL DEFAULT '0',
  `failure_message` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_paypal_agreements`
--

CREATE TABLE `user_paypal_agreements` (
  `id` int(11) NOT NULL,
  `agreement_id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_stripe_active_subscription`
--

CREATE TABLE `user_stripe_active_subscription` (
  `id` int(11) NOT NULL,
  `stripe_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscription_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `stripe_subscription_id` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_stripe_charges`
--

CREATE TABLE `user_stripe_charges` (
  `stripe_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `charge_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `charge_created` datetime DEFAULT NULL,
  `invoice_id` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failure_msg` text COLLATE utf8mb4_unicode_ci,
  `failure_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paying_card_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paying_card_brand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paying_card_lastfourdigit` int(11) DEFAULT NULL,
  `paid` tinyint(1) DEFAULT NULL,
  `refunded` tinyint(1) DEFAULT '0',
  `eligible` tinyint(1) DEFAULT '0',
  `commission_given` tinyint(1) DEFAULT '0',
  `commission_calc` tinyint(1) DEFAULT '0',
  `testing_commission_given_july` tinyint(1) DEFAULT '0',
  `testing_commission_given` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_stripe_details`
--

CREATE TABLE `user_stripe_details` (
  `stripe_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_stripe_invoice`
--

CREATE TABLE `user_stripe_invoice` (
  `stripe_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `charge_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` datetime DEFAULT NULL,
  `subscription_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid` tinyint(1) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_updates`
--

CREATE TABLE `user_updates` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yourls_log`
--

CREATE TABLE `yourls_log` (
  `click_id` int(11) NOT NULL,
  `click_time` datetime NOT NULL,
  `shorturl` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referrer` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(41) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yourls_options`
--

CREATE TABLE `yourls_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yourls_url`
--

CREATE TABLE `yourls_url` (
  `keyword` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(41) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clicks` int(10) UNSIGNED NOT NULL,
  `pixel` text COLLATE utf8mb4_unicode_ci,
  `pixel_approved` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_profile_directory`
--
ALTER TABLE `active_profile_directory`
  ADD KEY `insta_id_idx` (`insta_id`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `braintree_transactions`
--
ALTER TABLE `braintree_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clickfunnel_webhook_log`
--
ALTER TABLE `clickfunnel_webhook_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competition_updates`
--
ALTER TABLE `competition_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_idx` (`email`(10)),
  ADD KEY `date_idx` (`created_at`);

--
-- Indexes for table `create_insta_profile_log`
--
ALTER TABLE `create_insta_profile_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `default_image_category`
--
ALTER TABLE `default_image_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `default_image_gallery`
--
ALTER TABLE `default_image_gallery`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `dm_error_log`
--
ALTER TABLE `dm_error_log`
  ADD PRIMARY KEY (`error_log_id`);

--
-- Indexes for table `dm_job`
--
ALTER TABLE `dm_job`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `dm_job_archive`
--
ALTER TABLE `dm_job_archive`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `dm_job_log`
--
ALTER TABLE `dm_job_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `dm_log`
--
ALTER TABLE `dm_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `dm_thread`
--
ALTER TABLE `dm_thread`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dm_thread_items`
--
ALTER TABLE `dm_thread_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dm_thread_users`
--
ALTER TABLE `dm_thread_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engagement_job_queue`
--
ALTER TABLE `engagement_job_queue`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_sessions`
--
ALTER TABLE `instagram_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mailing_list`
--
ALTER TABLE `mailing_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `morfix_plans`
--
ALTER TABLE `morfix_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `morfix_qna`
--
ALTER TABLE `morfix_qna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `morfix_servers`
--
ALTER TABLE `morfix_servers`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `morfix_settings`
--
ALTER TABLE `morfix_settings`
  ADD PRIMARY KEY (`setting`,`value`);

--
-- Indexes for table `morfix_topics`
--
ALTER TABLE `morfix_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `morfix_watermark`
--
ALTER TABLE `morfix_watermark`
  ADD PRIMARY KEY (`watermark_id`);

--
-- Indexes for table `niches`
--
ALTER TABLE `niches`
  ADD PRIMARY KEY (`niche_id`);

--
-- Indexes for table `niche_targets`
--
ALTER TABLE `niche_targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `niche_id` (`niche_id`);

--
-- Indexes for table `niche_targets_hashtags`
--
ALTER TABLE `niche_targets_hashtags`
  ADD PRIMARY KEY (`niche_hashtag_id`);

--
-- Indexes for table `payment_log`
--
ALTER TABLE `payment_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_charges`
--
ALTER TABLE `paypal_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_webhook_log`
--
ALTER TABLE `paypal_webhook_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proxy`
--
ALTER TABLE `proxy`
  ADD PRIMARY KEY (`proxy`);

--
-- Indexes for table `referral_ip`
--
ALTER TABLE `referral_ip`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_UNIQUE` (`ip`);

--
-- Indexes for table `slave_node_connection_directory`
--
ALTER TABLE `slave_node_connection_directory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stripe_payment_log`
--
ALTER TABLE `stripe_payment_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `stripe_webhook_log`
--
ALTER TABLE `stripe_webhook_log`
  ADD PRIMARY KEY (`stripe_log_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_affiliate`
--
ALTER TABLE `user_affiliate`
  ADD PRIMARY KEY (`affiliate_id`),
  ADD UNIQUE KEY `unique_user_referral` (`referrer`,`referred`),
  ADD UNIQUE KEY `referred_UNIQUE` (`referred`);

--
-- Indexes for table `user_feedback`
--
ALTER TABLE `user_feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `user_images`
--
ALTER TABLE `user_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_insta_follower_analysis`
--
ALTER TABLE `user_insta_follower_analysis`
  ADD PRIMARY KEY (`analysis_id`);

--
-- Indexes for table `user_insta_photo_post_schedule`
--
ALTER TABLE `user_insta_photo_post_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `insta_id_idx` (`insta_id`);

--
-- Indexes for table `user_insta_profile`
--
ALTER TABLE `user_insta_profile`
  ADD PRIMARY KEY (`id`,`insta_username`),
  ADD KEY `insta_profile_user_idx` (`user_id`),
  ADD KEY `insta_profile_user_niche_idx` (`niche`),
  ADD KEY `insta_username_idx` (`insta_username`(30)),
  ADD KEY `insta_profile_email` (`email`),
  ADD KEY `insta_profile_id_idx` (`user_id`);

--
-- Indexes for table `user_insta_profile_comment`
--
ALTER TABLE `user_insta_profile_comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `user_insta_profile_comment_log`
--
ALTER TABLE `user_insta_profile_comment_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user_insta_profile_follow_log`
--
ALTER TABLE `user_insta_profile_follow_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user_insta_profile_like_log`
--
ALTER TABLE `user_insta_profile_like_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user_insta_profile_like_log_archive`
--
ALTER TABLE `user_insta_profile_like_log_archive`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user_insta_target_hashtag`
--
ALTER TABLE `user_insta_target_hashtag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insta_user_target_hashtag_idx` (`insta_id`);

--
-- Indexes for table `user_insta_target_username`
--
ALTER TABLE `user_insta_target_username`
  ADD PRIMARY KEY (`target_id`),
  ADD KEY `user_insta_target_username_idx` (`insta_id`),
  ADD KEY `insta_username_idx` (`insta_username`(30)),
  ADD KEY `target_username_idx` (`target_username`(30));

--
-- Indexes for table `user_interaction_failed`
--
ALTER TABLE `user_interaction_failed`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_paypal_agreements`
--
ALTER TABLE `user_paypal_agreements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `agreement_id_UNIQUE` (`agreement_id`);

--
-- Indexes for table `user_stripe_active_subscription`
--
ALTER TABLE `user_stripe_active_subscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stripe_id_idx` (`stripe_id`),
  ADD KEY `subscription_id_idx` (`subscription_id`),
  ADD KEY `stripe_sub_id_idx` (`stripe_subscription_id`);

--
-- Indexes for table `user_stripe_charges`
--
ALTER TABLE `user_stripe_charges`
  ADD PRIMARY KEY (`stripe_id`,`charge_id`);

--
-- Indexes for table `user_stripe_details`
--
ALTER TABLE `user_stripe_details`
  ADD PRIMARY KEY (`stripe_id`),
  ADD KEY `stripe_details_user_email_idx` (`email`);

--
-- Indexes for table `user_stripe_invoice`
--
ALTER TABLE `user_stripe_invoice`
  ADD PRIMARY KEY (`stripe_id`,`invoice_id`,`subscription_id`);

--
-- Indexes for table `user_updates`
--
ALTER TABLE `user_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yourls_log`
--
ALTER TABLE `yourls_log`
  ADD PRIMARY KEY (`click_id`);

--
-- Indexes for table `yourls_options`
--
ALTER TABLE `yourls_options`
  ADD PRIMARY KEY (`option_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clickfunnel_webhook_log`
--
ALTER TABLE `clickfunnel_webhook_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competition_updates`
--
ALTER TABLE `competition_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `create_insta_profile_log`
--
ALTER TABLE `create_insta_profile_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `default_image_category`
--
ALTER TABLE `default_image_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `default_image_gallery`
--
ALTER TABLE `default_image_gallery`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_error_log`
--
ALTER TABLE `dm_error_log`
  MODIFY `error_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_job`
--
ALTER TABLE `dm_job`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_job_archive`
--
ALTER TABLE `dm_job_archive`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_job_log`
--
ALTER TABLE `dm_job_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_log`
--
ALTER TABLE `dm_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_thread`
--
ALTER TABLE `dm_thread`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_thread_items`
--
ALTER TABLE `dm_thread_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_thread_users`
--
ALTER TABLE `dm_thread_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `engagement_job_queue`
--
ALTER TABLE `engagement_job_queue`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_sessions`
--
ALTER TABLE `instagram_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mailing_list`
--
ALTER TABLE `mailing_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `morfix_plans`
--
ALTER TABLE `morfix_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `morfix_qna`
--
ALTER TABLE `morfix_qna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `morfix_topics`
--
ALTER TABLE `morfix_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `niches`
--
ALTER TABLE `niches`
  MODIFY `niche_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `niche_targets`
--
ALTER TABLE `niche_targets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `niche_targets_hashtags`
--
ALTER TABLE `niche_targets_hashtags`
  MODIFY `niche_hashtag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_log`
--
ALTER TABLE `payment_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paypal_charges`
--
ALTER TABLE `paypal_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paypal_webhook_log`
--
ALTER TABLE `paypal_webhook_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_ip`
--
ALTER TABLE `referral_ip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stripe_payment_log`
--
ALTER TABLE `stripe_payment_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stripe_webhook_log`
--
ALTER TABLE `stripe_webhook_log`
  MODIFY `stripe_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_affiliate`
--
ALTER TABLE `user_affiliate`
  MODIFY `affiliate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_feedback`
--
ALTER TABLE `user_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_images`
--
ALTER TABLE `user_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_follower_analysis`
--
ALTER TABLE `user_insta_follower_analysis`
  MODIFY `analysis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_photo_post_schedule`
--
ALTER TABLE `user_insta_photo_post_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_profile`
--
ALTER TABLE `user_insta_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT for table `user_insta_profile_comment`
--
ALTER TABLE `user_insta_profile_comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_profile_comment_log`
--
ALTER TABLE `user_insta_profile_comment_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_profile_follow_log`
--
ALTER TABLE `user_insta_profile_follow_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_profile_like_log`
--
ALTER TABLE `user_insta_profile_like_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_profile_like_log_archive`
--
ALTER TABLE `user_insta_profile_like_log_archive`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_target_hashtag`
--
ALTER TABLE `user_insta_target_hashtag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_insta_target_username`
--
ALTER TABLE `user_insta_target_username`
  MODIFY `target_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35708;

--
-- AUTO_INCREMENT for table `user_interaction_failed`
--
ALTER TABLE `user_interaction_failed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_paypal_agreements`
--
ALTER TABLE `user_paypal_agreements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_stripe_active_subscription`
--
ALTER TABLE `user_stripe_active_subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_updates`
--
ALTER TABLE `user_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yourls_log`
--
ALTER TABLE `yourls_log`
  MODIFY `click_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yourls_options`
--
ALTER TABLE `yourls_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `niche_targets`
--
ALTER TABLE `niche_targets`
  ADD CONSTRAINT `niche_id` FOREIGN KEY (`niche_id`) REFERENCES `niches` (`niche_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_insta_profile`
--
ALTER TABLE `user_insta_profile`
  ADD CONSTRAINT `insta_profile_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `insta_profile_user_niche` FOREIGN KEY (`niche`) REFERENCES `niches` (`niche_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_stripe_active_subscription`
--
ALTER TABLE `user_stripe_active_subscription`
  ADD CONSTRAINT `stripe_id` FOREIGN KEY (`stripe_id`) REFERENCES `user_stripe_details` (`stripe_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_stripe_invoice`
--
ALTER TABLE `user_stripe_invoice`
  ADD CONSTRAINT `invoice_fk_stripe_id` FOREIGN KEY (`stripe_id`) REFERENCES `user_stripe_details` (`stripe_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 12, 2025 at 12:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gearup`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('admin1@gmail.com|127.0.0.1', 'i:1;', 1746827285),
('admin1@gmail.com|127.0.0.1:timer', 'i:1746827285;', 1746827285),
('admin123@gmail.com|127.0.0.1', 'i:1;', 1746159595),
('admin123@gmail.com|127.0.0.1:timer', 'i:1746159595;', 1746159595),
('j.palen.517524@umindanao.edu.ph|127.0.0.1', 'i:2;', 1746428019),
('j.palen.517524@umindanao.edu.ph|127.0.0.1:timer', 'i:1746428019;', 1746428019),
('j.palen.527524@umindanao.edu.ph|127.0.0.1', 'i:1;', 1746159889),
('j.palen.527524@umindanao.edu.ph|127.0.0.1:timer', 'i:1746159889;', 1746159889);

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
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Interior', 'interior', 'Interior parts and accessories for vehicles', '2025-05-01 11:47:47', '2025-05-01 11:47:47'),
(2, 'Exterior', 'exterior', 'Exterior parts and accessories for vehicles', '2025-05-01 11:47:47', '2025-05-01 11:47:47'),
(3, 'Engine', 'engine', 'Engine parts and components', '2025-05-01 11:47:47', '2025-05-01 11:47:47'),
(4, 'Under Chassis', 'under-chassis', 'Under chassis parts and components', '2025-05-01 11:47:47', '2025-05-01 11:47:47');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Maria Santos', '+63 912 345 6789', 'maria@example.com', NULL, '2025-05-01 11:47:47', '2025-05-01 11:47:47'),
(2, 'Juan dela Cruz', '+63 923 456 7890', 'juan@example.com', NULL, '2025-05-01 11:47:47', '2025-05-01 11:47:47'),
(3, 'Eraj', '+63 923 295 235-9', 'eraj@snlna', NULL, '2025-05-01 11:47:47', '2025-05-01 11:47:47');

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
(4, '2024_03_21_000000_create_suppliers_table', 1),
(5, '2024_03_21_000001_create_categories_table', 1),
(6, '2024_03_21_000002_create_products_table', 1),
(7, '2024_03_21_000003_create_customers_table', 1),
(8, '2024_03_21_000004_create_orders_table', 1),
(9, '2024_03_21_000004_create_stockins_table', 1),
(10, '2024_03_21_000005_create_order_items_table', 1),
(11, '2024_04_17_000000_add_role_and_is_active_to_users_table', 1),
(12, '2024_06_03_000001_add_audit_fields_to_users_table', 1),
(13, '2025_04_13_000006_create_stock_adjustments', 1),
(14, '2025_04_14_00007_create_stock_adjustments_items', 1),
(15, '2025_04_16_063811_add_image_to_products_table', 1),
(16, '2025_04_17_014835_add_user_id_to_orders_table', 1),
(17, '2025_04_17_015158_create_audit_logs_table', 1),
(18, '2025_04_19_000001_add_date_to_stock_adjustments', 1),
(19, '2025_04_30_130037_add_audit_fields_to_users_table', 1),
(20, '2025_05_01_000001_add_birthdate_and_last_login_to_users_table', 1),
(21, '2025_05_02_000001_modify_birthdate_format_in_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','gcash','maya') NOT NULL,
  `amount_received` decimal(10,2) DEFAULT NULL,
  `change` decimal(10,2) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `subtotal`, `tax`, `discount_amount`, `discount_percentage`, `total`, `payment_method`, `amount_received`, `change`, `payment_reference`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ORD-2025-001', 1, 5000.00, 600.00, 0.00, 0.00, 5600.00, 'cash', 6000.00, 400.00, NULL, NULL, 'completed', '2025-05-01 20:20:02', '2025-05-01 20:20:02'),
(2, 'ORD-2025-002', 1, 1000.00, 120.00, 0.00, 0.00, 1120.00, 'cash', 1200.00, 80.00, NULL, NULL, 'completed', '2025-05-09 15:06:42', '2025-05-09 15:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, 5000.00, 5000.00, '2025-05-01 20:20:02', '2025-05-01 20:20:02'),
(2, 2, 5, 1, 1000.00, 1000.00, '2025-05-09 15:06:42', '2025-05-09 15:06:42');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `reorder_level` int(11) NOT NULL DEFAULT 10,
  `unit` varchar(255) NOT NULL DEFAULT 'piece',
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `category_id`, `description`, `image`, `price`, `stock`, `reorder_level`, `unit`, `brand`, `model`, `manufacturer`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Baby Car Seat', '121038412', 1, NULL, 'images/products/1745730748.jpg', 1000.00, 19, 5, 'piece', 'Baby Products', 'Test', 'Test', 1, '2025-05-01 11:47:47', '2025-05-01 11:47:47', NULL),
(2, 'Steering Wheel', '12213', 1, 'adsclqb', 'images/products/1745730828.jpg', 1500.00, 18, 5, 'piece', 'Test', 'Test', 'test', 1, '2025-05-01 11:47:47', '2025-05-01 11:47:47', NULL),
(3, 'White Car Seat', '3829401', 1, 'Test', 'images/products/1745730896.jpg', 5000.00, 11, 7, 'piece', 'Test', 'Test', 'Test', 1, '2025-05-01 11:47:47', '2025-05-01 20:21:22', NULL),
(4, 'Black Car Bumper', '12345678', 2, 'Test', 'images/products/1745730975.jpg', 500.00, 10, 5, 'piece', 'Test', 'Test', 'Test', 1, '2025-05-01 11:47:47', '2025-05-01 20:20:56', NULL),
(5, 'Black Car Wiper', '1234567', 2, 'Test', 'images/products/1745745818.jpg', 1000.00, 9, 5, 'piece', 'Wiper', 'Test', 'Test', 1, '2025-05-01 11:47:47', '2025-05-09 15:06:42', NULL),
(6, 'Car Alternator', '121234', 3, 'Test', 'images/products/1745745874.jpg', 2500.00, 10, 4, 'piece', 'Engine', 'Test', 'Test', 1, '2025-05-01 11:47:47', '2025-05-01 11:47:47', NULL),
(7, 'Fuel Injection System', '3425678986342', 3, 'Test', 'images/products/1745745922.jpg', 1000.00, 12, 4, 'piece', 'Test', 'Test', 'Test', 1, '2025-05-01 11:47:47', '2025-05-01 11:47:47', NULL),
(8, 'Auto Steering Rack', '12408124', 4, 'Test', 'images/products/1745745971.jpg', 1500.00, 9, 3, 'piece', 'test', 'Test', 'Test', 1, '2025-05-01 11:47:47', '2025-05-04 23:01:09', NULL),
(9, 'Automotive disc', '12910412', 4, 'Test', 'images/products/1745746018.jpg', 1300.00, 14, 5, 'piece', 'Test', 'Test', 'Test', 1, '2025-05-01 11:47:47', '2025-05-08 01:29:55', NULL);

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
('hEzPKq2sjUhFGNN7uq9aEJpqhe7PhN7NQ8hOMJE7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieHpSZHpZYTBWNVZrTnNjVWhGendsc2VlUGpDNzhHaE40WlNuTTlUQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZXBvcnRzL2V4cG9ydC9leGNlbD9kYXRlX3JhbmdlPXRoaXNfbW9udGgmcHJvZHVjdF9jYXRlZ29yeT1hbGwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1746834213);

-- --------------------------------------------------------

--
-- Table structure for table `stockins`
--

CREATE TABLE `stockins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('draft','completed','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stockins`
--

INSERT INTO `stockins` (`id`, `supplier_id`, `invoice_number`, `date`, `total_amount`, `status`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, NULL, '2025-05-02', 500.00, 'completed', NULL, 1, '2025-05-01 20:20:56', '2025-05-01 20:20:56', NULL),
(2, 2, NULL, '2025-05-02', 2000.00, 'completed', NULL, 1, '2025-05-01 20:21:22', '2025-05-01 20:21:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stockin_items`
--

CREATE TABLE `stockin_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stockin_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stockin_items`
--

INSERT INTO `stockin_items` (`id`, `stockin_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, 500.00, 500.00, '2025-05-01 20:20:56', '2025-05-01 20:20:56'),
(2, 2, 3, 4, 500.00, 2000.00, '2025-05-01 20:21:22', '2025-05-01 20:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `type` enum('stock_out','adjustment') NOT NULL,
  `notes` text DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_adjustments`
--

INSERT INTO `stock_adjustments` (`id`, `reference_number`, `date`, `type`, `notes`, `processed_by`, `created_at`, `updated_at`) VALUES
(1, 'SO-20250505-001', '2025-05-05', 'stock_out', NULL, 6, '2025-05-04 23:01:09', '2025-05-04 23:01:09'),
(2, 'SO-20250508-001', '2025-05-08', 'stock_out', NULL, 1, '2025-05-08 01:29:55', '2025-05-08 01:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment_items`
--

CREATE TABLE `stock_adjustment_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_adjustment_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `current_stock` int(11) NOT NULL,
  `new_count` int(11) NOT NULL,
  `difference` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_adjustment_items`
--

INSERT INTO `stock_adjustment_items` (`id`, `stock_adjustment_id`, `product_id`, `current_stock`, `new_count`, `difference`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 10, 1, -1, 'damaged', '2025-05-04 23:01:09', '2025-05-04 23:01:09'),
(2, 2, 9, 15, 1, -1, 'damaged', '2025-05-08 01:29:55', '2025-05-08 01:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `payment_terms` enum('cod','15days','30days','60days') NOT NULL DEFAULT 'cod',
  `status` enum('active','on_hold','inactive') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_code`, `name`, `contact_person`, `position`, `phone`, `email`, `address`, `payment_terms`, `status`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MAP-001', 'Metro Auto Parts Inc.', 'Carlos Rodriguez', 'Sales Manager', '+63 912 345 6789', 'carlos@metroautoparts.com', '123 Main Avenue, Makati City, Metro Manila, Philippines', '30days', 'active', 'Reliable supplier for engine components and brake systems. Offers volume discounts on orders above ₱50,000.', '2025-05-01 11:47:47', '2025-05-01 11:47:47', NULL),
(2, 'GT-231', 'GearTech Solutions', 'Maria Santos', 'Account Executive', '+ 63 917 987 6543', 'maria@geartechsolutions.ph', '456 Technology Drive, BGC, Taguig City, Philippines', '15days', 'active', 'Premium supplier for electronic components and sensors. Fast delivery and excellent quality control.', '2025-05-01 11:47:47', '2025-05-01 11:47:47', NULL),
(3, 'PPM-123', 'Pinoy Parts Manufacturing', 'Juan dela Cruz', 'Owner', '+63 908 765 4321', 'juan@pinoymade.ph', '567 Industrial Zone, Calamba City, Laguna, Philippines', '30days', 'on_hold', 'Local manufacturer of aftermarket parts. Currently experiencing production delays. Expected to resume normal operations next month.', '2025-05-01 11:47:47', '2025-05-01 11:47:47', NULL),
(4, 'JIAP-88', 'Japanese Import Auto Parts', 'Hiroshi Yamada', 'International Sales Director', '+63 922 567 8901', 'hiroshi@japanesepartsimport.com', '321 Asia Avenue, Ortigas Center, Pasig City, Philippines', 'cod', 'active', 'Specializes in genuine and OEM parts for Japanese car brands. Direct import from Japan with 1-2 weeks lead time.', '2025-05-01 11:47:47', '2025-05-01 11:47:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `birthdate` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `is_active`, `birthdate`, `last_login_at`, `created_by`, `updated_by`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@gmail.com', 'admin', 1, NULL, '2025-05-09 13:47:11', NULL, NULL, '2025-05-01 11:47:46', '$2y$12$SlbnDiiY0DAFeiwySmIKKuESd8B1cepquXJUTyU2JbJloL4Urrdcu', NULL, '2025-05-01 11:47:47', '2025-05-09 13:47:11'),
(6, 'staff', 'staff@gmail.com', 'staff', 1, NULL, '2025-05-04 23:05:27', 1, 1, NULL, '$2y$12$rCG09Fjl7FJMOH0VZJ5x7uQLCscVMdAkdxP96t0fMJisEfxmwJcFK', NULL, '2025-05-04 22:53:46', '2025-05-04 23:05:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stockins`
--
ALTER TABLE `stockins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stockins_supplier_id_foreign` (`supplier_id`),
  ADD KEY `stockins_created_by_foreign` (`created_by`);

--
-- Indexes for table `stockin_items`
--
ALTER TABLE `stockin_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stockin_items_stockin_id_foreign` (`stockin_id`),
  ADD KEY `stockin_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_adjustments_reference_number_unique` (`reference_number`),
  ADD KEY `stock_adjustments_processed_by_foreign` (`processed_by`);

--
-- Indexes for table `stock_adjustment_items`
--
ALTER TABLE `stock_adjustment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustment_items_stock_adjustment_id_foreign` (`stock_adjustment_id`),
  ADD KEY `stock_adjustment_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_supplier_code_unique` (`supplier_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_created_by_foreign` (`created_by`),
  ADD KEY `users_updated_by_foreign` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stockins`
--
ALTER TABLE `stockins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stockin_items`
--
ALTER TABLE `stockin_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_adjustment_items`
--
ALTER TABLE `stock_adjustment_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stockins`
--
ALTER TABLE `stockins`
  ADD CONSTRAINT `stockins_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stockins_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stockin_items`
--
ALTER TABLE `stockin_items`
  ADD CONSTRAINT `stockin_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stockin_items_stockin_id_foreign` FOREIGN KEY (`stockin_id`) REFERENCES `stockins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_adjustment_items`
--
ALTER TABLE `stock_adjustment_items`
  ADD CONSTRAINT `stock_adjustment_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_adjustment_items_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

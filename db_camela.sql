-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 02, 2026 at 04:52 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_camela`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_booking` time NOT NULL,
  `status` varchar(255) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `jenis_pembayaran` enum('dp','lunas') NOT NULL,
  `total_pembayaran` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `order_id`, `user_id`, `tanggal_booking`, `jam_booking`, `status`, `total_harga`, `jenis_pembayaran`, `total_pembayaran`, `created_at`, `updated_at`) VALUES
(7, 'BOOKING-Z6FXW90', 2, '2025-12-19', '14:53:00', 'confirmed', 350000.00, 'lunas', 350000.00, '2025-12-19 00:53:40', '2025-12-19 00:54:38'),
(8, 'BOOKING-83UF6WJ', 2, '2025-12-19', '16:32:00', 'confirmed', 350000.00, 'lunas', 350000.00, '2025-12-19 02:32:46', '2025-12-19 02:33:02'),
(9, 'BOOKING-RLMJ517', 2, '2025-12-22', '09:44:00', 'confirmed', 350000.00, 'lunas', 350000.00, '2025-12-21 19:45:02', '2025-12-21 19:46:37'),
(10, 'BOOKING-GJFNDQR', 2, '2025-12-22', '09:44:00', 'pending', 350000.00, 'lunas', 350000.00, '2025-12-21 19:47:37', '2025-12-21 19:47:37'),
(11, 'BOOKING-EKSDO7Q', 2, '2025-12-22', '09:57:00', 'confirmed', 350000.00, 'lunas', 350000.00, '2025-12-21 19:58:02', '2025-12-21 19:58:30'),
(12, 'BOOKING-7O4GCVZ', 2, '2025-12-22', '09:57:00', 'pending', 350000.00, 'lunas', 350000.00, '2025-12-21 19:58:46', '2025-12-21 19:58:46'),
(13, 'BOOKING-BE6EZJW', 2, '2025-12-22', '10:43:00', 'pending', 350000.00, 'lunas', 350000.00, '2025-12-21 20:43:31', '2025-12-21 20:43:31'),
(14, 'BOOKING-YD6C4ML', 2, '2026-01-05', '14:13:00', 'pending', 350000.00, 'lunas', 350000.00, '2026-01-05 00:13:42', '2026-01-05 00:13:42'),
(15, 'BOOKING-X90AJSN', 2, '2026-01-05', '14:13:00', 'pending', 350000.00, 'lunas', 350000.00, '2026-01-05 00:15:31', '2026-01-05 00:15:31'),
(16, 'BOOKING-JYWUI45', 2, '2026-01-05', '16:40:00', 'pending', 400000.00, 'lunas', 400000.00, '2026-01-05 02:40:57', '2026-01-05 02:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `layanan_id` bigint(20) UNSIGNED NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_details`
--

INSERT INTO `booking_details` (`id`, `booking_id`, `layanan_id`, `harga`, `qty`, `created_at`, `updated_at`) VALUES
(7, 7, 1, 350000.00, 1, '2025-12-19 00:53:40', '2025-12-19 00:53:40'),
(8, 8, 1, 350000.00, 1, '2025-12-19 02:32:46', '2025-12-19 02:32:46'),
(9, 9, 1, 350000.00, 1, '2025-12-21 19:45:02', '2025-12-21 19:45:02'),
(10, 10, 1, 350000.00, 1, '2025-12-21 19:47:37', '2025-12-21 19:47:37'),
(11, 11, 1, 350000.00, 1, '2025-12-21 19:58:02', '2025-12-21 19:58:02'),
(12, 12, 1, 350000.00, 1, '2025-12-21 19:58:46', '2025-12-21 19:58:46'),
(13, 13, 1, 350000.00, 1, '2025-12-21 20:43:31', '2025-12-21 20:43:31'),
(14, 14, 1, 350000.00, 1, '2026-01-05 00:13:42', '2026-01-05 00:13:42'),
(15, 15, 1, 350000.00, 1, '2026-01-05 00:15:31', '2026-01-05 00:15:31'),
(16, 16, 2, 400000.00, 1, '2026-01-05 02:40:57', '2026-01-05 02:40:57');

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
('livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6', 'i:1;', 1768911776),
('livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer', 'i:1768911776;', 1768911776);

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
-- Table structure for table `jadwal_operasionals`
--

CREATE TABLE `jadwal_operasionals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jam_buka` time NOT NULL,
  `jam_tutup` time NOT NULL,
  `status` enum('buka','tutup') NOT NULL DEFAULT 'buka',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `kategori_layanans`
--

CREATE TABLE `kategori_layanans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_layanans`
--

INSERT INTO `kategori_layanans` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Bundling', '2025-12-18 20:20:20', '2025-12-18 20:20:20'),
(2, 'tes', '2026-01-20 05:26:53', '2026-01-20 05:26:53');

-- --------------------------------------------------------

--
-- Table structure for table `layanans`
--

CREATE TABLE `layanans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`image`)),
  `estimasi_menit` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `layanans`
--

INSERT INTO `layanans` (`id`, `kategori_id`, `name`, `deskripsi`, `harga`, `image`, `estimasi_menit`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bundling Hair & Nail Care', 'Paket kombinasi perawatan rambut dan kuku dengan harga hemat.', 350000.00, '[\"layanans/01K9F6NNJ0EEFEPWNACRBXJR2F.jpg\"]', 120, '2025-12-18 20:20:20', '2025-12-18 20:20:20'),
(2, 1, 'Bundling Facial & Body Spa', 'Nikmati relaksasi maksimal dengan perawatan wajah dan spa tubuh sekaligus.', 400000.00, '[\"layanans/01K9F6NNJ0EEFEPWNACRBXJR2F.jpg\"]', 150, '2025-12-18 20:20:20', '2025-12-18 20:20:20'),
(3, 1, 'Bundling Complete Care', 'Paket lengkap mulai dari hair, nail, facial hingga body spa dalam satu sesi.', 650000.00, '[\"layanans/01K9F6NNJ0EEFEPWNACRBXJR2F.jpg\"]', 240, '2025-12-18 20:20:20', '2025-12-18 20:20:20');

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
(4, '2025_11_07_124411_create_kategori_layanans_table', 1),
(5, '2025_11_07_124833_create_layanans_table', 1),
(6, '2025_11_07_130614_create_jadwal_operasionals_table', 1),
(7, '2025_11_07_133906_create_promo_layanans_table', 1),
(8, '2025_11_08_064203_create_personal_access_tokens_table', 1),
(9, '2025_12_09_144540_create_bookings_table', 2),
(10, '2025_12_09_144549_create_booking_details_table', 2),
(11, '2025_12_09_164005_add_order_id_to_bookings_table', 2),
(12, '2025_12_09_144511_create_pembayarans_table', 3);

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
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `transaction_status` varchar(255) NOT NULL DEFAULT 'pending',
  `fraud_status` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `payment_gateway` varchar(255) NOT NULL DEFAULT 'midtrans',
  `payment_gateway_reference_id` varchar(255) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `va_number` varchar(255) DEFAULT NULL,
  `qr_url` text DEFAULT NULL,
  `deeplink_url` text DEFAULT NULL,
  `payment_url` text DEFAULT NULL,
  `payment_gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_gateway_response`)),
  `midtrans_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`midtrans_response`)),
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `transaction_time` timestamp NULL DEFAULT NULL,
  `settlement_time` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `booking_id`, `order_id`, `transaction_id`, `gross_amount`, `transaction_status`, `fraud_status`, `payment_type`, `payment_gateway`, `payment_gateway_reference_id`, `bank`, `va_number`, `qr_url`, `deeplink_url`, `payment_url`, `payment_gateway_response`, `midtrans_response`, `payment_proof`, `payment_date`, `transaction_time`, `settlement_time`, `expired_at`, `created_at`, `updated_at`) VALUES
(1, 7, 'BOOKING-Z6FXW90', '336fa80c-4f85-4a37-b290-76535385123f', 350000.00, 'success', 'accept', 'BANK_TRANSFER', 'midtrans', 'BOOKING-Z6FXW90', 'bri', '368452662453704419', NULL, NULL, NULL, '\"{\\\"va_numbers\\\":[{\\\"va_number\\\":\\\"368452662453704419\\\",\\\"bank\\\":\\\"bri\\\"}],\\\"transaction_time\\\":\\\"2025-12-19 14:53:41\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"transaction_id\\\":\\\"336fa80c-4f85-4a37-b290-76535385123f\\\",\\\"status_message\\\":\\\"midtrans payment notification\\\",\\\"status_code\\\":\\\"200\\\",\\\"signature_key\\\":\\\"22f7847e6bea0342c39b0037965dcaff252e293e6d877f1e88bbef64e1c64a8c3917c4c36511a2ffbe76f5ac7ae02b3521aa214ce0a8b102edb126a8437fa886\\\",\\\"settlement_time\\\":\\\"2025-12-19 14:54:38\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"payment_amounts\\\":[],\\\"order_id\\\":\\\"BOOKING-Z6FXW90\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"expiry_time\\\":\\\"2025-12-19 15:53:41\\\",\\\"customer_details\\\":{\\\"phone\\\":\\\"N\\\\\\/A\\\",\\\"full_name\\\":\\\"fremas\\\",\\\"email\\\":\\\"fremas@gmail.com\\\"},\\\"currency\\\":\\\"IDR\\\"}\"', NULL, NULL, '2025-12-19 00:53:41', '2025-12-19 07:53:41', '2025-12-19 07:54:38', '2025-12-19 01:53:41', '2025-12-19 00:53:41', '2025-12-19 00:54:38'),
(2, 8, 'BOOKING-83UF6WJ', '808e12ab-7785-43a3-9669-baf404c2d566', 350000.00, 'success', 'accept', 'GOPAY', 'midtrans', 'BOOKING-83UF6WJ', NULL, NULL, 'https://api.sandbox.midtrans.com/v2/gopay/808e12ab-7785-43a3-9669-baf404c2d566/qr-code', 'https://simulator.sandbox.midtrans.com/v2/deeplink/detail?tref=A1202512190932473dHnaIqSM6ID', NULL, '\"{\\\"transaction_time\\\":\\\"2025-12-19 16:32:47\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"transaction_id\\\":\\\"808e12ab-7785-43a3-9669-baf404c2d566\\\",\\\"status_message\\\":\\\"midtrans payment notification\\\",\\\"status_code\\\":\\\"200\\\",\\\"signature_key\\\":\\\"76cc83e93fe68b04cb5fd8a37fa6e67cc0672c13460a812e24082eef430ad7b2d8c889c3068fe33c28a424ca8664d265438e90f8ebe9886298d5644b7eb22ea3\\\",\\\"settlement_time\\\":\\\"2025-12-19 16:33:02\\\",\\\"pop_id\\\":\\\"e85ba9d2-301d-4766-8bb3-67e5c30452cb\\\",\\\"payment_type\\\":\\\"gopay\\\",\\\"payment_option_type\\\":\\\"GOPAY_WALLET\\\",\\\"order_id\\\":\\\"BOOKING-83UF6WJ\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"expiry_time\\\":\\\"2025-12-19 17:32:47\\\",\\\"customer_details\\\":{\\\"phone\\\":\\\"N\\\\\\/A\\\",\\\"full_name\\\":\\\"fremas\\\",\\\"email\\\":\\\"fremas@gmail.com\\\"},\\\"currency\\\":\\\"IDR\\\"}\"', NULL, NULL, '2025-12-19 02:32:47', '2025-12-19 09:32:47', '2025-12-19 09:33:02', '2025-12-19 03:32:47', '2025-12-19 02:32:47', '2025-12-19 02:33:02'),
(3, 9, 'BOOKING-RLMJ517', '8e8c0212-0af1-4d5b-8ace-7c57c816a4a4', 350000.00, 'success', 'accept', 'GOPAY', 'midtrans', 'BOOKING-RLMJ517', NULL, NULL, 'https://api.sandbox.midtrans.com/v2/gopay/8e8c0212-0af1-4d5b-8ace-7c57c816a4a4/qr-code', 'https://simulator.sandbox.midtrans.com/v2/deeplink/detail?tref=A120251222024503t4OBmOGQR8ID', NULL, '\"{\\\"transaction_time\\\":\\\"2025-12-22 09:45:03\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"transaction_id\\\":\\\"8e8c0212-0af1-4d5b-8ace-7c57c816a4a4\\\",\\\"status_message\\\":\\\"midtrans payment notification\\\",\\\"status_code\\\":\\\"200\\\",\\\"signature_key\\\":\\\"f43d477ae19da5e1fa913fcffc5c84bc3344e2e25bd25959863abf8c0c712eb15c24c48e90c7731057dce1a3f83f8580d2702670485fba165b8f9d6753b01a63\\\",\\\"settlement_time\\\":\\\"2025-12-22 09:46:36\\\",\\\"pop_id\\\":\\\"e85ba9d2-301d-4766-8bb3-67e5c30452cb\\\",\\\"payment_type\\\":\\\"gopay\\\",\\\"payment_option_type\\\":\\\"GOPAY_WALLET\\\",\\\"order_id\\\":\\\"BOOKING-RLMJ517\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"expiry_time\\\":\\\"2025-12-22 10:45:03\\\",\\\"customer_details\\\":{\\\"phone\\\":\\\"N\\\\\\/A\\\",\\\"full_name\\\":\\\"fremas\\\",\\\"email\\\":\\\"fremas@gmail.com\\\"},\\\"currency\\\":\\\"IDR\\\"}\"', NULL, NULL, '2025-12-21 19:45:03', '2025-12-22 02:45:03', '2025-12-22 02:46:36', '2025-12-21 20:45:03', '2025-12-21 19:45:03', '2025-12-21 19:46:37'),
(4, 10, 'BOOKING-GJFNDQR', '4c45c927-d4b1-4314-993f-537e33d85875', 350000.00, 'pending', 'accept', 'GOPAY', 'midtrans', 'BOOKING-GJFNDQR', NULL, NULL, 'https://api.sandbox.midtrans.com/v2/gopay/4c45c927-d4b1-4314-993f-537e33d85875/qr-code', 'https://simulator.sandbox.midtrans.com/v2/deeplink/detail?tref=A120251222024738MzqeHGqRzHID', NULL, '\"{\\\"transaction_time\\\":\\\"2025-12-22 09:47:38\\\",\\\"transaction_status\\\":\\\"pending\\\",\\\"transaction_id\\\":\\\"4c45c927-d4b1-4314-993f-537e33d85875\\\",\\\"status_message\\\":\\\"midtrans payment notification\\\",\\\"status_code\\\":\\\"201\\\",\\\"signature_key\\\":\\\"29380725db4aaac8179822a146a711c17149273bd9a68124f4f72dddf080f752a6ee17180b9ffd3f3850015332b987f0331b0931c12ded68c65925fd64318680\\\",\\\"pop_id\\\":\\\"e85ba9d2-301d-4766-8bb3-67e5c30452cb\\\",\\\"payment_type\\\":\\\"gopay\\\",\\\"order_id\\\":\\\"BOOKING-GJFNDQR\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"expiry_time\\\":\\\"2025-12-22 10:47:38\\\",\\\"customer_details\\\":{\\\"phone\\\":\\\"N\\\\\\/A\\\",\\\"full_name\\\":\\\"fremas\\\",\\\"email\\\":\\\"fremas@gmail.com\\\"},\\\"currency\\\":\\\"IDR\\\"}\"', NULL, NULL, '2025-12-21 19:47:38', '2025-12-22 02:47:38', NULL, '2025-12-21 20:47:38', '2025-12-21 19:47:38', '2025-12-21 19:47:39'),
(5, 11, 'BOOKING-EKSDO7Q', '7a1bd7f7-7f2d-4e25-9066-f95273483ed0', 350000.00, 'success', 'accept', 'BANK_TRANSFER', 'midtrans', 'BOOKING-EKSDO7Q', 'bri', '368456068671927561', NULL, NULL, NULL, '\"{\\\"va_numbers\\\":[{\\\"va_number\\\":\\\"368456068671927561\\\",\\\"bank\\\":\\\"bri\\\"}],\\\"transaction_time\\\":\\\"2025-12-22 09:58:03\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"transaction_id\\\":\\\"7a1bd7f7-7f2d-4e25-9066-f95273483ed0\\\",\\\"status_message\\\":\\\"midtrans payment notification\\\",\\\"status_code\\\":\\\"200\\\",\\\"signature_key\\\":\\\"9466e688ba384e6f822af918e155143ee5f7badc5b2d682b7af6367cb08ba0e3a78af42812c71169ab314f1bc5f086044d844557e7db6248c0597f5588c3832d\\\",\\\"settlement_time\\\":\\\"2025-12-22 09:58:29\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"payment_amounts\\\":[],\\\"order_id\\\":\\\"BOOKING-EKSDO7Q\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"expiry_time\\\":\\\"2025-12-22 10:58:03\\\",\\\"customer_details\\\":{\\\"phone\\\":\\\"N\\\\\\/A\\\",\\\"full_name\\\":\\\"fremas\\\",\\\"email\\\":\\\"fremas@gmail.com\\\"},\\\"currency\\\":\\\"IDR\\\"}\"', NULL, NULL, '2025-12-21 19:58:03', '2025-12-22 02:58:03', '2025-12-22 02:58:29', '2025-12-21 20:58:03', '2025-12-21 19:58:03', '2025-12-21 19:58:30'),
(6, 12, 'BOOKING-7O4GCVZ', 'c354473d-a69c-4b04-bdff-fc18a28f8a87', 350000.00, 'pending', 'accept', 'BANK_TRANSFER', 'midtrans', 'BOOKING-7O4GCVZ', 'bri', '368458077434515682', NULL, NULL, NULL, '\"{\\\"va_numbers\\\":[{\\\"va_number\\\":\\\"368458077434515682\\\",\\\"bank\\\":\\\"bri\\\"}],\\\"transaction_time\\\":\\\"2025-12-22 09:58:47\\\",\\\"transaction_status\\\":\\\"pending\\\",\\\"transaction_id\\\":\\\"c354473d-a69c-4b04-bdff-fc18a28f8a87\\\",\\\"status_message\\\":\\\"midtrans payment notification\\\",\\\"status_code\\\":\\\"201\\\",\\\"signature_key\\\":\\\"50b11179f03812c53bc1b08f31fe0d8fdb344c74f0d2337e10fd88415476884d69fa60df33c385fa48e8f3f5084b794c0af83cdd4b1798a4c4b60c0510817fc9\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"payment_amounts\\\":[],\\\"order_id\\\":\\\"BOOKING-7O4GCVZ\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"expiry_time\\\":\\\"2025-12-22 10:58:47\\\",\\\"customer_details\\\":{\\\"phone\\\":\\\"N\\\\\\/A\\\",\\\"full_name\\\":\\\"fremas\\\",\\\"email\\\":\\\"fremas@gmail.com\\\"},\\\"currency\\\":\\\"IDR\\\"}\"', NULL, NULL, '2025-12-21 19:58:47', '2025-12-22 02:58:47', NULL, '2025-12-21 20:58:47', '2025-12-21 19:58:47', '2025-12-21 19:58:48'),
(7, 13, 'BOOKING-BE6EZJW', NULL, 350000.00, 'pending', NULL, 'BANK_TRANSFER', 'midtrans', 'BOOKING-BE6EZJW', 'bri', '368450523649646017', NULL, NULL, NULL, '\"{\\\"status_code\\\":\\\"201\\\",\\\"status_message\\\":\\\"Success, Bank Transfer transaction is created\\\",\\\"transaction_id\\\":\\\"443f9b2a-da61-49b1-b451-483de53a78bc\\\",\\\"order_id\\\":\\\"BOOKING-BE6EZJW\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"currency\\\":\\\"IDR\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"transaction_time\\\":\\\"2025-12-22 10:43:31\\\",\\\"transaction_status\\\":\\\"pending\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"va_numbers\\\":[{\\\"bank\\\":\\\"bri\\\",\\\"va_number\\\":\\\"368450523649646017\\\"}],\\\"expiry_time\\\":\\\"2025-12-22 11:43:31\\\"}\"', NULL, NULL, '2025-12-21 20:43:31', NULL, NULL, '2025-12-21 21:43:31', '2025-12-21 20:43:31', '2025-12-21 20:43:31'),
(8, 14, 'BOOKING-YD6C4ML', NULL, 350000.00, 'pending', NULL, 'BANK_TRANSFER', 'midtrans', 'BOOKING-YD6C4ML', 'bri', '368457844659495314', NULL, NULL, NULL, '\"{\\\"status_code\\\":\\\"201\\\",\\\"status_message\\\":\\\"Success, Bank Transfer transaction is created\\\",\\\"transaction_id\\\":\\\"f6b194f1-0ad5-454c-b86a-f25ff856372c\\\",\\\"order_id\\\":\\\"BOOKING-YD6C4ML\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"currency\\\":\\\"IDR\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"transaction_time\\\":\\\"2026-01-05 14:13:44\\\",\\\"transaction_status\\\":\\\"pending\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"va_numbers\\\":[{\\\"bank\\\":\\\"bri\\\",\\\"va_number\\\":\\\"368457844659495314\\\"}],\\\"expiry_time\\\":\\\"2026-01-05 15:13:44\\\"}\"', NULL, NULL, '2026-01-05 00:13:44', NULL, NULL, '2026-01-05 01:13:44', '2026-01-05 00:13:44', '2026-01-05 00:13:44'),
(9, 15, 'BOOKING-X90AJSN', NULL, 350000.00, 'pending', NULL, 'BANK_TRANSFER', 'midtrans', 'BOOKING-X90AJSN', 'bri', '368452930666412213', NULL, NULL, NULL, '\"{\\\"status_code\\\":\\\"201\\\",\\\"status_message\\\":\\\"Success, Bank Transfer transaction is created\\\",\\\"transaction_id\\\":\\\"c0bcce4b-841d-42c2-905f-6954c2d06292\\\",\\\"order_id\\\":\\\"BOOKING-X90AJSN\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"350000.00\\\",\\\"currency\\\":\\\"IDR\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"transaction_time\\\":\\\"2026-01-05 14:15:32\\\",\\\"transaction_status\\\":\\\"pending\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"va_numbers\\\":[{\\\"bank\\\":\\\"bri\\\",\\\"va_number\\\":\\\"368452930666412213\\\"}],\\\"expiry_time\\\":\\\"2026-01-05 15:15:32\\\"}\"', NULL, NULL, '2026-01-05 00:15:32', NULL, NULL, '2026-01-05 01:15:32', '2026-01-05 00:15:32', '2026-01-05 00:15:32'),
(10, 16, 'BOOKING-JYWUI45', NULL, 400000.00, 'pending', NULL, 'BANK_TRANSFER', 'midtrans', 'BOOKING-JYWUI45', 'bri', '368450890178035429', NULL, NULL, NULL, '\"{\\\"status_code\\\":\\\"201\\\",\\\"status_message\\\":\\\"Success, Bank Transfer transaction is created\\\",\\\"transaction_id\\\":\\\"6273e442-c85a-4602-8325-764eff241143\\\",\\\"order_id\\\":\\\"BOOKING-JYWUI45\\\",\\\"merchant_id\\\":\\\"G155536845\\\",\\\"gross_amount\\\":\\\"400000.00\\\",\\\"currency\\\":\\\"IDR\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"transaction_time\\\":\\\"2026-01-05 16:40:58\\\",\\\"transaction_status\\\":\\\"pending\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"va_numbers\\\":[{\\\"bank\\\":\\\"bri\\\",\\\"va_number\\\":\\\"368450890178035429\\\"}],\\\"expiry_time\\\":\\\"2026-01-05 17:40:58\\\"}\"', NULL, NULL, '2026-01-05 02:40:58', NULL, NULL, '2026-01-05 03:40:58', '2026-01-05 02:40:59', '2026-01-05 02:40:59');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'auth_token', 'c7d4c5ae13470af35e1ed5de1581ea90282b7675777621cd6c46d38858c4b9f2', '[\"*\"]', NULL, NULL, '2025-12-18 20:27:32', '2025-12-18 20:27:32'),
(4, 'App\\Models\\User', 2, 'auth_token', '8ccb4a811dd2b64852e14d88f77a5c3b14518785660123d0a32ad3a36e9c2205', '[\"*\"]', '2026-01-05 00:16:21', NULL, '2025-12-18 20:41:41', '2026-01-05 00:16:21'),
(8, 'App\\Models\\User', 2, 'auth_token', 'ee84c2496bd9dc5252b504283cc1f8bf3bc6e9dd47438ad2e8e42d02d4a9c5bf', '[\"*\"]', '2025-12-19 00:43:38', NULL, '2025-12-19 00:40:57', '2025-12-19 00:43:38'),
(9, 'App\\Models\\User', 2, 'auth_token', '0acf609a96fe2556a02b28b7f1c74e146054dc87395d0a95aecd489176da5b03', '[\"*\"]', '2025-12-19 00:46:51', NULL, '2025-12-19 00:46:38', '2025-12-19 00:46:51'),
(10, 'App\\Models\\User', 2, 'auth_token', '065d90d2f09c5ecdc840a6bca392ae56c1190e8b4e10f3fafb6a3c84e0b10ee8', '[\"*\"]', '2025-12-19 01:18:03', NULL, '2025-12-19 00:50:28', '2025-12-19 01:18:03'),
(11, 'App\\Models\\User', 2, 'auth_token', '690581168fcc87683a3c0a717e1f19f67d0b03282c379ef63bf90a70aebff286', '[\"*\"]', '2025-12-21 20:27:57', NULL, '2025-12-19 01:21:37', '2025-12-21 20:27:57'),
(14, 'App\\Models\\User', 2, 'auth_token', 'e79c53289be30bfc2cbce8a754141d001d96afe30d24e1ae179c203841e15780', '[\"*\"]', '2025-12-19 02:40:52', NULL, '2025-12-19 02:40:51', '2025-12-19 02:40:52'),
(16, 'App\\Models\\User', 2, 'auth_token', '9164b59095c50b08f87b5756bfe87010bd10312d99026202dcc1814e2a0fa624', '[\"*\"]', '2025-12-21 20:31:50', NULL, '2025-12-21 19:44:46', '2025-12-21 20:31:50'),
(17, 'App\\Models\\User', 2, 'auth_token', '9019f9eae0d67195cd74c9731a7af1b713510ea5fd527e54f61efad5aa4f59c0', '[\"*\"]', '2025-12-21 20:54:28', NULL, '2025-12-21 20:43:18', '2025-12-21 20:54:28'),
(19, 'App\\Models\\User', 2, 'auth_token', '4bfb4f9bd3973bc4914846171bcfe97a7373350a1ec937476ba5d750279f571a', '[\"*\"]', '2026-01-05 02:40:57', NULL, '2026-01-05 01:57:05', '2026-01-05 02:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `promo_layanans`
--

CREATE TABLE `promo_layanans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `layanan_id` bigint(20) UNSIGNED NOT NULL,
  `diskon_persen` tinyint(3) UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('LmLR9kqChFKgmTJzwoYxB9JaUO2NrmYMJ1XydfNS', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoieFBCRHQwdGlZRW5ES3htNEFzRm1YR1pDdTdoelRXdFpOT2pVdnZaVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sYXlhbmFucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkd3FCLjdKNi91Y1FLSXFhRXV2aWFWZUY3OEVNVmMzWk9ra1pGUEZGUzZqTG1DMEJrYXRYTm0iO3M6NjoidGFibGVzIjthOjM6e3M6NDA6ImU2NDQ4MzNmNGU0ZTA4NzEyMzE1ZGE3MWIzM2ZhY2QyX2NvbHVtbnMiO2E6Njp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6MTI6Ik5hbWEgTGVuZ2thcCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiZW1haWwiO3M6NToibGFiZWwiO3M6NToiRW1haWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjc6Im5vX3RlbHAiO3M6NToibGFiZWwiO3M6MTI6Ik5vbWVyIFRlbGZvbiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoicm9sZSI7czo1OiJsYWJlbCI7czo1OiJQZXJhbiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiZTYwMTMyNzU0MDVmNDM0MWZhZTI4MzY1NDM3ZGZjNGJfY29sdW1ucyI7YTozOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czoyMToiTmFtYSBLYXRlZ29yaSBMYXlhbmFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiQ3JlYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiVXBkYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiIzMDA3MjU2ZDVhMjk3ZmFmMWFmODU2NmIwMzliYzRmM19jb2x1bW5zIjthOjY6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoia2F0ZWdvcmkubmFtZSI7czo1OiJsYWJlbCI7czo4OiJLYXRlZ29yaSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJoYXJnYSI7czo1OiJsYWJlbCI7czo5OiJIYXJnYShScCkiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE0OiJlc3RpbWFzaV9tZW5pdCI7czo1OiJsYWJlbCI7czoxNToiRXN0aW1hc2kobWVuaXQpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiQ3JlYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiVXBkYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX19czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1768912644);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `no_telp`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$wqB.7J6/ucQKIqaEuviaVeF78EMVc3ZOkkZFPFFS6jLmC0BkatXNm', NULL, 'admin', NULL, '2025-12-18 20:19:59', '2025-12-18 20:19:59'),
(2, 'fremas2', 'fremas@gmail.com', NULL, '$2y$12$UTC8rwPnP5JBu4mKJ.rypeVWPElLFhJKts8QyMNWYSPHKnr.PRVwC', '12345648979', 'customer', NULL, '2025-12-18 20:27:32', '2026-01-20 05:35:03'),
(3, 'tes', 'tes@gmail.com', NULL, '$2y$12$Dhr3OcR8uOT58gZfwEBhNOPW2KggzbXKpNaI4peMVATgJqxVDmiXa', '123', 'customer', NULL, '2026-01-12 01:28:06', '2026-01-12 01:28:06'),
(4, 'tes notif', 'notif@gmail.com', NULL, '$2y$12$RNcYB430fiokk34qXbd4be2fgXEU7kxeAn.ZQh0JvrGef621URgIe', '12231', 'customer', NULL, '2026-01-12 01:28:48', '2026-01-12 01:28:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_details_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_details_layanan_id_foreign` (`layanan_id`);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_operasionals`
--
ALTER TABLE `jadwal_operasionals`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `kategori_layanans`
--
ALTER TABLE `kategori_layanans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layanans`
--
ALTER TABLE `layanans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `layanans_kategori_id_foreign` (`kategori_id`);

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
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembayarans_order_id_unique` (`order_id`),
  ADD KEY `pembayarans_order_id_index` (`order_id`),
  ADD KEY `pembayarans_booking_id_index` (`booking_id`),
  ADD KEY `pembayarans_transaction_status_index` (`transaction_status`),
  ADD KEY `pembayarans_payment_type_index` (`payment_type`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `promo_layanans`
--
ALTER TABLE `promo_layanans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promo_layanans_layanan_id_foreign` (`layanan_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_operasionals`
--
ALTER TABLE `jadwal_operasionals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_layanans`
--
ALTER TABLE `kategori_layanans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `layanans`
--
ALTER TABLE `layanans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `promo_layanans`
--
ALTER TABLE `promo_layanans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD CONSTRAINT `booking_details_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_details_layanan_id_foreign` FOREIGN KEY (`layanan_id`) REFERENCES `layanans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `layanans`
--
ALTER TABLE `layanans`
  ADD CONSTRAINT `layanans_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_layanans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promo_layanans`
--
ALTER TABLE `promo_layanans`
  ADD CONSTRAINT `promo_layanans_layanan_id_foreign` FOREIGN KEY (`layanan_id`) REFERENCES `layanans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

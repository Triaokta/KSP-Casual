-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2025 at 01:53 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suratv1`
--

-- --------------------------------------------------------

--
-- Table structure for table `classifications`
--

CREATE TABLE `classifications` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classifications`
--

INSERT INTO `classifications` (`id`, `code`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, '0', 'ADMIN', 'Hanya untuk Admin', '2025-06-23 05:03:06', '2025-06-23 05:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE `configs` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configs`
--

INSERT INTO `configs` (`id`, `code`, `value`, `created_at`, `updated_at`) VALUES
(1, 'default_password', 'admin', NULL, NULL),
(2, 'page_size', '50', NULL, NULL),
(3, 'app_name', 'Aplikasi Surat Menyurat', NULL, NULL),
(4, 'institution_name', '404nfid', NULL, NULL),
(5, 'institution_address', 'Jl. Padat Karya', NULL, NULL),
(6, 'institution_phone', '082121212121', NULL, NULL),
(7, 'institution_email', 'admin@admin.com', NULL, NULL),
(8, 'language', 'id', NULL, NULL),
(9, 'pic', 'M. Iqbal Effendi', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `directorate_id` bigint UNSIGNED DEFAULT NULL,
  `division_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `directorate_id`, `division_id`, `created_at`, `updated_at`) VALUES
(1, 'Corporate Secretary', 1, NULL, NULL, NULL),
(2, 'Legal & Compliance', 1, NULL, NULL, NULL),
(3, 'Internal Audit', 1, NULL, NULL, NULL),
(4, 'Business Development', 1, NULL, NULL, NULL),
(5, 'Engineering Planning', 1, NULL, NULL, '2025-07-21 03:55:13'),
(6, 'Project Control', 2, NULL, NULL, NULL),
(7, 'Security Fire & SHE Manager', 2, NULL, NULL, NULL),
(8, 'Finance', 3, NULL, NULL, NULL),
(9, 'Accounting', 3, NULL, NULL, NULL),
(10, 'Procurement', 3, NULL, NULL, NULL),
(11, 'IT & Management System', 3, NULL, NULL, NULL),
(12, 'Human Capital', 3, NULL, NULL, NULL),
(13, 'Marketing Industrial Estate & Housing', 2, 1, NULL, NULL),
(14, 'Industrial Estate & Housing', 2, 1, NULL, NULL),
(15, 'Building Management & Office Rent', 1, 1, NULL, '2025-07-21 03:55:13'),
(16, 'Real Estate', 2, 1, NULL, NULL),
(17, 'Golf & Sport Center Manager', 2, 1, NULL, NULL),
(18, 'Executive Marketing & Sales Hotel', 2, 2, NULL, NULL),
(19, 'Front Office', 2, 2, NULL, NULL),
(20, 'Housekeeping', 2, 2, NULL, NULL),
(21, 'Food & Beverage', 2, 2, NULL, NULL),
(22, 'Executive Chef', 2, 2, NULL, NULL),
(23, 'Engineering', 2, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `directorates`
--

CREATE TABLE `directorates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `directorates`
--

INSERT INTO `directorates` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'President', NULL, NULL),
(2, 'Operation', NULL, NULL),
(3, 'Human Capital & Finance', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `directorate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `directorate_id`, `created_at`, `updated_at`) VALUES
(1, 'Commercial Property', 2, NULL, NULL),
(2, 'Hotel', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor Induk Karyawan',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama',
  `address` text COLLATE utf8mb4_unicode_ci COMMENT 'Alamat',
  `nik_ktp` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'No KTP',
  `npwp` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NPWP',
  `no_kk` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'No Kartu Keluarga',
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `name`, `address`, `nik_ktp`, `npwp`, `no_kk`, `department_id`, `is_active`, `created_at`, `updated_at`) VALUES
(5, '0002', 'Dewi Director', 'serang', '3601234567891012', '1234567891011122', '3601234567891013', 9, 1, '2025-07-09 03:26:41', '2025-07-09 03:26:41'),
(6, '0001', 'Arief Assistant', 'cilegon', '3601234567891011', '1234567891011121', '3601234567891012', 21, 1, '2025-07-09 04:08:49', '2025-07-09 10:21:05'),
(52, '0003', 'Dedi Karyawan', 'serang', '3600987654321001', '1234567891011123', '3600987654321002', 15, 1, '2025-07-25 02:36:13', '2025-07-25 02:37:01'),
(53, '0004', 'Budi Karyawan', 'bandung', '3600987689764283', '1234567891112131', '3600987654321201', 5, 0, '2025-07-25 02:36:13', '2025-07-25 02:36:13');

-- --------------------------------------------------------

--
-- Table structure for table `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatans`
--

INSERT INTO `jabatans` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Director', NULL, NULL),
(2, 'Assistant Director', NULL, NULL),
(3, 'General Manager', NULL, NULL),
(4, 'Executive Assistant', NULL, NULL),
(5, 'Manager', NULL, NULL),
(6, 'Superintendent', NULL, NULL),
(7, 'Senior Engineer', NULL, NULL),
(8, 'Senior Officer', NULL, NULL),
(9, 'Supervisor', NULL, NULL),
(10, 'Engineer', NULL, NULL),
(11, 'Officer', NULL, NULL),
(12, 'Foreman', NULL, NULL),
(13, 'Junior Engineer', NULL, NULL),
(14, 'Junior Officer', NULL, NULL),
(15, 'Hotel Staff', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2022_12_05_081849_create_configs_table', 1),
(7, '2022_12_05_083409_create_letter_statuses_table', 1),
(8, '2022_12_05_083945_create_classifications_table', 1),
(9, '2022_12_05_084544_create_letters_table', 1),
(10, '2022_12_05_092303_create_dispositions_table', 1),
(11, '2022_12_05_093329_create_attachments_table', 1),
(12, '2025_06_26_035947_create_surat_pengajuan_training_example_table', 2),
(13, '2025_06_30_141517_create_directorates_table', 3),
(14, '2025_06_30_141601_create_departments_table', 3),
(15, '2025_06_30_141613_create_jabatans_table', 3),
(17, '2025_07_01_112438_create_training_participants_table', 5),
(20, '2025_07_01_135025_add_directorate_id_to_users_table', 7),
(21, '2025_07_01_112419_create_surat_pengajuan_pelatihan_table', 8),
(22, '2025_07_07_115052_create_divisions_table', 9),
(23, '2025_07_07_115138_update_users_and_departments_with_division_and_jabatan_full', 10),
(24, '2025_07_07_172406_add_signature_and_paraf_to_users_table', 11),
(26, '2025_07_01_112446_create_surat_pengajuan_training_signatures_table', 12),
(27, '2025_07_08_095507_create_user_signatures_table', 12),
(28, '2025_07_08_153505_create_employees_table', 13),
(29, '2025_07_09_103923_add_status_to_employees_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `registration_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `signature_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paraf_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jabatan_id` bigint UNSIGNED DEFAULT NULL,
  `jabatan_full` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `directorate_id` bigint UNSIGNED DEFAULT NULL,
  `division_id` bigint UNSIGNED DEFAULT NULL,
  `superior_id` bigint UNSIGNED DEFAULT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `registration_id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `phone`, `address`, `signature_path`, `paraf_path`, `role`, `is_active`, `profile_picture`, `remember_token`, `created_at`, `updated_at`, `jabatan_id`, `jabatan_full`, `department_id`, `directorate_id`, `division_id`, `superior_id`, `nik`) VALUES
(1, 'ADM01', 'Administrator', 'admin@admin.com', '2025-06-23 04:55:06', '$2y$10$TRhzLAV1wP.IjrpLAe6T6eeg2uw9J3fNwlc/xF/KlZklkeWpcKSd6', NULL, NULL, '082121212121', NULL, NULL, NULL, 'admin', 1, NULL, 'SeThhMyF9CDmWNwfzbyof8gGtKilVvPN2tADE0M0OAs6QnHWa74C1zIMiJfU', '2025-06-23 04:55:06', '2025-06-23 04:55:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'ADIR001', 'Arief Assistant', 'ariefasisten@gmail.com', NULL, '$2y$10$BhayvALSwTi.vUXmICMK9eYkNoZCYzCQubfU7zwSrKN8z8MZ.9rHO', NULL, NULL, '08111111002', 'Jl. HC No.1', NULL, NULL, 'admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-08 05:31:45', 2, NULL, NULL, 1, NULL, NULL, '3173000000000002'),
(50, 'GM001', 'Gita GM CP', NULL, NULL, '$2y$10$.0rbg1iVgUFkDZKfec3uUOV2jIyujakIsd2iRCNs4z4BJjYuPY/YS', NULL, NULL, '08111111003', 'Jl. Komersial No.1', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 3, NULL, NULL, 2, 1, NULL, '3173000000000003'),
(51, 'GM002', 'Hendra GM Hotel', NULL, NULL, '$2y$10$y9ehnPn2JhnDWAD89WIH3eMfX0peWjQHg9F3rR3BV4xSB54WKMu7K', NULL, NULL, '08111111004', 'Jl. Hotel No.1', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 3, NULL, NULL, 2, 2, NULL, '3173000000000004'),
(52, 'EA001', 'Ela Exec Hotel', NULL, NULL, '$2y$10$ocz.K7VJKg/fHs3fZ8UIvuXZ3Zn01WmC7nr8c.3CBrQ9NXBj6bhXO', NULL, NULL, '08111111005', 'Jl. Hotel No.2', NULL, NULL, 'upper_staff', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 4, NULL, NULL, 2, 2, NULL, '3173000000000005'),
(53, 'MAN001', 'Manager Corporate Secretary', NULL, NULL, '$2y$10$BPXAUrYPstRm8dUHJORz2uAxATNF18CaFJDyJddrc4N8cOs1DRM46', NULL, NULL, '08111110101', 'Jl. Corporate Secretary', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 5, NULL, 1, 1, NULL, NULL, '31730000000000006'),
(54, 'MAN002', 'Manager Legal & Compliance', NULL, NULL, '$2y$10$o5Uug4Bejtvanp8BoQrx/ORISMYJm2KUzaHsA0hEZlMl37t970vle', NULL, NULL, '08111110102', 'Jl. Legal & Compliance', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 5, NULL, 2, 1, NULL, NULL, '31730000000000007'),
(55, 'MAN003', 'Manager Internal Audit', NULL, NULL, '$2y$10$p9uf4MHU1TtjS8aPTp3.W.tBPd25l5FZu9BhpAT40q17PmteZIF3K', NULL, NULL, '08111110103', 'Jl. Internal Audit', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 5, NULL, 3, 1, NULL, NULL, '31730000000000008'),
(56, 'MAN004', 'Manager Business Development', NULL, NULL, '$2y$10$LDMChuWGb8UPepZgWSCSN.AYq6PXD8wXy4xV8rqkXqYpWDA7fjtZ6', NULL, NULL, '08111110104', 'Jl. Business Development', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 5, NULL, 4, 1, NULL, NULL, '31730000000000009'),
(57, 'MAN005', 'Manager Engineering Planning', NULL, NULL, '$2y$10$4vrl7/Q8WSzD.1YJF3gJU.qilZ62Dj4FyPJ.s79dLh/2mJox1eTvC', NULL, NULL, '08111110105', 'Jl. Engineering Planning', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 5, NULL, 5, 2, NULL, NULL, '31730000000000010'),
(58, 'MAN006', 'Manager Project Control', NULL, NULL, '$2y$10$Tzb2vPDaz6UlbUu0FobaLOwNov0WCRkY4r56i1iZ.ryiqhY4cAcqy', NULL, NULL, '08111110106', 'Jl. Project Control', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 5, NULL, 6, 2, NULL, NULL, '31730000000000011'),
(59, 'MAN007', 'Manager Security Fire & SHE Manager', NULL, NULL, '$2y$10$kcdEjIvPognCLrnnAUr2seJbqwUD5UNiDukYygAbce8m/yG5FGWxy', NULL, NULL, '08111110107', 'Jl. Security Fire & SHE Manager', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 5, NULL, 7, 2, NULL, NULL, '31730000000000012'),
(60, 'MAN008', 'Manager Finance', NULL, NULL, '$2y$10$XbRfK1th2hr6iztyfFbCQO0M/QvXdq1QHiF6zett0TU9qjJ/dkv5q', NULL, NULL, '08111110108', 'Jl. Finance', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:17', '2025-07-07 06:13:17', 5, NULL, 8, 3, NULL, NULL, '31730000000000013'),
(61, 'MAN009', 'Manager Accounting', 'asdadsadas@gmail.com', NULL, '$2y$10$Jjbt8kRVLJ.Huv48DUx8sO.AAK/J4CNagow/9KonICWM15VYF9tyO', NULL, NULL, '08111110109', 'Jl. Accounting', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 07:56:08', 5, NULL, 9, 3, NULL, NULL, '31730000000000014'),
(62, 'MAN010', 'Manager Procurement', NULL, NULL, '$2y$10$Lap8KDPWENhEYj2dtpsRLef2zZVMMdnqG4canekeYxEQz.D1sPnNW', NULL, NULL, '08111110110', 'Jl. Procurement', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 10, 3, NULL, NULL, '31730000000000015'),
(63, 'MAN011', 'Manager IT & Management System', NULL, NULL, '$2y$10$NlxAP7IgUjYfLn3wfWmDu.1HTHkLHSG2k8N5HaTRBXMqYEjg/ms42', NULL, NULL, '08111110111', 'Jl. IT & Management System', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 11, 3, NULL, NULL, '31730000000000016'),
(64, 'MAN012', 'Manager Human Capital', NULL, NULL, '$2y$10$sdP3TNYsN33lHnlwK8l0YeuMVbOABwn1uME72dQO.UBpDZU.JS1Oe', NULL, NULL, '08111110112', 'Jl. Human Capital', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 12, 3, NULL, NULL, '31730000000000017'),
(65, 'MAN013', 'Manager Marketing Industrial Estate & Housing', NULL, NULL, '$2y$10$zPwHqiiSDuNgyomJ8DNR8e8pdKJWOxT/9aJFkfpOsSrISaB7Hsdn6', NULL, NULL, '08111110113', 'Jl. Marketing Industrial Estate & Housing', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 13, 2, 1, NULL, '31730000000000018'),
(66, 'MAN014', 'Manager Industrial Estate & Housing', NULL, NULL, '$2y$10$jO/13m9za4icdXdsxF0/wOz.H16UhtGlentUFIDtPBNs6y4zTQl1i', NULL, NULL, '08111110114', 'Jl. Industrial Estate & Housing', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 14, 2, 1, NULL, '31730000000000019'),
(67, 'MAN015', 'Manager Building Management & Office Rent', NULL, NULL, '$2y$10$NrkyOgOhtg0n2IPKiMvH/O4qlE2hZ.xl.acIo6uxwC.haQzHpg1bi', NULL, NULL, '08111110115', 'Jl. Building Management & Office Rent', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 15, 2, 1, NULL, '31730000000000020'),
(68, 'MAN016', 'Manager Real Estate', NULL, NULL, '$2y$10$O5cjG7HmtjFCF1TI7hrmN.iIysgw/vj9DNiOLKDA.VrrcN06Q03yy', NULL, NULL, '08111110116', 'Jl. Real Estate', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 16, 2, 1, NULL, '31730000000000021'),
(69, 'MAN017', 'Manager Golf & Sport Center Manager', NULL, NULL, '$2y$10$A5sTYwa2Z9cxN.DPw3H63enco5EcAd4q9fw91KD0jLWQN/tReYwEa', NULL, NULL, '08111110117', 'Jl. Golf & Sport Center Manager', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 17, 2, 1, NULL, '31730000000000022'),
(70, 'MAN018', 'Manager Executive Marketing & Sales Hotel', NULL, NULL, '$2y$10$abZZs2jq3IrKMoVWVTPsM.6B5.ilb1TIs3r0FESPYfoRcY7l78r8.', NULL, NULL, '08111110118', 'Jl. Executive Marketing & Sales Hotel', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 18, 2, 2, NULL, '31730000000000023'),
(71, 'MAN019', 'Manager Front Office', 'managerfrontoffice@manager.com', NULL, '$2y$10$JRVFx8HaQmTdkWzdsN9q8O/GPLkv2CntW9c0JndAzhccNAOQrolYq', NULL, NULL, '08111110119', 'Jl. Front Offices', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 09:08:36', 5, NULL, 19, 2, NULL, NULL, '31730000000000024'),
(72, 'MAN020', 'Manager Housekeeping', NULL, NULL, '$2y$10$6Y5DWYpZA.CpuHCBp2v0DOyZO3KT6GSHS/tL2a8jYknb4FYY19SGe', NULL, NULL, '08111110120', 'Jl. Housekeeping', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 20, 2, 2, NULL, '31730000000000025'),
(73, 'MAN021', 'Manager Food & Beverage', NULL, NULL, '$2y$10$4Cf4SI.b8IS4kNIKJlxBRepqMZE1qpVH0rxmPGmMdd1qZ29GhhUgK', NULL, NULL, '08111110121', 'Jl. Food & Beverage', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 21, 2, 2, NULL, '31730000000000026'),
(74, 'MAN022', 'Manager Executive Chef', NULL, NULL, '$2y$10$tHJ2JvTxWgEJGk/EKIP7quiYsYKR2/liuuGFZvzc7cpAZrp1HHmq2', NULL, NULL, '08111110122', 'Jl. Executive Chef', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 22, 2, 2, NULL, '31730000000000027'),
(75, 'MAN023', 'Manager Engineering', NULL, NULL, '$2y$10$qEbq2obOJAsBI7cQReqz5unfF33/BCe/AsNRyPboBQsR//0NSML72', NULL, NULL, '08111110123', 'Jl. Engineering', NULL, NULL, 'department_admin', 1, NULL, NULL, '2025-07-07 06:13:18', '2025-07-07 06:13:18', 5, NULL, 23, 2, 2, NULL, '31730000000000028');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classifications`
--
ALTER TABLE `classifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classifications_code_unique` (`code`);

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `configs_code_unique` (`code`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`),
  ADD KEY `departments_directorate_id_foreign` (`directorate_id`),
  ADD KEY `departments_division_id_foreign` (`division_id`);

--
-- Indexes for table `directorates`
--
ALTER TABLE `directorates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `directorates_name_unique` (`name`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `divisions_name_unique` (`name`),
  ADD KEY `divisions_directorate_id_foreign` (`directorate_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `employees_nik_ktp_unique` (`nik_ktp`),
  ADD UNIQUE KEY `employees_npwp_unique` (`npwp`),
  ADD KEY `employees_department_id_foreign` (`department_id`);

--
-- Indexes for table `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jabatans_name_unique` (`name`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_directorate_id_foreign` (`directorate_id`),
  ADD KEY `users_division_id_foreign` (`division_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classifications`
--
ALTER TABLE `classifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `configs`
--
ALTER TABLE `configs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `directorates`
--
ALTER TABLE `directorates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_directorate_id_foreign` FOREIGN KEY (`directorate_id`) REFERENCES `directorates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `departments_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `divisions`
--
ALTER TABLE `divisions`
  ADD CONSTRAINT `divisions_directorate_id_foreign` FOREIGN KEY (`directorate_id`) REFERENCES `directorates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_directorate_id_foreign` FOREIGN KEY (`directorate_id`) REFERENCES `directorates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

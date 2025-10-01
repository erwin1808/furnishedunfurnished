-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2025 at 08:02 AM
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
-- Database: `furnished_unfurnished`
--

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `purpose` enum('registration','password_reset','login','transaction') DEFAULT 'registration',
  `is_used` tinyint(1) DEFAULT 0,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `otp_code`, `purpose`, `is_used`, `expires_at`, `created_at`) VALUES
(1, 3, '325731', 'registration', 1, '2025-10-01 07:27:26', '2025-10-01 05:22:26'),
(2, 3, '868351', 'registration', 1, '2025-10-01 07:28:25', '2025-10-01 05:23:25'),
(3, 3, '237389', 'registration', 1, '2025-10-01 07:30:52', '2025-10-01 05:25:52'),
(4, 4, '995956', 'registration', 1, '2025-10-01 07:31:36', '2025-10-01 05:26:36'),
(5, 3, '432703', 'registration', 1, '2025-10-01 07:32:42', '2025-10-01 05:27:42'),
(6, 3, '174097', 'registration', 1, '2025-10-01 07:35:49', '2025-10-01 05:30:49'),
(7, 3, '812033', 'registration', 1, '2025-10-01 07:38:38', '2025-10-01 05:33:38'),
(8, 3, '993340', 'registration', 1, '2025-10-01 07:39:03', '2025-10-01 05:34:03'),
(9, 3, '295548', 'registration', 1, '2025-10-01 07:40:14', '2025-10-01 05:35:14'),
(10, 3, '238745', 'registration', 1, '2025-10-01 07:40:41', '2025-10-01 05:35:41'),
(11, 3, '997411', 'registration', 1, '2025-10-01 07:41:23', '2025-10-01 05:36:23'),
(12, 3, '168384', 'registration', 1, '2025-10-01 07:47:26', '2025-10-01 05:42:26'),
(13, 3, '847374', 'registration', 1, '2025-10-01 07:55:55', '2025-10-01 05:50:55'),
(14, 3, '821107', 'registration', 1, '2025-10-01 07:56:53', '2025-10-01 05:51:53'),
(15, 3, '838634', 'registration', 1, '2025-10-01 08:00:15', '2025-10-01 05:55:15'),
(16, 3, '226176', 'registration', 1, '2025-10-01 08:03:01', '2025-10-01 05:58:01'),
(17, 3, '103046', 'registration', 1, '2025-10-01 08:04:58', '2025-10-01 05:59:58'),
(18, 3, '450708', 'registration', 1, '2025-10-01 08:06:31', '2025-10-01 06:01:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_verified` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(20) NOT NULL,
  `user_type` enum('tenant','landlord','admin') DEFAULT 'tenant',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `otp_verified`, `phone`, `user_type`, `created_at`, `updated_at`, `account_number`) VALUES
(1, 'daw', 'dawd', 'a@gmail.com', 0, '123123', 'tenant', '2025-09-29 00:39:06', '2025-09-29 00:39:06', NULL),
(2, 'the', 'dawd', 'ada@gmail.com', 0, '123123', 'tenant', '2025-10-01 05:11:30', '2025-10-01 05:11:30', '20250001'),
(3, '', '', 'erwinnrs31@gmail.com', 1, '', 'tenant', '2025-10-01 05:22:26', '2025-10-01 06:01:56', '20250002'),
(4, '', '', 'erwinnrs31@gmai.com', 0, '', 'tenant', '2025-10-01 05:26:35', '2025-10-01 05:26:35', '20250003');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `account_number` (`account_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

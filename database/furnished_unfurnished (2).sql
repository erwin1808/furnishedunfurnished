-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 06:43 PM
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
(4, 4, '995956', 'registration', 1, '2025-10-01 07:31:36', '2025-10-01 05:26:36'),
(19, 5, '166062', 'registration', 1, '2025-10-01 09:14:13', '2025-10-01 07:09:13'),
(20, 6, '508084', 'registration', 0, '2025-10-01 09:59:39', '2025-10-01 07:54:39'),
(21, 7, '947392', 'registration', 0, '2025-10-07 06:37:34', '2025-10-07 04:32:34'),
(23, 8, '126336', 'registration', 0, '2025-10-07 06:50:21', '2025-10-07 04:45:21'),
(26, 11, '115267', 'registration', 1, '2025-10-07 08:45:12', '2025-10-07 06:40:12'),
(27, 8, '116982', 'registration', 0, '2025-10-07 08:46:19', '2025-10-07 06:41:19'),
(28, 11, '915986', 'registration', 1, '2025-10-07 08:54:41', '2025-10-07 06:49:41'),
(29, 11, '970416', 'registration', 1, '2025-10-07 08:55:24', '2025-10-07 06:50:24'),
(30, 11, '663895', 'registration', 1, '2025-10-07 08:55:51', '2025-10-07 06:50:51'),
(31, 11, '578367', 'registration', 1, '2025-10-07 09:08:36', '2025-10-07 07:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `intake_id` int(11) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `beds` int(11) DEFAULT 0,
  `baths` int(11) DEFAULT 0,
  `bedrooms` int(12) DEFAULT NULL,
  `guests` int(12) DEFAULT NULL,
  `levels` int(11) DEFAULT 1,
  `property_type` varchar(100) DEFAULT NULL,
  `place_access_type` enum('Entire place','Private room','Shared room') DEFAULT NULL,
  `available_on` date DEFAULT NULL,
  `casita_inlaw_suite` enum('Yes','No') DEFAULT 'No',
  `furnishing` enum('Fully Furnished','Partially Furnished','Unfurnished') DEFAULT NULL,
  `bed_sizes` varchar(255) DEFAULT NULL,
  `insurance_provides_kitchenwares` enum('Yes','No') DEFAULT 'No',
  `appliances` varchar(255) DEFAULT NULL,
  `parking_spaces` int(11) DEFAULT 0,
  `parking_description` varchar(255) DEFAULT NULL,
  `utilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`utilities`)),
  `pets_allowed` enum('Yes','No','Maybe') DEFAULT NULL,
  `monthly_rent` decimal(10,2) DEFAULT NULL,
  `security_deposit` decimal(10,2) DEFAULT NULL,
  `move_in_fees` enum('Cleaning Fee','Pet Fee','Other') DEFAULT NULL,
  `application_needed` enum('Yes','No') DEFAULT 'No',
  `renters_insurance_required` enum('Yes','No') DEFAULT 'No',
  `application_fee` decimal(10,2) DEFAULT NULL,
  `ntv_days` int(11) DEFAULT 30,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `description` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `property_code` varchar(25) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`intake_id`, `account_number`, `street`, `barangay`, `city`, `province`, `postal_code`, `beds`, `baths`, `bedrooms`, `guests`, `levels`, `property_type`, `place_access_type`, `available_on`, `casita_inlaw_suite`, `furnishing`, `bed_sizes`, `insurance_provides_kitchenwares`, `appliances`, `parking_spaces`, `parking_description`, `utilities`, `pets_allowed`, `monthly_rent`, `security_deposit`, `move_in_fees`, `application_needed`, `renters_insurance_required`, `application_fee`, `ntv_days`, `photos`, `description`, `date_created`, `property_code`, `latitude`, `longitude`) VALUES
(7, '20250004', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'Room', NULL, NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, NULL, '2025-10-01 09:22:43', 'D99C39', NULL, NULL),
(11, '20250008', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'House', NULL, NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, NULL, '2025-10-07 07:17:35', '506905', NULL, NULL),
(12, '20250008', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'House', NULL, NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, NULL, '2025-10-07 07:17:46', 'EC9E33', NULL, NULL),
(13, '20250008', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'House', NULL, NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, NULL, '2025-10-07 07:18:39', 'D3D6F5', NULL, NULL),
(14, '20250008', '1', '1', 'binan', 'laguna', '4024', 0, 0, NULL, NULL, 1, 'Room', 'Entire place', NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, NULL, '2025-10-07 07:19:33', 'D22D91', NULL, NULL),
(15, '20250008', '1', '1', '1', '1', '1', 1, 1, 2, 2, 1, 'Room', 'Entire place', NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, NULL, '2025-10-07 07:19:41', '8CA58E', 14.33423467, -598.91721010),
(16, '20250008', '1', '1', '1', '1', '1', 0, 0, 1, 1, 1, 'Apartment', 'Private room', NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, NULL, '2025-10-07 15:09:35', '9C7C62', 0.00000000, 0.00000000);

-- --------------------------------------------------------

--
-- Table structure for table `property_amenities`
--

CREATE TABLE `property_amenities` (
  `id` int(11) NOT NULL,
  `property_code` varchar(50) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `amenity_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_amenities`
--

INSERT INTO `property_amenities` (`id`, `property_code`, `account_number`, `amenity_name`, `created_at`) VALUES
(157, '8CA58E', '20250008', 'Wifi', '2025-10-07 14:54:04'),
(158, '8CA58E', '20250008', 'TV', '2025-10-07 14:54:04'),
(159, '8CA58E', '20250008', 'Kitchen', '2025-10-07 14:54:04'),
(160, '8CA58E', '20250008', 'Washer', '2025-10-07 14:54:04'),
(161, '8CA58E', '20250008', 'Free parking on premises', '2025-10-07 14:54:04'),
(162, '8CA58E', '20250008', 'Paid parking on premises', '2025-10-07 14:54:04'),
(163, '8CA58E', '20250008', 'Air conditioning', '2025-10-07 14:54:04'),
(164, '8CA58E', '20250008', 'Dedicated workspace', '2025-10-07 14:54:04'),
(165, '8CA58E', '20250008', 'Pool', '2025-10-07 14:54:04'),
(166, '8CA58E', '20250008', 'Hot tub', '2025-10-07 14:54:04'),
(167, '8CA58E', '20250008', 'Patio', '2025-10-07 14:54:04'),
(168, '8CA58E', '20250008', 'BBQ grill', '2025-10-07 14:54:04'),
(169, '8CA58E', '20250008', 'Outdoor dining area', '2025-10-07 14:54:04'),
(170, '8CA58E', '20250008', 'Fire pit', '2025-10-07 14:54:04'),
(171, '8CA58E', '20250008', 'Pool table', '2025-10-07 14:54:04'),
(172, '8CA58E', '20250008', 'Indoor fireplace', '2025-10-07 14:54:04'),
(173, '8CA58E', '20250008', 'Piano', '2025-10-07 14:54:04'),
(174, '8CA58E', '20250008', 'Exercise equipment', '2025-10-07 14:54:04'),
(175, '8CA58E', '20250008', 'Lake access', '2025-10-07 14:54:04'),
(176, '8CA58E', '20250008', 'Beach access', '2025-10-07 14:54:04'),
(177, '8CA58E', '20250008', 'Ski-in/Ski-out', '2025-10-07 14:54:04'),
(178, '8CA58E', '20250008', 'Outdoor shower', '2025-10-07 14:54:04'),
(179, '8CA58E', '20250008', 'Smoke alarm', '2025-10-07 14:54:04'),
(180, '8CA58E', '20250008', 'First aid kit', '2025-10-07 14:54:04'),
(181, '8CA58E', '20250008', 'Fire extinguisher', '2025-10-07 14:54:04'),
(182, '8CA58E', '20250008', 'Carbon monoxide alarm', '2025-10-07 14:54:04'),
(183, '9C7C62', '20250008', 'Wifi', '2025-10-07 15:26:55'),
(184, '9C7C62', '20250008', 'Washer', '2025-10-07 15:26:55'),
(185, '9C7C62', '20250008', 'Dedicated workspace', '2025-10-07 15:26:55');

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` int(11) NOT NULL,
  `property_code` varchar(50) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_order` int(11) DEFAULT 0,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `account_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `otp_verified`, `phone`, `user_type`, `created_at`, `updated_at`, `account_number`, `password`) VALUES
(1, 'daw', 'dawd', 'a@gmail.com', 0, '123123', 'tenant', '2025-09-29 00:39:06', '2025-09-29 00:39:06', NULL, ''),
(2, 'the', 'dawd', 'ada@gmail.com', 0, '123123', 'tenant', '2025-10-01 05:11:30', '2025-10-01 05:11:30', '20250001', ''),
(4, '', '', 'erwinnrs31@gmai.com', 0, '', 'tenant', '2025-10-01 05:26:35', '2025-10-01 05:26:35', '20250003', ''),
(5, '', '', 'erwinnares1@gmail.com', 1, '', 'landlord', '2025-10-01 07:09:13', '2025-10-01 07:09:33', '20250004', ''),
(6, '', '', 'erwinnares23@gmail.com', 0, '', 'landlord', '2025-10-01 07:54:39', '2025-10-01 07:54:39', '20250005', ''),
(7, '', '', 'erwinnares2@gmail.com', 0, '', 'landlord', '2025-10-07 04:32:34', '2025-10-07 04:32:34', '20250006', ''),
(8, '', '', 'erwinnares31@gmail.com', 0, '', 'landlord', '2025-10-07 04:45:21', '2025-10-07 04:45:21', '20250007', ''),
(11, 'Erwin', 'Nares', 'erwinnrs31@gmail.com', 1, '09354241531', 'landlord', '2025-10-07 06:40:12', '2025-10-07 07:03:55', '20250008', '');

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
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`intake_id`),
  ADD UNIQUE KEY `idx_property_code` (`property_code`),
  ADD KEY `fk_users_account` (`account_number`);

--
-- Indexes for table `property_amenities`
--
ALTER TABLE `property_amenities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_amenity` (`property_code`,`amenity_name`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_code` (`property_code`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `intake_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `property_amenities`
--
ALTER TABLE `property_amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `fk_users_account` FOREIGN KEY (`account_number`) REFERENCES `users` (`account_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `property_images`
--
ALTER TABLE `property_images`
  ADD CONSTRAINT `property_images_ibfk_1` FOREIGN KEY (`property_code`) REFERENCES `property` (`property_code`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

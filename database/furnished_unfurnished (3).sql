-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 08:53 AM
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
  `description` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `property_code` varchar(25) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `property_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`intake_id`, `account_number`, `street`, `barangay`, `city`, `province`, `postal_code`, `beds`, `baths`, `bedrooms`, `guests`, `levels`, `property_type`, `place_access_type`, `available_on`, `casita_inlaw_suite`, `furnishing`, `bed_sizes`, `insurance_provides_kitchenwares`, `appliances`, `parking_spaces`, `parking_description`, `utilities`, `pets_allowed`, `monthly_rent`, `security_deposit`, `move_in_fees`, `application_needed`, `renters_insurance_required`, `application_fee`, `ntv_days`, `description`, `date_created`, `property_code`, `latitude`, `longitude`, `property_title`) VALUES
(7, '20250004', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'Room', NULL, NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, '2025-10-01 09:22:43', 'D99C39', NULL, NULL, ''),
(11, '20250008', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'House', NULL, NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, '2025-10-07 07:17:35', '506905', NULL, NULL, ''),
(12, '20250008', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'House', NULL, NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, '2025-10-07 07:17:46', 'EC9E33', NULL, NULL, ''),
(13, '20250008', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 'House', NULL, NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, '2025-10-07 07:18:39', 'D3D6F5', NULL, NULL, ''),
(14, '20250008', '1', '1', 'binan', 'laguna', '4024', 0, 0, NULL, NULL, 1, 'Room', 'Entire place', NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, '2025-10-07 07:19:33', 'D22D91', NULL, NULL, ''),
(15, '20250008', '1', '1', '1', '1', '1', 1, 1, 2, 2, 1, 'Room', 'Entire place', NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', NULL, 30, NULL, '2025-10-07 07:19:41', '8CA58E', 14.33423467, -598.91721010, ''),
(16, '20250008', '1', '1', '1', '1', '1', 0, 0, 1, 1, 1, 'Apartment', 'Private room', NULL, 'No', NULL, NULL, 'No', NULL, 0, NULL, NULL, NULL, 3333.00, NULL, NULL, 'No', 'No', NULL, 30, 'adada', '2025-10-07 15:09:35', '9C7C62', 0.00000000, 0.00000000, 'aaaaaaaaaaaaaa'),
(17, 'ACC-US-20251008-001', '1234 Sunset Blvd', NULL, 'Los Angeles', 'California', '90026', 3, 2, 3, 6, 2, 'House', 'Entire place', '2025-10-15', 'No', 'Fully Furnished', 'King, Queen', 'Yes', 'Refrigerator, Oven, Dishwasher, Washer, Dryer', 2, '2-car garage', NULL, 'Yes', 3200.00, 1000.00, 'Cleaning Fee', 'Yes', 'Yes', 100.00, 30, 'Beautiful 3-bedroom home in a quiet Los Angeles neighborhood, close to downtown and shopping centers.', '2025-10-08 05:17:56', 'PROP-LA-25017', 34.09280900, -118.32866100, 'Charming 3BR Home in Los Angeles'),
(18, 'ACC-US-20251008-002', '98 Ocean View Dr', NULL, 'San Diego', 'California', '92109', 2, 2, 2, 4, 1, 'Apartment', 'Entire place', '2025-10-20', 'No', 'Partially Furnished', 'Queen', 'No', 'Aircon, Refrigerator, Microwave', 1, 'Underground parking', NULL, 'Yes', 2800.00, 800.00, 'Cleaning Fee', 'Yes', 'Yes', 75.00, 30, 'Ocean-view 2-bedroom apartment near Pacific Beach, ideal for couples or small families.', '2025-10-08 05:17:56', 'PROP-SD-25018', 32.79700000, -117.25600000, 'Ocean View Apartment in San Diego'),
(19, 'ACC-US-20251008-003', '456 Elm Street', NULL, 'Austin', 'Texas', '73301', 4, 3, 4, 8, 2, 'House', 'Entire place', '2025-11-01', 'Yes', 'Fully Furnished', 'King, Twin', 'Yes', 'Refrigerator, Oven, Dishwasher, Dryer', 2, 'Attached garage', NULL, 'Maybe', 3500.00, 1200.00, 'Pet Fee', 'Yes', 'Yes', 120.00, 30, 'Spacious 4-bedroom family home with in-law suite and fenced yard in North Austin.', '2025-10-08 05:17:56', 'PROP-AUS-25019', 30.26720000, -97.74310000, 'Family Home with In-Law Suite'),
(20, 'ACC-US-20251008-004', '789 Pine Avenue', NULL, 'Seattle', 'Washington', '98101', 1, 1, 1, 2, 1, 'Condominium', 'Private room', '2025-11-05', 'No', 'Fully Furnished', 'Queen', 'No', 'Washer, Dryer, Refrigerator', 0, 'Street parking only', NULL, 'No', 1900.00, 500.00, 'Cleaning Fee', 'Yes', 'No', 50.00, 30, 'Modern 1-bedroom condo in downtown Seattle with skyline views.', '2025-10-08 05:17:56', 'PROP-SEA-25020', 47.60970000, -122.33310000, 'Downtown Seattle Condo'),
(21, 'ACC-US-20251008-005', '22 Maple Street', NULL, 'Boston', 'Massachusetts', '02108', 2, 1, 2, 3, 2, 'Townhouse', 'Entire place', '2025-11-10', 'No', 'Unfurnished', 'Double', 'No', 'Refrigerator, Oven', 1, 'Private driveway', NULL, 'Yes', 2700.00, 900.00, 'Cleaning Fee', 'Yes', 'Yes', 90.00, 30, 'Historic 2-bedroom townhouse in Beacon Hill, close to public transit.', '2025-10-08 05:17:56', 'PROP-BOS-25021', 42.36010000, -71.05890000, 'Beacon Hill Townhouse'),
(22, 'ACC-US-20251008-006', '1555 Broadway', NULL, 'New York', 'New York', '10036', 1, 1, 1, 2, 1, 'Studio', 'Entire place', '2025-11-15', 'No', 'Fully Furnished', 'Queen', 'No', 'Microwave, Mini Fridge, Aircon', 0, 'No parking', NULL, 'No', 2500.00, 700.00, 'Cleaning Fee', 'Yes', 'Yes', 85.00, 30, 'Compact and stylish studio apartment in Times Square, perfect for professionals.', '2025-10-08 05:17:56', 'PROP-NYC-25022', 40.75800000, -73.98550000, 'Times Square Studio Apartment'),
(23, 'ACC-US-20251008-007', '312 Oak Ridge Ln', NULL, 'Denver', 'Colorado', '80203', 3, 2, 3, 6, 1, 'House', 'Entire place', '2025-11-18', 'Yes', 'Fully Furnished', 'King, Twin', 'Yes', 'Refrigerator, Washer, Dryer, Oven', 2, 'Garage + Driveway', NULL, 'Yes', 3100.00, 1000.00, 'Cleaning Fee', 'Yes', 'Yes', 100.00, 30, 'Bright and cozy 3-bedroom home with mountain views in Denver.', '2025-10-08 05:17:56', 'PROP-DEN-25023', 39.73920000, -104.99030000, 'Cozy Home with Mountain View'),
(24, 'ACC-US-20251008-008', '2000 Bay Area Blvd', NULL, 'Houston', 'Texas', '77058', 2, 2, 2, 4, 1, 'Apartment', 'Entire place', '2025-12-01', 'No', 'Partially Furnished', 'Queen', 'No', 'Refrigerator, Oven, Microwave', 1, 'Reserved lot space', NULL, 'Maybe', 2400.00, 800.00, 'Cleaning Fee', 'Yes', 'Yes', 75.00, 30, 'Affordable 2-bedroom apartment near NASA and the Houston Bay Area.', '2025-10-08 05:17:56', 'PROP-HOU-25024', 29.76040000, -95.36980000, 'Bay Area Apartment'),
(25, 'ACC-US-20251008-009', '88 North Shore Dr', NULL, 'Chicago', 'Illinois', '60611', 2, 1, 2, 3, 1, 'Condominium', 'Private room', '2025-12-05', 'No', 'Fully Furnished', 'Queen', 'No', 'Refrigerator, Washer, Dryer', 0, 'Street parking only', NULL, 'Yes', 2700.00, 900.00, 'Cleaning Fee', 'Yes', 'Yes', 100.00, 30, 'Luxury condo near Lake Michigan with modern interiors and amenities.', '2025-10-08 05:17:56', 'PROP-CHI-25025', 41.87810000, -87.62980000, 'Lakefront Chicago Condo'),
(26, 'ACC-US-20251008-010', '555 Park Ave', NULL, 'New York', 'New York', '10022', 3, 2, 3, 5, 2, 'Penthouse', 'Entire place', '2025-12-10', 'No', 'Fully Furnished', 'King, Queen', 'Yes', 'Washer, Dryer, Dishwasher, Refrigerator', 1, 'Underground parking', NULL, 'No', 7500.00, 2000.00, 'Cleaning Fee', 'Yes', 'Yes', 150.00, 30, 'Premium penthouse in Park Avenue with stunning skyline views and private terrace.', '2025-10-08 05:17:56', 'PROP-NYC-25026', 40.76458051, -73.96470000, 'Luxury Park Avenue Penthouse');

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

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_code`, `image_path`, `image_order`, `uploaded_at`) VALUES
(33, 'PROP-NYC-25026', 'uploads/68e545d898830_1759856088.jpg', 1, '2025-10-07 16:54:48'),
(34, 'PROP-NYC-25026', 'uploads/68e545d8990df_1759856088.jpg', 2, '2025-10-07 16:54:48'),
(35, 'PROP-NYC-25022', 'uploads/68e545d899785_1759856088.jpg', 3, '2025-10-07 16:54:48'),
(36, 'PROP-HOU-25024', 'uploads/68e545d89a38f_1759856088.jpg', 4, '2025-10-07 16:54:48'),
(37, 'PROP-SD-25018', 'uploads/68e545d89a9e5_1759856088.jpg', 5, '2025-10-07 16:54:48'),
(38, 'PROP-CHI-25025', 'uploads/68e545d89afdf_1759856088.jpg', 6, '2025-10-07 16:54:48'),
(39, 'PROP-SEA-25020', 'uploads/68e545d89b56d_1759856088.jpg', 7, '2025-10-07 16:54:48');

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
(11, 'Erwin', 'Nares', 'erwinnrs31@gmail.com', 1, '09354241531', 'landlord', '2025-10-07 06:40:12', '2025-10-07 07:03:55', '20250008', ''),
(20, 'Ava', 'Clark', 'ava.clark@example.com', 1, '+1-213-555-0110', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:16:40', 'ACC-US-20251008-010', '$2y$10$examplehash10'),
(21, 'John', 'Smith', 'john.smith@example.com', 1, '+1-213-555-0101', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:16:43', 'ACC-US-20251008-001', '$2y$10$examplehash1'),
(22, 'Emily', 'Johnson', 'emily.johnson@example.com', 1, '+1-213-555-0102', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:16:47', 'ACC-US-20251008-002', '$2y$10$examplehash2'),
(23, 'Michael', 'Brown', 'michael.brown@example.com', 1, '+1-213-555-0103', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:16:49', 'ACC-US-20251008-003', '$2y$10$examplehash3'),
(24, 'Sarah', 'Davis', 'sarah.davis@example.com', 1, '+1-213-555-0104', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:16:51', 'ACC-US-20251008-004', '$2y$10$examplehash4'),
(25, 'David', 'Wilson', 'david.wilson@example.com', 1, '+1-213-555-0105', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:16:54', 'ACC-US-20251008-005', '$2y$10$examplehash5'),
(26, 'Olivia', 'Martinez', 'olivia.martinez@example.com', 1, '+1-213-555-0106', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:16:57', 'ACC-US-20251008-006', '$2y$10$examplehash6'),
(27, 'James', 'Anderson', 'james.anderson@example.com', 1, '+1-213-555-0107', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:16:59', 'ACC-US-20251008-007', '$2y$10$examplehash7'),
(28, 'Sophia', 'Thomas', 'sophia.thomas@example.com', 1, '+1-213-555-0108', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:17:04', 'ACC-US-20251008-008', '$2y$10$examplehash8'),
(29, 'Daniel', 'Harris', 'daniel.harris@example.com', 1, '+1-213-555-0109', 'landlord', '2025-10-08 05:16:17', '2025-10-08 05:17:02', 'ACC-US-20251008-009', '$2y$10$examplehash9');

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
  MODIFY `intake_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `property_amenities`
--
ALTER TABLE `property_amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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

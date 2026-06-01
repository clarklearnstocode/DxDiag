-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 01, 2026 at 06:18 AM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u367097290_estatebook`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_Id` int(11) NOT NULL,
  `Admin_Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `totp_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `totp_secret` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_Id`, `Admin_Name`, `Email`, `Username`, `Password`, `totp_enabled`, `totp_secret`) VALUES
(1, 'Clark Kenneth Sabordo', 'admin@estatebook.com', 'clark_admin', 'admin123', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `Booking_Id` int(11) NOT NULL,
  `User_Id` int(11) DEFAULT NULL,
  `Property_Id` int(11) DEFAULT NULL,
  `Booking_Date` date NOT NULL,
  `Payment_Id` int(11) DEFAULT NULL,
  `Reservation_Status` varchar(50) DEFAULT 'Pending',
  `Check_In` date DEFAULT NULL,
  `Check_In_Time` time DEFAULT NULL,
  `Check_Out` date DEFAULT NULL,
  `Check_Out_Time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_Id` int(11) NOT NULL,
  `Payment_Date` date NOT NULL DEFAULT curdate(),
  `Payment_Method` varchar(50) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `Property_Id` int(11) NOT NULL,
  `Property_Name` varchar(255) NOT NULL,
  `Property_location` varchar(255) NOT NULL,
  `Property_capacity` int(11) NOT NULL,
  `Property_rate` decimal(10,2) NOT NULL,
  `Property_Description` text DEFAULT NULL,
  `Property_size` int(11) DEFAULT NULL,
  `Property_bathrooms` int(11) DEFAULT NULL,
  `Status` varchar(50) DEFAULT 'Available',
  `Has_pool` tinyint(1) DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`Property_Id`, `Property_Name`, `Property_location`, `Property_capacity`, `Property_rate`, `Property_Description`, `Property_size`, `Property_bathrooms`, `Status`, `Has_pool`, `image_path`) VALUES
(1, 'Luxury Glass House', 'Don Salvador Benedicto', 8, 12500.00, NULL, 350, 3, 'Available', 1, 'villa1.png'),
(2, 'Modern Infinity Villa', 'Bacolod City', 10, 15000.00, NULL, 450, 4, 'Available', 1, 'villa2.png'),
(3, 'Tropical Garden Estate', 'Talisay City', 6, 9500.00, NULL, 280, 2, 'Available', 0, 'villa3.png');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL COMMENT '1–5 overall rating',
  `comment` text NOT NULL DEFAULT '',
  `cat_cleanliness` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = not rated',
  `cat_comfort` tinyint(1) NOT NULL DEFAULT 0,
  `cat_location` tinyint(1) NOT NULL DEFAULT 0,
  `cat_value` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `totp_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `totp_secret` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_Id`, `Name`, `Phone`, `Email`, `Username`, `Password`, `profile_image`, `totp_enabled`, `totp_secret`) VALUES
(1, 'Clark Kenneth Sabordo', '09123456789', 'clark@gmail.com', 'Clarke', '$2y$10$ZFCFU.7PLtIh.Du9DZy8wugVExny77MjcVUOLfJJYG2e86YBvtljG', 'user_1_1780238554.jpg', 1, 'BWOCDJ7HO3B5BA3IWWUU6BBSINPD766E'),
(2, 'lance libuha', '0961 468 5409', 'libunalanceeduard@gmail.com', 'lancelot', '$2y$10$aFhqynfTf63WsD2NWWkPQuNXBLgC89lw99Rm1eSiBJ8ur0mtIVN/.', NULL, 1, 'IMK3MBBAEMTYARK7D5R5BHCMVFSDDWM5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_Id`),
  ADD UNIQUE KEY `uq_admin_email` (`Email`),
  ADD UNIQUE KEY `uq_admin_username` (`Username`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`Booking_Id`),
  ADD KEY `fk_booking_user` (`User_Id`),
  ADD KEY `fk_booking_property` (`Property_Id`),
  ADD KEY `fk_booking_payment` (`Payment_Id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notif_user` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_Id`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`Property_Id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_review_booking` (`booking_id`),
  ADD KEY `fk_review_user` (`user_id`),
  ADD KEY `fk_review_property` (`property_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_Id`),
  ADD UNIQUE KEY `uq_user_email` (`Email`),
  ADD UNIQUE KEY `uq_user_username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `Booking_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `Property_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`User_Id`) REFERENCES `user` (`User_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`Property_Id`) REFERENCES `property` (`Property_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`Payment_Id`) REFERENCES `payment` (`Payment_Id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notif_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`User_Id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`Booking_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`User_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`property_id`) REFERENCES `property` (`Property_Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

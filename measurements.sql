-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2025 at 08:55 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tailor_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `measurements`
--

CREATE TABLE `measurements` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cloth_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receive_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `length` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waist` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sleeve` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `neck` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `front` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `back` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shoulder` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chest_up` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chest_down` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seat_hip` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_pohdai` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cut` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thighs` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `knee` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mori` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sleeve_mori` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chanya_length` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sleeve_length` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `measurements`
--
ALTER TABLE `measurements`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `measurements`
--
ALTER TABLE `measurements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

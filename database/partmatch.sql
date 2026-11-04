-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2026 at 05:48 PM
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
-- Database: `partmatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `asking_price` decimal(10,0) NOT NULL,
  `part_condition` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `location` varchar(50) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `image1_name` varchar(100) DEFAULT NULL,
  `image1_data` mediumblob DEFAULT NULL,
  `image2_name` varchar(100) DEFAULT NULL,
  `image2_data` mediumblob DEFAULT NULL,
  `image3_name` varchar(100) DEFAULT NULL,
  `image3_data` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `seller_id`, `part_id`, `asking_price`, `part_condition`, `status`, `location`, `date_time`, `image1_name`, `image1_data`, `image2_name`, `image2_data`, `image3_name`, `image3_data`) VALUES
(1, 104, 46, 126, 'new-in-box', 'available', 'Phoenix, NM', '2026-11-04 10:27:33', '', '', '', '', '', ''),
(2, 103, 46, 100, 'used', 'sold', 'Saint Cloud, MN', '2026-11-04 10:28:15', '', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

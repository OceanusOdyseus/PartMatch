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
  `listing_id` int PRIMARY KEY AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `asking_price` decimal(10,0) NOT NULL,
  `part_condition` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `location` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL, 
  'date_time' TIMESTAMP NOT NULL DEFAULT '',
  `image1_name` varchar(100) DEFAULT NULL,
  `image1_data` mediumblob DEFAULT NULL,
  `image2_name` varchar(100) DEFAULT NULL,
  `image2_data` mediumblob DEFAULT NULL,
  `image3_name` varchar(100) DEFAULT NULL,
  `image3_data` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users`
--

CREATE TABLE 'users'(
  user_id INT PRIMARY KEY,
  username VARCHAR(60) NOT NULL,
  email VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  location VARCHAR(100) NOT NULL
);

--
-- Table structure for table `parts`
--

CREATE TABLE 'parts'(
  part_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  category ENUM('CPU', 'GPU', 'Motherboard', 'RAM', 'PSU', 'Storage') NOT NULL,
  brand VARCHAR(40) NOT NULL,
  socket VARCHAR(40) NULL,
  memory_type VARCHAR(10) NULL,
  power_watts INT NULL
);

--
-- Table structure for table `user_builds`
--

CREATE TABLE 'user_builds'(
  user_id INT PRIMARY KEY,
  cpu_part_id INT NULL,
  gpu_part_id INT NULL,
  motherboard_part_id INT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (cpu_part_id) REFERENCES parts(part_id),
  FOREIGN KEY (gpu_part_id) REFERENCES parts(part_id),
  FOREIGN KEY (motherboard_part_id) REFERENCES parts(part_id),
);
--
-- Dumping data for table `parts` (USED AI to generate listings)
--

INSERT INTO 'parts' (part_id, name, category, brand, socket, memory_type, power_watts) VALUES
(1,  'AMD Ryzen 5 7600',                 'CPU', 'AMD',    'AM5',     NULL,   NULL),
(2,  'AMD Ryzen 7 7800X3D',              'CPU', 'AMD',    'AM5',     NULL,   NULL),
(3,  'AMD Ryzen 5 5600',                 'CPU', 'AMD',    'AM4',     NULL,   NULL),
(4,  'AMD Ryzen 7 5800X3D',              'CPU', 'AMD',    'AM4',     NULL,   NULL),
(5,  'Intel Core i5-13600K',             'CPU', 'Intel',  'LGA1700', NULL,   NULL),
(6,  'Intel Core i7-12700K',             'CPU', 'Intel',  'LGA1700', NULL,   NULL),
(7,  'ASUS TUF Gaming B650-PLUS',        'Motherboard', 'ASUS',     'AM5',     'DDR5', NULL),
(8,  'MSI MAG B650 Tomahawk',            'Motherboard', 'MSI',      'AM5',     'DDR5', NULL),
(9,  'Gigabyte B550 AORUS Elite',        'Motherboard', 'Gigabyte', 'AM4',     'DDR4', NULL),
(10, 'MSI B550-A PRO',                   'Motherboard', 'MSI',      'AM4',     'DDR4', NULL),
(11, 'ASUS PRIME Z790-P',                'Motherboard', 'ASUS',     'LGA1700', 'DDR5', NULL),
(12, 'Gigabyte B660M DS3H DDR4',         'Motherboard', 'Gigabyte', 'LGA1700', 'DDR4', NULL),
(13, 'NVIDIA GeForce RTX 4060',          'GPU', 'NVIDIA', NULL, NULL, 550),
(14, 'NVIDIA GeForce RTX 4070 Super',    'GPU', 'NVIDIA', NULL, NULL, 650),
(15, 'NVIDIA GeForce RTX 3080',          'GPU', 'NVIDIA', NULL, NULL, 750),
(16, 'AMD Radeon RX 7800 XT',            'GPU', 'AMD',    NULL, NULL, 700),
(17, 'AMD Radeon RX 6600',               'GPU', 'AMD',    NULL, NULL, 450),
(18, 'NVIDIA GeForce GTX 1660 Super',    'GPU', 'NVIDIA', NULL, NULL, 450),
(19, 'Corsair Vengeance 32GB DDR5-6000', 'RAM', 'Corsair',  NULL, 'DDR5', NULL),
(20, 'G.Skill Ripjaws 16GB DDR4-3200',   'RAM', 'G.Skill',  NULL, 'DDR4', NULL),
(21, 'Kingston Fury Beast 32GB DDR5-5600','RAM','Kingston', NULL, 'DDR5', NULL),
(22, 'Corsair LPX 32GB DDR4-3600',       'RAM', 'Corsair',  NULL, 'DDR4', NULL),
(23, 'Corsair RM650 650W',               'PSU', 'Corsair',  NULL, NULL, 650),
(24, 'NZXT C550 550W',                   'PSU', 'NZXT',     NULL, NULL, 550),
(25, 'EVGA SuperNOVA 750 GQ',            'PSU', 'EVGA',     NULL, NULL, 750),
(26, 'Samsung 980 Pro 1TB NVMe',         'Storage', 'Samsung', NULL, NULL, NULL),
(27, 'WD Blue SN580 500GB NVMe',         'Storage', 'WD',      NULL, NULL, NULL);
 
--
-- Dumping data for table `listings` (USED AI to generate listings)
--

INSERT INTO 'listings' (seller_id, part_id, asking_price, part_condition, status, location, description, date_time) VALUES
(101, 2,  329.00, 'new-in-box', 'available', 'Maple Grove, Minnesota', 'Sealed retail box, bought a spare.', NOW() - INTERVAL 1 DAY),
(101, 7,  189.99, 'used', 'available', 'Maple Grove, Minnesota', 'Ran for six months, no bent pins.', NOW() - INTERVAL 2 DAY),
(102, 1,  199.00, 'used', 'available', 'Chicago, Illinois', 'Great budget AM5 chip.', NOW() - INTERVAL 3 DAY),
(102, 13,  279.00, 'used', 'available', 'Chicago, Illinois', 'Light gaming use only.', NOW() - INTERVAL 4 DAY),
(102, 19,  119.00, 'new-in-box', 'available', 'Chicago, Illinois', 'DDR5-6000 kit, unopened.', NOW() - INTERVAL 5 DAY),
(103, 3,   89.00, 'used', 'available', 'Los Angeles, California', 'Upgraded to AM5, selling my old chip.', NOW() - INTERVAL 6 DAY),
(103, 9,   99.00, 'used', 'available', 'Los Angeles, California', 'Works fine, includes I/O shield.', NOW() - INTERVAL 7 DAY),
(103, 20,   39.00, 'used', 'available', 'Los Angeles, California', 'Matched 2x8GB kit.', NOW() - INTERVAL 8 DAY),
(104, 14,  549.00, 'used', 'available', 'Richmond, Virginia', 'Original box and cables included.', NOW() - INTERVAL 9 DAY),
(104, 24,   59.00, 'used', 'available', 'Richmond, Virginia', '550W bronze, all cables included.', NOW() - INTERVAL 10 DAY),
(105, 5,  239.00, 'new-in-box', 'available', 'Portland, Oregon', 'Unopened retail box.', NOW() - INTERVAL 11 DAY),
(105, 11,  179.00, 'used', 'available', 'Portland, Oregon', 'Z790 board, takes DDR5 memory.', NOW() - INTERVAL 12 DAY),
(105, 15,  299.00, 'used', 'available', 'Portland, Oregon', 'Always ran at stock clocks.', NOW() - INTERVAL 13 DAY),
(101, 16,  429.00, 'used', 'available', 'Maple Grove, Minnesota', 'Quiet, cool, and barely used.', NOW() - INTERVAL 14 DAY),
(102, 25,   89.00, 'new-in-box', 'available', 'Chicago, Illinois', '750W 80+ Gold, fully modular.', NOW() - INTERVAL 15 DAY),
(103, 26,   99.00, 'used', 'available', 'Los Angeles, California', '1TB NVMe, 95 percent drive health.', NOW() - INTERVAL 16 DAY),
(104, 27,   35.00, 'new-in-box', 'available', 'Richmond, Virginia', '500GB NVMe, still sealed.', NOW() - INTERVAL 17 DAY),
(105, 17,  119.00, 'used', 'available', 'Portland, Oregon', 'Fits small cases.', NOW() - INTERVAL 18 DAY),
(101, 4,  259.00, 'used', 'available', 'Maple Grove, Minnesota', 'Top AM4 gaming CPU.', NOW() - INTERVAL 19 DAY),
(102, 22,   69.00, 'used', 'available', 'Chicago, Illinois', '32GB DDR4-3600 kit.', NOW() - INTERVAL 20 DAY),
(103, 10,   79.00, 'broken', 'available', 'Los Angeles, California', 'Does not POST. Selling for parts.', NOW() - INTERVAL 21 DAY),
(104, 21,   95.00, 'new-in-box', 'available', 'Richmond, Virginia', '32GB DDR5-5600, sealed.', NOW() - INTERVAL 22 DAY),
(101, 12,   85.00, 'used', 'available', 'Maple Grove, Minnesota', 'DDR4 board for LGA1700 builds.', NOW() - INTERVAL 23 DAY),
(102, 6,  189.00, 'used', 'available', 'Chicago, Illinois', 'Stock cooler included, clean chip.', NOW() - INTERVAL 24 DAY),
(103, 8,  219.00, 'new-in-box', 'available', 'Los Angeles, California', 'Open box, never installed.', NOW() - INTERVAL 25 DAY),
(105, 23,   69.00, 'used', 'available', 'Portland, Oregon', '650W reliable unit.', NOW() - INTERVAL 26 DAY);



--
-- Dumping data for table `users`
--
INSERT INTO 'users' (user_id, username, email, phone, location) VALUES
(101, 'Nicholas Born', 'Born2BGreat@example.com', '243-0943', 'Maple Grove, Minnesota'),
(102, 'Jordan Michael', 'jordan2Be@example.edu', '672,9021', 'Chicago, Illinios'),
(103, 'Cash Money', 'rich2rags@example.com', '907-8742', 'Los Angeles, California'),
(104, 'Henry Holiday', 'HenryHoliday2@example.com', '987-234', 'Richmond, Virginia'),
(105, 'Leclerc Rozman', 'F1enjoyer294@example.com', '169-3215', 'Portland, Oregon');

--
-- Dumping data for table `parts`
--
INSERT INTO 'parts' (part_id, name, category, brand, socket, memory_type, power_watts) VALUES
(1,  'AMD Ryzen 5 7600',                 'CPU', 'AMD',    'AM5',     NULL,   NULL),
(2,  'AMD Ryzen 7 7800X3D',              'CPU', 'AMD',    'AM5',     NULL,   NULL),
(3,  'AMD Ryzen 5 5600',                 'CPU', 'AMD',    'AM4',     NULL,   NULL),
(4,  'AMD Ryzen 7 5800X3D',              'CPU', 'AMD',    'AM4',     NULL,   NULL),
(5,  'Intel Core i5-13600K',             'CPU', 'Intel',  'LGA1700', NULL,   NULL),
(6,  'Intel Core i7-12700K',             'CPU', 'Intel',  'LGA1700', NULL,   NULL),
(7,  'ASUS TUF Gaming B650-PLUS',        'Motherboard', 'ASUS',     'AM5',     'DDR5', NULL),
(8,  'MSI MAG B650 Tomahawk',            'Motherboard', 'MSI',      'AM5',     'DDR5', NULL),
(9,  'Gigabyte B550 AORUS Elite',        'Motherboard', 'Gigabyte', 'AM4',     'DDR4', NULL),
(10, 'MSI B550-A PRO',                   'Motherboard', 'MSI',      'AM4',     'DDR4', NULL),
(11, 'ASUS PRIME Z790-P',                'Motherboard', 'ASUS',     'LGA1700', 'DDR5', NULL),
(12, 'Gigabyte B660M DS3H DDR4',         'Motherboard', 'Gigabyte', 'LGA1700', 'DDR4', NULL),
(13, 'NVIDIA GeForce RTX 4060',          'GPU', 'NVIDIA', NULL, NULL, 550),
(14, 'NVIDIA GeForce RTX 4070 Super',    'GPU', 'NVIDIA', NULL, NULL, 650),
(15, 'NVIDIA GeForce RTX 3080',          'GPU', 'NVIDIA', NULL, NULL, 750),
(16, 'AMD Radeon RX 7800 XT',            'GPU', 'AMD',    NULL, NULL, 700),
(17, 'AMD Radeon RX 6600',               'GPU', 'AMD',    NULL, NULL, 450),
(18, 'NVIDIA GeForce GTX 1660 Super',    'GPU', 'NVIDIA', NULL, NULL, 450),
(19, 'Corsair Vengeance 32GB DDR5-6000', 'RAM', 'Corsair',  NULL, 'DDR5', NULL),
(20, 'G.Skill Ripjaws 16GB DDR4-3200',   'RAM', 'G.Skill',  NULL, 'DDR4', NULL),
(21, 'Kingston Fury Beast 32GB DDR5-5600','RAM','Kingston', NULL, 'DDR5', NULL),
(22, 'Corsair LPX 32GB DDR4-3600',       'RAM', 'Corsair',  NULL, 'DDR4', NULL),
(23, 'Corsair RM650 650W',               'PSU', 'Corsair',  NULL, NULL, 650),
(24, 'NZXT C550 550W',                   'PSU', 'NZXT',     NULL, NULL, 550),
(25, 'EVGA SuperNOVA 750 GQ',            'PSU', 'EVGA',     NULL, NULL, 750),
(26, 'Samsung 980 Pro 1TB NVMe',         'Storage', 'Samsung', NULL, NULL, NULL),
(27, 'WD Blue SN580 500GB NVMe',         'Storage', 'WD',      NULL, NULL, NULL);



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

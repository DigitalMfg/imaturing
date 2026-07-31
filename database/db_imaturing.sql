-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 31, 2026 at 02:04 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_imaturing`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory`
--

CREATE TABLE `tbl_inventory` (
  `id` int(11) NOT NULL,
  `inventory_date` date DEFAULT NULL,
  `model` varchar(150) DEFAULT NULL,
  `style` varchar(50) DEFAULT NULL,
  `colour` varchar(100) DEFAULT NULL,
  `mcs` varchar(20) DEFAULT NULL,
  `shift` int(11) DEFAULT NULL,
  `kg` decimal(10,2) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request_detail`
--

CREATE TABLE `tbl_request_detail` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `bucket` varchar(8) DEFAULT NULL,
  `style` varchar(50) DEFAULT NULL,
  `model` varchar(150) DEFAULT NULL,
  `crafted` int(11) DEFAULT NULL,
  `shift` varchar(17) DEFAULT NULL,
  `colour` varchar(100) DEFAULT NULL,
  `mcs` varchar(20) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `kg` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `reject_reason` text DEFAULT NULL,
  `approve_by` varchar(100) DEFAULT NULL,
  `approve_nik` varchar(30) DEFAULT NULL,
  `approve_date` datetime DEFAULT NULL,
  `reject_by` varchar(100) DEFAULT NULL,
  `reject_nik` varchar(30) DEFAULT NULL,
  `reject_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_request_detail`
--

INSERT INTO `tbl_request_detail` (`id`, `request_id`, `bucket`, `style`, `model`, `crafted`, `shift`, `colour`, `mcs`, `category`, `kg`, `status`, `reject_reason`, `approve_by`, `approve_nik`, `approve_date`, `reject_by`, `reject_nik`, `reject_date`) VALUES
(14, 22, '260803SO', 'IZ1672-100', 'NIKE ST DYNAMITE GS', 7, '2', 'Game Royal', '001', 'BON ASEMBLING', 10.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request_header`
--

CREATE TABLE `tbl_request_header` (
  `id` int(11) NOT NULL,
  `request_no` varchar(30) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `planning_by` varchar(50) DEFAULT NULL,
  `status` enum('OPEN','PROCESS','FINISH') DEFAULT 'OPEN',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_request_header`
--

INSERT INTO `tbl_request_header` (`id`, `request_no`, `request_date`, `planning_by`, `status`, `remarks`, `created_at`) VALUES
(22, NULL, '2026-07-30', 'Ciko', 'OPEN', NULL, '2026-07-30 09:04:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request_size`
--

CREATE TABLE `tbl_request_size` (
  `id` int(11) NOT NULL,
  `detail_id` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_request_size`
--

INSERT INTO `tbl_request_size` (`id`, `detail_id`, `size`, `qty`) VALUES
(7, 14, '1', 6),
(8, 14, '1T', 24),
(9, 14, '2', 36),
(10, 14, '2T', 6),
(11, 14, '3', 12),
(12, 14, '3T', 64);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `nik` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `authorize` varchar(100) NOT NULL,
  `scan_type` varchar(100) NOT NULL,
  `cost_center` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `nik`, `password`, `authorize`, `scan_type`, `cost_center`) VALUES
(1, 'Ciko', '1410422', '123456', 'Admin', 'IN_SM', 'QA'),
(123, 'User IN SM', '222222', '123456', 'User', 'IN_SM', 'Production'),
(138, 'User Test', '333333', '123456', 'Admin', 'OUT_PACKING', 'Planning'),
(139, 'User Test 1', '111111', '123456', 'Admin', '', 'Planning');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_request_detail`
--
ALTER TABLE `tbl_request_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `tbl_request_header`
--
ALTER TABLE `tbl_request_header`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_request_size`
--
ALTER TABLE `tbl_request_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_id` (`detail_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `nik` (`nik`),
  ADD KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_request_detail`
--
ALTER TABLE `tbl_request_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_request_header`
--
ALTER TABLE `tbl_request_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_request_size`
--
ALTER TABLE `tbl_request_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_request_detail`
--
ALTER TABLE `tbl_request_detail`
  ADD CONSTRAINT `tbl_request_detail_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `tbl_request_header` (`id`);

--
-- Constraints for table `tbl_request_size`
--
ALTER TABLE `tbl_request_size`
  ADD CONSTRAINT `tbl_request_size_ibfk_1` FOREIGN KEY (`detail_id`) REFERENCES `tbl_request_detail` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

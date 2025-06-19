-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2025 at 04:35 PM
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
-- Database: `safaribooks_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `commercial_invoice_number`
--

CREATE TABLE `commercial_invoice_number` (
  `id` int(20) NOT NULL,
  `number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commercial_invoice_number`
--

INSERT INTO `commercial_invoice_number` (`id`, `number`) VALUES
(1, '000896');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` bigint(20) NOT NULL,
  `invoice_number` mediumtext NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `invoice_date` varchar(200) NOT NULL,
  `shipping_via` text NOT NULL,
  `customer_reference` text NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_city` varchar(200) NOT NULL,
  `invoice_type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` bigint(20) NOT NULL,
  `invoice` bigint(20) NOT NULL,
  `product` int(20) NOT NULL,
  `qty` mediumint(20) NOT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT 0,
  `discount` varchar(250) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` int(20) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `bank_name` varchar(250) NOT NULL,
  `account_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`id`, `account_number`, `bank_name`, `account_name`) VALUES
(1, '4010915311', 'Mallory Safari', 'Fidelity Bank Plc.'),
(2, '0005345563', 'Access Bank Plc.', 'Safari Books Limited');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` enum('CEO','MANAGER','ACCOUNTANT','HEAD - BUSINESS DEVELOPMENT','HUMAN RESOURCE - HR','EDITORIAN MANAGER - ED','MARKETING MANAGER','DRIVER') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'CEO', '2025-06-13 11:12:07', '2025-06-13 11:12:07'),
(2, 'MANAGER', '2025-06-13 11:12:07', '2025-06-13 11:12:07'),
(3, 'ACCOUNTANT', '2025-06-13 11:12:07', '2025-06-13 11:12:07'),
(4, 'HEAD - BUSINESS DEVELOPMENT', '2025-06-13 11:12:07', '2025-06-13 11:12:07'),
(5, 'HUMAN RESOURCE - HR', '2025-06-13 11:12:07', '2025-06-13 11:12:07'),
(6, 'EDITORIAN MANAGER - ED', '2025-06-13 11:12:07', '2025-06-13 11:12:07'),
(7, 'MARKETING MANAGER', '2025-06-13 11:12:07', '2025-06-13 11:12:07'),
(8, 'DRIVER', '2025-06-13 11:12:07', '2025-06-13 11:12:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `telephone`, `created_at`, `updated_at`) VALUES
(1, 'Louisa Irabor', 'safarinigeria@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ejZEYTk5eVFPTnJGZUsvUQ$iSw9W7XdxxMseQFgQ8qHpbumLiFJb2jq0ozw7dt48qI', 4, '+2347060603020', '2025-06-13 11:14:54', '2025-06-17 00:50:31'),
(2, 'James Akpele', 'james@aol.com', '$argon2id$v=19$m=65536,t=4,p=1$ejZEYTk5eVFPTnJGZUsvUQ$iSw9W7XdxxMseQFgQ8qHpbumLiFJb2jq0ozw7dt48qI', 7, '+2348028909057', '2025-06-13 11:14:54', '2025-06-17 00:51:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commercial_invoice_number`
--
ALTER TABLE `commercial_invoice_number`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commercial_invoice_number`
--
ALTER TABLE `commercial_invoice_number`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

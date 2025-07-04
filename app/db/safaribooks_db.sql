-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 02:51 PM
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
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `author` text NOT NULL,
  `binding` enum('HB','PB','','') NOT NULL,
  `sale_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `books`
--

TRUNCATE TABLE `books`;
--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `binding`, `sale_price`) VALUES
(1, 'Brutally Frank', 'Edwin Clerk', 'HB', 25000.00),
(2, 'Nigerwives', '', 'HB', 10000.00),
(3, 'Nigerwives', '', 'PB', 15000.00),
(4, 'Working With Buhari', '', 'HB', 20000.00),
(5, 'The History Of The Nigeria Railway', '', '', 35000.00),
(6, 'Politics As Dashed Hopes In Nigeria', '', 'PB', 10000.00),
(7, 'A Brief History Of Time', '', '', 4500.00),
(8, 'International Relations', '', '', 7500.00),
(9, '60 Year Long March Towards Democracy', '', '', 0.00),
(10, 'Morning By Morning', '', 'HB', 10000.00),
(11, 'Morning By Morning', '', 'PB', 7500.00),
(12, 'Know Your Country Katsina', '', '', 0.00),
(13, 'Strategic Turnaround', '', 'HB', 25000.00),
(14, 'Strategic Turnaround', '', 'PB', 20000.00),
(15, 'What They Don\'t Teach You Broadcasting', '', '', 4500.00),
(16, 'Just Thinking Vol. 1', '', '', 4000.00),
(17, 'Just Thinking Vol. 2', '', '', 4000.00),
(19, 'Universal Tetiary Education in Nigeria', '', '', 0.00),
(20, 'Still Standing', '', '', 0.00),
(21, 'Universal Organic Chemistry', '', '', 5000.00),
(22, 'The Entrepreneur', '', 'HB', 15000.00),
(23, 'The Entrepreneur', '', 'PB', 10000.00),
(24, 'Standing For Truth With Courage', '', 'HB', 0.00),
(25, 'Standing For Truth With Courage', '', 'PB', 0.00),
(26, 'Battlelines', '', 'HB', 0.00),
(27, 'The Making of a Nigeria Engineer', '', 'PB', 0.00),
(28, 'Company Law & Practice in Nigeria', '', '', 10000.00),
(29, 'The Church of the Brethren', '', 'HB', 0.00),
(30, 'The Church of The Brethren', '', 'PB', 0.00),
(31, 'Travel Agency Operations in Nigeria', '', 'HB', 0.00),
(32, 'Travel Agency Operations in Nigeria', '', 'PB', 0.00),
(33, 'The Niger Delta Paradox', '', '', 5000.00),
(34, 'Prime Witness', '', '', 5000.00),
(35, 'To Live With Happiness', '', '', 0.00),
(36, 'Election Management in Nigeria', '', '', 0.00),
(37, 'Africa Philosophy of Education', '', 'HB', 0.00),
(38, 'Africa Philosophy of Education', '', 'PB', 0.00),
(39, 'The Holy of Book of Ifa Adimula', '', '', 0.00),
(40, 'Islam is Moderation', '', '', 0.00),
(41, 'All Will Be Well', '', 'HB', 15000.00),
(42, 'All Will Be Well', '', 'PB', 10000.00),
(43, 'Hallmark of Labour Vol. 11', '', 'HB', 0.00),
(44, 'Solar Power Installation', '', '', 4000.00),
(45, 'Stress Management', '', '', 0.00),
(46, 'Art of Courage', '', '', 0.00),
(47, 'Obasanjo - Nigeria\'s Most Successful Ruler', '', '', 0.00),
(48, 'Apga - The Trials Tribulation', '', 'HB', 0.00),
(49, 'Apga - The Trials Tribulation', '', 'PB', 0.00),
(50, 'The Builder Never Stops', '', 'PB', 0.00),
(51, 'Buhari: The Making of a President', '', 'HB', 0.00),
(52, 'Buhari: The Making of a President', '', 'PB', 15000.00),
(53, 'Essays on Contemporary Issues in Nigeria Banks', '', 'HB', 20000.00),
(54, 'Essays on Contemporary Issues in Nigeria Banks', '', 'PB', 0.00),
(55, 'The Accomplished Public Servant', '', 'PB', 0.00),
(56, 'The Great Igbinedion', '', 'HB', 0.00),
(57, 'Dialectics of Rights', '', '', 0.00),
(58, 'Onye Nkuzi Tech', '', '', 0.00),
(59, 'In Search for Sustainable', '', 'HB', 0.00),
(60, 'In Search for Sustainable', '', 'PB', 0.00),
(61, 'Nigeria Tariff and Non Tariff Polices', '', 'PB', 0.00),
(62, 'Know Your Country Oyo', '', 'PB', 0.00),
(63, 'Party Coalition in Nigeria', '', '', 0.00),
(64, 'Frontiers of Jihad', '', '', 5000.00),
(65, 'I Remember', '', 'HB', 10000.00),
(66, 'I Remember', '', 'PB', 5000.00),
(67, 'The Other Side of Biafra', '', 'HB', 15000.00),
(68, 'The Flame Tree', '', '', 5000.00),
(69, 'The Accidental Public Servant', '', '', 10000.00),
(70, 'Africa\'s Security Challenges in the 21st Century', '', '', 10000.00),
(71, 'I Am Because We Are', '', 'HB', 10000.00),
(72, 'Medicine My Passport', '', '', 10000.00),
(73, 'Lagos State Visual Portrait', '', '', 0.00),
(74, 'The Power of One Man', '', 'HB', 0.00),
(75, 'The Power of One Man', '', 'PB', 0.00),
(76, 'Uncommon Grace', '', 'PB', 0.00),
(77, 'The Riches of His Grace', '', 'PB', 0.00),
(78, 'Persona Non-Grata', '', 'HB', 20000.00),
(79, 'Persona Non-Grata', '', 'PB', 15000.00),
(80, 'Hallmark of Labour Vol. 12', '', 'HB', 0.00),
(81, 'Ogbeni: The Osun Renaissance Years', '', 'HB', 0.00),
(82, 'Ogbeni: The Osun Renaissance Years', '', 'PB', 0.00),
(83, 'Contending Issues in Nigeria Democracy and Development', '', 'HB', 0.00),
(84, 'Contending Issues in Nigeria Democracy and Development', '', 'PB', 0.00),
(85, 'Know Oyo State', '', '', 5000.00),
(86, 'Cecilia', '', '', 35000.00);


-- --------------------------------------------------------

--
-- Table structure for table `commercial_invoice_number`
--

CREATE TABLE `commercial_invoice_number` (
  `id` int(20) NOT NULL,
  `invoice_number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commercial_invoice_number`
--

INSERT INTO `commercial_invoice_number` (`id`, `invoice_number`) VALUES
(1, '000896');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_number` mediumtext NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `invoice_date` varchar(200) NOT NULL,
  `shipping_via` text NOT NULL,
  `customer_reference` text NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_city` varchar(200) NOT NULL,
  `invoice_type` varchar(200) NOT NULL,
  `terms` varchar(50) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method_id` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `invoice_number`, `customer_id`, `invoice_date`, `shipping_via`, `customer_reference`, `client_name`, `client_city`, `invoice_type`, `terms`, `total_amount`, `payment_method_id`, `createdAt`, `updatedAt`) VALUES
(4, 'NGSB-000900', 2, '20th June, 2025', '', '', 'James Akpele', 'Ibadan', 'Invoice', 'Due on receipt', 816650.00, 2, '2025-04-02 14:20:45', '2025-06-23 13:49:05'),
(11, 'NGSB-000903', 0, '20th June, 2025', '', '', 'Prof. Tunde Adeniran', 'Ibadan', 'Invoice', 'Due on receipt', 1836100.00, NULL, '2025-06-20 18:23:15', '2025-06-23 13:08:11'),
(14, 'NGSB-000904', 2, '23rd June, 2025', '', '', 'kim', 'Ibadan', 'Invoice', 'Due on receipt', 36000.00, 1, '2025-06-23 13:38:31', '2025-06-23 13:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

-- --------------------------------------------------------

--
-- Table structure for table `invoice_section`
--

CREATE TABLE IF NOT EXISTS `invoice_section` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `label` varchar(10) DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL,
  `discount_amount` decimal(15,2) DEFAULT NULL,
  `total_after_discount` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_section`
--
-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE IF NOT EXISTS `payment_details` (
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
-- Table structure for table `records`
--

CREATE TABLE IF NOT EXISTS `records` (
  `id` int(20) NOT NULL,
  `no_printed_invoices` bigint(20) NOT NULL,
  `no_printed_users` bigint(20) NOT NULL,
  `no_printed_books` bigint(20) NOT NULL,
  `no_send_emails` bigint(20) NOT NULL,
  `no_send_invoices` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `no_printed_invoices`, `no_printed_users`, `no_printed_books`, `no_send_emails`, `no_send_invoices`) VALUES
(1, 8, 1, 5, 12, 25);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
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
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(20) NOT NULL,
  `company_name` text NOT NULL,
  `company_tagline` text NOT NULL,
  `company_logo` text NOT NULL,
  `company_icon_logo` text NOT NULL,
  `company_rc` varchar(250) NOT NULL,
  `company_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `company_address` text NOT NULL,
  `company_telephone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `company_website` varchar(250) NOT NULL,
  `company_country` varchar(200) NOT NULL,
  `company_city` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `company_name`, `company_tagline`, `company_logo`, `company_icon_logo`, `company_rc`, `company_email`, `company_address`, `company_telephone`, `company_website`, `company_country`, `company_city`) VALUES
(1, 'SAFARI BOOKS LIMITED', 'SAFARI BOOKS LTD.', 'company_settings/main_logo/8b018a92564f62f5576d416bdf6ca1d7.webp', 'company_settings/icon_logo/88b34f2788d1246bb85a460de6820cac.webp', 'RC.172479', 'info@safaribooks.com.ng', 'Ile Ori-Detu, No-1Shell Close Onireke', '+234(0)7060603020', 'https://www.safaribooks.com.ng', 'Ibadan', 'Oyo-State');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
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
(1, 'Louisa Irabor', 'safarinigeria@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$TlJrUC80eXp1YlQvL2VIRg$j1NgkwDfFfTKgwYqvZy5oSHQL9Cj4n/HEU7ae3mKIGg', 4, '+2347060603020', '2025-06-13 11:14:54', '2025-06-21 21:56:57'),
(2, 'James Akpele', 'james@aol.com', '$argon2id$v=19$m=65536,t=4,p=1$ejZEYTk5eVFPTnJGZUsvUQ$iSw9W7XdxxMseQFgQ8qHpbumLiFJb2jq0ozw7dt48qI', 7, '+2348028909057', '2025-06-13 11:14:54', '2025-06-21 21:41:30');

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
  ADD KEY `fk_payment_method` (`payment_method_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `invoice_section`
--
ALTER TABLE `invoice_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `commercial_invoice_number`
--
ALTER TABLE `commercial_invoice_number`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `invoice_section`
--
ALTER TABLE `invoice_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_payment_method` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_details` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `invoice_section` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `invoice_section`
--
ALTER TABLE `invoice_section`
  ADD CONSTRAINT `invoice_section_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;


ALTER TABLE settings
MODIFY COLUMN id INT NOT NULL PRIMARY KEY;

DELIMITER $$

CREATE TRIGGER allow_only_one_row
BEFORE INSERT ON settings
FOR EACH ROW
BEGIN
  DECLARE row_count INT;
  SELECT COUNT(*) INTO row_count FROM settings;

  IF row_count >= 1 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Only one row allowed in the settings table';
  END IF;
END$$

DELIMITER;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

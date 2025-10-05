-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2025 at 10:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_lifelink`
--

-- --------------------------------------------------------

--
-- Table structure for table `donor_offers`
--

CREATE TABLE `donor_offers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organ_type` varchar(100) NOT NULL,
  `blood_type` varchar(10) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('available','matched','completed') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `status` enum('pending','verified','revealed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_requests`
--

CREATE TABLE `patient_requests` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `organ_needed` varchar(100) NOT NULL,
  `blood_type` varchar(10) NOT NULL,
  `urgency` enum('low','medium','high') DEFAULT 'medium',
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` enum('donor','doctor','admin') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `role`, `password`, `created_at`) VALUES
(1, 's', 'xav', 'donor', '$2y$10$9k1D1HXv0N7m75c7c/h7VemA4cmE2c.GNFOCwSE/MybqlC6ONU9oO', '2025-10-05 16:25:04'),
(2, 's', 'xavy', 'doctor', '$2y$10$3sJYIb.rjpg4IJZMLZXTmObvaBcJypWjPLTt9n2YKQHx204OVxOwW', '2025-10-05 17:46:41'),
(3, 'oussycat', 'meow', 'donor', '$2y$10$K1As2VG2RpaNymhl5wdfn.ScABEwP3XMThsovdAgNw/e8p4c4a0uq', '2025-10-05 19:28:50'),
(4, 'meowmeow', 'meow2', 'doctor', '$2y$10$fhM947Eqsinz6wUB/BSS8.LHp/ukGjiJaYo1uX67kgvPx6hwL9Upa', '2025-10-05 19:32:02'),
(5, 'quakquak', 'duck', 'doctor', '$2y$10$SLkPJquZPYc36DXwf.al7e7PONvM/Nzs0/DHH2HPOXAJ9ESjjYI7G', '2025-10-05 19:33:01'),
(6, 'meowww', 'ps', 'donor', '$2y$10$IGnpd.ncRGrEN.Nld4f9LedB7fCdVkbIzaf9A8nPO/91vIoOjB54i', '2025-10-05 20:17:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donor_offers`
--
ALTER TABLE `donor_offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patient_requests`
--
ALTER TABLE `patient_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donor_offers`
--
ALTER TABLE `donor_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_requests`
--
ALTER TABLE `patient_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donor_offers`
--
ALTER TABLE `donor_offers`
  ADD CONSTRAINT `donor_offers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donor_offers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_requests`
--
ALTER TABLE `patient_requests`
  ADD CONSTRAINT `patient_requests_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2026 at 03:53 PM
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
-- Database: `registrar_ticketing_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `ticket_id` int(11) NOT NULL,
  `student_user_id` int(11) NOT NULL,
  `request_type` varchar(50) NOT NULL,
  `department` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `status` enum('Pending','','','') NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `pickup_date` datetime(6) NOT NULL,
  `expiry_date` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`ticket_id`, `student_user_id`, `request_type`, `department`, `details`, `status`, `request_date`, `pickup_date`, `expiry_date`) VALUES
(3, 4, '', 'BSBA', 'samp', '', '2026-01-29 13:57:08', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000'),
(4, 4, '', 'BSBA', 'sampsamp', '', '2026-01-29 14:10:21', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000'),
(5, 4, '', 'BSBA', 'jiji', 'Pending', '2026-01-29 14:11:09', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000'),
(6, 4, '', 'IT', 'nahak', 'Pending', '2026-01-29 14:11:53', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000'),
(9, 1, 'PERMIT', 'nursing', 'bb', 'Pending', '2026-01-29 14:26:03', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000'),
(10, 1, 'Study Load', 'BSIT', 'samp', 'Pending', '2026-01-29 14:26:59', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `student_user_id` int(150) NOT NULL,
  `id_number` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('(''Student'', ''Admin'', ''SuperAdmin'') DEFAULT ''Student''','','','') NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`student_user_id`, `id_number`, `full_name`, `password`, `role`, `year_level`, `phone_number`, `email`, `created_at`) VALUES
(1, 'SCC-22-00015058', 'Angel Sabellano', '$2y$10$85sf4w3vfpBfrVxmTP0Hdu1UspTsJKT/9tvxXSS/msadWTIGLWCSe', '', '2nd year', '09089652119', 'angelsabellanolibay22@gmail.com', '2026-01-29 12:45:39'),
(2, 'SCC-22-00015057', 'Kc balse', '$2y$10$sUKcQCP9OC/2mI/SpbyJR.Apt5/YnJVxnoFD4.mCbtjlAl5k/3oWW', '', '2nd year', '09089652110', 'kc@gmail.com', '2026-01-29 13:07:13'),
(3, 'SCC-22-00015055', 'laira labang', '$2y$10$IgR7zBiJ1x2PVXeMcLL57egN5jUio1WVbMaE1eBY8jAR7a63Ekybu', '', '2nd year', '09089652115', 'lairalabang@gmail.com', '2026-01-29 13:52:34'),
(4, 'SCC-22-00015054', 'samp', '$2y$10$1KWj8fVkn3tK98884b/qku.sNziOzq5lrzl1YEd3gEqBFMNs41njC', '', '2nd year', '09089652114', 'samp@gmail.com', '2026-01-29 13:56:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `student_id` (`student_user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`student_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `student_user_id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`student_user_id`) REFERENCES `user` (`student_user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

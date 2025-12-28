-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 27, 2025 at 09:35 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `care4purrt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `pet_owner_username` varchar(50) NOT NULL,
  `doctor_username` varchar(50) NOT NULL,
  `pet_type` varchar(50) NOT NULL,
  `pet_age` int(11) NOT NULL,
  `pet_problem` text NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `pet_owner_username`, `doctor_username`, `pet_type`, `pet_age`, `pet_problem`, `appointment_date`, `appointment_time`, `created_at`) VALUES
(1, 'afshi', 'Dr. Reza', 'cat', 2, 'Monthly check-up', '2025-12-22', '16:20:00', '2025-12-19 17:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `doctor_email` varchar(150) DEFAULT NULL,
  `doctor_phone` varchar(30) DEFAULT NULL,
  `doctor_specialty` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_profiles`
--

CREATE TABLE `doctor_profiles` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `medical_designation` varchar(150) DEFAULT NULL,
  `specialization` varchar(150) NOT NULL,
  `medical_license_number` varchar(100) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT NULL,
  `medical_degree` varchar(150) DEFAULT NULL,
  `institution_name` varchar(150) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `upload_field` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_profiles`
--

INSERT INTO `doctor_profiles` (`id`, `username`, `name`, `contact_number`, `medical_designation`, `specialization`, `medical_license_number`, `years_of_experience`, `medical_degree`, `institution_name`, `profile_picture`, `upload_field`, `created_at`) VALUES
(1, 'Dr. Reza', 'Reza Ahmed', '01363782376', 'Veterinary Surgeon', 'Small Animal Medicine & Surgery', 'DVM-REG-2021-4587', 4, 'Doctor of Veterinary Medicine', 'Veterinary Science, Bangladesh Agricultural University', 'uploads/694588037f572-R.jpeg', '', '2025-12-19 17:14:43');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `pet_age` int(11) DEFAULT NULL,
  `owner_name` varchar(50) NOT NULL,
  `vaccine_status` varchar(100) DEFAULT NULL,
  `illness` text DEFAULT NULL,
  `pet_picture` varchar(255) DEFAULT NULL,
  `health_status` text DEFAULT NULL,
  `diet_plan` text DEFAULT NULL,
  `other_recommendations` text DEFAULT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `doctor_specialty` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `name`, `age`, `pet_age`, `owner_name`, `vaccine_status`, `illness`, `pet_picture`, `health_status`, `diet_plan`, `other_recommendations`, `doctor_name`, `doctor_specialty`, `created_at`) VALUES
(1, 'Shiny', 2, NULL, 'afshi', 'vaccinated', 'Physaloptera', 'uploads/1000086895.jpg', NULL, NULL, NULL, NULL, NULL, '2025-12-19 17:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `pet_moods`
--

CREATE TABLE `pet_moods` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `energy_level` varchar(50) DEFAULT NULL,
  `appetite` varchar(50) DEFAULT NULL,
  `social_interaction` varchar(50) DEFAULT NULL,
  `play_behavior` varchar(50) DEFAULT NULL,
  `sleep_rest` varchar(50) DEFAULT NULL,
  `vocalization` varchar(50) DEFAULT NULL,
  `other_info` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_moods`
--

INSERT INTO `pet_moods` (`id`, `username`, `energy_level`, `appetite`, `social_interaction`, `play_behavior`, `sleep_rest`, `vocalization`, `other_info`, `created_at`) VALUES
(1, 'afshi', 'normal', 'overeating', 'playful', 'couldnt_stop', 'slept_all_day', 'normal_sounds', '', '2025-12-19 17:22:56');

-- --------------------------------------------------------

--
-- Table structure for table `pet_owner_profiles`
--

CREATE TABLE `pet_owner_profiles` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_number` varchar(30) NOT NULL,
  `passport` varchar(50) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_owner_profiles`
--

INSERT INTO `pet_owner_profiles` (`id`, `username`, `name`, `contact_number`, `passport`, `profile_picture`, `additional_info`, `created_at`) VALUES
(1, 'afshi', 'Afshi', '0197265388', '', 'uploads/h.jpg', 'I love my baby shiny', '2025-12-19 17:18:38');

-- --------------------------------------------------------

--
-- Table structure for table `pet_passports`
--

CREATE TABLE `pet_passports` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `passport_number` varchar(50) NOT NULL,
  `vaccination_status` varchar(100) DEFAULT NULL,
  `vaccination_date` date DEFAULT NULL,
  `pet_picture` varchar(255) DEFAULT NULL,
  `passport_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','doctor','owner') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$9XIbX1iFsnUzA1h2HDsP3OyRcps.1afikC62815dzeAybK/XtHgRm', 'admin', '2025-12-19 17:12:16'),
(2, 'Dr. Reza', '$2y$10$RUopwKCEiswEYUkl7CHgs.5u.q9AE/Z/BsrVhvCepFiRDsKG5T.hu', 'doctor', '2025-12-19 17:12:54'),
(3, 'afshi', '$2y$10$WmX7SMRYnZFDRQGxIK.WQ.ApkBSuFKVm1BIfbdOfnPLDMwCGKR.SW', 'owner', '2025-12-19 17:15:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_appointments_owner` (`pet_owner_username`),
  ADD KEY `idx_appointments_doctor_datetime` (`doctor_username`,`appointment_date`,`appointment_time`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_profiles`
--
ALTER TABLE `doctor_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pets_owner_name` (`owner_name`);

--
-- Indexes for table `pet_moods`
--
ALTER TABLE `pet_moods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pet_moods_user` (`username`);

--
-- Indexes for table `pet_owner_profiles`
--
ALTER TABLE `pet_owner_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pet_passports`
--
ALTER TABLE `pet_passports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `passport_number` (`passport_number`),
  ADD KEY `idx_pet_passports_pet_id` (`pet_id`);

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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_profiles`
--
ALTER TABLE `doctor_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pet_moods`
--
ALTER TABLE `pet_moods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pet_owner_profiles`
--
ALTER TABLE `pet_owner_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pet_passports`
--
ALTER TABLE `pet_passports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_doctor` FOREIGN KEY (`doctor_username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_appointments_owner` FOREIGN KEY (`pet_owner_username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_profiles`
--
ALTER TABLE `doctor_profiles`
  ADD CONSTRAINT `fk_doctor_profiles_user` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `fk_pets_owner` FOREIGN KEY (`owner_name`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet_moods`
--
ALTER TABLE `pet_moods`
  ADD CONSTRAINT `fk_pet_moods_user` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet_owner_profiles`
--
ALTER TABLE `pet_owner_profiles`
  ADD CONSTRAINT `fk_pet_owner_profiles_user` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pet_passports`
--
ALTER TABLE `pet_passports`
  ADD CONSTRAINT `fk_pet_passports_pet` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

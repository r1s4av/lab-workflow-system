-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 08:53 PM
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
-- Database: `lab_workflow_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `registration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_id`, `patient_name`, `date_of_birth`, `gender`, `contact_number`, `email`, `address`, `registration_date`) VALUES
(1, 'Amit Patel\r\n', '2001-05-10', 'Male', '9876543210', 'amit@gmail.com\r\n', 'Ahmedabad', '2026-02-03'),
(2, 'Neha Singh', '1999-09-22', 'Female', '9876501234', 'neha@gmail.com', 'Surat', '2026-02-03'),
(3, 'Snehil', NULL, 'Female', NULL, NULL, NULL, '2026-02-03'),
(4, 'Arnav', NULL, 'Male', NULL, NULL, NULL, '2026-02-05'),
(6, 'Ria Rana', '2006-12-14', 'Female', '9464547083', '2006ria@gmail.com', 'Naya Gaon', '2026-03-29'),
(7, 'Aryan', '2006-05-16', 'Male', '454665465', '2006aryan@gmail.com', 'hjrhgkjfhb', '2026-04-01'),
(8, 'Ram Kumar', '2005-11-16', 'Female', '23546534', '2005ram@gmail.com', 'kjenfkjefkj', '2026-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `generated_date` datetime NOT NULL,
  `remarks` text DEFAULT NULL,
  `approved_by` int(11) NOT NULL,
  `sample_test_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sample`
--

CREATE TABLE `sample` (
  `sample_id` int(11) NOT NULL,
  `sample_type` varchar(50) NOT NULL,
  `collection_date` datetime NOT NULL,
  `sample_status` varchar(30) NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sample`
--

INSERT INTO `sample` (`sample_id`, `sample_type`, `collection_date`, `sample_status`, `patient_id`) VALUES
(1, 'Blood', '2026-02-03 18:03:02', 'Collected', 1),
(2, 'Blood', '2026-02-03 18:04:39', 'Collected', 2),
(3, 'Blood', '2026-02-04 00:45:05', 'Collected', 1),
(4, 'X-ray', '2026-02-04 01:23:34', 'Collected', 3),
(5, 'blood', '2026-03-29 01:33:08', 'Pending', 6),
(6, 'urine', '2026-04-01 09:40:42', 'Pending', 7),
(7, 'mri', '2026-04-04 14:27:33', 'Pending', 8);

-- --------------------------------------------------------

--
-- Table structure for table `sample_test`
--

CREATE TABLE `sample_test` (
  `sample_test_id` int(11) NOT NULL,
  `sample_id` int(11) DEFAULT NULL,
  `test_id` int(11) DEFAULT NULL,
  `result_value` varchar(50) DEFAULT NULL,
  `performed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sample_test`
--

INSERT INTO `sample_test` (`sample_test_id`, `sample_id`, `test_id`, `result_value`, `performed_at`) VALUES
(1, 5, 1, '90', '2026-04-01 09:41:15'),
(2, 6, 1, '56', '2026-04-04 14:19:17'),
(3, 7, 1, NULL, NULL),
(4, 7, 3, '54', '2026-04-04 15:36:14'),
(5, 7, 2, '75', '2026-04-04 14:28:00');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `test_id` int(11) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `test_description` text DEFAULT NULL,
  `normal_range` varchar(50) DEFAULT NULL,
  `test_cost` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`test_id`, `test_name`, `test_description`, `normal_range`, `test_cost`) VALUES
(1, 'Blood Sugar', 'Glucose level test', '70–110 mg/dL', 200.00),
(2, 'Hemoglobin', 'Hb level test', '12–16 g/dL', 300.00),
(3, 'Cholesterol', 'Cholesterol level test', '<200 mg/dL', 400.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Technician','Doctor','Receptionist') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Rohit Sharma', 'rohit.tech', 'pass123', 'Technician', '2026-02-03 22:24:44'),
(2, 'Anita Verma', 'anita.tech', 'pass123', 'Technician', '2026-02-03 22:25:36'),
(3, 'Dr Rishav Thakur', 'rishav.doc', 'doc123', 'Doctor', '2026-02-03 22:26:18'),
(4, 'Yogita', 'yogita.reception', 'reception123', 'Receptionist', '2026-02-04 01:18:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `sample`
--
ALTER TABLE `sample`
  ADD PRIMARY KEY (`sample_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `sample_test`
--
ALTER TABLE `sample_test`
  ADD PRIMARY KEY (`sample_test_id`),
  ADD UNIQUE KEY `sample_id` (`sample_id`,`test_id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sample`
--
ALTER TABLE `sample`
  MODIFY `sample_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sample_test`
--
ALTER TABLE `sample_test`
  MODIFY `sample_test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `sample`
--
ALTER TABLE `sample`
  ADD CONSTRAINT `sample_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

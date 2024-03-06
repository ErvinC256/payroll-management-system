-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2023 at 04:12 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payroll_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `allowances`
--

CREATE TABLE `allowances` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `base_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allowances`
--

INSERT INTO `allowances` (`id`, `name`, `type`, `base_amount`) VALUES
(1, 'Food Allowance', 'Fixed', '300.00'),
(2, 'Transport Allowance', 'Fixed', '150.00'),
(3, 'Housing Allowance', 'Fixed', '450.00'),
(4, 'Medical Allowance', 'Varied', '0.00'),
(5, 'Education Allowance', 'Varied', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `deleted_allowances`
--

CREATE TABLE `deleted_allowances` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deleted_allowances`
--

INSERT INTO `deleted_allowances` (`id`, `name`, `reason`) VALUES
(1, 'Travel Allowance', 'No longer required due to remote work policy'),
(2, 'Meal Allowance', 'Replaced with a comprehensive meal plan'),
(3, 'Car Allowance', 'Company no longer provides car benefits'),
(4, 'Rent Allowance', 'Employee purchased their own house'),
(5, 'Phone Allowance', 'Revised employee benefits package'),
(6, 'Internet Allowance', 'Cost-cutting measures'),
(7, 'Training Allowance', 'Budget reallocation'),
(8, 'Leave Allowance', 'Changes in leave policy'),
(9, 'Uniform Allowance', 'Uniforms no longer required'),
(10, 'Childcare Allowance', 'Changes in childcare benefits');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`code`, `name`) VALUES
('AFD', 'Accounting and Finance'),
('ENG', 'Engineering'),
('HR', 'Human Resources'),
('ITD', 'Information Technology'),
('MKGT', 'Marketing'),
('RD', 'Research and Development');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `department_code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `bonus_eligible` tinyint(1) NOT NULL,
  `overtime_rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `department_code`, `name`, `bonus_eligible`, `overtime_rate`) VALUES
(1, 'AFD', 'Accountant', 0, '45.00'),
(2, 'AFD', 'Financial Analyst', 1, '45.00'),
(3, 'AFD', 'Auditor', 1, '45.00'),
(4, 'ENG', 'Aerospace Engineer', 0, '50.00'),
(5, 'ENG', 'Mechanical Engineer', 1, '45.00'),
(6, 'ENG', 'Electrical Engineer', 1, '45.00'),
(7, 'HR', 'HR Manager', 0, '35.00'),
(8, 'HR', 'Recruiter', 1, '35.00'),
(9, 'HR', 'Training Specialist', 0, '35.00'),
(10, 'ITD', 'IT Manager', 0, '50.00'),
(11, 'ITD', 'System Administrator', 1, '40.00'),
(12, 'ITD', 'Network Engineer', 1, '40.00'),
(13, 'MKGT', 'Marketing Manager', 1, '35.00'),
(14, 'MKGT', 'Brand Manager', 0, '40.00'),
(15, 'MKGT', 'Sales Representative', 1, '35.00');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_no` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `nric` varchar(255) NOT NULL,
  `date_hired` date NOT NULL,
  `designation` int(11) DEFAULT NULL,
  `basic_salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_no`, `firstname`, `lastname`, `gender`, `nric`, `date_hired`, `designation`, `basic_salary`) VALUES
(1, 'E0001', 'Aarav', 'Patel', 'Male', 'S1234567A', '2022-01-01', 1, '8900.00'),
(2, 'E0002', 'Olivia', 'Chen', 'Female', 'S1234568B', '2022-02-01', 2, '6501.00'),
(3, 'E0003', 'Liam', 'Kim', 'Male', 'S1234569C', '2022-03-01', 3, '7000.00'),
(4, 'E0004', 'Emma', 'Nguyen', 'Female', 'S1234570D', '2022-04-01', 4, '8000.00'),
(5, 'E0005', 'Noah', 'Garcia', 'Male', 'S1234571E', '2022-05-01', 5, '9000.00'),
(6, 'E0006', 'Ava', 'Martinez', 'Female', 'S1234572F', '2022-06-01', 6, '10000.00'),
(7, 'E0007', 'Oliver', 'Lee', 'Male', 'S1234573G', '2022-07-01', 7, '10900.00'),
(8, 'E0008', 'Sophia', 'Hernandez', 'Female', 'S1234574H', '2022-08-01', 8, '7500.00'),
(9, 'E0009', 'William', 'Smith', 'Male', 'S1234575I', '2022-09-01', 9, '7500.00'),
(10, 'E0010', 'Stephanie', 'Tan', 'Female', 'S1234518I', '2022-09-15', 10, '8000.00'),
(11, 'E0011', 'Ethan', 'Jones', 'Male', 'S1234577K', '2022-11-01', 11, '14900.00'),
(12, 'E0012', 'Mia', 'Miller', 'Female', 'S1234578L', '2022-12-01', 12, '16000.00'),
(13, 'E0013', 'James', 'Davis', 'Male', 'S1234579M', '2023-01-01', 13, '17000.00'),
(14, 'E0014', 'Charlotte', 'Garcia', 'Female', 'S1234580N', '2023-02-01', 14, '18000.00'),
(15, 'E0015', 'Benjamin', 'Rodriguez', 'Male', 'S1234581O', '2023-03-01', 15, '19000.00');

-- --------------------------------------------------------

--
-- Table structure for table `employee_allowances`
--

CREATE TABLE `employee_allowances` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `allowance_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_allowances`
--

INSERT INTO `employee_allowances` (`id`, `employee_id`, `allowance_id`, `amount`) VALUES
(1, 1, 2, '150.00'),
(2, 2, 2, '150.00'),
(3, 3, 2, '150.00'),
(4, 4, 2, '150.00'),
(5, 5, 2, '150.00'),
(6, 6, 2, '150.00'),
(7, 7, 2, '150.00'),
(8, 8, 2, '150.00'),
(9, 9, 2, '150.00'),
(10, 10, 2, '150.00'),
(11, 11, 2, '150.00'),
(12, 12, 2, '150.00'),
(13, 13, 2, '150.00'),
(14, 14, 2, '150.00'),
(15, 15, 2, '150.00'),
(16, 4, 4, '100.00'),
(17, 5, 4, '150.00'),
(18, 6, 4, '50.00'),
(19, 12, 4, '50.00'),
(20, 1, 3, '450.00');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `payroll_period` varchar(255) NOT NULL,
  `earnings` decimal(10,2) NOT NULL,
  `deductions` decimal(10,2) NOT NULL,
  `net_pay` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `reference_no`, `employee_id`, `payroll_period`, `earnings`, `deductions`, `net_pay`, `status`) VALUES
(1, 'REF001', 1, '2023-01', '825.00', '3403.75', '6321.25', 'Approved'),
(2, 'REF002', 2, '2023-01', '450.00', '2432.85', '4518.15', 'Approved'),
(3, 'REF003', 3, '2023-01', '417.50', '2596.13', '4821.38', 'Approved'),
(4, 'REF004', 4, '2023-01', '400.00', '2940.00', '5460.00', 'Approved'),
(5, 'REF005', 5, '2023-01', '545.00', '3340.75', '6204.25', 'Approved'),
(6, 'REF006', 6, '2023-01', '400.00', '3640.00', '6760.00', 'Pending'),
(7, 'REF007', 7, '2023-01', '150.00', '3867.50', '7182.50', 'Pending'),
(8, 'REF008', 8, '2023-01', '600.00', '2835.00', '5265.00', 'Pending'),
(9, 'REF009', 9, '2023-01', '325.00', '2738.75', '5086.25', 'Pending'),
(10, 'REF010', 10, '2023-01', '150.00', '2852.50', '5297.50', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_items`
--

CREATE TABLE `payroll_items` (
  `id` int(11) NOT NULL,
  `payroll_id` int(11) DEFAULT NULL,
  `item` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_items`
--

INSERT INTO `payroll_items` (`id`, `payroll_id`, `item`, `amount`) VALUES
(1, 1, 'Basic Salary', '8900.00'),
(2, 1, 'Overtime', '225.00'),
(3, 1, 'Allowance', '600.00'),
(4, 1, 'Income Tax', '1945.00'),
(5, 1, 'Social Security', '1458.75'),
(6, 2, 'Basic Salary', '6501.00'),
(7, 2, 'Bonus', '300.00'),
(8, 2, 'Allowance', '150.00'),
(9, 2, 'Income Tax', '1390.20'),
(10, 2, 'Social Security', '1042.65'),
(11, 3, 'Basic Salary', '7000.00'),
(12, 3, 'Bonus', '200.00'),
(13, 3, 'Overtime', '67.50'),
(14, 3, 'Allowance', '150.00'),
(15, 3, 'Income Tax', '1483.50'),
(16, 3, 'Social Security', '1112.63'),
(17, 4, 'Basic Salary', '8000.00'),
(18, 4, 'Overtime', '150.00'),
(19, 4, 'Allowance', '250.00'),
(20, 4, 'Income Tax', '1680.00'),
(21, 4, 'Social Security', '1260.00'),
(22, 5, 'Basic Salary', '9000.00'),
(23, 5, 'Bonus', '20.00'),
(24, 5, 'Overtime', '225.00'),
(25, 5, 'Allowance', '300.00'),
(26, 5, 'Income Tax', '1909.00'),
(27, 5, 'Social Security', '1431.75'),
(28, 6, 'Basic Salary', '10000.00'),
(29, 6, 'Bonus', '200.00'),
(30, 6, 'Allowance', '200.00'),
(31, 6, 'Income Tax', '2080.00'),
(32, 6, 'Social Security', '1560.00'),
(33, 7, 'Basic Salary', '10900.00'),
(34, 7, 'Allowance', '150.00'),
(35, 7, 'Income Tax', '2210.00'),
(36, 7, 'Social Security', '1657.50'),
(37, 8, 'Basic Salary', '7500.00'),
(38, 8, 'Bonus', '100.00'),
(39, 8, 'Overtime', '350.00'),
(40, 8, 'Allowance', '150.00'),
(41, 8, 'Income Tax', '1620.00'),
(42, 8, 'Social Security', '1215.00'),
(43, 9, 'Basic Salary', '7500.00'),
(44, 9, 'Overtime', '175.00'),
(45, 9, 'Allowance', '150.00'),
(46, 9, 'Income Tax', '1565.00'),
(47, 9, 'Social Security', '1173.75'),
(48, 10, 'Basic Salary', '8000.00'),
(49, 10, 'Allowance', '150.00'),
(50, 10, 'Income Tax', '1630.00'),
(51, 10, 'Social Security', '1222.50');

-- --------------------------------------------------------

--
-- Table structure for table `temp_log`
--

CREATE TABLE `temp_log` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `operation` varchar(255) NOT NULL,
  `object` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `page` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `temp_log`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allowances`
--
ALTER TABLE `allowances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deleted_allowances`
--
ALTER TABLE `deleted_allowances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_code` (`department_code`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designation` (`designation`);

--
-- Indexes for table `employee_allowances`
--
ALTER TABLE `employee_allowances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `allowance_id` (`allowance_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `payroll_items`
--
ALTER TABLE `payroll_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_id` (`payroll_id`);

--
-- Indexes for table `temp_log`
--
ALTER TABLE `temp_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `allowances`
--
ALTER TABLE `allowances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `deleted_allowances`
--
ALTER TABLE `deleted_allowances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `employee_allowances`
--
ALTER TABLE `employee_allowances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payroll_items`
--
ALTER TABLE `payroll_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `temp_log`
--
ALTER TABLE `temp_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_ibfk_1` FOREIGN KEY (`department_code`) REFERENCES `departments` (`code`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`designation`) REFERENCES `designations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_allowances`
--
ALTER TABLE `employee_allowances`
  ADD CONSTRAINT `employee_allowances_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_allowances_ibfk_2` FOREIGN KEY (`allowance_id`) REFERENCES `allowances` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_items`
--
ALTER TABLE `payroll_items`
  ADD CONSTRAINT `payroll_items_ibfk_1` FOREIGN KEY (`payroll_id`) REFERENCES `payroll` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

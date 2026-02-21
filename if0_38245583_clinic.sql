-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql305.infinityfree.com
-- Generation Time: Jun 04, 2025 at 10:37 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_38245583_clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `ACCOUNT`
--

CREATE TABLE `ACCOUNT` (
  `id` varchar(9) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','staff') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ACCOUNT`
--

INSERT INTO `ACCOUNT` (`id`, `name`, `email`, `password`, `role`) VALUES
('21-407495', 'Alberto Catapang', 'alberto.catapang@my.jru.edu', '96a56fe61531e9a947f9faf6de285f6da35f494e8a5b68a13e37f785b511edfa', 'admin'),
('23-261028', 'John Keneth Plana', 'johnkeneth.plana@my.jru.edu', '3024f4d015f7ebca87b2041b7a8bc15bb1fe16521e7a94c4e1e53037571adde5', 'student'),
('23-261114', 'Leonardo Antero Yoro ', 'leonardoantero.yoro@my.jru.edu', '32eb6cdfe2abd25c17bde404e04bf83762c56f60f6db7785cea7ad2dd88987ce', 'admin'),
('15-246937', 'Evangelo Lazo', 'evangelo.lazo@my.jru.edu', 'eecbf929f7f5d9ede19c9c839ce39e1f57b3ed7661c07cf74981e9d598472aef', 'staff'),
('24-253216', 'John Dela Cruz', 'john.delacruz@my.jru.edu', 'eb3ffe069a75becf22262458c50f5a8d5c9d57065e71c61aa6562b379a3ac0de', 'admin'),
('24-261113', 'Alfonso Manabat', 'alfonso.manabat@my.jru.edu', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'staff'),
('22-982412', 'Leandro Tirso', 'leandro.tirso@my.jru.edu', '07ef19e7ce31290a3472d48b9d1432965c7bdd2d5604ff5a2096b18f64d4ed42', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` varchar(9) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `name`, `email`, `password`, `role`) VALUES
('15-246937', 'Evangelo Lazo', 'evangelo.lazo@my.jru.edu', 'eecbf929f7f5d9ede19c9c839ce39e1f57b3ed7661c07cf74981e9d598472aef', 'staff'),
('21-407495', 'Alberto Catapang', 'alberto.catapang@my.jru.edu', '96a56fe61531e9a947f9faf6de285f6da35f494e8a5b68a13e37f785b511edfa', 'admin'),
('23-261028', 'John Keneth Plana', 'johnkeneth.plana@my.jru.edu', '3024f4d015f7ebca87b2041b7a8bc15bb1fe16521e7a94c4e1e53037571adde5', 'student'),
('23-261114', 'Leonardo Antero Yoro ', 'leonardoantero.yoro@my.jru.edu', '6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
('21-407495', 'Alberto Catapang', 'alberto.catapang@my.jru.edu', '96a56fe61531e9a947f9faf6de285f6da35f494e8a5b68a13e37f785b511edfa'),
('23-261114', 'Leonardo Antero Yoro', 'leonardoantero.yoro@my.jru.edu', '32eb6cdfe2abd25c17bde404e04bf83762c56f60f6db7785cea7ad2dd88987ce'),
('84', 'Lec', 'singajiyu10@gmail.com', '44c8031cb036a7350d8b9b8603af662a4b9cdbd2f96e8d5de5af435c9c35da69');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('active','done','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `appointment_time` time NOT NULL,
  `reason` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `user_id`, `appointment_date`, `status`, `created_at`, `updated_at`, `appointment_time`, `reason`) VALUES
(1, 123, '2025-04-18 00:00:00', 'active', '2025-04-17 15:54:24', '2025-04-19 03:22:13', '23:40:00', 'Headache'),
(2, 123, '2025-04-18 00:00:00', 'active', '2025-04-17 15:55:04', '2025-04-19 03:22:13', '23:40:00', 'Headache'),
(3, 123, '2025-04-18 00:00:00', 'active', '2025-04-17 15:55:43', '2025-04-19 03:22:13', '23:40:00', 'Headache'),
(4, 123, '2025-04-18 00:00:00', 'active', '2025-04-17 15:56:10', '2025-04-19 03:22:13', '23:40:00', 'Headache'),
(5, 123, '2025-04-18 00:00:00', 'active', '2025-04-17 15:57:58', '2025-04-19 03:22:13', '23:40:00', 'Headache'),
(6, 123, '2025-04-18 00:00:00', 'active', '2025-04-17 15:58:15', '2025-04-19 03:22:13', '23:40:00', 'Headache'),
(7, 123, '2025-04-18 00:00:00', 'active', '2025-04-17 15:58:47', '2025-04-19 03:22:13', '23:40:00', 'Headache'),
(8, 123, '2025-04-19 00:00:00', 'done', '2025-04-17 16:04:57', '2025-04-19 05:52:57', '13:05:00', 'Sprain'),
(9, 123, '2025-04-19 00:00:00', 'active', '2025-04-17 16:05:38', '2025-04-19 05:38:10', '13:05:00', 'Sprain'),
(10, 123, '2025-04-19 00:00:00', 'active', '2025-04-17 16:06:02', '2025-04-19 05:38:10', '13:05:00', 'Sprain'),
(11, 123, '2025-04-19 00:00:00', 'done', '2025-04-17 16:07:32', '2025-04-19 05:54:33', '13:05:00', 'Sprain'),
(12, 123, '2025-04-19 00:00:00', 'active', '2025-04-17 16:09:08', '2025-04-19 05:38:10', '15:08:00', 'depression'),
(13, 123, '2025-04-21 00:00:00', 'active', '2025-04-17 16:13:31', '2025-04-19 03:22:13', '16:00:00', 'Knee Scratch'),
(14, 123, '2025-04-21 00:00:00', 'active', '2025-04-17 16:14:37', '2025-04-19 03:22:13', '09:14:00', 'Knee'),
(15, 123, '2025-04-18 00:00:00', 'done', '2025-04-17 16:18:03', '2025-05-04 06:32:20', '16:30:00', 'broken heart'),
(16, 85, '2025-04-19 00:00:00', 'done', '2025-04-19 02:53:34', '2025-05-04 06:32:33', '15:00:00', 'flu\r\n'),
(17, 85, '2025-04-26 00:00:00', 'cancelled', '2025-04-26 03:18:24', '2025-04-26 03:59:33', '14:00:00', 'light cold'),
(18, 85, '2025-04-26 00:00:00', 'done', '2025-04-26 03:56:33', '2025-05-04 06:34:34', '13:00:00', 'light cold'),
(19, 85, '2025-04-26 00:00:00', 'done', '2025-04-26 04:04:08', '2025-05-04 06:34:38', '11:58:00', 'test'),
(20, 85, '2025-04-19 00:00:00', 'done', '2025-04-26 04:19:31', '2025-05-04 06:34:42', '14:14:00', 'wqeqwe'),
(21, 85, '2025-04-26 00:00:00', 'done', '2025-04-26 04:21:01', '2025-05-04 06:34:46', '12:15:00', 'test'),
(22, 123, '2025-05-04 00:00:00', 'active', '2025-05-04 05:08:50', '2025-05-04 05:22:33', '13:08:00', 'Test'),
(23, 85, '2025-05-04 00:00:00', 'done', '2025-05-04 05:46:11', '2025-05-04 06:34:49', '13:45:00', 'test'),
(24, 85, '2025-05-05 00:00:00', 'done', '2025-05-04 05:51:17', '2025-05-04 06:33:47', '13:51:00', 'test 2'),
(28, 85, '2025-05-04 00:00:00', 'active', '2025-05-04 06:40:27', '2025-05-04 06:40:27', '14:40:00', 'test 4'),
(26, 85, '2025-05-04 00:00:00', 'done', '2025-05-04 06:11:20', '2025-05-04 06:12:39', '14:04:00', 'barbeque sauce');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `name`, `email`, `password`) VALUES
('15-246937', 'Evangelo Lazo', 'evangelo.lazo@my.jru.edu', 'eecbf929f7f5d9ede19c9c839ce39e1f57b3ed7661c07cf74981e9d598472aef'),
('33', 'Dr. Smith', 'drsmith@example.com', '1');

-- --------------------------------------------------------

--
-- Table structure for table `medical_record`
--

CREATE TABLE `medical_record` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `symptoms` text NOT NULL,
  `first_aid` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `certificate` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `medical_record`
--

INSERT INTO `medical_record` (`id`, `appointment_id`, `user_id`, `doctor_id`, `symptoms`, `first_aid`, `remarks`, `prescription`, `certificate`, `created_at`) VALUES
(15, 11, 123, 1, 'werwer', 'werwer', 'werwer', 'werwer', '', '2025-04-19 05:54:33'),
(16, 26, 85, 1, 'tset', 'test', 'seek', 'adn destory', 'Medical Certificate:\n\nThis is to certify that the patient, Uno, has been examined on the date of this check-up.\n\nSymptoms observed: tset\nFirst Aid administered: test\nRemarks: seek\n\nPrescription: adn destory\n\nIt is advised that the patient take adequate rest and limit physical activity to promote recovery.\nPlease follow the prescribed medication and consult with the doctor if symptoms persist.\n\nThank you for trusting JRU Clinic.\n\n', '2025-05-04 06:12:38'),
(14, 8, 123, 1, 'a', 'b', 'c', 'd', 'Medical Certificate:\n\nThis is to certify that the patient, Uno, has been examined on the date of this check-up.\n\nSymptoms observed: a\nFirst Aid administered: b\nRemarks: c\n\nPrescription: d\n\nIt is advised that the patient take adequate rest and limit physical activity to promote recovery.\nPlease follow the prescribed medication and consult with the doctor if symptoms persist.\n\nThank you for trusting JRU Clinic.\n\n', '2025-04-19 05:52:55');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

CREATE TABLE `otp_verification` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','verified','expired') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_verification`
--

INSERT INTO `otp_verification` (`id`, `user_id`, `otp`, `created_at`, `expires_at`, `status`) VALUES
(7, '23-261114', '151306', '2025-03-04 08:02:28', '2025-03-04 08:07:28', 'pending'),
(8, '23-261114', '238953', '2025-03-04 08:08:25', '2025-03-04 08:13:25', 'verified'),
(9, '23-261114', '578119', '2025-03-04 08:11:09', '2025-03-04 08:16:09', 'verified'),
(10, '23-261114', '248846', '2025-03-04 08:11:46', '2025-03-04 08:16:46', 'verified'),
(11, '23-261114', '547312', '2025-03-04 08:12:54', '2025-03-04 08:17:54', 'verified'),
(12, '23-261114', '986452', '2025-03-04 08:13:35', '2025-03-04 08:18:35', 'verified'),
(13, '23-261114', '758191', '2025-03-04 08:25:08', '2025-03-04 08:30:08', 'verified'),
(14, '23-261114', '152994', '2025-03-05 19:23:08', '2025-03-05 19:28:08', 'verified'),
(15, '23-261028', '389355', '2025-03-24 09:06:51', '2025-03-24 09:11:51', 'pending'),
(16, '23-261114', '211702', '2025-03-24 09:08:59', '2025-03-24 09:13:59', 'verified'),
(17, '15-246937', '229248', '2025-03-24 09:10:58', '2025-03-24 09:15:58', 'verified'),
(18, '15-246937', '459140', '2025-03-24 09:28:11', '2025-03-24 09:33:11', 'verified'),
(19, '23-261028', '432369', '2025-03-24 09:45:52', '2025-03-24 09:50:52', 'verified'),
(20, '23-261028', '675514', '2025-03-24 09:52:13', '2025-03-24 09:57:13', 'verified'),
(21, '23-261028', '255433', '2025-03-24 10:07:53', '2025-03-24 10:12:53', 'verified'),
(22, '23-261114', '125500', '2025-03-24 10:14:33', '2025-03-24 10:19:33', 'pending'),
(23, '23-261114', '588636', '2025-03-24 11:47:01', '2025-03-24 11:52:01', 'verified'),
(24, '23-261114', '249730', '2025-03-24 11:51:14', '2025-03-24 11:56:14', 'verified'),
(25, '23-261114', '225322', '2025-03-24 11:57:23', '2025-03-24 12:02:23', 'verified'),
(26, '23-261114', '914017', '2025-03-24 11:59:55', '2025-03-24 12:04:55', 'pending'),
(27, '23-261114', '528748', '2025-03-24 12:45:56', '2025-03-24 12:50:56', 'verified'),
(28, '21-407495', '898419', '2025-03-24 12:47:48', '2025-03-24 12:52:48', 'pending'),
(29, '23-261028', '999292', '2025-03-24 13:10:49', '2025-03-24 13:15:49', 'pending'),
(30, '23-261028', '377233', '2025-03-24 13:23:52', '2025-03-24 13:28:52', 'verified'),
(31, '23-261028', '818421', '2025-03-24 13:26:03', '2025-03-24 13:31:03', 'verified'),
(32, '23-261114', '372555', '2025-04-12 06:17:15', '2025-04-12 06:22:15', 'verified'),
(33, '15-246937', '424014', '2025-04-12 06:18:58', '2025-04-12 06:23:58', 'verified'),
(34, '23-261114', '521513', '2025-04-12 06:41:09', '2025-04-12 06:46:09', 'verified'),
(35, '15-246937', '782983', '2025-04-12 06:42:06', '2025-04-12 06:47:06', 'verified'),
(36, '23-261028', '522997', '2025-04-12 06:43:58', '2025-04-12 06:48:58', 'verified'),
(37, '23-261114', '638461', '2025-04-13 08:29:50', '2025-04-13 08:34:50', 'verified'),
(38, '23-261114', '347297', '2025-04-13 08:39:00', '2025-04-13 08:44:00', 'verified'),
(39, '23-261114', '593729', '2025-04-13 08:42:20', '2025-04-13 08:47:20', 'verified'),
(40, '23-261114', '415265', '2025-04-13 08:48:54', '2025-04-13 08:53:54', 'pending'),
(41, '11223', '538351', '2025-04-13 08:49:59', '2025-04-13 08:54:59', 'pending'),
(42, '23-261114', '230885', '2025-04-13 09:03:08', '2025-04-13 09:08:08', 'verified'),
(43, '23-261114', '962075', '2025-04-13 09:12:22', '2025-04-13 09:17:22', 'verified'),
(44, '23-261114', '991132', '2025-04-13 09:18:12', '2025-04-13 09:23:12', 'verified'),
(45, '23-261114', '841793', '2025-04-17 18:21:21', '2025-04-17 18:26:21', 'verified'),
(46, '123', '545910', '2025-04-17 18:24:43', '2025-04-17 18:29:43', 'pending'),
(47, '123', '578635', '2025-04-17 18:24:54', '2025-04-17 18:29:54', 'verified'),
(48, '84', '504060', '2025-04-19 05:25:25', '2025-04-19 05:30:25', 'verified'),
(49, '84', '557258', '2025-04-19 05:26:45', '2025-04-19 05:31:45', 'verified'),
(50, '84', '832928', '2025-04-19 05:32:28', '2025-04-19 05:37:28', 'verified'),
(51, '84', '326258', '2025-04-19 05:33:45', '2025-04-19 05:38:45', 'verified'),
(52, '84', '829374', '2025-04-19 05:40:36', '2025-04-19 05:45:36', 'verified'),
(53, '84', '106162', '2025-04-19 05:41:12', '2025-04-19 05:46:12', 'verified'),
(54, '123', '981277', '2025-04-19 05:51:35', '2025-04-19 05:56:35', 'verified'),
(55, '85', '713285', '2025-04-19 05:52:57', '2025-04-19 05:57:57', 'verified'),
(56, '84', '434629', '2025-04-19 06:51:03', '2025-04-19 06:56:03', 'verified'),
(57, '123', '334193', '2025-04-19 07:14:22', '2025-04-19 07:19:22', 'verified'),
(58, '84', '505183', '2025-04-19 08:33:59', '2025-04-19 08:38:59', 'verified'),
(59, '84', '153953', '2025-04-19 08:40:05', '2025-04-19 08:45:05', 'verified'),
(60, '84', '773323', '2025-04-19 08:43:56', '2025-04-19 08:48:56', 'verified'),
(61, '123', '878476', '2025-04-19 08:55:37', '2025-04-19 09:00:37', 'verified'),
(62, '85', '297160', '2025-04-19 08:56:45', '2025-04-19 09:01:45', 'verified'),
(63, '23-261114', '240766', '2025-04-19 09:06:28', '2025-04-19 09:11:28', 'verified'),
(64, '85', '844831', '2025-04-19 10:32:34', '2025-04-19 10:37:34', 'verified'),
(65, '84', '253658', '2025-04-26 06:08:40', '2025-04-26 06:13:40', 'verified'),
(66, '23-261114', '996209', '2025-04-26 06:09:37', '2025-04-26 06:14:37', 'verified'),
(67, '85', '820229', '2025-04-26 06:10:02', '2025-04-26 06:15:02', 'verified'),
(68, '123', '120210', '2025-04-26 06:11:35', '2025-04-26 06:16:35', 'verified'),
(69, '123', '609911', '2025-04-26 06:13:04', '2025-04-26 06:18:04', 'verified'),
(70, '85', '865667', '2025-04-26 06:17:39', '2025-04-26 06:22:39', 'verified'),
(71, '85', '801345', '2025-04-26 06:54:17', '2025-04-26 06:59:17', 'verified'),
(72, '123', '608603', '2025-04-26 07:01:51', '2025-04-26 07:06:51', 'verified'),
(73, '85', '431910', '2025-04-26 07:03:32', '2025-04-26 07:08:32', 'verified'),
(74, '123', '173262', '2025-04-26 07:04:18', '2025-04-26 07:09:18', 'verified'),
(75, '23-261114', '866089', '2025-04-26 07:08:41', '2025-04-26 07:13:41', 'verified'),
(76, '23-261114', '218938', '2025-04-26 07:16:30', '2025-04-26 07:21:30', 'verified'),
(77, '123', '886736', '2025-04-26 07:17:10', '2025-04-26 07:22:10', 'verified'),
(78, '85', '855026', '2025-04-26 07:18:58', '2025-04-26 07:23:58', 'verified'),
(79, '123', '436670', '2025-04-26 07:19:47', '2025-04-26 07:24:47', 'verified'),
(80, '85', '208728', '2025-04-26 07:20:28', '2025-04-26 07:25:28', 'verified'),
(81, '123', '217497', '2025-04-26 07:21:10', '2025-04-26 07:26:10', 'verified'),
(82, '123', '651500', '2025-05-04 08:06:58', '2025-05-04 08:11:58', 'verified'),
(83, '85', '392940', '2025-05-04 08:07:04', '2025-05-04 08:12:04', 'verified'),
(84, '123', '905191', '2025-05-04 08:08:21', '2025-05-04 08:13:21', 'verified'),
(85, '23-261114', '227358', '2025-05-04 08:29:48', '2025-05-04 08:34:48', 'verified'),
(86, '23-261114', '680750', '2025-05-04 08:44:14', '2025-05-04 08:49:14', 'verified'),
(87, '85', '618847', '2025-05-04 08:44:37', '2025-05-04 08:49:37', 'verified'),
(88, '23-261114', '991160', '2025-05-03 18:13:39', '2025-05-03 18:18:39', 'pending'),
(89, '85', '139763', '2025-05-03 18:23:12', '2025-05-03 18:28:12', 'pending'),
(90, '85', '826531', '2025-05-03 18:23:50', '2025-05-03 18:28:50', 'pending'),
(91, '85', '896458', '2025-05-03 18:25:45', '2025-05-03 18:30:45', 'verified'),
(92, '123', '692107', '2025-05-03 18:41:00', '2025-05-03 18:46:00', 'pending'),
(93, '23-261114', '264630', '2025-05-03 18:46:53', '2025-05-03 18:51:53', 'verified'),
(94, '23-261114', '177303', '2025-05-05 04:47:15', '2025-05-05 04:52:15', 'verified');

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE `system_log` (
  `id` int(11) NOT NULL,
  `account_id` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `system_log`
--

INSERT INTO `system_log` (`id`, `account_id`, `role`, `ip_address`, `login_time`) VALUES
(1, '23-261114', 'admin', '112.210.226.149', '2025-04-12 22:34:51'),
(2, '23-261114', 'admin', '112.210.226.149', '2025-04-12 22:36:53'),
(3, '23-261114', 'admin', '112.210.226.149', '2025-04-12 22:38:01'),
(4, '23-261114', 'admin', '112.210.226.149', '2025-04-12 23:18:12'),
(5, '23-261114', 'admin', '112.210.226.149', '2025-04-17 08:21:21'),
(6, '123', 'user', '112.210.226.149', '2025-04-17 08:24:43'),
(7, '123', 'user', '112.210.226.149', '2025-04-17 08:24:54'),
(8, '84', 'user', '112.210.226.149', '2025-04-18 19:25:24'),
(9, '84', 'doctor', '112.210.226.149', '2025-04-18 19:26:45'),
(10, '84', 'doctor', '112.210.226.149', '2025-04-18 19:32:28'),
(11, '84', 'doctor', '112.210.226.149', '2025-04-18 19:33:44'),
(12, '84', 'doctor', '112.210.226.149', '2025-04-18 19:40:36'),
(13, '84', 'doctor', '112.210.226.149', '2025-04-18 19:41:12'),
(14, '123', 'user', '112.210.226.149', '2025-04-18 19:51:34'),
(15, '85', 'user', '112.210.226.149', '2025-04-18 19:52:56'),
(16, '84', 'doctor', '112.210.226.149', '2025-04-18 20:51:02'),
(17, '123', 'user', '112.210.226.149', '2025-04-18 21:14:22'),
(18, '84', 'doctor', '112.210.226.149', '2025-04-18 22:33:58'),
(19, '84', 'doctor', '112.210.226.149', '2025-04-18 22:40:05'),
(20, '84', 'doctor', '112.210.226.149', '2025-04-18 22:43:56'),
(21, '123', 'user', '112.210.226.149', '2025-04-18 22:55:37'),
(22, '85', 'user', '112.210.226.149', '2025-04-18 22:56:44'),
(23, '23-261114', 'admin', '112.210.226.149', '2025-04-18 23:06:27'),
(24, '85', 'user', '112.210.226.149', '2025-04-19 00:32:34'),
(25, '84', 'admin', '154.205.22.51', '2025-04-25 20:08:40'),
(26, '23-261114', 'admin', '154.205.22.51', '2025-04-25 20:09:37'),
(27, '85', 'user', '154.205.22.51', '2025-04-25 20:10:01'),
(28, '123', 'admin', '154.205.22.51', '2025-04-25 20:11:35'),
(29, '123', 'doctor', '154.205.22.51', '2025-04-25 20:13:03'),
(30, '85', 'user', '154.205.22.51', '2025-04-25 20:17:39'),
(31, '85', 'user', '154.205.22.51', '2025-04-25 20:54:17'),
(32, '123', 'doctor', '154.205.22.51', '2025-04-25 21:01:50'),
(33, '85', 'user', '154.205.22.51', '2025-04-25 21:03:31'),
(34, '123', 'doctor', '154.205.22.51', '2025-04-25 21:04:18'),
(35, '23-261114', 'admin', '154.205.22.51', '2025-04-25 21:08:41'),
(36, '23-261114', 'admin', '154.205.22.51', '2025-04-25 21:16:30'),
(37, '123', 'doctor', '154.205.22.51', '2025-04-25 21:17:10'),
(38, '85', 'user', '154.205.22.51', '2025-04-25 21:18:58'),
(39, '123', 'doctor', '154.205.22.51', '2025-04-25 21:19:47'),
(40, '85', 'user', '154.205.22.51', '2025-04-25 21:20:27'),
(41, '123', 'doctor', '154.205.22.51', '2025-04-25 21:21:10'),
(42, '123', 'doctor', '112.210.226.149', '2025-05-03 22:06:58'),
(43, '85', 'user', '112.210.226.149', '2025-05-03 22:07:04'),
(44, '123', 'doctor', '112.210.226.149', '2025-05-03 22:08:21'),
(45, '23-261114', 'admin', '112.210.226.149', '2025-05-03 22:29:47'),
(46, '23-261114', 'admin', '112.210.226.149', '2025-05-03 22:44:14'),
(47, '85', 'user', '112.210.226.149', '2025-05-03 22:44:36'),
(48, '23-261114', 'admin', '112.210.226.149', '2025-05-04 14:13:39'),
(49, '85', 'user', '112.210.226.149', '2025-05-04 14:23:11'),
(50, '85', 'user', '112.210.226.149', '2025-05-04 14:23:50'),
(51, '85', 'user', '112.210.226.149', '2025-05-04 14:25:45'),
(52, '123', 'doctor', '112.210.226.149', '2025-05-04 14:40:59'),
(53, '23-261114', 'admin', '112.210.226.149', '2025-05-04 14:46:53'),
(54, '23-261114', 'admin', '112.210.226.149', '2025-05-06 00:47:15');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`) VALUES
('123', 'Uno', 'indojiyu10@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
('23-261028', 'John Keneth Plana', 'johnkeneth.plana@my.jru.edu', '3024f4d015f7ebca87b2041b7a8bc15bb1fe16521e7a94c4e1e53037571adde5'),
('85', 'Uno', 'genshinlio84@gmail.com', 'b4944c6ff08dc6f43da2e9c824669b7d927dd1fa976fadc7b456881f51bf5ccc');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ACCOUNT`
--
ALTER TABLE `ACCOUNT`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `medical_record`
--
ALTER TABLE `medical_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `otp_verification`
--
ALTER TABLE `otp_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

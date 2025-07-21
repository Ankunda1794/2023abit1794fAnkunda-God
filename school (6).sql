-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2025 at 05:36 PM
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
-- Database: `school`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date NOT NULL,
  `class` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_by` varchar(255) NOT NULL,
  `staff_id` varchar(50) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `title`, `description`, `due_date`, `class`, `subject`, `created_at`, `updated_at`, `file_path`, `uploaded_by`, `staff_id`, `uploaded_at`, `teacher_id`) VALUES
(4, 'HHHHHHHHHHH', 'HHHHHHHHHHHHHHHHH', '0000-00-00', 'S6', 'HHHHHHHHHHHHHH', '2025-07-14 19:55:29', '2025-07-14 19:55:29', 'uploads/assignments/1752522929_1752075057_ASSIGNMENT TWO (1).docx', '73', NULL, '2025-07-14 20:42:33', 0),
(5, 'TAKE AWAY ASIGNMENT', 'DO IT', '0000-00-00', 'S6', 'GEOGRAPHY', '2025-07-14 20:32:07', '2025-07-14 20:32:07', 'uploads/assignments/1752525127_1752073055_ASSIGNMENT TWO (4).docx', '73', NULL, '2025-07-14 20:42:33', 0),
(6, 'TAKE AWAY ASIGNMENT', 'DO IT', '0000-00-00', 'S6', 'GEOGRAPHY', '2025-07-14 20:34:27', '2025-07-14 20:34:27', 'uploads/assignments/1752525267_1752073055_ASSIGNMENT TWO (4).docx', '73', NULL, '2025-07-14 20:42:33', 0),
(7, 'SCIENCE FOR LIFE', 'TAKE AWAY HOME ASSIGNMENT', '0000-00-00', 'S5', 'GEOGRAPHY', '2025-07-21 07:06:14', '2025-07-21 07:06:14', 'uploads/assignments/1753081573_45555regisrered updated.docx', '85', NULL, '2025-07-21 07:06:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submissions`
--

CREATE TABLE `assignment_submissions` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_at` datetime NOT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment_submissions`
--

INSERT INTO `assignment_submissions` (`id`, `assignment_id`, `student_id`, `subject`, `file_path`, `submitted_at`, `teacher_id`) VALUES
(1, 6, 76, 'MATHS', 'uploads/submissions/1752527951_1752060348_certificate (2).pdf', '2025-07-15 00:19:11', 73),
(2, 7, 86, 'GEOGRAPHY', 'uploads/submissions/1753082238_45555regisrered updated.docx', '2025-07-21 10:17:18', 85);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class` varchar(50) NOT NULL,
  `week` varchar(7) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Late') NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `recorded_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `class`, `week`, `date`, `status`, `recorded_at`, `recorded_by`) VALUES
(1, 76, 'S6', '', '2025-07-15', 'Absent', '2025-07-15 07:53:06', 73);

-- --------------------------------------------------------

--
-- Table structure for table `bursar_notifications`
--

CREATE TABLE `bursar_notifications` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bursar_notifications`
--

INSERT INTO `bursar_notifications` (`id`, `payment_id`, `student_id`, `message`, `sent_at`, `status`) VALUES
(1, 1, 76, 'Student #76 paid UGX 20000 via Cash (Class S6). Remaining: UGX 280000', '2025-07-18 15:13:44', 'pending'),
(2, 2, 81, 'Student #81 paid UGX 275000 via Mobile Money (Class S4). Remaining: UGX 0', '2025-07-18 15:15:34', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(10) UNSIGNED NOT NULL,
  ` class_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_staff`
--

CREATE TABLE `class_staff` (
  `id` int(11) NOT NULL,
  `class_id` varchar(255) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_staff`
--

INSERT INTO `class_staff` (`id`, `class_id`, `staff_id`) VALUES
(1, 'S5', 3);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `posted_at` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `doc_type` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `signed_by` varchar(100) DEFAULT NULL,
  `signed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `doc_type`, `content`, `status`, `created_by`, `receiver_id`, `signed_by`, `signed_at`, `created_at`, `filename`) VALUES
(1, 'explusion letter', 'Other', NULL, 'Submitted', 'FAITH NATUKUNDA', NULL, NULL, NULL, '2025-07-19 06:45:12', '1752907512_45555regisrered updated.docx'),
(2, 'complaint letter', 'abusive director of studies', NULL, 'pending', '73', 74, NULL, NULL, '2025-07-19 08:15:35', '_Research Design Creswell_cp.pdf'),
(3, 'REQUEST', 'request', NULL, 'pending', '73', 74, NULL, NULL, '2025-07-19 09:02:05', '2312.docx'),
(4, 'REQUEST LETTER', 'request', NULL, 'pending', '73', 74, NULL, NULL, '2025-07-19 09:09:00', '_Research Design Creswell_cp.pdf'),
(5, 'XXXXXXXXXX', 'request', NULL, 'Sent to Headteacher', '73', 74, NULL, NULL, '2025-07-19 18:43:53', '1752950633_45555regisrered_updated.docx'),
(6, 'XXXXXXXXXXXXXXXXX', 'REQUEST', NULL, 'pending', '73', 74, NULL, NULL, '2025-07-21 06:05:24', '1753077923_Kemigishakkk_1_.docx'),
(7, 'XXXXXXXXXXXXXXXX', 'REQUEST', NULL, 'Sent to Headteacher', '73', 72, NULL, NULL, '2025-07-21 06:30:24', '1753079424__Research_Design_Creswell_cp.pdf'),
(8, 'XXXXXXXXXXXXXXXXXX', 'REQUEST', NULL, 'Sent to Headteacher', '73', 72, NULL, NULL, '2025-07-21 06:43:53', '1753080233_2312.docx');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `term` varchar(50) NOT NULL,
  `academic_year` varchar(50) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `class` varchar(20) NOT NULL,
  `class_enrolled` varchar(20) DEFAULT NULL,
  `term_year` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `term`, `academic_year`, `enrolled_at`, `class`, `class_enrolled`, `term_year`) VALUES
(8, 86, '2', '2025', '2025-07-21 08:41:16', '', 'S6', NULL),
(9, 86, '3', '2025', '2025-07-21 08:42:19', '', 'S5', NULL),
(10, 86, '1', '2025', '2025-07-21 08:52:46', '', 'S6', NULL),
(11, 76, '1', '2025', '2025-07-21 09:00:46', '', 'S6', NULL),
(12, 76, '2', '2025', '2025-07-21 09:11:07', '', 'S6', NULL),
(13, 79, '1', '2025', '2025-07-21 09:16:23', '', 'S5', NULL),
(14, 79, '2', '2025', '2025-07-21 09:17:21', '', 'S5', NULL),
(15, 87, '1', '', '2025-07-21 10:00:04', '', 'S2', '2025'),
(16, 91, '1', '', '2025-07-21 14:27:24', '', 'S5', '2025'),
(17, 81, '1', '', '2025-07-21 15:10:50', '', 'S3', '2025');

-- --------------------------------------------------------

--
-- Table structure for table `fees_structure`
--

CREATE TABLE `fees_structure` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `class` varchar(50) NOT NULL,
  `term` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance` int(100) DEFAULT NULL,
  `date_of_payment` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `reply` text DEFAULT NULL,
  `replied_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reply_to` int(11) DEFAULT NULL,
  `status` enum('unread','read') DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `reply`, `replied_at`, `created_at`, `reply_to`, `status`) VALUES
(1, 73, 72, 'Teacher (GOD ANKUNDAZ) says: THANKS FOR SERVICES AND GOOD WORK DONE', 'welcome sir', '2025-07-15 15:37:18', '2025-07-19 07:28:34', NULL, 'read'),
(2, 73, 72, 'Teacher (GOD ANKUNDAZ) says: I NEED A LEAVE  COZ MY CHILD IS SICK', 'ITS FINE YOU CAN LEAVE\r\nAND WISH YOUR CHILD QUICK RECOVERY', '2025-07-15 16:07:54', '2025-07-19 07:28:34', NULL, 'read'),
(3, 73, 72, 'Teacher (GOD ANKUNDAZ) says: good administration sir', 'welcome MR.ANKUNDA', '2025-07-15 16:43:31', '2025-07-19 07:28:34', NULL, 'read'),
(4, 73, 72, 'Teacher (GOD ANKUNDAZ) says: HELLO SIR!', 'YES HELLO MR GOD', '2025-07-15 16:50:06', '2025-07-19 07:28:34', NULL, 'read'),
(5, 73, 72, 'Teacher (GOD ANKUNDAZ) says: HELLO SIR', 'YES HELLO', '2025-07-15 17:09:44', '2025-07-19 07:28:34', NULL, 'read'),
(6, 73, 72, 'Teacher (GOD ANKUNDAZ) says: HELLO SIR', NULL, NULL, '2025-07-19 07:28:34', NULL, 'read'),
(8, 72, 74, 'i want you to to type for me an explusion letter for PAUL S6 due to an indiscpline case of escapism from school nad dodging classes', NULL, NULL, '2025-07-19 07:28:34', NULL, 'read'),
(9, 74, 72, 'okay sir am going to do it', 'yeah', '2025-07-19 10:31:13', '2025-07-19 07:30:16', 8, 'read'),
(10, 73, 72, 'Teacher (GOD ANKUNDAZ) says: good morning sir', 'good morning how are you', '2025-07-19 10:48:45', '2025-07-19 07:48:24', NULL, 'read'),
(11, 72, 74, 'GOOD MORNING FAITH', NULL, NULL, '2025-07-20 05:50:10', NULL, 'read'),
(12, 74, 72, 'GOOD MORNING TO SIR', NULL, NULL, '2025-07-20 05:52:33', 11, 'read'),
(13, 72, 74, 'HOW ARE YOU I WANT SOME DOCUMENTS ABOUT THE OTHER  MEETING', NULL, NULL, '2025-07-20 05:59:53', NULL, 'read'),
(14, 74, 72, 'OKAY SIR AM GOING TO SEND THEM', NULL, NULL, '2025-07-20 06:01:22', 13, 'read'),
(15, 72, 74, 'ggggggggggggggg', NULL, NULL, '2025-07-20 06:32:14', NULL, 'read'),
(16, 74, 72, 'hey', NULL, NULL, '2025-07-20 06:42:36', 15, 'read'),
(17, 72, 74, 'good morning madam secretary', NULL, NULL, '2025-07-20 07:01:46', NULL, 'read'),
(18, 72, 74, 'have left my keys on your table keep them', NULL, NULL, '2025-07-20 07:24:29', NULL, 'read'),
(19, 72, 73, 'hey', NULL, NULL, '2025-07-20 08:19:44', NULL, 'read'),
(20, 73, 83, 'hey', NULL, NULL, '2025-07-20 08:47:27', NULL, 'unread'),
(21, 74, 73, 'GOOD MORNING MR GOD ANKUNDA', NULL, NULL, '2025-07-20 08:53:07', NULL, 'read'),
(22, 73, 74, 'GOOD MORNING HOW ARE YOU', NULL, NULL, '2025-07-20 08:53:57', NULL, 'unread'),
(23, 80, 72, 'GOOD MORNING SIR', NULL, NULL, '2025-07-20 09:09:13', NULL, 'read'),
(24, 84, 72, 'GOOD MORNING SIR I WOULD LIKETO KNOW THE WORKERS WHO PRESENTED THEIR PAYMENT DETAILS TO YOU', NULL, NULL, '2025-07-21 05:59:59', NULL, 'read'),
(25, 72, 80, 'GOOD MORNING TO SIR HOWS YOU', NULL, NULL, '2025-07-21 06:01:02', NULL, 'unread'),
(26, 72, 84, 'GOD AKUNDAZ AND THE REST PROMISED ME TODAY', NULL, NULL, '2025-07-21 06:01:41', NULL, 'read'),
(27, 72, 74, 'GOOD  MORNING', NULL, NULL, '2025-07-21 07:13:58', NULL, 'read'),
(28, 72, 84, 'gggggggggggggg', NULL, NULL, '2025-07-21 10:20:33', NULL, 'unread');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `class` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `title`, `class`, `subject`, `file_path`, `uploaded_by`, `uploaded_at`, `description`) VALUES
(1, 'bbuuggggggggggsgggg', 'S6', 'maths', 'uploads/notes/1752575225_1752075057_ASSIGNMENT TWO (1) (3).docx', 73, '2025-07-15 10:27:05', 'hhhhhhhhhhhhhhhh');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_role` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(1) DEFAULT 0,
  `type` varchar(50) DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_role`, `user_id`, `message`, `created_at`, `seen`, `type`) VALUES
(1, NULL, 3, 'PAULINA submitted an assignment (ID: 4) in class S5.', '2025-07-10 06:53:24', 0, 'general'),
(2, NULL, 3, 'PAULINA submitted an assignment (ID: 3) in class S5.', '2025-07-10 07:17:34', 0, 'general'),
(3, NULL, 14, 'Student ID 14 updated their profile.', '2025-07-10 11:03:05', 0, 'profile_update'),
(4, 'director', NULL, 'Performance for class S6, subject Mathematics, term 1, academic year 2025/2026 has been recorded.', '2025-07-15 14:47:09', 0, 'general'),
(5, 'director', NULL, 'Performance for class S6, subject Mathematics, term 2, academic year 2025/2026 has been recorded.', '2025-07-15 15:29:36', 0, 'general'),
(6, 'director', NULL, 'Performance for class S6, subject Biology, term 1, academic year 2025/2026 has been recorded.', '2025-07-16 07:11:12', 0, 'general'),
(7, 'director', NULL, 'Performance for class S6, subject Biology, term 1, academic year 2025/2026 has been recorded.', '2025-07-16 07:31:47', 0, 'general'),
(8, 'director', NULL, 'Performance for class S6, subject Mathematics, term 1, academic year 2025/2026 has been recorded.', '2025-07-16 07:56:11', 0, 'general'),
(9, 'director', NULL, 'Performance for class S6, subject Biology, term 1, academic year 2025/2026 has been recorded.', '2025-07-16 07:59:43', 0, 'general');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class` varchar(3) NOT NULL,
  `is_boarder` tinyint(1) NOT NULL,
  `method` varchar(50) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `remaining` decimal(10,2) NOT NULL,
  `paid_at` datetime DEFAULT current_timestamp(),
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','verified') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `class`, `is_boarder`, `method`, `amount_paid`, `remaining`, `paid_at`, `payment_date`, `status`) VALUES
(1, 76, 'S6', 0, '0', 20000.00, 280000.00, '2025-07-18 15:13:43', '2025-07-18 15:58:13', 'verified'),
(2, 81, 'S4', 0, '0', 275000.00, 0.00, '2025-07-18 15:15:33', '2025-07-18 15:58:13', 'verified');

-- --------------------------------------------------------

--
-- Table structure for table `pending_admins`
--

CREATE TABLE `pending_admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `document_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_admins`
--

INSERT INTO `pending_admins` (`id`, `username`, `full_name`, `password`, `role`, `department`, `position`, `status`, `qualifications`, `contact`, `gender`, `address`, `email`, `created_at`, `document_path`) VALUES
(2, 'MUHABUZI EDMON', 'EDMON MUHABUZI', '$2y$10$cT3aPCOIbidb0E11LL./u.GAZem8f0017X5HU.wZsTYPCIfodjR7G', 'admin', 'school administrator', 'headteacher', '', 'MASTERS DEGREE OF EDUCATION', '', '', '', '2023abit179409f@kab.ac.ug', '2025-07-13 14:37:37', NULL),
(3, 'TALENT AMPEREZA', 'TALENT AMPEREZA', '$2y$10$n3x2vNh961Qkl9CHdwPsreUwaZXKG8B8txgXVt5fXb5vdnISvidKm', 'admin', 'school administrator', 'headteacher', '', 'MASTERS DEGREE OF EDUCATION', '0789000453233', '', '', '4023abit1794f@kab.ac.ug', '2025-07-13 14:45:52', NULL),
(4, 'mzalendopp', 'JUSTUS', '$2y$10$r6T6YuwPOgAi9BuJkM1iM.XtBwj1X.wCUaKMV6ZjryKeLSn.DQiUO', 'admin', 'school administrator', 'headteacher', '', 'MASTERS DEGREE OF EDUCATION', '', 'Male', 'KAMPALA', '20253abit1794f@kab.ac.ug', '2025-07-13 14:50:09', NULL),
(7, 'EMILY TUSHABOMWEE', 'EMILY TUSHABOMWEE', '$2y$10$wLbWpYo8QOx9xGD52T2A5emAz59GNuhlhGbrNr0uSNzJO/zaodNUu', 'admin', 'school administrator', 'deputy headteacher', '', 'MASTERS DEGREE OF EDUCATION', '0765126851', 'Male', 'KAMPALA', '2023abit1794f@kab.ac.ugggg', '2025-07-13 16:37:02', NULL),
(8, 'EMILY TUSHABOMWEE', 'EMILY TUSHABOMWEE', '$2y$10$LhfMBgA3pfoxaov6IJ/ooe4xCIuZgcKRhO4Pnl0Zh8xkISXTzMDMS', 'admin', 'school administrator', 'deputy headteacher', '', 'MASTERS DEGREE OF EDUCATION', '0765126851', 'Male', 'KAMPALA', '2023abit1794f@kab.ac.ugggg', '2025-07-13 16:54:35', NULL),
(9, 'EMILY TUSHABOMWEE', 'EMILY TUSHABOMWEE', '$2y$10$EXk3G5b7vxbN1Atsr6yIG.slHk6dNgBOAZwalr2WaU5ZLWTUTCQ/C', 'admin', 'school administrator', 'deputy headteacher', '', 'MASTERS DEGREE OF EDUCATION', '0765126851', 'Male', 'KAMPALA', '2023abit1794f@kab.ac.ugggg', '2025-07-13 16:54:39', NULL),
(10, 'JACKSON KAMANYOHA', 'JACKSON KAMANYOHA', '$2y$10$P/nJ4Gmx1XmeyK/LUKvec.wvRCHtYLYyTrJ1d2zwrRKH8aMkBz2ta', 'admin', 'school administrator', 'deputy headteacher', '', 'MASTERS DEGREE OF EDUCATION', '0765126851', 'Male', 'RUKUNGIRI', '202334abit4444f@kab.ac.ug', '2025-07-13 16:55:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pending_students`
--

CREATE TABLE `pending_students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `student_type` enum('new','returning') DEFAULT NULL,
  `scholar_type` enum('boarding','day') DEFAULT NULL,
  `requested_class` enum('S1','S4','S5','S6') DEFAULT NULL,
  `stream` varchar(50) DEFAULT NULL,
  `dormitory` varchar(50) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `admission_no` varchar(20) DEFAULT NULL,
  `registration_no` varchar(20) DEFAULT NULL,
  `uneb_olevel` varchar(255) DEFAULT NULL,
  `uneb_ulevel` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sent_reports`
--

CREATE TABLE `sent_reports` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class` varchar(10) NOT NULL,
  `term_year` varchar(10) NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `term` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sent_reports`
--

INSERT INTO `sent_reports` (`id`, `student_id`, `class`, `term_year`, `sent_at`, `term`) VALUES
(1, 81, 'S4', 'Term 1 202', '2025-07-18 14:41:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `staff_type` enum('teaching','non-teaching') DEFAULT NULL,
  `class_teaching` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `position` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `staff_position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_comments`
--

CREATE TABLE `staff_comments` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_list`
--

CREATE TABLE `staff_list` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `staff_type` enum('teaching','non_teaching') DEFAULT NULL,
  `class_teaching` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_subjects`
--

CREATE TABLE `staff_subjects` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `subject_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `class` varchar(10) DEFAULT NULL,
  `stream` varchar(50) DEFAULT NULL,
  `scholar_type` enum('boarding','day') DEFAULT NULL,
  `dormitory` varchar(50) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `admission_no` varchar(20) DEFAULT NULL,
  `registration_no` varchar(20) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees`
--

CREATE TABLE `student_fees` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `class` varchar(20) NOT NULL,
  `level` enum('O-Level','A-Level') NOT NULL,
  `fees_paid` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `class` varchar(10) DEFAULT NULL,
  `stream` varchar(50) DEFAULT NULL,
  `scholar_type` enum('boarding','day') DEFAULT NULL,
  `dormitory` varchar(50) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `admission_no` varchar(20) DEFAULT NULL,
  `registration_no` varchar(20) DEFAULT NULL,
  `term` varchar(20) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `submitted_on` datetime DEFAULT current_timestamp(),
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `user_id`, `full_name`, `dob`, `gender`, `contact`, `address`, `class`, `stream`, `scholar_type`, `dormitory`, `student_id`, `admission_no`, `registration_no`, `term`, `document_path`, `enrollment_date`, `status`, `created_at`, `submitted_on`, `email`) VALUES
(17, 76, '', '2000-07-01', 'female', '07890000012', 'KAMPALA', 'S6', 'ARTS', NULL, 'Angels', 'F20251712', 'ADM-44819', 'S6-8455', NULL, NULL, '2025-07-14', 'active', '2025-07-14 23:14:20', '2025-07-14 23:14:20', 'shivanZZ264@gmail.com'),
(18, 78, '', '2000-07-01', 'female', '07890000047', 'KAMPALA', 'S6', 'ARTS', NULL, 'Angels', 'F20255946', 'ADM-47075', 'S6-5287', NULL, NULL, '2025-07-15', 'active', '2025-07-15 17:31:55', '2025-07-15 17:31:55', '2023abit1794f@kab.ac.ugzzzz'),
(19, 79, '', '2003-07-01', 'male', '078900000345', 'KAMWENGYE', 'S4', 'B', NULL, 'Chicago', 'M20252652', 'ADM-16164', 'S4-4638', NULL, NULL, '2025-07-18', 'active', '2025-07-18 09:10:28', '2025-07-18 09:10:28', '0003abit1794f@kab.ac.ug'),
(20, 81, '', '2003-07-01', 'male', '07890000014', 'NTUNGAMO', 'S4', 'B', NULL, 'Los Angeles', 'M20252077', 'ADM-91618', 'S4-9033', NULL, NULL, '2025-07-18', 'active', '2025-07-18 09:17:32', '2025-07-18 09:17:32', '2023abit1791115f@kab.ac.ug'),
(21, 86, '', '2001-07-01', 'male', '078900000345', 'KAMWENGYE', 'S5', 'ARTS', NULL, 'New York', 'M20255731', 'ADM-35351', NULL, NULL, NULL, '2025-07-21', 'active', '2025-07-21 10:11:09', '2025-07-21 10:11:09', '2023abit1794f@kab.ac.ugffffffffffffffffff'),
(22, 87, '', '2000-07-01', 'male', '078900000312', 'KAMWENGYE', 'S1', 'C', NULL, 'Los Angeles', 'M20253149', 'ADM-97760', NULL, NULL, NULL, '2025-07-21', 'active', '2025-07-21 12:37:30', '2025-07-21 12:37:30', 'ankundagod8@gmail.commeee'),
(23, 89, '', '0000-00-00', 'female', '078900000345', 'KAMWENGYE', 'S6', 'Science Stream', NULL, 'Angels', 'ST3865', 'ADM11624', 'REG4383', '1', 'uploads/687e3df34290c_.Unit 3_1710570770223.pptx', '2025-07-21', 'active', '2025-07-21 17:08:00', '2025-07-21 17:08:00', '2023abit1794f@kab.ac.ugHHHHHH'),
(24, 90, '', '2025-07-01', 'female', '078900000345', 'NTUNGAMO', 'S6', 'Science Stream', NULL, 'Girls Dorm', 'ST8480', 'ADM16023', 'REG5702', '1', 'uploads/687e30d6c3af4_Ankunda God .pdf', '2025-07-21', 'active', '2025-07-21 17:08:15', '2025-07-21 17:08:15', '2023abit1795f@kab.ac.ugmmmmmmmmmmmmm'),
(25, 91, '', '2025-07-01', 'male', '07890000012232', 'KAMWENGYE', 'S6', 'Science Stream', NULL, NULL, 'ST9837', 'ADM40149', 'REG8145', '1', 'uploads/687e4c8033e5e__Research Design Creswell_cp.pdf', '2025-07-21', 'active', '2025-07-21 17:22:10', '2025-07-21 17:22:10', '2023abit1795f@kab.ac.ugzzzzzzzzzzziiii');

-- --------------------------------------------------------

--
-- Table structure for table `student_pending`
--

CREATE TABLE `student_pending` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) NOT NULL DEFAULT '',
  `contact` varchar(20) NOT NULL DEFAULT '',
  `dob` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `registration_no` varchar(20) DEFAULT NULL,
  `admission_no` varchar(20) DEFAULT NULL,
  `class` varchar(10) NOT NULL DEFAULT '',
  `stream` varchar(50) DEFAULT NULL,
  `scholar_type` enum('boarding','day') DEFAULT NULL,
  `dormitory` varchar(50) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `address` text NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `term` varchar(10) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_pending`
--

INSERT INTO `student_pending` (`id`, `student_id`, `full_name`, `gender`, `contact`, `dob`, `user_id`, `username`, `registration_no`, `admission_no`, `class`, `stream`, `scholar_type`, `dormitory`, `enrollment_date`, `status`, `created_at`, `updated_at`, `address`, `email`, `password`, `course`, `term`, `document_path`) VALUES
(42, 'ST1409', 'XXXXXXXXXX', 'Male', '078900000412223', '2000-07-01', NULL, 'XXXXXXXXXX', 'REG1588', 'ADM34524', 'S5', '', 'boarding', 'Boys Dorm', NULL, 'pending', '2025-07-21 14:09:18', NULL, 'NTUNGAMO', 'ankundagod8@gmail.commmm', '$2y$10$GckGPKlelw9UhJGkUi/pHeazupD.YgmbHGBGRwA2DWyZgWBvBdov6', 'PCM', '1', 'uploads/687e1fde3f892__Research Design Creswell_cp.pdf'),
(43, 'ST6625', 'ZZZZZZZZZZZZZZZZZ', 'Male', '07890000012', '2025-07-01', NULL, 'ZZZZZZZZZZZZZZZZZ', 'REG9320', 'ADM62295', 'S6', 'General Stream', 'boarding', 'Boys Dorm', NULL, 'pending', '2025-07-21 14:46:38', NULL, 'NTUNGAMO', 'jjjaa@gmail.commmeeeeeeee', '$2y$10$0JmIwmS2X7OZkMvUjdOs6OW/wgbXWoEKGlLc3hPHKNfaX3Oat6JM.', 'PCM', '1', 'uploads/687e289e1c58e_2312.docx'),
(46, 'ST2416', 'CCCCCCCCCCCCCCCCC', 'Female', '078900000345', '0000-00-00', NULL, 'CCCCCCCCCCCCCCCCC', 'REG1658', 'ADM18199', 'S6', 'Science Stream', 'boarding', 'Heavens', NULL, 'pending', '2025-07-21 17:08:25', NULL, 'KAMWENGYE', '2023abit1794f@kab.ac.ugHHHHHH', '$2y$10$EDbHQ5q0diCleWlAk/Ev4.EOwrF/OKaMKXR5flXusr3vcmZ2Q4y5e', 'BCM/ICT', '1', 'uploads/687e49d9c94a5_.Unit 3_1710570770223.pptx'),
(47, 'ST4282', 'MMMMMMMMMMMMMMMMZ', 'Male', '07890000012', '2025-07-01', NULL, 'MMMMMMMMMMMMMMMMZ', 'REG9605', 'ADM64991', 'S4', 'B', 'boarding', 'Los Angeles', NULL, 'pending', '2025-07-21 17:09:32', NULL, 'NTUNGAMO', '2023abit1794f@kab.ac.ugnnnnnnnnnnn', '$2y$10$.ymSHvK9xpXQ9y5tSU2.JOWHlPkIoWodVlz6qV2PMtX7wGkXjDzsS', '', '1', 'uploads/687e4a1c33f4c_45555regisrered updated.docx'),
(48, 'ST2780', 'TURYASINGURA DAVIDzzzzzz', 'Male', '07890000012', '2025-07-01', NULL, 'TURYASINGURA DAVIDzzzzzz', NULL, 'ADM52478', 'S5', 'Science Stream', 'day', 'California', NULL, 'pending', '2025-07-21 17:10:52', NULL, '', 'ankundagod232@GMAIL.COMzzzzzzz', '$2y$10$m25apTrmceqMt6tGTcp1TuFtZTM6gwuKd1KBTEm//Kf./9xcyJqxC', 'PCM/ICT', '1', 'uploads/687e4a6c124e2_ANKUNDA GOD 2023abit1794f (1).zip'),
(49, 'ST4674', 'bbbbbbbbbbbbbb', 'Male', '0789000003', '2025-07-01', NULL, 'TURYASINGURA DAVIDggggggggggg', NULL, 'ADM56273', 'S5', 'Arts Stream', 'day', 'Los Angeles', NULL, 'pending', '2025-07-21 17:13:34', NULL, 'NTUNGAMO', 'ankundagod232@GMAIL.COMbbbbbbbbbbb', '$2y$10$Tkgge7juj3N3WYf9XFXJP.R.Mq2pVJN01TfeEzheFaj6dP8zf4Zp2', 'HLD/ICT', '1', 'uploads/687e4b0ee6a0d__Research Design Creswell_cp.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `student_performance`
--

CREATE TABLE `student_performance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `class` varchar(10) NOT NULL,
  `term` int(11) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `U1` float DEFAULT 0,
  `U2` float DEFAULT 0,
  `U3` float DEFAULT 0,
  `U4` float DEFAULT 0,
  `formative_avg` float DEFAULT NULL,
  `average` float DEFAULT 0,
  `MT` float DEFAULT 0,
  `EOT` float DEFAULT 0,
  `summative_score` float DEFAULT NULL,
  `total` float DEFAULT 0,
  `grade` varchar(2) NOT NULL,
  `grade_point` int(11) DEFAULT 0,
  `submitted_by` varchar(100) DEFAULT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by_director` tinyint(1) DEFAULT 0,
  `total_mark` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_performance`
--

INSERT INTO `student_performance` (`id`, `student_id`, `subject`, `class`, `term`, `year`, `U1`, `U2`, `U3`, `U4`, `formative_avg`, `average`, `MT`, `EOT`, `summative_score`, `total`, `grade`, `grade_point`, `submitted_by`, `submission_date`, `approved_by_director`, `total_mark`) VALUES
(140, 81, 'Mathematics', 'S4', 1, NULL, 20, 16, 15, 16, NULL, 16.75, 25, 50, NULL, 59.25, 'D', 0, 'MATSIKO ZIONNNA', '2025-07-18 08:06:46', 0, NULL),
(141, 81, 'Mathematics', 'S4', 1, NULL, 20, 16, 15, 16, NULL, 16.75, 25, 50, NULL, 59.25, 'D', 0, 'MATSIKO ZIONNNA', '2025-07-18 08:09:23', 0, NULL),
(142, 81, 'History', 'S4', 1, NULL, 13, 14, 15, 18, NULL, 15, 30, 67, NULL, 70.9, 'B', 0, 'MATSIKO ZIONNNA', '2025-07-18 08:11:22', 0, NULL),
(143, 79, 'Biology', 'S4', 1, NULL, 15, 20, 15, 19, NULL, 17.25, 30, 60, NULL, 68.25, 'C', 0, 'GOD ANKUNDAZ', '2025-07-18 09:10:00', 0, NULL),
(144, 76, 'Physics', 'S6', 1, NULL, 14, 12, 12, 19, NULL, 14.25, 25, 65, NULL, 67.25, '0', 0, 'GOD ANKUNDAZ', '2025-07-18 09:17:42', 0, NULL),
(145, 76, 'Chemistry', 'S6', 1, NULL, 18, 18, 15, 18, NULL, 17.25, 20, 60, NULL, 65.25, '0', 0, 'MATSIKO ZIONNNA', '2025-07-18 09:23:51', 0, NULL),
(146, 81, 'Physical Education', 'S4', 1, NULL, 17, 15, 14, 14, NULL, 15, 26, 60, NULL, 64.8, 'C', 0, 'GOD ANKUNDAZ', '2025-07-18 09:28:30', 0, NULL),
(147, 81, 'Kiswahili', 'S4', 1, NULL, 15, 12, 11, 14, NULL, 13, 30, 50, NULL, 57, 'D', 0, 'GOD ANKUNDAZ', '2025-07-18 09:32:55', 0, NULL),
(148, 81, 'Christian Rel. Educ.', 'S4', 1, NULL, 13, 14, 16, 17, NULL, 15, 23, 70, NULL, 70.9, 'B', 0, 'GOD ANKUNDAZ', '2025-07-18 09:44:16', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `student_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `class` varchar(10) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `stream` varchar(10) NOT NULL,
  `subject` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submitted_assignments`
--

CREATE TABLE `submitted_assignments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assignment_title` varchar(255) NOT NULL,
  `submission_date` datetime DEFAULT current_timestamp(),
  `grade` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `term_fees`
--

CREATE TABLE `term_fees` (
  `id` int(11) NOT NULL,
  `class_name` varchar(10) NOT NULL,
  `term` varchar(10) NOT NULL,
  `year` int(11) NOT NULL,
  `fee_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `class` varchar(10) NOT NULL,
  `period` varchar(100) NOT NULL,
  `level` varchar(10) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `teacher` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` enum('Pending','Completed','Failed') DEFAULT 'Pending',
  `transaction_ref` varchar(100) DEFAULT NULL,
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
  `full_name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','teacher','staff','student') NOT NULL DEFAULT 'student',
  `gender` enum('male','female','other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `class` varchar(10) DEFAULT NULL,
  `stream` varchar(50) DEFAULT NULL,
  `scholar_type` enum('boarding','day') DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `dormitory` varchar(50) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `admission_no` varchar(20) DEFAULT NULL,
  `registration_no` varchar(20) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `staff_id` varchar(20) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `term` varchar(20) DEFAULT NULL,
  `class_teaching` varchar(255) DEFAULT NULL,
  `staff_type` varchar(100) DEFAULT NULL,
  `staff_position` varchar(100) DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `created_at`, `updated_at`, `email`, `role`, `gender`, `dob`, `contact`, `address`, `class`, `stream`, `scholar_type`, `course`, `dormitory`, `student_id`, `admission_no`, `registration_no`, `enrollment_date`, `staff_id`, `department`, `position`, `subject`, `status`, `term`, `class_teaching`, `staff_type`, `staff_position`, `qualifications`, `document_path`) VALUES
(65, 'ENOCKKKH', '$2y$10$QB6QJ6vTMd2Z8IzBOg0WPO/Efb5umWIAwPlIwrLIcxJ1tg8XVsBHu', 'ENOCKKKH', '2025-07-14 16:30:02', NULL, '2023abit1795f@kab.ac.ugssssssssss', '', 'male', NULL, '076512685155', 'RUKUNGIRI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'systems administrator', 'administrator', '', 'active', NULL, '', NULL, NULL, 'DEGREE IN CYBER SECURITY', NULL),
(66, 'ENOCKAH', '$2y$10$ZQ3U10DvG5EgcPl6lsVcRuOuXhkAv.Pr/.WtcmlgZHVqR5CUnpgyW', 'ENOCKAH', '2025-07-14 17:23:20', NULL, 'ankundagoddf232@GMAIL.COM', '', 'male', NULL, '07651268514', 'RUKUNGIRI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'administrator', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'ENOCKAZ', '$2y$10$7W1LhBHXoB1SjIXhNyvR/.zuHU7xtTbd5xE3r7YZPBdDyK6D6yNgG', 'ENOCKAZ', '2025-07-14 17:49:15', NULL, '2023abit179532f@kab.ac.ug', '', 'male', NULL, '0765126851', 'KAMPALA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'school administrator', 'administrator', NULL, 'active', NULL, NULL, NULL, NULL, 'DEGREE IN CYBER SECURITY', NULL),
(68, 'ENOCKAZZ', '$2y$10$YMACw7V2ajr8ivZwwOk1EO35g79fcifGUaTAD28iQzIoq0noTdqMi', 'ENOCKAZZ', '2025-07-14 18:01:00', NULL, '2023abit4444f@kab.aaac.ug', '', 'male', NULL, '07651268515', 'KAMPALA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'school administrator', 'administrator', NULL, 'active', NULL, NULL, NULL, NULL, 'MASTERS DEGREE OF EDUCATION', NULL),
(69, 'ENOCKKAHZ', '$2y$10$f7mYhkOUHQKbItiEETVPpeRMCfRAffy6g74qH1LKIYBRhsX5GkClK', 'ENOCKKAHZ', '2025-07-14 18:11:56', NULL, 'ankundagod232@GMAIL.COMMM', '', 'male', NULL, '0765126851', 'MUKONO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'systems administrator', 'administrator', NULL, 'active', NULL, NULL, NULL, NULL, 'DEGREE IN CYBER SECURITY', NULL),
(70, 'ENOCKKZA', '$2y$10$QWsbhlHliP0zVQT2HX7VFeKUEf9o/sbXHV10tB8z2pbhq9PpeutaC', 'ENOCKKZA', '2025-07-14 18:22:27', NULL, 'ankundagod232@GggggMAIL.COM', '', 'male', NULL, '07651268515', 'MUKONO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'systems administrator', 'administrator', NULL, 'active', NULL, NULL, NULL, NULL, 'DEGREE IN CYBER SECURITY', NULL),
(71, 'ENOCKis', '$2y$10$/9Fy09khuybvdHnMsKlKU.WzXuGR9H/6VxhOOcVPJyegOa11lYX4y', 'ENOCKis', '2025-07-14 18:29:07', NULL, '2023abit1794f@kabbb.ac.ug', '', 'male', NULL, '0765126851', 'MUKONO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'systems administrator', 'administrator', NULL, 'active', NULL, NULL, NULL, NULL, 'MASTERS DEGREE OF EDUCATION', NULL),
(72, 'TURYASINGURA DAVID', '$2y$10$bOcTXuUddwhua1P5ecqIvuLvs22N02jxd0FMAj7r/986sJaUtFqtG', 'TURYASINGURA DAVID', '2025-07-14 18:42:52', NULL, NULL, 'admin', 'male', NULL, '07651268515', 'RUKUNGIRI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ADM67147', 'school administrator', 'headteacher', NULL, '', NULL, NULL, NULL, NULL, 'MASTERS DEGREE OF EDUCATION', NULL),
(73, 'GOD ANKUNDAZ', '$2y$10$LRy30GHIgcqfrvwzETTJwOCefh.mDZxK2uGedwcCM362oifDf1Eca', 'GOD ANKUNDAZ', '2025-07-14 18:44:32', NULL, 'ankundagod88@gmail.com', 'staff', 'male', NULL, '0765126851', 'RUKUNGIRI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'STF54675', ' staff teacher', 'teacher', 'MATHS', '', NULL, 'S6', 'teaching', NULL, 'MASTERS DEGREE OF EDUCATION', NULL),
(74, 'FAITH NATUKUNDA', '$2y$10$20uV5epxvA4I/8dvsw//Aucegqty9zrZqLQx/POYkFb1Ty1g66Dry', 'FAITH NATUKUNDA', '2025-07-14 21:45:29', NULL, 'ankundagod87@gmail.com', 'staff', 'female', NULL, '07651268511', 'KASESE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'STF90394', 'school administrator', 'secretary', '', '', NULL, '', 'non-teaching', NULL, 'DEGREE IN SECRETARIAT', NULL),
(75, 'DAVID', '$2y$10$/qpeemjiROTwFFVj9xb9gOtYiEOIRIdTeraDuuReSaJaIicyC7e/y', 'DAVID', '2025-07-14 23:03:13', NULL, 'jjjaa@gmail.commm', 'admin', 'male', NULL, '07651268514', 'KAMWENGYE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'STF20694', 'school administrator', 'director of studies', '', 'active', NULL, '', '', NULL, 'DEGREE IN EDUCATION', NULL),
(76, 'ENOCKIE', '$2y$10$QGgDmwF8YWhZZVHqphAtuem7/ZT8i5NiapYEkjItFMN7QyKx08t9C', 'SHIVAN AKANKUNDANMN', '2025-07-14 23:14:19', NULL, 'shivanZZ264@gmail.com', 'student', 'female', '2000-07-01', '07890000012', 'KAMPALA', 'S6', 'ARTS', 'boarding', NULL, 'Angels', 'F20251712', 'ADM-44819', 'S6-8455', NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 'TURYASINGURA DAVIDOO', '$2y$10$dfZlzqYQqevoB5FVdtKg0epqMP0irESEBezkQnNZfGeHyg1hJXXgC', 'TURYASINGURA DAVIDOO', '2025-07-15 17:31:55', NULL, '2023abit1794f@kab.ac.ugzzzz', 'student', 'female', '2000-07-01', '07890000047', 'KAMPALA', 'S6', 'ARTS', 'day', NULL, 'Angels', 'F20255946', 'ADM-47075', 'S6-5287', NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 'MATSIKO ZION', '$2y$10$indvPClr2Et7wTLT6rPwQuQ/KkMCC/N4lkHoESVYlpA/X6Qa0eyJm', 'MATSIKO ZION', '2025-07-18 09:10:28', '2025-07-21 12:16:23', '0003abit1794f@kab.ac.ug', 'student', 'male', '2003-07-01', '078900000345', 'KAMWENGYE', 'S5', 'B', 'boarding', NULL, 'Chicago', 'M20252652', 'ADM-16164', 'S4-4638', NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 'MATSIKO ZIONNNA', '$2y$10$5bR2a1fxFdxHjcMl5tviJeIbDKVQJAsKCe4PwBwrhny067HBDJc9O', 'MATSIKO ZIONNNA', '2025-07-18 09:14:59', NULL, '2023abit1794f@kab.ac.ugzzzzzz', 'staff', 'male', NULL, '0782480893', 'KAMWENGYE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'STF13678', 'education department', 'teacher', 'maths', 'active', NULL, 'S5', 'teaching', NULL, 'MASTERS DEGREE OF EDUCATION', NULL),
(81, 'TURYASINGURA akram', '$2y$10$LZyzZEFg1uZo42BtxZyln.JlJ9WiEWXhonKuoICCIrECLeNLdYl46', 'TURYASINGURA akram', '2025-07-18 09:17:32', '2025-07-21 18:10:50', '2023abit1791115f@kab.ac.ug', 'student', 'male', '2003-07-01', '07890000014', 'NTUNGAMO', 'S3', 'B', 'boarding', NULL, 'Los Angeles', 'M20252077', 'ADM-91618', 'S4-9033', NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 'KAMUNTU HONEST', '$2y$10$V7MTA6InPlF71rzq.3nEGuNhFo2SPlOFgGQPAQ5rTzy0obYzxtbrS', 'TURYASINGURA DAVID', '2025-07-18 15:33:42', NULL, 'ankundagod86@gmail.com', 'admin', 'female', NULL, '07651268512323', 'NTUNGAMO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'STF31501', 'school administrator', 'director of studies', '', '', NULL, '', '', NULL, 'MASTERS DEGREE OF EDUCATION', NULL),
(84, 'MUHINDO JENIPHERO', '$2y$10$.uLMjq29bmaZqrycLAHeH.2KOZwSVFvUqytyQglVNLaeDMRpRHA2e', 'MUHINDO JENIPHERO', '2025-07-18 15:38:08', NULL, 'muhindo@gmail.com', 'admin', 'male', NULL, '076512685156', 'KASESE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'STF17207', 'finance department', 'bursar', '', 'active', NULL, '', '', NULL, 'DEGREE IN FINANCE MANAGEMENT', NULL),
(85, 'HANNIS', '$2y$10$X3WaxrSWZ5icX80S68UOC.jh7F/MkaAk.EHYqCthFs8nFaIgHuZG6', 'HANNIS', '2025-07-21 10:03:14', NULL, '2023abit4444f@kab.ac.ugggggg', 'staff', 'male', NULL, '07651268515', 'MUKONO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'STF50249', 'education department', 'teacher', 'GEOGRAPHY', 'active', NULL, 'S5', 'teaching', NULL, 'MASTERS DEGREE OF EDUCATION', NULL),
(86, 'HANNISEEE', '$2y$10$PBlv4n6mMGmaW9Ap/N1eLOVl2mGUj4t.3Yi2Br5B4ncfYJwUkHE.a', 'HANNISEEE', '2025-07-21 10:11:09', '2025-07-21 11:52:47', '2023abit1794f@kab.ac.ugffffffffffffffffff', 'student', 'male', '2001-07-01', '078900000345', 'KAMWENGYE', 'S6', 'ARTS', 'boarding', NULL, 'New York', 'M20255731', 'ADM-35351', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 'TURYAHIKAYO HANNSO', '$2y$10$Gm0fxvq7xLbAngrGT7ntO.iUqGsGF3tJr9irNOo4dF0AaBQeeWj.G', 'TURYAHIKAYO HANNSO', '2025-07-21 12:37:29', '2025-07-21 13:00:06', 'ankundagod8@gmail.commeee', 'student', 'male', '2000-07-01', '078900000312', 'KAMWENGYE', 'S2', 'C', 'day', NULL, 'Los Angeles', 'M20253149', 'ADM-97760', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 'CCCCCCCCCCCCCCCCC', '$2y$10$QStszXe4r30jIKR82KOqPuRXH9lqIQATq9nT8.7x4niqidRQb19pC', 'CCCCCCCCCCCCCCCCC', '2025-07-21 17:08:00', NULL, '2023abit1794f@kab.ac.ugHHHHHH', 'student', 'female', '0000-00-00', '078900000345', 'KAMWENGYE', 'S6', 'Science Stream', 'boarding', NULL, 'Angels', 'ST3865', 'ADM11624', 'REG4383', NULL, NULL, NULL, NULL, NULL, 'active', '1', NULL, NULL, NULL, NULL, 'uploads/687e3df34290c_.Unit 3_1710570770223.pptx'),
(90, 'MXXXXXXXXX', '$2y$10$iRLLxLPgurAUDhNA5qi3NexI48Ar2dj4xCpmTvXb9h7.Klgg/X/wq', 'MXXXXXXXXX', '2025-07-21 17:08:15', NULL, '2023abit1795f@kab.ac.ugmmmmmmmmmmmmm', 'student', 'female', '2025-07-01', '078900000345', 'NTUNGAMO', 'S6', 'Science Stream', 'boarding', NULL, 'Girls Dorm', 'ST8480', 'ADM16023', 'REG5702', NULL, NULL, NULL, NULL, NULL, 'active', '1', NULL, NULL, NULL, NULL, 'uploads/687e30d6c3af4_Ankunda God .pdf'),
(91, 'DAVIDOOO', '$2y$10$0O/iSpcGag3ENCCuzdu8FudELvYKxU/t9M2JLMOyXrM2vNIlJZMBq', 'DAVIDOOO', '2025-07-21 17:22:10', '2025-07-21 17:27:24', '2023abit1795f@kab.ac.ugzzzzzzzzzzziiii', 'student', 'male', '2025-07-01', '07890000012232', 'KAMWENGYE', 'S5', 'Science Stream', 'day', NULL, NULL, 'ST9837', 'ADM40149', 'REG8145', NULL, NULL, NULL, NULL, NULL, 'active', '1', NULL, NULL, NULL, NULL, 'uploads/687e4c8033e5e__Research Design Creswell_cp.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `class_teaching` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `staff_id` varchar(20) DEFAULT NULL,
  `staff_type` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subject` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`id`, `user_id`, `username`, `full_name`, `role`, `department`, `position`, `qualifications`, `class_teaching`, `contact`, `gender`, `staff_id`, `staff_type`, `address`, `created_at`, `subject`, `password`, `status`, `email`) VALUES
(3, 0, 'GOD ANKUNDAZ', 'GOD ANKUNDAZ', 'staff', ' staff teacher', 'teacher', 'MASTERS DEGREE OF EDUCATION', 'S6', '0765126851', 'Male', 'STF54675', NULL, 'RUKUNGIRI', '2025-07-14 15:44:32', 'MATHS', '$2y$10$LRy30GHIgcqfrvwzETTJwOCefh.mDZxK2uGedwcCM362oifDf1Eca', 'full-time', 'ankundagod88@gmail.com'),
(4, 0, 'FAITH NATUKUNDA', 'FAITH NATUKUNDA', 'staff', 'school administrator', 'secretary', 'DEGREE IN SECRETARIAT', '', '07651268511', 'Female', 'STF90394', NULL, 'KASESE', '2025-07-14 18:45:30', '', '$2y$10$20uV5epxvA4I/8dvsw//Aucegqty9zrZqLQx/POYkFb1Ty1g66Dry', 'full-time', 'ankundagod87@gmail.com'),
(5, 0, 'DAVID', 'DAVID', 'admin', 'school administrator', 'director of studies', 'DEGREE IN EDUCATION', '', '07651268514', 'Male', 'STF20694', NULL, 'KAMWENGYE', '2025-07-14 20:03:13', '', '$2y$10$/qpeemjiROTwFFVj9xb9gOtYiEOIRIdTeraDuuReSaJaIicyC7e/y', 'active', 'jjjaa@gmail.commm'),
(6, 0, 'MATSIKO ZIONNNA', 'MATSIKO ZIONNNA', 'staff', 'education department', 'teacher', 'MASTERS DEGREE OF EDUCATION', 'S5', '0782480893', 'Male', 'STF13678', NULL, 'KAMWENGYE', '2025-07-18 06:14:59', 'maths', '$2y$10$5bR2a1fxFdxHjcMl5tviJeIbDKVQJAsKCe4PwBwrhny067HBDJc9O', 'active', '2023abit1794f@kab.ac.ugzzzzzz'),
(7, 0, 'KAMUNTU HONEST', 'TURYASINGURA DAVID', 'admin', 'school administrator', 'director of studies', 'MASTERS DEGREE OF EDUCATION', '', '07651268512323', 'Female', 'STF31501', NULL, 'NTUNGAMO', '2025-07-18 12:33:42', '', '$2y$10$V7MTA6InPlF71rzq.3nEGuNhFo2SPlOFgGQPAQ5rTzy0obYzxtbrS', 'full-time', 'ankundagod86@gmail.com'),
(8, 0, 'MUHINDO JENIPHERO', 'MUHINDO JENIPHERO', 'admin', 'finance department', 'bursar', 'DEGREE IN FINANCE MANAGEMENT', '', '076512685156', 'Male', 'STF17207', NULL, 'KASESE', '2025-07-18 12:38:09', '', '$2y$10$.uLMjq29bmaZqrycLAHeH.2KOZwSVFvUqytyQglVNLaeDMRpRHA2e', 'active', 'muhindo@gmail.com'),
(9, 0, 'HANNIS', 'HANNIS', 'staff', 'education department', 'teacher', 'MASTERS DEGREE OF EDUCATION', 'S5', '07651268515', 'Male', 'STF50249', NULL, 'MUKONO', '2025-07-21 07:03:14', 'GEOGRAPHY', '$2y$10$X3WaxrSWZ5icX80S68UOC.jh7F/MkaAk.EHYqCthFs8nFaIgHuZG6', 'active', '2023abit4444f@kab.ac.ugggggg');

-- --------------------------------------------------------

--
-- Table structure for table `workers_pending`
--

CREATE TABLE `workers_pending` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `class_teaching` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `staff_id` varchar(200) DEFAULT NULL,
  `staff_type` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `document_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers_pending`
--

INSERT INTO `workers_pending` (`id`, `username`, `full_name`, `password`, `role`, `department`, `position`, `subject`, `status`, `qualifications`, `class_teaching`, `contact`, `gender`, `staff_id`, `staff_type`, `address`, `email`, `created_at`, `document_path`) VALUES
(10, 'DEWAR', 'DEWAR', '$2y$10$RfAf8vFoCm.FWkuBIpjh1upbDlvEzny0gT61gTJ/Y8udTN868Z83W', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268514', 'Male', NULL, '', 'MUKONO', 'ankundagod84@gmail.com', '2025-07-14 08:53:00', NULL),
(14, 'SAUNI AHEREZA', 'SAUNI AHEREZA', '$2y$10$UOBvPDQ5WgFsSIEjYRDViehPcIhhMkuXV6gK920wNbdMSiXRevLe.', 'staff', 'staff member', 'librarian', '', 'full-time', 'DEGREE IN LIBRARY AND RECORDS MANAGEMENT', '', '07651268511', 'Female', NULL, 'non-teaching', 'NYAKINONI', '1113abit1795f@kab.ac.ug', '2025-07-14 09:07:26', NULL),
(15, 'AMPEREZA COLLINS', 'AMPEREZA COLLINS', '$2y$10$BkkFxpREHOhHtSNQDRQTTemr8MANJTWhEd.ZzKEjlF.T9Jqd8BHJC', 'staff', 'SCIENCE DEPARTMENT', 'lab attendant', '', 'full-time', 'CERTIFICATE IN LABRATORY MANAGEMENT', '', '076512685112', 'Male', NULL, 'non-teaching', 'KAMURWA', 'ankundagod810@gmail.com', '2025-07-14 09:09:22', NULL),
(16, 'ENOCKK', 'ENOCKK', '$2y$10$b597Ullac/tM8kB7LCOIrux4ebSIwSPVwg6qLa.dNdWT2Fc2qCjpS', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '076512685113', 'Male', NULL, '', 'MUKONO', 'ankundagod867@gmail.com', '2025-07-14 14:28:35', NULL),
(17, 'ENOCKK', 'ENOCKK', '$2y$10$tePk6LDremoshcTTi/.Csun0rnEk6Cxo3chbKWFKLt9mF4CnzUQS2', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '076512685113', 'Male', NULL, '', 'MUKONO', 'ankundagod867@gmail.com', '2025-07-14 14:35:02', NULL),
(18, 'ENOCKA', 'ENOCKA', '$2y$10$UyCPSyUrIsU1Hn0PkFY2z.j10dimdETZS3GbUYPUuTj4HzIgmNUMS', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268515', 'Male', NULL, 'non-teaching', 'MUKONO', 'ankundagod898@gmail.com', '2025-07-14 14:36:17', NULL),
(19, 'ENOCKA', 'ENOCKA', '$2y$10$SQedObwMUFac8LmZ0eaQY.4kyxXjiudEcK8KiOULLp8sT4B.4v3be', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268515', 'Male', NULL, 'non-teaching', 'MUKONO', 'ankundagod898@gmail.com', '2025-07-14 14:40:54', NULL),
(20, 'ENOCKK', 'ENOCKK', '$2y$10$gM5AiA0iMlLetbPnMJlbbeBtk/UobWapDhqBLLAOK9hRjNn3LovAG', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '076512685113', 'Male', NULL, '', 'MUKONO', 'ankundagod867@gmail.com', '2025-07-14 14:41:07', NULL),
(21, 'ENOCKK', 'ENOCKK', '$2y$10$Ag3z1V3gc3Sxskqb/yUM5.O819wqSckuxwV.8Nh1IDS/A75utH8..', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '076512685113', 'Male', NULL, '', 'MUKONO', 'ankundagod867@gmail.com', '2025-07-14 14:45:11', NULL),
(22, 'ENOCKK', 'ENOCKK', '$2y$10$g0Sz0BtmH.KsNqDWMLJcYOpAbH0P9x.s7bc1xWZSFFW7g.JAgNVVm', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '076512685113', 'Male', NULL, '', 'MUKONO', 'ankundagod867@gmail.com', '2025-07-14 14:45:24', NULL),
(23, 'ENOCKK', 'ENOCKK', '$2y$10$Z93MvTilJpMjqvzDG/dD8O6wWfmCEr9DCycVFNBL.73ICkq6bYfi.', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '076512685113', 'Male', NULL, '', 'MUKONO', 'ankundagod867@gmail.com', '2025-07-14 14:46:18', NULL),
(24, 'ENOCKKAA', 'ENOCKKAA', '$2y$10$o0EH5BFt7anqlA3bpvFbve74iIxHKBL8GmcEksL7NEacbxAb5zFJ.', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268511', 'Male', NULL, 'teaching', 'MUKONO', 'ankundagod8O09@gmail.com', '2025-07-14 14:47:14', NULL),
(25, 'ENOCKKAA', 'ENOCKKAA', '$2y$10$2ncAo5xk2N6KVpX/MO85KOOk7SRCkyKEt8B6wNR1S.COcqTNd0wR6', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268511', 'Male', NULL, 'teaching', 'MUKONO', 'ankundagod8O09@gmail.com', '2025-07-14 15:17:19', NULL),
(26, 'ENOCKKAA', 'ENOCKKAA', '$2y$10$IYNvej8b3xpmJc2juWZIlOPcmFjKkvV0C3p62tYqW1t9KA0xur1T.', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268511', 'Male', NULL, 'teaching', 'MUKONO', 'ankundagod8O09@gmail.com', '2025-07-14 15:17:36', NULL),
(27, 'ENOCKKAA', 'ENOCKKAA', '$2y$10$QvbNC7/Qzf6bkupG4biJaecA.Xqjy5lihxDrLMHzQ/qioqtWt8RpS', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268511', 'Male', NULL, 'teaching', 'MUKONO', 'ankundagod8O09@gmail.com', '2025-07-14 15:17:48', NULL),
(28, 'ENOCKKAA', 'ENOCKKAA', '$2y$10$WRM8QeIIZNoGT/VFzee5ReONIzIK86pS8ga58uEgZ68JUmtonEBsy', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268511', 'Male', NULL, 'teaching', 'MUKONO', 'ankundagod8O09@gmail.com', '2025-07-14 15:18:46', NULL),
(29, 'ENOCKKAA', 'ENOCKKAA', '$2y$10$KEMjsEFiO3xLLgDXfXczve5Ns/85hbRuX1SfQsyc/gSb63wjK000a', 'admin', 'systems administrator', 'systems admin', '', 'full-time', 'DEGREE IN CYBER SECURITY', '', '07651268511', 'Male', NULL, 'teaching', 'MUKONO', 'ankundagod8O09@gmail.com', '2025-07-14 15:28:25', NULL),
(32, 'MATSIKO ZIONNNA', 'MATSIKO ZIONNNA', '$2y$10$yt/Tf9XCxUi1hTGWy2pqKOFRxb.sq/BbSIuwbiAK3TdVhiG/VTEy6', 'staff', 'education department', 'teacher', 'maths', 'active', 'MASTERS DEGREE OF EDUCATION', 'S5', '0782480893', 'Male', NULL, 'teaching', 'KAMWENGYE', '2023abit1794f@kab.ac.ugzzzzzz', '2025-07-18 09:15:48', NULL),
(35, 'HANNIS', 'HANNIS', '$2y$10$8O2AlE9evaORRxV8wBldvurMODysqDzZtAvNxve556dKwEZzXavw.', 'staff', 'education department', 'teacher', 'GEOGRAPHY', 'active', 'MASTERS DEGREE OF EDUCATION', 'S5', '07651268515', 'Male', NULL, 'teaching', 'MUKONO', '2023abit4444f@kab.ac.ugggggg', '2025-07-21 10:01:39', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_staff_id` (`staff_id`);

--
-- Indexes for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`date`),
  ADD UNIQUE KEY `unique_attendance_per_week` (`student_id`,`class`,`week`);

--
-- Indexes for table `bursar_notifications`
--
ALTER TABLE `bursar_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_staff`
--
ALTER TABLE `class_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_user` (`user_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `fees_structure`
--
ALTER TABLE `fees_structure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_admins`
--
ALTER TABLE `pending_admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_students`
--
ALTER TABLE `pending_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_reports`
--
ALTER TABLE `sent_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`class`,`term_year`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `staff_comments`
--
ALTER TABLE `staff_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_list`
--
ALTER TABLE `staff_list`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_students_user` (`user_id`);

--
-- Indexes for table `student_fees`
--
ALTER TABLE `student_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student_user` (`user_id`);

--
-- Indexes for table `student_pending`
--
ALTER TABLE `student_pending`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `registration_no` (`registration_no`),
  ADD UNIQUE KEY `admission_no` (`admission_no`),
  ADD KEY `fk_pending_user` (`user_id`);

--
-- Indexes for table `student_performance`
--
ALTER TABLE `student_performance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`student_id`,`subject`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `submitted_assignments`
--
ALTER TABLE `submitted_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_submitted_assignments_student` (`student_id`);

--
-- Indexes for table `term_fees`
--
ALTER TABLE `term_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `admission_no` (`admission_no`),
  ADD UNIQUE KEY `registration_no` (`registration_no`),
  ADD UNIQUE KEY `staff_id` (`staff_id`),
  ADD UNIQUE KEY `ux_email` (`email`),
  ADD UNIQUE KEY `ux_student_id` (`student_id`),
  ADD UNIQUE KEY `ux_admission_no` (`admission_no`),
  ADD UNIQUE KEY `ux_registration_no` (`registration_no`),
  ADD UNIQUE KEY `ux_staff_id` (`staff_id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`);

--
-- Indexes for table `workers_pending`
--
ALTER TABLE `workers_pending`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bursar_notifications`
--
ALTER TABLE `bursar_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_staff`
--
ALTER TABLE `class_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `fees_structure`
--
ALTER TABLE `fees_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pending_admins`
--
ALTER TABLE `pending_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pending_students`
--
ALTER TABLE `pending_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sent_reports`
--
ALTER TABLE `sent_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `staff_comments`
--
ALTER TABLE `staff_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fees`
--
ALTER TABLE `student_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `student_pending`
--
ALTER TABLE `student_pending`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `student_performance`
--
ALTER TABLE `student_performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submitted_assignments`
--
ALTER TABLE `submitted_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `term_fees`
--
ALTER TABLE `term_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `workers_pending`
--
ALTER TABLE `workers_pending`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `fk_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `users` (`staff_id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD CONSTRAINT `assignment_submissions_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`),
  ADD CONSTRAINT `assignment_submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `assignment_submissions_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_list`
--
ALTER TABLE `student_list`
  ADD CONSTRAINT `fk_student_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_pending`
--
ALTER TABLE `student_pending`
  ADD CONSTRAINT `fk_pending_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submitted_assignments`
--
ALTER TABLE `submitted_assignments`
  ADD CONSTRAINT `fk_submitted_assignments_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2025 at 01:03 PM
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
-- Database: `rposystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `archived_payments`
--

CREATE TABLE `archived_payments` (
  `id` int(11) NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archived_payments`
--

INSERT INTO `archived_payments` (`id`, `order_code`, `customer_name`, `amount`, `payment_method`, `proof_of_payment`, `archived_at`, `created_at`) VALUES
(3, 'CZOM-0497', 'James lucio', 500.00, 'Credit Card', '../admin/uploads/PAY674BC1C507811.png', '2024-12-01 02:15:38', '2024-12-01 02:15:38'),
(5, 'PGJD-4317', 'James Ucio', 250.00, 'Cash', '../admin/uploads/PAY67876BA66DBB4.png', '2025-01-16 06:50:59', '2025-01-16 06:50:59'),
(6, 'VZKU-3908', 'James Ucio', 250.00, 'Gcash', '../admin/uploads/PAY67876BC01C221.png', '2025-01-16 07:04:48', '2025-01-16 07:04:48'),
(7, 'GKET-1705', 'James Ucio', 50.00, 'Gcash', '../admin/uploads/PAY67876FF3004A5.png', '2025-01-16 07:06:38', '2025-01-16 07:06:38'),
(8, 'HSWY-1392', 'James Ucio', 5.00, 'Credit Card', '../admin/uploads/PAY67876C88C7D65.png', '2025-01-16 07:10:26', '2025-01-16 07:10:26'),
(9, 'NLJG-3085', 'James Ucio', 750.00, 'Gcash', '../admin/uploads/PAY6788B16387778.png', '2025-01-16 07:12:56', '2025-01-16 07:12:56'),
(10, 'INLJ-7423', 'James Ucio', 750.00, 'Credit Card', '../admin/uploads/PAY6788B389EC6E0.jpg', '2025-01-16 07:22:15', '2025-01-16 07:22:15'),
(11, 'MJZY-9783', 'James Ucio', 2.00, 'Credit Card', '../admin/uploads/PAY6788B3B47E58E.png', '2025-01-16 07:22:43', '2025-01-16 07:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `convo_list`
--

CREATE TABLE `convo_list` (
  `id` int(30) NOT NULL,
  `from_user` int(30) NOT NULL,
  `to_user` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(30) NOT NULL,
  `username` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(30) NOT NULL,
  `from_user` int(30) NOT NULL,
  `to_user` int(30) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = text , 2 = photos,3 = videos, 4 = documents',
  `message` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `popped` tinyint(1) NOT NULL DEFAULT 0,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_user`, `to_user`, `type`, `message`, `status`, `popped`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 1, 2, 1, 'test', 1, 1, 0, '2021-10-16 11:45:21', '2021-10-17 19:36:34'),
(2, 1, 2, 1, '1', 1, 1, 0, '2021-10-16 11:45:25', '2021-10-17 19:36:34'),
(3, 1, 2, 1, '2', 1, 1, 0, '2021-10-16 11:45:26', '2021-10-17 19:36:34'),
(4, 1, 2, 1, '3', 1, 1, 0, '2021-10-16 11:45:28', '2021-10-17 19:36:34'),
(5, 1, 2, 1, '4', 1, 1, 0, '2021-10-16 11:45:30', '2021-10-17 19:36:34'),
(6, 1, 2, 1, '5', 1, 1, 0, '2021-10-16 11:45:31', '2021-10-17 19:36:34'),
(7, 1, 2, 1, '6', 1, 1, 0, '2021-10-16 11:45:32', '2021-10-17 19:36:34'),
(8, 1, 2, 1, '7', 1, 1, 0, '2021-10-16 11:45:34', '2021-10-17 19:36:34'),
(9, 1, 2, 1, '8', 1, 1, 0, '2021-10-16 11:45:35', '2021-10-17 19:36:34'),
(10, 1, 2, 1, '9', 1, 1, 0, '2021-10-16 11:45:37', '2021-10-17 19:36:34'),
(11, 1, 2, 1, '10', 1, 1, 0, '2021-10-16 11:45:42', '2021-10-17 19:36:34'),
(12, 1, 2, 1, '11', 1, 1, 0, '2021-10-16 11:45:44', '2021-10-17 19:36:34'),
(13, 1, 2, 1, '12', 1, 1, 0, '2021-10-16 11:45:47', '2021-10-17 19:36:34'),
(14, 1, 2, 1, '13', 1, 1, 0, '2021-10-16 11:45:51', '2021-10-17 19:36:34'),
(15, 1, 2, 1, '14', 1, 1, 0, '2021-10-16 11:45:54', '2021-10-17 19:36:34'),
(16, 1, 2, 1, '15', 1, 1, 0, '2021-10-16 11:45:57', '2021-10-17 19:36:34'),
(17, 2, 1, 1, '16', 1, 1, 0, '2021-10-16 11:52:45', '2021-10-17 19:37:00'),
(18, 2, 1, 1, '17', 1, 1, 0, '2021-10-16 11:52:49', '2021-10-17 19:37:00'),
(19, 2, 1, 1, '18', 1, 1, 0, '2021-10-16 11:52:54', '2021-10-17 19:37:00'),
(20, 2, 1, 1, '19', 1, 1, 0, '2021-10-16 11:52:57', '2021-10-17 19:37:00'),
(21, 2, 1, 1, '20', 1, 1, 0, '2021-10-16 11:53:06', '2021-10-17 19:37:00'),
(22, 2, 1, 1, '21', 1, 1, 0, '2021-10-16 11:58:48', '2021-10-17 19:37:00'),
(23, 2, 1, 1, 'test', 1, 1, 0, '2021-10-16 12:03:40', '2021-10-17 19:37:00'),
(24, 2, 1, 1, 'test', 1, 1, 0, '2021-10-16 12:04:48', '2021-10-17 19:37:00'),
(25, 1, 2, 1, 're', 1, 1, 0, '2021-10-16 12:05:03', '2021-10-17 19:36:34'),
(26, 1, 2, 1, 'wew', 1, 1, 0, '2021-10-16 12:05:19', '2021-10-17 19:36:34'),
(27, 2, 1, 1, 'hey John', 1, 1, 0, '2021-10-17 18:43:58', '2021-10-17 19:37:00'),
(28, 1, 3, 1, 'Hi Sam', 1, 1, 0, '2021-10-17 18:50:20', '2021-10-17 19:42:15'),
(29, 1, 2, 1, 'claire', 1, 1, 0, '2021-10-17 18:50:37', '2021-10-17 19:36:34'),
(30, 3, 1, 1, 'hey john', 1, 1, 0, '2021-10-17 19:42:31', '2021-10-17 19:43:18'),
(31, 1, 2, 1, 'test', 1, 0, 0, '2021-10-17 19:42:43', '2021-10-17 19:42:44'),
(32, 3, 1, 1, 'yow', 1, 1, 0, '2021-10-17 19:43:22', '2021-10-17 19:43:49'),
(33, 1, 2, 1, 'claire', 1, 0, 1, '2021-10-17 19:43:57', '2021-10-18 00:01:46'),
(34, 3, 1, 1, 'john??', 1, 1, 0, '2021-10-17 19:44:30', '2021-10-17 19:46:01'),
(35, 3, 1, 1, 'test', 1, 1, 0, '2021-10-17 19:45:42', '2021-10-17 19:46:01'),
(36, 3, 1, 1, 'hey', 1, 1, 0, '2021-10-17 19:46:12', '2021-10-17 19:46:26'),
(37, 3, 1, 1, 'psst', 1, 1, 0, '2021-10-17 19:46:33', '2021-10-17 19:47:47'),
(38, 3, 1, 1, 'John??', 1, 1, 0, '2021-10-17 19:47:00', '2021-10-17 19:47:47'),
(39, 3, 1, 1, 'hey you', 1, 1, 0, '2021-10-17 19:47:27', '2021-10-17 19:47:47'),
(40, 3, 1, 1, 'test', 1, 1, 0, '2021-10-17 19:47:54', '2021-10-17 19:50:50'),
(41, 1, 2, 1, '123', 1, 0, 0, '2021-10-17 19:49:08', '2021-10-17 19:49:09'),
(42, 3, 1, 1, '1234', 1, 1, 0, '2021-10-17 19:49:17', '2021-10-17 19:50:50'),
(43, 3, 1, 1, 'test', 1, 1, 0, '2021-10-17 19:50:04', '2021-10-17 19:50:50'),
(44, 3, 1, 1, 'qweqwe', 1, 1, 0, '2021-10-17 19:50:42', '2021-10-17 19:50:50'),
(45, 3, 1, 1, 'aaa', 1, 1, 0, '2021-10-17 19:50:57', '2021-10-17 19:52:52'),
(46, 3, 1, 1, 'John??', 1, 1, 0, '2021-10-17 19:51:38', '2021-10-17 19:52:52'),
(47, 1, 2, 1, 'calire??', 1, 0, 0, '2021-10-17 19:51:50', '2021-10-17 19:51:51'),
(48, 3, 1, 1, 'hey', 1, 1, 0, '2021-10-17 19:52:02', '2021-10-17 19:52:52'),
(49, 3, 1, 1, 'yes ?', 1, 1, 0, '2021-10-17 19:52:58', '2021-10-17 19:53:09'),
(59, 4, 1, 1, 'dude', 1, 1, 0, '2021-10-17 20:15:38', '2021-10-17 20:15:43'),
(60, 1, 4, 1, 'hey', 1, 1, 0, '2021-10-17 20:15:50', '2021-10-17 20:16:04'),
(61, 4, 1, 1, 'men', 1, 1, 0, '2021-10-17 21:28:39', '2021-10-17 21:39:08'),
(62, 4, 1, 1, 'test', 1, 1, 0, '2021-10-17 21:32:31', '2021-10-17 21:39:08'),
(63, 1, 3, 1, 'test', 1, 1, 0, '2021-10-17 21:32:53', '2021-10-18 00:02:20'),
(64, 4, 1, 1, 'test', 1, 1, 0, '2021-10-17 21:33:00', '2021-10-17 21:39:08'),
(65, 4, 1, 1, 'dude', 1, 1, 0, '2021-10-17 21:33:27', '2021-10-17 21:39:08'),
(66, 4, 1, 1, 'yow', 1, 1, 0, '2021-10-17 21:35:24', '2021-10-17 21:39:08'),
(67, 4, 1, 1, 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', 1, 1, 0, '2021-10-17 21:38:07', '2021-10-17 21:42:42'),
(68, 4, 1, 1, 'teest', 1, 1, 0, '2021-10-17 21:49:16', '2021-10-17 21:49:18'),
(69, 4, 1, 1, 'dude??', 1, 1, 0, '2021-10-17 21:52:38', '2021-10-17 21:52:41'),
(70, 4, 1, 1, 'sup', 1, 1, 0, '2021-10-17 21:52:48', '2021-10-17 21:54:47'),
(71, 1, 4, 1, 'hey', 1, 1, 0, '2021-10-17 21:53:02', '2021-10-17 21:54:13'),
(72, 1, 4, 1, 'What ??', 1, 1, 0, '2021-10-17 21:54:54', '2021-10-17 21:55:15'),
(73, 1, 4, 1, 'How can I help you ?', 1, 1, 0, '2021-10-17 21:55:29', '2021-10-17 21:56:46'),
(74, 4, 1, 1, 'test only', 1, 1, 0, '2021-10-17 21:56:51', '2021-10-17 22:19:00'),
(75, 4, 1, 1, 'test', 1, 1, 0, '2021-10-17 21:57:08', '2021-10-17 22:19:00'),
(76, 4, 1, 1, 'a', 1, 1, 0, '2021-10-17 21:57:14', '2021-10-17 22:19:00'),
(77, 4, 1, 1, '123', 1, 1, 0, '2021-10-17 21:58:26', '2021-10-17 22:19:00'),
(78, 4, 1, 1, '123', 1, 1, 0, '2021-10-17 21:58:31', '2021-10-17 22:19:00'),
(79, 4, 1, 1, '2221\r\n25', 1, 1, 0, '2021-10-17 21:58:38', '2021-10-17 22:19:00'),
(80, 1, 4, 1, 'yes?\r\n22', 1, 1, 0, '2021-10-17 21:59:39', '2021-10-17 21:59:43'),
(81, 4, 1, 1, 'hey', 1, 1, 0, '2021-10-17 22:01:22', '2021-10-17 22:19:00'),
(82, 4, 1, 1, 'what\r\n??', 1, 1, 0, '2021-10-17 22:01:58', '2021-10-17 22:19:00'),
(83, 4, 1, 1, 'test\r\n', 1, 1, 0, '2021-10-17 22:15:43', '2021-10-17 22:19:00'),
(84, 4, 1, 1, 'test\r\n', 1, 1, 0, '2021-10-17 22:16:01', '2021-10-17 23:07:20'),
(85, 4, 1, 1, 'yow\r\n\r\nsup', 1, 1, 0, '2021-10-17 22:16:11', '2021-10-17 23:07:20'),
(86, 4, 1, 1, 'wew\r\ntest', 1, 1, 0, '2021-10-17 22:18:30', '2021-10-17 23:07:20'),
(87, 1, 4, 1, 'test', 1, 1, 0, '2021-10-17 22:19:08', '2021-10-17 22:29:46'),
(88, 1, 4, 1, 'test\r\ntest', 1, 1, 0, '2021-10-17 22:19:14', '2021-10-17 22:29:46'),
(89, 1, 4, 1, 'test\r\ntest', 1, 1, 0, '2021-10-17 22:21:13', '2021-10-17 22:29:46'),
(90, 1, 4, 1, 'dude\r\nCan I Ask ?', 1, 1, 1, '2021-10-17 22:30:01', '2021-10-17 23:36:55'),
(91, 4, 1, 1, 'What?\r\nIs it about something?', 1, 1, 1, '2021-10-17 22:30:32', '2021-10-17 23:37:56'),
(92, 1, 4, 1, 'Remeber test 101\r\nCan you check the bug ?', 1, 1, 1, '2021-10-17 22:31:09', '2021-10-17 23:36:01'),
(93, 4, 1, 1, 'test', 1, 1, 1, '2021-10-17 22:42:23', '2021-10-17 23:38:02'),
(94, 4, 1, 1, 'test', 1, 1, 1, '2021-10-17 22:43:28', '2021-10-17 23:07:29'),
(95, 4, 1, 1, 'test', 1, 1, 1, '2021-10-17 23:21:14', '2021-10-17 23:35:50'),
(96, 4, 1, 1, 'hey dude', 1, 1, 0, '2021-10-17 23:44:45', '2021-10-17 23:46:14'),
(97, 4, 1, 1, 'yow', 1, 1, 0, '2021-10-17 23:46:04', '2021-10-17 23:46:14'),
(98, 4, 1, 1, 'fs', 1, 1, 0, '2021-10-17 23:48:34', '2021-10-17 23:55:38'),
(99, 4, 1, 1, 'test', 1, 1, 0, '2021-10-17 23:49:12', '2021-10-17 23:55:38'),
(100, 1, 4, 1, 'what?', 1, 1, 0, '2021-10-17 23:49:22', '2021-10-17 23:51:07'),
(101, 1, 4, 1, 'yow', 1, 1, 1, '2021-10-17 23:55:42', '2024-12-23 18:35:41'),
(102, 3, 1, 1, 'JOhn?', 1, 1, 0, '2021-10-18 00:02:29', '2024-12-23 18:35:44'),
(103, 3, 1, 1, 'Hey John', 1, 1, 0, '2021-10-18 00:02:33', '2024-12-23 18:35:44'),
(104, 3, 1, 1, 'John', 1, 1, 0, '2021-10-18 00:02:49', '2024-12-23 18:35:44'),
(105, 3, 1, 1, 'test', 1, 1, 0, '2021-10-18 00:03:21', '2024-12-23 18:35:44'),
(106, 3, 1, 1, 'john', 1, 1, 0, '2021-10-18 00:03:26', '2024-12-23 18:35:44'),
(107, 3, 1, 1, 'hey', 1, 1, 0, '2021-10-18 00:03:58', '2024-12-23 18:35:44'),
(108, 3, 1, 1, 'hey', 1, 1, 0, '2021-10-18 00:04:06', '2024-12-23 18:35:44'),
(109, 3, 1, 1, 'test', 1, 1, 0, '2021-10-18 00:07:23', '2024-12-23 18:35:44'),
(110, 3, 1, 1, 'test', 1, 1, 0, '2021-10-18 00:07:56', '2024-12-23 18:35:44'),
(111, 3, 1, 1, 'test', 1, 1, 0, '2021-10-18 00:07:59', '2024-12-23 18:35:44'),
(112, 1, 4, 1, 'hi', 0, 0, 0, '2024-12-23 18:35:50', NULL),
(113, 1, 4, 1, 'hi', 0, 0, 0, '2024-12-23 18:38:10', NULL),
(114, 1, 4, 1, 'dsad', 0, 0, 0, '2024-12-23 18:38:13', NULL),
(115, 1, 4, 1, '', 0, 0, 0, '2024-12-23 18:38:14', NULL),
(116, 6, 5, 1, 'hi', 1, 1, 1, '2024-12-23 19:15:23', '2024-12-24 16:59:00'),
(117, 5, 6, 1, 'hello', 1, 1, 0, '2024-12-23 19:15:39', '2024-12-23 19:16:12'),
(118, 6, 5, 1, 'sadas', 1, 1, 1, '2024-12-24 16:57:45', '2024-12-26 19:57:43'),
(119, 6, 5, 1, '', 1, 1, 1, '2024-12-24 16:57:54', '2024-12-26 19:57:43'),
(120, 6, 5, 1, 'dsa', 1, 1, 0, '2024-12-24 16:58:52', '2024-12-26 19:57:43'),
(121, 5, 6, 1, 'asdas', 0, 0, 0, '2024-12-26 19:57:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pos_customers`
--

CREATE TABLE `pos_customers` (
  `customer_id` int(30) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_customers`
--

INSERT INTO `pos_customers` (`customer_id`, `firstname`, `lastname`, `address`, `contact_number`, `image`) VALUES
(1, 'James', 'Ucio', 'Looban', '09367773127', 'Cream Dark Grey Minimalist Printable Daily Journal (1).png');

-- --------------------------------------------------------

--
-- Table structure for table `refund_requests`
--

CREATE TABLE `refund_requests` (
  `refund_id` int(11) NOT NULL,
  `order_id` varchar(200) NOT NULL,
  `customer_id` varchar(200) NOT NULL,
  `refund_reason` text DEFAULT NULL,
  `refund_comments` text DEFAULT NULL,
  `refund_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rpos_admin`
--

CREATE TABLE `rpos_admin` (
  `admin_id` varchar(200) NOT NULL,
  `admin_name` varchar(200) NOT NULL,
  `admin_email` varchar(200) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rpos_admin`
--

INSERT INTO `rpos_admin` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
('10e0b6dc958adfb5b094d8935a13aeadbe783c2', 'System Admin', 'mjrdiagnosticmedicalsupply@gmail.com', '$2y$10$5vfe7DSV5znxb1mn4ynMAubiaDdUFo5GOuajoABG6D7GXS96ZY//.');

-- --------------------------------------------------------

--
-- Table structure for table `rpos_customers`
--

CREATE TABLE `rpos_customers` (
  `customer_id` varchar(200) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `customer_phoneno` varchar(15) DEFAULT NULL,
  `customer_email` varchar(200) NOT NULL,
  `customer_password` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `otp` varchar(6) NOT NULL,
  `archived` tinyint(4) DEFAULT 0,
  `street_address` varchar(255) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Philippines'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rpos_customers`
--

INSERT INTO `rpos_customers` (`customer_id`, `customer_name`, `customer_phoneno`, `customer_email`, `customer_password`, `created_at`, `token`, `is_verified`, `otp`, `archived`, `street_address`, `barangay`, `city`, `province`, `postal_code`, `country`) VALUES
('cust_675d67e467d739.24866736', 'James Ucio', '09367773127', 'luciojames493@gmail.com', '$2y$10$WR87ksJDwzxO8BMqLERay.Mei2oGU85RWEEwA/jg2CuQ2B9JYSVBS', '2024-12-14 11:12:32.173794', NULL, 1, '250307', 0, 'LOOBAN 2 LOMA DE GATO', 'loma', 'MARILAO', 'Bulacan', '3019', 'Philippines'),
('cust_676a83c80b0f61.97982658', 'Christian', '09367773123', 'genshinnikuya837@gmail.com', '$2y$10$orv1rYkfsYdiI4auzpi.FOFm7sEGTUP8/qupUh7ZrGk1SvB0BcCLm', '2024-12-24 09:50:21.673378', NULL, 1, '177323', 0, '', '', '', '', NULL, 'Philippines');

-- --------------------------------------------------------

--
-- Table structure for table `rpos_orders`
--

CREATE TABLE `rpos_orders` (
  `order_id` varchar(200) NOT NULL,
  `order_code` varchar(200) NOT NULL,
  `customer_id` varchar(200) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `prod_id` varchar(200) NOT NULL,
  `prod_name` varchar(200) NOT NULL,
  `prod_price` varchar(200) NOT NULL,
  `prod_qty` varchar(200) NOT NULL,
  `order_status` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `approved_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approved_by` varchar(255) DEFAULT NULL,
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `prod_expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rpos_orders`
--

INSERT INTO `rpos_orders` (`order_id`, `order_code`, `customer_id`, `customer_name`, `prod_id`, `prod_name`, `prod_price`, `prod_qty`, `order_status`, `created_at`, `approved_status`, `approved_by`, `proof_of_payment`, `prod_expiry_date`) VALUES
('8d4b38671c', 'DC84B-40163', 'cust_675d67e467d739.24866736', 'James Ucio', '075ef9cd67', 'Finecare Machine FIA', '50,000', '1', 'Pending', '2025-03-10 10:03:28.188595', 'Pending', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rpos_pass_resets`
--

CREATE TABLE `rpos_pass_resets` (
  `reset_id` int(20) NOT NULL,
  `reset_code` varchar(200) NOT NULL,
  `reset_token` varchar(200) NOT NULL,
  `reset_email` varchar(200) NOT NULL,
  `reset_status` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rpos_pass_resets`
--

INSERT INTO `rpos_pass_resets` (`reset_id`, `reset_code`, `reset_token`, `reset_email`, `reset_status`, `created_at`) VALUES
(20, '327186', '729dd5a43cf7e2dd44483fea9921e9b7d4527440', 'mjrdiagnosticmedicalsupply@gmail.com', 'Pending', '2024-11-21 07:18:22.963291'),
(21, '101785', '19cbd131c76a95473e5c3d0a4844bc6243445c4b', 'mjrdiagnosticmedicalsupply@gmail.com', 'Pending', '2024-11-21 07:27:08.366595'),
(22, '881343', '2ce4ebf432cbc235a7b523347036645ba6c915d8', 'mjrdiagnosticmedicalsupply@gmail.com', 'Pending', '2024-11-21 07:31:33.434986'),
(23, '689204', 'b2010399108135e1e934239efc2bf2f3b69edb64', 'mjrdiagnosticmedicalsupply@gmail.com', 'Pending', '2024-11-21 07:36:54.196268'),
(24, '829227', 'a3dc094619dc9b6a2fa001648c623129e7c88650', 'mjrdiagnosticmedicalsupply@gmail.com', 'Pending', '2024-11-21 07:48:11.718165'),
(25, '705709', 'cec28b8194e80213dd33e16abe50ff493be339f0', 'encoder1295@gmail.com', 'Pending', '2024-11-21 10:46:34.449959'),
(26, '609162', '4f13f84cd1a7e558ae7e709618aa50648ca7196e', 'luciojames493@gmail.com', 'Pending', '2024-11-22 03:46:48.963208'),
(27, '783652', '79e4da658c9fa1db8476a223f262c447c81f6598', 'mjrdiagnosticmedicalsupply@gmail.com', 'Pending', '2024-11-22 05:03:51.484286'),
(28, '125344', '7dfab3d69f39dd1d44f74d9b6b4cc81220a10152', 'mjrdiagnosticmedicalsupply@gmail.com', 'Pending', '2024-11-24 04:38:07.175739'),
(29, '720584', 'c5b3e6047ada97899d042428d6b1827ff69f91af', 'luciojames493@gmail.com', 'Pending', '2024-11-27 07:54:11.499324');

-- --------------------------------------------------------

--
-- Table structure for table `rpos_payments`
--

CREATE TABLE `rpos_payments` (
  `pay_id` varchar(200) NOT NULL,
  `pay_code` varchar(200) NOT NULL,
  `order_code` varchar(200) NOT NULL,
  `customer_id` varchar(200) NOT NULL,
  `pay_amt` varchar(200) DEFAULT NULL,
  `pay_method` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `product_name` varchar(200) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rpos_payments`
--

INSERT INTO `rpos_payments` (`pay_id`, `pay_code`, `order_code`, `customer_id`, `pay_amt`, `pay_method`, `created_at`, `proof_of_payment`, `product_name`, `quantity`) VALUES
('pay_67ceb8f02d924', 'PAY67CEB8E9A1A93', 'DC84B-40163', 'cust_675d67e467d739.24866736', ' 50,000.00', 'Gcash', '2025-03-10 10:03:28.187191', '../admin/uploads/PAY67CEB8E9A1A93.png', 'Finecare Machine FIA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rpos_products`
--

CREATE TABLE `rpos_products` (
  `prod_id` varchar(200) NOT NULL,
  `prod_code` varchar(200) NOT NULL,
  `prod_name` varchar(200) NOT NULL,
  `prod_img` varchar(200) NOT NULL,
  `prod_desc` longtext NOT NULL,
  `prod_price` varchar(200) NOT NULL,
  `prod_barcode` varchar(255) DEFAULT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `prod_stock` int(11) DEFAULT 0,
  `prod_expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rpos_products`
--

INSERT INTO `rpos_products` (`prod_id`, `prod_code`, `prod_name`, `prod_img`, `prod_desc`, `prod_price`, `prod_barcode`, `created_at`, `prod_stock`, `prod_expiry_date`) VALUES
('075ef9cd67', 'RDTM-4679', 'Finecare Machine FIA', 'FIA_meter__wondfo.jpg', 'Finecare™ FIA Meter is a fluorescence immunochromatographic analyzing system which helps diagnose conditions such as inflammation, diabetes, cardiovascular diseases, renal injury and cancers, etc.', '50,000', NULL, '2025-03-08 11:09:32.039710', 8, NULL),
('3dfaa3e19a', 'MADW-2386', 'Alcohol pads', 'alcohol pads.jfif', '3\r\n', '100', NULL, '2025-02-01 11:13:52.456398', 123, '2025-02-11'),
('45acad8ecf', 'XBQG-4627', 'Applicator stick sterile', 'applicator stick sterile.png', 'applicator stick sterile', '200', NULL, '2025-02-01 11:14:03.061973', 20, '2026-06-01'),
('48cf51b26a', 'ERNM-1683', 'BS-230 Pro', 'bs-230.thumb.319.319.JPEG', 'The MINDRAY BS230 is a bench top chemistry analyzer manufactured by Mindray. It measures 43 assays with a maximum throughput of 200 tests per hour.', '2,000,000', NULL, '2025-02-07 11:34:03.489625', 1, NULL),
('4d7c446144', 'BYRF-9045', 'Benedicts Solution', 'benedicts solution.webp', 'benedicts solution', '250', NULL, '2024-12-05 02:25:30.549072', 102, NULL),
('6766811758', 'FSHZ-2403', 'Blood Pressure ', 'blood pressure sinocare.jpg', 'Blood pressure is the pressure of blood on the walls of your arteries as your heart pumps blood around your body.', '500', NULL, '2024-12-05 02:54:30.876831', 10, NULL),
('6a39e02c92', 'KRDA-7468', 'CFL Lyse 3Parts', 'cfl lyse.jpg', 'Compact Fluorescent Lamps (CFLs) are energy-saving light bulbs, which last longer and use far less energy than traditional (or incandescent) light bulbs for the same level of light intensity.', '5,500', NULL, '2025-01-16 07:27:55.909504', 81, NULL),
('714e59a3bd', 'LKEN-6509', 'Finecare Machine FIA', 'FIA_meter__wondfo.jpg', 'Finecare™ FIA Meter Plus is a fluorescence immunochromatographic analysing system with internal temperature control, which can help diagnose conditions such as infection, diabetes, cardiovascular diseases, renal injury and cancers, etc.', '50,000', NULL, '2024-12-06 01:33:44.589588', 9, NULL),
('7b7aaf0582', 'OKUN-4086', 'BS-230 Pro', 'bs-230.thumb.319.319.JPEG', ' a “Cute”, multi-functional bench-top clinical chemistry analyzer with a throughput up to 200 T/H, up to 400 T/H with ISE.', '2,000,000', NULL, '2025-01-14 09:56:52.799307', 4, NULL),
('7e6d85df36', 'XORZ-0364', 'TSH Finecare', 'tsh.jpg', ' a fluorescence immunoassay for quantitative measurement of thyroid stimulating hormone (TSH) in human whole blood, serum or plasma. ', '750', NULL, '2025-01-15 08:45:48.303970', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rpos_sales`
--

CREATE TABLE `rpos_sales` (
  `sales id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rpos_staff`
--

CREATE TABLE `rpos_staff` (
  `staff_id` int(20) NOT NULL,
  `staff_name` varchar(200) NOT NULL,
  `staff_number` varchar(200) NOT NULL,
  `staff_email` varchar(200) NOT NULL,
  `staff_password` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `status` enum('active','archived') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rpos_staff`
--

INSERT INTO `rpos_staff` (`staff_id`, `staff_name`, `staff_number`, `staff_email`, `staff_password`, `created_at`, `status`) VALUES
(8, 'Ecoder', 'UOAS-9257', 'encoder1295@gmail.com', '$2y$10$bVXGKHXNmSfNRhfWsuh9Mu5Xv5rmhr868xlZlr6D49WKhkFwQgu0u', '2024-12-05 00:18:34.148481', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `rpos_suppliers`
--

CREATE TABLE `rpos_suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_phoneno` varchar(15) NOT NULL,
  `supplier_email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rpos_suppliers`
--

INSERT INTO `rpos_suppliers` (`supplier_id`, `supplier_name`, `supplier_phoneno`, `supplier_email`, `address`, `created_at`, `updated_at`, `archived`) VALUES
(4, 'DADSA', '09367773127', 'luciojames493@gmail.com', 'LOOBAN 2 LOMA DE GATO', '2024-12-14 10:37:35', '2024-12-14 10:57:46', 0),
(5, 'DADSA', '09367773127', 'luciojames493@gmail.com', 'LOOBAN 2 LOMA DE GATO', '2024-12-14 10:40:51', '2024-12-14 10:40:51', 0),
(6, 'DADSA', '09367773127', 'luciojames493@gmail.com', 'LOOBAN 2 LOMA DE GATO', '2024-12-14 10:42:05', '2024-12-14 10:42:05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `reciept_no` int(30) NOT NULL,
  `customer_id` int(30) NOT NULL,
  `username` varchar(255) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`reciept_no`, `customer_id`, `username`, `discount`, `total`) VALUES
(1, 1, 'admin', 0.00, 100.00),
(2, 1, 'admin', 0.00, 100.00),
(3, 1, 'admin', 0.00, 100.00),
(4, 1, 'admin', 0.00, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `sales_product`
--

CREATE TABLE `sales_product` (
  `id` int(30) NOT NULL,
  `reciept_no` int(30) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `position` varchar(20) NOT NULL,
  `contact_number` varchar(30) NOT NULL,
  `image` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `position`, `contact_number`, `image`, `password`) VALUES
(7, 'admin', 'Juan', 'Cruz', 'admin', '+63(09)1234-1234', 'Myprofile.jpg', '21232f297a57a5a743894a0e4a801fc3'),
(13, 'user', 'Chris', 'Doe', 'Employee', '+63(09)1234-1234', 'men-in-black.png', 'ee11cbb19052e40b07aac0ca060c23ee'),
(15, '', '', '', '', '', 'Cream Dark Grey Minimalist Pri', '$2y$10$EPLgDKZpdda6oAJXFy1AVeR1NbHotp7xaLCyeKieYRMQfir5n3ULC'),
(19, 'luciojames493', 'James', 'Ucio', 'Admin', '+63(09)1234-1237', '2.png', 'e64b78fc3bc91bcbc7dc232ba8ec59e0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archived_payments`
--
ALTER TABLE `archived_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `convo_list`
--
ALTER TABLE `convo_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_customers`
--
ALTER TABLE `pos_customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`refund_id`);

--
-- Indexes for table `rpos_admin`
--
ALTER TABLE `rpos_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `rpos_customers`
--
ALTER TABLE `rpos_customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_name` (`customer_name`),
  ADD KEY `idx_customer_id` (`customer_id`);

--
-- Indexes for table `rpos_orders`
--
ALTER TABLE `rpos_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `unique_order_code` (`order_code`),
  ADD KEY `CustomerOrder` (`customer_id`),
  ADD KEY `ProductOrder` (`prod_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `rpos_pass_resets`
--
ALTER TABLE `rpos_pass_resets`
  ADD PRIMARY KEY (`reset_id`);

--
-- Indexes for table `rpos_payments`
--
ALTER TABLE `rpos_payments`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `order` (`order_code`);

--
-- Indexes for table `rpos_products`
--
ALTER TABLE `rpos_products`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `rpos_staff`
--
ALTER TABLE `rpos_staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `rpos_suppliers`
--
ALTER TABLE `rpos_suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`reciept_no`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `sales_product`
--
ALTER TABLE `sales_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reciept_no` (`reciept_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `user_id` (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archived_payments`
--
ALTER TABLE `archived_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `convo_list`
--
ALTER TABLE `convo_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `pos_customers`
--
ALTER TABLE `pos_customers`
  MODIFY `customer_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `refund_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rpos_pass_resets`
--
ALTER TABLE `rpos_pass_resets`
  MODIFY `reset_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `rpos_staff`
--
ALTER TABLE `rpos_staff`
  MODIFY `staff_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rpos_suppliers`
--
ALTER TABLE `rpos_suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `reciept_no` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales_product`
--
ALTER TABLE `sales_product`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rpos_orders`
--
ALTER TABLE `rpos_orders`
  ADD CONSTRAINT `CustomerOrder` FOREIGN KEY (`customer_id`) REFERENCES `rpos_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ProductOrder` FOREIGN KEY (`prod_id`) REFERENCES `rpos_products` (`prod_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rpos_payments`
--
ALTER TABLE `rpos_payments`
  ADD CONSTRAINT `fk_order_code` FOREIGN KEY (`order_code`) REFERENCES `rpos_orders` (`order_code`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `pos_customers` (`customer_id`);

--
-- Constraints for table `sales_product`
--
ALTER TABLE `sales_product`
  ADD CONSTRAINT `sales_product_ibfk_1` FOREIGN KEY (`reciept_no`) REFERENCES `sales` (`reciept_no`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

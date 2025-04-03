-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2025 at 10:28 AM
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
-- Database: `pos_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `discount_type` enum('fixed','percentage') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_shifts`
--

CREATE TABLE `employee_shifts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shift_start` datetime NOT NULL,
  `shift_end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` enum('rent','utilities','supplies','salary','other') NOT NULL,
  `expense_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_log`
--

CREATE TABLE `inventory_log` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `change_type` enum('add','remove','sale') NOT NULL,
  `quantity` int(11) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_reports`
--

CREATE TABLE `inventory_reports` (
  `id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `low_stock_count` int(11) NOT NULL,
  `total_stock_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `change_type` enum('restock','sale','return','damage') NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','refunded') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('cash','card','digital_wallet') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `price` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `descriptions` varchar(255) DEFAULT NULL,
  `discount` int(11) NOT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `barcode`, `price`, `stock`, `created_at`, `category`, `image`, `size`, `descriptions`, `discount`, `gender`, `discount_type`) VALUES
(52, 'zaa', '234', '32.00', 43, '2025-03-21 16:11:57', 'Bag', 'uploads/Screenshot 2025-03-21 000052.png', '', '', 0, '', ''),
(53, 'mesa', '2342', '32.00', 27, '2025-03-21 19:27:10', 'Nightwear', 'uploads/Screenshot 2025-03-14 224851.png', '', '', 0, '', ''),
(54, 'zaa', '324', '34.00', 3, '2025-03-21 19:27:37', 'Bag', 'uploads/Screenshot 2025-03-15 105843.png', '', '', 0, '', ''),
(55, 'mesaa', '23', '23.00', 23, '2025-03-21 19:28:01', 'Shoes', 'uploads/Screenshot 2025-03-15 112321.png', '', '', 0, '', ''),
(56, 'haha', '453', '23.00', 53, '2025-03-21 19:28:26', 'Sport Clothes', 'uploads/Screenshot 2025-03-15 105843.png', NULL, 'dsf', 44, NULL, ''),
(57, 'mesaa', '45', '44.00', 43, '2025-03-21 19:32:46', 'Uniform', 'uploads/Screenshot 2025-03-21 000052.png', '', '', 0, '', ''),
(58, 'zaa', '43', '43.00', 43, '2025-03-21 19:33:37', 'T-shirt', 'uploads/Screenshot 2025-03-15 112321.png', '', '', 0, '', ''),
(59, 'error', '33', '44', 35, '2025-03-22 00:40:34', 'Bag', 'uploads/Screenshot 2025-03-17 225739.png', 'L', 'error', 34, 'Men', 'sdaf'),
(60, 'mesa', '44', '33.00', 23, '2025-03-22 01:52:49', 'Uniform', 'uploads/Screenshot 2025-03-14 230230.png', '', '', 0, '', ''),
(62, 'zaaa', '233', '22.00', 33, '2025-03-23 02:42:09', 'Student Material', 'uploads/Screenshot 2025-03-23 072803.png', '', '', 0, '', ''),
(63, 'mesa', 'ds3', '22.00', 33, '2025-03-23 04:17:22', 'Shirt', 'uploads/Screenshot 2025-03-22 011935.png', '', '', 0, '', ''),
(64, 'hehe', 'ea3def', '3.00', 23, '2025-03-23 05:08:11', 'Shirt', 'uploads/Screenshot 2025-03-15 112321.png', 'L', 'error', 33, 'Men', ''),
(65, 'error code', '234e', '34.00', 34, '2025-03-23 05:19:03', 'Shoes', 'uploads/Screenshot 2025-03-14 224851.png', 'L', 'fsgd', 34, 'Men', ''),
(66, 'mmm', '234dsf', '32.00', 32, '2025-03-23 06:44:11', 'Student Material', 'uploads/Screenshot 2025-03-15 112321.png', 'L', 'error', 23, 'Men', ''),
(67, 'kikii', '123', '22.00', 33, '2025-03-24 01:05:24', 'T-shirt', 'uploads/Black_O_Crew_Regular_NoPocket.webp', 'L', NULL, 33, 'Men', ''),
(68, 'MESA', '3211', '21', 33, '2025-03-24 02:19:13', 'Bag', 'uploads/360_F_236992283_sNOxCVQeFLd5pdqaKGh8DRGMZy7P4XKm.jpg', 'L', NULL, 23, 'Men', ''),
(69, 'MESA', '54321', '12', 33, '2025-03-24 02:33:01', 'Shirt', 'uploads/Black_O_Crew_Regular_NoPocket.webp', 'L', NULL, 20, 'Men', 'ort dg.'),
(70, 'MESA', '555', '32', 30, '2025-03-24 02:43:32', 'T-shirt', 'uploads/pets-3715733_1280.jpg', 'L', NULL, 23, 'Men', 'saf'),
(71, 'sa', '444', '22', 32, '2025-03-24 02:52:45', 'T-shirt', 'uploads/360_F_236992283_sNOxCVQeFLd5pdqaKGh8DRGMZy7P4XKm.jpg', 'L', 'good', 12, 'Men', 'sfdf'),
(72, 'zaaa', '1234321', '22', 33, '2025-03-24 12:05:18', 'Shirt', 'uploads/Black_O_Crew_Regular_NoPocket.webp', NULL, NULL, 0, NULL, NULL),
(73, 'MESA', '55555', '21', 33, '2025-03-24 12:19:26', 'Bag', 'uploads/360_F_236992283_sNOxCVQeFLd5pdqaKGh8DRGMZy7P4XKm.jpg', NULL, NULL, 0, NULL, NULL),
(74, 'zeiiza', '999999', '34', 33, '2025-03-24 12:22:57', 'T-shirt', 'uploads/Black_O_Crew_Regular_NoPocket.webp', NULL, NULL, 0, NULL, NULL),
(75, 'zaaa', '8888', '33', 44, '2025-03-24 12:25:46', 'Sport Clothes', 'uploads/360_F_236992283_sNOxCVQeFLd5pdqaKGh8DRGMZy7P4XKm.jpg', NULL, NULL, 0, NULL, NULL),
(76, 'zaaa', '3333', '11', 22, '2025-03-24 12:49:41', 'Bag', 'uploads/istockphoto-2159021515-612x612.jpg', 'L', NULL, 22, 'Men', 'dsfg'),
(77, 'zaaa', '222', '22', 22, '2025-03-25 06:43:25', 'T-shirt', 'uploads/Black_O_Crew_Regular_NoPocket.webp', 'L', NULL, 22, 'Men', 'ddd'),
(78, 'nh', '1111', '22', 24, '2025-03-25 07:09:37', 'Student Material', 'uploads/360_F_236992283_sNOxCVQeFLd5pdqaKGh8DRGMZy7P4XKm.jpg', NULL, NULL, 0, NULL, NULL),
(79, 'zaaasa', '243', '22', 2225, '2025-03-25 07:27:12', 'Shirt', 'uploads/Black_O_Crew_Regular_NoPocket.webp', 'L', 'asd', 21, 'Men', 'dsaf'),
(80, 'zaaasa', '32', '33', 36, '2025-03-25 07:53:40', 'Bag', 'uploads/Black_O_Crew_Regular_NoPocket.webp', NULL, NULL, 0, NULL, NULL),
(82, 'kikii', '4323', '32', 34, '2025-03-25 08:13:17', 'Clothes', 'uploads/pets-3715733_1280.jpg', NULL, NULL, 0, NULL, NULL),
(83, 'zaaa', '431', '32', 32, '2025-03-25 08:17:15', 'Nightwear', 'uploads/360_F_236992283_sNOxCVQeFLd5pdqaKGh8DRGMZy7P4XKm.jpg', 'L', 'sadf', 34, 'Men', 'dfs'),
(84, 'zaaa', '3422', '342', 34, '2025-03-25 09:09:32', 'Shoes', 'uploads/Black_O_Crew_Regular_NoPocket.webp', 'L', 'sd', 43, 'Men', '34'),
(85, 'sakded', '3454', '34', 27, '2025-03-25 11:49:46', 'Shirt', 'uploads/360_F_236992283_sNOxCVQeFLd5pdqaKGh8DRGMZy7P4XKm.jpg', NULL, NULL, 34, NULL, 'sdaf'),
(86, 'kikii', '124', '44', 46, '2025-03-26 10:15:59', 'Sport Clothes', 'uploads/Black_O_Crew_Regular_NoPocket.webp', 'L', 'goodd', 22, 'Men', 'sd'),
(87, 'z', '211', '11', 12, '2025-03-27 05:34:41', 'Shirt', 'uploads/360_F_236992283_sNOxCVQeFLd5pdqaKGh8DRGMZy7P4XKm.jpg', 'L', 'assda', 32, 'Men', 'asd'),
(88, 'ME', '344', '3', 33, '2025-03-27 10:23:48', 'Bag', 'uploads/Black_O_Crew_Regular_NoPocket.webp', 'L', 'sd', 34, 'Men', 'sdaf'),
(89, 'kikii', '23E', '11', 14, '2025-03-29 07:22:19', 'Student Material', 'uploads/WIN_20240904_09_21_31_Pro.jpg', 'L', 'AD', 12, 'Men', NULL),
(90, 'sdf', 'dfsgdsg', '43', 34, '2025-03-31 05:50:33', 'Bag', 'uploads/WIN_20240918_14_14_43_Pro.jpg', 'L', 'df', 43, 'Men', NULL),
(91, 'zaaa', '233333', '23', 12, '2025-03-31 07:47:19', 'Bag', 'uploads/', 'S', 'gfdh', 45, 'female', NULL),
(92, 'kikii', 'dsaf', '23', 43, '2025-03-31 08:02:03', 'Bag', 'https://static.vecteezy.com/system/resources/previews/024/176/721/non_2x/colorful-soft-3d-halftone-wave-effect-simple-gradient-abstract-background-suitable-for-landing-page-and-computer-desktop-wallpaper-vector.jpg', 'M', 'dsa', 233, 'female', NULL),
(93, 'zaaa', 'saf', '43', 23, '2025-03-31 08:02:38', 'Bag', 'https://static.vecteezy.com/system/resources/previews/024/176/721/non_2x/colorful-soft-3d-halftone-wave-effect-simple-gradient-abstract-background-suitable-for-landing-page-and-computer-desktop-wallpaper-vector.jpg', 'S', 'sda', 11, 'female', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','digital') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_reports`
--

CREATE TABLE `sales_reports` (
  `id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `total_sales` decimal(10,2) NOT NULL,
  `total_orders` int(11) NOT NULL,
  `total_refunds` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_config`
--

CREATE TABLE `tax_config` (
  `id` int(11) NOT NULL,
  `tax_name` varchar(50) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cashier') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `image`, `phone`, `address`) VALUES
(100, 'mesaaa', 'mesa@mfdgygmail.com', '$2y$10$TjOE5rJ8FOOB8UnCinacPOIiNzPyXyxYLHrhSaWDgeo0Ujt5GJYjW', 'cashier', '2025-03-23 07:29:09', '', '', ''),
(106, 'mesa', 'mesa@mygmail.comsad', '$2y$10$.hhsZoiv.o31xKx4.YUEAuvr4sZLpLGAPj1gt94foNco.aEMuRTwG', 'cashier', '2025-03-23 07:58:10', '', '', ''),
(110, 'MESA NAT', 'mesa.nath@gmail.com', '$2y$10$kUUVY7g/tEc75gCVcdZcCuEFkL2/5.QxFffh.EgcbQqA6KEFNWiqe', 'cashier', '2025-03-24 06:57:34', '', '', ''),
(113, 'Sinh', 'sinh.ern@gmail.com', '$2y$10$u/Vpjj2ith5wUlqe8vyLN.MljqhzGPNgpm545/Rk4pFLQ1fPhVu7K', 'admin', '2025-03-26 12:56:11', '', '', ''),
(124, 'zaaa', 'mesa@whoami.com', '$2y$10$QUtV2zAZkZfKLBUpRptSYuLKHq2chY43v8XDQzD4pJDnc6p7w4SVO', 'cashier', '2025-03-28 11:19:26', 'uploads/1743167891_WIN_20240904_09_21_38_Pro.jpg', '', ''),
(125, 'sa', 'mesa@whoami.comdsaf', '$2y$10$1nNX7ZeRaBQWkVOD1UUQpucFIC.2HwjYbbwWzL0QYW24LZN2Sclr6', 'cashier', '2025-03-28 12:30:01', 'uploads/1743165520_WIN_20250308_14_48_42_Pro.jpg', '', ''),
(126, 'kikii', 'mesa@whoami.comfs', '$2y$10$zESZQGrcBH9ec8k6wuc7zuElwVaSTJJRX9R.oJbZfgOHPleEHZ5OW', 'cashier', '2025-03-28 13:18:37', 'uploads/1743167917_WIN_20241009_09_27_02_Pro.jpg', '', ''),
(129, 'sad', 'mesa@sdkhjasdiujgimal.comds', '$2y$10$Ff2WuwsJv6/szGFzVfXZFOW5dYvA7/veCWUNoTDG7Xr0bqHmd/iJK', 'cashier', '2025-03-28 13:41:15', 'uploads/1743169275_WIN_20241009_09_27_02_Pro.jpg', '0909685942', 'Phnom Penh'),
(134, 'kikii', 'mesa@whoami.comdsf', '$2y$10$Ki7H94PH1VfOfLEk3DFtGe1pVtUdcWbVr41PBrEcCEChHUfJkrZGG', 'cashier', '2025-03-31 05:07:58', 'uploads/1743397678_WIN_20250308_14_50_37_Pro.jpg', '0909685941', 'Phnom Penh');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`);

--
-- Indexes for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_log`
--
ALTER TABLE `inventory_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_config`
--
ALTER TABLE `tax_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_log`
--
ALTER TABLE `inventory_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_reports`
--
ALTER TABLE `sales_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_config`
--
ALTER TABLE `tax_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  ADD CONSTRAINT `employee_shifts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_log`
--
ALTER TABLE `inventory_log`
  ADD CONSTRAINT `inventory_log_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2026 at 12:38 PM
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
-- Database: `techcart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Laptops'),
(2, 'Phones'),
(3, 'Audio'),
(4, 'Wearables');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `order_status`, `created_at`, `customer_name`, `email`, `phone`, `address`) VALUES
(1, NULL, 4993.00, 'Shipped', '2026-07-05 15:48:49', 'dvvadv', 'sc@gmail.com', '15633', 'reghe'),
(2, NULL, 897.00, 'Pending', '2026-07-05 15:51:59', 'fhghdf', 'fgdg@gmail.com', '35387', 'fdhh'),
(3, NULL, 299.00, 'Pending', '2026-07-06 12:08:23', 'dfkjs', 'jaS@gmail.com', '549', 'gdagr'),
(4, 1, 299.00, 'Processing', '2026-07-06 12:16:31', 'Test User', 'test123@gmail.com', '0247895692', 'Accra'),
(5, 2, 1997.00, 'Pending', '2026-07-07 12:45:45', 'Hanson Henry', 'hanhen@gmail.com', '+233 7456897531', 'GH789, Accra High ST, Accra'),
(6, 3, 1499.00, 'Pending', '2026-07-07 13:06:55', 'Nathan Mo', 'natmo@gmail.com', '+233 7896541234', 'HJ741, Teshie ST, Accra'),
(7, 4, 1499.00, 'Pending', '2026-07-08 09:06:52', 'James Kanigan', 'jamigan@gmail.com', '+233 5632147890', 'AD74, Adenta St, Accra'),
(8, 5, 4497.00, 'Pending', '2026-07-08 09:08:29', 'Jon Devi', 'devon@gmail.com', '+233 7896541230', 'AD25, Adenta ST, Accra'),
(9, 5, 4497.00, 'Pending', '2026-07-08 09:16:41', 'Jon Devi', 'devon@gmail.com', '+233 7896541230', 'AD25, Adenta ST, Accra'),
(10, 6, 199.00, 'Pending', '2026-07-08 11:04:01', 'Nick Jonghyun', 'jongant@gmail.com', '0245137869', 'AD78, Adenta St, Accra');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 3, 3, 199.00),
(2, 1, 2, 4, 1099.00),
(3, 2, 4, 3, 299.00),
(4, 3, 4, 1, 299.00),
(5, 4, 4, 1, 299.00),
(6, 5, 1, 1, 1499.00),
(7, 5, 3, 1, 199.00),
(8, 5, 4, 1, 299.00),
(9, 6, 1, 1, 1499.00),
(10, 7, 1, 1, 1499.00),
(11, 9, 1, 3, 1499.00),
(12, 10, 3, 1, 199.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `product_name`, `description`, `price`, `discount_price`, `stock`, `image`, `created_at`) VALUES
(1, 1, 'MacBook Pro', 'Apple M3 MacBook Pro 14-inch', 1499.00, 1299.00, 50, 'macbook.jpg', '2026-07-04 15:55:17'),
(2, 2, 'iPhone 16', 'Latest Apple iPhone 16', 1099.00, 999.00, 20, 'iphone.jpg', '2026-07-04 15:55:17'),
(3, 3, 'Gaming Headset', 'RGB Surround Gaming Headset', 199.00, 149.00, 28, 'headset.jpg', '2026-07-04 15:55:17'),
(4, 4, 'Smart Watch', 'Premium Smart Watch', 299.00, 249.00, 17, 'watch.jpg', '2026-07-04 15:55:17'),
(6, 1, 'Dell XPS 15 Pro', 'Intel Core Ultra 7 Laptop', 2000.00, NULL, 12, '1783502103_Dell XPS 15 Pro.jpg', '2026-07-05 16:31:30'),
(7, 2, 'Samsung S25 5G', 'Compact 6.2-inch flagship featuring a premium matte glass design with ultra-thin, symmetrical screen bezels.Driven by the elite Snapdragon 8 processor and 12GB RAM for seamless gaming and everyday multitasking.Advanced 50MP triple-camera array enhanced with smart Galaxy AI tools for flawless photo editing.Shipped with Android 15 (One UI 7) and backed by an industry-leading 7 years of software support.', 799.00, NULL, 50, '1783502079_Samsung S25 5G.webp', '2026-07-08 09:14:39'),
(8, 1, 'Dell XPS 15', 'Premium 15.6-inch laptop crafted with a sleek CNC aluminum chassis and a lightweight, durable carbon fiber palm rest.Stunning InfinityEdge display available up to a 3.5K OLED touchscreen with extreme color accuracy for creative work.Powerful Intel Core processors paired with NVIDIA RTX graphics to easily handle heavy video editing, coding, and multitasking.Flexible user-upgradable design featuring dual slots for future-proof RAM and SSD storage expansion.', 1500.00, NULL, 43, '1783502193_Dell XPS 15.webp', '2026-07-08 09:16:33'),
(9, 4, 'Ray-Ban Stories Wayfarer Smart Glasses', 'Meta smart sunglasses blend iconic fashion with advanced hands-free wearable artificial intelligence technology.Built-in 3K cameras and open-ear audio capture crisp POV content and deliver clear personal sound.Integrated Meta AI voice controls let you translate languages, ask questions, and interact hands-free.Multiple styles across Ray-Ban, Oakley, and standalone frames easily accommodate custom vision prescriptions.', 299.00, NULL, 50, '1783549565_Ray-Ban Stories Wayfarer Smart Glasses.webp', '2026-07-08 22:26:05'),
(10, 3, 'JBL Tune 720BT', '76-Hour Battery Life: Delivers exceptional longevity with a quick 5-minute charge providing 3 hours of playback.JBL Pure Bass Sound: Powered by 40mm dynamic drivers that emphasize deep, punchy low-end audio frequencies.Bluetooth 5.3 & Multi-Point: Ensures stable wireless connectivity and seamless switching between two devices simultaneously.Lightweight, Folding Design: Features a comfortable, collapsible build with app support for custom EQ adjustments.', 350.00, NULL, 60, '1783550531_JBL Tune 720BT.webp', '2026-07-08 22:42:11'),
(11, 2, 'Samsung Note 10', 'The Samsung Galaxy Note10 is a premium smartphone with a sleek 6.3-inch Dynamic AMOLED display and a compact, elegant design.\r\nIt is powered by a fast processor, offering smooth performance for multitasking, gaming, and everyday use.\r\nThe built-in S Pen provides precise note-taking, drawing, and productivity features, making it ideal for work and creativity.\r\nIts versatile cameras, long-lasting battery, and fast charging deliver a reliable all-around smartphone experience.', 949.99, NULL, 70, '1783585330_Samsung Note 10.jpg', '2026-07-09 08:22:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, 'Test User', 'test123@gmail.com', '', '0247895692', 'Accra', '2026-07-06 12:16:31'),
(2, 'Hanson Henry', 'hanhen@gmail.com', '', '+233 7456897531', 'GH789, Accra High ST, Accra', '2026-07-07 12:45:45'),
(3, 'Nathan Mo', 'natmo@gmail.com', '', '+233 7896541234', 'HJ741, Teshie ST, Accra', '2026-07-07 13:06:55'),
(4, 'James Kanigan', 'jamigan@gmail.com', '', '+233 5632147890', 'AD74, Adenta St, Accra', '2026-07-08 09:06:52'),
(5, 'Jon Devi', 'devon@gmail.com', '', '+233 7896541230', 'AD25, Adenta ST, Accra', '2026-07-08 09:08:29'),
(6, '', 'jongant@gmail.com', '$2y$10$qSDVsql1sS3eteVpLtz96.SrALbg5qhWJvXV996WNh6HxCEPVa4zK', '', '', '2026-07-08 10:27:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

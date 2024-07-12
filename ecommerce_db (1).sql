-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2024 at 05:55 AM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'Lenovo'),
(4, 'GiGABYTE'),
(10, 'DELL'),
(12, 'HP'),
(13, 'ACER'),
(14, 'ASUS'),
(15, 'MSI');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `quantity`) VALUES
(63, 23, 93, 4),
(64, 28, 93, 1);

-- --------------------------------------------------------

--
-- Table structure for table `displays`
--

CREATE TABLE `displays` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `displays`
--

INSERT INTO `displays` (`id`, `name`) VALUES
(2, '16\" 2560 x 1600 px IPS 240 Hz '),
(3, '16\" QHD+ 240Hz Mini LED'),
(4, '15.6\" 1920 x 1080 px IPS 144 HZ '),
(8, '16\" 3k IPS 165 Hz / touch'),
(9, '14\'\' 1920 x 1080 px IPS 60 Hz'),
(10, '18\" 3840 x 2400 px mini LED 120Hz'),
(11, '15.6\" 1920 x 1080 px IPS 60 Hz'),
(12, '16\" 2560 x 1600 px 240Hz OLED'),
(13, '16.0\" 1920x1200 px IPS, 165Hz');

-- --------------------------------------------------------

--
-- Table structure for table `graphics`
--

CREATE TABLE `graphics` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `graphics`
--

INSERT INTO `graphics` (`id`, `name`) VALUES
(2, 'NVIDIA GeForce RTX 4080 Laptop GPU ROG Boost: 2330MHz* at 175W (2280MHz Boost Clock+50MHz OC, 150W+25W Dynamic Boost) MUX Switch + NVIDIA Advanced Optimus'),
(3, 'Nvidia GeForce RTX 4050 with 6GB of DDR6 VRAM +Intel Integrated graphics'),
(4, 'AMD Radeon Vega 6, 6 CUs, 1.5 GHz'),
(5, 'Nvidia GeForce RTX 4090 Laptop 16GB graphics (up to 175W with Dynamic Boost) with MUX, without Advanced Optimus or GSync'),
(6, 'Nvidia MX550 2GB (30W) +Integrated Iris Xe Graphics'),
(7, 'Nvdia GeForce RTX 4070 Laptop Gpu - 8 GB VRAM, 80 W TDP'),
(8, 'Nvdia GeForce RTX™ 4050 Laptop GPU 6GB GDDR6');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `total_price` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `email`, `number`, `name`, `product_name`, `quantity`, `total_price`, `address`, `method`, `payment_status`) VALUES
(57, 23, 0, 'saefas@mail.com', '54653', 'sSD', 'GIGABYTE G6 Series - 16\'\' (2023)', 1, '140270', 'aefsf', 'credit_card', 'pending'),
(58, 25, 0, 'duhujity@mailinator.com', '58', 'Herman Ferguson', 'Asus ROG Zephyrus G16 (2024)', 1, '256000', 'Sit officia velit n', 'credit_card', 'complete'),
(59, 25, 0, 'ceqa@mailinator.com', '340', 'Rachel Wood', 'Titan 18 HX A14VIG-036US (2024)', 1, '701870', 'Ut fuga Saepe enim', 'credit_card', 'complete'),
(60, 25, 0, 'taful@mailinator.com', '833', 'Faith Harmon', 'ASUS ROG Strix SCAR 16 (2024) (Off Black)', 2, '921600', 'Eu officia vero pari', 'credit_card', 'pending'),
(67, 28, 0, 'rajo@example.com', '723', 'Octavius Deleon', 'Asus ROG Zephyrus G16 (2024)', 3, '768000', '885 Cowley Drive', 'credit_card', 'complete');

-- --------------------------------------------------------

--
-- Table structure for table `processors`
--

CREATE TABLE `processors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `processors`
--

INSERT INTO `processors` (`id`, `name`) VALUES
(5, '14th Gen / Intel Core i9-14900HX 2.20 GHz '),
(6, '13th Gen / Intel Core i9-13900HX 2.20 GHz'),
(7, 'Series 1 / Intel Core Ultra 9 185H 5.1GHz'),
(8, 'Ryzen 5 4500U / 6C/6T'),
(9, '12th Gen / Intel Core i7-1255U (up to 4.7 GHz Turbo)'),
(10, '13th Gen Intel Core i7-13620H (up to 4.9 GHz)');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `brand_id` int(11) DEFAULT NULL,
  `processor_id` int(11) DEFAULT NULL,
  `ram_id` int(11) DEFAULT NULL,
  `storage_id` int(11) DEFAULT NULL,
  `display_id` int(11) DEFAULT NULL,
  `graphic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `details`, `image`, `quantity`, `brand_id`, `processor_id`, `ram_id`, `storage_id`, `display_id`, `graphic_id`) VALUES
(84, 'Lenovo 16\" Legion Pro 7i 16IRX8H Gaming Laptop (Onyx Gray)', '336000', 'This gen8 Lenovo Legion Pro 7i is a mixed bag. It\'s still a good high-tier laptop with powerful components and solid performance in demanding loads, as well as an ergonomic utilitarian design. It\'s also among the more competitvely priced options in its class. But at the same time, compromises were made with this series, as this runs toasty on the high-power profiles and is only offered with a standad-gamut screen, among others.', 'lenovo_82wq002rus_16_legion_pro_7_1745683.jpg,1690997535_IMG_1928225.jpg,1690997535_IMG_1928231.jpg,1690997535_IMG_1928232.jpg,1690997535_IMG_1928230.jpg', 8, 1, 6, 9, 3, 2, 2),
(86, 'ASUS ROG Strix SCAR 16 (2024) (Off Black)', '460800', 'The Asus ROG Strix Scar 16 is a competitive high-performance laptop in its class, and stands out from the crowd in this i9+ RTX 4080 variant thanks to its performance, competitive pricing (in most markets), and beautiful mini LED display. Just make sure you\'re OK with its peculiar design particularities, and you acknowledge and accept the high CPU/GPU temperatures in sustained loads and gaming sessions.', '34-236-489-01.png,h732.png,23213.png,123.png,1231231.png,243rds.png', 17, 14, 5, 9, 2, 3, 2),
(88, 'Lenovo Yoga Pro 9i 16 (2024)', '239840', ' This Lenovo Yoga Pro 9i 16 laptop is a pretty good choice if you’re looking for a 16-inch computer for daily use and with some more features for creators.  It’s thin and light for what it is, quite powerful and well balanced in overall behavior, plus comes with a beautiful touch display and punchy speakers. At the same time, though, the market for such 16-inch laptops is highly competitive today.', '6571367_sd.jpg,6571367_rd.jpg,6571367cv13d.jpg,6571367cv14d.jpg,6571367cv17d.jpg', 10, 1, 7, 9, 4, 8, 3),
(89, 'Lenovo IdeaPad 5 14ARE05', '92268', ' For the most part, this Lenovo IdeaPad 5 feels and performs like a much more expensive product. It\'s a very quick little laptop, and also quiet and comfortable with daily use. It’s also well made, with the exception of that delicate metal lid, types well, and includes the IO and the battery life that I\'d need while on the go. However, having used it for the last few weeks, I decided a 54% sRGB screen won\'t do for me and I\'d rather pay extra for a better-quality panel. My entire experience with this notebook is documented down below.', 'IdeaPad_5_14ARE05_CT1_01.avif,6549071cv13d.jpg,6549071cv14d.jpg,6549071cv3d.jpg,6549071cv7d.jpg', 30, 1, 8, 10, 5, 9, 4),
(90, 'Titan 18 HX A14VIG-036US (2024)', '701870', 'The MSI Titan 18 is the most OG high-performance work and gaming laptop available today. But that doesn\'t mean it\'s also the right choice for most potential buyers, given the price demanded by MSI and the particularities of the chassis, keyboard and display, as well as the performance and thermals of this series compared to the other options in its niche. We get in-depth on all these details in the review.', 'TITAN18HX-1-1024x1024.png,TITAN18HX-5-1024x1024.png,TITAN18HX-7-1024x1024.png,TITAN18HX-8-1024x1024.png,TITAN18HX-9-400x400.png', 2, 15, 5, 11, 6, 10, 5),
(91, 'Acer Aspire 5 A515-57G (2022)', '88000', ' This generation of the Acer Aspire 5 refines on its predecessors with a slight update in design and practicality, and a more notable update in specs and internal cooling. Unfortunately, Acer haven\'t updated the display in the same manner, and only seem to offer basic-level IPS panels for this series, the kind that might not suffice in 2022. The audio quality isn\'t much here, either, but overall the series remains a serious contender in the budget class of full-size all-purpose laptops.', 'acer-aspire-5-a515-57-a515-57g-a515-57gt-a515-57t-s50-54-fingerprint-backlit-on-wallpaper-win11-steel-gray-01-1000x1000_nx.kn4aa.004.webp,1686266231_IMG_2015746.jpg,1686266231_IMG_2015747.jpg', 2, 13, 9, 12, 7, 11, 6),
(92, 'Asus ROG Zephyrus G16 (2024)', '256000', 'Asus\' new ROG Zephyrus G16 features a completely new and considerably slimmer design, with a target market in mind that goes beyond just gamers. The resulting product is a high-quality 16-inch laptop with an impressive OLED panel and excellent speakers that can easily compete with Apple\'s MacBook Pro or the Razer Blade 16. ', '321e.png,2342432.png,23423 re.png,23423er.png,123we.png', 0, 14, 7, 13, 8, 12, 7),
(93, 'GIGABYTE G6 Series - 16\'\' (2023)', '140270', 'Gigabyte is updating its G-series line of budget gaming laptops with the G6 model designed specifically for students and casual gamers. Highlights for the new G6 include 13th gen and 12 gen Intel processors coupled with Nvidia’s RTX 4000 GPUs, plus an improved display. ', 'G6 (2023)-01.png,G6 (2023)-04.png,G6 (2023)-05.png,G6 (2023)-06.png,G6 (2023)-02.png', 1, 4, 10, 14, 9, 13, 8);

-- --------------------------------------------------------

--
-- Table structure for table `rams`
--

CREATE TABLE `rams` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rams`
--

INSERT INTO `rams` (`id`, `name`) VALUES
(9, '32 GB DDR5 '),
(10, '16 GB DDR4 3200 MHz'),
(11, '64 GB DDR5 4000 MHz'),
(12, '8 GB DDR4 3200MHz'),
(13, '16 GB LPDDR5x-7467'),
(14, '16 GB DDR5');

-- --------------------------------------------------------

--
-- Table structure for table `storages`
--

CREATE TABLE `storages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `storages`
--

INSERT INTO `storages` (`id`, `name`) VALUES
(2, '1 TB PCIe 4.0 NVMe M.2 Performance SSD'),
(3, '1 TB M.2 2280 PCIe 4.0x4 NVMe SSD'),
(4, '1x 1TB M.2 NVMe gen 4 + 1 spare slot'),
(5, '1x 512 GB SSD / one extra M.2 2280 free slot'),
(6, '2 TB SSD, 1x M.2 PCI gen5, 2x M.2 PCIe gen4 slots'),
(7, '512 GB M.2 SSD'),
(8, '2TB PCIe® 4.0 NVMe™ M.2 SSD'),
(9, '1 TB PCIe Gen4x4 NVMe M.2 SSD');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(19, 'ankit8', 'ankit8@mail.com', '$2y$10$dnB3tR.Ijcz7xIofOo.KG.LgKFgARuVoAQxP0OQ5QT59AUKvP2R8e', 'admin'),
(20, 'ankitu', 'ankitu@mail.com', '$2y$10$dq1v4dmBms8nXbUhKjqqX.yW1xHlgqy.L9S9NOIltY7cIHEgHxovK', 'user'),
(23, 'user2', 'user2@mail.com', '$2y$10$CVcau6TxUsQqnOFg8LraVOlx.L259y5ZUlkErBu46obG.dbsOgOVa', 'user'),
(25, 'nischal', 'nischal@gmail.com', '$2y$10$kzVGbjps3b/CuN7KhgFjD.TPdjahhMpWNgjQh9hY0xMDRowgEHnWi', 'user'),
(26, 'admin', 'admin@mail.com', '$2y$10$TX3QSN.kaDGOAFM52HNM2uyiuMBgUfvPZOK4qoYJXuhcXQB8xLidi', 'admin'),
(27, 'test2', 'test2@mail.com', '$2y$10$PhNEW2PPVktp7TxCV8xXJOoatE3eB7ZDFH2fl2ynl6LDlbXTtO56K', 'admin'),
(28, 'test', 'test@mail.com', '$2y$10$F/ihIVuZBNo1S76N/tWXbuWs1xXMwMr5b/rx5wjkPeqqL4ijozcTe', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user1` (`user_id`),
  ADD KEY `fk_product12` (`pid`);

--
-- Indexes for table `displays`
--
ALTER TABLE `displays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `graphics`
--
ALTER TABLE `graphics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order` (`user_id`),
  ADD KEY `fk_product23` (`product_id`);

--
-- Indexes for table `processors`
--
ALTER TABLE `processors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_brand` (`brand_id`),
  ADD KEY `fk_rams` (`ram_id`),
  ADD KEY `fk_displays` (`display_id`),
  ADD KEY `fk_graphics` (`graphic_id`),
  ADD KEY `fk_processor` (`processor_id`),
  ADD KEY `fk_storages` (`storage_id`);

--
-- Indexes for table `rams`
--
ALTER TABLE `rams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `storages`
--
ALTER TABLE `storages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `displays`
--
ALTER TABLE `displays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `graphics`
--
ALTER TABLE `graphics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `processors`
--
ALTER TABLE `processors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `rams`
--
ALTER TABLE `rams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `storages`
--
ALTER TABLE `storages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_product12` FOREIGN KEY (`pid`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `fk_displays` FOREIGN KEY (`display_id`) REFERENCES `displays` (`id`),
  ADD CONSTRAINT `fk_graphics` FOREIGN KEY (`graphic_id`) REFERENCES `graphics` (`id`),
  ADD CONSTRAINT `fk_processor` FOREIGN KEY (`processor_id`) REFERENCES `processors` (`id`),
  ADD CONSTRAINT `fk_rams` FOREIGN KEY (`ram_id`) REFERENCES `rams` (`id`),
  ADD CONSTRAINT `fk_storages` FOREIGN KEY (`storage_id`) REFERENCES `storages` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

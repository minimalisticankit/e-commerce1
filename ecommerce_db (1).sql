 -- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2024 at 11:42 AM
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
(1, 'Lenevo'),
(3, 'ASUS'),
(4, 'GiGABYTE');

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
(18, 17, 68, 1),
(19, 17, 67, 3);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cat_slug` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `cat_slug`) VALUES
(1, 'Laptops', 'laptops'),
(2, 'Desktop PC', 'desktop-pc'),
(3, 'Tablets', 'tablets'),
(4, 'Smart Phones', '');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `total_products` varchar(255) NOT NULL,
  `total_price` varchar(255) NOT NULL,
  `place_on` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `brand_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `details`, `image`, `quantity`, `brand_id`) VALUES
(46, 'sfasdf', '1313', 'sadfas', 'Screenshot 2024-03-11 145812.png,Screenshot 2024-03-11 150405.png', 0, NULL),
(64, 'Legion Pro 7i Gen 9 Intel (16″) with RTX™ 4090', '2000000', 'Processor : 14th Generation Intel® Core™ i9-14900HX Processor (E-cores up to 4.10 GHz P-cores up to 5.80 GHz)\nOperating System : Windows 11 Home 64\nGraphic Card : NVIDIA® GeForce RTX™ 4090 Laptop GPU 16GB GDDR6\nMemory : 32 GB DDR5-5600MHz (SODIMM) - (2', 'wp11855044-992-gt3-rs-wallpapers.jpg', 0, NULL),
(65, 'Legion Pro 7i Gen 9 Intel (16″) with RTX™ 4090', '23412341', 'Processor : 14th Generation Intel® Core™ i9-14900HX Processor (E-cores up to 4.10 GHz P-cores up to 5.80 GHz)\r\nOperating System : Windows 11 Home 64\r\nGraphic Card : NVIDIA® GeForce RTX™ 4090 Laptop GPU 16GB GDDR6\r\nMemory : 32 GB DDR5-5600MHz (SODIMM) - (2 x 16 GB)\r\nStorage : 2 TB SSD M.2 2280 PCIe Gen4 TLC (2 x 1 TB)\r\nDisplay : 16\" WQXGA (2560 x 1600), IPS, Anti-Glare, Non-Touch, HDR 400, 100%DCI-P3, 500 nits, 240Hz, Low Blue Light\r\nCamera : 1080P FHD with Dual Microphone and Electronic Privacy Shutter\r\nAC Adapter / Power Supply : 330W\r\nKeyboard : Per-Key RGB Backlit, Black - English (US)\r\nWLAN : Wi-Fi 6E 2x2 AX & Bluetooth® 5.1 or above\r\nWarranty : One Year Legion Ultimate Support\r\nAdd-ons : 3 Month Xbox Game Pass', '2021-TechArt-GTstreet-R-002-1080.jpg,alien_flower_2-wallpaper-2560x1440.jpg', 0, NULL),
(67, 'ASUS ExpertBook B9 OLED (B9403, Series 1 intel)', '23444', 'Model : B9403CVAR\r\nColor : Star Black\r\nOperating System : Windows 11 Pro - ASUS recommends Windows 11 Pro for business\r\nNon-preinstalled OS : Windows 11 Home - ASUS recommends Windows 11 Pro for business\r\nProcessor :Intel® Core™ 7 Processor 150U 1.8 GHz (12MB Cache, up to 5.4 GHz, 10 Cores)\r\nIntel® Core™ 5 Processor 120U 1.4 GHz (12MB Cache, up to 5.0 GHz, 10 Cores)\r\n', 'fwebp.webp', 5, 3),
(68, 'ROG Strix SCAR 18 (2024) G834  G834JZR-R6108X', '23422', 'Operating System\r\nWindows 11 Pro\r\nWindows 11 Pro\r\nProcessor\r\nIntel® Core™ i9 Processor 14900HX 2.2 GHz (36MB Cache, up to 5.8 GHz, 24 cores, 32 Threads)\r\nIntel® Core™ i9 Processor 14900HX 2.2 GHz (36MB Cache, up to 5.8 GHz, 24 cores, 32 Threads)\r\nGraphics\r\nNVIDIA® GeForce RTX™ 4080 Laptop GPU\r\nROG Boost: 2330MHz* at 175W (2280MHz Boost Clock+50MHz OC, 150W+25W Dynamic Boost)\r\n12GB GDDR6\r\nNVIDIA® GeForce RTX™ 4090 Laptop GPU\r\nROG Boost: 2090MHz* at 175W (2040MHz Boost Clock+50MHz OC, 150W+25W Dynamic Boost)\r\n16GB GDDR6\r\nDisplay\r\nROG Nebula HDR Display\r\n18-inch\r\n2.5K (2560 x 1600, WQXGA) 16:10 aspect ratio\r\nMini LED\r\nAnti-glare display\r\nDCI-P3:\r\n100.00%\r\nRefresh Rate:\r\n240Hz\r\nResponse Time:\r\n3ms\r\nG-Sync\r\nPantone Validated\r\nMUX Switch + NVIDIA® Advanced Optimus\r\nSupport Dolby Vision HDR :\r\nYes\r\nROG Nebula HDR Display\r\n18-inch\r\n2.5K (2560 x 1600, WQXGA) 16:10 aspect ratio\r\nMini LED\r\nAnti-glare display\r\nDCI-P3:\r\n100.00%\r\nRefresh Rate:\r\n240Hz\r\nResponse Time:\r\n3ms\r\nG-Sync\r\nPantone Validated\r\nMUX Switch + NVIDIA® Advanced Optimus\r\nSupport Dolby Vision HDR :\r\nYes\r\nMemory\r\n32GB DDR5-5600 SO-DIMM, the memory speed of the systems vary by CPU SPEC x 2\r\nMax Capacity:\r\n64GB\r\nSupport dual channel memory\r\n32GB DDR5-5600 SO-DIMM, the memory speed of the systems vary by CPU SPEC x 2\r\nMax Capacity:\r\n64GB\r\nSupport dual channel memory\r\nStorage\r\n2TB + 2TB PCIe® 4.0 NVMe™ M.2 Performance SSD (RAID 0)\r\n2TB + 2TB PCIe® 4.0 NVMe™ M.2 Performance SSD (RAID 0)\r\nI/O Ports\r\n1x 3.5mm Combo Audio Jack\r\n1x HDMI 2.1 FRL\r\n2x USB 3.2 Gen 2 Type-A\r\n1x USB 3.2 Gen 2 Type-C support DisplayPort™ / power delivery / G-SYNC\r\n1x 2.5G LAN port\r\n1x Thunderbolt™ 4 support DisplayPort™ / G-SYNC\r\n1x 3.5mm Combo Audio Jack\r\n1x HDMI 2.1 FRL\r\n2x USB 3.2 Gen 2 Type-A\r\n1x USB 3.2 Gen 2 Type-C support DisplayPort™ / power delivery / G-SYNC\r\n1x 2.5G LAN port\r\n1x Thunderbolt™ 4 support DisplayPort™ / G-SYNC\r\nKeyboard and Touchpad\r\nBacklit Chiclet Keyboard Per-Key RGB\r\nTouchpad\r\nBacklit Chiclet Keyboard Per-Key RGB\r\nTouchpad\r\nCamera\r\n720P HD camera\r\n720P HD camera\r\nAudio\r\nSmart Amp Technology\r\nDolby Atmos\r\nAI noise-canceling technology\r\nHi-Res certification (for headphone)\r\nBuilt-in array microphone\r\n4-speaker system with Smart Amplifier Technology', 'w250.png', 0, NULL),
(70, 'Legion Pro 5i Gen 8 (16″ Intel) Gaming Laptop', '360000', 'Performance\r\nProcessor	\r\n13th Generation Intel® Core™ i5-13500HX Processor (E-Core Max 3.50 GHz, P-Core Max 4.70 GHz with Turbo Boost, 14 Cores, 20 Threads, 24 MB Cache)\r\n\r\n13th Generation Intel® Core™ i7-13700HX Processor (E-Core Max 3.70 GHz, P-Core Max 5.00 GHz with Turbo Boost, 16 Cores, 24 Threads, 30 MB Cache)\r\n\r\nOperating System	\r\nWindows 11 Home\r\n\r\nGraphics	\r\nNVIDIA® GeForce RTX™ 4050 6GB GDDR6\r\n\r\nNVIDIA® GeForce RTX™ 4060 8GB GDDR6\r\n\r\nNVIDIA® GeForce RTX™ 4070 8GB GDDR6\r\n\r\nMemory	\r\n16GB (2 x 8GB) 4800MHz DDR5\r\n\r\nStorage	\r\nUp to 1TB PCIe SSD Gen 4\r\n\r\nBattery	\r\n80Whr\r\n\r\nSuper Rapid Charge (30-minute charge for 0-80% capacity, 60-minute charge for 0-100% capacity)\r\n\r\nAudio	\r\n2x2W speaker system with Nahimic Audio\r\n\r\nCamera	\r\n1080p FHD\r\n\r\nTobii Horizon support\r\n\r\neshutter\r\n\r\nConnectivity\r\nPorts/Slots	\r\nLeft:\r\n\r\n\r\n\r\nUSB-C 3.2 Gen 2 (DisplayPort™ 1.4)\r\n\r\nUSB-A 3.2 Gen 1\r\n\r\n\r\n\r\nRight:\r\n\r\n\r\n\r\nHeadphone/mic combo\r\n\r\nUSB-A 3.2 Gen 1\r\n\r\nWebcam e-shutter switch\r\n\r\n\r\n\r\nRear:\r\n\r\n\r\n\r\nDC-in\r\n\r\nUSB-C 3.2 Gen 2 (DisplayPort™ 1.4, power delivery 140W)\r\n\r\n2 x USB-A 3.2 Gen 1 (1x always on 5V2A)\r\n\r\nHDMI 2.1\r\n\r\nEthernet (RJ45)\r\n\r\nWiFi	\r\nWiFi 6E* 802.11AX (2 x 2)\r\n\r\nBluetooth® 5.1\r\n\r\n\r\n\r\n\r\n\r\n*6GHz WiFi 6E operation is dependent on the support of the operating system, routers/APs/gateways that support WiFi 6E, along with the regional regulatory certifications and spectrum allocation.\r\n\r\nDesign\r\nDisplay	\r\n16.0\" WQXGA (2560 x 1600), IPS, Anti-Glare, Non-Touch, 100%sRGB, 300 nits, 165Hz, Narrow Bezel, Low Blue Light\r\n\r\n16.0\" WQXGA (2560 x 1600), IPS, Anti-Glare, Non-Touch, HDR 400, 100%sRGB, 500 nits, 240Hz\r\n\r\nDimensions (H x W x D)	\r\n21.9-26.75mm x 363.4mm x 260.35 / 0.86″-1.05″ x 14.3″ x 10.25″\r\n\r\nWeight	\r\nStarting at 2.5kg / 5.51lbs\r\n\r\nKeyboard	\r\n1.5mm key travel\r\n\r\n100% anti-ghosting\r\n\r\nSwappable cap set\r\n\r\nRGB TrueStrike keyboard\r\n\r\nLegion Spectrum RGB software support\r\n\r\nColor	\r\nOnyx Grey\r\n\r\nSustainability\r\nMaterial	\r\n30% post-consumer content in top cover\r\n\r\nOther Information\r\nSecurity	\r\nElectronic webcam e-shutter\r\n\r\nPreloaded Software	\r\nLenovo Vantage\r\n\r\nMcAfee® LiveSafe™ (Trial)\r\n\r\nMicrosoft Office Trial (Trial)\r\n\r\nTobii Horizon\r\n\r\nX-Rite Color Management Tool\r\n\r\nWhat’s in the Box	\r\nLegion Pro 5i Gen 8 (16” Intel)\r\n\r\n80Whr Internal Battery\r\n\r\n230W or 300W Power Adapter\r\n\r\nQuick Start Guide', 'humxyq653qmbamu9ggmpgpbjekupue482588.avif,w250 (1).png,w250.png', 50, 1),
(71, 'GIGABYTE AORUS 16X (2024)', '15000', 'Windows 11\r\n\r\nNVIDIA® GeForce RTX™ 40 Series Laptop GPUs, powered by NVIDIA DLSS 3, ultra-efficient Ada Lovelace arch, and Max-Q Technologies\r\n\r\nUp to Intel® Core™ i9 HX-Series\r\n\r\nUp to 16.0\" 16:10 WQXGA (2560 x 1600) 165Hz Panel\r\n\r\nWINDFORCE Infinity Cooling System', '3001.webp,300.webp', 10, 4);

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
(1, 'Cameron Beach', 'litydinany@mailinator.com', '12341234', 'admin'),
(2, 'Bernard Merrill', 'pafigego@mailinator.com', 'Pa$$w0rd!', 'user'),
(3, 'Cecilia Witt', 'fuxaveliq@mailinator.com', 'Pa$$w0rd!', 'user'),
(13, 'admin', 'admin@mail.com', 'admin', 'admin'),
(14, 'test', 'test@mail.com', 'test', 'user'),
(15, 'test2', 'test2@mail.com', 'test2', 'user'),
(16, 'test3', 'test3@mail.com', 'test3', 'user'),
(17, 'ankit', 'ankit@gmail', '1234', 'user');

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_brand` (`brand_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

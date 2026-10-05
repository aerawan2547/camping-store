-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2026 at 09:43 AM
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
-- Database: `e-commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `brand_logo` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `brand_logo`, `website_url`, `description`) VALUES
(1, 'Snow Peak', NULL, 'https://www.snowpeak.com', 'แบรนด์ Outdoor ไลฟ์สไตล์ระดับ High-End จากญี่ปุ่น'),
(2, 'Coleman', NULL, 'https://www.coleman.com', 'แบรนด์อเมริกันคลาสสิก ตำนานแห่งอุปกรณ์แคมป์ปิ้ง'),
(3, 'Naturehike', NULL, 'https://www.naturehike.com', 'แบรนด์ขวัญใจสาย Budget น้ำหนักเบา ราคาจับต้องได้'),
(4, 'The North Face', NULL, 'https://www.thenorthface.com', 'แบรนด์อุปกรณ์เดินป่าและแฟชั่นระดับโลก'),
(5, 'Camel', NULL, 'https://www.camelcrown.com', 'อุปกรณ์แคมป์ปิ้งราคาประหยัด หาซื้อง่าย'),
(6, 'Patagonia', NULL, 'https://www.patagonia.com', 'แบรนด์เสื้อผ้าสายรักษ์โลก'),
(7, 'Columbia', NULL, 'https://www.columbia.com', 'เสื้อผ้าและอุปกรณ์ Outdoor ฟังก์ชันครบ'),
(8, 'Montbell', NULL, 'https://en.montbell.jp', 'แบรนด์ญี่ปุ่น เน้นความเบา (Light & Fast)');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_image`, `description`) VALUES
(1, 'Tents & Tarps', NULL, 'เต็นท์และทาร์ปกันแดดฝน'),
(2, 'Furniture', NULL, 'โต๊ะ เก้าอี้ และเฟอร์นิเจอร์สนาม'),
(3, 'Sleeping Gear', NULL, 'ถุงนอน แผ่นรองนอน และเตียงพับ'),
(4, 'Kitchenware', NULL, 'อุปกรณ์ทำอาหาร เตา และแก้วน้ำ'),
(5, 'Lighting', NULL, 'ตะเกียง ไฟฉาย และไฟประดับ'),
(6, 'Apparel', NULL, 'เสื้อผ้า หมวก และรองเท้าเดินป่า'),
(7, 'Bags & Storage', NULL, 'กระเป๋าเป้ และกล่องเก็บอุปกรณ์');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `shipping_address` text NOT NULL,
  `shipping_courier` varchar(50) DEFAULT NULL,
  `payment_slip_url` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `status` enum('pending','paid','shipped','cancelled') DEFAULT 'pending',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `shipping_cost`, `shipping_address`, `shipping_courier`, `payment_slip_url`, `tracking_number`, `status`, `updated_at`) VALUES
(1, 3, '2026-02-02 10:24:54', 86100.00, 0.00, 'somchai Jaidee (0965743322)\n48 ม.5 อ.เมืองเพชรบุรี จ.เพชรบุรี 76000', NULL, 'slip_1770027894_3.jpg', '', 'cancelled', '2026-02-04 02:41:47'),
(2, 3, '2026-02-03 01:18:23', 4100.00, 0.00, 'somchai Jaidee (0965743322)\n123 ม.2 ต.ต้นมะม่วง อ.เมือง จ.เพชรบุรี 76000', NULL, 'slip_1770081503_3.jpg', '', 'paid', '2026-02-11 08:20:24'),
(3, 3, '2026-02-03 01:44:58', 62000.00, 0.00, 'somchai Jaidee (0965743322)\nkiyjuhgtfd', NULL, 'slip_1770083098_3.jpg', '', 'shipped', '2026-02-04 02:40:54'),
(4, 3, '2026-02-03 06:47:14', 18000.00, 0.00, 'somchai Jaidee (0965743322)\n48 ม.2 ต.ท่าราบ อ.เมือง จ.เพชรบุรี 76000', NULL, 'slip_1770101234_3.jpg', 'flash123456', 'paid', '2026-02-03 10:13:20'),
(5, 5, '2026-02-09 06:11:45', 4500.00, 0.00, 'อมินตา รุ่งเรือง (065462156)\n55/1 jhugu', NULL, 'slip_1770617505_5.png', 'KERRY4632566', 'shipped', '2026-02-11 08:20:12'),
(6, 5, '2026-02-10 07:34:13', 7200.00, 0.00, ' ()\n', NULL, '', '', 'cancelled', '2026-02-10 07:54:29'),
(7, 5, '2026-02-11 07:43:52', 6500.00, 0.00, ' ()\n', NULL, '', '', 'cancelled', '2026-02-11 08:19:45'),
(8, 5, '2026-02-11 08:08:57', 85000.00, 0.00, ' ()\n', NULL, 'slip_1770797337_5.jpg', '', 'paid', '2026-02-11 08:19:51'),
(9, 5, '2026-02-11 08:13:39', 85000.00, 0.00, 'อมินตา รุ่งเรือง (0656526330)\n55/1 ม.5 ต.สำมะโรง เพชรบุรี 76000', NULL, 'slip_1770797619_5.jpg', '', 'paid', '2026-02-11 08:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders_item`
--

CREATE TABLE `orders_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_item`
--

INSERT INTO `orders_item` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 1, 1, 85000.00),
(2, 1, 68, 1, 1100.00),
(3, 2, 62, 1, 3200.00),
(4, 2, 67, 1, 900.00),
(5, 3, 5, 1, 62000.00),
(6, 4, 27, 1, 18000.00),
(7, 5, 38, 1, 4500.00),
(8, 6, 3, 1, 7200.00),
(9, 7, 2, 1, 6500.00),
(10, 8, 1, 1, 85000.00),
(11, 9, 1, 1, 85000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `image_url`, `is_featured`, `brand_id`, `category_id`, `created_at`) VALUES
(1, 'Landlock Ivory', 'เต็นท์ 2 ห้องขนาดใหญ่ สีงาช้าง หรูหรา กันฝน', 85000.00, 3, 'sp_landlock.jpg', 1, 1, 1, '2026-02-02 08:45:59'),
(2, 'IGT Frame (3 Unit)', 'โต๊ะโครงเหล็กระบบ Modular เลือกใส่เตา/ซิงค์ได้', 6500.00, 4, 'sp_igt.jpg', 0, 1, 2, '2026-02-02 08:45:59'),
(3, 'Low Beach Chair', 'เก้าอี้ผ้าใบขาไม้ ทรงเตี้ย นั่งสบาย', 7200.00, 9, 'sp_lowchair.jpg', 1, 1, 2, '2026-02-02 08:45:59'),
(4, 'Titanium Mug 450', 'แก้วไทเทเนียมผนังเดี่ยว น้ำหนักเบา', 1200.00, 50, 'sp_mug450.jpg', 0, 1, 4, '2026-02-02 08:45:59'),
(5, '4S Wide 2-Room Cocoon III', 'เต็นท์รุ่นท็อปสุด Master Series แข็งแรงทนลมพายุ', 62000.00, 2, 'cm_cocoon.jpg', 1, 2, 1, '2026-02-02 08:45:59'),
(6, 'Steel Belted Cooler 54QT', 'ถังน้ำแข็งเหล็กในตำนาน สี Sage', 9900.00, 8, 'cm_cooler.jpg', 1, 2, 4, '2026-02-02 08:45:59'),
(7, 'Village 13.0 Tent', 'เต็นท์ทรงบ้านหลังใหญ่ กางอัตโนมัติ', 15000.00, 5, 'nh_village13.jpg', 1, 3, 1, '2026-02-02 08:45:59'),
(8, 'Kermit Chair (Walnut)', 'เก้าอี้ไม้แท้ ผ้าแคนวาส สไตล์คลาสสิก', 3500.00, 12, 'nh_kermit.jpg', 0, 3, 2, '2026-02-02 08:45:59'),
(9, 'Geodome 4', 'เต็นท์ทรงลูกบอล ดีไซน์ล้ำยุค ทนลม', 75000.00, 1, 'tnf_geodome.jpg', 1, 4, 1, '2026-02-02 08:45:59'),
(10, 'Camel Cabin Tent', 'เต็นท์ทรงบ้านหลังใหญ่ ราคาประหยัด', 4000.00, 20, 'camel_cabin.jpg', 0, 5, 1, '2026-02-02 08:45:59'),
(11, 'Retro-X Fleece Jacket', 'เสื้อแจ็คเก็ตขนแกะเทียม กันลมได้ดี', 9000.00, 8, 'pat_retrox.jpg', 1, 6, 6, '2026-02-02 08:45:59'),
(12, 'Bora Bora Booney', 'หมวกปีกกว้าง กันแดดได้ดี', 1200.00, 30, 'col_booney.jpg', 0, 7, 6, '2026-02-02 08:45:59'),
(13, 'Moonlight Tent', 'เต็นท์ทรง A-Frame กางง่าย ใช้งานสะดวก', 12000.00, 5, 'mb_moonlight.jpg', 0, 8, 1, '2026-02-02 08:45:59'),
(14, 'Field Tea Ceremony Set', 'ชุดชงชาเขียวแบบพกพา Zen Style', 4500.00, 3, 'mb_teaset.jpg', 1, 8, 4, '2026-02-02 08:45:59'),
(15, 'Takibi Tarp Octa', 'ทาร์ปทรงแปดเหลี่ยม มีฉนวนกันไฟด้านใน ก่อกองไฟใต้หลังคาได้', 29000.00, 3, 'sp_takibi_tarp.jpg', 0, 1, 1, '2026-02-02 09:42:33'),
(16, 'Takibi Fire & Grill', 'เตาปิ้งย่างและก่อกองไฟ สแตนเลส พับเก็บได้แบนราบ ทนทาน ใช้งานตลอดชีพ', 12000.00, 5, 'sp_fire_grill.jpg', 1, 1, 4, '2026-02-02 09:42:33'),
(17, 'Home & Camp Burner', 'เตาแก๊สกระป๋องดีไซน์ล้ำ พับเก็บเป็นทรงกระบอกเหมือนขวดน้ำ', 4500.00, 10, 'sp_home_camp.jpg', 0, 1, 4, '2026-02-02 09:42:33'),
(18, 'Hozuki Lantern', 'โคมไฟ LED ทรงลูกเชอรี่ ปรับหรี่แสงตามเสียงลมได้', 4200.00, 8, 'sp_hozuki.jpg', 0, 1, 5, '2026-02-02 09:42:33'),
(19, 'Hard Rock Cooler 40QT', 'ถังน้ำแข็งรุ่นท็อป เก็บความเย็นได้นานสุด ผนังหนา', 24000.00, 2, 'sp_cooler.jpg', 0, 1, 4, '2026-02-02 09:42:33'),
(20, 'Field Barista Kettle', 'กาดริปกาแฟสแตนเลส ด้ามจับไม้ ถอดด้ามเก็บได้', 5500.00, 6, 'sp_kettle.jpg', 0, 1, 4, '2026-02-02 09:42:33'),
(21, 'Instant Up Dome', 'เต็นท์กางเร็วระบบ Instant กางเสร็จใน 5 นาที เหมาะสำหรับมือใหม่', 5500.00, 10, 'cm_instant.jpg', 0, 2, 1, '2026-02-02 09:42:33'),
(22, 'Butterfly Table 120', 'โต๊ะพับรุ่น Master Series เบา แข็งแรง ปรับความสูงได้ 3 ระดับ', 7500.00, 5, 'cm_butterfly.jpg', 0, 2, 2, '2026-02-02 09:42:33'),
(23, 'Lumiere Lantern', 'ตะเกียงแก๊สเปลวเทียน เน้นสร้างบรรยากาศโรแมนติก', 3500.00, 12, 'cm_lumiere.jpg', 0, 2, 5, '2026-02-02 09:42:33'),
(24, 'Steel Belted Jug', 'กระติกน้ำสแตนเลส ใช้คู่กับถังน้ำแข็ง', 3500.00, 8, 'cm_jug.jpg', 0, 2, 4, '2026-02-02 09:42:33'),
(25, 'Classic Stove 413H', 'เตาแคมป์ปิ้ง 2 หัว ใช้น้ำมันเบนซิน ไฟแรง ใชงานง่ายแม้ลมแรง', 11000.00, 3, 'cm_stove413h.jpg', 0, 2, 4, '2026-02-02 09:42:33'),
(26, 'Outdoor Wagon Mesh', 'รถเข็นรุ่นตะแกรงเหล็ก ถอดซักง่าย ลุยน้ำได้ สี Earth Tone', 5200.00, 6, 'cm_wagon.jpg', 0, 2, 7, '2026-02-02 09:42:33'),
(27, 'Brighten 12.3 (Cotton)', 'เต็นท์กระโจมผ้า Cotton ผสม (TC) ระบายอากาศดี ', 18000.00, 3, 'nh_brighten.jpg', 1, 3, 1, '2026-02-02 09:42:34'),
(28, 'Ango Air Tent', 'เต็นท์สูบลมทรงกระท่อม ใช้งานง่าย สะดวก รวดเร็ว', 17000.00, 2, 'nh_ango.jpg', 0, 3, 1, '2026-02-02 09:42:34'),
(29, 'Wood Grain Roll Table', 'โต๊ะอลูมิเนียมทำสีลายไม้ ม้วนเก็บได้ น้ำหนักเบา', 2500.00, 20, 'nh_rolltable.jpg', 0, 3, 2, '2026-02-02 09:42:34'),
(30, 'Glamping Inflatable Mat', 'เบาะลมทรงสูง นุ่ม มีปั๊มลมในตัว', 4500.00, 5, 'nh_mat.jpg', 0, 3, 3, '2026-02-02 09:42:34'),
(31, 'Titanium Coffee Set', 'ชุดดริปกาแฟและแก้วไทเทเนียม น้ำหนักเบา', 3500.00, 8, 'nh_coffee.jpg', 0, 3, 4, '2026-02-02 09:42:34'),
(32, 'Outdoor Trolley (Cream)', 'รถเข็นสีครีม ล้อใหญ่พิเศษแบบ Off-Road ', 4200.00, 10, 'nh_trolley.jpg', 0, 3, 7, '2026-02-02 09:42:34'),
(33, 'Vintage Lamp', 'ตะเกียง LED ทรงตะเกียงรั้วเก่า สีทองเหลือง-ดำ ชาร์จด้วย USB', 1500.00, 30, 'nh_lamp.jpg', 0, 3, 5, '2026-02-02 09:42:34'),
(34, 'Homestead Domy 3', 'เต็นท์โดมลายกราฟิก ประตูกว้าง 3 ด้าน เหมาะสำหรับวัยรุ่น', 15000.00, 3, 'tnf_domy3.jpg', 0, 4, 1, '2026-02-02 09:42:34'),
(35, 'Base Camp Gear Box', 'กล่องใส่อุปกรณ์พับได้ ทำจากผ้ากันน้ำ แข็งแรงทนทาน', 7500.00, 5, 'tnf_gearbox.jpg', 0, 4, 7, '2026-02-02 09:42:34'),
(36, 'TNF Camp Chair', 'เก้าอี้สนามทรง Director ผ้า Base Camp ทนทาน พับง่าย', 6500.00, 8, 'tnf_chair.jpg', 0, 4, 2, '2026-02-02 09:42:34'),
(37, 'Wawona 6', 'เต็นท์อุโมงค์ขนาดใหญ่ ระบายอากาศดี', 28000.00, 2, 'tnf_wawona.jpg', 0, 4, 1, '2026-02-02 09:42:34'),
(38, 'Eco Trail Bed', 'ถุงนอนทรงสี่เหลี่ยม ลายกราฟิกสวย รีไซเคิล 100%', 4500.00, 9, 'tnf_ecotrail.jpg', 0, 4, 3, '2026-02-02 09:42:34'),
(39, 'Horizon Hat', 'หมวกปีกกว้างยอดฮิต กันแดดได้ดี', 1900.00, 50, 'tnf_horizon.jpg', 0, 4, 6, '2026-02-02 09:42:34'),
(40, 'Homestead Shelter', 'ทาร์ปบังแดด ทรงโค้ง กางครอบโต๊ะกินข้าวได้', 13000.00, 4, 'tnf_shelter.jpg', 0, 4, 1, '2026-02-02 09:42:34'),
(41, 'Hexagon Auto Tent', 'เต็นท์หกเหลี่ยม กางออโต้ สีเขียวขี้ม้า เหมาะสำหรับปิกนิก', 2500.00, 15, 'camel_hex.jpg', 0, 5, 1, '2026-02-02 09:42:34'),
(42, 'Canvas Butterfly Chair', 'เก้าอี้ผ้าแคนวาสทรงผีเสื้อ โครงไม้ นั่งสบาย', 1500.00, 10, 'camel_butterfly.jpg', 0, 5, 2, '2026-02-02 09:42:34'),
(43, 'Tarp Hexagon (Vinyl)', 'ทาร์ปกันแดดเคลือบไวนิลทึบแสง', 1200.00, 20, 'camel_tarp.jpg', 0, 5, 1, '2026-02-02 09:42:34'),
(44, 'Picnic Basket', 'ตะกร้าหวายสำหรับปิกนิก ใส่จานชามได้', 800.00, 5, 'camel_basket.jpg', 0, 5, 7, '2026-02-02 09:42:34'),
(45, 'Retro Gas Stove', 'เตาแก๊สปิกนิกสีพาสเทล ดีไซน์วินเทจ', 600.00, 25, 'camel_stove.jpg', 0, 5, 4, '2026-02-02 09:42:34'),
(46, 'Inflatable Sofa', 'โซฟาลม ปากกว้างดักลมม้วนปิดได้', 500.00, 30, 'camel_sofa.jpg', 0, 5, 2, '2026-02-02 09:42:34'),
(47, 'Enamel Mug', 'แก้วสังกะสีเคลือบ สีขาวขอบน้ำเงิน/เขียว', 150.00, 100, 'camel_mug.jpg', 0, 5, 4, '2026-02-02 09:42:34'),
(48, 'Folding Wagon', 'รถเข็นล้อเล็ก สำหรับขนของระยะสั้น ราคาประหยัด', 2000.00, 8, 'camel_wagon.jpg', 0, 5, 7, '2026-02-02 09:42:34'),
(49, 'Nano Puff Blanket', 'ผ้าห่มนวมใยสังเคราะห์ พับเล็ก เบา อุ่น', 7000.00, 5, 'pat_blanket.jpg', 0, 6, 3, '2026-02-02 09:42:34'),
(50, 'Baggies Shorts', 'กางเกงขาสั้น ใส่สบาย แห้งไว ใส่ว่ายน้ำได้', 2500.00, 20, 'pat_shorts.jpg', 0, 6, 6, '2026-02-02 09:42:34'),
(51, 'Torrentshell 3L Jacket', 'เสื้อกันฝน 3 ชั้น กันน้ำ ระบายอากาศได้ดี', 6500.00, 10, 'pat_rainjacket.jpg', 0, 6, 6, '2026-02-02 09:42:34'),
(52, 'Ultralight Black Hole Tote', 'เป้+กระเป๋าถือ พับเก็บได้', 3500.00, 12, 'pat_tote.jpg', 0, 6, 7, '2026-02-02 09:42:34'),
(53, 'Trucker Hat', 'หมวกแก๊ปตาข่าย โลโก้ Patagonia ', 1800.00, 25, 'pat_hat.jpg', 0, 6, 6, '2026-02-02 09:42:34'),
(54, 'Capilene Cool Daily', 'เสื้อยืดกัน UV ใส่แล้วเย็น แห้งไว', 2000.00, 30, 'pat_tshirt.jpg', 0, 6, 6, '2026-02-02 09:42:34'),
(55, 'Black Hole Cube', 'กระเป๋าจัดระเบียบของใช้ส่วนตัว กันน้ำ', 1500.00, 15, 'pat_cube.jpg', 0, 6, 7, '2026-02-02 09:42:34'),
(56, 'Provisions Salmon', 'ปลาแซลมอนรมควันซอง อาหารพกพาสำหรับเดินป่า', 500.00, 50, 'pat_food.jpg', 0, 6, 4, '2026-02-02 09:42:34'),
(57, 'PFG Bahama Shirt', 'เสื้อเชิ้ตตกปลา ระบายอากาศ ', 2500.00, 15, 'col_shirt.jpg', 0, 7, 6, '2026-02-02 09:42:34'),
(58, 'Watertight II Jacket', 'เสื้อกันฝนราคาคุ้มค่า ด้วยเทคโนโลยี Omni-Tech', 3500.00, 10, 'col_rainjacket.jpg', 0, 7, 6, '2026-02-02 09:42:34'),
(59, 'Fast Trek II Fleece', 'เสื้อกั๊ก/แขนยาวผ้าฟรีซ นุ่ม อุ่น ราคาประหยัด', 2000.00, 20, 'col_fleece.jpg', 0, 7, 6, '2026-02-02 09:42:34'),
(60, 'Convertible Pants', 'กางเกงเดินป่าที่รูดซิปเปลี่ยนเป็นขาสั้นได้', 2200.00, 18, 'col_pants.jpg', 0, 7, 6, '2026-02-02 09:42:34'),
(61, 'Camp Shoes (Nestent)', 'รองเท้าสลิปออนใส่ในแคมป์ พับส้นได้', 2500.00, 8, 'col_shoes.jpg', 0, 7, 6, '2026-02-02 09:42:34'),
(62, 'Convey 25L Rolltop', 'เป้กันน้ำ ดีไซน์สวย ใช้งานง่าย', 3200.00, 4, 'col_backpack.jpg', 0, 7, 7, '2026-02-02 09:42:34'),
(63, 'Bottle Holder', 'กระเป๋าใส่ขวดน้ำสะพายข้าง บุด้วยฟอยล์เก็บความเย็น', 900.00, 25, 'col_bottle.jpg', 0, 7, 7, '2026-02-02 09:42:34'),
(64, 'Omni-Heat Beanie', 'หมวกไหมพรมซับในสีเงิน สะท้อนความร้อนได้', 1000.00, 30, 'col_beanie.jpg', 0, 7, 6, '2026-02-02 09:42:34'),
(65, 'Multi Folding Table', 'โต๊ะพับปรับระดับ Hi-Low ได้ ดีไซน์ไม้+อลูมิเนียม', 6500.00, 4, 'mb_table.jpg', 0, 8, 2, '2026-02-02 09:42:34'),
(66, 'L.W. Trail Chair', 'เก้าอี้ทรงนั่งพื้น มีพนักพิง น้ำหนักเบา', 1200.00, 10, 'mb_chair.jpg', 0, 8, 2, '2026-02-02 09:42:34'),
(67, 'Alpine Kettle', 'กาน้ำอลูมิเนียมทรงแบน ร้อนไว', 900.00, 11, 'mb_kettle.jpg', 0, 8, 4, '2026-02-02 09:42:34'),
(68, 'Titanium Cup 450', 'แก้วไทเทเนียม ทรงเรียบ น้ำหนักเบา', 1100.00, 14, 'mb_cup.jpg', 0, 8, 4, '2026-02-02 09:42:34'),
(69, 'O.D. Compact Dripper', 'กรวยดริปกาแฟแบบใช้ไม้เสียบ ขนาดเล็ก สามารถพับเก็บได้', 600.00, 20, 'mb_dripper.jpg', 0, 8, 4, '2026-02-02 09:42:34'),
(70, 'Down Hugger 800', 'ถุงนอนยืดหยุ่นได้ (Spiral Stretch) นอนสบาย ไม่อึดอัด', 12000.00, 5, 'mb_sleepingbag.jpg', 0, 8, 3, '2026-02-02 09:42:34'),
(71, 'Sock-On Sandals', 'รองเท้าแตะสายรัดใส่กับถุงเท้าได้ ไม่หลุดง่าย', 1000.00, 30, 'mb_sandals.jpg', 0, 8, 6, '2026-02-02 09:42:34'),
(72, 'Travel Umbrella', 'ร่มพับที่น้ำหนักเบา (86g) สีสวย กันลมดี', 1500.00, 25, 'mb_umbrella.jpg', 0, 8, 6, '2026-02-02 09:42:34'),
(73, 'Coleman Walker 33L Outdoor Travel Backpack Unisex', 'กระเป๋าเป้สะพายหลังอเนกประสงค์ขนาด 33 ลิตร ออกแบบมาเพื่อการผจญภัยกลางแจ้งและการใช้งานในเมืองในชีวิตประจำวัน', 1760.00, 15, '69819d51dae94.jpg', 0, 2, 7, '2026-02-03 07:01:37'),
(74, 'CAMEL CROWN Mushroom Tent', 'CAMEL CROWN เต็นท์เห็ด เต็นท์ตั้งแคมป์ 4 - 5 คน กันฝนและแดด', 5300.00, 16, '6981ad55059be.jpg', 1, 5, 1, '2026-02-03 08:09:57'),
(75, 'Hydraulic Auto Tent', 'เต็นท์สปริงกางอัตโนมัติ (Hydraulic) สำหรับ 3-4 คน ขนาด 245x245x145 น้ำหนัก 4.67kg', 3600.00, 13, '6981af64d5eb7.jpg', 0, 5, 1, '2026-02-03 08:18:44'),
(76, 'Folding Camping Cot', 'เตียงผ้าใบพับได้ ขาเหล็กแข็งแรง นอนสบาย ไม่ต้องใช้เบาะ', 2520.00, 10, '6981b05c1db52.jpg', 0, 5, 2, '2026-02-03 08:22:52'),
(77, 'Mesh Moon Chair', 'โครงสร้างมั่นคง น้ำหนักรองรับขนาดใหญ่ สะดวกสบาย น้ำหนักเบาและพกพา', 2000.00, 3, '6981b12dd985e.jpg', 0, 5, 2, '2026-02-03 08:26:21'),
(78, 'Portable Cookware Set', 'ชุดหม้อแคมป์ปิ้งแบบพกพา น้ำหนักเบา ทำจากอลูมิเนียมอัลลอยด์ สำหรับปิกนิกกลางแจ้ง', 1030.00, 4, '6981b21c77544.jpg', 0, 5, 1, '2026-02-03 08:30:20'),
(79, 'Hiking Backpack 40L', 'กระเป๋าเป้สะพายหลัง กันน้ำ จุของได้ 40 ลิตร เหมาะกับการพกพาเดินทาง เล่นกีฬา ปีนเขา', 1150.00, 8, '6981b2df52997.jpg', 1, 5, 7, '2026-02-03 08:33:35'),
(80, 'CAMEL CROWN Camping Sleeping Pad, Self Inflating Air Mattress Foam Mat', 'แผ่นรองนอนพองลมเองอัตโนมัติ หนา 5cm สามารถต่อกระดุมเชื่อมกันได้', 1200.00, 6, '6981b3a5c8d1e.jpg', 0, 5, 1, '2026-02-03 08:36:53'),
(81, 'Retro LED storm lantern', '', 1825.00, 6, '6981b43f1c55b.jpg', 0, 5, 5, '2026-02-03 08:39:27'),
(82, 'Iron Mesh Table', 'โต๊ะตะแกรงเหล็กสีดำ ทนความร้อน วางหม้อร้อนหรือเตาแก๊สได้โดยตรง', 2800.00, 7, '6981b6de1daf1.jpg', 0, 5, 2, '2026-02-03 08:50:38'),
(83, 'Foldable Storage Crate', 'กล่องพลาสติกพับได้ ฝาไม้ (Top Wood) ใช้เก็บของและเป็นโต๊ะข้างเตียงได้', 779.00, 8, '6981b74340b15.jpg', 0, 5, 7, '2026-02-03 08:52:19'),
(84, 'CAMEL CROWN Tents for Camping', 'เต็นท์ครอบครัวขนาดใหญ่ แบ่งเป็น 2 ห้องนอน 1 ห้องนั่งเล่น เพดานสูงยืนได้', 9800.00, 2, '6981b81912154.jpg', 0, 5, 1, '2026-02-03 08:55:53'),
(85, 'Nano Puff Jacket', 'เสื้อกันหนาวรุ่นยอดฮิต กันลมกันละอองน้ำ พับเก็บใส่กระเป๋าตัวเองได้', 8500.00, 6, '6981b9f804e7d.jpg', 0, 6, 6, '2026-02-03 09:03:52'),
(86, 'Down Sweater', 'เสื้อขนเป็ด (Down) น้ำหนักเบาแต่ให้ความอบอุ่นสูง เหมาะกับอากาศหนาวเย็น', 9500.00, 3, '6981ba8662602.jpg', 0, 6, 6, '2026-02-03 09:06:14'),
(87, 'R1 Air Crew', 'เสื้อฟลีซระบายอากาศดีเยี่ยม เนื้อผ้าแบบ Zigzag แห้งไว เหมาะใส่ทำกิจกรรม', 4200.00, 10, 'pat_r1air.jpg', 0, 6, 6, '2026-02-03 09:13:12'),
(88, 'Stand Up Shorts', 'กางเกงขาสั้นผ้า Canvas ออร์แกนิค ทนทาน แข็งแรง สไตล์วินเทจ', 2800.00, 15, 'pat_standup.jpg', 0, 6, 6, '2026-02-03 09:13:12'),
(89, 'Organic Cotton Flannel', 'เสื้อเชิ้ตลายสก๊อตผ้าฝ้ายออร์แกนิคหนานุ่ม ใส่เป็นเสื้อคลุมเท่ๆ', 3500.00, 12, 'pat_flannel.jpg', 0, 6, 6, '2026-02-03 09:13:12'),
(90, 'Houdini Jacket', 'เสื้อกันลมที่เบาที่สุด พับเก็บได้เล็กเท่าฝ่ามือ พกพาสะดวกมาก', 4500.00, 20, 'pat_houdini.jpg', 0, 6, 6, '2026-02-03 09:13:12'),
(91, 'Black Hole Waist Pack 5L', 'กระเป๋าคาดเอวรุ่น Black Hole กันน้ำ ทนทาน ช่องใส่ของกว้าง', 2200.00, 18, 'pat_waistpack.jpg', 0, 6, 7, '2026-02-03 09:13:12'),
(92, 'Refugio Daypack 26L', 'เป้สะพายหลังขนาดกลาง มีช่องใส่ Laptop เหมาะทั้งเดินป่าและใช้ในเมือง', 3800.00, 10, 'pat_refugio.jpg', 0, 6, 7, '2026-02-03 09:13:12'),
(93, 'Tin Shed Hat', 'หมวกแก๊ปผ้า Canvas ผสม Hemp ทนทาน สไตล์ Workwear', 1600.00, 25, 'pat_tinshed.jpg', 0, 6, 6, '2026-02-03 09:13:12'),
(94, 'Fishermans Rolled Beanie', 'หมวกไหมพรมทรงสั้น (Beanie) ยอดฮิต ใส่ได้ทุกวัน', 1400.00, 30, 'pat_beanie.jpg', 0, 6, 6, '2026-02-03 09:13:12'),
(95, 'Altvia Alpine Pants', 'กางเกงขายาวสำหรับปีนเขาและเดินป่า ยืดหยุ่นสูง ระบายอากาศดี', 5500.00, 8, 'pat_altvia.jpg', 0, 6, 6, '2026-02-03 09:13:12'),
(96, 'Retro Pile Jacket', 'เสื้อแจ็คเก็ตผ้าฟลีซขนแกะเทียม (Shearling) หนานุ่ม ได้ลุคย้อนยุค', 6200.00, 6, 'pat_retropile.jpg', 0, 6, 6, '2026-02-03 09:13:12'),
(97, 'Guidewater Hip Pack', 'กระเป๋าคาดเอวกันน้ำระดับ IPX7 เหมาะสำหรับตกปลาหรือลุยน้ำ', 8900.00, 4, 'pat_guidewater.jpg', 0, 6, 7, '2026-02-03 09:13:12'),
(98, 'Black Hole Wheeled Duffel', 'กระเป๋าเดินทางมีล้อลาก ขนาด 40L กันน้ำ ทนทาน ลุยได้ทุกที่', 12000.00, 3, 'pat_wheeled.jpg', 0, 6, 7, '2026-02-03 09:13:12'),
(99, 'Silver Ridge Utility Lite', 'เสื้อเชิ้ตแขนยาวกันแดด UPF 50 ระบายอากาศดี แห้งไว', 2500.00, 15, 'col_silver_ridge.jpg', 0, 7, 6, '2026-02-03 09:20:44'),
(100, 'Newton Ridge Plus II', 'รองเท้าเดินป่ากันน้ำ (Waterproof) ทำจากหนังแท้ ทนทาน พื้นยึดเกาะดี', 4200.00, 8, 'col_newton_boots.jpg', 1, 7, 6, '2026-02-03 09:20:44'),
(101, 'Bora Bora Booney II', 'หมวกปีกกว้างกันแดด มีสายรัดคางและแผงระบายอากาศ', 1200.00, 25, 'col_booney_hat.jpg', 0, 7, 6, '2026-02-03 09:20:44'),
(102, 'Zigzag Hip Pack', 'กระเป๋าคาดเอว/อก ขนาดกะทัดรัด ดีไซน์คลาสสิก ใส่ของจุกจิกได้ดี', 990.00, 20, 'col_zigzag_hip.jpg', 0, 7, 7, '2026-02-03 09:20:44'),
(103, 'Atlas Explorer 25L', 'เป้สะพายหลังอเนกประสงค์ มีช่องใส่ Laptop และสายรัดอก', 2900.00, 10, 'col_atlas_backpack.jpg', 0, 7, 7, '2026-02-03 09:20:44'),
(104, 'Tandem Trail 22L', 'เป้พับเก็บได้ (Packable) น้ำหนักเบามาก พกพาสะดวก ใช้เป็นเป้สำรอง', 1800.00, 12, 'col_tandem_pack.jpg', 0, 7, 7, '2026-02-03 09:20:44'),
(105, 'River 30L Dry Bag', 'ถุงกันน้ำทรงกระบอกขนาด 30 ลิตร ป้องกันสัมภาระเปียกตอนลุยน้ำ', 1500.00, 15, 'col_drybag.jpg', 0, 7, 7, '2026-02-03 09:20:44'),
(106, 'PFG Terminal Tackle Hoodie', 'เสื้อฮู้ดแขนยาวสำหรับตกปลา กันแดดได้ดี ผ้าเย็นใส่สบาย', 2200.00, 18, 'col_pfg_hoodie.jpg', 0, 7, 6, '2026-02-03 09:20:44'),
(107, 'Switchback III Jacket', 'เสื้อกันฝนผู้หญิง พับเก็บฮู้ดได้ น้ำหนักเบา สีสันสดใส', 2500.00, 10, 'col_switchback.jpg', 0, 7, 6, '2026-02-03 09:20:44'),
(108, 'Youth Redmond Waterproof', 'รองเท้าเดินป่าสำหรับเด็ก กันน้ำ ทนทานเหมือนของผู้ใหญ่', 2500.00, 5, 'col_youth_shoes.jpg', 0, 7, 6, '2026-02-03 09:20:44'),
(109, 'Watch Cap', 'หมวกไหมพรมทรงสั้น พับขอบ สไตล์กะลาสี (Fisherman Style)', 800.00, 30, 'col_watch_cap.jpg', 0, 7, 6, '2026-02-03 09:20:44'),
(110, 'Storm Cruiser Jacket', 'เสื้อกันฝน GORE-TEX 3 ชั้น รุ่นตำนาน กันน้ำ ระบายอากาศได้ดี', 7900.00, 10, 'mb_storm_cruiser.jpg', 1, 8, 6, '2026-02-03 09:29:02'),
(111, 'Superior Down Parka', 'เสื้อขนเป็ด 800 Fill Power น้ำหนักเบา พับเก็บได้', 4500.00, 15, 'mb_superior_down.jpg', 1, 8, 6, '2026-02-03 09:29:02'),
(112, 'Plasma 1000 Down Jacket', 'ที่สุดของความเบา! ใช้ขนเป็ด 1000 Fill Power  อุ่นและบาง', 9900.00, 5, 'mb_plasma_1000.jpg', 0, 8, 6, '2026-02-03 09:29:02'),
(113, 'Versalite Jacket', 'เสื้อกันฝน Ultra-Light กันลมกันน้ำ พับเก็บได้ เหมาะกับสายเดินป่า', 5200.00, 12, 'mb_versalite.jpg', 0, 8, 6, '2026-02-03 09:29:02'),
(114, 'Alpine Light Down Parka', 'เสื้อขนเป็ดที่หนากว่ารุ่น Superior เน้นใส่อุ่นในแคมป์หรือที่อากาศหนาวจัด', 5900.00, 8, 'mb_alpine_light.jpg', 0, 8, 6, '2026-02-03 09:29:02'),
(115, 'Wickron T-Shirt', 'เสื้อยืดเดินป่า ระบายเหงื่อไว แห้งเร็ว ไม่เหม็นอับ ลายกราฟิก', 950.00, 30, 'mb_wickron_tee.jpg', 0, 8, 6, '2026-02-03 09:29:02'),
(116, 'Stretch O.D. Pants', 'กางเกงเดินป่าผ้ายืดหยุ่น 4 ทิศทาง เคลือบสารสะท้อนน้ำ ใส่สบาย คล่องตัว', 2200.00, 20, 'mb_stretch_pants.jpg', 0, 8, 6, '2026-02-03 09:29:02'),
(117, 'Chameece Jacket', 'เสื้อฟลีซเนื้อบาง (Chameece) นุ่ม ระบายอากาศดี ', 1900.00, 15, 'mb_chameece.jpg', 0, 8, 6, '2026-02-03 09:29:02'),
(118, 'Down Hugger 800 #3', 'ถุงนอนขนเป็ดระบบ Super Spiral Stretch ยืดหยุ่นตามตัว ไม่อึดอัด', 9500.00, 6, 'mb_down_hugger.jpg', 0, 8, 3, '2026-02-03 09:29:02'),
(119, 'Alpine Therm Bottle 0.5L', 'กระติกน้ำเก็บอุณหภูมิ ออกแบบมาเพื่อปีนเขาโดยเฉพาะ เก็บอุณหภูมิ 24 ชม.', 1200.00, 25, 'mb_therm_bottle.jpg', 0, 8, 4, '2026-02-03 09:29:02'),
(120, 'U.L. Mono Pouch', 'กระเป๋าสะพายข้างใบจิ๋ว น้ำหนักเบา สำหรับใส่กระเป๋าตังค์และมือถือ', 850.00, 20, 'mb_mono_pouch.jpg', 0, 8, 7, '2026-02-03 09:29:02'),
(121, 'Sock-On Sandals', 'รองเท้าแตะดีไซน์เอกลักษณ์ ใส่ทับถุงเท้าได้ สายรัดกระชับ ไม่หลุดง่าย', 1050.00, 18, 'mb_sock_on.jpg', 0, 8, 6, '2026-02-03 09:29:02'),
(122, 'Ango Automatic Tent (3P)', 'เต็นท์กางอัตโนมัติทรงกระโจม เหมาะสำหรับมือใหม่', 4500.00, 10, 'nh_ango3.jpg', 0, 3, 1, '2026-02-03 09:39:58'),
(123, 'Cloud Up 2 (20D Silicone)', 'เต็นท์เดินป่ารุ่นยอดฮิต ผ้า 20D เบาและกันน้ำ น้ำหนักรวมเพียง 1.7 kg', 3200.00, 15, 'nh_cloudup2.jpg', 0, 3, 1, '2026-02-03 09:39:58'),
(124, 'Camping Cot (High/Low)', 'เตียงผ้าใบปรับระดับความสูงได้ (ขาถอดได้) รับน้ำหนักได้ 150kg นอนสบายไม่ปวดหลัง', 3500.00, 8, 'nh_cot_adjust.jpg', 0, 3, 2, '2026-02-03 09:39:58'),
(125, 'Kermit Chair (Alu-Wood)', 'เก้าอี้แคมป์ปิ้งทรง Kermit วัสดุอลูมิเนียมลายไม้ พับเก็บง่าย', 1400.00, 20, 'nh_kermit_chair.jpg', 0, 3, 2, '2026-02-03 09:39:58'),
(126, 'Folding Wagon (Tear-Proof)', 'รถเข็นของแคมป์ปิ้ง ล้อใหญ่ลุยได้ทุกพื้นผิว ผ้าหนากันฉีกขาด จุของได้เยอะ', 2900.00, 6, 'nh_wagon.jpg', 0, 3, 7, '2026-02-03 09:39:58'),
(127, 'U250 Hooded Sleeping Bag', 'ถุงนอนผ้าฝ้ายทรงสี่เหลี่ยมมีฮู้ด นุ่มสบาย ต่อซิปคู่กันได้', 950.00, 25, 'nh_u250_bag.jpg', 0, 3, 3, '2026-02-03 09:39:58'),
(128, 'Sponge Automatic Mat (Double)', 'แผ่นรองนอนพองลมเองแบบคู่ หนา 3-5 cm นอนสองคนได้สบายๆ ไม่ต้องเป่าลม', 2500.00, 5, 'nh_sponge_mat.jpg', 0, 3, 3, '2026-02-03 09:39:58'),
(129, 'Mini Cassette Stove', 'เตาแก๊สปิกนิกขนาดมินิ ดีไซน์มินิมอล ไฟแรง ใช้กับแก๊สกระป๋องยาว', 1200.00, 12, 'nh_mini_stove.jpg', 0, 3, 4, '2026-02-03 09:39:58'),
(130, 'Cast Iron Dutch Oven', 'หม้อเหล็กหล่อ (Dutch Oven) พร้อมขาตั้งและที่ยกฝา ทำได้ทั้งอบ ต้ม ตุ๋น', 1800.00, 4, 'nh_dutch_oven.jpg', 0, 3, 4, '2026-02-03 09:39:58'),
(131, 'Hexagon Tarp (Coating)', 'ทาร์ปทรงหกเหลี่ยม กันแดดกันฝน เคลือบกัน UV เหมาะสำหรับนั่งเล่นหน้าเต็นท์', 1900.00, 15, 'nh_hex_tarp.jpg', 0, 3, 1, '2026-02-03 09:39:58'),
(132, 'Wawona 6 Tent', 'เต็นท์ครอบครัวสำหรับ 6 คน พื้นที่กว้างขวาง ยืนได้ ระบายอากาศดี', 18500.00, 3, 'tnf_wawona6.jpg', 1, 4, 1, '2026-02-03 09:45:50'),
(133, 'Base Camp Duffel (M)', 'กระเป๋าดัฟเฟิลรุ่นตำนาน (ไซส์ M 71L) กันน้ำ ทนทาน สะพายหลังได้', 5500.00, 15, 'tnf_basecamp_m.jpg', 1, 4, 7, '2026-02-03 09:45:50'),
(134, '1996 Retro Nuptse Jacket', 'เสื้อขนเป็ดรุ่นไอคอนิก ทรงพองตัวสั้น พับเก็บฮู้ดได้ กันหนาวติดลบได้สบาย', 10900.00, 8, 'tnf_nuptse.jpg', 1, 4, 6, '2026-02-03 09:45:50'),
(135, 'Recon Backpack', 'เป้ Daypack รุ่นยอดนิยม ช่องใส่ของเยอะ มีช่อง Laptop ซัพพอร์ตหลังดีมาก', 4200.00, 12, 'tnf_recon.jpg', 0, 4, 7, '2026-02-03 09:45:50'),
(136, 'Vectiv Exploris 2 Futurelight', 'รองเท้าเดินป่ากันน้ำ (Futurelight) พื้น VECTIV ช่วยส่งแรงเดิน พื้นยึดเกาะหนึบ', 6500.00, 10, 'tnf_vectiv.jpg', 0, 4, 6, '2026-02-03 09:45:50'),
(137, 'Eco Trail Bed 20', 'ถุงนอนทรงสี่เหลี่ยม (Envelope) ผลิตจากวัสดุรีไซเคิล กันหนาวได้ถึง -7°C', 4900.00, 6, 'tnf_eco_trail.jpg', 0, 4, 3, '2026-02-03 09:45:50'),
(138, 'Antora Jacket', 'เสื้อกันลมกันฝน (Rain Jacket) ดีไซน์ทันสมัย ผ้า DryVent กันน้ำ 100%', 4500.00, 20, 'tnf_antora.jpg', 0, 4, 6, '2026-02-03 09:45:50'),
(139, 'Paramount Convertible Pants', 'กางเกงเดินป่าถอดขาได้ (เป็นขาสั้น) ผ้าแห้งไว ยืดหยุ่น ทนทานต่อการขีดข่วน', 3200.00, 15, 'tnf_paramount.jpg', 0, 4, 6, '2026-02-03 09:45:50'),
(140, 'ThermoBall Traction Mules', 'รองเท้าสลิปเปอร์ใส่ในแคมป์ บุฉนวนกันหนาว พื้นยางกันลื่น ใส่สบายเท้า', 2500.00, 25, 'tnf_mules.jpg', 0, 4, 6, '2026-02-03 09:45:50'),
(141, 'Denali Jacket', 'เสื้อแจ็คเก็ตผ้าฟลีซ (Fleece) เสริมไหล่ด้วยผ้าไนลอน รุ่นคลาสสิก', 6900.00, 10, 'tnf_denali.jpg', 0, 4, 6, '2026-02-03 09:45:50'),
(142, 'Homestead Domey 3', 'เต็นท์โดมลวดลายกราฟิกสดใส สำหรับ 3 คน ประตูใหญ่ 3 ทิศทาง กางง่าย', 9500.00, 4, 'tnf_homestead.jpg', 0, 4, 1, '2026-02-03 09:45:50'),
(143, 'Instant Up 4P Tent', 'เต็นท์กางเร็วระบบ Instant กางเสร็จใน 2 นาที สำหรับ 4 คน กันฝน', 5900.00, 5, 'coleman_instant_4p.jpg', 1, 2, 1, '2026-02-03 09:51:30'),
(144, '50QT Xtreme Wheeled Cooler', 'กระติกน้ำแข็งเก็บความเย็น 5 วัน (Xtreme Technology) มีล้อลาก จุได้ 84 กระป๋อง', 3200.00, 10, 'coleman_xtreme_wheel.jpg', 0, 2, 4, '2026-02-03 09:51:30'),
(145, 'Triton Series 2-Burner Stove', 'เตาแก๊ส 2 หัว รุ่น Triton ทนทาน ไฟแรง ปรับความร้อนได้ละเอียด พับเก็บเป็นกระเป๋า', 3900.00, 8, 'coleman_triton.jpg', 0, 2, 4, '2026-02-03 09:51:30'),
(146, 'Deck Chair with Table', 'เก้าอี้ (Deck Chair) โครงอลูมิเนียมเบา มีโต๊ะพับข้างตัววางแก้วน้ำได้', 2500.00, 12, 'coleman_deck_chair.jpg', 0, 2, 2, '2026-02-03 09:51:30'),
(147, 'NorthStar Propane Lantern', 'ตะเกียงแก๊ส (1500 Lumens) จุดติดง่าย มีโครงเหล็กกันกระแทก', 3500.00, 15, 'coleman_northstar.jpg', 1, 2, 5, '2026-02-03 09:51:30'),
(148, 'Silverton 350 Sleeping Bag', 'ถุงนอนทรงมัมมี่ กันหนาวได้ถึง -18°C เหมาะสำหรับแคมป์หน้าหนาวจัด', 1800.00, 20, 'coleman_silverton.jpg', 0, 2, 3, '2026-02-03 09:51:30'),
(149, 'Pack-Away Kitchen', 'โต๊ะครัวพับได้ มีราวแขวนตะหลิว ขาตั้งเตา และอ่างล้างจานในตัว ครบจบในชุดเดียว', 4500.00, 4, 'coleman_kitchen.jpg', 0, 2, 2, '2026-02-03 09:51:30'),
(150, 'Instant Screen House', 'มุ้งกันยุงขนาดใหญ่ กางแบบ Instant ใช้ครอบโต๊ะกินข้าว ป้องกันแมลงรบกวน', 5500.00, 6, 'coleman_screen_house.jpg', 0, 2, 1, '2026-02-03 09:51:30'),
(151, 'Camp Oven', 'เตาอบพับได้ (วางบนเตาแก๊ส) สำหรับอบขนมปัง พิซซ่า หรือมัฟฟินในแคมป์', 1500.00, 10, 'coleman_camp_oven.jpg', 0, 2, 4, '2026-02-03 09:51:30'),
(152, 'CPX 6 Classic LED Lantern', 'ตะเกียง LED ดีไซน์คลาสสิก ชาร์จไฟได้หรือใส่ถ่านก็ได้ ปรับความสว่างได้ 2 ระดับ', 1900.00, 25, 'coleman_led_lantern.jpg', 0, 2, 5, '2026-02-03 09:51:30'),
(153, 'Extra Durable Airbed (Queen)', 'ที่นอนเป่าลมขนาดควีนไซส์ รุ่นทนทานพิเศษ น้ำหนักเบากว่ารุ่นปกติ 47%', 2200.00, 15, 'coleman_airbed.jpg', 0, 2, 3, '2026-02-03 09:51:30'),
(154, 'Outdoor Wagon (Red)', 'รถเข็นสีแดงในตำนาน รับน้ำหนักได้ 100kg พับเก็บง่าย ล้อใหญ่ลุยได้ทุกที่', 4200.00, 8, 'coleman_wagon_red.jpg', 1, 2, 7, '2026-02-03 09:51:30'),
(155, 'Takibi Fire & Grill (L)', 'เตาผิงไฟยอดฮิต (Takibi) สแตนเลสทนทาน ใช้ผิงไฟและปิ้งย่างได้ในตัวเดียว', 7500.00, 10, 'sp_takibi_l.jpg', 1, 1, 4, '2026-02-03 09:56:19'),
(156, 'Jikaro Firering Table', 'โต๊ะสแตนเลสล้อมรอบเตา Takibi สำหรับนั่งทานปิ้งย่างรอบกองไฟสไตล์ญี่ปุ่น', 11500.00, 5, 'sp_jikaro.jpg', 0, 1, 2, '2026-02-03 09:56:19'),
(157, 'Entry 2 Room Elfield', 'เต็นท์อุโมงค์ขนาดใหญ่ (2 Room) มีห้องนั่งเล่นและห้องนอนในตัว กันฝนดีเยี่ยม', 29900.00, 3, 'sp_elfield.jpg', 1, 1, 1, '2026-02-03 09:56:19'),
(158, 'Home & Camp Burner', 'เตาแก๊สพับได้ ดีไซน์สวยเหมือนกระบอกน้ำ พกพาง่าย ไฟแรง ปลอดภัยสูง', 3900.00, 20, 'sp_home_burner.jpg', 0, 1, 4, '2026-02-03 09:56:19'),
(159, 'Take! Chair Long', 'เก้าอี้ผ้าใบสีขาวโครงไม้ไผ่ พนักพิงสูง นั่งสบายและถ่ายรูปสวยมาก', 7200.00, 8, 'sp_take_long.jpg', 0, 1, 2, '2026-02-03 09:56:19'),
(160, 'Flat Burner', 'หัวเตาแก๊สแบบฝังลงโต๊ะ IGT ได้พอดี หรือจะวางบนโต๊ะธรรมดาก็ได้', 4200.00, 12, 'sp_flat_burner.jpg', 0, 1, 4, '2026-02-03 09:56:19'),
(161, 'Hard Rock Cooler 40QT', 'กระติกน้ำแข็งรุ่นทนทานที่สุด เก็บความเย็นได้ยาวนาน ดีไซน์ดุดัน', 22000.00, 4, 'sp_hard_rock.jpg', 0, 1, 4, '2026-02-03 09:56:19'),
(162, 'Fal Pro. Air 3', 'เต็นท์เดินป่ารุ่น Pro น้ำหนักเบา กางเร็วใน 20 วินาที สำหรับ 3 คน', 22500.00, 5, 'sp_fal_pro3.jpg', 0, 1, 1, '2026-02-03 09:56:19'),
(163, 'IGT Slim Table', 'โต๊ะไม้ Teak ธรรมชาติ ดีไซน์บางเฉียบ พับเก็บได้ พร้อมช่องใส่เตา IGT', 13900.00, 6, 'sp_igt_slim.jpg', 1, 1, 2, '2026-02-03 09:56:19'),
(164, '2-Layer Tarp Pro', 'ทาร์ปกันความร้อน 2 ชั้น ป้องกันแสงแดดและฝนได้อย่างดีเยี่ยม', 40831.43, 5, 'sp_2layer_tarp.jpg', 1, 1, 1, '2026-02-03 09:59:50'),
(165, 'Land Station L Ivory', 'ทาร์ปอเนกประสงค์ขนาดใหญ่ สีงาช้าง ปรับรูปแบบการกางได้หลากหลาย', 26119.30, 3, 'sp_landstation.jpg', 1, 1, 1, '2026-02-03 09:59:50'),
(166, 'Penta Ease in Ivory', 'เต็นท์ด้านใน (Inner Tent) สำหรับใช้คู่กับ Penta Tarp สีงาช้าง', 7700.00, 5, 'sp_penta_ease.jpg', 0, 1, 1, '2026-02-03 09:59:50'),
(167, 'Entry Pack TT', 'ชุดเริ่มต้นสุดคุ้ม ประกอบด้วยเต็นท์อุโมงค์และทาร์ปทรง Hexa สำหรับมือใหม่', 14228.00, 8, 'sp_entry_pack.jpg', 1, 1, 1, '2026-02-03 09:59:50'),
(168, 'Titanium Spork', 'ช้อนส้อมไทเทเนียม (Spork) น้ำหนักเบา แข็งแรง ทนทาน', 550.00, 50, 'sp_spork.jpg', 0, 1, 4, '2026-02-03 09:59:50'),
(169, 'Shimo Tumbler Set', 'เซ็ตแก้วเก็บความเย็นรุ่น Shimo ดีไซน์สวยงาม เก็บอุณหภูมิได้ดี', 3706.00, 10, 'sp_shimo.jpg', 0, 1, 4, '2026-02-03 09:59:50'),
(170, 'Mountain of Moods Snow Jacket', 'เสื้อแจ็คเก็ตหิมะคอลเลกชันพิเศษ Mountain of Moods กันหนาวและกันน้ำระดับสูง', 24500.00, 4, 'sp_mm_jacket.jpg', 0, 1, 6, '2026-02-03 09:59:50'),
(171, 'Everyday Down Jacket', 'เสื้อคลุมขนเป็ดชั้นนอก ดีไซน์เรียบง่าย ใส่ได้ทุกวัน อุ่นสบาย', 15900.00, 6, 'sp_down_jacket.jpg', 0, 1, 6, '2026-02-03 09:59:50'),
(172, 'System Ofuton & Wide Mat', 'ชุดเครื่องนอนและเบาะรองสไตล์ญี่ปุ่น นุ่มสบาย แยกส่วนได้', 9500.00, 5, 'sp_ofuton.jpg', 0, 1, 3, '2026-02-03 09:59:50'),
(173, 'Down System Ofuton & Wide Mat', 'ชุดระบบผ้านวมขนเป็ดพร้อมเบาะรอง ให้ความอบอุ่นขั้นสุดสำหรับฤดูหนาว', 38000.00, 3, 'sp_down_ofuton.jpg', 0, 1, 3, '2026-02-03 09:59:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone`, `address`, `fullname`, `role`, `created_at`, `updated_at`, `reset_token`, `reset_expires`) VALUES
(1, 'admin', '$2y$10$SiU3f2pF3tCfm8CEy36KdOTvX7JI7KgdLT00GQwP7K6W9m3nYOlc2', 'admin@campingstore.com', '', '', 'Admin User', 'admin', '2026-02-02 08:45:59', '2026-02-10 07:00:22', NULL, NULL),
(3, 'somchaiJai', '$2y$10$PcCvWMGDfJc.qGmxMjG.cOoXRqPjFv9McWfGMRCsSRvDNAEmsD/My', 'somchai@gmail.com', NULL, NULL, 'somchai Jaidee', 'customer', '2026-02-02 09:29:53', '2026-02-02 09:29:53', NULL, NULL),
(4, 'amimta11', '$2y$10$CSV33ddkHvJIggRts7Gote/loaYBLQGrXGG4tVgFCSRAtYXxyZAUW', 'bonus@gmail.com', NULL, NULL, 'อมินตา รุ่งเรือง', 'customer', '2026-02-04 02:52:12', '2026-02-04 02:52:12', NULL, NULL),
(5, 'aminta22', '$2y$10$jxPsd4d1jMNlabzkOobU0Oicjd7NOesekBxq5ntD7CO/l3REt.GMW', 'lalphone6911@gmail.com', '0656526330', '55/1 ม.5 ต.สำมะโรง เพชรบุรี 76000', 'อมินตา รุ่งเรือง', 'customer', '2026-02-09 06:09:37', '2026-02-11 05:36:44', NULL, NULL),
(7, 'somsri04', '$2y$10$41pgsH7DCzlWwavr0rRypOa9XRgOLsC2ZJioUfXLeaoHTNbnu6che', 'somsri04@gmail.com', '0646516655', '44/2 ม.6 ต.โพไร่หวาน อ.เมือง จ.เพชรบุรี 76000', 'สมศรี ตั้งใจ', 'customer', '2026-02-11 05:28:48', '2026-02-11 05:35:08', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders_item`
--
ALTER TABLE `orders_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders_item`
--
ALTER TABLE `orders_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders_item`
--
ALTER TABLE `orders_item`
  ADD CONSTRAINT `orders_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

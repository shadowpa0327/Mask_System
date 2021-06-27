-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2021 年 06 月 10 日 01:54
-- 伺服器版本： 10.4.17-MariaDB
-- PHP 版本： 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `Mask_system`
--

-- --------------------------------------------------------

--
-- 資料表結構 `Clerk`
--

CREATE TABLE `Clerk` (
  `username` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `store_name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `Clerk`
--

INSERT INTO `Clerk` (`username`, `store_name`) VALUES
('user2', 'Shop AA');

-- --------------------------------------------------------

--
-- 資料表結構 `mask_order`
--

CREATE TABLE `mask_order` (
  `OID` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `StartTime` datetime NOT NULL,
  `EndTime` datetime DEFAULT NULL,
  `Create_user` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `Finish_user` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `StoreName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `Order_Number` int(11) UNSIGNED NOT NULL,
  `Order_Price` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `mask_order`
--

INSERT INTO `mask_order` (`OID`, `status`, `StartTime`, `EndTime`, `Create_user`, `Finish_user`, `StoreName`, `Order_Number`, `Order_Price`) VALUES
(1, 'Cancelled', '2021-06-10 01:16:15', '2021-06-10 01:17:35', 'user2', 'user2', 'Shop', 1, 10),
(2, 'Cancelled', '2021-06-10 01:16:43', '2021-06-10 01:17:42', 'user2', 'user2', 'Shop', 2, 10),
(3, 'Cancelled', '2021-06-10 01:16:46', '2021-06-10 01:17:42', 'user2', 'user2', 'Shop', 3, 10),
(4, 'Cancelled', '2021-06-10 01:16:48', '2021-06-10 01:17:49', 'user2', 'user2', 'Shop', 5, 10),
(5, 'Not Finished', '2021-06-10 01:18:44', NULL, 'user1', NULL, 'Shop', 1, 10),
(6, 'Not Finished', '2021-06-10 01:18:46', NULL, 'user1', NULL, 'Shop', 1, 10),
(7, 'Not Finished', '2021-06-10 01:18:50', NULL, 'user1', NULL, 'Shop', 1, 10),
(8, 'Not Finished', '2021-06-10 01:18:56', NULL, 'user1', NULL, 'Shop', 1, 10),
(9, 'Not Finished', '2021-06-10 01:18:58', NULL, 'user1', NULL, 'Shop', 1, 10),
(10, 'Not Finished', '2021-06-10 01:19:00', NULL, 'user1', NULL, 'Shop', 1, 10),
(11, 'Not Finished', '2021-06-10 01:19:02', NULL, 'user1', NULL, 'Shop', 1, 10),
(12, 'Not Finished', '2021-06-10 01:19:04', NULL, 'user1', NULL, 'Shop', 1, 10),
(13, 'Not Finished', '2021-06-10 01:34:56', NULL, 'user2', NULL, 'Shop', 1, 10),
(14, 'Not Finished', '2021-06-10 01:36:24', NULL, 'user2', NULL, 'Shop', 1, 10),
(15, 'Not Finished', '2021-06-10 01:37:59', NULL, 'user2', NULL, 'Shop', 1, 10),
(16, 'Not Finished', '2021-06-10 01:41:12', NULL, 'user2', NULL, 'Shop', 1, 10),
(17, 'Not Finished', '2021-06-10 01:43:03', NULL, 'user2', NULL, 'Shop', 1, 10),
(18, 'Not Finished', '2021-06-10 01:43:26', NULL, 'user2', NULL, 'Shop', 1, 10),
(19, 'Cancelled', '2021-06-10 01:44:23', '2021-06-10 01:48:43', 'user2', 'user1', 'Shop AA', 1, 10),
(20, 'Cancelled', '2021-06-10 01:44:36', '2021-06-10 01:48:43', 'user2', 'user1', 'Shop AA', 1, 10),
(21, 'Finished', '2021-06-10 01:45:24', '2021-06-10 01:49:04', 'user2', 'user1', 'Shop AA', 1, 10),
(22, 'Not Finished', '2021-06-10 01:47:29', NULL, 'brian0113', NULL, 'Dick_samll', 1, 11),
(23, 'Finished', '2021-06-10 01:47:59', '2021-06-10 01:49:04', 'user2', 'user1', 'Shop AA', 1, 10),
(24, 'Cancelled', '2021-06-10 01:48:02', '2021-06-10 01:49:19', 'user2', 'user1', 'Shop AA', 1, 10),
(25, 'Cancelled', '2021-06-10 01:48:04', '2021-06-10 01:49:28', 'user2', 'user1', 'Shop AA', 1, 10),
(26, 'Cancelled', '2021-06-10 01:48:06', '2021-06-10 01:49:28', 'user2', 'user1', 'Shop AA', 1, 10);

-- --------------------------------------------------------

--
-- 資料表結構 `Store`
--

CREATE TABLE `Store` (
  `SID` int(11) NOT NULL,
  `store_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `city` varchar(20) NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `number` int(10) UNSIGNED NOT NULL,
  `shop_holder` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `Store`
--

INSERT INTO `Store` (`SID`, `store_name`, `city`, `price`, `number`, `shop_holder`) VALUES
(1, 'Shop AA', 'Taipei', 10, 500, 'user1'),
(2, 'BB shop', 'Taipei', 10, 50, 'user2'),
(3, 'Cshoop', 'Taipei', 10, 0, 'user3'),
(4, 'HsinChu AA', 'Taipei', 11, 111, 'brian1009'),
(5, 'HsinChu DD', 'Taipei', 12, 1111, 'brian1008'),
(6, 'Dick_samll', 'Taipei', 11, 1110, 'brian0113');

-- --------------------------------------------------------

--
-- 資料表結構 `Users`
--

CREATE TABLE `Users` (
  `UID` int(11) NOT NULL,
  `username` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `salt` char(4) NOT NULL,
  `phone_number` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `Users`
--

INSERT INTO `Users` (`UID`, `username`, `password`, `salt`, `phone_number`) VALUES
(1, 'user1', '241e65b3a64acfea5a75c27cc504ceb4e565786f14ccd3094a48955f227768ab', '1332', '0975950912'),
(2, 'user2', '5554aed2befc818816b8e2c6c2373364efaccc8ddeb146c588b2c44e262e6995', '5195', '0975950911'),
(3, 'user3', '08b7c32bd155276f563b10fb7700313267da1a9dca2c9c6e6e0c95cbe8e5f583', '8353', '0975950913'),
(4, 'brian1009', '6ba50874f5c8e84a8218da45f54ea33605522849f1945499c6bd29d1f25c8981', '9048', '0975950912'),
(5, 'brian1008', 'a9c98c139977ccf11fc6dbf41004ec4986b01d8df090d9e84db60d3c6959a993', '1949', '0975950913'),
(6, 'brian0113', '796cd6864b322825e26a2408cfa3c21cbedb56cb3329eae8a900b8d32a8c170a', '6317', '0912345678');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `Clerk`
--
ALTER TABLE `Clerk`
  ADD PRIMARY KEY (`username`,`store_name`);

--
-- 資料表索引 `mask_order`
--
ALTER TABLE `mask_order`
  ADD PRIMARY KEY (`OID`);

--
-- 資料表索引 `Store`
--
ALTER TABLE `Store`
  ADD PRIMARY KEY (`SID`);

--
-- 資料表索引 `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UID`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `mask_order`
--
ALTER TABLE `mask_order`
  MODIFY `OID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `Store`
--
ALTER TABLE `Store`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `Users`
--
ALTER TABLE `Users`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

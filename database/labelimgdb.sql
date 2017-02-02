-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2017 at 02:25 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `labelimgdb`
--
CREATE DATABASE IF NOT EXISTS `labelimgdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `labelimgdb`;

-- --------------------------------------------------------

--
-- Table structure for table `labelimgarea`
--

CREATE TABLE `labelimgarea` (
  `id` int(4) NOT NULL,
  `source` int(4) NOT NULL,
  `rectType` int(4) NOT NULL,
  `rectLeft` int(4) NOT NULL,
  `rectTop` int(4) NOT NULL,
  `rectRight` int(4) NOT NULL,
  `rectBottom` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `labelimgcategories`
--

CREATE TABLE `labelimgcategories` (
  `id` int(4) NOT NULL,
  `Category` char(25) NOT NULL,
  `Color` char(7) NOT NULL DEFAULT '#FFFFFF' COMMENT 'Color associated with the category'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `labelimgexportlinks`
--

CREATE TABLE `labelimgexportlinks` (
  `id` int(4) NOT NULL,
  `token` char(50) NOT NULL,
  `archivePath` char(100) NOT NULL,
  `expires` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `labelimglinks`
--

CREATE TABLE `labelimglinks` (
  `id` int(4) NOT NULL,
  `path` char(250) NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `available` tinyint(4) NOT NULL DEFAULT '1',
  `requested` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `labelimgusers`
--

CREATE TABLE `labelimgusers` (
  `id` int(4) NOT NULL,
  `username` char(30) NOT NULL,
  `password` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `labelimgusers`
--

INSERT INTO `labelimgusers` (`id`, `username`, `password`) VALUES
(1, 'UploadUser', '0000'),
(2, 'ValidateUser', '0000'),
(3, 'EnRouteUser', '0000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `labelimgarea`
--
ALTER TABLE `labelimgarea`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labelimgcategories`
--
ALTER TABLE `labelimgcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labelimgexportlinks`
--
ALTER TABLE `labelimgexportlinks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `labelimglinks`
--
ALTER TABLE `labelimglinks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `path` (`path`);

--
-- Indexes for table `labelimgusers`
--
ALTER TABLE `labelimgusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `labelimgarea`
--
ALTER TABLE `labelimgarea`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;
--
-- AUTO_INCREMENT for table `labelimgcategories`
--
ALTER TABLE `labelimgcategories`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `labelimgexportlinks`
--
ALTER TABLE `labelimgexportlinks`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `labelimglinks`
--
ALTER TABLE `labelimglinks`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT for table `labelimgusers`
--
ALTER TABLE `labelimgusers`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
DELIMITER $$
--
-- Events
--
CREATE DEFINER=`labelImgManager`@`localhost` EVENT `free images` ON SCHEDULE EVERY '10:0' MINUTE_SECOND STARTS '2017-01-29 00:00:00' ENDS '2018-02-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE labelimglinks SET available = 1 WHERE available = 0 AND requested < DATE_SUB(NOW(), INTERVAL 1 HOUR)$$

CREATE DEFINER=`labelImgManager`@`localhost` EVENT `Clean download links` ON SCHEDULE EVERY '0 6' DAY_HOUR STARTS '2017-01-29 00:00:00' ENDS '2018-01-29 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM `labelimgexportlinks` WHERE `labelimgexportlinks`.`expires`< NOW()$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

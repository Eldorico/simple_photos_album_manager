-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2018 at 09:03 PM
-- Server version: 5.7.23-0ubuntu0.18.04.1
-- PHP Version: 7.2.7-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Album`
--

CREATE TABLE `Album` (
  `id_album` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `Album`
--

INSERT INTO `Album` (`id_album`, `name`, `category`) VALUES
(1, 'AlbumTest', 1),
(2, 'Album Test 2', 2),
(3, 'Album Test 3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `id_category` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `Category`
--

INSERT INTO `Category` (`id_category`, `name`) VALUES
(1, 'Meute'),
(2, 'Troupe'),
(3, 'Groupe');

-- --------------------------------------------------------

--
-- Table structure for table `Miniature`
--

CREATE TABLE `Miniature` (
  `id_miniature` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL,
  `miniature_full_path` varchar(200) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `Miniature`
--

INSERT INTO `Miniature` (`id_miniature`, `id_photo`, `miniature_full_path`) VALUES
(1, 1, '/var/www/html/img/miniatures/1536955138_SANA.jpg'),
(2, 2, '/var/www/html/img/miniatures/1536955240_q2MU.jpg'),
(3, 3, '/var/www/html/img/miniatures/1536955285_0zPp.jpg'),
(4, 4, '/var/www/html/img/miniatures/1536955322_841J.jpg'),
(5, 5, '/var/www/html/img/miniatures/1536955334_In8b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Photo`
--

CREATE TABLE `Photo` (
  `id_photo` int(11) NOT NULL,
  `img_full_path` varchar(200) COLLATE utf8_bin NOT NULL,
  `id_album` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `Photo`
--

INSERT INTO `Photo` (`id_photo`, `img_full_path`, `id_album`) VALUES
(1, '/var/www/html/img/1536955138_e5U7.jpg', 1),
(2, '/var/www/html/img/1536955240_Q8YV.jpg', 1),
(3, '/var/www/html/img/1536955284_a1K0.jpg', 2),
(4, '/var/www/html/img/1536955322_Y0Ls.jpg', 3),
(5, '/var/www/html/img/1536955334_moyx.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id_user` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(120) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Album`
--
ALTER TABLE `Album`
  ADD PRIMARY KEY (`id_album`);

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `Miniature`
--
ALTER TABLE `Miniature`
  ADD PRIMARY KEY (`id_miniature`);

--
-- Indexes for table `Photo`
--
ALTER TABLE `Photo`
  ADD PRIMARY KEY (`id_photo`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Album`
--
ALTER TABLE `Album`
  MODIFY `id_album` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Miniature`
--
ALTER TABLE `Miniature`
  MODIFY `id_miniature` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Photo`
--
ALTER TABLE `Photo`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

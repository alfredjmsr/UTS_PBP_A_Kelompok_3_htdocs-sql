-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2021 at 02:43 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apptravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `id_flight` int(11) NOT NULL,
  `from_flight` varchar(30) NOT NULL,
  `to_flight` varchar(30) NOT NULL,
  `fromtime_flight` varchar(30) NOT NULL,
  `totime_flight` varchar(30) NOT NULL,
  `class_flight` varchar(30) NOT NULL,
  `price_flight` varchar(30) NOT NULL,
  `name_flight` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`id_flight`, `from_flight`, `to_flight`, `fromtime_flight`, `totime_flight`, `class_flight`, `price_flight`, `name_flight`) VALUES
(1, 'Yogyakarta', 'Bandung', '2021-11-22', '2021-11-23', 'Bisnis', '750000', 'Batikair'),
(2, 'Yogyakarta', 'Jakarta', '2021-11-22', '2021-11-22', 'Bisnis', '900000', 'Garuda'),
(3, 'Jakarta', 'Yogyakarta', '2021-11-24', '2021-11-26', 'Ekonomi', '600000', 'Batikair'),
(4, 'Yogyakarta', 'Medan', '2021-11-22', '2021-11-24', 'Ekonomi', '750000', 'Batikair'),
(5, 'Yogyakarta', 'Singapura', '2021-11-24', '2021-11-25', 'Ekonomi', '650000', 'Garuda'),
(6, 'Yogyakarta', 'Surakarta', '2022-12-11', '2022-21-11', 'Bisnis', '750000', 'Garuda'),
(7, 'Sleman', 'Depok', '2020-11-12', '2021-12-11', 'Bisnis', '100000', 'Garuda'),
(8, 'Jakarta', 'Surabaya', '2020-11-12', '2021-12-12', 'Bisnis', '120000', 'Garuda'),
(9, 'yogyakarta', 'makassar', '2021/10/25', '2021/10/25', 'Economic', '650000', 'Garuda');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `firstname_order` varchar(30) NOT NULL,
  `lastname_order` varchar(30) NOT NULL,
  `email_order` varchar(30) NOT NULL,
  `phonenumber_order` varchar(30) NOT NULL,
  `fromflight_order` varchar(30) NOT NULL,
  `toflight_order` varchar(30) NOT NULL,
  `fromflighttime_order` varchar(30) NOT NULL,
  `toflighttime_order` varchar(30) NOT NULL,
  `nameflight_order` varchar(30) NOT NULL,
  `username_order` varchar(30) NOT NULL,
  `price_order` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `firstname_order`, `lastname_order`, `email_order`, `phonenumber_order`, `fromflight_order`, `toflight_order`, `fromflighttime_order`, `toflighttime_order`, `nameflight_order`, `username_order`, `price_order`) VALUES
(25, 'kelompok1', 'uts', 'kelompok1uts@gmail.com', '082367224803', 'Yogyakarta', 'Jakarta', '2021-11-22', '2021-11-22', 'Garuda', 'kelompok1_uts', '900000'),
(26, 'kelompok1', 'uts', 'kelompok1uts@gmail.com', '082367224803', 'Surakarta', 'Yogyakarta', '2021-12-11', '2021-11-12', 'Jogjasemar', 'kelompok1_uts', '120000');

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE `trains` (
  `id_train` int(11) NOT NULL,
  `from_train` varchar(30) NOT NULL,
  `to_train` varchar(30) NOT NULL,
  `fromtime_train` varchar(30) NOT NULL,
  `totime_train` varchar(30) NOT NULL,
  `class_train` varchar(30) NOT NULL,
  `price_train` varchar(30) NOT NULL,
  `name_train` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trains`
--

INSERT INTO `trains` (`id_train`, `from_train`, `to_train`, `fromtime_train`, `totime_train`, `class_train`, `price_train`, `name_train`) VALUES
(1, 'Yogyakarta', 'KulonProgo', '2021-12-11', '2021-12-11', 'Bisnis', '13231323', 'KK'),
(2, 'Surakarta', 'Yogyakarta', '2021-12-11', '2021-11-12', 'Bisnis', '120000', 'Jogjasemar'),
(3, 'Depok', 'Klaten', '2012-22-21', '2021-11-11', 'Ekonomi', '200000', 'Depokexpress'),
(4, 'Jakarta', 'Surabaya', '2021-12-11', '2021-12-11', 'Ekonomi', '220000', 'JakartaExpress');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(40) NOT NULL,
  `password_user` varchar(40) NOT NULL,
  `role_user` int(11) NOT NULL,
  `phonenumber_user` varchar(30) NOT NULL,
  `birthdate_user` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_user`, `password_user`, `role_user`, `phonenumber_user`, `birthdate_user`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, '08237632434', '12-24-1999'),
(2, 'doni', '2da9cd653f63c010b6d6c5a5ad73fe32', 0, '08237632433', '12-24-2000'),
(5, 'rian', 'cb2b28afc2cc836b33eb7ed86f99e65a', 2, '08237632431', '12-24-2001'),
(6, 'alfred', '29cb2448018800ab65a9de297548b6e0', 2, '08237632438', '12-24-2002'),
(7, 'jojo', '5bc26c56a7abb8e921066e1a81bfd9c4', 2, '23234234', '12-12-1999'),
(8, 'x', '9dd4e461268c8034f5c8564e155c67a6', 2, 'x', 'x'),
(9, 'rei', 'bea0184aac2ef216c834b3e24a88c38e', 2, '040181040', '21-12-1999'),
(10, 'jono', '42867493d4d4874f331d288df0044baa', 2, '0946161', '12-11-1999'),
(11, 'alfred', '29cb2448018800ab65a9de297548b6e0', 2, '081242270826', '10072001'),
(12, 'alfred', '29cb2448018800ab65a9de297548b6e0', 2, '081242270826', '10072001'),
(30, 'kelompok1_uts', '34a6e5d64ade17ef4e51612c50dd72f5', 2, '0812269012', '11/10/2009');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`id_flight`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`);

--
-- Indexes for table `trains`
--
ALTER TABLE `trains`
  ADD PRIMARY KEY (`id_train`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `id_flight` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `trains`
--
ALTER TABLE `trains`
  MODIFY `id_train` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

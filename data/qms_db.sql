-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 21, 2017 at 03:41 PM
-- Server version: 5.6.35-1+deb.sury.org~xenial+0.1
-- PHP Version: 7.0.22-2+ubuntu16.04.1+deb.sury.org+4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qms`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `description`) VALUES
(1, 'testing', 'this is a testing announcement');

-- --------------------------------------------------------

--
-- Table structure for table `priority_number`
--

CREATE TABLE `priority_number` (
  `id` int(11) NOT NULL,
  `pnumber` int(4) NOT NULL,
  `is_serving` int(1) DEFAULT NULL,
  `is_done` int(1) NOT NULL,
  `assigned_teller` int(2) DEFAULT NULL,
  `is_sc` int(1) DEFAULT NULL,
  `transaction_type` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `priority_number`
--

INSERT INTO `priority_number` (`id`, `pnumber`, `is_serving`, `is_done`, `assigned_teller`, `is_sc`, `transaction_type`) VALUES
(1, 1, 1, 1, 1, 1, 2),
(2, 2, 1, 1, 2, 1, 2),
(3, 3, 1, 1, 2, 1, 2),
(4, 4, 1, 1, 1, 1, 2),
(5, 1, 1, 1, 2, 2, 7),
(6, 5, 1, 1, 1, 1, 6),
(7, 2, 1, 1, 3, 2, 1),
(8, 6, 1, 1, 2, 1, 8),
(9, 7, 1, 1, 1, 1, 6),
(10, 8, 1, 1, 2, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tellers`
--

CREATE TABLE `tellers` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `transactions` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tellers`
--

INSERT INTO `tellers` (`id`, `name`, `transactions`) VALUES
(1, 'Window 1', '1,2,6'),
(2, 'Window 2', '7,8'),
(3, 'Window 3', '1,2,6,7,8');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `name` varchar(65) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `name`) VALUES
(1, 'Water bill bill'),
(2, 'Business Permit'),
(6, 'CTC'),
(7, 'Electric Bill'),
(8, 'Cedula and papers');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(45) NOT NULL,
  `is_admin` int(1) NOT NULL,
  `teller_assigned` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `is_admin`, `teller_assigned`) VALUES
(1, 'Admin', 'Admin', 'admin', 'd66ab1349e0feb1adb42d19c605060a8', 1, NULL),
(2, 'Algie', 'Caballes', 'algie', '47032065637d9978bea8f2e144c1bc8e', 0, 1),
(3, 'Steph', 'Curry', 'steph', '6f374060aebf4d4f9a846337dd989c5a', 0, 3),
(4, 'Lebron', 'James', 'lebron', 'b4cc344d25a2efe540adbf2678e2304c', 0, 4),
(5, 'Kevin', 'Durant', 'kevin', 'd366e775160087870cdc4e87dbc30804', 0, 2),
(6, 'Adela', 'Arboleras', 'adela', '3bb739b1fb822449e8014a96b7d7a135', 0, 5),
(7, 'Rosalinda', 'Cloa', 'rosalinda', '51d2de98a7727a67b34bd5cfff5105ff', 0, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `priority_number`
--
ALTER TABLE `priority_number`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tellers`
--
ALTER TABLE `tellers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teller_assigned` (`teller_assigned`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `priority_number`
--
ALTER TABLE `priority_number`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tellers`
--
ALTER TABLE `tellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

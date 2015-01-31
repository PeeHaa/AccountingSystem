-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 31, 2015 at 06:59 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `accountingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
`id` int(11) NOT NULL,
  `accountName` varchar(255) NOT NULL,
  `accountClass` varchar(255) NOT NULL,
  `accountBalance` int(11) NOT NULL DEFAULT '0',
  `accountBalanceSide` varchar(255) DEFAULT NULL,
  `accountTotal` varchar(255) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `accountName`, `accountClass`, `accountBalance`, `accountBalanceSide`, `accountTotal`) VALUES
(1, 'Admin''s Account', 'C', 50000, 'Credit', '50000'),
(4, 'Cash Book', 'CA', 42100, 'Debit', '52100'),
(5, 'Sales', 'S', 2000, 'Credit', '2000'),
(6, 'Sales Returns', 'S', 100, 'Credit', '100'),
(13, 'Purchases', 'CoS', 10000, 'Debit', '10000');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
`id` int(11) NOT NULL,
  `transactionDescription` varchar(255) NOT NULL,
  `transactionAmount` int(11) NOT NULL,
  `transactionAccountID` int(11) NOT NULL,
  `transactionOppositeAccountID` varchar(255) NOT NULL,
  `transactionEntrySide` varchar(255) NOT NULL,
  `transactionDate` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transactionDescription`, `transactionAmount`, `transactionAccountID`, `transactionOppositeAccountID`, `transactionEntrySide`, `transactionDate`) VALUES
(13, 'Capital', 50000, 4, '1', 'Debit', '1422537703'),
(14, 'Cash', 50000, 1, '4', 'Credit', '1422537703'),
(15, 'Cash', 10000, 13, '4', 'Debit', '1422537719'),
(16, 'Purchases', 10000, 4, '13', 'Credit', '1422537719'),
(17, 'Sales', 2000, 4, '5', 'Debit', '1422537829'),
(18, 'Cash', 2000, 5, '4', 'Credit', '1422537829'),
(19, 'Sales', 100, 4, '6', 'Debit', '1422537847'),
(20, 'Cash', 100, 6, '4', 'Credit', '1422537847');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `rank`) VALUES
(1, 'Admin', '$2y$10$3XV/8aTei6haWGaHRUKLQOJI0t1tF.UlgNNJEUpaVffty1wRkm/Vy', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
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
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

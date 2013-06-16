-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2013 at 08:26 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lottery`
--

-- --------------------------------------------------------

--
-- Table structure for table `lotteries`
--

CREATE TABLE IF NOT EXISTS `lotteries` (
  `id` smallint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `reason` varchar(30) NOT NULL,
  `ticketNum` int(50) NOT NULL,
  `ticketLeft` int(30) NOT NULL,
  `ticketCost` int(30) NOT NULL,
  `forumLink` varchar(150) NOT NULL,
  `diceLink` varchar(150) NOT NULL,
  `open` tinyint(5) NOT NULL,
  `winner` int(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`reason`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE IF NOT EXISTS `refunds` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `reason` varchar(50) NOT NULL,
  `ownerID` bigint(100) NOT NULL,
  `ownerName` varchar(200) NOT NULL,
  `ticket` int(200) NOT NULL,
  `refID` int(15) NOT NULL,
  `refunded` tinyint(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `reason` varchar(50) NOT NULL,
  `refID` bigint(100) NOT NULL,
  `ownerID` bigint(100) NOT NULL,
  `ownerName` varchar(100) NOT NULL,
  `ticket` int(200) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

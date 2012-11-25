-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 12, 2012 at 09:14 PM
-- Server version: 5.1.53
-- PHP Version: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `upsmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `cp_companies`
--

CREATE TABLE IF NOT EXISTS `cp_companies` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `zip` smallint(5) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` char(2) NOT NULL,
  `website` varchar(55) NOT NULL DEFAULT '',
  `companyLogo` varchar(100) NOT NULL DEFAULT '',
  `primaryContactId` mediumint(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cp_companies`
--

INSERT INTO `cp_companies` (`id`, `name`, `zip`, `city`, `state`, `website`, `companyLogo`, `primaryContactId`) VALUES
(1, 'Vulcan Technologies', 21613, 'Cambridge', 'MD', 'http://www.vulcantechs.com', 'http://www.vulcantechnologies.com/images/vulcanLogoHome.png', 1),
(2, 'Continuous Compost', 12180, 'Troy', 'NY', 'http://www.continuouscompost.com', 'http://www.greenbuildingpro.com/images/avatar/thumb_0e962a5cb10b7feb758a728f.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `cp_employees`
--

CREATE TABLE IF NOT EXISTS `cp_employees` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `companyId` mediumint(9) NOT NULL,
  `positionId` mediumint(9) NOT NULL,
  `firstname` varchar(15) NOT NULL,
  `lastname` varchar(15) NOT NULL,
  `phone` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cp_employees`
--

INSERT INTO `cp_employees` (`id`, `companyId`, `positionId`, `firstname`, `lastname`, `phone`) VALUES
(1, 1, 1, 'Adam', 'Brooks', '443-521-6474'),
(2, 2, 0, 'William', 'Dorgan', '518-210-3041'),
(3, 1, 2, 'Ted', 'Brooks', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `cp_personbio`
--

CREATE TABLE IF NOT EXISTS `cp_personbio` (
  `employeeId` mediumint(9) NOT NULL,
  `bio` mediumtext NOT NULL,
  PRIMARY KEY (`employeeId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cp_personbio`
--

INSERT INTO `cp_personbio` (`employeeId`, `bio`) VALUES
(1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'),
(2, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'),
(3, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');

-- --------------------------------------------------------

--
-- Table structure for table `cp_positions`
--

CREATE TABLE IF NOT EXISTS `cp_positions` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cp_positions`
--

INSERT INTO `cp_positions` (`id`, `title`, `description`) VALUES
(1, 'Chief Information Officer', 'I love Information!Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type speci'),
(2, 'Chief Financial Officer', 'I love Finance!Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen ');

-- --------------------------------------------------------

--
-- Table structure for table `cp_products`
--

CREATE TABLE IF NOT EXISTS `cp_products` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `product` varchar(30) NOT NULL,
  `tier` varchar(10) NOT NULL,
  `description` tinytext NOT NULL,
  `companyId` mediumint(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cp_products`
--

INSERT INTO `cp_products` (`id`, `product`, `tier`, `description`, `companyId`) VALUES
(1, 'The Brooks Hand', 'Primary', 'This product sucks the big one!', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cp_siteinfo_about`
--

CREATE TABLE IF NOT EXISTS `cp_siteinfo_about` (
  `companyId` mediumint(9) NOT NULL,
  `missionStatement` varchar(200) NOT NULL,
  `about` mediumtext NOT NULL,
  PRIMARY KEY (`companyId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cp_siteinfo_about`
--

INSERT INTO `cp_siteinfo_about` (`companyId`, `missionStatement`, `about`) VALUES
(2, 'Our goal is to have a composter in every kitchen in in the U.S. and promote composting as the primary method of food waste disposal.', 'My name is William Dorgan and I am currently a student at Rensselaer\r\nPolytechnic Institute majoring in Design, Innovation & Society with a\r\nminor in Sustainability Studies. I was surprised by just how much food\r\nwaste was getting thrown away and I learned that composting could turn\r\nall of this food waste into valuable fertilizer thereby ""closing the\r\nnutrient cycle"" and creating a sustainable method of food waste disposal. \r\n\r\nThere was only one problem: very few people composted. There is a clear\r\nneed for a better method of composting. So I applied my design skills to\r\ncreate a composter that could be used conveniently by anyone with a kitchen.\r\n\r\nI founded ContinuousCompost in order to design, manufacture and sell this \r\ncomposter and make composting the primary method of food waste disposal\r\nin the U.S.'),
(1, 'We have none!', 'focused on using shape memory alloy wires in a partial hand prosthesis. Shape memory alloy wires when heated contract in a way similar to human muscle tissue. While the alloys are much lighter than comparable motor assemblies, they consume considerably more power which has prohibited their use in embedded systems. With recent advances in battery technology however, lightweight and efficient battery packs can be made to supply these demands.  \r\nState of the art devices place most of the heavier components towards the end of the device. This causes larger amounts of device-skin movement resulting in tissue damage to the end user. Through using shape memory alloy wires, the device’s motor assemblies can be moved around the residual limb allowing for a better anchor point and decreasing this harmful device chaffing. Currently we have demonstrated the technology’s efficacy in test bed models and are currently working towards building a unified prototype to replace fingers lost in partial hand amputees.');

-- --------------------------------------------------------

--
-- Table structure for table `cp_siteinfo_what`
--

CREATE TABLE IF NOT EXISTS `cp_siteinfo_what` (
  `companyId` mediumint(9) NOT NULL,
  `what` mediumtext NOT NULL,
  `why` mediumtext NOT NULL,
  `how` mediumtext NOT NULL,
  PRIMARY KEY (`companyId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cp_siteinfo_what`
--

INSERT INTO `cp_siteinfo_what` (`companyId`, `what`, `why`, `how`) VALUES
(1, 'We are utilizing shape memory alloy wires to build an efficient, comfortable, \r\nand biomimetic (biologically accurate) partial hand prosthetic device.', 'WHY?Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'HOW?Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'),
(2, 'Composting, in order to function optimally, needs equal parts of ""brown\r\nwaste"" and ""green waste"". Brown waste refers to carbon-heavy waste such\r\nas newspaper, cardboard, dryer lint, coffee filters and brown leaves.\r\nGreen waste refers to nitrogen-heavy waste such as vegetable scraps,\r\napple peels and grass/flower clippings.\r\n\r\nOur Composter is specifically designed to easily balance out these two\r\nparts and thus make the process of composting as easy and as convenient\r\nas possible so that anyone can do it in the comfort of their own kitchen. \r\nThe humus produced can even be used for personal gardens or donated at\r\nthe Farmer''s Market to support local organic farmers.', 'WHY?Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'HOW?Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');

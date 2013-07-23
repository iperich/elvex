-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 23, 2013 at 01:01 AM
-- Server version: 5.1.66-0+squeeze1
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `elvex`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversa`
--

CREATE TABLE IF NOT EXISTS `conversa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quien` text NOT NULL,
  `frase` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `implicancias`
--

CREATE TABLE IF NOT EXISTS `implicancias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `si` text NOT NULL,
  `entonces` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `preguntas`
--

CREATE TABLE IF NOT EXISTS `preguntas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `preguntas`
--

INSERT INTO `preguntas` (`id`, `pregunta`) VALUES
(1, 'quien'),
(2, 'como'),
(3, 'donde'),
(4, 'cuando');

-- --------------------------------------------------------

--
-- Table structure for table `sacadas`
--

CREATE TABLE IF NOT EXISTS `sacadas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concepto` text NOT NULL,
  `respuesta` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `verdades`
--

CREATE TABLE IF NOT EXISTS `verdades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frase` text NOT NULL,
  `razon_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


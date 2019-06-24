-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2019 m. Bir 24 d. 20:48
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `countriesdb`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `area` double DEFAULT NULL,
  `people_count` int(11) DEFAULT NULL,
  `post_code` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `add_date` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Sukurta duomenų kopija lentelei `city`
--

INSERT INTO `city` (`id`, `name`, `area`, `people_count`, `post_code`, `country_id`, `add_date`) VALUES
(18, 'Kaunas', 157, 286763, 'centrinis LT-44001', 31, '2019-06-19 11:45:59.000000'),
(19, 'Vilnius', 401, 536692, 'centrinis LT-01001', 31, '2019-06-21 11:46:04.000000'),
(20, 'KlaipÄ—da', 98, 149015, 'centrinis LT-91001', 31, '2019-06-21 11:46:09.000000'),
(21, 'KÄ—dainiai', 7, 23, 'centrinis LT-57001', 31, '2019-06-21 11:46:17.000000'),
(22, 'Antanavas', 0, 600, 'LT-69083', 31, '2019-06-18 11:46:33.000000');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `area` double DEFAULT NULL,
  `people_count` int(11) DEFAULT NULL,
  `phone_code` varchar(255) DEFAULT NULL,
  `add_date` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Sukurta duomenų kopija lentelei `country`
--

INSERT INTO `country` (`id`, `name`, `area`, `people_count`, `phone_code`, `add_date`) VALUES
(31, 'Lithuania', 65300, 2791133, '+370', '2019-06-19 11:30:43.000000'),
(32, 'France', 640679, 67348000, '+33', '2019-06-21 11:31:37.000000'),
(33, 'Armenia', 29743, 2924816, '+374', '2019-06-21 11:32:58.000000'),
(34, 'Estonia', 45227, 1324820, '+372', '2019-06-21 11:34:31.000000'),
(35, 'Denmark', 42933, 5806015, '+45', '2019-06-21 11:36:26.000000'),
(36, 'Sweden', 450295, 10223505, '+46', '2019-06-21 11:45:18.000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_city_country_id` (`country_id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Apribojimai eksportuotom lentelėm
--

--
-- Apribojimai lentelei `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `fk_city_country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

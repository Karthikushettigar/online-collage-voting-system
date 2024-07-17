-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jul 16, 2024 at 06:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voting_system`
--
CREATE DATABASE IF NOT EXISTS `voting_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `voting_system`;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `id` varchar(12) NOT NULL,
  `motto` text NOT NULL,
  `size` varchar(48) NOT NULL DEFAULT 'col-4 col-md-2',
  `campaign` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` varchar(12) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pfp` varchar(255) NOT NULL,
  `dept` enum('CSE Department','ISE Department','ME Department','Electrical Department','EC Department','IOT Department','AI Department','AE Department','MT Department') NOT NULL,
  `post` enum('General Secretary','Joint Secretary','Sports Secretary','Cultural Secretary','President','Vice President') NOT NULL,
  `reason` text NOT NULL,
  `cgpa` decimal(5,3) NOT NULL,
  `achieve` text NOT NULL,
  `club` text NOT NULL,
  `cert` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `comments` text NOT NULL,
  `attempts` int(1) NOT NULL,
  `voteCount` varchar(288) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `pfp`, `dept`, `post`, `reason`, `cgpa`, `achieve`, `club`, `cert`, `detail`, `status`, `comments`, `attempts`, `voteCount`) VALUES
('4mt21cs046', 'Dhanush A', '../assets/pfp/pic.jpg', 'CSE Department', 'General Secretary', 'ss', 8.200, 'pm', 'eu', '../assets/certificate/india.jpg', 'bad', 'Accepted', '', 1, 'OVY5dHJvZDk2Y0RyR1BEMVNLcWUzZz09OjpN+kQlPQVUkKbGTtUzgAxu'),
('4mt21cs064', 'Karthik U Shettigar', '../assets/pfp/photo.jpg', 'CSE Department', 'President', 'pp', 8.450, 'no', 'eu', '../assets/certificate/colorpic.jpg', 'good', 'Accepted', '', 1, 'MGExMzJ5ZmoybW5wdzNnelZ6Q0ZCUT09OjoP22pNjQncjzRXE9PZgG9z');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `sr` int(5) NOT NULL,
  `id` varchar(12) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `pw` varchar(8) NOT NULL,
  `voteStatus` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`sr`, `id`, `uname`, `pw`, `voteStatus`) VALUES
(18, 'admin', 'karthik', 'admin', 1),
(24, '4mt21cs046', 'Dhanush A', '1234', 1),
(25, '4mt21cs064', 'Karthik U Shettigar', '1234', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`sr`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `sr` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

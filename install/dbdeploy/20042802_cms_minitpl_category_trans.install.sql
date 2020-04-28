-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2020 at 12:23 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `minitemplate-web`
--

-- --------------------------------------------------------

--
-- Table structure for table `melis_cms_category_trans`
--

CREATE TABLE `melis_cms_category_trans` (
  `mtplct_id` int(11) NOT NULL,
  `mtplc_id` int(11) NOT NULL,
  `mtplct_lang_id` int(11) NOT NULL,
  `mtplct_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_cms_category_trans`
--
ALTER TABLE `melis_cms_category_trans`
  ADD PRIMARY KEY (`mtplct_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_cms_category_trans`
--
ALTER TABLE `melis_cms_category_trans`
  MODIFY `mtplct_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

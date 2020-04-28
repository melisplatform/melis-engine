-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2020 at 12:25 PM
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
-- Table structure for table `melis_cms_mini_tpl_site_category`
--

CREATE TABLE `melis_cms_mini_tpl_site_category` (
  `mtplsc_id` int(11) NOT NULL,
  `mtplsc_site_id` int(11) NOT NULL,
  `mtplsc_category_id` int(11) NOT NULL,
  `mtplc_order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_cms_mini_tpl_site_category`
--
ALTER TABLE `melis_cms_mini_tpl_site_category`
  ADD PRIMARY KEY (`mtplsc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_cms_mini_tpl_site_category`
--
ALTER TABLE `melis_cms_mini_tpl_site_category`
  MODIFY `mtplsc_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

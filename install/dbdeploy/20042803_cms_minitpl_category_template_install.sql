-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2020 at 12:24 PM
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
-- Table structure for table `melis_cms_mini_tpl_category_template`
--

CREATE TABLE `melis_cms_mini_tpl_category_template` (
  `mtplct_id` int(11) NOT NULL,
  `mtplct_category_id` int(11) NOT NULL,
  `mtplct_template_name` varchar(250) NOT NULL,
  `mtplct_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_cms_mini_tpl_category_template`
--
ALTER TABLE `melis_cms_mini_tpl_category_template`
  ADD PRIMARY KEY (`mtplct_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_cms_mini_tpl_category_template`
--
ALTER TABLE `melis_cms_mini_tpl_category_template`
  MODIFY `mtplct_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

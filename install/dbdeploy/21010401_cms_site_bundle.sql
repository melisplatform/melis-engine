-- phpMyAdmin SQL Dump
-- version 5.0.3

--
-- Table structure for table `melis_cms_site_bundle`
--

CREATE TABLE `melis_cms_site_bundle` (
  `bun_id` int(11) NOT NULL,
  `bun_site_id` int(11) NOT NULL,
  `bun_version_datetime` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_cms_site_bundle`
--
ALTER TABLE `melis_cms_site_bundle`
  ADD PRIMARY KEY (`bun_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_cms_site_bundle`
--
ALTER TABLE `melis_cms_site_bundle`
  MODIFY `bun_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

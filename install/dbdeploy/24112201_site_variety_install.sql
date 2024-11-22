-- --------------------------------------------------------

--
-- Table structure for table `melis_cms_site_varieties`
--

DROP TABLE IF EXISTS `melis_cms_site_varieties`;
CREATE TABLE `melis_cms_site_varieties` (
  `mcsv_id` int(11) NOT NULL,
  `mcsv_code` varchar(50) NOT NULL,
  `mcsv_date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `melis_cms_site_varieties`
--

INSERT INTO `melis_cms_site_varieties` (`mcsv_id`, `mcsv_code`, `mcsv_date_creation`) VALUES
(1, 'SITE', '2024-11-21 15:45:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_cms_site_varieties`
--
ALTER TABLE `melis_cms_site_varieties`
  ADD PRIMARY KEY (`mcsv_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_cms_site_varieties`
--
ALTER TABLE `melis_cms_site_varieties`
  MODIFY `mcsv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

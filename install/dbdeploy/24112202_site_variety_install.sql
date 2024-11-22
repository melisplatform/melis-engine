--
-- Table structure for table `melis_cms_site_varieties_trans`
--

DROP TABLE IF EXISTS `melis_cms_site_varieties_trans`;
CREATE TABLE `melis_cms_site_varieties_trans` (
  `mcsvt_id` int(11) NOT NULL,
  `mcsvt_mcsv_id` int(11) NOT NULL,
  `mcsvt_lang_id` int(11) NOT NULL,
  `mcsvt_variety_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `melis_cms_site_varieties_trans`
--

INSERT INTO `melis_cms_site_varieties_trans` (`mcsvt_id`, `mcsvt_mcsv_id`, `mcsvt_lang_id`, `mcsvt_variety_name`) VALUES
(1, 1, 1, 'Site'),
(2, 1, 2, 'Site');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_cms_site_varieties_trans`
--
ALTER TABLE `melis_cms_site_varieties_trans`
  ADD PRIMARY KEY (`mcsvt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_cms_site_varieties_trans`
--
ALTER TABLE `melis_cms_site_varieties_trans`
  MODIFY `mcsvt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

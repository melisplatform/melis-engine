

-- --------------------------------------------------------

--
-- Table structure for table `melis_cms_mini_tpl_flagged_template`
--

CREATE TABLE IF NOT EXISTS `melis_cms_mini_tpl_flagged_template` (
  `mtpft_id` int(11) NOT NULL,
  `mtpft_template_name` varchar(255) NOT NULL COMMENT 'the name of the updated template ',
  `mtpft_template_module` varchar(255) NOT NULL COMMENT 'the name of the module to which the template belongs'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_cms_mini_tpl_flagged_template`
--
ALTER TABLE `melis_cms_mini_tpl_flagged_template`
  ADD PRIMARY KEY (`mtpft_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_cms_mini_tpl_flagged_template`
--
ALTER TABLE `melis_cms_mini_tpl_flagged_template`
  MODIFY `mtpft_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

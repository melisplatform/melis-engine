--
-- Table structure for table `melis_cms_category`
--

CREATE TABLE `melis_cms_category` (
  `mtplc_id` int(11) NOT NULL,
  `mtplc_user_id` int(11) NOT NULL,
  `mtplc_creation_date` datetime NOT NULL,
  `mtplc_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_cms_category`
--
ALTER TABLE `melis_cms_category`
  ADD PRIMARY KEY (`mtplc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_cms_category`
--
ALTER TABLE `melis_cms_category`
  MODIFY `mtplc_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

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

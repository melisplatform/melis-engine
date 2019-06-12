--
-- Table structure for table `melis_cms_site_home`
--

CREATE TABLE IF NOT EXISTS `melis_cms_site_home` (
  `shome_id` int(11) NOT NULL AUTO_INCREMENT,
  `shome_site_id` int(11) NOT NULL,
  `shome_lang_id` int(11) NOT NULL,
  `shome_page_id` int(11) NOT NULL,
  PRIMARY KEY (`shome_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
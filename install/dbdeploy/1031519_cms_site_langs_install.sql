--
-- Table structure for table `melis_cms_site_langs`
--

CREATE TABLE IF NOT EXISTS `melis_cms_site_langs` (
  `slang_id` int(11) NOT NULL AUTO_INCREMENT,
  `slang_site_id` int(11) NOT NULL,
  `slang_lang_id` int(11) NOT NULL,
  `slang_status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`slang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Table structure for table `melis_cms_site_config`
--

CREATE TABLE IF NOT EXISTS `melis_cms_site_config` (
  `sconf_id` int(11) NOT NULL AUTO_INCREMENT,
  `sconf_site_id` int(11) NOT NULL,
  `sconf_lang_id` int(11) NOT NULL,
  `sconf_datas` longtext,
  PRIMARY KEY (`sconf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
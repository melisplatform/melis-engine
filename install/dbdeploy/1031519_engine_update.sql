--
-- Table structure for table `melis_cms_site_lang`
--

CREATE TABLE IF NOT EXISTS `melis_cms_site_langs` (
  `slang_id` int(11) NOT NULL AUTO_INCREMENT,
  `slang_site_id` int(11) NOT NULL,
  `slang_lang_id` int(11) NOT NULL,
  PRIMARY KEY (`slang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `melis_cms_site_langs` ADD `slang_status` TINYINT NOT NULL DEFAULT '1' AFTER `slang_lang_id`;

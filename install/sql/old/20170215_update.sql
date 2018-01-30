-- Updates since dec-15-2016


CREATE TABLE IF NOT EXISTS `melis_cms_page_default_urls` (
  `purl_page_id` int(11) NOT NULL,
  `purl_page_url` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE `melis_cms_page_default_urls`
  ADD PRIMARY KEY (`purl_page_id`);

ALTER TABLE `melis_cms_page_published` CHANGE `page_type` `page_type` ENUM('SITE', 'FOLDER', 'PAGE', 'NEWSLETTER') NOT NULL DEFAULT 'PAGE';
ALTER TABLE `melis_cms_page_saved` CHANGE `page_type` `page_type` ENUM('SITE', 'FOLDER', 'PAGE', 'NEWSLETTER') NOT NULL DEFAULT 'PAGE';
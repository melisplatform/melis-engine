-- -----------------------------------------------------
-- Alter Table `melis_cms_page_seo` column 'pseo_meta_title' and 'pseo_meta_description' data type from varchar to text
-- -----------------------------------------------------
ALTER TABLE `melis_cms_page_seo` CHANGE `pseo_meta_title` `pseo_meta_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `pseo_meta_description` `pseo_meta_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
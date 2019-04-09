-- -----------------------------------------------------
-- Table `melis_cms_gdpr_texts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_cms_gdpr_texts`;

CREATE TABLE IF NOT EXISTS `melis_cms_gdpr_texts` (
  `mcgdpr_text_id` INT NOT NULL AUTO_INCREMENT,
  `mcgdpr_text_site_id` INT(11) NOT NULL,
  `mcgdpr_text_lang_id` INT NOT NULL,
  `mcgdpr_text_value` LONGTEXT NOT NULL,
  PRIMARY KEY (`mcgdpr_text_id`))
ENGINE = InnoDB;

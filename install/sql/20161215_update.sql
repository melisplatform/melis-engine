-- Updates since dec-15-2016

-- -----------------------------------------------------
-- Table `melisv2`.`melis_cms_site_301`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `melisv2`.`melis_cms_site_301` (
  `s301_id` INT NOT NULL AUTO_INCREMENT COMMENT 'site redirect id',
  `s301_old_url` VARCHAR(255) NOT NULL COMMENT 'Old Site url',
  `s301_new_url` VARCHAR(255) NOT NULL COMMENT 'New Site url',
  PRIMARY KEY (`s301_id`))
ENGINE = InnoDB
COMMENT = 'Site redirect';
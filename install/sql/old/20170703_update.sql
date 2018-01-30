-- -----------------------------------------------------
-- Table `melis_cms_style`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_cms_style` ;

CREATE TABLE IF NOT EXISTS `melis_cms_style` (
  `style_id` INT(11) NOT NULL AUTO_INCREMENT,
  `style_name` VARCHAR(255) NOT NULL,
  `style_status` SMALLINT NOT NULL,
  `style_path` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`style_id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `melis_cms_page_style`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_cms_page_style` ;

CREATE TABLE IF NOT EXISTS `melis_cms_page_style` (
  `pstyle_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Table\'s primary key',
  `pstyle_page_id` INT(11) NOT NULL,
  `pstyle_style_id` INT(11) NOT NULL,
  PRIMARY KEY (`pstyle_id`))
ENGINE = InnoDB;

CREATE INDEX `fk_melis_cms_page_style_melis_cms_page_tree1_idx` ON `melis_cms_page_style` (`pstyle_page_id` ASC);

CREATE INDEX `fk_melis_cms_page_style_melis_cms_style1_idx` ON `melis_cms_page_style` (`pstyle_style_id` ASC);
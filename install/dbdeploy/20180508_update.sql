-- -----------------------------------------------------
-- Alter Table `melis_cms_page_seo` column 'pseo_meta_title' and 'pseo_meta_description' data type from varchar to text
-- -----------------------------------------------------
ALTER TABLE melis_cms_page_seo  MODIFY pseo_meta_title TEXT;
ALTER TABLE melis_cms_page_seo  MODIFY pseo_meta_description TEXT;

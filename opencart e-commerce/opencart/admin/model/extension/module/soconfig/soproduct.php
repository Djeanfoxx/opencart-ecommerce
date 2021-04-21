<?php
class ModelExtensionModuleSoconfigsoproduct extends Model {
    public function createColumnsInProducts() {

        $column_exists_tab_title = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_description LIKE 'tab_title'");

        if (!$column_exists_tab_title->num_rows) {
            $this->db->query("ALTER TABLE " . DB_PREFIX . "product_description ADD tab_title TEXT COLLATE utf8_bin NOT NULL  AFTER `meta_keyword`");
        }
        $column_exists_html_product_tab = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_description LIKE 'html_product_tab'");
        if (!$column_exists_html_product_tab->num_rows) {
            $this->db->query("ALTER TABLE " . DB_PREFIX . "product_description ADD html_product_tab TEXT COLLATE utf8_bin NOT NULL  AFTER `meta_keyword`");
        }
      
		
        $column_exists_video = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_description LIKE 'video'");
        if (!$column_exists_video->num_rows) {
            $this->db->query("ALTER TABLE " . DB_PREFIX . "product_description ADD video TEXT COLLATE utf8_bin NOT NULL  AFTER `meta_keyword`");
        }
		
		
    }
    
    
}
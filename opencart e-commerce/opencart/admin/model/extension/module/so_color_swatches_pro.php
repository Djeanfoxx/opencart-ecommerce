<?php

class ModelExtensionModuleSoColorSwatchesPro extends Model {
	public function addColumnProductImage() {
		$default_of_color = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_image LIKE 'default_of_color'");
        if (!$default_of_color->num_rows) {
            $this->db->query("ALTER TABLE " . DB_PREFIX . "product_image ADD default_of_color INT COLLATE utf8_bin NOT NULL AFTER `image`");
        }

        $color = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_image LIKE 'color'");
        if (!$color->num_rows) {
            $this->db->query("ALTER TABLE " . DB_PREFIX . "product_image ADD color INT COLLATE utf8_bin NOT NULL AFTER `default_of_color`");
        }
	}
	public function getSelectOption($type="select") {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "' AND o.`type` = '".$type."' ORDER BY o.sort_order");

		return $query->rows;
	}

	public function getColorSwatch() {
		$color_swatch = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int)$this->config->get('module_so_color_swatches_pro_option') . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order, ovd.name";
		$query = $this->db->query($sql);
		foreach ($query->rows as $option_value) {
			$color_swatch[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
			);
		}
		return $color_swatch;
	}
}
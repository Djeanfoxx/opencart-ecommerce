<?php
class ModelExtensionModuleSohomeslider extends Model {
	
	public function getListSlider($data = array()) {
		$sql = "SELECT  hs.*, hsd.* FROM " . DB_PREFIX . "so_homeslider hs LEFT JOIN " . DB_PREFIX . "so_homeslider_description hsd ON (hsd.homeslider_id = hs.id) WHERE  hs.module_id = '".$data['moduleid']."' AND hsd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND hs.status= 1";
		$sql .= " ORDER BY position ASC";
		$query = $this->db->query($sql);

		return $query->rows;
	}
}
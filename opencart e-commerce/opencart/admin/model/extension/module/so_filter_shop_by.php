<?php
class ModelExtensionModuleSofiltershopby extends Model {
	public function getModuleId() {
		$sql = " SHOW TABLE STATUS LIKE '" . DB_PREFIX . "module'" ;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getMenufacture(){
		$sql = 'SELECT * FROM `'.DB_PREFIX.'manufacturer` ORDER BY sort_order' ;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getOptions(){
		$type = "'radio','checkbox','select','image'";
		$sql = 'SELECT o.*, od.name AS option_name, od.language_id AS option_language FROM `'.DB_PREFIX.'option` AS o LEFT JOIN `'.DB_PREFIX.'option_description` AS od ON o.option_id = od.option_id WHERE o.type IN ('.$type.') AND od.language_id = "' . (int)$this->config->get('config_language_id') . '" ORDER BY o.sort_order' ;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getAttributes(){
		$sql = "SELECT *,ad.language_id AS attribute_language, (SELECT agd.name FROM `" . DB_PREFIX . "attribute_group_description` agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM `" . DB_PREFIX . "attribute` a LEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON (a.attribute_id = ad.attribute_id) LEFT JOIN `".DB_PREFIX."attribute_group` ag ON (ag.attribute_group_id = a.attribute_group_id) AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY a.attribute_id ORDER BY ag.sort_order, a.sort_order";
		$query = $this->db->query($sql);

		return $query->rows;
	}
}

?>
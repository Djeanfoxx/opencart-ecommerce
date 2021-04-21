<?php

class ModelExtensionModuleSoCountdown extends Model {
	public function getList() {
		$checktable = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."so_countdown_popup'");
		if ($checktable->num_rows == 1) {
			$now 	= date('Y-m-d H:i:s', time());
			$sql	= "SELECT cp.*, cpd.`description`, cpd.`heading_title`, cps.`link` FROM ".DB_PREFIX."so_countdown_popup cp LEFT JOIN ".DB_PREFIX."so_countdown_popup_description cpd ON (cpd.popup_id = cp.id) LEFT JOIN ".DB_PREFIX."so_countdown_popup_store cps ON (cps.popup_id = cp.id) WHERE cps.store_id = ".(int)$this->config->get('config_store_id')." AND cpd.language_id = ".(int)$this->config->get('config_language_id')." AND ((cp.date_start = '0000-00-00 00:00:00' OR cp.date_start < '".$now."') AND (cp.date_expire = '0000-00-00 00:00:00' OR cp.date_expire > '".$now."')) AND cp.`status` = 1 AND 1 = 1 GROUP BY cp.id ORDER BY cp.priority LIMIT 1";
			$query 	= $this->db->query($sql);

			return $query->row;
		}
		else
			return '';
	}
}
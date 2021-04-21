<?php

class ModelExtensionModuleSoCountdown extends Model {
	public function getTotal() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "so_countdown_popup");

		return $query->row['total'];
	}

	public function getLists($data = array()) {
		$sql = "SELECT scp.*, scpd.`description`  FROM " . DB_PREFIX . "so_countdown_popup scp 
				LEFT JOIN " . DB_PREFIX . "so_countdown_popup_description scpd ON (scp.id = scpd.popup_id) 
				WHERE scpd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sql .= " GROUP BY scp.id";

		$sort_data = array(
			'name',
			'priority'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY priority";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function add($data) {
		$sql = "INSERT INTO " . DB_PREFIX . "so_countdown_popup 
			SET status 			= '" . (int)$data['status'] . "',
			name 				= '".$this->db->escape($data['name'])."',
			priority 			= '".(int)$data['priority']."',
			width 				= '".(int)$data['width']."',
			opacity 			= '".$this->db->escape($data['opacity'])."',
			display_countdown 	= '".$data['display_countdown']."',
			image 				= '".$data['image']."',
			date_start 			= '".$this->db->escape($data['date_start'])."',
			date_expire 		= '".$this->db->escape($data['date_expire'])."'";
		
		$this->db->query($sql);

		$popup_id = $this->db->getLastId();

		if (isset($data['popup_description'])) {
			foreach ($data['popup_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "so_countdown_popup_description SET popup_id = '" . (int)$popup_id . "', language_id = '" . (int)$language_id . "', `description` = '" . $this->db->escape($value['description']) . "', `heading_title` = '" . $this->db->escape($value['heading_title']) . "'");
			}
		}

		if (isset($data['popup_store'])) {
			foreach ($data['popup_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "so_countdown_popup_store SET popup_id = '" . (int)$popup_id . "', store_id = '" . (int)$store_id . "', link = '".$data['popup_link'][$store_id]."'");
			}
		}

		$this->cache->delete('so_countdown_popup');

		return $popup_id;
	}

	public function edit($popup_id, $data) {
		$this->db->query("UPDATE ".DB_PREFIX."so_countdown_popup SET 
			status 				= '" . (int)$data['status'] . "',
			name 				= '".$this->db->escape($data['name'])."',
			priority 			= '".(int)$data['priority']."',
			width 				= '".(int)$data['width']."',
			opacity 			= '".$this->db->escape($data['opacity'])."',
			display_countdown 	= '".$data['display_countdown']."',
			image 				= '".$data['image']."',
			date_start 			= '".$this->db->escape($data['date_start'])."',
			date_expire 		= '".$this->db->escape($data['date_expire'])."' WHERE id = ".$popup_id."
		");

		$this->db->query("DELETE FROM " . DB_PREFIX . "so_countdown_popup_description WHERE popup_id = '" . (int)$popup_id . "'");
		if (isset($data['popup_description'])) {
			foreach ($data['popup_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "so_countdown_popup_description SET popup_id = '" . (int)$popup_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "', heading_title = '" . $this->db->escape($value['heading_title']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "so_countdown_popup_store WHERE popup_id = '" . (int)$popup_id . "'");
		if (isset($data['popup_store'])) {
			foreach ($data['popup_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "so_countdown_popup_store SET popup_id = '" . (int)$popup_id . "', store_id = '" . (int)$store_id . "', link = '".$data['popup_link'][$store_id]."'");
			}
		}

		$this->cache->delete('so_countdown_popup');
	}

	public function delete($popup_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "so_countdown_popup_description WHERE popup_id = '" . (int)$popup_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "so_countdown_popup_store WHERE popup_id = '" . (int)$popup_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "so_countdown_popup WHERE id = '" . (int)$popup_id . "'");
		
		$this->cache->delete('so_countdown_popup');
	}

	public function getPopup($popup_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "so_countdown_popup cp 
				LEFT JOIN " . DB_PREFIX . "so_countdown_popup_description cpd ON (cp.id = cpd.popup_id) 
				WHERE cpd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cp.id = ".$popup_id."
				GROUP BY cp.id
		");

		return $query->row;
	}

	function getPopupStoresId($popup_id) {
		$store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "so_countdown_popup_store WHERE popup_id = '" . (int)$popup_id . "'");

		foreach ($query->rows as $result) {
			$store_data[] = $result['store_id'];
		}

		return $store_data;
	}

	function getPopupStores($popup_id) {
		$popup_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "so_countdown_popup_store WHERE popup_id = '" . (int)$popup_id . "'");
		foreach ($query->rows as $result) {
			$popup_data[$result['store_id']] = $result;
		}
		return $popup_data;
	}

	public function getPopupDescriptions($popup_id) {
		$description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "so_countdown_popup_description WHERE popup_id = '" . (int)$popup_id . "'");

		foreach ($query->rows as $result) {
			$description_data[$result['language_id']] = array(
				'description'          => $result['description'],
				'heading_title'        => $result['heading_title']
			);
		}

		return $description_data;
	}

	public function is_table_exist($table){
        $query = $this->db->query("SHOW TABLES LIKE '".$table."'");
        if( count($query->rows) <= 0 ) {
            return true;
        }
        return false;
    }

	public function install() {
		if($this->is_table_exist(DB_PREFIX . "so_countdown_popup")) {
			$this->db->query("
				CREATE TABLE `".DB_PREFIX."so_countdown_popup` (
				  	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  	`status` tinyint(1) NOT NULL,
				  	`name` varchar(255) NOT NULL,
				  	`priority` int(11) NOT NULL,
				  	`width` int(11) NOT NULL,
				  	`height` int(11) NOT NULL,
				  	`opacity` char(3) NOT NULL,
				  	`display_countdown` tinyint(1) NOT NULL,
				  	`date_start` datetime NOT NULL,
				  	`date_expire` datetime NOT NULL,
				  	`image` varchar(500) NOT NULL,
				  	PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
			");
		}

		if($this->is_table_exist(DB_PREFIX . "so_countdown_popup_description")) {
			$this->db->query("
				CREATE TABLE `".DB_PREFIX."so_countdown_popup_description` (
				  	`language_id` int(11) NOT NULL,
				  	`popup_id` int(11) NOT NULL,
				  	`description` text NOT NULL,
				  	`heading_title` varchar(500) DEFAULT NULL,
				  	PRIMARY KEY (`language_id`,`popup_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			");
		}

		if($this->is_table_exist(DB_PREFIX . "so_countdown_popup_store")) {
			$this->db->query("
				CREATE TABLE `".DB_PREFIX."so_countdown_popup_store` (
				  	`popup_id` int(11) NOT NULL,
				  	`store_id` int(11) NOT NULL,
				  	`link` varchar(500) DEFAULT NULL,
				  	PRIMARY KEY (`popup_id`,`store_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			");
		}
	}

	public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "so_countdown_popup_store`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "so_countdown_popup_description`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "so_countdown_popup`");
    }
}
<?php

class ModelExtensionModuleSolatestblog extends Model {
	
	public function getModuleId() {
		$sql = " SHOW TABLE STATUS LIKE '" . DB_PREFIX . "module'" ;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getCategories($data = array()) {
		$category_data = array();
		$sql = "SELECT * FROM `" . DB_PREFIX . "simple_blog_category` sc LEFT JOIN `" . DB_PREFIX . "simple_blog_category_description` scd ON (sc.simple_blog_category_id = scd.simple_blog_category_id) WHERE sc.parent_id = '" . (int)$data['parent_id'] . "' AND scd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND sc.status = '1'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND scd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		 //ORDER BY c.sort_order, cd.name ASC
		
		$sort_data = array(
			'scd.name',
			'sc.sort_order',
			'sc.status'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY scd.name";	
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
	
		foreach ($query->rows as $result) {
			$category_data[] = array(
				'simple_blog_category_id' 	=> $result['simple_blog_category_id'],
				'name'        				=> $this->getPath($result['simple_blog_category_id'], $this->config->get('config_language_id')),
				'status'  	  				=> $result['status'],
				'sort_order'  				=> $result['sort_order']
			);
			$filter_data = array(
				'parent_id'	  	=> $result['simple_blog_category_id'],
			);
			$category_data = array_merge($category_data, $this->getCategories($filter_data));
		}	
		
		return $category_data;
	}
	public function getPath($simple_blog_category_id) {
		$query = $this->db->query("SELECT scd.name AS name, sc.parent_id AS parent_id FROM " . DB_PREFIX . "simple_blog_category sc LEFT JOIN " . DB_PREFIX . "simple_blog_category_description scd ON (sc.simple_blog_category_id = scd.simple_blog_category_id) WHERE sc.simple_blog_category_id = '" . (int)$simple_blog_category_id . "' AND scd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sc.sort_order, scd.name ASC");
		
		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . '&nbsp;&nbsp;&gt;&nbsp;&nbsp;' . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}
}
?>
<?php
	class ModelExtensionModuleSimpleBlogCategory extends Model {
		
		public function addCategory($data) {
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_category` SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', external_link = '" . $this->db->escape($data['external_link']) . "', blog_category_column = '" . (int)$data['blog_category_column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");
			
			$simple_blog_category_id = $this->db->getLastId();
			
			if (isset($data['image'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "simple_blog_category` SET image = '" . $this->db->escape($data['image']) . "' WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			}
			
			// if ($data['keyword']) {
			// 	$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET query = 'simple_blog_category_id=" . (int)$simple_blog_category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			// }
			
			foreach ($data['category_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_category_description` SET simple_blog_category_id = '" . (int)$simple_blog_category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
			}
			
			if (isset($data['category_store'])) {
				foreach ($data['category_store'] as $store_id) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_category_to_store` SET simple_blog_category_id = '" . (int)$simple_blog_category_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
			
			if (isset($data['category_layout'])) {
				foreach ($data['category_layout'] as $store_id => $layout) {
					if ($layout['layout_id']) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_category_to_layout` SET simple_blog_category_id = '" . (int)$simple_blog_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}

			// SEO URL
			if (isset($data['seo_url'])) {
				foreach ($data['seo_url'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (trim($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'simple_blog_category_id=" . (int)$simple_blog_category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
			
			$this->cache->delete('simple_blog_category');			
		}
		
		public function editCategory($simple_blog_category_id, $data) {
			
			$this->db->query("UPDATE `" . DB_PREFIX . "simple_blog_category` SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', external_link = '" . $this->db->escape($data['external_link']) . "', blog_category_column = '" . (int)$data['blog_category_column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			
			if (isset($data['image'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "simple_blog_category` SET image = '" . $this->db->escape($data['image']) . "' WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			}
			
			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'simple_blog_category_id=" . (int)$simple_blog_category_id. "'");
		
			// if ($data['keyword']) {
			// 	$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET query = 'simple_blog_category_id=" . (int)$simple_blog_category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			// }		
			
			$this->db->query("DELETE FROM `" . DB_PREFIX . "simple_blog_category_description` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");

			foreach ($data['category_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_category_description` SET simple_blog_category_id = '" . (int)$simple_blog_category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
			}
			
			$this->db->query("DELETE FROM `" . DB_PREFIX . "simple_blog_category_to_store` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
		
			if (isset($data['category_store'])) {		
				foreach ($data['category_store'] as $store_id) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_category_to_store` SET simple_blog_category_id = '" . (int)$simple_blog_category_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
			
			$this->db->query("DELETE FROM `" . DB_PREFIX . "simple_blog_category_to_layout` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");

			if (isset($data['category_layout'])) {
				foreach ($data['category_layout'] as $store_id => $layout) {
					if ($layout['layout_id']) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_category_to_layout` SET simple_blog_category_id = '" . (int)$simple_blog_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}

			// SEO URL
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'simple_blog_category_id=" . (int)$simple_blog_category_id . "'");
			
			if (isset($data['seo_url'])) {
				foreach ($data['seo_url']as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (trim($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'simple_blog_category_id=" . (int)$simple_blog_category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
			
			$this->cache->delete('simple_blog_category');		
		}		
		
		public function deleteCategory($simple_blog_category_id) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "simple_blog_category` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "simple_blog_category_description` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "simple_blog_category_to_store` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "simple_blog_category_to_layout` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'simple_blog_category_id=" . (int)$simple_blog_category_id . "'");
			
			$query = $this->db->query("SELECT simple_blog_category_id FROM `" . DB_PREFIX . "simple_blog_category` WHERE parent_id = '" . (int)$simple_blog_category_id . "'");
	
			foreach ($query->rows as $result) {
				$this->deleteCategory($result['simple_blog_category_id']);
			}
			
			$this->cache->delete('simple_blog_category');
		}
		
		public function getCategory($simple_blog_category_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "simple_blog_category` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");		
			return $query->row;
		}		
		
		public function getTotalCategories($data = array()) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(sbc.simple_blog_category_id)) AS total FROM `" . DB_PREFIX . "simple_blog_category` sbc LEFT JOIN `" . DB_PREFIX . "simple_blog_category_description` sbcd ON(sbc.simple_blog_category_id=sbcd.simple_blog_category_id) WHERE sbcd.language_id='" . (int)$this->config->get('config_language_id') . "'");
			return $sql->row['total'];
		}
		
		public function getCategories($parent_id = 0) {
			$category_data = array();
				
			$sql = "SELECT * FROM `" . DB_PREFIX . "simple_blog_category` sc LEFT JOIN `" . DB_PREFIX . "simple_blog_category_description` scd ON (sc.simple_blog_category_id = scd.simple_blog_category_id) WHERE sc.parent_id = '" . (int)$parent_id . "' AND scd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
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
					'name'        		=> $this->getPath($result['simple_blog_category_id'], $this->config->get('config_language_id')),
					'status'  	  		=> $result['status'],
					'sort_order'  		=> $result['sort_order']
				);
			
				$category_data = array_merge($category_data, $this->getCategories($result['simple_blog_category_id']));
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
		
		public function getCategoryDescriptions($simple_blog_category_id) {
			$simple_category_description_data = array();
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_category_description` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			
			foreach ($query->rows as $result) {
				$simple_category_description_data[$result['language_id']] = array(
					'name'             => $result['name'],
					'meta_keyword'     => $result['meta_keyword'],
					'meta_description' => $result['meta_description'],
					'description'      => $result['description']
				);
			}
			
			return $simple_category_description_data;
		}		
		
		public function getCategoryStores($simple_blog_category_id) {
			$simple_category_store_data = array();
		
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_category_to_store` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
	
			foreach ($sql->rows as $result) {
				$simple_category_store_data[] = $result['store_id'];
			}
			
			return $simple_category_store_data;
		}
		
		public function getCategoryLayouts($simple_blog_category_id) {
			$simple_category_layout_data = array();
			
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_category_to_layout` WHERE simple_blog_category_id = '" . (int)$simple_blog_category_id . "'");
			
			foreach ($sql->rows as $result) {
				$simple_category_layout_data[$result['store_id']] = $result['layout_id'];
			}
			
			return $simple_category_layout_data;
		}		
		
		public function getTotalArticleCategoryWise($simple_blog_category_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_article_to_category` WHERE simple_blog_category_id='" . (int)$simple_blog_category_id . "'");
			
			return $sql->num_rows;
		}

		public function getSeoUrls($simple_blog_category_id) {
			$seo_url_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'simple_blog_category_id=" . (int)$simple_blog_category_id . "'");

			foreach ($query->rows as $result) {
				$seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
			}

			return $seo_url_data;
		}
		
	}
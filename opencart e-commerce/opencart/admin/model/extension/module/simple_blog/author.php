<?php
	class ModelExtensionModuleSimpleBlogAuthor extends Model {
		
		public function addAuthor($data) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_author` SET name = '" . $this->db->escape($data['name']) . "', status='" . (int)$data['status'] . "', date_added=NOW(), date_modified=NOW()");
			
			$simple_blog_author_id = $this->db->getLastId();
			
			if (isset($data['image'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "simple_blog_author` SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE simple_blog_author_id = '" . (int)$simple_blog_author_id . "'");
			}
			
			// if ($data['keyword']) {
			// 	$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'simple_blog_author_id=" . (int)$simple_blog_author_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			// }

			// SEO URL
			if (isset($data['seo_url'])) {
				foreach ($data['seo_url'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (trim($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'simple_blog_author_id=" . (int)$simple_blog_author_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
			
			foreach ($data['author_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "simple_blog_author_description SET simple_blog_author_id = '" . (int)$simple_blog_author_id . "', language_id = '" . (int)$language_id . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'"); // ctitle = '" . $this->db->escape($value['ctitle']) . "',
			}
			
			$this->cache->delete('simple_blog_author');			
		}
		
		public function editAuthor($simple_blog_author_id, $data) {
			$this->db->query("UPDATE `" . DB_PREFIX . "simple_blog_author` SET name = '" . $this->db->escape($data['name']) . "', status='" . (int)$data['status'] . "', date_modified=NOW() WHERE simple_blog_author_id='" . (int)$simple_blog_author_id . "'");
			
			if (isset($data['image'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "simple_blog_author` SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE simple_blog_author_id = '" . (int)$simple_blog_author_id . "'");
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'simple_blog_author_id=" . (int)$simple_blog_author_id . "'");

			// if ($data['keyword']) {
			// 	$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'simple_blog_author_id=" . (int)$simple_blog_author_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			// }

			// SEO URL
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'simple_blog_author_id=" . (int)$simple_blog_author_id . "'");
			
			if (isset($data['seo_url'])) {
				foreach ($data['seo_url']as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (trim($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'simple_blog_author_id=" . (int)$simple_blog_author_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "simple_blog_author_description WHERE simple_blog_author_id = '" . (int)$simple_blog_author_id . "'");
			
			foreach ($data['author_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "simple_blog_author_description SET simple_blog_author_id = '" . (int)$simple_blog_author_id . "', language_id = '" . (int)$language_id . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'"); // ctitle = '" . $this->db->escape($value['ctitle']) . "',
			}
			
			$this->cache->delete('simple_blog_author');			
		}
		
		public function deleteAuthor($simple_blog_manager_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "simple_blog_author WHERE simple_blog_author_id = '" . (int)$simple_blog_manager_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "simple_blog_author_description WHERE simple_blog_author_id = '" . (int)$simple_blog_manager_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'simple_blog_author_id=" . (int)$simple_blog_manager_id . "'");
	
			$this->cache->delete('blog_author');
		}
		
		public function getAuthor($simple_blog_author_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "simple_blog_author` WHERE simple_blog_author_id = '" . (int)$simple_blog_author_id . "'");	
			return $query->row;
		}		
		
		public function getTotalAuthors($data = array()) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(sba.simple_blog_author_id)) AS total FROM `" . DB_PREFIX . "simple_blog_author` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_author_description` sbad ON(sba.simple_blog_author_id=sbad.simple_blog_author_id) WHERE sbad.language_id='" . (int)$this->config->get('config_language_id') . "'");
			return $sql->row['total'];
		}
		
		public function getAuthors($data = array()) {
			$sql = "SELECT sba.* FROM `" . DB_PREFIX . "simple_blog_author` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_author_description` sbad ON(sba.simple_blog_author_id=sbad.simple_blog_author_id) WHERE sbad.language_id='" . (int)$this->config->get('config_language_id') . "'";
			
			if(isset($data['filter_author']) && $data['filter_author'] != '') {
				$sql .= " AND sba.name LIKE '" . $this->db->escape($data['filter_author']) . "%'";
			}
			
			$sort_data = array(
				'sba.name',
				'sba.status',
				'sba.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sba.name";	
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
		
		public function getAuthorDescriptions($simple_blog_author_id) {
			$simple_author_description_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "simple_blog_author_description WHERE simple_blog_author_id = '" . (int)$simple_blog_author_id . "'");
			
			foreach ($query->rows as $result) {
				$simple_author_description_data[$result['language_id']] = array(
					'meta_keyword'     => $result['meta_keyword'],
					'meta_description' => $result['meta_description'],
					'description'      => $result['description']
				);
			}
			
			return $simple_author_description_data;
		}
		
		public function getAuthorName($simple_blog_author_id) {
			$sql = $this->db->query("SELECT name FROM `" . DB_PREFIX . "simple_blog_author` WHERE simple_blog_author_id='" . (int)$simple_blog_author_id . "'");
			return $sql->row['name'];
		}

		public function checkAuthorName($name, $simple_blog_author_id = 0) {
			if(!$simple_blog_author_id) {
				$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_author` WHERE LCASE(name) = '" . $this->db->escape(utf8_strtolower($name)) . "'");
				return $sql->num_rows;
			} else {
				$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_author` WHERE LCASE(name) = '" . $this->db->escape(utf8_strtolower($name)) . "' AND simple_blog_author_id <> '" . (int)$simple_blog_author_id . "'");
				return $sql->num_rows;
			}
		}
		
		public function getTotalArticleByAuthorId($simple_blog_author_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_article` WHERE simple_blog_author_id='" . (int)$simple_blog_author_id . "'");
			return $sql->num_rows;
		}		

		public function getSeoUrls($simple_blog_author_id) {
			$seo_url_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'simple_blog_author_id=" . (int)$simple_blog_author_id . "'");

			foreach ($query->rows as $result) {
				$seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
			}

			return $seo_url_data;
		}
	}
?>
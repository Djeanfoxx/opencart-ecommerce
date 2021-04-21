<?php
	class ModelExtensionModuleSimpleBlogReport extends Model {
		public function getTotalBlogViewed($data = array()) {
			$sql = "SELECT COUNT(DISTINCT(sbv.simple_blog_view_id)) AS total FROM `" . DB_PREFIX . "simple_blog_view` sbv LEFT JOIN `" . DB_PREFIX . "simple_blog_article` sba ON(sbv.simple_blog_article_id=sba.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sbad.language_id='" . (int)$this->config->get('config_language_id') . "' AND sbv.view > 0";
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(sbv.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
	
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(sbv.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];			
		}
		
		public function getTotalBlogViews($data = array()) {
			$sql = "SELECT SUM(sbv.view) AS total FROM `" . DB_PREFIX . "simple_blog_view` sbv LEFT JOIN `" . DB_PREFIX . "simple_blog_article` sba ON(sbv.simple_blog_article_id=sba.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sbad.language_id='" . (int)$this->config->get('config_language_id') . "'";
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];			
		}
		
		public function getBlogViewed($data = array()) {
			$sql = "SELECT sbv.*, sbad.article_title AS article_title, sbau.name AS author_name FROM `" . DB_PREFIX . "simple_blog_view` sbv LEFT JOIN `" . DB_PREFIX . "simple_blog_article` sba ON(sbv.simple_blog_article_id=sba.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sbad.language_id='" . (int)$this->config->get('config_language_id') . "'";
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(sbv.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
	
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(sbv.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			$sql .= " GROUP BY sbv.simple_blog_article_id";
			
			$sort_data = array(
				'sbv.view',
				'sba.article_title',
				'sbau.name'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY sbv.view";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
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
	}
<?php
	class ModelExtensionSimpleBlogArticle extends Model {
		
		public function getTotalArticle($data = array()) {
			
			$sql = "SELECT COUNT(DISTINCT(sba.simple_blog_article_id)) AS total FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "'";
			
			if(!empty($data['blog_search'])) {
				$sql .= " AND LCASE(sbad.article_title) LIKE '" . $this->db->escape(utf8_strtolower($data['blog_search'])) . "%'";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function getArticles($data = array()) {
			
			$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . (int)$this->config->get('config_language_id') . "'";
			
			if(!empty($data['blog_search'])) {
				$sql .= " AND LCASE(sbad.article_title) LIKE '" . $this->db->escape(utf8_strtolower($data['blog_search'])) . "%'";
			}
			
			$sql .= " ORDER BY sba.date_modified DESC";
			
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
		
		public function getTotalCategories($parent_id = 0) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(sbc.simple_blog_category_id)) AS total FROM `" . DB_PREFIX . "simple_blog_category` sbc LEFT JOIN `" . DB_PREFIX . "simple_blog_category_description` sbcd ON(sbc.simple_blog_category_id=sbcd.simple_blog_category_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_category_to_store` sbcs ON(sbc.simple_blog_category_id=sbcs.simple_blog_category_id) WHERE sbc.parent_id='" . (int)$parent_id . "' AND sbcd.language_id='" . (int)$this->config->get('config_language_id') . "' AND sbcs.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbc.status=1 ORDER BY sbc.sort_order, LCASE(sbcd.name)");
			
			return $sql->row['total'];
		}
		
		public function getCategories($parent_id = 0) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_category` sbc LEFT JOIN `" . DB_PREFIX . "simple_blog_category_description` sbcd ON(sbc.simple_blog_category_id=sbcd.simple_blog_category_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_category_to_store` sbcs ON(sbc.simple_blog_category_id=sbcs.simple_blog_category_id) WHERE sbc.parent_id='" . (int)$parent_id . "' AND sbcd.language_id='" . (int)$this->config->get('config_language_id') . "' AND sbcs.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbc.status=1 ORDER BY sbc.sort_order, LCASE(sbcd.name)");
            return $sql->rows;
		}
		
		public function getTotalArticles($simple_blog_category_id) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(simple_blog_article_id)) AS total FROM `" . DB_PREFIX . "simple_blog_article_to_category` WHERE simple_blog_category_id='" . (int)$simple_blog_category_id . "'");
			return $sql->row['total'];
		}	
		
		public function getTotalComments($simple_blog_article_id) {
			$sql = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "simple_blog_comment` WHERE simple_blog_article_id='" . (int)$simple_blog_article_id . "' AND status=1");
			return $sql->row['total'];
		}
		
		public function getAdditionalDescription($blog_article_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_article_description_additional` WHERE blog_article_id='" . (int)$blog_article_id . "'");
			return $sql->rows;	
		}	
		
		public function getArticle($simple_blog_article_id) {
			$sql = $this->db->query("SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sba.simple_blog_article_id='" . (int)$simple_blog_article_id . "' AND sbau.status=1 AND sba.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'");
			return $sql->row;
		}
		
		public function addBlogView($simple_blog_article_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_view` WHERE simple_blog_article_id='" . (int)$simple_blog_article_id . "'");
			
			if($sql->num_rows) {
				$counter = $sql->row['view'];
				
				$counter++;
				
				$this->db->query("UPDATE `" . DB_PREFIX . "simple_blog_view` SET view='" . (int)$counter . "', date_modified=NOW() WHERE simple_blog_article_id='" . (int)$simple_blog_article_id . "'");
				
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_view` SET simple_blog_article_id='" . (int)$simple_blog_article_id . "', view=1, date_added=NOW(), date_modified=NOW()");
			}
		}
		
		public function getArticleAdditionalDescription($simple_blog_article_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_article_description_additional` WHERE language_id='" . (int)$this->config->get('config_language_id') . "' AND simple_blog_article_id='" . (int)$simple_blog_article_id . "'");
			return $sql->rows;
		}
		
		public function getArticleProductRelated($simple_blog_article_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_article_product_related` WHERE simple_blog_article_id='" . (int)$simple_blog_article_id . "'");
			return $sql->rows;
		}
		
		public function getTotalCommentsByArticleId($simple_blog_article_id) {
			$sql = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "simple_blog_comment` WHERE simple_blog_article_id='" . (int)$simple_blog_article_id . "' AND status=1 AND simple_blog_article_reply_id=0");
			return $sql->row['total'];
		}
		
		public function getCommentsByArticle($simple_blog_article_id, $start = 0, $limit = 20, $simple_blog_comment_id = 0) {
			if(!$simple_blog_comment_id) {
				
				if ($start < 0) {
					$start = 0;
				}
				
				if ($limit < 1) {
					$limit = 20;
				}
				
				$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_comment` WHERE simple_blog_article_id='" . (int)$simple_blog_article_id . "' AND status=1 AND simple_blog_article_reply_id='0' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
				return $sql->rows;
			} else {
						
				if ($start < 0) {
					$start = 0;
				}
				
				if ($limit < 1) {
					$limit = 1000;
				}	
				
				$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_comment` WHERE simple_blog_article_reply_id='" . (int)$simple_blog_comment_id . "' AND status=1 ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
				return $sql->rows;
			}
		}
		
		public function addArticleComment($simple_blog_article_id, $data) {
					
			if($this->config->get('simple_blog_comment_auto_approval')) {
				$status = 1;
			} else {
				$status = 0;
			}
			
			if($data['reply_id']) {
				//echo "INSERT INTO `" . DB_PREFIX . "simple_blog_comment` SET simple_blog_article_id='" . (int)$simple_blog_article_id . "', simple_blog_article_reply_id='" . (int)$data['reply_id'] . "', author='" . $this->db->escape($data['name']) . "', comment='" . $this->db->escape($data['text']) . "', status='" . (int)$status . "', date_added=NOW(), date_modified=NOW()";
				//echo $data['reply_id']; exit;
				$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_comment` SET simple_blog_article_id='" . (int)$simple_blog_article_id . "', simple_blog_article_reply_id='" . (int)$data['reply_id'] . "', author='" . $this->db->escape($data['name']) . "', comment='" . $this->db->escape($data['text']) . "', status='" . (int)$status . "', date_added=NOW(), date_modified=NOW()");
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "simple_blog_comment` SET simple_blog_article_id='" . (int)$simple_blog_article_id . "', author='" . $this->db->escape($data['name']) . "', comment='" . $this->db->escape($data['text']) . "', status='" . (int)$status . "', date_added=NOW(), date_modified=NOW()");
			}
		}
		
		public function getCategory($simple_blog_category_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "simple_blog_category` sbc LEFT JOIN `" . DB_PREFIX . "simple_blog_category_description` sbcd ON (sbc.simple_blog_category_id = sbcd.simple_blog_category_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_category_to_store` sbcs ON (sbc.simple_blog_category_id = sbcs.simple_blog_category_id) WHERE sbc.simple_blog_category_id = '" . (int)$simple_blog_category_id . "' AND sbcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND sbcs.store_id = '" . (int)$this->config->get('config_store_id') . "' AND sbc.status = '1'");
			
			return $query->row;
		}	
		
		public function getTotalArticleCategoryWise($data = array()) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(sba.simple_blog_article_id)) AS total FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_category` sbac ON(sba.simple_blog_article_id=sbac.simple_blog_article_id) WHERE sbac.simple_blog_category_id='" . (int)$data['simple_blog_article_id'] . "' AND sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'");
			
			return $sql->row['total'];
		}
		
		public function getArticleCategoryWise($data = array()) {
			$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_category` sbac ON(sba.simple_blog_article_id=sbac.simple_blog_article_id) WHERE sbac.simple_blog_category_id='" . (int)$data['simple_blog_article_id'] . "' AND sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "' ORDER BY sba.date_modified DESC";
			
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
		
		public function getTotalArticleAuthorWise($simple_blog_author_id) {
			$sql = $this->db->query("SELECT COUNT(DISTINCT(sba.simple_blog_article_id)) AS total FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sba.simple_blog_author_id='" . (int)$simple_blog_author_id . "' AND sba.status=1 AND sbau.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'");
			
			return $sql->row['total'];
		}
		
		public function getArticleAuthorWise($data = array()) {
			$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sba.simple_blog_author_id='" . (int)$data['simple_blog_author_id'] . "' AND sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "' ORDER BY sba.date_modified DESC";
			
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
		
		public function getAuthorInformation($simple_blog_author_id) {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_author` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_author_description` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sba.simple_blog_author_id='" . (int)$simple_blog_author_id . "' AND sba.status=1 AND sbau.language_id='" . $this->config->get('config_language_id') . "'");
			return $sql->row;
		}
		
		public function getArticleModuleWise($data = array()) {
			$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_category` sbac ON(sba.simple_blog_article_id=sbac.simple_blog_article_id) WHERE sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'";
			
			if(!empty($data['filter_category_id'])) {
				$sql .= " AND sbac.simple_blog_category_id='" . (int)$data['filter_category_id'] . "'";
			}
			
			$sql .= " GROUP BY sba.simple_blog_article_id ORDER BY sba.date_added DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
	
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			//echo $sql; exit;
			
			$query = $this->db->query($sql);
			
			return $query->rows;	
		}
		
		public function getPopularArticlesModuleWise($data = array()) {
					
			$sql = "SELECT * FROM `" . DB_PREFIX . "simple_blog_view`";
			
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
			
			if($query->num_rows) {
				$sql = "SELECT sba.*, sbad.*, sbau.name AS author_name FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_store` sbas ON(sba.simple_blog_article_id=sbas.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_article_to_category` sbac ON(sba.simple_blog_article_id=sbac.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_view` sbv ON(sbv.simple_blog_article_id=sba.simple_blog_article_id) WHERE sba.status=1 AND sbau.status=1 AND sbas.store_id='" . (int)$this->config->get('config_store_id') . "' AND sbad.language_id='" . $this->config->get('config_language_id') . "'";
			
				$sql .= "  GROUP BY sba.simple_blog_article_id ORDER BY sbv.view DESC";
				
				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}				
		
					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}	
		
					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}	
				
				//echo $sql; exit;
				
				$my_query = $this->db->query($sql);
			
				return $my_query->rows;
			} else {
				return '';
			}
			
		}
		
		public function getRelatedArticles($simple_blog_article_id) {
					
			$this->load->model('tool/image');	
			
			$simple_blog_related_article_data = array();	
			
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "simple_blog_related_article` WHERE simple_blog_article_id='" . (int)$simple_blog_article_id . "' AND status=1 ORDER BY sort_order");
			
			foreach($sql->rows as $row) {
				$article_info = $this->db->query("SELECT sba.*, sbad.article_title AS article_title, sbad.description AS description, sbau.simple_blog_author_id AS simple_blog_author_id, sbau.name AS author_name FROM `" . DB_PREFIX . "simple_blog_article` sba LEFT JOIN `" . DB_PREFIX . "simple_blog_article_description` sbad ON(sba.simple_blog_article_id=sbad.simple_blog_article_id) LEFT JOIN `" . DB_PREFIX . "simple_blog_author` sbau ON(sba.simple_blog_author_id=sbau.simple_blog_author_id) WHERE sba.simple_blog_article_id='" . (int)$row['simple_blog_article_related_id'] . "' AND sbad.language_id='" . (int)$this->config->get('config_language_id') . "' AND sba.status=1 AND sbau.status=1");
				
				if($article_info->row) {
					
					$total_comment = $this->getTotalComments($row['simple_blog_article_related_id']);
					
					$image = $this->model_tool_image->resize($article_info->row['featured_image'], 150, 150);
					
					$simple_blog_related_article_data[] = array(
						'simple_blog_article_id'	=> $article_info->row['simple_blog_article_id'],
						'article_title'		=> $article_info->row['article_title'],
						'simple_blog_author_id'	=> $article_info->row['simple_blog_author_id'],
						'image'				=> $image,
						'description'		=> $article_info->row['description'],
						'author_name'		=> $article_info->row['author_name'],
						'date_added'		=> date('F jS, Y', strtotime($article_info->row['date_added'])),
						'date_modified'		=> date('F jS, Y', strtotime($article_info->row['date_modified'])),
						'total_comment'		=> $total_comment
					);	
				}				
			}
			
			
			return $simple_blog_related_article_data;
		}
		
		public function getAuthors() {
			$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_author` WHERE status=1");
			
			return $sql->rows;
		}
		
	}
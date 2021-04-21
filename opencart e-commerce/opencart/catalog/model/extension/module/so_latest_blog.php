<?php
class ModelExtensionModuleSolatestblog extends Model {
	
	public function getListBlogs( $data ){
		$sql_ = $this->db->query("SELECT DISTINCT(simple_blog_article_id) FROM `" . DB_PREFIX . "simple_blog_article_to_category` WHERE simple_blog_category_id IN(".$data['category_id'].")");
		$listBlog 	= array();
		$blogs 		= array();
		if(count($sql_->rows) && $sql_->rows != "")
		{
			foreach($sql_->rows as $item)
			{
				$listBlog[] = $item['simple_blog_article_id'];
			}
		}
		if(count($listBlog) && $listBlog != "")
		{
			$sql = ' SELECT b.*,bd.article_title,bd.description, (SELECT COUNT(*) FROM '.DB_PREFIX.'simple_blog_comment bc WHERE bc.simple_blog_article_id = b.simple_blog_article_id) AS comment,(SELECT COUNT(*) FROM '.DB_PREFIX.'simple_blog_view bv WHERE bv.simple_blog_article_id = b.simple_blog_article_id) AS view  FROM '.DB_PREFIX.'simple_blog_article b LEFT JOIN '.DB_PREFIX.'simple_blog_article_description bd ON b.simple_blog_article_id=bd.simple_blog_article_id  and bd.language_id='.(int)$this->config->get('config_language_id').' ' ;
				
			$sql .=" WHERE b.status = '1' AND bd.language_id=".(int)$this->config->get('config_language_id')." AND b.simple_blog_article_id IN(".implode(',',array_map('intval',$listBlog)).") ";
			
			$sort_data = array(
				'bd.article_title',
				'b.sort_order',
				'b.date_added'
			);	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				}else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY b.date_added";	
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
			
			$query = $this->db->query( $sql );
			$blogs = $query->rows;
		}
		
		return $blogs; 
	}
	
	public function getUsers(){
        $sql = "SELECT * FROM `" . DB_PREFIX . "user`";
        $query = $this->db->query( $sql );
        $users = $query->rows;
        $output = array();
        foreach( $users as $user ){
            $output[$user['user_id']] = $user['username'];
        }
        return $output;
    }

	public function checkCategory($category_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "simple_blog_category WHERE  simple_blog_category_id ='".$category_id."' ORDER BY sort_order ASC;";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getCategories_son($category_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "simple_blog_category WHERE parent_id ='".$category_id."' AND status = '1' ORDER BY sort_order ASC;";
		$query = $this->db->query($sql);
		return $query->rows;
	}	
}
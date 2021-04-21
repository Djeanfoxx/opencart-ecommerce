<?php
	class ControllerExtensionSimpleBlogSearch extends Controller {
		public function index() {
			$this->language->load('extension/simple_blog/article');
			$this->document->addStyle('catalog/view/javascript/simple_blog/css/style.css');
			$data['error_no_database'] = '';
			if($this->config->get('simple_blog_columns')){
				$data['simple_blog_columns'] =   $this->config->get('simple_blog_columns') ;
			}else{
				$data['simple_blog_columns'] =  'grid-2'; 
			}
			if(!$this->checkDatabase()) {
				if($this->config->get('simple_blog_heading')) {
					$this->document->setTitle($this->config->get('simple_blog_heading'));
				} else {
					$this->document->setTitle($this->language->get('heading_title'));
				}
				
				$this->load->model('extension/simple_blog/article');
				
				$this->load->model('tool/image');
				

				if($this->config->get('simple_blog_heading')) {
					$data['heading_title'] = $this->config->get('simple_blog_heading');
				} else {
					$data['heading_title'] = $this->language->get('heading_title');
				}
				
				$data['articles'] = array();
				
				if (isset($this->request->get['blog_search'])) {
					$blog_search = $this->request->get['blog_search'];
				} else { 
					$blog_search = '';
				}	
				
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else { 
					$page = 1;
				}		
				
				if (isset($this->request->get['limit'])) {
					$limit = $this->request->get['limit'];
				} else {
					$limit = 10;
					//$limit = 2;
				}
				
				$filter_data = array(
					'blog_search'	=> $blog_search,
					'start'	=> ($page - 1) * $limit,
					'limit'	=> $limit
				);
				
				$blog_total = $this->model_extension_simple_blog_article->getTotalArticle($filter_data);
				
				$results = $this->model_extension_simple_blog_article->getArticles($filter_data);
				
				//print "<pre>"; print_r($results); exit;
				
				foreach($results as $result) {
					
					$description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 300) . '...';
					
					if($result['featured_image']) {
						$image = HTTP_SERVER . 'image/' . $result['featured_image'];
						$featured_found = 1;
						//$image = $this->model_tool_image->resize($result['featured_image'], 873, 585);
					} else if($result['image']) {
						$image = HTTP_SERVER . 'image/' . $result['image'];
						$featured_found = '';
						//$image = $this->model_tool_image->resize($result['image'], 873, 585);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', 873, 390);
						$featured_found = '';
					}
					
					// get total comments
					$total_comments = $this->model_extension_simple_blog_article->getTotalComments($result['simple_blog_article_id']);
					
					if($total_comments != 1) {
						$total_comments .= $this->language->get('text_comments');
					} else {
						$total_comments .= $this->language->get('text_comment');
					}
					
					$data['articles'][] = array(
						'simple_blog_article_id'	=> $result['simple_blog_article_id'],
						'article_title'		=> $result['article_title'],
						'author_name'		=> $result['author_name'],
						'image'				=> $image,
						'featured_found'	=> $featured_found,
						'date_added'		=> date($this->language->get('text_date_format'), strtotime($result['date_modified'])),
						'description'		=> $description,
						'allow_comment'		=> $result['allow_comment'],
						'total_comment'		=> $total_comments,
						'href'				=> $this->url->link('extension/simple_blog/article/view', 'simple_blog_article_id=' . $result['simple_blog_article_id'], true),
						'author_href'		=> $this->url->link('extension/simple_blog/author', 'simple_blog_author_id=' . $result['simple_blog_author_id'], true),
						'comment_href'		=> $this->url->link('extension/simple_blog/article/view', 'simple_blog_article_id=' . $result['simple_blog_article_id'], true)
					);
				}			
				
				$data['button_continue_reading'] = $this->language->get('button_continue_reading');
				$data['text_no_found'] = $this->language->get('text_no_found');
				
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home'),
					'separator' => false
				);
		
				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('extension/simple_blog/article'),       		
					'separator' => ' :: '
				);
				
				$pagination = new Pagination();
				$pagination->total = $blog_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('extension/simple_blog/article', '&page={page}');

				$data['pagination'] = $pagination->render();
				
				$data['results'] = sprintf($this->language->get('text_pagination'), ($blog_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($blog_total - $limit)) ? $blog_total : ((($page - 1) * $limit) + $limit), $blog_total, ceil($blog_total / $limit));
            
			}else{
				$data['error_no_database'] = $this->language->get('text_no_database');
			}
			$data['column_left'] = $this->load->controller('common/column_left');
    		$data['column_right'] = $this->load->controller('common/column_right');
    		$data['content_top'] = $this->load->controller('common/content_top');
    		$data['content_bottom'] = $this->load->controller('common/content_bottom');
    		$data['footer'] = $this->load->controller('common/footer');
    		$data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('extension/simple_blog/article', $data));

        }
		
		public function checkDatabase() {
			$database_not_found = $this->validateTable();

			if(!$database_not_found) {
				return true;
			}
			return false;
		}
		
		public function validateTable() {
			$table_name = $this->db->escape('simple_blog_article');

			$table = DB_PREFIX . $table_name;

			$query = $this->db->query("SHOW TABLES LIKE '{$table}'");

			return $query->num_rows;
		}
		
	}
<?php
    class ControllerExtensionSimpleBlogCategory extends Controller {
    	public function index() {
    		$this->language->load('extension/simple_blog/article');
			
			$this->document->setTitle($this->language->get('heading_title'));
			$this->document->addStyle('catalog/view/javascript/simple_blog/css/style.css');
			
			$this->load->model('extension/simple_blog/article');
			
			$this->load->model('tool/image');
			
			$data['error_no_database'] = '';
			if($this->config->get('simple_blog_columns')){
				$data['simple_blog_columns'] =   $this->config->get('simple_blog_columns') ;
			}else{
				$data['simple_blog_columns'] =  'grid-2'; 
			}
			
			if(!$this->checkDatabase()) {
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else { 
					$page = 1;
				}	
		
				if (isset($this->request->get['limit'])) {
					$limit = $this->request->get['limit'];
				} else {
					$limit = 10;
				}
				
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home'),
					'separator' => false
				);
				
				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('extension/simple_blog/article'),
					'separator' => $this->language->get('text_separator')
				);
				
				if (isset($this->request->get['simple_blog_category_id'])) {
					$url = '';
					
					if (isset($this->request->get['limit'])) {
						$url .= '&limit=' . $this->request->get['limit'];
					}
					
					$path = '';

					$parts = explode('_', (string)$this->request->get['simple_blog_category_id']);
					
					$simple_blog_category_id = (int)array_pop($parts);
					
					foreach ($parts as $path_id) {
						if (!$path) {
							$path = (int)$path_id;
						} else {
							$path .= '_' . (int)$path_id;
						}
		
						$category_info = $this->model_extension_simple_blog_article->getCategory($path_id);
						
						if ($category_info) {
							$data['breadcrumbs'][] = array(
								'text'      => $category_info['name'],
								'href'      => (isset($category_info['external_link']) ? $category_info['external_link'] : $this->url->link('simple_blog/category', 'simple_blog_category_id=' . $path . $url)),
								'separator' => $this->language->get('text_separator')
							);
						}
					}
					
				} else {
					$simple_blog_category_id = 0;
				}
				
				$category_info = $this->model_extension_simple_blog_article->getCategory($simple_blog_category_id);

				if ($category_info) {
					$this->document->setTitle($category_info['name']);
					$this->document->setDescription($category_info['meta_description']);
					$this->document->setKeywords($category_info['meta_keyword']);

					$data['heading_title'] = $category_info['name'];

					// Set the last category breadcrumb		
					$url = '';
					
					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}
		
					if (isset($this->request->get['limit'])) {
						$url .= '&limit=' . $this->request->get['limit'];
					}
					
					$data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('extension/simple_blog/category', 'simple_blog_category_id=' . $this->request->get['simple_blog_category_id']),
						'separator' => $this->language->get('text_separator')
					);
					
					$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
					$data['image'] =  $this->model_tool_image->resize($category_info['image'], 870, 240);
					
					// now get blogs according to category wise.
					$data['categories'] = array();

					$results = $this->model_extension_simple_blog_article->getCategories($simple_blog_category_id);
					foreach ($results as $result) {
						
						$article_total = $this->model_extension_simple_blog_article->getTotalArticles($result['simple_blog_category_id']);

						$data['categories'] = array(
							'name'  => $result['name'] . ' (' . $article_total . ')',
							'href'  => $this->url->link('extension/simple_blog/category', 'simple_blog_category_id=' . $this->request->get['simple_blog_category_id'] . '_' . $result['simple_blog_category_id'])
						);						
					}
					
					//print "<pre>"; print_r($data['categories']); exit;
					
					$data['articles'] = array();
					
					if($category_info['column']) {
						$limit = $category_info['column'];
					}
					
					$filter_data = array(
						'simple_blog_article_id' 	=> $simple_blog_category_id,
						'start'             => ($page - 1) * $limit,
						'limit'             => $limit
					);
					
					$blog_total = $this->model_extension_simple_blog_article->getTotalArticleCategoryWise($filter_data); 
					
					$results = $this->model_extension_simple_blog_article->getArticleCategoryWise($filter_data);
					
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
					
					//print "<pre>"; print_r($data['articles']); exit;
					
					$data['button_continue_reading'] = $this->language->get('button_continue_reading');
					$data['text_post_by'] = $this->language->get('text_post_by');
					$data['text_no_found'] = $this->language->get('text_no_found');
					
					$pagination = new Pagination();
					$pagination->total = $blog_total;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = $this->language->get('text_pagination');
					$pagination->url = $this->url->link('simple_blog/category', 'simple_blog_category_id=' . $this->request->get['simple_blog_category_id'] . '&page={page}');
		
					$data['pagination'] = $pagination->render();
					
	                $data['results'] = sprintf($this->language->get('text_pagination'), ($blog_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($blog_total - $limit)) ? $blog_total : ((($page - 1) * $limit) + $limit), $blog_total, ceil($blog_total / $limit));
	                
					$data['column_left'] = $this->load->controller('common/column_left');
	        		$data['column_right'] = $this->load->controller('common/column_right');
	        		$data['content_top'] = $this->load->controller('common/content_top');
	        		$data['content_bottom'] = $this->load->controller('common/content_bottom');
	        		$data['footer'] = $this->load->controller('common/footer');
	        		$data['header'] = $this->load->controller('common/header');

	                $this->response->setOutput($this->load->view('extension/simple_blog/article', $data));

	            } else {
					$url = '';

					if (isset($this->request->get['simple_blog_category_id'])) {
						$url .= '&simple_blog_category_id=' . $this->request->get['simple_blog_category_id'];
					}
		
					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}
		
					if (isset($this->request->get['limit'])) {
						$url .= '&limit=' . $this->request->get['limit'];
					}
					
					$data['breadcrumbs'][] = array(
						'text'      => $this->language->get('text_category_error'),
						'href'      => $this->url->link('blog/article/category', $url),
						'separator' => $this->language->get('text_separator')
					);
		
					$this->document->setTitle($this->language->get('text_category_error'));
		
					$data['heading_title'] = $this->language->get('text_category_error');
		
					$data['text_error'] = $this->language->get('text_category_error');
		
					$data['button_continue'] = $this->language->get('button_continue');
		
					$data['continue'] = $this->url->link('common/home');
		
					$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
		
					$data['column_left'] = $this->load->controller('common/column_left');
	        		$data['column_right'] = $this->load->controller('common/column_right');
	        		$data['content_top'] = $this->load->controller('common/content_top');
	        		$data['content_bottom'] = $this->load->controller('common/content_bottom');
	        		$data['footer'] = $this->load->controller('common/footer');
	        		$data['header'] = $this->load->controller('common/header');

	                $this->response->setOutput($this->load->view('error/not_found', $data));
	            }
	        }else{
				$data['error_no_database'] = $this->language->get('text_no_database');
				$data['button_continue_reading'] = $this->language->get('button_continue_reading');
				$data['text_no_found'] = $this->language->get('text_no_found');
				
				
				$data['column_left'] = $this->load->controller('common/column_left');
        		$data['column_right'] = $this->load->controller('common/column_right');
        		$data['content_top'] = $this->load->controller('common/content_top');
        		$data['content_bottom'] = $this->load->controller('common/content_bottom');
        		$data['footer'] = $this->load->controller('common/footer');
        		$data['header'] = $this->load->controller('common/header');

                $this->response->setOutput($this->load->view('extension/simple_blog/article', $data));
			}   
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
<?php
	class ControllerExtensionSimpleBlogAuthor extends Controller {
		public function index() {
			$this->language->load('extension/simple_blog/article');
			
			$data['error_no_database'] = '';			
			$this->document->setTitle($this->language->get('heading_title'));
			$this->document->addStyle('catalog/view/javascript/simple_blog/css/style.css');
			
			$this->load->model('extension/simple_blog/article');			
			$this->load->model('tool/image');
			
			if($this->config->get('simple_blog_columns')){
				$data['simple_blog_columns'] =   $this->config->get('simple_blog_columns') ;
			}else{
				$data['simple_blog_columns'] =  'grid-2'; 
			}
			if(isset($this->request->get['simple_blog_author_id'])) {
				$simple_blog_author_id = $this->request->get['simple_blog_author_id'];
			} else {
				$simple_blog_author_id = 0;
			}
			
			if($simple_blog_author_id) {
				
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
				$data['description'] = '';
				$data['image'] =  '';
				
				$data['articles'] = array();
				
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
				
				$filter_data = array(
					'simple_blog_author_id'	=> $simple_blog_author_id,
					'start'	=> ($page - 1) * $limit,
					'limit'	=> $limit
				);
				$category_info ='';
				$author_total = $this->model_extension_simple_blog_article->getTotalArticleAuthorWise($simple_blog_author_id);
				
				$results = $this->model_extension_simple_blog_article->getArticleAuthorWise($filter_data);
				
				foreach($results as $result) {
					
					$data['heading_title'] = $result['author_name'];
					
					$this->document->setTitle($result['author_name']);
					
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
				
				
				if($data['articles']) {					
					// author related information
					$author_info = $this->model_extension_simple_blog_article->getAuthorInformation($simple_blog_author_id);
					
					//print "<pre>"; print_r($author_info); exit;
					
					if($author_info) {
						
						$data['author_information_found'] = 1;
						
						$data['author_name'] = $author_info['name'];
						
						if($author_info['image']) {
							$data['author_image'] = $this->model_tool_image->resize($author_info['image'], 150, 100);
						} else {
							$data['author_image'] = $this->model_tool_image->resize('no_image.jpg', 150, 100);
						}
						
						$data['author_description'] = html_entity_decode($author_info['description'], ENT_QUOTES, 'UTF-8');
					}	
				}
				
				if(!isset($data['heading_title'])) {
					$data['heading_title'] = $this->language->get('heading_title');
				}
				
				
				}else{
					$data['error_no_database'] = $this->language->get('text_no_database');
							
				}

				//print "<pre>"; print_r($data['articles']); exit;
				
				$data['button_continue_reading'] = $this->language->get('button_continue_reading');
				$data['text_no_found'] = $this->language->get('text_no_found');
				
				$pagination = new Pagination();
				$pagination->total = $author_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('extension/simple_blog/author', 'simple_blog_author_id=' . $this->request->get['simple_blog_author_id'] . '&page={page}');
				$data['pagination'] = $pagination->render();
				
                $data['results'] = sprintf($this->language->get('text_pagination'), ($author_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($author_total - $limit)) ? $author_total : ((($page - 1) * $limit) + $limit), $author_total, ceil($author_total / $limit));
                
                $data['column_left'] = $this->load->controller('common/column_left');
        		$data['column_right'] = $this->load->controller('common/column_right');
        		$data['content_top'] = $this->load->controller('common/content_top');
        		$data['content_bottom'] = $this->load->controller('common/content_bottom');
        		$data['footer'] = $this->load->controller('common/footer');
        		$data['header'] = $this->load->controller('common/header');

                $this->response->setOutput($this->load->view('extension/simple_blog/article', $data));


            } else {
				$url = '';

				if (isset($this->request->get['simple_blog_author_id'])) {
					$url .= '&simple_blog_author_id=' . $this->request->get['simple_blog_author_id'];
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
	
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home'),
					'separator' => false
				);
				
				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('blog/article'),
					'separator' => $this->language->get('text_separator')
				);
				
				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_author_error'),
					'href'      => $this->url->link('blog/author', $url),
					'separator' => $this->language->get('text_separator')
				);
	
				$this->document->setTitle($this->language->get('text_author_error'));
	
				$data['heading_title'] = $this->language->get('text_author_error');
	
				$data['text_error'] = $this->language->get('text_author_error');
	
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
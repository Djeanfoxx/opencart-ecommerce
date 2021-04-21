<?php
	class ControllerExtensionSimpleBlogArticle extends Controller {
		public function index() {
			$this->language->load('extension/simple_blog/article');
			$data['error_no_database'] = '';
			
			

			if(!$this->checkDatabase()) {
				if($this->config->has('simple_blog_heading')) {
					$this->document->setTitle($this->config->get('simple_blog_heading'));
				} else {
					$this->document->setTitle($this->language->get('heading_title'));
				}
				$this->document->addStyle('catalog/view/javascript/simple_blog/css/style.css');
				
				$this->load->model('extension/simple_blog/article');
				
				$this->load->model('tool/image');
				
				// Leader :Check folter Module
				 $data['theme_config'] = $this->config->get('theme_default_directory');


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
				}
				
				$filter_data = array(
					'blog_search'	=> $blog_search,
					'start'	=> ($page - 1) * $limit,
					'limit'	=> $limit
				);
				
				$blog_total = $this->model_extension_simple_blog_article->getTotalArticle($filter_data);
				
				$results = $this->model_extension_simple_blog_article->getArticles($filter_data);
				
				
				
				foreach($results as $result) {
					
					$description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 150) . '...';
					
					if ($result['featured_image']) {
						$image = HTTP_SERVER . 'image/' . $result['featured_image'];
						$featured_found = 1;

					} else if($result['image']) {
						$image = HTTP_SERVER . 'image/' . $result['image'];
						$featured_found = '';
					} else {
						//$image = false;
						$image = $this->model_tool_image->resize('placeholder.png', 873, 390);
						$featured_found = false;
					   
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
				$pagination = new Pagination();
				$pagination->total = $blog_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('extension/simple_blog/article', '&page={page}');

				$data['pagination'] = $pagination->render();
				
				$data['results'] = sprintf($this->language->get('text_pagination'), ($blog_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($blog_total - $limit)) ? $blog_total : ((($page - 1) * $limit) + $limit), $blog_total, ceil($blog_total / $limit));
				//print "<pre>"; print_r($data['articles']); exit;
			}else{
				$data['error_no_database'] = $this->language->get('text_no_database');
				
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
				
			
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				$data['right_blog'] = $this->load->controller('common/right_blog');
				

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
		
		public function view() {

			$this->language->load('extension/simple_blog/article');
			$this->document->addStyle('catalog/view/javascript/simple_blog/css/style.css');
			$this->document->setTitle($this->language->get('heading_title'));

			if  (isset($_GET['article'])){
				$data['simple_blog_articles_style'] =  $_GET['article'] ;
			}else{
				if($this->config->get('simple_blog_columns')){
					$data['simple_blog_articles_style'] =   $this->config->get('simple_blog_articles_style') ;
				}else {
					$data['simple_blog_articles_style'] =  'default'; 
				}
			}
			
			
			if($this->config->get('simple_blog_heading')) {
				$this->document->setTitle($this->config->get('simple_blog_heading'));
			} else {
				$this->document->setTitle($this->language->get('heading_title'));
			}
			
			$this->load->model('extension/simple_blog/article');
			
			$this->load->model('tool/image');
			
			$this->load->model('catalog/product');
			
			if(isset($this->request->get['simple_blog_article_id'])) {
				$simple_blog_article_id = $this->request->get['simple_blog_article_id'];
			} else {
				$simple_blog_article_id = 0;
			}
			
			if ($simple_blog_article_id) {
				
				$data['simple_blog_article_id'] = $simple_blog_article_id;
				
				if($this->config->has('simple_blog_product_related_heading')) {
					$data['text_related_product'] = $this->config->get('simple_blog_product_related_heading');
				} else {
					$data['text_related_product'] = $this->language->get('text_related_product');
				}
				
				//$data['button_cart'] = $this->language->get('button_cart');
				
				if($this->config->has('simple_blog_comment_related_heading')) {
					$data['text_related_comment'] = $this->config->get('simple_blog_comment_related_heading');
				} else {
					$data['text_related_comment'] = $this->language->get('text_related_comment');
				}
				
				
				
				$data['text_write_comment'] = $this->language->get('text_write_comment');
				
				$data['text_note'] = $this->language->get('text_note');
				$data['text_wait'] = $this->language->get('text_wait');
				
				$data['entry_name'] = $this->language->get('entry_name');
				$data['entry_captcha'] = $this->language->get('entry_captcha');
				$data['entry_review'] = $this->language->get('entry_review');		
				
				$data['button_submit'] = $this->language->get('button_submit');	
				
				$data['text_no_found'] = $this->language->get('text_no_found');

				// Leader :Check folter Module
				$data['theme_config'] = $this->config->get('theme_default_directory');

				$article_info = $this->model_extension_simple_blog_article->getArticle($simple_blog_article_id);
				
				if($article_info) {
					
					$this->document->setTitle($article_info['article_title']);
					$this->document->setDescription($article_info['meta_description']);
					$this->document->setKeywords($article_info['meta_keyword']);
					
					//print "<pre>"; print_r($article_info); exit;
					$data['article_info_found'] = $article_info;
					
					// here comes means customer or other viewer can see the blog so need to increment the view counter
					$this->model_extension_simple_blog_article->addBlogView($article_info['simple_blog_article_id']);

					$data['image'] = '';
					if($article_info['image']) {
						$article_img = $article_info['image'];
						$featured_found = '';
						//$image = $this->model_tool_image->resize($result['image'], 873, 585);
					} else if($article_info['featured_image']) {
						$article_img =  $article_info['featured_image'];
						$featured_found = 1;
						//$image = $this->model_tool_image->resize($result['featured_image'], 873, 585);
					} else {
						$article_img = 'placeholder.png';
						$featured_found = '';
					}

					// Setting  imagesize
					$data['image'] =  HTTP_SERVER . 'image/' . $article_img ;

					
					// author url
					$data['author_url'] = $this->url->link('extension/simple_blog/author', 'simple_blog_author_id=' . $article_info['simple_blog_author_id'], true);
					
					// get total comments
					$total_comments = $this->model_extension_simple_blog_article->getTotalComments($simple_blog_article_id);
					
					if($total_comments != 1) {
						$data['total_comment'] = $total_comments . " " . $this->language->get('text_comments');
					} else {
						$data['total_comment'] = $total_comments . " " . $this->language->get('text_comment');
					}

					$article_info['description']	= html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');
					
					$data['article_info'] = $article_info;
					
					$data['article_date_modified'] = date($this->language->get('text_date_format'), strtotime($article_info['date_modified']));
					
					$data['article_additional_description']	= array();
					$article_additional_description = $this->model_extension_simple_blog_article->getArticleAdditionalDescription($simple_blog_article_id);

					if ($article_additional_description) {
						foreach ($article_additional_description as $additional_description) {
							$additional_description['additional_description']	= html_entity_decode($additional_description['additional_description'], ENT_QUOTES, 'UTF-8');
							$data['article_additional_description'] = $additional_description['additional_description'] ;
						}
					}
					
                    // related product
					$related_product = $this->model_extension_simple_blog_article->getArticleProductRelated($simple_blog_article_id);
					
					$data['products'] = array();
					
					foreach($related_product as $product) {
						
						$product_info = $this->model_catalog_product->getProduct($product['product_id']);
						
						$image = ($product_info['image']) ? $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_'.$this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_related_height')) : false;
						
						$price = ($this->customer->isLogged() || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;
						
						$special = ((float)$product_info['special']) ? $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;
						
						$rating = ($this->config->get('config_review_status')) ? (int)$product_info['rating'] : false;
						
						$data['products'][] = array(
							'product_id' => $product_info['product_id'],
							'thumb'   	 => $image,
							'name'    	 => $product_info['name'],
							'price'   	 => $price,
							'special' 	 => $special,
							'rating'     => $rating,
							'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
							'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
						);
					}		
				
					// author related information
					$author_info = $this->model_extension_simple_blog_article->getAuthorInformation($article_info['simple_blog_author_id']);
					
					//print "<pre>"; print_r($author_info); exit;
					
					if($author_info) {
						
						$data['author_name'] = $author_info['name'];
						
						if($author_info['image']) {
							$data['author_image'] = $this->model_tool_image->resize($author_info['image'], 100, 100);
						} else {
							$data['author_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
						}
						
						$data['author_description'] = html_entity_decode($author_info['description'], ENT_QUOTES, 'UTF-8');
					}	
					
					// related article information
					$data['text_related_article'] = $this->language->get('text_related_article');
                    $data['text_author_information'] = $this->language->get('text_author_information');
					$data['text_posted_by'] = $this->language->get('text_posted_by');
					$data['text_on'] = $this->language->get('text_on');
					$data['text_updated'] = $this->language->get('text_updated');
					$data['text_created'] = $this->language->get('text_created');
					$data['text_comment_on_article'] = $this->language->get('text_comment_on_article');
					$data['text_view_comment'] = $this->language->get('text_view_comment');
					$data['button_continue_reading'] = $this->language->get('button_continue_reading');
										
					$related_articles = $this->model_extension_simple_blog_article->getRelatedArticles($article_info['simple_blog_article_id']);
                    $data['related_articles'] = array();
                    
                    foreach($related_articles as $article) {
                        $data['related_articles'][] = array(
    						'simple_blog_article_id'	=> $article['simple_blog_article_id'],
    						'article_title'		=> $article['article_title'],
    						'simple_blog_author_id'	=> $article['simple_blog_author_id'],
    						'image'				=> $article['image'],
    						'description'		=> $article['description'],
    						'author_name'		=> $article['author_name'],
    						'date_added'		=> date('d M', strtotime($article['date_added'])),
    						'date_modified'		=> date('d M', strtotime($article['date_modified'])),
    						'total_comment'		=> $article['total_comment'],
                            'article_href'      => $this->url->link('extension/simple_blog/article/view', 'simple_blog_article_id=' . $article['simple_blog_article_id'], true),
                            'author_href'       => $this->url->link('extension/simple_blog/author', 'simple_blog_author_id=' . $article['simple_blog_author_id'], true)
    					);
                    }
                    
                    if($this->config->has('simple_blog_columns')) {
                        $data['simple_blog_columns'] = $this->config->get('simple_blog_columns');   
                    }
                    
                    if($this->config->has('simple_blog_related_articles')) {
                        $data['simple_blog_related_articles'] = $this->config->get('simple_blog_related_articles');   
                    }

                    if($this->config->has('simple_blog_author_information')) {
                        $data['simple_blog_author_information'] = $this->config->get('simple_blog_author_information');   
                    }
                    
                    if($this->config->has('simple_blog_columns')) {
                        $data['simple_blog_columns'] = $this->config->get('simple_blog_columns');   
                    }
                    
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
					'separator' => ' :: '
				);
				
				if (isset($article_info['article_title'])) {
	                $data['breadcrumbs'][] = array(
	                    'text' => $article_info['article_title'],
	                    'href' => $this->url->link('extension/simple_blog/article/view&simple_blog_article_id='. $this->request->get['simple_blog_article_id'])
	                );
	            }

                $data['column_left'] = $this->load->controller('common/column_left');
        		$data['column_right'] = $this->load->controller('common/column_right');
        		$data['content_top'] = $this->load->controller('common/content_top');
        		$data['content_bottom'] = $this->load->controller('common/content_bottom');
        		$data['footer'] = $this->load->controller('common/footer');
        		$data['header'] = $this->load->controller('common/header');

			  
               // Captcha
				if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
					$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
					
				} else {
					$data['captcha'] = '';
				}

                $data['column_right_blog'] = $this->load->controller('common/right_blog');
                $store_id = $this->config->get('config_store_id');
                $customisation_blog = $this->config->get('customisation_blog_store');
                $data['post_columns'] = 1;
                if (isset($customisation_blog["post_columns"][$store_id])) {$data['post_columns'] = $customisation_blog["post_columns"][$store_id];}


                $this->response->setOutput($this->load->view('extension/simple_blog/article_info', $data));


            } else {
				
				$url = '';

				if (isset($this->request->get['article_id'])) {
					$url .= '&article_id=' . $this->request->get['article_id'];
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
	
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
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
				
				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_category_error'),
					'href'      => $this->url->link('blog/author', $url),
					'separator' => $this->language->get('text_separator')
				);
	
				$this->document->setTitle($this->language->get('text_article_error'));
	
				$data['heading_title'] = $this->language->get('text_article_error');
	
				$data['text_error'] = $this->language->get('text_article_error');
	
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
		
		public function comment() {
			$this->language->load('extension/simple_blog/article');
	
			$this->load->model('extension/simple_blog/article');
	
			$data['text_on'] = $this->language->get('text_on');
			$data['text_said'] = $this->language->get('text_said');
			$data['text_no_blog'] = $this->language->get('text_no_blog');
	
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
			$data['text_reply_comment'] = $this->language->get('text_reply_comment');
			
			$data['comments'] = array();
	
			$comment_total = $this->model_extension_simple_blog_article->getTotalCommentsByArticleId($this->request->get['simple_blog_article_id']);
	
			$results = $this->model_extension_simple_blog_article->getCommentsByArticle($this->request->get['simple_blog_article_id'],($page - 1) * 10, 10, 0);
			
			//print "<pre>"; print_r($results); exit;
			
			foreach ($results as $result) {
				
				$comment_reply = '';
				$comment_replies = array();
				$comment_reply = $this->model_extension_simple_blog_article->getCommentsByArticle($this->request->get['simple_blog_article_id'], 0, 1000, $result['simple_blog_comment_id']);
				
                foreach($comment_reply as $comment) {
                    $comment_replies[] = array(
                        'author'    => $comment['author'],
                        'date_added'    => date($this->language->get('text_date_format_long'), strtotime($comment['date_added'])),
                        'comment'       => $comment['comment']
                    );
                }
                
				$data['comments'][] = array(
					'simple_blog_article_id' 	=> $result['simple_blog_article_id'],
					'simple_blog_comment_id' 	=> $result['simple_blog_comment_id'],
					'comment_reply'		=> $comment_replies,
					'author'     		=> ucwords($result['author']),
					'comment'       	=> $result['comment'],
					'date_added' 		=> date($this->language->get('text_date_format_long'), strtotime($result['date_added']))
				);
			}

			//print "<pre>"; print_r($data['comments']); exit;
	
			$pagination = new Pagination();
			$pagination->total = $comment_total;
			$pagination->page = $page;
			$pagination->limit = 5;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('extension/simple_blog/article/comment', 'simple_blog_article_id=' . $this->request->get['simple_blog_article_id'] . '&page={page}');
	
			$data['pagination'] = $pagination->render();
            
            $data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($comment_total - 5)) ? $comment_total : ((($page - 1) * 5) + 5), $comment_total, ceil($comment_total / 5));

            $this->response->setOutput($this->load->view('extension/simple_blog/article_comment', $data));

        }
		
		public function writeComment() {
			
			$this->load->model('extension/simple_blog/article');
			
			$this->language->load('extension/simple_blog/article');
			
			$json = array();


			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				
				if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
					$json['error'] = $this->language->get('error_name');
				}
	
				if ((utf8_strlen($this->request->post['text']) < 3) || (utf8_strlen($this->request->post['text']) > 1000)) {
					$json['error'] = $this->language->get('error_text');
				}
	
				if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
					$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

					if ($captcha) {
						$json['error'] = $captcha;
					}
				}
				
				if (!isset($json['error'])) {
					//print_r($this->request->post); exit;
					$this->model_extension_simple_blog_article->addArticleComment($this->request->get['simple_blog_article_id'], $this->request->post);
					
					if($this->config->get('simple_blog_comment_auto_approval')) {
						$json['success'] = $this->language->get('text_success');	
					} else {
						$json['success'] = $this->language->get('text_success_approval');	
					}				

				}				
			}
			
			$this->response->setOutput(json_encode($json));
		}
	}
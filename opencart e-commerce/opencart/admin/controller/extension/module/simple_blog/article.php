<?php
	class ControllerExtensionModuleSimpleBlogArticle extends Controller {
		private $error = array();
		
		public function index() {
            $url = $this->request->get['route'];
            if($this->checkDatabase()) {
                
                $this->language->load('extension/module/simple_blog/install');
                
                $this->document->setTitle($this->language->get('error_database'));
                
                $data['install_database'] = $this->url->link('extension/module/simple_blog/install/installDatabase', 'user_token=' . $this->session->data['user_token'] . '&url=' . $url, true);
                
                $data['text_install_message'] = $this->language->get('text_install_message');                
                $data['text_upgread'] = $this->language->get('text_upgread');                
                $data['error_database'] = $this->language->get('error_database');
                
                $data['breadcrumbs'] = array();

    	   		$data['breadcrumbs'][] = array(
    	       		'text'      => $this->language->get('text_home'),
    				'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
    	      		'separator' => false
    	   		);
                
                $data['header'] = $this->load->controller('common/header');
      		    $data['column_left'] = $this->load->controller('common/column_left');
                $data['footer'] = $this->load->controller('common/footer');
        
                $this->response->setOutput($this->load->view('extension/module/simple_blog/notification', $data));
                
            } else {
                $this->getData();
            }	
		}
		
        public function checkDatabase() {
            $database_not_found = $this->load->controller('extension/module/simple_blog/install/validateTable');
            
            if(!$database_not_found) {
                return true;
            } 
            
            return false;
        }
        
        public function getData() {
            $this->language->load('extension/module/simple_blog/article');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('extension/module/simple_blog/article');
	
			$this->getList();
        }
        
		public function insert() {
			$this->language->load('extension/module/simple_blog/article');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('extension/module/simple_blog/article');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
				//print "<pre>"; print_r($this->request->post); exit;
				$this->model_extension_module_simple_blog_article->addArticle($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$this->response->redirect($this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . $url, true));				
			}
			
			$this->getForm();
		}
	
		public function update() {
			$this->language->load('extension/module/simple_blog/article');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('extension/module/simple_blog/article');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
				//print "<pre>"; print_r($this->request->post); exit;
				$this->model_extension_module_simple_blog_article->editArticle($this->request->get['simple_blog_article_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				$this->response->redirect($this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}
			
			$this->getForm();
		}
	
		public function delete() {
			$this->language->load('extension/module/simple_blog/article');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('extension/module/simple_blog/article');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				
				foreach ($this->request->post['selected'] as $simple_blog_article_id) {
					$this->model_extension_module_simple_blog_article->deleteArticle($simple_blog_article_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$this->response->redirect($this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}
			
			$this->getList();
		}
		
		public function getList() {
		  
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
            
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'ba.date_added';
			}
            
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}	
			
            if (isset($this->request->post['selected'])) {
    			$data['selected'] = (array)$this->request->post['selected'];
    		} else {
    			$data['selected'] = array();
    		}
            
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$data['breadcrumbs'] = array();

	   		$data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
	      		'separator' => false
	   		);
	
	   		$data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . $url, true),
	      		'separator' => ' :: '
	   		);
			
			$data['insert'] = $this->url->link('extension/module/simple_blog/article/insert', 'user_token=' . $this->session->data['user_token'] . $url, true);
			$data['delete'] = $this->url->link('extension/module/simple_blog/article/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
			
			$data['articles'] = array();
			
			$filter_data = array(
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit' => $this->config->get('config_limit_admin')
			);
			
			$article_limit = $this->model_extension_module_simple_blog_article->getTotalArticle($filter_data);
			
			$results = $this->model_extension_module_simple_blog_article->getArticles($filter_data);
			
			foreach($results as $result) {
				$data['articles'][] = array(
					'simple_blog_article_id' 	=> $result['simple_blog_article_id'],
					'article_title'		=> $result['article_title'],
					'author_name'       => $result['author_name'],
					'sort_order'		=> $result['sort_order'],
					'status'      		=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
					'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'selected'        	=> isset($this->request->post['selected']) && in_array($result['simple_blog_article_id'], $this->request->post['selected']),
					'edit'          	=> $this->url->link('extension/module/simple_blog/article/update', 'user_token=' . $this->session->data['user_token'] . '&simple_blog_article_id=' . $result['simple_blog_article_id'] . $url, true)
				);		
			}
			
			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_no_results'] = $this->language->get('text_no_results');
            $data['text_confirm'] = $this->language->get('text_confirm');
			
			$data['column_article_title'] = $this->language->get('column_article_title');
			$data['column_author_name'] = $this->language->get('column_author_name');
			$data['column_sort_order'] = $this->language->get('column_sort_order');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_action'] = $this->language->get('column_action');	
	
			$data['button_insert'] = $this->language->get('button_insert');
			$data['button_delete'] = $this->language->get('button_delete');
            $data['button_edit'] = $this->language->get('button_edit');
			
			$data['user_token'] = $this->session->data['user_token'];
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}
	
			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
	
				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}
			
			$url = '';

			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
	
			$data['sort_article_title'] = $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . '&sort=sbad.article_title' . $url, true);
			$data['sort_author_name'] = $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . '&sort=sbau.name' . $url, true);
			$data['sort_sortorder'] = $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . '&sort=sba.sort_order' . $url, true);
			$data['sort_status'] = $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . '&sort=sba.status' . $url, true);
			$data['sort_date_added'] = $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . '&sort=sba.date_added' . $url, true);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			$pagination = new Pagination();
			$pagination->total = $article_limit;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
			
			$data['pagination'] = $pagination->render();
			
            $data['results'] = sprintf($this->language->get('text_pagination'), ($article_limit) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($article_limit - $this->config->get('config_limit_admin'))) ? $article_limit : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $article_limit, ceil($article_limit / $this->config->get('config_limit_admin')));
            
			$data['sort'] = $sort;
			$data['order'] = $order;
            
            $data['header'] = $this->load->controller('common/header');
    		$data['column_left'] = $this->load->controller('common/column_left');
    		$data['footer'] = $this->load->controller('common/footer');
    
    		$this->response->setOutput($this->load->view('extension/module/simple_blog/article_list', $data));
		}		

		public function getForm() {
			$data['heading_title'] = $this->language->get('heading_title');
			
            $data['text_form'] = !isset($this->request->get['simple_blog_article_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');            
            
	    	$data['text_enabled'] = $this->language->get('text_enabled');
	    	$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_image_manager'] = $this->language->get('text_image_manager');
			$data['text_browse'] = $this->language->get('text_browse');
			$data['text_clear'] = $this->language->get('text_clear');	
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');	
			$data['text_default'] = $this->language->get('text_default');	
			$data['text_select_all'] = $this->language->get('text_select_all');
			$data['text_unselect_all'] = $this->language->get('text_unselect_all');
			
            $data['help_title'] = $this->language->get('help_title');
	    	$data['help_author_name'] = $this->language->get('help_author_name');
			$data['help_image'] = $this->language->get('help_image');
			$data['help_featured_image'] = $this->language->get('help_featured_image');
            $data['help_main_image'] = $this->language->get('help_main_image');

            $data['help_productwise'] = $this->language->get('help_productwise');
			$data['help_article_related_method'] = $this->language->get('help_article_related_method');
			$data['help_related_article_name'] = $this->language->get('help_related_article_name');	
			
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_add_description'] = $this->language->get('button_add_description');
			$data['button_add_articles'] = $this->language->get('button_add_articles');
			$data['button_remove'] = $this->language->get('button_remove');
			
			$data['entry_title'] = $this->language->get('entry_title');
			$data['entry_description'] = $this->language->get('entry_description');
			$data['entry_meta_description'] = $this->language->get('entry_meta_description');
			$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
			$data['entry_allow_comment'] = $this->language->get('entry_allow_comment');
			$data['entry_keyword'] = $this->language->get('entry_keyword');
			$data['entry_author_name'] = $this->language->get('entry_author_name');
			$data['entry_image'] = $this->language->get('entry_image');
			$data['entry_featured_image'] = $this->language->get('entry_featured_image');
            $data['entry_main_image'] = $this->language->get('entry_main_image');

            $data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_category'] = $this->language->get('entry_category');
			$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
			$data['entry_product'] = $this->language->get('entry_product');
			$data['entry_productwise'] = $this->language->get('entry_productwise');
			$data['entry_store'] = $this->language->get('entry_store');
			$data['entry_layout'] = $this->language->get('entry_layout');
			$data['entry_additional_description'] = $this->language->get('entry_additional_description');
			$data['entry_article_related_method'] = $this->language->get('entry_article_related_method');
			$data['entry_category_wise'] = $this->language->get('entry_category_wise');
			$data['entry_manufacturer_wise'] = $this->language->get('entry_manufacturer_wise');
			$data['entry_product_wise'] = $this->language->get('entry_product_wise');
			$data['entry_blog_related_articles'] = $this->language->get('entry_blog_related_articles');
			$data['entry_related_article_name'] = $this->language->get('entry_related_article_name');
			
			$data['tab_general'] = $this->language->get('tab_general');
			$data['tab_option'] = $this->language->get('tab_option');
			$data['tab_data'] = $this->language->get('tab_data');
			$data['tab_related'] = $this->language->get('tab_related');
			$data['tab_design'] = $this->language->get('tab_design');
			
			$data['user_token'] = $this->session->data['user_token'];
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');
			$this->document->addScript('view/javascript/summernote/opencart.js');

			
			if (isset($this->request->get['simple_blog_article_id'])) {
				$data['simple_blog_article_id'] = $this->request->get['simple_blog_article_id'];
			} else {
				$data['simple_blog_article_id'] = 0;
			}
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}
	
	 		if (isset($this->error['article_title'])) {
				$data['error_article_title'] = $this->error['article_title'];
			} else {
				$data['error_article_title'] = array();
			}
			
		 	if (isset($this->error['description'])) {
				$data['error_description'] = $this->error['description'];
			} else {
				$data['error_description'] = array();
			}
			
			if (isset($this->error['author_name'])) {
				$data['error_author_name'] = $this->error['author_name'];
			} else {
				$data['error_author_name'] = '';
			}
			
			// if (isset($this->error['seo_keyword'])) {
			// 	$data['error_seo_keyword'] = $this->error['seo_keyword'];
			// } else {
			// 	$data['error_seo_keyword'] = '';
			// }
			if (isset($this->error['keyword'])) {
				$data['error_keyword'] = $this->error['keyword'];
			} else {
				$data['error_keyword'] = '';
			}
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$data['breadcrumbs'] = array();

	   		$data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
	      		'separator' => false
	   		);
	
	   		$data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . $url, true),
	      		'separator' => ' :: '
	   		);
			
			if (!isset($this->request->get['simple_blog_article_id'])) {
				$data['action'] = $this->url->link('extension/module/simple_blog/article/insert', 'user_token=' . $this->session->data['user_token'] . $url, true);
			} else {
				$data['action'] = $this->url->link('extension/module/simple_blog/article/update', 'user_token=' . $this->session->data['user_token'] . '&simple_blog_article_id=' . $this->request->get['simple_blog_article_id'] . $url, true);
			}

			$data['cancel'] = $this->url->link('extension/module/simple_blog/article', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
			if ((isset($this->request->get['simple_blog_article_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$article_info = $this->model_extension_module_simple_blog_article->getArticle($this->request->get['simple_blog_article_id']);
			}
			
			$this->load->model('extension/module/simple_blog/author');
			$data['authors'] = array();
			$data['authors'] = $this->model_extension_module_simple_blog_author->getAuthors();

			$this->load->model('localisation/language');		
			$data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->post['article_description'])) {
				$data['article_description'] = $this->request->post['article_description'];
			} elseif (isset($this->request->get['simple_blog_article_id'])) {
				$data['article_description'] = $this->model_extension_module_simple_blog_article->getArticleDescriptions($this->request->get['simple_blog_article_id']);
			} else {
				$data['article_description'] = array();
			}
			
			if (isset($this->request->post['article_addition_description'])) {
				$data['article_addition_description'] = $this->request->post['article_addition_description'];
			} elseif (isset($this->request->get['simple_blog_article_id'])) {
				$data['article_addition_description'] = $this->model_extension_module_simple_blog_article->getArticleAdditionalDescriptions($this->request->get['simple_blog_article_id']);
			} else {
				$data['article_addition_description'] = array();
			}
			
			//print "<pre>"; print_r($data['article_addition_description']); exit;
			
			// if (isset($this->request->post['keyword'])) {
			// 	$data['keyword'] = $this->request->post['keyword'];
			// } elseif (isset($article_info)) {
			// 	$data['keyword'] = $article_info['keyword'];
			// } else {
			// 	$data['keyword'] = '';
			// }

			if (isset($this->request->post['allow_comment'])) {
				$data['allow_comment'] = $this->request->post['allow_comment'];
			} elseif (isset($article_info)) {
				$data['allow_comment'] = $article_info['allow_comment'];
			} else {
				$data['allow_comment'] = 0;
			}
			
			if (isset($this->request->post['simple_blog_author_id'])) {
				$data['author_name'] = $this->request->post['author_name'];
				$data['simple_blog_author_id'] = $this->request->post['simple_blog_author_id'];
			} elseif (isset($article_info)) {
				$data['simple_blog_author_id'] = $article_info['simple_blog_author_id'];
				$data['author_name'] = $this->model_extension_module_simple_blog_author->getAuthorName($article_info['simple_blog_author_id']);
			} else {
				$data['author_name'] = '';
				$data['simple_blog_author_id'] = '';
			}
			
			if (isset($this->request->post['sort_order'])) {
	      		$data['sort_order'] = $this->request->post['sort_order'];
	    	} elseif (isset($article_info)) {
	      		$data['sort_order'] = $article_info['sort_order'];
	    	} else {
				$data['sort_order'] = '';
			}
			
            if (isset($this->request->post['image'])) {
                $data['image'] = $this->request->post['image'];
            } elseif (!empty($article_info)) {
                $data['image'] = $article_info['image'];
            } else {
                $data['image'] = '';
            }

            $this->load->model('tool/image');
			
			$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            
			if (!empty($article_info) && $article_info['image'] && file_exists(DIR_IMAGE . $article_info['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($article_info['image'], 100, 100);
			} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}

            if (isset($this->request->post['featured_image'])) {
    			$data['featured_image'] = $this->request->post['featured_image'];
    		} elseif (!empty($article_info)) {
    			$data['featured_image'] = $article_info['featured_image'];
    		} else {
    			$data['featured_image'] = '';
    		}

    		$this->load->model('tool/image');
    
    		if (isset($this->request->post['featured_image']) && is_file(DIR_IMAGE . $this->request->post['featured_image'])) {
    			$data['thumb2'] = $this->model_tool_image->resize($this->request->post['featured_image'], 100, 100);
    		} elseif (!empty($article_info) && $article_info['featured_image'] && is_file(DIR_IMAGE . $article_info['featured_image'])) {
    			$data['thumb2'] = $this->model_tool_image->resize($article_info['featured_image'], 100, 100);
    		} else {
    			$data['thumb2'] = $this->model_tool_image->resize('no_image.png', 100, 100);
    		}


    		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            
            
			$this->load->model('setting/store');
			$data['stores'] = array();
		
			$data['stores'][] = array(
				'store_id' => 0,
				'name'     => $this->language->get('text_default')
			);
			
			$stores = $this->model_setting_store->getStores();

			foreach ($stores as $store) {
				$data['stores'][] = array(
					'store_id' => $store['store_id'],
					'name'     => $store['name']
				);
			}
			
			if (isset($this->request->post['article_store'])) {
				$data['article_store'] = $this->request->post['article_store'];
			} elseif (isset($this->request->get['simple_blog_article_id'])) {
				$data['article_store'] = $this->model_extension_module_simple_blog_article->getArticleStore($this->request->get['simple_blog_article_id']);
			} else {
				$data['article_store'] = array(0);
			}	
			
			$data['categories'] = array();
			
			$this->load->model('extension/module/simple_blog/category');
					
			$data['categories'] = $this->model_extension_module_simple_blog_category->getCategories(0);
			
			if (isset($this->request->post['article_category'])) {
				$data['article_category'] = $this->request->post['article_category'];
			} elseif (isset($this->request->get['simple_blog_article_id'])) {
				$data['article_category'] = $this->model_extension_module_simple_blog_article->getArticleCategories($this->request->get['simple_blog_article_id']);
			} else {
				$data['article_category'] = array();
			}
			
			// skip here for the related product & related article portion, complete after.
			
			$this->load->model('catalog/category');
			$data['default_categories'] = $this->model_catalog_category->getCategories(0);
			
			$this->load->model('catalog/manufacturer');
			$data['default_manufacturers'] = $this->model_catalog_manufacturer->getManufacturers(0);
			
			$this->load->model('catalog/product');
						
			if (isset($this->request->post['related_article'])) {
				$data['related_article'] = $this->request->post['related_article'];
				
				if(isset($this->request->post['category_wise'])) {
					$data['category_ids'] = $this->request->post['category_wise'];
				} else if(isset($this->request->post['manufacturer_wise'])) {
					$data['manufacturer_ids'] = $this->request->post['manufacturer_wise'];
				} else {
					if(isset($this->request->post['product_wise'])) {
						$data['products'] = array();
						
						foreach($this->request->post['product_wise'] as $product_id) {
							$product_info = $this->model_catalog_product->getProduct($product_id);
							
							$data['products'][] = array(
								'product_id' => $product_info['product_id'],
								'name'		=> $product_info['name']
							);								
						}									
					}
				}					
			} elseif (isset($article_info)) {
				if($article_info['article_related_method']) {
					$data['related_article'] = $article_info['article_related_method'];
					//echo $data['related_article']; exit;
					$options = unserialize($article_info['article_related_option']);
					
					if($data['related_article'] == 'category_wise' && $options) {
						foreach($options['category_wise'] as $option) {
							$data['category_ids'][] = $option;
						}
					} else if($data['related_article'] == 'manufacturer_wise' && $options) {
						foreach($options['manufacturer_wise'] as $option) {
							$data['manufacturer_ids'][] = $option;
						}						
					} else {
						$products = $this->model_extension_module_simple_blog_article->getArticleProduct($this->request->get['simple_blog_article_id']);
						
						foreach($products as $product) {
							$product_info = $this->model_catalog_product->getProduct($product['product_id']);
							
							$data['products'][] = array(
								'product_id' => $product_info['product_id'],
								'name'		=> $product_info['name']
							);
						}				
					}					
				} else {
					$data['related_article'] = 'product_wise';
				}				
			} else {
				$data['related_article'] = 'product_wise';
			}
			
			if (isset($this->request->post['blog_related_articles'])) {
				$data['blog_related_articles'] = $this->request->post['blog_related_articles'];
			}  elseif (isset($this->request->get['simple_blog_article_id'])) {
				$data['blog_related_articles'] = $this->model_extension_module_simple_blog_article->getRelatedArticles($this->request->get['simple_blog_article_id']);
			} else {
	      		$data['blog_related_articles'] = array();
	    	}
			
			if (isset($this->request->post['status'])) {
				$data['status'] = $this->request->post['status'];
			}  elseif (isset($article_info)) {
				$data['status'] = $article_info['status'];
			} else {
	      		$data['status'] = 0;
	    	}
			
			if (isset($this->request->post['article_layout'])) {
				$data['article_layout'] = $this->request->post['article_layout'];
			} elseif (isset($this->request->get['simple_blog_article_id'])) {
				$data['article_layout'] = $this->model_extension_module_simple_blog_article->getArticleLayouts($this->request->get['simple_blog_article_id']);
			} else {
				$data['article_layout'] = array();
			}

			if (isset($this->request->post['seo_url'])) {
				$data['seo_url'] = $this->request->post['seo_url'];
			} elseif (isset($this->request->get['simple_blog_article_id'])) {
				$data['seo_url'] = $this->model_extension_module_simple_blog_article->getSeoUrls($this->request->get['simple_blog_article_id']);
			} else {
				$data['seo_url'] = array();
			}
			
			$this->load->model('design/layout');		
			$data['layouts'] = $this->model_design_layout->getLayouts();
			
            $data['header'] = $this->load->controller('common/header');
  		    $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
    
            $this->response->setOutput($this->load->view('extension/module/simple_blog/article_form', $data));
		}
		
		public function autocomplete() {
			$json = array();
			
			if (isset($this->request->get['article_name'])) {
				
				if (isset($this->request->get['article_name'])) {
					$article_name = $this->request->get['article_name'];
				} else {
					$article_name = '';
				}
				
                if($article_name) {
                    $this->load->model('extension/module/simple_blog/article');
    				$filter_data = array(
                        'filter_article' => $article_name
                    );
    				$results = $this->model_extension_module_simple_blog_article->getArticles($data);
    				
    				foreach ($results as $result) {
    										
    					$json[] = array(
    						'simple_blog_article_id' 	=> $result['simple_blog_article_id'],
    						'name'       		=> strip_tags(html_entity_decode($result['article_title'], ENT_QUOTES, 'UTF-8'))	
    					);	
    				}	    
                }					
			}
	
			$this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
		}
		
		private function validateForm() {
			if (!$this->user->hasPermission('modify', 'extension/module/simple_blog/article')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			foreach ($this->request->post['article_description'] as $language_id => $value) {
				if ((strlen($value['article_title']) < 3) || (strlen($value['article_title']) > 100)) {
					$this->error['article_title'][$language_id] = $this->language->get('error_title');
				} else {
					if(!isset($this->request->get['simple_blog_article_id'])) {
						$found = $this->model_extension_module_simple_blog_article->checkArticleName($language_id, $value['article_title'], 0);
						
						if($found) {
							$this->error['warning'] = $this->language->get('error_title_found');
							$this->error['article_title'][$language_id] = $this->language->get('error_title_found');	
						}						
					} else {
						$found = $this->model_extension_module_simple_blog_article->checkArticleName($language_id, $value['article_title'], $this->request->get['simple_blog_article_id']);
						if($found) {
							$this->error['warning'] = $this->language->get('error_title_found');	
							$this->error['article_title'][$language_id] = $this->language->get('error_title_found');
						}	
					}
				}
				
				if (strlen($value['description']) < 3) {
					$this->error['description'][$language_id] = $this->language->get('error_description');
				}
			}
			
			if(!$this->request->post['author_name']) {
				$this->error['author_name'] = $this->language->get('error_author_name');
			} else {
				if($this->request->post['simple_blog_author_id']) {
					$found = $this->model_extension_module_simple_blog_article->checkAuthorName($this->request->post['author_name']);
				
					if(!$found) {
						$this->error['author_name'] = $this->language->get('error_author_not_found_list');
						$this->error['warning'] = $this->language->get('error_author_not_found');
					}
				} else {
					$this->error['author_name'] = $this->language->get('error_author_not_found_list');
					$this->error['warning'] = $this->language->get('error_author_not_found');
				}				
			}
			
			// if ((utf8_strlen($this->request->post['keyword']) < 3) || (utf8_strlen($this->request->post['keyword']) > 64)) {
			// 	$this->error['seo_keyword'] = $this->language->get('error_seo_not_found');
			// }

			if ($this->request->post['seo_url']) {
				$this->load->model('design/seo_url');
				
				foreach ($this->request->post['seo_url'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (trim($keyword)) {
							if (count(array_keys($language, $keyword)) > 1) {
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
							}						
							
							$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);
							
							foreach ($seo_urls as $seo_url) {
								if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['simple_blog_article_id']) || (($seo_url['query'] != 'simple_blog_article_id=' . $this->request->get['simple_blog_article_id'])))) {
									$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');
									
									break;
								}
							}
						}
					}
				}
			}
			
			if($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			
			if (!$this->error) {
				return true;
			} else {
				return false;
			}
		}
		
		private function validateDelete() {
			if (!$this->user->hasPermission('modify', 'extension/module/simple_blog/article')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			foreach ($this->request->post['selected'] as $simple_blog_article_id) {
				$found = $this->model_extension_module_simple_blog_article->checkDeleteArticle($simple_blog_article_id);
				
				if($found) {
					$this->error['warning'] = sprintf($this->language->get('error_article_related'), $found);
					break;
				}
			}
			
			if (!$this->error) {
				return true;
			} else {
				return false;
			}
		}	

		public function autocomplete_article() {
			$json = array();
			
			if(isset($this->request->get['simple_blog_article_id'])) {
				
				$this->load->model('extension/module/simple_blog/article');
				
				if(isset($this->request->get['filter_name'])) {
					$filter_name = $this->request->get['filter_name'];
				} else {
					$filter_name = '';
				}
				
                if($filter_name) {
                    $filter_data = array(
    					'filter_name'	=> $filter_name
    				);
    				
    				$results = $this->model_extension_module_simple_blog_article->getArticlesRelated($filter_data, $this->request->get['simple_blog_article_id']);
    				
    				foreach ($results as $result) {				
    					$json[] = array(
    						'simple_blog_article_id' 	=> $result['simple_blog_article_id'],
    						'article_title' 	=> strip_tags(html_entity_decode($result['article_title'], ENT_QUOTES, 'UTF-8'))
    					);	
    				}    
                }					
			}
			
			$this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
		}
	}
<?php
	class ControllerExtensionModuleSimpleBlogAuthor extends Controller {		
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
    				'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
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
            $this->language->load('extension/module/simple_blog/author');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('extension/module/simple_blog/author');
	
			$this->getList();
        }
        
		public function insert() {
			$this->language->load('extension/module/simple_blog/author');
	
			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('extension/module/simple_blog/author');
	
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				//print "<pre>"; print_r($this->request->post); exit;
				$this->model_extension_module_simple_blog_author->addAuthor($this->request->post);
	
				$this->session->data['success'] = $this->language->get('text_success');
	
				$url = '';
	
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
	
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
	
				$this->response->redirect($this->url->link('extension/module/simple_blog/author', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}
	
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('extension/module/simple_blog/author');
	
			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('extension/module/simple_blog/author');
	
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				//print "<pre>"; print_r($this->request->post); exit;
				$this->model_extension_module_simple_blog_author->editAuthor($this->request->get['simple_blog_author_id'], $this->request->post);
	
				$this->session->data['success'] = $this->language->get('text_success');
	
				$url = '';
	
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
	
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
	
				$this->response->redirect($this->url->link('extension/module/simple_blog/author', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}
	
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('extension/module/simple_blog/author');
	
			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('extension/module/simple_blog/author');
	
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $simple_blog_author_id) {
					$this->model_extension_module_simple_blog_author->deleteAuthor($simple_blog_author_id);
				}
	
				$this->session->data['success'] = $this->language->get('text_success');
	
				$url = '';
	
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
	
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
	
				$this->response->redirect($this->url->link('extension/module/simple_blog/author', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}
	
			$this->getList();
		}
		
		public function getList() {
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'sba.name';
			}
	
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}
	
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
            
            if (isset($this->request->post['selected'])) {
    			$data['selected'] = (array)$this->request->post['selected'];
    		} else {
    			$data['selected'] = array();
    		}
            
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
	
			$data['breadcrumbs'] = array();
	
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
				'separator' => false
			);
	
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('extension/module/simple_blog/author', 'user_token=' . $this->session->data['user_token'] . $url, true),
				'separator' => ' :: '
			);
			
			$data['insert'] = $this->url->link('extension/module/simple_blog/author/insert', 'user_token=' . $this->session->data['user_token'] . $url, true);
			$data['delete'] = $this->url->link('extension/module/simple_blog/author/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
	
			$data['authors'] = array();
	
			$filter_data = array(
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit' => $this->config->get('config_limit_admin')
			);
			
			$author_total = $this->model_extension_module_simple_blog_author->getTotalAuthors($filter_data);
			
			$results = $this->model_extension_module_simple_blog_author->getAuthors($filter_data);
			
			foreach($results as $result) {
				
				$data['authors'][] = array(
					'simple_blog_author_id' 	=> $result['simple_blog_author_id'],
					'name'            	=> $result['name'],
					'status'      		=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
					'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'selected'        	=> isset($this->request->post['selected']) && in_array($result['simple_blog_author_id'], $this->request->post['selected']),
					'edit'          	=> $this->url->link('extension/module/simple_blog/author/update', 'user_token=' . $this->session->data['user_token'] . '&simple_blog_author_id=' . $result['simple_blog_author_id'] . $url, true)
				);				
			}		
			
			$data['heading_title'] = $this->language->get('heading_title');
            
            $data['text_confirm'] = $this->language->get('text_confirm');
			$data['text_no_results'] = $this->language->get('text_no_results');
	
			$data['column_author_name'] = $this->language->get('column_author_name');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_action'] = $this->language->get('column_action');	
	
			$data['button_insert'] = $this->language->get('button_insert');
			$data['button_delete'] = $this->language->get('button_delete');
            $data['button_edit'] = $this->language->get('button_edit');
			
			$data['token'] = $this->session->data['user_token'];
			
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
	
			$data['sort_name'] = $this->url->link('simple_blog/author', 'user_token=' . $this->session->data['user_token'] . '&sort=sba.name' . $url, true);
			$data['sort_status'] = $this->url->link('simple_blog/author', 'user_token=' . $this->session->data['user_token'] . '&sort=sba.status' . $url, true);
			$data['sort_date_added'] = $this->url->link('simple_blog/author', 'user_token=' . $this->session->data['user_token'] . '&sort=sba.date_added' . $url, true);
	
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			$pagination = new Pagination();
			$pagination->total = $author_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('simple_blog/author', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
	
			$data['pagination'] = $pagination->render();
			
            $data['results'] = sprintf($this->language->get('text_pagination'), ($author_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($author_total - $this->config->get('config_limit_admin'))) ? $author_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $author_total, ceil($author_total / $this->config->get('config_limit_admin')));
            
			$data['sort'] = $sort;
			$data['order'] = $order;
            
            $data['header'] = $this->load->controller('common/header');
    		$data['column_left'] = $this->load->controller('common/column_left');
    		$data['footer'] = $this->load->controller('common/footer');
    
    		$this->response->setOutput($this->load->view('extension/module/simple_blog/author_list', $data));
		}		
		
		public function getForm() {
			$data['heading_title'] = $this->language->get('heading_title');
			
            $data['text_form'] = !isset($this->request->get['simple_blog_author_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');            
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_image_manager'] = $this->language->get('text_image_manager');
			$data['text_browse'] = $this->language->get('text_browse');
			$data['text_clear'] = $this->language->get('text_clear');
			$data['text_keyword'] = $this->language->get('text_keyword');
            
            $data['help_name'] = $this->language->get('help_name');
			$data['help_keyword'] = $this->language->get('help_keyword');
			$data['help_adminid'] = $this->language->get('help_adminid');		
			
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_keyword'] = $this->language->get('entry_keyword');
			$data['entry_image'] = $this->language->get('entry_image');
			$data['entry_adminid'] = $this->language->get('entry_adminid');
			$data['entry_ctitle'] = $this->language->get('entry_ctitle');
			$data['entry_description'] = $this->language->get('entry_description');
			$data['entry_meta_description'] = $this->language->get('entry_meta_description');
			$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_store'] = $this->language->get('entry_store');
	
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			
			$data['user_token'] = $this->session->data['user_token'];
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');

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

			if (isset($this->request->get['simple_blog_author_id'])) {
				$data['simple_blog_author_id'] = $this->request->get['simple_blog_author_id'];
			} else {
				$data['simple_blog_author_id'] = 0;
			}
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}
			
			if (isset($this->error['name'])) {
				$data['error_name'] = $this->error['name'];
			} else {
				$data['error_name'] = '';
			}
            
            if (isset($this->error['keyword'])) {
				$data['error_keyword'] = $this->error['keyword'];
			} else {
				$data['error_keyword'] = '';
			}

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
				'separator' => false
			);
	
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('extension/module/simple_blog/author', 'user_token=' . $this->session->data['user_token'] . $url, true),
				'separator' => ' :: '
			);
			
			if (!isset($this->request->get['simple_blog_author_id'])) {
				$data['action'] = $this->url->link('extension/module/simple_blog/author/insert', 'user_token=' . $this->session->data['user_token'] . $url, true);
			} else {
				$data['action'] = $this->url->link('extension/module/simple_blog/author/update', 'user_token=' . $this->session->data['user_token'] . '&simple_blog_author_id=' . $this->request->get['simple_blog_author_id'] . $url, true);
			}
			
			$data['cancel'] = $this->url->link('extension/module/simple_blog/author', 'user_token=' . $this->session->data['user_token'] . $url, true);

			if (isset($this->request->get['simple_blog_author_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$author_info = $this->model_extension_module_simple_blog_author->getAuthor($this->request->get['simple_blog_author_id']);
			}
			
			$this->load->model('localisation/language');
		
			$data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->post['author_description'])) {
				$data['author_description'] = $this->request->post['author_description'];
			} elseif (isset($this->request->get['simple_blog_author_id'])) {
				$data['author_description'] = $this->model_extension_module_simple_blog_author->getAuthorDescriptions($this->request->get['simple_blog_author_id']);
			} else {
				$data['author_description'] = array();
			}

			if (isset($this->request->post['name'])) {
				$data['name'] = $this->request->post['name'];
			} elseif (!empty($author_info)) {
				$data['name'] = $author_info['name'];
			} else {	
				$data['name'] = '';
			}
			
			// if (isset($this->request->post['keyword'])) {
			// 	$data['keyword'] = $this->request->post['keyword'];
			// } elseif (!empty($author_info)) {
			// 	$data['keyword'] = $author_info['keyword'];
			// } else {
			// 	$data['keyword'] = '';
			// }
	
			if (isset($this->request->post['image'])) {
				$data['image'] = $this->request->post['image'];
			} elseif (!empty($author_info)) {
				$data['image'] = $author_info['image'];
			} else {
				$data['image'] = '';
			}
			
			$this->load->model('tool/image');

			if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
			} elseif (!empty($author_info) && $author_info['image'] && is_file(DIR_IMAGE . $author_info['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($author_info['image'], 100, 100);
			} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
            
			$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			
			if (isset($this->request->post['status'])) {
				$data['status'] = $this->request->post['status'];
			} elseif (!empty($author_info)) {
				$data['status'] = $author_info['status'];
			} else {
				$data['status'] = 0;
			}

			if (isset($this->request->post['seo_url'])) {
				$data['seo_url'] = $this->request->post['seo_url'];
			} elseif (isset($this->request->get['simple_blog_author_id'])) {
				$data['seo_url'] = $this->model_extension_module_simple_blog_author->getSeoUrls($this->request->get['simple_blog_author_id']);
			} else {
				$data['seo_url'] = array();
			}
			
            $data['header'] = $this->load->controller('common/header');
  		    $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
    
            $this->response->setOutput($this->load->view('extension/module/simple_blog/author_form', $data));
		}

		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'extension/module/simple_blog/author')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
	
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
				$this->error['name'] = $this->language->get('error_name');
			} else {
				// check here whether duplicate name occur or not?
				if(!isset($this->request->get['simple_blog_author_id'])) {
					$found = $this->model_extension_module_simple_blog_author->checkAuthorName($this->request->post['name'], 0);
					
					if($found) {
						$this->error['warning'] = $this->language->get('error_author_found');
						$this->error['name'] = $this->language->get('error_author_found');
					}
				} else {
					$found = $this->model_extension_module_simple_blog_author->checkAuthorName($this->request->post['name'], $this->request->get['simple_blog_author_id']);
					
					if($found) {
						$this->error['warning'] = $this->language->get('error_author_found');
						$this->error['name'] = $this->language->get('error_author_found');
					}
				}
			}
			
   //          if ((utf8_strlen($this->request->post['keyword']) < 3) || (utf8_strlen($this->request->post['keyword']) > 64)) {
			// 	$this->error['keyword'] = $this->language->get('error_seo_not_found');
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
								if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['simple_blog_author_id']) || (($seo_url['query'] != 'simple_blog_author_id=' . $this->request->get['simple_blog_author_id'])))) {
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
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'extension/module/simple_blog/author')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
	
			$this->load->model('extension/module/simple_blog/author');
	
			foreach ($this->request->post['selected'] as $blog_author_id) {
				$article_total = $this->model_extension_module_simple_blog_author->getTotalArticleByAuthorId($blog_author_id);
	
				if ($article_total) {
					$this->error['warning'] = sprintf($this->language->get('error_article'), $article_total);
				}	
			}
            
			if (!$this->error) {
				return true;
			} else {
				return false;
			}  
		}
		
		public function autocomplete() {
			$json = array();
			
			if (isset($this->request->get['author_name'])) {
				if (isset($this->request->get['author_name'])) {
					$author_name = $this->request->get['author_name'];
				} else {
					$author_name = '';
				}
                
                if($author_name) {
                    $this->load->model('extension/module/simple_blog/author');
    				$filter_data = array('filter_author' => $author_name);
    				$results = $this->model_extension_module_simple_blog_author->getAuthors($filter_data);
    				
    				foreach ($results as $result) {
    										
    					$json[] = array(
    						'simple_blog_author_id' 	=> $result['simple_blog_author_id'],
    						'name'       		=> strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))	
    					);	
    				}	    
                }					
			}
	
			$this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
		}
		
	}
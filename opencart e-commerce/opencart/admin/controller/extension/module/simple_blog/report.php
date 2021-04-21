<?php
    class ControllerExtensionModuleSimpleBlogReport extends Controller {
    	
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
    		$this->language->load('extension/module/simple_blog/report');

			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('extension/module/simple_blog/report');
			
			if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start = $this->request->get['filter_date_start'];
			} else {
				$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			}
	
			if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end = $this->request->get['filter_date_end'];
			} else {
				$filter_date_end = date('Y-m-d');
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'bv.view';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'DESC';
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
				'href'      => $this->url->link('extension/module/simple_blog/report', 'user_token=' . $this->session->data['user_token'] . $url, true),
				'separator' => ' :: '
			);
			
			$filter_data = array(
				'filter_date_start' => $filter_date_start,
				'filter_date_end' => $filter_date_end,
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit' => $this->config->get('config_limit_admin')
			);
			
			$blog_viewed_total = $this->model_extension_module_simple_blog_report->getTotalBlogViewed($filter_data); 
			
			// following query gives the total views of whole blog
			$blog_views_total = $this->model_extension_module_simple_blog_report->getTotalBlogViews($filter_data); 
			
			$data['blog_views'] = array();

			$results = $this->model_extension_module_simple_blog_report->getBlogViewed($filter_data);
			
			//print "<pre>"; print_r($results); exit;
			
			foreach ($results as $result) {
				if ($result['view']) {
					$percent = round($result['view'] / $blog_views_total * 100, 2);
				} else {
					$percent = 0;
				}
	
				$data['blog_views'][] = array(
					'article_title'	=> $result['article_title'],
					'author_name'   => $result['author_name'],
					'viewed'  		=> $result['view'],
					'percent' 		=> $percent . '%'			
				);
			}
			
			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_no_results'] = $this->language->get('text_no_results');
	
			$data['column_article_name'] = $this->language->get('column_article_name');
			$data['column_author_name'] = $this->language->get('column_author_name');
			$data['column_viewed'] = $this->language->get('column_viewed');
			$data['column_percent'] = $this->language->get('column_percent');
			
			$data['entry_date_start'] = $this->language->get('entry_date_start');
			$data['entry_date_end'] = $this->language->get('entry_date_end');
			
			$data['button_filter'] = $this->language->get('button_filter');
			$data['button_reset'] = $this->language->get('button_reset');
			
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
	
			$data['sort_article_title'] = $this->url->link('extension/module/simple_blog/report', 'user_token=' . $this->session->data['user_token'] . '&sort=sbad.article_title' . $url, true);
			$data['sort_author_name'] = $this->url->link('extension/module/simple_blog/report', 'user_token=' . $this->session->data['user_token'] . '&sort=sbau.name' . $url, true);
			$data['sort_view'] = $this->url->link('extension/module/simple_blog/report', 'user_token=' . $this->session->data['user_token'] . '&sort=sbv.view' . $url, true);			
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$pagination = new Pagination();
			$pagination->total = $blog_viewed_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('extension/module/simple_blog/report', 'user_token=' . $this->session->data['user_token'] . '&page={page}', true);
	
			$data['pagination'] = $pagination->render();
            
            $data['results'] = sprintf($this->language->get('text_pagination'), ($blog_viewed_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($blog_viewed_total - $this->config->get('config_limit_admin'))) ? $blog_viewed_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $blog_viewed_total, ceil($blog_viewed_total / $this->config->get('config_limit_admin')));
			
			$data['sort'] = $sort;
			$data['order'] = $order;	
			$data['filter_date_start'] = $filter_date_start;
			$data['filter_date_end'] = $filter_date_end;			
            
            $data['header'] = $this->load->controller('common/header');
    		$data['column_left'] = $this->load->controller('common/column_left');
    		$data['footer'] = $this->load->controller('common/footer');
    
    		$this->response->setOutput($this->load->view('extension/module/simple_blog/report', $data));
    	}
    }
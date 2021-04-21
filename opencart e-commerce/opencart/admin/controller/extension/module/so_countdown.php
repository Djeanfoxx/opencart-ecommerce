<?php

class ControllerExtensionModuleSoCountdown extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/so_countdown');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/so_countdown');

		$this->getList();		
	}

	public function add() {
		$this->load->language('extension/module/so_countdown');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/so_countdown');

		$new_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);

			$new_id = $this->model_extension_module_so_countdown->add($this->request->post);
			
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

			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/so_countdown/edit', 'user_token=' . $this->session->data['user_token'] . '&id='.$new_id, 'SSL'));
			}else {
				$this->response->redirect($this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/module/so_countdown');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/so_countdown');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_so_countdown->edit($this->request->get['id'], $this->request->post);
			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);

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

			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/so_countdown/edit', 'user_token=' . $this->session->data['user_token'] . '&id='.$this->request->get['id'], 'SSL'));
			}else {
				$this->response->redirect($this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/module/so_countdown');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/so_countdown');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $value_id) {
				$this->model_extension_module_so_countdown->delete($value_id);
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

			$this->response->redirect($this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_countdown')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_countdown')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['error_name'] = $this->language->get('error_name');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!is_numeric($this->request->post['width']) || $this->request->post['width'] <= 0) {
			$this->error['error_width'] = $this->language->get('error_width');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		/*
		if (!is_numeric($this->request->post['height']) || $this->request->post['height'] <= 0) {
			$this->error['error_height'] = $this->language->get('error_height');
			$this->error['warning'] = $this->language->get('error_warning');
		}
		*/

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (strtotime($this->request->post['date_expire']) < strtotime($this->request->post['date_start'])) {
			$this->error['error_date'] = $this->language->get('error_date');
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('extension/module/so_countdown/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/module/so_countdown/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		$data['lists'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$total = $this->model_extension_module_so_countdown->getTotal();

		$results = $this->model_extension_module_so_countdown->getLists($filter_data);

		$this->load->model('setting/store');
		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($popup_info) && is_file(DIR_IMAGE . $popup_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($popup_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		foreach ($results as $result) {
			$store_id 	= $this->model_extension_module_so_countdown->getPopupStores($result['id']);
			$store_default = array('Default');
			$stores = array();
			if (count($store_id)) {
				foreach ($store_id as $_id) {
					if ($_id['store_id'] != 0) {
						$_store = $this->model_setting_store->getStore($_id['store_id']);
						$stores[]	= $_store['name'];
					}
				}
			}
			$stores = array_merge($store_default, $stores);

			$data['lists'][] = array(
				'id'			=> $result['id'],
				'name'        	=> $result['name'],
				'image'			=> !empty($result['image']) && is_file(DIR_IMAGE . $result['image']) ? $this->model_tool_image->resize($result['image'], 100, 100) : '',
				'sort_order'  	=> $result['priority'],
				'status'		=> $result['status'],
				'date_start'	=> $result['date_start'],
				'date_expire'	=> $result['date_expire'],
				'edit'        	=> $this->url->link('extension/module/so_countdown/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'] . $url, true),
				'delete'      	=> $this->url->link('catalog/category/delete', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'] . $url, true),
				'stores'		=> $stores
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_start_expire'] = $this->language->get('column_date_start_expire');
		$data['column_store'] = $this->language->get('column_store');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		$data['text_enabled'] 			= $this->language->get('text_enabled');
		$data['text_disabled'] 			= $this->language->get('text_disabled');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
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

		$data['sort_name'] = $this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/so_countdown/list', $data));
	}

	protected function getForm() {
		$this->load->language('extension/module/so_countdown');
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['objlang']	= $this->language;

		// $this->document->addStyle('view/javascript/summernote/summernote.css');
		// $this->document->addScript('view/javascript/summernote/summernote.js');
		// $this->document->addScript('view/javascript/summernote/opencart.js');

		$this->document->setTitle($this->language->get('heading_title'));

		// Save and Stay --------------------------------------------------------------
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['heading_title'] 		= $this->language->get('heading_title');
		$data['heading_title_so'] 	= $this->language->get('heading_title_so');
		$data['button_save'] 		= $this->language->get('button_save');
		$data['button_savestay'] 	= $this->language->get('button_savestay');
		$data['button_cancel'] 		= $this->language->get('button_cancel');

		$data['text_edit'] 						= isset($this->request->get['id']) ? $this->language->get('text_edit') : $this->language->get('text_add');
		$data['text_status'] 					= $this->language->get('text_status');
		$data['text_status_help'] 				= $this->language->get('text_status_help');
		$data['text_name'] 						= $this->language->get('text_name');
		$data['text_name_help'] 				= $this->language->get('text_name_help');
		$data['entry_name'] 					= $this->language->get('entry_name');
		$data['text_priority'] 					= $this->language->get('text_priority');
		$data['text_priority_help'] 			= $this->language->get('text_priority_help');
		$data['entry_priority'] 				= $this->language->get('entry_priority');
		$data['text_enabled'] 					= $this->language->get('text_enabled');
		$data['text_disabled'] 					= $this->language->get('text_disabled');
		$data['text_width'] 					= $this->language->get('text_width');
		$data['text_width_help'] 				= $this->language->get('text_width_help');
		$data['entry_width'] 					= $this->language->get('entry_width');
		$data['text_height'] 					= $this->language->get('text_height');
		$data['text_height_help'] 				= $this->language->get('text_height_help');
		$data['entry_height'] 					= $this->language->get('entry_height');
		$data['text_opacity'] 					= $this->language->get('text_opacity');
		$data['text_opacity_help'] 				= $this->language->get('text_opacity_help');
		$data['entry_opacity'] 					= $this->language->get('entry_opacity');
		$data['text_display_countdown'] 		= $this->language->get('text_display_countdown');
		$data['text_display_countdown_help'] 	= $this->language->get('text_display_countdown_help');
		$data['text_yes'] 						= $this->language->get('text_yes');
		$data['text_no'] 						= $this->language->get('text_no');
		$data['text_date_start'] 				= $this->language->get('text_date_start');
		$data['text_date_start_help'] 			= $this->language->get('text_date_start_help');
		$data['text_date_expire'] 				= $this->language->get('text_date_expire');
		$data['text_date_expire_help'] 			= $this->language->get('text_date_expire_help');
		$data['text_heading_title'] 			= $this->language->get('text_heading_title');
		$data['text_heading_title_help'] 		= $this->language->get('text_heading_title_help');
		$data['text_content'] 					= $this->language->get('text_content');
		$data['text_content_help'] 				= $this->language->get('text_content_help');
		$data['entry_store'] 					= $this->language->get('entry_store');
		$data['text_default'] 					= $this->language->get('text_default');
		$data['text_link'] 						= $this->language->get('text_link');
		$data['text_image'] 					= $this->language->get('text_image');
		$data['text_image_help'] 				= $this->language->get('text_image_help');		
		$data['tab_module_setting'] 			= $this->language->get('tab_module_setting');
		$data['tab_help'] 						= $this->language->get('tab_help');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['error_name'])) {
			$data['error_name'] = $this->error['error_name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['error_width'])) {
			$data['error_width'] = $this->error['error_width'];
		} else {
			$data['error_width'] = '';
		}

		// if (isset($this->error['error_height'])) {
		// 	$data['error_height'] = $this->error['error_height'];
		// } else {
		// 	$data['error_height'] = '';
		// }

		if (isset($this->error['error_date'])) {
			$data['error_date'] = $this->error['error_date'];
		} else {
			$data['error_date'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);			
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

		$data['user_token'] = $this->session->data['user_token'];

		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('extension/module/so_countdown/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/module/so_countdown/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $this->request->get['id'] . $url, true);
		}
		
		$data['cancel'] = $this->url->link('extension/module/so_countdown', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$popup_info = $this->model_extension_module_so_countdown->getPopup($this->request->get['id']);
		}

		if (isset($this->request->post['popup_description'])) {
			$data['popup_description'] = $this->request->post['popup_description'];
		} elseif (isset($this->request->get['id'])) {
			$data['popup_description'] = $this->model_extension_module_so_countdown->getPopupDescriptions($this->request->get['id']);
		} else {
			$data['popup_description'] = array();
		}

		if (isset($this->request->post['popup_store'])) {
			$data['popup_store_id'] = $this->request->post['popup_store'];
		} elseif (isset($this->request->get['id'])) {
			$data['popup_store_id'] = $this->model_extension_module_so_countdown->getPopupStoresId($this->request->get['id']);
		} else {
			$data['popup_store_id'] = array(0);
		}

		if (isset($this->request->post['popup_link'])) {
			$data['popup_link'] = $this->request->post['popup_link'];
		} elseif (isset($this->request->get['id'])) {
			$data['popup_link'] = $this->model_extension_module_so_countdown->getPopupStores($this->request->get['id']);
		} else {
			$data['popup_link'] = array(0);
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($popup_info)) {
			$data['status'] = $popup_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($popup_info)) {
			$data['name'] = $popup_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['priority'])) {
			$data['priority'] = $this->request->post['priority'];
		} elseif (!empty($popup_info)) {
			$data['priority'] = $popup_info['priority'];
		} else {
			$data['priority'] = 0;
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($popup_info)) {
			$data['width'] = $popup_info['width'];
		} else {
			$data['width'] = '';
		}

		// if (isset($this->request->post['height'])) {
		// 	$data['height'] = $this->request->post['height'];
		// } elseif (!empty($popup_info)) {
		// 	$data['height'] = $popup_info['height'];
		// } else {
		// 	$data['height'] = '';
		// }

		if (isset($this->request->post['opacity'])) {
			$data['opacity'] = $this->request->post['opacity'];
		} elseif (!empty($popup_info)) {
			$data['opacity'] = $popup_info['opacity'];
		} else {
			$data['opacity'] = '';
		}

		if (isset($this->request->post['display_countdown'])) {
			$data['display_countdown'] = $this->request->post['display_countdown'];
		} elseif (!empty($popup_info)) {
			$data['display_countdown'] = $popup_info['display_countdown'];
		} else {
			$data['display_countdown'] = 0;
		}

		if (isset($this->request->post['date_start'])) {
			$data['date_start'] = $this->request->post['date_start'];
		} elseif (!empty($popup_info)) {
			$data['date_start'] = $popup_info['date_start'];
		} else {
			$data['date_start'] = '';
		}

		if (isset($this->request->post['date_expire'])) {
			$data['date_expire'] = $this->request->post['date_expire'];
		} elseif (!empty($popup_info)) {
			$data['date_expire'] = $popup_info['date_expire'];
		} else {
			$data['date_expire'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($popup_info)) {
			$data['image'] = $popup_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($popup_info) && is_file(DIR_IMAGE . $popup_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($popup_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['header'] 		= $this->load->controller('common/header');
		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['footer'] 		= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/so_countdown/form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_countdown')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['error_name'] = $this->language->get('error_name');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!is_numeric($this->request->post['width']) || $this->request->post['width'] <= 0) {
			$this->error['error_width'] = $this->language->get('error_width');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		// if (!is_numeric($this->request->post['height']) || $this->request->post['height'] <= 0) {
		// 	$this->error['error_height'] = $this->language->get('error_height');
		// 	$this->error['warning'] = $this->language->get('error_warning');
		// }

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
	

		return !$this->error;
	}

	public function uninstall() {
        $this->load->model('extension/module/so_countdown');
        $this->model_extension_module_so_countdown->uninstall();
    }

    public function install() {
        $this->load->model('setting/module');
        $this->load->model('extension/module/so_countdown');
        $data = array(
            'name' => 'So Popup Countdown',
            'status' => 1
        );
        $this->model_setting_module->addModule('so_countdown', $data);
		$this->model_extension_module_so_countdown->install();
    }

    public function getModulesByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

		return $query->row;
	}	
}
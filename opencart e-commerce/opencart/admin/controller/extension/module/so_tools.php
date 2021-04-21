<?php

class ControllerExtensionModuleSoTools extends Controller {
	private $error = array();

	function index() {
		$this->load->language('extension/module/so_tools');
		$this->load->model('extension/module/so_tools');
		$this->load->model('setting/module');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);
			
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('so_tools', $this->request->post);
				$module_id	= $this->db->getLastId();
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
				$module_id 	= $this->request->get['module_id'];
			}

			$this->session->data['success'] = $this->language->get('text_success');

			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/so_tools', 'user_token=' . $this->session->data['user_token'] . '&module_id='.$module_id, 'SSL'));
			}else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
		}

		$this->getForm();	
	}

	protected function getForm() {
		$this->load->language('extension/module/so_tools');
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] 		= $this->language->get('heading_title');
		$data['heading_title_so'] 	= $this->language->get('heading_title_so');
		$data['button_save'] 		= $this->language->get('button_save');
		$data['button_cancel'] 		= $this->language->get('button_cancel');

		$data['text_edit'] 			= $this->language->get('text_edit');
		$data['text_status'] 		= $this->language->get('text_status');
		$data['text_status_help'] 	= $this->language->get('text_status_help');
		$data['text_name'] 			= $this->language->get('text_name');
		$data['text_name_help'] 	= $this->language->get('text_name_help');
		$data['text_enabled'] 		= $this->language->get('text_enabled');
		$data['text_disabled'] 		= $this->language->get('text_disabled');
		$data['text_yes'] 			= $this->language->get('text_yes');
		$data['text_no'] 			= $this->language->get('text_no');
		$data['text_position'] 		= $this->language->get('text_position');
		$data['text_position_help'] = $this->language->get('text_position_help');
		$data['text_left'] 			= $this->language->get('text_left');
		$data['text_right']			= $this->language->get('text_right');
		$data['text_top'] 			= $this->language->get('text_top');
		$data['text_top_help'] 		= $this->language->get('text_top_help');
		$data['text_show_category'] 		= $this->language->get('text_show_category');
		$data['text_show_category_help'] 	= $this->language->get('text_show_category_help');
		$data['text_show_cart'] 			= $this->language->get('text_show_cart');
		$data['text_show_cart_help'] 		= $this->language->get('text_show_cart_help');
		$data['text_show_account'] 			= $this->language->get('text_show_account');
		$data['text_show_account_help'] 	= $this->language->get('text_show_account_help');
		$data['text_show_search'] 			= $this->language->get('text_show_search');
		$data['text_show_search_help'] 		= $this->language->get('text_show_search_help');
		$data['text_show_recent_product'] 	= $this->language->get('text_show_recent_product');
		$data['text_show_recent_product_help'] 	= $this->language->get('text_show_recent_product_help');
		$data['text_limit_product'] 			= $this->language->get('text_limit_product');
		$data['text_limit_product_help']		= $this->language->get('text_limit_product_help');
		$data['text_show_backtop'] 				= $this->language->get('text_show_backtop');
		$data['text_show_backtop_help'] 		= $this->language->get('text_show_backtop_help');
		$data['button_savestay'] 				= $this->language->get('button_savestay');
		$data['text_layout'] 					= sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));

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
				'href' => $this->url->link('extension/module/so_tools', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_tools', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);			
		}


		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_tools', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_tools', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['position'])) {
			$data['position'] = $this->request->post['position'];
		} elseif (!empty($module_info)) {
			$data['position'] = $module_info['position'];
		} else {
			$data['position'] = '';
		}

		if (isset($this->request->post['top'])) {
			$data['top'] = $this->request->post['top'];
		} elseif (!empty($module_info)) {
			$data['top'] = $module_info['top'];
		} else {
			$data['top'] = '';
		}

		if (isset($this->request->post['show_category'])) {
			$data['show_category'] = $this->request->post['show_category'];
		} elseif (!empty($module_info)) {
			$data['show_category'] = $module_info['show_category'];
		} else {
			$data['show_category'] = 0;
		}

		if (isset($this->request->post['show_cart'])) {
			$data['show_cart'] = $this->request->post['show_cart'];
		} elseif (!empty($module_info)) {
			$data['show_cart'] = $module_info['show_cart'];
		} else {
			$data['show_cart'] = 0;
		}

		if (isset($this->request->post['show_account'])) {
			$data['show_account'] = $this->request->post['show_account'];
		} elseif (!empty($module_info)) {
			$data['show_account'] = $module_info['show_account'];
		} else {
			$data['show_account'] = 0;
		}

		if (isset($this->request->post['show_search'])) {
			$data['show_search'] = $this->request->post['show_search'];
		} elseif (!empty($module_info)) {
			$data['show_search'] = $module_info['show_search'];
		} else {
			$data['show_search'] = 0;
		}

		if (isset($this->request->post['show_recent_product'])) {
			$data['show_recent_product'] = $this->request->post['show_recent_product'];
		} elseif (!empty($module_info)) {
			$data['show_recent_product'] = $module_info['show_recent_product'];
		} else {
			$data['show_recent_product'] = 0;
		}

		if (isset($this->request->post['limit_product'])) {
			$data['limit_product'] = $this->request->post['limit_product'];
		} elseif (!empty($module_info)) {
			$data['limit_product'] = $module_info['limit_product'];
		} else {
			$data['limit_product'] = 0;
		}

		if (isset($this->request->post['show_backtop'])) {
			$data['show_backtop'] = $this->request->post['show_backtop'];
		} elseif (!empty($module_info)) {
			$data['show_backtop'] = $module_info['show_backtop'];
		} else {
			$data['show_backtop'] = 0;
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
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

		$data['header'] 		= $this->load->controller('common/header');
		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['footer'] 		= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/so_tools/form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_tools')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}
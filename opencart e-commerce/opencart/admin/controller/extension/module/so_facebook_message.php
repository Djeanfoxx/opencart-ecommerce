<?php

class ControllerExtensionModuleSoFacebookMessage extends Controller {
	private $error = array();
	public function index() {
		$this->load->language('extension/module/so_facebook_message');
		$this->load->model('setting/module');
		$this->load->model('localisation/language');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/javascript/so_facebook_message/css/style.css');

		$data['objlang'] = $this->language;

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);
			
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('so_facebook_message', $this->request->post);
				$module_id	= $this->db->getLastId();
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
				$module_id 	= $this->request->get['module_id'];
			}

			$this->session->data['success'] = $this->language->get('text_success');

			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'] . '&module_id='.$module_id, 'SSL'));
			}else if ($action == 'save_new') {
				$this->response->redirect($this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
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
				'href' => $this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['error']= $this->error;

		// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$default = array(
			'name' 		=> '',
			'status'	=> 1,
			'position'	=> '',
			'width'		=> '',
			'height'	=> '',
			'button_style'	=> '',
			'widget_text'	=> '',
			'module_description'	=> array(),
			'page_url'		=> '',
			'tabs'			=> '',
			'small_header'	=> 1,
			'hide_cover'	=> 0,
			'show_facepile'	=> 0,
			'enablemobile'	=> 1
		);

		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));	

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validateForm() && isset($this->request->get['module_id'])) {
			$module_info 	= $this->model_setting_module->getModule($this->request->get['module_id']);

			$data['action'] 	= $this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		}
		else {
			$module_info	= $default;

			$data['action'] 	= $this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] = $this->language->get('text_create_new_module');
			$data['selectedid'] = 0;
		}

		$data['modules']	= array( 0=> $module_info );
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_facebook_message' );
		$data['link'] 		= $this->url->link('extension/module/so_facebook_message', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['languages'] 	= $this->model_localisation_language->getLanguages();
		$data['module_description'] = $module_info['module_description'];
		
		$data['header'] 		= $this->load->controller('common/header');
		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['footer'] 		= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/so_facebook_message', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_facebook_message')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!is_numeric($this->request->post['width'])) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!is_numeric($this->request->post['height'])) {
			$this->error['height'] = $this->language->get('error_height');
		}

		if (empty($this->request->post['page_url'])) {
			$this->error['page_url'] = $this->language->get('error_page_url');
		}

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		foreach($languages as $language){
			$module_description = $this->request->post['module_description'];
			if (utf8_strlen($module_description[$language['language_id']]['widget_text']) < 3) {
				$this->error['widget_text'] = $this->language->get('error_widget_text');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}
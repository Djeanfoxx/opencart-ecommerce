<?php
class ControllerExtensionModuleSoquickview extends Controller {
	private $error = array();
	private $data = array();
	public function index() {
		// Load language
		$this->load->language('extension/module/so_quickview');
		$data['objlang'] = $this->language;

		$data['entry_button_save'] 			= $this->language->get('entry_button_save');
		$data['entry_button_save_and_edit'] = $this->language->get('entry_button_save_and_edit');
		$data['entry_button_save_and_new'] 	= $this->language->get('entry_button_save_and_new');
		$data['entry_button_cancel'] 		= $this->language->get('entry_button_cancel');
		$data['heading_title_so'] 			= $this->language->get('heading_title_so');
		$data['button_add_module'] 			= $this->language->get('button_add_module');
		$data['entry_button_delete'] 		= $this->language->get('entry_button_delete');
		$data['entry_name_desc'] 			= $this->language->get('entry_name_desc');
		$data['entry_name'] 				= $this->language->get('entry_name');
		$data['entry_status_desc'] 			= $this->language->get('entry_status_desc');
		$data['entry_status'] 				= $this->language->get('entry_status');
		$data['text_enabled'] 				= $this->language->get('text_enabled');
		$data['text_disabled'] 				= $this->language->get('text_disabled');
		$data['entry_module'] 				= $this->language->get('entry_module');
		$data['entry_class_suffix_desc'] 				= $this->language->get('entry_class_suffix_desc');
		$data['entry_class_suffix'] 				= $this->language->get('entry_class_suffix');
		$data['entry_label_button_desc'] 				= $this->language->get('entry_label_button_desc');
		$data['entry_label_button'] 				= $this->language->get('entry_label_button');

		// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();

		// Load model
		$this->load->model('catalog/category');
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_quickview');

		$this->document->setTitle($this->language->get('heading_title'));

		// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		// Get module id new 
		$moduleid_new= $this->model_extension_module_so_quickview->getModuleId(); // Get module id
		$module_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];
				$this->model_setting_module->addModule('so_quickview', $this->request->post);

			} else {
				$module_id = $this->request->get['module_id'];
				$this->request->post['moduleid'] = $this->request->get['module_id'];
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);
			$data = $this->request->post;

			$this->session->data['success'] = $this->language->get('text_success');
			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/so_quickview', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$default = array(
			'name' 					=> '',
			'head_name' 			=> '',
			'action' 				=> '',
			
			'status'				=> '1',
			'class_suffix'			=> '',
			'label_text' 			=> '',
			'label_button'			=> array(),
		);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info =  array_merge($default,$module_info);//check data empty database
			
			$data['action'] = $this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		} else {
			$module_info = $default;
			if($this->request->post != null){
				$module_info = array_merge($module_info,$this->request->post);
			}
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] = $this->language->get('text_create_new_module');
		}
		
		$data['token'] = $this->session->data['user_token'];
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['error']= $this->error;

		// Save and Stay --------------------------------------------------------------
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));

		// ---------------------------Load module --------------------------------------------
		$data['modules'] = array( 0=> $module_info );
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_quickview' );
		$data['link'] = $this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_quickview&user_token=' . $this->session->data['user_token']);

		//--------------------------------Load Data -------------------------------------------
		$data['label_button'] = $module_info['label_button'];
		//Get Data Default
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/so_quickview', $data));


	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_quickview')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		foreach($languages as $language){
			$label_button = $this->request->post['label_button'];
			if ((utf8_strlen($label_button[$language['language_id']]['label_text']) < 3) || (utf8_strlen($label_button[$language['language_id']]['label_text']) > 13)) {
				$this->error['label_text'] = $this->language->get('error_label_text');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}

	public function _breadcrumbs(){
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_quickview', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
}
?>
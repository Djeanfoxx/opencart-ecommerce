<?php
class ControllerExtensionModuleSosearchpro extends Controller {
	private $error = array();
	private $data = array();

	public function index() {
	// Load language
		$this->load->language('extension/module/so_searchpro');
		$data['objlang'] = $this->language;
		$this->document->setTitle($this->language->get('heading_title'));
	// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();
		
		$this->load->model('catalog/category');
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_searchpro');

		// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		$moduleid_new= $this->model_extension_module_so_searchpro->getModuleId(); // Get module id
		$module_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];
				$this->model_setting_module->addModule('so_searchpro', $this->request->post);

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
				$this->response->redirect($this->url->link('extension/module/so_searchpro', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}
		$default = array(
			'name' 					=> '',
			'head_name' 			=> '',
			'action' 				=> '',
			'module_description'	=> array(),
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'class'					=> 'so-search',
			'showcategory'			=> '1',
			'showimage'				=> '1',
			'showprice'				=> '1',
			'height'				=> '100',
			'width'					=> '100',
			'character'				=> '3',
			'limit'					=> '5',
			'status'				=> '1',
			'store_layout'			=> 'default',
			'use_cache'				=> '0',
			'cache_time'			=> '3600'
		);

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['error']= $this->error;
		
		// keyword 
		$data['text_show_keyword'] 		= $this->language->get('entry_show_keyword');
		$data['text_show_keyword_desc']   = $this->language->get('entry_show_keyword_desc');
		$data['text_str_keyword'] 		= $this->language->get('entry_str_keyword');
		$data['text_str_keyword_desc']   = $this->language->get('entry_str_keyword_desc');
		$data['text_limit_keyword'] 		= $this->language->get('entry_limit_keyword');
		$data['text_limit_keyword_desc']   = $this->language->get('entry_limit_keyword_desc');
		$data['text_yes'] 		= $this->language->get('text_yes');
		$data['text_no'] 		= $this->language->get('text_no');

		// Save and Stay --------------------------------------------------------------
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info =  array_merge($default,$module_info);//check data empty database
			$data['action'] = $this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		}else{
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info,$this->request->post);
			}
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] = $this->language->get('text_create_new_module');
		}

		// ---------------------------Load module --------------------------------------------
		$data['modules'] = array( 0=> $module_info );
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_searchpro');
		$data['link'] = $this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_searchpro&user_token=' . $this->session->data['user_token']);
		// Keyword
		if (isset($this->request->post['show_keyword'])) {
			$data['show_keyword'] = $this->request->post['show_keyword'];
		} elseif (!empty($module_info)) {
			$data['show_keyword'] = (isset($module_info['show_keyword']) ? $module_info['show_keyword'] : 1);
		} else {
			$data['show_keyword'] = 1;
		}		
		if (isset($this->request->post['str_keyword'])) {
			$data['str_keyword'] = $this->request->post['str_keyword'];
		} elseif (!empty($module_info)) {
			$data['str_keyword'] = (isset($module_info['str_keyword']) ? $module_info['str_keyword'] : "Keywords");
		} else {
			$data['str_keyword'] = "Keywords";
		}
		if (isset($this->request->post['limit_keyword'])) {
			$data['limit_keyword'] = $this->request->post['limit_keyword'];
		} elseif (!empty($module_info)) {
			$data['limit_keyword'] = (isset($module_info['limit_keyword']) ? $module_info['limit_keyword'] : 5);
		} else {
			$data['limit_keyword'] = 5;
		}
		// Store Layout
		$data['store_layouts'] = array(
			'default' 	=> $this->language->get('value_default')	,
			
		);
		
		// Module description
		$data['module_description'] = $module_info['module_description'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		// Remove cache
		$data['success_remove'] = $this->language->get('text_success_remove');
		$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		if($is_ajax && isset($_REQUEST['is_ajax_cache_lite']) && $_REQUEST['is_ajax_cache_lite']){
			self::remove_cache();
		}
		$this->response->setOutput($this->load->view('extension/module/so_searchpro', $data));
	}
	public function remove_cache()
	{
		$folder_cache = DIR_CACHE.'so/';
		if(file_exists($folder_cache))
		{
			self::mageDelTree($folder_cache);
		}
	}
	function mageDelTree($path) {
		if (is_dir($path)) {
			$entries = scandir($path);
			foreach ($entries as $entry) {
				if ($entry != '.' && $entry != '..') {
					self::mageDelTree($path.'/'.$entry);
				}
			}
			@rmdir($path);
		} else {
			@unlink($path);
		}
	}
	public function addModule($code, $data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "'");
		$result = $this->db->query("SELECT LAST_INSERT_ID() as module_id");
		return $result->row['module_id'];
	}	

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_searchpro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach($languages as $language){
			$module_description = $this->request->post['module_description'];
			if ((utf8_strlen($module_description[$language['language_id']]['head_name']) < 3) || (utf8_strlen($module_description[$language['language_id']]['head_name']) > 64)) {
				$this->error['head_name'] = $this->language->get('error_head_name');
			}
			if ((utf8_strlen($module_description[$language['language_id']]['str_keyword']) < 3) || (utf8_strlen($module_description[$language['language_id']]['str_keyword']) > 64)) {
				$this->error['module_description'] = $this->language->get('error_str_keyword');
			}
		}
		
		if ($this->request->post['width'] != '0' && !filter_var($this->request->post['width'],FILTER_VALIDATE_INT) || $this->request->post['width'] < 0) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if ($this->request->post['height'] != '0' && !filter_var($this->request->post['height'],FILTER_VALIDATE_INT) || $this->request->post['height'] < 0) {
			$this->error['height'] = $this->language->get('error_height');
		}		

		if ($this->request->post['limit'] != '0' && !filter_var($this->request->post['limit'],FILTER_VALIDATE_INT) || $this->request->post['limit'] < 0) {
			$this->error['limit'] = $this->language->get('error_limit');
		}
		
		if ($this->request->post['character'] != '0' && !filter_var($this->request->post['character'],FILTER_VALIDATE_INT) || $this->request->post['character'] < 0) {
			$this->error['character'] = $this->language->get('error_character');
		}
		
		if ($this->request->post['cache_time'] != '0' && !filter_var($this->request->post['cache_time'],FILTER_VALIDATE_INT) || $this->request->post['cache_time'] < 0) {
			$this->error['cache_time'] = $this->language->get('error_cache_time');
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
				'href' => $this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_searchpro', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
}
<?php
class ControllerExtensionModuleSofiltershopby extends Controller {
	private $error = array();
	private $data = array();
	public function index() {
		// Load language
		$this->load->language('extension/module/so_filter_shop_by');
		$data['objlang'] = $this->language;

		// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();

		// Load model
		$this->load->model('catalog/category');
		$this->load->model('catalog/attribute');
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_filter_shop_by');

		$this->document->setTitle($this->language->get('heading_title'));

		// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		// Get module id new 
		$moduleid_new= $this->model_extension_module_so_filter_shop_by->getModuleId(); // Get module id
		$module_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];
				
				$this->model_setting_module->addModule('so_filter_shop_by', $this->request->post);

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
				$this->response->redirect($this->url->link('extension/module/so_filter_shop_by', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$default = array(
			'name' 					=> '',
			'head_name' 			=> '',
			'action' 				=> '',
			'module_description'	=> array(),
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'class_suffix'			=> '',
			'in_class'				=> '#content .row',
			'disp_pro_price'		=> '1',
			'disp_search_text'		=> '1',
			'character_search'		=> '3',	
			'disp_rating'			=> '1',
			'disp_reset_all'		=> '1',
			'disp_manu_all'			=> '1',
			'disp_subcategory'		=> '1',
			'use_cache'				=> '0',
			'cache_time'			=> '3600'
		);
		// Get all attribute
		$attributes =  $this->model_extension_module_so_filter_shop_by->getAttributes();
		if(!empty($attributes)){
			foreach($attributes as $item)
			{
				$disp_attributes["disp_att_id_".$item['attribute_id']] = 1;
				$disp_attributes_group["disp_att_group_id_".$item['attribute_group_id']] = 1;
			}
			$default = array_merge($default,$disp_attributes); // Array config display attribute
			$default = array_merge($default,$disp_attributes_group); // Array config display attribute group
		}
		
		
		// Get all options
		$options_arr = $this->model_extension_module_so_filter_shop_by->getOptions();
		if(!empty($options_arr)){
			foreach($options_arr as $item)
			{
				$disp_options["disp_opt_id_".$item['option_id']] = 1;
			}
			$default = array_merge($default,$disp_options); // Array config display option
		}
		
		// Get All manufactures
		$manufactures = $this->model_extension_module_so_filter_shop_by->getMenufacture();
		if(!empty($manufactures)){
			foreach($manufactures as $item)
			{
				$disp_manufacturer["disp_manu_id_".$item['manufacturer_id']] = 1;
			}
			$default = array_merge($default,$disp_manufacturer); // Array config display manufactures
		}
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info = array_merge($default,$module_info);
			$data['action'] = $this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		} else {
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info,$this->request->post);
			}
			
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] = $this->language->get('text_create_new_module');
		}
		$data['user_token'] = $this->session->data['user_token'];
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
		// echo "<pre>";print_r($module_info);
		$data['modules'] = array( 0=> $module_info );
		
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_filter_shop_by' );
		$data['link'] = $this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_filter_shop_by&user_token=' . $this->session->data['user_token']);
		
		// Load all options
		$data['options'] = array();
		if(!empty($options_arr)){
			foreach($options_arr as $item)
			{
				$data['options'][] = array(
					'option_id'		=> $item['option_id'],
					'option_name'	=> $item['option_name'],
					'option_value'	=> "disp_opt_".$this->convertNameToParam($item['option_name'])
				);
			}
		}
		// Load all attribute
		$data['attributes'] = array();
		if(!empty($attributes)){
			foreach($attributes as $item)
			{
				$data['attributes'][$item['attribute_group_id'].'_'.$item['attribute_group'].'_'.$this->convertNameToParam($item['attribute_group'])][] = array(
					'attribute_id'		=> $item['attribute_id'],
					'attribute_name'	=> $item['name'],
					'attribute_value'	=> "disp_att_".$this->convertNameToParam($item['name'])
				);
			}
		}
		// Load Manufacturer
		$data['manufactures'] = array();
		if(!empty($manufactures)){
			foreach($manufactures as $item)
			{
				$data['manufactures'][] = array(
					'manu_id'				=> $item['manufacturer_id'],
					'manu_name'				=> $item['name'],
					'manu_value'			=> "disp_manu_".$this->convertNameToParam($item['name'])
				);
			}
		}
		//Get Data Default
		$data['header'] 			= $this->load->controller('common/header');
		$data['column_left'] 		= $this->load->controller('common/column_left');
		$data['footer'] 			= $this->load->controller('common/footer');
		// Module description
		$data['module_description'] = $module_info['module_description'];
		// Remove cache
		$data['success_remove'] = $this->language->get('text_success_remove');
		
		$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		if($is_ajax && isset($_REQUEST['is_ajax_cache_lite']) && $_REQUEST['is_ajax_cache_lite']){
			self::remove_cache();
		}
		$this->response->setOutput($this->load->view('extension/module/so_filter_shop_by', $data));
	}
	public function remove_cache(){
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
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_filter_shop_by')) {
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
		}
		
		if (!filter_var($this->request->post['character_search'],FILTER_VALIDATE_INT) || $this->request->post['character_search'] <= 0) {
			$this->error['character_search'] = $this->language->get('error_character_search');
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
				'href' => $this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_filter_shop_by', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}

	public function convertNameToParam($string) {
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
}

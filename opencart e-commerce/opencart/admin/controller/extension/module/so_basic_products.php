<?php
class ControllerExtensionModuleSoBasicProducts extends Controller {
	private $error = array();
	private $data = array();
	
	public function index() {
		// Load language
		$this->load->language('extension/module/so_basic_products');
		$data['objlang'] = $this->language;

		// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();

		// Load model
		$this->load->model('catalog/category');
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_basic_products');

		$this->document->setTitle($this->language->get('heading_title'));

		// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		// Get module id new 
		$moduleid_new= $this->model_extension_module_so_basic_products->getModuleId(); // Get module id
		$module_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];

				$this->model_setting_module->addModule('so_basic_products', $this->request->post);

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
				$this->response->redirect($this->url->link('extension/module/so_basic_products', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'].'&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$default = array(
			'name' 					=> 'SO Basic Product',
			'head_name' 			=> 'Header Title',
			'action' 				=> '',
			'module_description' 	=> array(
					'1'=>'Header Name Title',
					'2'=>'Header Name Title',
			),
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'class_suffix'			=> '',
			'item_link_target'		=> '_blank',
			'layout_theme'			=> 'default',
			'nb_column0'			=> '4',
			'nb_column1'			=> '4',
			'nb_column2'			=> '3',
			'nb_column3'			=> '2',
			'nb_column4'			=> '1',
			'categorys'				=> array(),
			'child_category'		=> '1',
			'category_depth'		=> '1',
			'product_sort'			=> 'p.price',
			'product_ordering'		=> 'ASC',
			'limitation'			=> '6',
			'display_title'			=> '1',
			'title_maxlength'		=> '50',
			'display_description'	=> '0',
			'description_maxlength' => '100',
			'display_price'			=> '1',
			'display_add_to_cart'	=> '1',
			'display_wishlist' 		=> '1',
			'display_compare'		=> '1',
			'display_rating'		=> '1',
			'display_sale'			=> '1',
			'display_new'			=> '1',
			'date_day'				=> '7',
			'product_image_num' 	=> '1',
			
			'product_image'			=> '1',
			'product_get_image_data'=> '1',
			'product_get_image_image'=> '1',
			'width'					=> '200',
			'height'				=> '200',
			'product_placeholder_path'		=> 'nophoto.png',
			
			'post_text'				=> '',
			'pre_text'				=> '',
			'use_cache'				=> '0',
			'cache_time'			=> '3600'
		);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info =  array_merge($default,$module_info);//check data empty database
			if(isset($module_info['category']) && !empty($module_info['category'])){
			$categorys = $module_info['category'];
				foreach ($categorys as $category_id) {
					$category_info = $this->model_catalog_category->getCategory($category_id);
					$name = ($category_info['path'] != null) ? $category_info['path'].' > '.$category_info['name'] : $category_info['name'];
					if ($category_info) {
						$module_info['categorys'][] = array(
							'category_id' 	=> $category_info['category_id'],
							'name'       	=> $name
						); 
					}
				}
			}
			$data['action'] = $this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'].'&module_id='. $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		} else {
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info,$this->request->post);
				$categorys = $module_info['category'];
				if($categorys != null)
				{
					foreach ($categorys as $category_id) {
						$category_info = $this->model_catalog_category->getCategory($category_id);

						if ($category_info) {
							$module_info['categorys'][] = array(
								'category_id' => $category_info['category_id'],
								'name'       => $category_info['name']
							);
						}
					}
				}
			}
			
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'], 'SSL');
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
		
		$data['modules'] = array( 0=> $module_info );
		//var_dump("<pre>",$data['modules'],"</pre>");
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_basic_products' );
		$data['link'] = $this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_basic_products&user_token=' . $this->session->data['user_token']);

		//--------------------------------Load Data -------------------------------------------
		// Module description
		$data['module_description'] = $module_info['module_description'];
		
		// Link target
		$data['item_link_targets'] = array(
			'_blank' => $this->language->get('value_blank'),
			'_self'  => $this->language->get('value_self'),
		);

		//Column
		$data['nb_columns'] = array(
			'1'   => '1',
			'2'   => '2',
			'3'   => '3',
			'4'   => '4',
			'5'   => '5',
			'6'   => '6',
		);

		//Product order by
		$data['product_sorts'] = array(
			'pd.name'  		=> $this->language->get('value_name'),
			'p.model'  		=> $this->language->get('value_model'),
			'p.price'  		=> $this->language->get('value_price'),
			'p.quantity' 	=> $this->language->get('value_quantity'),
			'rating' 		=> $this->language->get('value_rating'),
			'p.sort_order' 	=> $this->language->get('value_sort_order'),
			'p.date_added' 	=> $this->language->get('value_date_added'),
			'sales' 		=> $this->language->get('value_sales')
		);
		//Product order direction
		$data['product_orderings'] = array(
			'ASC'   => $this->language->get('value_asc'),
			'DESC'  => $this->language->get('value_desc'),
		);
		
		//Number Product Image
		$data['product_image_nums'] = array(
			'1'   => '1',
			'2'   => '2'
		);
		
		//Layout theme
		$data['layout_themes'] = array(
			'default'   		=> $this->language->get('value_default'),
			'acc_vertical'   	=> $this->language->get('value_acc_vertical'),
		);

		//Get Data Default
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		// Remove cache
		$data['success_remove'] = $this->language->get('text_success_remove');
		$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';


		if($is_ajax && isset($_REQUEST['is_ajax_cache_lite']) && $_REQUEST['is_ajax_cache_lite']){
			self::remove_cache();
		}
		$this->response->setOutput($this->load->view('extension/module/so_basic_products', $data));


	}
	
	public function remove_cache(){
		/*$this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], 'SSL');*/
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
		if (!$this->user->hasPermission('modify', 'extension/module/so_basic_products')) {
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
		
		/*if ($this->request->post['category'] == null) {
			$this->error['category'] = $this->language->get('error_category');
		}
		
		if (!filter_var($this->request->post['category_depth'],FILTER_VALIDATE_FLOAT) || $this->request->post['category_depth'] < 0) {
			$this->error['category_depth'] = $this->language->get('error_category_depth');
		}*/
		if ($this->request->post['limitation'] != '0' && !filter_var($this->request->post['limitation'],FILTER_VALIDATE_FLOAT) || $this->request->post['limitation'] < 0) {
			$this->error['limitation'] = $this->language->get('error_limitation');
		}
		
		if ($this->request->post['title_maxlength'] != '0' && !filter_var($this->request->post['title_maxlength'],FILTER_VALIDATE_FLOAT) || $this->request->post['title_maxlength'] < 0) {
			
			$this->error['title_maxlength'] = $this->language->get('error_title_maxlength');
		}
		
		if ($this->request->post['description_maxlength'] != '0' && !filter_var($this->request->post['description_maxlength'],FILTER_VALIDATE_FLOAT) || $this->request->post['description_maxlength'] < 0) {
			$this->error['description_maxlength'] = $this->language->get('error_description_maxlength');
		}
		if (!filter_var($this->request->post['width'],FILTER_VALIDATE_FLOAT) || $this->request->post['width'] < 0) {
			$this->error['width'] = $this->language->get('error_width');
		}
		if (!filter_var($this->request->post['height'],FILTER_VALIDATE_FLOAT) || $this->request->post['height'] < 0) {
			$this->error['height'] = $this->language->get('error_height');
		}
		if ($this->request->post['product_placeholder_path'] == null ) {
			$this->error['product_placeholder_path'] = $this->language->get('error_product_placeholder_path');
		}
		
		if (!filter_var($this->request->post['date_day'],FILTER_VALIDATE_INT) || $this->request->post['date_day'] <= 0) {
			$this->error['date_day'] = $this->language->get('error_date_day');
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
				'href' => $this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_basic_products', 'user_token=' . $this->session->data['user_token'].'&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/module/so_basic_products');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_module_so_basic_products->getCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>
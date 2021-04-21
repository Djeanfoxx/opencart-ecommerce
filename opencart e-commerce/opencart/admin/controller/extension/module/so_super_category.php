<?php
class ControllerExtensionModuleSosupercategory extends Controller {
	private $error = array();
	public $data = array();
	public function index() {
	// Load language
		$this->load->language('extension/module/so_super_category');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['objlang'] = $this->language;	
		
	// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();	
	
	// Load model	
		$this->load->model('catalog/category');
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_super_category');
	
	// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
	//Get Module Id New
		$moduleid_new= $this->model_extension_module_so_super_category->getModuleId();
		$module_id = '';	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];
				$this->model_setting_module->addModule('so_super_category', $this->request->post);
				
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
				$this->response->redirect($this->url->link('extension/module/so_super_category', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}
		//---------------------------------------------------------------------------------

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		$filter_data = array(
				'sort'        => 'name',
				'order'       => 'ASC',
			);
		$categorys_list = $this->model_extension_module_so_super_category->getCategories($filter_data);
		
		$default = array(
			'name' 							=> '',
			'module_description'			=> array(),
			'disp_title_module'				=> '1',
			'status'						=> '1',
			'advanced_mod_class_suffix'		=> '',
			'item_link_target'				=> '_blank',
			'categorys'						=> $categorys_list,
			'category'						=> '',
			'category_depth'				=> '1',
			'field_product_tabs'			=> array(),
			'field_preload'					=> '',
			'limitation'					=> '4',
			'product_ordering'				=> 'ASC',
			'category_column0'				=> '4',
			'category_column1'				=> '4',
			'category_column2'				=> '3',
			'category_column3'				=> '2',
			'category_column4'				=> '1',
			'category_title_maxlength'		=> '25',	
			'display_title_sub_category'	=> '1',
			'display_slide_category'		=> '1',
			'show_category_type'			=> '1',
			'sub_category_title_maxlength'	=> '25',
			'category_width'				=> '200',
			'category_height'				=> '100',
			'category_placeholder_path'		=> 'nophoto.png',
			'product_column0'				=> '4',
			'product_column1'				=> '4',
			'product_column2'				=> '3',
			'product_column3'				=> '2',
			'product_column4'				=> '1',
			'type_show'						=> 'slider',
			'rows'							=> '1',
			'product_display_title'			=> '1',
			'product_title_maxlength'		=> '25',
			'product_display_description'	=> '1',
			'product_description_maxlength'	=> '200',
			'product_display_price'			=> '1',
			'display_add_to_cart'			=> '1',
			'display_wishlist'				=> '1',
			'display_compare'				=> '1',
			'display_rating'				=> '1',
			'display_sale'					=> '1',
			'display_new'					=> '1',
			'date_day'						=> '7',
			'product_image_num' 			=> '1',
			
			'product_image'					=> '1',
			'product_get_image_data'		=> '1',
			'product_get_image_image'		=> '1',
			'product_width'					=> '150',
			'product_height'				=> '150',
			'product_placeholder_path'		=> 'nophoto.png',
			'effect'						=> 'flip',
			'product_duration'				=> '600',
			'product_delay'					=> '300',
			'subcategory_center'			=> '0',
			'subcategory_display_navigation'=> '1',
			'subcategory_display_loop'		=> '1',
			'subcategory_margin_right'		=> '5',
			'subcategory_slideby'			=> '1',
			'subcategory_auto_play'			=> '0',
			'subcategory_auto_interval_timeout'=> '300',
			'subcategory_auto_hover_pause'	=> '1',
			'subcategory_auto_play_speed'	=> '300',
			'subcategory_navigation_speed'	=> '3000',
			'subcategory_start_position'	=> '0',
			'subcategory_mouse_drag'		=> '1',
			'subcategory_touch_drag'		=> '1',
			'slider_auto_play'				=> '1',
			'slider_display_navigation'		=> '1',
			'slider_display_loop'			=> '1',
			'slider_mouse_drag'				=> '1',
			'slider_touch_drag'				=> '1',
			'slider_auto_hover_pause'		=> '1',
			'slider_auto_interval_timeout'	=> '5000',
			'slider_auto_play_speed'		=> '2000',
			
			'post_text'						=> '',
			'pre_text'						=> '',
			'use_cache'				=> '0',
			'cache_time'			=> '3600'
		);
		//Field product tab
		$field_product_tab = array(
			'pd_name'  		=> $this->language->get('value_name'),
			'p_model'  		=> $this->language->get('value_model'),
			'p_price'  		=> $this->language->get('value_price'),
			'p_quantity' 	=> $this->language->get('value_quantity'),
			'rating' 		=> $this->language->get('value_rating'),
			'p_sort_order' 	=> $this->language->get('value_sort_order'),
			'p_date_added' 	=> $this->language->get('value_date_added'),
			'sales' 		=> $this->language->get('value_sales')
		);
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info =  array_merge($default,$module_info);//check data empty database
			$module_info['categorys'] = $categorys_list;
			$field_product_tabs = $module_info['field_product_tab'];
			foreach($field_product_tab as $option_id => $option_value) 
			{
				if($field_product_tabs != null && in_array($option_id,$field_product_tabs))
				{
					$module_info['field_product_tabs'][] = array(
						'product_id' => $option_id,
						'name'       => $option_value
					);
				}
			}
			$data['action'] = $this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		} else {
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info,$this->request->post);
				$field_product_tabs = $module_info['field_product_tab'];
				foreach($field_product_tab as $option_id => $option_value) 
				{
					if($field_product_tabs != null && in_array($option_id,$field_product_tabs))
					{
						$module_info['field_product_tabs'][] = array(
							'product_id' => $option_id,
							'name'       => $option_value
						);
					}
				}
			}
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] = $this->language->get('text_create_new_module');
		}

		$data['user_token'] = $this->session->data['user_token'];
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['error']= $this->error;
		
	// Save and Stay
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));
	// ---------------------------Load module --------------------------------------------
		$data['modules'] = array( 0=> $module_info );
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_super_category' );
		$data['link'] = $this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_super_category&user_token=' . $this->session->data['user_token']);
	// ---------------------------Load data ----------------------------------------------
		$data['item_link_targets'] = array(
					'_blank' => $this->language->get('value_blank'),
					'_self'  => $this->language->get('value_self'),
				);
	// Columns
		$data['list_columns']= array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
		);
		
	//Type Show
		$data['type_shows'] = array(
			'loadmore' => $this->language->get('value_loadmore'),
			'slider' => $this->language->get('value_slider')
		);
			
	// Product Ordering
		$data['product_orderings'] = array(
			'ASC'   => $this->language->get('value_asc'),
			'DESC'  => $this->language->get('value_desc'),
		);
	
	// Effect 
		$data['effects'] = array(
			'none'			=>$this->language->get('none'),	
			'bounce'		=>$this->language->get('bounce'),
			'flash'			=>$this->language->get('flash'),
			'pulse'			=>$this->language->get('pulse'),
			'rubberBand'	=>$this->language->get('rubberBand'),
			'shake'			=>$this->language->get('shake'),
			'swing'			=>$this->language->get('swing'),
			'tada'			=>$this->language->get('tada'),
			'wobble'		=>$this->language->get('wobble'),
			'jello'			=>$this->language->get('jello'),
			'starwars'		=> $this->language->get('starwars'),
			'pageTop'		=> $this->language->get('pageTop'),
			'pageBottom'	=> $this->language->get('pageBottom'),
			'slideLeft'  	=> $this->language->get('slideLeft'),
			'slideRight' 	=> $this->language->get('slideRight'),
			'slideTop' 		=> $this->language->get('slideTop'),
			'slideBottom' 	=> $this->language->get('slideBottom'),
			'bounceIn'		=>$this->language->get('bounceIn'),
			'bounceInDown'	=>$this->language->get('bounceInDown'),
			'bounceInLeft'	=>$this->language->get('bounceInLeft'),
			'bounceInRight'	=>$this->language->get('bounceInRight'),
			'bounceInUp'	=>$this->language->get('bounceInUp'),
			'fadeIn'		=>$this->language->get('fadeIn'),
			'fadeInDown'	=>$this->language->get('fadeInDown'),
			'fadeInDownBig'	=>$this->language->get('fadeInDownBig'),
			'fadeInLeft'	=>$this->language->get('fadeInLeft'),
			'fadeInLeftBig'	=>$this->language->get('fadeInLeftBig'),
			'fadeInRight'	=>$this->language->get('fadeInRight'),
			'fadeInRightBig'=>$this->language->get('fadeInRightBig'),
			'fadeInUp'		=>$this->language->get('fadeInUp'),
			'fadeInUpBig'	=>$this->language->get('fadeInUpBig'),
			'flip'			=>$this->language->get('flip'),
			'flipInX'		=>$this->language->get('flipInX'),
			'flipInY'		=>$this->language->get('flipInY'),
			'lightSpeedIn'	=>$this->language->get('lightSpeedIn'),
			'rotateIn'		=>$this->language->get('rotateIn'),
			'rotateInDownLeft'	=>$this->language->get('rotateInDownLeft'),
			'rotateInDownRight'	=>$this->language->get('rotateInDownRight'),
			'rotateInUpLeft'	=>$this->language->get('rotateInUpLeft'),
			'rotateInUpRight'	=>$this->language->get('rotateInUpRight'),
			'slideInUp'			=>$this->language->get('slideInUp'),
			'slideInDown'		=>$this->language->get('slideInDown'),
			'slideInLeft'		=>$this->language->get('slideInLeft'),
			'slideInRight'		=>$this->language->get('slideInRight'),
			'zoomIn'			=>$this->language->get('zoomIn'),
			'zoomInDown'		=>$this->language->get('zoomInDown'),
			'zoomInLeft'		=>$this->language->get('zoomInLeft'),
			'zoomInRight'		=>$this->language->get('zoomInRight'),
			'zoomInUp'			=>$this->language->get('zoomInUp'),
			'rollIn'			=>$this->language->get('rollIn'),
		);
	//Number Product Image
		$data['product_image_nums'] = array(
			'1'   => '1',
			'2'   => '2'
		);	
		
	// Module description
		$data['module_description'] = $module_info['module_description'];
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
		$this->response->setOutput($this->load->view('extension/module/so_super_category', $data));
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
				'href' => $this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_super_category', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_super_category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	// validate name
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
	// validate language
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		foreach($languages as $language){
			$module_description = $this->request->post['module_description'];
			if ((utf8_strlen($module_description[$language['language_id']]['head_name']) < 3) || (utf8_strlen($module_description[$language['language_id']]['head_name']) > 64)) {
				$this->error['head_name'] = $this->language->get('error_head_name');
			}
		}
	// validate category depth
		if (!filter_var($this->request->post['category_depth'],FILTER_VALIDATE_FLOAT) || $this->request->post['category_depth'] < 0){
			$this->error['category_depth'] = $this->language->get('error_category_depth');
		}
	
	// validate field_product_tab	
		if ($this->request->post['field_product_tab'] == null) {
			$this->error['field_product_tab'] = $this->language->get('error_field_product_tab');
		}
	// validate limitation
		if ($this->request->post['limitation'] != '0' && !filter_var($this->request->post['limitation'],FILTER_VALIDATE_FLOAT) || $this->request->post['limitation'] < 0) {
			$this->error['limitation'] = $this->language->get('error_limitation');
		}
	// validate category title maxlength	
		if ($this->request->post['category_title_maxlength'] != '0' && !filter_var($this->request->post['category_title_maxlength'],FILTER_VALIDATE_FLOAT) || $this->request->post['category_title_maxlength'] < 0) {
			$this->error['category_title_maxlength'] = $this->language->get('error_category_title_maxlength');
		}
	// validate sub category title maxlength	
		if ($this->request->post['sub_category_title_maxlength'] != '0' && !filter_var($this->request->post['sub_category_title_maxlength'],FILTER_VALIDATE_FLOAT) || $this->request->post['sub_category_title_maxlength'] < 0) {
			$this->error['sub_category_title_maxlength'] = $this->language->get('error_sub_category_title_maxlength');
		}	
	// validate category width		
		if (!filter_var($this->request->post['category_width'],FILTER_VALIDATE_FLOAT) || $this->request->post['category_width'] < 0){
			$this->error['category_width'] = $this->language->get('error_category_width');
		}
	// validate category height		
		if (!filter_var($this->request->post['category_height'],FILTER_VALIDATE_FLOAT) || $this->request->post['category_height'] < 0) {
			$this->error['category_height'] = $this->language->get('error_category_height');
		}
	// validate product title_maxlength		
		if ($this->request->post['product_title_maxlength'] != '0' && !filter_var($this->request->post['product_title_maxlength'],FILTER_VALIDATE_FLOAT) || $this->request->post['product_title_maxlength'] < 0) {
			
			$this->error['product_title_maxlength'] = $this->language->get('error_product_title_maxlength');
		}
	// validate product description_maxlength			
		if ($this->request->post['product_description_maxlength'] != '0' && !filter_var($this->request->post['product_description_maxlength'],FILTER_VALIDATE_FLOAT) || $this->request->post['product_description_maxlength'] < 0) {
			$this->error['product_description_maxlength'] = $this->language->get('error_product_description_maxlength');
		}	
	// validate product width		
		if (!filter_var($this->request->post['product_width'],FILTER_VALIDATE_FLOAT) || $this->request->post['product_width'] < 0){
			$this->error['product_width'] = $this->language->get('error_product_width');
		}
	// validate product height		
		if (!filter_var($this->request->post['product_height'],FILTER_VALIDATE_FLOAT) || $this->request->post['product_height'] < 0) {
			$this->error['product_height'] = $this->language->get('error_product_height');
		}	
	// validate product duration		
		if (!filter_var($this->request->post['product_duration'],FILTER_VALIDATE_FLOAT) || $this->request->post['product_duration'] < 0) {
			$this->error['product_duration'] = $this->language->get('error_product_duration');
		}	
	// validate product delay		
		if (!filter_var($this->request->post['product_delay'],FILTER_VALIDATE_FLOAT) || $this->request->post['product_delay'] < 0) {
			$this->error['product_delay'] = $this->language->get('error_product_delay');
		}	
	// validate subcategory margin right		
		if (!filter_var($this->request->post['subcategory_margin_right'],FILTER_VALIDATE_INT) && $this->request->post['subcategory_margin_right'] != '0'  || $this->request->post['subcategory_margin_right'] < 0) {
			$this->error['subcategory_margin_right'] = $this->language->get('error_subcategory_margin_right');
		}
	// validate product_placeholder_path
		if ((utf8_strlen($this->request->post['product_placeholder_path']) < 1)) {
			$this->error['product_placeholder_path'] = $this->language->get('error_product_placeholder_path');
		}
	// validate category_placeholder_path
		if ((utf8_strlen($this->request->post['category_placeholder_path']) < 1)) {
			$this->error['category_placeholder_path'] = $this->language->get('error_category_placeholder_path');
		}
	
	// validate subcategory auto interval timeout		
		if (!filter_var($this->request->post['subcategory_auto_interval_timeout'],FILTER_VALIDATE_FLOAT) || $this->request->post['subcategory_auto_interval_timeout'] < 0) {
			$this->error['subcategory_auto_interval_timeout'] = $this->language->get('error_subcategory_auto_interval_timeout');
		}
	// validate subcategory auto play speed	
		if (!filter_var($this->request->post['subcategory_auto_play_speed'],FILTER_VALIDATE_FLOAT) || $this->request->post['subcategory_auto_play_speed'] < 0) {
			$this->error['subcategory_auto_play_speed'] = $this->language->get('error_subcategory_auto_play_speed');
		}
	// validate subcategory navigation speed	
		if (!filter_var($this->request->post['subcategory_navigation_speed'],FILTER_VALIDATE_FLOAT) || $this->request->post['subcategory_navigation_speed'] < 0) {
			$this->error['subcategory_navigation_speed'] = $this->language->get('error_subcategory_navigation_speed');
		}
	
	
	// validate slider auto interval timeout		
		if (!filter_var($this->request->post['slider_auto_interval_timeout'],FILTER_VALIDATE_FLOAT) || $this->request->post['slider_auto_interval_timeout'] < 0) {
			$this->error['slider_auto_interval_timeout'] = $this->language->get('error_slider_auto_interval_timeout');
		}
	// validate slider auto play speed	
		if (!filter_var($this->request->post['slider_auto_play_speed'],FILTER_VALIDATE_FLOAT) || $this->request->post['slider_auto_play_speed'] < 0) {
			$this->error['slider_auto_play_speed'] = $this->language->get('error_slider_auto_play_speed');
		}
	// validate category
		if ($this->request->post['category'] == null) {
			$this->error['category'] = $this->language->get('error_category');
		}
	// validate date day
		if (!filter_var($this->request->post['date_day'],FILTER_VALIDATE_INT) || $this->request->post['date_day'] <= 0) {
			$this->error['date_day'] = $this->language->get('error_date_day');
		}
		//----------------------------
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
	public function autocomplete() {
		$json = array();
		$this->load->language('extension/module/so_super_category');
		if (isset($this->request->get['filter_name'])) {
			$field_product_tabs = array(
				'pd_name'  		=> $this->language->get('value_name'),
				'p_model'  		=> $this->language->get('value_model'),
				'p_price'  		=> $this->language->get('value_price'),
				'p_quantity' 	=> $this->language->get('value_quantity'),
				'rating' 		=> $this->language->get('value_rating'),
				'p_sort_order' 	=> $this->language->get('value_sort_order'),
				'p_date_added' 	=> $this->language->get('value_date_added'),
				'sales' 		=> $this->language->get('value_sales')
			);
			foreach ($field_product_tabs as $option_id => $option_value) {
				$json[] = array(
					'product_id' => $option_id,
					'name'        => strip_tags(html_entity_decode($option_value, ENT_QUOTES, 'UTF-8'))
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
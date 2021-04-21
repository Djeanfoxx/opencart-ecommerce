<?php
class ControllerExtensionModuleSolistingtabs extends Controller {
	private $error = array();
	private $data = array();
	public function index() {
		// Load language
		$this->load->language('extension/module/so_listing_tabs');
		$data['objlang'] = $this->language;

		// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();

		// Load model
		$this->load->model('catalog/category');
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_listing_tabs');
		$this->load->model('tool/image');
		$this->document->setTitle($this->language->get('heading_title'));

	// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
	// Get Module Id new 
		$moduleid_new= $this->model_extension_module_so_listing_tabs->getModuleId();
		$module_id = '';	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];
				$this->model_setting_module->addModule('so_listing_tabs', $this->request->post);

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
				$this->response->redirect($this->url->link('extension/module/so_listing_tabs', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$moduleid_new= $this->model_extension_module_so_listing_tabs->getModuleId(); // Get module id

		$default = array(
			'name'					=> '',
			'head_name' 			=> '',
			'action'				=> '',
			'module_description'	=> array(),
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'class_suffix'			=> '',
			'item_link_target'		=> '_blank',
			'nb_column0'			=> '4',
			'nb_column1'			=> '4',
			'nb_column2'			=> '3',
			'nb_column3'			=> '2',
			'nb_column4'			=> '1',
			'type_show'				=> 'loadmore',
			'nb_row'				=> '1',

			'store_layout'			=> 'default',
			'type_source'			=> '1',
			'categorys'				=> array(),
			'category'				=> array(),
			'child_category'		=> '1',
			'category_depth'		=> '1',
			'product_sort'			=> 'p.price',
			'product_ordering'		=> 'ASC',
			'source_limit'			=> '4',
			
			'field_product_tabs'			=> array(),
			'catid_preload'			=> '*',
			'field_product_tab'		=> '',
			'field_preload'			=> '',
			'tab_all_display'		=> '1',
			'tab_max_characters'	=> '25',
			'tab_icon_display'		=> '1',
			'cat_order_by'		=> 'name',
			'imgcfgcat_width'		=> '30',
			'imgcfgcat_height'		=> '30',

			'display_title'			=> '1',
			'title_maxlength'		=> '50',
			'display_description'	=> '1',
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
			'width'					=> '150',
			'height'				=> '200',
			'product_placeholder_path'=> 'nophoto.png',
			'display_banner_image'	=> '0',
			'banner_image'			=> 'no_image.png',
			'banner_image_url'		=> '',
			'banner_width'			=> '150',
			'banner_height'			=> '250',

			'autoplay'				=> '0',
			'autoplayTimeout'		=> '5000',
			'pausehover'			=> '0',
			'autoplaySpeed'			=> '1000',
			'mousedrag'				=> '1',
			'touchdrag'				=> '1',
			'display_loop'			=> '1',
			'loop'					=> '1',
			'display_nav'			=> '1',
			'navs'					=> '1',
			'navSpeed'				=> '500',
			'effect'				=> 'starwars',
			'duration'				=> '800',
			'delay'					=> '500',
			
			'post_text'				=> '',
			'pre_text'				=> '',
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
			'sell' 			=> $this->language->get('value_sell')
		);
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info =  array_merge($default,$module_info);//check data empty database
			
			$module_info['thumb']= $this->model_tool_image->resize($module_info['banner_image'],100,100);
			$module_info['thumb_default']= $this->model_tool_image->resize('no_image.png',100,100);
			$categorys = $module_info['category'];
			foreach ($categorys as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);
				if(!empty($category_info)){
					$name = ($category_info['path'] != null) ? $category_info['path'].' > '.$category_info['name'] : $category_info['name'];	
				}else{
					$name = '';
				}
				
				if ($category_info) {
					$module_info['categorys'][] = array(
						'category_id' => $category_info['category_id'],
						'name'       => $name
					);
				}
			}
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
			$data['action'] = $this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		} else {
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info =  array_merge($module_info,$this->request->post);
				$categorys = $module_info['category'];
				if($categorys !=null)
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
			$module_info['thumb']= $this->model_tool_image->resize($module_info['banner_image'],100,100);
			$module_info['thumb_default']= $this->model_tool_image->resize('no_image.png',100,100);
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'], 'SSL');
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
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_listing_tabs' );
		$data['link'] = $this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_listing_tabs&user_token=' . $this->session->data['user_token']);
		//--------------------------------Load Data -------------------------------------------
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

		// Store Layout
		$data['store_layouts'] = array(
			'default' 	=> $this->language->get('value_default')	,
			'layout4' 	=> $this->language->get('value_layout4')	,
			'layout7' 	=> $this->language->get('value_layout7')	,
			'layout13' 	=> $this->language->get('value_layout13')	
		);
		

		//row
		$data['nb_rows'] = array(
			'1'   => '1',
			'2'   => '2',
			'3'   => '3',
			'4'   => '4',
			'5'   => '5',
			'6'   => '6',
		);
		
		// Type show
		$data['type_shows']  = array(
			'loadmore' => $this->language->get('type_show_loadmore'),
			'slider' => $this->language->get('type_show_slider')
		);

		$data['cat_order_bys'] = array(
			'name' 		=> $this->language->get('value_cat_name'),
			'lft' 		=> $this->language->get('value_cat_lft'),
			'random' 	=> $this->language->get('value_cat_random'),
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
			'sell' 			=> $this->language->get('value_sell')
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
		
		//Effect
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
		//Get Data Default
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		// Module description
		$data['module_description'] = $module_info['module_description'];
		// Remove cache
		$data['success_remove'] = $this->language->get('text_success_remove');
		$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		if($is_ajax && isset($_REQUEST['is_ajax_cache_lite']) && $_REQUEST['is_ajax_cache_lite']){
			self::remove_cache();
		}
		$this->response->setOutput($this->load->view('extension/module/so_listing_tabs', $data));
	}
	
	public function remove_cache(){
		$this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$folder_cache = DIR_CACHE.'So/';
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
				'href' => $this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_listing_tabs', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_listing_tabs')) {
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

		if ($this->request->post['category'] == null) {
			$this->error['category'] = $this->language->get('error_category');
		}

		if (!filter_var($this->request->post['category_depth'],FILTER_VALIDATE_INT) || $this->request->post['category_depth'] < 0) {
			$this->error['category_depth'] = $this->language->get('error_category_depth');
		}
		if ($this->request->post['source_limit'] != '0' && !filter_var($this->request->post['source_limit'],FILTER_VALIDATE_INT) || $this->request->post['source_limit'] < 0) {
			$this->error['source_limit'] = $this->language->get('error_source_limit');
		}

		if ($this->request->post['tab_max_characters'] != '0' && !filter_var($this->request->post['tab_max_characters'],FILTER_VALIDATE_INT) || $this->request->post['tab_max_characters'] < 0) {

			$this->error['tab_max_characters'] = $this->language->get('error_tab_max_characters');
		}

		if ($this->request->post['description_maxlength'] != '0' && !filter_var($this->request->post['description_maxlength'],FILTER_VALIDATE_INT) || $this->request->post['description_maxlength'] < 0) {
			$this->error['description_maxlength'] = $this->language->get('error_description_maxlength');
		}

		if ($this->request->post['field_product_tab'] == null && $this->request->post['type_source'] ==1) {
			$this->error['field_product_tab'] = $this->language->get('error_field_product_tab');
		}

		if ($this->request->post['imgcfgcat_width'] != '0' && !filter_var($this->request->post['imgcfgcat_width'],FILTER_VALIDATE_INT) || $this->request->post['imgcfgcat_width'] < 0) {
			$this->error['imgcfgcat_width'] = $this->language->get('error_imgcfgcat_width');
		}

		if ($this->request->post['imgcfgcat_height'] != '0' && !filter_var($this->request->post['imgcfgcat_height'],FILTER_VALIDATE_INT) || $this->request->post['imgcfgcat_height'] < 0) {
			$this->error['imgcfgcat_height'] = $this->language->get('error_imgcfgcat_height');
		}

		if ($this->request->post['title_maxlength'] != '0' && !filter_var($this->request->post['title_maxlength'],FILTER_VALIDATE_INT) || $this->request->post['title_maxlength'] < 0) {
			$this->error['title_maxlength'] = $this->language->get('error_title_maxlength');
		}

		if ($this->request->post['width'] != '0' && !filter_var($this->request->post['width'],FILTER_VALIDATE_INT) || $this->request->post['width'] < 0) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if ($this->request->post['height'] != '0' && !filter_var($this->request->post['height'],FILTER_VALIDATE_INT) || $this->request->post['height'] < 0) {
			$this->error['height'] = $this->language->get('error_height');
		}
		
		if ($this->request->post['banner_width'] != '0' && !filter_var($this->request->post['banner_width'],FILTER_VALIDATE_INT) || $this->request->post['banner_width'] < 0) {
			$this->error['banner_width'] = $this->language->get('error_width');
		}

		if ($this->request->post['banner_height'] != '0' && !filter_var($this->request->post['banner_height'],FILTER_VALIDATE_INT) || $this->request->post['banner_height'] < 0) {
			$this->error['banner_height'] = $this->language->get('error_height');
		}
		
		if (utf8_strlen($this->request->post['banner_image']) < 1) {
			$this->error['banner_image'] = $this->language->get('error_banner_image');
		}
		
		if ($this->request->post['duration'] != '0' && !filter_var($this->request->post['duration'],FILTER_VALIDATE_INT) || $this->request->post['duration'] < 0) {
			$this->error['duration'] = $this->language->get('error_duration');
		}

		if ($this->request->post['delay'] != '0' && !filter_var($this->request->post['delay'],FILTER_VALIDATE_INT) || $this->request->post['delay'] < 0) {
			$this->error['delay'] = $this->language->get('error_delay');
		}

		if ($this->request->post['autoplayTimeout'] != '0' && !filter_var($this->request->post['autoplayTimeout'],FILTER_VALIDATE_INT) || $this->request->post['autoplayTimeout'] < 0) {
			$this->error['autoplayTimeout'] = $this->language->get('error_autoplayTimeout');
		}

		if ($this->request->post['autoplaySpeed'] != '0' && !filter_var($this->request->post['autoplaySpeed'],FILTER_VALIDATE_INT) || $this->request->post['autoplaySpeed'] < 0) {
			$this->error['autoplaySpeed'] = $this->language->get('error_autoplaySpeed');
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
	
	public function autocomplete() {
		$json = array();
		$this->load->language('extension/module/so_listing_tabs');
		if (isset($this->request->get['filter_name'])) {
			$field_product_tabs = array(
				'pd_name'  		=> $this->language->get('value_name'),
				'p_model'  		=> $this->language->get('value_model'),
				'p_price'  		=> $this->language->get('value_price'),
				'p_quantity' 	=> $this->language->get('value_quantity'),
				'rating' 		=> $this->language->get('value_rating'),
				'p_sort_order' 	=> $this->language->get('value_sort_order'),
				'p_date_added' 	=> $this->language->get('value_date_added'),
				'sell' 			=> $this->language->get('value_sell')
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
	
	public function autocomplete_category() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/module/so_listing_tabs');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_module_so_listing_tabs->getCategories($filter_data);

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
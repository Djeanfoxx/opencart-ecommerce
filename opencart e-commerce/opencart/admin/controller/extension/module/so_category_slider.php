<?php
class ControllerExtensionModuleSocategoryslider extends Controller {
	public $error = array();
	public $data = array();
	public function index() {
		// Load language
		
		$this->load->language('extension/module/so_category_slider');
		$data['objlang'] = $this->language;

		// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();

		// Load model
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_category_slider');
		$this->load->model('localisation/language');
		
		$this->document->setTitle($this->language->get('heading_title'));

		// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		//Get Module Id New
		$moduleid_new= $this->model_extension_module_so_category_slider->getModuleId(); // Get module id
		$module_id = '';	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];
				$this->model_setting_module->addModule('so_category_slider', $this->request->post);

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
				$this->response->redirect($this->url->link('extension/module/so_category_slider', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$default = array(
			'name' 					=> '',
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

			'categorys'				=> array(),
			'category'				=> '',
			'child_category'		=> '1',
			'category_depth'		=> '1',
			'product_sort'			=> 'p.price',
			'product_ordering'		=> 'ASC',
			'source_limit'			=> '6',
			
			'display_feature'		=> '0',
			'product_features'		=> array(),

			'cat_title_display'		=> '1',
			'cat_title_maxcharacs'	=> '25',
			'cat_image_display'		=> '1',
			'width_cat'				=> '200',
			'height_cat'			=> '200',
			'placeholder_path'		=> 'nophoto.png',
			'child_category_cat'	=> '1',
			'source_limit_cat'		=> '6',
			'cat_sub_title_maxcharacs'		=> '25',
			'cat_all_product'		=> '1',

			'display_title'			=> '1',
			'title_maxlength'		=> '50',
			'display_description'	=> '1',
			'description_maxlength' => '50',
			'product_image' 		=> '1',
			'product_image_num' 	=> '1',
			'width' 				=> '200',
			'height' 				=> '200',
			'nb_row'				=> '1',
			'display_rating'		=> '1',
			'display_price'			=> '1',
			'display_addtocart'		=> '1',
			'display_wishlist' 		=> '1',
			'display_compare'		=> '1',
			'display_sale'			=> '1',
			'display_new'			=> '1',
			'date_day'				=> '7',

			'margin'				=> '5',
			'slideBy'				=> '1',
			'autoplay'				=> '0',
			'autoplay_timeout'		=> '5000',
			'pausehover'			=> '0',
			'autoplaySpeed'			=> '1000',
			'startPosition'			=> '0',
			'mouseDrag'				=> '1',
			'touchDrag'				=> '1',
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
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			//$module_info = $default;
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info =  array_merge($default,$module_info);//check data empty database
			$data['categorys'] = $this->model_extension_module_so_category_slider->getCategories();
			$product_features = $module_info['product_feature'];
			if($product_features != null){
				foreach ($product_features as $product_feature_id) {
					$product_feature_info = $this->model_catalog_product->getProduct($product_feature_id);
					if ($product_feature_info) {
						$module_info['product_features'][] = array(
							'product_id' 		=> $product_feature_info['product_id'],
							'product_name'      => $product_feature_info['name']
						);
					}
				}
			}	
			$data['action'] = $this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		} else {
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info,$this->request->post);
				$product_features = $module_info['product_feature'];
				if($product_features != null){
					if($product_features != null){
						foreach ($product_features as $product_feature_id) {
							$product_feature_info = $this->model_catalog_product->getProduct($product_feature_id);
							if ($product_feature_info) {
								$module_info['product_features'][] = array(
									'product_id' 		=> $product_feature_info['product_id'],
									'product_name'      => $product_feature_info['name']
								);
							}
						}
					}	
				}		
			}
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] = $this->language->get('text_create_new_module');
			$data['categorys'] = $this->model_extension_module_so_category_slider->getCategories();
			
		}

		$data['user_token'] = $this->session->data['user_token'];
		
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
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_category_slider' );
		$data['link'] = $this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_category_slider&user_token=' . $this->session->data['user_token']);
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
		
		//Number Product Image
		$data['product_image_nums'] = array(
			'1'   => '1',
			'2'   => '2'
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
		//button page
		$data['button_pages'] = array(
			'top' => $this->language->get('value_top'),
			'under' => $this->language->get('value_under'),
		);
		//Rows
		$data['nb_rows'] = array(
			'1'   => '1',
			'2'   => '2',
			'3'   => '3',
			'4'   => '4',
			'5'   => '5',
			'6'   => '6',
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
		$this->response->setOutput($this->load->view('extension/module/so_category_slider', $data));
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
				'href' => $this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_category_slider', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_category_slider')) {
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

		if ($this->request->post['source_limit_cat'] != '0' && !filter_var($this->request->post['source_limit_cat'],FILTER_VALIDATE_INT) || $this->request->post['source_limit_cat'] < 0) {
			$this->error['source_limit_cat'] = $this->language->get('error_source_limit_cat');
		}
		
		if ($this->request->post['display_feature'] == 1 && $this->request->post['product_feature'] == null) {
			$this->error['product_feature'] = $this->language->get('error_product_feature');
		}

		if ($this->request->post['cat_title_maxcharacs'] != '0' && !filter_var($this->request->post['cat_title_maxcharacs'],FILTER_VALIDATE_INT) || $this->request->post['cat_title_maxcharacs'] < 0) {

			$this->error['cat_title_maxcharacs'] = $this->language->get('error_cat_title_maxcharacs');
		}

		if ($this->request->post['cat_sub_title_maxcharacs'] != '0' && !filter_var($this->request->post['cat_sub_title_maxcharacs'],FILTER_VALIDATE_INT) || $this->request->post['cat_sub_title_maxcharacs'] < 0) {
			$this->error['cat_sub_title_maxcharacs'] = $this->language->get('error_cat_sub_title_maxcharacs');
		}

		if ($this->request->post['title_maxlength'] != '0' && !filter_var($this->request->post['title_maxlength'],FILTER_VALIDATE_FLOAT) || $this->request->post['title_maxlength'] < 0) {

			$this->error['title_maxlength'] = $this->language->get('error_title_maxlength');
		}

		if ($this->request->post['description_maxlength'] != '0' && !filter_var($this->request->post['description_maxlength'],FILTER_VALIDATE_FLOAT) || $this->request->post['description_maxlength'] < 0) {
			$this->error['description_maxlength'] = $this->language->get('error_description_maxlength');
		}

		if ($this->request->post['width'] != '0' && !filter_var($this->request->post['width'],FILTER_VALIDATE_INT) || $this->request->post['width'] <= 0) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if ($this->request->post['height'] != '0' && !filter_var($this->request->post['height'],FILTER_VALIDATE_INT) || $this->request->post['height'] <= 0) {
			$this->error['height'] = $this->language->get('error_height');
		}
		
		if (!filter_var($this->request->post['date_day'],FILTER_VALIDATE_INT) || $this->request->post['date_day'] <= 0) {
			$this->error['date_day'] = $this->language->get('error_date_day');
		}
		
		if ($this->request->post['width_cat'] != '0' && !filter_var($this->request->post['width_cat'],FILTER_VALIDATE_INT) || $this->request->post['width_cat'] <= 0) {
			$this->error['width_cat'] = $this->language->get('error_width_cat');
		}

		if ($this->request->post['height_cat'] != '0' && !filter_var($this->request->post['height_cat'],FILTER_VALIDATE_INT) || $this->request->post['height_cat'] <= 0) {
			$this->error['height_cat'] = $this->language->get('error_height_cat');
		}

		if ($this->request->post['placeholder_path'] == null ) {
			$this->error['placeholder_path'] = $this->language->get('error_placeholder_path');
		}

		if (!filter_var($this->request->post['navSpeed'],FILTER_VALIDATE_INT) || $this->request->post['navSpeed'] < 0) {
			$this->error['navSpeed'] = $this->language->get('error_navSpeed');
		}

		if ($this->request->post['autoplay_timeout'] != '0' && !filter_var($this->request->post['autoplay_timeout'],FILTER_VALIDATE_INT) || $this->request->post['autoplay_timeout'] < 0) {
			$this->error['autoplay_timeout'] = $this->language->get('error_autoplay_timeout');
		}
		if ($this->request->post['duration'] != '0' && !filter_var($this->request->post['duration'],FILTER_VALIDATE_INT) || $this->request->post['duration'] < 0) {
			$this->error['duration'] = $this->language->get('error_duration');
		}

		if ($this->request->post['delay'] != '0' && !filter_var($this->request->post['delay'],FILTER_VALIDATE_INT) || $this->request->post['delay'] < 0) {
			$this->error['delay'] = $this->language->get('error_delay');
		}
		if (!filter_var($this->request->post['margin'],FILTER_VALIDATE_INT) || $this->request->post['margin'] < 0) {
			$this->error['margin'] = $this->language->get('error_margin');
		}

		if (!filter_var($this->request->post['slideBy'],FILTER_VALIDATE_INT) || $this->request->post['slideBy'] < 0) {
			$this->error['slideBy'] = $this->language->get('error_slideBy');
		}
		if (!filter_var($this->request->post['autoplaySpeed'],FILTER_VALIDATE_INT) || $this->request->post['autoplaySpeed'] < 0) {
			$this->error['autoplaySpeed'] = $this->language->get('error_autoplaySpeed');
		}

		if ($this->request->post['startPosition'] != '0' && !filter_var($this->request->post['startPosition'],FILTER_VALIDATE_INT) || $this->request->post['startPosition'] < 0) {
			$this->error['startPosition'] = $this->language->get('error_startPosition');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
	public function autocomplete_product_feature() {
		$json = array();	
		$this->load->model('extension/module/so_category_slider');
		if (isset($this->request->get['filter_name'])) {
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_module_so_category_slider->getProducts_deals($filter_data);
			foreach ($results as $result) {
				$json[] = array(
					'product_id' 	=> $result['product_id'],
					'product_name'	=> strip_tags(html_entity_decode($result['product_name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['product_name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
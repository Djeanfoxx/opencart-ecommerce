<?php
class ControllerExtensionModuleSodeals extends Controller {
	private $error = array();
	private $data = array();
	public function index() {
	// Load language
	$this->load->language('extension/module/so_deals');
	$data['objlang'] = $this->language;
	$data['entry_button_save'] 				= $this->language->get('entry_button_save');
	$data['entry_button_save_and_edit'] 	= $this->language->get('entry_button_save_and_edit');
	$data['entry_button_save_and_new'] 		= $this->language->get('entry_button_save_and_new');
	$data['entry_button_cancel'] 			= $this->language->get('entry_button_cancel');
	$data['heading_title_so'] 				= $this->language->get('heading_title_so');
	$data['button_add_module'] 				= $this->language->get('button_add_module');
	$data['entry_button_delete'] 			= $this->language->get('entry_button_delete');
	$data['entry_name_desc'] 				= $this->language->get('entry_name_desc');
	$data['entry_name'] 					= $this->language->get('entry_name');
	$data['entry_head_name_desc'] 			= $this->language->get('entry_head_name_desc');
	$data['entry_head_name'] 				= $this->language->get('entry_head_name');
	$data['entry_display_title_module_desc'] 	= $this->language->get('entry_display_title_module_desc');
	$data['text_yes'] 						= $this->language->get('text_yes');
	$data['text_no'] 						= $this->language->get('text_no');
	$data['entry_status_desc'] 				= $this->language->get('entry_status_desc');
	$data['entry_status'] 					= $this->language->get('entry_status');
	$data['entry_module'] 					= $this->language->get('entry_module');
	$data['entry_source_option'] 			= $this->language->get('entry_source_option');
	$data['entry_items_option'] 			= $this->language->get('entry_items_option');
	$data['entry_image_option'] 			= $this->language->get('entry_image_option');
	$data['entry_effect_option'] 			= $this->language->get('entry_effect_option');
	$data['entry_advanced_option'] 			= $this->language->get('entry_advanced_option');
	$data['entry_class_suffix_desc'] 		= $this->language->get('entry_class_suffix_desc');
	$data['entry_class_suffix'] 			= $this->language->get('entry_class_suffix');
	$data['entry_open_link_desc'] 			= $this->language->get('entry_open_link_desc');
	$data['entry_open_link'] 				= $this->language->get('entry_open_link');
	$data['entry_include_js_desc'] 			= $this->language->get('entry_include_js_desc');
	$data['text_owl_carousel'] 				= $this->language->get('text_owl_carousel');
	$data['text_slick_slider'] 				= $this->language->get('text_slick_slider');
	$data['entry_nb_column0_desc'] 			= $this->language->get('entry_nb_column0_desc');
	$data['entry_nb_column1_desc'] 			= $this->language->get('entry_nb_column1_desc');
	$data['entry_nb_column2_desc'] 			= $this->language->get('entry_nb_column2_desc');
	$data['entry_nb_column3_desc'] 			= $this->language->get('entry_nb_column3_desc');
	$data['entry_column'] 					= $this->language->get('entry_column');
	$data['entry_nb_row_desc'] 				= $this->language->get('entry_nb_row_desc');
	$data['entry_nb_row'] 					= $this->language->get('entry_nb_row');
	$data['entry_display_feature_desc'] 	= $this->language->get('entry_display_feature_desc');
	$data['entry_display_feature'] 			= $this->language->get('entry_display_feature');
	$data['entry_product_feature_desc'] 	= $this->language->get('entry_product_feature_desc');
	$data['entry_product_feature'] 			= $this->language->get('entry_product_feature');
	$data['entry_position_thumbnail_desc'] 	= $this->language->get('entry_position_thumbnail_desc');
	$data['entry_position_thumbnail'] 		= $this->language->get('entry_position_thumbnail');
	$data['entry_category_desc'] 			= $this->language->get('entry_category_desc');
	$data['entry_category'] 				= $this->language->get('entry_category');
	$data['entry_child_category_desc'] 		= $this->language->get('entry_child_category_desc');
	$data['entry_child_category'] 			= $this->language->get('entry_child_category');
	$data['entry_include'] 					= $this->language->get('entry_include');
	$data['entry_exclude'] 					= $this->language->get('entry_exclude');
	$data['entry_category_depth_desc'] 		= $this->language->get('entry_category_depth_desc');
	$data['entry_category_depth'] 			= $this->language->get('entry_category_depth');
	$data['entry_product_order_desc'] 		= $this->language->get('entry_product_order_desc');
	$data['entry_product_order'] 			= $this->language->get('entry_product_order');
	$data['entry_ordering_desc'] 			= $this->language->get('entry_ordering_desc');
	$data['entry_ordering'] 				= $this->language->get('entry_ordering');
	$data['entry_source_limit_desc'] 		= $this->language->get('entry_source_limit_desc');
	$data['entry_source_limit'] 			= $this->language->get('entry_source_limit');
	$data['entry_display_title_desc'] 		= $this->language->get('entry_display_title_desc');
	$data['entry_display_title'] 			= $this->language->get('entry_display_title');
	$data['entry_title_maxlength_desc'] 	= $this->language->get('entry_title_maxlength_desc');
	$data['entry_title_maxlength'] 			= $this->language->get('entry_title_maxlength');
	$data['entry_display_description_desc'] = $this->language->get('entry_display_description_desc');
	$data['entry_display_description'] 		= $this->language->get('entry_display_description');
	$data['entry_description_maxlength_desc'] 	= $this->language->get('entry_description_maxlength_desc');
	$data['entry_description_maxlength'] 		= $this->language->get('entry_description_maxlength');
	$data['entry_display_price_desc'] 		= $this->language->get('entry_display_price_desc');
	$data['entry_display_price'] 			= $this->language->get('entry_display_price');
	$data['entry_display_add_to_cart_desc'] = $this->language->get('entry_display_add_to_cart_desc');
	$data['entry_display_add_to_cart'] 		= $this->language->get('entry_display_add_to_cart');
	$data['entry_display_wishlist_desc'] 	= $this->language->get('entry_display_wishlist_desc');
	$data['entry_display_wishlist'] 		= $this->language->get('entry_display_wishlist');
	$data['entry_display_compare_desc'] 	= $this->language->get('entry_display_compare_desc');
	$data['entry_display_compare'] 			= $this->language->get('entry_display_compare');
	$data['entry_display_rating_desc'] 		= $this->language->get('entry_display_rating_desc');
	$data['entry_display_rating'] 			= $this->language->get('entry_display_rating');
	$data['entry_display_sale_desc'] 		= $this->language->get('entry_display_sale_desc');
	$data['entry_display_sale'] 			= $this->language->get('entry_display_sale');
	$data['entry_display_new_desc'] 		= $this->language->get('entry_display_new_desc');
	$data['entry_display_new'] 				= $this->language->get('entry_display_new');
	$data['entry_date_day_desc'] 			= $this->language->get('entry_date_day_desc');
	$data['entry_date_day'] 				= $this->language->get('entry_date_day');
	$data['entry_product_image_num_desc'] 	= $this->language->get('entry_product_image_num_desc');
	$data['entry_product_image_num'] 		= $this->language->get('entry_product_image_num');
	$data['entry_product_get_image_data_desc'] 	= $this->language->get('entry_product_get_image_data_desc');
	$data['entry_product_get_image_data'] 		= $this->language->get('entry_product_get_image_data');
	$data['entry_product_get_image_image_desc'] = $this->language->get('entry_product_get_image_image_desc');
	$data['entry_product_get_image_image'] 		= $this->language->get('entry_product_get_image_image');
	$data['entry_width_desc'] 				= $this->language->get('entry_width_desc');
	$data['entry_width'] 					= $this->language->get('entry_width');
	$data['entry_height_desc'] 				= $this->language->get('entry_height_desc');
	$data['entry_height'] 					= $this->language->get('entry_height');
	$data['entry_placeholder_path_desc'] 	= $this->language->get('entry_placeholder_path_desc');
	$data['entry_placeholder_path'] 		= $this->language->get('entry_placeholder_path');
	$data['entry_margin_desc'] 				= $this->language->get('entry_margin_desc');
	$data['entry_margin'] 					= $this->language->get('entry_margin');
	$data['entry_slideBy_desc'] 			= $this->language->get('entry_slideBy_desc');
	$data['entry_slideBy'] 					= $this->language->get('entry_slideBy');
	$data['entry_autoplay_desc'] 			= $this->language->get('entry_autoplay_desc');
	$data['entry_autoplay'] 				= $this->language->get('entry_autoplay');
	$data['entry_autoplayTimeout_desc'] 	= $this->language->get('entry_autoplayTimeout_desc');
	$data['entry_autoplayTimeout'] 			= $this->language->get('entry_autoplayTimeout');
	$data['entry_autoplayHoverPause_desc'] 	= $this->language->get('entry_autoplayHoverPause_desc');
	$data['entry_autoplayHoverPause'] 		= $this->language->get('entry_autoplayHoverPause');
	$data['entry_autoplaySpeed_desc'] 		= $this->language->get('entry_autoplaySpeed_desc');
	$data['entry_autoplaySpeed'] 			= $this->language->get('entry_autoplaySpeed');
	$data['entry_startPosition_desc'] 		= $this->language->get('entry_startPosition_desc');
	$data['entry_startPosition'] 			= $this->language->get('entry_startPosition');
	$data['entry_mouseDrag_desc'] 			= $this->language->get('entry_mouseDrag_desc');
	$data['entry_mouseDrag'] 				= $this->language->get('entry_mouseDrag');
	$data['entry_touchDrag_desc'] 			= $this->language->get('entry_touchDrag_desc');
	$data['entry_touchDrag'] 				= $this->language->get('entry_touchDrag');
	$data['entry_loop_desc'] 				= $this->language->get('entry_loop_desc');
	$data['entry_loop'] 					= $this->language->get('entry_loop');
	$data['entry_dots_desc'] 				= $this->language->get('entry_dots_desc');
	$data['entry_dots'] 					= $this->language->get('entry_dots');
	$data['entry_dotsSpeed_desc'] 			= $this->language->get('entry_dotsSpeed_desc');
	$data['entry_dotsSpeed'] 				= $this->language->get('entry_dotsSpeed');
	$data['entry_navs_desc'] 				= $this->language->get('entry_navs_desc');
	$data['entry_navs'] 					= $this->language->get('entry_navs');
	$data['entry_navspeed_desc'] 			= $this->language->get('entry_navs');
	$data['entry_navspeed'] 				= $this->language->get('entry_navspeed');
	$data['entry_effect_desc'] 				= $this->language->get('entry_effect_desc');
	$data['entry_effect'] 					= $this->language->get('entry_effect');
	$data['entry_duration_desc'] 			= $this->language->get('entry_duration_desc');
	$data['entry_duration'] 				= $this->language->get('entry_duration');
	$data['entry_delay_desc'] 				= $this->language->get('entry_delay_desc');
	$data['entry_delay'] 					= $this->language->get('entry_delay');
	$data['entry_store_layout_desc'] 		= $this->language->get('entry_store_layout_desc');
	$data['entry_store_layout'] 			= $this->language->get('entry_store_layout');
	$data['entry_pre_text_desc'] 			= $this->language->get('entry_pre_text_desc');
	$data['entry_pre_text']	 				= $this->language->get('entry_pre_text');
	$data['entry_post_text_desc']	 		= $this->language->get('entry_post_text_desc');
	$data['entry_post_text']	 			= $this->language->get('entry_post_text');
	$data['entry_use_cache_desc']	 		= $this->language->get('entry_use_cache_desc');
	$data['entry_use_cache']	 			= $this->language->get('entry_use_cache');
	$data['entry_cache_time_desc']	 		= $this->language->get('entry_cache_time_desc');
	$data['entry_cache_time']	 			= $this->language->get('entry_cache_time');
	$data['entry_button_clear_cache']	 			= $this->language->get('entry_button_clear_cache');
	
	// Load breadcrumbs
	$data['breadcrumbs'] = $this->_breadcrumbs();
	
	// Load model	
	$this->load->model('catalog/product');
	$this->load->model('catalog/category');
	$this->load->model('setting/module');
	$this->load->model('extension/module/so_deals');
	
	$this->document->setTitle($this->language->get('heading_title'));
		
	// Delete Module
	if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
		$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
		$this->response->redirect($this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'], 'SSL'));
	}
	// Get Module Id new 
	$moduleid_new= $this->model_extension_module_so_deals->getModuleId();
	$module_id = '';	
	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		if (!isset($this->request->get['module_id'])) {
			$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
			$module_id = $moduleid_new[0]['Auto_increment'];
			$this->model_setting_module->addModule('so_deals', $this->request->post);
			
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
			$this->response->redirect($this->url->link('extension/module/so_deals', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
		}elseif($action == "save_new"){
			$this->response->redirect($this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}else{
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
	}
	if (!isset($this->request->get['module_id'])) {
		$data['action'] = $this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'], 'SSL');
	} else {
		$data['action'] = $this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
	}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		$default = array(
			'name' 					=> '',
			'module_description'	=> array(),
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'class_suffix'			=> '',
			'item_link_target'		=> '_blank',
			
			'include_js'			=> 'owlCarousel',
			'position_thumbnail'	=> 'vertical',
			
			'nb_column0'			=> '4',
			'nb_column1'			=> '4',
			'nb_column2'			=> '3',
			'nb_column3'			=> '2',
			'nb_column4'			=> '1',
			'nb_row'				=> '1',
			
			'categorys'				=> array(),
			'child_category'		=> '1',	
			'category_depth'		=> '1',
			'product_sort'			=> 'p.price',
			'product_ordering'		=> 'ASC',
			'source_limit'			=> '6',
			
			'display_feature'		=> '0',
			'product_features'		=> array(),
			
			'display_title'			=> '1',
			'title_maxlength'		=> '50',
			'display_description'	=> '1',
			'description_maxlength' => '100',
			'display_price'			=> '1',
			'display_countdown'			=> '1',
			'display_all_countdown'			=> '0',
			'display_addtocart'		=> '1',
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
			'placeholder_path'		=> 'nophoto.png',
			'margin'				=> '5',
			'slideBy'				=> '1',
			'autoplay'				=> '0',
			'autoplayTimeout'		=> '5000',
			'autoplayHoverPause'	=> '0',
			'autoplaySpeed'			=> '1000',
			'startPosition'			=> '0',
			'mouseDrag'				=> '1',
			'touchDrag'				=> '1',
			'loop'					=> '1',
			'button_page' 			=> 'top',
			'dots'					=> '1',
			'dotsSpeed'				=> '500',
			'navs'					=> '1',
			'navSpeed'				=> '500',
			'effect'				=> 'starwars',
			'duration'				=> '800',
			'delay'					=> '500',
			
			'store_layout'			=> 'default',
			'post_text'				=> '',
			'pre_text'				=> '',
			'use_cache'				=> '0',
			'cache_time'			=> '3600'
		);
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info =  array_merge($default,$module_info);//check data empty database
			$categorys = $module_info['category'];
			foreach ($categorys as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);

				if ($category_info) {
					$module_info['categorys'][] = array(
						'category_id' => $category_info['category_id'],
						'name'       => $category_info['name']
					);
				}
			}
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
			$data['action'] = $this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
		} else {
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info,$this->request->post);
				$categorys = $module_info['category'];
				if($categorys != null){
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
			}
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'], 'SSL');
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
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_deals' );
		$data['link'] = $this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('module/so_basic_products&user_token=' . $this->session->data['user_token']);
		//--------------------------------Load Data -------------------------------------------
		
		// Store Layout
		$data['store_layouts'] = array(
			'default' 	=> $this->language->get('value_default')	,
			'home6' 	=> $this->language->get('value_home6')	,
			'home8' 	=> $this->language->get('value_home8')	,
			'home9' 	=> $this->language->get('value_home9')	,
			'home10' 	=> $this->language->get('value_home10')
		);
		
		// Link Target
		$data['item_link_targets'] = array(
			'_blank' => $this->language->get('value_blank'),
			'_self'  => $this->language->get('value_self'),
		);
		
		// Position Thumnails
		$data['position_thumbnails'] = array(
			'vertical' 		=> $this->language->get('value_vertical'),
			'horizontal'  	=> $this->language->get('value_horizontal'),
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
		//rows
		$data['nb_rows'] = array(
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
		//button page
		$data['button_pages'] = array(
			'top' => $this->language->get('value_top'),
			'under' => $this->language->get('value_under'),
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
		$this->response->setOutput($this->load->view('extension/module/so_deals', $data));
	}
	public function remove_cache()	{
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
				'href' => $this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_deals', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_deals')) {
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
		
		if ($this->request->post['display_feature'] == 1 && $this->request->post['product_feature'] == null) {
			$this->error['product_feature'] = $this->language->get('error_product_feature');
		}
		
		if ($this->request->post['title_maxlength'] != '0' && !filter_var($this->request->post['title_maxlength'],FILTER_VALIDATE_INT) || $this->request->post['title_maxlength'] < 0) {
			
			$this->error['title_maxlength'] = $this->language->get('error_title_maxlength');
		}
		
		if ($this->request->post['description_maxlength'] != '0' && !filter_var($this->request->post['description_maxlength'],FILTER_VALIDATE_INT) || $this->request->post['description_maxlength'] < 0) {
			$this->error['description_maxlength'] = $this->language->get('error_description_maxlength');
		}
		
		if (!filter_var($this->request->post['margin'],FILTER_VALIDATE_INT) && $this->request->post['title_maxlength'] != '0'  || $this->request->post['margin'] < 0) {
			$this->error['margin'] = $this->language->get('error_margin');
		}
		
		if (!filter_var($this->request->post['slideBy'],FILTER_VALIDATE_INT) || $this->request->post['slideBy'] < 0) {
			$this->error['slideBy'] = $this->language->get('error_slideBy');
		}
		
		if (!filter_var($this->request->post['autoplayTimeout'],FILTER_VALIDATE_INT) || $this->request->post['autoplayTimeout'] < 0) {
			$this->error['autoplayTimeout'] = $this->language->get('error_autoplayTimeout');
		}
		
		if (!filter_var($this->request->post['autoplaySpeed'],FILTER_VALIDATE_INT) || $this->request->post['autoplaySpeed'] < 0) {
			$this->error['autoplaySpeed'] = $this->language->get('error_autoplaySpeed');
		}

		if ($this->request->post['startPosition'] != '0' && !filter_var($this->request->post['startPosition'],FILTER_VALIDATE_INT) || $this->request->post['startPosition'] < 0) {
			$this->error['startPosition'] = $this->language->get('error_startPosition');
		}
		
		if (!filter_var($this->request->post['dotsSpeed'],FILTER_VALIDATE_INT) || $this->request->post['dotsSpeed'] < 0) {
			$this->error['dotsSpeed'] = $this->language->get('error_dotsSpeed');
		}
		
		if (!filter_var($this->request->post['navSpeed'],FILTER_VALIDATE_INT) || $this->request->post['navSpeed'] < 0) {
			$this->error['navSpeed'] = $this->language->get('error_navSpeed');
		}
		
		if (!filter_var($this->request->post['duration'],FILTER_VALIDATE_INT) || $this->request->post['duration'] < 0) {
			$this->error['duration'] = $this->language->get('error_duration');
		}
		
		if (!filter_var($this->request->post['delay'],FILTER_VALIDATE_INT) || $this->request->post['delay'] < 0) {
			$this->error['delay'] = $this->language->get('error_delay');
		}
		if (!filter_var($this->request->post['width'],FILTER_VALIDATE_FLOAT) || $this->request->post['width'] < 0 || $this->request->post['width'] > 5000) {
			$this->error['width'] = $this->language->get('error_width');
		}
		if (!filter_var($this->request->post['height'],FILTER_VALIDATE_FLOAT) || $this->request->post['height'] < 0 || $this->request->post['height'] > 5000) {
			$this->error['height'] = $this->language->get('error_height');
		}
		if ((utf8_strlen($this->request->post['placeholder_path']) < 5) || (utf8_strlen($this->request->post['placeholder_path']) > 64)) {
			$this->error['placeholder_path'] = $this->language->get('error_placeholder_path');
		}
		
		if (!filter_var($this->request->post['date_day'],FILTER_VALIDATE_INT) || $this->request->post['date_day'] <= 0) {
			$this->error['date_day'] = $this->language->get('error_date_day');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
	
	public function autocomplete_category() {
		$json = array();	
		$this->load->model('extension/module/so_deals');
		if (isset($this->request->get['filter_name'])) {
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_module_so_deals->getCategories($filter_data);
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
	
	public function autocomplete_product_feature() {
		$json = array();	
		$this->load->model('extension/module/so_deals');
		if (isset($this->request->get['filter_name'])) {
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_module_so_deals->getProducts_deals($filter_data);
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
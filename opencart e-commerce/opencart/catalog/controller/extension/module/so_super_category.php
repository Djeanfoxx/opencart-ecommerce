<?php
class ControllerExtensionModuleSosupercategory extends Controller {
	protected $hash = null;
	public function index($setting) {
		$this->load->language('extension/module/so_super_category');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_view_all'] = $this->language->get('text_view_all');
		
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('extension/module/so_super_category');
		$this->load->model('tool/image');
		
		// Add Style
		$this->document->addStyle('catalog/view/javascript/so_super_category/css/style.css');
		if (!defined ('OWL_CAROUSEL')){
			$this->document->addStyle('catalog/view/javascript/so_super_category/css/animate.css');
			$this->document->addStyle('catalog/view/javascript/so_super_category/css/owl.carousel.css');
			$this->document->addScript('catalog/view/javascript/so_super_category/js/owl.carousel.js');
			define( 'OWL_CAROUSEL', 1 );
		}

		$pre_text 	= $setting['pre_text'];
		$post_text 	= $setting['post_text'];
		
		$setting['pre_text']	= '';
		$setting['post_text']	= '';
		
		$default = array(
			'objlang'						=> $this->language,
			'name' 							=> '',
			'module_description'			=> array(),
			'disp_title_module'				=> '1',
			'status'						=> '1',
			'advanced_mod_class_suffix'		=> '',
			'item_link_target'				=> '_blank',
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
			'subcategory_center'			=> 1,
			'subcategory_display_navigation'=> 1,
			'subcategory_display_loop'		=> 1,
			'subcategory_margin_right'		=> '5',
			'subcategory_slideby'			=> '1',
			'subcategory_auto_play'			=> 1,
			'subcategory_auto_interval_timeout'=> '300',
			'subcategory_auto_hover_pause'	=> 1,
			'subcategory_auto_play_speed'	=> '300',
			'subcategory_navigation_speed'	=> '3000',
			'subcategory_start_position'	=> '0',
			'subcategory_mouse_drag'		=> 1,
			'subcategory_touch_drag'		=> 1,
			'slider_auto_play'				=> 1,
			'slider_display_navigation'		=> 1,
			'slider_display_loop'			=> 1,
			'slider_mouse_drag'				=> 1,
			'slider_touch_drag'				=> 1,
			'slider_auto_hover_pause'		=> 1,
			'slider_auto_interval_timeout'	=> '5000',
			'slider_auto_play_speed'		=> '2000',
			
			'post_text'						=> '',
			'pre_text'						=> '',
			'use_cache'						=> '0',
			'cache_time'					=> '3600',
			'moduleid'						=> $setting['moduleid'],
			'setting'						=> serialize($setting),
			'tag_id'						=> 'so_super_category_'.$setting['moduleid'],			
			'direction'						=> ($this->language->get('direction') == 'rtl' ? 'true' : 'false'),
			'direction_class'				=> ($this->language->get('direction') == 'rtl' ? 'so-super-category-rtl' : 'so-super-category-ltr')
		);
		
		$data =  array_merge($default,$setting);//check data empty setting
		$data['subcategory_center']				= $setting['subcategory_center'] == 1 ? 'true' : 'false';
		$data['subcategory_display_navigation']	= $setting['subcategory_display_navigation'] == 1 ? 'true' : 'false';
		$data['subcategory_display_loop']		= $setting['subcategory_display_loop'] == 1 ? 'true' : 'false';
		
		$data['subcategory_auto_play']			= $setting['subcategory_auto_play'] == 1 ? 'true' : 'false';
		$data['subcategory_auto_hover_pause']	= $setting['subcategory_auto_hover_pause'] == 1 ? 'true' : 'false';
		$data['subcategory_mouse_drag']			= $setting['subcategory_mouse_drag'] == 1 ? 'true' : 'false';
		$data['subcategory_touch_drag']			= $setting['subcategory_touch_drag'] == 1 ? 'true' : 'false';
		$data['slider_auto_play']				= $setting['slider_auto_play'] == 1 ? 'true' : 'false';
		
		$data['slider_display_navigation']		= $setting['slider_display_navigation'];
		$data['slider_display_loop']			= $setting['slider_display_loop'] == 1 ? 'true' : 'false';
		$data['slider_mouse_drag']				= $setting['slider_mouse_drag'] == 1 ? 'true' : 'false';
		$data['slider_touch_drag']				= $setting['slider_touch_drag'] == 1 ? 'true' : 'false';
		$data['slider_auto_hover_pause']		= $setting['slider_auto_hover_pause'] == 1 ? 'true' : 'false';
		
		$http = $_SERVER["HTTPS"]  ? 'https://' : 'http://';
		$data['ajaxurl'] 		= $http."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$data['theme_config']	= $this->config->get('theme_default_directory');
		$data['tag'] 			= 'cat_slider_' . rand() . time();
		$data['instance']		= rand() . time();
		
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['head_name'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['head_name'], ENT_QUOTES, 'UTF-8');
		}else{
			$data['head_name']              = reset($setting['module_description'])['head_name'];
		}

		$data['pre_text'] = html_entity_decode($pre_text, ENT_QUOTES, 'UTF-8');
		$data['post_text'] = html_entity_decode($post_text, ENT_QUOTES, 'UTF-8');
		
		$data['class_spcat']				= 'spcat00-' . $data['product_column0'] . ' spcat01-' . $data['product_column1'] . ' spcat02-' . $data['product_column2'] . ' spcat03-' . $data['product_column3'] . ' spcat04-' . $data['product_column4'];
		
		// get category son
		$listCategory_son = self::getCategoryson($setting);
		
		$data['category_tree'] = array();
		$data['tabs'] = array();
		$data['category_parent']  = array();
		if($listCategory_son != null){
			$listCategory_son_str = implode(",",$listCategory_son);
			$data['category_parent'] 			= $this->model_catalog_category->getCategory($listCategory_son_str);
			$data['category_parent']['link']    = $this->url->link('product/category', 'path=' .$setting['category']);
			foreach($listCategory_son as $item){
				$info_category = $this->model_catalog_category->getCategory($item);
				if ($info_category['image'] != "") {
					$image = $this->model_tool_image->resize($info_category['image'], $setting['category_width'], $setting['category_height']);
				}else {
					$url = file_exists("image/so_super_category/images/".$setting['category_placeholder_path']);
					if ($url) {
						$image_name = "so_super_category/images/".$setting['category_placeholder_path'];
					} else {
						$image_name = "no_image.png";
					}
					$image = $this->model_tool_image->resize($image_name, $setting['category_width'], $setting['category_height']);
					
				}

				$data['category_tree'][] = array(
					'category_id' 		=> $info_category['category_id'],
					'name'				=> $info_category['name'],
					'name_maxlength' 	=> ((strlen($info_category['name']) > $setting['sub_category_title_maxlength'] && $setting['sub_category_title_maxlength'] !=0)  ? utf8_substr(strip_tags(html_entity_decode($info_category['name'], ENT_QUOTES, 'UTF-8')), 0, $setting['sub_category_title_maxlength']) . '..' : $info_category['name']),
					'link' 				=> $this->url->link('product/category', 'path=' . $item),
					'image' 			=> $image
				);
			}
		
			$tabs = array();
			$filters = $setting['field_product_tab'];
			$articles_filter = array();
			$filter_preload = $setting['field_preload'];
			
			$setting['product_sort'] = $filter_preload;
			$setting['start']             	=0;
			$data['rl_loaded'] 				= $setting['start'] ;
			$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
			if($is_ajax && isset($_POST['is_ajax_super_category']) && $_POST['is_ajax_super_category'] ){
				foreach ($filters as $filter) {
					$filter_data = array(
						'filter_category_id'  	=> implode(',',$listCategory_son),
						'sort'         			=> $setting['product_sort'],
						'order'        			=> $setting['product_ordering'],
						'limit'        			=> $setting['limitation'],
						'start' 	   			=> $setting['start']
					);
					$aritles['count'] = $this->model_extension_module_so_super_category->getTotalProducts_super_category($filter_data);
					$aritles['category_id'] = $filter;
					$aritles['title'] = $this->getLabel($filter);
					array_unshift($articles_filter, $aritles);
				}
				
				foreach ($articles_filter as $filter) {
					if ($filter['count'] > 0) {
						if ($filter['category_id'] == $filter_preload) {
							$filter['sel'] = 'sel';
							$setting['product_sort'] = $filter_preload;
							$filter['child'] = self::getProducts($listCategory_son, $setting);
						}
						$tabs[$filter['category_id']] = $filter;
					}
				}
				
				$tabs = $tabs;
				$setting 						= unserialize($_POST['setting']);
				$objlang						= $this->language;
				$listCategory_son 				= self::getCategoryson($setting);
				$setting['start'] 				= $_POST['ajax_reslisting_start'];
				$start							= $setting['start'];
				$product_image 					= $setting['product_image'];
				$direction 						=  $this->language->get('direction') == 'rtl' ?  'true' : 'false';
				$type_show 						= $setting['type_show'];
				$product_delay 					= $setting['product_delay'];
				$product_duration				= $setting['product_duration'];
				$effect							= $setting['effect'];
				$product_display_title 			= $setting['product_display_title'];
				$product_display_description 	= $setting['product_display_description'];
				$product_display_price 			= $setting['product_display_price'];
				$product_image					= $setting['product_image'];
				$display_add_to_cart    		= $setting['display_add_to_cart'];
				$display_wishlist   			= $setting['display_wishlist'];
				$display_compare    			= $setting['display_compare'];
				$product_column0				= $setting['product_column0'];
				$product_column1				= $setting['product_column1'];
				$product_column2				= $setting['product_column2'];
				$product_column3 				= $setting['product_column3'];
				$product_column4 				= $setting['product_column4'];
				$moduleid						= $setting['moduleid'];
				$slider_display_navigation 		= $setting['slider_display_navigation'];
				$slider_display_loop 			= $setting['slider_display_loop'];
				$slider_auto_play 				= $setting['slider_auto_play'];
				$slider_auto_hover_pause 		= $setting['slider_auto_hover_pause'];
				$slider_auto_interval_timeout 	= $setting['slider_auto_interval_timeout'];
				$slider_auto_play_speed 		= $setting['slider_auto_play_speed'];
				$slider_mouse_drag				= $setting['slider_mouse_drag'];
				$slider_touch_drag 				= $setting['slider_touch_drag'];
				$display_rating 				= (int)$setting['display_rating'] ;
				$display_sale 					= $setting['display_sale'];
				$display_new 					= $setting['display_new'];
				$product_image_num 				= (int)$setting['product_image_num'];
				$rows							= $setting['rows'];
				$tag_id							= 'so_super_category_'.$_POST['lbmoduleid'];
				$setting['product_sort'] 		= $_POST['fieldorder'];
				$item_link_target 				= $setting['item_link_target'];
				$child_items 					= self::getProducts($listCategory_son,$setting);

				$rl_loaded = $start;
				$result = new stdClass();

				$data_template = array(
					'child_items'			=> $child_items,
					'rows'					=> $rows,
					'tag_id'				=> $tag_id,
					'item_link_target'		=> $item_link_target,
					'rl_loaded'				=> $rl_loaded,
					'product_column0'		=> $product_column0,
					'product_column1'		=> $product_column1,
					'product_column2'		=> $product_column2,
					'product_column3'		=> $product_column3,
					'product_column4'		=> $product_column4,
					'display_compare'		=> $display_compare,
					'display_wishlist'		=> $display_wishlist,
					'display_add_to_cart'	=> $display_add_to_cart,
					'product_image'			=> $product_image,
					'moduleid'			=> $moduleid,
					'start'			=> $start,
					'direction'			=> $direction,
					'product_display_title'			=> $product_display_title,
					'product_display_description'			=> $product_display_description,
					'product_display_price'			=> $product_display_price,
					'type_show'			=> $type_show,
					'effect'			=> $effect,
					'product_duration'			=> $product_duration,
					'product_delay'			=> $product_delay,
					'slider_display_navigation'			=> $slider_display_navigation,
					'slider_display_loop'			=> $slider_display_loop,
					'slider_auto_play'			=> $slider_auto_play,
					'slider_auto_hover_pause'			=> $slider_auto_hover_pause,
					'slider_auto_interval_timeout'			=> $slider_auto_interval_timeout,
					'slider_auto_play_speed'			=> $slider_auto_play_speed,
					'slider_mouse_drag'			=> $slider_mouse_drag,
					'slider_touch_drag'			=> $slider_touch_drag,
					'display_rating'			=> $display_rating,
					'display_sale'			=> $display_sale,
					'display_new'			=> $display_new,
					'product_image_num'			=> $product_image_num,
					'button_cart'			=> $this->language->get('button_cart'),
				);
				
				include_once(DIR_SYSTEM . 'library/template/Twig/Autoloader.php');
				Twig_Autoloader::register();
				if (file_exists(DIR_TEMPLATE.$this->config->get('theme_default_directory')."/template/extension/module/so_super_category/default_items.twig")) {
					$loader 	= new Twig_Loader_Filesystem(DIR_TEMPLATE.$this->config->get('theme_default_directory')."/template/extension/module/so_super_category/");
				}
				else {
					$loader 	= new Twig_Loader_Filesystem(DIR_TEMPLATE."default/template/extension/module/so_super_category/");	
				}
				$twig 		= new Twig_Environment($loader, array('cache'=>DIR_CACHE, 'autoescape'=>'html'));
				$template 	= $twig->loadTemplate("default_items.twig");
				ob_start();
				echo $template->render($data_template);
				$buffer = ob_get_contents();
				$result->items_markup = preg_replace(
						array(
								'/ {2,}/',
								'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
						),
						array(
								' ',
								''
						),
						$buffer
				);
				ob_end_clean();
				die (json_encode($result));
			}
			
			
			foreach ($filters as $filter) {
				$filter_data = array(
					'filter_category_id'  	=> implode(',',$listCategory_son),
					'sort'         			=> $setting['product_sort'],
					'order'        			=> $setting['product_ordering'],
					'limit'        			=> $setting['limitation'],
					'start' 	   			=> $setting['start']
				);
				$aritles['count'] = $this->model_extension_module_so_super_category->getTotalProducts_super_category($filter_data);
				$aritles['category_id'] = $filter;
				$aritles['title'] = $this->getLabel($filter);
				array_unshift($articles_filter, $aritles);
			}
		
			foreach ($articles_filter as $filter) {
				if ($filter['count'] > 0) {
					if ($filter['category_id'] == $filter_preload) {
						$filter['sel'] = 'sel';
						$setting['product_sort'] = $filter_preload;
						$filter['child'] = self::getProducts($listCategory_son, $setting);
					}
					$tabs[$filter['category_id']] = $filter;
					
				}
			}
			$data['tabs'] = $tabs;
		}

		$array = array();
		foreach ($data['tabs'] as $index=>$items) {
			$array[] = $items['count'];
		}
		$data['_count_item'] = (count($array) >0 ? (int)$array[0] : 0);
		$data['array']	= $array;

		// caching
		$use_cache = (int)$setting['use_cache'];
		$cache_time = (int)$setting['cache_time'];
		$folder_cache = DIR_CACHE.'so/SuperCategory/';
		if(!file_exists($folder_cache))
			mkdir ($folder_cache, 0777, true);
		if (!class_exists('Cache_Lite'))
			require_once (DIR_SYSTEM . 'library/so/super_category/Cache_Lite/Lite.php');

		$options = array(
			'cacheDir' => $folder_cache,
			'lifeTime' => $cache_time
		);
		$Cache_Lite = new Cache_Lite($options);
		if ($use_cache){
			$this->hash = md5( serialize(array($this->config->get('config_language_id'), $this->session->data['currency'], $setting)));
			$_data = $Cache_Lite->get($this->hash);
			if (!$_data) {
				$_data = $this->load->view('extension/module/so_super_category/default', $data);
				$Cache_Lite->save($_data);
				return  $_data;
			} else {
				return  $_data;
			}
		}else{
			if(file_exists($folder_cache))
				$Cache_Lite->_cleanDir($folder_cache);
			return $this->load->view('extension/module/so_super_category/default', $data);
		}
	}
	
	private  function getCategoryson($setting){
		$setting['category'] = array($setting['category']);
		// check lại category nếu người dùng unpublic category sau khi cấu hình
		$category_list = array();
		$listCategory_son = array();
		
		foreach($setting['category'] as $category_item){
			$checkCategory = $this->model_extension_module_so_super_category->checkCategory($category_item);
			if(isset($checkCategory) && $checkCategory != null){
				$category_list[] =  $category_item;
			}
		}
		if($category_list != null){
			for($i=1; $i<=$setting['category_depth'];$i++){
				foreach ($setting['category'] as $categorys){
					$filter_data = array(
						'category_id'  => $categorys,
					);
					$categoryss = $this->model_extension_module_so_super_category->getCategories_son($filter_data);
					foreach ($categoryss as $category)
					{
						$setting['category'][]  = $category['category_id'];
					}
				}
			}
			$listCategory_son = array_unique($setting['category']);
		}
		
		return $listCategory_son;
	}
	
	public function getProducts($category_id_list,$setting)	{
		$list = array();
		if(is_array($category_id_list))	{
			$filter_data = array(
				'filter_category_id'  => implode(',',$category_id_list),
				'sort'         => $setting['product_sort'],
				'order'        => $setting['product_ordering'],
				'limit'        => (int)$setting['limitation'],
				'start' 	   => $setting['start']
			);
		}else{
			$filter_data = array(
				'filter_category_id'  => $category_id_list,
				'sort'         => $setting['product_sort'],
				'order'        => $setting['product_ordering'],
				'limit'        => (int)$setting['source_limit'],
				'start' 	   => $setting['start']
			);
		}
		
		$cat['count'] = $this->model_extension_module_so_super_category->getTotalProducts_super_category($filter_data);
		if ($cat['count'] > 0) {
			$products_arr = $this->model_extension_module_so_super_category->getProducts_super_category($filter_data);
			foreach($products_arr as $product_info){
				$product_image = $this->model_catalog_product->getProductImages($product_info['product_id']);
				$setting['product_width'] = ($setting['product_width'] == 0 ? "30px" : $setting['product_width']);
				$setting['product_height'] = ($setting['product_height'] == 0 ? "30px" : $setting['product_height']);
				$product_image_first = array_shift($product_image);
				$image2 = $this->model_tool_image->resize('placeholder.png', $setting['product_width'], $setting['product_height']);
				if($product_image_first != null)
				{
					$image2 = $this->model_tool_image->resize($product_image_first['image'], $setting['product_width'], $setting['product_height']);
				}
				if (($product_info['image'] != null) && ($setting['product_get_image_data'] == 1)) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['product_width'], $setting['product_height']);
				}elseif(isset($product_image_first['image']) && $setting['product_get_image_image'] == 1){
					$image = $this->model_tool_image->resize($product_image_first['image'], $setting['product_width'], $setting['product_height']);
				} else {
					$url = file_exists("image/catalog/so_super_category/images/".$setting['product_placeholder_path']);
					if ($url) {
						$image_name = "catalog/so_super_category/images/".$setting['product_placeholder_path'];
					} else {
						$image_name = "no_image.png";
					}
					$image = $this->model_tool_image->resize($image_name, $setting['product_width'], $setting['product_height']);
				}
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$discount = '-'.round((($product_info['price'] - $product_info['special'])/$product_info['price'])*100, 0).'%';
				} else {
					$special = false;
					$discount = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}
				$name = (($setting['product_title_maxlength'] != 0 && strlen($product_info['name']) > $setting['product_title_maxlength']) ? utf8_substr(strip_tags(html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')), 0, $setting['product_title_maxlength']) . '..' : $product_info['name']);
				$description = (($setting['product_description_maxlength'] != 0 && strlen($product_info['description']) > $setting['product_description_maxlength']) ? utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $setting['product_description_maxlength']) . '..' : $product_info['description']);
				
				$datetimeNow = new DateTime();
				$datetimeCreate = new DateTime($product_info['date_available']);
				$interval = $datetimeNow->diff($datetimeCreate);
				$dateDay = $interval->format('%a');
				$productNew = ($dateDay <= $setting['date_day'] ? 1 : 0);
				
				$cat['child'][] = array(
					'product_id'  			=> $product_info['product_id'],
					'thumb'       			=> $image,
					'thumb2'       			=> $image2,
					'name'        			=> $product_info['name'],
					'name_maxlength' 		=> $name,
					'description' 			=> $product_info['description'],
					'description_maxlength'	=> html_entity_decode($description),
					'price'       			=> $price,
					'special'     			=> $special,
					'discount'				=> $discount,
					'productNew'			=> $productNew,
					'tax'         			=> $tax,
					'rating'      			=> $rating,
					'date_added'  			=> $product_info['date_added'],
					'model'  	  			=> $product_info['model'],
					'quantity'    			=> $product_info['quantity'],
					'link'        			=> $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
				);
			}
			$list = $cat['child'];
		}
		return $list;
	}
	
	private  function getLabel($filter){
		switch ($filter) {
			case 'p_price' 			: return $this->language->get('value_price');
			case 'pd_name' 			: return $this->language->get('value_name');
			case 'p_model' 			: return $this->language->get('value_model');
			case 'p_quantity' 		: return $this->language->get('value_quantity');
			case 'rating' 			: return $this->language->get('value_rating');
			case 'p_sort_order' 	: return $this->language->get('value_sort_add');
			case 'p_date_added' 	: return $this->language->get('value_date_add');
			case 'sales' 			: return $this->language->get('value_sale');
		}
	}
	
}
<?php
class ControllerExtensionModuleSocategoryslider extends Controller {
	public function index($setting) {
		static $module = 1;
		$this->load->language('extension/module/so_category_slider');
		$data['heading_title'] = $setting['name'];
		
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('extension/module/so_category_slider');
		$this->document->addStyle('catalog/view/javascript/so_category_slider/css/slider.css');
		if (!defined ('OWL_CAROUSEL')){
			$this->document->addStyle('catalog/view/javascript/so_category_slider/css/animate.css');
			$this->document->addScript('catalog/view/javascript/so_category_slider/js/owl.carousel.js');
			$this->document->addStyle('catalog/view/javascript/so_category_slider/css/owl.carousel.css');
			define( 'OWL_CAROUSEL', 1 );
		}
		if (!isset($setting['start'])) {
			$setting['start'] = 0;
		}
		$default = array(
			'objlang'				=> $this->language,
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
			'display_add_to_cart'	=> '1',
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
			'cache_time'			=> '3600',
			'direction'				=> ($this->language->get('direction') == 'rtl' ? 'true' : 'false'),
			'direction_class'		=> ($this->language->get('direction') == 'rtl' ? 'so-category-slider-rtl' : 'so-category-slider-ltr')
		);
		$data =  array_merge($default,$setting);//check data empty setting
		$this->load->model('localisation/language');
		$data['languages'] 			= $this->model_localisation_language->getLanguages();
		$data['start'] 				= $setting['start'];
		$data['moduleid']  			= $setting['moduleid'];
		$data['tag_id'] 			= 'so_category_slider_'.$data['moduleid'];
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['head_name'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['head_name'], ENT_QUOTES, 'UTF-8');
		}else{
			$data['head_name']              = reset($setting['module_description'])['head_name'];
		}
		$data['nb_rows'] 				= $setting['nb_row'];

		if (isset($setting['pre_text']) && !empty($setting['pre_text']))
			$data['pre_text']	= html_entity_decode($setting['pre_text']);
		else
			$data['pre_text']	= '';

		if (isset($setting['post_text']) && !empty($setting['post_text']))
			$data['post_text']	= html_entity_decode($setting['post_text']);
		else
			$data['post_text']	= '';
		
		if ($data['autoplay'] == 1) {
			$data['autoplay_timeout'] 	= $setting['autoplay_timeout'];
		}else{
			$data['autoplay_timeout'] 	= 0;
		}
		$data['nav'] 			= ($setting['navs'] == 1 ? "true" : "false");
		
		//Default
		$catids = array();
		$catids[] = $setting['category'];
		$_catids = (array)self::processCategory($catids); 
		if($_catids != null){
			
			if($setting['child_category']){
				for($i=1; $i<=$setting['category_depth'];$i++){
					foreach ($_catids as $categorys)	{
						$filter_data = array(
							'category_id'  => $categorys,
						);
						$categoryss = $this->model_extension_module_so_category_slider->getCategories_son($filter_data);
						foreach ($categoryss as $category){
							$_catids[]  = $category['category_id'];
						}
						$_catids[] = $categorys;
					}
				}
				$catids = array_unique($_catids);
			}
			$product = self::getProducts($catids,$setting);
			$list = array();
			$cats = array();
			
			foreach($_catids as $category_id){
				$category_info = $this->model_catalog_category->getCategory($category_id);
				$title = (($setting['cat_title_maxcharacs'] != 0 && strlen($category_info['name']) > $setting['cat_title_maxcharacs']) ? utf8_substr(strip_tags(html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8')), 0, $setting['cat_title_maxcharacs']) . '..' : $category_info['name']);
				if ($category_info['image'] != null) {
					$image = $this->model_tool_image->resize($category_info['image'], $setting['width_cat'], $setting['height_cat']);
				}else {
					$url = file_exists("image/catalog/so_category_slider/images/".$setting['placeholder_path']);
					if ($url) {
						$image_name = "catalog/so_category_slider/images/".$setting['placeholder_path'];
					} else {
						$image_name = "no_image.png";
					}
					$image = $this->model_tool_image->resize($image_name, $setting['width_cat'], $setting['height_cat']);
				}
				if(isset($category_info['name'])){
					$data['list'][] = array(
						'title' 	 	=> $title,
						'titleFull'		=> $category_info['name'],
						'image'		 	=> $image,
						'link'  	 	=> $this->url->link('product/category', 'path=' . $category_id),
						'child_cat' 	=> self::getCategoryson($category_id,$setting),
						'product_image' => $setting['product_image'],
						'product' 		=> $product,
					);
				}
			}
		}
		$data['product_feature_ids'] = array();
		if($setting['display_feature']){
			$data['product_feature_ids'] = $setting['product_feature'];
			if(count($setting['product_feature']) > 0){
				$filter_data = array(
					'filter_product_id'  => implode(',',$setting['product_feature']),
					'sort'         => $setting['product_sort'],
					'order'        => $setting['product_ordering'],
					'limit'        => $setting['source_limit'],
					'start' 	   => $setting['start']
				);
				$products_deals = $this->model_extension_module_so_category_slider->getDeals($filter_data);
				foreach($products_deals as $product)
				{
					$specialPriceToDate = '';
					if (strtotime ($product['date_start']) != false && strtotime ($product['date_end']) != false)
					{
						$current = date ('Y/m/d H:i:s');
						$start_date = date ('Y/m/d H:i:s', strtotime ($product['date_start']));
						$date_end = date ('Y/m/d H:i:s', strtotime ($product['date_end']));
						if (strtotime ($date_end) >= strtotime ($current) && strtotime ($start_date) <= strtotime ($date_end))
							$specialPriceToDate = $date_end;
					}
					
					$product_image = $this->model_catalog_product->getProductImages($product['product_id']);
					
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					
					$product_image_first = array_shift($product_image);
					$image2 = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					if(count($product_image) >0)
					{
						$image2 = $this->model_tool_image->resize($product_image[0]['image'], $setting['width'], $setting['height']);
					}
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					}elseif(isset($product_image[0]['image'])){
						$image = $this->model_tool_image->resize($product_image[0]['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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
					
					$name = (($setting['title_maxlength'] != 0 && strlen($product_info['name']) > $setting['title_maxlength'] ) ? (utf8_substr(strip_tags(html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')), 0, $setting['title_maxlength']) .'..') : $product_info['name']);
					$description = (($setting['description_maxlength'] != 0 && strlen($product_info['description']) > $setting['description_maxlength'] ) ? utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $setting['description_maxlength']) . '..' : $product_info['description']);
					
					$datetimeNow = new DateTime();
					$datetimeCreate = new DateTime($product_info['date_available']);
					$interval = $datetimeNow->diff($datetimeCreate);
					$dateDay = $interval->format('%a');
					$productNew = ($dateDay <= $setting['date_day'] ? 1 : 0);
				
					$data['product_features'][] = array(
						'product_id'  		=> $product_info['product_id'],
						'thumb'       		=> $image,
						'thumb2'       		=> $image2,
						'name'        		=> $product_info['name'],
						'name_maxlength'    => $name,
						'description' 		=> $product_info['description'],
						'description_maxlength'	=> html_entity_decode($description),
						'price'       		=> $price,
						'special'     		=> $special,
						'discount'      => $discount,
						'productNew'		=> $productNew,
						'tax'         		=> $tax,
						'rating'      		=> $rating,
						'date_added'  		=> $product_info['date_added'],
						'model'  	  		=> $product_info['model'],
						'quantity'    		=> $product_info['quantity'],
						'href'        		=> $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
						'specialPriceToDate' => $specialPriceToDate,
					);
				}
			}
		} // display feature
		$data['module'] = $module++;
		// caching
		$use_cache = (int)$setting['use_cache'];
		$cache_time = (int)$setting['cache_time'];
		$folder_cache = DIR_CACHE.'so/CategorySlider/';
		if(!file_exists($folder_cache))
			mkdir ($folder_cache, 0777, true);
		if (!class_exists('Cache_Lite'))
			require_once (DIR_SYSTEM . 'library/so/category_slider/Cache_Lite/Lite.php');

		$options = array(
			'cacheDir' => $folder_cache,
			'lifeTime' => $cache_time
		);
		$Cache_Lite = new Cache_Lite($options);
		if ($use_cache){
			$this->hash = md5( serialize(array($this->config->get('config_language_id'), $this->session->data['currency'], $setting)));
			$_data = $Cache_Lite->get($this->hash);
			if (!$_data) {
				$_data = $this->load->view('extension/module/so_category_slider/default', $data);
				$Cache_Lite->save($_data);
				return  $_data;
			} else {
				return  $_data;
			}
		}else{
			if(file_exists($folder_cache))
				$Cache_Lite->_cleanDir($folder_cache);
			return $this->load->view('extension/module/so_category_slider/default', $data);
		}
	}
	public function getCategoryson($category_id, $setting)
	{
		$categoryss = array();
		if($setting['child_category_cat'] ==1)
		{
			$filter_data = array(
				'category_id'  => $category_id,
				'limit'        => $setting['source_limit_cat'],
				'start' 	   => 0,
				'width'        => $setting['width'],
				'height'       => $setting['height'],
				'category_depth' => $setting['category_depth']
			);
			$categoryss = $this->model_extension_module_so_category_slider->getCategories_son_categories($filter_data);
		}
		return $categoryss;
	}
	public function getProducts($category_id_list,$setting)
	{
		$filter_data = array(
			'filter_category_id'  => implode(',',$category_id_list),
			'sort'         => $setting['product_sort'],
			'order'        => $setting['product_ordering'],
			'limit'        => $setting['source_limit'],
			'start' 	   => $setting['start']
		);
		$cat['count'] = $this->model_extension_module_so_category_slider->getTotalProducts_categories($filter_data);
		if ($cat['count'] > 0)
		{
			$products_arr = $this->model_extension_module_so_category_slider->getProducts_categories($filter_data);
			foreach($products_arr as $product_info)
			{
				$product_image = $this->model_catalog_product->getProductImages($product_info['product_id']);
				$image2 = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				if(count($product_image) >0)
				{
					$image2 = $this->model_tool_image->resize($product_image[0]['image'], $setting['width'], $setting['height']);
				}
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
				}elseif(isset($product_image[0]['image'])){
					$image = $this->model_tool_image->resize($product_image[0]['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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

				$name = (($setting['title_maxlength'] != 0 && strlen($product_info['name']) > $setting['title_maxlength']) ? utf8_substr(strip_tags(html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')), 0, $setting['title_maxlength']) . '..' : $product_info['name']);
				$description = (($setting['description_maxlength'] != 0 && strlen($product_info['description']) > $setting['description_maxlength']) ? utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $setting['description_maxlength']) . '..' : $product_info['description']);
				
				$datetimeNow = new DateTime();
				$datetimeCreate = new DateTime($product_info['date_available']);
				$interval = $datetimeNow->diff($datetimeCreate);
				$dateDay = $interval->format('%a');
				$productNew = ($dateDay <= $setting['date_day'] ? 1 : 0);
				
				$cat['child'][] = array(
					'product_id'  	=> $product_info['product_id'],
					'thumb'       	=> $image,
					'thumb2'       	=> $image2,
					'name'        	=> $product_info['name'],
					'name_maxlength'=> $name,
					'description' 	=> $product_info['description'],
					'description_maxlength' => $description,
					'price'       	=> $price,
					'special'     	=> $special,
					'discount'      => $discount,
					'productNew'	=> $productNew,	
					'tax'         	=> $tax,
					'rating'      	=> $rating,
					'date_added'  	=> $product_info['date_added'],
					'model'  	  	=> $product_info['model'],
					'quantity'    	=> $product_info['quantity'],
					'href'        	=> $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
				);
			}
			$list = $cat['child'];
			return $list;
		}
	}
	private function processCategory($catids)
	{
		$catpubid = array();
		if (empty($catids)) return;
		foreach ($catids as $i => $cid) {
			$category = $this->model_catalog_category->getCategory($cid);
			$cats[$i] = $category;
			if (empty($category)) {
				unset($cats[$i]);
			} else {
				$catpubid[] = $category['category_id'];
			}
		}
		return $catpubid;
	}
}
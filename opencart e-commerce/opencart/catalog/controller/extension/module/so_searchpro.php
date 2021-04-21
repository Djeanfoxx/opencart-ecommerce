<?php  
class ControllerExtensionModuleSosearchpro extends Controller {
	
	private $data = array();

	public function index( $setting ) { 
		
		static $module = 0;
		$this->load->language('extension/module/so_searchpro');
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		$this->load->model('extension/module/so_searchpro');
		$this->document->addStyle('catalog/view/javascript/so_searchpro/css/so_searchpro.css');
		
		/*Entry */
		$this->data['text_search'] 			= $this->language->get('text_search');	
		$this->data['text_category_all'] 	= $this->language->get('text_category_all');	
		$this->data['text_tax'] 			= $this->language->get('text_tax');	
		$this->data['text_price'] 			= $this->language->get('text_price');	
		$this->data['button_cart'] 			= $this->language->get('button_cart');
		$this->data['button_wishlist'] 		= $this->language->get('button_wishlist');
		$this->data['button_compare'] 		= $this->language->get('button_compare');	
		/*End Entry */
		
		/*Data */
		$this->data['disp_title_module'] = (int)$setting['disp_title_module'] ;
		$this->data['additional_class'] = isset($setting['class_suffix']) ? $setting['class_suffix']:'';
		$this->data['limit'] = isset($setting['limit']) ? $setting['limit'] : 5 ;
		$this->data['height'] = isset($setting['height']) ? $setting['height'] : 50 ;
		$this->data['width'] = isset($setting['width']) ? $setting['width'] : 50 ;
		$this->data['character'] = isset($setting['character']) ? $setting['character'] : 2 ;
		$this->data['showimage'] = isset($setting['showimage']) ? $setting['showimage'] : '1';
		$this->data['showprice'] = isset($setting['showprice']) ? $setting['showprice'] : '1';		
		$this->data['showcategory'] = isset($setting['showcategory']) ? $setting['showcategory']:'1';
		$this->data['store_layout'] 			= $setting['store_layout'];
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$this->data['head_name'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['head_name'], ENT_QUOTES, 'UTF-8');
		}else{
			$this->data['head_name']  = reset($setting['module_description'])['head_name'];
		}
		
		//=== Theme Custom Code====
		if(!isset($setting['str_keyword'])){
			$setting['str_keyword']= "Keywords";
		}
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$this->data['head_name'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['head_name'], ENT_QUOTES, 'UTF-8');
			if(!isset($setting['module_description'][$this->config->get('config_language_id')]['head_name'])){
				$setting['module_description'][$this->config->get('config_language_id')]['head_name'] = "Keywords";
			}
			$this->data['str_keyword'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['str_keyword'], ENT_QUOTES, 'UTF-8');
		}else{
			$this->data['head_name']  = reset($setting['module_description'])['head_name'];;
			$this->data['str_keyword']  = $setting['str_keyword'];
		}
		$this->data['categories'] = array();
		/*End Data*/
		if($this->data['showcategory'] && $this->data['showcategory'] == 1){
	
			$this->data['categories'] = array();
			$this->data['category_id'] = 0;
			/* Level 1 */	
			$categories = $this->model_catalog_category->getCategories(0);

			foreach ($categories as $category) {
				$children_2 = array();
				/* Level 2 */	
				$categories_lv2 = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($categories_lv2 as $category_lv2) {
					$children_3 = array();
					/* Level 3 */	
					$categories_lv3 = $this->model_catalog_category->getCategories($category_lv2['category_id']);

					foreach ($categories_lv3 as $category_lv3) {
						$children_3[] = array(
							'category_id' => $category_lv3['category_id'],
							'name'        => $category_lv3['name'],
						);
					}

					$children_2[] = array(
						'category_id' => $category_lv2['category_id'],	
						'name'        => $category_lv2['name'],
						'children'    => $children_3
					);					
				}
				
				$this->data['categories'][] = array(
					'category_id' => $category['category_id'],
					'name'        => $category['name'],
					'children'    => $children_2
				);
			}

		}
		
		$this->data['module'] = $module++;
		if(!isset($setting['show_keyword'])){
			$setting['show_keyword'] = 1;
		}
		if(!isset($setting['limit_keyword'])){
			$setting['limit_keyword'] = 5;
		}
		$this->data['list_products'] = self::getProducts($setting);
		$this->data['show_keyword'] = $setting['show_keyword'];
		
		// caching
		$use_cache = (int)$setting['use_cache'];
		$cache_time = (int)$setting['cache_time'];
		$folder_cache = DIR_CACHE.'so/Searchpro/';
		if(!file_exists($folder_cache))
			mkdir ($folder_cache, 0777, true);
		if (!class_exists('Cache_Lite'))
			require_once (DIR_SYSTEM . 'library/so/searchpro/Cache_Lite/Lite.php');

		$options = array(
			'cacheDir' => $folder_cache,
			'lifeTime' => $cache_time
		);
		$Cache_Lite = new Cache_Lite($options);
		
		if ($use_cache){
			$this->hash = md5( serialize($setting));
			$_data = $Cache_Lite->get($this->hash);
			if (!$_data) {
				$_data = $this->load->view('extension/module/so_searchpro/'.$this->data['store_layout'], $this->data);
				$Cache_Lite->save($_data);
				return  $_data;
			} else {
				return  $_data;
			}
		}else{
			
			
			if(file_exists($folder_cache)) $Cache_Lite->_cleanDir($folder_cache);
			return $this->load->view('extension/module/so_searchpro/'.$this->data['store_layout'], $this->data);
		}
		
	}
	public function getProducts($setting)
	{
		
		$filter_data = array(
			'sort'			=> 'p.viewed',
			'order'			=> 'DESC',
			'limit'        	=> $setting['limit_keyword'] ,
			'start'        	=> '0' 
		);
		$data['products'] = '';
		$data['list_products']= array();
		
		$this->load->language('extension/module/so_searchpro');
		
		$products_arr = $this->model_extension_module_so_searchpro->getProducts($filter_data);
		
		foreach($products_arr as $product_info){
			$name = $product_info['name'];
			$name_maxlength = ((strlen($product_info['name']) > 25)  ? utf8_substr(strip_tags(html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')), 0, 25) . '..' : $product_info['name']);
			$data['list_products'][] = array(
				'product_id'  		=> $product_info['product_id'],
				'name'        		=> $name,
				'nameFull'			=> $product_info['name'],
				'name_maxlength'	=> $name_maxlength,
				'href'        		=> $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
			);
		}
		return $data['list_products'];
	}
	public function autocomplete() {
		$json = array();

		$this->load->language('product/category');
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_category_id'])) {
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
			$this->load->model('catalog/category');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_category_id'])) {
				$filter_category_id = $this->request->get['filter_category_id'];
			} else {
				$filter_category_id = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}
			
			if (isset($this->request->get['width'])) {
				$width = $this->request->get['width'];	
			} else {
				$width = 64;	
			}

			if (isset($this->request->get['height'])) {
				$height = $this->request->get['height'];	
			} else {
				$height = 64;	
			}			

			$data = array(
				'filter_name'  => $filter_name,
				'filter_tag'   => $filter_name,
				'filter_category_id' => $filter_category_id,
				'start'        => 0,
				'limit'        => $limit
			);
			
			$results = $this->model_catalog_product->getProducts($data);
			$total = $this->model_catalog_product->getTotalProducts($data);
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $width, $height);
				} else {
					$image = false;
				}
				// Check Version
				if(version_compare(VERSION, '2.1.0.2', '>')) {
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
					} else {
						$rating = false;
					}
				} else {
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}

					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
					} else {
						$rating = false;
					}
				}
				$path = 0;
				$category_name = '';
				$categories = $this->model_catalog_product->getCategories($result['product_id']);
				if ($categories){
				   $categories_info = $this->model_catalog_category->getCategory($categories[0]['category_id']);
				   $path=	$this->getCategoryPath($categories[0]['category_id']);
				   $category_name = (isset($categories_info['name']) && $categories_info['name']) ? $categories_info['name'] : '';
				}   
  
				
				$json[] = array(
					'total' => $total,
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),	
					'category_name' => strip_tags(html_entity_decode($category_name, ENT_QUOTES, 'UTF-8')),	
					'model'      => $result['model'],
					'image'		 => $image,
					'link'		 => $this->url->link('product/product','path='.$path.'&product_id='.$result['product_id']),
					'special'	 => $special,
					'tax'		 => $tax,
					'price'      => $price,
					'minimum'    =>  $result['minimum']
				);	
			}
			
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function getCategoryPath($category_id){
        $path = '';
			$category = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c WHERE c.category_id = " .(int)($category_id));
	   
		if($category->row['parent_id'] != 0){
			$path .= $this->getCategoryPath($category->row['parent_id']) . '_';
		}
	   
	   $path .= $category->row['category_id'];
	   
		return $path;
	}
}
?>

<?php
class ControllerExtensionModuleSohomeslider extends Controller {
	public function index($setting) {
		
		$this->document->addStyle('catalog/view/javascript/so_home_slider/css/style.css');
		
		if (!defined ('OWL_CAROUSEL')){
			$this->document->addStyle('catalog/view/javascript/so_home_slider/css/animate.css');
			$this->document->addStyle('catalog/view/javascript/so_home_slider/css/owl.carousel.css');
			$this->document->addScript('catalog/view/javascript/so_home_slider/js/owl.carousel.js');
			define( 'OWL_CAROUSEL', 1 );
		}
		
		
		// caching
		$use_cache = (int)$setting['use_cache'];
		$cache_time = (int)$setting['cache_time'];
		$folder_cache = DIR_CACHE.'so/HomeSlider/';
		if(!file_exists($folder_cache))
			mkdir ($folder_cache, 0777, true);
		if (!class_exists('Cache_Lite'))
			require_once (DIR_SYSTEM . 'library/so/home_slider/Cache_Lite/Lite.php');

		$options = array(
			'cacheDir' => $folder_cache,
			'lifeTime' => $cache_time
		);
		$Cache_Lite = new Cache_Lite($options);
		if ($use_cache){
			$this->hash = md5( serialize($setting));
			$_data = $Cache_Lite->get($this->hash);
			if (!$_data) {
				$data = $this->readData($setting);
				$_data = $this->load->view('extension/module/so_home_slider/default', $data);
				$Cache_Lite->save($_data);
				return  $_data;
			} else {
				return  $_data;
			}
		}else{
			$data = $this->readData($setting);
			if(file_exists($folder_cache))
				$Cache_Lite->_cleanDir($folder_cache);
			return $this->load->view('extension/module/so_home_slider/default', $data);
		}
		
		
	}
	
	public function readData($setting){
		static $module = 1;
		$this->load->language('extension/module/so_home_slider');
		$data['heading_title'] = $this->language->get('heading_title');
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('extension/module/so_home_slider');
		
		$default = array(
			'objlang'				=> $this->language,
			'name' 					=> '',
			'module_description'	=> array(),
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'class_suffix'			=> '',
			'item_link_target'		=> '_blank',
			'nb_column0'			=> '1',
			'nb_column1'			=> '1',
			'nb_column2'			=> '1',
			'nb_column3'			=> '1',
			'nb_column4'			=> '1',
			'width'					=> '350',
			'height'				=> '150',
			'autoplay'				=> '1',
			'autoplayTimeout'		=> '5000',
			'autoplayHoverPause'	=> '1',
			'autoplaySpeed'			=> '1000',
			'startPosition'			=> '0',
			'mouseDrag'				=> '1',
			'touchDrag'				=> '1',
			'loop'					=> '1',
			'dots'					=> '1',
			'navs'					=> '1',
			'link'					=> 'http://',
			'caption'				=> '',
			'animateIn'				=> 'bounceIn',
			'animateOut'			=> 'bounceOut',
			'thumb'					=> $this->model_tool_image->resize('no_image.png', 100, 100),
			'pre_text'				=> '',
			'post_text'				=> '',
			'use_cache'				=> '1',
			'cache_time'			=> '3600',
			'direction'				=> ($this->language->get('direction') == 'rtl' ? 'true' : 'false'),
			'direction_class'		=> ($this->language->get('direction') == 'rtl' ? 'so-homeslider-rtl' : 'so-homeslider-ltr')
		);
		$data =  array_merge($default,$setting);//check data empty setting
		if (isset($setting['post_text'])) $data['post_text']  = html_entity_decode($setting['post_text'], ENT_QUOTES, 'UTF-8');
		if (isset($setting['pre_text'])) $data['pre_text']  = html_entity_decode($setting['pre_text'], ENT_QUOTES, 'UTF-8');
		$data['disp_title_module'] 	= $setting['disp_title_module'];
		
		$data['autoplay'] 				= ($setting['autoplay'] ==1 ? "true" : "false");
		$data['autoplayHoverPause'] 	= ($setting['autoplayHoverPause'] ==1 ? "true" : "false");
		$data['mouseDrag'] 				= ($setting['mouseDrag'] == 1 ? "true" : "false" );
		$data['touchDrag'] 				= ($setting['touchDrag'] == 1 ? "true" : "false" );
		$data['loop'] 					= ($setting['loop'] == 1 ? "true" : "false" );
		$data['dots'] 					= ($setting['dots'] == 1 ? "true" : "false");
		$data['nav'] 					= ($setting['navs'] == 1 ? "true" : "false");
		
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['head_name'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['head_name'], ENT_QUOTES, 'UTF-8');
		}else{
			$data['head_name']              = reset($setting['module_description'])['head_name'];
		}
		
		//Default
		$slide_arr = array();
		$slide_arr = self::getSlides($setting);
		$data['list'] = $slide_arr;
		$data['module'] = $module++;
		
		return $data;
	}
	
	public function getSlides($setting)
	{
		$list = array();
		$slides_arr = $this->model_extension_module_so_home_slider->getListSlider($setting);
		foreach($slides_arr as $slide_info)
			{	
				if (isset($slide_info['description'])) $slide_info['description']  = html_entity_decode($slide_info['description'], ENT_QUOTES, 'UTF-8');
				$image = $this->model_tool_image->resize($slide_info['image'],$setting['width'],$setting['height']);
				$list[] = array(
					'slider_id'  	=> $slide_info['id'],
					'thumb'       	=> $image,
					'title'        	=> $slide_info['title'],
					'description' 	=> $slide_info['description'],
					'caption'       => $slide_info['caption'],
					'url'     		=> $slide_info['url']
				);
			}
		return $list;
	}
		
}
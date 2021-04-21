<?php
class ControllerExtensionModuleSomegamenu extends Controller {
    public function index($setting) {
        
        $this->document->addStyle('catalog/view/javascript/so_megamenu/so_megamenu.css');
        $this->document->addStyle('catalog/view/javascript/so_megamenu/wide-grid.css');
        $this->document->addScript('catalog/view/javascript/so_megamenu/so_megamenu.js');
		

        // caching
        $use_cache = (int)$setting['use_cache'];
        $cache_time = (int)$setting['cache_time'];
        $folder_cache = DIR_CACHE.'so/Megamenu/';
        if(!file_exists($folder_cache))
            mkdir ($folder_cache, 0777, true);
        if (!class_exists('Cache_Lite'))
            require_once (DIR_SYSTEM . 'library/so/megamenu/Cache_Lite/Lite.php');

        $options = array(
            'cacheDir' => $folder_cache,
            'lifeTime' => $cache_time
        );
        $Cache_Lite = new Cache_Lite($options);
        if ($use_cache){
            $this->hash = md5( serialize($setting).$this->config->get('config_language_id').$this->session->data['currency']);
            $_data = $Cache_Lite->get($this->hash);
            if (!$_data) {
				$data = $this->readData($setting);
                $_data = $this->load->view('extension/module/so_megamenu/default', $data);
                $Cache_Lite->save($_data);
                return  $_data;
            } else {
                return  $_data;
            }
        }else{
            if(file_exists($folder_cache))
                $Cache_Lite->_cleanDir($folder_cache);
			$data = $this->readData($setting);
            return $this->load->view('extension/module/so_megamenu/default', $data);
        }
    }
	
	public function readData($setting) {
		$this->load->model('extension/module/so_megamenu');
		
		$module_id = (isset($setting['moduleid']) && $setting['moduleid']) ? $setting['moduleid'] : 0;
        $data['menu'] = $this->model_extension_module_so_megamenu->getMenu($module_id);

        //Leader Cusstom
        $this->load->language('extension/module/so_megamenu');
        $data['text_more_category']             = $this->language->get('text_more_category');
        $data['text_close_category']            = $this->language->get('text_close_category');
        
		foreach($data['menu'] as &$menu){
			if(isset($menu['link']) && $menu['link']){
				$menu['link'] = trim($menu['link']);
				$link = (isset($menu['link']) && ($menu['link'])) ? @unserialize($menu['link']) : array();
				$menu['route'] = '';
				$menu['path'] = '';
				if($link){
					if(isset($menu['type_link']) && $menu['type_link'] == 1){
						$menu['link'] = $this->url->link('product/category', 'path=' . $link['category']);
						$menu['route'] = 'product/category';
						$menu['path']	= $link['category'];
					}else
						$menu['link'] = $link['url'];
				}
				else
					$menu['link'] = '';
			}	
		}
        $lang_id = $this->config->get('config_language_id');
		if($setting['show_itemver'] == ""){
			$setting['show_itemver'] = 5;
		}
        $data['ustawienia'] = array(
            'orientation' => $setting['orientation'],
            'search_bar' => $setting['search_bar'],
            'navigation_text' => $setting['navigation_text'],
            'full_width' => $setting['full_width'],
            'home_item' => $setting['home_item'],
            'home_text' => $setting['home_text'],
            'animation' => $setting['animation'],
            'show_itemver' => $setting['show_itemver'],
            'animation_time' => $setting['animation_time'],
			'disp_title_module' => isset($setting['disp_title_module']) ? $setting['disp_title_module'] : ''
        );
        $data['navigation_text'] = 'Navigation';
        if(isset($setting['navigation_text'][$lang_id])) {
            if(!empty($setting['navigation_text'][$lang_id])) {
                $data['navigation_text'] = $setting['navigation_text'][$lang_id];
            }
        }
        if(isset($setting['head_name'][$lang_id])) {
            if(!empty($setting['head_name'][$lang_id])) {
                $data['head_name'] = $setting['head_name'][$lang_id];
            }
        }		
        $data['home_text'] = 'Home';
        if(isset($setting['home_text'][$lang_id])) {
            if(!empty($setting['home_text'][$lang_id])) {
                $data['home_text'] = $setting['home_text'][$lang_id];
            }
        }
        $data['home'] = $this->url->link('common/home');
        $data['lang_id'] = $this->config->get('config_language_id');

        $http = $_SERVER["HTTPS"]  ? 'https://' : 'http://';
        $data['actual_link'] = $http."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (isset($_GET['route']))
            $data['route']  = $_GET['route'];
        else
            $data['route']  = '';

        if (isset($_GET['path']))
            $data['path']   = $_GET['path'];
        else
            $data['path']   = '';
		
        // Search
        $this->language->load('common/header');
		
		return $data;
	}
}
?>
<?php
class ControllerExtensionModuleSohtmlcontent extends Controller {
	public function index($setting) {
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
			$data['class_suffix'] 			= $setting['class_suffix'];
			$data['store_layout'] 			= $setting['store_layout'];
		
			// caching
			$use_cache = (int)$setting['use_cache'];
			$cache_time = (int)$setting['cache_time'];
			$folder_cache = DIR_CACHE.'so/HtmlContent/';
			if(!file_exists($folder_cache))
				mkdir ($folder_cache, 0777, true);
			if (!class_exists('Cache_Lite'))
				require_once (DIR_SYSTEM . 'library/so/html_content/Cache_Lite/Lite.php');

			$options = array(
				'cacheDir' => $folder_cache,
				'lifeTime' => $cache_time
			);
			$Cache_Lite = new Cache_Lite($options);
			if ($use_cache){
				$this->hash = md5( serialize($setting));
				$_data = $Cache_Lite->get($this->hash);
				if (!$_data) {
					$_data = $this->load->view('extension/module/so_html_content/'.$data['store_layout'], $data);
					$Cache_Lite->save($_data);
					return  $_data;
				} else {
					return  $_data;
				}
			}else{
				if(file_exists($folder_cache))
					$Cache_Lite->_cleanDir($folder_cache);
				return $this->load->view('extension/module/so_html_content/'.$data['store_layout'], $data);
			}
			
		}
	}
}

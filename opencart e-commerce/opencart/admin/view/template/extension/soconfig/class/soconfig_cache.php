<?php
class SoconfigCache {
	private $config;
	
	public function __construct($registry){
		$this->config = $registry->get('config');
		$this->request = $registry->get('request');
		$this->url = $registry->get('url');
		
    }
	
	public function get($key) {
		$file = SOCONFIG_CACHE_DIR.'cache-'.$key;
		 
		if (file_exists($file) && filesize($file)) {
			$handle = fopen($file, 'r');

			flock($handle, LOCK_SH);

			$data = fread($handle, filesize($file));

			flock($handle, LOCK_UN);

			fclose($handle);

			return json_decode($data, true);
		}

		return false;
	}

	public function set($key, $value) {
		$this->delete($key);

		$file = SOCONFIG_CACHE_DIR.'cache-'.$key;

		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, json_encode($value));

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);
	}

	public function delete($key) {
		$file = SOCONFIG_CACHE_DIR.'cache-'.$key;
    
    if (file_exists($file)) 
    {
		  unlink($file);
		}
	}
  
  public function clean($key) {
    $files = glob(SOCONFIG_CACHE_DIR . 'cache-'.$key.'*');
    if ($files) 
    {
      foreach ($files as $file) 
      {
				if (file_exists($file)) 
        {
					unlink($file);
				}
			}
    }
  }
  
	public function clear($clear_minify = true){
		if ($clear_minify){
		  $this->clear_minify_cache('css');
		  $this->clear_minify_cache('js');
		}
	}
	public function clear_css($clear_minify = true){
		if ($clear_minify) $this->clear_css_cache('css');
	}
  
	public function clear_minify_cache($type){
		$files = glob(SOCONFIG_CACHE_DIR . 'minify/*.'.$type);
		if ($files) {
		  foreach ($files as $file) {
				if (file_exists($file)) unlink($file);
			}
		}
	}
  
	public function clear_css_cache($type){
		
		$themes = $this->config->get('theme_default_directory');
		$files = glob('view/theme/'.$themes.'/css/theme-*.'.$type);
		if ($files) {
		  foreach ($files as $file) {
			if (file_exists($file)) unlink($file);
					
			}
		}
	}
  
}
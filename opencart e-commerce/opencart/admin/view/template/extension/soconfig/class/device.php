<?php
require_once('soconfig_settings.php');
final class Device {
  	public $device;
	public $soconfig;

	public function __construct($registry) {
		
		$this->config = $registry->get('config');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->soconfig = new SoconfigSettings($registry);

		$this->mobile_agents = array('iPod','iPhone','webOS','BlackBerry','windows phone','symbian','vodafone','opera mini','windows ce','smartphone','palm','midp') ;
		
		$this->exclude_mobile_agents = array() ;
		
		$this->tablet_agents = array('iPad','RIM Tablet','hp-tablet','Kindle Fire','Android') ;
		
		$this->exclude_tablet_agents = array() ;
	
		if(isset($this->request->get['change_device'])){
			$device_name = $this->request->get['device_name'];
			$this->session->data['set_device'] = $device_name;
		}

		if(!isset($this->session->data['set_device']))
		{
			if((!isset($this->session->data['device'])) || (!isset($this->request->cookie['device']))) {
				if ($this->isTablet()) {
					$this->set("tablet");
				}else if($this->isMobile()) {
					$this->set("mobile");
				}else {
					$this->set("desktop");	
				}
			}
		}elseif(isset($this->request->get['change_device'])){
			if($device_name=='mobile_desktop' || $device_name=='tablet_desktop'){
				$this->session->data['device'] = 'desktop';
			}elseif($device_name=='mobile'){
				$this->session->data['device'] = 'mobile';
			}elseif($device_name=='tablet'){
				$this->session->data['device'] = 'tablet';
			}
		}
		
		$template_mobile = $this->get_settings('platforms_mobile');
		$template_tablet = 1;
		
		if(!$this->is_admin()){
			if($this->session->data['device']=="mobile" && !empty($template_mobile))	{
				$this->config->set('theme_default_directory','so-mobile' ) ;
			}
			if (!defined('URL_TEMPLATE'))  define('URL_TEMPLATE', 'catalog/view/theme/');
		}else{
			if (!defined('URL_TEMPLATE'))  define('URL_TEMPLATE', DIR_CATALOG.'/view/theme/');
			if (!defined('DIR_TEMPLATE_FRONT')) define('DIR_TEMPLATE_FRONT', DIR_CATALOG.'view/theme/');
		}
		
		
	} 

	public function get_settings($name,$default = null){
		return $this->soconfig->get_cfg($name,$default);    
    }

	public function is_admin(){
		return (defined('HTTP_CATALOG'));
    }
	
	public function set($device) {
      	$this->session->data['device'] = $device;
	}
	
	
	public function isMobile() {
		$mobile = false;
		
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
							
			foreach($this->mobile_agents as $mobile_agent){
				if(stripos($_SERVER['HTTP_USER_AGENT'],$mobile_agent)){
					$mobile = true;
				}
			}
			if(stripos($_SERVER['HTTP_USER_AGENT'],"Android") && stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){
				$mobile = true;
			}
			foreach($this->exclude_mobile_agents as $exclude_mobile_agent){
				if(stripos($_SERVER['HTTP_USER_AGENT'],$exclude_mobile_agent)){
					echo 'exclude';
					$mobile = false;
				}
			}
		}
		return $mobile;
	}
	
	public function isTablet() {
		$tablet = false;
		
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
					
			foreach($this->tablet_agents as $tablet_agent){
				if(stripos($_SERVER['HTTP_USER_AGENT'],$tablet_agent)){
					$tablet = true;
				}
			}
			
			if(stripos($_SERVER['HTTP_USER_AGENT'],"Android") && stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){
				$tablet = false;
			}
			
			foreach($this->exclude_tablet_agents as $exclude_tablet_agent){
				if(stripos($_SERVER['HTTP_USER_AGENT'],$exclude_tablet_agent)){
					$tablet = false;
				}
			}
		}
		return $tablet;
		}
}

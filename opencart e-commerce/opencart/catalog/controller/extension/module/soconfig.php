<?php  
class ControllerExtensionModuleSoconfig extends Controller {
	protected function index($setting) {
		
		$this->data['status'] = $this->config->get('cpanel');
		
		if($this->data['status']==1){
			$data_template = $this->cache->get('soconfig.' . (int)$this->config->get('config_language_id'));
			$this->data['data_template'] = $data_template;
			
			$data_template = false;
			$this->data['data_template'] = false;
			
			if(!$data_template){
				
				$colors_data = $this->data['temp_setting_arr'];
				
				$this->data['colors_data'] = json_encode($colors_data);
				
				
				$this->cache->set('soconfig.' . (int)$this->config->get('config_language_id'),$this->render());
				$this->render(); 
			}else{
				
				$this->render();
			}
		}else{
			
			$this->render();
		}
	}
}
?>
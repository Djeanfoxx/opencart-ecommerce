<?php
class SoconfigSettings
 {
   private $db;
   private $config;
   private $configuration;
   private $store_layout_id;
   private $store_id;

   public function __construct($registry){
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');
		
		$this->store_id = $this->config->get('config_store_id');
		
		//Check oc_soconfig Exit
		$this->load_configuration($this->store_id);
    }
   
    private function load_configuration($store_id=null){
		$this->configuration = array();
		
		if($this->db != null ){

			$result = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."soconfig'");
			if( $result->num_rows == 1){
				$sql = "select * from ".DB_PREFIX."soconfig where store_id='$store_id' ";
				$this->load_config = $this->db->query($sql)->rows;
				$lng_id = $this->config->get('config_language_id');
				
				foreach($this->load_config as $conf_id=>$conf_data){
					$config = json_decode($conf_data['value'] , true);
					
					if (is_array($config)) {
						foreach($config as $name => $value){
							$this->configuration[$name] = $value;
							if (is_array($value)){
								if (isset($value[0])) {
								 	$this->configuration[$name] = $value;
								} else if (isset($value[$lng_id])){
									 $this->configuration[$name] = $value[$lng_id];
								} else {
								 	$this->configuration[$name] = reset($value);
								}
							}
							
						}
					}
				}
			}
			
			
		}
		return $this->configuration;
    }
    
	
    public function get_cfg($name,$default = null){
		if (!isset($this->configuration[$name]) || $this->configuration[$name] == '') return $default;
		else return $this->configuration[$name];
	}
	
	
	
	public function set_cfg($name,$value){
	  $this->configuration[$name] = $value;
	}
 }
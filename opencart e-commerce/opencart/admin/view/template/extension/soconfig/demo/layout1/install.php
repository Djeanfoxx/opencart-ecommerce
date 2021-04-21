<?php
	
	$sql = "Delete from ".DB_PREFIX."soconfig where store_id=".$store_id." AND `key` REGEXP 'soconfig'"; $this->db->query($sql);
	
	//Inset Data Table - soconfig, layout_module
	$theme_sql = DIR_TEMPLATE.'extension/soconfig/demo/layout'.$install_layout.'/themes.sql';
	
	if( file_exists($theme_sql)){
		$query_setting = loo_parse_queries($theme_sql,DB_PREFIX,$store_id);
		foreach ($query_setting as $query) {
			$this->db->query($query);
		}
	} 
	
	
	
	/**
	 * Function loo_parse_queries
	 * Performs a query on the database
	 *
	 * Parameters:
	 *     ($db) 			- 
	 *     ($sql_file) 		- Source File SQL
	 *     ($prefix) 		- Prefix of DB
	 */
	function loo_parse_queries($sql_file,$prefix,$store_id=null) {
		$contents = file_get_contents($sql_file);
		$contents 	= preg_replace('/(?<=t);(?=\n)/', "{{semicolon_in_text}}", $contents);
		$statements = preg_split('/;(?=\n)/', $contents);
		
		$queries = array();
		foreach ($statements as $query) {
			if (trim($query) != '') {
				$query = str_replace("{{semicolon_in_text}}", ";", $query);
				//apply db prefix parametr
				preg_match("/\{table_prefix}\w*/i", $query, $matches);
				$table_name = str_replace('{table_prefix}', DB_PREFIX, $matches[0]);
				if ( !empty($table_name) ) {
					if($store_id!=null) {
						$query =  str_replace('{store_id}',$store_id,$query);
					}
					$query = str_replace(array($matches[0], 'key = '), array($table_name, '`key` = '), $query);
				}
				$queries[] = $query;
			}
		}
		
		return $queries ;
		
	}
	
	
?>
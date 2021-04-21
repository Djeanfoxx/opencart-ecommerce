<?php
/******************************************************
 * @package	SO Theme Framework for Opencart 2.3.x
 * @author	http://www.magentech.com
 * @license	GNU General Public License
 * @copyright(C) 2008-2015 Magentech.com. All rights reserved.
*******************************************************/
 
require_once (DIR_APPLICATION.'view/template/extension/soconfig/class/so_field.php');
require_once (DIR_APPLICATION.'view/template/extension/soconfig/class/soconfig.php');

class ControllerExtensionModuleSoMobile extends Controller {

    private $error = array();
	private $demos = array();
	private $typeheader = array();
	private $typefooter = array();
	private $typelayout = array();
	 
	public function  __construct($registry) {
		parent::__construct($registry);
		$this->soconfig = new Soconfig($registry);
		if(!defined('DIR_SOCONFIG')) define('DIR_SOCONFIG','view/template/extension/soconfig/');
		if(!defined('PATH_SOCONFIG')) define('PATH_SOCONFIG',DIR_APPLICATION.'view/template/extension/soconfig/');
		//Dev Custom Theme
		$this->listColor= array(
			'red'    =>'#ea3a3c',
			'orange' =>'#ff5c00',
			'blue'   =>'#3786c7',
			'cyan'   =>'#0f8db3',
			'green'  =>'#20bc5a',
		);
		
		
		$this->typelayouts = array(
			array(
			'key'=>'1',
			'mtypelayout'=>'<p>Mobile Layout 1</p>',
			'mtypeheader'=> array('key'=>'1', 'title'=>'Header 1 (used in Layout 1)'),
			'mtypefooter'=> array(),
			),
			array(
			'key'=>'2',
			'mtypelayout'=>'<p>Mobile Layout 2</p>',
			'mtypeheader'=> array('key'=>'2', 'title'=>'Header 2 (used in Layout 2)'),
			'mtypefooter'=> array(),
			),
			array(
			'key'=>'3',
			'mtypelayout'=>'<p>Mobile Layout 3 </p>',
			'mtypeheader'=> array('key'=>'3', 'title'=>'Header 3 (used in Layout 3)'),
			'mtypefooter'=> array(),
			),
			
		);

		//End Dev Custom Theme
	}
	
    public function index() {
		/*===== Load language ========== */
		$this->load->language('extension/module/somobile');
		$data['direction'] = $this->language->get('direction');
		$data['objlang'] = $this->language;
		
		/*===== Load Title Module ========== */
		$this->document->setTitle($this->language->get('heading_title_normal'));
		
		// Load breadcrumbs
		$store_id = isset($this->request->get['store_id']) ? (int)$this->request->get['store_id'] : 0;
		
		
		/*===== Load CSS & JS ========== */
		$this->document->addScript(DIR_SOCONFIG.'asset/plugin/bs-colorpicker/js/colorpicker.js');
		$this->document->addScript(DIR_SOCONFIG.'asset/js/jquery.cookie.js');
		$this->document->addScript(DIR_SOCONFIG.'asset/js/jquery.sticky-kit.min.js');
		$this->document->addScript(DIR_SOCONFIG.'asset/js/theme.js');
		
        $this->document->addStyle(DIR_SOCONFIG.'asset/plugin/bs-colorpicker/css/colorpicker.css');
        $this->document->addScript('view/javascript/summernote/summernote.js');
		$this->document->addScript('view/javascript/summernote/summernote-image-attributes.js');
		$this->document->addScript('view/javascript/summernote/opencart.js');
		$this->document->addStyle('view/javascript/summernote/summernote.css');
        
		// Check RTL Css
        if ($data['direction'] != 'rtl') $this->document->addStyle(DIR_SOCONFIG.'asset/css/theme.css');
		else $this->document->addStyle(DIR_SOCONFIG.'asset/css/theme-rtl.css');
		
		/*===== Load model ========== */
		$this->load->model('setting/store');
        $this->load->model('setting/setting');
		$this->load->model('extension/module/soconfig/setting');
		$this->load->model('design/layout');
		$this->load->model('tool/image');
		$this->load->model('localisation/language');
		
		/*===== Load Stores========== */
		$store_id = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : 0;
        $stores = $this->model_setting_store->getStores();
		array_unshift($stores, array('store_id' => '0','name'     => $this->config->get('config_name'),));
		foreach ($stores as $store) {
			$store_data[] = array(
				'name'   => $store['name'],
				'store_id' =>  $store['store_id'],
				'status' => $this->model_setting_setting->getSettingValue('theme_default_status', $store['store_id']) 
			);
		}
		$data['stores'] = $store_data;
		$data['active_store'] = $store_id;
		/*===== End Load Stores========== */

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_extension_module_soconfig_setting->editMobile( $this->request->post,$store_id);	
			
			// buttonForm apply
			if($this->request->post['buttonForm'] == 'color' ){
				$data['scsscompile'] = $this->request->post['mobile_general']['mscsscompile'];
				if(!$data['scsscompile']){
					$this->session->data['success'] = 'Success Compile Sass File To Css';
					$this->soconfig->scss_compassMobile($this->request->post['mobile_general']['colorHex'],$this->request->post['mobile_general']['nameColor'],$this->request->post['mobile_general']['mcompileMutiColor'],$this->listColor);
					unset($this->request->post['buttonForm']);
					$this->response->redirect($this->url->link('extension/module/somobile', 'user_token=' . $this->session->data['user_token'], 'SSL'));
				}else{
					$this->session->data['success'] = 'Error: Compile Sass File To Css, Select Performace -> SCSS Compile = Off';
				}
				
			}elseif ($this->request->post['buttonForm'] == 'apply') {
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('extension/module/somobile', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id, true));
			} else {
                $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
            }
			
		}
		
		$default = array(
			'mobile_general'	=> array(
			'mobilelayout' 			=> '1',
			'nameColor' 			=> 'blue',
			'colorHex' 				=> '#673199',
			'mobilecolor' 				=> 'blue',
			'platforms_mobile' 			=> '1',
			'logomobile'	=> 'nophoto.png',
			'barnav' 				=> '1',
			'mcopyright'		=> 'Copyright demo © 2017 by opencartworks.com',
			'mtypeheader'	=> '1',
			'mimgpayment'	=>'catalog/demo/payment/payment.png',
			'mphone_status'=> '1',
			'mphone_text' 	=> array(
				'1'=>'(84+) 1234455669',
				'2'=>'(84+) 1234455667',
			),
			'memail_status'=> '1',
			'memail_text' 	=> array(
				'1'=>'support@opencartworks.com',
				'2'=>'support@opencartworks.com',
			),
			'customfooter_status'=> '1',
			'customfooter_text' 	=> array(
				'1'=>'Custom block Html',
				'2'=>'Custom block Html',
			),
			'menufooter_status'=> '1',
			'footermenus'		=> array(
				array(
				'name'=> array(
					'1'=>'Demo Menu 1',
					'2'=>'Demo Menu 2',
				),
				'link'=>'#',
				'sort'=>'1',
				)
			),
			'bottombar_status'=> '1',
			'barmore_status'=> '1',
			'listmenus'		=> array(
				array(
				'namemore'=> array(
					'1'=>'Demo Menu 1',
					'2'=>'Demo Menu 2',
				),
				'link'=>'#',
				'sort'=>'1',
				)
			),
			'barsearch_status'=> '1',
			'barmega1_status'=> '0',
			'barmega1_status'=> '0',
			'barcategory_status'=> '1',
			'barwistlist_status'=> '1',
			'barcompare_status'=> '1',
			'category_more'	=> '1',
			'compare_status'	=> '1',
			'wishlist_status'	=> '1',
			'addcart_status'	=> '1',

			'mbody_status'	=> 'google',
			'mnormal_body'	=> 'inherit',
			'murl_body'	=> 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700',
			'mfamily_body'	=> 'Open Sans, sans-serif;',
			'mselector_body'	=> 'body',
			'mheading_status'	=> 'standard',
			'mnormal_heading'	=> 'inherit',
			'murl_heading'	=> 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700',
			'mfamily_heading'	=> 'Open Sans, sans-serif;',
			'mselector_heading'	=> 'body',
			'mscsscompile'	=> '0',
			'mscssformat'	=> 'Nested',
			'mcompilemuticolor'	=> '1',
			),
		);
		if (($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() ) {
			$module_info = $this->model_extension_module_soconfig_setting->getMobile($store_id);
			
			$module_info =  array_merge($default,$module_info);//check data empty database
				
		}
		$data['module'] = $module_info;
		$data['listmenus'] =  $this->sortArray($module_info['mobile_general']['listmenus'],'sort') ;;
		$data['footermenus'] = $this->sortArray($module_info['mobile_general']['footermenus'],'sort') ;

		// ---------------------------Load module --------------------------------------------
		/*$data['clear_cache_href'] = $this->url->link('extension/module/soconfig/clearcache', 'user_token=' . $this->session->data['user_token'].'&store_id='.$store_id, 'SSL');
		$data['clear_css_href'] = $this->url->link('extension/module/soconfig/clearcss', 'user_token=' . $this->session->data['user_token'].'&store_id='.$store_id, 'SSL');
		$data['compiled_css'] = $this->url->link('extension/module/soconfig/compiled_css', 'user_token=' . $this->session->data['user_token'].'&store_id='.$store_id, 'SSL');
		*/

		
        if (isset($this->session->data['success'])) {$data['success'] = $this->session->data['success'];unset($this->session->data['success']);} else {$data['success'] = '';}
		$data['error']= $this->error;
		$data['oc_layouts'] = $this->model_design_layout->getLayouts();
		$data['typelayouts'] = $this->typelayouts;
		$data['base_href'] = $this->url->link('extension/module/somobile', 'user_token=' . $this->session->data['user_token'].'&store_id='.$store_id, 'SSL');
		$data['action'] = $this->url->link('extension/module/somobile', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id, true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		
		//Get theme_directory
		if ($this->config->get('theme_default_directory')) $data['theme_directory'] = $this->config->get('theme_default_directory'); // Remove được
		else $data['theme_directory'] = 'default';
		
		$data['allThemeColor'] =  $this->soconfig->getColorMobile() ? $this->soconfig->getColorMobile() : array('none' => 'None'); 
		$data['user_user_token'] 		= $this->session->data['user_token'];
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
        /*end variables for theme */

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		
		$fields = new So_Fields($module_info);
		$data['fields'] = $fields;
		$this->response->setOutput($this->load->view('extension/soconfig/somobile', $data));
	}
	
	public function sortArray( $data, $field ) {
		$field = (array) $field;
		uasort( $data, function($a, $b) use($field) {
			$retval = 0;
			foreach( $field as $fieldname ) {
				if( $retval == 0 ) $retval = strnatcmp( $a[$fieldname], $b[$fieldname] );
			}
			return $retval;
		} );
		return $data;
	}
	
	public function uninstall() {
       
    }
	
    public function install(){
	
		$this->session->data['success'] = $this->language->get('text_success');
    }
	
	


	public function clearcache(){
      $this->soconfig->cache->clear();
      $this->session->data['success'] = 'Cache cleared';
      $this->response->redirect($this->url->link('extension/module/somobile', 'user_token=' . $this->session->data['user_token'], 'SSL'));
    }
	
	public function clearcss(){
      $this->soconfig->cache->clear_css();
      $this->session->data['success'] = 'Cache cleared';
      $this->response->redirect($this->url->link('extension/module/somobile', 'user_token=' . $this->session->data['user_token'], 'SSL'));
	 
    }
	
	
	public function getColorScheme() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$filter_data = $this->request->get['filter_name'];
			$results = $this->soconfig->getColorScheme($filter_data);
			
			if(!empty($results)){
				foreach ($results as $result) {
					$json[] = array(
						'name'        => html_entity_decode($result, ENT_QUOTES, 'UTF-8')
					);
				}
			}else{
				$json[] = array(
					'name'        => 'No Value'
				);
			}
			
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
    protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/somobile')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (empty($this->request->post['mobile_general']['nameColor']) || empty($this->request->post['mobile_general']['colorHex'])) {
			$this->error['nameColor'] = $this->language->get('error_nameColor');
		}

		if (empty($this->request->post['mobile_general']['mcopyright']) ) {
			$this->error['copyright'] = $this->language->get('error_nameColor');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
}

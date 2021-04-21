<?php
/******************************************************
 * @package	SO Theme Framework for Opencart 2.3.x
 * @author	http://www.magentech.com
 * @license	GNU General Public License
 * @copyright(C) 2008-2015 Magentech.com. All rights reserved.
*******************************************************/


require_once (DIR_APPLICATION.'view/template/extension/soconfig/class/so_field.php');
require_once (DIR_APPLICATION.'view/template/extension/soconfig/class/soconfig.php');
require_once (DIR_APPLICATION.'view/template/extension/soconfig/class/soconfig_cache.php');

class ControllerExtensionModuleSoconfig extends Controller {

    private $error = array();
	private $typeheader = array();
	private $typefooter = array();
	public $typelayout = array();
	 
	public function  __construct($registry) { 
		parent::__construct($registry);
		$this->soconfig = new Soconfig($registry);
		$this->soconfig->cache = new SoconfigCache($registry);
		if(!defined('DIR_SOCONFIG')) define('DIR_SOCONFIG','view/template/extension/soconfig/');
		if(!defined('PATH_SOCONFIG')) define('PATH_SOCONFIG',DIR_APPLICATION.'view/template/extension/soconfig/');

		//Dev Custom Theme
		
		$this->listColor= array(
			'red'    =>'#ea3a3c',
			'orange' =>'#ff5c00',
			'yellow' =>'#e5bc4d',
			'blue'   =>'#003764',
			'cyan'   =>'#0f8db3',
			'green'  =>'#6e8f04',
			// 'red2'  =>'#e21836',
			// 'blue2'  =>'#1090d4',
			// 'blue3'  =>'#1f83cf',
			// 'orange2' =>'#b79663',
			// 'orange3' =>'#ca9f63',
			// 'red3' =>'#d62f2f',
			// 'black' => '#222222'

					
		);
		
		$this->typelayouts = array(
			array(
			'key'=>'1',
			'typelayout'=>'<p>Layout 1</p>',
			'typeheader'=> array('key'=>'1', 'title'=>'Header 1 (used in Layout 1)'),
			'typefooter'=> array('key'=>'1', 'title'=>'Footer 1 (used in Layout 1)'),
			),
			array(
			'key'=>'2',
			'typelayout'=>'<p>Layout 2</p>',
			'typeheader'=> array('key'=>'2', 'title'=>'Header 2 (used in Layout 2)'),
			'typefooter'=> array('key'=>'2', 'title'=>'Footer 2 (used in Layout 2)'),
			),
			array(
			'key'=>'3',
			'typelayout'=>'<p>Layout 3 </p>',
			'typeheader'=> array('key'=>'3', 'title'=>'Header 3 (used in Layout 3)'),
			'typefooter'=> array('key'=>'3', 'title'=>'Footer 3 (used in Layout 3)'),
			),
			array(
			'key'=>'4',
			'typelayout'=>'<p>Layout 4</p>',
			'typeheader'=> array('key'=>'4', 'title'=>'Header 4 (used in Layout 4)'),
			'typefooter'=> array('key'=>'4', 'title'=>'Footer 4 (used in Layout 4)'),
			),
			array(
			'key'=>'5',
			'typelayout'=>'<p>Layout 5</p>',
			'typeheader'=> array('key'=>'5', 'title'=>'Header 5 (used in Layout 5)'),
			'typefooter'=> array('key'=>'5', 'title'=>'Footer 5 (used in Layout 5)'),
			),
			array(
			'key'=>'6',
			'typelayout'=>'<p>Layout 6</p>',
			'typeheader'=> array('key'=>'6', 'title'=>'Header 6 (used in Layout 6)'),
			'typefooter'=> array('key'=>'6', 'title'=>'Footer 6 (used in Layout 6)'),
			),
			array(
			'key'=>'7',
			'typelayout'=>'<p>Layout 7</p>',
			'typeheader'=> array('key'=>'7', 'title'=>'Header 7 (used in Layout 7)'),
			'typefooter'=> array('key'=>'7', 'title'=>'Footer 7 (used in Layout 7)'),
			),
			array(
			'key'=>'8',
			'typelayout'=>'<p>Layout 8</p>',
			'typeheader'=> array('key'=>'8', 'title'=>'Header 8 (used in Layout 8)'),
			'typefooter'=> array('key'=>'8', 'title'=>'Footer 8 (used in Layout 8)'),
			),
			array(
			'key'=>'9',
			'typelayout'=>'<p>Layout 9</p>',
			'typeheader'=> array('key'=>'9', 'title'=>'Header 9 (used in Layout 9)'),
			'typefooter'=> array('key'=>'9', 'title'=>'Footer 9 (used in Layout 9)'),
			),
			array(
			'key'=>'10',
			'typelayout'=>'<p>Layout 10</p>',
			'typeheader'=> array('key'=>'10', 'title'=>'Header 10 (used in Layout 10)'),
			'typefooter'=> array('key'=>'10', 'title'=>'Footer 10 (used in Layout 10)'),
			),
			array(
			'key'=>'11',
			'typelayout'=>'<p>Layout 11</p>',
			'typeheader'=> array('key'=>'11', 'title'=>'Header 11 (used in Layout 11)'),
			'typefooter'=> array('key'=>'11', 'title'=>'Footer 11 (used in Layout 11)'),
			),
			array(
			'key'=>'12',
			'typelayout'=>'<p>Layout 12</p>',
			'typeheader'=> array('key'=>'12', 'title'=>'Header 12 (used in Layout 12)'),
			'typefooter'=> array('key'=>'12', 'title'=>'Footer 12 (used in Layout 12)'),
			),
			array(
			'key'=>'13',
			'typelayout'=>'<p>Layout 13</p>',
			'typeheader'=> array('key'=>'13', 'title'=>'Header 13 (used in Layout 13)'),
			'typefooter'=> array('key'=>'13', 'title'=>'Footer 13 (used in Layout 13)'),
			),
			array(
			'key'=>'14',
			'typelayout'=>'<p>Layout 14</p>',
			'typeheader'=> array('key'=>'14', 'title'=>'Header 14 (used in Layout 14)'),
			'typefooter'=> array('key'=>'14', 'title'=>'Footer 14 (used in Layout 14)'),
			),
			array(
			'key'=>'15',
			'typelayout'=>'<p>Layout 15</p>',
			'typeheader'=> array('key'=>'15', 'title'=>'Header 15 (used in Layout 15)'),
			'typefooter'=> array('key'=>'15', 'title'=>'Footer 15 (used in Layout 15)'),
			),
			
		);

		//End Dev Custom Theme
	}
    public function index() {
		/*===== Load language ========== */
		$this->load->language('extension/module/soconfig');
		$data['direction'] = $this->language->get('direction');
		$data['objlang'] = $this->language;

		/*===== Load Title Module ========== */
		$this->document->setTitle($this->language->get('heading_title_normal'));
		
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
		$this->document->addStyle(DIR_SOCONFIG.'asset/css/banner-effect.css');
		
		/*===== Check RTL Css ========== */
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
	
		if (  $this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate() ) {
			$this->model_extension_module_soconfig_setting->editSetting($this->request->post, $store_id);
			
            // ButtonForm apply
			if($this->request->post['buttonForm'] == 'color' ){
				$scsscompile = $this->request->post['soconfig_advanced_store']['scsscompile'];
				if($scsscompile != 1){
					unset($this->request->post['buttonForm']);
					$this->session->data['success'] = 'Success Compile Sass File To Css';
					$this->soconfig->scss_compass($this->request->post['soconfig_advanced_store']['theme_color'],$this->request->post['soconfig_advanced_store']['name_color'],$this->request->post['soconfig_general_store']['typelayout'],$this->request->post['soconfig_advanced_store']['compileMutiColor'],$this->listColor);
					$this->response->redirect($this->url->link('extension/module/soconfig','user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id, true));
				}else{
					$this->session->data['success'] = 'Error: Compile Sass File To Css, Select Performace -> SCSS Compile = Off';
				}
				
			}else if ($this->request->post['buttonForm'] == 'apply') {
				$this->session->data['success'] = $this->language->get('text_success');
                $this->response->redirect($this->url->link('extension/module/soconfig', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id, true));
				
          	}else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
            }
			
		}
		
		
        if (isset($this->session->data['success'])) {
        	$data['success'] = $this->session->data['success'];
        	unset($this->session->data['success']);
        } else {
        	$data['success'] = '';
        }
		$data['error']= $this->error;
	
		
		$data['typelayouts'] = $this->typelayouts;
		$data['dir_soconfig'] = DIR_SOCONFIG;
		$data['base_href'] = $this->url->link('extension/module/soconfig', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		$data['action'] = $this->url->link('extension/module/soconfig', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id, true);
		$data['clear_cache'] = $this->url->link('extension/module/soconfig/clearcache', 'user_token='. $this->session->data['user_token'].'&store_id='.$store_id, true);
		$data['add_db_product'] = $this->url->link('extension/module/soconfig/addCustomProduct', 'user_token='. $this->session->data['user_token'].'&store_id='.$store_id, true);
		$data['href_product'] = $this->url->link('catalog/product', 'user_token='. $this->session->data['user_token'].'&store_id='.$store_id, true);
		$data['status_product_option']= false;
		$column_exists_tab_title = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_description LIKE 'tab_title'");
		if ($column_exists_tab_title->num_rows)  $data['status_product_option']= true;

		/*===== Default Config Theme Stores========== */
		$default = array(
			'soconfig_advanced_store'	=> array(
				'name_color'	=>'blue',
				'theme_color'	=>'#1f83cf',
				'scsscompile'	=>0,
				'scssformat'	=>'Nested',
				'compileMutiColor'		=>0,
				'cssminify'	=>0,
				'cssExclude'	=> array(
					array(
						'namecss' => 'catalog/view/javascript/font-awesome/css/font-awesome.min.css',
					)
				),
				'jsminify'	=>0,
				'jsExclude'	=> array(
					array(
						'namecss' => 'catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js',
					)
				),
			),
			'soconfig_general_store'	=> array(
				'typelayout'	=>1,
				'themecolor'	=>'blue',
				'toppanel_status'	=>1,
				'toppanel_type'	=>1,
				'phone_status'		=>1,
				'contact_number' 	=> array(
					'1'=>'(84+) 1234455669',
					'2'=>'(84+) 1234455667',
			   	),
				'welcome_message_status'	=>1,
				'welcome_message' 	=> array(
					'1'=>'Welcome Message English',
					'2'=>'Welcome Message Arabic',
			   	),
			   	'promotion_status'	=>1,
				'promotion' 	=> array(
					'1'=>'Promotion English',
					'2'=>'Promotion Arabic',
			   	),
				'wishlist_status'	=>1,
				'checkout_status'	=>1,
				'lang_status'	=>1,
				'preloader'	=>1,
				'preloader_animation'	=>'line',
				'backtop'	=>1,
				'imgpayment_status'	=>1,
				'imgpayment'	=>'catalog/demo/payment/payment.png',
				'copyright'	=>'SO Revo Â© 2015 - {year}. All Rights Reserved. Designed by &lt;a href="magentech.com"&gt;MagenTech.Com&lt;/a&gt;',
				'typeheader'	=>1,
				'typefooter'	=>1,
				'type_banner'	=>4,
			),
			'soconfig_layout_store'	=> array(
				'layoutstyle'	=>'full',
				'theme_bgcolor'	=>'#7a167a',
				'pattern'	=>6,
				'contentbg'	=>'',
				'content_bg_mode'	=>'repeat',
				'content_attachment'		=>'scroll',
			),
			'soconfig_pages_store'	=> array(
				'catalog_col_position'	=>'outside',
				'catalog_col_type'	=>'default',
				'catalog_sidebar_sticky'	=>'disable',
				'catalog_refine_number'	=>6,
				'product_catalog_refine'	=>0,
				'lstimg_cate_status'	=>1,
				'product_catalog_mode'	=>'grid-4',
				'product_scroller'		=> 1,
				'desktop_addcart_position'			=>'left',
				'quick_status'			=>1,
				'desktop_addcart_status'			=>1,
				'desktop_wishlist_status'			=>1,
				'desktop_Compare_status'			=>1,
				'discount_status'		=>1,
				'countdown_status'		=>1,
				'card_gallery'				=>2,
				'rating_status'			=>1,
				'radio_style'			=>1,
				'placeholder_img'  =>'catalog/logo.png',
				'placeholder_status'	=>1,
				'placeholder_status'	=>1,
				'product_enablezoom'	=>1,
				'product_order'	=>1,
				'product_enablesizechart'		=>1,
				'img_sizechart'  =>'catalog/logo.png',
				'tabs_position'  =>2,
				'product_enableshowmore'  =>1,
				'product_enableshipping'  =>0,
				'product_contentshipping' 	=> array(
					'1'=>'Custom block Html',
					'2'=>'Custom block Html',
			   	),
				'product_page_button'  =>1,
				'product_socialshare' 	=> array(
					'1'=>'Custom block Html',
					'2'=>'Custom block Html',
			   	),
				'related_status'  =>1,
				'product_related_column_lg'	=>4,
				'product_related_column_md'	=>3,
				'product_related_column_sm'	=>2,
				'product_related_column_xs'	=>1,
				'comingsoon_imglogo'	=>'catalog/logo.png',
				'comingsoon_title'	=> array(
					'1'=>'Coming Soon Title',
					'2'=>'Coming Soon Title',
			   	),
				'comingsoon_date'	=>'2018-05-26',
				'comingsoon_content'	=> array(
					'1'=>'Coming soon content',
					'2'=>'Coming soon content',
			   	),
			),
			'soconfig_fonts_store'	=> array(
				'body_status'	=>'standard',
				'normal_body'	=>'Arial, Helvetica, sans-serif',
				'url_body'	=>'',
				'family_body'	=>'',
				'selector_body'	=>'body',
				'menu_status'		=>'standard',
				'normal_menu'	=>'inherit',
				'url_menu'	=>'',
				'family_menu'	=>'',
				'selector_menu'	=>'',
				'heading_status'		=>'standard',
				'normal_heading'	=>'inherit',
				'url_heading'	=>'',
				'family_heading'	=>'',
				'selector_heading'	=>'',
			),
			'soconfig_social_store'	=> array(
				'social_sidebar'	=>1,
				'social_fb_status'	=>1,
				'facebook'	=>'magentech',
				'social_twitter_status'	=>1,
				'social_custom_status'	=>1,
				'twitter'	=>'smartaddons',
				'video_code' 	=> array(
					'1'=>'Custom block Html',
					'2'=>'Custom block Html',
			   	),
				
			),
			'soconfig_custom_store'	=> array(
				'cssinput_status'	=>0,
				'cssfile_status'	=>0,
				'cssinput_content'	=>'',
				'cssfile_url'	=> array(
					"0"=>"catalog/view/theme/so-revo/css/custom.css"
			   	),
				'jsinput_status'	=>0,
				'jsfile_status'	=>0,
			),
		);

		if (($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() ) {
			$soconfig_setting = $this->model_extension_module_soconfig_setting->getSetting($store_id);
			$soconfig_setting =  array_merge($default,$soconfig_setting);//check data empty database
		}
				
		//Get theme_directory
		if ($this->config->get('theme_default_directory')) $data['theme_directory'] = $this->config->get('theme_default_directory'); // Remove được
		else $data['theme_directory'] = 'default';
		
		$data['cssExcludes'] = isset($soconfig_setting['soconfig_advanced_store']['cssExclude']) ? $soconfig_setting['soconfig_advanced_store']['cssExclude'] : array();
		$data['jsExcludes'] = isset($soconfig_setting['soconfig_advanced_store']['jsExclude']) ? $soconfig_setting['soconfig_advanced_store']['jsExclude'] : array();

		$typelayout 				= $soconfig_setting['soconfig_general_store']['typelayout'];
		$data['scsscompile'] 		= $soconfig_setting['soconfig_advanced_store']['scsscompile'];
		$data['compileMutiColor'] 	= $soconfig_setting['soconfig_advanced_store']['compileMutiColor'];
		$data['cssfile_url'] 			= isset($soconfig_setting['soconfig_custom_store']['cssfile_url']) ? $soconfig_setting['soconfig_custom_store']['cssfile_url'] : array();
		
		$data['allThemeColor'] 		=  $this->soconfig->getColorScheme($typelayout) ? $this->soconfig->getColorScheme($typelayout) : array('none' => 'None');
        $data['user_token'] 		= $this->session->data['user_token'];
        $data['languages'] = $this->model_localisation_language->getLanguages();
        /*end variables for theme */

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$fields = new So_Fields($soconfig_setting);
		$data['fields'] = $fields;
		$this->response->setOutput($this->load->view('extension/soconfig/soconfig', $data));
	}
	
	public function uninstall() {
        $this->load->model('extension/module/soconfig/setting');
        $this->model_extension_module_soconfig_setting->deleteSetting();
    }
	
    public function install(){
       
		$this->load->model('setting/setting');
		$this->load->model('extension/module/soconfig/setting');
		$this->model_extension_module_soconfig_setting->createTableSoconfig();
		
		//Import sample data current theme
		$main_sql = DIR_TEMPLATE.'extension/soconfig/demo/default/install.php';
		if (!file_exists($main_sql)) return false;   
		include($main_sql);
		
		$this->session->data['success'] = $this->language->get('text_success');
    }
	
	
     public function install_demo($install_layout,$store_active){
		$store_id = $store_active;
		$main_sql = DIR_TEMPLATE.'extension/soconfig/demo/layout'.$install_layout.'/install.php';
		if (!file_exists($main_sql)) return false;   
		
		include($main_sql);
		return true;  
    }
	
	public function addCustomProduct(){
		$this->load->model('extension/module/soconfig/soproduct');
		$this->model_extension_module_soconfig_soproduct->createColumnsInProducts();
	    $this->session->data['success'] = 'You have add field (video, tab_title, html_product_tab) for table product_description';
	    $this->response->redirect($this->url->link('extension/module/soconfig', 'user_token=' . $this->session->data['user_token'], 'SSL'));
    }
	
	public function clearcache(){
	      $this->soconfig->cache->clear();
	      $this->session->data['success'] = 'Cache cleared';
	      $this->response->redirect($this->url->link('extension/module/soconfig', 'user_token=' . $this->session->data['user_token'], 'SSL'));
    }
	

		
	public function applyBaseLayout() {
		$this->load->model('extension/module/soconfig/setting');
		$json = array();

		if (isset($this->request->get['keylayout'])) {
			$keylayout = $this->request->get['keylayout'];
			$store_active = $this->request->get['store_id'];
			$this->install_demo($keylayout,$store_active);
		}
		$this->session->data['success'] = 'Success: You have  apply layout';
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function ColorScheme() {
		$this->load->model('extension/module/soconfig/setting');
		$json = array();

		if (isset($this->request->get['keylayout'])) {
			$keylayout = $this->request->get['keylayout'];
			$results = $this->soconfig->getColorScheme($keylayout);

			if(!empty($results)){
				foreach ($results as $result) {
					$json[] = array('name'        => html_entity_decode($result, ENT_QUOTES, 'UTF-8'));
				}
			}else{
				$json[] = array('name'        => 'No Value');
			}
			
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
    protected function validate() {
    	if (!$this->user->hasPermission('modify', 'extension/module/soconfig')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (empty($this->request->post['soconfig_advanced_store']['name_color']) || empty($this->request->post['soconfig_advanced_store']['theme_color'])) {
			$this->error['nameColor'] = $this->language->get('error_nameColor');
		}
		if (empty($this->request->post['soconfig_general_store']['copyright']) ) {
			$this->error['copyright'] = $this->language->get('error_nameColor');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
}

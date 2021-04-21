<?php
class ControllerExtensionModuleSohomeslider extends Controller {
	private $error = array();
	private $data = array();
	public function index() {
	if(isset($this->request->get['module_id']))
	{
		// Add js
		$this->document->addScript('view/javascript/so_home_slider/js/jquery-ui.min.js');
		$this->document->addStyle('view/javascript/so_home_slider/js/jquery-ui.min.css');
	}
	
	// Load language
		$this->load->language('extension/module/so_home_slider');
		$data['objlang'] = $this->language;
	
	// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();
	
	// Load model	
		$this->load->model('catalog/category');
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_home_slider');
		$this->load->model('tool/image');
		
		$this->document->setTitle($this->language->get('heading_title'));
	// Get data
		$model = $this->model_extension_module_so_home_slider; 
		$model->checkInstall();
			
	// Delete Module
		if( isset($this->request->get['slide_id']) && isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_extension_module_so_home_slider->deleteSlide( $this->request->get['slide_id'] );
			$this->response->redirect($this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'].'&tab=slide&module_id='.$this->request->get['module_id'], 'SSL'));
		}elseif(!isset($this->request->get['slide_id']) && isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->model_extension_module_so_home_slider->deleteAllSlide($this->request->get['module_id']);
			$this->response->redirect($this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
	// Get Module Id New
		$moduleid_new= $this->model_extension_module_so_home_slider->getModuleId(); // Get module id
		$module_id = '';	
		$slideid_new= $this->model_extension_module_so_home_slider->getSlideId(); // Get slide id
		$slide_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$tab_module = isset($this->request->post["tab_module"]) ? $this->request->post["tab_module"] : "";
			//var_dump("<pre>", $this->request->post,"</pre>"); die();
			unset($this->request->post['tab_module']);
			$tab = isset($this->request->get["tab"]) ? $this->request->get["tab"] : "";
			
			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);
			//var_dump("<pre>", $tab_module,"</pre>"); die();
			if($tab_module == 'add-slide' || $tab == 'slide' && $tab_module != 'add-module'  )
			{
				if (!isset($this->request->get['slide_id'])) {
					$this->request->post['slideid'] = $slideid_new[0]['Auto_increment'];
					$slide_id = $slideid_new[0]['Auto_increment'];
					$this->request->post['moduleid'] = $this->request->get['module_id'];

					$this->model_extension_module_so_home_slider->addSlide($this->request->post);
				} else {
					$slide_id = $this->request->get['slide_id'];
					$this->request->post['moduleid'] = $this->request->get['module_id'];
					
					$this->model_extension_module_so_home_slider->editSlide($this->request->get['slide_id'], $this->request->post);
				}
				
				if($action == "save_edit") {
					$this->response->redirect($this->url->link('extension/module/so_home_slider', 'module_id='.$this->request->get['module_id'].'&tab=slide&slide_id='.$slide_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
				}elseif($action == "save_new"){
					$this->response->redirect($this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'].'&tab=slide&module_id='.$this->request->get['module_id'], 'SSL'));
				}else{
					$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
				}
			}else{
				if (!isset($this->request->get['module_id'])) {
					$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
					$module_id = $moduleid_new[0]['Auto_increment'];
					$this->model_setting_module->addModule('so_home_slider', $this->request->post);
					
				} else {
					$module_id = $this->request->get['module_id'];
					$this->request->post['moduleid'] = $this->request->get['module_id'];
					$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
				}
				$data = $this->request->post;
				
				$this->session->data['success'] = $this->language->get('text_success');
				if($action == "save_edit") {
					$this->response->redirect($this->url->link('extension/module/so_home_slider', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
				}elseif($action == "save_new"){
					$this->response->redirect($this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'], 'SSL'));
				}else{
					$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
				}
			}
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		
		
		$default = array(
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
			'use_cache'				=> '0',
			'cache_time'			=> '3600',
			'slide_status'			=> '1'
		);
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || ($this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) || ($this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['slide_id']))) {
			$module_info = $default;
			$info_save   = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info = array_merge($module_info,$info_save);
			$module_info['thumb']			= $this->model_tool_image->resize('no_image.png', 100, 100);
			
			$data['action'] 				= $this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			
			if(isset($this->request->get['slide_id'])){
				$data['action'] = $this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'] . '&tab=slide&module_id='.$this->request->get['module_id'].'&slide_id=' . $this->request->get['slide_id'], 'SSL');
				
			}elseif(isset($this->request->get['tab']) && $this->request->get['tab'] == "slide")
			{
				$data['action'] = $this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'] . '&tab=slide&module_id='.$this->request->get['module_id'], 'SSL');
			}
			$data['subheading'] 			= $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] 			= $this->request->get['module_id'];
			$data['slide_description'] = array(
				'status'	=> '1'
			);
			
			
			if(isset($this->request->get['slide_id'])){
				$data['slide_description'] = $this->model_extension_module_so_home_slider->getSliderById($this->request->get['slide_id']);
				$language = count($data['slide_description']);
				$language_id = (int)$this->config->get('config_language_id');
				if($data['slide_description'][$language_id]['image'] != "")
				{
					if (is_file(DIR_IMAGE . $data['slide_description'][$language_id]['image'])) {
						$data['slide_description']['thumb'] = $this->model_tool_image->resize($data['slide_description'][$language_id]['image'], 100, 100);
						
					} else {
						$data['slide_description']['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
					}
				}
				$data['slide_description']['image'] = $data['slide_description'][$language_id]['image'];
				$data['slide_description']['url'] = $data['slide_description'][$language_id]['url'];
				$data['slide_description']['status'] = $data['slide_description'][$language_id]['status'];
			}


			if($this->request->post != null){

				foreach($this->request->post['slide_description'] as $item){
					//var_dump($item);die();
						$data['slide_description'][$item['language_id']] = array(
						'title'		=> $item['slide_title'],
						'caption'   => $item['slide_caption'],
						'description'=> $item['slide_desciption']
					);
				}
				$data['slide_description']['url'] = $this->request->post['slide_link'];
				$data['slide_description']['image'] = $this->request->post['image'];
				$data['slide_description']['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);	
				$data['slide_description']['status'] = $this->request->post['slide_status'];
						
			}
			
		} else {
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info,$this->request->post);
			}
			
			$data['selectedid'] 	= 0;
			$data['action'] 		= $this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] 	= $this->language->get('text_create_new_module');
		}

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['error']= $this->error;
		// Save and Stay --------------------------------------------------------------
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));	
		
		// ---------------------------Load module --------------------------------------------
		$data['modules'] = array( 0=> $module_info );
		
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_home_slider' );
		$data['link'] = $this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_home_slider&user_token=' . $this->session->data['user_token']);
		//--------------------------------Load Data -------------------------------------------
		$data['item_link_targets'] = array(
					'_blank' => $this->language->get('value_blank'),
					'_self'  => $this->language->get('value_self'),
				);
		
		
		//button page
		$data['button_pages'] = array(
			'top' => $this->language->get('value_top'),
			'under' => $this->language->get('value_under'),
		);
		$data['nb_columns'] = array(
			'1'   => '1',
			'2'   => '2',
			'3'   => '3',
			'4'   => '4',
			'5'   => '5',
			'6'   => '6',
		);
		$data['nb_rows'] = array(
			'1'   => '1',
			'2'   => '2',
			'3'   => '3',
			'4'   => '4',
			'5'   => '5',
			'6'   => '6',
		);
		//Effect 
		//Effect 
		$data['animateIns'] = array(
			'none'=>$this->language->get('none'),	
			'bounceIn'=>$this->language->get('bounceIn'),
			'bounceInDown'=>$this->language->get('bounceInDown'),
			'bounceInLeft'=>$this->language->get('bounceInLeft'),
			'bounceInRight'=>$this->language->get('bounceInRight'),
			'bounceInUp'=>$this->language->get('bounceInUp'),
			'fadeIn'=>$this->language->get('fadeIn'),
			'fadeInDown'=>$this->language->get('fadeInDown'),
			'fadeInDownBig'=>$this->language->get('fadeInDownBig'),
			'fadeInLeft'=>$this->language->get('fadeInLeft'),
			'fadeInLeftBig'=>$this->language->get('fadeInLeftBig'),
			'fadeInRight'=>$this->language->get('fadeInRight'),
			'fadeInRightBig'=>$this->language->get('fadeInRightBig'),
			'fadeInUp'=>$this->language->get('fadeInUp'),
			'fadeInUpBig'=>$this->language->get('fadeInUpBig'),
			'flipInX'=>$this->language->get('flipInX'),
			'flipInY'=>$this->language->get('flipInY'),
			'lightSpeedIn'=>$this->language->get('lightSpeedIn'),
			'rotateIn'=>$this->language->get('rotateIn'),
			'rotateInDownLeft'=>$this->language->get('rotateInDownLeft'),
			'rotateInDownRight'=>$this->language->get('rotateInDownRight'),
			'rotateInUpLeft'=>$this->language->get('rotateInUpLeft'),
			'rotateInUpRight'=>$this->language->get('rotateInUpRight'),
			'slideInUp'=>$this->language->get('slideInUp'),
			'slideInDown'=>$this->language->get('slideInDown'),
			'slideInLeft'=>$this->language->get('slideInLeft'),
			'slideInRight'=>$this->language->get('slideInRight'),
			'zoomIn'=>$this->language->get('zoomIn'),
			'zoomInDown'=>$this->language->get('zoomInDown'),
			'zoomInLeft'=>$this->language->get('zoomInLeft'),
			'zoomInRight'=>$this->language->get('zoomInRight'),
			'zoomInUp'=>$this->language->get('zoomInUp'),
			'rollIn'=>$this->language->get('rollIn'),
		);
		$data['animateOuts'] = array(
			'none'=>$this->language->get('none'),	
			'bounceOut'=>$this->language->get('bounceOut'),
			'bounceOutDown'=>$this->language->get('bounceOutDown'),
			'bounceOutLeft'=>$this->language->get('bounceOutLeft'),
			'bounceOutRight'=>$this->language->get('bounceOutRight'),
			'bounceOutUp'=>$this->language->get('bounceOutUp'),
			'fadeOut'=>$this->language->get('fadeOut'),
			'fadeOutDown'=>$this->language->get('fadeOutDown'),
			'fadeOutDownBig'=>$this->language->get('fadeOutDownBig'),
			'fadeOutLeft'=>$this->language->get('fadeOutLeft'),
			'fadeOutLeftBig'=>$this->language->get('fadeOutLeftBig'),
			'fadeOutRight'=>$this->language->get('fadeOutRight'),
			'fadeOutRightBig'=>$this->language->get('fadeOutRightBig'),
			'fadeOutUp'=>$this->language->get('fadeOutUp'),
			'fadeOutUpBig'=>$this->language->get('fadeOutUpBig'),
			'flipOutX'=>$this->language->get('flipOutX'),
			'flipOutY'=>$this->language->get('flipOutY'),
			'lightSpeedOut'=>$this->language->get('lightSpeedOut'),
			'rotateOut'=>$this->language->get('rotateOut'),
			'rotateOutDownLeft'=>$this->language->get('rotateOutDownLeft'),
			'rotateOutDownRight'=>$this->language->get('rotateOutDownRight'),
			'rotateOutUpLeft'=>$this->language->get('rotateOutUpLeft'),
			'rotateOutUpRight'=>$this->language->get('rotateOutUpRight'),
			'slideOutUp'=>$this->language->get('slideOutUp'),
			'slideOutDown'=>$this->language->get('slideOutDown'),
			'slideOutLeft'=>$this->language->get('slideOutLeft'),
			'slideOutRight'=>$this->language->get('slideOutRight'),
			'zoomOut'=>$this->language->get('zoomOut'),
			'zoomOutDown'=>$this->language->get('zoomOutDown'),
			'zoomOutLeft'=>$this->language->get('zoomOutLeft'),
			'zoomOutRight'=>$this->language->get('zoomOutRight'),
			'zoomOutUp'=>$this->language->get('zoomOutUp'),
			'rollOut'=>$this->language->get('rollOut'),
		);
		// Module description
		$data['module_description'] = $module_info['module_description'];
		// List slide
		$data['slides'] = array();
		
		if(isset($module_info['moduleid']))
		{
			$data['slides'] = $model->getListSliderGroups($module_info['moduleid']);
		}
		
		for($i =0; $i < count($data['slides']); $i++)
		{
			if (is_file(DIR_IMAGE . $data['slides'][$i]['image'])) {
				$data['slides'][$i]['image'] = $this->model_tool_image->resize($data['slides'][$i]['image'], 1400, 600);
			} else {
				$data['slides'][$i]['image'] = $this->model_tool_image->resize('no_image.png', 1000, 600);
			}
		}
		//Get Data Default
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// Remove cache
		$data['success_remove'] = $this->language->get('text_success_remove');
		$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		if($is_ajax && isset($_REQUEST['is_ajax_cache_lite']) && $_REQUEST['is_ajax_cache_lite']){
			self::remove_cache();
		}
		$this->response->setOutput($this->load->view('extension/module/so_home_slider', $data));
	}
	public function remove_cache()
	{
		$folder_cache = DIR_CACHE.'so/';
		if(file_exists($folder_cache))
		{
			self::mageDelTree($folder_cache);
		}
	}
	function mageDelTree($path) {
		if (is_dir($path)) {
			$entries = scandir($path);
			foreach ($entries as $entry) {
				if ($entry != '.' && $entry != '..') {
					self::mageDelTree($path.'/'.$entry);
				}
			}
			@rmdir($path);
		} else {
			@unlink($path);
		}
	}
	public function _breadcrumbs(){
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_home_slider', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_home_slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		foreach($languages as $language){
			if(isset($this->request->post['module_description']))
			{
				$module_description = $this->request->post['module_description'];
				if ((utf8_strlen($module_description[$language['language_id']]['head_name']) < 3) || (utf8_strlen($module_description[$language['language_id']]['head_name']) > 64)) {
					$this->error['head_name'] = $this->language->get('error_head_name');
				}
			}
			if(isset($this->request->post['slide_description']) && $this->request->post['tab_module'] == 'add-slide' || isset($this->request->post['slide_description']) && isset($this->request->get['tab']) && ($this->request->get['tab'] == "slide") && isset($this->request->post['slide_description']) && $this->request->post['tab_module'] == '')
			{
				$slide_description = $this->request->post['slide_description'];
				if ((utf8_strlen($slide_description[$language['language_id']]['slide_title']) < 1)) {
					$this->error['slide_title'] = $this->language->get('error_slide_title');
				}
			}
		}
		
		if (isset($this->request->post['image']) && $this->request->post['tab_module'] == 'add-slide' && (utf8_strlen($this->request->post['image']) < 1) || isset($this->request->post['image']) && isset($this->request->get['tab']) && ($this->request->get['tab'] == "slide") && (utf8_strlen($this->request->post['image']) < 1) && isset($this->request->post['image']) && $this->request->post['tab_module'] == '') {
			
			$this->error['image'] = $this->language->get('error_image');
		}

		
		if (!filter_var($this->request->post['autoplayTimeout'],FILTER_VALIDATE_INT) || $this->request->post['autoplayTimeout'] < 0) {
			$this->error['autoplayTimeout'] = $this->language->get('error_autoplayTimeout');
		}
		
		if (!filter_var($this->request->post['width'],FILTER_VALIDATE_INT) || $this->request->post['width'] < 0) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if (!filter_var($this->request->post['height'],FILTER_VALIDATE_INT) || $this->request->post['height'] < 0) {
			$this->error['height'] = $this->language->get('error_height');
		}
		
		if (!filter_var($this->request->post['autoplaySpeed'],FILTER_VALIDATE_INT) || $this->request->post['autoplaySpeed'] < 0) {
			$this->error['autoplaySpeed'] = $this->language->get('error_autoplaySpeed');
		}

		if ($this->request->post['startPosition'] != '0' && !filter_var($this->request->post['startPosition'],FILTER_VALIDATE_INT) || $this->request->post['startPosition'] < 0) {
			$this->error['startPosition'] = $this->language->get('error_startPosition');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
	
	
	public function sortposition() {
		$json = array();
		$this->load->model('extension/module/so_home_slider');
		if (isset($this->request->get['sortarray'])) {
			$result = $this->model_extension_module_so_home_slider->updatePositionSlide($this->request->get['sortarray']);
			//var_dump($result);die();
		}
	}
}
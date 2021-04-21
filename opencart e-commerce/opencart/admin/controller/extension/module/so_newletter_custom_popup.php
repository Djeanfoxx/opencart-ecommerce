<?php
class ControllerExtensionModuleSonewlettercustompopup extends Controller {
	private $error = array();
	private $data = array();
	public function index() {
		$this->document->addStyle('view/javascript/so_newletter_custom_popup/css/colpick.css');
		$this->document->addStyle('view/javascript/so_newletter_custom_popup/css/style.css');
		$this->document->addScript('view/javascript/so_newletter_custom_popup/js/colpick.js');
		// Load language
		$this->load->language('extension/module/so_newletter_custom_popup');
		$data['objlang'] = $this->language;
		// Load breadcrumbs
		$data['breadcrumbs'] = $this->_breadcrumbs();

		// Load model
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_newletter_custom_popup');
		$this->model_extension_module_so_newletter_custom_popup->createNewsletter();
		$this->document->setTitle($this->language->get('heading_title'));

		// Delete Module
		if( isset($this->request->get['module_id']) && isset($this->request->get['delete']) ){
			$this->model_setting_module->deleteModule( $this->request->get['module_id'] );
			$this->response->redirect($this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		// Get Module Id new
		$moduleid_new= $this->model_extension_module_so_newletter_custom_popup->getModuleId();
		$module_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];
				$this->model_setting_module->addModule('so_newletter_custom_popup', $this->request->post);

			} else {
				$module_id = $this->request->get['module_id'];
				$this->request->post['moduleid'] = $this->request->get['module_id'];
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);
			$data = $this->request->post;

			$this->session->data['success'] = $this->language->get('text_success');
			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/so_newletter_custom_popup', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		
		//=== Theme Custom Code====
		$data['type_footer'] = $this->getFooter();
		
		$default = array(
			'name' 					=> '',
			'module_description'	=> array(),
			'description_content'	=> array(),
			'disp_title_module'		=> '0',
			'status'				=> '1',
			'class_suffix'			=> '',
			'layout'				=> 'layout_default',
			'footer_display1'			=> '0',
			'footer_display2'			=> '0',
			'footer_display3'			=> '0',
			'footer_display_no'			=> '0',
			'expired'				=> '1',
			'width'					=> '50%',
			'image_bg_display'		=> '0',
			'image'					=> '',
			'color_bg'				=> 'fff',
			'title_display'			=> '1',
			'email_template_subject'=> '',
			'content_email'			=> '',
			
			'post_text'				=> '',
			'pre_text'				=> '',
			'use_cache'				=> '0',
			'cache_time'			=> '3600'
		);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info =  array_merge($default,$module_info);//check data empty database
			$data['action'] = $this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit_module') . $module_info['name'];
			$data['selectedid'] = $this->request->get['module_id'];
			$data['image'] = $module_info['image'];
		} else {
			$module_info = $default;
			$data['image'] = $module_info['image'];
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info,$this->request->post);
			}
			$data['selectedid'] = 0;
			$data['action'] = $this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] = $this->language->get('text_create_new_module');
		}

		$this->load->model('tool/image');
		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($module_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['user_token'] = $this->session->data['user_token'];
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['error']= $this->error;

		// Save and Stay --------------------------------------------------------------
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));

		// ---------------------------Load module --------------------------------------------
		$data['modules'] = array( 0=> $module_info );
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode( 'so_newletter_custom_popup');
		$data['link'] = $this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$data['linkremove'] = $this->url->link('extension/module/so_newletter_custom_popup&user_token=' . $this->session->data['user_token']);
		//--------------------------------Load Data -------------------------------------------
		//layout
		$data['layouts'] = array(
			'layout_default' 	=> $this->language->get('value_layout1'),
			'layout_popup' 		=> $this->language->get('value_layout2'),
		);
		
		// Module description
		$data['module_description'] = $module_info['module_description'];
		// Description content
		$data['description_content'] = $module_info['description_content'];

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
		$this->response->setOutput($this->load->view('extension/module/so_newletter_custom_popup/so_newletter_custom_popup', $data));
	}
	//=== Theme Custom Code====
	public function getFooter(){
		$footer_directory  = DIR_CATALOG.'view/theme/'.$this->config->get('theme_default_directory').'/template/footer/';
		if (is_dir($footer_directory)) {
			$file_footer = scandir($footer_directory);
			
			foreach ($file_footer as  $item_footer) {
				if (strpos($item_footer, '.tpl') == true) {
					
					list($fileName_footer) = explode('.tpl',$item_footer); 
					$fileNames_footer[] = ucfirst($fileName_footer);
					
				}
			}
		} 
		return isset($fileNames_footer) ? $fileNames_footer : '';
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
				'href' => $this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_newletter_custom_popup', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_newletter_custom_popup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		foreach($languages as $language){
			$module_description = $this->request->post['module_description'];
			if ((utf8_strlen($module_description[$language['language_id']]['head_name']) < 3) || (utf8_strlen($module_description[$language['language_id']]['head_name']) > 64)) {
				$this->error['head_name'] = $this->language->get('error_head_name');
			}
		}
		foreach($languages as $language){
			$description_content = $this->request->post['description_content'];
			if ((utf8_strlen(html_entity_decode($description_content[$language['language_id']]['title'])) < 3) || (utf8_strlen(html_entity_decode($description_content[$language['language_id']]['title'])) > 64)) {
				$this->error['title'] = $this->language->get('error_title');
			}
			if ((utf8_strlen(html_entity_decode($description_content[$language['language_id']]['newsletter_promo'])) < 3) || (utf8_strlen(html_entity_decode($description_content[$language['language_id']]['newsletter_promo'])) > 164)) {
				$this->error['newsletter_promo'] = $this->language->get('error_newsletter_promo');
			}
		}

		if (!filter_var($this->request->post['expired'],FILTER_VALIDATE_FLOAT) || $this->request->post['expired'] < 0) {
			$this->error['expired'] = $this->language->get('error_expired');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}
	public function history()
	{
		$this->load->language('extension/module/so_newletter_custom_popup');
		$data['objlang'] = $this->language;
		$this->load->model('extension/module/so_newletter_custom_popup');
		$data['newletter_email'] = $this->model_extension_module_so_newletter_custom_popup->getListemail();
		$this->response->setOutput($this->load->view('extension/module/so_newletter_custom_popup/so_newletter_subscribers', $data));
	}
	public function approve_selected()
	{
		$this->load->model('extension/module/so_newletter_custom_popup');
		$json = array();
		$json['success'] = $this->model_extension_module_so_newletter_custom_popup->approve_status($_GET['subscribe_id']);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	public function approve_all_selected()
	{
		if(!$this->request->post){
			$json['error'] =  "Update Fail! plese check item";
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}else {
			$this->load->model('extension/module/so_newletter_custom_popup');
			$json = array();
			$check = $this->model_extension_module_so_newletter_custom_popup->approve_all_selected($this->request->post);
			if (isset($check)) {
				$json['success'] = "Update Successfull";
			} else {
				$json['error'] = "Update Fail";
			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}

	}
	public function approve_all_not_approved()
	{
		$this->load->model('extension/module/so_newletter_custom_popup');
		$json = array();
		$json['success'] = $this->model_extension_module_so_newletter_custom_popup->approve_all_not_approved();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	public function delete_selected()
	{
		$this->load->model('extension/module/so_newletter_custom_popup');
		$json = array();
		$json['success'] = $this->model_extension_module_so_newletter_custom_popup->delete_item($_GET['subscribe_id']);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	public function delete_all()
	{
		$this->load->model('extension/module/so_newletter_custom_popup');
		$json = array();
		$json['success'] = $this->model_extension_module_so_newletter_custom_popup->delete_all();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function delete_all_selected()
	{
		if(!$this->request->post){
			$json['error'] =  "Delete Fail! plese check item";
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}else {
			$this->load->model('extension/module/so_newletter_custom_popup');
			$json = array();
			$check = $this->model_extension_module_so_newletter_custom_popup->delete_all_selected($this->request->post);
			if (isset($check)) {
				$json['success'] = "Delete Successfull";
			} else {
				$json['error'] = "Delete Fail";
			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function delete_all_not_approved()
	{
		$this->load->model('extension/module/so_newletter_custom_popup');
		$json = array();
		$json['success'] = $this->model_extension_module_so_newletter_custom_popup->delete_all_not_approved();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	public function revert_yet_send()
	{
		$this->load->model('extension/module/so_newletter_custom_popup');
		$json = array();
		$json['success'] = $this->model_extension_module_so_newletter_custom_popup->revert_yet_send();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function mailing_all()
	{
		$json = array();
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_newletter_custom_popup');
		$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		$title = $module_info['email_template_subject'];
		$content = $module_info['content_email'];

		$emails = array();
		$results = $this->model_extension_module_so_newletter_custom_popup->mailing_all();
		foreach ($results as $result) {
			$emails[] = $result['news_email'];
		}
		try{
			if($title =='' || $content ==''){
				$json['error'] =  'Failure in sending email because of empty content';
			}else{
				$this->sendmail($emails, $title, $content);
				$json['success'] = "Send mail Successfull";
				$this->model_extension_module_so_newletter_custom_popup->getconfirm_mail_all();
			}
		}catch (Exception $e)
		{
			$json['error'] =  $e;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function mailing_selected()
	{
		$json = array();
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_newletter_custom_popup');
		$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		$title = $module_info['email_template_subject'];
		$content = $module_info['content_email'];

		$emails = array();
		$results = $this->model_extension_module_so_newletter_custom_popup->mailing_selected($_GET['subscribe_id']);
		//var_dump($results); Fixbugs
		foreach ($results as $result) {
			$emails[] = $result['news_email'];
		}
		try{
			if($title =='' || $content ==''){
				$json['error'] =  'Failure in sending email because of empty content';
			}else{

				$this->sendmail($emails, $title, $content);
				$json['success'] = "Send mail Successfull";
				$this->model_extension_module_so_newletter_custom_popup->getconfirm_mailing_selected($_GET['subscribe_id']);
			}
		}catch (Exception $e)
		{
			$json['error'] =  $e;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function mailing_all_selected()
	{
		if(!$this->request->post){
			$json['error'] =  "Send mail Fail! plese check item";
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}else {
			$json = array();
			$this->load->model('setting/module');
			$this->load->model('extension/module/so_newletter_custom_popup');
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$title = $module_info['email_template_subject'];
			$content = $module_info['content_email'];

			$emails = array();
			$results = $this->model_extension_module_so_newletter_custom_popup->mailing_all_selected($this->request->post);
			foreach ($results as $result) {
				foreach ($result as $r) {
					$emails[] = $r['news_email'];
				}
			}
			try {
				if($title =='' || $content ==''){
					$json['error'] =  'Failure in sending email because of empty content';
				}else{
					$this->sendmail($emails, $title, $content);
					$json['success'] = "Send mail Successfull";
					$this->model_extension_module_so_newletter_custom_popup->getconfirm_mail_all_selected($this->request->post);
				}
			} catch (Exception $e) {
				$json['error'] = $e;
			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}
	public function mailing_all_not_notified()
	{
		$json = array();
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_newletter_custom_popup');
		$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		$title = $module_info['email_template_subject'];
		$content = $module_info['content_email'];

		$emails = array();
		$results = $this->model_extension_module_so_newletter_custom_popup->mailing_all_not_notified();
		foreach ($results as $result) {
			$emails[] = $result['news_email'];
		}
		try{
			if($title =='' || $content ==''){
				$json['error'] =  'Failure in sending email because of empty content';
			}else {
				if (!$emails) {
					$json['error'] = 'Send email error';
				} else {
					$this->sendmail($emails, $title, $content);
					$json['success'] = "Send mail Successfull";
					$this->model_extension_module_so_newletter_custom_popup->getconfirm_mail_all_not_notified();
				}
			}
		}catch (Exception $e)
		{
			$json['error'] =  $e;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function mailing_all_approved()
	{
		$json = array();
		$this->load->model('setting/module');
		$this->load->model('extension/module/so_newletter_custom_popup');
		$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		$title = $module_info['email_template_subject'];
		$content = $module_info['content_email'];

		$emails = array();
		$results = $this->model_extension_module_so_newletter_custom_popup->mailing_all_approved();
		foreach ($results as $result) {
			$emails[] = $result['news_email'];
		}
		try{
			if($title =='' || $content ==''){
				$json['error'] =  'Failure in sending email because of empty content';
			}else{
				if(!$emails){
					$json['error'] = 'Send email error';
				}else{
					$this->sendmail($emails, $title, $content);
					$json['success'] = "Send mail Successfull";
					$this->model_extension_module_so_newletter_custom_popup->getconfirm_mail_all_approved();
				}
			}
		}catch (Exception $e)
		{
			$json['error'] =  $e;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function sendmail($emails, $title, $content){

		$message  = '<html dir="ltr" lang="en">' . "\n";
		$message .= '  <head>' . "\n";
		$message .= '    <title>' . $title . '</title>' . "\n";
		$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
		$message .= '  </head>' . "\n";
		$message .= '  <body>' . html_entity_decode($content, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
		$message .= '</html>' . "\n";
		foreach ($emails as $email) {
			if (preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($email);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($message);
				$mail->send();
			}
		}
	}
}
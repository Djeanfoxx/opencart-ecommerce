<?php

class ControllerExtensionModuleSoFacebookMessage extends Controller {
	public function index($setting) {
        $data = array();
        if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$setting['widget_text'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['widget_text'], ENT_QUOTES, 'UTF-8');
		}
		$data['setting']    = $setting;
		$this->load->model('extension/module/so_facebook_message');
        $this->document->addStyle('catalog/view/javascript/so_facebook_message/css/style.css');                
		return $this->load->view('extension/module/so_facebook_message/default', $data);
	}
}
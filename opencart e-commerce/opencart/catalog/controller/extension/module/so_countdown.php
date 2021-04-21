<?php

class ControllerExtensionModuleSoCountdown extends Controller {
	public function index() {
		$this->load->model('extension/module/so_countdown');
        $this->document->addStyle('catalog/view/javascript/so_countdown/css/style.css');
        $this->document->addScript('catalog/view/javascript/so_countdown/js/jquery.cookie.js');
        $this->load->language('extension/module/so_deals');
        $data       = array();
        $result     = $this->model_extension_module_so_countdown->getList();
        if (!empty($result)) {
            $now        = strtotime(date('Y-m-d', time()));
            $end_date   = strtotime($result['date_expire']);
            $result['end_date'] = date('Y/m/d', $end_date);
            $result['end_date'] = date("Y/m/d", strtotime($result["end_date"]));
            $result['description'] = html_entity_decode($result['description']);
            if (isset($result['image']) && !empty($result['image']) && is_file(getcwd().'/image/'.$result['image'])) {
                $image    = $result['image'];
            }
            else {
                $image = false;
            }
            $result['image'] = $image;

            $http = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
            if (isset($result['link']) && $result['link'] != '') {
                if (strpos($result['link'], $http) === false) {
                    $link = $http.'://'.$result['link'];
                }
                else {
                    $link = $result['link'];
                }
            }
            else {
                $link = false;
            }
            $result['link']     = $link;

            $data['result']   = $result;        
            
    		return $this->load->view('extension/module/so_countdown/default', $data);
        }
        else
            return '';
	}
}
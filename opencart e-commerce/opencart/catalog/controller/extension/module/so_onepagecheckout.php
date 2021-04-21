<?php
class ControllerExtensionModuleSoOnepageCheckout extends Controller {
	public function index() {
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('so_onepagecheckout');

		$this->load->language('extension/module/so_onepagecheckout');
		
		$data['heading_title'] = $this->language->get('heading_title');
		if(isset($this->request->get['route']))
		{
			$this->session->data['route']=$this->request->get['route'];
		}

		// Shipping Methods
		$method_data = array();

		$this->load->model('extension/extension');

		$results = $this->model_extension_extension->getExtensions('shipping');

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('extension/shipping/' . $result['code']);

				$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

				if ($quote) {
					$method_data[$result['code']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
		}

		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		$this->session->data['shipping_methods'] = $method_data;
		
		if(VERSION >= '2.2.0.0'){
        	return $this->load->view('extension/module/so_onepagecheckout', $data);
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/so_onepagecheckout.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/so_onepagecheckout.tpl', $data);
        } else {
            return $this->load->view('default/template/module/so_onepagecheckout.tpl', $data);
        }
	}
}
<?php
class ControllerExtensionModuleSoOnepageCheckout extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/so_onepagecheckout');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['objlang']	= $this->language;

		$this->load->model('setting/module');
		$this->load->model('setting/setting');
		$this->load->model('extension/module/so_onepagecheckout');
		$this->load->model('localisation/country');
		
		$this->document->addStyle('view/javascript/so_onepagecheckout/css/so_onepagecheckout.css');

		$module_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);
			
			$params 				= $this->request->post['so_onepagecheckout'];
			$params_general 		= $this->request->post['so_onepagecheckout']['so_onepagecheckout_general'];
			$params_layout_setting 	= $this->request->post['so_onepagecheckout']['so_onepagecheckout_layout_setting'];
			// $_params = array_merge($params_general, $)
			// echo "<pre>";print_r($params);die();
			$this->model_setting_setting->editSetting('so_onepagecheckout', $params);
			
			$params_module = array('name'=>$params_general['so_onepagecheckout_name'], 'status'=>$params_general['so_onepagecheckout_enabled']);
			
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('so_onepagecheckout', $params_module);
				$module_id = $this->db->getLastId();
			}
			else {
				$module_id = $this->request->get['module_id'];
				$this->model_setting_module->editModule($this->request->get['module_id'], $params_module);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/so_onepagecheckout', 'user_token=' . $this->session->data['user_token'] . '&module_id='.$module_id, 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_onepagecheckout', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
		}

		// Save and Stay --------------------------------------------------------------
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_onepagecheckout', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_onepagecheckout', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);			
		}

		// Get country list
		$data['countries']	= $this->model_localisation_country->getCountries();
		$data['user_token']	= $this->session->data['user_token'];

		// Get Language
		$data['heading_title'] 		= $this->language->get('heading_title');
		$data['heading_title_so'] 	= $this->language->get('heading_title_so');
		$data['tab_general']		= $this->language->get('tab_general');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		
		// Tabs
		$data['tab_account_setting'] 	= $this->language->get('tab_account_setting');
		$data['tab_layout_setting'] 	= $this->language->get('tab_layout_setting');
		$data['tab_shipping_cart'] 		= $this->language->get('tab_shipping_cart');
		$data['tab_delivery_methods'] 	= $this->language->get('tab_delivery_methods');
		$data['tab_payment_methods'] 	= $this->language->get('tab_payment_methods');
		$data['tab_confirm_order'] 		= $this->language->get('tab_confirm_order');
		
		$data['text_yes'] 						= $this->language->get('text_yes');
		$data['text_no'] 						= $this->language->get('text_no');
		$data['text_default_country'] 			= $this->language->get('text_default_country');
		$data['text_default_zone'] 				= $this->language->get('text_default_zone');
		
		$data['text_register_account'] 			= $this->language->get('text_register_account');
		$data['text_guest_checkout'] 			= $this->language->get('text_guest_checkout');
		$data['text_login_checkout'] 			= $this->language->get('text_login_checkout');
		$data['text_default_display'] 			= $this->language->get('text_default_display');
		$data['text_register'] 					= $this->language->get('text_register');
		$data['text_guest'] 					= $this->language->get('text_guest');
		$data['text_login'] 					= $this->language->get('text_login');
		
		$data['text_shopping_cart'] 			= $this->language->get('text_shopping_cart');
		$data['text_shopping_cart_status'] 		= $this->language->get('text_shopping_cart_status');
		$data['text_show_weight'] 				= $this->language->get('text_show_weight');
		$data['text_quantity_update_permission'] = $this->language->get('text_quantity_update_permission');
		$data['text_show_removecart'] 			= $this->language->get('text_show_removecart');
		$data['text_product_image_size'] 		= $this->language->get('text_product_image_size');
		$data['text_delivery_methods'] 			= $this->language->get('text_delivery_methods');
		$data['text_delivery_methods_status'] 	= $this->language->get('text_delivery_methods_status');
		$data['text_payment_methods_status'] 	= $this->language->get('text_payment_methods_status');
		$data['text_payment_methods'] 			= $this->language->get('text_payment_methods');
		$data['text_confirm_order'] 			= $this->language->get('text_confirm_order');
		$data['text_add_comments'] 				= $this->language->get('text_add_comments');
		$data['text_require_comment'] 			= $this->language->get('text_require_comment');
		$data['text_show_newsletter'] 			= $this->language->get('text_show_newsletter');
		$data['text_show_privacy'] 				= $this->language->get('text_show_privacy');
		$data['text_show_term'] 				= $this->language->get('text_show_term');
		$data['text_checkout_order_button'] 	= $this->language->get('text_checkout_order_buttons');

		$data['text_coupon_voucher'] 			= $this->language->get('text_coupon_voucher');
		$data['text_show_module_name'] 			= $this->language->get('text_show_module_name');
		$data['text_login_account'] 			= $this->language->get('text_login_account');
		$data['text_coupon'] 					= $this->language->get('text_coupon');
		$data['text_reward'] 					= $this->language->get('text_reward');
		$data['text_voucher'] 					= $this->language->get('text_voucher');
		$data['text_status'] 					= $this->language->get('text_status');
		$data['text_layout_title'] 				= $this->language->get('text_layout_title');
		$data['text_layout'] 					= $this->language->get('text_layout');
		$data['text_layout_one'] 				= $this->language->get('text_layout_one');
		$data['text_layout_two'] 				= $this->language->get('text_layout_two');
		$data['text_layout_three'] 				= $this->language->get('text_layout_three');
		$data['text_shipping_methods'] 			= $this->language->get('text_shipping_methods');
		$data['tab_help'] 						= $this->language->get('tab_help');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['entry_name_title'] = $this->language->get('entry_name_title');
		$data['entry_status_title'] = $this->language->get('entry_status_title');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['error_name'])) {
			$data['error_name'] = $this->error['error_name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['error_product_image_width'])) {
			$data['error_product_image_width'] = $this->error['error_product_image_width'];
		} else {
			$data['error_product_image_width'] = '';
		}

		if (isset($this->error['error_product_image_height'])) {
			$data['error_product_image_height'] = $this->error['error_product_image_height'];
		} else {
			$data['error_product_image_height'] = '';
		}
		
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_onepagecheckout', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_onepagecheckout', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		$setting_so_onepagecheckout					= $this->model_setting_setting->getSetting('so_onepagecheckout');
		$setting_so_onepagecheckout_general			= isset($setting_so_onepagecheckout['so_onepagecheckout_general']) ? $setting_so_onepagecheckout['so_onepagecheckout_general'] : array();
		$setting_so_onepagecheckout_layout_setting	= isset($setting_so_onepagecheckout['so_onepagecheckout_layout_setting']) ? $setting_so_onepagecheckout['so_onepagecheckout_layout_setting'] : array();
		$params 				= isset($this->request->post['so_onepagecheckout']) ? $this->request->post['so_onepagecheckout'] : array();
		$params_general 		= isset($this->request->post['so_onepagecheckout']['so_onepagecheckout_general']) ? $this->request->post['so_onepagecheckout']['so_onepagecheckout_general'] : array();
		$params_layout_setting 	= isset($this->request->post['so_onepagecheckout']['so_onepagecheckout_layout_setting']) ? $this->request->post['so_onepagecheckout']['so_onepagecheckout_layout_setting'] : array();

		if (isset($params_general['so_onepagecheckout_name'])) {
			$data['so_onepagecheckout_name'] = $params_general['so_onepagecheckout_name'];
		} elseif (!empty($setting_so_onepagecheckout_general)) {
			$data['so_onepagecheckout_name'] = isset($setting_so_onepagecheckout_general['so_onepagecheckout_name']) ? $setting_so_onepagecheckout_general['so_onepagecheckout_name'] : '';
		} else {
			$data['so_onepagecheckout_name'] = '';
		}

		if (isset($params_general['so_onepagecheckout_layout'])) {
			$data['so_onepagecheckout_layout'] = $params_general['so_onepagecheckout_layout'];
		} elseif (!empty($setting_so_onepagecheckout_general)) {
			$data['so_onepagecheckout_layout'] = isset($setting_so_onepagecheckout_general['so_onepagecheckout_layout']) ? $setting_so_onepagecheckout_general['so_onepagecheckout_layout'] : '';
		} else {
			$data['so_onepagecheckout_layout'] = '';
		}

		if (isset($params_general['so_onepagecheckout_enabled'])) {
			$data['so_onepagecheckout_enabled'] = $params_general['so_onepagecheckout_enabled'];
		} elseif (!empty($setting_so_onepagecheckout_general)) {
			$data['so_onepagecheckout_enabled'] = isset($setting_so_onepagecheckout_general['so_onepagecheckout_enabled']) ? $setting_so_onepagecheckout_general['so_onepagecheckout_enabled'] : '';
		} else {
			$data['so_onepagecheckout_enabled'] = '';
		}

		if (isset($params_general['so_onepagecheckout_country_id'])) {
			$data['so_onepagecheckout_country_id'] = $params_general['so_onepagecheckout_country_id'];
		} elseif (!empty($setting_so_onepagecheckout_general)) {
			$data['so_onepagecheckout_country_id'] = isset($setting_so_onepagecheckout_general['so_onepagecheckout_country_id']) ? $setting_so_onepagecheckout_general['so_onepagecheckout_country_id'] : '';
		} else {
			$data['so_onepagecheckout_country_id'] = '';
		}

		if (isset($params_general['so_onepagecheckout_zone_id'])) {
			$data['so_onepagecheckout_zone_id'] = $params_general['so_onepagecheckout_zone_id'];
		} elseif (!empty($setting_so_onepagecheckout_general)) {
			$data['so_onepagecheckout_zone_id'] = isset($setting_so_onepagecheckout_general['so_onepagecheckout_zone_id']) ? $setting_so_onepagecheckout_general['so_onepagecheckout_zone_id'] : '';
		} else {
			$data['so_onepagecheckout_zone_id'] = '';
		}

		if (isset($params_layout_setting['so_onepagecheckout_register_checkout'])) {
			$data['so_onepagecheckout_register_checkout'] = $params_layout_setting['so_onepagecheckout_register_checkout'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['so_onepagecheckout_register_checkout'] = isset($setting_so_onepagecheckout_layout_setting['so_onepagecheckout_register_checkout']) ? $setting_so_onepagecheckout_layout_setting['so_onepagecheckout_register_checkout'] : '';
		} else {
			$data['so_onepagecheckout_register_checkout'] = '';
		}

		if (isset($params_layout_setting['so_onepagecheckout_guest_checkout'])) {
			$data['so_onepagecheckout_guest_checkout'] = $params_layout_setting['so_onepagecheckout_guest_checkout'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['so_onepagecheckout_guest_checkout'] = isset($setting_so_onepagecheckout_layout_setting['so_onepagecheckout_guest_checkout']) ? $setting_so_onepagecheckout_layout_setting['so_onepagecheckout_guest_checkout'] : '';
		} else {
			$data['so_onepagecheckout_guest_checkout'] = '';
		}

		if (isset($params_layout_setting['so_onepagecheckout_enable_login'])) {
			$data['so_onepagecheckout_enable_login'] = $params_layout_setting['so_onepagecheckout_enable_login'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['so_onepagecheckout_enable_login'] = isset($setting_so_onepagecheckout_layout_setting['so_onepagecheckout_enable_login']) ? $setting_so_onepagecheckout_layout_setting['so_onepagecheckout_enable_login'] : '';
		} else {
			$data['so_onepagecheckout_enable_login'] = '';
		}

		if (isset($params_layout_setting['so_onepagecheckout_account_open'])) {
			$data['so_onepagecheckout_account_open'] = $params_layout_setting['so_onepagecheckout_account_open'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['so_onepagecheckout_account_open'] = isset($setting_so_onepagecheckout_layout_setting['so_onepagecheckout_account_open']) ? $setting_so_onepagecheckout_layout_setting['so_onepagecheckout_account_open'] : '';
		} else {
			$data['so_onepagecheckout_account_open'] = '';
		}

		if (isset($params_layout_setting['shopping_cart_status'])) {
			$data['shopping_cart_status'] = $params_layout_setting['shopping_cart_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['shopping_cart_status'] = isset($setting_so_onepagecheckout_layout_setting['shopping_cart_status']) ? $setting_so_onepagecheckout_layout_setting['shopping_cart_status'] : '';
		} else {
			$data['shopping_cart_status'] = '';
		}

		if (isset($params_layout_setting['show_product_weight'])) {
			$data['show_product_weight'] = $params_layout_setting['show_product_weight'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['show_product_weight'] = isset($setting_so_onepagecheckout_layout_setting['show_product_weight']) ? $setting_so_onepagecheckout_layout_setting['show_product_weight'] : '';
		} else {
			$data['show_product_weight'] = '';
		}

		if (isset($params_layout_setting['show_product_qnty_update'])) {
			$data['show_product_qnty_update'] = $params_layout_setting['show_product_qnty_update'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['show_product_qnty_update'] = isset($setting_so_onepagecheckout_layout_setting['show_product_qnty_update']) ? $setting_so_onepagecheckout_layout_setting['show_product_qnty_update'] : '';
		} else {
			$data['show_product_qnty_update'] = '';
		}

		if (isset($params_layout_setting['show_product_removecart'])) {
			$data['show_product_removecart'] = $params_layout_setting['show_product_removecart'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['show_product_removecart'] = isset($setting_so_onepagecheckout_layout_setting['show_product_removecart']) ? $setting_so_onepagecheckout_layout_setting['show_product_removecart'] : '';
		} else {
			$data['show_product_removecart'] = '';
		}

		if (isset($params_layout_setting['show_product_image_width'])) {
			$data['show_product_image_width'] = $params_layout_setting['show_product_image_width'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['show_product_image_width'] = isset($setting_so_onepagecheckout_layout_setting['show_product_image_width']) ? $setting_so_onepagecheckout_layout_setting['show_product_image_width'] : '';
		} else {
			$data['show_product_image_width'] = '';
		}

		if (isset($params_layout_setting['show_product_image_height'])) {
			$data['show_product_image_height'] = $params_layout_setting['show_product_image_height'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['show_product_image_height'] = isset($setting_so_onepagecheckout_layout_setting['show_product_image_height']) ? $setting_so_onepagecheckout_layout_setting['show_product_image_height'] : '';
		} else {
			$data['show_product_image_height'] = '';
		}

		if (isset($params_layout_setting['delivery_method_status'])) {
			$data['delivery_method_status'] = $params_layout_setting['delivery_method_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['delivery_method_status'] = isset($setting_so_onepagecheckout_layout_setting['delivery_method_status']) ? $setting_so_onepagecheckout_layout_setting['delivery_method_status'] : '';
		} else {
			$data['delivery_method_status'] = '';
		}

		if (isset($params_layout_setting['comment_status'])) {
			$data['comment_status'] = $params_layout_setting['comment_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['comment_status'] = isset($setting_so_onepagecheckout_layout_setting['comment_status']) ? $setting_so_onepagecheckout_layout_setting['comment_status'] : '';
		} else {
			$data['comment_status'] = '';
		}

		if (isset($params_layout_setting['require_comment_status'])) {
			$data['require_comment_status'] = $params_layout_setting['require_comment_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['require_comment_status'] = isset($setting_so_onepagecheckout_layout_setting['require_comment_status']) ? $setting_so_onepagecheckout_layout_setting['require_comment_status'] : '';
		} else {
			$data['require_comment_status'] = '';
		}

		if (isset($params_layout_setting['show_newsletter'])) {
			$data['show_newsletter'] = $params_layout_setting['show_newsletter'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['show_newsletter'] = isset($setting_so_onepagecheckout_layout_setting['show_newsletter']) ? $setting_so_onepagecheckout_layout_setting['show_newsletter'] : '';
		} else {
			$data['show_newsletter'] = '';
		}

		if (isset($params_layout_setting['show_privacy'])) {
			$data['show_privacy'] = $params_layout_setting['show_privacy'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['show_privacy'] = isset($setting_so_onepagecheckout_layout_setting['show_privacy']) ? $setting_so_onepagecheckout_layout_setting['show_privacy'] : '';
		} else {
			$data['show_privacy'] = '';
		}

		if (isset($params_layout_setting['show_term'])) {
			$data['show_term'] = $params_layout_setting['show_term'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['show_term'] = isset($setting_so_onepagecheckout_layout_setting['show_term']) ? $setting_so_onepagecheckout_layout_setting['show_term'] : '';
		} else {
			$data['show_term'] = '';
		}

		if (isset($params_layout_setting['confirm_button_status'])) {
			$data['confirm_button_status'] = $params_layout_setting['confirm_button_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['confirm_button_status'] = isset($setting_so_onepagecheckout_layout_setting['confirm_button_status']) ? $setting_so_onepagecheckout_layout_setting['confirm_button_status'] : '';
		} else {
			$data['confirm_button_status'] = '';
		}

		if (isset($params_layout_setting['coupon_login_status'])) {
			$data['coupon_login_status'] = $params_layout_setting['coupon_login_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['coupon_login_status'] = isset($setting_so_onepagecheckout_layout_setting['coupon_login_status']) ? $setting_so_onepagecheckout_layout_setting['coupon_login_status'] : '';
		} else {
			$data['coupon_login_status'] = '';
		}

		if (isset($params_layout_setting['coupon_register_status'])) {
			$data['coupon_register_status'] = $params_layout_setting['coupon_register_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['coupon_register_status'] = isset($setting_so_onepagecheckout_layout_setting['coupon_register_status']) ? $setting_so_onepagecheckout_layout_setting['coupon_register_status'] : '';
		} else {
			$data['coupon_register_status'] = '';
		}

		if (isset($params_layout_setting['coupon_guest_status'])) {
			$data['coupon_guest_status'] = $params_layout_setting['coupon_guest_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['coupon_guest_status'] = isset($setting_so_onepagecheckout_layout_setting['coupon_guest_status']) ? $setting_so_onepagecheckout_layout_setting['coupon_guest_status'] : '';
		} else {
			$data['coupon_guest_status'] = '';
		}

		if (isset($params_layout_setting['reward_login_status'])) {
			$data['reward_login_status'] = $params_layout_setting['reward_login_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['reward_login_status'] = isset($setting_so_onepagecheckout_layout_setting['reward_login_status']) ? $setting_so_onepagecheckout_layout_setting['reward_login_status'] : '';
		} else {
			$data['reward_login_status'] = '';
		}

		if (isset($params_layout_setting['reward_register_status'])) {
			$data['reward_register_status'] = $params_layout_setting['reward_register_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['reward_register_status'] = isset($setting_so_onepagecheckout_layout_setting['reward_register_status']) ? $setting_so_onepagecheckout_layout_setting['reward_register_status'] : '';
		} else {
			$data['reward_register_status'] = '';
		}

		if (isset($params_layout_setting['reward_guest_status'])) {
			$data['reward_guest_status'] = $params_layout_setting['reward_guest_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['reward_guest_status'] = isset($setting_so_onepagecheckout_layout_setting['reward_guest_status']) ? $setting_so_onepagecheckout_layout_setting['reward_guest_status'] : '';
		} else {
			$data['reward_guest_status'] = '';
		}

		if (isset($params_layout_setting['voucher_login_status'])) {
			$data['voucher_login_status'] = $params_layout_setting['voucher_login_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['voucher_login_status'] = isset($setting_so_onepagecheckout_layout_setting['voucher_login_status']) ? $setting_so_onepagecheckout_layout_setting['voucher_login_status'] : '';
		} else {
			$data['voucher_login_status'] = '';
		}

		if (isset($params_layout_setting['voucher_register_status'])) {
			$data['voucher_register_status'] = $params_layout_setting['voucher_register_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['voucher_register_status'] = isset($setting_so_onepagecheckout_layout_setting['voucher_register_status']) ? $setting_so_onepagecheckout_layout_setting['voucher_register_status'] : '';
		} else {
			$data['voucher_register_status'] = '';
		}

		if (isset($params_layout_setting['voucher_guest_status'])) {
			$data['voucher_guest_status'] = $params_layout_setting['voucher_guest_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['voucher_guest_status'] = isset($setting_so_onepagecheckout_layout_setting['voucher_guest_status']) ? $setting_so_onepagecheckout_layout_setting['voucher_guest_status'] : '';
		} else {
			$data['voucher_guest_status'] = '';
		}

		if (isset($params_layout_setting['payment_method_status'])) {
			$data['payment_method_status'] = $params_layout_setting['payment_method_status'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['payment_method_status'] = isset($setting_so_onepagecheckout_layout_setting['payment_method_status']) ? $setting_so_onepagecheckout_layout_setting['payment_method_status'] : '';
		} else {
			$data['payment_method_status'] = '';
		}

		if (isset($params_layout_setting['so_onepagecheckout_default_payment'])) {
			$data['so_onepagecheckout_default_payment'] = $params_layout_setting['so_onepagecheckout_default_payment'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['so_onepagecheckout_default_payment'] = isset($setting_so_onepagecheckout_layout_setting['so_onepagecheckout_default_payment']) ? $setting_so_onepagecheckout_layout_setting['so_onepagecheckout_default_payment'] : '';
		} else {
			$data['so_onepagecheckout_default_payment'] = '';
		}

		if (isset($params_layout_setting['so_onepagecheckout_default_shipping'])) {
			$data['so_onepagecheckout_default_shipping'] = $params_layout_setting['so_onepagecheckout_default_shipping'];
		} elseif (!empty($setting_so_onepagecheckout_layout_setting)) {
			$data['so_onepagecheckout_default_shipping'] = isset($setting_so_onepagecheckout_layout_setting['so_onepagecheckout_default_shipping']) ? $setting_so_onepagecheckout_layout_setting['so_onepagecheckout_default_shipping'] : '';
		} else {
			$data['so_onepagecheckout_default_shipping'] = '';
		}

		$data['payment_methods']	= $this->model_extension_module_so_onepagecheckout->getPaymentMethods();
		$data['shipping_methods']	= $this->model_extension_module_so_onepagecheckout->getShippingMethods();

		$data['setting_so_onepagecheckout_layout_setting']	= $setting_so_onepagecheckout_layout_setting;

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/so_onepagecheckout', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_onepagecheckout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$params					= $this->request->post['so_onepagecheckout'];
		$params_general 		= $params['so_onepagecheckout_general'];
		$params_layout_setting 	= $params['so_onepagecheckout_layout_setting'];
		
		if ((utf8_strlen($params_general['so_onepagecheckout_name']) < 3) || (utf8_strlen($params_general['so_onepagecheckout_name']) > 64)) {
			$this->error['error_name'] = $this->language->get('error_name');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!empty($params_layout_setting['show_product_image_width'])) {
			if (!is_numeric($params_layout_setting['show_product_image_width'])) {
				$this->error['error_product_image_width'] = $this->language->get('error_product_image_width');
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}

		if (!empty($params_layout_setting['show_product_image_height'])) {
			if (!is_numeric($params_layout_setting['show_product_image_height'])) {
				$this->error['error_product_image_height'] = $this->language->get('error_product_image_height');
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}
		
		return !$this->error;
	}

	function install() {
		$this->load->model('setting/setting');
		$this->load->model('setting/module');

		$data	= array(
			'so_onepagecheckout_general'	=> array(
				'so_onepagecheckout_enabled'	=> 1,
				'so_onepagecheckout_name'		=> 'So Onepage Checkout',
				'so_onepagecheckout_layout'		=> 1,
				'so_onepagecheckout_country_id'	=> 223,
				'so_onepagecheckout_zone_id'	=> 3655
			),	
			'so_onepagecheckout_layout_setting'	=> array(
				'so_onepagecheckout_register_checkout'	=> 1,
				'so_onepagecheckout_guest_checkout'	=> 1,
				'so_onepagecheckout_enable_login'	=> 1,
				'so_onepagecheckout_account_open'	=> 'register',
				'shopping_cart_status'	=> 1,
				'show_product_weight'	=> 1,
				'show_product_qnty_update'	=> 1,
				'show_product_removecart'	=> 1,
				'show_product_image_width'	=> 80,
				'show_product_image_height'	=> 80,
				'coupon_login_status'	=> 1,
				'coupon_register_status'	=> 1,
				'coupon_guest_status'	=> 1,
				'reward_login_status'	=> 1,
				'reward_register_status'	=> 1,
				'reward_guest_status'	=> 1,
				'voucher_login_status'	=> 1,
				'voucher_register_status'	=> 1,
				'voucher_guest_status'	=> 1,
				'delivery_method_status'	=> 1,
				'so_onepagecheckout_layout_setting'	=> 'free',
				'flat_status'	=> 1,
				'free_status'	=> 1,
				'free_status'	=> 1,
				'payment_method_status'	=> 1,
				'so_onepagecheckout_default_payment'	=> 'bank_transfer',
				'bank_transfer_status'	=> 1,
				'cod_status'	=> 1,
				'comment_status'	=> 1,
				'require_comment_status'	=> 1,
				'show_newsletter'	=> 1,
				'show_privacy'	=> 1,
				'show_term'	=> 1,
			)
		);

		$data_module = array('name'=>'So Onepage Checkout', 'status'=>1);
		$this->model_setting_setting->editSetting('so_onepagecheckout', $data);
		$this->model_setting_module->addModule('so_onepagecheckout', $data_module);
	}

	function uninstall() {
		$this->load->model('setting/setting');
		$this->load->model('setting/module');
		$this->model_setting_setting->deleteSetting('so_onepagecheckout');
		$this->model_setting_module->deleteModulesByCode('so_onepagecheckout');
	}
}
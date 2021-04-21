<?php
class ControllerExtensionModuleSosocialLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/so_sociallogin');
		$data['objlang'] = $this->language;

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');
		$this->load->model('setting/setting');

		$module_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);
			
			$params = $this->request->post['so_sociallogin'];
			$this->model_setting_setting->editSetting('so_sociallogin', $params);

			$params_module = array_merge($params, array('name'=>$params['so_sociallogin_name'], 'status'=>$params['so_sociallogin_enable']));
			
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('so_sociallogin', $params_module);
				$module_id = $this->db->getLastId();
			}
			else {
				$module_id = $this->request->get['module_id'];
				$this->model_setting_module->editModule($this->request->get['module_id'], $params_module);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/so_sociallogin', 'user_token=' . $this->session->data['user_token'] . '&module_id='.$module_id, 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_sociallogin', 'user_token=' . $this->session->data['user_token'], 'SSL'));
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

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_title_so'] = $this->language->get('heading_title_so');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['tab_setting'] = $this->language->get('tab_setting');
		$data['tab_facbook'] = $this->language->get('tab_facbook');
		$data['tab_twitter'] = $this->language->get('tab_twitter');
		$data['tab_google'] = $this->language->get('tab_google');
		$data['tab_linkedin'] = $this->language->get('tab_linkedin');
		$data['tab_introductions'] = $this->language->get('tab_introductions');
		
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_apikey'] = $this->language->get('entry_apikey');
		$data['entry_apisecret'] = $this->language->get('entry_apisecret');
		$data['entry_twapikey'] = $this->language->get('entry_twapikey');
		$data['entry_twapisecret'] = $this->language->get('entry_twapisecret');
		$data['entry_googleapikey'] = $this->language->get('entry_goapikey');
		$data['entry_googleapisecret'] = $this->language->get('entry_goapisecret');
		$data['entry_liapikey'] = $this->language->get('entry_liapikey');
		$data['entry_liapisecret'] = $this->language->get('entry_liapisecret');
		$data['entry_iconsize'] = $this->language->get('entry_iconsize');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_buttonsocial'] = $this->language->get('entry_buttonsocial');
		$data['text_fblink'] = $this->language->get('text_fblink');
		$data['text_twitlink'] = $this->language->get('text_twitlink');
		$data['text_googlelink'] = $this->language->get('text_googlelink');
		$data['text_linkdinlink'] = $this->language->get('text_linkdinlink');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['fbapikey'])) {
			$data['error_fbapikey'] = $this->error['fbapikey'];
		} else {
			$data['error_fbapikey'] = '';
		}
		
		if (isset($this->error['fbsecretapi'])) {
			$data['error_fbsecretapi'] = $this->error['fbsecretapi'];
		} else {
			$data['error_fbsecretapi'] = '';
		}
		
		if (isset($this->error['twitapikey'])) {
			$data['error_twitapikey'] = $this->error['twitapikey'];
		} else {
			$data['error_twitapikey'] = '';
		}
		
		
		if (isset($this->error['twitsecretapi'])) {
			$data['error_twitsecret'] = $this->error['twitsecretapi'];
		} else {
			$data['error_twitsecret'] = '';
		}
		
	
		if (isset($this->error['googleapikey'])) {
			$data['error_googleapikey'] = $this->error['googleapikey'];
		} else {
			$data['error_googleapikey'] = '';
		}
		
		if (isset($this->error['googlesecretapi'])) {
			$data['error_googlesecret'] = $this->error['googlesecretapi'];
		} else {
			$data['error_googlesecret'] = '';
		}
		
		if (isset($this->error['linkdinapikey'])) {
			$data['error_linkdinapikey'] = $this->error['linkdinapikey'];
		} else {
			$data['error_linkdinapikey'] = '';
		}
		
		if (isset($this->error['linkdinsecretapi'])) {
			$data['error_linkdinsecret'] = $this->error['linkdinsecretapi'];
		} else {
			$data['error_linkdinsecret'] = '';
		}

		if (isset($this->error['error_width'])) {
			$data['error_width'] = $this->error['error_width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['error_height'])) {
			$data['error_height'] = $this->error['error_height'];
		} else {
			$data['error_height'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
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
				'href' => $this->url->link('extension/module/so_sociallogin', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_sociallogin', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_sociallogin', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_sociallogin', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			// $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info = $this->model_setting_setting->getSetting('so_sociallogin');
		}
		else
			$module_info = $this->model_setting_setting->getSetting('so_sociallogin');
		
		$params = isset($this->request->post['so_sociallogin']) ? $this->request->post['so_sociallogin'] : array();

		if (isset($params['so_sociallogin_name'])) {
			$data['name'] = $params['so_sociallogin_name'];
		} elseif (!empty($module_info)) {
			$data['name'] = isset($module_info['so_sociallogin_name']) ? $module_info['so_sociallogin_name'] : '';
		} else {
			$data['name'] = '';
		}

		if (isset($params['so_sociallogin_button'])) {
			$data['button_social'] = $params['so_sociallogin_button'];
		} elseif (!empty($module_info)) {
			$data['button_social'] = isset($module_info['so_sociallogin_button']) ? $module_info['so_sociallogin_button'] : '';
		} else {
			$data['button_social'] = '';
		}

		if (isset($params['so_sociallogin_popuplogin'])) {
			$data['popuplogin'] = $params['so_sociallogin_popuplogin'];
		} elseif (!empty($module_info)) {
			$data['popuplogin'] = isset($module_info['so_sociallogin_popuplogin']) ? $module_info['so_sociallogin_popuplogin'] : '';
		} else {
			$data['popuplogin'] = '';
		}

		if (isset($params['so_sociallogin_fbtitle'])) {
			$data['fbtitle'] = $params['so_sociallogin_fbtitle'];
		} elseif (!empty($module_info)) {
			$data['fbtitle'] = isset($module_info['so_sociallogin_fbtitle']) ? $module_info['so_sociallogin_fbtitle'] : '';
		} else {
			$data['fbtitle'] = '';
		}
			
		if (isset($params['so_sociallogin_twittertitle'])) {
			$data['twittertitle'] = $params['so_sociallogin_twittertitle'];
		} elseif (!empty($module_info)) {
			$data['twittertitle'] = isset($module_info['so_sociallogin_twittertitle']) ? $module_info['so_sociallogin_twittertitle'] : '';
		} else {
			$data['twittertitle'] = '';
		}
				
		if (isset($params['so_sociallogin_googletitle'])) {
			$data['googletitle'] = $params['so_sociallogin_googletitle'];
		} elseif (!empty($module_info)) {
			$data['googletitle'] = isset($module_info['so_sociallogin_googletitle']) ? $module_info['so_sociallogin_googletitle'] : '';
		} else {
			$data['googletitle'] = '';
		}
					
		if (isset($params['so_sociallogin_linkedintitle'])) {
			$data['linkedintitle'] = $params['so_sociallogin_linkedintitle'];
		} elseif (!empty($module_info)) {
			$data['linkedintitle'] = isset($module_info['so_sociallogin_linkedintitle']) ? $module_info['so_sociallogin_linkedintitle'] : '';
		} else {
			$data['linkedintitle'] = '';
		}
						
		
		if (isset($params['so_sociallogin_width'])) {
			$data['width'] = $params['so_sociallogin_width'];
		} elseif (!empty($module_info)) {
			$data['width'] = isset($module_info['so_sociallogin_width']) ? $module_info['so_sociallogin_width'] : '';
		} else {
			$data['width'] = '100';
		}
		
		if (isset($params['so_sociallogin_height'])) {
			$data['height'] = $params['so_sociallogin_height'];
		} elseif (!empty($module_info)) {
			$data['height'] = isset($module_info['so_sociallogin_height']) ? $module_info['so_sociallogin_height'] : '';
		} else {
			$data['height'] = '100';
		}
			
		if (isset($params['so_sociallogin_enable'])) {
			$data['status'] = $params['so_sociallogin_enable'];
		} elseif (!empty($module_info)) {
			$data['status'] = isset($module_info['so_sociallogin_enable']) ? $module_info['so_sociallogin_enable'] : '';
		} else {
			$data['status'] = 0;
		}
					
		if (isset($params['so_sociallogin_fbstatus'])) {
			$data['fbstatus'] = $params['so_sociallogin_fbstatus'];
		} elseif (!empty($module_info)) {
			$data['fbstatus'] = isset($module_info['so_sociallogin_fbstatus']) ? $module_info['so_sociallogin_fbstatus'] : '';
		} else {
			$data['fbstatus'] = '';
		}
		
		if (isset($params['so_sociallogin_twitstatus'])) {
			$data['twitstatus'] = $params['so_sociallogin_twitstatus'];
		} elseif (!empty($module_info)) {
			$data['twitstatus'] = isset($module_info['so_sociallogin_twitstatus']) ? $module_info['so_sociallogin_twitstatus'] : '';
		} else {
			$data['twitstatus'] = '';
		}
		
		if (isset($params['so_sociallogin_googlestatus'])) {
			$data['googlestatus'] = $params['so_sociallogin_googlestatus'];
		} elseif (!empty($module_info)) {
			$data['googlestatus'] = isset($module_info['so_sociallogin_googlestatus']) ? $module_info['so_sociallogin_googlestatus'] : '';
		} else {
			$data['googlestatus'] = '';
		}
		
		if (isset($params['so_sociallogin_linkstatus'])) {
			$data['linkstatus'] = $params['so_sociallogin_linkstatus'];
		} elseif (!empty($module_info)) {
			$data['linkstatus'] = isset($module_info['so_sociallogin_linkstatus']) ? $module_info['so_sociallogin_linkstatus'] : '';
		} else {
			$data['linkstatus'] = '';
		}
		
		if (isset($params['so_sociallogin_fbimage'])) {
			$data['fbimage'] = $params['so_sociallogin_fbimage'];
		} elseif (!empty($module_info)) {
			$data['fbimage'] = isset($module_info['so_sociallogin_fbimage']) ? $module_info['so_sociallogin_fbimage'] : '';
		} else {
			$data['fbimage'] = '';
		}
		
			
		if (isset($params['so_sociallogin_twitimage'])) {
			$data['twitimage'] = $params['so_sociallogin_twitimage'];
		} elseif (!empty($module_info)) {
			$data['twitimage'] = isset($module_info['so_sociallogin_twitimage']) ? $module_info['so_sociallogin_twitimage'] : '';
		} else {
			$data['twitimage'] = '';
		}
		
		
		if (isset($params['so_sociallogin_googleimage'])) {
			$data['googleimage'] = $params['so_sociallogin_googleimage'];
		} elseif (!empty($module_info)) {
			$data['googleimage'] = isset($module_info['so_sociallogin_googleimage']) ? $module_info['so_sociallogin_googleimage'] : '';
		} else {
			$data['googleimage'] = '';
		}
	
		if (isset($params['so_sociallogin_linkdinimage'])) {
			$data['linkdinimage'] = $params['so_sociallogin_linkdinimage'];
		} elseif (!empty($module_info)) {
			$data['linkdinimage'] = isset($module_info['so_sociallogin_linkdinimage']) ? $module_info['so_sociallogin_linkdinimage'] : '';
		} else {
			$data['linkdinimage'] = '';
		}
		
		if (isset($params['so_sociallogin_fbapikey'])) {
			$data['fbapikey'] = $params['so_sociallogin_fbapikey'];
		} elseif (!empty($module_info)) {
			$data['fbapikey'] = isset($module_info['so_sociallogin_fbapikey']) ? $module_info['so_sociallogin_fbapikey'] : '';
		} else {
			$data['fbapikey'] = '';
		}
		
		if (isset($params['so_sociallogin_fbsecretapi'])) {
			$data['fbsecretapi'] = $params['so_sociallogin_fbsecretapi'];
		} elseif (!empty($module_info)) {
			$data['fbsecretapi'] = isset($module_info['so_sociallogin_fbsecretapi']) ? $module_info['so_sociallogin_fbsecretapi'] : '';
		} else {
			$data['fbsecretapi'] = '';
		}
		
		if (isset($params['so_sociallogin_twitapikey'])) {
			$data['twitapikey'] = $params['so_sociallogin_twitapikey'];
		} elseif (!empty($module_info)) {
			$data['twitapikey'] = isset($module_info['so_sociallogin_twitapikey']) ? $module_info['so_sociallogin_twitapikey'] : '';
		} else {
			$data['twitapikey'] = '';
		}
		
		if (isset($params['so_sociallogin_twitsecretapi'])) {
			$data['twitsecretapi'] = $params['so_sociallogin_twitsecretapi'];
		} elseif (!empty($module_info)) {
			$data['twitsecretapi'] = isset($module_info['so_sociallogin_twitsecretapi']) ? $module_info['so_sociallogin_twitsecretapi'] : '';
		} else {
			$data['twitsecretapi'] = '';
		}
		
		if (isset($params['so_sociallogin_googleapikey'])) {
			$data['googleapikey'] = $params['so_sociallogin_googleapikey'];
		} elseif (!empty($module_info)) {
			$data['googleapikey'] = isset($module_info['so_sociallogin_googleapikey']) ? $module_info['so_sociallogin_googleapikey'] : '';
		} else {
			$data['googleapikey'] = '';
		}
		
		if (isset($params['so_sociallogin_googlesecretapi'])) {
			$data['googlesecretapi'] = $params['so_sociallogin_googlesecretapi'];
		} elseif (!empty($module_info)) {
			$data['googlesecretapi'] = isset($module_info['so_sociallogin_googlesecretapi']) ? $module_info['so_sociallogin_googlesecretapi'] : '';
		} else {
			$data['googlesecretapi'] = '';
		}
		if (isset($params['so_sociallogin_linkdinapikey'])) {
			$data['linkdinapikey'] = $params['so_sociallogin_linkdinapikey'];
		} elseif (!empty($module_info)) {
			$data['linkdinapikey'] = isset($module_info['so_sociallogin_linkdinapikey']) ? $module_info['so_sociallogin_linkdinapikey'] : '';
		} else {
			$data['linkdinapikey'] = '';
		}
		
		if (isset($params['so_sociallogin_linkdinsecretapi'])) {
			$data['linkdinsecretapi'] = $params['so_sociallogin_linkdinsecretapi'];
		} elseif (!empty($module_info)) {
			$data['linkdinsecretapi'] = isset($module_info['so_sociallogin_linkdinsecretapi']) ? $module_info['so_sociallogin_linkdinsecretapi'] : '';
		} else {
			$data['linkdinsecretapi'] = '';
		}
		
		
		$this->load->model('tool/image');

		if (isset($params['so_sociallogin_fbimage']) && is_file(DIR_IMAGE . $params['so_sociallogin_fbimage'])) {
			$data['fbthumb'] = $this->model_tool_image->resize($params['so_sociallogin_fbimage'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['so_sociallogin_fbimage'])) {
			$data['fbthumb'] = $this->model_tool_image->resize($module_info['so_sociallogin_fbimage'], 100, 100);
		} else {
			$data['fbthumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($params['so_sociallogin_twitimage']) && is_file(DIR_IMAGE . $params['so_sociallogin_twitimage'])) {
			$data['twiterthumb'] = $this->model_tool_image->resize($params['so_sociallogin_twitimage'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['so_sociallogin_twitimage'])) {
			$data['twiterthumb'] = $this->model_tool_image->resize($module_info['so_sociallogin_twitimage'], 100, 100);
		} else {
			$data['twiterthumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($params['so_sociallogin_googleimage']) && is_file(DIR_IMAGE . $params['so_sociallogin_googleimage'])) {
			$data['googlethumb'] = $this->model_tool_image->resize($params['so_sociallogin_googleimage'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['so_sociallogin_googleimage'])) {
			$data['googlethumb'] = $this->model_tool_image->resize($module_info['so_sociallogin_googleimage'], 100, 100);
		} else {
			$data['googlethumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}


		if (isset($params['so_sociallogin_linkdinimage']) && is_file(DIR_IMAGE . $params['so_sociallogin_linkdinimage'])) {
			$data['linkdinthumb'] = $this->model_tool_image->resize($params['so_sociallogin_linkdinimage'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['so_sociallogin_linkdinimage'])) {
			$data['linkdinthumb'] = $this->model_tool_image->resize($module_info['so_sociallogin_linkdinimage'], 100, 100);
		} else {
			$data['linkdinthumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/so_sociallogin', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_sociallogin')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$params	= $this->request->post['so_sociallogin'];

		if ((utf8_strlen($params['so_sociallogin_name']) < 3) || (utf8_strlen($params['so_sociallogin_name']) > 64)) {
			$this->error['so_sociallogin_name'] = $this->language->get('error_name');
		}
		
		if(!empty($params['so_sociallogin_fbstatus']) && $params['so_sociallogin_fbstatus']==1) {
			
			if(empty($params['so_sociallogin_fbapikey'])) {
				$this->error['fbapikey'] = $this->language->get('error_fbapikey');
			}
				
			if(empty($params['so_sociallogin_fbsecretapi'])) {
				$this->error['fbsecretapi'] = $this->language->get('error_fbsecretapi');
			}
		
		}
		if(!empty($params['so_sociallogin_twitstatus']) && $params['so_sociallogin_twitstatus']==1) {
			if(empty($params['so_sociallogin_twitapikey'])) {
				$this->error['twitapikey'] = $this->language->get('error_twitapikey');
			}
			
			if(empty($params['so_sociallogin_twitsecretapi'])) {
				$this->error['twitsecretapi'] = $this->language->get('error_twitsecret');
			}
		}
		if(!empty($params['so_sociallogin_googlestatus']) && $params['so_sociallogin_googlestatus']==1) {
			if(empty($params['so_sociallogin_googleapikey'])) {
				$this->error['googleapikey'] = $this->language->get('error_googleapikey');
			}
			if(empty($params['so_sociallogin_googlesecretapi'])) {
				$this->error['googlesecretapi'] = $this->language->get('error_googlesecret');
			}
		}
		
		if(!empty($params['so_sociallogin_linkstatus']) && $params['so_sociallogin_linkstatus']==1) {
			if(empty($params['so_sociallogin_linkdinapikey'])) {
				$this->error['linkdinapikey'] = $this->language->get('error_linkdinapikey');
			}
			
			if(empty($params['so_sociallogin_linkdinsecretapi'])) {
				$this->error['linkdinsecretapi'] = $this->language->get('error_linkdinsecret');
			}
		}

		if (!empty($params['so_sociallogin_width'])) {
			if (!is_numeric($params['so_sociallogin_width'])) {
				$this->error['error_width'] = $this->language->get('error_width');
			}
		}

		if (!empty($params['so_sociallogin_height'])) {
			if (!is_numeric($params['so_sociallogin_height'])) {
				$this->error['error_height'] = $this->language->get('error_height');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
	

		return !$this->error;
	}

	function install() {
		$this->load->model('setting/setting');
		$this->load->model('setting/module');

		$data	= array(
			'so_sociallogin_name'				=> 'So Social Login',
			'so_sociallogin_width'				=> 130,
			'so_sociallogin_height'				=> 35,
			'so_sociallogin_button'				=> 'icon',
			'so_sociallogin_enable'				=> 1,
			'so_sociallogin_popuplogin'			=> 1,
			'so_sociallogin_fbtitle'			=> 'Facebook Login',
			'so_sociallogin_fbimage'			=> 'catalog/sociallogin/fb.png',
			'so_sociallogin_fbapikey'			=> '442675926063537',
			'so_sociallogin_fbsecretapi'		=> '88d0f814891d4d1a9b173647291a911e',
			'so_sociallogin_fbstatus'			=> 1,
			'so_sociallogin_twittertitle'		=> 'Twitter Login',
			'so_sociallogin_twitimage'			=> 'catalog/sociallogin/twitter.png',
			'so_sociallogin_twitapikey'			=> 'EEJ3pjetfaHXrOw54ZyjATQGw',
			'so_sociallogin_twitsecretapi'		=> 'i7kGpUlhPsEmb4AkmaSQ2kFqgBM2U1nYs7ijHGk2f65J0672mP',
			'so_sociallogin_twitstatus'			=> 1,
			'so_sociallogin_googletitle'		=> 'Google Login',
			'so_sociallogin_googleimage'		=> 'catalog/sociallogin/google.png',
			'so_sociallogin_googleapikey'		=> '21690390667-tco9t3ca2o89d3sshkb2fmppoioq5mfq.apps.googleusercontent.com',
			'so_sociallogin_googlesecretapi'	=> 'COYNPrxaLq42QdIM2XBPASna',
			'so_sociallogin_googlestatus'		=> 1,
			'so_sociallogin_linkedintitle'		=> 'Linkedin Login',
			'so_sociallogin_linkdinimage'		=> 'catalog/sociallogin/linkedin.png',
			'so_sociallogin_linkdinapikey'		=> '78b7xin6x0kjj3',
			'so_sociallogin_linkdinsecretapi'	=> 'qvTyRdKakj6WFmWs',
			'so_sociallogin_linkstatus'			=> 1
		);
		$data_module = array_merge($data, array('name'=>$data['so_sociallogin_name'], 'status'=>$data['so_sociallogin_enable']));
		$this->model_setting_setting->editSetting('so_sociallogin', $data);
		$this->model_setting_module->addModule('so_sociallogin', $data_module);
	}

	function uninstall() {
		$this->load->model('setting/setting');
		$this->load->model('setting/module');
		$this->model_setting_setting->deleteSetting('so_sociallogin');
		$this->model_setting_module->deleteModulesByCode('so_sociallogin');
	}
}
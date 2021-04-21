<?php
class ControllerExtensionModuleSoInstagramShop extends Controller {
	public $error = array();
	public $data = array();

	public function index() {
		$this->load->language('extension/module/so_instagram_shop');
		
		$data['breadcrumbs'] = $this->_breadcrumbs();

		$this->load->model('setting/module');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$moduleid_new = $this->getModuleId();
		$module_id = '';	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
				$module_id = $moduleid_new[0]['Auto_increment'];
				$this->model_setting_module->addModule('so_instagram_shop', $this->request->post);

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
				$this->response->redirect($this->url->link('extension/module/so_instagram_shop', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/so_instagram_shop', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/so_instagram_shop', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/so_instagram_shop', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$default = array(
			'last_moduleid' 		=>$moduleid_new[0]['Auto_increment'],
			'name' 					=> '',
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'embed_widget'			=> '<script src=\'https://snapppt.com/widgets/widget_loader/bb0c1210-13e2-43f8-86ea-ff0ff4fdbe95/grid.js\' defer class=\'snapppt-widget\'></script>',
			'class_suffix'			=> '',
			'post_text'				=> '',
			'pre_text'				=> ''			
		);
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') || $this->request->server['REQUEST_METHOD'] == 'POST' && !$this->validate() && isset($this->request->get['module_id'])) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info['last_moduleid'] = $this->request->get['module_id'];
			
			$data['action'] = $this->url->link('extension/module/so_instagram_shop', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			$data['subheading'] = $this->language->get('text_edit');
		} else {
			$module_info = $default;
			if($this->request->post != null)
			{
				$module_info = array_merge($module_info, $this->request->post);
			}
			$data['action'] = $this->url->link('extension/module/so_instagram_shop', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['subheading'] = $this->language->get('text_add');
		}

		$data['module'] = $module_info;
		$data['error']	= $this->error;

		$data['user_token'] = $this->session->data['user_token'];
		
		// Save and Stay --------------------------------------------------------------
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], 'SSL'));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/so_instagram_shop', $data));
	}

	public function getModuleId() {
		$sql = "SHOW TABLE STATUS LIKE '" . DB_PREFIX . "module'";
		$query = $this->db->query($sql);
		return $query->rows;
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
				'href' => $this->url->link('extension/module/so_instagram_shop', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_instagram_shop', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		return $this->data['breadcrumbs'];
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/so_instagram_shop')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}
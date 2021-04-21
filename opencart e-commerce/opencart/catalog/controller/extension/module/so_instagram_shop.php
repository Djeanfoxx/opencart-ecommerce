<?php
class ControllerExtensionModuleSoInstagramShop extends Controller {
	public function index($setting) {
		$default = array(
			'name' 					=> '',
			'status'				=> '1',
			'disp_title_module'		=> '1',
			'embed_widget'			=> '<script src=\'https://snapppt.com/widgets/widget_loader/bb0c1210-13e2-43f8-86ea-ff0ff4fdbe95/grid.js\' defer class=\'snapppt-widget\'></script>',
			'class_suffix'			=> '',
			'post_text'				=> '',
			'pre_text'				=> ''
		);
		$data =  array_merge($default, $setting);
		$data['pre_text']			= html_entity_decode($setting['pre_text'], ENT_QUOTES, 'UTF-8');
		$data['post_text']			= html_entity_decode($setting['post_text'], ENT_QUOTES, 'UTF-8');
		$data['embed_widget']		= html_entity_decode($setting['embed_widget'], ENT_QUOTES, 'UTF-8');

		return $this->load->view('extension/module/so_instagram_shop', $data);
	}
}
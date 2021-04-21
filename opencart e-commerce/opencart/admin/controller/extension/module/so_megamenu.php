<?php
class ControllerExtensionModuleSoMegaMenu extends Controller {
    private $error = array();
    public function index() {
        //Load the language file for this module
        $this->load->language('extension/module/so_megamenu');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        $this->load->model('extension/module/so_megamenu');
        $this->load->model('tool/image');
        $this->load->model('setting/module');
        $this->document->addStyle('view/javascript/so_megamenu/so_megamenu.css');
        $this->document->addScript('view/javascript/so_megamenu/jquery.nestable.js');
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();
        $data['language_id'] = 0;
        foreach($data['languages'] as $value) {
            if($value['code'] == $this->config->get('config_language')) {
                $data['language_id'] = $value['language_id'];
            }
        }
		
		//Languages
		$this->load->language('extension/module/so_megamenu');
		$data['heading_title'] 		= $this->language->get('heading_title');
		$data['heading_title_so'] 	= $this->language->get('heading_title_so');
		$data['text_edit'] 			= $this->language->get('text_edit');
		$data['text_enabled'] 		= $this->language->get('text_enabled');
		$data['text_disabled'] 		= $this->language->get('text_disabled');
		$data['text_yes'] 			= $this->language->get('text_yes');
		$data['text_no'] 			= $this->language->get('text_no');
		$data['entry_name'] 		= $this->language->get('entry_name');
		$data['entry_description_name'] 		= $this->language->get('entry_description_name');	
		$data['text_creat_new_item'] 			= $this->language->get('text_creat_new_item');	
		$data['text_expand_all'] 				= $this->language->get('text_expand_all');
		$data['text_collapse_all'] 				= $this->language->get('text_collapse_all');
		$data['text_edit_item'] 				= $this->language->get('text_edit_item');
		$data['text_name'] = $this->language->get('text_name');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_label_item'] = $this->language->get('text_label_item');
		$data['text_icon_font'] = $this->language->get('text_icon_font');
		$data['text_class_menu'] = $this->language->get('text_class_menu');
		$data['text_type_link'] = $this->language->get('text_type_link');
		$data['text_categories'] = $this->language->get('text_categories');	
		$data['text_all_categories'] = $this->language->get('text_all_categories');	
		$data['text_link_in_new_window'] = $this->language->get('text_link_in_new_window');	
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_enabled'] = $this->language->get('text_enabled');		
		$data['text_status'] = $this->language->get('text_status');		
		$data['text_position'] = $this->language->get('text_position');
		$data['text_left'] = $this->language->get('text_left');
		$data['text_right'] = $this->language->get('text_right');
		$data['text_submenu_width'] = $this->language->get('text_submenu_width');
		$data['text_example'] = $this->language->get('text_example');
		$data['text_display_submenu_on'] = $this->language->get('text_display_submenu_on');	
		$data['text_content_item'] = $this->language->get('text_content_item');
		$data['text_content_config'] = $this->language->get('text_content_config');
		$data['text_parent_config'] = $this->language->get('text_parent_config');	
		$data['text_parent_item'] = $this->language->get('text_parent_item');	
		$data['text_content_width'] = $this->language->get('text_content_width');	
		$data['text_content_type'] = $this->language->get('text_content_type');	
		$data['text_name'] 						= $this->language->get('text_name');	
		$data['text_basic_configuration'] 		= $this->language->get('text_basic_configuration');	
		$data['text_design_configuration'] 		= $this->language->get('text_design_configuration');
		$data['text_orientation'] 				= $this->language->get('text_orientation');	
		$data['text_number_load_vertical'] 		= $this->language->get('text_number_load_vertical');	
		$data['text_navigation_text'] 			= $this->language->get('text_navigation_text');	
		$data['text_expand_menu_bar'] 			= $this->language->get('text_expand_menu_bar');
		$data['text_home_item'] 				= $this->language->get('text_home_item');
		$data['text_home_text'] 				= $this->language->get('text_home_text');	
		$data['text_jquery_animations'] 		= $this->language->get('text_jquery_animations');	
		$data['text_animation'] 				= $this->language->get('text_animation');	
		$data['text_animation_time'] 			= $this->language->get('text_animation_time');

		$data['entry_head_name'] 				= $this->language->get('entry_head_name');
		$data['entry_display_title_module'] 	= $this->language->get('entry_display_title_module');

		$data['text_use_cache'] 		= $this->language->get('entry_use_cache');
		$data['text_cache_time'] 		= $this->language->get('entry_cache_time');
		$data['button_clear_cache'] 	= $this->language->get('entry_button_clear_cache');
		$data['button_add_module'] 		= $this->language->get('text_button_add_module');
		
		$data['moduletabs'] = $this->model_setting_module->getModulesByCode('so_megamenu');
		$data['link_add'] = $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'] . '', 'SSL');
		$this->load->model('catalog/category');
		$results = $this->model_catalog_category->getCategories(0);
		foreach ($results as $result) {
			$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name']
			);
		}	
		
        // Usuwanie menu
        if(isset($_GET['delete'])) {
            if($this->validate()){
                if($this->model_extension_module_so_megamenu->deleteMenu(intval($_GET['delete']))) {
                    $this->session->data['success'] = 'This menu has been properly removed from the database.';
                } else {
                    $this->session->data['error_warning'] = $this->model_extension_module_so_megamenu->displayError();
                }
            } else {
                $this->session->data['error_warning'] = $this->language->get('error_permission');
            }
            $this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token'].'&action=create&module_id='.$this->request->get['module_id']);
        }
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if(isset($_POST['button-create'])) {
                if($this->validate()) {
                    $error = false;
                    $lang_id = $data['language_id'];
                    if($this->request->post['name'][$lang_id] == '') $error = true;
                    if($error == true) {
                        $this->session->data['error_warning'] = $this->language->get('text_warning');
                    } else {
						if($this->request->post['link'])
							$this->request->post['link'] = serialize ($this->request->post['link']);
						if(isset($this->request->post['content']['subcategory']['category']) && ($this->request->post['content']['subcategory']['category']))
							$this->request->post['content']['subcategory']['category'] = serialize ($this->request->post['content']['subcategory']['category']);	
                        $this->model_extension_module_so_megamenu->addMenu($this->request->post);
                        $this->session->data['success'] = $this->language->get('text_success');
						$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token'].'&action=create&module_id='.$this->request->get['module_id']);
                    }
                } else {
                    $this->session->data['error_warning'] = $this->language->get('error_permission');
                }
                $this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token']);
            }
        }		
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if(isset($_POST['button-back'])) {
				$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token'].'&module_id='.$this->request->get['module_id']);
			}	
            elseif(isset($_POST['button-close'])) {
				 $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}			
            elseif(isset($_POST['button-edit'])) {
                if($this->validate()) {
                    $error = false;
                    $lang_id = $data['language_id'];
                    if($this->request->post['name'][$lang_id] == '') $error = true;
                    if($error == true) {
                        $this->session->data['error_warning'] = $this->language->get('text_warning');
                    } else {
						if($this->request->post['link'])
							$this->request->post['link'] = serialize ($this->request->post['link']);
						if(isset($this->request->post['content']['subcategory']['category']) && ($this->request->post['content']['subcategory']['category']))
							$this->request->post['content']['subcategory']['category'] = serialize ($this->request->post['content']['subcategory']['category']);						
                        $this->model_extension_module_so_megamenu->saveMenu($this->request->post);
                        $this->session->data['success'] = $this->language->get('text_success');
						$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token'].'&edit='.$this->request->post['id'].'&module_id='.$this->request->get['module_id']);
                    }
                } else {
                    $this->session->data['error_warning'] = $this->language->get('error_permission');
                }
                $this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token'].'&action=create&module_id='.$this->request->get['module_id']);
            }
        }
        $data['nestable_list'] = $this->model_extension_module_so_megamenu->generate_nestable_list($data['language_id']);
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if(isset($_POST['button-save'])){
                $megamenu = array();
                if(isset($this->request->post['search_bar'])) {
                    $search_bar = 1;
                } else {
                    $search_bar = 0;
                }
                if(!isset($this->request->post['layout_id'])) 
					$this->request->post['layout_id'] = 100;
                if(!isset($this->request->post['position'])) 
					$this->request->post['position'] = 'menu';
                if(!isset($this->request->post['status'])) 
					$this->request->post['status'] = 0;
                if(!isset($this->request->post['sort_order'])) 
					$this->request->post['sort_order'] = 0;
                if(!isset($this->request->post['orientation'])) 
					$this->request->post['orientation'] = 0;
                if(!isset($this->request->post['navigation_text'])) 
					$this->request->post['navigation_text'] = 0;
                if(!isset($this->request->post['home_text'])) 
					$this->request->post['home_text'] = 0;
                if(!isset($this->request->post['full_width'])) 
					$this->request->post['full_width'] = 0;
                if(!isset($this->request->post['home_item'])) 
					$this->request->post['home_item'] = 0;
                if(!isset($this->request->post['animation'])) 
					$this->request->post['animation'] = 'slide';
                if(!isset($this->request->post['animation_time'])) 
					$this->request->post['animation_time'] = 500;
                if(!isset($this->request->post['name'])) 
					$this->request->post['name'] = 'so_megamenu';
                if(!isset($this->request->post['label_item'])) 
					$this->request->post['label_item'] = 'hot';
                if(!isset($this->request->post['icon_font'])) 
					$this->request->post['icon_font'] = 'fa fa-camera-retro';
                if(!isset($this->request->post['class_menu'])) 
					$this->request->post['class_menu'] = '';					
                if(!isset($this->request->post['show_itemver']))
					$this->request->post['show_itemver'] = 5;
                if(!isset($this->request->post['use_cache']))
					$this->request->post['use_cache'] = 1;
                if(!isset($this->request->post['cache_time']))
					$this->request->post['cache_time'] = 3600;
				if (!isset($this->request->post['head_name'])) {
					$this->request->post['head_name'] = array();
				}	
				if (!isset($this->request->post['disp_title_module'])) {
					$this->request->post['disp_title_module'] = array();
				}				
                $this->request->post['search_bar'] = $search_bar  ;
				
				$moduleid_new= $this->model_extension_module_so_megamenu->getModuleId(); // Get module id
				$module_id = '';
				if (!isset($this->request->get['module_id'])) {
					$this->request->post['moduleid'] = $moduleid_new[0]['Auto_increment'];
					$module_id = $moduleid_new[0]['Auto_increment'];
					$this->model_setting_module->addModule('so_megamenu', $this->request->post);	
				} else {
					$module_id = $this->request->get['module_id'];
					$this->request->post['moduleid'] = $this->request->get['module_id'];
					$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
				}	
				if (isset($this->request->post['import_module']) && $this->request->post['import_module']) {
					$import_module = $this->request->post['import_module'];
					$this->model_extension_module_so_megamenu->duplicateModule($module_id,$import_module);
				}					
                $this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('extension/module/so_megamenu', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
			}
        }


        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
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
				'href' => $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}	

		$this->load->model('setting/module');
		$data['modules'] = $this->model_setting_module->getModulesByCode('so_megamenu');
		if (isset($this->request->post['head_name'])) {
			$data['head_name'] = $this->request->post['head_name'];
		} elseif (!empty($module_info)) {
			$data['head_name'] = (isset($module_info['head_name'])) ? $module_info['head_name'] : array();
		} else {
			$data['head_name'] = array();
		}
		
		if (isset($this->request->get['module_id'])) {
			$data['moduleid'] = $this->request->get['module_id'];
		} elseif (!empty($module_info) && isset($module_info['moduleid'])) {
			$data['moduleid'] = $module_info['moduleid'];
		} else {
			$data['moduleid'] = '';
		}	
		
		if (isset($this->request->post['disp_title_module'])) {
			$data['disp_title_module'] = $this->request->post['disp_title_module'];
		} elseif (!empty($module_info) && isset($module_info['disp_title_module'])) {
			$data['disp_title_module'] = $module_info['disp_title_module'];
		} else {
			$data['disp_title_module'] = 1;
		}	
		
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }
        if (isset($this->request->post['show_itemver'])) {
            $data['show_itemver'] = $this->request->post['show_itemver'];
        } elseif (!empty($module_info)) {
            $data['show_itemver'] = $module_info['show_itemver'];
        } else {
            $data['show_itemver'] = '';
        }
        if (isset($this->request->post['use_cache'])) {
            $data['use_cache'] = $this->request->post['use_cache'];
        } elseif (!empty($module_info) && isset($module_info['use_cache'])) {
            $data['use_cache'] = $module_info['use_cache'];
        } else {
            $data['use_cache'] = 1;
        }
        if (isset($this->request->post['cache_time'])) {
            $data['cache_time'] = $this->request->post['cache_time'];
        } elseif (!empty($module_info) && isset($module_info['cache_time'])) {
            $data['cache_time'] = $module_info['cache_time'];
        } else {
            $data['cache_time'] = 3600;
        }
        if (isset($this->request->post['layout_id'])) {
            $data['layout_id'] = $this->request->post['layout_id'];
        } elseif (!empty($module_info)) {
            $data['layout_id'] = $module_info['layout_id'];
        } else {
            $data['layout_id'] = '';
        }
        if (isset($this->request->post['position'])) {
            $data['position'] = $this->request->post['position'];
        } elseif (!empty($module_info)) {
            $data['position'] = $module_info['position'];
        } else {
            $data['position'] = '';
        }
        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }
        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($module_info)) {
            $data['sort_order'] = $module_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }
        if (isset($this->request->post['orientation'])) {
            $data['orientation'] = $this->request->post['orientation'];
        } elseif (!empty($module_info)) {
            $data['orientation'] = $module_info['orientation'];
        } else {
            $data['orientation'] = '';
        }
        if (isset($this->request->post['navigation_text'])) {
            $data['navigation_text'] = $this->request->post['navigation_text'];
        } elseif (!empty($module_info)) {
            $data['navigation_text'] = $module_info['navigation_text'];
        } else {
            $data['navigation_text'] = '';
        }
        if (isset($this->request->post['home_text'])) {
            $data['home_text'] = $this->request->post['home_text'];
        } elseif (!empty($module_info)) {
            $data['home_text'] = $module_info['home_text'];
        } else {
            $data['home_text'] = '';
        }
        if (isset($this->request->post['full_width'])) {
            $data['full_width'] = $this->request->post['full_width'];
        } elseif (!empty($module_info)) {
            $data['full_width'] = $module_info['full_width'];
        } else {
            $data['full_width'] = '';
        }
			
		
        if (isset($this->request->post['home_item'])) {
            $data['home_item'] = $this->request->post['home_item'];
        } elseif (!empty($module_info)) {
            $data['home_item'] = $module_info['home_item'];
        } else {
            $data['home_item'] = '';
        }
		
        if (isset($this->request->post['animation'])) {
            $data['animation'] = $this->request->post['animation'];
        } elseif (!empty($module_info)) {
            $data['animation'] = $module_info['animation'];
        } else {
            $data['animation'] = '';
        }
		
        if (isset($this->request->post['animation_time'])) {
            $data['animation_time'] = $this->request->post['animation_time'];
        } elseif (!empty($module_info)) {
            $data['animation_time'] = $module_info['animation_time'];
        } else {
            $data['animation_time'] = '';
        }
		
        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['selectedid'] = 0;       
	   } else {
            $data['action'] = $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');			
			$data['selectedid'] = $this->request->get['module_id'];
		}

		$data['user_token'] = $this->session->data['user_token'];
		if (isset($_GET['edit']))
			$data['edit'] 	= $_GET['edit'];
		else
			$data['edit']	= '';		
		
        if (isset($_GET['jsonstring'])) {
            if($this->validate()){
                $jsonstring = $_GET['jsonstring'];
                $jsonDecoded = json_decode(html_entity_decode($jsonstring));
                function parseJsonArray($jsonArray, $parentID = 0) {
                    $return = array();
                    foreach ($jsonArray as $subArray) {
                        $returnSubSubArray = array();
                        if (isset($subArray->children)){
                            $returnSubSubArray = parseJsonArray($subArray->children, $subArray->id);
                        }
                        $return[] = array('id' => $subArray->id, 'parentID' => $parentID);
                        $return = array_merge($return, $returnSubSubArray);
                    }
                    return $return;
                }
                $readbleArray = parseJsonArray($jsonDecoded);
                foreach ($readbleArray as $key => $value) {
                    if (is_array($value)) {
                        $this->model_extension_module_so_megamenu->save_rang($value['parentID'], $value['id'], $key);
                    }
                }

                die("The list was updated ".date("y-m-d H:i:s")."!");
            } else {
                die($this->language->get('error_permission'));
            }
        }


        $data['action_type'] = 'basic';
        if(isset($_GET['action'])) {				
			$_['error_width']      = 'Width required!';
            if($_GET['action'] == 'create') {
                $data['action_type'] = 'create';
                $data['name'] = '';
                $data['description'] = '';
                $data['icon'] = '';
				$data['type_link'] = '0';
                $data['link'] = array(
					'url' 		=> '',
					'category' 	=> ''
				);
                $data['new_window'] = '';
                $data['label_item'] = 'hot';
                $data['icon_font'] = 'fa fa-home';
				$data['class_menu'] = '';
                $data['status'] = '';
                $data['position'] = '';
                $data['submenu_width'] = '100%';
                $data['display_submenu'] = '';
                $data['content_width'] = '4';
                $data['content_type'] = '0';
                $data['content'] = array(
                    'html' => array(
                        'text' => array()
                    ),
                    'product' => array(
                        'id' => '',
                        'name' => ''
                    ),
                    'manufacture' => array(
                        'id' => '',
                        'name' => ''
                    ),					
                    'categories' => array(
                        'categories' => array(),
                        'columns' => '',
                        'submenu' => '',
                        'submenu_columns' => '',
						'limit' => ''
                    ),
                    'productlist' => array(
                        'limit' => '',
                        'type' => '',
						'show_title'	=> '',
						'col'	=> '',
                    ),					
                    'subcategory' => array(
                        'category' => '',
                        'limit_level_1' => '4',
						'limit_level_2' => '4',
						'limit_level_3' => '4',
						'show_title' 	=> '1',
						'show_image' 	=> '1',
                        'submenu' => '',
                        'submenu_columns' => '',
						'columns' => '',
                    ),					
					'image' => ''
                );
                $data['list_categories'] = false;
            }
        }
        // Edycja menu
        if(isset($_GET['edit'])) {
            $data['action_type'] = 'edit';						
            $dane = $this->model_extension_module_so_megamenu->getMenu(intval($_GET['edit']));
			
			$this->load->model('tool/image');
			if (isset($dane['content']['image']['link']) && is_file(DIR_IMAGE . $dane['content']['image']['link'])) {
				$dane['content']['image']['image_link'] = $this->model_tool_image->resize($dane['content']['image']['link'], 100, 100);
			} elseif (!empty($dane) && is_file(DIR_IMAGE . $dane['icon'])) {
				$dane['content']['image']['image_link'] = $this->model_tool_image->resize($dane['content']['image']['link'], 100, 100);
			} else {
				$dane['content']['image']['image_link'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}	
		
			$dane['content']['subcategory']['category'] = (isset($dane['content']['subcategory']['category']) && $dane['content']['subcategory']['category']) ? @unserialize($dane['content']['subcategory']['category']) :  array();
       
			if($dane) {
                $data['name'] = $dane['name'];
                $data['description'] = $dane['description'];
                $data['icon'] 		= 	$dane['icon'];
				$data['type_link'] 	= 	$dane['type_link'];
                $data['link'] 		= (isset($dane['link']) && $dane['link']) ? @unserialize($dane['link']) :  array();
                $data['label_item'] = $dane['label_item'];
                $data['icon_font'] = $dane['icon_font'];
				$data['class_menu'] = $dane['class_menu'];
                $data['new_window'] = $dane['new_window'];
                $data['status'] = $dane['status'];
                $data['position'] = $dane['position'];
                $data['submenu_width'] = $dane['submenu_width'];
                $data['display_submenu'] = $dane['display_submenu'];
                $data['content_width'] = $dane['content_width'];
                $data['content_type'] = $dane['content_type'];
                $data['content'] = $dane['content'];
                $data['list_categories'] = $this->model_extension_module_so_megamenu->getCategories($dane['content']['categories']['categories']);
            } else {
                $this->session->data['error_warning'] = 'This menu does not exist!';
                $this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token']);
            }
        }
        elseif (isset($_GET['duplicate']))
        {
            $new_menu_id = $this->model_extension_module_so_megamenu->duplicateMenu($_GET['duplicate']);
            $this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token'].'&module_id='.$this->request->get['module_id'].'&edit='. $new_menu_id);
        }
        elseif (isset($_GET['changestatus']))
        {
            $dane = $this->model_extension_module_so_megamenu->getMenu(intval($_GET['changestatus']));
            if ($dane['status']==1)
                $status['status'] =0;
            else
                $status['status'] =1;
            $status['id'] = intval($_GET['changestatus']);
            $this->model_extension_module_so_megamenu->UpdatePosition($status);
            $this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/so_megamenu&user_token=' . $this->session->data['user_token'].'&action=create&module_id='.$this->request->get['module_id']);
        }
        $this->load->model('tool/image');
        if (isset($this->request->post['icon']) && is_file(DIR_IMAGE . $this->request->post['icon'])) {
            $data['icon'] = $this->request->post['icon'];
        } elseif (!empty($dane) && is_file(DIR_IMAGE . $dane['icon'])) {
            $data['icon'] = $dane['icon'];
        } else {
            $data['icon'] = 'no_image.png';
        }
		$data['src_icon'] = $this->model_tool_image->resize($data['icon'],100,100);
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['image_default'] = 'no_image.png';
		$data['src_image_default'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        // Layouts
        $this->load->model('design/layout');
        $data['layouts'] = $this->model_design_layout->getLayouts();
        //This creates an error message. The error['warning'] variable is set by the call to function validate() in this controller (below)
        if (isset($this->session->data['error_warning'])) {
            $data['error_warning'] = $this->session->data['error_warning'];
            unset($this->session->data['error_warning']);
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        $data['linkremove'] = $this->url->link('extension/module/so_megamenu&user_token=' . $this->session->data['user_token']);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
        //Choose which template file will be used to display this request.
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        // Remove cache
        $data['success_remove'] = $this->language->get('text_success_remove');
        $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        if($is_ajax && isset($_REQUEST['is_ajax_cache_lite']) && $_REQUEST['is_ajax_cache_lite']){
            self::remove_cache();
        }
        $this->response->setOutput($this->load->view('extension/module/so_megamenu', $data));
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
    public function uninstall() {
        $this->load->model('setting/setting');
        $this->load->model('extension/module/so_megamenu');
        $this->model_extension_module_so_megamenu->uninstall();
    }

    public function install() {
        $this->load->model('setting/setting');
        $this->load->model('setting/module');
        $this->load->model('extension/module/so_megamenu');
        $data = array(
            'layout_id'  => 100,
            'position'   => 'menu',
            'sort_order' => 0,
            'orientation' =>  0,
            'search_bar' => 0,
            'navigation_text' => '',
            'home_text'  => '',
            'full_width' => 1000,
            'home_item'  => 'icon',
            'animation'  => 'slide',
            'animation_time'  => 500,
            'show_itemver'  => 5,
            'status' => 1,
            'name' => 'So Megamenu',
            'use_cache'				=> '1',
            'cache_time'			=> '3600'
        );
        $this->model_setting_module->addModule('so_megamenu',$data);
		$new_module = $this->getModulesByCode('so_megamenu');
		if(is_array($new_module))		
			$this->model_extension_module_so_megamenu->install($new_module['module_id']);
    }
	public function getModulesByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

		return $query->row;
	}	
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/so_megamenu')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }		
		return !$this->error;
    }

}

?>
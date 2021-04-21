<?php

class ModelExtensionModuleSomegamenu extends Model {
    private $errors = array();
    public function generate_nestable_list($lang_id) {
		$module_id = (isset($this->request->get['module_id']) && $this->request->get['module_id']) ? $this->request->get['module_id'] : 0;
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE parent_id='0' AND module_id='".$module_id."' ORDER BY rang");
        if (!isset($this->request->get['module_id'])) {
            $action = $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'], 'SSL');
        } else {
            $action = $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
        }
        $output = '<div class="cf nestable-lists">';
        $output .= '<div class="dd" id="nestable">';
        $output .= '<ol class="dd-list">';
        foreach ($query->rows as $row) {
            $json = unserialize($row['name']);
            if(isset($json[$lang_id])) {
                $name = $this->skrut($json[$lang_id], 10);
            } else {
                $name = 'Set name';
            }
            if ($row['status']==0)
                $class ='fa fa-square';
            else
                $class ='fa fa-square-o';
            $output .= '<li class="dd-item" data-id="'.$row['id'].'">';
            $output .= '<a data-toggle="tooltip" title="Duplicate" href="'.$action.'&duplicate='.$row['id'].'"  class="fa fa-plus"></a>';
            $output .= '<a data-toggle="tooltip" title="Change Status" href="'.$action.'&changestatus='.$row['id'].'"  class="'.$class.'"></a>';
            $output .= '<a data-toggle="tooltip" title="Delete" href="'.$action.'&delete='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete?\')" class="fa fa-trash-o fa-fw"></a><a data-toggle="tooltip" title="edit" href="'.$action.'&edit='.$row['id'].'" class="fa fa-pencil fa-fw"></a>';
            $output .= '<div class="dd-handle">'.$name.' (ID: '.$row['id'].')</div>';
            $output .= $this->menu_showNested($row['id'], $lang_id);
            $output .= '</li>';
        }
        $output .= '</ol>';
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }

    public function menu_showNested($parentID, $lang_id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE parent_id='".$parentID."' ORDER BY rang");
        if (!isset($this->request->get['module_id'])) {
            $action = $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'], 'SSL');
        } else {
            $action = $this->url->link('extension/module/so_megamenu', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
        }
        $output = false;
        if (count($query->rows) > 0) {
            $output .= "<ol class='dd-list'>\n";
            foreach ($query->rows as $row) {
                $output .= "\n";
                $json = unserialize($row['name']);
                if(isset($json[$lang_id])) {
                    $name = $this->skrut($json[$lang_id], 10);
                } else {
                    $name = 'Set name';
                }
                if ($row['status']==0)
                    $class ='fa fa-square';
                else
                    $class ='fa fa-square-o';
                $output .= "<li class='dd-item' data-id='{$row['id']}'>\n";
                $output .= '<a data-toggle="tooltip" title="Duplicate" href="'.$action.'&duplicate='.$row['id'].'"  class="fa fa-plus"></a>';
                $output .= '<a data-toggle="tooltip" title="Change Status" href="'.$action.'&changestatus='.$row['id'].'" class="'.$class.'" ></a>';
                $output .= '<a  data-toggle="tooltip" title="Delete" href="'.$action.'&delete='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete?\')" class="fa fa-trash-o fa-fw"></a>';
                $output .= "<a data-toggle='tooltip' title='edit'  href='".$action."&edit=".$row['id']."' class='fa fa-pencil fa-fw'></a><div class='dd-handle'>{$name} (ID: {$row['id']})</div>\n";
                $output .= $this->menu_showNested($row['id'], $lang_id);
                $output .= "</li>\n";
            }
            $output .= "</ol>\n";
        }
        return $output;
    }

    public  function getSubMenu($parentID){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE parent_id='".$parentID."' ORDER BY rang");
        return $query->rows;
    }
    public function save_rang($parent_id, $id, $rang) {
        $this->db->query("UPDATE " . DB_PREFIX . "mega_menu SET parent_id = '" . $parent_id . "', rang = '" . $rang . "' WHERE id = '" . $id . "'");
    }



    public function addMenu($data) {
		
		$data['parent_id'] = (isset($data['parent_id']) && $data['parent_id']) ? $data['parent_id'] : 0;
		if(isset($data['module_id']) && $data['module_id'])
			$module_id = $data['module_id'];
		else
			$module_id = (isset($this->request->get['module_id']) && $this->request->get['module_id']) ? $this->request->get['module_id'] : 0;
		
        //$data['content']['categories']['categories'] = @json_decode(html_entity_decode($data['content']['categories']['categories']), true);
		
		if(!isset($data['label_item'])) $data['label_item'] = 'hot';
		if(!isset($data['icon_font'])) $data['icon_font'] = 'fa fa-camera-retro';
		if(!isset($data['class_menu'])) $data['class_menu'] = '';
		
        $this->db->query("INSERT INTO " . DB_PREFIX . "mega_menu SET name = '" . $this->db->escape(serialize($data['name'])) . "',label_item = '".$data['label_item']."',icon_font = '".$data['icon_font']."',class_menu = '".$data['class_menu']."',  description = '" . $this->db->escape(serialize($data['description'])) . "', icon = '" . $data['icon'] . "', parent_id = '". $data['parent_id'] ."', type_link = '" . $data['type_link'] . "', module_id = '" . $module_id . "', link = '" . $data['link'] . "', new_window = '" . $data['new_window'] . "', status = '" . $data['status'] . "', position = '" . $data['position'] . "', submenu_width = '" . $data['submenu_width'] . "', submenu_type = '" . $data['display_submenu'] . "', rang='1000', content_width='" . $data['content_width'] . "', content_type='" . $data['content_type'] . "', content='" . $this->db->escape(serialize($data['content'])) . "'");
		
        return $this->db->getLastId();
    }

    public function saveMenu($data) {		
		
		$module_id = (isset($this->request->get['module_id']) && $this->request->get['module_id']) ? $this->request->get['module_id'] : 0;
        $data['content']['categories']['categories'] = json_decode(html_entity_decode($data['content']['categories']['categories']), true);
        $this->db->query("UPDATE " . DB_PREFIX . "mega_menu SET name = '" . $this->db->escape(serialize($data['name'])) . "', label_item = '".$data['label_item']."',icon_font = '".$data['icon_font']."',class_menu = '".$data['class_menu']."', description = '" . $this->db->escape(serialize($data['description'])) . "', icon = '" . $data['icon'] ."',type_link = '" . $data['type_link'] . "', module_id = '" . $module_id . "', link = '" . $data['link'] ."', new_window = '" . $data['new_window'] ."', status = '" . $data['status'] ."', position = '" . $data['position'] ."', submenu_width = '" . $data['submenu_width'] ."', submenu_type = '" . $data['display_submenu'] ."', content_width = '" . $data['content_width'] ."', content_type = '" . $data['content_type'] ."', content = '" . $this->db->escape(serialize($data['content'])) . "' WHERE id = '" . $data['id'] . "'");
    }
    public function UpdatePosition($data) {
        $this->db->query("UPDATE " . DB_PREFIX . "mega_menu SET  status = '" . $data['status'] ."' WHERE id = '" . $data['id'] . "'");
    }

    public function deleteMenu($id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE id='".$id."'");
        if(count($query->rows) > 0) {
            $query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE parent_id='".$id."'");
            if(count($query->rows) > 0) {
                $this->errors[] = "Menu wasn't removed because contains submenu.";
            } else {
                $this->db->query("DELETE FROM " . DB_PREFIX . "mega_menu WHERE id = '" . $id . "'");
                return true;
            }
        } else {
            $this->errors[] = 'This menu does not exist!';
        }
        return false;
    }

    public function getMenu($id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE id='".$id."'");
        if(count($query->rows) > 0) {
            $data = array();
            foreach ($query->rows as $result) {
                $data = array(
                    'name' => unserialize($result['name']),
                    'description' => unserialize($result['description']),
                    'icon' => $result['icon'],
					'type_link' => $result['type_link'],
                    'link' => $result['link'],
                    'label_item' => $result['label_item'],
                    'icon_font' => $result['icon_font'],
					'class_menu' => $result['class_menu'],
                    'new_window' => $result['new_window'],
                    'status' => $result['status'],
                    'position' => $result['position'],
                    'submenu_width' => $result['submenu_width'],
                    'display_submenu' => $result['submenu_type'],
                    'content_width' => $result['content_width'],
                    'content_type' => $result['content_type'],
                    'content' => @unserialize($result['content'])
                );
            }
            return $data;
        }
        return false;
    }



    public function getCategories($array = array()) {
        $output = '';
        if(is_array($array) && !empty($array) && count($array)>0) {
            foreach($array as $row) {
                $output .= '<li class="dd-item" data-id="'.$row['id'].'" data-name="'.$row['name'].'">';
                $output .= '<a class="fa fa-times"></a>';
                $output .= '<div class="dd-handle">'.$row['name'].'</div>';
                if(isset($row['children'])) {
                    if(!empty($row['children'])) {
                        $output .= $this->getCategoriesChildren($row['children']);
                    }
                }
                $output .= '</li>';
            }
        }
        return $output;
    }

    public function getCategoriesChildren($array = array()) {
        $output = '';
        $output .= '<ol class="dd-list">';
        foreach($array as $row) {
            $output .= '<li class="dd-item" data-id="'.$row['id'].'" data-name="'.$row['name'].'">';
            $output .= '<a class="fa fa-times"></a>';
            $output .= '<div class="dd-handle">'.$row['name'].'</div>';
            if(isset($row['children'])) {
                if(!empty($row['children'])) {
                    $output .= $this->getCategoriesChildren($row['children']);
                }
            }
            $output .= '</li>';
        }
        $output .= '</ol>';
        return $output;
    }

    public function displayError() {
        $errors = '';
        foreach ($this->errors as $error) {
            $errors .= '<div>'.$error.'</div>';
        }
        return $errors;
    }

    public function install($module_id) {
        if($this->is_table_exist(DB_PREFIX . "mega_menu")) {
            $query = $this->db->query("
				CREATE TABLE IF NOT EXISTS `".DB_PREFIX."mega_menu` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`parent_id` int(11) NOT NULL,
					`rang` int(11) NOT NULL,
					`icon` varchar(255) NOT NULL DEFAULT '',
					`name` text,
					`type_link` int(11),
					`module_id` int(11),
					`link` text,
					`description` text,
					`new_window` int(11) NOT NULL DEFAULT '0',
					`status` int(11) NOT NULL DEFAULT '0',
					`position` int(11) NOT NULL DEFAULT '0',
					`submenu_width` text,
					`submenu_type` int(11) NOT NULL DEFAULT '0',
					`content_width` int(11) NOT NULL DEFAULT '12',
					`content_type` int(11) NOT NULL DEFAULT '0',
					`content` text,
					`label_item` varchar(255) NOT NULL DEFAULT '',
					`icon_font` varchar(255) NOT NULL DEFAULT '',
					`class_menu` varchar(255),
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
			");
            $query = $this->db->query("
            INSERT INTO `".DB_PREFIX."mega_menu` (`id`, `parent_id`, `rang`, `icon`, `name`, `type_link`,`module_id`, `link`, `description`, `new_window`, `status`, `position`, `submenu_width`, `submenu_type`, `content_width`, `content_type`, `content`, `label_item`, `icon_font`) VALUES
(90, 89, 1, '', 'a:4:{i:1;s:16:\"Responsive theme\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:183:\"&lt;b&gt;Notice&lt;/b&gt;: Undefined index: url in &lt;b&gt;F:xampphtdocsytc_extensionsopencartadminview	emplatemoduleso_megamenu.tpl&lt;/b&gt; on line &lt;b&gt;123&lt;/b&gt;\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:690:\"&lt;h3 style=&quot;margin: 10px 0px; font-family: ''Open Sans''; font-weight: 600; line-height: 24px; color: rgb(78, 205, 196); text-rendering: optimizelegibility; font-size: 24px;&quot;&gt;Responsive Theme for you!&lt;/h3&gt;\r\n\r\n&lt;p style=&quot;margin: 0px 0px 10px; color: rgb(102, 114, 128); font-family: ''Open Sans''; font-size: 13px; line-height: 20px; padding: 15px 0px 0px 0px;&quot;&gt;Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries&lt;/p&gt;\r\n\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(91, 89, 2, '', 'a:4:{i:1;s:16:\"Categories hover\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:183:\"&lt;b&gt;Notice&lt;/b&gt;: Undefined index: url in &lt;b&gt;F:xampphtdocsytc_extensionsopencartadminview	emplatemoduleso_megamenu.tpl&lt;/b&gt; on line &lt;b&gt;123&lt;/b&gt;\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 2, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:16:{i:0;a:2:{s:4:\"name\";s:10:\"Components\";s:2:\"id\";i:25;}i:1;a:2:{s:4:\"name\";s:14:\"Desktops > Mac\";s:2:\"id\";i:27;}i:2;a:2:{s:4:\"name\";s:26:\"Laptops & Notebooks > Macs\";s:2:\"id\";i:46;}i:3;a:2:{s:4:\"name\";s:11:\"MP3 Players\";s:2:\"id\";i:34;}i:4;a:2:{s:4:\"name\";s:21:\"Components > Printers\";s:2:\"id\";i:30;}i:5;a:2:{s:4:\"name\";s:21:\"Components > Scanners\";s:2:\"id\";i:31;}i:6;a:2:{s:4:\"name\";s:24:\"Components > Web Cameras\";s:2:\"id\";i:32;}i:7;a:2:{s:4:\"name\";s:8:\"Software\";s:2:\"id\";i:17;}i:8;a:2:{s:4:\"name\";s:7:\"Cameras\";s:2:\"id\";i:33;}i:9;a:2:{s:4:\"name\";s:13:\"Desktops > PC\";s:2:\"id\";i:26;}i:10;a:2:{s:4:\"name\";s:13:\"Phones & PDAs\";s:2:\"id\";i:24;}i:11;a:2:{s:4:\"name\";s:19:\"Laptops & Notebooks\";s:2:\"id\";i:18;}i:12;a:2:{s:4:\"name\";s:21:\"Components > Monitors\";s:2:\"id\";i:28;}i:13;a:2:{s:4:\"name\";s:8:\"Desktops\";s:2:\"id\";i:20;}i:14;a:2:{s:4:\"name\";s:42:\"Components  >  Monitors  >  test 1\";s:2:\"id\";i:35;}i:15;a:2:{s:4:\"name\";s:8:\"Software\";s:2:\"id\";i:17;}}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(92, 89, 3, '', 'a:4:{i:1;s:18:\"Categories visible\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 6, 2, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:4:{i:0;a:3:{s:4:\"name\";s:14:\"Desktops > Mac\";s:2:\"id\";i:27;s:8:\"children\";a:5:{i:0;a:2:{s:4:\"name\";s:26:\"Laptops & Notebooks > Macs\";s:2:\"id\";i:46;}i:1;a:2:{s:4:\"name\";s:21:\"Components > Printers\";s:2:\"id\";i:30;}i:2;a:2:{s:4:\"name\";s:13:\"Phones & PDAs\";s:2:\"id\";i:24;}i:3;a:2:{s:4:\"name\";s:30:\"Components > Monitors > test 2\";s:2:\"id\";i:36;}i:4;a:2:{s:4:\"name\";s:21:\"MP3 Players > test 17\";s:2:\"id\";i:49;}}}i:1;a:3:{s:4:\"name\";s:30:\"Components > Monitors > test 1\";s:2:\"id\";i:35;s:8:\"children\";a:5:{i:0;a:2:{s:4:\"name\";s:21:\"MP3 Players > test 11\";s:2:\"id\";i:43;}i:1;a:2:{s:4:\"name\";s:21:\"MP3 Players > test 12\";s:2:\"id\";i:44;}i:2;a:2:{s:4:\"name\";s:21:\"MP3 Players > test 20\";s:2:\"id\";i:52;}i:3;a:2:{s:4:\"name\";s:19:\"Laptops & Notebooks\";s:2:\"id\";i:18;}i:4;a:2:{s:4:\"name\";s:21:\"Components > Scanners\";s:2:\"id\";i:31;}}}i:2;a:3:{s:4:\"name\";s:8:\"Software\";s:2:\"id\";i:17;s:8:\"children\";a:5:{i:0;a:2:{s:4:\"name\";s:26:\"Laptops & Notebooks > Macs\";s:2:\"id\";i:46;}i:1;a:2:{s:4:\"name\";s:11:\"MP3 Players\";s:2:\"id\";i:34;}i:2;a:2:{s:4:\"name\";s:14:\"Desktops > Mac\";s:2:\"id\";i:27;}i:3;a:2:{s:4:\"name\";s:32:\"Components > Mice and Trackballs\";s:2:\"id\";i:29;}i:4;a:2:{s:4:\"name\";s:21:\"Components > Monitors\";s:2:\"id\";i:28;}}}i:3;a:3:{s:4:\"name\";s:13:\"Phones & PDAs\";s:2:\"id\";i:24;s:8:\"children\";a:5:{i:0;a:2:{s:4:\"name\";s:21:\"Components > Printers\";s:2:\"id\";i:30;}i:1;a:2:{s:4:\"name\";s:13:\"Desktops > PC\";s:2:\"id\";i:26;}i:2;a:2:{s:4:\"name\";s:20:\"MP3 Players > test 8\";s:2:\"id\";i:41;}i:3;a:2:{s:4:\"name\";s:20:\"MP3 Players > test 7\";s:2:\"id\";i:40;}i:4;a:2:{s:4:\"name\";s:20:\"MP3 Players > test 6\";s:2:\"id\";i:39;}}}}s:7:\"columns\";s:1:\"2\";s:7:\"submenu\";s:1:\"2\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(94, 0, 4, '', 'a:4:{i:1;s:7:\"Product\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 1, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:2:\"42\";s:4:\"name\";s:21:\"Apple Cinema 30&quot;\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-car'),
(95, 94, 6, '', 'a:4:{i:1;s:7:\"Product\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 1, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:2:\"44\";s:4:\"name\";s:11:\"MacBook Air\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(96, 94, 8, '', 'a:4:{i:1;s:7:\"Product\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 1, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:2:\"46\";s:4:\"name\";s:9:\"Sony VAIO\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(97, 94, 5, '', 'a:4:{i:1;s:7:\"Product\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 1, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:2:\"29\";s:4:\"name\";s:13:\"Palm Treo Pro\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(98, 94, 9, '', 'a:4:{i:1;s:12:\"Manufacturer\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 3, 'a:7:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:11:\"manufacture\";a:2:{s:4:\"name\";a:2:{i:0;s:15:\"Hewlett-Packard\";i:1;s:4:\"Palm\";}s:2:\"id\";a:2:{i:0;s:1:\"7\";i:1;s:1:\"6\";}}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(100, 0, 12, '', 'a:4:{i:1;s:10:\"Categories\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 4, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', 'fa fa-taxi'),
(102, 0, 17, '', 'a:4:{i:1;s:4:\"Blog\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 4, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', 'fa fa-camera-retro'),
(103, 100, 16, '', 'a:4:{i:1;s:12:\"Manufacturer\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 12, 3, 'a:7:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:11:\"manufacture\";a:2:{s:4:\"name\";a:5:{i:0;s:5:\"Apple\";i:1;s:5:\"Canon\";i:2;s:3:\"HTC\";i:3;s:4:\"Palm\";i:4;s:15:\"Hewlett-Packard\";}s:2:\"id\";a:5:{i:0;s:1:\"8\";i:1;s:1:\"9\";i:2;s:1:\"5\";i:3;s:1:\"6\";i:4;s:1:\"7\";}}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(115, 114, 25, '', 'a:4:{i:1;s:7:\"Macbook\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 4, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:21:\"catalog/demo/hp_1.jpg\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', ''),
(84, 0, 18, '', 'a:4:{i:1;s:14:\"Buy this theme\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 1, '80%', 1, 4, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:5:\"adfdf\";i:3;s:8:\"ádfasdf\";i:4;s:8:\"ádfadsf\";i:2;s:11:\"adfdasfadsf\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', 'fa fa-camera-retro'),
(85, 84, 19, '', 'a:4:{i:1;s:6:\"Item 1\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:955:\"&lt;h3 style=&quot;margin: 10px 0px; font-family: ''Open Sans''; font-weight: 600; line-height: 24px; color: rgb(78, 205, 196); text-rendering: optimizelegibility; font-size: 24px;&quot;&gt;Responsive Theme for you!&lt;/h3&gt;\r\n\r\n&lt;p style=&quot;margin: 0px 0px 10px; color: rgb(102, 114, 128); font-family: ''Open Sans''; font-size: 13px; line-height: 20px; padding: 15px 0px 27px;&quot;&gt;Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries.&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;a href=&quot;http://localhost:8888/HTML/vitalia/#&quot; style=&quot;text-decoration: none; color: rgb(255, 102, 102); line-height: 1.6; font-family: ''Open Sans''; font-size: 14px; font-weight: 600;&quot;&gt;Buy this Open Cart theme&lt;/a&gt;&lt;/p&gt;\r\n\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(86, 84, 20, '', 'a:4:{i:1;s:6:\"Item 2\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:1048:\"&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;image/no_image.png&quot; style=&quot;height: 119px;width:253px; max-width: 100%; vertical-align: middle; border: 0px; color: rgb(102, 114, 128); font-family: ''Open Sans''; font-size: 13px; line-height: 20px;&quot;&gt;&lt;/p&gt;\r\n\r\n&lt;h3 style=&quot;margin: 0px; font-family: ''Open Sans''; font-weight: 600; line-height: 30px; color: rgb(102, 114, 128); text-rendering: optimizelegibility; font-size: 20px; padding: 17px 0px 7px;&quot;&gt;50+ CMS blocks&lt;/h3&gt;\r\n\r\n&lt;p style=&quot;margin: 0px 0px 10px; color: rgb(102, 114, 128); font-family: ''Open Sans''; font-size: 13px; line-height: 20px; padding-bottom: 10px;&quot;&gt;Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it.&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;a href=&quot;#&quot; style=&quot;text-decoration: none; color: rgb(102, 114, 128); line-height: 20px; font-family: ''Open Sans''; font-size: 13px; font-weight: 600;&quot;&gt;See all features&lt;/a&gt;&lt;/p&gt;\r\n\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(87, 84, 21, '', 'a:4:{i:1;s:6:\"Item 3\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:1053:\"&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;image/no_image.png&quot; style=&quot;height: 119px;width:253px; max-width: 100%; vertical-align: middle; border: 0px; color: rgb(102, 114, 128); font-family: ''Open Sans''; font-size: 13px; line-height: 20px;&quot;&gt;&lt;/p&gt;\r\n\r\n&lt;h3 style=&quot;margin: 0px; font-family: ''Open Sans''; font-weight: 600; line-height: 30px; color: rgb(102, 114, 128); text-rendering: optimizelegibility; font-size: 20px; padding: 17px 0px 7px;&quot;&gt;Super documentation&lt;/h3&gt;\r\n\r\n&lt;p style=&quot;margin: 0px 0px 10px; color: rgb(102, 114, 128); font-family: ''Open Sans''; font-size: 13px; line-height: 20px; padding-bottom: 10px;&quot;&gt;Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it.&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;a href=&quot;#&quot; style=&quot;text-decoration: none; color: rgb(102, 114, 128); line-height: 20px; font-family: ''Open Sans''; font-size: 13px; font-weight: 600;&quot;&gt;See all features&lt;/a&gt;&lt;/p&gt;\r\n\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(88, 84, 22, '', 'a:4:{i:1;s:6:\"Item 4\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:1051:\"&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;image/no_image.png&quot; style=&quot;height: 119px;width:253px; max-width: 100%; vertical-align: middle; border: 0px; color: rgb(102, 114, 128); font-family: ''Open Sans''; font-size: 13px; line-height: 20px;&quot;&gt;&lt;/p&gt;\r\n\r\n&lt;h3 style=&quot;margin: 0px; font-family: ''Open Sans''; font-weight: 600; line-height: 30px; color: rgb(102, 114, 128); text-rendering: optimizelegibility; font-size: 20px; padding: 17px 0px 7px;&quot;&gt;Easy to customize&lt;/h3&gt;\r\n\r\n&lt;p style=&quot;margin: 0px 0px 10px; color: rgb(102, 114, 128); font-family: ''Open Sans''; font-size: 13px; line-height: 20px; padding-bottom: 10px;&quot;&gt;Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it.&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;a href=&quot;#&quot; style=&quot;text-decoration: none; color: rgb(102, 114, 128); line-height: 20px; font-family: ''Open Sans''; font-size: 13px; font-weight: 600;&quot;&gt;See all features&lt;/a&gt;&lt;/p&gt;\r\n\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(99, 0, 10, '', 'a:4:{i:1;s:5:\"Women\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '25%', 0, 4, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', 'fa fa-rocket'),
(89, 0, 0, '', 'a:4:{i:1;s:3:\"Men\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 4, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', 'fa fa-camera-retro'),
(104, 100, 15, '', 'a:4:{i:1;s:7:\"Product\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 1, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:2:\"45\";s:4:\"name\";s:11:\"MacBook Pro\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(105, 100, 14, '', 'a:4:{i:1;s:18:\"Categories visible\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 2, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:2:{i:0;a:3:{s:4:\"name\";s:13:\"Phones & PDAs\";s:2:\"id\";i:24;s:8:\"children\";a:6:{i:0;a:2:{s:4:\"name\";s:13:\"Desktops > PC\";s:2:\"id\";i:26;}i:1;a:2:{s:4:\"name\";s:13:\"Phones & PDAs\";s:2:\"id\";i:24;}i:2;a:2:{s:4:\"name\";s:11:\"MP3 Players\";s:2:\"id\";i:34;}i:3;a:2:{s:4:\"name\";s:26:\"Laptops & Notebooks > Macs\";s:2:\"id\";i:46;}i:4;a:2:{s:4:\"name\";s:14:\"Desktops > Mac\";s:2:\"id\";i:27;}i:5;a:2:{s:4:\"name\";s:13:\"Phones & PDAs\";s:2:\"id\";i:24;}}}i:1;a:3:{s:4:\"name\";s:32:\"Components > Mice and Trackballs\";s:2:\"id\";i:29;s:8:\"children\";a:5:{i:0;a:2:{s:4:\"name\";s:21:\"Components > Monitors\";s:2:\"id\";i:28;}i:1;a:2:{s:4:\"name\";s:30:\"Components > Monitors > test 1\";s:2:\"id\";i:35;}i:2;a:2:{s:4:\"name\";s:8:\"Software\";s:2:\"id\";i:17;}i:3;a:2:{s:4:\"name\";s:21:\"Components > Scanners\";s:2:\"id\";i:31;}i:4;a:2:{s:4:\"name\";s:10:\"Components\";s:2:\"id\";i:25;}}}}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"2\";s:15:\"submenu_columns\";s:1:\"1\";}}', 'hot', 'fa fa-camera-retro'),
(106, 100, 13, '', 'a:4:{i:1;s:18:\"Categories visible\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 6, 2, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:2:{i:0;a:3:{s:4:\"name\";s:32:\"Components > Mice and Trackballs\";s:2:\"id\";i:29;s:8:\"children\";a:15:{i:0;a:2:{s:4:\"name\";s:21:\"Components > Monitors\";s:2:\"id\";i:28;}i:1;a:2:{s:4:\"name\";s:14:\"Desktops > Mac\";s:2:\"id\";i:27;}i:2;a:2:{s:4:\"name\";s:26:\"Laptops & Notebooks > Macs\";s:2:\"id\";i:46;}i:3;a:2:{s:4:\"name\";s:11:\"MP3 Players\";s:2:\"id\";i:34;}i:4;a:2:{s:4:\"name\";s:30:\"Components > Monitors > test 1\";s:2:\"id\";i:35;}i:5;a:2:{s:4:\"name\";s:30:\"Components > Monitors > test 2\";s:2:\"id\";i:36;}i:6;a:2:{s:4:\"name\";s:20:\"MP3 Players > test 8\";s:2:\"id\";i:41;}i:7;a:2:{s:4:\"name\";s:20:\"MP3 Players > test 6\";s:2:\"id\";i:39;}i:8;a:2:{s:4:\"name\";s:20:\"MP3 Players > test 5\";s:2:\"id\";i:37;}i:9;a:2:{s:4:\"name\";s:20:\"MP3 Players > test 4\";s:2:\"id\";i:38;}i:10;a:2:{s:4:\"name\";s:21:\"MP3 Players > test 24\";s:2:\"id\";i:56;}i:11;a:2:{s:4:\"name\";s:21:\"MP3 Players > test 23\";s:2:\"id\";i:55;}i:12;a:2:{s:4:\"name\";s:21:\"MP3 Players > test 21\";s:2:\"id\";i:53;}i:13;a:2:{s:4:\"name\";s:10:\"Components\";s:2:\"id\";i:25;}i:14;a:2:{s:4:\"name\";s:7:\"Cameras\";s:2:\"id\";i:33;}}}i:1;a:3:{s:4:\"name\";s:21:\"Components > Printers\";s:2:\"id\";i:30;s:8:\"children\";a:15:{i:0;a:2:{s:4:\"name\";s:13:\"Phones & PDAs\";s:2:\"id\";i:24;}i:1;a:2:{s:4:\"name\";s:13:\"Desktops > PC\";s:2:\"id\";i:26;}i:2;a:2:{s:4:\"name\";s:8:\"Software\";s:2:\"id\";i:17;}i:3;a:2:{s:4:\"name\";s:21:\"Components > Scanners\";s:2:\"id\";i:31;}i:4;a:2:{s:4:\"name\";s:8:\"Desktops\";s:2:\"id\";i:20;}i:5;a:2:{s:4:\"name\";s:19:\"Laptops & Notebooks\";s:2:\"id\";i:18;}i:6;a:2:{s:4:\"name\";s:26:\"Laptops & Notebooks > Macs\";s:2:\"id\";i:46;}i:7;a:2:{s:4:\"name\";s:11:\"MP3 Players\";s:2:\"id\";i:34;}i:8;a:2:{s:4:\"name\";s:32:\"Components > Mice and Trackballs\";s:2:\"id\";i:29;}i:9;a:2:{s:4:\"name\";s:21:\"Components > Monitors\";s:2:\"id\";i:28;}i:10;a:2:{s:4:\"name\";s:14:\"Desktops > Mac\";s:2:\"id\";i:27;}i:11;a:2:{s:4:\"name\";s:8:\"Software\";s:2:\"id\";i:17;}i:12;a:2:{s:4:\"name\";s:21:\"Components > Scanners\";s:2:\"id\";i:31;}i:13;a:2:{s:4:\"name\";s:10:\"Components\";s:2:\"id\";i:25;}i:14;a:2:{s:4:\"name\";s:7:\"Cameras\";s:2:\"id\";i:33;}}}}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"2\";s:15:\"submenu_columns\";s:1:\"3\";}}', 'hot', 'fa fa-camera-retro'),
(107, 99, 11, '', 'a:4:{i:1;s:11:\"categories1\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '50%', 0, 12, 2, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:1:{i:0;a:3:{s:4:\"name\";s:10:\"Components\";s:2:\"id\";i:25;s:8:\"children\";a:2:{i:0;a:3:{s:4:\"name\";s:27:\"Components  >  Monitors\";s:2:\"id\";i:28;s:8:\"children\";a:1:{i:0;a:2:{s:4:\"name\";s:10:\"Components\";s:2:\"id\";i:25;}}}i:1;a:3:{s:4:\"name\";s:38:\"Components  >  Mice and Trackballs\";s:2:\"id\";i:29;s:8:\"children\";a:1:{i:0;a:2:{s:4:\"name\";s:27:\"Components  >  Monitors\";s:2:\"id\";i:28;}}}}}}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', ''),
(116, 114, 26, '', 'a:4:{i:1;s:10:\"Television\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 4, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:21:\"catalog/demo/hp_2.jpg\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', ''),
(117, 114, 27, '', 'a:4:{i:1;s:4:\"Ipad\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 4, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:21:\"catalog/demo/hp_3.jpg\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', ''),
(114, 0, 23, '', 'a:4:{i:1;s:5:\"Image\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 4, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', 'fa fa-picture-o'),
(111, 94, 7, '', 'a:4:{i:1;s:7:\"Product\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 3, 1, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:2:\"46\";s:4:\"name\";s:9:\"Sony VAIO\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', ''),
(119, 114, 24, '', 'a:4:{i:1;s:6:\"Laptop\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 2, 3, 4, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:21:\"catalog/demo/hp_2.jpg\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', ''),
(120, 121, 29, '', 'a:4:{i:1;s:12:\"Product List\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 12, 6, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:1:\"4\";s:4:\"type\";s:7:\"popular\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:1:\"4\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', ''),
(121, 0, 28, '', 'a:4:{i:1;s:12:\"Product List\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0,".$module_id.", 'a:2:{s:3:\"url\";s:0:\"\";s:8:\"category\";s:0:\"\";}', 'a:4:{i:1;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";i:2;s:0:\"\";}', 0, 0, 0, '100%', 0, 4, 0, 'a:6:{s:4:\"html\";a:1:{s:4:\"text\";a:4:{i:1;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:3;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:4;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";i:2;s:29:\"&lt;p&gt;&lt;br&gt;&lt;/p&gt;\";}}s:7:\"product\";a:2:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";}s:5:\"image\";a:2:{s:4:\"link\";s:12:\"no_image.png\";s:10:\"show_title\";s:1:\"1\";}s:11:\"subcategory\";a:8:{s:8:\"category\";s:0:\"\";s:13:\"limit_level_1\";s:0:\"\";s:13:\"limit_level_2\";s:0:\"\";s:10:\"show_title\";s:1:\"1\";s:10:\"show_image\";s:1:\"1\";s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}s:11:\"productlist\";a:4:{s:5:\"limit\";s:0:\"\";s:4:\"type\";s:3:\"new\";s:10:\"show_title\";s:1:\"1\";s:3:\"col\";s:0:\"\";}s:10:\"categories\";a:4:{s:10:\"categories\";a:0:{}s:7:\"columns\";s:1:\"1\";s:7:\"submenu\";s:1:\"1\";s:15:\"submenu_columns\";s:1:\"1\";}}', '', '')
            ");
        }
        return false;
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "mega_menu`");
        //$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "settting  WHERE code = 'so_megamenu'");
    }

    public function skrut($c,$d) {
        if(strlen($c) > $d) {
            $ciag = substr($c,0,$d);
            $ciag .="...";
            return $ciag;
        } else {
            return $c;
        }
    }

    public function is_table_exist($table){
        $query = $this->db->query("SHOW TABLES LIKE '".$table."'");
        if( count($query->rows) <= 0 ) {
            return true;
        }
        return false;
    }
	public function getModuleId() {
		$sql = " SHOW TABLE STATUS LIKE '" . DB_PREFIX . "module'" ;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function duplicateModule($module_id,$import_module){
		$parent_menu = $this->getMenuByIdModule($import_module);
		if($parent_menu){
			foreach ($parent_menu as $menu) {
				$dane = $this->model_extension_module_so_megamenu->getMenu(intval($menu['id']));
				$dane['module_id'] = $module_id;
				$id_parent_add = $this->model_extension_module_so_megamenu->addMenu($dane);
				$subcategories = $this->model_extension_module_so_megamenu->getSubMenu(intval($menu['id']));
				if($subcategories){
				foreach ($subcategories as $result) {
					$data = array(
							'parent_id' => $id_parent_add,
							'name' => unserialize($result['name']),
							'description' => unserialize($result['description']),
							'icon' => $result['icon'],
							'module_id' => $module_id,
							'link' => $result['link'],
							'type_link' => $result['type_link'],
							'new_window' => $result['new_window'],
							'status' => $result['status'],
							'position' => $result['position'],
							'submenu_width' => $result['submenu_width'],
							'display_submenu' => $result['submenu_type'],
							'content_width' => $result['content_width'],
							'content_type' => $result['content_type'],
							'content' => @unserialize($result['content']),
							'list_categories' => (isset($result['content']['categories']['categories']) && $result['content']['categories']['categories']) ? $this->model_extension_module_so_megamenu->getCategories(unserialize($result['content']['categories']['categories'])) : ''
						);
						$this->model_extension_module_so_megamenu->addMenu($data);
					}
				}
			}
		}
	}
	public function getMenuByIdModule($module_id){
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."mega_menu WHERE  module_id = '".$module_id."' AND parent_id=0");
		return $query->rows;
	}
	public function duplicateMenu($id_duplicate){
		$dane = $this->model_extension_module_so_megamenu->getMenu(intval($id_duplicate));
		$id_parent_add = $this->model_extension_module_so_megamenu->addMenu($dane);
		$subcategories = $this->model_extension_module_so_megamenu->getSubMenu(intval($id_duplicate));
		if($subcategories){
			foreach ($subcategories as $result) {
				$data = array(
					'parent_id' => $id_parent_add,
					'name' => unserialize($result['name']),
					'description' => unserialize($result['description']),
					'icon' => $result['icon'],
					'link' => $result['link'],
					'type_link' => $result['type_link'],
					'new_window' => $result['new_window'],
					'status' => $result['status'],
					'position' => $result['position'],
					'submenu_width' => $result['submenu_width'],
					'display_submenu' => $result['submenu_type'],
					'content_width' => $result['content_width'],
					'content_type' => $result['content_type'],
					'content' => @unserialize($result['content']),
					'list_categories' => (isset($result['content']['categories']['categories']) && $result['content']['categories']['categories']) ? $this->model_extension_module_so_megamenu->getCategories(unserialize($result['content']['categories']['categories'])) : ''
				);
				$this->model_extension_module_so_megamenu->addMenu($data);
			}
		}
		
		return $id_parent_add;
	}
}
?>

<?php
class So_Fields {
	public $config;
	
	public function __construct($config ) {
		$this->config =  $config;
		 $module_row = 0;
	}
	/**
	 * Function field_onOff
	 * Create Field Button
	 *
	 * Parameters:
	 *     (key)  - array value of Parent level 
	 *     (name) - string value of the name
	*/
	public function field_onOff($key,$name) {
		
		$onClassActive = '';
		$offClassActive = '';
		$onCheck = '';
		$offCheck = '';
		$names	= $key.'['.$name.']';
		$values	= isset($this->config[$key][$name]) ? $this->config[$key][$name] : '';
		
		(($values == 1)? $onClassActive ='btn-success active' : $offClassActive='btn-success active');
		(($values == 1)? $onCheck ='checked="checked"' : $offCheck='checked="checked"');
		
		$fieldOnOff  = '<div class="btn-group btn-toggle" data-toggle="buttons">';
		$fieldOnOff .= '<label class="btn btn-default btn-sm '.$onClassActive.'">';
		$fieldOnOff .= '<input type="radio" class="field-'.$name.'" name="'.$names.'" value="1" '.$onCheck.'>ON';
		$fieldOnOff .= '</label>';
		$fieldOnOff .= '<label class="btn btn-default btn-sm '.$offClassActive.'">';
		$fieldOnOff .= '<input type="radio" class="field-'.$name.'" name="'.$names.'" value="0" '.$offCheck.'>OFF';
		$fieldOnOff .= '</label>';
		$fieldOnOff .=	'</div>';
		return $fieldOnOff;
		
		
	}
	
	public function field_radio($key,$name,$options,$valueDefault=null,$className=null) {
		$field = '';
		$radios = array();
		$names	= $key.'['.$name.']';
		
		$selected	= isset($this->config[$key][$name]) ? $this->config[$key][$name] : $valueDefault;
		
		$radios[] = '<div class="btn-group btn-toggle '.$className.'" data-toggle="buttons">';
		foreach ($options as $value => $description) {
			if($value ==  $selected){
				$radios[] = '<label class="btn btn-default btn-sm btn-success active"><input class="field-'.$name.'" type="radio" name="'.$names.'" value="'.$value.'" checked="checked" /> ' . $description . '</label>';
			}else{
				$radios[] = '<label class="btn btn-default btn-sm "><input class="field-'.$name.'" type="radio" name="'.$names.'" value="'.$value.'" /> ' . $description . '</label>';
			}
		}
		$radios[]=	'</div>';
		$field = implode('', $radios);
		return $field;
	}
	
	
	/**
	 * Function field_onOffFont
	 * Create Field Button Google Font
	 *
	 * Parameters:
	 *     (key)  - array value of Parent level 
	 *     (name) - string value of the name
	*/
	function field_onOffFont($key,$name) {
		
		$onClassActive = null;
		$offClassActive = null;
		$onCheck = null;
		$offCheck = null;
		
		$names	= $key.'['.$name.']';
		$values	= isset($this->config[$key][$name]) ? $this->config[$key][$name] : '';
		
		(($values == 'standard')? $onClassActive ='btn-success active' : $offClassActive='btn-success active');
		(($values == 'standard')? $onCheck ='checked="checked"' : $offCheck='checked="checked"');
		
		$fieldOnOff  = '<div class="btn-group btn-toggle block-group " data-toggle="buttons">';
		$fieldOnOff .= '<label class="btn btn-default btn-sm '.$onClassActive.'">';
		$fieldOnOff .= '<input class="type-fonts" type="radio" name="'.$names.'" value="standard" '.$onCheck.'>Standard';
		$fieldOnOff .= '</label>';
		$fieldOnOff .= '<label class="btn btn-default btn-sm '.$offClassActive.'">';
		$fieldOnOff .= '<input class="type-fonts" type="radio" name="'.$names.'" value="google" '.$offCheck.'>Google Fonts';
		$fieldOnOff .= '</label>';
		$fieldOnOff .=	'</div>';
		return $fieldOnOff;
	}
	
	
	function field_typeheader($nameconfig,$element,$element_array,$columns= null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		$columns = isset($columns) ? 'col-sm-'.round(12/$columns) : 'col-sm-12';
		$fieldType  = '<div class="panel-container row">';
		
		$result = [];
		
		foreach ($element_array as  $fc){
			if (!array_intersect($fc[$element], $result)) {

				$result = $fc[$element];
				$keylayout = $fc['key'];
				$element_key = $fc[$element]['key'];
				
				$fieldType  .= '<div class="'.$columns.'"><div class="group-typeheader  radio ">';
				$fieldType  .= '<h3 class="typetitle">'.$fc[$element]['title'].'</h3>';
				$fieldType  .= '<label class="col-sm-12">';
				
				if($element_key == $values  ){
					$fieldType  .= '<input type="radio" name="'.$names.'" value="'.$element_key.'" checked  >';
					
					$fieldType  .= '<span class="type fa active" data-keylayout="'.$keylayout.'" data-'.$element.'="'.$element_key.'">';
					if(file_exists(PATH_SOCONFIG.'images/desktop/'.$element.$element_key.'.jpg')){
						$fieldType  .='<img src="'.DIR_SOCONFIG.'images/desktop/'.$element.$element_key.'.jpg" alt="'.$element.$element_key.'"/>';
					}else{
						$fieldType  .='	<img src="http://via.placeholder.com/1000x150/ddd/3498db" alt="'.$element.$fc['key'].'"/>';
					}
					$fieldType  .='</span>';

				}else{
					$fieldType  .= '<input type="radio" name="'.$names.'" value="'.$element_key.'" >';
					$fieldType  .= '<span class="type fa" data-keylayout="'.$keylayout.'" data-'.$element.'="'.$element_key.'">';
					if(file_exists(PATH_SOCONFIG.'images/desktop/'.$element.$element_key.'.jpg')){
						$fieldType  .='<img src="'.DIR_SOCONFIG.'images/desktop/'.$element.$element_key.'.jpg" alt="'.$element.$element_key.'"/>';
					}else{
						$fieldType  .='	<img src="http://via.placeholder.com/1000x150/ddd/3498db" alt="'.$element.$fc['key'].'"/>';
					}
					$fieldType  .='</span>';

				}
				$fieldType  .= '</label>';
				
				$fieldType  .= '</div></div>';
			}
		}
		$fieldType  .= '</div>';
		return $fieldType;
	}
	
	function field_typelayout($nameconfig,$element,$element_array,$columns= null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		$columns = isset($columns) ? 'col-sm-'.round(12/$columns) : 'col-sm-15';
		$fieldType  = '<div class="panel-container row">';

		foreach ($element_array as  $fc){
			$keylayout = $fc['key'];
			$keyheader = $fc['typeheader']['key'];
			$keyfooter = !empty($fc['typefooter']) ? $fc['typefooter']['key'] : '';
			
			$fieldType  .= '<div class="'.$columns.'"><div class="group-typeheader  radio ">';
			$fieldType  .= '<label class="col-sm-12">';
			if($fc['key'] == $values){
				$fieldType  .= '<input  type="radio" name="'.$names.'" value="'.$fc['key'].'" checked  >';
				$fieldType  .= '<span class="type fa active" data-keylayout="'.$keylayout.'" data-keyheader="'.$keyheader.'" data-keyfooter="'.$keyfooter.'">';
				if(file_exists(PATH_SOCONFIG.'images/desktop/'.$element.$fc['key'].'.jpg')){
					$fieldType  .='<img src="'.DIR_SOCONFIG.'images/desktop/'.$element.$fc['key'].'.jpg" alt="'.$element.$fc['key'].'"/>';
				}else{
					$fieldType  .='	<img src="http://via.placeholder.com/150x180/ddd/3498db" alt="'.$element.$fc['key'].'"/>';
				}
				$fieldType  .='</span>';

			}else{
				$fieldType  .= '<input type="radio" name="'.$names.'" value="'.$fc['key'].'" >';
				$fieldType  .= '<span class="type fa" data-keylayout="'.$keylayout.'" data-keyheader="'.$keyheader.'" data-keyfooter="'.$keyfooter.'">';
				if(file_exists(PATH_SOCONFIG.'images/desktop/'.$element.$fc['key'].'.jpg')){
					$fieldType  .='<img src="'.DIR_SOCONFIG.'images/desktop/'.$element.$fc['key'].'.jpg" alt="'.$element.$fc['key'].'"/>';
				}else{
					$fieldType  .='	<img src="http://via.placeholder.com/150x180/ddd/3498db" alt="'.$element.$fc['key'].'"/>';
				}
				$fieldType  .='</span>';
			}
			$fieldType  .= '</label>';
			$fieldType  .= '<h3 class="typetitle">'.$fc['typelayout'].'</h3>';
			$fieldType  .= '<p class="applyLayout btn btn-success" data-keylayout="'.$keylayout.'">Apply Default Setting</p>';
			$fieldType  .= '</div></div>';
		}
		$fieldType  .= '</div>';
		return $fieldType;
	}

	function field_mtypelayout($nameconfig,$element,$element_array,$columns= null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		$columns = isset($columns) ? 'col-sm-'.round(12/$columns) : 'col-sm-15';
		$fieldType  = '<div class="panel-container row">';

		foreach ($element_array as  $fc){
			$keylayout = $fc['key'];
			$keyheader = $fc['mtypeheader']['key'];
			$keyfooter = !empty($fc['mtypefooter']) ? $fc['mtypefooter']['key'] : '';
			
			$fieldType  .= '<div class="'.$columns.'"><div class="group-typeheader  radio ">';
			$fieldType  .= '<label class="col-sm-12">';
			if($fc['key'] == $values){
				$fieldType  .= '<input  type="radio" name="'.$names.'" value="'.$fc['key'].'" checked  >';
				$fieldType  .= '<span class="type fa active" data-keylayout="'.$keylayout.'" data-keyheader="'.$keyheader.'" data-keyfooter="'.$keyfooter.'">';
				if(file_exists(PATH_SOCONFIG.'images/mobile/'.$element.$fc['key'].'.jpg')){
					$fieldType  .='<img src="'.DIR_SOCONFIG.'images/mobile/'.$element.$fc['key'].'.jpg" alt="'.$element.$fc['key'].'"/>';
				}else{
					$fieldType  .='	<img src="http://via.placeholder.com/150x197/ddd/3498db" alt="'.$element.$fc['key'].'"/>';
				}
				$fieldType  .='</span>';
			}else{
				$fieldType  .= '<input type="radio" name="'.$names.'" value="'.$fc['key'].'" >';
				$fieldType  .= '<span class="type fa" data-keylayout="'.$keylayout.'" data-keyheader="'.$keyheader.'" data-keyfooter="'.$keyfooter.'">';
				if(file_exists(PATH_SOCONFIG.'images/mobile/'.$element.$fc['key'].'.jpg')){
					$fieldType  .='<img src="'.DIR_SOCONFIG.'images/mobile/'.$element.$fc['key'].'.jpg" alt="'.$element.$fc['key'].'"/>';
				}else{
					$fieldType  .='	<img src="http://via.placeholder.com/150x197/ddd/3498db" alt="'.$element.$fc['key'].'"/>';
				}
				$fieldType  .='</span>';
			}
			$fieldType  .= '</label>';
			$fieldType  .= '<h3 class="typetitle">'.$fc['mtypelayout'].'</h3>';
			$fieldType  .= '</div></div>';
		}
		$fieldType  .= '</div>';
		return $fieldType;
	}
	
	function field_mtypeheader($nameconfig,$element,$element_array,$columns= null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		$columns = isset($columns) ? 'col-sm-'.round(12/$columns) : 'col-sm-12';
		$fieldType  = '<div class="panel-container row">';
		
		$result = [];
		
		foreach ($element_array as  $fc){
			if (!array_intersect($fc[$element], $result)) {

				$result = $fc[$element];
				$keylayout = $fc['key'];
				$element_key = $fc[$element]['key'];
				
				$fieldType  .= '<div class="'.$columns.'"><div class="group-typeheader  radio ">';
				$fieldType  .= '<h3 class="typetitle">'.$fc[$element]['title'].'</h3>';
				$fieldType  .= '<label class="col-sm-12">';
				
				if($element_key == $values  ){
					$fieldType  .= '<input type="radio" name="'.$names.'" value="'.$element_key.'" checked  >';

					$fieldType  .= '<span class="type fa active" data-keylayout="'.$keylayout.'" data-'.$element.'="'.$element_key.'">';
					if(file_exists(PATH_SOCONFIG.'images/mobile/'.$element.$fc['key'].'.jpg')){
						$fieldType  .='<img src="'.DIR_SOCONFIG.'images/mobile/'.$element.$element_key.'.jpg" alt="'.$element.$fc['key'].'"/>';
					}else{
						$fieldType  .='<img src="http://via.placeholder.com/480x73/ddd/3498db" alt="'.$element.$fc['key'].'"/>';
					}
					$fieldType  .='</span>';
				}else{
					$fieldType  .= '<input type="radio" name="'.$names.'" value="'.$element_key.'" >';
					$fieldType  .= '<span class="type fa " data-keylayout="'.$keylayout.'" data-'.$element.'="'.$element_key.'">';
					if(file_exists(PATH_SOCONFIG.'images/mobile/'.$element.$fc['key'].'.jpg')){
						$fieldType  .='<img src="'.DIR_SOCONFIG.'images/mobile/'.$element.$element_key.'.jpg" alt="'.$element.$fc['key'].'"/>';
					}else{
						$fieldType  .='	<img src="http://via.placeholder.com/480x73/ddd/3498db" alt="'.$element.$fc['key'].'"/>';
					}
					$fieldType  .='</span>';
				}
				$fieldType  .= '</label>';
				
				$fieldType  .= '</div></div>';
			}
		}
		$fieldType  .= '</div>';
		return $fieldType;
	}

	function field_typebanner($nameconfig,$element,$element_array,$columns= null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		
		$columns = isset($columns) ? 'col-sm-'.round(12/$columns) : 'col-sm-12';
		$fieldType  = '<div class="panel-container row">';
		foreach ($element_array as $fv => $fc){
			$fieldType  .= '<div class="banners-effect-'.$fv.' hover_effect_type '.$columns.'"><div class="group-typeheader group-banners  radio">';
			
			$fieldType  .= '<label class="col-sm-12">';
			if($fv == $values){
				$fieldType  .= '<input type="radio" name="'.$names.'" value="'.$fv.'" checked  >';
				$fieldType  .= '<h3 class="typetitle">'.$fc.'</h3>';
				$fieldType  .= '<div class="banners type fa active"><div><a href="javascript:void(0)" ><img src="'.DIR_SOCONFIG.'images/desktop/banner-01.png" alt=""></a></div></div>';
			}else{
				$fieldType  .= '<input type="radio" name="'.$names.'" value="'.$fv.'" >';
				$fieldType  .= '<h3 class="typetitle">'.$fc.'</h3>';
				$fieldType  .= '<div class="banners type fa"><div><a href="javascript:void(0)"><img src="'.DIR_SOCONFIG.'images/desktop/banner-01.png" alt=""></a></div></div>';
			}
			$fieldType  .= '</label>';
			
			$fieldType  .= '</div></div>';
		}
		$fieldType  .= '</div>';
		return $fieldType;
	}
	
	/**
	 * Function field_text
	 * Create Field Button Google Font
	 *
	 * Parameters:
	 *     (key)  - array value of Parent level 
	 *     (name) - string value of the name
	*/
	function field_text($nameconfig,$element,$placeholder=null,$className=null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';

		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		
		return '<input type="text" name="'.$names.'" value="'.$values.'" placeholder="'.$placeholder.'"  class="form-control '.$className.'" />';
	}
	
	function field_date($nameconfig,$element,$placeholder=null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
	  

        $fielddate ='<div class="input-group date">';
		$fielddate .='<input type="text" name="'.$names.'" value="'.$values.'" placeholder="'.$placeholder.'" data-date-format="YYYY-MM-DD" id="input-date-comingsoon" class="form-control" />';
		$fielddate .='<span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> </span>';
		$fielddate .='</div>';
		return $fielddate;

		//return '<input type="text" name="'.$names.'" value="'.$values.'" placeholder="'.$placeholder.'"  class="form-control" />';
	}

	function field_colors($nameconfig,$element,$placeholder=null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		return '<input id="'.$element.'"  style="background-color:'.$values.';color:white" type="text" name="'.$names.'" value="'.$values.'" placeholder="'.$placeholder.'"  class="form-control text-capital" />';
	}
	
	
	function field_textarea($nameconfig,$element,$placeholder=null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		return '<textarea name="'.$names.'" rows="5" placeholder="'.$placeholder.'" id="input-description-'.$element.'" class="form-control">'.$values.'</textarea>';
	}
	
	function field_addimage($nameconfig,$element,$className=null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		if (is_file(DIR_IMAGE.$values)) $srcimage = $this->resize($values, 100, 100);
		else $srcimage = $this->resize('no_image.png', 100, 100);
		
		$fieldimage ='<a href="" id="thumb-'.$element.'" data-toggle="image" class="img-thumbnail '.$className.'">';
		$fieldimage .='<img src="'.$srcimage.'"  data-placeholder="Background Image:" />';
		$fieldimage .='</a>';
		$fieldimage .='<input type="hidden" name="'.$names.'" value="'.$values.'" id="input-'.$element.'" />';
		return $fieldimage;
	}
	
	public function field_devices($nameconfig,$element,$element_array,$column_array=null){
		$config = $this-> config;
		
		$fielddevices ='<ul class="nav nav-tabs" role="tablist">';
			foreach ($element_array as $subfix => $device):
			$navActive = ($subfix == 'lg') ? 'active' : '';
			$fielddevices .='<li class="'.$navActive.'"><a href="#language-'.$element.$subfix.'" role="tab" data-toggle="tab">'.$device.'</a></li>';
			endforeach;
		$fielddevices .='</ul>';
		
		$fielddevices .='<div class="tab-content">';
			foreach ($element_array as $subfix => $device):
			$navActive = ($subfix == 'lg') ? 'active' : '';
			$names	= $nameconfig.'['.$element.$subfix.']';
			$values	= isset($config[$nameconfig][$element.$subfix]) ? $config[$nameconfig][$element.$subfix]: '';
		
			$fielddevices .='<div class="tab-pane '.$navActive.'" id="language-'.$element.$subfix.'">';
			$fielddevices .='<select name="'.$names.'"  class="form-control width30">';
				foreach ($column_array as $fv => $fc):
					$current = ($fv == $values ) ?  'selected' : '';
					$fielddevices .=' <option value="'.$fv.'" '.$current.' >'.$fc.'</option>';
				
				endforeach;
			$fielddevices .='</select>';
			$fielddevices .='</div>';
			endforeach;
		$fielddevices .='</div>';
		$fielddevices .='<span class="help-block">(set number columns of devices)</span>';
		
		
		return $fielddevices;
	}
	
	
	function field_select($nameconfig,$element,$element_array,$className=null){
		$config = $this-> config;
		$names	= $nameconfig.'['.$element.']';
		$values	= isset($config[$nameconfig][$element]) ? $config[$nameconfig][$element] : '';
		
		$fieldSelect  = '<select name="'.$names.'"  class="form-control '.$className.'">';
		foreach ($element_array as $fv => $fc){
			($fv == $values) ? $current = 'selected' : $current='';
			$fieldSelect .= '<option value="'.$fv.'" '.$current.' >'.$fc.'</option>	';
		}
		$fieldSelect .= '</select>';
		return $fieldSelect;
	}
	
	
	function field_langHori($languages,$nameconfig,$element,$size=null,$className=null){
		$config = $this-> config;
		$fieldLangHori   = '<div class="tab-horizontal '.$className.'">';
		$fieldLangHori  .= '<ul class="nav nav-tabs main_tabs_horizontal">';
		
		foreach ($languages as $language){
			$active = $language['language_id'] == 1 ? 'active' : ' ';
			$fieldLangHori .= '<li class="'.$active.'">
			<a href="#language-'.$element.$language['language_id'].'" data-toggle="tab"><img src="language/'.$language['code'].'/'.$language['code'].'.png" title="'.$language['name'].'" /> '.$language['name'].'</a>
			</li>';
		}
		$fieldLangHori .= '</ul>';
		$fieldLangHori .= '<div class="tab-content">';
		foreach ($languages as $language){
			$active = $language['language_id'] == 1 ? 'active' : ' ';
			$names	= $nameconfig.'['.$element.']['.$language['language_id'].']';
			$values	= isset($config[$nameconfig][$element][$language['language_id']])?$config[$nameconfig][$element][$language['language_id']]: '';
		
			$fieldLangHori .= '<div class="tab-pane '.$active.'" id="language-'.$element.$language['language_id'].'">';
			$fieldLangHori .= '<input type="text" name="'.$names.'" value="'.$values.'"  class="form-control" size="'.$size.'" />';
			$fieldLangHori .= '</div>';
		}
		$fieldLangHori .= '</div>';
		$fieldLangHori .= '</div>';
		
		return $fieldLangHori;
	}

	function field_langAddMenu($languages,$nameconfig,$element,$valuemenu,$menu_row,$name=null){
		$config = $this-> config;
		$fieldLangHori   = '<div class="tab-horizontal">';
		$fieldLangHori  .= '<ul class="nav nav-tabs main_tabs_horizontal">';
		
		foreach ($languages as $language){
			$active = $language['language_id'] == 1 ? 'active' : ' ';
			$fieldLangHori .= '<li class="'.$active.'">
			<a href="#language-'.$name.$menu_row.$language['language_id'].'" data-toggle="tab"><img src="language/'.$language['code'].'/'.$language['code'].'.png" title="'.$language['name'].'" /> '.$language['name'].'</a>
			</li>';
		}
		$fieldLangHori .= '</ul>';
		$fieldLangHori .= '<div class="tab-content">';
		foreach ($languages as $language){
			$active = $language['language_id'] == 1 ? 'active' : ' ';
			$names	= $nameconfig.'['.$element.']['.$menu_row.']['.$name.']['.$language['language_id'].']';
			
			$values	= isset($valuemenu[$name][$language['language_id']])?$valuemenu[$name][$language['language_id']]: '';
		
			$fieldLangHori .= '<div class="tab-pane '.$active.'" id="language-'.$name.$menu_row.$language['language_id'].'">';
			$fieldLangHori .= '<input type="text" name="'.$names.'" value="'.$values.'"  class="form-control" size="45" />';
			$fieldLangHori .= '</div>';
		}
		$fieldLangHori .= '</div>';
		$fieldLangHori .= '</div>';
		
		return $fieldLangHori;
	}
	
	function field_langTextarea($languages ,$nameconfig,$element){
		
		$config = $this->config;
		$fieldLangTextarea  = '<ul class="nav nav-tabs">';
		foreach ($languages as $language){
			$active = $language['language_id'] == 1 ? 'active' : ' ';
			
			$fieldLangTextarea .= '<li class="'.$active.'">
			<a href="#language-'.$element.$language['language_id'].'" data-toggle="tab"><img src="language/'.$language['code'].'/'.$language['code'].'.png" title="'.$language['name'].'" /> '.$language['name'].'</a>
			</li>';
		}
		$fieldLangTextarea .= '</ul>';
		$fieldLangTextarea .= '<div class="tab-content">';
		foreach ($languages as $language){
			$active = $language['language_id'] == 1 ? 'active' : ' ';
			$names	= $nameconfig.'['.$element.']['.$language['language_id'].']';
			$values	= isset($config[$nameconfig][$element][$language['language_id']])?$config[$nameconfig][$element][$language['language_id']]: '';
		
			$fieldLangTextarea .= '<div class="tab-pane '.$active.'" id="language-'.$element.$language['language_id'].'">';
			$fieldLangTextarea .='<textarea name="'.$names.'" data-toggle="summernote" id="input-description-'.$element.$language['language_id'].'">';
			$fieldLangTextarea .=  $values;
			$fieldLangTextarea .= '</textarea>';
			$fieldLangTextarea .= '</div>';
		}
		$fieldLangTextarea .= '</div>';
		
		
		return $fieldLangTextarea;
	}
	
	public function create_position($titlepositon=null,$namepositon=null,$layout_modules=null,$extensions=null,$module_row =null) {
		
		global $module_row;
		if($module_row == null ) $module_row = 0;
		$namepositon2 = str_replace('', '-', $namepositon);

		$create_position  = '<table id="module-'.$namepositon2.'" class="table table-striped table-bordered table-hover">';
		$create_position .= '<thead><tr><td class="text-center">'.$titlepositon.'</td></tr></thead> <tbody>';
		
		foreach ($layout_modules as $layout_module) { 
			if ($layout_module['position'] == $namepositon) {
				
				$create_position .= '<tr id="module-row'.$module_row.'"><td class="text-left"><div class="input-group">';
				$create_position .= '<select name="layout_module['.$module_row.'][code]" class="form-control input-sm select2-input">';
				
				foreach ($extensions as $extension) {
					$create_position .= '<optgroup label="'. $extension['name'].'">';
					if (!$extension['module']) {
						if ($extension['code'] == $layout_module['code']) {
							$create_position .= '<option value="'. $extension['code'] .'" selected="selected">'. $extension['name'].'</option>';
						}else{
							$create_position .= '<option value="'. $extension['code'] .'" >'. $extension['name'].'</option>';
						}
							
					}else{
						foreach ($extension['module'] as $module) { 
							if ($module['code'] == $layout_module['code']) { 
								$create_position .= '<option value="'.$module['code'].'" selected="selected">'.$module['name'].'</option>';
							}else{
								$create_position .= '<option value="'. $module['code'] .'">'. $module['name'].'</option>';
							}
						}
					}
					$create_position .= '</optgroup>';
				}
				
				$create_position .= '</select>';
				$create_position .= '<input type="hidden" name="layout_module['.$module_row.'][position]" value="'. $layout_module['position'].'" />';
				$create_position .= '<div class="input-group-btn">';
					$create_position .= '<input type="text" size="5" class="form-order input-sm" name="layout_module['. $module_row.'][sort_order]" value="'. $layout_module['sort_order'].'" />';
					$create_position .= '<a href="'. $layout_module['edit'] .'" type="button" data-toggle="tooltip" title="Edit" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>';
					$create_position .= '<button type="button" onclick=$("#module-row'.$module_row.'").remove(); data-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm"><i class="fa fa fa-minus-circle"></i></button>';
				$create_position .= ' </div>';
				$create_position .= '</div></td></tr>';
				
				$module_row++;
			}
		}
		
		$create_position .= '</tbody>';
		$create_position .= '<tfoot><tr><td class="text-left"><div class="input-group">';
		$create_position .= ' <select class="form-control input-sm select2-input">';
		$create_position .= ' <option value=""></option>';
		foreach ($extensions as $extension) {
					$create_position .= '<optgroup label="'.$extension['name'] .'">';
			if (!$extension['module']) { 
					$create_position .= '<option value="'. $extension['code'] .'">'. $extension['name'].'</option>';
			}else{
				foreach ($extension['module'] as $module) { 
					$create_position .= '<option value="'. $module['code'] .'">'. $module['name'].'</option>';
				}
			}
		}
		$create_position .= ' </select>';
		$create_position .= ' <div class="input-group-btn">';
		$create_position .=	'<button type="button" onclick=addModule("'.$namepositon2.'") data-toggle="tooltip" title="Add Module" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button>';
		$create_position .=	'</div>';
		$create_position .=	'</div></td></tr> </tfoot>';
		$create_position .=	'</table>';
		
		return $create_position;
		
	}	
	
	public function home_position($titlepositon=null,$namepositon=null,$layout_modules=null,$extensions=null,$module_row =null,$homeLayouts =null) {
		
		global $module_row;
		if($module_row == null ) $module_row = 0;
		$namepositon2 = str_replace('', '-', $namepositon);

		$create_position  = '<table id="module-'.$namepositon2.'" class="table table-striped table-bordered table-hover">';
		$create_position .= '<thead><tr><td class="text-center">'.$titlepositon.'</td></tr></thead> <tbody>';
		
		foreach ($layout_modules as $layout_module) { 
			if ($layout_module['position'] == $namepositon) {
				
				$create_position .= '<tr id="module-row'.$module_row.'"><td class="text-left"><div class="input-group ">';
				$create_position .= '<select name="layout_module['.$module_row.'][code]" class="form-control input-sm select2-input">';
				
				foreach ($extensions as $extension) {
					$create_position .= '<optgroup label="'. $extension['name'].'">';
					if (!$extension['module']) {
						if ($extension['code'] == $layout_module['code']) {
							$create_position .= '<option value="'. $extension['code'] .'" selected="selected">'. $extension['name'].'</option>';
						}else{
							$create_position .= '<option value="'. $extension['code'] .'" >'. $extension['name'].'</option>';
						}
							
					}else{
						foreach ($extension['module'] as $module) { 
							if ($module['code'] == $layout_module['code']) { 
								$create_position .= '<option value="'.$module['code'].'" selected="selected">'.$module['name'].'</option>';
							}else{
								$create_position .= '<option value="'. $module['code'] .'">'. $module['name'].'</option>';
							}
						}
					}
					$create_position .= '</optgroup>';
				}
				$create_position .= '</select>';
				
				$create_position .= '<input type="hidden" name="layout_module['.$module_row.'][position]" value="'. $layout_module['position'].'" />';
				$create_position .= '<div class="input-group-btn"><h5 style="margin: 0 15px;">Home Layout</h5></div>';
				$create_position .= '<div class="input-group-btn" style="width: 250px;">';
					$create_position .= ' <select  name="layout_module['. $module_row.'][sort_order]"  class="form-control input-sm" style="width: 75%;">';
					foreach ($homeLayouts as $homeid =>$homelayout) {
						($homeid == $layout_module['sort_order']) ? $current = 'selected' : $current='';
						$create_position .= '<option value="'.$homeid.'" '.$current.' >'. $homelayout.'</option>';
					}
				$create_position .= ' </select>';
					//$create_position .= '<input type="text" size="5" class="form-order input-sm" name="layout_module['. $module_row.'][sort_order]" value="'. $layout_module['sort_order'].'" />';
					$create_position .= '<a href="'. $layout_module['edit'] .'" type="button" data-toggle="tooltip" title="Edit" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>';
					$create_position .= '<button type="button" onclick=$("#module-row'.$module_row.'").remove(); data-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm"><i class="fa fa fa-minus-circle"></i></button>';
				$create_position .= ' </div>';
				$create_position .= '</div></td></tr>';
				
				$module_row++;
			}
		}
		
		$create_position .= '</tbody>';
		$create_position .= '<tfoot><tr><td class="text-left"><div class="input-group">';
		$create_position .= ' <select class="form-control input-sm select2-input">';
		$create_position .= ' <option value=""></option>';
		foreach ($extensions as $extension) {
					$create_position .= '<optgroup label="'.$extension['name'] .'">';
			if (!$extension['module']) { 
					$create_position .= '<option value="'. $extension['code'] .'">'. $extension['name'].'</option>';
			}else{
				foreach ($extension['module'] as $module) { 
					$create_position .= '<option value="'. $module['code'] .'">'. $module['name'].'</option>';
				}
			}
		}
		$create_position .= ' </select>';
		$create_position .= ' <div class="input-group-btn">';
		$create_position .=	'<button type="button" onclick=addModule("'.$namepositon2.'") data-toggle="tooltip" title="Add Module" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button>';
		$create_position .=	'</div>';
		$create_position .=	'</div></td></tr> </tfoot>';
		$create_position .=	'</table>';
		
		return $create_position;
		
	}
	
	public function var_module_row() {
		global $module_row;
		$js_position = 'var module_row ='. $module_row;
		return $js_position;
	}

	public function resize($filename, $width, $height) {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return DIR_IMAGE . $image_old;
			}
 
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		return HTTP_CATALOG . 'image/' . $image_new;
	}
}
<?php
	global $registry;
	$registry = new Registry();
  	$registry->set('currency', new Currency($registry));
    require_once(DIR_SYSTEM . 'library/so_onepagecheckout/classes/so_utils.php');
    global $so_utils;
    $so_utils = new SoUtils();
    $this->registry->set('so_utils', $so_utils);

  <?php //echo $_GET['account_video'];
	require_once(DIR_SYSTEM . 'soconfig/classes/soconfig.php');
	if(isset($registry)){$soconfig = new Soconfig($registry);}

	var_dump($soconfig->get_settings('video_code'));die();
 ?>
 <iframe style="width: 430px; position:relative; height: 242px; margin: 0; border: 0; overflow:hidden;" >

 </iframe>
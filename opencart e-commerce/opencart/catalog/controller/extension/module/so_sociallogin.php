<?php
class ControllerExtensionModuleSoSociallogin extends Controller {
	public function index() {
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('so_sociallogin');
		if (isset($setting) && $setting['so_sociallogin_enable'] && $this->config->get('so_sociallogin_enable')) {
			$data['setting']	= $setting;

			$this->load->language('extension/module/so_sociallogin');
			$this->load->model('account/customer');
			
			$data['heading_title'] = $this->language->get('heading_title');
			if(isset($this->request->get['route']))
			{
				$this->session->data['route']=$this->request->get['route'];
			}
			$data['text_tax'] = $this->language->get('text_tax');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['text_title_login_with_social'] = $this->language->get('text_title_login_with_social');

			$this->load->model('catalog/product');

			$this->load->model('tool/image');
			$data['warning']='';
			if(isset($this->session->data['warning']))
			{
				$data['warning']=$this->session->data['warning'];
				unset($this->session->data['warning']);
			}
				
			/* Get Image */
            $sociallogin_width = 130;
            $sociallogin_height = 35;
            if (isset($setting['so_sociallogin_width']) && is_numeric($setting['so_sociallogin_width'])) {
                $sociallogin_width = $setting['so_sociallogin_width'];
            }
            if (isset($setting['so_sociallogin_height']) && is_numeric($setting['so_sociallogin_height'])) {
                $sociallogin_height = $setting['so_sociallogin_height'];
            }
            if ($setting['so_sociallogin_fbimage']) {
                $fbicon = $this->model_tool_image->resize($setting['so_sociallogin_fbimage'], $sociallogin_width, $sociallogin_height);
            } else {
                $fbicon = $this->model_tool_image->resize('placeholder.png', $sociallogin_width, $sociallogin_height);
            }
                
            if ($setting['so_sociallogin_twitimage']) {
                $twiticon = $this->model_tool_image->resize($setting['so_sociallogin_twitimage'], $sociallogin_width, $sociallogin_height);
            } else {
                $twiticon = $this->model_tool_image->resize('placeholder.png', $sociallogin_width, $sociallogin_height);
            }
                
            if ($setting['so_sociallogin_googleimage']) {
                $googleicon = $this->model_tool_image->resize($setting['so_sociallogin_googleimage'], $sociallogin_width, $sociallogin_height);
            } else {
                $googleicon = $this->model_tool_image->resize('placeholder.png', $sociallogin_width, $sociallogin_height);
            }
            
            if ($setting['so_sociallogin_linkdinimage']) {
                $linkdinicon = $this->model_tool_image->resize($setting['so_sociallogin_linkdinimage'], $sociallogin_width, $sociallogin_height);
            } else {
                $linkdinicon = $this->model_tool_image->resize('placeholder.png', $sociallogin_width, $sociallogin_height);
            }
								
			$data['iconwidth']  	= $sociallogin_width;
            $data['iconheight'] 	= $sociallogin_height;
			$data['fbimage']   		= $fbicon;
			$data['twitimage']  	= $twiticon;
			$data['googleimage'] 	= $googleicon;
			$data['linkdinimage'] 	= $linkdinicon;
			$data['fbstatus'] 	  	= $setting['so_sociallogin_fbstatus'];
			$data['twittertitle'] 	= $setting['so_sociallogin_twittertitle'];
			$data['googletitle']  	= $setting['so_sociallogin_googletitle'];
			$data['linkedintitle'] 	= $setting['so_sociallogin_linkedintitle'];
			$data['fbtitle']      	= $setting['so_sociallogin_fbtitle'];
			$data['twitstatus']    	= $setting['so_sociallogin_twitstatus'];
			$data['googlestatus']   = $setting['so_sociallogin_googlestatus'];
			$data['linkstatus']    	= $setting['so_sociallogin_linkstatus'];
					
			//Facebook Libery file inculde	
			require_once (DIR_SYSTEM.'library/so_social/Facebook/autoload.php');
			
			// Google Libery file inculde
			require_once DIR_SYSTEM.'library/so_social/src/Google_Client.php';
			require_once DIR_SYSTEM.'library/so_social/src/contrib/Google_Oauth2Service.php';
			
			//Facebook  Login link code
			$fb = new Facebook\Facebook ([
				'app_id' 					=> $setting['so_sociallogin_fbapikey'], 
				'app_secret' 				=> $setting['so_sociallogin_fbsecretapi'],
				'default_graph_version' 	=> 'v2.4',
			]);

            $helper = $fb->getRedirectLoginHelper();

			// try {
			//   	$accessToken = $helper->getAccessToken();
			// } catch(Facebook\Exceptions\FacebookResponseException $e) {
			//   	// When Graph returns an error
			//   	echo 'Graph returned an error: ' . $e->getMessage();
			//   	exit;
			// } catch(Facebook\Exceptions\FacebookSDKException $e) {
			//   	// When validation fails or other local issues
			//   	echo 'Facebook SDK returned an error: ' . $e->getMessage();
			//   	exit;
			// }		
			
			$data['fblink'] = $helper->getLoginUrl($this->url->link('extension/module/so_sociallogin/FacebookLogin', '', 'SSL'), array('public_profile','email'));
            /* Facebook  Login link code */
			
			
			/* Twitter Login */						
			$data['twitlink'] =  $this->url->link('extension/module/so_sociallogin/TwitterLogin', '', 'SSL');
				
			/* Linkedin Login */
			$data['linkdinlink'] = $this->url->link('extension/module/so_sociallogin/LinkedinLogin', '', 'SSL');
			 /* Linkedin Login */
			
			/* Google Login link code */
            $gClient = new Google_Client();
            $gClient->setApplicationName($setting['so_sociallogin_googletitle']);
            $gClient->setClientId($setting['so_sociallogin_googleapikey']);
            $gClient->setClientSecret($setting['so_sociallogin_googlesecretapi']);
            $gClient->setRedirectUri($this->url->link('extension/module/so_sociallogin/GoogleLogin', '', 'SSL'));
            $google_oauthV2 = new Google_Oauth2Service($gClient);
            $data['googlelink']  = $gClient->createAuthUrl();
			/* Google Login link code */
			
			if(!$this->customer->isLogged())
			{
				return $this->load->view('extension/module/so_sociallogin/default', $data);
		    }
		}
	}
	
	//facebook
	public function FacebookLogin() {
		if(!session_id()) {
		    session_start();
		}
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('so_sociallogin');
		
		if(isset($this->session->data['route']))
		{
			$location = $this->url->link($this->session->data['route'], "", 'SSL');
		}
		else
		{
			$location = $this->url->link("account/account", "", 'SSL');
		}
		
		if ($this->customer->isLogged())	
			$this->response->redirect($location);

		require_once (DIR_SYSTEM.'library/so_social/Facebook/autoload.php');
		 
		$fb = new Facebook\Facebook ([
			'app_id' 					=> $setting['so_sociallogin_fbapikey'], 
			'app_secret' 				=> $setting['so_sociallogin_fbsecretapi'],
			'default_graph_version' 	=> 'v2.4',
		]);

		$helper = $fb->getRedirectLoginHelper();

		if (isset($_GET['state'])) {
		    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$site_url = $this->config->get('config_ssl');
		} else {
			$site_url = $this->config->get('config_url');
		}

		try {
		  	$accessToken = $helper->getAccessToken($site_url.'index.php?route=extension/module/so_sociallogin/FacebookLogin');
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	echo 'Graph returned an error: ' . $e->getMessage();
		  	exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  	exit;
		}

		if (!isset($accessToken)) {
		  	if ($helper->getError()) {
		    	header('HTTP/1.0 401 Unauthorized');
		    	echo "Error: " . $helper->getError() . "\n";
		    	echo "Error Code: " . $helper->getErrorCode() . "\n";
		    	echo "Error Reason: " . $helper->getErrorReason() . "\n";
		    	echo "Error Description: " . $helper->getErrorDescription() . "\n";
		  	} else {
		    	header('HTTP/1.0 400 Bad Request');
		    	echo 'Bad request';
		  	}
		  	exit;
		}
		
		try {
		  	$fields = array('id', 'name', 'email', 'first_name', 'last_name');
		  	$response = $fb->get('/me?fields='.implode(',', $fields).'', $accessToken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	echo 'Graph returned an error: ' . $e->getMessage();
		  	exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  	exit;
		}

		$user = $response->getGraphUser();

		if($user['id'] && $user['email']) {
			$this->load->model('account/customer');
	
			$email = $user['email'];
			
			$customer_info = $this->model_account_customer->getCustomerByEmail($email);
			
			if (!empty($customer_info)){
				if ($customer_info && !$customer_info['status']) {
					$this->session->data['warning'] = 'Customer not Approved';
				}
				else
				{
					if ($this->customer->login($email, '', true)){
						$this->response->redirect($location);
					}
				}				
			}			
	 		else{
	 			$customer_group_id = $this->config->get('config_customer_group_id');
	 			$this->load->model('account/customer_group');
	 			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
	 			$data = array();
	 			$data['email'] 		= $user['email'];
	 			$data['firstname'] 	= $user['first_name'];
	 			$data['lastname'] 	= $user['last_name'];
	 			$data['telephone'] = '';
	 			$sql = "INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW(), code = '".$this->db->escape(utf8_strtolower($user['email']))."'";
	 			$this->db->query($sql);
				$customer_id = $this->db->getLastId();
				if ($customer_group_info['approval']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = NOW()");
				}

				if ($this->customer->login($email, '', true)){
					$this->response->redirect($location);
				}
			}
		}
		else{			
			$this->session->data['warning'] = 'Please Verify facebook App';
		}
		$location=	$this->url->link("account/login", "", 'SSL');
		
		$this->response->redirect($location);
		
	}
	
	// google
	public function GoogleLogin() {
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('so_sociallogin');
		
		if(isset($this->session->data['route']))
		{
			$location = $this->url->link($this->session->data['route'], "", 'SSL');
		}
		else
		{
			$location = $this->url->link("account/account", "", 'SSL');
		}

		// Google Libery file inculde
		require_once DIR_SYSTEM.'library/so_social/src/Google_Client.php';
		require_once DIR_SYSTEM.'library/so_social/src/contrib/Google_Oauth2Service.php';
		
		/* Google Login link code */
		$gClient = new Google_Client();
		$gClient->setApplicationName($setting['so_sociallogin_googletitle']);
		$gClient->setClientId($setting['so_sociallogin_googleapikey']);
		$gClient->setClientSecret($setting['so_sociallogin_googlesecretapi']);
		$gClient->setRedirectUri($this->url->link('extension/module/so_sociallogin/GoogleLogin', '', 'SSL'));
		$google_oauthV2 = new Google_Oauth2Service($gClient);
		/* Google Login link code */
		
		if(isset($this->request->get['code'])){
			$gClient->authenticate();
			$this->session->data['googletoken'] = $gClient->getAccessToken();
		}
			
		if (isset($this->session->data['googletoken'])) {
			$gClient->setAccessToken($this->session->data['googletoken']);
		}
		
		if ($gClient->getAccessToken()) {
			$userProfile = $google_oauthV2->userinfo->get();
			$this->session->data['googletoken'] = $gClient->getAccessToken();
						
			$this->load->model('account/customer');
	
			$email = $userProfile['email'];
			
			$customer_info = $this->model_account_customer->getCustomerByEmail($email);
			
			if (!empty($customer_info)){				
				if ($customer_info && !$customer_info['status']) {
					$this->session->data['warning'] = 'Customer not Approved';
				}
				else
				{
					if($this->customer->login($email, '', true)){
						$this->response->redirect($location);
					}
				}				
			}else{	
				$customer_group_id = $this->config->get('config_customer_group_id');
	 			$this->load->model('account/customer_group');
	 			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
	 			$data = array();
	 			$names	= explode(' ',$userProfile['name']);
	 			$data['email'] 		= $userProfile['email'];
	 			$data['firstname'] 	= isset($names[0]) ? $names[0] : '';
	 			$data['lastname'] 	= isset($names[1]) ? $names[1] : '';
	 			$data['telephone'] = '';
	 			$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW(), code = '".$this->db->escape(utf8_strtolower($userProfile['email']))."'");
	 			$this->db->query($sql);
				$customer_id = $this->db->getLastId();
				if ($customer_group_info['approval']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = NOW()");
				}

				if ($this->customer->login($email, '', true)){
					$this->response->redirect($location);
				}
			}
		}
		else {
			die("error access token !");
		}
	}
	
	public function TwitterLogin() {
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('so_sociallogin');		
		
		$twitapikey = $setting['so_sociallogin_twitapikey'];
		$twitsecretapi = $setting['so_sociallogin_twitsecretapi'];
		require_once DIR_SYSTEM.'library/so_social/twitter/twitteroauth.php';
		
		//Fresh authentication
		$connection = new TwitterOAuth($twitapikey, $twitsecretapi);
		$request_token = $connection->getRequestToken($this->url->link('extension/module/so_sociallogin/TwitterToken', '', 'SSL'));

		if (isset($request_token['oauth_token']) && isset($request_token['oauth_token_secret'])) {
			//Received token info from twitter
			$this->session->data['oauth_token'] 		= $request_token['oauth_token'];
			$this->session->data['oauth_token_secret'] 	= $request_token['oauth_token_secret'];

			//Any value other than 200 is failure, so continue only if http code is 200
			if($connection->http_code == '200')
			{
				//redirect user to twitter
				$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
				header('Location: ' . $twitter_url); 
			}else{
				die("error connecting to twitter! try again later!");
			}
		}
		else {
			die('Could not authenticate you.');
		}
	}
	
	public function TwitterToken() {
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('so_sociallogin');

		if(isset($this->session->data['route']))
		{
			$location = $this->url->link($this->session->data['route'], "", 'SSL');
		}
		else
		{
			$location = $this->url->link("account/account", "", 'SSL');
		}
		
		require_once DIR_SYSTEM.'library/so_social/twitter/twitteroauth.php';		
		
		if (!empty($this->request->get['oauth_verifier']) && !empty($this->session->data['oauth_token']) && !empty($this->session->data['oauth_token_secret'])) {
			$twitteroauth = new TwitterOAuth($setting['so_sociallogin_twitapikey'], $setting['so_sociallogin_twitsecretapi'], $this->session->data['oauth_token'], $this->session->data['oauth_token_secret']);
			$access_token = $twitteroauth->getAccessToken($this->request->get['oauth_verifier']);
			$this->session->data['access_token'] = $access_token;
			$user_info = $twitteroauth->get('account/verify_credentials', array('include_email' => 'true'));			
			
			if (isset($user_info->error)) {
			
			} else {
				$twiter_id = $user_info->id;
				$name = $user_info->name;
				
				$name_arr 	= explode(" ", $name);
				$f_name 	= array_shift($name_arr);
				$l_name 	= implode(" ", $name_arr);
				
				if(isset($user_info->email))
				{
					$email = $user_info->email;
					$this->response->redirect($this->url->link("account/login", "", 'SSL'));
				}
				else
				{
					$this->session->data['warning'] = 'Need Special varification for twitter';
					$this->response->redirect($this->url->link("account/login", "", 'SSL'));
				}
				
				$redirect = $this->url->link("account/account", "", 'SSL');
				
				$this->load->model('account/customer');
				if($this->customer->login($email, '', true)){
					$this->response->redirect($location);
				}
				
				$customer_info = $this->model_account_customer->getCustomerByEmail($email);
				if(isset($customer_info)){				
					if ($customer_info && !$customer_info['status']) {
						$this->session->data['warning'] = 'Customer not Approved';
					}
				} else{
					$customer_group_id = $this->config->get('config_customer_group_id');
		 			$this->load->model('account/customer_group');
		 			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		 			$data = array();
		 			$data['email'] 		= $user_info->email;
		 			$data['firstname'] 	= isset($user_info->first_name) ? $user_info->first_name : $user_info->name;
		 			$data['lastname'] 	= isset($user_info->last_name) ? $user_info->last_name : '';
		 			$data['telephone'] = '';
		 			$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW(), code = '".$this->db->escape(utf8_strtolower($user_info->email))."'");
		 			$this->db->query($sql);
					$customer_id = $this->db->getLastId();
					if ($customer_group_info['approval']) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = NOW()");
					}

					if ($this->customer->login($email, '', true)){
						$this->response->redirect($location);
					}
				}
			}
		} else {			
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}
	}
	 
	
	/* LinkedIn */
	public function LinkedinLogin() {
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('so_sociallogin');

		if(isset($this->session->data['route']))
		{
			$location = $this->url->link($this->session->data['route'], "", 'SSL');
		}
		else
		{
			$location = $this->url->link("account/account", "", 'SSL');
		}
		
		// linkdin Libery file inculde
		require_once DIR_SYSTEM.'library/so_social/linkedIn/http.php';
		require_once DIR_SYSTEM.'library/so_social/linkedIn/oauth_client.php';
		
		$client = new oauth_client_class;
		$client->debug = false;
		$client->debug_http = true;
		$client->redirect_uri = $this->url->link('extension/module/so_sociallogin/LinkedinLogin', '', 'SSL');
		$client->client_id = $setting['so_sociallogin_linkdinapikey'];
		$application_line = __LINE__;
		$client->client_secret = $setting['so_sociallogin_linkdinsecretapi'];
		if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
		die('Please go to LinkedIn Apps page https://www.linkedin.com/secure/developer?newapp= , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri.' Make sure you enable the '.
			'necessary permissions to execute the API calls your application needs.');
		$client->scope = 'r_basicprofile r_emailaddress';
		if (($success = $client->Initialize())) {
		  	if (($success = $client->Process())) {
				if (strlen($client->authorization_error)) {
			  		$client->error = $client->authorization_error;
			  		$success = false;
				} elseif (strlen($client->access_token)) {
			  		$success = $client->CallAPI(
							'http://api.linkedin.com/v1/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name)', 
							'GET', array(
								'format'=>'json'
							), array('FailOnAccessError'=>true), $user);
				}
		  	}
		   	$success = $client->Finalize($success);
		}
		
		if($success) {
			$this->load->model('account/customer');
			$email = $user->emailAddress;			
			$customer_info = $this->model_account_customer->getCustomerByEmail($email);
			
			if(!empty($customer_info)){
				if ($customer_info && !$customer_info['status']) {
					$this->session->data['warning'] = 'Customer not Approved';
				}
				else
				{
					if($this->customer->login($email, '', true)){
						$this->response->redirect($location);					
					}
				}
			}else {
				$customer_group_id = $this->config->get('config_customer_group_id');
	 			$this->load->model('account/customer_group');
	 			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
	 			$data = array();
	 			$data['email'] 		= $user->emailAddress;
	 			$data['firstname'] 	= isset($user->firstName) ? $user->firstName : '';
	 			$data['lastname'] 	= isset($user->lastName) ? $user->lastName : '';
	 			$data['telephone'] = '';
	 			$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW(), code = '".$this->db->escape(utf8_strtolower($user->emailAddress))."'");
	 			$this->db->query($sql);
				$customer_id = $this->db->getLastId();
				if ($customer_group_info['approval']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = NOW()");
				}

				if ($this->customer->login($user->emailAddress, '', true)){
					$this->response->redirect($location);
				}
			}
		}
	}

	private function clean_decode($server)
	{
		return $server;
	}	
}

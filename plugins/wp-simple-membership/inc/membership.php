<?php 
  class BcMembership {
	  
	public $reg_errors;
	  
	function __construct() {
		
		add_filter('widget_text', 'do_shortcode');
		
		add_action('init', array($this,'validate'));
		
		add_action('init', array($this,'create_user'));
		
		add_action('init', array($this,'update_profile'));
		
		add_action('wp_login_failed', array($this, 'login_failed'), 10, 3 );
		
		add_shortcode('bc_login_form', array($this, 'bc_login_form') );
		
		add_shortcode('bc_registration_form', array($this, 'registeration_form') );
		
		add_shortcode('bc_my_profile', array($this, 'profile_form') );
		
		add_action('bc_after_registration', array($this, 'registration_notification'));
		
		add_action('wp_logout', array($this, 'bc_logout_redirect'), 30);
		
		$this->settings = get_option('bc_settings');
		
	}
	
	function bc_login_form( $args ) {
		
		ob_start();
		
		if ( is_wp_error( $this->reg_errors ) ) {
			
			$form_errors = $this->reg_errors;
			
		}else {
		
			 $form_errors = new WP_Error;		
		}
		$redirect = $redirect = get_the_permalink($this->settings['redirect_login']);
		if(is_array($args)) {
			extract($args);
		}
		
		if(isset($_GET['action']) && $_GET['action'] == 'lostpassword' ) {
		
			include BC_PLUGINPATH."/templates/lost-password.php";
			
		} else  {
		   
			include BC_PLUGINPATH."/templates/login-form.php";
			 do_action('login_enqueue_scripts');
		
		}
		
		return ob_get_clean();
		
	}
	
	public function validate() {
	
		if(false != bc_post('bc_submit_register') || false != bc_post('bc_update_profile')) {
		
			$this->validate_reg_form();
		}
		if(false != bc_post('bc_lostpasword-submit')) {
		
			$this->validate_lostpassword_form();
		}

	}
	
	public function validate_reg_form() {
		
		$this->reg_errors = new WP_Error;
		
		$current_user = wp_get_current_user();
		
		if(isset($_POST['bc_update_profile'])) {
		
			$username = true;
		
		} else {
		
			$username = bc_post('username');
		
		}
		
		$password = bc_post('password');
		
		
		$email = bc_post('email');
		
		if ( false === $username && false === $password && false === $email ) {
		
			$this->reg_errors->add('all_required_error', 'Required form field is missing');
			
			return;
			
		}
		
		
		if($username !== true) {
		
			if ( 4 > strlen( $username ) ) 
				$this->reg_errors->add( 'username', __('Username too short. At least 4 characters is required','bc-domain') );
			
			if ( ! validate_username( $username ) ) 
				$this->reg_errors->add( 'username', __('Sorry, the username you entered is not valid','bc-domain') );
			
			if ( username_exists( $username ) )
				$this->reg_errors->add('username', __('Sorry, that username already exists!','bc-domain'));	
			
		}	
			
		if ( !is_email( $email ) ) 
			$this->reg_errors->add( 'email', __('Email is not valid','bc-domain') );
		
		if(false != bc_post('bc_update_profile')) {	
			
				 $em_ex = email_exists( $email );
				 
				if ( $em_ex && $em_ex != $current_user->ID    )	
					$this->reg_errors->add( 'email', __('Email Already in use','bc-domain') );
		} else {
				 
				 if ( email_exists( $email ) )	
					$this->reg_errors->add( 'email', __('Email Already in use','bc-domain') );
					
				//if ( username_exists( $username ) )
				//	$this->reg_errors->add('username', __('Sorry, that username already exists!','bc-domain'));	
			
			}
			
		$conf_password = bc_post('conf_password');
		if($password !='') :
			if(false != $conf_password) {
				
				if ( 1 < strlen( $password ) && 5 > strlen( $password ) ) { 
				
					$this->reg_errors->add( 'password', __('Password length must be greater than 5','bc-domain') ); 
					
				} else if ( $conf_password !== $password ) {
				
					$this->reg_errors->add( 'conf_password', __('Password and confirm Password Must match.','bc-domain') ); 
					
				}
			
			} else {
			
				if ( 4 > strlen( $password ) ) 
					$this->reg_errors->add( 'password', __('Password is too short.','bc-domain') );
			
			}
		endif;
		do_action("validate_custom_registration_fields");	
		
	}
	
	public function registeration_form($args) {
	
		ob_start();
		
		if ( is_wp_error( $this->reg_errors ) ) {
			
			$form_errors = $this->reg_errors;
			
		}else {
		
			 $form_errors = new WP_Error;
		
		}
		$redirect = get_the_permalink($this->settings['redirect_register']);
		
		if(is_array($args)) {
			
			extract($args);
			
		}
		
		include BC_PLUGINPATH."/templates/register-form.php";
		
		return ob_get_clean();
		
	}
	
	 public function create_user() {
   	
	 if ( is_wp_error( $this->reg_errors ) && empty($this->reg_errors->errors ) && false != bc_post('bc_submit_register') ) { 
   	 	
		$data['user_login'] = bc_post('username');
		
		$data['user_email'] = bc_post('email');
		
		$user_pass = bc_post('password');
		
		$data['user_url'] = bc_post('website');
		
		$data['first_name'] = bc_post('fname');
		
		$data['last_name'] = bc_post('lname');
		
		$data['nickname'] = bc_post('nickname');
		
		$data['role']  =  'subscriber';
			
		$data['description'] = bc_post('bio');
		
		$userID = wp_create_user($data['user_login'], $user_pass, $data['user_email']);
		
		update_user_meta( $userID, 'show_admin_bar_front', false);
		
		if ( !is_wp_error($userID) ) {
		
				unset($data['user_login']); 
				
				unset($data['user_email']); 
				
				unset($data['user_pass']); 
				
				$data['ID'] = $userID;
				
				$data['role'] = "subscriber";
				
				wp_update_user( $data );

				$user = get_user_by( 'id', $userID );
				
				update_user_meta( $userID, 'show_admin_bar_front', false);
				
				do_action("bc_add_user_custom_meta", $user);
				
				do_action("bc_after_registration", array('user_id'=>$userID, 'user_pass'=>$user_pass));
				
				
				if(isset($_POST['redirect_to']) && $_POST['redirect_to'] != '') {
					
					wp_redirect($_POST['redirect_to']); 
					
					exit;
				}
				
				wp_redirect($_SERVER['HTTP_REFERER'].'?rg=1');
				
				exit;
				
		}
		
	 	wp_redirect( $_SERVER['HTTP_REFERER'] );
				
		exit;
		
	 }
   
   }
   
   public function profile_form() {
		
		ob_start();
		
		if ( is_wp_error( $this->reg_errors ) ) { 
			
			$form_errors = $this->reg_errors;
			
		}else {
		
			 $form_errors = new WP_Error;
		
		}
		
		$current_user = wp_get_current_user();
		
		include BC_PLUGINPATH."/templates/profile-form.php";
		
		return ob_get_clean();
		
	}
	
   public function update_profile() {
   	
	 if ( is_wp_error( $this->reg_errors ) && empty($this->reg_errors->errors ) && false != bc_post('bc_update_profile') ) { 
		
		$data['user_email'] = bc_post('email');
		
		$user_pass = bc_post('password');
		
		if($user_pass > 5 ) { 
		
			$data['user_pass'] = $user_pass;
		
		}
		
		$data['user_url'] = bc_post('website');
		
		$data['first_name'] = bc_post('fname');
		
		$data['last_name'] = bc_post('lname');
		
		$data['nickname'] = bc_post('nickname');
			
		$data['description'] = bc_post('bio');
		
		$current_user = wp_get_current_user();
		
		$data['ID'] = $current_user->ID;
		
		$userID = wp_update_user($data);
		
		if ( !is_wp_error($userID) ) {
				
				$data['ID'] = $userID;
				
				do_action("bc_update_user_custom_meta", $current_user);
				
				wp_redirect(reset(explode('?', $_SERVER['HTTP_REFERER']))."?updated_profile");
				
				exit;	
		} 
			
	 	wp_redirect( $_SERVER['HTTP_REFERER'] );
				
		exit;
		
	 }
   }
	public function login_failed() {
		
		$referrer = $_SERVER['HTTP_REFERER'];
		
		wp_redirect($referrer."/?login=invalid");
			
		exit;
  }
  
  function registration_notification($uid_user_pass) { 
	$default = array('user_id'=>0, 'user_pass'=>'');
	$info =  array_merge($default, $uid_user_pass);
	if($this->settings['reg_notification'] == 'none') {
		return;
	}
	if($this->settings['reg_notification'] == 'admin' || $this->settings['reg_notification'] == 'both') {
		wp_new_user_notification( $info['user_id'], '', 'admin' );
	}
	if($this->settings['reg_notification'] == 'user' || $this->settings['reg_notification'] == 'both') {
		if($info['user_id'] > 0) {
			$user_info = get_userdata($info['user_id']);
			$subject = __("Your registration was successful","bc-domain");
			$body = "<p>".__("Dear","bc-domain")." ".$user_info->first_name."</p>";
			$body .= "<p>".__("Congratulations, your registration is ","bc-domain").get_bloginfo().' '.__("successful","bc-domain").".</p>";
			$body .= "<p>".__("Username","bc-domain").' : '.$user_info->user_login ."</p>";
			if($info['user_pass'] !='') {
				$body .= "<p>".__("Password","bc-domain").' : '.$info['user_pass']."</p>";
			}
			$body .= "<p>".__("Email address","bc-domain").' : '.$user_info->user_email."</p>";
			$body .= "<p>".__("Thank you for your registration ","bc-domain").$user_info->first_name."</p>";
			$from = get_option('admin_email');
			$from_name = get_bloginfo();
			//$headers[] = 'From: '.$from_name.' <'.$from.'>';
			add_filter( 'wp_mail_content_type', function( $content_type ) {
				return 'text/html';
			});
		
		  @wp_mail( $user_info->user_email, $subject, $body, $headers);
		 }
	  }
   }
  
 
  public function bc_logout_redirect($redirect) {
   			
  		wp_redirect(site_url());
	  	
		exit;
  }
	
  }
  
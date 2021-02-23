<?php

 function bc_current_url($query='') {
  
  	global $wp;
	
	$current_url = home_url(add_query_arg(array(),$wp->request));
	
	return $current_url."/".$query;
	
}
  
function bc_create_form_field($arg) {
	$defaults = array(
	   'name'              => '',
	   'id'              => '',
	   'value'             => '',
	   'error_msg'         => false,
	   'type'              => 'text',
	   'label'             => '',
	   'placeholder'       => '',
	   'class'             => array(),
	   'label_class'       => array(),
	   'input_class'       => array(),
	   'maxlength'		   => false,
	   'echo'              => false,
	   'options'           => array(),
	   'custom_attributes' => array(),
	   'default'           => '',
	 );
	$args = wp_parse_args( $arg, $defaults ); 
	$args = apply_filters( 'bc_form_field_args', $args );
	extract($args);
	$custom_attr = array();
    if ( ! empty( $custom_attributes ) && is_array( $custom_attributes ) ) {
         foreach ( $custom_attributes as $attribute => $attribute_value ) {
            $custom_attr[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
         }
    }
	$maxlength = ( $maxlength ) ? 'maxlength="' . absint( $maxlength ) . '"' : '';
	$html = '<div class="form-row form-row-'.esc_attr($name).' '.esc_attr(implode(' ', $class)).' ">';
	if($label !='') {
		if($type == 'radio' || $type == 'checkbox') {
			$html .= '<label class="'.esc_attr(implode(' ', $label_class)).'">'.esc_attr($label).' </label>';
		} else {
			$html .= '<label for="" class="'.esc_attr(implode(' ', $label_class)).'">'.esc_attr($label).' </label>';
		}
	}
	switch ( $type ) {
		
		case 'text' :
		case 'number' :
		case 'password' :
			$html .= '<input type="'.esc_attr($type).'" value="'.esc_attr($value).'" name="'.esc_attr($name).'" placeholder="'.esc_attr($placeholder).'" ';
			$html .= ' class="'.esc_attr(implode(' ', $input_class)).'" '.$maxlength.' '; 
			$html .= ' '.esc_attr(implode( ' ', $custom_attr )).' ';
			$html .= ' id="'.esc_attr( $id ).'" />';
			if($error_msg != '') {
				$html .= '<div class="error-msg error-msg-'.esc_attr($name).'">'.$error_msg.'</div>';
			}
			
		break;
		
		case 'textarea':
			$html .= '<textarea name="'.esc_attr($name).'" placeholder="'.esc_attr($placeholder).'" class="'.esc_attr(implode(' ', $input_class)).'" ';
			$html .= ' '.esc_attr(implode( ' ', $custom_attr )).' '.$maxlength.' id="'.esc_attr( $id ).'" >';
			$html .= esc_attr($value);
			$html .= '</textarea>';
			if($error_msg != '') {
				$html .= '<div class="error-msg error-msg-'.esc_attr($name).'">'.$error_msg.'</div>';
			}
		break;
		
		case 'radio':
			if ( ! empty( $options ) ) {
                     foreach ( $options as $option_key => $option_text ) {
                       $html .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $input_class ) ) .'" value="' . esc_attr( $option_key ) . '" '; 
					   $html .= 'name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false );
					   $html .= ' '.esc_attr(implode( ' ', $custom_attr )).' />';
                       $html .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $label_class ) .'">'
					    . $option_text . '</label>';
                    }
					if($error_msg != '') {
						   $html .= '<div class="error-msg error-msg-'.esc_attr($name).'">'.$error_msg.'</div>';
					 }
                 }
		break;
		
		case 'checkbox': 
			if ( ! empty( $options ) ) { 
                     foreach ( $options as $option_key => $option_text ) {
                       $html .= '<input type="checkbox" class="input-checkbox ' . esc_attr( implode( ' ', $input_class ) ) .'" value="' . esc_attr( $option_key ) . '" '; 
					   $html .= 'name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false );
					   $html .= ' '.esc_attr(implode( ' ', $custom_attr )).' />';
                       $html .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="checkbox ' . implode( ' ', $label_class ) .'">' . $option_text . '</label>';
					   
                    }
					if($error_msg != '') {
							$html .= '<div class="error-msg error-msg-'.esc_attr($name).'">'.$error_msg.'</div>';
					 }
                 }
		break;
		
		case 'select':
			$optionshtml = '';
			if ( ! empty( $options ) ) {
                     foreach ( $options as $option_key => $option_text ) {
                         if ( "" === $option_key ) {
                             if ( empty( $placeholder ) ) {
                                 $placeholder = $option_text ? $option_text : __( 'Choose an option', 'bc-domain' );
                             }
                         }
                        $optionshtml .= '<option value="' . esc_attr( $option_key ) . '" '. selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) .'</option>';
                    }
			}
		$html .= '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="input-select '.esc_attr( implode( ' ', $input_class ) ) .'" 
		' . implode( ' ', $custom_attr ) . ' placeholder="' . esc_attr( $placeholder ) . '">
                           ' . $optionshtml . '
          </select>';
		  if($error_msg != '') {
				$html .= '<div class="error-msg error-msg-'.esc_attr($name).'">'.$error_msg.'</div>';
		  }
		break;
		
	}
	$html .= '</div>';
	return $html;	
}

function register_form_fields($form_errors = false) {
	
	$fieldsArray = array(); 
	
	 $fieldsArray['fname'] = array(
	 					'name' 			=> 'fname',
						'value'			=> bc_post('fname'),
						'type'			=> 'text',
						'placeholder'	=> __('First Name','bc-domain'),
						'label'	=> __('First Name','bc-domain')
						);
	$fieldsArray['lname'] = array(
	 					'name' 			=> 'lname',
						'value'			=> bc_post('lname'),
						'type'			=> 'text',
						'placeholder'	=> __('Last Name','bc-domain'),
						'label'	=> __('Last Name','bc-domain')
						);
	$fieldsArray['username'] = array(
	 					'name' 			=> 'username',
						'value'			=> bc_post('username'),
						'type'			=> 'text',
						'placeholder'	=> __('User Name','bc-domain'),
						'label'			=> __('User Name','bc-domain'),
						'error_msg'		=> $form_errors->get_error_message('username')
						);
	
	$fieldsArray['email'] = array(
	 					'name' 			=> 'email',
						'value'			=> bc_post('email'),
						'type'			=> 'text',
						'placeholder'	=> __('Email','bc-domain'),
						'label'			=> __('Email','bc-domain'),
						'error_msg'		=> $form_errors->get_error_message('email')
						);
	$fieldsArray['password'] = array(
	 					'name' 			=> 'password',
						'value'			=> bc_post('password'),
						'type'			=> 'password',
						'placeholder'	=> __('Password','bc-domain'),
						'label'			=> __('Password','bc-domain'),
						'error_msg'		=> $form_errors->get_error_message('password')
						);
	$fieldsArray['conf_password'] = array(
	 					'name' 			=> 'conf_password',
						'value'			=> bc_post('conf_password'),
						'type'			=> 'password',
						'placeholder'	=> __('Confirm Password','bc-domain'),
						'label'			=> __('Confirm Password','bc-domain'),
						'error_msg'		=> $form_errors->get_error_message('conf_password')
						);
	/*$fieldsArray['website'] = array(
	 					'name' 			=> 'website',
						'value'			=> bc_post('website'),
						'type'			=> 'text',
						'placeholder'	=> __('Website','bc-domain'),
						'label'			=> __('Website','bc-domain')
						);*/
	
	$fieldsArray['bio'] = array(
	 					'name' 			=> 'bio',
						'value'			=>  bc_post('bio'),
						'type'			=> 'textarea',
						'placeholder'	=> __('Bio','bc-domain'),
						'label'			=> __('Bio','bc-domain')
						);

	$fieldsArray = apply_filters("register_form_fields", $fieldsArray);
	
	return $fieldsArray;

}


function bc_textdomain() {
 
   load_plugin_textdomain( 'bc-domain', '', BC_PLUGIN_BASENAME.'/languages' ); 
   
 }
 
function bc_default_settings() {
	$bc_settings = get_option('bc_settings');
	if($bc_settings == false || $bc_settings == '') {
		  $default = array(
			'redirect_login' => '',
			'redirect_register' => '',
			'reg_notification' => 'user'
		 );
		 update_option('bc_settings',$default);
	}
}

function bc_post($name, $default='') {
	if(isset($_POST[$name])){
		return $_POST[$name];
	}else {
		return $default;
	}
}
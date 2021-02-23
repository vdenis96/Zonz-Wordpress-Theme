<?php

//********************************//
// AMP CTA Form Screen Start here //
//********************************//

    // Remove wysiwyg Editor from Forms Post type
    add_action('admin_head', 'ampforwp_cta_init_remove_support');
    function ampforwp_cta_init_remove_support(){
        $post_type = get_post_type();
        if ( $post_type === 'amp-cta' ) {
            remove_action( 'media_buttons', 'media_buttons' );
        }
    }

    add_filter( 'user_can_richedit', 'ampforwp_cta_disable_wysiwyg_for_amp_form' );
    function ampforwp_cta_disable_wysiwyg_for_amp_form( $default ) {
    	global $post;
    	if ( 'amp-cta' == get_post_type( $post ) ){
    		return false;
    	}
    	return $default;
    }

//******************************//
// AMP CTA Form Screen End here //
//******************************//



//*******************************//
// AMP CTA Meta Boxes Start here //
//*******************************//

  //*************************************************************************************************************************

      add_action( 'add_meta_boxes', 'ampforwp_cta_add_img_name_metabox' );

      function ampforwp_cta_add_img_name_metabox() {
        add_meta_box('ampforwp_cta_add_img_name_metabox', 'Image Icon', 'ampforwp_cta_add_img_icon_metabox', 'amp-cta', 'normal', 'default');
      }

      function ampforwp_cta_add_img_icon_metabox() {
        global $post;

        // Get the location data if its already been entered
        $ampforwp_cta_img_chck = get_post_meta($post->ID, '_ampforwp_cta_img_chck', true);
        $ampforwp_cta_img_icon = get_post_meta($post->ID, '_ampforwp_cta_img_icon', true);
        $cta_img_chck = ($ampforwp_cta_img_chck!='')? '':'hidden';
        $cta_hidden = ($ampforwp_cta_img_icon!='')? '':'hidden';

        echo  '<table><tbody>';
        echo  '<tr><td><label for="_ampforwp_cta_img_chck" class="button_checkbox_label" style="font-style:italic; margin-right: 15px;">Show Image in the CTA Box</label><input type="checkbox" name="_ampforwp_cta_img_chck" id="toggle_cta_icon" value="yes" '. ( $ampforwp_cta_img_chck == 'yes' ? ' checked ' : '' ) .' ><p></p></td></tr>';
        echo '<tr><td><div class="amp_cta_img_wrapper" '.$cta_img_chck.'><input type="text" name="_ampforwp_cta_img_icon" class="regular-text ampforwp_cta_img_icon" placeholder="Add Image Url" value="'.$ampforwp_cta_img_icon.'">&nbsp;&nbsp;<input id="upload_image_button" type="button" class="button button-primary set_cta_icon" value="Upload Icon"><p class="image-preview-wrapper"><img name="ampforwp_cta_img_wrp" id="image-preview" src="'.$ampforwp_cta_img_icon.'" alt="No Icon" width="75" height="75" style="max-height: 75px; width: auto;"></p><input type="hidden" name="image_attachment_id" id="image_attachment_id" value=""><p class="remove_media '.$cta_hidden.'"><button class="button button-info delete_custom_img" type="button" name="remove_media" value="image">Remove</button><div class="cta-desc-icon">Please add thumbnail or logo size Image</div></div></tr></td>';
        echo '</tbody></table>';
      }

  //*************************************************************************************************************************
        add_action( 'add_meta_boxes', 'ampforwp_cta_add_btn_name_metabox' );

        function ampforwp_cta_add_btn_name_metabox() {
        	add_meta_box('ampforwp_cta_btn_name_metabox', 'Button Title', 'ampforwp_cta_btn_name_metabox_callback', 'amp-cta', 'normal', 'default');
        }

        function ampforwp_cta_btn_name_metabox_callback() {
        	global $post;

        	// Get the location data if its already been entered
        	$ampforwp_cta_btn_name = get_post_meta($post->ID, '_ampforwp_cta_btn_name', true);

          echo '<input type="text" name="_ampforwp_cta_btn_name" class="widefat" cols="20" rows="20" value="'. $ampforwp_cta_btn_name  . '">' ;
        }

    //*************************************************************************************************************************

        add_action( 'add_meta_boxes', 'ampforwp_cta_add_btn_url_metabox' );

        function ampforwp_cta_add_btn_url_metabox() {
        	add_meta_box('ampforwp_cta_btn_url_metabox', 'URL', 'ampforwp_cta_btn_url_metabox_callback', 'amp-cta', 'normal', 'default');
        }
        // The Event Location Metabox

        function ampforwp_cta_btn_url_metabox_callback() {
        	global $post;

        	// Get the location data if its already been entered
        	$ampforwp_cta_btn_url = get_post_meta($post->ID, '_ampforwp_cta_btn_url', true);
        	$ampforwp_cta_btn_url_target = get_post_meta($post->ID, '_ampforwp_cta_btn_url_target', true);

          echo '<input type="text" name="_ampforwp_cta_btn_url" class="widefat" cols="20" rows="20" value="'. $ampforwp_cta_btn_url  . '">' ;
          echo '<label for="_ampforwp_cta_btn_url_target" class="amp-checkbox-label">'. __('Do you want users to open URL in new tab ?','accelerated-mobile-pages') .'</label>';
          echo '<input type="checkbox" name="_ampforwp_cta_btn_url_target" class="widefat" cols="20" rows="20" value="yes" '. ( $ampforwp_cta_btn_url_target == 'yes' ? ' checked ' : '' ) .' >' ;
        }

    //*************************************************************************************************************************

      // add_action( 'add_meta_boxes', 'ampforwp_cta_add_checkbox_metabox' );
      //
      // function ampforwp_cta_add_checkbox_metabox() {
      //   add_meta_box('ampforwp_cta_content_checkbox_metabox', 'CTA position', 'ampforwp_cta_checkbox_metabox_callback', 'amp-cta', 'normal', 'default');
      // }
      // // The Event Location Metabox
      //
      // function ampforwp_cta_checkbox_metabox_callback() {
      //   global $post;
      //
      //   // Get the location data if its already been entered
      //   $ampforwp_cta_checkbox_content_bottom = get_post_meta($post->ID, '_ampforwp_cta_checkbox_content_bottom', true);
      //   $ampforwp_cta_checkbox_content_top = get_post_meta($post->ID, '_ampforwp_cta_checkbox_content_top', true);
      //
      //   <input type="checkbox" class="widefat" name="_ampforwp_cta_checkbox_content_top" value="yes" <?php if ( isset ( $ampforwp_cta_checkbox_content_top ) ) checked( $ampforwp_cta_checkbox_content_top, 'yes' );  />Show above content <br/>
      //
      //   <input type="checkbox" class="widefat" name="_ampforwp_cta_checkbox_content_bottom" value="yes" <?php if ( isset ( $ampforwp_cta_checkbox_content_bottom ) ) checked( $ampforwp_cta_checkbox_content_bottom, 'yes' );  />Show below content
      //
      // }

    //*************************************************************************************************************************

//        add_action( 'add_meta_boxes', 'ampforwp_cta_add_color_title_metabox' );
//        function ampforwp_cta_add_color_title_metabox() {
//        	add_meta_box('ampforwp_cta_color_title_metabox', 'Title Color', 'ampforwp_cta_add_color_title_metabox_callback', 'amp-cta', 'side');
//        }
//
//        function ampforwp_cta_add_color_title_metabox_callback() {
//        	global $post;
//
//        	$ampforwp_cta_color = get_post_meta($post->ID, '_ampforwp_cta_color_title', true);
//            // default value for color picker
//            if ( empty( $ampforwp_cta_color )) {
//                $ampforwp_cta_color = '#777';
//            }
//            echo '<input type="text"  name="_ampforwp_cta_color_title" class="widefat ampforwp-color-picker" value="' . $ampforwp_cta_color . '" />';
//
//        }
//
//
//    //*************************************************************************************************************************

//
//        add_action( 'add_meta_boxes', 'ampforwp_cta_add_color_background_metabox' );
//
//        function ampforwp_cta_add_color_background_metabox() {
//        	add_meta_box('ampforwp_cta_color_background_metabox', 'Background Color', 'ampforwp_cta_add_color_background_metabox_callback', 'amp-cta', 'side');
//        }
//
//        function ampforwp_cta_add_color_background_metabox_callback() {
//        	global $post;
//
//        	$ampforwp_cta_color = get_post_meta($post->ID, '_ampforwp_cta_color_background', true);            // default value for color picker
//            if ( empty( $ampforwp_cta_color )) {
//                $ampforwp_cta_color = '#dd0001';
//            }
//
//          echo '<input type="text"  name="_ampforwp_cta_color_background" class="widefat ampforwp-color-picker" value="' . $ampforwp_cta_color . '" />';
//
//        }


    //*************************************************************************************************************************

//
//        add_action( 'add_meta_boxes', 'ampforwp_cta_add_color_btn_txt_metabox' );
//
//        function ampforwp_cta_add_color_btn_txt_metabox() {
//        	add_meta_box('ampforwp_cta_color_btn_txt_metabox', 'Button Text Color', 'ampforwp_cta_add_color_btn_txt_metabox_callback', 'amp-cta', 'side');
//        }
//
//        function ampforwp_cta_add_color_btn_txt_metabox_callback() {
//        	global $post;
//
//        	$ampforwp_cta_color = get_post_meta($post->ID, '_ampforwp_cta_color_btn_txt', true);            // default value for color picker
//            if ( empty( $ampforwp_cta_color )) {
//                $ampforwp_cta_color = '#bada55';
//            }
//
//          echo '<input type="text"  name="_ampforwp_cta_color_btn_txt" class="widefat ampforwp-color-picker" value="' . $ampforwp_cta_color . '" />';
//
//        }
//
//
//    //*************************************************************************************************************************
//
//
//        add_action( 'add_meta_boxes', 'ampforwp_cta_add_color_btn_metabox' );
//
//        function ampforwp_cta_add_color_btn_metabox() {
//        	add_meta_box('ampforwp_cta_color_btn_metabox', 'Button Color', 'ampforwp_cta_add_color_btn_metabox_callback', 'amp-cta', 'side');
//        }
//
//        function ampforwp_cta_add_color_btn_metabox_callback() {
//        	global $post;
//
//        	$ampforwp_cta_color = get_post_meta($post->ID, '_ampforwp_cta_color_btn', true);            // default value for color picker
//            if ( empty( $ampforwp_cta_color )) {
//                $ampforwp_cta_color = '#eeee22';
//            }
//
//          echo '<input type="text"  name="_ampforwp_cta_color_btn" class="widefat ampforwp-color-picker" value="' . $ampforwp_cta_color . '" />';
//
//        }


    //*************************************************************************************************************************


        add_action( 'add_meta_boxes', 'ampforwp_cta_add_shortcode_metabox' );

        function ampforwp_cta_add_shortcode_metabox() {
        	add_meta_box('ampforwp_cta_shortcode_metabox', 'Shortcode', 'ampforwp_cta_shortcode_metabox_callback', 'amp-cta', 'side', 'high');
        }
        function ampforwp_cta_shortcode_metabox_callback() {
        	global $post;
        	$ampforwp_cta_post_id = $post->ID;
            echo '<input type="text" class="widefat urlfield" readonly="readonly" value="[amp-cta id=\''. $ampforwp_cta_post_id .'\']">';
        }



        add_action( 'add_meta_boxes', 'ampforwp_cta_add_shortcode_metabox_two' );

        function ampforwp_cta_add_shortcode_metabox_two() {
        	add_meta_box('ampforwp_cta_shortcode_metabox_two', 'Instructions & HELP', 'ampforwp_cta_shortcode_metabox_callback_two', 'amp-cta', 'side', 'high');
        }
        function ampforwp_cta_shortcode_metabox_callback_two() {
        	global $post;
        	$ampforwp_cta_post_id = $post->ID;
            echo 'Its easy to use these options once you understand them.<br /><br /> If this is your first time, then please<br /><a style="background: #607D8B;border-radius: 16px;padding: 6px 15px;line-height: 1;text-decoration: none;display: inline-block;margin-top: 7px;color: #fff;"href="https://ampforwp.com/tutorials/how-to-add-call-to-action-in-amp/#box" target="_blank">see this tutorial</a>';
        }


    //*************************************************************************************************************************

    add_action( 'admin_enqueue_scripts', 'ampforwp_cta_enqueue_color_picker' );
    function ampforwp_cta_enqueue_color_picker( $hook_suffix ) {
        // first check that $hook_suffix is appropriate for your admin page
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'ampforwp_cta-handle', plugins_url('app.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }

    // Save the Metabox Data
    function ampforwp_cta_save_meta_box($post_id, $post) {

    	// Is the user allowed to edit the post or page?
    	if ( !current_user_can( 'edit_post', $post->ID ))
    		return $post->ID;

    	// OK, we're authenticated: we need to find and save the data
    	// We'll put it into an array to make it easier to loop though.
        if(  array_key_exists( '_ampforwp_cta_btn_name' , $_POST ) || array_key_exists( '_ampforwp_cta_btn_url' , $_POST ) || array_key_exists( '_ampforwp_cta_btn_url_target' , $_POST ) || array_key_exists( '_ampforwp_cta_img_chck' , $_POST ) ||array_key_exists( '_ampforwp_cta_img_icon' , $_POST ) ) {

            $events_meta['_ampforwp_cta_btn_name'] = $_POST['_ampforwp_cta_btn_name'];
            $events_meta['_ampforwp_cta_btn_url'] = $_POST['_ampforwp_cta_btn_url'];
            $events_meta['_ampforwp_cta_btn_url_target'] = $_POST['_ampforwp_cta_btn_url_target'];
            $events_meta['_ampforwp_cta_img_chck'] = $_POST['_ampforwp_cta_img_chck'];
            $events_meta['_ampforwp_cta_img_icon'] = $_POST['_ampforwp_cta_img_icon'];


            foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
                if( $post->post_type == 'revision' ) return; // Don't store custom data twice
                $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
                if(get_post_meta($post->ID, $key, true)) { // If the custom field already has a value
                    update_post_meta($post->ID, $key, $value);
                } else { // If the custom field doesn't have a value
                    add_post_meta($post->ID, $key, $value);
                }
                if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
            }
        }

    	// $events_meta['_ampforwp_cta_checkbox_content_bottom'] = $_POST['_ampforwp_cta_checkbox_content_bottom'];
    	// $events_meta['_ampforwp_cta_checkbox_content_top'] = $_POST['_ampforwp_cta_checkbox_content_top'];

//    	$events_meta['_ampforwp_cta_color_title'] = $_POST['_ampforwp_cta_color_title'];
//    	$events_meta['_ampforwp_cta_color_background'] = $_POST['_ampforwp_cta_color_background'];
//    	$events_meta['_ampforwp_cta_color_btn_txt'] = $_POST['_ampforwp_cta_color_btn_txt'];
//    	$events_meta['_ampforwp_cta_color_btn'] = $_POST['_ampforwp_cta_color_btn'];

    	// Add values of $events_meta as custom fields



    }

    add_action('save_post', 'ampforwp_cta_save_meta_box', 1, 2); // save the custom fields

//*****************************//
// AMP CTA Meta Boxes end here //
//*****************************//

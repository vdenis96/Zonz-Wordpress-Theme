<?php

//*****************************//
// AMP CTA posttype Start here //
//*****************************//

    define('AMPCTA_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

    // Our custom post type function
    function create_amp_cta_post_type() {

        register_post_type( 'amp-cta',
        // CPT Options
            array(
                'labels' => array(
                    'name' => __( 'AMP CTA' ),
                    'singular_name' => __( 'AMP CTA Box' ),
                    'all_items' => __('CTA Boxes'),
                    'add_new' => __( 'Add New CTA Box' ),
                    'add_new_item' => __( 'Add New CTA Box' ),
                    'edit_item' => __( 'Edit CTA Box' ),
                    'new_item' => __( 'Add New CTA Box' ),
                    'view_item' => __( 'View CTA Box' ),
                    'search_items' => __( 'Search CTA Box' ),
                    'not_found' => __( 'No CTA Box\'s found' ),
                    'not_found_in_trash' => __( 'No CTA Box\'s found in trash' )
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'amp-cta'),
                'show_in_admin_bar'   => false,
                'exclude_from_search'   => true,
                'publicly_queryable' => false,
                'menu_position'       => 5,
                'show_in_menu'        => 'edit.php?post_type=amp_cta_bar',
            )
        );
    }
    add_action( 'init', 'create_amp_cta_post_type' );

//***************************//
// AMP CTA posttype end here //
//***************************//



//******************************//
// AMP CTA requiring Start here //
//******************************//

    // modifiying our Add New CTA Page
    require 'meta.php';

//****************************//
// AMP CTA requiring end here //
//****************************//

add_action( 'admin_enqueue_scripts', 'amp_cta_box_conditional_fields_style_script' );
function amp_cta_box_conditional_fields_style_script() {
  global $pagenow, $typenow;
    if (is_admin() && $pagenow=='post-new.php' OR $pagenow=='post.php' && $typenow=='amp-cta') {
        wp_register_script( 'amp_cta_box_admin', plugin_dir_url(__FILE__) . '/assets/js/amp_cta_box_admin.js', array( 'jquery'), AMP_CTA_VERSION, true );
        wp_enqueue_script('amp_cta_box_admin');
        wp_enqueue_script('media-upload');
        wp_enqueue_media('amp_cta_box_admin');
    }
}

//*******************************//
// AMP CTA Magic Code Start here //
//*******************************//

    // Field in AMP CTA edit page
    add_filter( 'manage_amp-cta_posts_columns', 'ampforwp_cta_set_custom_edit_columns' );
    add_action( 'manage_amp-cta_posts_custom_column' , 'ampforwp_cta_custom_amp_optin_column', 10, 2 );

    function ampforwp_cta_set_custom_edit_columns( $columns ) {
        $columns['amp-cta-shortcode-output'] = __( 'CTA  ShortCode', 'your_text_domain' );
        return $columns;
    }

    function ampforwp_cta_custom_amp_optin_column( $column, $post_id ) {
        switch ( $column ) {

            case 'amp-cta-shortcode-output' :
                $terms = '[amp-cta id="'.$post_id.'"]';
                if ( is_string( $terms ) )
                    echo $terms;
                else
                    _e( 'Unable to get author(s)', 'your_text_domain' );
            break;

        }
    }

//*****************************//
// AMP CTA Magic Code End here //
//*****************************//


//**********************************************//
// AMP CTA COntent manipulation Code Start here //
//**********************************************//

  function get_amp_cta_direct_markup( $atts ) {
    // initializing these to avoid debug errors
    global $post;

    $url = get_post_meta( $atts['id'] , "_ampforwp_cta_btn_url" , true);
    $url_target = get_post_meta( $atts['id'] , "_ampforwp_cta_btn_url_target" , true);
    $title = get_the_title( $atts['id'] );
    $description = get_post_field('post_content', $atts['id']);
    $btn_text = get_post_meta( $atts['id'] , "_ampforwp_cta_btn_name" , true);
    $check_cta_box_image_icon = get_post_meta($atts['id'], '_ampforwp_cta_img_chck', true);
    $get_cta_box_image_icon = get_post_meta($atts['id'], '_ampforwp_cta_img_icon', true);

    $set_cta_box_image_icon = '';
    if($check_cta_box_image_icon && $get_cta_box_image_icon){
      $set_cta_box_image_icon = '<div class="cta-icon amp-cta"><amp-img src="'.$get_cta_box_image_icon.'" width="50" height="50" alt="CTA Image" class="amp-cta-icon" layout="responsive"></amp-img></div><div class="cta-cont amp-cta">';
       add_action('amp_post_template_css','amp_cta_image_icon_styling', 20);
    }

    $content =  '
                  <div class="ampforwp-cta-wrapper">'.$set_cta_box_image_icon.'
                    <div class="ampforwp-cta-text">
                        <a href="'.$url.'"'. ( $url_target == 'yes' ?' rel="nofollow" target="_blank" ':'').'>
                       <span class="cta-box-title">'.$title.'</span>
                        <p>'.$description.'</p></a>
                    </div>
                    <div class="ampforwp-button-wrapper">
                        <div class="ampforwp-button button ampforwp-in-article-block">
                            <a href="'.$url.'"'. ( $url_target == 'yes' ?' rel="nofollow" target="_blank" ':'').'>'.$btn_text.'</a>
                        </div>
                    </div>
                  </div>
                ';


    // Add CTA only on AMP posts
  //print_r($content); die;
if(function_exists('ampforwp_is_amp_endpoint') || ampforwp_is_amp_endpoint())
    { 
      return $content;
    }
  }

  add_action( 'pre_amp_render_post','ampforwp_cta_content_manipulation');
  function ampforwp_cta_content_manipulation() {
    add_filter( 'the_content', 'ampforwp_content_cta_insertion_top', 15  );
    add_filter( 'the_content', 'ampforwp_content_cta_insertion_bottom', 14  );
  }

  function ampforwp_content_cta_insertion_top( $content ) {
    global $redux_builder_amp, $post;
    $post_id = $post->ID;
    $cta_id = $cta_meta = '';
    $cta_id = isset($redux_builder_amp['ampforwp-cta-box-selected-cta'])? $redux_builder_amp['ampforwp-cta-box-selected-cta']:'';
    $post_id_array = array();
    $is_post_type = $is_page_type = $is_product_type = '';
    
    if( isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('product',$redux_builder_amp['amp-cta-posttype-support'])){
          if(function_exists('is_product')){
           $is_product_type = is_product();
          }
      }
    if( isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('post',$redux_builder_amp['amp-cta-posttype-support'])){
        if(function_exists('is_product')){
        $is_post_type = (is_single() && !is_product());
        }else{
         $is_post_type = is_single();
       }
     }

    if(isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('page',$redux_builder_amp['amp-cta-posttype-support'])){
      $is_page_type = is_page();
    }
    if( true == $redux_builder_amp['ampforwp-cta-variation-testing'] ){
        $post_id_array = $redux_builder_amp['ampforwp-cta-variations'];
        if(empty($post_id_array)){
            return $content;
        }
        $cta_meta = intval(get_post_meta($post_id, 'amp-cta-var', true));
        if($cta_meta ==  null){
            $cta_meta = 0;
        }
        $cta_id = $post_id_array[$cta_meta];
    }  
    $arg = array( 'id' => $cta_id );
    $ampforwp_cta_checkbox_content_top = $redux_builder_amp['ampforwp-cta-box-content-top'];
    $cont = get_amp_cta_direct_markup( $arg );
    if( $ampforwp_cta_checkbox_content_top ){
        if( $is_post_type || $is_page_type || $is_product_type ){
       $content = $cont . '  ' . $content ;
       add_action('amp_post_template_css','amp_cta_styling', 20);
       }
    }
    return $content;
  }

  function ampforwp_content_cta_insertion_bottom( $content ) {
    global $redux_builder_amp, $post;
    $post_id = $post->ID;
    $cta_id = $cta_meta = '';
    $cta_id = isset($redux_builder_amp['ampforwp-cta-box-selected-cta'])? $redux_builder_amp['ampforwp-cta-box-selected-cta']:'';
     $post_id_array = array(); 
      $is_post_type = $is_page_type = $is_product_type = '';
    
    if( isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('product',$redux_builder_amp['amp-cta-posttype-support'])){
      if(function_exists('is_product')){
      $is_product_type = is_product();
     }
     }
    if( isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('post',$redux_builder_amp['amp-cta-posttype-support'])){
        if(function_exists('is_product')){
       $is_post_type = (is_single() && !is_product());
      }else{
       $is_post_type = is_single();
      }
    }

    if(isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('page',$redux_builder_amp['amp-cta-posttype-support'])){
      $is_page_type = is_page();
    }
    if( true == $redux_builder_amp['ampforwp-cta-variation-testing'] ){
        $post_id_array = $redux_builder_amp['ampforwp-cta-variations'];
        if(empty($post_id_array)){
            return $content;
        }
        $cta_meta = intval(get_post_meta($post_id, 'amp-cta-var', true));
        if($cta_meta ==  null){
            $cta_meta = 0;
        }
        $cta_id = $post_id_array[$cta_meta];
    }
    $arg = array( 'id' => $cta_id );
    $ampforwp_cta_checkbox_content_bottom = $redux_builder_amp['ampforwp-cta-box-content-bottom'];
    $cont = get_amp_cta_direct_markup( $arg );
    if( $ampforwp_cta_checkbox_content_bottom ){
        if( $is_post_type || $is_page_type || $is_product_type ){
      $content = $content . '  '. $cont;
      add_action('amp_post_template_css','amp_cta_styling', 20);
      }
    }
    return $content;
  }

// For Variation Testing
add_action('pre_amp_render_post', 'amp_cta_variation_check_proper_cta_id');
if( ! function_exists('amp_cta_variation_check_proper_cta_id') ) {
    function amp_cta_variation_check_proper_cta_id() {
        global $post, $redux_builder_amp;
        $post_id = $post->ID;
        $post_id_array = array(); 
        if( true == $redux_builder_amp['ampforwp-cta-variation-testing'] ){
            $post_id_array = $redux_builder_amp['ampforwp-cta-variations'];
            if(empty($post_id_array)){
                return;
            }       
            $currentCTA = intval(get_post_meta($post_id, 'amp-cta-var', true));
            if ( $currentCTA === null  ) {
                $currentCTA = 0;
            } else {
                $currentCTA++;
            }

            if ( $currentCTA >= count($post_id_array) ) {
               $currentCTA = 0;
            }
            update_post_meta($post_id, 'amp-cta-var', $currentCTA);
        }
    }
}

add_action( 'pre_amp_render_post', 'ampforwp_incontent_cta_code' );
if ( ! function_exists( 'ampforwp_incontent_cta_code' ) ) {
	function ampforwp_incontent_cta_code() {
    global $redux_builder_amp;
       $is_post_type = $is_page_type = $is_product_type = '';
    
    if( isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('product',$redux_builder_amp['amp-cta-posttype-support'])){
      if(function_exists('is_product')){
      $is_product_type = is_product();
     }
     }
    if( isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('post',$redux_builder_amp['amp-cta-posttype-support'])){
        if(function_exists('is_product')){
       $is_post_type = (is_single() && !is_product());
      }else{
       $is_post_type = is_single();
      }
    }

    if(isset($redux_builder_amp['amp-cta-posttype-support']) && in_array('page',$redux_builder_amp['amp-cta-posttype-support'])){
      $is_page_type = is_page();
    }

    if($redux_builder_amp['ampforwp-cta-box-content']) {
      if( $is_post_type || $is_page_type || $is_product_type ){
      add_filter( 'the_content', 'ampforwp_insert_incontent_cta_code', 12 );
      }
    }
	}
}

if ( ! function_exists( 'ampforwp_insert_incontent_cta_code' ) ) {
	function ampforwp_insert_incontent_cta_code( $content ) {
	 	global $redux_builder_amp, $post;
        $post_id = $post->ID;
        $cta_id = $cta_meta = '';
        $post_id_array = array(); 
        $cta_id = $redux_builder_amp['ampforwp-cta-box-selected-cta'];
        if( true == $redux_builder_amp['ampforwp-cta-variation-testing'] ){
            $post_id_array = $redux_builder_amp['ampforwp-cta-variations'];
            if(empty($post_id_array)){
                return $content;
            }
            $cta_meta = intval(get_post_meta($post_id, 'amp-cta-var', true));
            if($cta_meta ==  null){
                $cta_meta = 0;
            }
            $cta_id = $post_id_array[$cta_meta];
        }
        $arg = array( 'id' =>  $cta_id);
		$insertion = get_amp_cta_direct_markup($arg);

		// to show cta after below number of paragraph.
        $paragraph_id = '50-percent';

		if ( ( is_single() || is_page() || is_front_page() )  && ! is_admin() ) {
      add_action('amp_post_template_css','amp_cta_styling', 20);
			return ampforwp_insert_after_paragraph_cta_code( $insertion, $paragraph_id, $content );
		}
		return $content;
	}
}

// Parent Function that makes the magic happen
if ( ! function_exists( 'ampforwp_insert_after_paragraph_cta_code' ) ) {
	function ampforwp_insert_after_paragraph_cta_code( $insertion, $paragraph_id, $content ) {
		global $redux_builder_amp;
		$closing_p = '</p>';
		$paragraphs = explode( $closing_p, $content );
// var_dump($paragraphs);
		if ( $paragraph_id == '50-percent' ) {
			$paragraph_id =  count($paragraphs);
			$paragraph_id = $paragraph_id / 2 ;
			$paragraph_id = round($paragraph_id);
		}

		foreach ($paragraphs as $index => $paragraph) {
			if ( trim( $paragraph ) ) {
				$paragraphs[$index] .= $closing_p;
			}
			if ( $paragraph_id == $index + 1 ) {
				$paragraphs[$index] .= $insertion;
			}
		}
		return implode( '', $paragraphs );
	}
}

add_action('pre_amp_render_post', 'exclude_cta_from_spec_post', 9);
function exclude_cta_from_spec_post() {
    global $post, $redux_builder_amp;
      
    if(isset($redux_builder_amp['ampforwp-exclude-cta-box-for-spec-post'])){
    $exclude_post_id = (array) ($redux_builder_amp['ampforwp-exclude-cta-box-for-spec-post']);
    }
    if(isset($redux_builder_amp['ampforwp-exclude-cta-box-for-spec-page'])){
    $exclude_page_id = (array) ($redux_builder_amp['ampforwp-exclude-cta-box-for-spec-page']);
    }
    $exclude_id = array_merge( $exclude_post_id, $exclude_page_id );

    if (in_array($post->ID, $exclude_id  )) {

        remove_action( 'pre_amp_render_post','ampforwp_cta_content_manipulation');
        remove_action( 'pre_amp_render_post', 'ampforwp_incontent_cta_code' );
     }
}

add_action( 'wp_ajax_ampforwp_posts', 'ampforwp_ajax_posts' );
function ampforwp_ajax_posts(){
        if(!wp_verify_nonce($_GET['security'],'ampforwp-verify-request') ){
            echo json_encode(array('status'=>403,'message'=>'user request is not allowed')) ;
            die;
        }
    $return = array();

    $args = array(
            's' => $_GET['q'],
            'numberposts' => -1,
            'post_type' => 'post'
        );
  $query1 = new WP_Query($args);

   foreach ($query1->posts as $cat ) {
   if ( stristr($cat->post_title,$args['s']) ){
                $return[] = array($cat->ID,$cat->post_title);
        }
    }
    wp_send_json( $return );
  }

add_action( 'wp_ajax_ampforwp_pages', 'ampforwp_ajax_pages' );
function ampforwp_ajax_pages(){
        if(!wp_verify_nonce($_GET['security'],'ampforwp-verify-request') ){
            echo json_encode(array('status'=>403,'message'=>'user request is not allowed')) ;
            die;
        }
    $return = array();

    $args = array(
            's' => $_GET['q'],
            'numberposts' => -1,
            'post_type' => 'page'
        );
    $query1 = new WP_Query($args);

    foreach ($query1->posts as $cat ) {
   if ( stristr($cat->post_title,$args['s']) ){
                $return[] = array($cat->ID,$cat->post_title);
        }
    }
    wp_send_json( $return );
  }

//**********************************************//
// AMP CTA COntent manipulation Code End here //
//**********************************************//
<?php
//FrontEnd
function amp_cta_bar_get_all_schema_posts(){
    $post_idArray = array();
    $allPosts = get_posts([
              'post_type' => 'amp_cta_bar',
              'post_status' => 'publish',
              'posts_per_page' => -1
              // 'order'    => 'ASC'
            ]);
    if ( $allPosts ) {
      foreach ( $allPosts as $post ) {
          $post_idArray[] += $post->ID;
      }
    }
    $unique_checker = '';
    if(count($post_idArray)>0){    
      $returnData = array();
      foreach ($post_idArray as $key => $post_id){
            $dataAll = amp_cta_bar_generate_field_data( $post_id );
            
            if($dataAll){  
                $condition_array = array();    
                foreach ($dataAll as $result){

                    $data = array_filter($result);

                    $number_of_fields = count($data);
                    $checker = 0;

                    // Check if we have more then 1 fields.
                    if ( $number_of_fields > 0 ) {
                        $checker = count( array_unique($data) );
                        $array_is_false =  in_array(false, $result);
                        if (  $array_is_false ) {
                            $checker = 0;
                        }
                    }
                $condition_array[] = $checker;
                }
            $unique_checker = in_array(true,$condition_array);              
          }else{
            $unique_checker ='notset';    
          }
          
        if( $unique_checker === 1||$unique_checker === true||$unique_checker == 'notset'){
            $conditions = array();
            $data_group_array = get_post_meta( $post_id, 'data_group_array', true);
            $amp_cta_bar_options = get_post_meta( $post_id, 'amp_cta_bar_options', true);

            $returnData[] = array(
                // 'schema_type' => get_post_meta( $post_id, 'schema_type', true),
                // 'schema_options' => get_post_meta( $post_id, 'schema_options', true),
                //'conditions'  => $conditions, 
                'cta_bar_options' => $amp_cta_bar_options,
                'post_id' => $post_id
                );
            }
        }//foreach closed post_idArray
      
      return $returnData;
    }//iF Closed post_idArray
   return false;
}
function amp_cta_bar_generate_field_data( $post_id ){
    $data_group_array = get_post_meta( $post_id, 'data_group_array', true);
    //$conditions = $amp_cta_bar_options['data_array'];
    $output = array();
    if ( $data_group_array ) {
        foreach ($data_group_array as $gropu){
            $output[] = array_map('amp_cta_bar_comparison_logic_checker', $gropu['data_array']); 
        }
    }
    return $output;
}

function amp_cta_bar_comparison_logic_checker($input){
        global $post;
        $type       = $input['key_1'];
        $comparison = $input['key_2'];
        $data       = isset($input['key_3'])? $input['key_3']:'';

        $result             = '';
        // Get all the users registered
        $user = wp_get_current_user();

        switch ($type) {
        // Basic Controls ------------ 
          // Posts Type
          case 'show_globally':   
               $result = true;    
          break;
          
          case 'post_type':
                $current_post_type  = isset($post->post_type)? $post->post_type:'';            
                  if ( $comparison == 'equal' ) {
                  if ( $current_post_type == $data ) {
                    $result = true;
                  }
              }
              if ( $comparison == 'not_equal') {              
                  if ( $current_post_type != $data ) {
                    $result = true;
                  }
              }
                
          break;

      // Logged in User Type
         case 'user_type':            
            if ( $comparison == 'equal') {
                if ( in_array( $data, (array) $user->roles ) ) {
                    $result = true;
                }
            }            
            if ( $comparison == 'not_equal') {
                require_once ABSPATH . 'wp-admin/includes/user.php';
                // Get all the registered user roles
                $roles = get_editable_roles();                
                $all_user_types = array();
                foreach ($roles as $key => $value) {
                  $all_user_types[] = $key;
                }
                // Flip the array so we can remove the user that is selected from the dropdown
                $all_user_types = array_flip( $all_user_types );

                // User Removed
                unset( $all_user_types[$data] );

                // Check and make the result true that user is not found 
                if ( in_array( $data, (array) $all_user_types ) ) {
                    $result = true;
                }
            }
            
           break; 

    // Post Controls  ------------ 
      // Posts
      case 'post': 
            $current_post = isset($post->ID)? $post->ID:0;
            if ( $comparison == 'equal' ) {
                if ( $current_post == $data ) {
                  $result = true;
                }
            }
            if ( $comparison == 'not_equal') {              
                if ( $current_post != $data ) {
                  $result = true;
                }
            }

        break;

      // Post Category
      case 'post_category':
          $current_category = '';
          if(isset($post->ID)){
            $postcat = get_the_category( $post->ID );
            $current_category = $postcat[0]->cat_ID; 
          }
           
          if ( $comparison == 'equal') {
              if ( $data == $current_category ) {
                  $result = true;
              }
          }
          if ( $comparison == 'not_equal') {
              if ( $data != $current_category ) {
                  $result = true;
              }
          }
        break;
      // Post Format
      case 'post_format':
          $current_post_format = get_post_format( $post->ID );
          if ( $current_post_format === false ) {
              $current_post_format = 'standard';
          }
          if ( $comparison == 'equal') {
              if ( $data == $current_post_format ) {
                  $result = true;
              }
          }
          if ( $comparison == 'not_equal') {
              if ( $data != $current_post_format ) {
                  $result = true;
              }
          }
        break;

    // Page Controls ---------------- 
      // Page
      case 'page': 
        global $redux_builder_amp;
        if(function_exists('ampforwp_is_front_page')){
          if(ampforwp_is_front_page()){
          $current_post = $redux_builder_amp['amp-frontpage-select-option-pages'];    
          } else{
          $current_post = $post->ID;    
          }           
        }else{
          $current_post = $post->ID;
        }
            if ( $comparison == 'equal' ) {
                if ( $current_post == $data ) {
                  $result = true;
                }
            }
            if ( $comparison == 'not_equal') {              
                if ( $current_post != $data ) {
                  $result = true;
                }
            }
        break;

      // Page Template 
      case 'page_template':
        $current_page_template = false;
        if(isset($post->ID)){
          $current_page_template = get_page_template_slug( $post->ID );  
        }
            if ( $current_page_template == false ) {
                $current_page_template = 'default';
            }
            if ( $comparison == 'equal' ) {
                if ( $current_page_template == $data ) {
                    $result = true;
                }
            }
            if ( $comparison == 'not_equal') {              
                if ( $current_page_template != $data ) {
                    $result = true;
                }
            }

        break; 

    // Other Controls ---------------
      // Taxonomy Term
      case 'ef_taxonomy':
        // Get all the post registered taxonomies        
        // Get the list of all the taxonomies associated with current post
        $taxonomy_names = get_post_taxonomies( $post->ID );

        $checker    = '';
        $post_terms = '';

          if ( $data != 'all') {
            $post_terms = wp_get_post_terms($post->ID, $data);           

            if ( $comparison == 'equal' ) {
                if ( $post_terms ) {
                    $result = true;
                }
            }

            if ( $comparison == 'not_equal') { 
                $checker =  in_array($data, $taxonomy_names);       
                if ( ! $checker ) {
                    $result = true;
                }
            }
            if($result==true && isset( $input['key_4'] ) && $input['key_4'] !='all'){
              $term_data       = $input['key_4'];
              $terms = wp_get_post_terms( $post->ID ,$data);
              if(count($terms)>0){
                $termChoices = array();
                foreach ($terms as $key => $termvalue) {
                   $termChoices[] = $termvalue->slug;
                 } 
              }
              $result = false;
              if(in_array($term_data, $termChoices)){
                $result = true;
              }
            }//if closed for key_4

          } else {

            if ( $comparison == 'equal' ) {
              if ( $taxonomy_names ) {                
                  $result = true;
              }
            }

            if ( $comparison == 'not_equal') { 
              if ( ! $taxonomy_names ) {                
                  $result = true;
              }
            }

          }
        break;
      
      default:
        $result = false;
        break;
    }

    return $result;
}
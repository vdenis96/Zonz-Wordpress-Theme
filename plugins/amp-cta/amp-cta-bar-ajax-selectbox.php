<?php
$response = $data;
    $is_ajax = false;
    if( $_SERVER['REQUEST_METHOD']=='POST'){
        $is_ajax = true;
        if(wp_verify_nonce($_POST["amp_cta_bar_call_nonce"],'amp_cta_bar_select_action_nonce')){
            
            if ( isset( $_POST["id"] ) ) {
              $response = sanitize_text_field(wp_unslash($_POST["id"]));
            }
            if ( isset( $_POST["number"] ) ) {
              $current_number   = intval($_POST["number"]);
            }
            if ( isset( $_POST["group_number"] ) ) {
              $current_group_number   = intval($_POST["group_number"]);
            }
        }else{
            exit;
        }
       
    }        
        // send the response back to the front end
       // vars
    
    $choices = array();    
    
    $options['param'] = $response;
    // some case's have the same outcome
        if($options['param'] == "page_parent")
        {
          $options['param'] = "page";
        }
    
        switch($options['param'])
        {
          case "post_type":

            $choices = self::amp_cta_bar_post_type_generator();

            $choices = apply_filters('amp_cta_modify_select_post_type', $choices );
            unset($choices['amp_cta_bar']);
            unset($choices['amp-cta']);          
            break;

          case "page":

            $post_type = 'page';
            $posts = get_posts(array(
              'posts_per_page'          =>  -1,
              'post_type'               => $post_type,
              'orderby'                 => 'menu_order title',
              'order'                   => 'ASC',
              'post_status'             => 'any',
              'suppress_filters'        => false,
              'update_post_meta_cache'  => false,
            ));

            if( $posts )
            {
              // sort into hierachial order!
              if( is_post_type_hierarchical( $post_type ) )
              {
                $posts = get_page_children( 0, $posts );
              }

              foreach( $posts as $page )
              {
                $title = '';
                $ancestors = get_ancestors($page->ID, 'page');
                if($ancestors)
                {
                  foreach($ancestors as $a)
                  {
                    $title .= '- ';
                  }
                }

                $title .= apply_filters( 'the_title', $page->post_title, $page->ID );                        
                // status
                if($page->post_status != "publish")
                {
                  $title .= " ($page->post_status)";
                }

                $choices[ $page->ID ] = $title;

              }
              // foreach($pages as $page)

            }

            break;

          case "page_template" :

            $choices = array(
              'default' =>  esc_html__('Default Template','amp-cta'),
            );

            $templates = get_page_templates();
            foreach($templates as $k => $v)
            {
              $choices[$v] = $k;
            }

            break;

          case "post" :

            $post_types = get_post_types();

            unset( $post_types['page'], $post_types['attachment'], $post_types['revision'] , $post_types['nav_menu_item'], $post_types['acf'] , $post_types['amp_acf'], $post_types['amp_cta_bar'] );

            if( $post_types )
            {
              foreach( $post_types as $post_type )
              {

                $posts = get_posts(array(
                  'numberposts' => '-1',
                  'post_type' => $post_type,
                  'post_status' => array('publish', 'private', 'draft', 'inherit', 'future'),
                  'suppress_filters' => false,
                ));

                if( $posts)
                {
                  $choices[$post_type] = array();

                  foreach($posts as $post)
                  {
                    $title = apply_filters( 'the_title', $post->post_title, $post->ID );

                    // status
                    if($post->post_status != "publish")
                    {
                      $title .= " ($post->post_status)";
                    }

                    $choices[$post_type][$post->ID] = $title;

                  }
                  // foreach($posts as $post)
                }
                // if( $posts )
              }
              // foreach( $post_types as $post_type )
            }
            // if( $post_types )


            break;

          case "post_category" :

            $terms = get_terms( 'category', array( 'hide_empty' => false ) );

            if( !empty($terms) ) {

              foreach( $terms as $term ) {

                $choices[ $term->term_id ] = $term->name;

              }

            }

            break;

          case "post_format" :

            $choices = get_post_format_strings();

            break;

          case "user_type" :
           global $wp_roles;
            $choices = $wp_roles->get_names();

            if( is_multisite() )
            {
              $choices['super_admin'] = esc_html__('Super Admin','amp-cta');
            }

            break;

          case "ef_taxonomy" :

            $choices = array('all' => esc_html__('All','amp-cta'));
            $taxonomies = amp_cta_bar_taxonomy_generator();        
            $choices = array_merge($choices, $taxonomies);                      
            break;

        }        
    // allow custom location rules
    $choices = $choices; 

    // Add None if no elements found in the current selected items
    if ( empty( $choices) ) {
      $choices = array('none' => esc_html__('No Items', 'amp-cta') );
    }
     //  echo $current_number;
    // echo $saved_data;

      $output = '<select  class="widefat ajax-output" name="data_group_array[group-'.esc_attr($current_group_number).'][data_array]['. esc_attr($current_number) .'][key_3]">'; 

        // Generate Options for Posts
        if ( $options['param'] == 'post' ) {
          foreach ($choices as $choice_post_type) {      
            foreach ($choice_post_type as $key => $value) { 
                if ( $saved_data ==  $key ) {
                    $selected = 'selected="selected"';
                } else {
                  $selected = '';
                }

                $output .= '<option '. esc_attr($selected) .' value="' .  esc_attr($key) .'"> ' .  esc_html__($value, 'amp-cta') .'  </option>';            
            }
          }
         // Options for Other then posts
        } else {
          foreach ($choices as $key => $value) { 
                if ( $saved_data ==  $key ) {
                    $selected = 'selected="selected"';
                } else {
                  $selected = '';
                }

            $output .= '<option '. esc_attr($selected) .' value="' . esc_attr($key) .'"> ' .  esc_html__($value, 'amp-cta') .'  </option>';            
          } 
        }
      $output .= ' </select> '; 
    $allowed_html = amp_cta_bar_expanded_allowed_tags();
    echo wp_kses($output, $allowed_html); 
    
    if ( $is_ajax ) {
      die();
    }
// endif; 

// Generate Proper Post Taxonomy for select and to add data.
if(!function_exists('amp_cta_bar_taxonomy_generator')){
  function amp_cta_bar_taxonomy_generator(){
      $taxonomies = '';  
      $choices    = array();
      $taxonomies = get_taxonomies( array('public' => true), 'objects' );
      

        foreach($taxonomies as $taxonomy) {
          $choices[ $taxonomy->name ] = $taxonomy->labels->name;
        }
        
        // unset post_format (why is this a public taxonomy?)
        if( isset($choices['post_format']) ) {
          unset( $choices['post_format']) ;
        }
        
      return $choices;
  }
}
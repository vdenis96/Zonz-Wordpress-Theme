<?php
class Amp_CTA_Bar_Post_Type
{
    /**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		register_activation_hook( AMPFORWP_CTA_PLUGIN_DIR.'amp-cta.php',[ $this, 'amp_cta_activation_hook']  );
	}
	public function amp_cta_activation_hook() {
  	global $wpdb;
  	$default_option = get_option( 'amp_cta_bar_default' );
  	$redux_builder_amp =  get_option('redux_builder_amp');
  	$bar_location = '';
  	$close_btn_type = '';
  	$data_array = array();
  	$meta_value = array();
  	$default_values = array();
  		if(!$default_option){
			$current_user = wp_get_current_user();
		    $this->amp_cta_bar_post_type();
		    $my_post = array(
		        'post_title'    => 'CTA Bar Default',
		          'post_content'  => 'This is default post for cta bar.',
		          'post_status'   => 'publish',
		          'post_author'   => $current_user->ID,
		          'post_type' => 'amp_cta_bar',
		    );
		    $data_array[] = array(
		  		"key_1" => "show_globally",
		  		"key_2" => "equal",
		  		"key_3" => "none"
		  	);
	    	$post_id = wp_insert_post( $my_post);
	    	$bar_location = $redux_builder_amp['ampforwp-cta-bar-position'];
	    	$close_btn_type = $redux_builder_amp['ampforwp-cta-close-button-text'];
	    	if( $bar_location == '1'){
	    		$bar_location = 'top';
	    	}else{
	    		$bar_location = 'bottom';
	    	}
	    	if( $close_btn_type == '1'){
	    		$close_btn_type = 'x';
	    	}else{
	    		$close_btn_type = 'close_text';
	    	}
	    	//Post meta Default values
	      	$meta_value['status'] = 'active';
	      	$meta_value['data_array'] = $data_array;
			$meta_value['bar_description_btn'] = $redux_builder_amp['ampforwp-cta-bar-content'];
			$meta_value['cta_bar_content'] = $redux_builder_amp['ampforwp-cta-subsection-notification-description'];
			$meta_value['bar_location'] = $bar_location;
			$meta_value['primary_btn'] = $redux_builder_amp['ampforwp-cta-primary-button'];
			$meta_value['primary_button_text'] = $redux_builder_amp['ampforwp-cta-subsection-notification-button-text'];
			$meta_value['primary_button_url'] = $redux_builder_amp['ampforwp-cta-subsection-notification-button-url'];
			$meta_value['primary_link_target'] = $redux_builder_amp['ampforwp-cta-subsection-notification-button-url-target'];
			$meta_value['secondary_btn'] = $redux_builder_amp['ampforwp-cta-secondary-button'];
			$meta_value['secondary_button_text'] = $redux_builder_amp['ampforwp-cta-secondary-button-text'];
			$meta_value['secondary_button_url'] = $redux_builder_amp['ampforwp-cta-secondary-button-url'];
			$meta_value['secondary_link_target'] = $redux_builder_amp['ampforwp-cta-secondary-button-url-target'];
			$meta_value['close_button'] = $redux_builder_amp['ampforwp-cta-close-button'];
			$meta_value['close_button_type'] = $close_btn_type;//need to change
			$meta_value['close_btn_text'] = $redux_builder_amp['ampforwp-cta-close-button-text-custom'];
			$meta_value['title_color'] = $redux_builder_amp['ampforwp-cta-subsection-notification-text-color']['color'];
			$meta_value['bar_bgcolor'] = $redux_builder_amp['ampforwp-cta-subsection-notification-background-color']['color'];
			$meta_value['primary_btn_text_color'] = $redux_builder_amp['ampforwp-cta-subsection-notification-button-text-color']['color'];
			$meta_value['primary_btn_bgcolor'] = $redux_builder_amp['ampforwp-cta-subsection-notification-button-color']['color'];
			$meta_value['secondary_btn_text_color'] = $redux_builder_amp['ampforwp-cta-secondary-button-text-color']['color'];
			$meta_value['secondary_btn_bgcolor'] = $redux_builder_amp['ampforwp-cta-secondary-button-color']['color'];
			$meta_value['close_btn_text_color'] = $redux_builder_amp['ampforwp-cta-close-button-text-color']['color'];
			$meta_value['close_btn_bgcolor'] = $redux_builder_amp['ampforwp-cta-close-button-color']['color'];

			/*Default Values*/
			$default_values['bar_description_btn'] = '1';
			$default_values['cta_bar_content'] = 'You can edit this default title from options';
			$default_values['bar_location'] = 'bottom';
			$default_values['primary_btn'] = '1';
			$default_values['primary_button_text'] = 'Click This';
			$default_values['primary_button_url'] = '#';
			$default_values['primary_link_target'] = '0';
			$default_values['secondary_btn'] = '0';
			$default_values['secondary_button_text'] = 'Click This';
			$default_values['secondary_button_url'] = '#';
			$default_values['secondary_link_target'] = '0';
			$default_values['close_button'] = '1';
			$default_values['close_button_type'] = 'x';
			$default_values['close_btn_text'] = '';

			/*Color Schema Settings*/
			$default_values['title_color'] = '#555555';
			$default_values['bar_bgcolor'] = '#ffffff';
			$default_values['primary_btn_text_color'] = '#ffffff';
			$default_values['primary_btn_bgcolor'] = '#f42f42';
			$default_values['secondary_btn_text_color'] = '#ffffff';
			$default_values['secondary_btn_bgcolor'] = '#555555';
			$default_values['close_btn_text_color'] = '#666666';
			$default_values['close_btn_bgcolor'] = '#ffffff';

			update_option('amp_cta_bar_default_options', $default_values);
			$result = update_post_meta( $post_id, 'amp_cta_bar_options', $meta_value );
			update_option( 'amp_cta_bar_default', '1' );
  		}
	}
	
	public function init(){

		add_action( 'init', [ $this,'amp_cta_bar_post_type'] );
		//Custom CTA Meta Box
		add_action('add_meta_boxes', [ $this, 'amp_cta_bar_meta_box']);
		add_action('add_meta_boxes', [ $this, 'amp_cta_bar_options_meta_box']);
		add_action( 'add_meta_boxes', [ $this, 'amp_cta_bar_conditional_fields_meta_box'] );
		add_action( 'add_meta_boxes', [ $this, 'amp_cta_bar_type_meta_box'] );
		
		add_action( 'add_meta_boxes', [ $this, 'amp_cta_bar_show_hide_meta_box'] );

		add_action('save_post', ['Amp_CTA_Bar_Post_Type', 'amp_cta_bar_options_save_post_page'], 15, 2);
		add_action( 'admin_enqueue_scripts', [ $this, 'amp_cta_bar_colorpicker_scripts']);
		add_action( 'admin_enqueue_scripts', [ $this, 'amp_cta_bar_conditional_fields_style_script'] );

		add_action('wp_ajax_amp_cta_bar_select_field',['Amp_CTA_Bar_Post_Type','amp_cta_bar_select_field_handler']);
		add_action('wp_ajax_amp_cta_bar_ajax_select_taxonomy',['Amp_CTA_Bar_Post_Type','amp_cta_bar_ajax_select_taxonomy_handler']);
  		// Generate Proper post types for select and to add data.
		add_action('wp_loaded', ['Amp_CTA_Bar_Post_Type','amp_cta_bar_post_type_generator']);

		//Add custom columns in custom post list table
		add_filter('manage_amp_cta_bar_posts_columns', [ $this,'amp_cta_bar_columns_bar_head']);
		add_action('manage_amp_cta_bar_posts_custom_column', [ $this,'amp_cta_bar_columns_bar_content'], 10, 2);
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_action('admin_menu', [ $this,'remove_submenus']);
	}
	public function remove_submenus() {
	    if(current_user_can('activate_plugins')) {
	    	global $submenu;
	    	unset($submenu['edit.php?post_type=amp_cta_bar'][10]); // Removes 'Add New'.
	    }
	}

	public function amp_cta_bar_columns_bar_head($defaults){
		$defaults['status']  = 'Status';
    	$defaults['position'] = 'Position';
    	return $defaults;
	}
	public function amp_cta_bar_columns_bar_content($column_name, $post_ID){
		$amp_cta_bar_options = get_post_meta($post_ID, 'amp_cta_bar_options', true);
		
		if ($column_name == 'status') {
        	
	        if (isset($amp_cta_bar_options['status']) && $amp_cta_bar_options['status'] =='active') {
	            echo '<span class="">Active</span>';
	        }else{
	        	echo '<span class="">InActive</span>';
	        }
    	}
    	if ($column_name == 'position') {
        	
	        if (isset($amp_cta_bar_options['bar_location'])) {
	            echo ucfirst($amp_cta_bar_options['bar_location']);
	        }
    	}
	}
	public function amp_cta_bar_show_hide_meta_box(){
		$screens = ['amp_cta_bar'];
	    foreach ($screens as $screen) {
			add_meta_box( 
				'amp-cta-bar-show-hide', 
				esc_html__( 'CTA Bar Status','amp-cta' ), 
				[__CLASS__,'amp_cta_bar_show_hide_callback'], 
				$screen,'side', 'low', null 
			);
		}
	}
	public static function amp_cta_bar_show_hide_callback($post){
		$statusValue = get_post_meta($post->ID, 'amp_cta_bar_options', true);
		$text = isset( $statusValue['status'] ) ? $statusValue['status']: '';
		$default = '';
		if($text == ''){
			$default = "checked='checked'";
		}
		?>
		<label class="radio-inline">
      		<input type="radio" name="amp_cta_bar_options[status]" value="active" <?php echo ($text == 'active')? 'checked="checked"':''; ?> <?php echo $default;?>>Active
    	</label>
    	&nbsp;&nbsp;&nbsp;&nbsp;
    	<label class="radio-inline">
      		<input type="radio" name="amp_cta_bar_options[status]" value="inactive" <?php echo ($text == 'inactive')? 'checked="checked"':'';?>>InActive
    	</label>
		<?php
	}
	public static function amp_cta_bar_post_type_generator(){
		$post_types = '';
    	$post_types = get_post_types( array( 'public' => true ), 'names' );

    	// Remove Unsupported Post types
    	unset($post_types['attachment'], $post_types['amp_acf']);

    	return $post_types;
	}
	public static function amp_cta_bar_select_field_handler($data = '', $saved_data= '', $current_number = '', $current_group_number ='') {
		include AMPFORWP_CTA_PLUGIN_DIR."amp-cta-bar-ajax-selectbox.php";
	}
	public static function amp_cta_bar_ajax_select_taxonomy_handler($selectedParentValue = '',$selectedValue='', $current_number ='', $current_group_number  = ''){
	    $is_ajax = false;
	    if( $_SERVER['REQUEST_METHOD']=='POST'){
	        $is_ajax = true;
	        if(wp_verify_nonce($_POST["amp_cta_bar_call_nonce"],'amp_cta_bar_select_action_nonce')){
	              if(isset($_POST['id'])){
	                $selectedParentValue = sanitize_text_field(wp_unslash($_POST['id']));
	              }
	              if(isset($_POST['number'])){
	                $current_number = intval($_POST['number']);
	              }
	              if ( isset( $_POST["group_number"] ) ) {
	              $current_group_number   = intval($_POST["group_number"]);
	              }
	        }else{
	            exit;
	        } 
	       wp_die();      
	    }
	    $taxonomies = array(); 
	    if($selectedParentValue == 'all'){
	    $taxonomies =  get_terms( array(
	                        'hide_empty' => true,
	                    ) );    
	    }else{
	    $taxonomies =  get_terms($selectedParentValue, array(
	                        'hide_empty' => true,
	                    ) );    
	    }     
	    $choices = '<option value="all">'.esc_html__('All','amp-cta').'</option>';
	    foreach($taxonomies as $taxonomy) {
	      $sel="";
	      if($selectedValue == $taxonomy->slug){
	        $sel = "selected";
	      }
	      $choices .= '<option value="'.esc_attr($taxonomy->slug).'" '.esc_attr($sel).'>'.esc_html__($taxonomy->name,'amp-cta').'</option>';
	    }
	    $allowed_html = amp_cta_bar_expanded_allowed_tags();
	    echo '<select  class="widefat ajax-output-child" name="data_group_array[group-'. esc_attr($current_group_number) .'][data_array]['.esc_attr($current_number).'][key_4]">'. wp_kses($choices, $allowed_html).'</select>';
	    if($is_ajax){
	      die;
	    }
	}
	public function amp_cta_bar_conditional_fields_style_script() {
    	global $pagenow, $typenow;
	    if (is_admin() && $pagenow=='post-new.php' OR $pagenow=='post.php' && $typenow=='amp_cta_bar') {
	       	wp_register_script( 'amp_cta_bar_admin', plugin_dir_url(__FILE__) . '/assets/js/amp_cta_bar_admin.js', array( 'jquery'), AMP_CTA_VERSION, true );
	       // Localize the script with new data
			$data_array = array(
			  'ajax_url'    =>  admin_url( 'admin-ajax.php' ) 
			);
	      	wp_localize_script( 'amp_cta_bar_admin', 'amp_cta_bar_field_data', $data_array );
	      	wp_enqueue_script('amp_cta_bar_admin');
	    }
  	}
	public static function amp_cta_bar_options_save_post_page($post_id, $post){
		// Bail if we're doing an auto save
    	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		if ( "amp_cta_bar" != get_post_type( $post_id ) ) return;
		
		// if our nonce isn't there, or we can't verify it, bail
      	if( !isset( $_POST['amp_cta_bar_select_name_nonce'] ) || !wp_verify_nonce( $_POST['amp_cta_bar_select_name_nonce'], 'amp_cta_bar_select_action_nonce' ) ) return;

      	// if our current user can't edit this post, bail
    	if( !current_user_can( 'edit_post' ) ) return;

    	/*$post_data_array = array();

    	foreach($_POST['amp_cta_bar_options']['data_array'] as $post){
    		$post_data_array[] = array_map('sanitize_text_field', $post);  
    	}   */  
    	
    	$metavalue = array();
		if($post->post_status != 'auto-draft' && $post->post_status != 'trash'){
			/*if(isset($_POST['amp_cta_bar_options']['data_array'])){
				$amp_cta_bar_options['data_array'] = $post_data_array;
      			update_post_meta(  $post_id, 'amp_cta_bar_options', $amp_cta_bar_options );
  			}*/

			if(isset($_POST['amp_cta_bar_options']) && !empty($_POST['amp_cta_bar_options'])){

				$metavalue = $_POST['amp_cta_bar_options'];
				$metavalue['cta_bar_content'] = $_POST['cta_bar_content'];
				update_post_meta($post_id, 'amp_cta_bar_options', $metavalue);
			}else{
				$metavalue = get_option('amp_cta_bar_default_options');
				update_post_meta($post_id, 'amp_cta_bar_options', $metavalue);
			}

	    	$meta_value = get_post_meta( $post_id, null, true );
	    	$post_data_group_array = array();  
		    $temp_condition_array  = array();
		    $show_globally =false;
		    if(isset($_POST['data_group_array'])){        
				$post_data_group_array = $_POST['data_group_array'];    
				foreach($post_data_group_array as $groups){        
				  	foreach($groups['data_array'] as $group ){              
					    if(array_search('show_globally', $group)){
					      	$temp_condition_array[0] =  $group;  
					      	$show_globally = true;              
					    }
					}
				}    
				if($show_globally){
					unset($post_data_group_array);
				$post_data_group_array['group-0']['data_array'] = $temp_condition_array;
				}      
			}
			if(isset($_POST['data_group_array'])){
				update_post_meta( $post_id, 'data_group_array', $post_data_group_array );
			}
		}
	}
	public function amp_cta_bar_colorpicker_scripts($hook){
		global $pagenow;
		if( ('post.php' == $pagenow || 'post-new.php' == $pagenow ) && 'amp_cta_bar' === get_post_type() ){
	        wp_enqueue_style( 'wp-color-picker');
	        wp_enqueue_script( 'wp-color-picker');
		}
	}
	public function amp_cta_bar_options_meta_box(){
		$screens = ['amp_cta_bar'];
	    foreach ($screens as $screen) {
	        add_meta_box(
	            'amp-cta-bar-options-metabox',           // Unique ID
	            esc_html__( 'AMP CTA Bar Settings','amp-cta' ),  // Box title
	            [__CLASS__,'amp_cta_bar_options_metabox_callback'],  // Content callback, must be of type callable
	            $screen,'normal', 'high', null                // Post type
	        );
	    }
	}
	public static function amp_cta_bar_options_metabox_callback(){
		global $post;
		require_once "amp-cat-bar-settings.php";
	}
	public function amp_cta_bar_conditional_fields_meta_box(){
		$screens = ['amp_cta_bar'];
	    foreach ($screens as $screen) {
			add_meta_box( 
				'amp-cta-bar-conditional-fields', 
				esc_html__( 'Display Conditions','amp-cta' ), 
				[__CLASS__,'amp_cta_bar_conditional_fields_callback'], 
				$screen,'normal', 'high', null 
			);
		}
	}
	
	public static function amp_cta_bar_conditional_fields_callback($post){
		//$data_group_array = esc_sql ( get_post_meta($post->ID, 'amp_cta_bar_options', true)  );
		$data_group_array = esc_sql ( get_post_meta($post->ID, 'data_group_array', true)  );
    	$data_group_array = is_array($data_group_array)? array_values($data_group_array): array();  
	 	if( empty( $data_group_array ) ) {
               $data_group_array[0] =array(
                   'data_array' => array(
	                    array(
	                    'key_1' => 'post_type',
	                    'key_2' => 'equal',
	                    'key_3' => 'none',
	                    )
           			)               
	      	);
    	}    
    
    //security check
    wp_nonce_field( 'amp_cta_bar_select_action_nonce', 'amp_cta_bar_select_name_nonce' );?>

    <?php 
    // Type Select    
      $choices = array(
        esc_html__("Basic",'amp-cta') => array(        
          'post_type'   =>  esc_html__("Post Type",'amp-cta'),
          'show_globally'   =>  esc_html__("Show Globally",'amp-cta'),  
          'user_type'   =>  esc_html__("Logged in User Type",'amp-cta'),
        ),
        esc_html__("Post",'amp-cta') => array(
          'post'      =>  esc_html__("Post",'amp-cta'),
          'post_category' =>  esc_html__("Post Category",'amp-cta'),
          'post_format' =>  esc_html__("Post Format",'amp-cta'), 
        ),
        esc_html__("Page",'amp-cta') => array(
          'page'      =>  esc_html__("Page",'amp-cta'), 
          'page_template' =>  esc_html__("Page Template",'amp-cta'),
        ),
        esc_html__("Other",'amp-cta') => array( 
          'ef_taxonomy' =>  esc_html__("Taxonomy Term",'amp-cta'), 
        )
      ); 

	$comparison = array(
		'equal'   =>  esc_html__( 'Equal to', 'amp-cta'), 
		'not_equal' =>  esc_html__( 'Not Equal to', 'amp-cta'),     
	);	
	
   //  if($data_array[0]['key_1'] == 'show_globally'){
   //  	$total_fields = 1;      
   //  } else{
   //  	$total_fields = count( $data_array );
  	// }

  	$total_group_fields = count( $data_group_array );
  	
  	?>
  	<div class="amp-cta-placement-groups">
  		<?php 
  		for ($j=0; $j < $total_group_fields; $j++) {
        	$data_array = $data_group_array[$j]['data_array'];
        	$total_fields = count( $data_array );
        ?>
        <div class="amp-cta-placement-group" name="data_group_array[<?php echo esc_attr( $j) ?>]" data-id="<?php echo esc_attr($j); ?>">           
		<?php 
			if($j>0){
				echo '<span style="margin-left:10px;font-weight:600">Or</span>';    
			}     
		?> 
      <table class="widefat amp-cta-placement-table">
        <tbody id="amp-cta-repeater-tbody" class="fields-wrapper-1">
        <?php  for ($i=0; $i < $total_fields; $i++) {  
          $selected_val_key_1 = $data_array[$i]['key_1']; 
          $selected_val_key_2 = $data_array[$i]['key_2']; 
          $selected_val_key_3 = $data_array[$i]['key_3'];
          $selected_val_key_4 = '';
          if(isset($data_array[$i]['key_4'])){
            $selected_val_key_4 = $data_array[$i]['key_4'];
          }
          ?>
          <tr class="toclone">
            <td style="width:31%" class="post_types"> 
              <select class="widefat select-post-type <?php echo esc_attr( $i );?>" name="data_group_array[group-<?php echo esc_attr( $j) ?>][data_array][<?php echo esc_attr( $i) ?>][key_1]">    
                <?php 
                foreach ($choices as $choice_key => $choice_value) { ?>         
                  <option disabled class="pt-heading" value="<?php echo esc_attr($choice_key);?>"> <?php echo esc_html__($choice_key,'amp-cta');?> </option>
                  <?php
                  foreach ($choice_value as $sub_key => $sub_value) { ?> 
                    <option class="pt-child" value="<?php echo esc_attr( $sub_key );?>" <?php selected( $selected_val_key_1, $sub_key );?> > <?php echo esc_html__($sub_value,'amp-cta');?> </option>
                    <?php
                  }
                } ?>
              </select>
            </td>
            <td style="width:31%; <?php if (  $selected_val_key_1 =='show_globally' ) { echo 'display:none;'; }  ?>">
              <select class="widefat comparison" name="data_group_array[group-<?php echo esc_attr( $j) ?>][data_array][<?php echo esc_attr( $i )?>][key_2]"> <?php
                foreach ($comparison as $key => $value) { 
                  $selcomp = '';
                  if($key == $selected_val_key_2){
                    $selcomp = 'selected';
                  }
                  ?>
                  <option class="pt-child" value="<?php echo esc_attr( $key );?>" <?php echo esc_attr($selcomp); ?> > <?php echo esc_html__($value,'amp-cta');?> </option>
                  <?php
                } ?>
              </select>
            </td>
            <td style="width:31%;<?php if (  $selected_val_key_1 =='show_globally' ) { echo 'display:none;'; }  ?>">
              <div class="insert-ajax-select">              
                <?php
                 self::amp_cta_bar_select_field_handler($selected_val_key_1, $selected_val_key_3,$i, $j );
                if($selected_val_key_1 == 'ef_taxonomy'){
                  self::amp_cta_bar_ajax_select_taxonomy_handler($selected_val_key_3, $selected_val_key_4, $i, $j);
                }
                ?>
                <div style="display:none;" class="spinner"></div>
              </div>
            </td>

            <td class="widefat amp-cta-structured-clone" style="width:3.5%; <?php if (  $selected_val_key_1 =='show_globally' ) { echo 'display:none;'; }  ?>">
            <span> <button type="button" class="button button-primary amp-cta-placement-button"> <?php echo esc_html__('And' ,'amp-cta');?> </button> </span> </td>
            
            <td class="widefat structured-delete" style="width:3.5%; <?php if (  $selected_val_key_1 =='show_globally' ) { echo 'display:none;'; }  ?>">
            <span> <button  type="button" class="button button-info amp-cta-placement-button"><span class="dashicons dashicons-trash"></span></button> </span> </td>         
          </tr>
          <?php
          } 
        ?>
        </tbody>
      </table>
      </div>
       <?php } ?>
    	<a style="margin-left: 8px; margin-bottom: 8px;" class="button  amp-cta-placement-or-group amp-cta-placement-button" href="#">Or</a>
    </div>      
      <style type="text/css">
        .option-table-class{width:100%;}
         .option-table-class tr td {padding: 10px 10px 10px 10px ;}
         .option-table-class tr > td{width: 30%;}
         .option-table-class tr td:last-child{width: 60%;}
         .option-table-class input[type="text"], select{width:100%;}
      </style>
    <?php
	}

	public function amp_cta_bar_type_meta_box(){
		$screens = ['amp_cta_bar'];
	    foreach ($screens as $screen) {
			add_meta_box( 
				'amp-cta-bar-style-type', 
				esc_html__( 'Advanced CTA Bar Settings','amp-cta' ), 
				[__CLASS__,'amp_cta_bar_type_callback'], 
				$screen,'normal', 'low', null 
			);
		}
	}
	public static function amp_cta_bar_type_callback(){ 
       global $post;
       $amp_cta_type = '';
       $post_ID = $post->ID;
		$amp_cta_bar_options = get_post_meta($post_ID, 'amp_cta_bar_options', true);
		$amp_cta_type = $amp_cta_bar_options['amp_cta_type'];
		?>
        <table class="form-table">
            <tbody class="amp-cta-bar-type options">
                <tr class="form-field">
                    <th scope="row">
                        <label for="amp_cta_type"><?php esc_attr_e('CTA Bar Type', 'amp-cta' ); ?></label>
                    </th>
                    <td>
                        <select class="regular-text" name="amp_cta_bar_options[amp_cta_type]" id="amp_cta_type">
                            <option value="cta_user_notify_amp" <?php echo ($amp_cta_type == 'cta_user_notify_amp')?"selected='selected'":"";?>> AMP component (&#60;amp-user-notification&#62;)</option>
                            <option value="cta_basic_style" <?php echo ( $amp_cta_type == 'cta_basic_style')? "selected='selected'":"";?>> Basic CSS</option>
                        </select>
                    </td>
                </tr>
            </tbody>
    </table>
    <?php
	}

	public static function amp_cta_bar_meta_box(){
		$screens = ['amp_cta_bar'];
	    foreach ($screens as $screen) {
	        add_meta_box(
	            'amp-cta-bar-metabox',           // Unique ID
	            'AMP CTA Bar',  // Box title
	            [__CLASS__,'amp_cta_bar_metabox_callback'],  // Content callback, must be of type callable
	            $screen,'side', 'high', null                // Post type
	        );
	    }
	}

	public static function amp_cta_bar_metabox_callback(){
		global $post;
        $ampforwp_cta_post_id = $post->ID;
        echo 'Its easy to use these options once you understand them.<br /><br /> If this is your first time, then please<br /><a style="background: #607D8B;border-radius: 16px;padding: 6px 15px;line-height: 1;text-decoration: none;display: inline-block;margin-top: 7px;color: #fff;"href="https://ampforwp.com/tutorials/how-to-add-call-to-action-in-amp/#bar" target="_blank">see this tutorial</a>';
	}
	public function amp_cta_bar_post_type(){
		// Set UI labels for Custom Post Type
	    $labels = array(
	        'name'                => _x( 'AMP CTA Bar', 'Post Type General Name', 'amp-cta' ),
	        'singular_name'       => _x( 'AMP CTA Bar', 'Post Type Singular Name', 'amp-cta' ),
	        'menu_name'           => __( 'AMP CTA', 'amp-cta' ),
	        'parent_item_colon'   => __( 'Parent AMP CTA Bar', 'amp-cta' ),
	        'all_items'           => __( 'CTA Bars', 'amp-cta' ),
	        'view_item'           => __( 'View AMP CTA Bar', 'amp-cta' ),
	        'add_new_item'        => __( 'Add New CTA Bar', 'amp-cta' ),
	        'add_new'             => __( 'Add New CTA Bar', 'amp-cta' ),
	        'edit_item'           => __( 'Edit AMP CTA Bar', 'amp-cta' ),
	        'update_item'         => __( 'Update AMP CTA Bar', 'amp-cta' ),
	        'search_items'        => __( 'Search AMP CTA Bar', 'amp-cta' ),
	        'not_found'           => __( 'Not Found', 'amp-cta' ),
	        'not_found_in_trash'  => __( 'Not found in Trash', 'amp-cta' ),
	    );

	    // Set other options for Custom Post Type
     
	    $args = array(
	        'label'               => __( 'ampctabars', 'amp-cta' ),
	        'description'         => __( 'AMP CTA Bar', 'amp-cta' ),
	        'labels'              => $labels,
	        // Features this CPT supports in Post Editor
	        'supports'            => array( 'title' ),
	        // You can associate this CPT with a taxonomy or custom taxonomy. 
	        'taxonomies'          => array( ),
	        /* A hierarchical CPT is like Pages and can have
	        * Parent and child items. A non-hierarchical CPT
	        * is like Posts.
	        */ 
	        'hierarchical'        => false,
	        'public'              => true,
	        'show_ui'             => true,
	        'show_in_nav_menus'   => true,
	        'show_in_admin_bar'   => false,
	        'menu_position'       => 5,
	        'can_export'          => true,
	        'has_archive'         => true,
	        'exclude_from_search' => true,
	        'publicly_queryable'  => false,
	        'capability_type'     => 'page',
	    );
     
    	// Registering your Custom Post Type
    	register_post_type( 'amp_cta_bar', $args );
    	flush_rewrite_rules();
	}
}

new Amp_CTA_Bar_Post_Type();

function amp_cta_bar_expanded_allowed_tags() {
            $my_allowed = wp_kses_allowed_html( 'post' );
            // form fields - input
            $my_allowed['input']  = array(
                    'class'        => array(),
                    'id'           => array(),
                    'name'         => array(),
                    'value'        => array(),
                    'type'         => array(),
                    'style'        => array(),
                    'placeholder'  => array(),
                    'maxlength'    => array(),
                    'checked'      => array(),
                    'readonly'     => array(),
                    'disabled'     => array(),
                    'width'        => array(),  
                    'data-id'      => array()
            );
            $my_allowed['hidden']  = array(                    
                    'id'           => array(),
                    'name'         => array(),
                    'value'        => array(),
                    'type'         => array(), 
                    'data-id'         => array(), 
            );
            //number
            $my_allowed['number'] = array(
                    'class'        => array(),
                    'id'           => array(),
                    'name'         => array(),
                    'value'        => array(),
                    'type'         => array(),
                    'style'        => array(),                    
                    'width'        => array(),                    
            ); 
            //textarea
             $my_allowed['textarea'] = array(
                    'class' => array(),
                    'id'    => array(),
                    'name'  => array(),
                    'value' => array(),
                    'type'  => array(),
                    'style'  => array(),
                    'rows'  => array(),                                                            
            );              
            // select
            $my_allowed['select'] = array(
                    'class'  => array(),
                    'id'     => array(),
                    'name'   => array(),
                    'value'  => array(),
                    'type'   => array(),                    
            );
            // checkbox
            $my_allowed['checkbox'] = array(
                    'class'  => array(),
                    'id'     => array(),
                    'name'   => array(),
                    'value'  => array(),
                    'type'   => array(),                    
            );
            //  options
            $my_allowed['option'] = array(
                    'selected' => array(),
                    'value' => array(),
            );                       
            // style
            $my_allowed['style'] = array(
                    'types' => array(),
            );
            return $my_allowed;
        }  
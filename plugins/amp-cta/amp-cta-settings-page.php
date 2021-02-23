<?php

//*****************************//
// AMP CTA settings Start here //
//*****************************//


if(is_plugin_active( 'amp/amp.php' )){
$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'              => 'redux_builder_amp', // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'          =>  __( 'AMPforWP CTA Options','accelerated-mobile-pages' ), // Name that appears at the top of your panel
    'menu_type'             => 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'        => true, // Show the sections below the admin menu item or not
    'menu_title'            => __( 'AMP CTA', 'accelerated-mobile-pages' ),
    'page_title'            => __('AMP CTA','accelerated-mobile-pages'),
    'global_variable'       => '', // Set a different name for your global variable other than the opt_name
    'dev_mode'              => false, // Show the time the page took to load, etc
    'customizer'            => false, // Enable basic customizer support,
    'async_typography'      => false, // Enable async for fonts,
    'disable_save_warn'     => true,
    'open_expanded'         => false,
    // OPTIONAL -> Give you extra features
    'page_priority'         => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'           => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'      => 'manage_options', // Permissions needed to access the options panel.
    'last_tab'              => '', // Force your panel to always open to a specific tab (by id)
    'page_icon'             => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
    'page_slug'             => 'amp_cta_options', // Page slug used to denote the panel
    'save_defaults'         => true, // On load save the defaults to DB before user clicks save or not
    'default_show'          => false, // If true, shows the default value next to each field that is not the default value.
    'default_mark'          => '', // What to print by the field's title if the value shown is default. Suggested: *
    'admin_bar'             => false,
    'admin_bar_icon'        => 'dashicons-admin-generic', 
    // CAREFUL -> These options are for advanced use only
    'output'                => false, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'            => false, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    //'domain'              => 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
    'footer_credit'         => false, // Disable the footer credit of Redux. Please leave if you can help it.
    'footer_text'           => "",
    'show_import_export'    => false,
    'system_info'           => true,

);

    $args['share_icons'][] = array(
        'url'   => 'https://github.com/ahmedkaludi/Accelerated-Mobile-Pages',
        'title' => __('Visit us on GitHub','accelerated-mobile-pages'),
        'icon'  => 'el el-github'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );


Redux::setArgs( "redux_builder_amp", $args );
}

		if ( ! function_exists( 'ampforwp_cta_settings' ) ) {
			function ampforwp_cta_settings($sections){
                   
        $ampctaposts = get_posts(array(
                        'posts_per_page' => -1,
                        'post_type' => 'amp-cta'
                     ));
        $cta_arr = array();
        $i = 0;
        $default_cta_key = '';
        foreach ($ampctaposts as $ampctapost) {
            
            if($i == 0){
                 $default_cta_key = $ampctapost->ID;
            }
            $cta_arr[$ampctapost->ID] = $ampctapost->post_title;

            $i++;
        }        

		$sections[] = array(
		      'title'      => __( 'AMP CTA', 'redux-framework-demo' ),
		      'icon' => 'el el-fire ',
					'id'	=> 'ampforwp-cta-subsection',
		      'desc'  => " ",
											);

		$sections[] = array(
		      'title'      => __( 'Introduction', 'redux-framework-demo' ),
 					'id'				=> 'ampforwp-cta-subsection-shortcode',
        'desc' => '<style>.cta_custom_wrapper{font-size:16px;line-height:1.5}
        .cta_custom_wrapper p{font-size:14px;line-height:1.5}
        .cta_custom_wrapper b{color:#111}
        .cta_box_img{height:178px !important;margin-bottom: -5px;}
        .cta_bar_img{height:83px !important;margin-bottom: -5px;border: 1px solid #ddd;}
        .amp_cta_btn{background: #607D8B;padding: 4px 10px;color: #fff;text-decoration: none;}
        .amp_cta_btn:hover{color:#fff;}
        </style>
            <h3>There are 2 kinds of CTA</h3> <div class=cta_custom_wrapper>
              <p><ol>
              <li><b>Box</b>: Which goes between the content. It can be delivered with the shortcode manually and automatically.<br />
              <p style=text-align:center><small>This is how the CTA box looks like:</small><br />
               <img class="cta_box_img" src="'.AMPFORWP_CTA_IMAGE_DIR . '/cta-box.png" width="427" height="178" /> </p> <p style=text-align:center>Do you want to see the tutorial for CTA Box?: <a href="https://ampforwp.com/tutorials/how-to-add-call-to-action-in-amp/#box" target="_blank" class="amp_cta_btn">View Tutorial</a></p>
              </li>


              <li style="margin-top: 35px;border-top: 1px solid #ccc;padding-top: 30px;"><b>Bar</b>: which sticks on the bottom of browser window through out the website.<br />
              <p style=text-align:center><small>This is how the CTA bar looks like:</small><br />
               <img class="cta_bar_img" src="'.AMPFORWP_CTA_IMAGE_DIR . '/cta-bar.png" width="442" height="83" /> </p> <p style=text-align:center>Do you want to see the tutorial for CTA Bar?: <a href="https://ampforwp.com/tutorials/how-to-add-call-to-action-in-amp/#bar" target="_blank" class="amp_cta_btn">View Tutorial</a></p>
              </li>


            </ol></p></div>',		      'subsection' => true
						);

		$sections[] = array(
		      'title'      => __( '1. BOX', 'redux-framework-demo' ),
		      'desc'       => __( '', 'redux-framework-demo'),
					'id'				=> 'ampforwp-cta-subsection-shortcode',
		      'desc'       => "<b>LEARN:</b> <a href='https://ampforwp.com/tutorials/how-to-add-call-to-action-in-amp/#box' target='_blank'>How to use CTA Box?</a>",
		      'subsection' => true,
                'fields'		=> array(
                array(
                    'id'        => 'ampforwp-cta-box-position',
                    'type'      => 'switch',
                    'title'     => 'Automatically Show CTA Box in content',
                    'default'   =>  0,
                    ),
                array(
                    'id'        => 'ampforwp-cta-variation-testing',
                    'type'      => 'switch',
                    'title'     => 'A/B Testing',
                    //'subtitle'  => 'Use UTM code in the URL of CTAs to track the stats',
                    'default'   =>  0,
                    'required'  => array('ampforwp-cta-box-position','=','1'),
                    ),
                array(
                        'id'       => 'ampforwp-cta-variation-notice',
                        'type'     => 'info',
                        'style'    => 'info',
                        'desc'     => __('This option is used to rotate the below selected CTAs, If you want to track them use UTM enabled URL for CTAs', 'accelerated-mobile-pages'),
                        'title'    => __('Notice:', 'accelerated-mobile-pages'),
                        'required' => array('ampforwp-cta-variation-testing', '=', 1)
                    ),
                array(
                    'id'       => 'ampforwp-cta-variations',
                    'type'     => 'select',
                    'title'    => __("Select CTA's to display for A/B Testing", 'accelerated-mobile-pages'),
                    'multi'    => true,
                    'data' => 'posts',
                    'args' => array(
                                'post_type'      => 'amp-cta',
                                'posts_per_page' => 500
                            ),
                    'required'  => array('ampforwp-cta-variation-testing','=','1'),
                    ),
                array(
                    'id'       => 'ampforwp-cta-box-selected-cta',
                    'type'     => 'select',
                    'title'    => __('Select a CTA to display', 'accelerated-mobile-pages'),
                    'options' => $cta_arr,
                    'required'  => array(array('ampforwp-cta-box-position','=','1'),
                                   array('ampforwp-cta-variation-testing','=','0')),
                    'default' => $default_cta_key,
                    ),
                array(
                    'id'        => 'ampforwp-cta-box-content-top',
                    'type'      => 'switch',
                    'title'     => 'Above Content',
                    'default'   =>  0,
                    'required'  => array('ampforwp-cta-box-position','=','1'),
                    ),
                array(
                    'id'        => 'ampforwp-cta-box-content',
                    'type'      => 'switch',
                    'title'     => 'Between the 50% content',
                    'default'   =>  0,
                    'required'  => array('ampforwp-cta-box-position','=','1'),
                    ),
                array(
                    'id'        => 'ampforwp-cta-box-content-bottom',
                    'type'      => 'switch',
                    'default'   =>  0,
                    'required'  => array('ampforwp-cta-box-position','=','1'),
                    'title'     => 'Below Content',
                    ),
                array(
                        'id'      => 'amp-cta-posttype-support',
                        'type'    => 'select',
                        'title'   => esc_html__('Post Types'),
                        'tooltip-subtitle'   => esc_html__('Enable AMP Support on required Post Types'),
                        'multi'   => true,
                        'required' => array('ampforwp-cta-box-position','=','1'),
                        //'data' => 'post_type',
                        'default' => ampforwp_supported_post_types_cta(isset($supported_types) ? $supported_types : ''),
                        'options' => ampforwp_supported_post_types_cta(isset($supported_types) ? $supported_types : ''),
                      ),
                array(
                    'id'        => 'ampforwp-exclude-cta-box-for-spec-post',
                    'type'      => 'select',
                    'multi'     => true,
                    'ajax'      => true, 
                    'data-action'     => 'ampforwp_posts', 
                    'data'      => 'posts',
                    'args'      => array(
                                        'post_type' => 'post',
                                        'posts_per_page' => -1
                                    ),
                    'default'   =>  0,
                    'required'  => array('ampforwp-cta-box-position','=','1'),
                    'title'     => 'Exclude CTA for specific posts',
                    'desc'      => 'Select specific Post to exclude the CTA'
                    ),
                array(
                    'id'        => 'ampforwp-exclude-cta-box-for-spec-page',
                    'type'      => 'select',
                    'multi'     => true,
                    'ajax'      => true, 
                    'data-action'     => 'ampforwp_pages', 
                    'data'      => 'pages',
                    'args'      => array(
                                        'post_type' => 'page',
                                        'posts_per_page' => -1
                                    ),
                    'default'   =>  0,
                    'required'  => array('ampforwp-cta-box-position','=','1'),
                    'title'     => 'Exclude CTA for specific pages',
                    'desc'      => 'Select specific Page to exclude the CTA'
                    ),
                array(
                    'id'        => 'ampforwp-cta-box-bg',
                    'type'      => 'color_rgba',
                    'title'     => 'Box Background',
                    'default'   => array( 'color' => '#FFFFFF'),
                    ),

                array(
                    'id'        => 'ampforwp-cta-box-title-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Title Color',
                    'default'   => array( 'color' => '#000000'),
                    ),

                array(
                    'id'        => 'ampforwp-cta-box-desc-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Description Color',
                    'default'   => array( 'color' => '#333333'),
                    ),

                array(
                    'id'        => 'ampforwp-cta-box-btnbg-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Button Background',
                    'default'   => array(
                        'color'     => '#F42F42',
                    ),
                    ),

                array(
                    'id'        => 'ampforwp-cta-box-btntxt-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Button Text Color',
                    'default'   => array(
                            'color'     => '#ffffff',
                    ),
                    ),

                ),

        );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active('amp-optin/amp-optin.php')){
    $args = array(
        'post_type'        => 'amp-optin',
        'post_status'      => 'publish',
        'posts_per_page' => -1,
    );
    $posts_array = get_posts( $args );
    $cat_with_optin_posts = array();
    foreach( $posts_array as $cta_optin ) {
        $cat_with_optin_posts[ $cta_optin->ID ] = $cta_optin->post_title;
    }
    $ampcta_with_optin[] = array(

                'id'=>'ampforwp-cta-bar-with-amp-optin',
                'title'=>__('CTA Bar with AMP Opt-in Forms', 'redux-framework-demo'),
                'type'=> 'switch',
                'default'=>0,
                'required' => array( 'ampforwp-cta-subsection-notification-sticky', '=','1'),

        );
    $ampcta_with_optin[] = array(
                    'id'       => 'ampforwp-cta-bar-with-amp-optin-select',
                    'type'     => 'select',
                    'title'    => __('Select the optin', 'accelerated-mobile-pages'),
                    'options'  => $cat_with_optin_posts, 
                    'required' => array('ampforwp-cta-bar-with-amp-optin','=','1'),
                    'default'  => '1',
         );
         
}
else{
    $ampcta_with_optin = '';
} 

$fields = array(      
            array(
                    'id' => 'ampforwp-cta-subsection-notification-sticky-sec',
                   'type' => 'section',
                   'title' => __('CTA Notification bar', 'redux-framework-demo'),
                   'indent' => true,
                   'layout_type' => 'accordion',
                   'accordion-open'=> 1,
            ),
            array(
                    'title'    => __('CTA Notification bar', 'redux-framework-demo'),
                    'id'                => 'ampforwp-cta-subsection-notification-sticky',
                    'type'     => 'switch',
                    'default'  => 0
                    ),
            array(

                    'id'=>'ampforwp-cta-bar-content',
                    'title'=>__('Content', 'redux-framework-demo'),
                    'type'=> 'switch',
                    'default'=>1,
                    'required' => array( 'ampforwp-cta-subsection-notification-sticky', '=','1'),

            ),
             array(
                'class' => 'child_opt',
                 'id'               => 'ampforwp-cta-subsection-notification-description',
                  'type'     => 'text',
                  'title'    => __('Title', 'redux-framework-demo'),
                  'default' => 'You can edit this default title from options',
                    'required' => array( 'ampforwp-cta-bar-content', '=','1')

            ),  
            array(
                  'id'       => 'ampforwp-cta-bar-position',
                  'type'     => 'select',
                  'title'    => __('Bar Location', 'redux-framework-demo'),
                  'options'   => array(
                                '1' => __('Top', 'accelerated-mobile-pages' ),
                                '2' => __('Bottom', 'accelerated-mobile-pages' ),
                                ),
                  'default'  => '2',
                  'required' => array( 'ampforwp-cta-subsection-notification-sticky', '=','1'),

            ),
            
            array(
                        'id' => 'ampforwp-cta-primary-button-sec',
                       'type' => 'section',
                       'title' => __('Primary Button', 'redux-framework-demo'),
                       'indent' => true,
                       'layout_type' => 'accordion',
                       'accordion-open'=> 1,
                        'required' => array(
                                    array( 'ampforwp-cta-subsection-notification-sticky', '=','1'),
                                )
            ),
            array(
                            'title'    => __('Primary Button', 'redux-framework-demo'),
                            'id'                => 'ampforwp-cta-primary-button',
                            'type'     => 'switch',
                            'default'  => 1,
                            'required' => array( 'ampforwp-cta-subsection-notification-sticky', '=','1')
                    ),
             array(
                    'class' => 'child_opt',
                    'id'                => 'ampforwp-cta-subsection-notification-button-text',
                    'type'     => 'text',
                    'title'    => __('Button Text', 'redux-framework-demo'),
                    'default' => 'Click This',
                    'required' => array( 'ampforwp-cta-primary-button', '=','1')
            ),
             array(
                    'class' => 'child_opt',
                     'id'               => 'ampforwp-cta-subsection-notification-button-url',
                    'type'     => 'text',
                    'title'    => __('Button URL', 'redux-framework-demo'),
                    'default' => '#',
                    'required' => array(
                        array( 'ampforwp-cta-primary-button', '=','1'),
                        array('ampforwp-cta-bar-with-amp-optin', '=','0'),
                    ),
            ),
             array(
                    'class' => 'child_opt',
                    'id'                => 'ampforwp-cta-subsection-notification-button-url-target',
                    'type'     => 'switch',
                    'title'    => __('Open URL in new Tab ?', 'redux-framework-demo'),
                    'default' => 0,
                    'required' => array(
                        array( 'ampforwp-cta-primary-button', '=','1'),
                        array( 'ampforwp-cta-bar-with-amp-optin','=','0'),
                    ),
            ),
             array(
                        'id' => 'ampforwp-cta-secondary-button-sec',
                       'type' => 'section',
                       'title' => __('Secondary Button', 'redux-framework-demo'),
                       'indent' => true,
                       'layout_type' => 'accordion',
                       'accordion-open'=> 1,
                        'required' => array(
                                    array( 'ampforwp-cta-subsection-notification-sticky', '=','1'),
                                )
                ),

              array(
                        'title'    => __('Secondary Button', 'redux-framework-demo'),
                        'id'                => 'ampforwp-cta-secondary-button',
                        'type'     => 'switch',
                        'default'  => 0,
                        'required' => array( 'ampforwp-cta-subsection-notification-sticky', '=','1')
                    ),
             array(
                    'class' => 'child_opt',
                    'id'               => 'ampforwp-cta-secondary-button-text',
                    'type'     => 'text',
                    'title'    => __('Button Text', 'redux-framework-demo'),
                    'default' => 'Click This',
                    'required' => array( 'ampforwp-cta-secondary-button', '=','1')
            ),
             array(
                    'class' => 'child_opt',
                    'id'               => 'ampforwp-cta-secondary-button-url',
                    'type'     => 'text',
                    'title'    => __('Button URL', 'redux-framework-demo'),
                    'default' => '#',
                    'required' => array( 'ampforwp-cta-secondary-button', '=','1')
            ),
             array(
                    'class' => 'child_opt',
                    'id'               => 'ampforwp-cta-secondary-button-url-target',
                    'type'     => 'switch',
                    'title'    => __('Open URL in new Tab ?', 'redux-framework-demo'),
                    'default' => 0,
                    'required' => array( 'ampforwp-cta-secondary-button', '=','1')
            ),

             array(
                    'id' => 'ampforwp-cta-close-button-sec',
                   'type' => 'section',
                   'title' => __('Close Button', 'redux-framework-demo'),
                   'indent' => true,
                   'layout_type' => 'accordion',
                   'accordion-open'=> 1,
                    'required' => array(
                                    array( 'ampforwp-cta-subsection-notification-sticky', '=','1'),
                                )
            ),

            array(
                    'title'    => __('Close Button', 'redux-framework-demo'),
                    'id'       => 'ampforwp-cta-close-button',
                    'type'     => 'switch',
                    'default'  => 1,
                    'required' => array( 'ampforwp-cta-subsection-notification-sticky', '=','1')
            ),
            array(
                    'class' => 'child_opt',
                    'title'    => __('Close Button Type', 'redux-framework-demo'),
                    'id'       => 'ampforwp-cta-close-button-text',
                    'type'     => 'select',
                    'options'  => array(
                             '1' => __('X', 'accelerated-mobile-pages' ),
                             '2' => __('Custom Text', 'accelerated-mobile-pages' ),
                         ),
                    'default'  => 1,
                    'required' => array( 'ampforwp-cta-close-button', '=','1')
            ),
            array(
                        'class' => 'child_opt',
                        'id'       => 'ampforwp-cta-close-button-text-custom',
                        'type'     => 'text',
                        'title'    => __('Enter Text', 'accelerated-mobile-pages'),
                        'tooltip-subtitle' => __('Please enter the text to show on the Close Button', 'accelerated-mobile-pages'),
                        'default'   => '',
                        'required' => array( 'ampforwp-cta-close-button-text', '=','2')
            ),
         array(
                        'id'       => 'color-scheme-section',
                        'type'     => 'section',
                        'title'    => __('Color Scheme', 'accelerated-mobile-pages'), 
                        'indent' => true,
                        'required' => array(
                                    array( 'ampforwp-cta-subsection-notification-sticky', '=','1'),
                                ),
                        'layout_type' => 'accordion',
                        'accordion-open'=> 1,
                    ),

            array(
                    'id'        => 'ampforwp-cta-subsection-notification-text-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Title Text Color',
                    'default'   => array(
                            'color'     => '#555555',
                    ),
                    'required' => array( 'ampforwp-cta-subsection-notification-sticky', '=','1')
                ),
            array(
                    'id'        => 'ampforwp-cta-subsection-notification-background-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Bar Background',
                    'default'   => array(
                            'color'     => '#ffffff',
                    ),
                    'required' => array( 'ampforwp-cta-subsection-notification-sticky', '=','1')
                ),
            array(
                    'id'        => 'ampforwp-cta-subsection-notification-button-text-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Button Text Color',
                    'default'   => array(
                            'color'     => '#FFFFFF',
                    ),
                    'required' => array( 'ampforwp-cta-primary-button', '=','1')
                ),
            array(
                    'id'        => 'ampforwp-cta-subsection-notification-button-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Button Background',
                    'default'   => array(
                            'color'     => '#f42f42',
                    ),
                    'required' => array( 'ampforwp-cta-primary-button', '=','1')
                ),
            array(
                    'id'        => 'ampforwp-cta-secondary-button-text-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Secondary Button Text Color',
                    'default'   => array(
                            'color'     => '#FFFFFF',
                    ),
                    'required' => array( 'ampforwp-cta-secondary-button', '=','1')
                ),
            array(
                    'id'        => 'ampforwp-cta-secondary-button-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Secondary Button Background',
                    'default'   => array(
                            'color'     => '#555555',
                    ),
                    'required' => array( 'ampforwp-cta-secondary-button', '=','1')
                ),

            array(
                    'id'        => 'ampforwp-cta-close-button-text-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Close Button Text Color',
                    'default'   => array(
                            'color'     => '#666',
                    ),
                    'required' => array(
                                    array( 'ampforwp-cta-close-button', '=','1'),
                                   // array('ampforwp-cta-close-button-text', '=', '1'),
                                   // array('ampforwp-cta-close-button-text', '=', '2')
                                )
                ),
            array(
                    'id'        => 'ampforwp-cta-close-button-color',
                    'type'      => 'color_rgba',
                    'title'     => 'Close Button Background',
                    'default'   => array(
                            'color'     => '#555555',
                    ),
                    'required' => array(
                                    array( 'ampforwp-cta-close-button', '=','1'),
                                    array('ampforwp-cta-close-button-text', '=', '2')
                                )
                ),
);
array_splice($fields, 5,0,$ampcta_with_optin);
 
 $sections[] = array(
     'title'      => __( '2. BAR', 'redux-framework-demo' ),
    'desc'       => "<b>LEARN:</b> <a href='https://ampforwp.com/tutorials/how-to-add-call-to-action-in-amp/#bar' target='_blank'>How to use CTA Bar?</a>",
    'id'				=> 'ampforwp-cta-subsection-notification',
    'subsection' => true,
     'fields'		=> array(   array(
                            'id'   => 'info_cta_bar',
                            'type' => 'info',
                            'desc' => '<div style="background: #FFF9C4;padding: 12px;line-height: 1.6;margin:-45px -14px -18px -17px;"><b>Note:</b> we have added a Unlimited CTA Bar feature,<a href="'.admin_url( 'edit.php?post_type=amp_cta_bar' ).'" target="_blank"> Go to the new options </a>.</div>',               
                               ),
                        )
    );
 

 $sections[] = array(
'title'      => __( '3. CONTACT BAR', 'redux-framework-demo' ),
'desc'       => "<b>LEARN:</b> <a href='https://ampforwp.com/tutorials/how-to-add-call-to-action-in-amp/#box' target='_blank'>How to use CTA Contact Bar?</a>",
'id'                => 'ampforwp-cta-contact-bar',
'subsection' => true,
'fields'        => array(
            array(
                'title' => __('CTA Contact Bar','redux-framework-demo'),
                'id'    => 'ampforwp-cta-contact-bar-sec',
                'type'  => 'section',
                'indent'=> true,
                'layout_type' => 'accordion',
                'accordion-open'=> 1,
            ),
            array(
                'title'    => __('Contact Bar', 'redux-framework-demo'),
                'id'       => 'ampforwp-cta-contact-bar-sticky',
                'type'     => 'switch',
                'default'  => 0,
            ),
            array(
                'title' => __('Call','redux-framework-demo'),
                'id'    => 'ampforwp-cta-contact-bar-call',
                'type'  => 'switch',
                'default' => 0,
                'required'=>array('ampforwp-cta-contact-bar-sticky','=','1'),

            ),
            array(
                'title' => __('Enter Phone Number','redux-framework-demo'),
                'desc'  => 'You must fill the number in the international model + country code and full number.',
                'id'    => 'ampforwp-cta-contact-bar-call-text',
                'class' => 'child_opt child_opt_arrow',
                'type'  => 'text',
                'default'   => '',
                'required'=>array(
                    array('ampforwp-cta-contact-bar-sticky','=','1'),
                    array('ampforwp-cta-contact-bar-call','=','1'),
                ),

            ),
            array(
                'title' => __('Email','redux-framework-demo'),
                'id'    => 'ampforwp-cta-contact-bar-email',
                'type'  => 'switch',
                'default' => 0,
                'required'=>array('ampforwp-cta-contact-bar-sticky','=','1'),

            ),
            array(
                'title' => __('Enter Email','redux-framework-demo'),
                'id'    => 'ampforwp-cta-contact-bar-email-text',
                'class' => 'child_opt child_opt_arrow',
                'type'  => 'text',
                'default'   => '',
                'required'=>array(
                    array('ampforwp-cta-contact-bar-sticky','=','1'),
                    array('ampforwp-cta-contact-bar-email','=','1'),
                ),

            ),
            array(
                'title' => __('Skype','redux-framework-demo'),
                'id'    => 'ampforwp-cta-contact-bar-skype',
                'type'  => 'switch',
                'default' => 0,
                'required'=>array('ampforwp-cta-contact-bar-sticky','=','1'),

            ),
            array(
                'title' => __('Enter Skype ID','redux-framework-demo'),
                'id'    => 'ampforwp-cta-contact-bar-skype-text',
                'class' => 'child_opt child_opt_arrow',
                'type'  => 'text',
                'default'   => '',
                'required'=>array(
                    array('ampforwp-cta-contact-bar-sticky','=','1'),
                    array('ampforwp-cta-contact-bar-skype','=','1'),
                ),

            ),
            array(
                'title' => __('WhatsApp','redux-framework-demo'),
                'id'    => 'ampforwp-cta-contact-bar-whatsapp',
                'type'  => 'switch',
                'default' => 0,
                'required'=>array('ampforwp-cta-contact-bar-sticky','=','1'),

            ),
            array(
                'title' => __('Enter WhatsApp Number','redux-framework-demo'),
                'desc'  => 'You must fill the number in the international model + country code and full number.',
                'id'    => 'ampforwp-cta-contact-bar-whatsapp-text',
                'class' => 'child_opt child_opt_arrow',
                'type'  => 'text',
                'default'   => '',
                'required'=>array(
                    array('ampforwp-cta-contact-bar-sticky','=','1'),
                    array('ampforwp-cta-contact-bar-whatsapp','=','1'),
                ),

            ),
        array(
                'title' => __('Color Scheme','redux-framework-demo'),
                'id'    => 'ampforwp-cta-contact-bar-color-scheme',
                'type'  => 'section',
                'indent'=> true,
                'layout_type' => 'accordion',
                'accordion-open'=> 1,
                'required' => array(
                                    array( 'ampforwp-cta-contact-bar-sticky', '=','1'),
                                )
            ),
        array(
                    'title'     => __('Contact Bar background','redux-framework-demo'),
                    'id'        => 'ampforwp-cta-contact-bar-bg-color',
                    'type'      => 'color_rgba',
                    'default'   => array(
                            'color'     => '#0367bf',
                    ),
                    'required' => array(
                                    array( 'ampforwp-cta-contact-bar-sticky', '=','1'),
                                )
                ),

        ),
);

return $sections;
}
}
add_filter("redux/options/redux_builder_amp/sections", 'ampforwp_cta_settings');

add_filter('pre_amp_render_post','ampforwp_supported_post_types_cta');
function ampforwp_supported_post_types_cta($supported_types) {
  $supported_types = '' ;
  if(function_exists('ampforwp_get_all_post_types')){
     $supported_types =  ampforwp_get_all_post_types();
     unset($supported_types['category']);
    }
    return $supported_types;
}
//*****************************//
// AMP CTA settings Start here //
//*****************************//
<?php
/*
Plugin Name: AMP CTA
Plugin URI: https://ampforwp.com/call-to-action/
Description: Call to Action, also known as CTA helps you get your message, product or offering to your visitors in AMP
Version: 2.3.10
Author: Mohammed Khaled, Ahmed Kaludi
Author URI: https://ampforwp.com/
Donate link: https://www.paypal.me/Kaludi/5
License: GPL2
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'AMP_CTA_VERSION' ) ) {
    define( 'AMP_CTA_VERSION', '2.3.10' );
}

/*CTA Plugin Constant variables*/
define('AMPFORWP_CTA_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('AMPFORWP_CTA_PLUGIN_DIR_URI', plugin_dir_url(__FILE__));
//define('AMPFORWP_CTA_IMAGE_DIR',plugin_dir_url(__FILE__).'assets/images');
define('AMPFORWP_CTA_MAIN_PLUGIN_DIR', plugin_dir_path( __DIR__ ) );

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'AMP_CTA_STORE_URL', 'https://accounts.ampforwp.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
define( 'AMP_CTA_ITEM_NAME', 'Call To Action for AMP' );

// the download ID. This is the ID of your product in EDD and should match the download ID visible in your Downloads list (see example below)
//define( 'AMPFORWP_ITEM_ID', 2502 );
// the name of the settings page for the license input to be displayed
define( 'AMP_CTA_LICENSE_PAGE', 'call-to-action-for-amp' );

if(! defined('AMP_CTA_ITEM_FOLDER_NAME')){
    $folderName = basename(__DIR__);
    define( 'AMP_CTA_ITEM_FOLDER_NAME', $folderName );
}


//custom prefix for Fuctions and variables : ampforwp_cta_
define('AMPFORWP_CTA_IMAGE_DIR',plugin_dir_url(__FILE__).'images');
//*************************************//
// Plugin activation check starts here //
//*************************************//

//Create amp-cta-bar Custom Post Type
require 'amp-cta-bar-post-type.php';

      include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  		if ( is_plugin_active( 'accelerated-mobile-pages/accelerated-moblie-pages.php' ) || is_plugin_active( 'amp/amp.php' )) {

        if(is_plugin_active( 'amp/amp.php' )){
          if ( !class_exists( 'ReduxFramework' ) ) {

              require_once dirname( __FILE__ ).'/includes/extensions/loader.php';
              require_once dirname( __FILE__ ).'/includes/redux-core/framework.php';
          }
          // require 'amp-cta-settings-page.php';
        }

        add_filter( 'plugin_action_links', 'ampforwp_cta_settings_link', 10, 5 );

      } else {
        add_filter( 'plugin_action_links', 'ampforwp_cta_plugin_activation_link', 10, 5 );
        // Return if Parent plugin is not active, and don't load the below code.
        return;
      }

      // Add Activate Parent Plugin button in settings page
        function ampforwp_cta_plugin_activation_link( $actions, $plugin_file ) {
          static $plugin;
          if (!isset($plugin))
            $plugin = plugin_basename(__FILE__);
            if ($plugin == $plugin_file) {
                $settings = array('settings' => '<a href="plugin-install.php?s=accelerated+mobile+pages&tab=search&type=term">' . __('Please Activate the Parent Plugin.', 'ampforwp_cta') . '</a>');
                $actions = array_merge($settings , $actions );
              }
            return $actions;
        }

    // Add settings Icon in the plugin activation page
      function ampforwp_cta_settings_link( $actions, $plugin_file )  {
          static $plugin;
          if (!isset($plugin))
            $plugin = plugin_basename(__FILE__);
            if ($plugin == $plugin_file) {
                $settings = array('settings' => '<a href="admin.php?page=amp_options&tab=19">' . __('Settings', 'ampforwp_cta') . '</a>');
                $actions = array_merge( $actions , $settings);
              }
            return $actions;
      }

//***********************************//
// Plugin activation check ends here //
//***********************************//



//**************************//
// AMP CTA short Start here //
//**************************//
    /**
    * //example
    * [amp-cta id="post ID"]
    */
    // Adding the Markup for CTA ShortCode
    function get_amp_cta_markup( $atts ) {
      // initializing these to avoid debug errors
      global $post;

      $atts[] = shortcode_atts( array(
          'src' => get_permalink($atts['id']),
      ), $atts );

      $url = get_post_meta( $atts['id'] , "_ampforwp_cta_btn_url" , true);
      $url_target = get_post_meta( $atts['id'] , "_ampforwp_cta_btn_url_target" , true);
      $title = get_the_title( $atts['id'] );
      $description = get_post_field('post_content', $atts['id']);
      $btn_text = get_post_meta( $atts['id'] , "_ampforwp_cta_btn_name" , true);
      $check_cta_box_image_icon = get_post_meta($atts['id'], '_ampforwp_cta_img_chck', true);
      $get_cta_box_image_icon = get_post_meta($atts['id'], '_ampforwp_cta_img_icon', true);

      $set_cta_box_image_icon = $image_icon_closing_div = '';
      if($check_cta_box_image_icon && $get_cta_box_image_icon){
        $set_cta_box_image_icon = '<div class="cta-icon amp-cta"><img src="'.$get_cta_box_image_icon.'" alt="CTA Image" class="amp-cta-icon" ></img></div><div class="cta-cont amp-cta">';
        add_action('amp_post_template_css','amp_cta_image_icon_styling', 20);
        $image_icon_closing_div = '</div>';
      }

      $content =  '
                    <div class="ampforwp-cta-wrapper">'.$set_cta_box_image_icon.'
                    <div class="ampforwp-cta-text">
                        <a href="'.$url.'"'. ( $url_target == 'yes' ? ' rel="nofollow" target="_blank" ':'').'>
                        <span class="cta-box-title">'.$title.'</span>
                        <p>'.$description.'</p></a>
                      </div>
                      <div class="ampforwp-button-wrapper">
                      <div class="ampforwp-button button ampforwp-in-article-block">
                          <a href="'.$url.'"'. ( $url_target == 'yes' ?' rel="nofollow" target="_blank" ':'').'>'.$btn_text.'</a>
                        </div>
                        </div>'.$image_icon_closing_div.'
                    </div>
                  ';


      add_action('amp_post_template_css','amp_cta_styling', 20);           
      // Add CTA only on AMP posts
      if ( function_exists('ampforwp_is_amp_endpoint') && ampforwp_is_amp_endpoint() ) {
        return $content;
      }
    }

    function amp_cta_image_icon_styling(){
      echo 'div.ampforwp-button-wrapper {
              float: none;
            }';
    }

    // Generating Short code for AMP CTA
    function ampforwp_cta_register_shortcodes() {
      add_shortcode('amp-cta', 'get_amp_cta_markup');
    }
    add_action( 'init', 'ampforwp_cta_register_shortcodes');


    // Adding the styling for AMP CTA
    function amp_cta_styling() {
      global $redux_builder_amp; ?>
.ampforwp-cta-wrapper{
    position: relative;
    background:<?php echo $redux_builder_amp['ampforwp-cta-box-bg']['color']?>;
    border: 1px solid #EEE;
    border-radius: 4px;
    box-shadow: 0 2px 12px rgba(0,0,0,.1);
    margin: 0px 0 25px 0;
    padding: 16px;
    clear: both;
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.amp-cta {
    flex: 1 0 100%;
}
.cta-icon {
    flex-basis: calc(25%);
    margin-right: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cta-icon amp-img {
    height: auto;
}
.cta-cont {
    flex-basis: calc(70%);
}
.ampforwp-cta-text{
    width:75%;
    display: inline-block;
}
.ampforwp-cta-wrapper .ampforwp-cta-text .cta-box-title{
    color: <?php echo $redux_builder_amp['ampforwp-cta-box-title-color']['color']?>;
    font-size: 18px;
    font-weight: 700;
    margin: 2px 0 10px 0;
    line-height: 1.1;
}
.ampforwp-cta-wrapper .ampforwp-cta-text p{
    font-size: 14px;
    color: <?php echo $redux_builder_amp['ampforwp-cta-box-desc-color']['color']?>;
    line-height: 1.5;
    margin-bottom: 0;
}
.ampforwp-button-wrapper{
  float: right;
  margin-top:5px
  }
.ampforwp-button a{
    background: <?php echo $redux_builder_amp['ampforwp-cta-box-btnbg-color']['color']?>;
    color: <?php echo $redux_builder_amp['ampforwp-cta-box-btntxt-color']['color']?>;
    border-radius: 3px;
    cursor: pointer;
    display: inline-block;
    font-size: 13px;
    font-weight: 700;
    line-height: 40px;
    padding: 0 16px;
}
/** Design 1,2,3,4 CSS***/
.ampforwp_cta_notification_bar{
    display: inline-block;
    width: 100%;
    text-align: center;
}
#amp-user-notification1 p{
  display:inline-block;
}
@media screen and (max-width: 767px){
.ampforwp-cta-text{float:none;width:100%}
.ampforwp-button-wrapper{float:none;width:100%}
.ampforwp-button a{margin-top: 1px;}

}

        <?php
    }

// creating amp-cta post type
require 'amp-cta-post-type.php';


//************************//
// AMP CTA short End here //
//************************//



//**************************//
//Setttings Page starts here//
//**************************//

require 'amp-cta-settings-page.php';

require 'output.php';


//**************************//
// Setttings Page ends here //
//**************************//



//***********************************//
// notifications section begins here //
//***********************************//
    function ampforwp_cta_user_notification_cta(){
      global $redux_builder_amp;
      $ampforwp_all_cta_bars = amp_cta_bar_get_all_schema_posts();
      
      if($ampforwp_all_cta_bars){
        foreach( $ampforwp_all_cta_bars as $ctabaroptions ){
        $cta_bar_id = $ctabaroptions['post_id'];
        $amp_cta_bar_options = $ctabaroptions['cta_bar_options'];
        $bar_location = $amp_cta_bar_options['bar_location'];
        $primary_link_target = $amp_cta_bar_options['primary_link_target'];
        $secondary_link_target = $amp_cta_bar_options['secondary_link_target'];
        $status = $amp_cta_bar_options['status'];
        $content = $amp_cta_bar_options['cta_bar_content'];
        $primary_btn_txt = $amp_cta_bar_options['primary_button_text'];
        $cta_bar_content = $amp_cta_bar_options['bar_description_btn'];
        $primary_btn = $amp_cta_bar_options['primary_btn'];
        $secondary_btn = $amp_cta_bar_options['secondary_btn'];
        $secondary_btn_txt = $amp_cta_bar_options['secondary_button_text'];
        $close_btn = $amp_cta_bar_options['close_button'];
        $close_btn_type = $amp_cta_bar_options['close_button_type'];
      
        if( $status == "active"){
        if($amp_cta_bar_options['amp_cta_type'] == 'cta_user_notify_amp'){  
        echo '<amp-user-notification layout=nodisplay class="ampforwp_cta_notification_bar" id="amp-user-notification_'.$cta_bar_id.'">';
        if( isset($cta_bar_content) && $cta_bar_content ==1 ){
          if(isset($content) && $content!=''){
              $content = '<p>'.$content.'</p>';
          }else{
              $content = '';
          }
        }else{
          $content = '';
        }

        if(isset($redux_builder_amp['ampforwp-cta-bar-with-amp-optin']) && 1 == $redux_builder_amp['ampforwp-cta-bar-with-amp-optin']){

            $button = ' <a href="#" on="tap:cta-optin-lightbox">'.$amp_cta_bar_options['primary_button_text'].'</a>'.$amp_cta_bar_options['status'];
            }
         else{
            $button = ' <a href="'.$amp_cta_bar_options['primary_button_url'].'" '. ( $primary_link_target ? ' target="_blank" ' : '' ) .' >'.$amp_cta_bar_options['primary_button_text'].'</a>';
         }
          if( isset($primary_btn) && $primary_btn==1){
            if(isset($primary_btn_txt) && $primary_btn_txt!=''){
              $primaryBtnTxt = '<div class="amp_notifi_de_btn amp_cta_notification_bar_btn">'.$button.'</div>';
            }else{
              $primaryBtnTxt = '';
            }  
          }else{
              $primaryBtnTxt = '';
          } 
          if(isset($secondary_btn) && $secondary_btn==1){
            if(isset($secondary_btn_txt) && $secondary_btn_txt!=''){
              $secondaryBtnTxt = '<div class="amp_notifi_de_btn ampforwp_cta_secondary_button">
                   <a href="'.$amp_cta_bar_options['secondary_button_url'].'" '. ( $secondary_link_target ? ' target="_blank" ' : '' ) .'>'.$amp_cta_bar_options['secondary_button_text'].'</a>
                 </div>';
            }else{
              $secondaryBtnTxt = '';
            }
          }else{
            $secondaryBtnTxt = '';
          }
          $closeBtnTxt = '';
          if(isset($close_btn) && $close_btn == 1){
            if($close_btn_type == 'x'){
              $closeBtnTxt = '<button class="ampforwp_cta_cls_icon" on="tap:amp-user-notification_'.$cta_bar_id.'.dismiss">X</button>';
            }elseif($close_btn_type == 'close_text'){
                $closeBtnTxt = '<button class="ampforwp_cls_btn" on="tap:amp-user-notification_'.$cta_bar_id.'.dismiss">'.$amp_cta_bar_options['close_btn_text'].'</button>';
            }else{
                $closeBtnTxt = '';
            }
          }
          echo $content.''.$primaryBtnTxt.''.$secondaryBtnTxt.''.$closeBtnTxt;
        echo '</amp-user-notification>';
        }
        else{
        echo '<div class="ampforwp_cta_notification_bar" id="amp-user-notification_'.$cta_bar_id.'">';
        if( isset($cta_bar_content) && $cta_bar_content ==1 ){
          if(isset($content) && $content!=''){
              $content = '<p>'.$content.'</p>';
          }else{
              $content = '';
          }
        }else{
          $content = '';
        }

        if(isset($redux_builder_amp['ampforwp-cta-bar-with-amp-optin']) && 1 == $redux_builder_amp['ampforwp-cta-bar-with-amp-optin']){

            $button = ' <a href="#" on="tap:cta-optin-lightbox">'.$amp_cta_bar_options['primary_button_text'].'</a>'.$amp_cta_bar_options['status'];
            }
         else{
            $button = ' <a href="'.$amp_cta_bar_options['primary_button_url'].'" '. ( $primary_link_target ? ' target="_blank" ' : '' ) .' >'.$amp_cta_bar_options['primary_button_text'].'</a>';
         }
          if( isset($primary_btn) && $primary_btn==1){
            if(isset($primary_btn_txt) && $primary_btn_txt!=''){
              $primaryBtnTxt = '<div class="amp_notifi_de_btn amp_cta_notification_bar_btn">'.$button.'</div>';
            }else{
              $primaryBtnTxt = '';
            }  
          }else{
              $primaryBtnTxt = '';
          } 
          if(isset($secondary_btn) && $secondary_btn==1){
            if(isset($secondary_btn_txt) && $secondary_btn_txt!=''){
              $secondaryBtnTxt = '<div class="amp_notifi_de_btn ampforwp_cta_secondary_button">
                   <a href="'.$amp_cta_bar_options['secondary_button_url'].'" '. ( $secondary_link_target ? ' target="_blank" ' : '' ) .'>'.$amp_cta_bar_options['secondary_button_text'].'</a>
                 </div>';
            }else{
              $secondaryBtnTxt = '';
            }
          }else{
            $secondaryBtnTxt = '';
          }
          echo $content.''.$primaryBtnTxt.''.$secondaryBtnTxt;
        echo '</div>';
        } 
       }
      }
    }
  }


    add_filter( 'amp_post_template_data', 'ampforwp_cta_add_notification_scripts',15);
    function ampforwp_cta_add_notification_scripts( $data ) {
    	global $redux_builder_amp;
       $lastposts = get_posts( array(
          'posts_per_page' => -1,
          'post_type'   => 'amp_cta_bar',
          'post_status' => 'publish',
          'order'       => 'ASC',
      ) );
 
      if ( $lastposts ) {
        //print_r($lastposts);
        foreach ( $lastposts as $post ){
          //post_content,post_title,post_name,post_type
          $amp_cta_bar_options = get_post_meta($post->ID, 'amp_cta_bar_options', true);
           
          if( $amp_cta_bar_options['status'] == "active"){
            if ( empty( $data['amp_component_scripts']['amp-user-notification'] ) ) {
                $data['amp_component_scripts']['amp-user-notification'] = 'https://cdn.ampproject.org/v0/amp-user-notification-0.1.js';
            }
          }
        }
      }
    	return $data;
    }

    function ampforwp_cta_notifications_styling() {
    	global $redux_builder_amp; 
      $ampforwp_all_cta_bars = amp_cta_bar_get_all_schema_posts();
      if($ampforwp_all_cta_bars){
      foreach ($ampforwp_all_cta_bars as $ctabaroptions) {
        $amp_cta_bar_options = $ctabaroptions['cta_bar_options'];
        $bar_bgcolor = $amp_cta_bar_options['bar_bgcolor'];
        $bar_bgcolor = (!empty($bar_bgcolor))? $bar_bgcolor:'#fff';
        $title_color = $amp_cta_bar_options['title_color'];
        $title_color = (!empty($title_color))? $title_color:'#000';
        $pr_btn_color = $amp_cta_bar_options['primary_btn_text_color'];
        $pr_btn_color = (!empty($pr_btn_color))? $pr_btn_color:'#fff';
        $pr_btn_bgcolor = $amp_cta_bar_options['primary_btn_bgcolor'];
        $pr_btn_bgcolor = (!empty($pr_btn_bgcolor))? $pr_btn_bgcolor:'#666666';
        $sec_btn_color = $amp_cta_bar_options['secondary_btn_text_color'];
        $sec_btn_color = (!empty($sec_btn_color))? $sec_btn_color:'#fff';
        $sec_btn_bgcolor = $amp_cta_bar_options['secondary_btn_bgcolor'];
        $sec_btn_bgcolor = (!empty($sec_btn_bgcolor))? $sec_btn_bgcolor:'#666666'; 
        $close_txt_color = $amp_cta_bar_options['close_btn_text_color'];
        $close_txt_color = (!empty($close_txt_color))? $close_txt_color:'#000';
        $close_btn_bgcolor = $amp_cta_bar_options['close_btn_bgcolor'];
        $close_btn_bgcolor = (!empty($close_btn_bgcolor))? $close_btn_bgcolor:'#fff';
        ?>
        div#amp-user-notification_<?php echo $ctabaroptions['post_id'];?>{
            position: fixed;
        }
        #amp-user-notification_<?php echo $ctabaroptions['post_id'];?>{
            background-color: <?php echo $bar_bgcolor;?>;
        }
        #amp-user-notification_<?php echo $ctabaroptions['post_id'];?>.ampforwp_cta_notification_bar p{ color:<?php echo $title_color;?> }
        <?php if(isset($amp_cta_bar_options['secondary_btn']) && $amp_cta_bar_options['secondary_btn'] == '1') { ?>
        #amp-user-notification_<?php echo $ctabaroptions['post_id'];?>.ampforwp_cta_notification_bar p {
          width: 100%;
          margin-bottom: 10px;
        } <?php } ?>
        #amp-user-notification_<?php echo $ctabaroptions['post_id'];?> .amp_cta_notification_bar_btn a {
            background-color: <?php echo $pr_btn_bgcolor;?>;
            color: <?php echo $pr_btn_color;?>;
        }
        #amp-user-notification_<?php echo $ctabaroptions['post_id'];?>  .ampforwp_cta_secondary_button a{
          background-color: <?php echo $sec_btn_bgcolor;?>;
          color: <?php echo $sec_btn_color;?>; 
        }
        #amp-user-notification_<?php echo $ctabaroptions['post_id'];?> .ampforwp_cta_cls_icon{
          background-color: <?php echo $close_btn_bgcolor;?>;
          color: <?php echo $close_txt_color;?>;
        }
        #amp-user-notification_<?php echo $ctabaroptions['post_id'];?>
         button.ampforwp_cls_btn{
          background-color: <?php echo $close_btn_bgcolor;?>;
          color: <?php echo $close_txt_color;?>;
        }
        #amp-user-notification_<?php echo $ctabaroptions['post_id'];?> {
            <?php if (isset($amp_cta_bar_options['bar_location']) && $amp_cta_bar_options['bar_location'] == "bottom" ) { ?> 
                    bottom:<?php echo $redux_builder_amp['enable-single-social-icons'] == 1? '40px':'0px' ?>; z-index:9998;
            <?php } 
            else {
                  if(isset($amp_cta_bar_options['bar_location']) && $amp_cta_bar_options['bar_location'] == "top" ) { ?>
                    top:0px;
                    bottom: auto;z-index:9998;
                  <?php }
            } ?>
        }
        <?php
    }
  }
    ?>
    <?php if ((isset($amp_cta_bar_options['bar_description_btn']) && $amp_cta_bar_options['bar_description_btn'] == 1 ) || (isset($amp_cta_bar_options['primary_btn']) && $amp_cta_bar_options['primary_btn'] == '1') ) { ?>
      .ampforwp_cta_notification_bar p{
        display: block;
        text-align: center;
        line-height: 1.0;
        position:relative;
      }
      .ampforwp_cta_notification_bar { 
        padding:13px 0px 13px 0px;
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        flex-wrap: wrap;
        box-shadow: 0 0 11px rgba(0,0,0,.15);         
      }
      .amp_notifi_de_btn{
        display: inline-block;
        margin-left: 10px;
        font-size: 12px;
        line-height: 1; position:relative;
      }

      .ampforwp_cta_notification_bar .ampforwp_cta_secondary_button a {
        padding: 6px 14px;
        border-radius: 43px;
        display:inline-block;
        text-decoration:none; 
      }
      .ampforwp_cta_notification_bar button{
          font-size: 12px;
          height: 20px;
          position: absolute;
          right: 6px;
          border: 0;
          border-radius: 10px;
        }
      .ampforwp_cta_notification_bar .amp_cta_notification_bar_btn a {
          padding: 6px 8px;
          border-radius: 43px;
          text-decoration:none;
          display:inline-block; 
      }
      <?php } ?>


          <?php /*global $redux_builder_amp;
          if( array_key_exists( 'ampforwp-cta-subsection-notification-sticky', $redux_builder_amp ) ) {
                if($redux_builder_amp['ampforwp-cta-subsection-notification-sticky']) { ?>
                    .sticky_social {  z-index: -10 }
                <?php }
            } */ ?>
      <?php if(isset($redux_builder_amp['ampforwp-cta-contact-bar-sticky']) && $redux_builder_amp['ampforwp-cta-contact-bar-sticky'] == 1) { ?>
.ampforwp_contact_bar_parent {
      position: fixed;
    left: 0;
    bottom: 0;
    line-height: 0;
    width: 100%;
    z-index: 99999;
        display: inline-block;
}
.ampforwp_contact_bar {
    background-color: <?php echo $redux_builder_amp['ampforwp-cta-contact-bar-bg-color']['color']?>;
    height: 40px;
    width: 100%;
}
.ampforwp_contact_bar ul{
      display: table;
    height: inherit;
    list-style-type: none;
    margin: 0 auto;
    max-width: 100%;
    padding: 0;
    table-layout: fixed;
    width: inherit;
    list-style: none;
}
.ampforwp_contact_bar ul li {
      display: table-cell;
    text-align: center;
    vertical-align: middle;
}
.bar_phone{ position:relative }
.bar_phone a:before { content: ""; display:inline-block; width: 4px; height: 8px; border-width: 6px 0 6px 3px; border-style: solid; border-color: #fff; background: transparent; transform: rotate(-30deg); box-sizing: initial; border-top-left-radius: 3px 5px; border-bottom-left-radius: 3px 5px; }

.ampforwp_contact_bar ul li a { color: #fff; }

#cta-optin-lightbox {z-index:10001;}
<?php } ?>
<?php if(isset($redux_builder_amp['ampforwp-cta-contact-bar-sticky']) && $redux_builder_amp['ampforwp-cta-contact-bar-sticky'] == 1) { ?>
  #footer {    padding: 35px 0 45px 0; }
<?php } ?>



<?php }

function ampforwp_cta_contact_bar(){
  global $redux_builder_amp;
  if(isset($redux_builder_amp['ampforwp-cta-contact-bar-sticky']) && $redux_builder_amp['ampforwp-cta-contact-bar-sticky'] == 1){ ?>
    <div class="ampforwp_contact_bar_parent" >
    <div class="ampforwp_contact_bar">
    <ul>
    <?php
      if ( isset($redux_builder_amp['ampforwp-cta-contact-bar-call']) && 1 == $redux_builder_amp['ampforwp-cta-contact-bar-call'] ) { ?>
        <li>
           
            <span class="amp-cta-contact-bar" id="amp-cta-cb"><a href="tel:<?php echo $redux_builder_amp['ampforwp-cta-contact-bar-call-text']; ?>"><amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjY0cHgiIGhlaWdodD0iNjRweCIgdmlld0JveD0iMCAwIDQwMS45OTggNDAxLjk5OCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDAxLjk5OCA0MDEuOTk4OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTQwMS4xMjksMzExLjQ3NWMtMS4xMzctMy40MjYtOC4zNzEtOC40NzMtMjEuNjk3LTE1LjEyOWMtMy42MS0yLjA5OC04Ljc1NC00Ljk0OS0xNS40MS04LjU2NiAgIGMtNi42NjItMy42MTctMTIuNzA5LTYuOTUtMTguMTMtOS45OTZjLTUuNDMyLTMuMDQ1LTEwLjUyMS01Ljk5NS0xNS4yNzYtOC44NDZjLTAuNzYtMC41NzEtMy4xMzktMi4yMzQtNy4xMzYtNSAgIGMtNC4wMDEtMi43NTgtNy4zNzUtNC44MDUtMTAuMTQtNi4xNGMtMi43NTktMS4zMjctNS40NzMtMS45OTUtOC4xMzgtMS45OTVjLTMuODA2LDAtOC41NiwyLjcxNC0xNC4yNjgsOC4xMzUgICBjLTUuNzA4LDUuNDI4LTEwLjk0NCwxMS4zMjQtMTUuNywxNy43MDZjLTQuNzU3LDYuMzc5LTkuODAyLDEyLjI3NS0xNS4xMjYsMTcuN2MtNS4zMzIsNS40MjctOS43MTMsOC4xMzgtMTMuMTM1LDguMTM4ICAgYy0xLjcxOCwwLTMuODYtMC40NzktNi40MjctMS40MjRjLTIuNTY2LTAuOTUxLTQuNTE4LTEuNzY2LTUuODU4LTIuNDIzYy0xLjMyOC0wLjY3MS0zLjYwNy0xLjk5OS02Ljg0NS00LjAwNCAgIGMtMy4yNDQtMS45OTktNS4wNDgtMy4wOTQtNS40MjgtMy4yODVjLTI2LjA3NS0xNC40NjktNDguNDM4LTMxLjAyOS02Ny4wOTMtNDkuNjc2Yy0xOC42NDktMTguNjU4LTM1LjIxMS00MS4wMTktNDkuNjc2LTY3LjA5NyAgIGMtMC4xOS0wLjM4MS0xLjI4Ny0yLjE5LTMuMjg0LTUuNDI0Yy0yLTMuMjM3LTMuMzMzLTUuNTE4LTMuOTk5LTYuODU0Yy0wLjY2Ni0xLjMzMS0xLjQ3NS0zLjI4My0yLjQyNS01Ljg1MiAgIHMtMS40MjctNC43MDktMS40MjctNi40MjRjMC0zLjQyNCwyLjcxMy03LjgwNCw4LjEzOC0xMy4xMzRjNS40MjQtNS4zMjcsMTEuMzI2LTEwLjM3MywxNy43LTE1LjEyOCAgIGM2LjM3OS00Ljc1NSwxMi4yNzUtOS45OTEsMTcuNzAxLTE1LjY5OWM1LjQyNC01LjcxMSw4LjEzNi0xMC40NjcsOC4xMzYtMTQuMjczYzAtMi42NjMtMC42NjYtNS4zNzgtMS45OTctOC4xMzcgICBjLTEuMzMyLTIuNzY1LTMuMzc4LTYuMTM5LTYuMTM5LTEwLjEzOGMtMi43NjItMy45OTctNC40MjctNi4zNzQtNC45OTktNy4xMzljLTIuODUyLTQuNzU1LTUuNzk5LTkuODQ2LTguODQ4LTE1LjI3MSAgIGMtMy4wNDktNS40MjQtNi4zNzctMTEuNDctOS45OTUtMTguMTMxYy0zLjYxNS02LjY1OC02LjQ2OC0xMS43OTktOC41NjQtMTUuNDE1Qzk4Ljk4Niw5LjIzMyw5My45NDMsMS45OTcsOTAuNTE2LDAuODU5ICAgQzg5LjE4MywwLjI4OCw4Ny4xODMsMCw4NC41MjEsMGMtNS4xNDIsMC0xMS44NSwwLjk1LTIwLjEyOSwyLjg1NmMtOC4yODIsMS45MDMtMTQuNzk5LDMuODk5LTE5LjU1OCw1Ljk5NiAgIGMtOS41MTcsMy45OTUtMTkuNjA0LDE1LjYwNS0zMC4yNjQsMzQuODI2QzQuODYzLDYxLjU2NiwwLjAxLDc5LjI3MSwwLjAxLDk2Ljc4YzAsNS4xMzUsMC4zMzMsMTAuMTMxLDAuOTk5LDE0Ljk4OSAgIGMwLjY2Niw0Ljg1MywxLjg1NiwxMC4zMjYsMy41NzEsMTYuNDE4YzEuNzEyLDYuMDksMy4wOTMsMTAuNjE0LDQuMTM3LDEzLjU2YzEuMDQ1LDIuOTQ4LDIuOTk2LDguMjI5LDUuODUyLDE1Ljg0NSAgIGMyLjg1Miw3LjYxNCw0LjU2NywxMi4yNzUsNS4xMzgsMTMuOTg4YzYuNjYxLDE4LjY1NCwxNC41NiwzNS4zMDcsMjMuNjk1LDQ5Ljk2NGMxNS4wMywyNC4zNjIsMzUuNTQxLDQ5LjUzOSw2MS41MjEsNzUuNTIxICAgYzI1Ljk4MSwyNS45OCw1MS4xNTMsNDYuNDksNzUuNTE3LDYxLjUyNmMxNC42NTUsOS4xMzQsMzEuMzE0LDE3LjAzMiw0OS45NjUsMjMuNjk4YzEuNzE0LDAuNTY4LDYuMzc1LDIuMjc5LDEzLjk4Niw1LjE0MSAgIGM3LjYxNCwyLjg1NCwxMi44OTcsNC44MDUsMTUuODQ1LDUuODUyYzIuOTQ5LDEuMDQ4LDcuNDc0LDIuNDMsMTMuNTU5LDQuMTQ1YzYuMDk4LDEuNzE1LDExLjU2NiwyLjkwNSwxNi40MTksMy41NzYgICBjNC44NTYsMC42NTcsOS44NTMsMC45OTYsMTQuOTg5LDAuOTk2YzE3LjUwOCwwLDM1LjIxNC00Ljg1Niw1My4xMDUtMTQuNTYyYzE5LjIxOS0xMC42NTYsMzAuODI2LTIwLjc0NSwzNC44MjMtMzAuMjY5ICAgYzIuMTAyLTQuNzU0LDQuMDkzLTExLjI3Myw1Ljk5Ni0xOS41NTVjMS45MDktOC4yNzgsMi44NTctMTQuOTg1LDIuODU3LTIwLjEyNkM0MDEuOTksMzE0LjgxNCw0MDEuNzAzLDMxMi44MTksNDAxLjEyOSwzMTEuNDc1eiIgZmlsbD0iI0ZGRkZGRiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" width="20" height="20" alt="contact-bar-call"/></a></span>
          
        </li>
         <?php
      }

      if ( isset($redux_builder_amp['ampforwp-cta-contact-bar-email'] ) && $redux_builder_amp['ampforwp-cta-contact-bar-email'] ) { ?>
        <li>
          <div class="bar_email" id="amp-cta-eb">
            <a href="mailto:<?php echo $redux_builder_amp['ampforwp-cta-contact-bar-email-text']; ?>"> <amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI2LjkzNiAyNi45MzYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI2LjkzNiAyNi45MzY7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNjRweCIgaGVpZ2h0PSI2NHB4Ij4KPGc+Cgk8cGF0aCBkPSJNMCw0LjYxN3YwLjkxMnYxLjMxNHYxMy4yNTF2MC40NzZ2MS43NWgyNi45MzZ2LTEuNzV2LTAuNDc2VjYuODQ0VjUuNTI5VjQuNjE3SDB6IE0yNC42OTEsMTguNjA0ICAgbC02LjMzMi01LjUzOGw2LjMzMi00LjQ4NkMyNC42OTEsOC41OCwyNC42OTEsMTguNjA0LDI0LjY5MSwxOC42MDR6IE0yLjI0NCw4LjQ4NWw2LjQxNCw0LjU0N2wtNi40MTQsNS42OVY4LjQ4NXogTTEzLjUzMSwxMy43MzcgICBMMy44MDYsNi44NDRoMTkuNDU2TDEzLjUzMSwxMy43Mzd6IE0xMC41MzksMTQuMzY1bDIuOTgsMi4xMTRsMC4wMDcsMC4wMTFsMC4wMDUtMC4wMDRsMC4wMDQsMC4wMDRsMC4wMDYtMC4wMTFsMi45MzYtMi4wNzggICBsNi41MDksNS42OTRINC4wNzlMMTAuNTM5LDE0LjM2NXoiIGZpbGw9IiNGRkZGRkYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" width="20" height="20" alt="contact-bar-email" /> </a>
          </div>
        </li>

      <?php
      }

      if ( isset($redux_builder_amp['ampforwp-cta-contact-bar-skype']) && $redux_builder_amp['ampforwp-cta-contact-bar-skype'] ) { ?>
        <li>
         <span class="amp-cta-skype-bar" id="amp-cta-sb"><a href="skype:<?php echo $redux_builder_amp['ampforwp-cta-contact-bar-skype-text']; ?>"><amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjY0cHgiIGhlaWdodD0iNjRweCIgdmlld0JveD0iMCAwIDQzMC4xMjMgNDMwLjEyMyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDMwLjEyMyA0MzAuMTIzOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggaWQ9IlNreXBlIiBkPSJNNDEyLjE2NCwyNDYuMTk4YzEuNjA1LTEwLjE1NSwyLjQ1LTIwLjU0NCwyLjQ1LTMxLjE0OGMwLTExMC4yMTUtODkuMzQyLTE5OS41NTUtMTk5LjU2LTE5OS41NTUgICBjLTEwLjU3NiwwLTIwLjk5NSwwLjg3MS0zMS4xNDEsMi40NThDMTY1LjUzNCw2LjU4MSwxNDMuODQyLDAsMTIwLjU5NSwwQzUzLjk5NiwwLDAuMDA1LDUzLjk4NCwwLjAwNSwxMjAuNTkgICBjMCwyMy4yNDIsNi41ODUsNDQuOTE2LDE3Ljk1Miw2My4zMzJjLTEuNTczLDEwLjE3Mi0yLjQzOSwyMC41MjgtMi40MzksMzEuMTMyYzAsMTEwLjIyMyw4OS4zNCwxOTkuNTM2LDE5OS41MzIsMTk5LjUzNiAgIGMxMC41ODUsMCwyMS4wMDctMC44MTYsMzEuMTUyLTIuNDE3YzE4LjM5OCwxMS4zNSw0MC4wNzIsMTcuOTQ5LDYzLjMxNCwxNy45NDljNjYuNjE3LDAsMTIwLjYwMi01My45OTgsMTIwLjYwMi0xMjAuNjAyICAgQzQzMC4xMjMsMjg2LjI2OSw0MjMuNTQyLDI2NC42LDQxMi4xNjQsMjQ2LjE5OHogTTMwOS44MDEsMzA1LjgxYy04LjQ0MiwxMi4xNTMtMjAuODQzLDIxLjY1LTM3LjA0NywyOC40NzkgICBjLTE2LjIzNyw2Ljg0Ny0zNS40MjgsMTAuMjU0LTU3LjU5LDEwLjI1NGMtMjYuNTYyLDAtNDguNTU0LTQuNjQ4LTY1LjkxMy0xNC4wMzRjLTEyLjMwNS02LjcyMS0yMi4zMTMtMTUuNzM3LTMwLjAwNy0yNi45OCAgIGMtNy43MS0xMS4yNTItMTEuNjE5LTIyLjI3MS0xMS42MTktMzMuMDE1YzAtNi4zMzgsMi40MTctMTEuODUsNy4xMjItMTYuMzQ0YzQuNzU0LTQuNTI3LDEwLjg3Ny02Ljc5NiwxOC4xMDQtNi43OTYgICBjNS45MiwwLDExLjAwNSwxLjc4MywxNS4xNDUsNS4zMTZjNC4xMDksMy41MzIsNy41NTYsOC42NjcsMTAuMzc1LDE1LjQ0M2MzLjM3OSw3Ljg1LDcuMDQ5LDE0LjQxMiwxMC45NTQsMTkuNjQ4ICAgYzMuODgxLDUuMTcxLDkuMzQzLDkuNDg4LDE2LjQxNywxMi44NjdjNi45OTYsMy4zODgsMTYuMzA3LDUuMDgyLDI3Ljk1OCw1LjA4MmMxNS45NDUsMCwyOC44MzEtMy40NDksMzguNjkzLTEwLjI1MyAgIGM5LjkxMi02Ljg2NiwxNC42NzMtMTUuMjIsMTQuNjczLTI1LjMxNGMwLTguMDUxLTIuNTY3LTE0LjQ0NS03LjgzMS0xOS40M2MtNS4zMTItNS4wNS0xMi4xNzItOC44OTYtMjAuNjg1LTExLjU3OSAgIGMtOC41NDYtMi43MTEtMjAuMDItNS41ODYtMzQuMzk5LTguNjE1Yy0xOS4zMDgtNC4yMTQtMzUuNDU2LTkuMTE5LTQ4LjUzMy0xNC43NTJjLTEzLjA5NC01LjY1NC0yMy41MjItMTMuMzgzLTMxLjI1MS0yMy4xNDYgICBjLTcuNzQ1LTkuODU5LTExLjYwNy0yMi4xMTMtMTEuNjA3LTM2LjYyN2MwLTEzLjg0LDQuMDc3LTI2LjIxNywxMi4yMzktMzYuOTg5YzguMTU4LTEwLjgwMiwxOS45NjEtMTkuMDcsMzUuMzUxLTI0LjgyOSAgIGMxNS4zMzYtNS43NTcsMzMuMzkxLTguNjM3LDU0LjA3NS04LjYzN2MxNi41NDEsMCwzMC44NDksMS45MTQsNDIuOTYsNS43MjJjMTIuMDc4LDMuODM2LDIyLjE0Niw4Ljg5OCwzMC4xOTYsMTUuMjUgICBjNy45OTksNi4zMzgsMTMuODg1LDEzLjAyMywxNy42MTMsMjAuMDM4YzMuNzI1LDcuMDM2LDUuNjAxLDEzLjkwOCw1LjYwMSwyMC42MTRjMCw2LjIzNS0yLjQxNywxMS44OS03LjEyNywxNi44NDYgICBjLTQuNzA5LDQuOTYzLTEwLjczMyw3LjQ5My0xNy43NjgsNy40N2MtNi4zNDIsMC0xMS4zMTctMS40NjMtMTQuNzY3LTQuNTZjLTMuMzYtMi45OTYtNi44ODktNy43NjYtMTAuNzA2LTE0LjQxNCAgIGMtNC44MjUtOS4yNzYtMTAuNjA0LTE2LjUwMy0xNy4zMjQtMjEuNjcyYy02LjU0My01LjA3My0xNy4zMzgtNy43MTQtMzIuMzU5LTcuNjgyYy0xMy44OTgsMC0yNS4wMTgsMi44NC0zMy40MTEsOC4zOTQgICBjLTguNDQ1LDUuNjQzLTEyLjQ4MiwxMi4xOS0xMi41MDEsMTkuODYxYzAuMDE0LDQuODA3LDEuMzk2LDguODYsNC4xNzcsMTIuMzI2YzIuODI0LDMuNDk4LDYuNzUzLDYuNTI5LDExLjgwMyw5LjA4NCAgIGM1LjA1MiwyLjU5NSwxMC4xOTEsNC42MDIsMTUuMzU1LDYuMDQ2YzUuMjI1LDEuNDgxLDEzLjg5NCwzLjYyMiwyNS45NDQsNi40NTdjMTUuMTEyLDMuMjU3LDI4LjgxOSw2Ljg5Niw0MS4xMzYsMTAuODYyICAgYzEyLjI5MywzLjk4NiwyMi43ODQsOC43OTMsMzEuNDUxLDE0LjUyYzguNzM2LDUuNzEsMTUuNTY0LDEyLjk5LDIwLjQzOCwyMS43ODNjNC45MDksOC44MDcsNy4zNTQsMTkuNTY0LDcuMzU0LDMyLjIyMiAgIEMzMjIuNTA5LDI3OS45NjgsMzE4LjI2MywyOTMuNjc2LDMwOS44MDEsMzA1LjgxeiIgZmlsbD0iI0ZGRkZGRiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" width="20" height="20" alt="contact-bar-skype"/></a></span>
       </li>
      <?php
      }  
      
      if ( isset($redux_builder_amp['ampforwp-cta-contact-bar-whatsapp']) && $redux_builder_amp['ampforwp-cta-contact-bar-whatsapp'] ) { ?>
        <li>
          <?php 
        $str = $redux_builder_amp['ampforwp-cta-contact-bar-whatsapp-text'];
        $str = str_replace(array("(",")","+","-"," "),array("","","","",""), $str);
          ?>
         <span class="amp-cta-whatsapp-bar" id="amp-cta-wb"><a href="https://api.whatsapp.com/send?phone=<?php echo $str ?>" ><amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgOTAgOTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDkwIDkwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggaWQ9IldoYXRzQXBwIiBkPSJNOTAsNDMuODQxYzAsMjQuMjEzLTE5Ljc3OSw0My44NDEtNDQuMTgyLDQzLjg0MWMtNy43NDcsMC0xNS4wMjUtMS45OC0yMS4zNTctNS40NTVMMCw5MGw3Ljk3NS0yMy41MjIgICBjLTQuMDIzLTYuNjA2LTYuMzQtMTQuMzU0LTYuMzQtMjIuNjM3QzEuNjM1LDE5LjYyOCwyMS40MTYsMCw0NS44MTgsMEM3MC4yMjMsMCw5MCwxOS42MjgsOTAsNDMuODQxeiBNNDUuODE4LDYuOTgyICAgYy0yMC40ODQsMC0zNy4xNDYsMTYuNTM1LTM3LjE0NiwzNi44NTljMCw4LjA2NSwyLjYyOSwxNS41MzQsNy4wNzYsMjEuNjFMMTEuMTA3LDc5LjE0bDE0LjI3NS00LjUzNyAgIGM1Ljg2NSwzLjg1MSwxMi44OTEsNi4wOTcsMjAuNDM3LDYuMDk3YzIwLjQ4MSwwLDM3LjE0Ni0xNi41MzMsMzcuMTQ2LTM2Ljg1N1M2Ni4zMDEsNi45ODIsNDUuODE4LDYuOTgyeiBNNjguMTI5LDUzLjkzOCAgIGMtMC4yNzMtMC40NDctMC45OTQtMC43MTctMi4wNzYtMS4yNTRjLTEuMDg0LTAuNTM3LTYuNDEtMy4xMzgtNy40LTMuNDk1Yy0wLjk5My0wLjM1OC0xLjcxNy0wLjUzOC0yLjQzOCwwLjUzNyAgIGMtMC43MjEsMS4wNzYtMi43OTcsMy40OTUtMy40Myw0LjIxMmMtMC42MzIsMC43MTktMS4yNjMsMC44MDktMi4zNDcsMC4yNzFjLTEuMDgyLTAuNTM3LTQuNTcxLTEuNjczLTguNzA4LTUuMzMzICAgYy0zLjIxOS0yLjg0OC01LjM5My02LjM2NC02LjAyNS03LjQ0MWMtMC42MzEtMS4wNzUtMC4wNjYtMS42NTYsMC40NzUtMi4xOTFjMC40ODgtMC40ODIsMS4wODQtMS4yNTUsMS42MjUtMS44ODIgICBjMC41NDMtMC42MjgsMC43MjMtMS4wNzUsMS4wODItMS43OTNjMC4zNjMtMC43MTcsMC4xODItMS4zNDQtMC4wOS0xLjg4M2MtMC4yNy0wLjUzNy0yLjQzOC01LjgyNS0zLjM0LTcuOTc3ICAgYy0wLjkwMi0yLjE1LTEuODAzLTEuNzkyLTIuNDM2LTEuNzkyYy0wLjYzMSwwLTEuMzU0LTAuMDktMi4wNzYtMC4wOWMtMC43MjIsMC0xLjg5NiwwLjI2OS0yLjg4OSwxLjM0NCAgIGMtMC45OTIsMS4wNzYtMy43ODksMy42NzYtMy43ODksOC45NjNjMCw1LjI4OCwzLjg3OSwxMC4zOTcsNC40MjIsMTEuMTEzYzAuNTQxLDAuNzE2LDcuNDksMTEuOTIsMTguNSwxNi4yMjMgICBDNTguMiw2NS43NzEsNTguMiw2NC4zMzYsNjAuMTg2LDY0LjE1NmMxLjk4NC0wLjE3OSw2LjQwNi0yLjU5OSw3LjMxMi01LjEwN0M2OC4zOTgsNTYuNTM3LDY4LjM5OCw1NC4zODYsNjguMTI5LDUzLjkzOHoiIGZpbGw9IiNGRkZGRkYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" width="20" height="20" alt="contact-bar-whatsapp" /></a></span>

       </li>
      <?php
      } ?>

    </ul>
  </div>
</div>
<?php
  }
}

function ampforwp_cta_bar_css_condition(){
 $ampforwp_all_cta_bars = amp_cta_bar_get_all_schema_posts();
      if($ampforwp_all_cta_bars){
    add_action( 'amp_post_template_css' , 'ampforwp_cta_notifications_styling' , 20);
  }
}
add_action( 'wp' , 'ampforwp_cta_bar_css_condition' );

function ampforwp_cta_notifications_getting_hooked(){
    add_action( 'amp_post_template_footer' , 'ampforwp_cta_user_notification_cta' );
    add_action( 'amp_post_template_footer' , 'ampforwp_cta_contact_bar',22 );
    add_action( 'amp_post_template_css' , 'ampforwp_cta_notifications_styling' , 20);
    do_action('amp_footer_link');  
    remove_action('ampforwp_global_after_footer','ampforwp_footer');
}
add_action( 'amp_init' , 'ampforwp_cta_notifications_getting_hooked' );


//*********************************//
// notifications section ends here //
//*********************************//


//***************************//
// Updater code Starts here //
//**************************//


  /*
  Plugin Update Method
 */
require_once dirname( __FILE__ ) . '/updater/EDD_SL_Plugin_Updater.php';

// Check for updates
function amp_cta_plugin_updater() {

    // retrieve our license key from the DB
    //$license_key = trim( get_option( 'amp_ads_license_key' ) );
    $selectedOption = get_option('redux_builder_amp',true);
    $license_key = '';//trim( get_option( 'amp_ads_license_key' ) );
    $pluginItemName = '';
    $pluginItemStoreUrl = '';
    $pluginstatus = '';
    if( isset($selectedOption['amp-license']) && "" != $selectedOption['amp-license'] && isset($selectedOption['amp-license'][AMP_CTA_ITEM_FOLDER_NAME])){

       $pluginsDetail = $selectedOption['amp-license'][AMP_CTA_ITEM_FOLDER_NAME];
       $license_key = $pluginsDetail['license'];
       //$pluginItemName = $pluginsDetail['item_name'];
       $pluginItemStoreUrl = $pluginsDetail['store_url'];
       $pluginstatus = $pluginsDetail['status'];
    }
    
    // setup the updater
    $edd_updater = new AMP_CTA_EDD_SL_Plugin_Updater( AMP_CTA_STORE_URL, __FILE__, array(
            'version'   => AMP_CTA_VERSION,                // current version number
            'license'   => $license_key,                        // license key (used get_option above to retrieve from DB)
            'license_status'=>$pluginstatus,
            'item_name' => AMP_CTA_ITEM_NAME,          // name of this plugin
            'author'    => 'Mohammed Kaludi',                   // author of this plugin
            'beta'      => false,
        )
    );
}
add_action( 'admin_init', 'amp_cta_plugin_updater', 0 );

// Notice to enter license key once activate the plugin

$path = plugin_basename( __FILE__ );
    add_action("after_plugin_row_{$path}", function( $plugin_file, $plugin_data, $status ) {
        global $redux_builder_amp;
        if(! defined('AMP_CTA_ITEM_FOLDER_NAME')){
        $folderName = basename(__DIR__);
            define( 'AMP_CTA_ITEM_FOLDER_NAME', $folderName );
        }
        $pluginstatus = '';
        if(isset($redux_builder_amp['amp-license'][AMP_CTA_ITEM_FOLDER_NAME])){
        $pluginsDetail = $redux_builder_amp['amp-license'][AMP_CTA_ITEM_FOLDER_NAME];
        $pluginstatus = $pluginsDetail['status'];
      }
       if(empty($redux_builder_amp['amp-license'][AMP_CTA_ITEM_FOLDER_NAME]['license'])){
            echo "<tr class='active'><td>&nbsp;</td><td colspan='2'><a href='".esc_url(  self_admin_url( 'admin.php?page=amp_options&tabid=opt-go-premium' )  )."'>Please enter the license key</a> to get the <strong>latest features</strong> and <strong>stable updates</strong></td></tr>";
               }elseif($pluginstatus=="valid"){
                $update_cache = get_site_transient( 'update_plugins' );
            $update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();
            if(isset($update_cache->response[ AMP_CTA_ITEM_FOLDER_NAME ]) 
                && empty($update_cache->response[ AMP_CTA_ITEM_FOLDER_NAME ]->download_link) 
             ){
               unset($update_cache->response[ AMP_CTA_ITEM_FOLDER_NAME ]);
            }
            set_site_transient( 'update_plugins', $update_cache );
            
        }
    }, 10, 3 );

//***************************//
// Updater code ends here //
//**************************//
<?php
/**
 * Plugin Name: Simple Lightbox Gallery - Responsive Lightbox Gallery 
 * Version: 1.7.8
 * Description: Simple Lightbox Gallery plugin is allow users to view larger versions of images, simple slide shows and Gallery view with grid layout.
 * Author: Weblizar
 * Author URI: https://www.weblizar.com
 * Plugin URI: https://weblizar.com/
 */

/**
 * Constant Variable
 */
defined( 'ABSPATH' ) or die();
define( "WEBLIZAR_SLGF_TEXT_DOMAIN", "weblizar_image_gallery" );
define( "WEBLIZAR_SLGF_PLUGIN_URL", plugin_dir_url( __FILE__ ) );

// Image Crop Size Function 
add_image_size( 'slgf_12_thumb', 500, 9999, array( 'center', 'top' ) );
add_image_size( 'slgf_346_thumb', 400, 9999, array( 'center', 'top' ) );
add_image_size( 'slgf_12_same_size_thumb', 500, 500, array( 'center', 'top' ) );
add_image_size( 'slgf_346_same_size_thumb', 400, 400, array( 'center', 'top' ) );

/**
 * Support and Our Products Page
 */
add_action( 'admin_menu', 'slgf_SettingsPage' );
function slgf_SettingsPage() {
	add_submenu_page( 'edit.php?post_type=slgf_slider', esc_html__( 'Help and Support', WEBLIZAR_SLGF_TEXT_DOMAIN ), esc_html__( 'Help and Support', WEBLIZAR_SLGF_TEXT_DOMAIN ), 'administrator', 'SLGF-help-page', 'SLGF_Help_and_Support_page' );
	add_submenu_page( 'edit.php?post_type=slgf_slider', esc_html__( 'Pro Screenshots', WEBLIZAR_SLGF_TEXT_DOMAIN ), esc_html__( 'Pro Screenshots', WEBLIZAR_SLGF_TEXT_DOMAIN ), 'administrator', 'SLGF-Pro-Plugin', 'SLGF_Pro_page_Function' );
	add_submenu_page( 'edit.php?post_type=slgf_slider', esc_html__( 'Recommendation', WEBLIZAR_SLGF_TEXT_DOMAIN ), esc_html__( 'Recommendation', WEBLIZAR_SLGF_TEXT_DOMAIN ), 'administrator', 'SLGF-Recommendation-Page', 'SLGF_Recommendation_Page' );
}

function SLGF_Help_and_Support_page() {
	wp_enqueue_style( 'wrgf-help_and_support-custom-css', WEBLIZAR_SLGF_PLUGIN_URL . 'css/help_and_support.css' );
	require_once( "help_and_support.php" );
}

/**
 * Get Responsive Gallery Pro Plugin Page
 */
function SLGF_Pro_page_Function() {
	//css
	//
	wp_enqueue_style( 'font-awesome', WEBLIZAR_SLGF_PLUGIN_URL . 'css/all.min.css' );
	wp_enqueue_style('wrgf-pricing-table-css', WEBLIZAR_SLGF_PLUGIN_URL.'css/pricing-table.css');
	wp_enqueue_style( 'bootstrap', WEBLIZAR_SLGF_PLUGIN_URL . 'css/bootstrap.min.css' );
	wp_enqueue_style( 'wrgf-get-lightbox-slider-pro-custom-css', WEBLIZAR_SLGF_PLUGIN_URL . 'css/get-lightbox-slider-pro-custom.css' );
	
	require_once( "get-lightbox-slider-pro.php" );
}

function SLGF_Recommendation_Page() {
	wp_enqueue_style( 'recom2', WEBLIZAR_SLGF_PLUGIN_URL . 'css/recom.css' );
	require_once( "recommendations.php" );
}

/**
 * Weblizar Lightbox Slider Pro Shortcode Detect Function
 */
function slgf_js_css_load_function() {
	global $wp_query;
	$Posts   = $wp_query->posts;
	$Pattern = get_shortcode_regex();
	foreach ( $Posts as $Post ) {
		if ( isset( $Post->post_content ) && strpos( $Post->post_content, 'SLGF' ) ) {
			/**
			 * js scripts
			 */
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script(  'wp-color-picker' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'wl-slgf-hover-pack-js', WEBLIZAR_SLGF_PLUGIN_URL . 'js/hover-pack.js', array( 'jquery' ) );
			wp_enqueue_script( 'wl-slgf-rpg-script', WEBLIZAR_SLGF_PLUGIN_URL . 'js/reponsive_photo_gallery_script.js', array( 'jquery' ) );


			//swipe box js css
			wp_enqueue_style( 'wl-slgf-swipe-css', WEBLIZAR_SLGF_PLUGIN_URL . 'lightbox/swipebox/swipebox.css' );
			wp_enqueue_script( 'wl-slgf-swipe-js', WEBLIZAR_SLGF_PLUGIN_URL . 'lightbox/swipebox/jquery.swipebox.js', array( 'jquery' ), '', true );

			/**
			 * css scripts
			 */
			wp_enqueue_style( 'wl-slgf-hover-pack-css', WEBLIZAR_SLGF_PLUGIN_URL . 'css/hover-pack.css' );
			// wp_enqueue_style( 'bootstrap', WEBLIZAR_SLGF_PLUGIN_URL . 'css/bootstrap.min.css' );
			wp_enqueue_style( 'wl-slgf-img-gallery-css', WEBLIZAR_SLGF_PLUGIN_URL . 'css/img-gallery.css' );

			/**
			 * font awesome css
			 */
			wp_enqueue_style( 'font-awesome-5', WEBLIZAR_SLGF_PLUGIN_URL . 'css/all.min.css' );

			/*** envira & isotope js ***/
			wp_enqueue_script( 'slgf_envira-js', WEBLIZAR_SLGF_PLUGIN_URL . 'js/masonry.pkgd.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'slgf_imagesloaded', WEBLIZAR_SLGF_PLUGIN_URL . 'js/imagesloaded.pkgd.min.js', array( 'jquery' ), '', true );

			break;
		} //end of if
	} //end of foreach
}

/** For the_title function **/
add_action( 'wp', 'slgf_js_css_load_function' );

add_filter( 'the_title', 'slgf_convac_lite_untitled' );
function slgf_convac_lite_untitled( $title ) {
	if ( $title == '' ) {
		return esc_html__( 'No Title', WEBLIZAR_SLGF_TEXT_DOMAIN );
	} else {
		return $title;
	}
}

function slgf_remove_image_box() {
	remove_meta_box( 'postimagediv', 'slgf_slider', 'side' );
}

add_action( 'do_meta_boxes', 'slgf_remove_image_box' );

/**
 * Class Defination For Lightbox Slider Pro
 */
class SLGF {

	private $admin_thumbnail_size = 150;
	private $thumbnail_size_w = 150;
	private $thumbnail_size_h = 150;

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( &$this, 'slgf_admin_print_scripts' ) );
		add_image_size( 'rpg_gallery_admin_thumb', $this->admin_thumbnail_size, $this->admin_thumbnail_size, true );
		add_image_size( 'rpg_gallery_thumb', $this->thumbnail_size_w, $this->thumbnail_size_h, true );
		add_shortcode( 'lightboxslider', array( &$this, 'shortcode' ) );

		if ( is_admin() ) {
			add_action( 'init', array( &$this, 'SLGF_CPT' ), 1 );
			add_action( 'add_meta_boxes', array( &$this, 'add_all_slgf_meta_boxes' ) );
			add_action( 'admin_init', array( &$this, 'add_all_slgf_meta_boxes' ), 1 );
			add_action( 'save_post', array( &$this, 'slgf_add_image_meta_box_save' ), 9, 1 );
			add_action( 'save_post', array( &$this, 'slgf_settings_meta_save' ), 9, 1 );
			add_action( 'wp_ajax_slgf_get_thumbnail', array( &$this, 'ajax_get_thumbnail_slgf' ) );
		}
	}

	//Required JS & CSS
	public function slgf_admin_print_scripts( $hook_suffix ) {
	    if ( in_array( $hook_suffix, array('post.php', 'post-new.php') ) ) {
	        $screen = get_current_screen();
	        if ( is_object( $screen ) && 'slgf_slider' === $screen->post_type ) {
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'slgf-media-uploader-js', WEBLIZAR_SLGF_PLUGIN_URL . 'js/slgf-multiple-media-uploader.js', array( 'jquery' ) );
				wp_enqueue_media();
				//custom add image box css
				wp_enqueue_style( 'slgf-meta-css', WEBLIZAR_SLGF_PLUGIN_URL . 'css/rpg-meta.css' );

				//font awesome css
				wp_enqueue_style( 'font-awesome-5', WEBLIZAR_SLGF_PLUGIN_URL . 'css/all.min.css' );

				//single media uploader js
				wp_enqueue_script( 'slgf-media-uploads', WEBLIZAR_SLGF_PLUGIN_URL . 'js/slgf-media-upload-script.js', array(
					'media-upload',
					'thickbox',
					'jquery'
				) );
				wp_enqueue_script(  'wp-color-picker' );
				// code-mirror css & js for custom css section
				wp_enqueue_style( 'slgf_codemirror-css', WEBLIZAR_SLGF_PLUGIN_URL . 'css/codemirror/codemirror.css' );
				wp_enqueue_style( 'slgf_blackboard', WEBLIZAR_SLGF_PLUGIN_URL . 'css/codemirror/blackboard.css' );
				wp_enqueue_style( 'slgf_show-hint-css', WEBLIZAR_SLGF_PLUGIN_URL . 'css/codemirror/show-hint.css' );

				wp_enqueue_script( 'slgf_codemirror-js', WEBLIZAR_SLGF_PLUGIN_URL . 'css/codemirror/codemirror.js', array( 'jquery' ) );
				wp_enqueue_script( 'slgf_css-js', WEBLIZAR_SLGF_PLUGIN_URL . 'css/codemirror/slgf-css.js', array( 'jquery' ) );
				wp_enqueue_script( 'slgf_css-hint-js', WEBLIZAR_SLGF_PLUGIN_URL . 'css/codemirror/css-hint.js', array( 'jquery' ) );
	        }
	    }
	}

	// Register Custom Post Type
	public function SLGF_CPT() {
		$labels = array(
			'name'               => esc_html__( 'Lightbox Gallery', 'Lightbox Slider Pro', 'slgf_slider' ),
			'singular_name'      => esc_html__( 'Lightbox Gallery', 'Lightbox Slider Pro', 'slgf_slider' ),
			'menu_name'          => esc_html__( 'Simple Lightbox Gallery', 'slgf_slider' ),
			'parent_item_colon'  => esc_html__( 'Parent Item:', 'slgf_slider' ),
			'all_items'          => esc_html__( 'All Galleries', 'slgf_slider' ),
			'view_item'          => esc_html__( 'View Gallery', 'slgf_slider' ),
			'add_new_item'       => esc_html__( 'Add New Lightbox Gallery', 'slgf_slider' ),
			'add_new'            => esc_html__( 'Add Lightbox Gallery', 'slgf_slider' ),
			'edit_item'          => esc_html__( 'Edit Lightbox Gallery', 'slgf_slider' ),
			'new_item'           => esc_html__( 'New Gallery', 'slgf_slider' ),
			'update_item'        => esc_html__( 'Update Lightbox Gallery', 'slgf_slider' ),
			'search_items'       => esc_html__( 'Search Gallery', 'slgf_slider' ),
			'not_found'          => esc_html__( 'No Lightbox Gallery Found', 'slgf_slider' ),
			'not_found_in_trash' => esc_html__( 'No Lightbox Gallery found in Trash', 'slgf_slider' ),
		);

		$args = array(
			'label'               => esc_html__( 'slgf_slider', WEBLIZAR_SLGF_TEXT_DOMAIN ),
			'description'         => esc_html__( 'Free Lightbox Gallery', WEBLIZAR_SLGF_TEXT_DOMAIN ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', '', '', '', '', '', '', '', '', '', ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 10,
			'menu_icon'           => 'dashicons-format-gallery',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'slgf_slider', $args );
		add_filter( 'manage_edit-slgf_slider_columns', array( &$this, 'slgf_gallery_columns' ) );
		add_action( 'manage_slgf_slider_posts_custom_column', array( &$this, 'slgf_gallery_manage_columns' ), 10, 2 );
	}

	//column fields on all galleries page
	function slgf_gallery_columns( $columns ) {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => esc_html__( 'Gallery', WEBLIZAR_SLGF_TEXT_DOMAIN ),
			'shortcode' => esc_html__( 'Gallery Shortcode', WEBLIZAR_SLGF_TEXT_DOMAIN ),
			'author'    => esc_html__( 'Author', WEBLIZAR_SLGF_TEXT_DOMAIN ),
			'date'      => esc_html__( 'Date', WEBLIZAR_SLGF_TEXT_DOMAIN )
		);

		return $columns;
	}

	//column action fields on all galleries page
	function slgf_gallery_manage_columns( $column, $post_id ) {
		global $post;
		switch ( $column ) {
			case 'shortcode' :
				echo '<input type="text" value="[SLGF id=' . $post_id . ']" readonly="readonly" />';
				break;
			default :
				break;
		}
	}

	// all metabox generator function
	public function add_all_slgf_meta_boxes() {
		add_meta_box( esc_html__( 'Add Images', WEBLIZAR_SLGF_TEXT_DOMAIN ), esc_html__( 'Add Images', WEBLIZAR_SLGF_TEXT_DOMAIN ), array(
			&$this,
			'slgf_generate_add_image_meta_box_function'
		), 'slgf_slider', 'normal', 'low' );
		add_meta_box( esc_html__( 'Apply Setting On Lightbox Gallery', WEBLIZAR_SLGF_TEXT_DOMAIN ), esc_html__( 'Apply Setting On Lightbox Gallery', WEBLIZAR_SLGF_TEXT_DOMAIN ), array(
			&$this,
			'slgf_settings_meta_box_function'
		), 'slgf_slider', 'normal', 'low' );
		add_meta_box( esc_html__( 'Lightbox Gallery Shortcode', WEBLIZAR_SLGF_TEXT_DOMAIN ), esc_html__( 'Lightbox Gallery Shortcode', WEBLIZAR_SLGF_TEXT_DOMAIN ), array(
			&$this,
			'slgf_shotcode_meta_box_function'
		), 'slgf_slider', 'side', 'low' );
		add_meta_box( esc_html__( 'Lightbox Slider Pro', WEBLIZAR_SLGF_TEXT_DOMAIN ), esc_html__( 'Lightbox Slider Pro', WEBLIZAR_SLGF_TEXT_DOMAIN ), array(
			&$this,
			'slgf_upgrade_to_pro_function'
		), 'slgf_slider', 'side', 'low' );
		add_meta_box( esc_html__( 'Rate us on WordPress', WEBLIZAR_SLGF_TEXT_DOMAIN ), esc_html__( 'Rate us on WordPress', WEBLIZAR_SLGF_TEXT_DOMAIN ), array(
			&$this,
			'slgf_rate_us_function'
		), 'slgf_slider', 'side', 'low' );
		
		wp_enqueue_style( 'font-awesome-5', WEBLIZAR_SLGF_PLUGIN_URL . 'css/all.min.css' );
	}

	/**    Rate us **/
	function slgf_rate_us_function() { ?>
        <div style="text-align:center">
            <h3><?php esc_html_e( "If you like our plugin then please show us some love", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></h3>
            <a class="wrg-rate-us" style="text-align:center; text-decoration: none;font:normal 30px;"
               href="http://wordpress.org/plugins/simple-lightbox-gallery/#reviews" target="_blank">
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
            </a>
            <div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
                <a href="https://wordpress.org/plugins/simple-lightbox-gallery/#reviews" target="_new"
                   class="button button-primary button-hero"><?php esc_html_e( "Click Here", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></a>
            </div>
        </div>
		<?php
	}

	/**    Upgarde to Pro **/
	function slgf_upgrade_to_pro_function() {
		$imgpath = WEBLIZAR_SLGF_PLUGIN_URL . "images/lbs_pro.jpg";
		?>
        <div class="">
            <div class="update_pro_button">
			<a target="_blank" href="https://weblizar.com/lightbox-slider-pro/"><?php esc_html_e( 'Buy Now $12', WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></a>
            </div>
            <div class="update_pro_image">
                <img class="slgf_getpro" src="<?php echo esc_url($imgpath); ?>">
            </div>
            <div class="update_pro_button">
                <a class="upg_anch" target="_blank"
                   href="https://weblizar.com/lightbox-slider-pro/"><?php esc_html_e( 'Buy Now $12', WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></a>
            </div>
        </div>
		<?php
	}

	/**
	 * This function display Add New Image interface
	 * Also loads all saved gallery photos into photo gallery
	 */
	public function slgf_generate_add_image_meta_box_function( $post ) { ?>
        <div id="rpggallery_container">
            <input type="hidden" id="slgf_wl_action" name="slgf_wl_action" value="wl-save-settings">
            <ul id="slgf_gallery_thumbs" class="clearfix">
				<?php
				/* Load saved photos into gallery */
				$SLGF_AllPhotosDetails = unserialize( get_post_meta( $post->ID, 'slgf_all_photos_details', true ) );
				$TotalImages           = get_post_meta( $post->ID, 'slgf_total_images_count', true );
				$i = 0;
				if ( $TotalImages ) {
					foreach ( $SLGF_AllPhotosDetails as $SLGF_SinglePhotoDetails ) {
						$name         = $SLGF_SinglePhotoDetails['slgf_image_label'];
						$UniqueString = substr( str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 5 );
						$url          = $SLGF_SinglePhotoDetails['slgf_image_url'];
						$url1         = $SLGF_SinglePhotoDetails['slgf_12_thumb'];
						$url2         = $SLGF_SinglePhotoDetails['slgf_346_thumb'];
						$url3         = $SLGF_SinglePhotoDetails['slgf_12_same_size_thumb'];
						$url4         = $SLGF_SinglePhotoDetails['slgf_346_same_size_thumb'];
						$img_desc     = $SLGF_SinglePhotoDetails['img_desc'];
						?>
                        <li class="rpg-image-entry" id="rpg_img">
                            <a class="gallery_remove lbsremove_bt" href="#gallery_remove" id="lbs_remove_bt"><img
                                        src="<?php echo WEBLIZAR_SLGF_PLUGIN_URL . 'images/Close-icon-new.png'; ?>"/></a>
                            <img src="<?php echo esc_url($url); ?>" class="rpg-meta-image" alt="">
                            <input type="button" id="upload-background-<?php echo esc_attr($UniqueString); ?>"
                                   name="upload-background-<?php echo esc_attr($UniqueString); ?>" value="Upload Image"
                                   class="button-primary " onClick="weblizar_image('<?php echo esc_attr($UniqueString); ?>')"/>
                            <input type="text" id="slgf_image_label[]" name="slgf_image_label[]"
                                   value="<?php echo html_entity_decode( $name, ENT_QUOTES, "UTF-8" ); ?>"
                                   placeholder="Enter Image Label" class="rpg_label_text">

                            <input type="text" id="slgf_image_url[]" name="slgf_image_url[]" class="rpg_label_text"
                                   value="<?php echo esc_url($url); ?>" readonly="readonly" style="display:none;"/>
                            <input type="text" id="slgf_image_url1[]" name="slgf_image_url1[]" class="rpg_label_text"
                                   value="<?php echo esc_url($url1); ?>" readonly="readonly" style="display:none;"/>
                            <input type="text" id="slgf_image_url2[]" name="slgf_image_url2[]" class="rpg_label_text"
                                   value="<?php echo esc_url($url2); ?>" readonly="readonly" style="display:none;"/>
                            <input type="text" id="slgf_image_url3[]" name="slgf_image_url3[]" class="rpg_label_text"
                                   value="<?php echo esc_url($url3); ?>" readonly="readonly" style="display:none;"/>
                            <input type="text" id="slgf_image_url4[]" name="slgf_image_url4[]" class="rpg_label_text"
                                   value="<?php echo esc_url($url4); ?>" readonly="readonly" style="display:none;"/>
								   
								   
                            <label><?php esc_html_e( 'Description', WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label>
							<textarea name="img_desc[]" id="img_desc[]"
								  placeholder="<?php esc_html_e( 'Description', WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>"
								  class="gal_richeditbox_<?php echo esc_attr($i); ?>"><?php echo htmlentities( $img_desc ); ?>
						    </textarea>
                        </li>
						<?php

					} // end of foreach
				} else {
					$TotalImages = 0;
				}
				?>
            </ul>
        </div>

        <!--Add New Image Button-->
        <div class="rpg-image-entry add_rpg_new_image" id="slgf_gallery_upload_button"
             data-uploader_title="Upload Image" data-uploader_button_text="Select">
            <div class="dashicons dashicons-plus"></div>
            <p>
				<?php esc_html_e( 'Add New Images', WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
            </p>
        </div>

        <div class="rpg-image-entry del_rpg_image" id="slgf_delete_all_button">
            <div class="dashicons dashicons-trash"></div>
            <p>
				<?php esc_html_e( 'Delete All', WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
            </p>
        </div>

        <div style="clear:left;"></div>
        <!--<input id="slgf_delete_all_button" class="button" type="button" value="Delete All" rel="">-->

        <p><strong><?php esc_html_e( "Tips", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
            :</strong> <?php esc_html_e( "Plugin crop images with same size thumbnails", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
        . <?php esc_html_e( "So please upload all gallery images using Add New Image button", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
        . <?php esc_html_e( "Do not use or add pre uploaded images which are uploaded previously using Media or Post or Page", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
        .</p><?php esc_html_e( "Show Us Some Love", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
        (<?php esc_html_e( "Rate Us", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>) &nbsp;
        <a class="wrg-rate-us2" style="text-align:center; text-decoration: none;font:normal 30px;"
           href="http://wordpress.org/plugins/simple-lightbox-gallery/#reviews" target="_blank">
            <span class="dashicons dashicons-star-filled"></span>
            <span class="dashicons dashicons-star-filled"></span>
            <span class="dashicons dashicons-star-filled"></span>
            <span class="dashicons dashicons-star-filled"></span>
            <span class="dashicons dashicons-star-filled"></span>
        </a>
		<?php
	}

	/**
	 * This function display Add New Image interface
	 * Also loads all saved gallery photos into Lightbox gallery
	 */
	public function slgf_settings_meta_box_function( $post ) {
		require_once( 'simple-lightbox-slider-setting-metabox.php' );
	}

	public function slgf_shotcode_meta_box_function() { ?>
        <p><?php esc_html_e( "Use below shortcode in any Page/Post to publish your photo gallery", WEBLIZAR_SLGF_PLUGIN_URL ); ?></p>
        <input readonly="readonly" type="text" value="<?php echo "[SLGF id=" . get_the_ID() . "]"; ?>">
		<?php
	}

	//
	public function admin_thumb( $id ) {
		$image        = wp_get_attachment_image_src( $id, 'lightboxslider_admin_medium', true );
		$image1       = wp_get_attachment_image_src( $id, 'slgf_12_thumb', true );
		$image2       = wp_get_attachment_image_src( $id, 'slgf_346_thumb', true );
		$image3       = wp_get_attachment_image_src( $id, 'slgf_12_same_size_thumb', true );
		$image4       = wp_get_attachment_image_src( $id, 'slgf_346_same_size_thumb', true );
		$UniqueString = substr( str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 5 );
		?>
        <li class="rpg-image-entry" id="rpg_img">
            <a class="gallery_remove lbsremove_bt" href="#gallery_remove" id="lbs_remove_bt">
            <img src="<?php echo WEBLIZAR_SLGF_PLUGIN_URL . 'images/Close-icon-new.png'; ?>"/></a>
            <img src="<?php echo esc_url($image[0]); ?>" class="rpg-meta-image" alt="">
            <input type="button" id="upload-background-<?php echo esc_attr($UniqueString); ?>"
                   name="upload-background-<?php echo esc_attr($UniqueString); ?>" value="Upload Image" class="button-primary "
                   onClick="weblizar_image('<?php echo esc_attr($UniqueString); ?>')"/>
            <input type="text" id="slgf_image_label[]" name="slgf_image_label[]" placeholder="Enter Image Label"
                   class="rpg_label_text">
            <input type="text" id="slgf_image_url[]" name="slgf_image_url[]" class="rpg_label_text"
                   value="<?php echo esc_url($image[0]); ?>" readonly="readonly" style="display:none;"/>
            <input type="text" id="slgf_image_url1[]" name="slgf_image_url1[]" class="rpg_label_text"
                   value="<?php echo esc_url($image1[0]); ?>" readonly="readonly" style="display:none;"/>
            <input type="text" id="slgf_image_url2[]" name="slgf_image_url2[]" class="rpg_label_text"
                   value="<?php echo esc_url($image2[0]); ?>" readonly="readonly" style="display:none;"/>
            <input type="text" id="slgf_image_url3[]" name="slgf_image_url3[]" class="rpg_label_text"
                   value="<?php echo esc_url($image3[0]); ?>" readonly="readonly" style="display:none;"/>
            <input type="text" id="slgf_image_url4[]" name="slgf_image_url4[]" class="rpg_label_text"
                   value="<?php echo esc_url($image4[0]); ?>" readonly="readonly" style="display:none;"/>
             <label><?php esc_html_e( 'Description', WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label>
			<textarea name="img_desc[]" id="img_desc[]"
				  placeholder="<?php esc_html_e( 'Description', WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>"
				  class="gal_richeditbox_<?php echo esc_attr($id); ?>">
		    </textarea>
        </li>
		<?php
	}

	public function ajax_get_thumbnail_slgf() {
		echo esc_html($this->admin_thumb( $_POST['imageid'] ));
		die;
	}

	public function slgf_add_image_meta_box_save( $PostID ) {
		if ( isset( $PostID ) && isset( $_POST['slgf_wl_action'] )) {
			$TotalImages = count( $_POST['slgf_image_url'] );
			$ImagesArray = array();
			if ( $TotalImages ) {
				for ( $i = 0; $i < $TotalImages; $i ++ ) {
					$image_label = stripslashes( $_POST['slgf_image_label'][ $i ] );
					$url         = sanitize_text_field( $_POST['slgf_image_url'][ $i ] );
					$url1        = sanitize_text_field( $_POST['slgf_image_url1'][ $i ] );
					$url2        = sanitize_text_field( $_POST['slgf_image_url2'][ $i ] );
					$url3        = sanitize_text_field( $_POST['slgf_image_url3'][ $i ] );
					$url4        = sanitize_text_field( $_POST['slgf_image_url4'][ $i ] );
					$img_desc    = stripslashes( $_POST['img_desc'][ $i ] );
					
					$ImagesArray[] = array(
						'slgf_image_label'         => $image_label,
						'slgf_image_url'           => $url,
						'slgf_12_thumb'            => $url1,
						'slgf_346_thumb'           => $url2,
						'slgf_12_same_size_thumb'  => $url3,
						'slgf_346_same_size_thumb' => $url4,
						'img_desc'                 => $img_desc
					);
				}
				update_post_meta( $PostID, 'slgf_all_photos_details', serialize( $ImagesArray ) );
				update_post_meta( $PostID, 'slgf_total_images_count', $TotalImages );
			} else {
				$TotalImages = 0;
				update_post_meta( $PostID, 'slgf_total_images_count', $TotalImages );
				$ImagesArray = array();
				update_post_meta( $PostID, 'slgf_all_photos_details', serialize( $ImagesArray ) );
			}
		}
		//die;
	}

	//save settings meta box values
	public function slgf_settings_meta_save( $PostID ) {
		if ( isset( $PostID ) && isset( $_POST['slgf_save_action'] ) && isset( $_POST['security'] ) ) {
			if ( ! wp_verify_nonce( $_POST['security'], 'nonce_save_settings_option' ) ) {
		die();}
			$SLGF_Show_Gallery_Title  = sanitize_text_field( $_POST['wl-show-gallery-title'] );
			$SLGF_Show_Image_Label    = sanitize_text_field( $_POST['wl-show-image-label'] );
			$SLGF_Hover_Animation     = sanitize_text_field( $_POST['wl-hover-animation'] );
			$SLGF_Gallery_Layout      = sanitize_text_field( $_POST['wl-gallery-layout'] );
			$SLGF_Thumbnail_Layout    = sanitize_text_field( $_POST['wl-thumbnail-layout'] );
			$SLGF_Hover_Color         = sanitize_text_field( $_POST['wl-hover-color'] );
			$SLGF_Text_BG_Color       = sanitize_text_field( $_POST['wl-text-bg-color'] );
			$SLGF_Text_Color          = sanitize_text_field( $_POST['wl-text-color'] );
			$lk_show_img_desc         = sanitize_option( 'lk_show_img_desc', $_POST['lk_show_img_desc'] );
			$SLGF_Hover_Color_Opacity = sanitize_text_field( $_POST['wl-hover-color-opacity'] );
			$SLGF_Font_Style          = sanitize_text_field( $_POST['wl-font-style'] );
			$SLGF_Box_Shadow          = sanitize_text_field( $_POST['wl-box-Shadow'] );
			$SLGF_Custom_CSS          = sanitize_text_field( $_POST['wl-custom-css'] );

			$SLGF_DefaultSettingsArray = serialize( array(
				'SLGF_Show_Gallery_Title'  => $SLGF_Show_Gallery_Title,
				'SLGF_Show_Image_Label'    => $SLGF_Show_Image_Label,
				'SLGF_Hover_Animation'     => $SLGF_Hover_Animation,
				'SLGF_Gallery_Layout'      => $SLGF_Gallery_Layout,
				'SLGF_Thumbnail_Layout'    => $SLGF_Thumbnail_Layout,
				'SLGF_Hover_Color'         => $SLGF_Hover_Color,
				'SLGF_Text_BG_Color'       => $SLGF_Text_BG_Color,
				'SLGF_Text_Color'          => $SLGF_Text_Color,
				'SLGF_Hover_Color_Opacity' => $SLGF_Hover_Color_Opacity,
				'SLGF_Font_Style'          => $SLGF_Font_Style,
				'lk_show_img_desc'         => $lk_show_img_desc,
				'SLGF_Box_Shadow'          => $SLGF_Box_Shadow,
				'SLGF_Custom_CSS'          => $SLGF_Custom_CSS
			) );

			$SLGF_Gallery_Settings = "SLGF_Gallery_Settings_" . $PostID;
			update_post_meta( $PostID, $SLGF_Gallery_Settings, $SLGF_DefaultSettingsArray );
		}
	}
}

/**
 * Initialize Class with Object
 */
$SLGF = new SLGF();

/**
 * Lightbox Slider Pro Short Code [SLGF]
 */
require_once( "simple-lightbox-slider-shortcode.php" );

/**
 * Hex Color code to RGB Color Code converter function
 */
if ( ! function_exists( 'SLGF_RPGhex2rgb' ) ) {
	function SLGF_RPGhex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );

		return $rgb; // returns an array with the rgb values
	}
}

add_action( 'media_buttons_context', 'add_slgf_custom_button' );
function add_slgf_custom_button( $context ) {
	$img          = plugins_url( '/images/Photos-icon.png', __FILE__ );
	$container_id = 'SLGF';
	$title        = 'Select Lightbox Gallery to insert into post';
	$context      .= '<a class="button button-primary thickbox" title="Select Lightbox Gallery to insert into post" href="#TB_inline?width=400&inlineId=' . $container_id . '">
	<span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>
	Simple Lightbox Gallery Shortcode</a>';

	return $context;
}

add_action( 'admin_footer', 'add_slgf_inline_popup_content' );
function add_slgf_inline_popup_content() { ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#slgfgalleryinsert').on('click', function () {
                var id = jQuery('#slgf-gallery-select option:selected').val();
                window.send_to_editor('<p>[SLGF id=' + id + ']</p>');
                tb_remove();
            })
        });
    </script>

    <div id="SLGF" style="display:none;">
        <h3><?php esc_html_e( "Select Lightbox Gallery To Insert Into Post", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></h3>
		<?php
		$all_posts = wp_count_posts( 'slgf_slider' )->publish;
		$args      = array( 'post_type' => 'slgf_slider', 'posts_per_page' => $all_posts );
		global $rpg_galleries;
		$rpg_galleries = new WP_Query( $args );
		if ( $rpg_galleries->have_posts() ) { ?>
            <select id="slgf-gallery-select">
				<?php
				while ( $rpg_galleries->have_posts() ) : $rpg_galleries->the_post(); ?>
                    <option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
				<?php endwhile;	?>
            </select>
            <button class='button primary' id='slgfgalleryinsert'>
				<?php esc_html_e( "Insert Gallery Shortcode", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
			</button>
			</button>
			<?php
		} else {
			esc_html_e( "No Gallery Found", WEBLIZAR_SLGF_TEXT_DOMAIN );
		}
		?>
    </div>
	<?php
}

// Add settings link on plugin page
$slgf_plugin_name = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$slgf_plugin_name", 'as_settings_link_slgf' );
function as_settings_link_slgf( $links ) {
	$as_settings_link1 = '<a href="https://weblizar.com/lightbox-slider-pro/" style="font-weight:700; color:#e35400" target="_blank">'. esc_html__('Get Premium', WEBLIZAR_SLGF_TEXT_DOMAIN).'</a>';
	$as_settings_link2 = '<a href="edit.php?post_type=slgf_slider">'. esc_html__('Settings', WEBLIZAR_SLGF_TEXT_DOMAIN ).'</a>';
	array_unshift( $links, $as_settings_link1, $as_settings_link2 );

	return $links;
}

// Review Notice Box
add_action( "admin_notices", "review_admin_notice_slg_free" );
function review_admin_notice_slg_free() {
	global $pagenow;
	$slg_screen = get_current_screen();
	if ( $pagenow == 'edit.php' && $slg_screen->post_type == "slgf_slider" ) {
		include( 'slg-banner.php' );
	}
}
?>
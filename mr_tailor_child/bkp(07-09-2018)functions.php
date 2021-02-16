<?php

/****** Register additional widgetized area in footer. 
 * and update sidebar with default widgets ******/
/******************************************************************************/

function my_widgets_init() {
	
	$sidebars_widgets = wp_get_sidebars_widgets();	
	$footer_area_widgets_counter = "0";	
	if (isset($sidebars_widgets['footer-widget-area-2'])) $footer_area_widgets_counter  = count($sidebars_widgets['footer-widget-area-2']);
	
	switch ($footer_area_widgets_counter) {
		case 0:
			$footer_area_widgets_columns ='large-12';
			break;
		case 1:
			$footer_area_widgets_columns ='large-12 medium-12 small-12';
			break;
		case 2:
			$footer_area_widgets_columns ='large-6 medium-6 small-12';
			break;
		case 3:
			$footer_area_widgets_columns ='large-4 medium-6 small-12';
			break;
		case 4:
			$footer_area_widgets_columns ='large-3 medium-6 small-12';
			break;
		case 5:
			$footer_area_widgets_columns ='footer-5-columns large-2 medium-6 small-12';
			break;
		case 6:
			$footer_area_widgets_columns ='large-2 medium-6 small-12';
			break;
		default:
			$footer_area_widgets_columns ='large-2 medium-6 small-12';
	}
		
	//footer widget area - 2
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area 2', 'mr_tailor' ),
		'id'            => 'footer-widget-area-2',
		'before_widget' => '<div class="' . $footer_area_widgets_columns . ' columns"><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'my_widgets_init' );

add_filter( 'woocommerce_ajax_variation_threshold', 'remove_variations_limit', 10, 2 );
function remove_variations_limit( $limit, $product ){
   return '100';
}

//add_action( 'woocommerce_before_mini_cart', 'ha_add_custom_price' );
//add_action( 'woocommerce_before_calculate_totals', 'ha_add_custom_price' );
function ha_add_custom_price( $cart_object ) {
 
 //print_r($cart_object->cart_contents);exit;
    foreach ( $cart_object->cart_contents as $key => $value ) {
       if( $_POST['myprice']) {
           $newPrice = $_POST['myprice'];     
       }
       else{
           $newPrice = $_SESSION['productAttr'][$key]['myprice']; 
       }
        
       if($_POST['product_id']){
           $prod_id = $_POST['product_id'];
       }
       else{
           $prod_id =$_SESSION['productAttr'][$value['product_id']]['product_id']; 
       }

        if($newPrice != 0){
             $value['data']->set_price($newPrice);
             //$value['data']->regular_price = $new_price;
        }    
    }
}

//add_action( 'woocommerce_before_mini_cart', 'ha_add_custom_price' );
add_action( 'woocommerce_before_calculate_totals', 'set_custom_price_disc' );

function set_custom_price_disc($cart_obj) {
    //get_price()
    $disc = 0;
    if (is_user_logged_in ()) {
        $opt = get_option('eh_pricing_discount_price_adjustment_options');
        $rols = wp_get_current_user()->roles;
        foreach ($rols as $rol) {
            if ($opt[$rol]['role_price'] == 'on') {
                $disc = (float)$opt[$rol]['adjustment_percent'];
            }
        }
    }
        /*echo '<pre>';
        //print_r($rol);
        //print_r($opt); 
        print_r($disc);
        print_r(WC()->cart->get_cart());
        echo '</pre>';*/

    foreach ($cart_obj->get_cart() as $key => $value) {
        if (isset($_SESSION['productAttr'][$key]) && $_SESSION['productAttr'][$key]['disc'] == 0) {
            $new_price = $_SESSION['productAttr'][$key]['myprice'];
            $value['data']->regular_price = $new_price;
        } else if (isset($_SESSION['productAttr'][$key])) {
            $new_price = $_SESSION['productAttr'][$key]['myprice']/((100-$disc)/100);
            //$value['data']->set_price($new_price);
            $value['data']->regular_price = $new_price;
        }
    }
}

//exclusion of Afterpay for custom products
add_filter('woocommerce_available_payment_gateways','filter_gateways',1);

function filter_gateways($gateways){
global $woocommerce;
 foreach ($woocommerce->cart->cart_contents as $key => $values ) {
//store product id's in array
$highpriceditems = array(1860,801,1122,1127,1860,1902,1943,1984,2026,2068,3593,9110,9595,9598,9822,9868,9914,9960,10006,10052,10099,14589,14729);			

  if(in_array($values['product_id'],$highpriceditems)){	

									unset($gateways['afterpay_openinvoice']);
                                    unset($gateways['afterpay_business']);
                                    unset($gateways['MULTISAFEPAY_IDEAL']);
                                    unset($gateways['MULTISAFEPAY_MAESTRO']);
                                    unset($gateways['MULTISAFEPAY_MASTERCARD']);
                                    unset($gateways['MULTISAFEPAY_MISTERCASH']);
                                    unset($gateways['MULTISAFEPAY_VISA']);                                    
break;
                                }}
return $gateways;
}

add_action( 'wp', 'remove_product_content' );
function remove_product_content() {
	// If a product in the 'Cookware' category is being viewed...
	if ( is_product() && has_term( 'Maatwerk Schaduwdoeken', 'product_cat' ) ) {
		//... Remove the images
		echo '<style>';
		echo '.single_product_summary_related{display:none;}';
		echo '.recently_viewed_in_single_wrapper {display: none;}';
		
		
		echo '</style>';
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		// For a full list of what can be removed please see woocommerce-hooks.php
	}
}

add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab($tabs) {
 unset($tabs['reviews']);
 return $tabs;
}

/* Redirect if there is only one product in the category */
add_action( 'woocommerce_before_shop_loop', 'custom_woocommerce_redirect_if_single_product_in_category', 10 );
function custom_woocommerce_redirect_if_single_product_in_category ($wp_query) {
	global $wp_query;
	if (is_product_category()) {
		if ($wp_query->post_count==1) {
			$product = new WC_Product($wp_query->post->ID);
			if ($product->is_visible()) wp_safe_redirect( get_permalink($product->id), 302 );
			exit;
		}
	}
}

function custom_woocommerce_tag_cloud_widget() {
    $args = array(
       /* 'number' => 18, 
        'include' => '388,402,336,403,328,404,405,406,284,330,407,408,409', 
        'taxonomy' => 'product_tag'
    );*/
	'number' => 18, 
        'include' => '388,402,336,403,328,404,405,406,284,330,407,408,409', 
        'taxonomy' => 'product_tag'
    );
    return $args;
}
add_filter( 'woocommerce_product_tag_cloud_widget_args', 'custom_woocommerce_tag_cloud_widget' );

add_action( 'wp_print_scripts', 'custom_order_status_icon' );
function custom_order_status_icon() {
	
	if( ! is_admin() ) { 
		return; 
	}
	
	?> <style>
		/* Add custom status order icons */
		.column-order_status mark.beoordeelbestelli::after  {
  content: url(https://nlzonz-chibykovo.savviihq.com/wp-content/uploads/2016/03/status-1-1.png);
	}
 .column-order_status mark.beoordeelbestelli::after  {
  font-family: WooCommerce;
  font-variant: normal;
  font-weight: 400;
  height: 88%;
  left: 0;
  line-height: 1;
  margin: 0;
  position: absolute;
  text-align: center;
  text-indent: 0;
  text-transform: none;
  top: 0;
  width: 100%;
}
.widefat .column-order_status mark.beoordeelbestelli {
  background-color: transparent !important;
  text-indent: -9999px !important;
  width: 100%;
}
	
		/* Repeat for each different icon; tie to the correct status */
 
	</style> <?php
}


add_action('wp_enqueue_scripts', 'removeScripts', 100 );
add_action('init', 'removeScripts', 100 );
add_action('init', 'addScripts', 100 );

function removeScripts() {
 wp_deregister_script('mr_tailor-scripts');
 
}


function addScripts() {

 //wp_enqueue_script('newscript', get_template_directory_uri() . '-child/js/scripts.js');
 

}


function custom_figure() {  
    add_meta_box(  
        'custom_figure', 
        'Custom Figure', 
        'show_custom_figure', 
        'product',
        'normal', 
        'high');
}  
add_action('add_meta_boxes', 'custom_figure');

$meta_fields = array(  
    array(  
        'label' => 'Triangle',  
        'desc'  => 'Triangle Product',  
        'id'    => 'triangle',
        'type'  => 'checkbox' 
    ) ,
    array(  
        'label' => 'Triangle 90dr',  
        'desc'  => 'Triangle Product 90d right',  
        'id'    => 'triangle90dr', 
        'type'  => 'checkbox'
    ) ,
    array(  
        'label' => 'Triangle 90dl',  
        'desc'  => 'Triangle Product 90d left',  
        'id'    => 'triangle90dl',
        'type'  => 'checkbox'
    ) ,
    array(  
        'label' => 'Triangle 60d',  
        'desc'  => 'Triangle Product 60d',  
        'id'    => 'triangle60d',
        'type'  => 'checkbox'  
    ) ,
    array(  
        'label' => 'Square',  
        'desc'  => 'Square Product',  
        'id'    => 'square',
        'type'  => 'checkbox'  
    ) ,
    array(  
        'label' => 'Rectangle',  
        'desc'  => 'Rectangle Product',  
        'id'    => 'rectsquare',
        'type'  => 'checkbox'  
    ) ,
    array(  
        'label' => 'Inequable Square',  
        'desc'  => 'Inequable Square Product',  
        'id'    => 'isquare',
        'type'  => 'checkbox'  
    ) ,
    array(  
        'label' => 'Lamellae Square',  
        'desc'  => 'Lamellae Square Product',  
        'id'    => 'lsquare',
        'type'  => 'checkbox'  
    ) ,
    array(  
        'label' => 'Winddoek',  
        'desc'  => 'Winddoek Products',  
        'id'    => 'winddoek',
        'type'  => 'checkbox'  
    )
 
);

function show_custom_figure() {  
global $meta_fields; 
global $post;  

echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
 
    echo '<table class="form-table">';  
    foreach ($meta_fields as $field) {  
         
        $meta = get_post_meta($post->ID, $field['id'], true);  
        
        echo '<tr> 
                <td style="display:none;"><label for="'.$field['id'].'">'.$field['label'].'</label></td> 
                <td>';  
                switch($field['type']) {  
                    case 'checkbox':  
                    echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/> 
                        <label for="'.$field['id'].'">'.$field['desc'].'</label>';  
                break;
                }
        echo '</td></tr>';  
    }  
    echo '</table>'; 
}
// Пишем функцию для сохранения
function save_show_custom_figure($post_id) {  
    global $meta_fields;  // Массив с нашими полями
 
    // проверяем наш проверочный код 
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))   
        return $post_id;  
    // Проверяем авто-сохранение 
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  
        return $post_id;  
    // Проверяем права доступа  
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can('edit_page', $post_id))  
            return $post_id;  
        } elseif (!current_user_can('edit_post', $post_id)) {  
            return $post_id;  
    }  
 
    // Если все отлично, прогоняем массив через foreach
    foreach ($meta_fields as $field) {  
        $old = get_post_meta($post_id, $field['id'], true); // Получаем старые данные (если они есть), для сверки
        $new = $_POST[$field['id']];  
        if ($new && $new != $old) {  // Если данные новые
            update_post_meta($post_id, $field['id'], $new); // Обновляем данные
        } elseif ('' == $new && $old) {  
            delete_post_meta($post_id, $field['id'], $old); // Если данных нету, удаляем мету.
        }  
    } // end foreach  
}  
add_action('save_post', 'save_show_custom_figure'); // Запускаем функцию сохранения
/********************************************************************************/
if ( ! isset( $content_width ) ) $content_width = 640; /* pixels */



function search_box_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Search sidebar', 'search_sidebar' ),
		'id' => 'search_sidebar',
		'description' => __( 'Search sidebar', 'search_sidebar' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'search_box_widgets_init' );


function woa_content_before_shop() {
	echo "<style>.large-8 {
  width: 100% !important;
}.</style>";
}

add_action( 'woocommerce_before_shop_loop', 'woa_content_before_shop');




// Edit term page
function image_taxonomy_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" );
      
        ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[image_url]"><?php _e( 'Image URL'); ?></label></th>
		<td>
                        <div class="img-container">
                            <?php if(!empty($term_meta['image_url'])): ?>
                            <img style="max-width: 200px;" src="<?php echo $term_meta['image_url']; ?>">
                            <?php endif; ?>
                        </div>
			<input type="text" name="term_meta[image_url]" id="term_meta_image_url" value="<?php echo esc_attr( $term_meta['image_url'] ) ? esc_attr( $term_meta['image_url'] ) : ''; ?>">
                        <button class="set_custom_images button">Set Image</button>
                        <button class="del_custom_images button">Remove Image</button>
			
                        <script>
                        jQuery(document).ready(function() {
                        var $ = jQuery;
                        
                        $('.del_custom_images').click(function(){
                            $('#term_meta_image_url').val('');
                            $('.img-container').html('');
                        });
                        
                        if ($('.set_custom_images').length > 0) {
                                if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                                    $(document).on('click', '.set_custom_images', function(e) {
                                        e.preventDefault();
                                        var button = $(this);
                                        var id = $('#term_meta_image_url');
                                        wp.media.editor.send.attachment = function(props, attachment) {
                                            var atturl = attachment.url.replace('https://<?=$_SERVER['SERVER_NAME']; ?>', '');    
                                            id.val(atturl);
                                            $('.img-container').html('<img style="max-width: 200px;" src="'+atturl+'">');
                                        };
                                        wp.media.editor.open(button);
                                        return false;
                                    });
                                }
                            }
                        });
                        </script>
		</td>
	</tr>
<?php
}
//add_action( 'pa_material-type_edit_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_material-type_add_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );
//
//add_action( 'pa_doeksoort-2_edit_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_doeksoort-2_add_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );
//
//add_action( 'pa_doeksoort-wind_edit_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_doeksoort-wind_add_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );

function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
//add_action( 'edited_pa_material-type', 'save_taxonomy_custom_meta', 10, 2 );  
//add_action( 'create_pa_material-type', 'save_taxonomy_custom_meta', 10, 2 );
//
//add_action( 'edited_pa_doeksoort-2', 'save_taxonomy_custom_meta', 10, 2 );  
//add_action( 'create_pa_doeksoort-2', 'save_taxonomy_custom_meta', 10, 2 );
//
//add_action( 'edited_pa_doeksoort-wind', 'save_taxonomy_custom_meta', 10, 2 );  
//add_action( 'create_pa_doeksoort-wind', 'save_taxonomy_custom_meta', 10, 2 );

function price_taxonomy_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" );
        //print_r($term_meta);
        ?>
	<tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[data-price]"><?php _e( 'Price'); ?></label></th>
            <td>
		<input type="text" name="term_meta[data-price]" id="term_meta_data_price" value="<?php echo esc_attr( $term_meta['data-price'] ) ? esc_attr( $term_meta['data-price'] ) : '0'; ?>">
            </td>
	</tr>
<?php
}

$add_imageprice_taxonomies = array(
    "pa_material-type", 
    "pa_material-type2", 
    "pa_doeksoort",
    "pa_doeksoort2",
    "pa_doeksoort-2",
    "pa_doeksoort-wind",
    "pa_doeksoort-wind2",
    "pa_doeksoort-curv1",
    "pa_doeksoort-curv2",
    "pa_doeksoort-recht1",
    "pa_doeksoort-recht2"
    );

foreach ($add_imageprice_taxonomies as $image_tax) {
    add_action( $image_tax.'_edit_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );
    add_action( $image_tax.'_add_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );

    add_action( 'edited_'.$image_tax, 'save_taxonomy_custom_meta', 10, 2 );  
    add_action( 'create_'.$image_tax, 'save_taxonomy_custom_meta', 10, 2 );
    
    add_action( $image_tax.'_edit_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
    add_action( $image_tax.'_add_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
}
$add_image_taxonomies = array(
    "pa_zijde-ab", 
    "pa_zijde-bc", 
    "pa_zijde-ca",
    "pa_zijde-cd",
    "pa_zijde-da",
    "pa_bepaal-je-afmeting"
    );
foreach ($add_image_taxonomies as $image_tax) {
    add_action( $image_tax.'_edit_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );
    add_action( $image_tax.'_add_form_fields', 'image_taxonomy_edit_meta_field', 10, 2 );

    add_action( 'edited_'.$image_tax, 'save_taxonomy_custom_meta', 10, 2 );  
    add_action( 'create_'.$image_tax, 'save_taxonomy_custom_meta', 10, 2 );
}
//add_action( 'pa_material-type_edit_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_material-type_add_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//
//add_action( 'pa_doeksoort-2_edit_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_doeksoort-2_add_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//
//add_action( 'pa_doeksoort-wind_edit_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_doeksoort-wind_add_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );

$add_price_taxonomies = array(
    "pa_bevestigingsmateriaal-test", 
    "pa_bevestigingsmateriaal-test2",
    "pa_bevestigingsmateriaal-test3",
    "pa_bevestigingsmateriaal-test4"
    );

foreach ($add_price_taxonomies as $price_tax) {
    add_action( $price_tax.'_edit_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
    add_action( $price_tax.'_add_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );

    add_action( 'edited_'.$price_tax, 'save_taxonomy_custom_meta', 10, 2 );  
    add_action( 'create_'.$price_tax, 'save_taxonomy_custom_meta', 10, 2 );
}

//add_action( 'pa_bevestigingsmateriaal-test_edit_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_bevestigingsmateriaal-test_add_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//
//add_action( 'edited_pa_bevestigingsmateriaal-test', 'save_taxonomy_custom_meta', 10, 2 );  
//add_action( 'create_pa_bevestigingsmateriaal-test', 'save_taxonomy_custom_meta', 10, 2 );
//
//
//add_action( 'pa_bevestigingsmateriaal-test2_edit_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_bevestigingsmateriaal-test2_add_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//
//add_action( 'edited_pa_bevestigingsmateriaal-test2', 'save_taxonomy_custom_meta', 10, 2 );  
//add_action( 'create_pa_bevestigingsmateriaal-test2', 'save_taxonomy_custom_meta', 10, 2 );
//
//
//add_action( 'pa_bevestigingsmateriaal-test3_edit_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//add_action( 'pa_bevestigingsmateriaal-test3_add_form_fields', 'price_taxonomy_edit_meta_field', 10, 2 );
//
//add_action( 'edited_pa_bevestigingsmateriaal-test3', 'save_taxonomy_custom_meta', 10, 2 );  
//add_action( 'create_pa_bevestigingsmateriaal-test3', 'save_taxonomy_custom_meta', 10, 2 );

function remove_content_filter() {
   remove_filter( 'woocommerce_sale_flash', 'woocommerce_custom_sale_tag_sale_flash' );
}
add_action( 'after_setup_theme', 'remove_content_filter' );

function woo_custom_hide_sales_flash()
{
    return false;
}
add_filter('woocommerce_sale_flash', 'woo_custom_hide_sales_flash');



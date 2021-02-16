<?php 
//@session_start();
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
/*function ha_add_custom_price( $cart_object ) {
 
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
}*/
/*
//add_action( 'woocommerce_before_mini_cart', 'ha_add_custom_price' );
add_action( 'woocommerce_before_calculate_totals', 'set_custom_price_disc', 99, 1 );
function set_custom_price_disc($cart_obj) {

    if(is_admin() && ! defined('DOING_AJAX'))
        return;


    if(did_action('woocommerce_before_calculate_totals') >= 2)
        return;



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
    //foreach ($cart_obj->get_cart() as $key => $value) {
        if (isset($_SESSION['productAttr'][$key]) && $_SESSION['productAttr'][$key]['disc'] == 0) {
            $new_price = $_SESSION['productAttr'][$key]['myprice'];
            
            $value['data']->set_price($new_price);
            $value['data']->regular_price = $new_price;
        } else if (isset($_SESSION['productAttr'][$key])) {
            $new_price = $_SESSION['productAttr'][$key]['myprice']/((100-$disc)/100);
            $value['data']->set_price($new_price);
            $value['data']->regular_price = $new_price;
        }
    }
}*/

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


function cross_sells_custom_limit(){
    return -1;
}
add_filter('woocommerce_cross_sells_total', 'cross_sells_custom_limit', 99);


function custom_dropdown_variation_attribute_options_args($args){
    $args['show_option_none'] = 'Maak je keuze';
    return $args;
}
add_filter('woocommerce_dropdown_variation_attribute_options_args', 'custom_dropdown_variation_attribute_options_args', 99);


function overwride_woocommerce_cart_widgets() { 
    if ( class_exists( 'WC_Widget_Cart' ) ) {
        include_once( 'inc/widgets/woocommerce-cart.php' ); 
        register_widget( 'custom_WC_Widget_Cart' );
    }
}
add_action( 'widgets_init', 'overwride_woocommerce_cart_widgets', 15 );

function custom_woocommerce_add_to_cart_fragments($args){
    // Get mini cart
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();
    $args['div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content 222222">' . $mini_cart . '</div>';
    return $args;
}
add_filter('woocommerce_add_to_cart_fragments', 'custom_woocommerce_add_to_cart_fragments', 99, 1);

function custom_define(){
    if ( ! defined( 'WC_MAX_LINKED_VARIATIONS' ) ) {
        define( 'WC_MAX_LINKED_VARIATIONS', 399 );
    }
}
add_action('init', 'custom_define');


function custom_woocommerce_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data){
    $productAttr = array(
        'area' =>   $_POST['area'], 
        'disc' =>   $_POST['disc'], 
        'point1' => $_POST['point1'],
        'point2' => $_POST['point2'], 
        'point3' => $_POST['point3'],
        'width' =>  number_format($_POST['width'], 2, '.', ''),
        'height' =>  number_format($_POST['height'], 2, '.', ''),
        'product_id' => $_POST['product_id'],
        'myprice' => $_POST['myprice'],
        'mystyle' => (!empty($_POST['mystyle'])) ? 'Ja' : 'Nee',
        'formtype' => $_POST['formtype']
    );

    if(!empty($productAttr['area'])){
        //$_SESSION['productAttr'][$product_id.'_'.$quantity] = $productAttr;  
        $_SESSION['productAttr'][$cart_item_key] = $productAttr;  
    }
}
add_action( 'woocommerce_add_to_cart', 'custom_woocommerce_add_to_cart', 10, 6 );

function custom_woocommerce_checkout_create_order_line_item($item, $cart_item_key, $value, $order){
    $area = $item->legacy_values['area']['area'];
    if(!empty($area)){
        $attr['area'] = str_replace(',','<br />',$area);
        $item->add_meta_data('Oppervlakte', $attr['area'], true);
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'custom_woocommerce_checkout_create_order_line_item', 20, 4 );

if ( ! function_exists( 'wc_display_item_meta' ) ) {
    function wc_display_item_meta( $item, $args = array() ) {

        $strings = array();
        $html    = '';
        $args    = wp_parse_args( $args, array(
            'before'    => '<ul class="wc-item-meta"><li>',
            'after'     => '</li></ul>',
            'separator' => '</li><li>',
            'echo'      => true,
            'autop'     => false,
        ) );

        foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
            $value     = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
            $strings[] = '<strong class="wc-item-meta-label">' . wp_kses_post( $meta->display_key ) . ':</strong> ' . $value;
        }

        if ( $strings ) {
            $html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
        }

        $html = apply_filters( 'woocommerce_display_item_meta', $html, $item, $args );

        if ( $args['echo'] ) {
            echo $html; // WPCS: XSS ok.
        } else {
            return $html;
        }
    }
}

function custom_woocommerce_add_cart_item_data($cart_item_data, $product_id, $variation_id){
    if(!empty($_POST['myprice'])){
        $myprice = $_POST['myprice'];
        $cart_item_data['myprice'] = $myprice;
        $cart_item_data['disc'] = $_POST['disc'];

        $productAttr = array(
            'area' => $_POST['area'], 
            'disc' => $_POST['disc'], 
            'point1' => $_POST['point1'],
            'point2' => $_POST['point2'], 
            'point3' => $_POST['point3'],
            'width' =>  $_POST['width'],
            'height' =>  $_POST['height']
        );
        $cart_item_data['area'] = $productAttr;
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'custom_woocommerce_add_cart_item_data', 99, 3 );

function custom_set_custom_price_disc($cart_obj) {
    if(is_admin() && ! defined('DOING_AJAX'))
        return;


    if(did_action('woocommerce_before_calculate_totals') >= 2)
        return;

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
    foreach ($cart_obj->get_cart() as $key => $value) {
         if ( !empty($value['myprice']) && empty(['disc']) ) {
            $new_price = $value['myprice'];
            
            $value['data']->set_price($new_price);
            $value['data']->set_regular_price($new_price);
        } else if (!empty($value['myprice'])) {
            $new_price = $value['myprice']/((100-$disc)/100);
            $value['data']->set_price($new_price);
            $value['data']->set_regular_price($new_price);
        }
        /*if (isset($_SESSION['productAttr'][$key]) && $_SESSION['productAttr'][$key]['disc'] == 0) {
            $new_price = $_SESSION['productAttr'][$key]['myprice'];
            
            $value['data']->set_price($new_price);
            $value['data']->set_regular_price($new_price);
        } else if (isset($_SESSION['productAttr'][$key])) {
            $new_price = $_SESSION['productAttr'][$key]['myprice']/((100-$disc)/100);
            $value['data']->set_price($new_price);
            $value['data']->set_regular_price($new_price);
        }*/
    }
}
add_action( 'woocommerce_before_calculate_totals', 'custom_set_custom_price_disc', 9999, 1 );

function custom_woocommerce_cart_item_price($finalPrice, $value, $cart_item_key){
    $finalPrice = $value['data']->get_price();
    if (!empty($value['myprice'])) {
        $finalPrice = $value['myprice'];
        $value['data']->set_price($finalPrice);
    }
    return number_format($finalPrice,2,',','');
}
add_filter( 'woocommerce_cart_item_price', 'custom_woocommerce_cart_item_price', 10, 3 );

function custom_wc_price( $return, $price, $args, $unformatted_price ) {
    $price = str_replace(',', '.', $price);
    $args = apply_filters(
        'wc_price_args', wp_parse_args(
            $args, array(
                'ex_tax_label'       => false,
                'currency'           => '',
                'decimal_separator'  => wc_get_price_decimal_separator(),
                'thousand_separator' => wc_get_price_thousand_separator(),
                'decimals'           => wc_get_price_decimals(),
                'price_format'       => get_woocommerce_price_format(),
            )
        )
    );

    $unformatted_price = $price;
    $negative          = $price < 0;
    $price             = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * -1 : $price ) );
    $price             = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

    if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
        $price = wc_trim_zeros( $price );
    }

    $formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], get_woocommerce_currency_symbol( $args['currency'] ), $price );
    $return          = '<span class="amount"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">' . $formatted_price . '</font></font></span>';

    if ( $args['ex_tax_label'] && wc_tax_enabled() ) {
        $return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
    }
    return $return;
}
add_filter( 'wc_price', 'custom_wc_price', 10, 4 );


function custom_wc_order_is_editable($in_array, $order){
    return in_array( $order->get_status(), array( 'pending', 'on-hold', 'auto-draft', 'beoordeelbestelli' ) );
}
add_filter('wc_order_is_editable', 'custom_wc_order_is_editable', 20, 2);

if ( ! function_exists( 'wc_dropdown_variation_attribute_options' ) ) {

    function wc_dropdown_variation_attribute_options( $args = array() ) {
        $args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
            'options'          => false,
            'attribute'        => false,
            'product'          => false,
            'selected'         => false,
            'name'             => '',
            'id'               => '',
            'class'            => '',
            'show_option_none' => __( 'Maak je keuze', 'woocommerce' ),
        ) );

        // Get selected value.
        if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
            $selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
            $args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( urldecode( wp_unslash( $_REQUEST[ $selected_key ] ) ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
        }

        $options               = $args['options'];
        $product               = $args['product'];
        $attribute             = $args['attribute'];
        $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
        $id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
        $class                 = $args['class'];
        $show_option_none      = (bool) $args['show_option_none'];
        $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }

        
        $posid = strpos($id, 'doeksoort'); //add class for carousel
        $posid2 = strpos($id, 'zijde'); //add class for carousels zijde
        
        if ($posid === false) {
            if ($posid2 === false) {
                $html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
            } else {
                $html = '<select id="' . esc_attr( $id ) . '" class="zijde ' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
            }
        } else {
         $html = '<select id="' . esc_attr( $id ) . '" class="doeksoort ' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
        }
        if ( $args['show_option_none'] ) {
            //$html .= '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
            $html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
        }

        //$html  = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
        //$html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

        if ( ! empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms( $product->get_id(), $attribute, array(
                    'fields' => 'all',
                ) );

                foreach ( $terms as $term ) {

                    $added_options = get_option( "taxonomy_".$term->term_id );
                    $term->image_url = $added_options['image_url'];
                    $term->data_price = $added_options['data-price'];

                    if ( in_array( $term->slug, $options, true ) ) {
                        
                        $pos  = strpos($term->taxonomy, 'bevestigingsmateriaal'); //add price bevestigingsmateriaal
                        $pos2 = strpos($term->taxonomy, 'doeksoort'); //add image and price doeksoort
                        $pos3 = strpos($term->taxonomy, 'zijde'); //add image zijde
                        $pos4 = strpos($term->taxonomy, 'bepaal-je-afmeting');
                        ($pos4) ? $pos3 = 1 : "";
                        if ($term->data_price == '') {$term->data_price = 0;}
                        if ($pos === false) {
                            if ($pos2 === false) {
                                if ($pos3 === false) {
                                    $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
                                } else {
                                    $html .= '<option value="' . esc_attr( $term->slug ) . '" img-url="'.$term->image_url.'"' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
                                }
                            } else {
                                $html .= '<option value="' . esc_attr( $term->slug ) . '" img-url="'.$term->image_url.'" data-price="'.$term->data_price.'" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
                            }
                        } else {
                          $html .= '<option value="' . esc_attr( $term->slug ) . '" data-size="'.$term->description.'" ' .' data-price="'.$term->data_price.'" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
                        }
                        //$html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                    $html    .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                }
            }
        }

        $html .= '</select>';

        echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args ); // WPCS: XSS ok.
    }
}

/** Forms */

if ( ! function_exists( 'woocommerce_form_field' ) ) {

    /**
     * Outputs a checkout/address form field.
     *
     * @subpackage  Forms
     * @param string $key
     * @param mixed $args
     * @param string $value (default: null)
     * @todo This function needs to be broken up in smaller pieces
     */
    function woocommerce_form_field( $key, $args, $value = null ) {
        $defaults = array(
            'type'              => 'text',
            'label'             => '',
            'description'       => '',
            'placeholder'       => '',
            'maxlength'         => false,
            'required'          => false,
            'id'                => $key,
            'class'             => array(),
            'label_class'       => array(),
            'input_class'       => array(),
            'return'            => false,
            'options'           => array(),
            'custom_attributes' => array(),
            'validate'          => array(),
            'default'           => '',
        );

        $args = wp_parse_args( $args, $defaults );
        $args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );

        if ( $args['required'] ) {
            $args['class'][] = 'validate-required';
            $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
        } else {
            $required = '';
        }

        $args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

        if ( is_string( $args['label_class'] ) ) {
            $args['label_class'] = array( $args['label_class'] );
        }

        if ( is_null( $value ) ) {
            $value = $args['default'];
        }

        // Custom attribute handling
        $custom_attributes = array();

        if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
            foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
                $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
            }
        }

        if ( ! empty( $args['validate'] ) ) {
            foreach( $args['validate'] as $validate ) {
                $args['class'][] = 'validate-' . $validate;
            }
        }

        $field = '';
        $label_id = $args['id'];
        $field_container = '<p class="form-row %1$s" id="%2$s">%3$s</p>';

        switch ( $args['type'] ) {
            case 'country' :

                $countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

                if ( 1 === sizeof( $countries ) ) {

                    $field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

                    $field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys($countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="custom_select_dropdown country_to_state" />';

                } else {

                    $field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="custom_select_dropdown country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . '>'
                            . '<option value="">'.__( 'Select a country&hellip;', 'woocommerce' ) .'</option>';

                    foreach ( $countries as $ckey => $cvalue ) {
                        $field .= '<option value="' . esc_attr( $ckey ) . '" '. selected( $value, $ckey, false ) . '>'. __( $cvalue, 'woocommerce' ) .'</option>';
                    }

                    $field .= '</select>';

                    $field .= '<noscript><input type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country', 'woocommerce' ) . '" /></noscript>';

                }

                break;
            case 'state' :
                /* Get Country */
                $country_key = 'billing_state' === $key ? 'billing_country' : 'shipping_country';
                $current_cc  = WC()->checkout->get_value( $country_key );
                $states      = WC()->countries->get_states( $current_cc );

                //echo '<pre>';print_r($states);die();
                if ( is_array( $states ) && empty( $states ) ) {

                    $field_container = '<p class="form-row %1$s" id="%2$s" style="display: none">%3$s</p>';

                    $field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key )  . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" />';

                } elseif ( is_array( $states ) ) {

                    $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="custom_select_dropdown state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
                        <option value="">'.__( 'Select a state&hellip;', 'woocommerce' ) .'</option>';

                    foreach ( $states as $ckey => $cvalue ) {
                        $field .= '<option value="' . esc_attr( $ckey ) . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'woocommerce' ) .'</option>';
                    }

                    $field .= '</select>';

                } else {

                    $field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

                }

                break;
            case 'textarea' :

                $field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>'. esc_textarea( $value  ) .'</textarea>';

                break;
            case 'checkbox' :

                $field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) .'" ' . implode( ' ', $custom_attributes ) . '>
                        <input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" '.checked( $value, 1, false ) .' /> '
                         . $args['label'] . $required . '</label>';

                break;
            case 'password' :
            case 'text' :
            case 'email' :
            case 'tel' :
            case 'number' :

                $field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

                break;
            case 'select' :

                $options = $field = '';

                if ( ! empty( $args['options'] ) ) {
                    foreach ( $args['options'] as $option_key => $option_text ) {
                        if ( '' === $option_key ) {
                            // If we have a blank option, select2 needs a placeholder
                            if ( empty( $args['placeholder'] ) ) {
                                $args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
                            }
                            $custom_attributes[] = 'data-allow_clear="true"';
                        }
                        $options .= '<option value="' . esc_attr( $option_key ) . '" '. selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) .'</option>';
                    }

                    $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select '. esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
                            ' . $options . '
                        </select>';
                }

                break;
            case 'radio' :

                $label_id = current( array_keys( $args['options'] ) );

                if ( ! empty( $args['options'] ) ) {
                    foreach ( $args['options'] as $option_key => $option_text ) {
                        $field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
                        $field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) .'">' . $option_text . '</label>';
                    }
                }

                break;
        }

        if ( ! empty( $field ) ) {
            $field_html = '';

            if ( $args['label'] && 'checkbox' != $args['type'] ) {
                $field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
            }

            $field_html .= $field;

            if ( $args['description'] ) {
                $field_html .= '<span class="description">' . esc_html( $args['description'] ) . '</span>';
            }

            $container_class = 'form-row ' . esc_attr( implode( ' ', $args['class'] ) );
            $container_id = esc_attr( $args['id'] ) . '_field';

            $after = ! empty( $args['clear'] ) ? '<div class="clear"></div>' : '';

            $field = sprintf( $field_container, $container_class, $container_id, $field_html ) . $after;
        }

        $field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

        if ( $args['return'] ) {
            return $field;
        } else {
            echo $field;
        }
    }
}

/*function custom_woocommerce_form_field_email($field, $key, $args, $value){
    $args['class'][0] = 'form-row-first';
    return $field;
}
add_filter( 'woocommerce_form_field_email', 'custom_woocommerce_form_field_email', 10, 4 );*/


function custom_address_fields_placeholder($address_fields){

    $address_fields['address_1']['label'] = 'Adres';
    $address_fields['address_1']['placeholder'] = 'Straat + huisnummer';
    $address_fields['address_2']['placeholder'] = 'Toevoeging (optioneel)';
    return $address_fields;
}
add_filter('woocommerce_default_address_fields', 'custom_address_fields_placeholder');

function custom_dequeue_stylesandscripts_select2(){
    if(class_exists('woocommerce')){
        wp_dequeue_style('select2');
        wp_deregister_style('select2');

        wp_dequeue_script('select2');
        wp_deregister_script('select2');
    }
}
add_action('wp_enqueue_scripts', 'custom_dequeue_stylesandscripts_select2', 100);

function custom_order_billing_fields($fields) {
    $order = array(
        "billing_first_name", 
        "billing_last_name", 
        "billing_company", 
        "billing_email", 
        "billing_phone", 
        "billing_country", 
        "billing_address_1", 
        "billing_address_2", 
        "billing_city", 
        "billing_state", 
        "billing_postcode", 

    );
    foreach($order as $field){
        $ordered_fields[$field] = $fields["billing"][$field];
    }

    $fields["billing"] = $ordered_fields;
    return $fields;

}
add_filter("woocommerce_checkout_fields", "custom_order_billing_fields");


function custom_order_shipping_fields($fields) {
    $order = array(
        "shipping_first_name", 
        "shipping_last_name", 
        "shipping_company", 
        "shipping_country", 
        "shipping_address_1", 
        "shipping_address_2", 
        "shipping_postcode",
        "shipping_city", 
        "shipping_state",  

    );
    foreach($order as $field){
        $ordered_fields[$field] = $fields["shipping"][$field];
    }

    $fields["shipping"] = $ordered_fields;
    return $fields;

}
add_filter("woocommerce_checkout_fields", "custom_order_shipping_fields");

function custom_woocommerce_shipping_package_name($i, $package) {
    return ( ( $i + 1 ) > 1 ) ? sprintf( _x( 'Verzending %d', 'verzending packages', 'woocommerce' ), ( $i + 1 ) ) : _x( 'Verzending', 'verzending packages', 'woocommerce' );
}
add_filter("woocommerce_shipping_package_name", "custom_woocommerce_shipping_package_name", 10, 2);

function wc_customer_details( $fields, $sent_to_admin, $order ) {
    if ( empty( $fields ) ) {
        if ( $order->get_customer_note() ) {
            $fields['customer_note'] = array(
                'label' => __( 'Customer note', 'woocommerce' ),
                'value' => wptexturize( $order->get_customer_note() ),
            );
        }
        if ( $order->get_billing_email() ) {
            $fields['billing_email'] = array(
                'label' => __( 'Email address', 'woocommerce' ),
                'value' => wptexturize( $order->get_billing_email() ),
            );
        }
        if ( $order->get_billing_phone() ) {
            $fields['billing_phone'] = array(
                'label' => __( 'Phone', 'woocommerce' ),
                'value' => wptexturize( $order->get_billing_phone() ),
            );
        }
    }
    return $fields;
}
add_filter( 'woocommerce_email_customer_details_fields', 'wc_customer_details', 10, 3 );

function add_in_head(){
    echo "
        <style type='text/css'>
            .woocommerce-store-notice {
                background-color: #f18b21 !important;
                position: fixed !important;
                z-index: 999 !important;
                top: auto !important;
            }
        </style>
    ";
}
add_action('wp_head', 'add_in_head');

function add_in_footer(){
    echo "
        <style type='text/css'>
            .woocommerce-store-notice {
                background-color: #f18b21 !important;
                position: fixed !important;
                z-index: 999 !important;
                top: auto !important;
            }
        </style>
    ";
    echo "
        <script type='text/javascript'>
            jQuery(window).on('load resize', function(){
                if(!jQuery('.moove-gdpr-info-bar-hidden').length){
                    setTimeout(function(){
                        var ck_height = jQuery('#moove_gdpr_cookie_info_bar').height();
                        jQuery('body .woocommerce-store-notice').css('bottom',ck_height+'px');
                    },250);
                }else{
                    jQuery('body .woocommerce-store-notice').css('bottom','0px');
                }
            });
            jQuery(document).on('click', '.moove-gdpr-infobar-allow-all', function(){
                jQuery('body .woocommerce-store-notice').css('bottom','0px');
            });
        </script>
    ";
}
add_action('wp_footer', 'add_in_footer');

function custom_flush_rules(){
    flush_rewrite_rules();
}
add_action('init', 'custom_flush_rules');

add_filter('dynamic_sidebar_params', 'add_custom_classes_to_widget'); 
function add_custom_classes_to_widget($args){
    if ($args[0]['widget_id'] == "text-2"){
        $args[0]['before_widget'] = "<div class='hideinamp'>";
        $args[0]['after_widget'] = "</div>";
    }
    return $args;
}

//include( 'wc-template-functions.php' ); 

//add_filter( 'woocommerce_order_number', 'custom_filter_order_number', 5, 2 );
function custom_filter_order_number( $order_number, $order ) {

    if ( $custom_order_number = get_post_meta( $order_number, '_order_number', true ) ) {
        //$order_number = $custom_order_number;
        //$order_number = $custom_order_number.'_new';
        delete_post_meta( $order_number, '_order_number' );
        //update_post_meta( $order_number, '_order_number', $order_number );
        //update_post_meta( $order_number, '_order_number_formatted', $order_number );
    }

    if ( $custom_order_number = get_post_meta( $order_number, '_alg_wc_custom_order_number', true ) ) {
        //$order_number = $custom_order_number;
        //$order_number = $custom_order_number.'_new';
        delete_post_meta( $order_number, '_alg_wc_custom_order_number' );
    }

    return $order_number;
}
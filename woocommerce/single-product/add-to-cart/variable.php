<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;


//check multistep product
$multistep = mspc_enabled($product->id);

if($multistep){
	?>
	<style>
		.woocommerce-tabs{
			display: block !important;
		}
	</style>
	<?php 
}

$triangle = get_post_meta($product->id); 
$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); 

$attr = $_SESSION['productAttr'];
   
if ($triangle['winddoek'][0] == 'on')     {
    $formkey = 'winddoek';
    ?>
    <style>
        .no_canvas_block {
            display:block !important;
        }
    </style>
    <?php
}
if ($triangle['triangle'][0] == 'on')     {$formkey = 'triangle';}
if ($triangle['triangle90dr'][0] == 'on') {$formkey = 'triangle90dr';}
if ($triangle['triangle90dl'][0] == 'on') {$formkey = 'triangle90dl';}
if ($triangle['triangle60d'][0] == 'on')  {$formkey = 'triangle60d';}	
if ($triangle['square'][0] == 'on')       {$formkey = 'square';}
if ($triangle['rectsquare'][0] == 'on')   {$formkey = 'rectsquare';}	
if ($triangle['isquare'][0] == 'on')      {$formkey = 'isquare';}
if ($triangle['lsquare'][0] == 'on')      {$formkey = 'lsquare';}	


if ( isset($formkey) && $formkey != 'lsquare') {
    set_query_var( 'product', $product );
	set_query_var( 'attributes', $attributes );
	set_query_var( 'attribute_keys', $attribute_keys );
	set_query_var( 'attr', $attr );
	set_query_var( 'triangle', $triangle );
	set_query_var( 'formkey', $formkey );

    get_template_part('variable-templates/lsquare_before_form');
} 

set_query_var( 'product', $product );
set_query_var( 'multistep', $multistep );
set_query_var( 'attributes', $attributes );
set_query_var( 'attribute_keys', $attribute_keys );
set_query_var( 'attr', $attr );
set_query_var( 'triangle', $triangle );
set_query_var( 'formkey', $formkey );

get_template_part('variable-templates/test-var');
?>

<form id="varform" class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $available_variations ) ) ?>">
	
    <?php 
        do_action( 'woocommerce_before_variations_form' );
        if ( empty( $available_variations ) && false !== $available_variations ) : 
  
        	echo '<p class="stock out-of-stock">'. _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ) . '</p>';

        else :  
            $custom_product = false;
            $product_categories = get_the_terms($product->post->ID,'product_cat');
            $custom_product_cat_ids = array(12, 25, 26, 27, 28, 266, 466, 467, 468, 469, 470, 471, 473, 474, 475, 476, 477, 478, 479, 633, 634, 635, 636, 637, 638, 639, 640);
            foreach ($product_categories as $key => $product_category) {
                if(in_array($product_category->term_id, $custom_product_cat_ids)){
                    $custom_product = true;
                    break;
                }
            } 

            $catid = $product_categories[0]->term_id;
            if($multistep){ 
                if  (isset($formkey)) { 
                    echo '<h2>Stap <span id="number_menu_step_item">' . (count($attributes) + 1) .'</span>: Afmeting</h2>';
    			}
            }
 
            $variations = $product->get_variation_attributes();

            if($multistep){
            	/** 
            	 * multistep
            	 */

            	set_query_var( 'product', $product );
				set_query_var( 'attributes', $attributes );
				set_query_var( 'attribute_keys', $attribute_keys );
				set_query_var( 'attr', $attr );
				set_query_var( 'triangle', $triangle );
				set_query_var( 'formkey', $formkey );

				get_template_part('variable-templates/multistep/variations_lines');
            }else{
            	/** 
            	 * single
            	 */

            	set_query_var( 'product', $product );
				set_query_var( 'attributes', $attributes );
				set_query_var( 'attribute_keys', $attribute_keys );
				set_query_var( 'attr', $attr );
				set_query_var( 'triangle', $triangle );
				set_query_var( 'formkey', $formkey );

				get_template_part('variable-templates/single/variations_lines');
			} 

            $product_variations = $product->get_available_variations();
            
            	
            $variations = $product->get_variation_attributes();

            if($multistep){
            	/** 
            	 * multistep
            	 */

            	set_query_var( 'product', $product );
				set_query_var( 'attributes', $attributes );
				set_query_var( 'attribute_keys', $attribute_keys );
				set_query_var( 'attr', $attr );
				set_query_var( 'triangle', $triangle );
				set_query_var( 'formkey', $formkey );
				set_query_var( 'product_variations', $product_variations );

				get_template_part('variable-templates/multistep/figure');
            }else{
            	/** 
            	 * single
            	 */

            	set_query_var( 'product', $product );
				set_query_var( 'attributes', $attributes );
				set_query_var( 'attribute_keys', $attribute_keys );
				set_query_var( 'attr', $attr );
				set_query_var( 'triangle', $triangle );
				set_query_var( 'formkey', $formkey );
				set_query_var( 'product_variations', $product_variations );

				get_template_part('variable-templates/single/figure');
            	
			} 
            if($multistep == false){
                if (($triangle['winddoek'][0] == 'on') && (($triangle['triangle90dr'][0] == 'on') || ($triangle['triangle90dl'][0] == 'on') || ($triangle['triangle60d'][0] == 'on'))) {
                    /** 
                     * test single bottom 1
                    */

                    get_template_part('variable-templates/single/test-single-bottom1');
                    
                } else if (($triangle['winddoek'][0] == 'on') && (($triangle['square'][0] == 'on') || ($triangle['rectsquare'][0] == 'on'))) { 
                    /** 
                    * test single bottom 
                    */

                    get_template_part('variable-templates/single/test-single-bottom2');
                } 
            }
    		
?>
<div class="clear"></div>   
<?php 
    if ($formkey != "" && $formkey != "triangle" && $formkey != "isquare" && $formkey != "lsquare" && $formkey != 'triangle60d' && $formkey != 'triangle90dl' && $formkey != 'triangle90dr' && $formkey != 'square' && $formkey != 'rectsquare') { 
        if ($gecurvd != 1) {
            if(is_product('1521566') && $_SERVER['REQUEST_URI'] == '/?post_type=product&p=1521566&preview=true'){

?>
            <style>
                .var-tab{
                    float: left !important;
                }
            </style>
            Houd zelf nog rekening met de benodigde spanmarge voor bevestiging. 
            Je doek wordt dus kleiner dan de ruimte die je hebt.:<br>
<?php 
            }else{ 
?>                    
            Onderstaand zie je de bevestigingsmaterialen waarmee je je winddoek kunt bevestigen. 
            Deze bevestigingsmaterialen zijn separaat los te bestellen in 
            <a href="/schaduwdoeken/bevestigingsmateriaal-palen-schaduwdoeken/" target="_blank">onze webshop</a>.  
            Houd zelf nog rekening met de benodigde spanmarge voor bevestiging. 
            Je doek wordt dus kleiner dan de ruimte die je hebt.:<br>
            <img src="/wp-content/uploads/2017/03/Keuze-bevestigingsmateriaal-windschermen-330.png">
                 
<?php 
            }
        }
        echo $valid_form;
    } 
    if(isset($formkey) && $multistep == true && $triangle['winddoek'][0] == 'on'){
        ?>
            <label class="holes">Jouw maatwerk doek heeft <span id="count_holes" style="font-size:16px;">XX</span> ogen.</label>
        <?php 
    }
    if (isset($formkey)) {
?>
        <div class="price2"><span class="from">Jouw prijs </span><span class="amount">€ 0,00</span> <small class="woocommerce-price-suffix">inclusief BTW</small></div>
<?php
    }
?>
        
        <div class="single_variation_wrap 1 <?php if($formkey == "lsquare"){ echo 'ds-none';}?>">
            <?php
                /**
                 * woocommerce_before_single_variation Hook.
                 */
                //if($formkey != "lsquare"){
                	do_action( 'woocommerce_before_single_variation' );
                //}
                

                /**
                 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
                 * @since 2.4.0
                 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
                 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
                 */
                do_action( 'woocommerce_single_variation' );

                /**
                 * woocommerce_after_single_variation Hook.
                 */
                //if($formkey != "lsquare"){
                do_action( 'woocommerce_after_single_variation' );
            //}
            ?>
        </div>
   

        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
<?php 
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
?>
<?php if ($formkey) { 
		/** 
        * final hidden input
        */
		set_query_var( 'formkey', $formkey );
		set_query_var( 'disc', $disc );

		get_template_part('variable-templates/final-hidden-input');
	} ?>                    
<?php
    if (($triangle['triangle'][0] == 'on') ||
    ($triangle['triangle'][0] == 'on') ||      
    ($triangle['triangle90dr'][0] == 'on') ||
    ($triangle['triangle90dl'][0] == 'on') ||
    ($triangle['triangle60d'][0] == 'on')){
?>
        <input type="button" id="submit-button-configurator" name="submit-button-configurator" value="Ga naar de stap 2 voor de maatvoering en keuze bevestigingsmateriaal"> 
        <div style="clear: both;"></div>
<?php } ?>
<?php
    if (($triangle['square'][0] == 'on') ||
    ($triangle['isquare'][0] == 'on') ||
    ($triangle['lsquare'][0] == 'on')){
?>
        <input type="button" id="submit-button-configurator" name="submit-button-configurator" value="Ga naar de stap 2 voor de maatvoering en keuze bevestigingsmateriaal"> 
        <div style="clear: both;"></div>
<?php } ?>
<?php endif; ?>
<?php do_action( 'woocommerce_after_variations_form' ); ?>
<?php 
    //$triangle = get_post_meta($product->id); 
    if ($triangle['triangle90dr'][0] == 'on') {
    	if($multistep){
    		get_template_part('calculation_price-area/multistep/area90r');
    	}else{
        	include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area90r.php'; 
        }    
    }
?>  

<?php 
    //$triangle = get_post_meta($post->ID);
    if ($triangle['triangle90dl'][0] == 'on') {
    	if($multistep){
    		get_template_part('calculation_price-area/multistep/area90l');
    	}else{
        	include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area90l.php';
        }     
    }
?> 

<?php 
    //$triangle = get_post_meta($post->ID);

    if ($triangle['triangle60d'][0] == 'on') {
        
    	if($multistep){
    		get_template_part('calculation_price-area/multistep/area60d');
    	}else{
    		include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area60d.php';    
    	} 
    }
?>
<?php 
    //$triangle = get_post_meta($post->ID);
    if ($triangle['square'][0] == 'on') {
    	if($multistep){
    		get_template_part('calculation_price-area/multistep/area4d90');
    	}else{
        	include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area4d90.php';
        }     
    }
?>
<?php 
    //$triangle = get_post_meta($post->ID);
    if ($triangle['rectsquare'][0] == 'on') {
    	if($multistep){
    		get_template_part('calculation_price-area/multistep/area4d90r');
    	}else{
        	include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area4d90r.php';
        }     
    }
?>
<?php 
    //$triangle = get_post_meta($post->ID);
    if ($triangle['isquare'][0] == 'on') {
        if($multistep){
    		get_template_part('calculation_price-area/multistep/area4isquare');
    	}     
    }
?>
<?php 
	if($triangle['triangle'][0] == 'on'){
		if($multistep){
    		get_template_part('calculation_price-area/multistep/areaTriangle');
    	}
	}
?>
</form>
<script>
    var disc = '<?php echo (100-$disc)/100; ?>' ;
</script>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

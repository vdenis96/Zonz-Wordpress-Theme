<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

	if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	
	global $post, $product, $mr_tailor_theme_options;

    $modal_class = "";
	$zoom_class = "";
	$plus_button = "";
	
	if (get_option('woocommerce_enable_lightbox') == "yes") {
        $modal_class = "fresco";
		$zoom_class = "";
		$plus_button = '<span class="product_image_zoom_button show-for-medium-up"><i class="fa fa-plus"></i></span>';
    }
	
	if ( (isset($mr_tailor_theme_options['product_gallery_zoom'])) && ($mr_tailor_theme_options['product_gallery_zoom'] == "1" ) ) {
		$modal_class = "zoom";
		$zoom_class = "easyzoom el_zoom";
		$plus_button = "";
	}	
	

?>

<?php
    
//Featured

$image_title 				= esc_attr( get_the_title( get_post_thumbnail_id() ) );
$image_src 					= wp_get_attachment_image_src( get_post_thumbnail_id(), 'shop_thumbnail' );
$image_data_src				= wp_get_attachment_image_src( get_post_thumbnail_id(), 'shop_single' );
$image_data_src_original 	= wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
$image_link  				= wp_get_attachment_url( get_post_thumbnail_id() );
$image       				= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
$image_original				= get_the_post_thumbnail( $post->ID, 'full' );
$attachment_count   		= count( $product->get_gallery_attachment_ids() );

echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="featured_img_temp">%s</div>', $image ), $post->ID );

?>

<div class="images">

	<?php if ( has_post_thumbnail() ) { ?>
    
    <div class="product_images">
        
        <div id="product-images-carousel" class="owl-carousel">
    
			<?php

            //Featured
			
			?>
			
			<div class="<?php echo $zoom_class; ?>">
                
            	<a data-fresco-group="product-gallery" data-fresco-options="fit: 'width'" class="<?php echo $modal_class; ?>" href="<?php echo esc_url($image_link); ?>">
                
					<?php echo $image; ?>
                    <?php echo $plus_button; ?>

            	</a>
           
            </div>
            
			
			<?php
            
			//Thumbs
            
            $attachment_ids = $product->get_gallery_attachment_ids();
            
            if ( $attachment_ids ) {
                
                foreach ( $attachment_ids as $attachment_id ) {
        
                    $image_link = wp_get_attachment_url( $attachment_id );
        
                    if (!$image_link) continue;
        
                    $image_title       			= esc_attr( get_the_title( $attachment_id ) );
                    $image_src         			= wp_get_attachment_image_src( $attachment_id, 'shop_single_small_thumbnail' );
					$image_data_src    			= wp_get_attachment_image_src( $attachment_id, 'shop_single' );
					$image_data_src_original 	= wp_get_attachment_image_src( $attachment_id, 'full' );
					$image_link        			= wp_get_attachment_url( $attachment_id );
				    $image		      			= wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
					
					?>                    
								
					<div class="<?php echo $zoom_class; ?>">
                        
                        <a data-fresco-group="product-gallery" data-fresco-options="fit: 'width'" class="<?php echo $modal_class; ?>" href="<?php echo esc_url($image_link); ?>">
                    
                            <img src="<?php echo esc_url($image_src[0]); ?>" data-src="<?php echo esc_url($image_data_src[0]); ?>" class="lazyOwl" alt="<?php echo esc_html($image_title); ?>">
                            <?php echo $plus_button; ?>

                        </a>
                        
                    </div>
                    
                	<?php
				
                }
                
            }
            
            ?>
                
    	</div>
        
    </div><!-- /.product_images -->

	<?php

    } else {
	echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
    }
	
    ?>

</div>
<style>
    .product-info *{
         font-family: Montserrat,sans-serif;
         font-size: 12px;
         margin-bottom: 7px;
    }
    
    .prod-name{
        color: #009DDC;
    }
    .prod-name span{
        font-size: 14.5px !important;
    }
    .before_bev label {
        font-family: Montserrat,sans-serif;
        font-size: 1rem;
        text-transform: none;
    }
</style>
<br><br>
<?php
$triangle = get_post_meta($product->id);  
if (($triangle['triangle'][0] == 'on') ||
 ($triangle['triangle90dr'][0] == 'on') ||
 ($triangle['triangle90dl'][0] == 'on') ||
 ($triangle['triangle60d'][0] == 'on') ||
 ($triangle['square'][0] == 'on') ||
 ($triangle['rectsquare'][0] == 'on') ||
 ($triangle['isquare'][0] == 'on') ||
 ($triangle['lsquare'][0] == 'on') )     {
?>
<div class="product-info">
    <div class="text1">Je hebt gekozen voor:</div>
    <div class="text1">Maatwerk product: <span class="prod-name"><?php echo $product->post->post_title; ?></span></div>
    
    <div class="div_waterbestendigheid"></div>
    <div class="div_doeksoort"></div>
    <div class="div_kleur"></div>
    <div class="div_bepaal-je-afmeting"></div>
    <div class="div_bevestigingsmateriaal">
    <div class="div_bevestigingsmateriaal1"></div>
    <div class="div_bevestigingsmateriaal2"></div>
    <div class="div_bevestigingsmateriaal3"></div>
    <div class="div_bevestigingsmateriaal4"></div>
    </div>
    <div class="div_hoe-wil-je"></div>
    <div class="div_width"></div>
    <div class="div_height"></div>
</div>
<div class="reset_var" style="display:none; background-color: #f1f1f1; color: #009ddc; font-size: 1.2rem; padding: 20px 30px;margin-top:45px;">Heb je verkeerde keuzes gemaakt?<br>
    <a class="reset_var" href="javascript:void(0);" style="color: #ef7d00;">Begin dan nu opnieuw.</a>
</div>
<?php
}
?>
<script>
    jQuery(document).ready(function(){
        var res_var =  jQuery('.res_var');
        var reset_var =  jQuery('.reset_var');
        
function bevestigingsmateriaal(z=0) {
    if (z==0) {
        if (jQuery('.before_bev').length) {
            jQuery('.before_bev').hide();
        }
        jQuery('#pa_bevestigingsmateriaal-test').parents('.variations_lines').hide();
        jQuery('#pa_bevestigingsmateriaal-test2').parents('.variations_lines').hide();
        jQuery('#pa_bevestigingsmateriaal-test3').parents('.variations_lines').hide();
        jQuery('#pa_bevestigingsmateriaal-test4').parents('.variations_lines').hide();
//        jQuery('#pa_bevestigingsmateriaal-test + div.select_box > div[data-value="geen-bevestigingsmateriaal"]').click();
//        jQuery('#pa_bevestigingsmateriaal-test2 + div.select_box > div[data-value="geen-bevestigingsmateriaal"]').click();
//        jQuery('#pa_bevestigingsmateriaal-test3 + div.select_box > div[data-value="geen-bevestigingsmateriaal"]').click();
//        jQuery('#pa_bevestigingsmateriaal-test4 + div.select_box > div[data-value="geen-bevestigingsmateriaal"]').click();
        jQuery('#pa_bevestigingsmateriaal-test option[value="geen-bevestigingsmateriaal"]').prop('selected', true);
        jQuery('#pa_bevestigingsmateriaal-test2 option[value="geen-bevestigingsmateriaal"]').prop('selected', true);
        jQuery('#pa_bevestigingsmateriaal-test3 option[value="geen-bevestigingsmateriaal"]').prop('selected', true);
        jQuery('#pa_bevestigingsmateriaal-test4 option[value="geen-bevestigingsmateriaal"]').prop('selected', true);
        jQuery('.div_bevestigingsmateriaal').hide();
        check_var();
        choose_msg();
    } else {
        if (jQuery('.before_bev').length) {
            jQuery('.before_bev').show();
        } else {
            jQuery('#pa_bevestigingsmateriaal-test').parents('.variations_lines').before('<div class="before_bev"><label>'+
                    'Kies hieronder je bevestigingsmaterialen. De lengte van het bevestigingsmateriaal brengen wij voor elke hoek '+
                    'in mindering op het formaat van jouw schaduwdoek:</label></div>');
        }
        jQuery('#pa_bevestigingsmateriaal-test').parents('.variations_lines').show();
        jQuery('#pa_bevestigingsmateriaal-test2').parents('.variations_lines').show();
        jQuery('#pa_bevestigingsmateriaal-test3').parents('.variations_lines').show();
        jQuery('#pa_bevestigingsmateriaal-test4').parents('.variations_lines').show();
        jQuery('.div_bevestigingsmateriaal').show();
    }
}
<?php if ($_SESSION["winddoek"] == 0) { ?>
        bevestigingsmateriaal();//alert(123);
<?php } ?>
        
        jQuery(document).on('click','.reset_var', function(){
            reset_var.hide();
            jQuery('.div_kleur').html('');
            jQuery('.div_bevestigingsmateriaal1').html('');
            jQuery('.div_bevestigingsmateriaal2').html('');
            jQuery('.div_bevestigingsmateriaal3').html('');
            jQuery('.div_bevestigingsmateriaal4').html('');
            res_var.click();
            bevestigingsmateriaal(1);
        });
        jQuery(document).on('change','#pa_waterproof, #pa_waterbestendigheid-wind', function(){
           
            var $select = jQuery(this).parent();
            var selectLabel = $select.parents('.variations_lines').find('.label label').text();
            var selectText = jQuery(this).find('option:selected').text();
            if(jQuery(this).attr('value')!=''){
                jQuery('.div_waterbestendigheid').html(selectLabel+': <span class="prod-name">'+selectText+'</span>'); 
                reset_var.show();
            }
            else{
                 jQuery('.div_waterbestendigheid').html('');
            }
        });
        
        jQuery(document).on('change','.doeksoort', function(){
        //alert(123)
            var $select = jQuery(this).parent();
            var selectLabel = $select.parents('.variations_lines').find('.label label').text();
            var selectText = jQuery(this).find('option:selected').text();
            var selectImg = jQuery(this).find('option:selected').attr('img-url');
            if(jQuery(this).attr('value')!=''){
                jQuery('.div_doeksoort').html(selectLabel+': <span class="prod-name">'+selectText+'</span><br /><img style="" src="'+selectImg+'">'); 
                reset_var.show();
                if ((typeof area == 'function') || (typeof sides == 'function')) {
                    if (circles[2]) {
                        area();
                    } else if (AB > 0 && BC > 0) {
                        sides();
                    }
                }
            }
            else{
                 jQuery('.div_doeksoort').html('');
            }
        });
        /*
        jQuery(document).on('change','#pa_material-type2', function(){
        //alert(111)
            var $select = jQuery(this).parent();
            var selectLabel = $select.parents('.variations_lines').find('.label label').text();
            var selectText = jQuery(this).find('option:selected').text();
            var selectImg = jQuery(this).find('option:selected').attr('img-url');
            if(jQuery(this).attr('value')!=''){
                jQuery('.div_doeksoort').html(selectLabel+': <span class="prod-name">'+selectText+'</span><br /><img style="" src="'+selectImg+'">'); 
                reset_var.show();
            }
            else{
                 jQuery('.div_doeksoort').html('');
            }
        });*/
        
        
//        jQuery(document).on('click','.attribute_pa_material-type .select_option_image', function(){
//            var img = jQuery(this).find('img').attr('src');
//            var title = jQuery(this).find('.yith_wccl_tooltip span').text();
//            
//            var imgLabel = 'Doeksoort';
//            if(!jQuery(this).hasClass('selected')){
//                 jQuery('.div_doeksoort').html('');
//            }
//            else{
//                 jQuery('.div_doeksoort').html(imgLabel+': '+title+'<br /><span class="prod-name"><img style="" src="'+img+'"></span>');
//            }
//            
//        });
        
        jQuery(document).on('click','.attribute_pa_color .select_option_image, .attribute_pa_kleur-wind .select_option_image', function(){
            var img = jQuery(this).find('img').attr('src');
            var imgLabel = 'Kleur';
            if(!jQuery(this).hasClass('selected')){
                 jQuery('.div_kleur').html('');
            }
            else{
                 jQuery('.div_kleur').html(imgLabel+': <span class="prod-name"><img style="height:80px" src="'+img+'"></span>');
                 reset_var.show();
            }
        });
//        jQuery(document).on('click','.attribute_pa_bepaal-je-afmeting .select_option_image', function(){
//            var img = jQuery(this).find('img').attr('src');
//            var imgLabel = 'Het formaat van mijn schaduwdoek';
//            if(!jQuery(this).hasClass('selected')){
//                 jQuery('.div_bepaal-je-afmeting').html('');
//            }
//            else{
//                 jQuery('.div_bepaal-je-afmeting').html(imgLabel+': <br><span class="prod-name"><img style="" src="'+img+'"></span>');
//                 reset_var.show();
//            }
//        });
        jQuery(document).on('change','#pa_bepaal-je-afmeting', function(){
        //alert(123)
            var $select = jQuery(this).parent();
            var selectLabel = $select.parents('.variations_lines').find('.label label').text();
            var selectText = jQuery(this).find('option:selected').text();
            if (selectText == 'Ik weet mijn doekmaten al') {
                jQuery('.ja').show();
                bevestigingsmateriaal();
                jQuery('.triangle h2').text('Jouw terras/pergola op schaal, geef hieronder aan op welke \
                    positie de hoeken van je maatwerk product dienen te komen door met je muis driemaal of bij \
                    een vierhoek viermaal in het kader te klikken. Je geeft dus de netto doekmaat aan; zo groot \
                    maken wij je maatwerk doek.');
                jQuery('.leni').hide();
                jQuery('.len').show();
                if (circles[2]) {
                    area();
                } else if (AB > 0 && BC > 0) {
                        sides();
                }
            } else {
                jQuery('.ja').hide();
                bevestigingsmateriaal(1);
                jQuery('.triangle h2').text('Jouw terras/pergola op schaal, geef hieronder aan op welke positie \
                    de bevestigingsmaterialen van je maatwerk product dienen te komen door met je muis driemaal of \
                    bij een vierhoek viermaal in het kader te klikken. Je geeft dus de bruto doekmaat aan; de lengte \
                    van de bevestigingsmaterialen worden door ons in mindering gebracht om te komen tot de netto doekmaat.');
                jQuery('.leni').show();
                jQuery('.len').hide();
                if (circles[2]) {
                    area();
                } else if (AB > 0 && BC > 0) {
                        sides();
                }
            }

            var selectImg = jQuery(this).find('option:selected').attr('img-url');
            if(jQuery(this).attr('value')!=''){
                jQuery('.div_bepaal-je-afmeting').html('Het formaat van mijn schaduwdoek'+': <span class="prod-name">'+selectText+'</span><br /><img style="" src="'+selectImg+'">'); 
                reset_var.show();
            }
            else{
                 jQuery('.div_bepaal-je-afmeting').html('');
            }
        });
        
        jQuery(document).on('click','.attribute_pa_bevestigingsmateriaal-test .select_option_image', function(){
            var img = jQuery(this).find('img').attr('src');
            var imgLabel = 'Bevestigingsmateriaal Hoek A';
            if(!jQuery(this).hasClass('selected')){
                 jQuery('.div_bevestigingsmateriaal1').html('');
            }
            else{
                 jQuery('.div_bevestigingsmateriaal1').html(imgLabel+': <br><span class="prod-name"><img style="height:80px" src="'+img+'"></span>');
                 reset_var.show();
            }
        });
        jQuery(document).on('click','.attribute_pa_bevestigingsmateriaal-test2 .select_option_image', function(){
            var img = jQuery(this).find('img').attr('src');
            var imgLabel = 'Bevestigingsmateriaal Hoek B';
            if(!jQuery(this).hasClass('selected')){
                 jQuery('.div_bevestigingsmateriaal2').html('');
            }
            else{
                 jQuery('.div_bevestigingsmateriaal2').html(imgLabel+': <br><span class="prod-name"><img style="height:80px" src="'+img+'"></span>');
                 reset_var.show();
            }
        });
        jQuery(document).on('click','.attribute_pa_bevestigingsmateriaal-test3 .select_option_image', function(){
            var img = jQuery(this).find('img').attr('src');
            var imgLabel = 'Bevestigingsmateriaal Hoek C';
            if(!jQuery(this).hasClass('selected')){
                 jQuery('.div_bevestigingsmateriaal3').html('');
            }
            else{
                 jQuery('.div_bevestigingsmateriaal3').html(imgLabel+': <br><span class="prod-name"><img style="height:80px" src="'+img+'"></span>');
                 reset_var.show();
            }
        });
        jQuery(document).on('click','.attribute_pa_bevestigingsmateriaal-test4 .select_option_image', function(){
            var img = jQuery(this).find('img').attr('src');
            var imgLabel = 'Bevestigingsmateriaal Hoek D';
            if(!jQuery(this).hasClass('selected')){
                 jQuery('.div_bevestigingsmateriaal4').html('');
            }
            else{
                 jQuery('.div_bevestigingsmateriaal4').html(imgLabel+': <br><span class="prod-name"><img style="height:80px" src="'+img+'"></span>');
                 reset_var.show();
            }
        });
        
        jQuery(document).on('change','#hoe-wil-je-het-doek-laten-hangen-wanneer-het-is-uitgeschoven', function(){
           
            var $select = jQuery(this).parent();
            var selectLabel = "Je wilt dat het doek als volgt hangt wanner het is uitgeschoven"; //$select.parents('.variations_lines').find('.label label').text();
            var selectText = jQuery(this).find('option:selected').text();
            if(jQuery(this).attr('value')!=''){
                jQuery('.div_hoe-wil-je').html(selectLabel+': <span class="prod-name">'+selectText+'</span>'); 
                reset_var.show();
            }
            else{
                 jQuery('.div_hoe-wil-je').html('');
            }
        });
        
        /*jQuery(document).on('change','#w2', function(){
           
            var $w = jQuery(this).val();
            if(jQuery(this).val()!=''){
                jQuery('.div_width').html('Jouw maatwerk product krijgt een breedte van '+ $w +' centimeters</span>'); 
            }
            else{
                 jQuery('.div_width').html('');
            }
        });*/
    });
</script>

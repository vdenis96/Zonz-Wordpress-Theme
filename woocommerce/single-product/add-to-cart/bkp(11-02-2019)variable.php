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
//print_r($_SESSION["winddoek"]);
//echo '<br>';
$triangle = get_post_meta($product->id); 
//echo (isset($triangle['winddoek'][0])) ? '1' : '2';
//print_r($triangle['winddoek'][0]);
$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); 

session_start();
/*
 $productAttr = array(
     'area' =>   $_POST['area'], 
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
    $_SESSION['productAttr'][$product->id] = $productAttr;  
 }*/

$attr =   $_SESSION['productAttr'];
 
$triangle = get_post_meta($product->id);  
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
//print_r($formkey);
?>
<?php if ( isset($formkey) && $formkey != 'lsquare') { ?>
<div id="dialog3" title="Waterdicht" style="display: none">
    <img src="/wp-content/uploads/2017/03/Popup-30-Afschot-Waterdichtdoek.png" style="width: 200px">
</div>
<div id="dialog2" title="Atex935" style="display: none">
    <img src="/wp-content/uploads/2017/02/Atex935-doeknummers2.jpg">
</div>
<script>
var areaoff; 
var jQ = jQuery;
var AB, BC, CA, CD, DA;
function popUp() {
    var i = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
    var maxw = jQuery(window).width()*0.8;
    maxw = (maxw > 320) ? 320 : maxw;
    maxw = parseInt(maxw);
    if (i==3) {
        jQuery( "#dialog3" ).dialog({
            width: maxw
        })
    }
}
jQuery(document).ready(function(){
    jQuery(document).on('change', '#pa_waterproof, #pa_waterbestendigheid-wind', function () {
        //if (jQuery('#pa_waterproof').val() != '') {
            //jQ('.doeksoort').click();
            if (jQuery(this).val() == 'yes' || jQuery(this).val() == 'waterdicht-windscherm') {
                jQuery('.underlabelimg').hide();
                //alert('<?php echo $_SESSION["winddoek"];	?>');
<?php //if ((!isset($triangle['winddoek'][0])) || ((isset($triangle['winddoek'][0])) && ($_SESSION["winddoek"] == 0))) {	
if (!isset($triangle['winddoek'][0]) || isset($triangle['winddoek'][0]) && $_SESSION["winddoek"] == 1) {
?>
                popUp(3);
<?php }	?>
            } else if (jQuery(this).val() == 'ilucht-en-waterdoorlatend-wind') {
                jQuery('.underlabelimg').show();
            } else {
                jQuery('.underlabelimg').hide();
            }

        //}
    });

});
</script>
<style>
.choose-msg {
    color: #ef7d00;
    font-size: 11px;
}
.choose-msg ul {
    margin: 0 0 0 10px;
}
.choose-msg li {
    list-style: none;
    display: block;
}
.choose-msg ul.list {
    margin-top: 10px;
}
.before_bev + .variations_lines .select_option .yith_wccl_tooltip,
.before_bev + .variations_lines + .variations_lines .select_option .yith_wccl_tooltip,
.before_bev + .variations_lines + .variations_lines + .variations_lines .select_option .yith_wccl_tooltip,
.before_bev + .variations_lines + .variations_lines + .variations_lines + .variations_lines .select_option .yith_wccl_tooltip {
    width: auto !important;
}
@media screen and (max-width: 1023px) {
    .jcarousel-control-prev {
        left: -15px !important;
    }
    .jcarousel-control-next {
        right: -15px !important;
    }
    .jcarousel2-control-prev {
        left: -15px !important;
    }

    .jcarousel2-control-next {
        right: -15px !important;
    }
    input[type="text"], input[type="number"] {
        font-size: 16px !important;
    }
}
@media screen and (max-width: 430px) {
    .variations_lines select {
        font-size: 0.9rem !important;
        height: 2.5rem !important;
        padding-left: 5px !important;
    }
}
</style>
<script>
function choose_msg() {
    (jQuery('.choose-msg ul li:visible').length > 0) ? jQuery('.choose-msg span').show() : jQuery('.choose-msg span').hide();
};
//var n=0;
function check_var() {
    jQuery('.variations_lines select').each(function() {
        var cl = jQuery(this).attr('id');
        (jQuery(this).val() == "") ? jQuery('.'+cl).show() : jQuery('.'+cl).hide();
        //console.log(n+' id='+cl+' val='+jQuery(this).val());
        //n++
    });
};
jQuery(document).ready(function() {
    jQuery('.variations_lines').each(function() {
        var name = jQuery(this).find('.label label').text();
        var cl = jQuery(this).find('select').attr('id');
        jQuery('.choose-msg').children('ul.list').append('<li class="' + cl + '">' + name + '</li>');
    });

    canv_sides_check();

    sides_check();

});

    function canv_sides_check() {
        if (jQuery('#w2').is(":visible")) {
            if (isNaN(jQuery('#w2').attr('digit')) && jQuery('#w2').attr('digit') > 0) { 
                jQuery('#w2').change();
                jQuery('.w2_2').hide();
            } else {
                jQuery('.w2_2').show();
            }
        } else {
            jQuery('.w2_2').hide();
        }

        if (jQuery('#h').is(":visible")) {
            if (isNaN(jQuery('#h').attr('digit')) && jQuery('#h').attr('digit') > 0) { 
                jQuery('#h').change();
                jQuery('.h_2').hide();
            } else {
                jQuery('.h_2').show();
            }
        } else {
            jQuery('.h_2').hide();
        }
        if (jQuery('#cv').is(":visible")) {
            canvas_check();
        } else {
            jQuery('.canvas_msg').hide();
            choose_msg();
        }
    }

function canvas_check() {
    if (jQuery('#cv.three').is(":visible")) {
        if (AB > 0 && BC > 0 && CA > 0) {
            jQuery('.canvas_msg').hide();
            choose_msg();
        } else {
            jQuery('.canvas_msg').show();
            choose_msg();
        }
    }
    if (jQuery('#cv.four').is(":visible")) {
        if (AB > 0 && BC > 0 && CD > 0 && DA > 0) {
            jQuery('.canvas_msg').hide();
            choose_msg();
        } else {
            jQuery('.canvas_msg').show();
            choose_msg();
        }
    }
}
function sides_check() {
    if (jQuery('#side_ab').length && jQuery('#side_ab').is(':visible')) {
        if (jQuery('#side_ab').val() > 0) {
            jQuery('.side_ab_msg').hide();
            choose_msg();
        } else {
            jQuery('.side_ab_msg').show();
            choose_msg();
        }
    } else {
        jQuery('.side_ab_msg').hide();
    }
    if (jQuery('#side_bc').length && jQuery('#side_bc').is(':visible')) {
        if (jQuery('#side_bc').val() > 0) {
            jQuery('.side_bc_msg').hide();
            choose_msg();
        } else {
            jQuery('.side_bc_msg').show();
            choose_msg();
        }
    } else {
        jQuery('.side_bc_msg').hide();
    }
    if (jQuery('#side_ca').length && jQuery('#side_ca').is(':visible')) {
        if (jQuery('#side_ca').val() > 0) {
            jQuery('.side_ca_msg').hide();
            choose_msg();
        } else {
            jQuery('.side_ca_msg').show();
            choose_msg();
        }
    } else {
        jQuery('.side_ca_msg').hide();
    }
    if (jQuery('#side_cd').length && jQuery('#side_cd').is(':visible')) {
        if (jQuery('#side_cd').val() > 0) {
            jQuery('.side_cd_msg').hide();
            choose_msg();
        } else {
            jQuery('.side_cd_msg').show();
            choose_msg();
        }
    } else {
        jQuery('.side_cd_msg').hide();
    }
    if (jQuery('#side_da').length && jQuery('#side_da').is(':visible')) {
        if (jQuery('#side_da').val() > 0) {
            jQuery('.side_da_msg').hide();
            choose_msg();
        } else {
            jQuery('.side_da_msg').show();
            choose_msg();
        }
    } else {
        jQuery('.side_da_msg').hide();
    }
}

jQuery(document).on('change', '.variations_lines select', function() {
    check_var();
    choose_msg();
});
jQuery(document).on('change, keyup', '#w2, #h', function() {
    var cl2 = jQuery(this).attr('id');
    (jQuery(this).attr('digit') > 0) ? jQuery('.'+cl2+'_2').hide() : jQuery('.'+cl2+'_2').show();
    choose_msg();
});

</script>
<?php }	?>
<script>
jQuery(document).ready(function(){
    jQuery('.variations_lines .value.select_option').hover(function () {
        var mar_top = jQuery(this).offset().top;
        var sel_w = jQuery(this).width()+10;
        var tooltip = jQuery(this).children('.yith_wccl_tooltip')
        var scroll = jQuery(window).scrollTop();
        //console.log(mar_top)
        //console.log(tooltip.height())
        var windowWidth = jQuery(window).width();
        if ((mar_top-scroll-90) < tooltip.height() && windowWidth > 1023) {
        //if ((mar_top-scroll-90) < tooltip.height()) {
            tooltip.css('top', '0px')
            tooltip.css('left', 'auto')
            tooltip.css('right', sel_w+'px')
        } else {
            /*tooltip.css('top', '')
            tooltip.css('left', '')
            tooltip.css('right', '')*/
        }
        if (windowWidth < 500) {
            tooltip.css('max-width', sel_w)
            tooltip.css('font-size', "11px")
        };
    });
    jQuery('#submit-button-configurator').click(function(){
        window.location.href = "#stap2";
        if (window.location.hash == "#stap2") {
        jQuery(this).attr('style', 'display:none!important');
        jQuery('.triangle').attr('style', 'display:block!important');
        jQuery('.checkb').attr('style', 'display:block!important');
//        jQuery('.step1').attr('style', 'display:none!important');
//        jQuery('.step2').attr('style', 'display:block!important');
        jQuery('.woocommerce-variation-add-to-cart').attr('style', 'display:block!important');
        jQuery('.variations-table:first-child tr:nth-child(1), .variations-table:first-child tr:nth-child(2),.variations-table:first-child tr:nth-child(3)').attr('style', 'display:none!important');
        jQuery('.variations .variations_lines:nth-child(1), .variations .variations_lines:nth-child(2), .variations .variations_lines:nth-child(3)').attr('style', 'display:none!important');
        jQuery('.variations-table:first-child tr:nth-child(4),.variations-table:first-child tr:nth-child(5),.variations-table:first-child tr:nth-child(6),.variations-table:first-child tr:nth-child(7),.variations-table:first-child tr:nth-child(8),.variations-table:first-child tr:nth-child(9)').attr('style', 'display:table-row!important');
        jQuery('.variations .variations_lines:nth-child(4),.variations .variations_lines:nth-child(5),.variations .variations_lines:nth-child(6),.variations .variations_lines:nth-child(7),.variations .variations_lines:nth-child(8),.variations .variations_lines:nth-child(9)').attr('style', 'display:block!important');
        rax();
        }
    });
    window.onhashchange = function() {
     if (window.location.hash == "") {
        jQuery('#submit-button-configurator').attr('style', 'display:block!important');
        jQuery('.triangle').attr('style', 'display:none!important');
        jQuery('.checkb').attr('style', 'display:none!important');
        jQuery('.step1').attr('style', 'display:block!important');
        jQuery('.step2').attr('style', 'display:none!important');
        jQuery('.woocommerce-variation-add-to-cart').attr('style', 'display:none!important');
        jQuery('.variations-table:first-child tr:nth-child(1), .variations-table:first-child tr:nth-child(2),.variations-table:first-child tr:nth-child(3)').attr('style', 'display:block!important');
        jQuery('.variations .variations_lines:nth-child(1), .variations .variations_lines:nth-child(2), .variations .variations_lines:nth-child(3)').attr('style', 'display:block!important');
        jQuery('.variations-table:first-child tr:nth-child(4),.variations-table:first-child tr:nth-child(5),.variations-table:first-child tr:nth-child(6),.variations-table:first-child tr:nth-child(7),.variations-table:first-child tr:nth-child(8),.variations-table:first-child tr:nth-child(9)').attr('style', 'display:none!important');
        jQuery('.variations .variations_lines:nth-child(4),.variations .variations_lines:nth-child(5),.variations .variations_lines:nth-child(6),.variations .variations_lines:nth-child(7),.variations .variations_lines:nth-child(8),.variations .variations_lines:nth-child(9)').attr('style', 'display:none!important');

     }
    }
    jQuery(document).on('change, keyup', '#h, #w2', function () {
        //rax2();
    });
    jQuery(document).on('blur', '#h, #w2', function () {
        h  = parseFloat(jQuery('#h').val().replace(",", "."));
        (jQuery('#w2').length) ? w2 = parseFloat(jQuery('#w2').val().replace(",", ".")) :'';
        
        (jQuery('#h').val().length) ? jQuery('#h').val(h.toFixed(0).toString().replace(/\./g, ',')) :'';
        if (jQuery('#w2').length) {
            (jQuery('#w2').val().length) ? jQuery('#w2').val(w2.toFixed(0).toString().replace(/\./g, ',')) :'';
        }
        
        if(jQuery('#w2').val()!='' && jQuery('#w2').length){
                jQuery('.div_width').html('Jouw maatwerk product krijgt een breedte van <span class="prod-name">'+ w2 +' centimeters</span></span>'); 
            }
            else{
                jQuery('.div_width').html('');
            }
        if(jQuery('#h').val()!=''){
                jQuery('.div_height').html('Jouw maatwerk product krijgt een uitschuifbare lengte van <span class="prod-name">'+ h +' centimeters</span></span>'); 
            }
            else{
                jQuery('.div_height').html('');
            }
            
        rax2();
    });
    //alert(window.location.hash);
    //jQuery('#h').blur();
    
    setTimeout(function(){ jQuery('#h').blur();; }, 1500);
    jQuery('.name-price').html(jQuery('h1.product_title').text()+jQuery('p.price').html());
    //jQuery('#pa_orderid option:selected').remove();
    //jQuery('#pa_orderid option').attr('selected','selected');
    //jQuery('#pa_orderid option:selected').val(Math.random(0,999999));
});
function terras() {
    var terras_h ;
    if (jQuery('#h').is(":visible")) {
        terras_h = (jQuery('#h').attr('digit') > 0) ? jQuery('#h').attr('digit')*100 : 0;
    }
    (terras_h > 400) ? terras_h = 400 : "";
    jQuery('.tabl canvas+img').height(terras_h);
};
function rax2() {
    h  = parseFloat(jQuery('#h').val().replace(",", "."));
    
    (jQuery('#w2').length) ? w2 = parseFloat(jQuery('#w2').val().replace(",", ".")) : '';
    var w_breedte;
    if (jQuery('#w2').length) {
        var row = jQuery(".breedte-label").parent().width();
        //w_breedte = jQuery('#w2').val();
        //w_breedte = (jQuery('#w2').val() > w_breedte) ? w_breedte : (jQuery('#w2').val());
        w_breedte = (jQuery('#w2').val() < 100) ? 100 : (jQuery('#w2').val());
        w_breedte = (row < w_breedte) ? row : w_breedte;
        jQuery(".breedte-label").css('width', w_breedte+"px");
        console.log(w_breedte)
    }
    jQuery('#h').attr('digit',h/100);
    (jQuery('#w2').length) ? jQuery('#w2').attr('digit',w2/100) : '';
    if (jQuery('#h').attr('digit') > 0 && jQuery('#w2').attr('digit') > 0) {
        jQuery('.terras').show();
    } else {
	jQuery('.terras').hide();
    }
    var zijde_car = jQuery('label.beforejcar, .jcarouseles, .jcarouseles + label.holes');
if (jQuery('#h').is(':visible')) {
    if (jQuery('#w2').length) {
        if (jQuery('#w2').val() > 0) { 
            if (jQuery('#h').val() > 0){
                zijde_car.show();
            } else {
                zijde_car.hide();
            }
        } else {
            zijde_car.hide();
        }
    } else {
        if (jQuery('#h').val() > 0){
            zijde_car.show();
        } else {
            zijde_car.hide();
        }
    }
}



    

    if (typeof rax == "function" ) {
        rax();
    } else {
        return false;
    };
    if (typeof holes_count == "function" ) {
        holes_count();
    } else {
        return false;
    };
};

</script>

<style>
    .underlabelimg {display:none;}
    #pa_orderid {display: none;}
    label[for=pa_orderid] {display: none;}
    p.price {
        font-size: 2em !important;
    }
    .price2 {
        color: rgb(74, 79, 106);
        font-size: 2em !important;
        margin: 10px 0;
    }
    .triangle .price2 * {
        color: rgb(74, 79, 106);
        font-size: 32px;
        margin: 10px 0;
    }
    .triangle .price2 .woocommerce-price-suffix {
        font-size: 19px;
    }
    .name-price {
        float: right;
        font-size: 30px;
        font-weight: bold;
    }
/*    .variations-table tr, .triangle, .variations .variations_lines{
      display: none !important;
    }*/
    
    .variations-table:first-child tr:nth-child(1),
    .variations-table:first-child tr:nth-child(2),
    .variations-table:first-child tr:nth-child(3)
    {
        display: table-row!important;
    }
    .variations .variations_lines:nth-child(1),
    .variations .variations_lines:nth-child(2),
    .variations .variations_lines:nth-child(3)
    {
        display: block!important;
    }
    .product_navigation {display: none}
    .inform {display: none}
<?php if ($formkey != "") { ?>
    .woocommerce-tabs {display:none;}
<?php } ?>
    .recently_viewed_in_single_wrapper {display:none;}
    /*.woocommerce-variation-add-to-cart {display: none;}*/
    #submit-button-configurator {
        padding: 17px 13px;
        width: 100%;
        white-space: normal;
    }
    .variations_lines > .select_option {float: none;}
    .variations_lines > .select_option .yith_wccl_tooltip {
        left: 0;
        margin-left: 0;
        width: 400px;
    }
    .select_option .yith_wccl_tooltip > span {
        padding: 15px !important;
    }
    a.reset_variations {visibility: hidden !important;}
    .var-tab {
        margin-top: 20px;
    }
    .var-tab tr{
        position: relative;
    }
    .step2 {display: none;}
.triangle .var-tab label, .triangle .tabl label{
    font-size: 1rem;
}
.triangle * {
    font-family: Montserrat,sans-serif;
    font-size: 13px;
    text-transform: none;
}
.clear {
    clear: both;
}
.single_add_to_cart_button {
    text-transform: none;
}
                <?php 
                    $triangle = get_post_meta($product->id); 
                    if ($triangle['triangle'][0] == 'on' || $triangle['isquare'][0] == 'on' || $triangle['lsquare'][0] == 'on') { ?>
    .triangle + .clear + .price2 + .single_variation_wrap {
        display: none !important;
    }
    .triangle + .clear + .price2 {
        display: none !important;
    }
    
    <?php 
    }
    ?>
    <?php 
                    $triangle = get_post_meta($product->id); 
                    if ($triangle['lsquare'][0] == 'on') { ?>
    .var-tab tr{
        display: block;
        /*height: 63px;*/
    }
    
    <?php 
    }
    ?>
    .step1, .step2{
          font-family: "custom-one" !important;
          color:#009DDC;
          font-weight: bold;
          font-size: 14.5px;
    }
    
    .step1 span, .step2 span{
        font-size:14.5px!important;
    }
    
    
    .variations_lines.hidden .label, .variations_lines.hidden .value {
    display: block !important;
}

.entry-content.triangle{
    width:auto!important;
}

#submit-button-configurator{
    display: none !important;
}
table.intable tr {display: table-row;}
.select_option .yith_wccl_tooltip > span {text-align:left;}
    .dtext {
        line-height: 1.6;
        background-image: url('/wp-content/uploads/2017/02/Zonz-Sail.png');
        background-repeat: no-repeat;
        background-position: left 7px;
        padding-left: 20px;
    }
#pa_bepaal-je-afmeting option {display:none;}
.cornerinfo {display:none;}
.before_breedte label {
    font-size: 1rem;
    text-transform: none;
}
.breedte-label {
    display: block;
    text-align: center;
}
#w2, #h {
    display: inline-block !important; 
    width: auto !important;
}
</style>

<form id="varform" class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
        
        <?php 
        $catid = get_the_terms($product->post->ID,'product_cat');
        $catid = $catid[0]->term_id;
        if  (isset($formkey)) { ?>
        <div class="step1">Ontwerp jouw eigen <?php echo $product->post->post_title; ?><br><br></div>
        <?php } ?>
		<div class="variations">
			<?php foreach ( $attributes as $attribute_name => $options ) : ?>
                        <?php 
                        $taxname = str_replace('pa_','',$attribute_name); 
                        global $wpdb;
                        $query = "SELECT ".$wpdb->prefix."yith_wccl_meta.meta_value 
                                  FROM ".$wpdb->prefix."woocommerce_attribute_taxonomies 
                                  LEFT JOIN ".$wpdb->prefix."yith_wccl_meta 
                                  ON ".$wpdb->prefix."yith_wccl_meta.wc_attribute_tax_id = ".$wpdb->prefix."woocommerce_attribute_taxonomies.attribute_id 
                                  WHERE ".$wpdb->prefix."woocommerce_attribute_taxonomies.attribute_name = '$taxname' 
                                  AND ".$wpdb->prefix."yith_wccl_meta.meta_key = '_wccl_attribute_description'";
                        $data = $wpdb->get_results($query, ARRAY_A);
                        $select_desc = $data[0]['meta_value'];
                        ?>
				<div class="variations_lines">
					<div class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></div>
                                        <?php if (sanitize_title( $attribute_name ) == 'hoe-wil-je-het-doek-laten-hangen-wanneer-het-is-uitgeschoven') { ?>
                                        <div class="underlabelimg">
                                            <img src="/wp-content/uploads/2017/02/standaard_losse_lamellen.png">
                                            <img src="/wp-content/uploads/2017/02/standaard_rechte_lamellen.png">
                                        </div>

                                        <?php } else if (($triangle['winddoek'][0] == 'on') && (sanitize_title( $attribute_name ) == 'pa_waterproof' || sanitize_title( $attribute_name ) == 'pa_waterbestendigheid-wind')) { ?>
                                        <div class="underlabelimg">
                                            <img src="/wp-content/uploads/2017/02/Doeksoorten-Maatwerk-Windschermen.png" style="width: 100%; max-width: 514px">
                                        </div>

                                        <?php } ?>
                                        
					<div class="value <?php if ($attribute_name != 'pa_color' && !empty($select_desc)) echo 'select_option';?>">
						<?php
							$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
							wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
							echo end( $attribute_keys ) === $attribute_name ? '<a class="reset_variations res_var" href="#">' . __( 'Clear', 'woocommerce' ) . '</a>' : '';
						?>
                        <span class="yith_wccl_tooltip top fade"><span><?php echo wp_kses_stripslashes($select_desc); ?></span></span>
					</div>
				</div>
	        <?php endforeach;?>
		</div>
                <?php $triangle = get_post_meta($product->id); 
                $before_breedte = ""; 
                $valid_form = '<div class="dtext">De prijs is incl. verzendkosten</div>
    <div class="dtext">De levertijd bedraagt 3-4 wkn</div>
    <div class="choose-msg">
    <br>
    <span style="font-size: 16px;line-height: 20px;">Onderstaande keuzes dien je hierboven nog te maken alvorens je je maatwerk product kunt bestellen:</span>
    <ul class="list">
    </ul>
    <ul class="">
        <li class="w2_2">Breedte</li>
        <li class="h_2">Lengte</li>
    </ul>
    <ul class="canvas_msg">
        <li>Teken hierboven je ZONZ maatwerk</li>
    </ul>
    <ul class="sides_msg">
        <li  class="side_ab_msg">Lengte zijde AB</li>
        <li  class="side_bc_msg">Lengte zijde BC</li>
        <li  class="side_ca_msg">Lengte zijde CA</li>
        <li  class="side_cd_msg">Lengte zijde CD</li>
        <li  class="side_da_msg">Lengte zijde DA</li>
    </ul>
</div>';
                
    if ($triangle['winddoek'][0] == 'on') {
    $before_breedte = '<div class="before_breedte"><label>BEPAAL JE AFMETING:</label></div>';
    }
?>
        
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
        
                <?php 
                
                $gecurvd = $_SESSION["gecurvd"];
                $_SESSION["gecurvd"] = 0;
    //$string = $product->get_image(1);
    //echo $string;

    echo $before_breedte;           
    if (($triangle['triangle90dr'][0] == 'on') || ($triangle['triangle90dl'][0] == 'on')) { 
        $a_len = ($triangle['triangle90dl'][0] == 'on') ? "Lengte zijde AB:" : "Lengte zijde BC:"; 
        $b_len = ($triangle['triangle90dl'][0] == 'on') ? "Lengte zijde AC:" : "Lengte zijde AC:"; 
        if ($gecurvd) {
?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );?>
            <img src="<?php  echo $image[0]; ?>"><br>
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label for="w2"><?=$b_len;?></label></td>
            <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
        </tr>
        <tr>
            <td><label for="h"><?=$a_len;?></label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
        </tr>
    </table>
    <br>
</div>  
    <?php
        } else {
    ?>        
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );?>
            <img src="<?php  echo $image[0]; ?>"><br>
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label for="w2"><?=$b_len;?></label></td>
            <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
        </tr>
        <tr>
            <td><label for="h"><?=$a_len;?></label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/>cm</td>
        </tr>
    </table>
    <br>
</div>  
    <?php
        }
    }
    ?> 
                
                <?php 
                    $triangle = get_post_meta($product->id); 
                    if ($triangle['triangle60d'][0] == 'on') { 
        if ($gecurvd) {
?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
    </table>
    <br>
</div> 
    <?php
        } else {
    ?>        
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
    </table>
    <br>
</div>  
                <?php
                }
        }
                ?> 
                
                <?php 
                    $triangle = get_post_meta($product->id); 
                    if ($triangle['triangle'][0] == 'on') { ?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
    <?php /*if ($triangle['winddoek'][0] == 'on'){ ?>
        <tr>
            <td><label>Breedte in cm:</label></td>
            <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
        <tr>
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();terras();" onkeyup="rax2();terras();"/></td>
        </tr>
        <tr class="terras" style="display:none">
            <td colspan="2"><label>Scroll omlaag om jouw  winddoek in te tekenen op jouw terras.</label></td>
        </tr>
    <?php } else { */?>
        <tr class="ja no_canvas_block" style="display:none;">
            <td colspan="2">
            <div class="dtext">Ik vul mijn doekmaten in</div>
            <div class="dtext">Vul de maten in hele cm’s in</div>
            <div class="dtext">Bepaal de maat altijd van bovenaf gezien</div>
            <div class="dtext">Bij twijfel, bestel je doek liever iets te klein, dan te groot</div>
            </td>
        </tr>
        <!--
        <tr class="ja" style="display:none;">
            <td colspan="2">
                Bepaal eerst de breedte en lengte van de locatie (terras, pergola) waar jouw maatwerk doek komt te hangen.
            </td>
        </tr>
        -->
        <tr class="canvas_block">
            <td colspan="2"><label>Vul eerst de maten van je terras/pergola in:</label></td>
        </tr>
        <tr class="canvas_block">
            <td><label>Breedte in cm:</label></td>
            <td width="20%"><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
        <tr class="canvas_block">
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();terras();" onkeyup="rax2();terras();"/></td>
        </tr>
        <tr class="terras2" style="display:none">
            <td class="canvas_block" colspan="2"><label>Scroll omlaag om jouw  winddoek in te tekenen op jouw terras.</label></td>
        </tr>
        <div class="sizes_enrty no_canvas_block">
            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );?>
            <img src="<?php  echo $image[0]; ?>"><br>
            <label>Lengte zijde AB:</label> <input id="side_ab" class="side_size" type="text"> cm<br>
            <label>Lengte zijde BC:</label> <input id="side_bc" class="side_size" type="text"> cm<br>
            <label>Lengte zijde CA:</label> <input id="side_ca" class="side_size" type="text"> cm<br>
        </div>
    <?php //} ?>
    </table>
</div>  
                <?php
                    }
                ?> 
                <?php 
                    $triangle = get_post_meta($product->id); 
                    if ($triangle['square'][0] == 'on') { 
        if ($gecurvd) {
?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
    </table>
    <br>
</div>  
    <?php
        } else {
    ?> 

<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
    </table>
    <br>
</div>  
                <?php
        }
    }
                ?> 
                <?php 
                    $triangle = get_post_meta($product->id); 
                    if ($triangle['rectsquare'][0] == 'on') { 
        if ($gecurvd) { 
?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label>Lengte zijde AB & CD:</label></td>
            <td><input id="h" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();" />cm</td>
        </tr>
        <tr>
            <td><label>Lengte zijde BC & AD:</label></td>
            <td><input id="w2" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();" style="display: inline-block; width: auto;"/>cm</td>
        </tr>
    </table>
    <br>
</div>
    <?php
        } else { 
    ?> 

<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label>Lengte zijde AB & CD:</label></td>
            <td><input id="h" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();" style="display: inline-block; width: auto;"/>cm</td>
        </tr>
        <tr>
            <td><label>Lengte zijde BC & AD:</label></td>
            <td><input id="w2" name="h" type="text" value="" onchange="rax2();" onkeyup="rax2();" style="display: inline-block; width: auto;"/>cm</td>
        </tr>
    </table>
    <br>
</div>  
                <?php
        }
    }
                ?> 
                <?php 
                    $triangle = get_post_meta($product->id); 
                    if ($triangle['isquare'][0] == 'on') { ?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <table class="variations-table2222 var-tab" cellspacing="0">
    <?php /*if ($triangle['winddoek'][0] == 'on'){  ?>
        <tr>
            <td><label>Breedte in cm:</label></td>
            <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
        <tr>
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();terras();" onkeyup="rax2();terras();"/></td>
        </tr>
        <tr class="terras" style="display:none">
            <td colspan="2"><label>Scroll omlaag om jouw  winddoek in te tekenen op jouw terras.</label></td>
        </tr>
    <?php } else {*/ ?>
        <div class="sizes_enrty no_canvas_block">
            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );?>
            <img src="<?php  echo $image[0]; ?>"><br>
            <label>Lengte zijde AB:</label> <input id="side_ab" class="side_size" type="text"> cm<br>
            <label>Lengte zijde BC:</label> <input id="side_bc" class="side_size" type="text"> cm<br>
            <label>Lengte zijde CA:</label> <input id="side_ca" class="side_size" type="text"> cm (= E, de diagonaal)<br>
            <label>Lengte zijde CD:</label> <input id="side_cd" class="side_size" type="text"> cm<br>
            <label>Lengte zijde DA:</label> <input id="side_da" class="side_size" type="text"> cm<br>
        </div>
        <tr class="canvas_block">
            <td colspan="2"><label>Vul eerst de maten van je terras/pergola in:</label></td>
        </tr>
        <tr class="canvas_block">
            <td><label>Breedte in cm:</label></td>
            <td><input id="w2" name="w2" type="text" value="" onchange="rax2();" onkeyup="rax2();"/></td>
        </tr>
        <tr class="canvas_block">
            <td><label>Lengte in cm:</label></td>
            <td><input id="h" name="h" type="text" value="" onchange="rax2();terras();" onkeyup="rax2();terras();"/></td>
        </tr>
    <?php //} ?>
    </table>
    <br>
</div>  
                <?php
                    }
                ?> 
                
                <?php 
                    $triangle = get_post_meta($product->id); 
                    if ($triangle['lsquare'][0] == 'on') { ?>
<div class="entry-content triangle" style="width: 400px; margin: 0 auto; display: block;">
    <img src="/wp-content/uploads/2016/03/Productconfigurator-LAMELLEN-HARMONICADOEK1.png"/>
    <table class="variations-table2222 var-tab" cellspacing="0">
        <tr>
            <td><label for="w2">Breedte in cm (max. 400cm):</label></td>
            <td width="20%">
                <input id="w2" name="w2" type="text" value="" min="0" max="4" step="1" onchange="rax2();" onkeyup="rax2();"/>
                <div class="w-err-msg err-msg" style="display: none;">De maximale breedte van uw eigen Lamellen Harmonicadoek is 4.00 meter</div>
            </td>
        </tr>
        <tr>
        <td colspan="2">Vul de afstand tussen je pergolabalken of muren in.</td>
        </tr>
        <tr>
        <td colspan="2">Standaard is de lamellengte 42-60cm, afhankelijk van de gekozen doeksoort. Geen keuze in lamellengte.</td>
        </tr>
        <tr>
        <td colspan="2">Wij trekken er standaard 4 centimeter in de breedte van af, voor 2 centimeter vrije ruimte aan weerszijden.</td>
        </tr>
        <tr>
            <td><label for="h">Uitschuifbare lengte in cm:</label></td>
            <td  width="20%">
                <input id="h" name="h" type="text" value="" min="0" max="9" step="1" onchange="rax2();" onkeyup="rax2();"/>
                <div class="h-err-msg err-msg" style="display: none;">De maximale lengte van uw eigen Lamellen Harmonicadoek is 9.00 meter</div>
                <div class="a-err-msg err-msg" style="display: none;">De door jou ingevoerde afmetingen overschrijdt ons advies dat waterdichte doeken maximaal 16 m2 groot mogen zijn. Wij adviseren je de afmetingen van je waterdichte doek aan te passen dan wel te kiezen voor een waterdoorlatend doek.</div>
            </td>
        </tr>
        <tr>
        <td colspan="2">Afwijkende lengtematen worden op een volledige laatste lamellengte afgerond.</td>
        </tr>
        <tr style="display: table-row">
        <td colspan="2">
        <table class="intable">
        <tbody>
            <tr>
                <td>3 rijen ogen bij 200-400cm breedte<br><img src="/wp-content/uploads/2016/12/Lamellen-harmonicadoek-configurator.png"/></td>
                <td></td>
            </tr>
            <tr>
                <td>2 rijen ogen bij < 200cm breedte</td>
                <td></td>
            </tr>
            <tr>
                <td><img src="/wp-content/uploads/2016/12/Lamellen-harmonicadoek-configurator2.png"/></td>
                <td></td>
            </tr>
            <tr>
                <td>De buitenste lamellen hebben altijd 3 ogen te bevestiging</td>
                <td></td>
            </tr>
        </tbody>
        </table>
        </td>
        </tr>
    </table>
</div>  
                <?php
                    }
                ?> 
<?php
                if (($triangle['winddoek'][0] == 'on') && (
                    ($triangle['triangle90dr'][0] == 'on') ||
                    ($triangle['triangle90dl'][0] == 'on') ||
                    ($triangle['triangle60d'][0] == 'on'))	
                    ) {
                ?>
                    <div class="clear"></div>
                    <label class='beforejcar'>Maak je keuze in randafwerking. Dit kan per zijde verschillen.</label>
                    <div class="jcarouseles"></div>
                    <label class="holes">Jouw maatwerk doek heeft <span style="font-size:16px;">XX</span> ogen.</label>
<!-- Carouseles zijde code 3corner-->
<script>
var holes = 0;
var holes_c;
function holes_count() {
    var k1_holes = [0,0,0];
    var L;
    jQ('.zijde').each(function(){
        var id  = jQ(this).attr('id');
        var n;
        if (jQ(this).val() == 'k1-verstevigingsband-met-verchroomde-zeilogen-om-de-30-cm') {
            holes+=1;
            switch(id) {
                case 'pa_zijde-ab':
                    k1_holes[0]=1;
                    break;
                case 'pa_zijde-bc':
                    k1_holes[1]=1;
                    break;
                case 'pa_zijde-ca':
                    k1_holes[2]=1;
                    break;
                default:
                    break;
            };
        }; 
        (jQ(this).val() == 'k2-verstevigingsband-met-verchroomde-zeilogen-op-de-hoeken') ? holes+=10   : "";
        (jQ(this).val() == 'k3-alleen-verstevigingsband')                                ? holes+=100  : "";
        (jQ(this).val() == 'k4-holle-zoom-van-2cm-diamater')                             ? holes+=1000 : "";
        
        L = k1_holes[0]*AB+k1_holes[1]*BC+k1_holes[2]*CA;
    });
    
    console.log(k1_holes);
    
    switch(holes) {
        case 3000:
        case 2100:  
        case 1200:
        case  300:
            holes_c = 0;
            break;
        case 2010:
        case 1110:
        case  210:
            holes_c = 2;
            break;
        case 1020:
        case  120:
        case   30:
            holes_c = 3;
            break;
        case 2001:
        case 1101:
        case  201:
            holes_c = Math.ceil(L/0.3) + 2; //"1 * L-K1 / 30 + 2";
            break;
        case 1011:
        case  111:
            holes_c = Math.ceil(L/0.3) + 2; //"1 * L-K1 / 30 + 2";
            break;
        case 1002:
        case  102:
            holes_c = Math.ceil(L/0.3) + 2; //"2 * L-K1 / 30 + 2";
            break;
        case   21:
            holes_c = Math.ceil(L/0.3) + 4; //"1 * L-K1 / 30 + 4";
            break;
        case   12:
            holes_c = Math.ceil(L/0.3) + 2; //"2 * L-K1 / 30 + 2";
            break;
        case    3:
            holes_c = Math.ceil(L/0.3) + 2; //"3 * L-K1 / 30 + 2";
            break;
        default:
            holes_c = 'XX';
    } 
    jQ('.holes span').html(holes_c+'<span style="display:none; font-size:16px;">'+holes+'</span>');
    holes = 0;
    if (!areaoff) {
        if (typeof circles[0] != 'undefined') {
            area();
            console.log('area');
        }
    } else {
        sides(); 
        console.log('sides');
    }
}
jQuery( document ).ready(function() {
    var count = 0;
    if (jQuery('.jcarouseles').text() == '') {
        jQuery('.zijde').each(function(){
            var select_id = jQ(this).attr('id');
            jQuery(this).parents('.variations_lines').hide();
            var label = jQuery('label[for='+select_id+']').text();
            var innercarousel = '<div id="jc'+count+'">\
                        <label>'+label+'</label>\
                        <div class="sel_name"></div>\
                        <div class="jcarousel2" sel_id="'+select_id+'">\
                        <ul>';
            var options = jQuery(this).children('option');
            var w = jQuery('.jcarouseles').width();
            for(var i = 1; i < options.length; i++) {
              options[i].getAttribute('img-url');  
              innercarousel+='<li opt="'+options[i].getAttribute('value')+'"><img src="'+options[i].getAttribute('img-url')+'" width="'+w+'"><a type="button">Kies deze</a></li>';
            }
            innercarousel += '</ul>\
                        </div>\
                        <a href="#" class="jcarousel2-control-prev">&lsaquo;</a>\
                        <a href="#" class="jcarousel2-control-next">&rsaquo;</a>\
                        </div>';
            jQuery('.jcarouseles').append(innercarousel);
            count++;
        })
    }
    jQ('.jcarousel2 > ul > li > a').on("click", function() {
        var opt = jQ(this).parent().attr('opt');
        console.log(opt);
        var sel = jQ(this).parents('.jcarousel2').attr('sel_id');
        var opt_text = jQ('#'+sel).children('option[value="'+opt+'"]').text();
        var img = jQ(this).siblings('img').attr('src');
        jQ(this).parents('.jcarousel2').hide().siblings('a').hide();
        jQ(this).parents('.jcarousel2').siblings('.sel_name').html(opt_text+'<br><img src="'+img+'" width="150">').show();
        jQ('#'+sel).children('option[value="'+opt+'"]').prop('selected', true);
        jQ('#'+sel).change();
        holes_count();
    });
    jQ('.jcarouseles > div .sel_name').on("click", function() {
        jQ(this).hide();
        jQ(this).siblings('.jcarousel2, a').show();
    });
    jQ('.jcarousel2').jcarousel();
    jQ('.jcarousel2-control-prev')
            .on('jcarouselcontrol:active', function() {
                jQ(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQ(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        jQ('.jcarousel2-control-next')
            .on('jcarouselcontrol:active', function() {
                jQ(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQ(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });
    
})
</script>
<style>
/** Carousel **/

.jcarousel2 {
    display: block;
    position: relative;
    overflow: hidden;
}

.jcarousel2 ul {
    width: 20000em;
    position: relative;
    list-style: none;
    margin: 0;
    padding: 0;
}

.jcarousel2 li {
    float: left;
}

/** Carousel Controls **/

.jcarousel2-control-prev,
.jcarousel2-control-next {
    position: absolute;
    top: 155px;
    width: 30px;
    height: 30px;
    text-align: center;
    background: #4E443C;
    color: #fff !important;
    text-decoration: none;
    text-shadow: 0 0 1px #000;
    font: 24px/27px Arial, sans-serif;
    -webkit-border-radius: 30px;
       -moz-border-radius: 30px;
            border-radius: 30px;
    -webkit-box-shadow: 0 0 2px #999;
       -moz-box-shadow: 0 0 2px #999;
            box-shadow: 0 0 2px #999;
}

.jcarousel2-control-prev {
    left: -40px;
}

.jcarousel2-control-next {
    right: -40px;
}

.jcarousel2-control-prev:hover span,
.jcarousel2-control-next:hover span {
    display: block;
}

.jcarousel2-control-prev.inactive,
.jcarousel2-control-next.inactive {
    opacity: .5;
    cursor: default;
}

/** Carousel Pagination **/


    /*.jcarousel2, .jcarousel2-control-prev, .jcarousel2-control-next {display: none}*/
    .jcarousel2 ul li {position: relative}

    .jcarousel2 ul li a {
        background-color: #ef7d00;
        border: 2px solid #000;
        color: #fff;
        display: block;
        font-weight: bold;
        left: 50%;
        padding: 0 20px;
        position: absolute;
        text-align: center;
        top: 88px;
    }
    .jcarousel2 ul li a:hover {
        background-color: #de6c00;
    }
    .jcarousel2 ul li img {
        /*max-width: 390px;*/
    }
    .jcarouseles {
        width: 100%;
    }
    .jcarouseles > div{
        position: relative;
    }
    .jcarouseles div{
        width: 100%;
    }
</style>
<!-- END Carouseles zijde code 3corner-->
                <?php } else if (($triangle['winddoek'][0] == 'on') && (
                    ($triangle['square'][0] == 'on')       ||
                    ($triangle['rectsquare'][0] == 'on'))
                    ) { ?>
<!-- Carouseles zijde code 4corner-->
<script>
var holes = 0;
var holes_c;
function holes_count() {
    var k1_holes = [0,0,0,0];
    var L;
    jQ('.zijde').each(function(){
        var id  = jQ(this).attr('id');
        var n;

        if (jQ(this).val() == 'k1-verstevigingsband-met-verchroomde-zeilogen-om-de-30-cm') {
            holes+=1;
            switch(id) {
                case 'pa_zijde-ab':
                    k1_holes[0]=1;
                    break;
                case 'pa_zijde-bc':
                    k1_holes[1]=1;
                    break;
                case 'pa_zijde-cd':
                    k1_holes[2]=1;
                    break;
                case 'pa_zijde-da':
                    k1_holes[3]=1;
                    break;
                default:
                    break;
            };
        }; 
        (jQ(this).val() == 'k2-verstevigingsband-met-verchroomde-zeilogen-op-de-hoeken') ? holes+=10   : "";
        (jQ(this).val() == 'k3-alleen-verstevigingsband')                                ? holes+=100  : "";
        (jQ(this).val() == 'k4-holle-zoom-van-2cm-diamater')                             ? holes+=1000 : "";
        
        L = k1_holes[0]*AB+k1_holes[1]*BC+k1_holes[2]*CD+k1_holes[3]*DA;
    });
    
    console.log(k1_holes);
    console.log(holes);
    switch(holes) {
        case 4000:
        case 3100:  
        case 2200:
        case 1300:
        case  400:
            holes_c = 0;
            break;
        case 3010:
        case 2110:
        case 1210:
        case  310:
            holes_c = 2;
            break;
        case 1030:
        case  130:
            holes_c = 3;
            break;
        case 2020:
        case 1120:
        case  220:
        case   40:
            holes_c = 4;
            break;
        case 3001:
        case 2101:
        case 1201:
        case  301:
            holes_c = Math.ceil(L/0.3) + 2;
            break;
        case 2011:
        case 1111:
        case  211:
            holes_c = Math.ceil(L/0.3) + 4;
            break;
        case 2002:
        case 1102:
        case  202:
            holes_c = Math.ceil(L/0.3) + 1;
            break;
        case 1021:
        case  121:
            holes_c = Math.ceil(L/0.3) + 4;
            break;
        case 1012:
        case  112:
            holes_c = Math.ceil(L/0.3) + 2;
            break;
        case 1003:
        case  103:
            holes_c = Math.ceil(L/0.3); 
            break;
        case   31:
            holes_c = Math.ceil(L/0.3)+4; 
            break;
        case   22:
            holes_c = Math.ceil(L/0.3)+4; 
            break;
        case   13:
            holes_c = Math.ceil(L/0.3)+2; 
            break;
        case    4:
            holes_c = Math.ceil(L/0.3); 
            break;
        default:
            holes_c = 'XX';
    } 
    jQ('.holes span').html(holes_c+'<span style="display:none; font-size:16px;">'+holes+'</span>');
    holes = 0;
    if (!areaoff) {
        area();
    } else {
        sides(); 
    }
}
jQuery( document ).ready(function() {
    var count = 0;
    if (jQ('.jcarouseles').text() == '') {
        jQ('.zijde').each(function(){
            var select_id = jQ(this).attr('id');
            jQ(this).parents('.variations_lines').hide();
            var label = jQuery('label[for='+select_id+']').text();
            var innercarousel = '<div id="jc'+count+'">\
                        <label>'+label+'</label>\
                        <div class="sel_name"></div>\
                        <div class="jcarousel2" sel_id="'+select_id+'">\
                        <ul>';
            var options = jQ(this).children('option');
            var w = jQuery('.jcarouseles').width();
            for(var i = 1; i < options.length; i++) {
              options[i].getAttribute('img-url');  
              innercarousel+='<li opt="'+options[i].getAttribute('value')+'"><img src="'+options[i].getAttribute('img-url')+'" width="'+w+'"><a type="button">Kies deze</a></li>';
            }
            innercarousel += '</ul>\
                        </div>\
                        <a href="#" class="jcarousel2-control-prev">&lsaquo;</a>\
                        <a href="#" class="jcarousel2-control-next">&rsaquo;</a>\
                        </div>';
            jQ('.jcarouseles').append(innercarousel);
            count++;
        })
    }
    jQ('.jcarousel2 > ul > li > a').on("click", function() {
        var opt = jQ(this).parent().attr('opt');
        console.log(opt);
        var sel = jQ(this).parents('.jcarousel2').attr('sel_id');
        var opt_text = jQ('#'+sel).children('option[value="'+opt+'"]').text();
        var img = jQ(this).siblings('img').attr('src');
        jQ(this).parents('.jcarousel2').hide().siblings('a').hide();
        jQ(this).parents('.jcarousel2').siblings('.sel_name').html(opt_text+'<br><img src="'+img+'" width="150">').show();
        jQ('#'+sel).children('option[value="'+opt+'"]').prop('selected', true);
        jQ('#'+sel).change();
        holes_count();
    });
    jQ('.jcarouseles > div .sel_name').on("click", function() {
        jQ(this).hide();
        jQ(this).siblings('.jcarousel2, a').show();
    });
    jQ('.jcarousel2').jcarousel();
    jQ('.jcarousel2-control-prev')
            .on('jcarouselcontrol:active', function() {
                jQ(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQ(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        jQ('.jcarousel2-control-next')
            .on('jcarouselcontrol:active', function() {
                jQ(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQ(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });
    
})
                        </script> 
<style>
/** Carousel **/

.jcarousel2 {
    display: block;
    position: relative;
    overflow: hidden;
}

.jcarousel2 ul {
    width: 20000em;
    position: relative;
    list-style: none;
    margin: 0;
    padding: 0;
}

.jcarousel2 li {
    float: left;
}

/** Carousel Controls **/

.jcarousel2-control-prev,
.jcarousel2-control-next {
    position: absolute;
    top: 155px;
    width: 30px;
    height: 30px;
    text-align: center;
    background: #4E443C;
    color: #fff !important;
    text-decoration: none;
    text-shadow: 0 0 1px #000;
    font: 24px/27px Arial, sans-serif;
    -webkit-border-radius: 30px;
       -moz-border-radius: 30px;
            border-radius: 30px;
    -webkit-box-shadow: 0 0 2px #999;
       -moz-box-shadow: 0 0 2px #999;
            box-shadow: 0 0 2px #999;
}

.jcarousel2-control-prev {
    left: -40px;
}

.jcarousel2-control-next {
    right: -40px;
}

.jcarousel2-control-prev:hover span,
.jcarousel2-control-next:hover span {
    display: block;
}

.jcarousel2-control-prev.inactive,
.jcarousel2-control-next.inactive {
    opacity: .5;
    cursor: default;
}

/** Carousel Pagination **/


    /*.jcarousel2, .jcarousel2-control-prev, .jcarousel2-control-next {display: none}*/
    .jcarousel2 ul li {position: relative}

    .jcarousel2 ul li a {
        background-color: #ef7d00;
        border: 2px solid #000;
        color: #fff;
        display: block;
        font-weight: bold;
        left: 50%;
        padding: 0 20px;
        position: absolute;
        text-align: center;
        top: 88px;
    }
    .jcarousel2 ul li a:hover {
        background-color: #de6c00;
    }
    .jcarousel2 ul li img {
        /*max-width: 390px;*/
    }
    .jcarouseles {
        width: 100%;
    }
    .jcarouseles > div{
        position: relative;
    }
    .jcarouseles div{
        width: 100%;
    }
</style>
<!-- END Carouseles zijde code 4corner -->
                    <div class="clear"></div>
                    <label  class='beforejcar'>Maak je keuze in randafwerking. Dit kan per zijde verschillen.</label>
                    <div class="jcarouseles"></div>
                    <label class="holes">Jouw maatwerk doek heeft <span style="font-size:16px;">XX</span> ogen.</label>
                    <?php }?>
                    <div class="clear"></div>
<?php if ($formkey != "" && $formkey != "triangle" && $formkey != "isquare" && $formkey != "lsquare") {    
    if ($gecurvd != 1) {?>
                    
                    Onderstaand zie je de bevestigingsmaterialen waarmee je je winddoek kunt bevestigen. 
                    Deze bevestigingsmaterialen zijn separaat los te bestellen in 
                    <a href="/schaduwdoeken/bevestigingsmateriaal-palen-schaduwdoeken/" target="_blank">onze webshop</a>.  
                    Houd zelf nog rekening met de benodigde spanmarge voor bevestiging. 
                    Je doek wordt dus kleiner dan de ruimte die je hebt.:<br>
                    <img src="/wp-content/uploads/2017/03/Keuze-bevestigingsmateriaal-windschermen-330.png">
    <?php }
    echo $valid_form;
} ?> 
<?php
if (isset($formkey)) {
    ?>

                <div class="price2"></div>   
<?php
}
    ?>
		<div class="single_variation_wrap">
			<?php
				/**
				 * woocommerce_before_single_variation Hook.
				 */
				do_action( 'woocommerce_before_single_variation' );

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
				do_action( 'woocommerce_after_single_variation' );
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
/*echo '<pre>';
print_r($rol);
print_r($opt); 
print_r($disc);
echo '</pre>';
*/
?>
                    <?php if ($formkey) { ?>           
                        <input type="hidden" value="" name="area" id="area-input" />
                        <input type="hidden" value="" name="width" id="width-input" />
                        <input type="hidden" value="" name="height" id="height-input" />
                        <input type="hidden" value="<?php echo $disc;?>" name="disc" id="disc" />
                        
                        <input type="hidden" value="0" name="point1" id="point1" />
                        <input type="hidden" value="0" name="point2" id="point2" />
                        <input type="hidden" value="0" name="point3" id="point3" />
                        <input type="hidden" value="0" name="myprice" id="myprice" />
                        <input type="hidden" value="<?php echo $formkey;?>" name="formtype" id="formtype" />
                        <div style="clear: both;"></div>
                    <?php } ?>
                        
                        <?php
                        if (($triangle['triangle'][0] == 'on') ||
                        ($triangle['triangle'][0] == 'on') ||	   
                        ($triangle['triangle90dr'][0] == 'on') ||
                        ($triangle['triangle90dl'][0] == 'on') ||
                        ($triangle['triangle60d'][0] == 'on'))	
                        {
                        ?>
                        <input type="button" id="submit-button-configurator" name="submit-button-configurator" value="Ga naar de stap 2 voor de maatvoering en keuze bevestigingsmateriaal<? //=get_the_title( $product->id);?>"> 
                        <div style="clear: both;"></div>
                        <?php } ?>
                        <?php
                        if (($triangle['square'][0] == 'on') ||
                        ($triangle['isquare'][0] == 'on') ||
                        ($triangle['lsquare'][0] == 'on'))	
                        {
                        ?>
                        <input type="button" id="submit-button-configurator" name="submit-button-configurator" value="Ga naar de stap 2 voor de maatvoering en keuze bevestigingsmateriaal<? //=get_the_title( $product->id);?>"> 
                        <div style="clear: both;"></div>
                        <?php } ?>
	<?php endif; ?>
                        
	<?php do_action( 'woocommerce_after_variations_form' ); ?>
                <?php 
                    $triangle = get_post_meta($product->id); 
                    

                    if ($triangle['triangle90dr'][0] == 'on') {
                        include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area90r.php';     
                    }
                ?>  
                
                <?php 
                    //$triangle = get_post_meta($post->ID);
                    if ($triangle['triangle90dl'][0] == 'on') {
                        include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area90l.php';     
                    }
                ?> 
                
                <?php 
                    //$triangle = get_post_meta($post->ID);
                    if ($triangle['triangle60d'][0] == 'on') {
                        include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area60d.php';     
                    }
                ?>
                <?php 
                    //$triangle = get_post_meta($post->ID);
                    if ($triangle['square'][0] == 'on') {
                        include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area4d90.php';     
                    }
                ?>
                <?php 
                    //$triangle = get_post_meta($post->ID);
                    if ($triangle['rectsquare'][0] == 'on') {
                        include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area4d90r.php';     
                    }
                ?>
                <?php 
                    //$triangle = get_post_meta($post->ID);
                    if ($triangle['isquare'][0] == 'on') {
                        //include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area4.php';     
                    }
                ?>
</form>

<script>
    var disc = '<?php echo (100-$disc)/100; ?>' ;
</script>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
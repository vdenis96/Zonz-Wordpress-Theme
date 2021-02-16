<script>
    jQuery(document).on('change', '.variations_lines select', function() {
        var value = ".product-variation";
                        
        jQuery('.variations_lines').each(function(){
            value += "-"+jQuery( "select option:selected", this ).val();
        });
        
        var owl = jQuery("#product-images-carousel").data('owlCarousel');
        var i = 0;
        var found;
        jQuery('#product-images-carousel .owl-wrapper .owl-item').each(function(){
            if(jQuery(value, this).length){
                owl.goTo(i);
                found = 1;
            }else{
                found = 0;
            }
            i++;
        });
        if(found == 0){
            owl.goTo(0);
        }
                
    });
</script>
<style>
    .product-variation-images .product-variation-image {
        display: none;
    }
</style>

<?php 
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

    do_action( 'woocommerce_before_add_to_cart_button' ); 
                
    $gecurvd = $_SESSION["gecurvd"];
    $_SESSION["gecurvd"] = 0;

    echo $before_breedte;

    if (($triangle['triangle90dr'][0] == 'on') || ($triangle['triangle90dl'][0] == 'on')) { 
        set_query_var( 'triangle', $triangle );
        set_query_var( 'gecurvd', $gecurvd );
        get_template_part('variable-templates/single/figures/triangle90dl');
    }

    if ($triangle['triangle60d'][0] == 'on') { 
        set_query_var( 'gecurvd', $gecurvd );
        get_template_part('variable-templates/single/figures/triangle60d');
    }

    if ($triangle['triangle'][0] == 'on') { 
        get_template_part('variable-templates/single/figures/triangle');
    }

    if ($triangle['square'][0] == 'on') { 
        set_query_var( 'gecurvd', $gecurvd );
        get_template_part('variable-templates/single/figures/square');
    }
 
    if ($triangle['rectsquare'][0] == 'on') { 
        set_query_var( 'gecurvd', $gecurvd );
        get_template_part('variable-templates/single/figures/rectsquare');
    }
     
    if ($triangle['isquare'][0] == 'on') { 
        get_template_part('variable-templates/single/figures/isquare');
    }

    if ($triangle['lsquare'][0] == 'on') { 
      get_template_part('variable-templates/single/figures/lsquare');
    }
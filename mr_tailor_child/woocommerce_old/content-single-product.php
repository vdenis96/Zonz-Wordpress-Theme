<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
    global $post, $product, $mr_tailor_theme_options;

    //woocommerce_before_single_product
	//nothing changed
	
	//woocommerce_before_single_product_summary
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
	
	add_action( 'woocommerce_before_single_product_summary_sale_flash', 'woocommerce_show_product_sale_flash', 10 );
	add_action( 'woocommerce_before_single_product_summary_product_images', 'woocommerce_show_product_images', 20 );
	
	//woocommerce_single_product_summary
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	
	add_action( 'woocommerce_single_product_summary_single_title', 'woocommerce_template_single_title', 5 );
	add_action( 'woocommerce_single_product_summary_single_rating', 'woocommerce_template_single_rating', 10 );
	add_action( 'woocommerce_single_product_summary_single_price', 'woocommerce_template_single_price', 10 );
	add_action( 'woocommerce_single_product_summary_single_excerpt', 'woocommerce_template_single_excerpt', 20 );
	add_action( 'woocommerce_single_product_summary_single_add_to_cart', 'woocommerce_template_single_add_to_cart', 30 );
	add_action( 'woocommerce_single_product_summary_single_meta', 'woocommerce_template_single_meta', 40 );
	add_action( 'woocommerce_single_product_summary_single_sharing', 'woocommerce_template_single_sharing', 50 );
	
	//woocommerce_after_single_product_summary
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	
	add_action( 'woocommerce_after_single_product_summary_data_tabs', 'woocommerce_output_product_data_tabs', 10 );

	//woocommerce_after_single_product
	//nothing changed

	//custom actions
	add_action( 'woocommerce_before_main_content_breadcrumb', 'woocommerce_breadcrumb', 20, 0 );
	add_action( 'woocommerce_product_summary_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
	
	$product_page_has_sidebar = false;
	
	if ( (isset($mr_tailor_theme_options['products_layout'])) && ($mr_tailor_theme_options['products_layout'] == "0" ) ) {
		
		$product_page_has_sidebar = false;
	
	} else {
	
		$product_page_has_sidebar = true;
	
	}

//echo '<pre>';
//print_r($product); 
//print_r($post); 
$terms = get_the_terms( $post->ID, 'product_cat' );
$gecurvd=0;
$winddoek = 1;
//echo '<pre>';
//    print_r($terms); 
//    echo '</pre>';
foreach ($terms as $term) {
    if ($term->slug == 'schaduwdoeken-gecurvd') {
        $gecurvd=1;
    }
    if ($term->term_id == 28) {
        $winddoek = 0;
    }
}
//if ($winddoek == 0) {
//    echo '<style>.variations_lines{display:block !important;}</style>';
//}
session_start();

//echo '<pre>';
//print_r ($product); 
$_SESSION["gecurvd"]  = $gecurvd;
$_SESSION["winddoek"] = $winddoek;
//print_r($gecurvd); 
//echo '</pre>';
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="row">
        <div class="large-12 columns">
    
            <?php do_action( 'woocommerce_before_single_product' ); ?>
    
            <?php do_action( 'woocommerce_before_main_content_breadcrumb' ); ?>
            
            <div class="product_summary_top">
            <?php
                do_action( 'woocommerce_single_product_summary_single_title' );
                do_action( 'woocommerce_single_product_summary_single_rating' );
                
                if ( post_password_required() ) {
                    echo get_the_password_form();
                    return;
                }
            ?>
            </div>
            <?php 
                    $triangle = get_post_meta($post->ID);
                    //echo '<pre>';
                    //print_r($triangle); 
                    //echo '</pre>';
                    if (($triangle['lsquare'][0] == 'on')||
                        ($triangle['triangle60d'][0] == 'on')||
                        ($triangle['square'][0] == 'on')||
                        ($triangle['isquare'][0] == 'on')||
                        ($triangle['rectsquare'][0] == 'on')||
                        ($triangle['triangle'][0] == 'on')||
                        ($triangle['triangle90dl'][0] == 'on')||
                        ($triangle['triangle90dr'][0] == 'on')||
                        ($triangle['winddoek'][0] == 'on')){
                ?>
            <div class='top-ban' style="margin: -20px auto 20px;">
                <img src="/wp-content/uploads/2017/01/Maatwerk-Unieke-Punten-UPS-ZONZ.png">
            </div>
            <?php } ?>
        </div><!-- .columns -->        
    </div><!-- .row -->

	
	<?php if ( $product_page_has_sidebar ) : ?>
	
	<div class="single-product with-sidebar">
	
		<div class="row">
						   
			<div class="large-3 columns show-for-large-up">
				<div class="wpb_widgetised_column">
					<?php dynamic_sidebar('catalog-widget-area'); ?>
				</div>
			</div>
			
			<div class="large-9 columns">
				
				<div class="row">
					
					<div class="large-7 large-push-0 medium-8 medium-push-2 columns">
			
						<?php				
							if ( (isset($mr_tailor_theme_options['catalog_mode'])) && ($mr_tailor_theme_options['catalog_mode'] == 0) ) {
								do_action( 'woocommerce_before_single_product_summary_sale_flash' );
							}
							do_action( 'woocommerce_before_single_product_summary_product_images' );
							do_action( 'woocommerce_before_single_product_summary' );
						?>
						
						<?php            
						if (isset($mr_tailor_theme_options['out_of_stock_text'])) {
							$out_of_stock_text = __($mr_tailor_theme_options['out_of_stock_text'], 'woocommerce');
						} else {
							$out_of_stock_text = __('Out of stock', 'woocommerce');
						}
						?>
						
						<?php if ( (isset($mr_tailor_theme_options['catalog_mode'])) && ($mr_tailor_theme_options['catalog_mode'] == 0) ) : ?>
							<?php if ( !$product->is_in_stock() ) : ?>            
                                <div class="out_of_stock_badge_single <?php if (!$product->is_on_sale()) : ?>first_position<?php endif; ?>"><?php echo $out_of_stock_text; ?></div>            
                            <?php endif; ?>
                        <?php endif; ?>
						
						<div class="product_summary_thumbnails_wrapper with-sidebar">
							<div><?php do_action( 'woocommerce_product_summary_thumbnails' ); ?></div>
						</div><!-- .product_summary_thumbnails_wrapper-->
						
					</div><!-- .columns -->
					
					<div class="large-5 large-push-0 columns">
					
						<div class="product_infos">
							
							<?php
								do_action( 'woocommerce_single_product_summary_single_price' );
								do_action( 'woocommerce_single_product_summary_single_excerpt' );
								if ( (isset($mr_tailor_theme_options['catalog_mode'])) && ($mr_tailor_theme_options['catalog_mode'] == 0) ) {
									do_action( 'woocommerce_single_product_summary_single_add_to_cart' );
								}
								do_action( 'woocommerce_single_product_summary' );
							?>
							
						</div>
			
					</div><!-- .columns -->
				
				</div><!--.row-->
				
				<div class="row">
					<div class="large-12 large-uncentered columns">
						<?php
							do_action( 'woocommerce_after_single_product_summary_data_tabs' );
							
							do_action( 'woocommerce_single_product_summary_single_meta' );
							do_action( 'woocommerce_single_product_summary_single_sharing' );
							
							do_action( 'woocommerce_after_single_product_summary' );
						?>
				
						<div class="product_navigation">
							<?php mr_tailor_product_nav( 'nav-below' ); ?>
						</div>
				
					</div><!-- .columns -->
				</div><!-- .row -->
			
			</div><!--.large-9-->
		
		</div><!--.row-->
	
	</div><!--.single-product .with-sidebar-->
		
	<?php else : ?>
					  
	<div class="single-product without-sidebar">				  
                <div class="row" data-sticky_parent="">				  
						 
			<div data-sticky_column="" class="large-1 columns product_summary_thumbnails_wrapper without_sidebar">
				<div><?php do_action( 'woocommerce_product_summary_thumbnails' ); ?>&nbsp;</div>
			</div><!-- .columns -->
			
			<div data-sticky_column="" class="large-5 large-push-0 medium-8 medium-push-2 columns sticky">
	
				<?php				
					if ( (isset($mr_tailor_theme_options['catalog_mode'])) && ($mr_tailor_theme_options['catalog_mode'] == 0) ) {
						do_action( 'woocommerce_before_single_product_summary_sale_flash' );
					}
					do_action( 'woocommerce_before_single_product_summary_product_images' );
					do_action( 'woocommerce_before_single_product_summary' );
				?>
				
				<?php            
				if (isset($mr_tailor_theme_options['out_of_stock_text'])) {
					$out_of_stock_text = __($mr_tailor_theme_options['out_of_stock_text'], 'woocommerce');
				} else {
					$out_of_stock_text = __('Out of stock', 'woocommerce');
				}
				?>
				
				<?php if ( (isset($mr_tailor_theme_options['catalog_mode'])) && ($mr_tailor_theme_options['catalog_mode'] == 0) ) : ?>
					<?php if ( !$product->is_in_stock() ) : ?>            
                        <div class="out_of_stock_badge_single <?php if (!$product->is_on_sale()) : ?>first_position<?php endif; ?>"><?php echo $out_of_stock_text; ?></div>            
                    <?php endif; ?>
                <?php endif; ?>
				
				&nbsp;
				<?php do_action( 'woocommerce_single_product_summary_single_excerpt' ); ?>
			</div><!-- .columns -->
			
			<?php

			if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) )
				$viewed_products = array();
			else
				$viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );

			if ( ! in_array( $post->ID, $viewed_products ) ) {
				$viewed_products[] = $post->ID;
			}

			if ( sizeof( $viewed_products ) > 4 ) {
				array_shift( $viewed_products );
			}
			
			?>
			
			<?php if ( empty( $viewed_products ) ) : ?>
				<div class="large-6 large-push-0 columns" data-sticky_column="">
			<?php  else : ?>
			
				<?php if ( (!isset($mr_tailor_theme_options['recently_viewed_products'])) || ($mr_tailor_theme_options['recently_viewed_products'] == "1" ) ) : ?>
					<div class="large-5 large-push-0 columns" data-sticky_column="">
				<?php  else : ?>
					<div class="large-6 large-push-0 columns" data-sticky_column="">
				<?php endif; ?>
			
			<?php endif; ?>
			
				<div class="product_infos">
					
					<?php
						do_action( 'woocommerce_single_product_summary_single_price' );
						//do_action( 'woocommerce_single_product_summary_single_excerpt' );
						if ( (isset($mr_tailor_theme_options['catalog_mode'])) && ($mr_tailor_theme_options['catalog_mode'] == 0) ) {
							do_action( 'woocommerce_single_product_summary_single_add_to_cart' );
						}						
						do_action( 'woocommerce_single_product_summary' );
					?>
				
				</div>
	
			</div><!-- .columns -->
            
		
			<?php if ( !empty( $viewed_products ) ) : ?>
			<?php if ( (!isset($mr_tailor_theme_options['recently_viewed_products'])) || ($mr_tailor_theme_options['recently_viewed_products'] == "1" ) ) : ?>
			<div class="large-1 columns recently_viewed_in_single_wrapper">
			
				<div class="recently_viewed_in_single">
					
					<?php include_once('single-product/recently-viewed.php'); ?>
					
				</div>
			
			</div><!-- .columns -->
			<?php endif; ?>
			<?php endif; ?>
				   
		</div><!-- .row -->
                <?php 
                    $triangle = get_post_meta($post->ID);
                    //print_r($triangle['triangle']);
                    if (($triangle['winddoek'][0] == 'on') && 
                        ($triangle['triangle60d'][0] == 'on' || $triangle['square'][0] == 'on' || 
                         $triangle['triangle90dr'][0] == 'on' || $triangle['triangle90dl'][0] == 'on'||
                         $triangle['rectsquare'][0] == 'on' )){
                ?>
                <div class="row" style="display:none;">
                    <div class="large-10 columns large-centered">
                        <?php
                        //$string = $product->get_image(1);
                        //echo $string;
                        ?>
                    </div>
                </div>
                <div class="row" style="display:none;">
                    <div class="large-10 columns large-centered">
                    Onderstaand zie je de bevestigingsmaterialen waarmee je je winddoek kunt bevestigen. 
                    Deze bevestigingsmaterialen zijn separaat los te bestellen in 
                    <a href="/schaduwdoeken/bevestigingsmateriaal-palen-schaduwdoeken/" target="_blank">onze webshop</a>.  
                    Houd zelf nog rekening met de benodigde spanmarge voor bevestiging. 
                    Je doek wordt dus kleiner dan de ruimte die je hebt.:<br>
                    <img src="/wp-content/uploads/2019/06/Zijden-langer-dan-200-cm_01-1.png">
                    </div>
                </div>
                <?php 
                    }
                    if (($triangle['triangle60d'][0] == 'on')||
                        ($triangle['square'][0] == 'on')||
                        ($triangle['isquare'][0] == 'on')||
                        ($triangle['rectsquare'][0] == 'on')||
                        ($triangle['triangle'][0] == 'on')||
                        ($triangle['triangle90dl'][0] == 'on')||
                        ($triangle['triangle90dr'][0] == 'on')||
                        ($triangle['winddoek'][0] == 'on')) {
                    ?>
<!-- Carousel code -->
<style>
.jcarousel-wrapper {
    margin: 20px auto;
    position: relative;
    border: 10px solid #fff;
    width: 600px;
    height: 400px;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 0 2px #999;
       -moz-box-shadow: 0 0 2px #999;
            box-shadow: 0 0 2px #999;
}


.jcarousel-wrapper .photo-credits {
    position: absolute;
    right: 15px;
    bottom: 0;
    font-size: 13px;
    color: #fff;
    text-shadow: 0 0 1px rgba(0, 0, 0, 0.85);
    opacity: .66;
}

.jcarousel-wrapper .photo-credits a {
    color: #fff;
}

/** Carousel **/

.jcarousel {
    position: relative;
    overflow: hidden;
}

.jcarousel ul {
    width: 20000em;
    position: relative;
    list-style: none;
    margin: 0;
    padding: 0;
}

.jcarousel li {
    float: left;
}

/** Carousel Controls **/

.jcarousel-control-prev,
.jcarousel-control-next {
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

.jcarousel-control-prev {
    left: -40px;
}

.jcarousel-control-next {
    right: -40px;
}

.jcarousel-control-prev:hover span,
.jcarousel-control-next:hover span {
    display: block;
}

.jcarousel-control-prev.inactive,
.jcarousel-control-next.inactive {
    opacity: .5;
    cursor: default;
}

/** Carousel Pagination **/

.jcarousel-pagination {
    position: absolute;
    bottom: 0;
    left: 15px;
}

.jcarousel-pagination a {
    text-decoration: none;
    display: inline-block;
    
    font-size: 11px;
    line-height: 14px;
    min-width: 14px;
    
    background: #fff;
    color: #4E443C;
    border-radius: 14px;
    padding: 3px;
    text-align: center;
    
    margin-right: 2px;
    
    opacity: .75;
}

.jcarousel-pagination a.active {
    background: #4E443C;
    color: #fff;
    opacity: 1;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.75);
}
.variations_lines .value {position: relative}

    .doeksoort option {display: none}
    .jcarousel, .jcarousel-control-prev, .jcarousel-control-next {display: none}
    .jcarousel ul li {position: relative}

    #pa_bepaal-je-afmeting + .jcarousel ul li a {top: 37% !important;}
    .jcarousel ul li a {
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
    .jcarousel ul li a:hover {
        background-color: #de6c00;
    }
    .jcarousel ul li img {
        /*max-width: 390px;*/
    }
    .opened {display: block !important}
    .opened2 {display: block !important}
</style>
<script src="/wp-content/themes/mrtailor-child/js/jquery.jcarousel.min.js"></script>
<script src="/wp-content/themes/mrtailor-child/js/jquery-ui.min.js"></script>
<script>
jQuery( document ).ready(function() {
jQuery(document).on('change', '.doeksoort', function () {
            rax();
        });
        function popUp() {
            var i = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
            var maxw = jQuery(window).width()*0.8;
            maxw = (maxw>620) ? 620 : maxw;
            maxw = parseInt(maxw);
            if (i==1) {
                jQuery( "#dialog1" ).dialog({
                    width: maxw
                })
            } else if (i==2) {
                jQuery( "#dialog2" ).dialog({
                    width: maxw
                })  
            }


        }
        var open = false;
        var open2 = false;
        //var sopen = jQ('.jcarousel');
        jQuery(document).on('change', '#pa_waterproof, #pa_waterbestendigheid-wind', function () {
            if (jQuery('#pa_waterproof').val() != '') {
                jQ('.doeksoort').click();
                if (jQuery(this).val() == 'yes') {
                    //popUp(1);
                }
            }
        });
        jQuery('.doeksoort, #pa_bepaal-je-afmeting').each(function () {
            jQ(this).after('<div class="jcarousel"><ul></ul></div><a href="#" class="jcarousel-control-prev">&lsaquo;</a><a href="#" class="jcarousel-control-next">&rsaquo;</a>');
        })        
        jQuery(document).on('click', '.doeksoort, #pa_bepaal-je-afmeting', function () {

            var options;
            options = jQ(this).find('option');
      
            var inhtml = '';
            var w = jQuery('.variations_lines').width();
            for(var i = 1; i < options.length; i++) {
              options[i].getAttribute('img-url');  
              inhtml+='<li opt="'+options[i].getAttribute('value')+'"><img src='+options[i].getAttribute('img-url')+' width="'+w+'"><a type="button">Kies deze</a></li>';
            }
            jQ(this).parent().find('.jcarousel ul').html(inhtml);
            if (jQ(this).attr('id') != 'pa_bepaal-je-afmeting') {
                open = !open;
                console.log(isOpen());
            } else {
        	open2 = !open2;
                console.log(isOpen2());
            }

            
            /*
            jQ( ".doeksoort" ).selectmenu({
                open: function( event, ui ) {alert('opened')}
            });
            jQ( ".doeksoort" ).on( "selectmenuopen", function( event, ui ) {alert('opened2')} );
            */
               
   // just a function to print out message
   function isOpen(){
       if(open) {
           jQ('.doeksoort ~ .jcarousel').addClass('opened');
           jQ('.doeksoort ~ .jcarousel-control-prev').addClass('opened');
           jQ('.doeksoort ~ .jcarousel-control-next').addClass('opened');
           return "menu is open";
       } else {
           //jQ('.jcarousel').removeClass('opened');
           return "menu is closed";
      }
   }
   function isOpen2(){
       if(open2) {
           jQ('#pa_bepaal-je-afmeting ~ .jcarousel').addClass('opened2');
           jQ('#pa_bepaal-je-afmeting ~ .jcarousel-control-prev').addClass('opened2');
           jQ('#pa_bepaal-je-afmeting ~ .jcarousel-control-next').addClass('opened2');
           return "menu is open";
       } else {
           //jQ('.jcarousel').removeClass('opened');
           return "menu is closed";
      }
   }
   // on each click toggle the "open" variable
   /*jQ('.doeksoort').on("click", function() {
         open = !open;
         console.log(isOpen());
   });*/
   // on each blur toggle the "open" variable
   // fire only if menu is already in "open" state
   jQ('.doeksoort').on("blur", function() {
         if(open){
            open = !open;
            console.log(isOpen());
         }
   });
   // on ESC key toggle the "open" variable only if menu is in "open" state
   jQ(document).keyup(function(e) {
       if (e.keyCode == 27) { 
         if(open){
            open = !open;
            console.log(isOpen());
         }
       }
   });
   
            jQ('.doeksoort + .jcarousel > ul > li > a, #pa_bepaal-je-afmeting + .jcarousel > ul > li > a').on("click", function() {
                var opt = jQ(this).parent().attr('opt');
                var sel = jQ(this).parents('.value').children('select');
                console.log(sel.attr('id'))
                console.log(opt)
                sel.children('option[value="'+opt+'"]').prop('selected', true);
                //jQ('.doeksoort option[value="'+opt+'"]').prop('selected', true);
                sel.change();
                // jQ('.jcarousel').removeClass('opened'); 
                
                
                if (sel.attr('id') == 'pa_bepaal-je-afmeting') {
                    open2 = false;
                    jQ('.opened2').removeClass('opened2');
                } else{
                    jQ('.opened').removeClass('opened');
                }
                
                if (opt == 'polyester-waterdicht-atex-935' || 
                    opt == 'atex935-e-3250' ||
                    opt == 'atex-935-polyester'   ||
                    opt == 'polyester-waterdicht-atex-935-4'                       
                    ) {
                    popUp(2);
                    console.log('popoup2')
                }
            });
            
            jQ('.jcarousel').jcarousel();
            jQ('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                jQ(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQ(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        jQ('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                jQ(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQ(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });

        jQ('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                jQ(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                jQ(this).removeClass('active');
            })
            .jcarouselPagination();
                // Configuration goes here
        });
    /*One row TITLE*/
    var title_h = jQ('.product_summary_top h1.product_title').height();
    var title_line_h = jQ('.product_summary_top h1.product_title').css('line-height');
    while ((title_h/parseInt(title_line_h)) > 1) {
        jQ('.product_summary_top h1.product_title').css('font-size', parseInt(jQ('.product_summary_top h1.product_title').css('font-size'))-3+"px");
        title_h = jQ('.product_summary_top h1.product_title').height();
    };
/* END One row TITLE*/    
});    
</script>
<!-- END Carousel code -->
                <?php
                }    
                $triangle = get_post_meta($post->ID);
                    //print_r($triangle['triangle']);
                    if ($triangle['triangle'][0] == 'on') {
                ?>
        <div class="row">
			<div class="large-10 large-centered columns">        
                <?php 
                        include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area.php';
                ?>
        	</div>
        </div>
                <?php 
                    }
                ?>
                <?php 
                    $triangle = get_post_meta($post->ID);
                    if ($triangle['isquare'][0] == 'on') {
                ?>
        <div class="row">
			<div class="large-10 large-centered columns">        
                <?php 
                        include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area4.php';
                ?>
        	</div>
        </div>
                <?php 
                    }
                ?>
                <?php 
                    $triangle = get_post_meta($post->ID);
                    if ($triangle['lsquare'][0] == 'on') {
                ?>
        <div class="row">
			<div class="large-10 large-centered columns">        
                <?php 
                        include $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/mrtailor-child/area4l.php';
                ?>
        	</div>
        </div>
                <?php 
                    }
                ?>
		<div class="row">
			<div class="large-12 large-uncentered columns" >
				<?php
					do_action( 'woocommerce_after_single_product_summary_data_tabs' );
					
					do_action( 'woocommerce_single_product_summary_single_meta' );
					do_action( 'woocommerce_single_product_summary_single_sharing' );
					
					do_action( 'woocommerce_after_single_product_summary' );
				?>
				
				<div class="product_navigation">
					<?php mr_tailor_product_nav( 'nav-below' ); ?>
				</div>
				
			</div><!-- .columns -->
		</div><!-- .row -->
    
	</div><!--.single-product .without-sidebar-->
	
	<?php endif; ?>
	
    <meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
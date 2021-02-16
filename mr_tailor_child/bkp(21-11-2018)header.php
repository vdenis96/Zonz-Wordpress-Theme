<?php
	global $mr_tailor_theme_options;
	global $woocommerce;
    global $wp_version;
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    
    <!-- ******************************************************************** -->
    <!-- * Title ************************************************************ -->
    <!-- ******************************************************************** -->
    
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    
    <!-- ******************************************************************** -->
    <!-- * Custom Favicon *************************************************** -->
    <!-- ******************************************************************** -->
    
    <?php
	if ( (isset($mr_tailor_theme_options['favicon']['url'])) && (trim($mr_tailor_theme_options['favicon']['url']) != "" ) ) {
        
        if (is_ssl()) {
            $favicon_image_img = str_replace("http://", "https://", $mr_tailor_theme_options['favicon']['url']);		
        } else {
            $favicon_image_img = $mr_tailor_theme_options['favicon']['url'];
        }
	?>
    
    <!-- ******************************************************************** -->
    <!-- * Favicon ********************************************************** -->
    <!-- ******************************************************************** -->
    
    <link rel="shortcut icon" href="<?php echo $favicon_image_img; ?>" type="image/x-icon" />
        
    <?php } ?>
    
    <!-- ******************************************************************** -->
    <!-- * Custom Header JavaScript Code ************************************ -->
    <!-- ******************************************************************** -->
    
    <?php if ( (isset($mr_tailor_theme_options['header_js'])) && ($mr_tailor_theme_options['header_js'] != "") ) : ?>
		<script type="text/javascript">
			<?php echo $mr_tailor_theme_options['header_js']; ?>
		</script>
    <?php endif; ?>
    
    <!-- ******************************************************************** -->
    <!-- * WordPress wp_head() ********************************************** -->
    <!-- ******************************************************************** -->
    
<?php wp_head(); ?>
              <link rel='stylesheet'  href='/wp-content/themes/mrtailor/css/custom0.css?time=<?=time()?>' type='text/css' media='all' />    
<!--<style>#sendcloudshipping_service_point_select{display:none!important}</style>    -->
<script>
jQuery(document).ready(function(){
    
   jQuery(document).on('click','.search-form .getbowtied-icon-search',function(){
       jQuery('.site-header-sticky').removeClass('site-search-open');
   });
    
   if(jQuery('#shipping_method_0_service_point_shipping_method').length > 0){
    
        setInterval(function(){
            
            if(jQuery('#shipping_method_0_service_point_shipping_method').prop('checked')){
                jQuery('#sendcloudshipping_service_point_select').attr('style','display:block!important');
                  
            }
            else{
                jQuery('#sendcloudshipping_service_point_select').attr('style','display:none!important');
            }
        },500); 
   } 
   
   
   jQuery(document).on('click','#shipping_method_0_service_point_shipping_method', function(){
       jQuery('.styleDIV').html('<style>#sendcloudshipping_service_point_select{display:block!important}</style>');
  
   });
   
   jQuery(document).on('click','#shipping_method_0_free_shipping', function(){
       
        jQuery('.styleDIV').html('<style>#sendcloudshipping_service_point_select{display:none!important}</style>');
   });
   
   
 
  
});
</script>  

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-5056019-1', 'auto');
  ga('send', 'pageview');
  ga('require','ec','ec.js');
</script -->


<style>
    .woocommerce-cart  .woocommerce-message{
   display: none!important;
}

.woocommerce-cart #site-footer .row .columns, .woocommerce-checkout #site-footer .row .columns{
   display: none!important; 
}

.woocommerce-cart #site-footer .site-footer-copyright-area .row .columns, .woocommerce-checkout #site-footer .site-footer-copyright-area .row .columns{
     display: block!important;
}
</style>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(document).on('click', '.select2.select2-container', function(){
            var sco = jQuery('body > .select2-container.select2-container--default.select2-container--open');
            if(sco.length > 0){
                var dfl = sco.offset().left
                sco.css('left',dfl+15);
            }
        });
    });
</script>
</head>

<body <?php body_class(); ?>>
    <div class="styleDIV">
        <style>#sendcloudshipping_service_point_select{display:none!important}</style>
    </div>
    
        
    <?php if ( (isset($mr_tailor_theme_options['sticky_header'])) && (trim($mr_tailor_theme_options['sticky_header']) == "1" ) ) : ?>
    
	<!-- ******************************************************************** -->
    <!-- * Sticky Header **************************************************** -->
    <!-- ******************************************************************** -->
    <?php 
    $pid =  get_the_ID();
    $post_type = get_post_type( $pid );?>
	<div class="site-header-sticky ">
        <div class="row">		
		<div class="large-12 columns">
		    <div class="site-header-sticky-inner">
                    
                <?php
                
                if ( (isset($mr_tailor_theme_options['site_logo']['url'])) && (trim($mr_tailor_theme_options['site_logo']['url']) != "" ) ) {
                    if (is_ssl()) {
                        $sticky_header_logo = str_replace("http://", "https://", $mr_tailor_theme_options['site_logo']['url']);		
                    } else {
                        $sticky_header_logo = $mr_tailor_theme_options['site_logo']['url'];
                    }
                }

                if ( (isset($mr_tailor_theme_options['sticky_header_logo']['url'])) && (trim($mr_tailor_theme_options['sticky_header_logo']['url']) != "" ) ) {
                    if (is_ssl()) {
                        $sticky_header_logo = str_replace("http://", "https://", $mr_tailor_theme_options['sticky_header_logo']['url']);		
                    } else {
                        $sticky_header_logo = $mr_tailor_theme_options['sticky_header_logo']['url'];
                    }
                }

                ?>

                <div class="site-branding">

                    <?php if ( (isset($mr_tailor_theme_options['site_logo']['url'])) && (trim($mr_tailor_theme_options['site_logo']['url']) != "" ) ) : ?>

                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="site-logo" src="<?php echo $sticky_header_logo; ?>" title="<?php bloginfo( 'description' ); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>

                    <?php else : ?>

                        <div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>

                <?php endif; ?>

                </div><!-- .site-branding -->
                
                <div id="site-menu">
                    
                    <nav id="site-navigation" class="main-navigation" role="navigation">                    
                        <?php 
                            $walker = new rc_scm_walker;
                            wp_nav_menu(array(
                                'theme_location'  => 'main-navigation',
                                'fallback_cb'     => false,
                                'container'       => false,
                                'items_wrap'      => '<ul id="%1$s">%3$s</ul>',
                                'walker' 		  => $walker
                            ));
                        ?>           
                    </nav><!-- #site-navigation -->                  
                    
                    <div class="site-tools">
                        <ul>
                            
                            <li class="mobile-menu-button"><a><i class="getbowtied-icon-menu"></i></a></li>
                            
                            <?php if (class_exists('YITH_WCWL')) : ?>
                            <?php if ( (isset($mr_tailor_theme_options['main_header_wishlist'])) && (trim($mr_tailor_theme_options['main_header_wishlist']) == "1" ) ) : ?>
                            <li class="wishlist-button">
                                <a href="<?php echo esc_url($yith_wcwl->get_wishlist_url()); ?>">
                                    <?php if ( (isset($mr_tailor_theme_options['main_header_wishlist_icon']['url'])) && ($mr_tailor_theme_options['main_header_wishlist_icon']['url'] != "") ) : ?>
                                    <img src="<?php echo esc_url($mr_tailor_theme_options['main_header_wishlist_icon']['url']); ?>">
                                    <?php else : ?>
                                    <i class="getbowtied-icon-heart"></i>
                                    <?php endif; ?>
                                    <span class="wishlist_items_number"><?php echo yith_wcwl_count_products(); ?></span>
                                </a>
                            </li>							
							<?php endif; ?>
                            <?php endif; ?>
                            
                            
                            
                            <?php if (class_exists('WooCommerce')) : ?>
                            <?php if ( (isset($mr_tailor_theme_options['main_header_shopping_bag'])) && (trim($mr_tailor_theme_options['main_header_shopping_bag']) == "1" ) ) : ?>
                            <?php if ( (isset($mr_tailor_theme_options['catalog_mode'])) && ($mr_tailor_theme_options['catalog_mode'] == 1) ) : ?>
                            <?php else : ?>
                            <li class="shopping-bag-button" class="right-off-canvas-toggle">
                                <a>
                                    <?php if ( (isset($mr_tailor_theme_options['main_header_shopping_bag_icon']['url'])) && ($mr_tailor_theme_options['main_header_shopping_bag_icon']['url'] != "") ) : ?>
                                    <img src="<?php echo esc_url($mr_tailor_theme_options['main_header_shopping_bag_icon']['url']); ?>">
                                    <?php else : ?>
                                    <i class="getbowtied-icon-shop"></i>
                                    <?php endif; ?>
                                    <span class="shopping_bag_items_number"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
                                </a>
                            </li>
							<?php endif; ?>
                            <?php endif; ?>
                            <?php endif; ?>

                            <?php if ( (isset($mr_tailor_theme_options['main_header_search_bar'])) && (trim($mr_tailor_theme_options['main_header_search_bar']) == "1" ) ) : ?>
                            <li class="search-button">
                                <a>
                                    <?php if ( (isset($mr_tailor_theme_options['main_header_search_bar_icon']['url'])) && ($mr_tailor_theme_options['main_header_search_bar_icon']['url'] != "") ) : ?>
                                    <img class="getbowtied-icon-search" src="<?php echo esc_url($mr_tailor_theme_options['main_header_search_bar_icon']['url']); ?>">
                                    <?php else : ?>
                                    <i class="getbowtied-icon-search"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                        </ul>	
                    </div>
                    <div class="site-search">
                        <?php dynamic_sidebar( 'search_sidebar' ); ?>
                        <style>.search-but-added{display:block!important}</style>
                    </div>
                
                </div><!-- #site-menu -->
                
                <div class="clearfix"></div>
                <div class="name-price"></div>
			</div><!--.site-header-sticky-inner-->	
		</div><!-- .large-12-->
		</div><!--.row--> 
    </div><!-- .site-header-sticky -->
     
    <?php endif; ?>

	<div id="st-container" class="st-container">

        <div class="st-pusher">
            
            <div class="st-pusher-after"></div>   
                
                <div class="st-content">
                    
                    <?php

					$header_transparency_class = "";
					$transparency_scheme = "";
					
					if ( (isset($mr_tailor_theme_options['main_header_background_transparency'])) && ($mr_tailor_theme_options['main_header_background_transparency'] == "1" ) ) {
						$header_transparency_class = "transparent_header";
					} else {
                        $header_transparency_class = "normal_header";
                    }
					
					if ( (isset($mr_tailor_theme_options['main_header_transparency_scheme'])) ) {
						$transparency_scheme = $mr_tailor_theme_options['main_header_transparency_scheme'];
					}
					
					$page_id = "";
					if ( is_single() || is_page() ) {
						$page_id = get_the_ID();
					} else if ( is_home() ) {
						$page_id = get_option('page_for_posts');		
					}
					
					if ( (get_post_meta($page_id, 'page_header_transparency', true)) && (get_post_meta($page_id, 'page_header_transparency', true) != "inherit") ) {
						$header_transparency_class = "transparent_header";
						$transparency_scheme = get_post_meta( $page_id, 'page_header_transparency', true );
					} else {
                        $header_transparency_class = "normal_header";
                        $transparency_scheme = "";
                    }
					
					if ( (get_post_meta($page_id, 'page_header_transparency', true)) && (get_post_meta($page_id, 'page_header_transparency', true) == "no_transparency") ) {
						$header_transparency_class = "normal_header";
						$transparency_scheme = "";
					}
					
					?>
                    
                    <div id="page" class="<?php echo $header_transparency_class; ?> <?php echo $transparency_scheme; ?>">
                    
                        <?php do_action( 'before' ); ?>
                        
                        <div class="top-headers-wrapper">
						
							<?php if ( (!isset($mr_tailor_theme_options['top_bar_switch'])) || ($mr_tailor_theme_options['top_bar_switch'] == "1" ) ) : ?>                        
                                <?php include_once('header-topbar.php'); ?>
                            <?php endif; ?>                      
                            
                            <?php
                            
							if ( (isset($mr_tailor_theme_options['header_layout'])) && ($mr_tailor_theme_options['header_layout'] == "0" ) ) {
								include_once('header-default.php');
							} else {
								include_once('header-centered.php');
							}
							
							?>
                        
                        </div>
                        
                        <?php if (function_exists('wc_print_notices')) : ?>
                        <?php wc_print_notices(); ?>
                        <?php endif; ?>

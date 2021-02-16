					<?php global $woocommerce, $yith_wcwl, $mr_tailor_theme_options, $page_id; ?>
                    
                    <?php

                    $page_id = "";
                    if ( is_single() || is_page() ) {
                        $page_id = get_the_ID();
                    } else if ( is_home() ) {
                        $page_id = get_option('page_for_posts');		
                    }
					
					if (get_post_meta( $page_id, 'footer_meta_box_check', true )) {
						$page_footer_option = get_post_meta( $page_id, 'footer_meta_box_check', true );
					} else {
						$page_footer_option = "off";
					}
					
					?>
                    
                    <?php if ( $page_footer_option == "off" ) : ?>

					<div class="my-before-footer"><img src="/wp-content/themes/mrtailor-child/img/ZONZ Sunsails Maatwerk Schaduwdoeken Footer.png" alt="footer" /></div>
                    <footer id="site-footer" role="contentinfo" class="4444 oleg">
<?php
echo 'WordPress: '
    . round(memory_get_usage()/1024/1024, 2) . 'MB '
    .' |  MySQL:' . get_num_queries() . ' | ';
timer_stop(1);
echo 'sec';
?>
						 <?php if ( is_active_sidebar( 'footer-widget-area' ) ) : ?>
						 
						
								<div class="row site-footer-widget-area-1">
									<?php dynamic_sidebar( 'footer-widget-area' ); ?>
								</div><!-- .row -->
						
						<?php endif; ?>
                        <div class="my-site-footer-area text-center"><a href="https://www.keurmerk.info/consumenten/webwinkel/?key=5306" target="_blank"><img src="/wp-content/uploads/2018/02/ZONZ-Sunsails-Webshop-Keurmerk.png" alt="ZONZ Sunsails Webshop Keurmerk" style="width:10%"/></a>

<img src="/wp-content/uploads/2018/02/Betaalmethodes-ZONZ-sunsail-Webshop.png" alt="Betaalmethodes-ZONZ-sunsail-Webshop" style="width:80%"/>
</div>						
                        <div class="site-footer-copyright-area">
                            <div class="row">
								<?php  /* medium-4 columns payment_methods credit_card_icons removed  */  ?>                                
                                <div class="medium-12 columns">
                                    <div class="copyright_text">
                                        <?php if ( (isset($mr_tailor_theme_options['footer_copyright_text'])) && (trim($mr_tailor_theme_options['footer_copyright_text']) != "" ) ) { ?>
                                            <?php _e( $mr_tailor_theme_options['footer_copyright_text'], 'mr_tailor' ); ?>
                                        <?php } ?>


<?php echo get_num_queries(); ?> queries in <?php timer_stop(1); ?> seconds 111.



                                    </div><!-- .copyright_text -->  
                                </div><!-- .large-8 .columns -->            
                            </div><!-- .row --> 
                        </div><!-- .site-footer-copyright-area -->
 

						 <?php if ( is_active_sidebar( 'footer-widget-area-2' ) ) : ?>
 							<div class="site-footer-widget-area site-footer-widget-area-2">
								<div class="row">
									<?php dynamic_sidebar( 'footer-widget-area-2' ); ?>
								</div><!-- .row -->
							</div><!-- .site-footer-widget-area-2 -->
                        
						<?php endif; ?>
 
                               
                    </footer>

                   

                    <?php endif; ?>


                    
                </div><!-- #page -->
                        
            </div><!-- /st-content -->
        </div><!-- /st-pusher -->
        
        <nav class="st-menu slide-from-left">
            <div class="nano">
                <div class="nano-content">
                    <div id="mobiles-menu-offcanvas" class="offcanvas-left-content">
                    	
                        <nav id="mobile-main-navigation" class="mobile-navigation" role="navigation">
						<?php 
							wp_nav_menu(array(
								'theme_location'  => 'main-navigation',
								'fallback_cb'     => false,
								'container'       => false,
								'items_wrap'      => '<ul id="%1$s">%3$s</ul>',
							));
						?>
                        </nav>
                        
                        <?php 
						
						$theme_locations  = get_nav_menu_locations();
						if (isset($theme_locations['top-bar-navigation'])) {
							$menu_obj = get_term($theme_locations['top-bar-navigation'], 'nav_menu');
						}
						
						if ( (isset($menu_obj->count) && ($menu_obj->count > 0)) || (is_user_logged_in()) ) {
						?>
                        
                            <nav id="mobile-top-bar-navigation" class="mobile-navigation" role="navigation">
                            <?php 
                                wp_nav_menu(array(
                                    'theme_location'  => 'top-bar-navigation',
                                    'fallback_cb'     => false,
                                    'container'       => false,
                                    'items_wrap'      => '<ul id="%1$s">%3$s</ul>',
                                ));
                            ?>
                            
                            <?php if ( is_user_logged_in() ) { ?>
                                <ul><li><a href="<?php echo get_site_url(); ?>/?<?php echo get_option('woocommerce_logout_endpoint'); ?>=true" class="logout_link"><?php _e('Logout', 'mr_tailor'); ?></a></li></ul>
                            <?php } ?>
                            </nav>
                        
                        <?php } ?>
                        
                        <div class="language-and-currency-offcanvas hide-for-large-up">
							
							<?php if (function_exists('icl_get_languages')) { ?>
            
                                <?php $additional_languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str'); ?>
                                
                                <select class="topbar-language-switcher">
                                    <option><?php echo ICL_LANGUAGE_NAME; ?></option>
                                    <?php
                                            
                                    if (count($additional_languages) > 1) {
                                        foreach($additional_languages as $additional_language){
                                          if(!$additional_language['active']) $langs[] = '<option value="'.$additional_language['url'].'">'.$additional_language['native_name'].'</option>';
                                        }
                                        echo join(', ', $langs);
                                    }
                                    
                                    ?>
                                </select>
                            
                            <?php } ?>
                            
                            <?php if (class_exists('woocommerce_wpml')) { ?>
                                <?php echo(do_shortcode('[currency_switcher]')); ?>
                            <?php } ?>
                        
                        </div>
                        
                        <div class="mobile-socials">
                            <div class="site-social-icons">
                                <ul class="//animated //flipY">
                                    <?php if ( (isset($mr_tailor_theme_options['facebook_link'])) && (trim($mr_tailor_theme_options['facebook_link']) != "" ) ) { ?><li class="site-social-icons-facebook"><a target="_blank" href="<?php echo $mr_tailor_theme_options['facebook_link']; ?>"><i class="fa fa-facebook"></i><span>Facebook</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['twitter_link'])) && (trim($mr_tailor_theme_options['twitter_link']) != "" ) ) { ?><li class="site-social-icons-twitter"><a target="_blank" href="<?php echo $mr_tailor_theme_options['twitter_link']; ?>"><i class="fa fa-twitter"></i><span>Twitter</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['pinterest_link'])) && (trim($mr_tailor_theme_options['pinterest_link']) != "" ) ) { ?><li class="site-social-icons-pinterest"><a target="_blank" href="<?php echo $mr_tailor_theme_options['pinterest_link']; ?>"><i class="fa fa-pinterest"></i><span>Pinterest</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['linkedin_link'])) && (trim($mr_tailor_theme_options['linkedin_link']) != "" ) ) { ?><li class="site-social-icons-linkedin"><a target="_blank" href="<?php echo $mr_tailor_theme_options['linkedin_link']; ?>"><i class="fa fa-linkedin"></i><span>LinkedIn</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['googleplus_link'])) && (trim($mr_tailor_theme_options['googleplus_link']) != "" ) ) { ?><li class="site-social-icons-googleplus"><a target="_blank" href="<?php echo $mr_tailor_theme_options['googleplus_link']; ?>"><i class="fa fa-google-plus"></i><span>Google+</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['rss_link'])) && (trim($mr_tailor_theme_options['rss_link']) != "" ) ) { ?><li class="site-social-icons-rss"><a target="_blank" href="<?php echo $mr_tailor_theme_options['rss_link']; ?>"><i class="fa fa-rss"></i><span>RSS</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['tumblr_link'])) && (trim($mr_tailor_theme_options['tumblr_link']) != "" ) ) { ?><li class="site-social-icons-tumblr"><a target="_blank" href="<?php echo $mr_tailor_theme_options['tumblr_link']; ?>"><i class="fa fa-tumblr"></i><span>Tumblr</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['instagram_link'])) && (trim($mr_tailor_theme_options['instagram_link']) != "" ) ) { ?><li class="site-social-icons-instagram"><a target="_blank" href="<?php echo $mr_tailor_theme_options['instagram_link']; ?>"><i class="fa fa-instagram"></i><span>Instagram</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['youtube_link'])) && (trim($mr_tailor_theme_options['youtube_link']) != "" ) ) { ?><li class="site-social-icons-youtube"><a target="_blank" href="<?php echo $mr_tailor_theme_options['youtube_link']; ?>"><i class="fa fa-youtube-play"></i><span>Youtube</span></a></li><?php } ?>
                                    <?php if ( (isset($mr_tailor_theme_options['vimeo_link'])) && (trim($mr_tailor_theme_options['vimeo_link']) != "" ) ) { ?><li class="site-social-icons-vimeo"><a target="_blank" href="<?php echo $mr_tailor_theme_options['vimeo_link']; ?>"><i class="fa fa-vimeo-square"></i><span>Vimeo</span></a></li><?php } ?>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                    <div id="filters-offcanvas" class="offcanvas-left-content wpb_widgetised_column">
						<?php if ( is_active_sidebar( 'catalog-widget-area' ) ) : ?>
                            <?php dynamic_sidebar( 'catalog-widget-area' ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
      
        <nav class="st-menu slide-from-right">
            <div class="nano">
                <div class="nano-content">
					<div id="minicart-offcanvas" class="offcanvas-right-content"><?php if ( class_exists( 'WC_Widget_Cart' ) ) { the_widget( 'mr_tailor_WC_Widget_Cart' ); } ?></div>
                    <div id="wishlist-offcanvas" class="offcanvas-right-content"><div class="widget"></div></div>
                </div>
            </div>
        </nav>
    
    </div><!-- /st-container -->
    
    <!-- ******************************************************************** -->
    <!-- * Custom Footer JavaScript Code ************************************ -->
    <!-- ******************************************************************** -->
    
    <?php if ( (isset($mr_tailor_theme_options['footer_js'])) && ($mr_tailor_theme_options['footer_js'] != "") ) : ?>
		<script type="text/javascript">
			<?php echo $mr_tailor_theme_options['footer_js']; ?>
		</script>
    <?php endif; ?>

	
	
    <!-- ******************************************************************** -->
    <!-- * WP Footer() ****************************************************** -->
    <!-- ******************************************************************** -->
	
	<div class="login_header">
		<a class="go_home" href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
	</div>
    
   

<?php wp_footer(); ?>
<style>
    .st-menu {max-width: 100%;}
</style>
     <script type='text/javascript' src='<?php echo get_template_directory_uri()?>-child/js/scripts.js?ver=4.4.5'></script>
<?php $triangle = get_post_meta($post->ID);
//print_r($triangle);
/*
    if ($triangle['triangle'][0]     == 'on') { exit; }	   
    if ($triangle['triangle90dr'][0] == 'on') { exit; }
    if ($triangle['triangle90dl'][0] == 'on') { exit; }
    if ($triangle['triangle60d'][0]  == 'on') { exit; }
    if ($triangle['square'][0]       == 'on') { exit; }
    if ($triangle['rectsquare'][0]       == 'on') { exit; }
    if ($triangle['isquare'][0]      == 'on') { exit; }
    if ($triangle['lsquare'][0]      == 'on') { exit; }*/
    ?>
<style>
    .blockOverlay {display: none!important;}
</style>

<script type='text/javascript'>
window.__lo_site_id = 93708;

	(function() {
		var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
		wa.src = 'https://d10lpsik1i8c69.cloudfront.net/w.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
	  })();
	</script>
</body>
</html>

<?php
/*
Template Name: Page without all dropdown footer items
*/
?>

<?php
    global $mr_tailor_theme_options;

    $page_id = "";
    if ( is_single() || is_page() ) {
        $page_id = get_the_ID();
    } else if ( is_home() ) {
        $page_id = get_option('page_for_posts');        
    }

    $blog_with_sidebar = "";
    if ( (isset($mr_tailor_theme_options['sidebar_blog_listing'])) && ($mr_tailor_theme_options['sidebar_blog_listing'] == "1" ) ) $blog_with_sidebar = "yes";
    if (isset($_GET["blog_with_sidebar"])) $blog_with_sidebar = $_GET["blog_with_sidebar"];

    $page_header_src = "";

    if (has_post_thumbnail()) $page_header_src = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

    if (get_post_meta( $page_id, 'page_title_meta_box_check', true )) {
        $page_title_option = get_post_meta( $page_id, 'page_title_meta_box_check', true );
    } else {
        $page_title_option = "off";
    }

?>

<?php get_header(); ?>

    <div id="primary" class="content-area">
       
        <div id="content" class="site-content" role="main">
        
            <header class="entry-header <?php if ($page_header_src != "") : ?>with_featured_img<?php endif; ?>" style="background-image:url(<?php echo $page_header_src; ?>)">
        
                <div class="page_header_overlay"></div>
                
                <div class="row">
                    <?php if ( $blog_with_sidebar == "yes" ) : ?>
                    <div class="large-12 columns">
                    <?php else : ?>
                    <div class="large-12 large-centered columns without-sidebar">
                    <?php endif; ?>
        
                        <?php if ( is_page() ) : ?>
        
                        <?php if ( (isset($page_title_option)) && ($page_title_option == "off") ) : ?>
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <?php endif; ?>
                        
                        <?php if($post->post_excerpt) : ?>
                            <div class="page-description"><?php the_excerpt(); ?></div>
                        <?php endif; ?>
                        
                        <?php else : ?>
        
                        <h1 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h1>
        
                        <?php endif; // is_page() ?>
        
                    </div>
                </div>
        
            </header><!-- .entry-header -->

            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'content', 'page' ); ?>
                    
                <?php if (function_exists('is_cart') && is_cart()) : ?>
                <?php else: ?>    
                <div class="clearfix"></div>
                <footer class="entry-meta">    
                    <?php //edit_post_link( __( 'Edit', 'mr_tailor' ), '<div class="edit-link"><i class="fa fa-pencil-square-o"></i> ', '</div>' ); ?>
                </footer><!-- .entry-meta -->
                <?php endif; ?>

                <?php
                    
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || '0' != get_comments_number() ) comments_template();
                    
                ?>

            <?php endwhile; // end of the loop. ?>

        </div><!-- #content -->           
        
    </div><!-- #primary -->
    
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
                    <footer id="site-footer" role="contentinfo">

                        <div class="my-site-footer-area text-center"><img src="/wp-content/themes/mrtailor-child/img/Betaalmethodes-ZONZ-sunsail-Webshop.png" alt="Betaalmethodes ZONZ sunsail Webshop"/></div>                     
                        <div class="site-footer-copyright-area">
                            <div class="row">
                                <?php  /* medium-4 columns payment_methods credit_card_icons removed  */  ?>                                
                                <div class="medium-12 columns">
                                    <div class="copyright_text">
                                        <?php if ( (isset($mr_tailor_theme_options['footer_copyright_text'])) && (trim($mr_tailor_theme_options['footer_copyright_text']) != "" ) ) { ?>
                                            <?php _e( $mr_tailor_theme_options['footer_copyright_text'], 'mr_tailor' ); ?>
                                        <?php } ?>
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
    if ($triangle['triangle'][0]     == 'on') { exit; }    
    if ($triangle['triangle90dr'][0] == 'on') { exit; }
    if ($triangle['triangle90dl'][0] == 'on') { exit; }
    if ($triangle['triangle60d'][0]  == 'on') { exit; }
    if ($triangle['square'][0]       == 'on') { exit; }
    if ($triangle['rectsquare'][0]       == 'on') { exit; }
    if ($triangle['isquare'][0]      == 'on') { exit; }
    if ($triangle['lsquare'][0]      == 'on') { exit; }
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

<!-- Global site tag (gtag.js) - Google AdWords: 1036426429 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-1036426429"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-1036426429');
</script>

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '2006226799703666');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=2006226799703666&ev=PageView"
/></noscript>
<!-- End Facebook Pixel Code -->
</body>
</html>


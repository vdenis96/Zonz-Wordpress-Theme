<?php
/*
Template Name: Page without header
*/
?>

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


    <div class="full-width-page">
    
        <div id="primary" class="content-area">
           
            <div id="content" class="site-content" role="main">
                
                    <?php while ( have_posts() ) : the_post(); ?>
        
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div><!-- .entry-content -->
        
                    <?php endwhile; // end of the loop. ?>
    
            </div><!-- #content -->           
            
        </div><!-- #primary -->
    
    </div><!-- .full-width-page -->
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
<?php get_footer(); ?>
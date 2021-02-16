<?php
/*
Template Name: Page without footer
*/
?>

<?php get_header(); ?>

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


                   

                    <?php endif; ?>


                    
                </div><!-- #page -->
                        
            </div><!-- /st-content -->
        </div><!-- /st-pusher -->
        
 
    
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

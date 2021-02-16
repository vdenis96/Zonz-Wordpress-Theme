<div id="site-top-bar">

    <div class="row">

        <div class="large-7 columns">

			<?php

				$page_id = get_the_ID();

				if(1){ ?>


          <div class="language-and-currency large-8 columns lang-text">

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

    <div style="float:left" class="site-top-message">
   Dé specialist in maatwerk schaduwdoeken
    </div>
    			</div>
	 				<?php
	 			}
			?>
      <div class="large-4 columns sunny-image">

         <div class="sunny-side-logo">
           <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/Sunny side of life.png" alt="" />
         </div>

   </div>	<!--.language-and-currency-->



        </div><!-- .large-6 .columns -->

        <div class="large-5 columns">

             <nav id="site-navigation-top-bar" class="main-navigation" role="navigation">
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
            </nav><!-- #site-navigation -->

        <div class="site-social-icons-wrapper">
                <div class="site-social-icons">
                    <ul class="//animated //flipY">
                        <?php if ( (isset($mr_tailor_theme_options['facebook_link'])) && (trim($mr_tailor_theme_options['facebook_link']) != "" ) ) { ?><li class="site-social-icons-facebook"><a target="_blank" href="<?php echo $mr_tailor_theme_options['facebook_link']; ?>"><i class="fa fa-facebook"></i><span>Facebook</span></a></li><?php } ?>
                        <?php if ( (isset($mr_tailor_theme_options['pinterest_link'])) && (trim($mr_tailor_theme_options['pinterest_link']) != "" ) ) { ?><li class="site-social-icons-pinterest"><a target="_blank" href="<?php echo $mr_tailor_theme_options['pinterest_link']; ?>"><i class="fa fa-pinterest"></i><span>Pinterest</span></a></li><?php } ?>
                        <?php if ( (isset($mr_tailor_theme_options['instagram_link'])) && (trim($mr_tailor_theme_options['instagram_link']) != "" ) ) { ?><li class="site-social-icons-instagram"><a target="_blank" href="<?php echo $mr_tailor_theme_options['instagram_link']; ?>"><i class="fa fa-instagram"></i><span>Instagram</span></a></li><?php } ?>
                        <?php if ( (isset($mr_tailor_theme_options['youtube_link'])) && (trim($mr_tailor_theme_options['youtube_link']) != "" ) ) { ?><li class="site-social-icons-youtube"><a target="_blank" href="<?php echo $mr_tailor_theme_options['youtube_link']; ?>"><i class="fa fa-youtube-play"></i><span>Youtube</span></a></li><?php } ?>   
                    </ul>
                </div>
        </div>

            <div style="float:right" class="site-top-message">  E-mail ons: <a href="mailto:info@zonz.nl">info@zonz.nl</a>
				<?php // if ( isset($mr_tailor_theme_options['top_bar_text']) ) _e( str_replace('Dé expert in maatwerk schaduwdoeken - ','<span style="display:block;float:left;">Dé expert in maatwerk schaduwdoeken</span> ', $mr_tailor_theme_options['top_bar_text']), 'mr_tailor' ); ?></div>

        </div><!-- .large-8 .columns -->

    </div><!-- .row -->

</div><!-- #site-top-bar -->

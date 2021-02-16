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
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-MSTS4V8');</script>
	<!-- End Google Tag Manager -->
	<!-- Google Tag Manager 2021 -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-N73QDGH');</script>
	<!-- End Google Tag Manager -->
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) 2021 -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N73QDGH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MSTS4V8"
				  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
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

								<!--<li class="search-button 11">


										<h3 class="widget-title">Zoek in de ZONZ webshop</h3>
										<div id="product-search-0" class="product-search floating">
											<div class="product-search-form">
												<form id="product-search-form-0" class="product-search-form " action="https://staging-zonzsunsails.kinsta.cloud/" method="get">
													<input id="product-search-field-0" name="s" type="text" class="product-search-field" placeholder="Waar ben je naar op zoek?" autocomplete="off">
													<input type="hidden" name="post_type" value="product">
													<input type="hidden" name="title" value="1">
													<input type="hidden" name="excerpt" value="1">
													<input type="hidden" name="content" value="1">
													<input type="hidden" name="categories" value="1">
													<input type="hidden" name="attributes" value="1">
													<input type="hidden" name="tags" value="1">
													<input type="hidden" name="sku" value="1">
													<input type="hidden" name="orderby" value="popularity-DESC">
													<input type="hidden" name="ixwps" value="1">
													<span title="Clear" class="product-search-field-clear" style="display:none"></span>
													<noscript><button type="submit">Zoek nu!</button></noscript>
												</form>
											</div>
											<div id="product-search-results-0" class="product-search-results">
												<div id="product-search-results-content-0" class="product-search-results-content" style=""></div>
											</div>
										</div>
										<script type="text/javascript">document.getElementById("product-search-field-0").disabled = true;document.addEventListener( "DOMContentLoaded", function() {if ( typeof jQuery !== "undefined" ) {if ( typeof jQuery().typeWatch !== "undefined" ) {jQuery("#product-search-field-0").typeWatch( {
					callback: function (value) { ixwps.productSearch('product-search-field-0', 'product-search-0', 'product-search-0 div.product-search-results-content', 'https://staging-zonzsunsails.kinsta.cloud/wp-admin/admin-ajax.php?order=DESC&order_by=popularity&title=1&excerpt=1&content=1&categories=1&attributes=1&tags=1&sku=1&limit=10&category_results=1&category_limit=5&product_thumbnails=1', value, {no_results:"Geen resultaten gevonden",dynamic_focus:true,product_thumbnails:true,show_description:true,show_price:true,show_add_to_cart:true,show_more:true}); },
					wait: 500,
					highlight: true,
					captureLength: 1
				} );ixwps.navigate("product-search-field-0","product-search-results-0");ixwps.dynamicFocus("product-search-0","product-search-results-content-0");} else {if ( typeof console !== "undefined" && typeof console.log !== "undefined" ) { document.getElementById("product-search-field-0").disabled = false;console.log("A conflict is preventing required resources to be loaded."); }}}} );</script>

								</li>-->

							</ul>
						</div>
						<div class="site-search">
							<?php get_search_form(); ?>
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

					<?php /* oleg 07052020 Disabled to fix PHP warnings as this part is not implemented in child theme

							if ( (isset($mr_tailor_theme_options['header_layout'])) && ($mr_tailor_theme_options['header_layout'] == "0" ) ) {
								include_once('header-default.php');
							} else {
								include_once('header-centered.php');
							}
*/
					?>

				</div>

				<?php if (function_exists('wc_print_notices')) : ?>
					<?php wc_print_notices(); ?>
				<?php endif; ?>

<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<style>
	.entry-title { display: none; }
</style>

<div class="row">
	<div class="large-10 text-center large-centered columns">

		<div class="cart-empty-banner cart-wishlist-empty-banner">
			<img id="cart-empty-img" alt="cart-empty-image"  width="480" height="233"  src="<?php echo get_template_directory_uri() . '/images/empty_cart.png'; ?>" data-interchange="[<?php echo get_template_directory_uri() . '/images/empty_cart.png'; ?>, (default)], [<?php echo get_template_directory_uri() . '/images/empty_cart_retina.png'; ?>, (retina)]">
		</div>
		
		<p class="cart-empty cart-wishlist-empty"><?php _e( 'Your cart is currently empty.', 'woocommerce' ) ?></p>
		
		<?php do_action( 'woocommerce_cart_is_empty' ); ?>
		
		<p class="return-to-shop"><a class="wc-backward" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php _e( 'Return To Shop', 'woocommerce' ) ?></a></p>

	</div><!-- .large-10-->
</div><!-- .row-->
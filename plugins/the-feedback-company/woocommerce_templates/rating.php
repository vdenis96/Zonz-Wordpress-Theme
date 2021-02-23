<?php
/**
 * WooCommerce template for overriding the product rating with our own widget
 */

if (!defined( 'ABSPATH'))
	exit();

global $feedbackcompany_woocommerce;

?>

<div class="woocommerce-product-rating">
	<?php echo $feedbackcompany_woocommerce->output_rating(); ?>
</div>

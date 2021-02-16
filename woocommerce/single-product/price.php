<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<!-- div itemprop="offers" itemscope itemtype="http://schema.org/Offer" -->
<?php //oleg
  error_log(print_r('oleg price.php Start of $product->get_price_html()',true)); ?>
	<p class="price"><?php echo '<span class="from"><!-- oleg1111price.php -->Vanaf' .' </span>'. $product->get_price_html(); ?></p>

<?php //oleg
  error_log(print_r('oleg price.php End of $product->get_price_html()',true)); ?>
  
  
	<!-- meta itemprop="price" content="<?php echo esc_attr( $product->get_price() ); ?>" />
<?php //oleg
  error_log(print_r('oleg price.php End of 222',true)); ?>

	<meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>" />
<?php //oleg
  error_log(print_r('oleg price.php End of 333',true)); ?>

	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
<?php //oleg
  error_log(print_r('oleg price.php End of 444',true)); ?>
</div -->

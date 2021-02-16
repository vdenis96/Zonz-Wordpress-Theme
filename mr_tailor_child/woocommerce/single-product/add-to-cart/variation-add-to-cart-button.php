<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php if ( ! $product->is_sold_individually() ) : ?>
		<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
	<?php endif; ?>
    <?php
    $triangle = get_post_meta($product->id); 
    if (($triangle['triangle'][0] == 'on') || 	   
     ($triangle['triangle90dr'][0] == 'on')  || 
     ($triangle['triangle90dl'][0] == 'on')  || 
     ($triangle['square'][0] == 'on')  || 
     ($triangle['isquare'][0] == 'on')  || 
     ($triangle['lsquare'][0] == 'on')  || 
     ($triangle['rectsquare'][0] == 'on')  ||
     ($triangle['triangle60d'][0] == 'on')) {
    ?>
    <button type="submit" class="single_add_to_cart_button button alt"><?php echo 'Voeg mijn ZONZ maatwerk toe aan mijn winkelmandje'; ?></button>
    <?php     
     } else {
    ?>
    <button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
    <?php     
     }
    ?>
	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>

<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<style>
    .woocommerce-cart .entry-content .woocommerce .cart-collaterals .cart_totals{
        padding-top: 0px!important;
        padding-bottom: 0px!important;
    }
    
    .cart-collaterals{
         padding-top: 0px!important;
        padding-bottom: 0px!important;
    }
    
    .woocommerce-cart .entry-content .woocommerce > form{
        margin-bottom: 0px!important;
    }
    
    .woocommerce table.shop_table, .woocommerce-page table.shop_table{
        margin-bottom: 10px!important;
    }
</style>

<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<?php 
    $productAttr = array(
        'area' => $_POST['area'], 
        'disc' => $_POST['disc'], 
        'point1' => $_POST['point1'],
        'point2' => $_POST['point2'], 
        'point3' => $_POST['point3'],
        'width' =>  $_POST['width'],
        'height' =>  $_POST['height']
    );

    //session_start();
    if(!empty($productAttr['area'])){
        $_SESSION['productAttr'] = $productAttr;  
    }

    $attr =  $_SESSION['productAttr'];
    //echo print_r($attr, true);
    /*echo '<pre>';
    echo 'sess<br>';
    print_r($_SESSION); 
    echo 'post<br>';
    //print_r($_POST); 
    echo 'attr<br>';
    //print_r($attr); 
    echo 'WC<br>';
    print_r(WC()->cart->get_cart());
    echo '</pre>';*/
?>
<style>
td.aftercoupon div .woo_quote_button {display: none;}
</style>
<table class="shop_table shop_table_responsive cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>
			<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>
<?php 
/*
echo '<pre>';
 print_r(WC()->cart->get_cart()); 
 echo '</pre>'; 
 */
 ?>
 
 
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			//echo $cart_item['area']['area'];
			//echo '<pre>';print_r($cart_item);die();

			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-remove">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
								__( 'Remove this item', 'woocommerce' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
					</td>

					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() ) {
								echo $thumbnail;
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail );
							}
						?>
					</td>

					<td class="product-name" data-title="<?php _e( 'Product', 'woocommerce' ); ?>">
						<?php
							if ( ! $_product->is_visible() ) {
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
							} else {
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $_product->get_title() ), $cart_item, $cart_item_key );
							}

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );
                            ?>
                            <style>
                            	.attributes {
								    font-size: 12px;
								}
                        	</style>
                            <div class="attributes" style="<?php echo ($cart_item['area']['area']) ? '' : 'display:none' ?>">
                                <?php if ($cart_item['area']['area']) { ?>
                                <style>
                                .woo_quote_button {display: inline-block !important;}
                                </style>
                                <?php } ?>

                                                    <div class="attr"><strong>Oppervlakte:&nbsp;</strong><?=$cart_item['area']['area'];?></div>
                                                    <!--
                                                    <div class="attr"><strong>Breedte:&nbsp;</strong><?=$attr[$cart_item_key]['width'];?></div>
                                                    <div class="attr"><strong>Lengte:&nbsp;</strong><?=$attr[$cart_item_key]['height'];?></div>
                                                    
                                                    <?php /*if ($attr[$cart_item_key]['point1'] != "0") : ?>
                                                    <div class="attr">
                                                        <strong>Point1:&nbsp;</strong>
                                                           <?php 
                                                                $point1 = explode(',', $attr[$cart_item_key]['point1']);
                                                                echo 'X = '.$point1[0].'&nbsp;&nbsp;'.'Y='.$point1[1];
                                                           ?>
                                                    </div>
                                                    <div class="attr"><strong>Point2:&nbsp;</strong>
                                                           <?php 
                                                                $point2 = explode(',', $attr[$cart_item_key]['point2']);
                                                                echo 'X = '.$point2[0].'&nbsp;&nbsp;'.'Y='.$point2[1];
                                                           ?>
                                                    </div>
                                                    <div class="attr"><strong>Point3:&nbsp;</strong>
                                                          <?php 
                                                                $point3 = explode(',', $attr[$cart_item_key]['point3']);
                                                                echo 'X = '.$point3[0].'&nbsp;&nbsp;'.'Y='.$point3[1];
                                                           ?>
                                                    </div>    
                                                    <?php endif; */?>
                                                    -->
                                                </div>
                                                <?php

							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
							}
						?>
					</td>

					<td class="product-price" data-title="<?php _e( 'Price', 'woocommerce' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity" data-title="<?php _e( 'Quantity', 'woocommerce' ); ?>">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
					</td>

					<td class="product-subtotal" data-title="<?php _e( 'Total', 'woocommerce' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
        <tr>
            <td colspan="6" class="aftercoupon">
                <div class="wc-proceed-to-checkout2" style="text-align:right">
                    <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
                </div>
            </td>
        </tr>
           
		<tr>
                    <td colspan="6" class="actions" style="text-align: left; padding-bottom:40px">
                            
				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon">

						<label for="coupon_code"><?php _e( 'Coupon', 'woocommerce' ); ?>:</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'woocommerce' ); ?>" />

						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
					<a class="button wc-backward" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php _e( 'Terug Naar winkel', 'woocommerce' ) ?></a>
				<?php } ?>

				<input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Winkelmand bijwerken', 'woocommerce' ); ?>" />

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
                              
			</td>
		</tr>
		    
                <!-- add button after coupon --> 
               
                <style>
				 .woo_quote_button{
                        border: 2px solid #4a4f6a !important;
                        color: #4a4f6a !important;
                        background: #fff!important
                    }
                    .shop_table + .wc-proceed-to-checkout {
                        display: none !important;
                    }
                    .woocommerce-cart .entry-content .woocommerce .actions .button{
                        float: none!important
                    }
                    
                   
					
					
                </style>
                <!-- END add button after coupon --> 
		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<script>
jQuery(document).ready(function(){
    jQuery('.cart-collaterals .shipping th:first-child').text('Verzendkosten'); 
    jQuery('.cart-collaterals .shipping td p').text('Gratis verzending naar Nederland en België'); 
});
</script>


<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
<br><br>
<center><img src="/wp-content/uploads/Vertrouwensbanner-ZONZ.jpg"></center>
<br><br>

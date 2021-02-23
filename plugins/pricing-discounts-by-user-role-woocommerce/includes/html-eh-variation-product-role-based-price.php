<strong><p style="text-align: center; font-size: 20px;">
<?php 
	$user_roles = get_option('eh_pricing_discount_product_price_user_role');
	if(is_array($user_roles)) {
	_e( 'Role Based Price', 'eh-woocommerce-pricing-discount' ); 
	global $wp_roles;
?>
</p></strong>
<table class="product_role_based_price widefat" id="eh_pricing_discount_product_price_adjustment_data">
	<thead>
		<th class="sort">&nbsp;</th>
		<th><?php _e( 'User Role', 'eh-woocommerce-pricing-discount' ); ?></th>
		<th><?php echo sprintf( __( 'Price (%s)', 'eh-woocommerce-pricing-discount' ), get_woocommerce_currency_symbol() ); ?></th>
	</thead>
	<tbody>
	
		<?php
			$this->price_table = array();
			$i=0;
			$product_adjustment_price;
			$product_adjustment_price = get_post_meta( $variation->ID, 'product_role_based_price', false );
			$user_roles = get_option('eh_pricing_discount_product_price_user_role');
			foreach ( $user_roles as $id => $value ) {
				$this->price_table[$i]['id'] = $value;
				$this->price_table[$i]['name'] = $wp_roles->role_names[$value];
				if((is_array($product_adjustment_price) && !empty($product_adjustment_price)) && key_exists($value,$product_adjustment_price[0] )) {
					$this->price_table[$i]['role_price'] = $product_adjustment_price[0][$value]['role_price'];
				}
				$i++;
			}
			foreach ( $this->price_table as $key => $value ) {
				?>
				<tr>
					<td class="sort" style="padding: 10px;">
						<input type="hidden" class="order" name="product_role_based_price[<?php echo $loop; ?>][<?php echo $this->price_table[ $key ]['id'] ?>]" value="<?php echo $this->price_table[ $key ]['id']; ?>" />
					</td>
					<td style="padding: 10px;">
						<label name="product_role_based_price[<?php echo $loop; ?>][<?php echo $this->price_table[ $key ]['id']; ?>][name]" style="margin-left:0px;"><?php echo isset( $this->price_table[ $key ]['name'] ) ? $this->price_table[ $key ]['name'] : ''; ?></label>
					</td>
					<td style="padding: 10px;">
						<?php echo get_woocommerce_currency_symbol(); ?><input style="width:30%;" type="text" name="product_role_based_price[<?php echo $loop; ?>][<?php echo $this->price_table[ $key ]['id']; ?>][role_price]" placeholder="N/A" value="<?php echo isset( $this->price_table[$key]['role_price'] ) ? $this->price_table[$key]['role_price'] : ''; ?>" size="4" />
					</td>
				</tr>
				<?php
			}
		?>
	</tbody>
</table>
<?php } ?>
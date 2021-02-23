?>
<h3 style="text-align: center;">
<?php 
	_e( 'Role Based Discount and Price Adjustment', 'eh-woocommerce-pricing-discount' ); 
	global $wp_roles;
?>
</h3>
<table class="product_price_adjustment widefat" id="eh_pricing_discount_product_price_adjustment_data">
	<thead>
		<th class="sort">&nbsp;</th>
		<th><?php _e( 'User Role', 'eh-woocommerce-pricing-discount' ); ?></th>
		<th><?php echo sprintf( __( 'Price Adjustment (%s)', 'eh-woocommerce-pricing-discount' ), get_woocommerce_currency_symbol() ); ?></th>
		<th><?php _e( 'Price Adjustment (%)', 'eh-woocommerce-pricing-discount' ); ?></th>
	</thead>
	<tbody>
	
		<?php
			$this->price_table = array();
			$i=0;
			$this->product_adjustment_price = get_post_meta( $post->ID, 'product_price_adjustment', false );
			foreach ( $wp_roles->role_names as $id => $value ) {
				$this->price_table[$i]['id'] = $id;
				$this->price_table[$i]['name'] = $value;
				if(is_array($this->product_adjustment_price) && key_exists($id,$this->product_adjustment_price[0]))
				{
					$this->price_table[$i]['adjustment_price'] = $this->product_adjustment_price[0][$id]['adjustment_price'];
					$this->price_table[$i]['adjustment_percent'] = $this->product_adjustment_price[0][$id]['adjustment_percent'];
				}
				$i++;
			}
			foreach ( $this->price_table as $key => $value ) {
				?>
				<tr>
					<td class="sort">
						<input type="hidden" class="order" name="product_price_adjustment[<?php echo $this->price_table[ $key ]['id'] ?>]" value="<?php echo $this->price_table[ $key ]['id']; ?>" />
					</td>
					<td>
						<label name="product_price_adjustment[<?php echo $this->price_table[ $key ]['id']; ?>][name]" style="margin-left:0px;"><?php echo isset( $this->price_table[ $key ]['name'] ) ? $this->price_table[ $key ]['name'] : ''; ?></label>
					</td>
					<td>
						<?php echo get_woocommerce_currency_symbol(); ?><input type="text" name="product_price_adjustment[<?php echo $this->price_table[ $key ]['id']; ?>][adjustment_price]" placeholder="N/A" value="<?php echo isset( $this->price_table[$key]['adjustment_price'] ) ? $this->price_table[$key]['adjustment_price'] : ''; ?>" size="4" />
					</td>
					<td>
						<input type="text" name="product_price_adjustment[<?php echo $this->price_table[ $key ]['id']; ?>][adjustment_percent]" placeholder="N/A" value="<?php echo isset( $this->price_table[$key]['adjustment_percent'] ) ? $this->price_table[$key]['adjustment_percent'] : ''; ?>" size="4" />%
					</td>
				</tr>
				<?php
			}
		?>
	</tbody>
</table>
<?php
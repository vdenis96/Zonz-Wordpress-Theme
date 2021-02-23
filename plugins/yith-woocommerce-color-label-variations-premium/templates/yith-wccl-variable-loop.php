<?php
/**
 * Variable product add to cart in loop
 *
 * @author  Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $available_variations ) ) : ?>

	<div class="variations_form cart in_loop" data-product_id="<?php echo $product->id; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
		<?php foreach ( $attributes as $name => $options ) : ?>
			<div class="<?php echo 'variations ' . sanitize_title( $name ); ?>">
				<select id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>" <?php if( isset( $attributes_types[$name] ) ) echo 'data-type="' . $attributes_types[$name] . '"'; ?>>
					<option value=""><?php echo __( 'Choose an option', 'ywcl' ) ?>&hellip;</option>
					<?php

					if ( is_array( $options ) ) {

						if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
							$selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
						} elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
							$selected_value = $selected_attributes[ sanitize_title( $name ) ];
						} else {
							$selected_value = '';
						}

						// Get terms if this is a taxonomy - ordered
						if ( taxonomy_exists( $name ) ) {

							$terms = wc_get_product_terms( $product->id, $name, array( 'fields' => 'all' ) );

							foreach ( $terms as $term ) {
								if ( ! in_array( $term->slug, $options ) ) {
									continue;
								}
								$value    = get_woocommerce_term_meta( $term->term_id, $name . '_yith_wccl_value');
								$tooltip  = get_woocommerce_term_meta( $term->term_id, $name . '_yith_wccl_tooltip');
								echo '<option value="' . esc_attr( $term->slug ) . '"' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . ' data-value="'. $value . '" data-tooltip="' . $tooltip . '">' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
							}
						} else {

							foreach ( $options as $option ) {
								echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '"' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
							}
						}
					}
					?>
				</select>
			</div>
		<?php endforeach;?>
	</div>

<?php endif;
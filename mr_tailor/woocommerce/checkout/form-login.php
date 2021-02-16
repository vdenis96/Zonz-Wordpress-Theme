<?php
/**
 * Checkout login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() || 'no' == get_option( 'woocommerce_enable_checkout_login_reminder' ) ) return;

$info_message  = apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'woocommerce' ) );
$info_message .= ' <a href="#" class="showlogin">' . __( 'Click here to login', 'woocommerce' ) . '</a>';
?>

<div class="checkout_login">
	 
	<?php wc_print_notice( $info_message, 'notice' ); ?>
	
	<div class="row">
		<div class="large-8 large-centered text-center columns">
	
			<?php
				woocommerce_login_form(
					array(
						'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'woocommerce' ),
						'redirect' => get_permalink( wc_get_page_id( 'checkout' ) ),
						'hidden'   => true
					)
				);
			?>
			
			<div class="notice-border-container">
				<img id="notice-border" src="<?php echo get_template_directory_uri(); ?>/images/checkout_notice_border.png"  alt="Checkout Notice Border" />
			</div>
	
		</div>
	</div>

</div><!-- .checkout_login-->

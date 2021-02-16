<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price">
        <?php $triangle = get_post_meta($product->id);  
    if (($triangle['triangle'][0] == 'on') ||
 ($triangle['triangle90dr'][0] == 'on') ||
 ($triangle['triangle90dl'][0] == 'on') ||
 ($triangle['triangle60d'][0] == 'on') ||	
 ($triangle['square'][0] == 'on')      ||
 ($triangle['rectsquare'][0] == 'on')  ||
 ($triangle['isquare'][0] == 'on')      ||
 ($triangle['lsquare'][0] == 'on')      )	{
    echo 'Vanaf' .' '. $price_html;
    } else {
        echo $price_html;
    } ?>
    </span>
<?php endif; ?>


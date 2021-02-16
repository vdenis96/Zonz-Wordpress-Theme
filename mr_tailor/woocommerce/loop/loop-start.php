<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
 
global $woocommerce_loop, $mr_tailor_theme_options;

if ( ( isset($woocommerce_loop['columns']) && $woocommerce_loop['columns'] != "" ) ) {
	$products_per_column = $woocommerce_loop['columns'];
} else {
	if ( ( !isset($mr_tailor_theme_options['products_per_column']) ) ) {
		$products_per_column = 4;
	} else {
		$products_per_column = $mr_tailor_theme_options['products_per_column'];
        if (isset($_GET["products_per_row"])) $products_per_column = $_GET["products_per_row"];
	}
}

if ($products_per_column == 6) {
	$products_per_column_large = 6;
	$products_per_column_medium = 4;
	$products_per_column_small = 2;
}

if ($products_per_column == 5) {
	$products_per_column_large = 5;
	$products_per_column_medium = 4;
	$products_per_column_small = 2;
}

if ($products_per_column == 4) {
	$products_per_column_large = 4;
	$products_per_column_medium = 3;
	$products_per_column_small = 2;
}

if ($products_per_column == 3) {
	$products_per_column_large = 3;
	$products_per_column_medium = 3;
	$products_per_column_small = 2;
}

if ($products_per_column == 2) {
	$products_per_column_large = 2;
	$products_per_column_medium = 2;
	$products_per_column_small = 2;
}

if ($products_per_column == 1) {
	$products_per_column_large = 1;
	$products_per_column_medium = 1;
	$products_per_column_small = 1;
}

if ( isset($mr_tailor_theme_options['products_animation']) ) {
    $effect = $mr_tailor_theme_options['products_animation'];
} else {
    $effect = e0;
}

?>
<div class="row">
	<div class="large-12 columns">
		<ul id="products-grid" class="<?php if ($mr_tailor_theme_options['add_to_cart_display'] != 1) echo 'force-add-to-cart'; ?> products products-grid effect-<?php echo $effect; ?> small-block-grid-<?php echo $products_per_column_small; ?> medium-block-grid-<?php echo $products_per_column_medium; ?> large-block-grid-<?php echo $products_per_column_large; ?> columns-<?php echo $products_per_column; ?>">
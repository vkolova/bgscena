<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span></span>

	<?php endif; ?>

	<?php
	echo '<span class="glyphicon glyphicon-map-marker"></span>';
	echo $product->get_categories( ', ', '<span class="posted_in">' . ' ', '</span></br>' );

	play_info($product->id);
	?>

	<?php //echo $product->get_tags( ', ', '<span class="tagged_as">' .  ' ', '</span>' ); ?>

	<?php do_action( 'vk_add_status_buttons' ); ?>



	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>

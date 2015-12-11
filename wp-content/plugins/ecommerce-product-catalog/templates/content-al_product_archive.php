<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * The template for displaying products archive content.
 *
 * @version		1.1.3
 * @package		ecommerce-product-catalog/templates
 * @author 		Norbert Dreszer
 */
global $post;
$default_archive_names	 = default_archive_names();
$multiple_settings		 = get_multiple_settings();
$archive_names			 = get_archive_names();
do_action( 'product_listing_begin', $multiple_settings );
$listing_class			 = apply_filters( 'product_listing_classes', 'al_product responsive type-page' );
?>
<article id="product_listing" <?php post_class( $listing_class ); ?>>
	<?php do_action( 'before_product_listing_entry', $post, $archive_names ); ?>
	<div class="entry-content">
		<?php
		$archive_template		 = get_product_listing_template();
		do_action( 'product_listing_entry_inside', $archive_template, $multiple_settings );
		?>
	</div>
</article><?php
do_action( 'product_listing_end', $archive_template, $multiple_settings );


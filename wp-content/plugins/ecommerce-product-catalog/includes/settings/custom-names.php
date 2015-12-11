<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manages custom names settings
 *
 * Here custom names settings are defined and managed.
 *
 * @version		1.1.4
 * @package		ecommerce-product-catalog/functions
 * @author 		Norbert Dreszer
 */
function default_single_names() {
	$single_names = array(
		'product_price'		 => __( 'Product Price:', 'ecommerce-product-catalog' ),
		'product_sku'		 => __( 'SKU:', 'ecommerce-product-catalog' ),
		'product_shipping'	 => __( 'Product Shipping:', 'ecommerce-product-catalog' ),
		'product_features'	 => __( 'Product Features', 'ecommerce-product-catalog' ),
		'other_categories'	 => __( 'See also different:', 'ecommerce-product-catalog' ),
		'return_to_archive'	 => __( '<< return to products', 'ecommerce-product-catalog' ),
	);

	return $single_names;
}

/**
 * Defines default labels for product listing pages
 *
 * @return array
 */
function default_archive_names() {
	$archive_names = array(
		'all_products'			 => __( 'All Products', 'ecommerce-product-catalog' ),
		'all_prefix'			 => __( 'All', 'ecommerce-product-catalog' ),
		'all_main_categories'	 => __( 'Main Categories', 'ecommerce-product-catalog' ),
		'all_subcategories'		 => '[product_category_name] ' . __( 'subcategories', 'ecommerce-product-catalog' ),
		'category_products'		 => '[product_category_name] ' . __( 'products', 'ecommerce-product-catalog' ),
		'next_products'			 => __( 'Next Page »', 'ecommerce-product-catalog' ),
		'previous_products'		 => __( '« Previous Page', 'ecommerce-product-catalog' )
	);

	return $archive_names;
}

function custom_names_menu() {
	?>
	<a id="names-settings" class="nav-tab" href="<?php echo admin_url( 'edit.php?post_type=al_product&page=product-settings.php&tab=names-settings&submenu=single-names' ) ?>	"><?php _e( 'Front-end Labels', 'ecommerce-product-catalog' ); ?></a>
	<?php
}

add_action( 'settings-menu', 'custom_names_menu' );

function custom_names_settings() {
	register_setting( 'product_names_archive', 'archive_names' );
	register_setting( 'product_names_single', 'single_names' );
}

add_action( 'product-settings-list', 'custom_names_settings' );

function custom_names_content() {
	?>
	<div class="names-product-settings settings-wrapper"> <?php
		$tab	 = $_GET[ 'tab' ];
		$submenu = $_GET[ 'submenu' ];
		?>
		<div class="settings-submenu">
			<h3>
				<a id="single-names" class="element current" href="<?php echo admin_url( 'edit.php?post_type=al_product&page=product-settings.php&tab=names-settings&submenu=single-names' ) ?>"><?php _e( 'Single Product', 'ecommerce-product-catalog' ); ?></a>
				<a id="archive-names" class="element" href="<?php echo admin_url( 'edit.php?post_type=al_product&page=product-settings.php&tab=names-settings&submenu=archive-names' ) ?>"><?php _e( 'Product Listings', 'ecommerce-product-catalog' ); ?></a>
			</h3>
		</div><?php if ( $submenu == 'single-names' ) { ?>
			<div id="single_names" class="setting-content submenu">
				<script>
					jQuery( '.settings-submenu a' ).removeClass( 'current' );
					jQuery( '.settings-submenu a#single-names' ).addClass( 'current' );
				</script>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'product_names_single' );
					$single_names = get_single_names();
					?>
					<h2><?php _e( 'Front-end Labels', 'ecommerce-product-catalog' ); ?></h2>
					<h3><?php _e( 'Single Product Labels', 'ecommerce-product-catalog' ); ?></h3>
					<table class="wp-list-table widefat product-settings-table" style="clear:right; text-align: left;">
						<thead><th><strong><?php _e( 'Front-end Element', 'ecommerce-product-catalog' ); ?></strong></th><th><strong><?php _e( 'Front-end Text', 'ecommerce-product-catalog' ); ?></strong></th></thead>
						<tbody>
							<tr><td><?php _e( 'Price Label', 'ecommerce-product-catalog' ); ?></td><td><input type="text" name="single_names[product_price]" value="<?php echo esc_html( $single_names[ 'product_price' ] ); ?>" /></td></tr>
							<tr><td><?php _e( 'SKU Label', 'ecommerce-product-catalog' ); ?></td><td><input type="text" name="single_names[product_sku]" value="<?php echo esc_html( $single_names[ 'product_sku' ] ); ?>" /></td></tr>
							<tr><td><?php _e( 'Shipping Label', 'ecommerce-product-catalog' ); ?></td><td><input type="text" name="single_names[product_shipping]" value="<?php echo esc_html( $single_names[ 'product_shipping' ] ); ?>" /></td></tr>
							<tr><td><?php _e( 'Features Label', 'ecommerce-product-catalog' ); ?></td><td><input type="text" name="single_names[product_features]" value="<?php echo esc_html( $single_names[ 'product_features' ] ); ?>" /></td></tr>
							<tr><td><?php _e( 'Another Categories', 'ecommerce-product-catalog' ); ?></td><td><input type="text" name="single_names[other_categories]" value="<?php echo esc_html( $single_names[ 'other_categories' ] ); ?>" /></td></tr>
							<tr><td><?php _e( 'Return to Products', 'ecommerce-product-catalog' ); ?></td><td><input type="text" name="single_names[return_to_archive]" value="<?php echo esc_html( $single_names[ 'return_to_archive' ] ); ?>" /></td></tr>
							<?php do_action( 'single_names_table', $single_names ) ?>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'ecommerce-product-catalog' ); ?>" />
					</p>
				</form>
			</div>
			<div class="helpers"><div class="wrapper"><?php main_helper(); ?>
				</div></div><?php
		} else if ( $submenu == 'archive-names' ) {
			?>
			<div id="archive_names" class="setting-content submenu">
				<script>
					jQuery( '.settings-submenu a' ).removeClass( 'current' );
					jQuery( '.settings-submenu a#archive-names' ).addClass( 'current' );
				</script>
				<form method="post" action="options.php"><?php
					settings_fields( 'product_names_archive' );
					$archive_names	 = get_archive_names();
					?>
					<h2><?php _e( 'Front-end Labels', 'ecommerce-product-catalog' ); ?></h2><?php
					$disabled		 = '';
					if ( get_integration_type() == 'simple' ) {
						$disabled = 'disabled';
						if ( is_integration_mode_selected() ) {
							implecode_warning( sprintf( __( 'Product listing pages are disabled with simple theme integration. See <a href="%s">Theme Integration Guide</a> to enable product listing pages.', 'ecommerce-product-catalog' ), 'https://implecode.com/wordpress/product-catalog/theme-integration-guide/#cam=simple-mode&key=front-labels' ) );
						} else {
							implecode_warning( sprintf( __( 'Product listing pages are disabled due to a lack of theme integration.%s', 'ecommerce-product-catalog' ), sample_product_button( 'p' ) ) );
						}
					}
					?>
					<h3><?php _e( 'Product Listing Labels', 'ecommerce-product-catalog' ); ?></h3>
					<table class="wp-list-table widefat product-settings-table" style="clear:right; text-align: left; width: 100%;">
						<style>.names-product-settings .setting-content th {text-align: left;}</style>
						<thead><th><strong><?php _e( 'Front-end Element', 'ecommerce-product-catalog' ); ?></strong></th><th style="width:69%"><strong><?php _e( 'Front-end Text', 'ecommerce-product-catalog' ); ?></strong></th></thead>
						<tbody>
							<?php
							implecode_settings_text( __( 'Product Archive Title', 'ecommerce-product-catalog' ), 'archive_names[all_products]', $archive_names[ 'all_products' ], null, 1, 'wide', null, $disabled );
							implecode_settings_text( __( 'Categories Header', 'ecommerce-product-catalog' ), 'archive_names[all_main_categories]', $archive_names[ 'all_main_categories' ], null, 1, 'wide', null, $disabled );
							implecode_settings_text( __( 'Subcategories Header', 'ecommerce-product-catalog' ), 'archive_names[all_subcategories]', $archive_names[ 'all_subcategories' ], null, 1, 'wide', null, $disabled );
							implecode_settings_text( __( 'Category Prefix', 'ecommerce-product-catalog' ), 'archive_names[all_prefix]', $archive_names[ 'all_prefix' ], null, 1, 'wide', null, $disabled );
							implecode_settings_text( __( 'Category Products Header', 'ecommerce-product-catalog' ), 'archive_names[category_products]', $archive_names[ 'category_products' ], null, 1, 'wide', null, $disabled );
							implecode_settings_text( __( 'Next Page Link', 'ecommerce-product-catalog' ), 'archive_names[next_products]', $archive_names[ 'next_products' ], null, 1, 'wide', null, $disabled );
							implecode_settings_text( __( 'Previous Page Link', 'ecommerce-product-catalog' ), 'archive_names[previous_products]', $archive_names[ 'previous_products' ], null, 1, 'wide', null, $disabled );
							?>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" <?php echo $disabled ?> class="button-primary" value="<?php _e( 'Save changes', 'ecommerce-product-catalog' ); ?>" />
					</p>
				</form>
			</div>
			<div class="helpers"><div class="wrapper"><?php main_helper(); ?>
				</div></div><?php } do_action( 'names-settings' );
						?>
	</div><?php
}

function get_archive_names() {
	$default_archive_names	 = default_archive_names();
	$archive_names			 = wp_parse_args( get_option( 'archive_names' ), $default_archive_names );
	return $archive_names;
}

function get_single_names() {
	$default_single_names	 = default_single_names();
	$single_names			 = get_option( 'single_names', $default_single_names );
	foreach ( $default_single_names as $key => $value ) {
		$single_names[ $key ] = isset( $single_names[ $key ] ) ? $single_names[ $key ] : $value;
	}
	return $single_names;
}

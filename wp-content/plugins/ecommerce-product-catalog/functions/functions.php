<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manages product functions
 *
 * Here all plugin functions are defined and managed.
 *
 * @version        1.0.0
 * @package        ecommerce-product-catalog/functions
 * @author        Norbert Dreszer
 */

/**
 * Returns default product image
 *
 * @return string
 */
function default_product_thumbnail() {
	if ( get_option( 'default_product_thumbnail' ) ) {
		$url = get_option( 'default_product_thumbnail' );
	} else {
		$product_id = get_the_ID();
		if ( $product_id == sample_product_id() ) {
			$url = AL_PLUGIN_BASE_PATH . 'img/implecode.jpg';
		} else {
			$url = AL_PLUGIN_BASE_PATH . 'img/no-default-thumbnail.png';
		}
	}

	return '<img src="' . $url . '"  />';
}

/**
 * Returns default product image URL
 *
 * @return string
 */
function default_product_thumbnail_url() {
	if ( get_option( 'default_product_thumbnail' ) ) {
		$url = get_option( 'default_product_thumbnail' );
	} else {
		$url = AL_PLUGIN_BASE_PATH . 'img/no-default-thumbnail.png';
	}

	return $url;
}

//add_action( 'wp', 'redirect_listing_on_non_permalink' );

/**
 * Redirects the product listing page to archive page on non permalink configuration
 *
 */
function redirect_listing_on_non_permalink() {
	if ( !is_ic_permalink_product_catalog() && get_integration_type() == 'advanced' ) {
		$product_listing_id = get_product_listing_id();
		if ( !empty( $product_listing_id ) && is_ic_product_listing_enabled() && is_page( $product_listing_id ) ) {
			$url = product_listing_url();
			wp_redirect( $url, 301 );
			exit;
		}
	}
}

function upload_product_image( $name, $button_value, $option_name, $option_value = null, $default_image = null ) {
	wp_enqueue_media();
	if ( empty( $option_value ) ) {
		$option_value = get_option( $option_name );
	}
	if ( empty( $default_image ) ) {
		$default_image = AL_PLUGIN_BASE_PATH . 'img/no-default-thumbnail.png';
	}
	if ( $option_value ) {
		$src = $option_value;
	} else {
		$src = $default_image;
	}
	?>
	<div class="custom-uploader">
		<input type="hidden" id="default" value="<?php echo $default_image; ?>"/>
		<input type="hidden" name="<?php echo $option_name; ?>" id="<?php echo $name; ?>"
			   value="<?php echo $option_value; ?>"/>

		<div class="admin-media-image"><img class="media-image" src="<?php echo $src; ?>" width="100%" height="100%"/>
		</div>
		<a href="#" class="button insert-media add_media" name="<?php echo $name; ?>_button"
		   id="button_<?php echo $name; ?>"><span class="wp-media-buttons-icon"></span> <?php echo $button_value; ?></a>
		<a class="button" id="reset-image-button"
		   href="#"><?php _e( 'Reset image', 'al-ecommerce-product-catalog' ); ?></a>
	</div>
	<script>
	    jQuery( document ).ready( function () {
	        jQuery( '#button_<?php echo $name; ?>' ).on( 'click', function () {
	            wp.media.editor.send.attachment = function ( props, attachment ) {
	                jQuery( '#<?php echo $name; ?>' ).val( attachment.url );
	                jQuery( '.media-image' ).attr( "src", attachment.url );
	            }

	            wp.media.editor.open( this );

	            return false;
	        } );
	    } );

	    jQuery( '#reset-image-button' ).on( 'click', function () {
	        jQuery( '#<?php echo $name; ?>' ).val( '' );
	        src = jQuery( '#default' ).val();
	        jQuery( '.media-image' ).attr( "src", src );
	    } );
	</script>
	<?php
}

if ( !function_exists( 'select_page' ) ) {

	function select_page( $option_name, $first_option, $selected_value, $buttons = false, $custom_view_url = false,
					   $echo = 1, $custom = false ) {
		$args		 = array(
			'sort_order'	 => 'ASC',
			'sort_column'	 => 'post_title',
			'hierarchical'	 => 1,
			'exclude'		 => '',
			'include'		 => '',
			'meta_key'		 => '',
			'meta_value'	 => '',
			'authors'		 => '',
			'child_of'		 => 0,
			'parent'		 => -1,
			'exclude_tree'	 => '',
			'number'		 => '',
			'offset'		 => 0,
			'post_type'		 => 'page',
			'post_status'	 => 'publish'
		);
		$pages		 = get_pages( $args );
		$select_box	 = '<div class="select-page-wrapper"><select id="' . $option_name . '" name="' . $option_name . '"><option value="noid">' . $first_option . '</option>';
		foreach ( $pages as $page ) {
			$select_box .= '<option name="' . $option_name . '[' . $page->ID . ']" value="' . $page->ID . '" ' . selected( $page->ID, $selected_value, 0 ) . '>' . $page->post_title . '</option>';
		}
		if ( $custom ) {
			$select_box .= '<option value="custom"' . selected( 'custom', $selected_value, 0 ) . '>' . __( 'Custom URL', 'al-ecommerce-product-catalog' ) . '</option>';
		}
		$select_box .= '</select>';
		if ( $buttons && ($selected_value != 'noid' || $custom_view_url != '') ) {
			$edit_link	 = get_edit_post_link( $selected_value );
			$front_link	 = $custom_view_url ? $custom_view_url : get_permalink( $selected_value );
			if ( !empty( $edit_link ) ) {
				$select_box .= ' <a class="button button-small" style="vertical-align: middle;" href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
			}
			if ( !empty( $front_link ) ) {
				$select_box .= ' <a class="button button-small" style="vertical-align: middle;" href="' . $front_link . '">' . __( 'View Page' ) . '</a>';
			}
		}
		$select_box .= '</div>';
		return echo_ic_setting( $select_box, $echo );
	}

}

function show_page_link( $page_id ) {
	$page_url	 = post_permalink( $page_id );
	$page_link	 = '<a target="_blank" href=' . $page_url . '>' . $page_url . '</a>';
	echo $page_link;
}

function verify_page_status( $page_id ) {
	$page_status = get_post_status( $page_id );
	if ( $page_status != 'publish' AND $page_status != '' ) {
		echo '<div class="al-box warning">This page has wrong status: ' . $page_status . '.<br>Don\'t forget to publish it before going live!</div>';
	}
}

/**
 *
 * @param string $which color, size, box or none
 * @param int $echo
 * @return string
 */
function design_schemes( $which = null, $echo = 1 ) {
	$custom_design_schemes	 = unserialize( DEFAULT_DESIGN_SCHEMES );
	$design_schemes			 = get_option( 'design_schemes', $custom_design_schemes );
	if ( $which == 'color' ) {
		$output = $design_schemes[ 'price-color' ];
	} else if ( $which == 'size' ) {
		$output = $design_schemes[ 'price-size' ];
	} else if ( $which == 'box' ) {
		$output = $design_schemes[ 'box-color' ];
	} else if ( $which == 'none' ) {
		$output = '';
	} else {
		$output = $design_schemes[ 'price-color' ] . ' ' . $design_schemes[ 'price-size' ];
	}
	return echo_ic_setting( apply_filters( 'design_schemes_output', $output ), $echo );
}

/* Single Product Functions */
add_action( 'before_product_entry', 'single_product_header', 10, 2 );

/**
 * Displays header on product pages
 *
 * @param object $post
 * @param array $single_names
 */
function single_product_header( $post, $single_names ) {
	if ( get_integration_type() != 'simple' ) {
		?>
		<header class="entry-header product-page-header">
			<?php do_action( 'single_product_header', $post, $single_names ); ?>
		</header><?php
	}
}

add_action( 'single_product_header', 'add_product_name' );

/**
 * Shows product name on product page
 */
function add_product_name() {
	if ( is_ic_product_name_enabled() ) {
		echo '<h1 class="entry-title product-name">' . get_the_title() . '</h1>';
	}
}

add_action( 'before_product_listing_entry', 'product_listing_header', 10, 2 );

/**
 * Shows product listing header
 *
 * @param object $post
 * @param array $archive_names
 */
function product_listing_header( $post, $archive_names ) {
	if ( get_integration_type() != 'simple' ) {
		?>
		<header class="entry-header product-listing-header">
			<?php do_action( 'product_listing_header', $post, $archive_names ); ?>
		</header><?php
	}
}

add_action( 'product_listing_header', 'add_product_listing_name' );

/**
 * Shows product listing title tag
 */
function add_product_listing_name() {
	if ( is_ic_taxonomy_page() ) {
		$archive_names	 = get_archive_names();
		//$the_tax		 = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$the_tax		 = get_queried_object();
		if ( !empty( $archive_names[ 'all_prefix' ] ) ) {
			$title = $archive_names[ 'all_prefix' ] . ' ' . $the_tax->name;
		} else {
			$title = $the_tax->name;
		}
	} else if ( is_ic_product_search() ) {
		$title = __( 'Search Results for:', 'al-ecommerce-product-catalog' ) . ' ' . $_GET[ 's' ];
	} else if ( is_ic_product_listing() ) {
		$title = get_product_listing_title();
	} else {
		$title = get_the_title();
	}
	echo '<h1 class="entry-title product-listing-name">' . $title . '</h1>';
}

function example_price() {
	echo '2500.00 EUR';
}

add_action( 'example_price', 'example_price' );
add_action( 'product_details', 'show_price', 7, 2 );

/**
 * Shows price on product page
 *
 * @param type $post
 * @param type $single_names
 */
function show_price( $post, $single_names ) {
	echo get_product_price_table( $post->ID, $single_names );
}

/**
 * Returns price table for product page
 * @param type $product_id
 * @param type $single_names
 * @return type
 */
function get_product_price_table( $product_id, $single_names ) {
	$price_value = product_price( $product_id );
	$table		 = '';
	if ( !empty( $price_value ) ) {
		$table .= '<table class="price-table">';
		$table .= '<tr>';
		$table .= '<td class="price-label">' . $single_names[ 'product_price' ] . '</td>';
		$table .= '<td class="price-value ' . design_schemes( null, 0 ) . '">' . price_format( $price_value ) . '</td>';
		$table .= '</tr>';
		ob_start();
		do_action( 'price_table' );
		$table .= ob_get_clean();
		$table .= '</table>';
		ob_start();
		do_action( 'after_price_table' );
		$table .= ob_get_contents();
	}
	return $table;
}

add_action( 'product_details', 'show_sku', 8, 2 );

/**
 * Shows product SKU table
 *
 * @param object $post
 * @param array $single_names
 */
function show_sku( $post, $single_names ) {
	echo get_product_sku_table( $post->ID, $single_names );
}

/**
 * Returns sku table for product page
 *
 * @param int $product_id
 * @param array $single_names
 * @return string
 */
function get_product_sku_table( $product_id, $single_names ) {
	$sku_value	 = get_product_sku( $product_id );
	$table		 = '';
	if ( is_ic_sku_enabled() && !empty( $sku_value ) ) {
		$table .= '<table class="sku-table">';
		$table .= '<tr>';
		$table .= '<td>' . $single_names[ 'product_sku' ] . '</td>';
		$table .= '<td class="sku-value">' . $sku_value . '</td>';
		$table .= '</tr>';
		$table .= '</table>';
	}
	return $table;
}

/**
 * Returns SKU
 *
 * @param int $product_id
 * @return string
 */
function get_product_sku( $product_id ) {
	$sku = get_post_meta( $product_id, '_sku', true );
	return $sku;
}

/**
 * Returns product price
 *
 * @param int $product_id
 * @param string $unfiltered Assign any value to return the original price (without any modifications)
 * @return string
 */
function product_price( $product_id, $unfiltered = null ) {
	if ( empty( $unfiltered ) ) {
		$price_value = apply_filters( 'product_price', get_post_meta( $product_id, "_price", true ), $product_id );
	} else {
		$price_value = apply_filters( 'unfiltered_product_price', get_post_meta( $product_id, "_price", true ), $product_id );
	}
	$price_value = (is_ic_price_enabled()) ? $price_value : '';
	return $price_value;
}

/**
 * Returns product currency
 *
 * @return string
 */
function product_currency() {
	$product_currency			 = get_option( 'product_currency', DEF_CURRENCY );
	$product_currency_settings	 = get_option( 'product_currency_settings', unserialize( DEF_CURRENCY_SETTINGS ) );
	if ( !empty( $product_currency_settings[ 'custom_symbol' ] ) ) {
		$currency = $product_currency_settings[ 'custom_symbol' ];
	} else {
		$currency = $product_currency;
	}
	return $currency;
}

function get_shipping_options_number() {
	return get_option( 'product_shipping_options_number', 1 );
}

function get_shipping_options( $product_id ) {
	$shipping_options	 = get_shipping_options_number();
	$shipping_values	 = array();
	for ( $i = 1; $i <= $shipping_options; $i++ ) {
		$sh_val = get_post_meta( $product_id, "_shipping" . $i, true );
		if ( $sh_val != null ) {
			$any_shipping_value = $sh_val;
		}
		$shipping_values[ $i ] = $sh_val;
	}
	if ( !isset( $any_shipping_value ) ) {
		$shipping_values = 'none';
	}
	return apply_filters( 'product_shipping_values', $shipping_values );
}

function get_shipping_label( $i = 1, $product_id ) {
	$label	 = get_post_meta( $product_id, "_shipping-label" . $i, true );
	$label	 = empty( $label ) ? __( 'Shipping', 'al-ecommerce-product-catalog' ) : $label;
	return $label;
}

add_action( 'product_details', 'show_shipping_options', 9, 2 );

/**
 * Shows shipping table
 *
 * @param object $post
 * @param array $single_names
 */
function show_shipping_options( $post, $single_names ) {
	echo get_shipping_options_table( $post->ID, $single_names );
}

/**
 * Returns shipping options table
 *
 * @param int $product_id
 * @param array $v_single_names
 * @return string
 */
function get_shipping_options_table( $product_id, $v_single_names = null ) {
	$single_names	 = isset( $v_single_names ) ? $v_single_names : get_single_names();
	$shipping_values = get_shipping_options( $product_id );
	$table			 = '';
	if ( $shipping_values != 'none' ) {
		$table .= '<table class="shipping-table">';
		$table .= '<tr>';
		$table .= '<td>';
		$table .= $single_names[ 'product_shipping' ];
		$table .= '</td>';
		$table .= '<td>';
		$table .= '<ul>';
		foreach ( $shipping_values as $i => $shipping_value ) {
			if ( $shipping_value != null ) {
				$table .= '<li>' . get_shipping_label( $i, $product_id ) . ' : ' . price_format( $shipping_value ) . '</li>';
			}
		}
		$table .= '</ul>';
		$table .= '</td>';
		$table .= '</tr>';
		$table .= '</table>';
	}
	return $table;
}

function show_short_desc( $post, $single_names ) {
	$shortdesc = get_product_short_description( $post->ID );
	?>
	<div class="shortdesc">
		<?php echo apply_filters( 'product_short_description', $shortdesc ); ?>
	</div>
	<?php
}

add_action( 'product_details', 'show_short_desc', 5, 2 );
add_filter( 'product_short_description', 'wptexturize' );
add_filter( 'product_short_description', 'convert_smilies' );
add_filter( 'product_short_description', 'convert_chars' );
add_filter( 'product_short_description', 'wpautop' );
add_filter( 'product_short_description', 'shortcode_unautop' );
add_filter( 'product_short_description', 'do_shortcode', 11 );
add_action( 'after_product_details', 'show_product_attributes', 10, 2 );

/**
 * Shows product attributes table on product page
 *
 * @param object $post
 * @param array $single_names
 */
function show_product_attributes( $post, $single_names ) {
	echo get_product_attributes( $post->ID, $single_names );
}

/**
 * Returns product attributes table
 *
 * @param int $product_id
 * @param array $v_single_names
 * @return string
 */
function get_product_attributes( $product_id, $v_single_names = null ) {
	$single_names		 = isset( $v_single_names ) ? $v_single_names : get_single_names();
	$attributes_number	 = product_attributes_number();
	$at_val				 = '';
	$any_attribute_value = '';
	for ( $i = 1; $i <= $attributes_number; $i++ ) {
		$at_val = get_post_meta( $product_id, "_attribute" . $i, true );
		if ( !empty( $at_val ) ) {
			$any_attribute_value = $at_val;
		}
	}
	$table = '';
	if ( $attributes_number > 0 && !empty( $any_attribute_value ) ) {
		$table .= '<div id="product_features" class="product-features">';
		$table .= '<h3>' . $single_names[ 'product_features' ] . '</h3>';
		$table .= '<table class="features-table">';
		for ( $i = 1; $i <= $attributes_number; $i++ ) {
			$attribute_value = get_post_meta( $product_id, "_attribute" . $i, true );
			if ( !empty( $attribute_value ) ) {
				$table .= '<tr><td class="attribute-label-single">' . get_post_meta( $product_id, "_attribute-label" . $i, true ) . '</td><td>' . get_post_meta( $product_id, "_attribute" . $i, true ) . ' ' . get_post_meta( $product_id, "_attribute-unit" . $i, true ) . '</td></tr>';
			}
		}
		$table .= '</table>';
		$table .= '</div>';
	}
	return $table;
}

function show_product_description( $post, $single_names ) {
	$product_description = get_product_description( $post->ID );
	if ( !empty( $product_description ) ) {
		?>
		<div class="product-description"><?php
			if ( get_integration_type() == 'simple' ) {
				echo apply_filters( 'product_simple_description', $product_description );
			} else {
				echo apply_filters( 'the_content', $product_description );
			}
			?>
		</div>
		<?php
	}
}

add_action( 'after_product_details', 'show_product_description', 10, 2 );
add_filter( 'product_simple_description', 'wptexturize' );
add_filter( 'product_simple_description', 'convert_smilies' );
add_filter( 'product_simple_description', 'convert_chars' );
add_filter( 'product_simple_description', 'wpautop' );
add_filter( 'product_simple_description', 'shortcode_unautop' );

//add_filter('product_simple_description', 'do_shortcode', 11);
add_action( 'single_product_end', 'show_related_categories', 10, 3 );

/**
 * Shows related categories table on product page
 *
 * @param object $post
 * @param array $single_names
 * @param string $taxonomy_name
 * @return string
 */
function show_related_categories( $post, $single_names, $taxonomy_name ) {
	$settings = get_multiple_settings();
	if ( $settings[ 'related' ] == 'categories' ) {
		echo get_related_categories( $post->ID, $single_names, $taxonomy_name );
	} else {
		echo get_related_products( null, true );
	}
}

/**
 * Returns related categories table
 *
 * @param int $product_id
 * @param array $v_single_names
 * @param string $taxonomy_name
 * @return string
 */
function get_related_categories( $product_id, $v_single_names = null, $taxonomy_name = 'al_product-cat' ) {
	$single_names	 = isset( $v_single_names ) ? $v_single_names : get_single_names();
	$terms			 = wp_get_post_terms( $product_id, $taxonomy_name, array( "fields" => "ids" ) );
	if ( empty( $terms ) || is_wp_error( $terms ) || get_integration_type() == 'simple' ) {
		return;
	}
	$term		 = $terms[ 0 ];
	$categories	 = wp_list_categories( 'title_li=&taxonomy=' . $taxonomy_name . '&include=' . $term . '&echo=0&hierarchical=0' );
	$table		 = '';
	if ( $categories != '<li class="cat-item-none">No categories</li>' ) {
		$table .= '<div id="product_subcategories" class="product-subcategories">';
		$table .= '<table>';
		$table .= '<tr>';
		$table .= '<td>';
		$table .= $single_names[ 'other_categories' ];
		$table .= '</td>';
		$table .= '<td>';
		$table .= $categories;
		$table .= '</td>';
		$table .= '</tr>';
		$table .= '</table>';
		$table .= '</div>';
		return $table;
	}
	return;
}

add_filter( 'the_content', 'show_simple_product_listing' );

/**
 * Shows product listing in simple mode if no shortcode exists.
 *
 * @param string $content
 * @return string
 */
function show_simple_product_listing( $content ) {
	if ( is_main_query() && in_the_loop() && get_integration_type() == 'simple' && is_ic_product_listing() && is_ic_product_listing_enabled() ) {
		if ( !has_shortcode( $content, 'show_products' ) ) {
			$archive_multiple_settings = get_multiple_settings();
			$content .= do_shortcode( '[show_products products_limit="' . $archive_multiple_settings[ 'archive_products_limit' ] . '"]' );
		}
	}
	return $content;
}

/* Archive Functions */

function show_archive_price( $post ) {
	$price_value = product_price( $post->ID );
	if ( !empty( $price_value ) ) {
		?>
		<div class="product-price <?php design_schemes( 'color' ); ?>">
			<?php echo price_format( $price_value ) ?>
		</div>
		<?php
	}
}

add_action( 'archive_price', 'show_archive_price', 10, 1 );

function set_archive_price( $archive_price, $post ) {
	$price_value = product_price( $post->ID );
	if ( !empty( $price_value ) ) {
		$archive_price = '<span class="product-price ' . design_schemes( 'color', 0 ) . '">';
		$archive_price .= price_format( $price_value );
		$archive_price .= '</span>';
	}
	return $archive_price;
}

add_filter( 'archive_price_filter', 'set_archive_price', 10, 2 );

function get_quasi_post_type( $post_type = null ) {
	if ( empty( $post_type ) && is_home_archive() ) {
		$post_type = 'al_product';
	} else if ( empty( $post_type ) ) {
		$post_type = get_post_type();
	}
	$quasi_post_type = substr( $post_type, 0, 10 );
	return $quasi_post_type;
}

function get_quasi_post_tax_name( $tax_name, $exact = true ) {
	if ( $exact ) {
		$quasi_tax_name = substr( $tax_name, 0, 14 );
	} else if ( strpos( $tax_name, 'al_product-cat' ) !== false ) {
		$quasi_tax_name = 'al_product-cat';
	}
	return $quasi_tax_name;
}

function product_breadcrumbs() {
	if ( get_integration_type() != 'simple' && !is_front_page() ) {
		global $post;
		$post_type	 = get_post_type();
		$home_page	 = get_home_url();
		if ( function_exists( 'additional_product_listing_url' ) && $post_type != 'al_product' ) {
			$catalog_id			 = catalog_id( $post_type );
			$product_archives	 = additional_product_listing_url();
			$product_archive	 = $product_archives[ $catalog_id ];
			$archives_ids		 = get_option( 'additional_product_archive_id' );
			$breadcrumbs_options = get_option( 'product_breadcrumbs', unserialize( DEFAULT_PRODUCT_BREADCRUMBS ) );
			if ( empty( $breadcrumbs_options[ 'enable_product_breadcrumbs' ][ $catalog_id ] ) || !empty( $breadcrumbs_options[ 'enable_product_breadcrumbs' ][ $catalog_id ] ) && $breadcrumbs_options[ 'enable_product_breadcrumbs' ][ $catalog_id ] != 1 ) {
				return;
			}
			$product_archive_title_options = $breadcrumbs_options[ 'breadcrumbs_title' ][ $catalog_id ];
			if ( $product_archive_title_options != '' ) {
				$product_archive_title = $product_archive_title_options;
			} else {
				$product_archive_title = get_the_title( $archives_ids[ $catalog_id ] );
			}
		} else {
			$archive_multiple_settings = get_multiple_settings();
			if ( empty( $archive_multiple_settings[ 'enable_product_breadcrumbs' ] ) || !empty( $archive_multiple_settings[ 'enable_product_breadcrumbs' ] ) && $archive_multiple_settings[ 'enable_product_breadcrumbs' ] != 1 ) {
				return;
			}

			$product_archive = product_listing_url();
			if ( $archive_multiple_settings[ 'breadcrumbs_title' ] != '' ) {
				$product_archive_title = $archive_multiple_settings[ 'breadcrumbs_title' ];
			} else {
				$product_archive_title = get_product_listing_title();
			}
		}
		$additional = '';
		if ( is_ic_product_page() ) {
			$current_product = get_the_title();
		} else if ( is_ic_taxonomy_page() ) {
			$obj				 = get_queried_object();
			$current_product	 = $obj->name;
			$taxonomy			 = isset( $obj->taxonomy ) ? $obj->taxonomy : 'al_product-cat';
			$current_category_id = $obj->term_id;
			$parents			 = array_filter( explode( '|', ic_get_product_category_parents( $current_category_id, $taxonomy, true, '|' ) ) );
			array_pop( $parents );
			foreach ( $parents as $parent ) {
				if ( !empty( $parent ) ) {
					$additional .= ' » <span typeof="v:Breadcrumb">
		<span class="breadcrumb_last" property="v:title">' . $parent . '</span>
	</span>';
				}
			}
		} else if ( is_search() ) {
			$current_product = __( 'Product Search', 'al-ecommerce-product-catalog' );
		} else {
			$current_product = '';
		}
		$bread = '<p id="breadcrumbs"><span xmlns:v="http://rdf.data-vocabulary.org/#"><span typeof="v:Breadcrumb"><a href="' . $home_page . '" rel="v:url" property="v:title">' . __( 'Home', 'al-ecommerce-product-catalog' ) . '</a></span>';
		if ( !empty( $product_archive ) ) {
			$bread .= ' » <span typeof="v:Breadcrumb"><a href="' . $product_archive . '" rel="v:url" property="v:title">' . $product_archive_title . '</a></span>';
		}
		if ( !empty( $additional ) ) {
			$bread .= $additional;
		}
		if ( !empty( $current_product ) ) {
			$bread .= ' » <span typeof="v:Breadcrumb"><span class="breadcrumb_last" property="v:title">' . $current_product . '</span></span></span>';
		}
		$bread .= '</p>';
		return $bread;
	}
}

function ic_get_product_category_parents( $id, $taxonomy, $link = false, $separator = '/', $nicename = false,
										  $visited = array() ) {
	$chain	 = '';
	$parent	 = get_term( $id, $taxonomy );

	if ( is_wp_error( $parent ) ) {
		return $parent;
	}

	if ( $nicename )
		$name	 = $parent->slug;
	else
		$name	 = $parent->name;

	if ( $parent->parent && ($parent->parent != $parent->term_id) && !in_array( $parent->parent, $visited ) ) {
		$visited[] = $parent->parent;
		$chain .= ic_get_product_category_parents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
	}

	if ( !$link ) {
		$chain .= $name . $separator;
	} else {
		$url = get_term_link( $parent );
		$chain .= '<a href="' . $url . '">' . $name . '</a>' . $separator;
	}
	return $chain;
}

function get_product_name( $product_id = null ) {
	return get_the_title( $product_id );
}

function get_product_url( $product_id = null ) {
	return get_permalink( $product_id );
}

add_action( 'single_product_begin', 'add_product_breadcrumbs' );
add_action( 'product_listing_begin', 'add_product_breadcrumbs' );

/**
 * Shows product breadcrumbs
 *
 */
function add_product_breadcrumbs() {
	echo product_breadcrumbs();
}

function al_product_register_widgets() {
	register_widget( 'product_cat_widget' );
	register_widget( 'product_widget_search' );
	do_action( 'implecode_register_widgets' );
}

add_action( 'widgets_init', 'al_product_register_widgets' );

if ( !function_exists( 'permalink_options_update' ) ) {

	/**
	 * Updates the permalink rewrite option that triggers the rewrite function
	 */
	function permalink_options_update() {
		update_option( 'al_permalink_options_update', 1 );
	}

}
if ( !function_exists( 'check_permalink_options_update' ) ) {

	/**
	 * Checks if the permalinks should be rewritten and does it if necessary
	 */
	function check_permalink_options_update() {
		$options_update = get_option( 'al_permalink_options_update', 'none' );
		if ( $options_update != 'none' ) {
			flush_rewrite_rules();
			update_option( 'al_permalink_options_update', 'none' );
		}
	}

}

add_action( 'init', 'check_permalink_options_update', 99 );

function is_lightbox_enabled() {
	$enable_catalog_lightbox = get_option( 'catalog_lightbox', 1 );
	$return					 = false;
	if ( $enable_catalog_lightbox == 1 ) {
		$return = true;
	}
	return apply_filters( 'is_lightbox_enabled', $return );
}

add_action( 'before_product_details', 'show_product_gallery', 10, 2 );

/**
 * Shows product gallery on product page
 *
 * @param int $product_id
 * @param array $single_options
 * @return string
 */
function show_product_gallery( $product_id, $single_options ) {
	if ( $single_options[ 'enable_product_gallery' ] == 1 ) {
		echo get_product_gallery( $product_id, $single_options );
	} else {
		return;
	}
}

/**
 * Returns whole product gallery for product page
 *
 * @param int $product_id
 * @param array $v_single_options
 * @return string
 */
function get_product_gallery( $product_id, $v_single_options = null ) {
	$single_options = isset( $v_single_options ) ? $v_single_options : get_product_page_settings();
	if ( $single_options[ 'enable_product_gallery' ] == 1 ) {
		$product_gallery = '';
		ob_start();
		do_action( 'before_product_image', $product_id );
		$product_gallery .= ob_get_clean();
		$product_gallery .= '<div class="entry-thumbnail product-image">';
		ob_start();
		do_action( 'above_product_image', $product_id );
		$product_gallery .= ob_get_clean();
		$image_size		 = apply_filters( 'product_image_size', 'medium' );
		if ( has_post_thumbnail( $product_id ) ) {
			if ( is_lightbox_enabled() ) {
				$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'large' );
				$product_gallery .= '<a class="a-product-image" href="' . $img_url[ 0 ] . '">' . get_the_post_thumbnail( $product_id, $image_size ) . '</a>';
			} else {
				$product_gallery .= get_the_post_thumbnail( $product_id, $image_size );
			}
		} else if ( $single_options[ 'enable_product_gallery_only_when_exist' ] != 1 ) {
			$product_gallery .= default_product_thumbnail();
		}
		ob_start();
		do_action( 'below_product_image', $product_id );
		$product_gallery .= ob_get_clean();
		$product_gallery .= '</div>';
		ob_start();
		do_action( 'after_product_image', $product_id );
		$product_gallery .= ob_get_clean();
		return $product_gallery;
	} else {
		return;
	}
}

function product_gallery_enabled( $enable, $enable_inserted, $post ) {
	$details_class = 'no-image';
	if ( $enable == 1 ) {
		if ( $enable_inserted == 1 && !has_post_thumbnail() ) {
			return $details_class;
		} else {
			return;
		}
	} else {
		return $details_class;
	}
}

function product_post_type_array() {
	$array = apply_filters( 'product_post_type_array', array( 'al_product' ) );
	return $array;
}

function product_taxonomy_array() {
	$array = apply_filters( 'product_taxonomy_array', array( 'al_product-cat' ) );
	return $array;
}

function array_to_url( $array ) {
	$url = urlencode( serialize( $array ) );
	return $url;
}

function url_to_array( $url ) {
	$array = unserialize( stripslashes( urldecode( $url ) ) );
	return $array;
}

function exclude_products_search( $search, &$wp_query ) {
	global $wpdb;
	if ( empty( $search ) )
		return $search;
	$search .= " AND (($wpdb->posts.post_type NOT LIKE '%al_product%'))";
	return $search;
}

function modify_product_search( $query ) {
	if ( !is_admin() && $query->is_search == 1 && $query->is_main_query() && ((isset( $_GET[ 'post_type' ] ) && strpos( $_GET[ 'post_type' ], 'al_product' ) === false) || (!isset( $query->query_vars[ 'post_type' ] ) || (isset( $query->query_vars[ 'post_type' ] ) && strpos( $query->query_vars[ 'post_type' ], 'al_product' ) === false ))) ) {
		add_filter( 'posts_search', 'exclude_products_search', 10, 2 );
	}
}

add_action( 'pre_get_posts', 'modify_product_search', 10, 1 );
add_action( 'wp', 'modify_product_listing_title_tag', 99 );

function modify_product_listing_title_tag() {
	if ( is_ic_product_listing() ) {
		add_filter( 'wp_title', 'product_archive_title', 99, 3 );
		add_filter( 'wp_title', 'product_archive_custom_title', 99, 3 );
	}
}

/**
 * Modifies main product listing title tag
 *
 * @global type $post
 * @param type $title
 * @param type $sep
 * @param type $seplocation
 * @return type
 */
function product_archive_custom_title( $title = null, $sep = null, $seplocation = null ) {
	global $post;
	if ( is_post_type_archive( 'al_product' ) && is_object( $post ) && $post->post_type == 'al_product' ) {
		$settings = get_multiple_settings();
		if ( $settings[ 'seo_title' ] != '' ) {
			$settings					 = get_option( 'archive_multiple_settings', unserialize( DEFAULT_ARCHIVE_MULTIPLE_SETTINGS ) );
			$settings[ 'seo_title' ]	 = isset( $settings[ 'seo_title' ] ) ? $settings[ 'seo_title' ] : '';
			$settings[ 'seo_title_sep' ] = isset( $settings[ 'seo_title_sep' ] ) ? $settings[ 'seo_title_sep' ] : '';
			if ( $settings[ 'seo_title_sep' ] == 1 ) {
				if ( $sep != '' ) {
					$sep = ' ' . $sep . ' ';
				}
			} else {
				$sep = '';
			}
			if ( $seplocation == 'right' ) {
				$title = $settings[ 'seo_title' ] . $sep;
			} else {
				$title = $sep . $settings[ 'seo_title' ];
			}
		}
	}
	return $title;
}

function product_archive_title( $title = null, $sep = null, $seplocation = null ) {
	global $post;
	if ( is_ic_product_listing() && is_object( $post ) && $post->post_type == 'al_product' ) {
		$settings = get_multiple_settings();
		if ( $settings[ 'seo_title' ] == '' ) {
			$id = get_product_listing_id();
			if ( !empty( $id ) ) {
				$title = get_single_post_title( $id, $sep, $seplocation );
			}
		}
	}
	return $title;
}

function get_single_post_title( $post_id, $sep, $seplocation ) {
	global $wp_query;
	$wp_query	 = new WP_Query( 'page_id=' . $post_id );
	remove_filter( 'wp_title', 'product_archive_title', 99, 3 );
	$title		 = wp_title( $sep, false, $seplocation );
	wp_reset_query();
	return $title;
}

function add_support_link( $links, $file ) {

	$plugin = plugin_basename( AL_PLUGIN_MAIN_FILE );

	// create link
	if ( $file == $plugin ) {
		return array_merge(
		$links, array( sprintf( '<a href="edit.php?post_type=al_product&page=product-settings.php&tab=product-settings&submenu=support">%s</a>', __( 'Support' ) ) )
		);
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'add_support_link', 10, 2 );

function implecode_al_box( $text, $type = 'info' ) {
	echo '<div class="al-box ' . $type . '">';
	echo $text;
	echo '</div>';
}

function get_product_image_id( $attachment_url = '' ) {
	global $wpdb;
	$attachment_id = false;
	if ( '' == $attachment_url ) {
		return;
	}
	$upload_dir_paths = wp_upload_dir();
	if ( false !== strpos( $attachment_url, $upload_dir_paths[ 'baseurl' ] ) ) {
		$attachment_url	 = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
		$attachment_url	 = str_replace( $upload_dir_paths[ 'baseurl' ] . '/', '', $attachment_url );
		$attachment_id	 = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	}
	return $attachment_id;
}

function get_product_image_url( $product_id ) {
	$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'large' );
	if ( !$img_url ) {
		$img_url[ 0 ] = default_product_thumbnail_url();
	}
	return $img_url[ 0 ];
}

/**
 * Returns all products array
 * @return array
 */
function get_all_catalog_products( $orderby = null ) {
	$args = array(
		'post_type'		 => product_post_type_array(),
		'post_status'	 => 'publish',
		'posts_per_page' => -1,
	);
	if ( !empty( $orderby ) ) {
		$args[ 'orderby' ] = $orderby;
	}
	$products = get_posts( $args );
	return $products;
}

function all_ctalog_products_dropdown( $option_name, $first_option, $selected_value ) {
	$pages		 = get_all_catalog_products();
	$select_box	 = '<select class="all_products_dropdown" id="' . $option_name . '" name="' . $option_name . '"><option value="noid">' . $first_option . '</option>';
	foreach ( $pages as $page ) {
		$select_box .= '<option class="id_' . $page->ID . '" name="' . $option_name . '[' . $page->ID . ']" value="' . $page->ID . '" ' . selected( $page->ID, $selected_value, 0 ) . '>' . $page->post_title . '</option>';
	}
	$select_box .= '</select>';
	return $select_box;
}

function thumbnail_support_products() {
	$support		 = get_theme_support( 'post-thumbnails' );
	$support_array	 = product_post_type_array();
	if ( is_array( $support ) ) {
		$support_array = array_merge( $support[ 0 ], $support_array );
		add_theme_support( 'post-thumbnails', $support_array );
	} else if ( !$support ) {
		add_theme_support( 'post-thumbnails', $support_array );
	} else {
		add_theme_support( 'post-thumbnails' );
	}
}

add_action( 'after_setup_theme', 'thumbnail_support_products', 99 );
add_action( 'pre_get_posts', 'set_product_order' );

/**
 * Sets default product order
 *
 * @param object $query
 */
function set_product_order( $query ) {
	if ( !is_admin() && !isset( $_GET[ 'order' ] ) && $query->is_main_query() && (is_ic_product_listing( $query ) || is_ic_taxonomy_page()) ) {
		$archive_multiple_settings = get_multiple_settings();
		if ( !isset( $_GET[ 'product_order' ] ) ) {
			if ( $archive_multiple_settings[ 'product_order' ] == 'product-name' ) {
				$query->set( 'orderby', 'title' );
				$query->set( 'order', 'ASC' );
			}
			$query = apply_filters( 'modify_product_order', $query, $archive_multiple_settings );
		} else if ( $_GET[ 'product_order' ] != 'newest' && !empty( $_GET[ 'product_order' ] ) ) {
			$orderby = translate_product_order();
			$query->set( 'orderby', $orderby );
			$query->set( 'order', 'ASC' );
			$query	 = apply_filters( 'modify_product_order-dropdown', $query, $archive_multiple_settings );
		}
	}
}

add_filter( 'shortcode_query', 'set_shortcode_product_order' );
add_filter( 'home_product_listing_query', 'set_shortcode_product_order' );

function set_shortcode_product_order( $shortcode_query ) {
	$archive_multiple_settings = get_multiple_settings();
	if ( !isset( $_GET[ 'product_order' ] ) ) {
		if ( $archive_multiple_settings[ 'product_order' ] == 'product-name' ) {
			$shortcode_query[ 'orderby' ]	 = 'title';
			$shortcode_query[ 'order' ]		 = 'ASC';
		}
		$shortcode_query = apply_filters( 'shortcode_modify_product_order', $shortcode_query, $archive_multiple_settings );
	} else if ( $_GET[ 'product_order' ] != 'newest' && !empty( $_GET[ 'product_order' ] ) ) {
		$orderby						 = translate_product_order();
		$shortcode_query[ 'orderby' ]	 = $orderby;
		$shortcode_query[ 'order' ]		 = 'ASC';
		$shortcode_query				 = apply_filters( 'shortcode_modify_product_order-dropdown', $shortcode_query, $archive_multiple_settings );
	}
	return $shortcode_query;
}

//add_action( 'before_product_list', 'show_product_order_dropdown', 10, 2 );

/**
 * Shows sorting drop down
 *
 * @global string $product_sort
 * @param string $archive_template
 * @param array $multiple_settings
 */
function show_product_order_dropdown( $archive_template = null, $multiple_settings = null ) {
	$multiple_settings	 = empty( $multiple_settings ) ? get_multiple_settings() : $multiple_settings;
	$sort_options		 = get_product_sort_options();
	$selected			 = isset( $_GET[ 'product_order' ] ) ? esc_attr( $_GET[ 'product_order' ] ) : $multiple_settings[ 'product_order' ];
	echo '<form class="product_order"><select class="product_order_selector" name="product_order">';
	foreach ( $sort_options as $name => $value ) {
		$option = '<option value="' . $name . '" ' . selected( $name, $selected, 0 ) . '>' . $value . '</option>';
		echo apply_filters( 'product_order_dropdown_options', $option, $name, $value, $multiple_settings, $selected );
	}
	echo '</select>';
	foreach ( $_GET as $key => $get_value ) {
		if ( $key != 'product_order' ) {
			echo '<input type="hidden" value="' . esc_attr( $get_value ) . '" name="' . esc_attr( $key ) . '" />';
		}
	}
	echo '</form>';
}

add_action( 'before_product_list', 'show_product_sort_bar', 10, 2 );

/**
 * Shows product sort and filters bar
 *
 * @global boolean $is_filter_bar
 * @param string $archive_template
 * @param array $multiple_settings
 */
function show_product_sort_bar( $archive_template = null, $multiple_settings = null ) {
	if ( is_product_sort_bar_active() ) {
		if ( is_active_sidebar( 'product_sort_bar' ) ) {
			global $is_filter_bar;
			$is_filter_bar	 = true;
			echo '<div class="product-sort-bar ' . design_schemes( 'box', 0 ) . '">';
			dynamic_sidebar( 'product_sort_bar' );
			echo '</div>';
			$is_filter_bar	 = false;
			unset( $is_filter_bar );
		} else {
			show_default_product_sort_bar( $archive_template, $multiple_settings = null );
		}
	}
}

/**
 * Shows default product sort bar content
 *
 */
function show_default_product_sort_bar( $archive_template, $multiple_settings = null ) {
	if ( get_option( 'old_sort_bar' ) == 1 ) {
		show_product_order_dropdown( $archive_template, $multiple_settings = null );
	} else if ( current_user_can( 'edit_theme_options' ) ) {
		$show = get_option( 'hide_empty_bar_message', 0 );
		if ( $show == 0 ) {
			global $is_filter_bar;
			$is_filter_bar	 = true;
			echo '<div class="product-sort-bar ' . design_schemes( 'box', 0 ) . '">';
			echo '<div class="empty-filters-info">';
			echo '<h3>' . __( 'Product Filters Bar has no widgets', 'al-ecommerce-product-catalog' ) . '</h3>';
			$current_url	 = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
			$customize_url	 = add_query_arg( array( 'url' => urlencode( $current_url ), urlencode( 'autofocus[panel]' ) => 'widgets' ), wp_customize_url() );
			echo sprintf( __( 'Add widgets to the filters bar %snow%s or %sdismiss this notice%s.', 'al-ecommerce-product-catalog' ), '<a href="' . $customize_url . '">', '</a>', '<a class="dismiss-empty-bar" href="#">', '</a>' );
			echo '</div>';
			echo '</div>';
			unset( $is_filter_bar );
		}
	}
}

function translate_product_order() {
	$orderby = ($_GET[ 'product_order' ] == 'product-name') ? 'title' : $_GET[ 'product_order' ];
	$orderby = apply_filters( 'product_order_translate', $orderby );
	return $orderby;
}

function ic_products_count() {
	$count = wp_count_posts( 'al_product' );
	if ( isset( $count->publish ) ) {
		return $count->publish;
	} else {
		return 0;
	}
}

/**
 * Returns per row setting for current product listing theme
 * @return int
 */
function get_current_per_row() {
	$archive_template	 = get_product_listing_template();
	$per_row			 = 3;
	if ( $archive_template == 'default' ) {
		$settings	 = get_modern_grid_settings();
		$per_row	 = $settings[ 'per-row' ];
	} else if ( $archive_template == 'grid' ) {
		$settings	 = get_classic_grid_settings();
		$per_row	 = $settings[ 'entries' ];
	}
	return apply_filters( 'current_per_row', $per_row, $archive_template );
}

function get_current_screen_tax() {
	$obj		 = get_queried_object();
	$taxonomies	 = array();
	if ( isset( $obj->ID ) ) {
		$taxonomies = get_object_taxonomies( $obj );
	} else if ( isset( $obj->taxonomies ) ) {
		$taxonomies = $obj->taxonomies;
	} else if ( isset( $obj->taxonomy ) ) {
		$taxonomies = array( $obj->taxonomy );
	}
	foreach ( $taxonomies as $tax ) {
		if ( strpos( $tax, 'al_product-cat' ) !== false ) {
			return $tax;
		}
	}
	return 'al_product-cat';
}

function get_current_screen_post_type( $true = false ) {
	$obj		 = get_queried_object();
	$post_type	 = 'al_product';
	if ( isset( $obj->post_type ) && strpos( $obj->post_type, 'al_product' ) !== false ) {
		$post_type = $obj->post_type;
	} else if ( isset( $obj->name ) && strpos( $obj->name, 'al_product' ) !== false ) {
		$post_type = $obj->name;
	} else if ( isset( $_GET[ 'post_type' ] ) && strpos( $_GET[ 'post_type' ], 'al_product' ) !== false ) {
		$post_type = $_GET[ 'post_type' ];
	}
	return $post_type;
}

function ic_strtolower( $string ) {
	if ( function_exists( 'mb_strtolower' ) ) {
		return mb_strtolower( $string );
	} else {
		return strtolower( $string );
	}
}

function ic_substr( $string, $start, $length ) {
	if ( function_exists( 'mb_substr' ) ) {
		return mb_substr( $string, $start, $length );
	} else {
		return substr( $string, $start, $length );
	}
}

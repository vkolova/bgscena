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
$listing_class			 = apply_filters( 'product_listing_classes', 'al_product responsive' );
?>
<article id="product_listing" <?php post_class( $listing_class ); ?>>
	<?php do_action( 'before_product_listing_entry', $post, $archive_names ); ?>
	<div class="entry-content">
		<?php
		$archive_template		 = get_product_listing_template();
		$taxonomy_name			 = apply_filters( 'current_product_catalog_taxonomy', 'al_product-cat' );
		if ( !is_tax() && !is_search() ) {
			$before_archive = content_product_adder_archive_before();
			if ( $before_archive != '<div class="entry-summary"></div>' ) {
				echo $before_archive;
			}
			if ( $multiple_settings[ 'product_listing_cats' ] == 'on' || $multiple_settings[ 'product_listing_cats' ] == 'cats_only' ) {
				if ( $multiple_settings[ 'cat_template' ] != 'template' ) {
					$product_subcategories = wp_list_categories( 'show_option_none=No_cat&echo=0&title_li=&taxonomy=' . $taxonomy_name . '&parent=0' );
					if ( !strpos( $product_subcategories, 'No_cat' ) ) {
						do_action( 'before_product_listing_category_list' );
						echo '<div class="product-subcategories">' . $product_subcategories . '</div>';
					}
				} else {
					$show_categories = do_shortcode( '[show_categories parent="0" shortcode_query="no"]' );
					if ( !empty( $show_categories ) ) {
						do_action( 'before_product_listing_category_list' );
						echo $show_categories;
						if ( $archive_template != 'list' && !is_ic_only_main_cats() ) {
							echo '<hr>';
						}
					}
				}
			}
		} else if ( is_tax() ) {
			$term = get_queried_object()->term_id;
			if ( is_ic_category_image_enabled() ) {
				$term_img = get_product_category_image_id( $term );
				echo wp_get_attachment_image( $term_img, apply_filters( 'product_cat_image_size', 'large' ), false, array( 'class' => 'product-category-image' ) );
			}
			$term_description = term_description();
			if ( !empty( $term_description ) ) {
				echo '<div class="taxonomy-description">' . $term_description . '</div>';
			}
			if ( $multiple_settings[ 'category_top_cats' ] == 'on' || $multiple_settings[ 'category_top_cats' ] == 'only_subcategories' ) {
				if ( $multiple_settings[ 'cat_template' ] != 'template' ) {
					$product_subcategories = wp_list_categories( 'show_option_none=No_cat&echo=0&title_li=&taxonomy=' . $taxonomy_name . '&child_of=' . $term );
					if ( !strpos( $product_subcategories, 'No_cat' ) ) {
						?>
						<div class="product-subcategories">
							<?php
							do_action( 'before_category_subcategories' );
							echo $product_subcategories;
							?>
						</div>
						<?php
					}
				} else {
					$show_categories = do_shortcode( '[show_categories parent=' . get_queried_object_id() . ' shortcode_query=no]' );
					if ( !empty( $show_categories ) ) {
						do_action( 'before_category_subcategories' );
						echo $show_categories;
						if ( $archive_template != 'list' && !is_ic_only_main_cats() ) {
							echo '<hr>';
						}
					}
				}
			}
		}
		if ( is_home_archive() || (!more_products() && is_custom_product_listing_page()) ) {
			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} else {
				$paged = 1;
			}
			$args	 = apply_filters( 'home_product_listing_query', array( 'post_type' => 'al_product', 'posts_per_page' => $multiple_settings[ 'archive_products_limit' ], 'paged' => $paged ) );
			query_posts( $args );
			$is_home = 1;
		}
		if ( (is_tax() || is_search() || !is_ic_only_main_cats()) && more_products() ) {
			do_action( 'before_product_list', $archive_template, $multiple_settings );
			$product_list = '';
			while ( have_posts() ) : the_post();
				$product_list .= get_catalog_template( $archive_template, $post );
			endwhile;
			$product_list = apply_filters( 'product_list_ready', $product_list, $archive_template, 'auto_listing' );
			echo '<div class="product-list responsive ' . $archive_template . ' ' . product_list_class( $archive_template ) . '">' . $product_list . '</div><span class="clear"></span>';
		} else if ( (!is_product_filters_active() && is_search()) && !more_products() ) {
			echo '<p>' . __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'al-ecommerce-product-catalog' ) . '</p>';
			product_search_form();
		} else if ( is_product_filters_active() && !more_products() ) {
			show_product_sort_bar();
			$filters = get_active_product_filters();
			if ( isset( $_GET[ 's' ] ) ) {
				$filters[] = 's';
			}
			echo '<p>' . sprintf( __( 'Sorry, but nothing matched your search terms. Please try again with some different options or %sreset filters%s.', 'al-ecommerce-product-catalog' ), '<a href="' . esc_url( remove_query_arg( $filters ) ) . '">', '</a>' ) . '</p>';
		}
		?>
	</div>
</article><?php
do_action( 'product_listing_end', $archive_template, $multiple_settings );
if ( isset( $is_home ) ) {
	wp_reset_query();
}

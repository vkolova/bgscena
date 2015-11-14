<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manages product search widget
 *
 * Here product search widget is defined.
 *
 * @version		1.4.0
 * @package		ecommerce-product-catalog/includes
 * @author 		Norbert Dreszer
 */
add_action( 'widgets_init', 'register_product_filter_bar', 30 );

function register_product_filter_bar() {
	$args = array(
		'name'			 => __( 'Product Filters Bar', 'al-ecommerce-product-catalog' ),
		'id'			 => 'product_sort_bar',
		'description'	 => __( 'Appears above the product list. Recommended widgets: Product Search, Product Price Filter, Product Sort and Product Category Filter.', 'al-ecommerce-product-catalog' ),
		'class'			 => '',
		'before_widget'	 => '<div id="%1$s" class="filter-widget %2$s">',
		'after_widget'	 => '</div>',
		'before_title'	 => '<h2 class="filter-widget-title">',
		'after_title'	 => '</h2>' );
	register_sidebar( $args );
}

class product_category_filter extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'product_category_filter', 'description' => __( 'Filter products by categories.', 'al-ecommerce-product-catalog' ) );
		parent::__construct( 'product_category_filter', __( 'Product Category Filter', 'al-ecommerce-product-catalog' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		if ( get_integration_type() != 'simple' && (is_ic_taxonomy_page() || is_ic_product_listing()) ) {
			$title = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ], $instance, $this->id_base );

			$taxonomy = get_current_screen_tax();
			if ( is_ic_taxonomy_page() && !is_product_filter_active( 'product_category' ) ) {
				$categories = get_terms( $taxonomy, array( 'parent' => get_queried_object()->term_id ) );
			} else {
				$categories = get_terms( $taxonomy, array( 'parent' => 0 ) );
			}
			$form		 = '';
			$child_form	 = '';
			foreach ( $categories as $category ) {
				$form .= get_product_category_filter_element( $category );
			}
			$class = 'product-category-filter-container';
			if ( is_product_filter_active( 'product_category' ) ) {
				$class .= ' filter-active';
				$filter_value	 = get_product_filter_value( 'product_category' );
				$children		 = get_terms( $taxonomy, array( 'parent' => $filter_value ) );
				//if ( !is_ic_taxonomy_page() ) {
				$parent_term	 = get_term_by( 'id', $filter_value, $taxonomy );
				if ( !empty( $parent_term->parent ) ) {
					$form .= get_product_category_filter_element( $parent_term );
				}
				//}
				if ( is_array( $children ) ) {
					foreach ( $children as $child ) {
						$child_form .= get_product_category_filter_element( $child );
					}
				}
			}
			if ( !empty( $form ) || !empty( $child_form ) ) {
				echo $args[ 'before_widget' ];
				if ( $title ) {
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
				}
				echo '<div class="' . $class . '">';
				echo $form;
				if ( !empty( $child_form ) ) {
					echo '<div class="child-category-filters">' . $child_form . '</div>';
				}
				echo '</div>';
				echo $args[ 'after_widget' ];
			}
		}
	}

	function form( $instance ) {
		if ( get_integration_type() != 'simple' ) {
			$instance	 = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title		 = $instance[ 'title' ];
			?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'al-ecommerce-product-catalog' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p><?php
		} else {
			if ( is_integration_mode_selected() ) {
				implecode_warning( sprintf( __( 'Category filter widget is disabled with simple theme integration. Please see <a href="%s">Theme Integration Guide</a> to enable product category filter widget.', 'al-ecommerce-product-catalog' ), 'https://implecode.com/wordpress/product-catalog/theme-integration-guide/#cam=simple-mode&key=search-widget' ) );
			} else {
				implecode_warning( sprintf( __( 'Category filter widget is disabled due to a lack of theme integration.%s', 'al-ecommerce-product-catalog' ), sample_product_button( 'p' ) ) );
			}
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance			 = $old_instance;
		$new_instance		 = wp_parse_args( (array) $new_instance, array( 'title' => '' ) );
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		return $instance;
	}

}

class product_sort_filter extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'product_sort_filter', 'description' => __( 'Sort products dropdown.', 'al-ecommerce-product-catalog' ) );
		parent::__construct( 'product_sort_filter', __( 'Product Sort', 'al-ecommerce-product-catalog' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		if ( get_integration_type() != 'simple' ) {
			$title = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ], $instance, $this->id_base );

			echo $args[ 'before_widget' ];
			if ( $title )
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];

			// Use current theme search form if it exists
			show_product_order_dropdown();
			echo $args[ 'after_widget' ];
		}
	}

	function form( $instance ) {
		if ( get_integration_type() != 'simple' ) {
			$instance	 = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title		 = $instance[ 'title' ];
			?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'al-ecommerce-product-catalog' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p><?php
		} else {
			if ( is_integration_mode_selected() ) {
				implecode_warning( sprintf( __( 'Sort widget is disabled with simple theme integration. Please see <a href="%s">Theme Integration Guide</a> to enable product sort widget.', 'al-ecommerce-product-catalog' ), 'https://implecode.com/wordpress/product-catalog/theme-integration-guide/#cam=simple-mode&key=search-widget' ) );
			} else {
				implecode_warning( sprintf( __( 'Sort widget is disabled due to a lack of theme integration.%s', 'al-ecommerce-product-catalog' ), sample_product_button( 'p' ) ) );
			}
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance			 = $old_instance;
		$new_instance		 = wp_parse_args( (array) $new_instance, array( 'title' => '' ) );
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		return $instance;
	}

}

class product_price_filter extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'product_price_filter', 'description' => __( 'Filter products by price.', 'al-ecommerce-product-catalog' ) );
		parent::__construct( 'product_price_filter', __( 'Product Price Filter', 'al-ecommerce-product-catalog' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		if ( get_integration_type() != 'simple' ) {
			$title = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ], $instance, $this->id_base );

			echo $args[ 'before_widget' ];
			if ( $title )
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];

			// Use current theme search form if it exists
			$min_price	 = isset( $_GET[ 'min-price' ] ) ? floatval( $_GET[ 'min-price' ] ) : '';
			$max_price	 = isset( $_GET[ 'max-price' ] ) ? floatval( $_GET[ 'max-price' ] ) : '';
			$currency	 = product_currency();
			?>
			<div class="price-filter">
				<span class="filter-label"><?php _e( 'Price', 'al-ecommerce-product-catalog' ) ?>:</span>
				<form class="price-filter-form">
					<?php
					foreach ( $_GET as $key => $value ) {
						if ( $key != 'min-price' && $key != 'max-price' ) {
							echo('<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />');
						}
					}
					?>
					<input class="number-box" placeholder="<?php echo $currency ?>" type="number" min="0" step="0.01" name="min-price" value="<?php echo $min_price ?>"> - <input placeholder="<?php echo $currency ?>" min="0" step="0.01" type="number" class="number-box" name="max-price" value="<?php echo $max_price ?>">
					<input class="price-filter-submit" type="submit" value="OK">
				</form>
			</div>
			<?php
			echo $args[ 'after_widget' ];
		}
	}

	function form( $instance ) {
		if ( get_integration_type() != 'simple' ) {
			$instance	 = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title		 = $instance[ 'title' ];
			?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'al-ecommerce-product-catalog' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p><?php
		} else {
			if ( is_integration_mode_selected() ) {
				implecode_warning( sprintf( __( 'Sort widget is disabled with simple theme integration. Please see <a href="%s">Theme Integration Guide</a> to enable product sort widget.', 'al-ecommerce-product-catalog' ), 'https://implecode.com/wordpress/product-catalog/theme-integration-guide/#cam=simple-mode&key=search-widget' ) );
			} else {
				implecode_warning( sprintf( __( 'Sort widget is disabled due to a lack of theme integration.%s', 'al-ecommerce-product-catalog' ), sample_product_button( 'p' ) ) );
			}
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance			 = $old_instance;
		$new_instance		 = wp_parse_args( (array) $new_instance, array( 'title' => '' ) );
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		return $instance;
	}

}

add_action( 'implecode_register_widgets', 'register_filter_widgets' );

function register_filter_widgets() {
	register_widget( 'product_category_filter' );
	register_widget( 'product_sort_filter' );
	if ( is_ic_price_enabled() ) {
		register_widget( 'product_price_filter' );
	}
}

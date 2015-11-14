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
class related_products_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'related_products_widget', 'description' => __( 'Shows related products.', 'al-ecommerce-product-catalog' ) );
		parent::__construct( 'related_products_widget', __( 'Related Products', 'al-ecommerce-product-catalog' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		if ( is_ic_product_page() ) {
			$title	 = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ], $instance, $this->id_base );
			$related = get_related_products();
			if ( !empty( $related ) ) {
				echo $args[ 'before_widget' ];
				if ( $title )
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];

				// Use current theme search form if it exists

				echo $related;

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
				implecode_warning( sprintf( __( 'Search widget is disabled with simple theme integration. Please see <a href="%s">Theme Integration Guide</a> to enable product search widget.', 'al-ecommerce-product-catalog' ), 'https://implecode.com/wordpress/product-catalog/theme-integration-guide/#cam=simple-mode&key=search-widget' ) );
			} else {
				implecode_warning( sprintf( __( 'Search widget is disabled due to a lack of theme integration.%s', 'al-ecommerce-product-catalog' ), sample_product_button( 'p' ) ) );
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

add_action( 'implecode_register_widgets', 'register_related_products_widget' );

function register_related_products_widget() {
	register_widget( 'related_products_widget' );
}

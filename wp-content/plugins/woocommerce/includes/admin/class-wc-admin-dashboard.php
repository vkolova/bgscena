<?php
/**
 * Admin Dashboard
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Admin_Dashboard' ) ) :

/**
 * WC_Admin_Dashboard Class
 */
class WC_Admin_Dashboard {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Only hook in admin parts if the user has admin access
		if ( current_user_can( 'view_woocommerce_reports' ) || current_user_can( 'manage_woocommerce' ) || current_user_can( 'publish_shop_orders' ) ) {
			add_action( 'wp_dashboard_setup', array( $this, 'init' ) );
		}
	}

	/**
	 * Init dashboard widgets
	 */
	public function init() {
		if ( current_user_can( 'publish_shop_orders' ) ) {
			wp_add_dashboard_widget( 'woocommerce_dashboard_recent_reviews', __( 'Recent Reviews', 'woocommerce' ), array( $this, 'recent_reviews' ) );
		}

		}

	

	/**
	 * Recent reviews widget
	 */
	public function recent_reviews() {
		global $wpdb;
		$comments = $wpdb->get_results( "SELECT *, SUBSTRING(comment_content,1,100) AS comment_excerpt
		FROM $wpdb->comments
		LEFT JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
		WHERE comment_approved = '1'
		AND comment_type = ''
		AND post_password = ''
		AND post_type = 'product'
		ORDER BY comment_date_gmt DESC
		LIMIT 8" );

		if ( $comments ) {
			echo '<ul>';
			foreach ( $comments as $comment ) {

				echo '<li>';

				echo get_avatar( $comment->comment_author, '32' );

				$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

				echo '<div class="star-rating" title="' . esc_attr( $rating ) . '">
					<span style="width:'. ( $rating * 20 ) . '%">' . $rating . ' ' . __( 'out of 5', 'woocommerce' ) . '</span></div>';

				echo '<h4 class="meta"><a href="' . get_permalink( $comment->ID ) . '#comment-' . absint( $comment->comment_ID ) .'">' . esc_html__( apply_filters( 'woocommerce_admin_dashboard_recent_reviews', $comment->post_title, $comment ) ) . '</a> ' . __( 'reviewed by', 'woocommerce' ) . ' ' . esc_html( $comment->comment_author ) .'</h4>';
				echo '<blockquote>' . wp_kses_data( $comment->comment_excerpt ) . ' [...]</blockquote></li>';

			}
			echo '</ul>';
		} else {
			echo '<p>' . __( 'There are no product reviews yet.', 'woocommerce' ) . '</p>';
		}
	}

}

endif;

return new WC_Admin_Dashboard();

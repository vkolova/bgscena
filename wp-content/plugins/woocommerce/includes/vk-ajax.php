<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * VK_AJAX
 * AJAX Event Handler
 * -vkolova
 */

 add_action( 'vk_enqueue_scripts', 'vk_my_enqueue' );
 function vk_my_enqueue($hook) {
	  wp_enqueue_script( 'vk-change-user-play-status' );
		$ajax_nonce = wp_create_nonce( "vk-my-special-string" );
		wp_localize_script( 'vk-change-user-play-status', 'vk_select_params', array(
	 											 'vk_ajax_url'		=> admin_url( 'admin-ajax.php' ),
												 'ajax_nonce'			=> wp_create_nonce( "vk-my-special-string" )
	  ) );

	}

add_action( 'wp_ajax_update_seen', 'update_seen_callback' );
function update_seen_callback() {
	global $wpdb;

	check_ajax_referer( 'vk-my-special-string', 'security' );

	$user_id = intval( $_POST['user_id'] );
	$play_id = intval( $_POST['play_id'] );

	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}vk_user_play_status WHERE user_id = %d AND play_id = %d AND status_value = %d", $user_id, $play_id, 1));

	$res_count = count($result);

	if ( $res_count ) {
		echo "There are results! :) ";
	} else {
		$resin = $wpdb->insert(
					$wpdb->prefix . 'vk_user_play_status',
					array(
						'user_id' => $user_id,
						'play_id' => $play_id,
						'status_value' => 1
					),
					array(
						'%d',
						'%d',
						'%d'
					)
				);
		echo $resin;
	}

	wp_die();
}



 ?>

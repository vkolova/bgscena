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
		'ajax_nonce'			=> wp_create_nonce( "vk-my-special-string" ),
		'plugin_url'			=> plugins_url()
		) );
	}

add_action( 'wp_ajax_update_seen', 'update_seen_callback' );
function update_seen_callback() {
	global $wpdb;
	check_ajax_referer( 'vk-my-special-string', 'security' );

	$user_id = intval( $_POST['user_id'] );
	$play_id = intval( $_POST['play_id'] );
	$status_id = intval( $_POST['status_id'] );

	$result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}vk_user_play_status WHERE user_id = %d AND play_id = %d", $user_id, $play_id));
	$res_count = count($result);

	if ( $res_count && $result->status_value == 0) {
		$wpdb->update(
			$wpdb->prefix . 'vk_user_play_status',
			array( 'status_value' => 1 ),
			array( 'status_id' => $result->status_id ),
			array( '%d' )
		);
		echo '1';
	} elseif( $res_count && $result->status_value == 1) {
		$wpdb->delete(
			$wpdb->prefix . 'vk_user_play_status',
			array(
				'user_id' => $user_id,
				'play_id' => $play_id
			), array( '%d', '%d' )
		);
		echo '-1';
	} else {
		$wpdb->insert(
					$wpdb->prefix . 'vk_user_play_status',
					array(
						'user_id' => $user_id,
						'play_id' => $play_id,
						'status_value' => 1
					),
					array( '%d', '%d', '%d' )
				);
	echo '1';
	}
	wp_die();
}

add_action( 'wp_ajax_want_to_see', 'want_to_see_callback' );
function want_to_see_callback() {
	global $wpdb;
	check_ajax_referer( 'vk-my-special-string', 'security' );

	$user_id = intval( $_POST['user_id'] );
	$play_id = intval( $_POST['play_id'] );
	$status_id = intval( $_POST['status_id'] );

	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}vk_user_play_status WHERE user_id = %d AND play_id = %d", $user_id, $play_id));

	$res_count = count($result);

	if ( $res_count && $result->status_value == 1) {
		$wpdb->update(
				$wpdb->prefix . 'vk_user_play_status',
				array( 'status_value' => 0 ),
				array( 'status_id' => $status_id ),
				array( '%d' )
			);
			echo '0';
	} elseif ( $res_count && $result->status_value == 0 ) {
	$wpdb->delete(
		$wpdb->prefix . 'vk_user_play_status',
		array('user_id' => $user_id,
		'play_id' => $play_id), array( '%d', '%d' )
	);
		echo '-1';
	} else {
		$wpdb->insert(
					$wpdb->prefix . 'vk_user_play_status',
					array(
						'user_id' => $user_id,
						'play_id' => $play_id,
						'status_value' => 0
					),
					array( '%d', '%d', '%d' )
				);
		echo '0';
	}
	wp_die();
}

 ?>

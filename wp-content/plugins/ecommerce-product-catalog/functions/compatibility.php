<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Defines compatibility functions with previous versions
 *
 * Created by Norbert Dreszer.
 * Date: 10-Mar-15
 * Time: 12:49
 * Package: compatibility.php
 */
function ic_start_compatibility() {
	$first_version = (string) get_option( 'first_activation_version' );
	if ( version_compare( $first_version, '2.2.0' ) < 0 ) {
		add_filter( 'get_product_short_description', 'compatibility_product_short_description', 10, 2 );
		add_filter( 'get_product_description', 'compatibility_product_description', 10, 2 );
	}
}

add_action( 'init', 'ic_start_compatibility' );

function compatibility_product_short_description( $product_desc, $product_id ) {
	$old_desc = get_post_meta( $product_id, '_shortdesc', true );
	if ( empty( $product_desc ) && !empty( $old_desc ) ) {
		if ( current_user_can( 'edit_products' ) ) {
			update_post_meta( $product_id, 'excerpt', $old_desc );
		}
		return $old_desc;
	}
	return $product_desc;
}

function compatibility_product_description( $product_desc, $product_id ) {
	$old_desc = get_post_meta( $product_id, '_desc', true );
	if ( empty( $product_desc ) && !empty( $old_desc ) ) {
		if ( current_user_can( 'edit_products' ) ) {
			update_post_meta( $product_id, 'content', $old_desc );
		}
		return $old_desc;
	}
	return $product_desc;
}

add_filter( 'infinite_scroll_archive_supported', 'ic_jetpack_infinite_scroll_disable' );

/**
 * Disables jetpack infinite scroll on product pages
 *
 * @param boolean $return
 * @return boolean
 */
function ic_jetpack_infinite_scroll_disable( $return ) {
	if ( is_ic_product_listing() || is_ic_taxonomy_page() ) {
		return false;
	}
	return $return;
}

add_action( 'before_product_page', 'set_product_page_image_html' );

/**
 * Sets product page image html if was modified by third party
 */
function set_product_page_image_html() {
	if ( has_filter( 'post_thumbnail_html' ) ) {
		add_filter( 'post_thumbnail_html', 'get_default_product_page_image_html', 1 );
		add_filter( 'post_thumbnail_html', 'product_page_image_html', 99 );
	}
}

/**
 * Inserts default thumbnail html to global
 * @global type $product_page_image_html
 * @param type $html
 * @return type
 */
function get_default_product_page_image_html( $html ) {
	global $product_page_image_html;
	$product_page_image_html = $html;
	return $html;
}

/**
 * Replaces the product page image HTML with the default
 *
 * @global type $product_page_image_html
 * @param type $html
 * @return \type
 */
function product_page_image_html( $html ) {
	if ( is_ic_product_page() ) {
		global $product_page_image_html;
		return $product_page_image_html;
	}
	return $html;
}

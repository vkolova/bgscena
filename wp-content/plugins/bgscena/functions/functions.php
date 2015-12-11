<?php
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
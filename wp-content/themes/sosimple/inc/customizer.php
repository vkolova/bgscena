<?php
/**
 * SoSimple Theme Customizer
 *
 * @package SoSimple
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sosimple_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->add_section( 'sosimple_logo_section' , array(
    'title'       => __( 'Logo', 'sosimple' ),
    'priority'    => 30,
    'description' => 'Upload a logo to replace the default site name and description in the header',
    ) );
    
    $wp_customize->add_setting( 'sosimple_logo',
        'sanitize_callback' == 'esc_url_raw'
    );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'sosimple_logo', array(
        'label'    => __( 'Logo', 'sosimple' ),
        'section'  => 'sosimple_logo_section',
        'settings' => 'sosimple_logo',
        'sanitize_callback' => 'esc_url_raw',
    ) ) );
}
add_action( 'customize_register', 'sosimple_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sosimple_customize_preview_js() {
	wp_enqueue_script( 'sosimple_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'sosimple_customize_preview_js' );



<?php
/**
 * Theme functions for the Valjevska pivara child theme.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load the parent stylesheet before the child stylesheet.
 *
 * Weisber registers the active theme's stylesheet under its main style
 * handle. Re-registering that handle with the parent stylesheet keeps the
 * parent's generated inline CSS attached to the correct file.
 *
 * @return void
 */
function valjevska_pivara_enqueue_styles() {
	$parent_theme = wp_get_theme( get_template() );

	wp_dequeue_style( 'weisber-theme-style' );
	wp_deregister_style( 'weisber-theme-style' );

	wp_enqueue_style(
		'weisber-theme-style',
		get_template_directory_uri() . '/style.css',
		array( 'bootstrap', 'weisber-plugins' ),
		$parent_theme->get( 'Version' )
	);

	wp_enqueue_style(
		'valjevska-pivara-style',
		get_stylesheet_uri(),
		array( 'weisber-theme-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'valjevska_pivara_enqueue_styles', 11 );

<?php
/**
 * Theme setup for the Valjevska pivara child theme.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register child-theme menu locations used by the footer.
 *
 * The parent already registers `primary`. This adds footer locations
 * without replacing that registration.
 *
 * @return void
 */
function valjevska_pivara_register_menus() {
	register_nav_menus(
		array(
			'footer'       => __( 'Footer', 'valjevska-pivara' ),
			'footer-legal' => __( 'Footer legal', 'valjevska-pivara' ),
		)
	);
}
add_action( 'after_setup_theme', 'valjevska_pivara_register_menus' );

/**
 * Print the site logo image, preferring the SVG asset.
 *
 * @param array $args {
 *     Optional. Logo output arguments.
 *
 *     @type bool $lazy Whether to add loading="lazy". Default false.
 * }
 * @return void
 */
function valjevska_pivara_the_logo( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'lazy' => false,
		)
	);

	$relative_path = 'assets/images/logo-valjevsko.svg';
	$file_path     = get_stylesheet_directory() . '/' . $relative_path;
	$site_name     = get_bloginfo( 'name' );

	if ( ! is_readable( $file_path ) ) {
		echo esc_html( $site_name );
		return;
	}

	printf(
		'<img src="%1$s" width="134" height="76" alt="%2$s" decoding="async"%3$s />',
		esc_url( get_stylesheet_directory_uri() . '/' . $relative_path ),
		esc_attr( $site_name ),
		$args['lazy'] ? ' loading="lazy"' : ''
	);
}

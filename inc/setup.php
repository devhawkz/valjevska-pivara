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
 * Register menu locations and keep Appearance → Menus in the admin.
 *
 * The child registers every location it renders so the Menus screen does
 * not depend on the parent `init` callback. `primary` is merged if the
 * parent also registers it.
 *
 * @return void
 */
function valjevska_pivara_register_menus() {
	add_theme_support( 'menus' );

	register_nav_menus(
		array(
			'primary'      => __( 'Main menu', 'valjevska-pivara' ),
			'footer'       => __( 'Footer', 'valjevska-pivara' ),
			'footer-legal' => __( 'Footer legal', 'valjevska-pivara' ),
		)
	);
}
add_action( 'after_setup_theme', 'valjevska_pivara_register_menus', 9 );

/**
 * Restore Appearance → Menus.
 *
 * Core may omit the item when Unyson/Freemius rewrite the Appearance
 * submenu. add_theme_page is the same API as Konfiguracija teme, which
 * does appear. The screen redirects to the native Menus UI.
 *
 * @return void
 */
function valjevska_pivara_ensure_appearance_menus_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	global $submenu;

	$has_core_menus = false;

	if ( isset( $submenu['themes.php'] ) && is_array( $submenu['themes.php'] ) ) {
		foreach ( $submenu['themes.php'] as $item ) {
			if ( empty( $item[2] ) ) {
				continue;
			}

			if ( 'nav-menus.php' === $item[2] || 'valjevska-pivara-menus' === $item[2] ) {
				$has_core_menus = true;
				break;
			}
		}
	}

	if ( $has_core_menus ) {
		return;
	}

	add_theme_page(
		__( 'Menus' ),
		__( 'Menus' ),
		'edit_theme_options',
		'valjevska-pivara-menus',
		'valjevska_pivara_redirect_to_nav_menus'
	);
}
add_action( 'admin_menu', 'valjevska_pivara_ensure_appearance_menus_page', 1000000003 );

/**
 * Open the native Menus screen.
 *
 * @return void
 */
function valjevska_pivara_redirect_to_nav_menus() {
	wp_safe_redirect( admin_url( 'nav-menus.php' ) );
	exit;
}

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

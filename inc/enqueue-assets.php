<?php
/**
 * Front-end stylesheet registration for the Valjevska pivara child theme.
 *
 * Loading strategy:
 * - Global tokens and base styles: loaded on all public-facing pages.
 * - Header and footer variant assets: loaded on all public-facing pages
 *   for the active registry entries only.
 * - Homepage variant assets: loaded only on the front page, and only
 *   for section parts registered on the active homepage variant. Each
 *   part's stylesheet is `assets/css/{template}.css` unless the part
 *   declares a `stylesheet` key.
 * - Opt-in button primitives: loaded globally while there is no PHP
 *   component that can detect usage. Do not add further component CSS
 *   to this global set.
 * - Reusable component styles: enqueue only when the component is used,
 *   where WordPress APIs make that reliable.
 * - Gutenberg block styles: use wp_enqueue_block_style() or the
 *   project's existing block-loading mechanism.
 * - Template-specific assets: load with reliable conditional tags.
 * - JavaScript: enqueue only for components that require it.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return a cache-busting version for a child-theme file.
 *
 * Uses the file modification time when the file is readable. Falls back
 * to the child theme version. The returned value is a version string for
 * WordPress enqueue APIs, not a filesystem path.
 *
 * @param string $relative_path Path relative to the child theme root.
 * @return string
 */
function valjevska_pivara_get_asset_version( $relative_path ) {
	$relative_path = ltrim( $relative_path, '/' );
	$file_path     = get_stylesheet_directory() . '/' . $relative_path;

	if ( is_readable( $file_path ) ) {
		$mtime = filemtime( $file_path );

		if ( false !== $mtime ) {
			return (string) $mtime;
		}
	}

	return wp_get_theme()->get( 'Version' );
}

/**
 * Enqueue a child-theme stylesheet when the file exists.
 *
 * @param string   $handle         Style handle.
 * @param string   $relative_path  Path relative to the child theme root.
 * @param string[] $dependencies   Style handles this sheet depends on.
 * @return void
 */
function valjevska_pivara_enqueue_style( $handle, $relative_path, $dependencies = array() ) {
	$relative_path = ltrim( $relative_path, '/' );
	$file_path     = get_stylesheet_directory() . '/' . $relative_path;

	if ( ! is_readable( $file_path ) ) {
		return;
	}

	wp_enqueue_style(
		$handle,
		get_stylesheet_directory_uri() . '/' . $relative_path,
		$dependencies,
		valjevska_pivara_get_asset_version( $relative_path )
	);
}

/**
 * Enqueue a child-theme script when the file exists.
 *
 * @param string   $handle         Script handle.
 * @param string   $relative_path  Path relative to the child theme root.
 * @param string[] $dependencies   Script handles this file depends on.
 * @return void
 */
function valjevska_pivara_enqueue_script( $handle, $relative_path, $dependencies = array() ) {
	$relative_path = ltrim( $relative_path, '/' );
	$file_path     = get_stylesheet_directory() . '/' . $relative_path;

	if ( ! is_readable( $file_path ) ) {
		return;
	}

	wp_enqueue_script(
		$handle,
		get_stylesheet_directory_uri() . '/' . $relative_path,
		$dependencies,
		valjevska_pivara_get_asset_version( $relative_path ),
		true
	);

	wp_script_add_data( $handle, 'strategy', 'defer' );
}

/**
 * Load the parent stylesheet before child foundation styles.
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
		valjevska_pivara_get_asset_version( 'style.css' )
	);

	valjevska_pivara_enqueue_style(
		'valjevska-pivara-tokens',
		'assets/css/foundation/tokens.css',
		array( 'valjevska-pivara-style' )
	);

	valjevska_pivara_enqueue_style(
		'valjevska-pivara-base',
		'assets/css/foundation/base.css',
		array( 'valjevska-pivara-tokens' )
	);

	valjevska_pivara_enqueue_style(
		'valjevska-pivara-buttons',
		'assets/css/components/buttons.css',
		array( 'valjevska-pivara-tokens' )
	);

	valjevska_pivara_enqueue_variant_assets( 'header' );
	valjevska_pivara_enqueue_variant_assets( 'footer' );

	if ( is_front_page() ) {
		valjevska_pivara_enqueue_homepage_part_assets();
	}
}

/**
 * Enqueue stylesheets for homepage section parts used on the front page.
 *
 * @return void
 */
function valjevska_pivara_enqueue_homepage_part_assets() {
	foreach ( valjevska_pivara_get_homepage_parts() as $part ) {
		$stylesheet = '';

		if ( ! empty( $part['stylesheet'] ) && is_string( $part['stylesheet'] ) ) {
			$stylesheet = $part['stylesheet'];
		} else {
			$stylesheet = 'assets/css/' . $part['template'] . '.css';
		}

		$style_handle = 'valjevska-pivara-' . sanitize_key( str_replace( '/', '-', $part['template'] ) );

		if ( ! empty( $part['style_handle'] ) && is_string( $part['style_handle'] ) ) {
			$style_handle = $part['style_handle'];
		}

		valjevska_pivara_enqueue_style(
			$style_handle,
			$stylesheet,
			array( 'valjevska-pivara-tokens', 'valjevska-pivara-base' )
		);
	}
}

/**
 * Enqueue stylesheet and optional script for the active component variant.
 *
 * @param string $component Component key.
 * @return void
 */
function valjevska_pivara_enqueue_variant_assets( $component ) {
	$variant = valjevska_pivara_get_active_variant( $component );

	if ( empty( $variant ) ) {
		return;
	}

	if ( ! empty( $variant['stylesheet'] ) && is_string( $variant['stylesheet'] ) ) {
		$style_handle = ! empty( $variant['style_handle'] ) ? $variant['style_handle'] : 'valjevska-pivara-' . $component;
		valjevska_pivara_enqueue_style(
			$style_handle,
			$variant['stylesheet'],
			array( 'valjevska-pivara-tokens', 'valjevska-pivara-base' )
		);
	}

	if ( empty( $variant['script'] ) || ! is_string( $variant['script'] ) ) {
		return;
	}

	$script_handle = ! empty( $variant['script_handle'] ) ? $variant['script_handle'] : 'valjevska-pivara-' . $component;

	valjevska_pivara_enqueue_script( $script_handle, $variant['script'] );

	if ( 'valjevska-pivara-header' !== $script_handle || ! wp_script_is( $script_handle, 'enqueued' ) ) {
		return;
	}

	wp_localize_script(
		$script_handle,
		'valjevskaPivaraHeader',
		array(
			'expandSubmenu'   => __( 'Expand submenu', 'valjevska-pivara' ),
			'collapseSubmenu' => __( 'Collapse submenu', 'valjevska-pivara' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'valjevska_pivara_enqueue_styles', 11 );

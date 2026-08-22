<?php
/**
 * Front-end stylesheet registration for the Valjevska pivara child theme.
 *
 * Loading strategy:
 * - Global tokens and base styles: loaded on all public-facing pages.
 * - Header styles and script: loaded on all public-facing pages because
 *   the header template part is used site-wide.
 * - Footer styles: loaded on all public-facing pages because the footer
 *   template part is used site-wide. No footer JavaScript.
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

	valjevska_pivara_enqueue_header_assets();
	valjevska_pivara_enqueue_footer_assets();
}

/**
 * Load header CSS and deferred vanilla JS.
 *
 * @return void
 */
function valjevska_pivara_enqueue_header_assets() {
	valjevska_pivara_enqueue_style(
		'valjevska-pivara-header',
		'assets/css/components/header.css',
		array( 'valjevska-pivara-tokens', 'valjevska-pivara-base' )
	);

	valjevska_pivara_enqueue_script(
		'valjevska-pivara-header',
		'assets/js/header.js'
	);

	if ( ! wp_script_is( 'valjevska-pivara-header', 'enqueued' ) ) {
		return;
	}

	wp_localize_script(
		'valjevska-pivara-header',
		'valjevskaPivaraHeader',
		array(
			'expandSubmenu'   => __( 'Expand submenu', 'valjevska-pivara' ),
			'collapseSubmenu' => __( 'Collapse submenu', 'valjevska-pivara' ),
		)
	);
}

/**
 * Load footer CSS. No footer script.
 *
 * @return void
 */
function valjevska_pivara_enqueue_footer_assets() {
	valjevska_pivara_enqueue_style(
		'valjevska-pivara-footer',
		'assets/css/components/footer.css',
		array( 'valjevska-pivara-tokens', 'valjevska-pivara-base' )
	);
}
add_action( 'wp_enqueue_scripts', 'valjevska_pivara_enqueue_styles', 11 );

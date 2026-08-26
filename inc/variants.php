<?php
/**
 * Variant registry and resolved theme configuration.
 *
 * Selections are stored in the `valjevska_pivara_theme_config` option.
 * Template and asset paths are taken only from this whitelist.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the implemented variant registry, grouped by component.
 *
 * @return array<string, array<string, array<string, string>>>
 */
function valjevska_pivara_get_variant_registry() {
	return array(
		'header'   => array(
			'v1' => array(
				'label'          => __( 'Header V1', 'valjevska-pivara' ),
				'template'       => 'tmpl/header-v1',
				'stylesheet'     => 'assets/css/components/header.css',
				'script'         => 'assets/js/header.js',
				'style_handle'   => 'valjevska-pivara-header',
				'script_handle'  => 'valjevska-pivara-header',
			),
		),
		'footer'   => array(
			'v1' => array(
				'label'        => __( 'Footer V1', 'valjevska-pivara' ),
				'template'     => 'tmpl/footer',
				'stylesheet'   => 'assets/css/components/footer.css',
				'style_handle' => 'valjevska-pivara-footer',
			),
		),
		'homepage' => array(
			'v1' => array(
				'label'    => __( 'Homepage V1', 'valjevska-pivara' ),
				'template' => 'tmpl/homepage-v1',
				'parts'    => array(
					array(
						'template' => 'parts/homepage-v1-hero',
					),
					array(
						'template' => 'parts/traditional-method',
					),
				),
			),
		),
	);
}

/**
 * Return component keys that have at least one registered variant.
 *
 * @return string[]
 */
function valjevska_pivara_get_variant_components() {
	return array_keys( valjevska_pivara_get_variant_registry() );
}

/**
 * Return a registered variant slug, or v1 when the value is missing or invalid.
 *
 * @param string $component Component key: header, footer, or homepage.
 * @param mixed  $slug      Candidate variant slug.
 * @return string
 */
function valjevska_pivara_sanitize_variant_slug( $component, $slug ) {
	if ( ! is_string( $slug ) ) {
		return 'v1';
	}

	$slug     = sanitize_key( $slug );
	$registry = valjevska_pivara_get_variant_registry();

	if ( isset( $registry[ $component ][ $slug ] ) ) {
		return $slug;
	}

	return 'v1';
}

/**
 * Sanitize the Theme Configuration option array.
 *
 * @param mixed $input Raw submitted value.
 * @return array<string, string>
 */
function valjevska_pivara_sanitize_theme_config( $input ) {
	$clean = array();

	if ( ! is_array( $input ) ) {
		$input = array();
	}

	foreach ( valjevska_pivara_get_variant_components() as $component ) {
		$value = '';

		if ( isset( $input[ $component ] ) ) {
			$value = $input[ $component ];
		}

		$clean[ $component ] = valjevska_pivara_sanitize_variant_slug( $component, $value );
	}

	return $clean;
}

/**
 * Return stored theme configuration with every component resolved to a valid slug.
 *
 * @return array<string, string>
 */
function valjevska_pivara_get_theme_config() {
	$stored = get_option( 'valjevska_pivara_theme_config', array() );

	return valjevska_pivara_sanitize_theme_config( $stored );
}

/**
 * Return the active registry entry for a component.
 *
 * @param string $component Component key.
 * @return array<string, string>
 */
function valjevska_pivara_get_active_variant( $component ) {
	$registry = valjevska_pivara_get_variant_registry();

	if ( ! isset( $registry[ $component ] ) || ! is_array( $registry[ $component ] ) ) {
		return array();
	}

	$config = valjevska_pivara_get_theme_config();
	$slug   = isset( $config[ $component ] ) ? $config[ $component ] : 'v1';

	if ( isset( $registry[ $component ][ $slug ] ) ) {
		return $registry[ $component ][ $slug ];
	}

	if ( isset( $registry[ $component ]['v1'] ) ) {
		return $registry[ $component ]['v1'];
	}

	return array();
}

/**
 * Whether a registry template path is allowed.
 *
 * @param string $template Path relative to the child theme, without .php.
 * @return bool
 */
function valjevska_pivara_is_allowed_template_path( $template ) {
	if ( ! is_string( $template ) || '' === $template || false !== strpos( $template, '..' ) ) {
		return false;
	}

	return ( 0 === strpos( $template, 'tmpl/' ) || 0 === strpos( $template, 'parts/' ) );
}

/**
 * Return registered homepage section parts for the active homepage variant.
 *
 * @return array<int, array<string, string>>
 */
function valjevska_pivara_get_homepage_parts() {
	$variant = valjevska_pivara_get_active_variant( 'homepage' );
	$parts   = array();

	if ( empty( $variant['parts'] ) || ! is_array( $variant['parts'] ) ) {
		return $parts;
	}

	foreach ( $variant['parts'] as $part ) {
		if ( ! is_array( $part ) || empty( $part['template'] ) || ! is_string( $part['template'] ) ) {
			continue;
		}

		if ( ! valjevska_pivara_is_allowed_template_path( $part['template'] ) ) {
			continue;
		}

		$parts[] = $part;
	}

	return $parts;
}

/**
 * Render registered homepage section parts in registry order.
 *
 * @return void
 */
function valjevska_pivara_the_homepage_parts() {
	foreach ( valjevska_pivara_get_homepage_parts() as $part ) {
		$file_path = get_stylesheet_directory() . '/' . $part['template'] . '.php';

		if ( ! is_readable( $file_path ) ) {
			continue;
		}

		get_template_part( $part['template'] );
	}
}

/**
 * Load the active variant template part for a component.
 *
 * @param string $component Component key.
 * @return void
 */
function valjevska_pivara_the_variant_template( $component ) {
	$variant = valjevska_pivara_get_active_variant( $component );

	if ( empty( $variant['template'] ) || ! is_string( $variant['template'] ) ) {
		return;
	}

	$template = $variant['template'];

	if ( ! valjevska_pivara_is_allowed_template_path( $template ) ) {
		return;
	}

	$file_path = get_stylesheet_directory() . '/' . $template . '.php';

	if ( ! is_readable( $file_path ) ) {
		return;
	}

	get_template_part( $template );
}

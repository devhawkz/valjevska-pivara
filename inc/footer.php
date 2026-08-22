<?php
/**
 * Footer helpers for the Valjevska pivara child theme.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the allowed social networks in Figma order.
 *
 * @return array<string, array<string, string>>
 */
function valjevska_pivara_get_social_networks() {
	return array(
		'x'         => array(
			'mod'   => 'valjevska_pivara_social_x',
			'label' => __( 'Visit our X profile', 'valjevska-pivara' ),
		),
		'facebook'  => array(
			'mod'   => 'valjevska_pivara_social_facebook',
			'label' => __( 'Visit our Facebook profile', 'valjevska-pivara' ),
		),
		'instagram' => array(
			'mod'   => 'valjevska_pivara_social_instagram',
			'label' => __( 'Visit our Instagram profile', 'valjevska-pivara' ),
		),
	);
}

/**
 * Return a sanitized http(s) URL from a theme mod, or an empty string.
 *
 * @param string $mod Theme mod name.
 * @return string
 */
function valjevska_pivara_get_social_url( $mod ) {
	$url = get_theme_mod( $mod, '' );

	if ( ! is_string( $url ) || '' === $url ) {
		return '';
	}

	return esc_url( $url, array( 'http', 'https' ) );
}

/**
 * Return networks that have both a usable URL and a bundled icon.
 *
 * @return array<string, array<string, string>>
 */
function valjevska_pivara_get_social_links() {
	$links = array();

	foreach ( valjevska_pivara_get_social_networks() as $slug => $network ) {
		$url = valjevska_pivara_get_social_url( $network['mod'] );

		if ( '' === $url || '' === valjevska_pivara_get_icon_svg( $slug ) ) {
			continue;
		}

		$links[ $slug ] = array(
			'url'   => $url,
			'label' => $network['label'],
		);
	}

	return $links;
}

/**
 * Return sanitized inline SVG markup for a bundled social icon.
 *
 * @param string $slug Icon file slug: facebook, instagram, or x.
 * @return string
 */
function valjevska_pivara_get_icon_svg( $slug ) {
	$allowed = array( 'facebook', 'instagram', 'x' );

	if ( ! in_array( $slug, $allowed, true ) ) {
		return '';
	}

	$path = get_stylesheet_directory() . '/assets/icons/' . $slug . '.svg';

	if ( ! is_readable( $path ) ) {
		return '';
	}

	$svg = file_get_contents( $path );

	if ( false === $svg ) {
		return '';
	}

	$svg = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $svg );
	$svg = preg_replace( '/<foreignObject\b[^>]*>.*?<\/foreignObject>/is', '', $svg );
	$svg = preg_replace( '/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $svg );
	$svg = preg_replace( '/\s(?:href|xlink:href)\s*=\s*("\s*javascript:[^"]*"|\'\s*javascript:[^\']*\')/i', '', $svg );

	if ( ! is_string( $svg ) || false === strpos( $svg, '<svg' ) ) {
		return '';
	}

	if ( false === strpos( $svg, 'aria-hidden' ) ) {
		$svg = preg_replace( '/<svg\b/i', '<svg aria-hidden="true" focusable="false"', $svg, 1 );
	}

	return is_string( $svg ) ? $svg : '';
}

/**
 * Print a sanitized social icon SVG.
 *
 * @param string $slug Icon file slug: facebook, instagram, or x.
 * @return void
 */
function valjevska_pivara_the_icon_svg( $slug ) {
	echo valjevska_pivara_get_icon_svg( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized SVG from theme files.
}

/**
 * Return the footer description text.
 *
 * Prefers the Customizer field. Falls back to the site tagline so the
 * native WordPress Site Identity option is used when the Customizer
 * field is empty.
 *
 * @return string
 */
function valjevska_pivara_get_footer_description() {
	$description = get_theme_mod( 'valjevska_pivara_footer_description', '' );

	if ( is_string( $description ) ) {
		$description = trim( $description );

		if ( '' !== $description ) {
			return $description;
		}
	}

	$tagline = get_bloginfo( 'description' );

	if ( ! is_string( $tagline ) ) {
		return '';
	}

	return trim( wp_strip_all_tags( $tagline ) );
}

/**
 * Print the footer description when text is available.
 *
 * @return void
 */
function valjevska_pivara_the_footer_description() {
	$description = valjevska_pivara_get_footer_description();

	if ( '' === $description ) {
		return;
	}

	echo '<div class="vp-footer__description">';
	echo wp_kses_post( wpautop( esc_html( $description ) ) );
	echo '</div>';
}

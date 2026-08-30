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
					array(
						'template' => 'parts/brands',
					),
					array(
						'template' => 'parts/partner-cta',
					),
					array(
						'template' => 'parts/quality',
					),
					array(
						'template' => 'parts/instagram',
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
 * Sanitize a contact phone stored in Theme Configuration.
 *
 * Keeps a display value. A tel: href is derived separately.
 *
 * @param mixed $phone Raw submitted value.
 * @return string
 */
function valjevska_pivara_sanitize_contact_phone( $phone ) {
	if ( ! is_string( $phone ) ) {
		return '';
	}

	$phone = trim( wp_strip_all_tags( $phone ) );
	$phone = preg_replace( '/[^\d+\-\s\/().]/', '', $phone );

	if ( ! is_string( $phone ) ) {
		return '';
	}

	$phone = trim( $phone );

	if ( '' === $phone ) {
		return '';
	}

	$digits = preg_replace( '/\D/', '', $phone );

	if ( ! is_string( $digits ) || strlen( $digits ) < 6 ) {
		return '';
	}

	return $phone;
}

/**
 * Sanitize a contact email stored in Theme Configuration.
 *
 * @param mixed $email Raw submitted value.
 * @return string
 */
function valjevska_pivara_sanitize_contact_email( $email ) {
	if ( ! is_string( $email ) ) {
		return '';
	}

	$email = sanitize_email( $email );

	if ( '' === $email || ! is_email( $email ) ) {
		return '';
	}

	return $email;
}

/**
 * Sanitize a social profile URL for an allowed host list.
 *
 * @param mixed    $url           Raw value.
 * @param string[] $allowed_hosts Hosts without a www. prefix.
 * @return string
 */
function valjevska_pivara_sanitize_social_profile_url( $url, $allowed_hosts ) {
	if ( ! is_string( $url ) || '' === $url || ! is_array( $allowed_hosts ) ) {
		return '';
	}

	$url = esc_url_raw( $url, array( 'https', 'http' ) );

	if ( '' === $url ) {
		return '';
	}

	$parts = wp_parse_url( $url );

	if ( ! is_array( $parts ) || empty( $parts['host'] ) || ! is_string( $parts['host'] ) ) {
		return '';
	}

	$host = strtolower( $parts['host'] );

	if ( 0 === strpos( $host, 'www.' ) ) {
		$host = substr( $host, 4 );
	}

	if ( ! in_array( $host, $allowed_hosts, true ) ) {
		return '';
	}

	$path = '';

	if ( isset( $parts['path'] ) && is_string( $parts['path'] ) ) {
		$path = trim( $parts['path'], '/' );
	}

	if ( '' === $path ) {
		return '';
	}

	return $url;
}

/**
 * Sanitize a public Instagram profile URL.
 *
 * @param mixed $url Raw value.
 * @return string
 */
function valjevska_pivara_sanitize_instagram_profile_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return '';
	}

	$url = esc_url_raw( $url, array( 'https', 'http' ) );

	if ( '' === $url ) {
		return '';
	}

	$parts = wp_parse_url( $url );

	if ( ! is_array( $parts ) || empty( $parts['host'] ) || ! is_string( $parts['host'] ) ) {
		return '';
	}

	$host = strtolower( $parts['host'] );

	if ( 0 === strpos( $host, 'www.' ) ) {
		$host = substr( $host, 4 );
	}

	if ( 'instagram.com' !== $host ) {
		return '';
	}

	$path = '';

	if ( isset( $parts['path'] ) && is_string( $parts['path'] ) ) {
		$path = trim( $parts['path'], '/' );
	}

	if ( '' === $path || false !== strpos( $path, '/' ) ) {
		return '';
	}

	if ( ! preg_match( '/^[A-Za-z0-9._]{1,30}$/', $path ) ) {
		return '';
	}

	return 'https://www.instagram.com/' . $path;
}

/**
 * Sanitize one selected Instagram cache post ID.
 *
 * @param mixed           $value          Raw value.
 * @param array<int,bool> $seen           IDs already accepted.
 * @param bool            $validate_posts Whether to confirm the CPT exists.
 * @return string
 */
function valjevska_pivara_sanitize_instagram_post_id( $value, &$seen, $validate_posts ) {
	$id = absint( $value );

	if ( $id < 1 || isset( $seen[ $id ] ) ) {
		return '';
	}

	if ( $validate_posts ) {
		$post = get_post( $id );

		if ( ! $post instanceof WP_Post || 'vp_instagram_post' !== $post->post_type || 'publish' !== $post->post_status ) {
			return '';
		}
	}

	$seen[ $id ] = true;

	return (string) $id;
}

/**
 * Extract an Instagram username from a sanitized profile URL.
 *
 * @param string $url Profile URL.
 * @return string
 */
function valjevska_pivara_get_instagram_profile_username( $url ) {
	$url = valjevska_pivara_sanitize_instagram_profile_url( $url );

	if ( '' === $url ) {
		return '';
	}

	$path = wp_parse_url( $url, PHP_URL_PATH );

	if ( ! is_string( $path ) ) {
		return '';
	}

	return trim( $path, '/' );
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

	$phone = '';
	$email = '';

	if ( isset( $input['phone'] ) ) {
		$phone = $input['phone'];
	}

	if ( isset( $input['email'] ) ) {
		$email = $input['email'];
	}

	$clean['phone'] = valjevska_pivara_sanitize_contact_phone( $phone );
	$clean['email'] = valjevska_pivara_sanitize_contact_email( $email );

	$social_hosts = array(
		'social_x'         => array( 'x.com', 'twitter.com' ),
		'social_facebook'  => array( 'facebook.com', 'fb.com' ),
		'social_instagram' => array( 'instagram.com' ),
	);

	foreach ( $social_hosts as $key => $hosts ) {
		$raw = '';

		if ( isset( $input[ $key ] ) ) {
			$raw = $input[ $key ];
		}

		$clean[ $key ] = valjevska_pivara_sanitize_social_profile_url( $raw, $hosts );
	}

	$enabled = '1';

	if ( array_key_exists( 'instagram_enabled', $input ) ) {
		$enabled = ( '1' === (string) $input['instagram_enabled'] ) ? '1' : '';
	}

	$profile_url = 'https://www.instagram.com/valjevskopivo';

	if ( isset( $input['instagram_profile_url'] ) ) {
		$sanitized_url = valjevska_pivara_sanitize_instagram_profile_url( $input['instagram_profile_url'] );

		if ( '' !== $sanitized_url ) {
			$profile_url = $sanitized_url;
		}
	}

	$clean['instagram_enabled']     = $enabled;
	$clean['instagram_profile_url'] = $profile_url;

	$seen           = array();
	$validate_posts = doing_filter( 'sanitize_option_valjevska_pivara_theme_config' );

	for ( $position = 1; $position <= 6; $position++ ) {
		$key   = 'instagram_post_' . $position;
		$value = '';

		if ( isset( $input[ $key ] ) ) {
			$value = $input[ $key ];
		}

		$clean[ $key ] = valjevska_pivara_sanitize_instagram_post_id( $value, $seen, $validate_posts );
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
 * Return the Theme Configuration phone display value, or an empty string.
 *
 * @return string
 */
function valjevska_pivara_get_contact_phone() {
	$config = valjevska_pivara_get_theme_config();

	return isset( $config['phone'] ) ? $config['phone'] : '';
}

/**
 * Return a tel: href from Theme Configuration, or an empty string.
 *
 * @return string
 */
function valjevska_pivara_get_contact_tel() {
	$phone = valjevska_pivara_get_contact_phone();

	if ( '' === $phone ) {
		return '';
	}

	$href = preg_replace( '/[^\d+]/', '', $phone );

	if ( ! is_string( $href ) || '' === $href ) {
		return '';
	}

	if ( 0 === strpos( $href, '+' ) ) {
		$href = '+' . str_replace( '+', '', substr( $href, 1 ) );
	} else {
		$href = str_replace( '+', '', $href );
	}

	$digits = preg_replace( '/\D/', '', $href );

	if ( ! is_string( $digits ) || strlen( $digits ) < 6 ) {
		return '';
	}

	return 'tel:' . $href;
}

/**
 * Return the Theme Configuration email, or an empty string.
 *
 * @return string
 */
function valjevska_pivara_get_contact_email() {
	$config = valjevska_pivara_get_theme_config();

	return isset( $config['email'] ) ? $config['email'] : '';
}

/**
 * Whether the Homepage V1 Instagram section is enabled.
 *
 * @return bool
 */
function valjevska_pivara_is_instagram_section_enabled() {
	$config = valjevska_pivara_get_theme_config();

	return isset( $config['instagram_enabled'] ) && '1' === $config['instagram_enabled'];
}

/**
 * Return the public Instagram profile URL.
 *
 * @return string
 */
function valjevska_pivara_get_instagram_profile_url() {
	$config = valjevska_pivara_get_theme_config();

	if ( ! empty( $config['instagram_profile_url'] ) ) {
		return $config['instagram_profile_url'];
	}

	return 'https://www.instagram.com/valjevskopivo';
}

/**
 * Return the six Theme Configuration Instagram post IDs in display order.
 *
 * Empty positions are omitted. Values are already sanitized.
 *
 * @return int[]
 */
function valjevska_pivara_get_instagram_selected_post_ids() {
	$config = valjevska_pivara_get_theme_config();
	$ids    = array();

	for ( $position = 1; $position <= 6; $position++ ) {
		$key = 'instagram_post_' . $position;

		if ( empty( $config[ $key ] ) ) {
			continue;
		}

		$id = absint( $config[ $key ] );

		if ( $id > 0 ) {
			$ids[] = $id;
		}
	}

	return $ids;
}

/**
 * Return six placeholder Instagram cards for Homepage V1.
 *
 * Used when no synchronized posts are selected or available.
 *
 * @return array<int, array<string, string>>
 */
function valjevska_pivara_get_instagram_placeholder_cards() {
	$image  = valjevska_pivara_get_instagram_placeholder_image_html();
	$avatar = valjevska_pivara_get_instagram_placeholder_avatar_html();

	if ( '' === $image ) {
		return array();
	}

	$username = valjevska_pivara_get_instagram_profile_username( valjevska_pivara_get_instagram_profile_url() );

	if ( '' === $username ) {
		$username = 'valjevskopivo';
	}

	$permalink = valjevska_pivara_get_instagram_profile_url();
	$label     = sprintf(
		/* translators: %s: Instagram username */
		__( 'Instagram post by %s (opens in a new tab)', 'valjevska-pivara' ),
		$username
	);

	$card = array(
		'permalink' => $permalink,
		'username'  => $username,
		'image'     => $image,
		'avatar'    => $avatar,
		'label'     => $label,
	);

	return array_fill( 0, 6, $card );
}

/**
 * Return placeholder post image markup from theme assets.
 *
 * @return string
 */
function valjevska_pivara_get_instagram_placeholder_image_html() {
	$directory = get_stylesheet_directory() . '/assets/images/';
	$uri       = get_stylesheet_directory_uri() . '/assets/images/';
	$base      = 'traditional-method-hops';
	$fallback  = $directory . $base . '-699.webp';

	if ( ! is_readable( $fallback ) ) {
		return '';
	}

	$sizes  = '(min-width: 64em) 28rem, (min-width: 30em) 44vw, 92vw';
	$avif   = array();
	$webp   = array();

	foreach ( array( 320, 480, 640, 699 ) as $width ) {
		foreach ( array( 'avif', 'webp' ) as $type ) {
			$file = $directory . $base . '-' . $width . '.' . $type;

			if ( ! is_readable( $file ) ) {
				continue;
			}

			$entry = esc_url( $uri . $base . '-' . $width . '.' . $type ) . ' ' . $width . 'w';

			if ( 'avif' === $type ) {
				$avif[] = $entry;
			} else {
				$webp[] = $entry;
			}
		}
	}

	ob_start();
	?>
	<picture>
		<?php if ( ! empty( $avif ) ) : ?>
			<source type="image/avif" srcset="<?php echo esc_attr( implode( ', ', $avif ) ); ?>" sizes="<?php echo esc_attr( $sizes ); ?>">
		<?php endif; ?>
		<?php if ( ! empty( $webp ) ) : ?>
			<source type="image/webp" srcset="<?php echo esc_attr( implode( ', ', $webp ) ); ?>" sizes="<?php echo esc_attr( $sizes ); ?>">
		<?php endif; ?>
		<img
			class="vp-instagram__image"
			src="<?php echo esc_url( $uri . $base . '-699.webp' ); ?>"
			width="699"
			height="995"
			alt=""
			loading="lazy"
			decoding="async"
		>
	</picture>
	<?php

	$html = ob_get_clean();

	return is_string( $html ) ? $html : '';
}

/**
 * Return placeholder profile image markup from the theme logo.
 *
 * @return string
 */
function valjevska_pivara_get_instagram_placeholder_avatar_html() {
	$relative_path = 'assets/images/logo-valjevsko.png';
	$file_path     = get_stylesheet_directory() . '/' . $relative_path;

	if ( ! is_readable( $file_path ) ) {
		$relative_path = 'assets/images/logo-valjevsko.svg';
		$file_path     = get_stylesheet_directory() . '/' . $relative_path;
	}

	if ( ! is_readable( $file_path ) ) {
		return '';
	}

	return sprintf(
		'<img class="vp-instagram__avatar" src="%1$s" width="36" height="36" alt="" loading="lazy" decoding="async" />',
		esc_url( get_stylesheet_directory_uri() . '/' . $relative_path )
	);
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

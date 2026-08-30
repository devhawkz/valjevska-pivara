<?php
/**
 * Appearance → Theme Configuration (Settings API).
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Theme Configuration screen under Appearance.
 *
 * @return void
 */
function valjevska_pivara_register_theme_config_page() {
	static $registered = false;

	if ( $registered ) {
		return;
	}

	$registered = true;
	$page_title = __( 'Theme Configuration', 'valjevska-pivara' );
	$menu_title = __( 'Konfiguracija teme', 'valjevska-pivara' );
	$cap        = 'manage_options';

	add_menu_page(
		$page_title,
		$menu_title,
		$cap,
		'valjevska-pivara',
		'valjevska_pivara_render_theme_config_page',
		'dashicons-admin-generic',
		3
	);

	add_options_page(
		$page_title,
		$menu_title,
		$cap,
		'valjevska-pivara-settings',
		'valjevska_pivara_render_theme_config_page'
	);

	add_theme_page(
		$page_title,
		$menu_title,
		$cap,
		'valjevska-pivara-theme-config',
		'valjevska_pivara_render_theme_config_page'
	);

	add_submenu_page(
		'weisber',
		$page_title,
		$menu_title,
		$cap,
		'valjevska-pivara-activation',
		'valjevska_pivara_render_theme_config_page'
	);
}
add_action( 'admin_menu', 'valjevska_pivara_register_theme_config_page', 1000000002 );

/**
 * Add a shortcut to Theme Configuration in the admin bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar.
 * @return void
 */
function valjevska_pivara_admin_bar_theme_config( $wp_admin_bar ) {
	if ( ! $wp_admin_bar instanceof WP_Admin_Bar || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$wp_admin_bar->add_node(
		array(
			'id'     => 'valjevska-pivara-theme-config',
			'parent' => 'site-name',
			'title'  => __( 'Konfiguracija teme', 'valjevska-pivara' ),
			'href'   => admin_url( 'admin.php?page=valjevska-pivara' ),
		)
	);
}
add_action( 'admin_bar_menu', 'valjevska_pivara_admin_bar_theme_config', 80 );

/**
 * Register the theme configuration setting and fields.
 *
 * @return void
 */
function valjevska_pivara_register_theme_config_setting() {
	register_setting(
		'valjevska_pivara_theme_config_group',
		'valjevska_pivara_theme_config',
		array(
			'type'              => 'array',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'valjevska_pivara_sanitize_theme_config',
			'default'           => array(
				'header'                 => 'v1',
				'footer'                 => 'v1',
				'homepage'               => 'v1',
				'phone'                  => '',
				'email'                  => '',
				'social_x'               => '',
				'social_facebook'        => '',
				'social_instagram'       => '',
				'instagram_enabled'      => '1',
				'instagram_profile_url'  => 'https://www.instagram.com/valjevskopivo',
				'instagram_post_1'       => '',
				'instagram_post_2'       => '',
				'instagram_post_3'       => '',
				'instagram_post_4'       => '',
				'instagram_post_5'       => '',
				'instagram_post_6'       => '',
			),
		)
	);

	add_settings_section(
		'valjevska_pivara_theme_config_section',
		'',
		'__return_false',
		'valjevska-pivara-theme-config'
	);

	$fields = array(
		'header'   => __( 'Header variant', 'valjevska-pivara' ),
		'footer'   => __( 'Footer variant', 'valjevska-pivara' ),
		'homepage' => __( 'Homepage variant', 'valjevska-pivara' ),
	);

	foreach ( $fields as $component => $label ) {
		add_settings_field(
			'valjevska_pivara_' . $component . '_variant',
			$label,
			'valjevska_pivara_render_variant_field',
			'valjevska-pivara-theme-config',
			'valjevska_pivara_theme_config_section',
			array(
				'component' => $component,
				'label_for' => 'valjevska_pivara_' . $component . '_variant',
			)
		);
	}

	add_settings_section(
		'valjevska_pivara_theme_config_contact_section',
		__( 'Contact', 'valjevska-pivara' ),
		'__return_false',
		'valjevska-pivara-theme-config'
	);

	add_settings_field(
		'valjevska_pivara_phone',
		__( 'Phone', 'valjevska-pivara' ),
		'valjevska_pivara_render_contact_field',
		'valjevska-pivara-theme-config',
		'valjevska_pivara_theme_config_contact_section',
		array(
			'key'         => 'phone',
			'type'        => 'tel',
			'label_for'   => 'valjevska_pivara_phone',
			'description' => __( 'Used for the Partner CTA call button. Leave empty to hide the button. Do not use a placeholder number.', 'valjevska-pivara' ),
			'placeholder' => '',
		)
	);

	add_settings_field(
		'valjevska_pivara_email',
		__( 'Email', 'valjevska-pivara' ),
		'valjevska_pivara_render_contact_field',
		'valjevska-pivara-theme-config',
		'valjevska_pivara_theme_config_contact_section',
		array(
			'key'         => 'email',
			'type'        => 'email',
			'label_for'   => 'valjevska_pivara_email',
			'description' => __( 'Used for the Partner CTA email button. Leave empty to hide the button.', 'valjevska-pivara' ),
			'placeholder' => 'valjevsko@mts.rs',
		)
	);

	add_settings_section(
		'valjevska_pivara_theme_config_social_section',
		__( 'Social icons', 'valjevska-pivara' ),
		'valjevska_pivara_render_social_section_intro',
		'valjevska-pivara-theme-config'
	);

	$social_fields = array(
		'social_x'         => array(
			'label'       => __( 'X URL', 'valjevska-pivara' ),
			'placeholder' => 'https://x.com/valjevskopivo',
		),
		'social_facebook'  => array(
			'label'       => __( 'Facebook URL', 'valjevska-pivara' ),
			'placeholder' => 'https://www.facebook.com/valjevskopivo',
		),
		'social_instagram' => array(
			'label'       => __( 'Instagram URL', 'valjevska-pivara' ),
			'placeholder' => 'https://www.instagram.com/valjevskopivo',
		),
	);

	foreach ( $social_fields as $key => $field ) {
		add_settings_field(
			'valjevska_pivara_' . $key,
			$field['label'],
			'valjevska_pivara_render_social_field',
			'valjevska-pivara-theme-config',
			'valjevska_pivara_theme_config_social_section',
			array(
				'key'         => $key,
				'label_for'   => 'valjevska_pivara_' . $key,
				'placeholder' => $field['placeholder'],
			)
		);
	}

	add_settings_section(
		'valjevska_pivara_theme_config_instagram_section',
		__( 'Instagram', 'valjevska-pivara' ),
		'valjevska_pivara_render_instagram_section_intro',
		'valjevska-pivara-theme-config'
	);

	add_settings_field(
		'valjevska_pivara_instagram_enabled',
		__( 'Show Instagram section', 'valjevska-pivara' ),
		'valjevska_pivara_render_instagram_enabled_field',
		'valjevska-pivara-theme-config',
		'valjevska_pivara_theme_config_instagram_section',
		array(
			'label_for' => 'valjevska_pivara_instagram_enabled',
		)
	);

	add_settings_field(
		'valjevska_pivara_instagram_profile_url',
		__( 'Instagram profile URL', 'valjevska-pivara' ),
		'valjevska_pivara_render_instagram_profile_url_field',
		'valjevska-pivara-theme-config',
		'valjevska_pivara_theme_config_instagram_section',
		array(
			'label_for' => 'valjevska_pivara_instagram_profile_url',
		)
	);

	for ( $position = 1; $position <= 6; $position++ ) {
		add_settings_field(
			'valjevska_pivara_instagram_post_' . $position,
			sprintf(
				/* translators: %d: grid position 1–6 */
				__( 'Position %d', 'valjevska-pivara' ),
				$position
			),
			'valjevska_pivara_render_instagram_post_field',
			'valjevska-pivara-theme-config',
			'valjevska_pivara_theme_config_instagram_section',
			array(
				'position'  => $position,
				'label_for' => 'valjevska_pivara_instagram_post_' . $position,
			)
		);
	}
}
add_action( 'admin_init', 'valjevska_pivara_register_theme_config_setting' );

/**
 * Render the Theme Configuration page.
 *
 * @return void
 */
function valjevska_pivara_render_theme_config_page() {
	if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to access this page.', 'valjevska-pivara' ), '', array( 'response' => 403 ) );
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php valjevska_pivara_render_instagram_sync_notices(); ?>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'valjevska_pivara_theme_config_group' );
			do_settings_sections( 'valjevska-pivara-theme-config' );
			submit_button();
			?>
		</form>
		<?php valjevska_pivara_render_instagram_sync_panel(); ?>
	</div>
	<?php
}

/**
 * Render a variant select for one component.
 *
 * @param array $args Field arguments with a `component` key.
 * @return void
 */
function valjevska_pivara_render_variant_field( $args ) {
	$component = isset( $args['component'] ) ? $args['component'] : '';
	$registry  = valjevska_pivara_get_variant_registry();

	if ( ! isset( $registry[ $component ] ) || ! is_array( $registry[ $component ] ) ) {
		return;
	}

	$config   = valjevska_pivara_get_theme_config();
	$selected = isset( $config[ $component ] ) ? $config[ $component ] : 'v1';
	$field_id = 'valjevska_pivara_' . $component . '_variant';
	?>
	<select
		id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( 'valjevska_pivara_theme_config[' . $component . ']' ); ?>"
	>
		<?php foreach ( $registry[ $component ] as $slug => $variant ) : ?>
			<?php
			$label = isset( $variant['label'] ) ? $variant['label'] : $slug;
			?>
			<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $selected, $slug ); ?>>
				<?php echo esc_html( $label ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php
}

/**
 * Render a contact text field.
 *
 * @param array $args Field arguments with `key` and `type`.
 * @return void
 */
function valjevska_pivara_render_contact_field( $args ) {
	$key = isset( $args['key'] ) ? $args['key'] : '';

	if ( 'phone' !== $key && 'email' !== $key ) {
		return;
	}

	$config      = valjevska_pivara_get_theme_config();
	$value       = isset( $config[ $key ] ) ? $config[ $key ] : '';
	$type        = isset( $args['type'] ) ? $args['type'] : 'text';
	$field_id    = 'valjevska_pivara_' . $key;
	$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
	$description = isset( $args['description'] ) ? $args['description'] : '';
	?>
	<input
		type="<?php echo esc_attr( $type ); ?>"
		id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( 'valjevska_pivara_theme_config[' . $key . ']' ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
		class="regular-text"
		autocomplete="<?php echo esc_attr( $key ); ?>"
		<?php if ( '' !== $placeholder ) : ?>
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
		<?php endif; ?>
	>
	<?php if ( '' !== $description ) : ?>
		<p class="description"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Render the social icons settings intro.
 *
 * @return void
 */
function valjevska_pivara_render_social_section_intro() {
	echo '<p>' . esc_html__( 'These URLs drive the X, Facebook, and Instagram icons in the header, footer, and Homepage V1 hero. Leave a field empty to hide that icon everywhere.', 'valjevska-pivara' ) . '</p>';
}

/**
 * Render a social profile URL field.
 *
 * @param array $args Field arguments with a `key`.
 * @return void
 */
function valjevska_pivara_render_social_field( $args ) {
	$key     = isset( $args['key'] ) ? $args['key'] : '';
	$allowed = array( 'social_x', 'social_facebook', 'social_instagram' );

	if ( ! in_array( $key, $allowed, true ) ) {
		return;
	}

	$config      = valjevska_pivara_get_theme_config();
	$value       = isset( $config[ $key ] ) ? $config[ $key ] : '';
	$field_id    = 'valjevska_pivara_' . $key;
	$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

	if ( '' === $value ) {
		$mod_map = array(
			'social_x'         => 'valjevska_pivara_social_x',
			'social_facebook'  => 'valjevska_pivara_social_facebook',
			'social_instagram' => 'valjevska_pivara_social_instagram',
		);

		if ( isset( $mod_map[ $key ] ) ) {
			$mod_value = get_theme_mod( $mod_map[ $key ], '' );

			if ( is_string( $mod_value ) && '' !== $mod_value ) {
				$value = $mod_value;
			}
		}
	}
	?>
	<input
		type="url"
		id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( 'valjevska_pivara_theme_config[' . $key . ']' ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
		class="regular-text"
		<?php if ( '' !== $placeholder ) : ?>
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
		<?php endif; ?>
	>
	<?php
}

/**
 * Enqueue Theme Configuration admin styles.
 *
 * @param string $hook Current admin page hook.
 * @return void
 */
function valjevska_pivara_enqueue_theme_config_admin_assets( $hook ) {
	$allowed = array(
		'appearance_page_valjevska-pivara-theme-config',
		'toplevel_page_valjevska-pivara',
		'settings_page_valjevska-pivara-settings',
		'theme-activation_page_valjevska-pivara-activation',
		'weisber_page_valjevska-pivara-activation',
	);

	if ( ! in_array( $hook, $allowed, true ) ) {
		return;
	}

	valjevska_pivara_enqueue_style(
		'valjevska-pivara-theme-config-admin',
		'assets/css/admin/theme-config.css'
	);
}
add_action( 'admin_enqueue_scripts', 'valjevska_pivara_enqueue_theme_config_admin_assets' );

/**
 * Return synchronized Instagram cache items for Theme Configuration selectors.
 *
 * @return array<int, array<string, string>>
 */
function valjevska_pivara_get_instagram_selector_items() {
	static $items = null;

	if ( null !== $items ) {
		return $items;
	}

	$items = array();

	if ( function_exists( 'vp_instagram_feed_get_selector_items' ) ) {
		$items = vp_instagram_feed_get_selector_items();
	}

	if ( ! is_array( $items ) ) {
		$items = array();
	}

	return $items;
}

/**
 * Render the Instagram settings section intro.
 *
 * @return void
 */
function valjevska_pivara_render_instagram_section_intro() {
	echo '<p>' . esc_html__( 'Until Instagram is synchronized, Homepage V1 shows six placeholder cards. The public profile URL is only used for the website. It does not change API credentials.', 'valjevska-pivara' ) . '</p>';
	echo '<p class="description">' . esc_html__( 'After a successful sync, select up to six posts. Each post can be used in only one position. Leave a position empty to keep placeholders or show fewer than six live cards.', 'valjevska-pivara' ) . '</p>';
}

/**
 * Render the Instagram section enable checkbox.
 *
 * @return void
 */
function valjevska_pivara_render_instagram_enabled_field() {
	$config  = valjevska_pivara_get_theme_config();
	$enabled = isset( $config['instagram_enabled'] ) ? $config['instagram_enabled'] : '';
	?>
	<input type="hidden" name="valjevska_pivara_theme_config[instagram_enabled]" value="">
	<label for="valjevska_pivara_instagram_enabled">
		<input
			type="checkbox"
			id="valjevska_pivara_instagram_enabled"
			name="valjevska_pivara_theme_config[instagram_enabled]"
			value="1"
			<?php checked( $enabled, '1' ); ?>
		>
		<?php echo esc_html__( 'Display the Instagram grid on Homepage V1, below the quality section. Placeholder cards are used until live posts are selected.', 'valjevska-pivara' ); ?>
	</label>
	<?php
}

/**
 * Render the public Instagram profile URL field.
 *
 * @return void
 */
function valjevska_pivara_render_instagram_profile_url_field() {
	$config = valjevska_pivara_get_theme_config();
	$value  = isset( $config['instagram_profile_url'] ) ? $config['instagram_profile_url'] : 'https://www.instagram.com/valjevskopivo';
	?>
	<input
		type="url"
		id="valjevska_pivara_instagram_profile_url"
		name="valjevska_pivara_theme_config[instagram_profile_url]"
		value="<?php echo esc_attr( $value ); ?>"
		class="regular-text"
		placeholder="https://www.instagram.com/valjevskopivo"
	>
	<p class="description"><?php echo esc_html__( 'Only instagram.com profile URLs are allowed.', 'valjevska-pivara' ); ?></p>
	<?php
}

/**
 * Render one ordered Instagram post selector.
 *
 * @param array $args Field arguments with a `position` key.
 * @return void
 */
function valjevska_pivara_render_instagram_post_field( $args ) {
	$position = isset( $args['position'] ) ? absint( $args['position'] ) : 0;

	if ( $position < 1 || $position > 6 ) {
		return;
	}

	$key      = 'instagram_post_' . $position;
	$field_id = 'valjevska_pivara_' . $key;
	$config   = valjevska_pivara_get_theme_config();
	$selected = isset( $config[ $key ] ) ? $config[ $key ] : '';
	$items    = valjevska_pivara_get_instagram_selector_items();

	$thumbs = array();

	foreach ( $items as $item ) {
		if ( empty( $item['id'] ) ) {
			continue;
		}

		$thumbs[ (string) $item['id'] ] = isset( $item['thumb'] ) ? $item['thumb'] : '';
	}

	$selected_thumb = ( '' !== $selected && isset( $thumbs[ $selected ] ) ) ? $thumbs[ $selected ] : '';
	?>
	<div class="vp-theme-config-instagram-row">
		<select
			id="<?php echo esc_attr( $field_id ); ?>"
			name="<?php echo esc_attr( 'valjevska_pivara_theme_config[' . $key . ']' ); ?>"
		>
			<option value=""><?php echo esc_html__( 'Placeholder', 'valjevska-pivara' ); ?></option>
			<?php foreach ( $items as $item ) : ?>
				<?php
				if ( empty( $item['id'] ) || empty( $item['label'] ) ) {
					continue;
				}
				?>
				<option value="<?php echo esc_attr( $item['id'] ); ?>" <?php selected( $selected, (string) $item['id'] ); ?>>
					<?php echo esc_html( $item['label'] ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php if ( '' !== $selected_thumb ) : ?>
			<img
				src="<?php echo esc_url( $selected_thumb ); ?>"
				alt=""
				width="48"
				height="48"
			>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render notices after a manual Instagram synchronization.
 *
 * @return void
 */
function valjevska_pivara_render_instagram_sync_notices() {
	if ( ! isset( $_GET['vp_instagram_sync'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display flag after authenticated POST redirect.
		return;
	}

	if ( '1' !== (string) wp_unslash( $_GET['vp_instagram_sync'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	if ( ! function_exists( 'vp_instagram_feed_get_status' ) ) {
		return;
	}

	$status = vp_instagram_feed_get_status();

	if ( '' !== $status['last_error'] ) {
		echo '<div class="notice notice-error"><p>' . esc_html( $status['last_error'] ) . '</p></div>';
		return;
	}

	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Instagram posts were synchronized.', 'valjevska-pivara' ) . '</p></div>';
}

/**
 * Render the read-only API status and manual sync form.
 *
 * @return void
 */
function valjevska_pivara_render_instagram_sync_panel() {
	$plugin_active = function_exists( 'vp_instagram_feed_get_status' );
	?>
	<div class="vp-theme-config-instagram-sync">
		<h2><?php echo esc_html__( 'Instagram synchronization', 'valjevska-pivara' ); ?></h2>
		<?php if ( ! $plugin_active ) : ?>
			<p><?php echo esc_html__( 'Activate the VP Instagram Feed plugin when API credentials are ready. Until then, Homepage V1 uses placeholder cards.', 'valjevska-pivara' ); ?></p>
		<?php else : ?>
			<?php
			$status          = vp_instagram_feed_get_status();
			$config_username = valjevska_pivara_get_instagram_profile_username( valjevska_pivara_get_instagram_profile_url() );
			$api_username    = $status['username'];
			$has_credentials = function_exists( 'vp_instagram_feed_has_credentials' ) && vp_instagram_feed_has_credentials();
			?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php echo esc_html__( 'Connected API username', 'valjevska-pivara' ); ?></th>
				<td>
					<?php if ( '' !== $api_username ) : ?>
						<code><?php echo esc_html( $api_username ); ?></code>
					<?php else : ?>
						<p class="description"><?php echo esc_html__( 'Placeholder: valjevskopivo. Live username appears after a successful sync.', 'valjevska-pivara' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php echo esc_html__( 'Last successful sync', 'valjevska-pivara' ); ?></th>
				<td>
					<?php
					if ( '' !== $status['last_success'] ) {
						$parsed = strtotime( $status['last_success'] );

						if ( false !== $parsed ) {
							echo esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $parsed ) );
						} else {
							echo esc_html__( 'Unknown', 'valjevska-pivara' );
						}
					} else {
						echo esc_html__( 'Never', 'valjevska-pivara' );
					}
					?>
				</td>
			</tr>
		</table>
		<?php if ( '' !== $api_username && '' !== $config_username && 0 !== strcasecmp( $api_username, $config_username ) ) : ?>
			<div class="notice notice-warning inline">
				<p><?php echo esc_html__( 'The public profile URL username differs from the authorized Instagram API account. Changing the public URL does not change API credentials.', 'valjevska-pivara' ); ?></p>
			</div>
		<?php endif; ?>
		<?php if ( ! $has_credentials ) : ?>
			<p class="description"><?php echo esc_html__( 'Access token and Instagram user ID are optional for now. Add VP_INSTAGRAM_ACCESS_TOKEN and VP_INSTAGRAM_USER_ID in wp-config.php when they are available.', 'valjevska-pivara' ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $status['last_error'] ) : ?>
			<p class="notice notice-error inline"><?php echo esc_html( $status['last_error'] ); ?></p>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="vp_instagram_feed_sync">
			<?php wp_nonce_field( 'vp_instagram_feed_sync' ); ?>
			<?php submit_button( __( 'Sync Instagram posts', 'valjevska-pivara' ), 'secondary', 'submit', false ); ?>
		</form>
		<?php endif; ?>
	</div>
	<?php
}

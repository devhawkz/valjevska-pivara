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
	add_theme_page(
		__( 'Theme Configuration', 'valjevska-pivara' ),
		__( 'Theme Configuration', 'valjevska-pivara' ),
		'edit_theme_options',
		'valjevska-pivara-theme-config',
		'valjevska_pivara_render_theme_config_page'
	);
}
add_action( 'admin_menu', 'valjevska_pivara_register_theme_config_page' );

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
				'header'   => 'v1',
				'footer'   => 'v1',
				'homepage' => 'v1',
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
}
add_action( 'admin_init', 'valjevska_pivara_register_theme_config_setting' );

/**
 * Render the Theme Configuration page.
 *
 * @return void
 */
function valjevska_pivara_render_theme_config_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to access this page.', 'valjevska-pivara' ), '', array( 'response' => 403 ) );
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'valjevska_pivara_theme_config_group' );
			do_settings_sections( 'valjevska-pivara-theme-config' );
			submit_button();
			?>
		</form>
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

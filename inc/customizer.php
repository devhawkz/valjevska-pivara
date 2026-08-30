<?php
/**
 * Customizer settings for the Valjevska pivara child theme.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Stop Unyson from registering Customizer controls.
 *
 * Unyson (lt-core) hooks customize_register at priority 7. On WordPress 7.1
 * its option types throw during registration, so later callbacks never run
 * and the Customizer accordion stays empty.
 *
 * @return void
 */
function valjevska_pivara_disable_unyson_customizer() {
	if ( ! function_exists( 'fw' ) ) {
		return;
	}

	remove_action( 'customize_register', array( fw()->backend, '_action_customize_register' ), 7 );
	add_filter( 'fw_customizer_options', '__return_empty_array', 99 );
}
add_action( 'fw_init', 'valjevska_pivara_disable_unyson_customizer', 20 );

/**
 * Keep Unyson Customizer scripts from loading on the controls screen.
 *
 * @return void
 */
function valjevska_pivara_dequeue_unyson_customizer_assets() {
	wp_dequeue_script( 'fw-backend-customizer' );
	wp_dequeue_style( 'fw-backend-customizer' );
}
add_action( 'customize_controls_enqueue_scripts', 'valjevska_pivara_dequeue_unyson_customizer_assets', 100 );

/**
 * Register footer description and social URL controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function valjevska_pivara_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'valjevska_pivara_footer',
		array(
			'title'    => __( 'Footer', 'valjevska-pivara' ),
			'priority' => 120,
		)
	);

	$wp_customize->add_setting(
		'valjevska_pivara_footer_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'valjevska_pivara_footer_description',
		array(
			'label'       => __( 'Description', 'valjevska-pivara' ),
			'description' => __( 'Shown under the footer logo. Leave empty to use the site tagline. Leave both empty to hide.', 'valjevska-pivara' ),
			'section'     => 'valjevska_pivara_footer',
			'type'        => 'textarea',
		)
	);

	$social_controls = array(
		'valjevska_pivara_social_x'         => __( 'X URL', 'valjevska-pivara' ),
		'valjevska_pivara_social_facebook'  => __( 'Facebook URL', 'valjevska-pivara' ),
		'valjevska_pivara_social_instagram' => __( 'Instagram URL', 'valjevska-pivara' ),
	);

	foreach ( $social_controls as $setting_id => $label ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'       => $label,
				'description' => __( 'Prefer Appearance → Konfiguracija teme → Social icons. This field is used only when that setting is empty.', 'valjevska-pivara' ),
				'section'     => 'valjevska_pivara_footer',
				'type'        => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'valjevska_pivara_customize_register', 20 );

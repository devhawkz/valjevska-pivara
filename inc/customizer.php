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
				'label'   => $label,
				'section' => 'valjevska_pivara_footer',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'valjevska_pivara_customize_register' );

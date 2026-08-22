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
 * Register child-theme menu locations used by the footer.
 *
 * The parent already registers `primary`. This adds footer locations
 * without replacing that registration.
 *
 * @return void
 */
function valjevska_pivara_register_menus() {
	register_nav_menus(
		array(
			'footer'       => __( 'Footer', 'valjevska-pivara' ),
			'footer-legal' => __( 'Footer legal', 'valjevska-pivara' ),
		)
	);
}
add_action( 'after_setup_theme', 'valjevska_pivara_register_menus' );

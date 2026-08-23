<?php
/**
 * Front page: selected Homepage variant between header and footer.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
valjevska_pivara_the_variant_template( 'homepage' );
get_footer();

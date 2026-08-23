<?php
/**
 * Back-compat template part name used by the parent header.php.
 *
 * The child header.php loads Header V1 through the variant registry.
 * This file remains so `get_template_part( 'tmpl/pageheader' )` still
 * renders Header V1.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'tmpl/header-v1' );

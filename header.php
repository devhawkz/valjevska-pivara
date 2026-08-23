<?php
/**
 * Document header for the Valjevska pivara child theme.
 *
 * Loads the selected Header variant, then opens the main content wrapper
 * expected by footer.php.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();

if ( function_exists( 'weisber_the_pageloader_overlay' ) ) {
	weisber_the_pageloader_overlay();
}

valjevska_pivara_the_variant_template( 'header' );
?>
		<div class="container main-wrapper">

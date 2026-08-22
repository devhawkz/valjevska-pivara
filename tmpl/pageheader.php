<?php
/**
 * Page header wrappers required by the parent layout.
 *
 * Renders the child navbar only. Weisber's title/breadcrumb banner is
 * omitted so the site header matches the Figma bar.
 *
 * These two opening divs, plus the parent header's main-wrapper, are
 * closed in the child footer.php.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ltx-content-wrapper">
	<div class="header-wrapper ltx-pageheader-disabled">
	<?php
		get_template_part( 'tmpl/navbar' );

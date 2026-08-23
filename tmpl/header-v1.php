<?php
/**
 * Header V1: layout wrappers and the site navbar.
 *
 * The two opening divs, plus the document header's main-wrapper, are
 * closed in footer.php.
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

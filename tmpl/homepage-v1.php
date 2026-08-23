<?php
/**
 * Homepage V1 entry point.
 *
 * Sections are not implemented yet. Existing page content is retained
 * through the standard loop and the parent content-page template part.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

while ( have_posts() ) :
	the_post();
	get_template_part( 'tmpl/content', 'page' );
endwhile;

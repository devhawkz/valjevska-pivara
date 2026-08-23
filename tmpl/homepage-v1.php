<?php
/**
 * Homepage V1: registered section parts, then existing page content.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

valjevska_pivara_the_homepage_parts();

while ( have_posts() ) :
	the_post();
	get_template_part( 'tmpl/content', 'page' );
endwhile;

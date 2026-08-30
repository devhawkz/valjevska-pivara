<?php
/**
 * Homepage V1: registered section parts in registry order, then page content.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

while ( have_posts() ) :
	the_post();
	valjevska_pivara_the_homepage_parts();
	get_template_part( 'tmpl/content', 'page' );
endwhile;

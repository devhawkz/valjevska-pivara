<?php
/**
 * Document footer for the Valjevska pivara child theme.
 *
 * Closes the layout wrappers opened in header.php and Header V1, loads
 * the selected Footer variant, then prints wp_footer() and closing markup.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
		</div><!-- .container.main-wrapper -->
	</div><!-- .header-wrapper -->
</div><!-- .ltx-content-wrapper -->
<?php
valjevska_pivara_the_variant_template( 'footer' );
wp_footer();
?>
</body>
</html>

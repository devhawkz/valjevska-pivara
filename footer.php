<?php
/**
 * Site footer for the Valjevska pivara child theme.
 *
 * Closes the layout wrappers opened in the parent header and the child
 * page header, then renders the Figma footer. Weisber's widget,
 * subscribe, copyright, and go-top output is omitted.
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
get_template_part( 'tmpl/footer' );
wp_footer();
?>
</body>
</html>

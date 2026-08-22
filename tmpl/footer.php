<?php
/**
 * Site footer: logo, description, menus, copyright, and social links.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vp_has_footer_menu = has_nav_menu( 'footer' );
$vp_has_legal_menu  = has_nav_menu( 'footer-legal' );
$vp_social_links    = valjevska_pivara_get_social_links();
$vp_logo_rel        = 'assets/images/logo-valjevsko.png';
$vp_logo_readable   = is_readable( get_stylesheet_directory() . '/' . $vp_logo_rel );
$vp_home_url        = home_url( '/' );
$vp_site_name       = get_bloginfo( 'name' );
$vp_year            = wp_date( 'Y' );
?>
<footer class="vp-footer">
	<div class="vp-footer__top-container">
		<div class="vp-footer__main vp-container">
			<div class="vp-footer__intro">
				<a class="vp-footer__brand" href="<?php echo esc_url( $vp_home_url ); ?>">
					<?php if ( $vp_logo_readable ) : ?>
						<img
							src="<?php echo esc_url( get_stylesheet_directory_uri() . '/' . $vp_logo_rel ); ?>"
							width="134"
							height="76"
							alt="<?php echo esc_attr( $vp_site_name ); ?>"
							decoding="async"
							loading="lazy"
						/>
					<?php else : ?>
						<?php echo esc_html( $vp_site_name ); ?>
					<?php endif; ?>
				</a>
				<?php valjevska_pivara_the_footer_description(); ?>
			</div>

			<?php if ( $vp_has_footer_menu ) : ?>
				<nav class="vp-footer__nav" aria-label="<?php echo esc_attr__( 'Footer', 'valjevska-pivara' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_id'        => 'vp-footer-menu',
							'menu_class'     => 'vp-footer__menu',
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>
		</div>
	</div>

	<div class="vp-footer__bar">
		<div class="vp-footer__bar-inner vp-container">
			<div class="vp-footer__meta">
				<p class="vp-footer__copyright">
					<?php
					echo esc_html(
						sprintf(
							/* translators: 1: site name, 2: current year */
							__( '© %1$s, %2$s', 'valjevska-pivara' ),
							$vp_site_name,
							$vp_year
						)
					);
					?>
				</p>

				<?php if ( $vp_has_legal_menu ) : ?>
					<nav class="vp-footer__legal-nav" aria-label="<?php echo esc_attr__( 'Legal', 'valjevska-pivara' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer-legal',
								'container'      => false,
								'menu_id'        => 'vp-footer-legal',
								'menu_class'     => 'vp-footer__legal',
								'fallback_cb'    => false,
								'depth'          => 1,
							)
						);
						?>
					</nav>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $vp_social_links ) ) : ?>
				<nav class="vp-footer__social-nav" aria-label="<?php echo esc_attr__( 'Social', 'valjevska-pivara' ); ?>">
					<ul class="vp-footer__social">
						<?php foreach ( $vp_social_links as $vp_slug => $vp_network ) : ?>
							<li>
								<a
									href="<?php echo esc_url( $vp_network['url'] ); ?>"
									rel="noopener noreferrer"
									target="_blank"
									aria-label="<?php echo esc_attr( $vp_network['label'] ); ?>"
								>
									<?php valjevska_pivara_the_icon_svg( $vp_slug ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		</div>
	</div>
</footer>

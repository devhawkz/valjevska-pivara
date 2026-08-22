<?php
/**
 * Site header: WordPress primary menu on the left, logo on the right.
 *
 * Overrides the parent Weisber navbar template part.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vp_has_primary_menu = has_nav_menu( 'primary' );
$vp_panel_id         = 'vp-header-panel';
$vp_nav_id           = 'vp-primary-navigation';
$vp_logo_rel         = 'assets/images/logo-valjevsko.png';
$vp_logo_readable    = is_readable( get_stylesheet_directory() . '/' . $vp_logo_rel );
$vp_home_url         = home_url( '/' );
$vp_site_name        = get_bloginfo( 'name' );
?>
<header class="vp-header">
	<div class="vp-header__bar vp-container">
		<a class="vp-header__brand vp-header__brand--bar" href="<?php echo esc_url( $vp_home_url ); ?>">
			<?php if ( $vp_logo_readable ) : ?>
				<img
					src="<?php echo esc_url( get_stylesheet_directory_uri() . '/' . $vp_logo_rel ); ?>"
					width="134"
					height="76"
					alt="<?php echo esc_attr( $vp_site_name ); ?>"
					decoding="async"
				/>
			<?php else : ?>
				<?php echo esc_html( $vp_site_name ); ?>
			<?php endif; ?>
		</a>

		<?php if ( $vp_has_primary_menu ) : ?>
			<button
				type="button"
				class="vp-header__toggle"
				aria-expanded="false"
				aria-controls="<?php echo esc_attr( $vp_panel_id ); ?>"
			>
				<span class="vp-header__toggle-box" aria-hidden="true">
					<span class="vp-header__toggle-line"></span>
					<span class="vp-header__toggle-line"></span>
					<span class="vp-header__toggle-line"></span>
				</span>
				<span class="vp-visually-hidden"><?php echo esc_html__( 'Menu', 'valjevska-pivara' ); ?></span>
			</button>

			<div
				id="<?php echo esc_attr( $vp_panel_id ); ?>"
				class="vp-header__panel"
				aria-hidden="true"
			>
				<button
					type="button"
					class="vp-header__close"
				>
					<span class="vp-header__close-icon" aria-hidden="true"></span>
					<span class="vp-visually-hidden"><?php echo esc_html__( 'Close menu', 'valjevska-pivara' ); ?></span>
				</button>

				<a class="vp-header__brand vp-header__brand--panel" href="<?php echo esc_url( $vp_home_url ); ?>">
					<?php if ( $vp_logo_readable ) : ?>
						<img
							src="<?php echo esc_url( get_stylesheet_directory_uri() . '/' . $vp_logo_rel ); ?>"
							width="134"
							height="76"
							alt="<?php echo esc_attr( $vp_site_name ); ?>"
							decoding="async"
						/>
					<?php else : ?>
						<?php echo esc_html( $vp_site_name ); ?>
					<?php endif; ?>
				</a>

				<hr class="vp-header__rule" />

				<nav id="<?php echo esc_attr( $vp_nav_id ); ?>" class="vp-header__nav" aria-label="<?php echo esc_attr__( 'Primary', 'valjevska-pivara' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_id'        => 'vp-header-menu',
							'menu_class'     => 'vp-header__menu',
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			</div>
		<?php endif; ?>
	</div>
</header>

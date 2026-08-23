<?php
/**
 * Homepage V1 hero: full-width image with a left overlay.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vp_hero_dir     = get_stylesheet_directory() . '/assets/images/';
$vp_hero_uri     = get_stylesheet_directory_uri() . '/assets/images/';
$vp_hero_webp    = $vp_hero_dir . 'hero-valjevsko-1024.webp';
$vp_social_links = valjevska_pivara_get_social_links();
$vp_desktop_set  = array();
$vp_mobile_set   = array(
	'avif' => array(),
	'webp' => array(),
);

if ( ! is_readable( $vp_hero_webp ) ) {
	return;
}

foreach ( array( 768, 1024 ) as $vp_hero_width ) {
	$vp_hero_file = $vp_hero_dir . 'hero-valjevsko-' . $vp_hero_width . '.webp';

	if ( ! is_readable( $vp_hero_file ) ) {
		continue;
	}

	$vp_desktop_set[] = esc_url( $vp_hero_uri . 'hero-valjevsko-' . $vp_hero_width . '.webp' ) . ' ' . $vp_hero_width . 'w';
}

foreach ( array( 320, 430, 640, 768 ) as $vp_hero_width ) {
	foreach ( array( 'avif', 'webp' ) as $vp_hero_type ) {
		$vp_hero_file = $vp_hero_dir . 'hero-valjevsko-mobile-' . $vp_hero_width . '.' . $vp_hero_type;

		if ( ! is_readable( $vp_hero_file ) ) {
			continue;
		}

		$vp_mobile_set[ $vp_hero_type ][] = esc_url( $vp_hero_uri . 'hero-valjevsko-mobile-' . $vp_hero_width . '.' . $vp_hero_type ) . ' ' . $vp_hero_width . 'w';
	}
}
?>
<section class="vp-hero" aria-labelledby="vp-hero-title">
	<picture>
		<?php if ( ! empty( $vp_mobile_set['avif'] ) ) : ?>
			<source
				media="(max-width: 47.99em)"
				type="image/avif"
				srcset="<?php echo esc_attr( implode( ', ', $vp_mobile_set['avif'] ) ); ?>"
				sizes="100vw"
			>
		<?php endif; ?>
		<?php if ( ! empty( $vp_mobile_set['webp'] ) ) : ?>
			<source
				media="(max-width: 47.99em)"
				type="image/webp"
				srcset="<?php echo esc_attr( implode( ', ', $vp_mobile_set['webp'] ) ); ?>"
				sizes="100vw"
			>
		<?php endif; ?>
		<img
			class="vp-hero__image"
			src="<?php echo esc_url( $vp_hero_uri . 'hero-valjevsko-1024.webp' ); ?>"
			<?php if ( ! empty( $vp_desktop_set ) ) : ?>
				srcset="<?php echo esc_attr( implode( ', ', $vp_desktop_set ) ); ?>"
				sizes="100vw"
			<?php endif; ?>
			width="1024"
			height="691"
			alt="<?php echo esc_attr__( 'Valjevsko beer bottle and can on a wooden table in the brewery cellar', 'valjevska-pivara' ); ?>"
			loading="eager"
			fetchpriority="high"
		>
	</picture>

	<div class="vp-hero__overlay">
		<div class="vp-hero__copy">
			<div class="vp-hero__content">
				<h1 id="vp-hero-title" class="vp-hero__title">
					<span class="vp-hero__title-accent"><?php echo esc_html__( '160 GODINA', 'valjevska-pivara' ); ?></span>
					<span class="vp-hero__title-text"><?php echo esc_html__( 'VALJEVSKE', 'valjevska-pivara' ); ?></span>
					<span class="vp-hero__title-text"><?php echo esc_html__( 'PIVARE', 'valjevska-pivara' ); ?></span>
				</h1>
				<p class="vp-hero__lead">
					<?php
					echo esc_html__(
						'Razvojni put Valjevska pivara, dug punih 160 godina, nije samo istorija jednog preduzeća – to je priča o generacijama koje su gradile, čuvale i unapređivale tradiciju. Danas, taj jubilej nosi i ponos i odgovornost da se nasleđe sačuva i ostavi još snažnije budućim generacijama.',
						'valjevska-pivara'
					);
					?>
				</p>
			</div>

			<div class="vp-hero__social-row">
				<span class="vp-hero__rule" aria-hidden="true"></span>
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
	</div>
</section>

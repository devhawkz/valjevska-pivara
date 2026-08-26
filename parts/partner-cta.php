<?php
/**
 * Homepage V1 partner CTA: full-width image with centered contact actions.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vp_partner_dir  = get_stylesheet_directory() . '/assets/images/';
$vp_partner_uri  = get_stylesheet_directory_uri() . '/assets/images/';
$vp_partner_base = 'partner-cta';
$vp_partner_webp = $vp_partner_dir . $vp_partner_base . '-970.webp';
$vp_partner_set  = array(
	'avif' => array(),
	'webp' => array(),
);

if ( ! is_readable( $vp_partner_webp ) ) {
	return;
}

foreach ( array( 320, 480, 640, 768, 970 ) as $vp_partner_width ) {
	foreach ( array( 'avif', 'webp' ) as $vp_partner_type ) {
		$vp_partner_file = $vp_partner_dir . $vp_partner_base . '-' . $vp_partner_width . '.' . $vp_partner_type;

		if ( ! is_readable( $vp_partner_file ) ) {
			continue;
		}

		$vp_partner_set[ $vp_partner_type ][] = esc_url( $vp_partner_uri . $vp_partner_base . '-' . $vp_partner_width . '.' . $vp_partner_type ) . ' ' . $vp_partner_width . 'w';
	}
}

$vp_partner_email  = valjevska_pivara_get_contact_email();
$vp_partner_mailto = '';
$vp_partner_tel    = valjevska_pivara_get_contact_tel();

if ( '' !== $vp_partner_email ) {
	$vp_partner_mailto = 'mailto:' . $vp_partner_email;
}
?>
<section class="vp-partner-cta" aria-labelledby="vp-partner-cta-title">
	<picture class="vp-partner-cta__media">
		<?php if ( ! empty( $vp_partner_set['avif'] ) ) : ?>
			<source
				type="image/avif"
				srcset="<?php echo esc_attr( implode( ', ', $vp_partner_set['avif'] ) ); ?>"
				sizes="100vw"
			>
		<?php endif; ?>
		<?php if ( ! empty( $vp_partner_set['webp'] ) ) : ?>
			<source
				type="image/webp"
				srcset="<?php echo esc_attr( implode( ', ', $vp_partner_set['webp'] ) ); ?>"
				sizes="100vw"
			>
		<?php endif; ?>
		<img
			class="vp-partner-cta__image"
			src="<?php echo esc_url( $vp_partner_uri . $vp_partner_base . '-970.webp' ); ?>"
			width="970"
			height="380"
			alt=""
			loading="lazy"
			decoding="async"
		>
	</picture>

	<div class="vp-partner-cta__inner vp-container">
		<div class="vp-partner-cta__header">
			<h2 id="vp-partner-cta-title" class="vp-partner-cta__title"><?php echo esc_html__( 'POSTANITE PARTNER PIVARE', 'valjevska-pivara' ); ?></h2>
			<p class="vp-partner-cta__body">
				<?php
				echo esc_html__(
					'Lorem ipsum dolor sit amet consectetur. Lectus donec posuere molestie in tristique vivamus ut.',
					'valjevska-pivara'
				);
				echo '<br>';
				echo esc_html__(
					'Justo eget eget donec molestie fringilla commodo a.',
					'valjevska-pivara'
				);
				?>
			</p>
		</div>
		<div class="vp-partner-cta__content">
			<p class="vp-partner-cta__subtitle"><?php echo esc_html__( 'POZOVITE NAS ILI NAM PIŠITE:', 'valjevska-pivara' ); ?></p>
			<?php if ( '' !== $vp_partner_tel || '' !== $vp_partner_mailto ) : ?>
				<div class="vp-partner-cta__actions">
					<?php if ( '' !== $vp_partner_tel ) : ?>
						<a class="vp-button vp-button--primary vp-partner-cta__button" href="<?php echo esc_url( $vp_partner_tel, array( 'tel' ) ); ?>">
							<?php echo esc_html__( 'Pozovite nas', 'valjevska-pivara' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( '' !== $vp_partner_mailto ) : ?>
						<a class="vp-button vp-button--secondary vp-partner-cta__button" href="<?php echo esc_url( $vp_partner_mailto, array( 'mailto' ) ); ?>">
							<?php echo esc_html( $vp_partner_email ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

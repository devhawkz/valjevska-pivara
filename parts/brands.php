<?php
/**
 * Homepage V1 “Naši brendovi” slideshow.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'vp_brands_get_slides' ) ) {
	return;
}

/**
 * Theme AVIF/WebP/PNG wordmark for a seeded brand logo key.
 *
 * @param string $slug Brand slug without the -logo suffix.
 * @return string
 */
if ( ! function_exists( 'valjevska_pivara_get_brand_logo_html' ) ) {
	function valjevska_pivara_get_brand_logo_html( $slug ) {
		$dimensions = array(
			'valjevsko'  => array( 134, 76 ),
			'jagodinsko' => array( 273, 156 ),
			'eichinger'  => array( 273, 156 ),
		);

		if ( ! isset( $dimensions[ $slug ] ) ) {
			return '';
		}

		$theme_dir = trailingslashit( get_stylesheet_directory() );
		$theme_uri = trailingslashit( get_stylesheet_directory_uri() );

		if ( 'valjevsko' === $slug && is_readable( $theme_dir . 'assets/images/logo-valjevsko.svg' ) ) {
			return sprintf(
				'<img class="vp-brands__logo" src="%1$s" width="134" height="76" alt="" loading="lazy" decoding="async">',
				esc_url( $theme_uri . 'assets/images/logo-valjevsko.svg' )
			);
		}

		$directory = $theme_dir . 'assets/images/brands/';
		$uri       = $theme_uri . 'assets/images/brands/';
		$png_path  = $directory . $slug . '-logo.png';

		if ( ! is_readable( $png_path ) ) {
			return '';
		}

		$avif_path = $directory . $slug . '-logo.avif';
		$webp_path = $directory . $slug . '-logo.webp';
		$width     = (string) $dimensions[ $slug ][0];
		$height    = (string) $dimensions[ $slug ][1];

		ob_start();
		?>
		<picture>
			<?php if ( is_readable( $avif_path ) ) : ?>
				<source type="image/avif" srcset="<?php echo esc_url( $uri . $slug . '-logo.avif' ); ?>">
			<?php endif; ?>
			<?php if ( is_readable( $webp_path ) ) : ?>
				<source type="image/webp" srcset="<?php echo esc_url( $uri . $slug . '-logo.webp' ); ?>">
			<?php endif; ?>
			<img
				class="vp-brands__logo"
				src="<?php echo esc_url( $uri . $slug . '-logo.png' ); ?>"
				width="<?php echo esc_attr( $width ); ?>"
				height="<?php echo esc_attr( $height ); ?>"
				alt=""
				loading="lazy"
				decoding="async"
			>
		</picture>
		<?php

		$html = ob_get_clean();

		return is_string( $html ) ? $html : '';
	}
}

$vp_brands_slides = vp_brands_get_slides();

if ( empty( $vp_brands_slides ) ) {
	return;
}

$vp_brands_total  = count( $vp_brands_slides );
$vp_brands_slider = $vp_brands_total > 1;
$vp_brands_arrow  = '<svg class="vp-brands__arrow" width="22" height="10" viewBox="0 0 22 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M0 5h16" stroke="currentColor" stroke-width="1.25"/><path d="M13.5 1.25 19.5 5l-6 3.75" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$vp_brands_prev   = '<svg class="vp-brands__chevron" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M10 2 2 10l8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$vp_brands_next   = '<svg class="vp-brands__chevron" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M2 2l8 8-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<section
	class="vp-brands<?php echo $vp_brands_slider ? '' : ' vp-brands--static'; ?>"
	aria-labelledby="vp-brands-title"
	data-vp-brands
	data-vp-a11y-prev="<?php echo esc_attr__( 'Prethodni brend', 'valjevska-pivara' ); ?>"
	data-vp-a11y-next="<?php echo esc_attr__( 'Sledeći brend', 'valjevska-pivara' ); ?>"
	data-vp-a11y-first="<?php echo esc_attr__( 'Ovo je prvi slajd', 'valjevska-pivara' ); ?>"
	data-vp-a11y-last="<?php echo esc_attr__( 'Ovo je poslednji slajd', 'valjevska-pivara' ); ?>"
	data-vp-a11y-bullet="<?php echo esc_attr__( 'Idi na slajd {{index}}', 'valjevska-pivara' ); ?>"
	data-vp-a11y-slide="<?php echo esc_attr__( '{{index}} / {{slidesLength}}', 'valjevska-pivara' ); ?>"
>
	<h2 id="vp-brands-title" class="vp-visually-hidden">
		<?php echo esc_html__( 'Naši brendovi', 'valjevska-pivara' ); ?>
	</h2>

	<div class="vp-brands__frame vp-container">
		<div class="vp-brands__swiper" data-vp-brands-swiper>
		<div class="vp-brands__wrapper">
			<?php foreach ( $vp_brands_slides as $vp_brands_slide ) : ?>
				<?php
				$vp_brands_image_class = 'vp-brands__image';

				if ( preg_match( '/^(valjevsko|jagodinsko|eichinger|sirupi)-slide$/', $vp_brands_slide['image_key'], $vp_brands_match ) ) {
					$vp_brands_image_class .= ' vp-brands__image--' . $vp_brands_match[1];
				}

				$vp_brands_image = wp_get_attachment_image(
					$vp_brands_slide['image_id'],
					'full',
					false,
					array(
						'class'    => $vp_brands_image_class,
						'alt'      => $vp_brands_slide['title'],
						'loading'  => 'lazy',
						'decoding' => 'async',
							'sizes'    => '(min-width: 48em) 50vw, 100vw',
					)
				);

				if ( '' === $vp_brands_image ) {
					continue;
				}

				$vp_brands_logo     = '';
				$vp_brands_logo_key = isset( $vp_brands_slide['logo_key'] ) ? $vp_brands_slide['logo_key'] : '';

				if ( preg_match( '/^(valjevsko|jagodinsko|eichinger)-logo$/', $vp_brands_logo_key, $vp_brands_logo_match ) ) {
					$vp_brands_logo = valjevska_pivara_get_brand_logo_html( $vp_brands_logo_match[1] );
				} elseif ( $vp_brands_slide['logo_id'] > 0 ) {
					$vp_brands_logo = wp_get_attachment_image(
						$vp_brands_slide['logo_id'],
						'full',
						false,
						array(
							'class'    => 'vp-brands__logo',
							'alt'      => '',
							'loading'  => 'lazy',
							'decoding' => 'async',
						)
					);
				}

				$vp_brands_body = function_exists( 'vp_brands_get_body_html' )
					? vp_brands_get_body_html( $vp_brands_slide['content'] )
					: '';

				$vp_brands_cta_label = $vp_brands_slide['cta_label'];
				$vp_brands_cta_url   = $vp_brands_slide['cta_url'];

				if ( '' === $vp_brands_cta_label ) {
					$vp_brands_cta_label = __( 'Pogledaj više', 'valjevska-pivara' );
				}

				if ( '' === $vp_brands_cta_url || '#' === $vp_brands_cta_url ) {
					$vp_brands_cta_url = home_url( '/' );
				}

				$vp_brands_show_cta = '' !== $vp_brands_cta_url && '' !== $vp_brands_cta_label;
				?>
				<div class="swiper-slide vp-brands__slide">
					<div class="vp-brands__panel vp-brands__panel--content">
						<?php if ( '' !== $vp_brands_slide['heading'] || '' !== $vp_brands_body ) : ?>
							<div class="vp-brands__intro">
								<?php if ( '' !== $vp_brands_slide['heading'] ) : ?>
									<div class="vp-brands__heading-wrap">
										<p class="vp-brands__heading"><?php echo esc_html( $vp_brands_slide['heading'] ); ?></p>
									</div>
								<?php endif; ?>
								<?php if ( '' !== $vp_brands_body ) : ?>
									<div class="vp-brands__body">
										<?php echo $vp_brands_body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses_post() in vp_brands_get_body_html(). ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( '' !== $vp_brands_logo ) : ?>
							<div class="vp-brands__logo-wrap">
								<?php echo $vp_brands_logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme picture or wp_get_attachment_image(). ?>
							</div>
						<?php endif; ?>
						<?php if ( $vp_brands_show_cta ) : ?>
							<div class="vp-brands__actions">
								<a class="vp-button vp-button--primary vp-brands__cta" href="<?php echo esc_url( $vp_brands_cta_url ); ?>">
									<span><?php echo esc_html( $vp_brands_cta_label ); ?></span>
									<?php echo $vp_brands_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
					<div class="vp-brands__panel vp-brands__panel--media">
						<picture>
							<?php echo $vp_brands_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image(). ?>
						</picture>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

		<?php if ( $vp_brands_slider ) : ?>
			<div class="vp-brands__ui">
				<button type="button" class="vp-brands__control vp-brands__control--prev" data-vp-brands-prev hidden disabled>
					<span class="vp-visually-hidden"><?php echo esc_html__( 'Prethodni brend', 'valjevska-pivara' ); ?></span>
					<?php echo $vp_brands_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				</button>
				<button type="button" class="vp-brands__control vp-brands__control--next" data-vp-brands-next>
					<span class="vp-visually-hidden"><?php echo esc_html__( 'Sledeći brend', 'valjevska-pivara' ); ?></span>
					<?php echo $vp_brands_next; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				</button>
				<div class="vp-brands__pagination" data-vp-brands-pagination></div>
			</div>
		<?php endif; ?>
	</div>
</section>

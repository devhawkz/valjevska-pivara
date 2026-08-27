<?php
/**
 * Homepage V1 recognized-quality section: heading and six feature cards.
 *
 * SVG markup is inlined from sanitized theme icons. Files are not read
 * at runtime.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vp_quality_svg = array(
	'hourglass' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48" fill="none" aria-hidden="true" focusable="false"><path d="M12 7h24M12 41h24M16 7 24 24 32 7M16 41 24 24 32 41" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 15h11M18.5 33h11" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/><circle cx="24" cy="24" r="1.6" fill="currentColor"/></svg>',
	'hops'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48" fill="none" aria-hidden="true" focusable="false"><path d="M24 8 18 4M24 8l6-4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 10c-6 1.2-9.5 7-9.5 13.2 0 8.3 9.5 20.3 9.5 20.3s9.5-12 9.5-20.3C33.5 17 30 11.2 24 10Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"/><path d="M24 10v33.5M16.8 18.5c2.4-2 5.6-2.8 7.2-2.8s4.8.8 7.2 2.8M15.8 25c2.8-2.4 5.8-3.4 8.2-3.4s5.4 1 8.2 3.4M17.2 31.5c2.2-1.8 4.6-2.6 6.8-2.6s4.6.8 6.8 2.6M19.5 37.2c1.5-1.1 3-1.6 4.5-1.6s3 .5 4.5 1.6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	'chip'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48" fill="none" aria-hidden="true" focusable="false"><rect x="14" y="14" width="20" height="20" rx="1.5" stroke="currentColor" stroke-width="1.75"/><rect x="19" y="19" width="10" height="10" stroke="currentColor" stroke-width="1.75"/><path d="M18 14V8M22.7 14V8M27.3 14V8M32 14V8M18 40v-6M22.7 40v-6M27.3 40v-6M32 40v-6M14 18H8M14 22.7H8M14 27.3H8M14 32H8M40 18h-6M40 22.7h-6M40 27.3h-6M40 32h-6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>',
	'flask'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48" fill="none" aria-hidden="true" focusable="false"><path d="M20 6h8M20 6v10L11 40h26L28 16V6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 30h16" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>',
	'location'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48" fill="none" aria-hidden="true" focusable="false"><path d="M24 6c-7.2 0-13 5.6-13 13.2 0 9.6 13 22.8 13 22.8s13-13.2 13-22.8C37 11.6 31.2 6 24 6Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"/><circle cx="24" cy="19" r="5.25" stroke="currentColor" stroke-width="1.75"/></svg>',
);

$vp_quality_items = array(
	array(
		'icon'   => '',
		'number' => '160',
		'title'  => array(
			__( 'Tradicija', 'valjevska-pivara' ),
			__( '160+ godina', 'valjevska-pivara' ),
		),
		'text'   => __( 'Znanje i iskustvo koje se prenosi generacijama.', 'valjevska-pivara' ),
	),
	array(
		'icon'   => 'hourglass',
		'number' => '',
		'title'  => array(
			__( 'Prirodno', 'valjevska-pivara' ),
			__( 'odležavanje', 'valjevska-pivara' ),
		),
		'text'   => __( 'Dovoljno vremena da razvije pun i uravnotežen ukus.', 'valjevska-pivara' ),
	),
	array(
		'icon'   => 'hops',
		'number' => '',
		'title'  => array(
			__( 'Kvalitetni', 'valjevska-pivara' ),
			__( 'sastojci', 'valjevska-pivara' ),
		),
		'text'   => __( 'Odabrani slad, hmelj, kvasac i čista voda.', 'valjevska-pivara' ),
	),
	array(
		'icon'   => 'chip',
		'number' => '',
		'title'  => array(
			__( 'Savremena', 'valjevska-pivara' ),
			__( 'proizvodnja', 'valjevska-pivara' ),
		),
		'text'   => __( 'Spoj tradicionalne recepture i moderne tehnologije.', 'valjevska-pivara' ),
	),
	array(
		'icon'   => 'flask',
		'number' => '',
		'title'  => array(
			__( 'Kontrola', 'valjevska-pivara' ),
			__( 'kvaliteta', 'valjevska-pivara' ),
		),
		'text'   => __( 'Dosledan standard u svakoj seriji.', 'valjevska-pivara' ),
	),
	array(
		'icon'   => 'location',
		'number' => '',
		'title'  => array(
			__( 'Lokalni', 'valjevska-pivara' ),
			__( 'ponos', 'valjevska-pivara' ),
		),
		'text'   => __( 'Proizvedeno u Valjevu, uz poštovanje pivarske tradicije.', 'valjevska-pivara' ),
	),
);
?>
<section class="vp-quality" aria-labelledby="vp-quality-title">
	<div class="vp-quality__inner vp-container">
		<h2 id="vp-quality-title" class="vp-quality__title">
			<span class="vp-quality__title-accent"><?php echo esc_html__( 'KVALITET', 'valjevska-pivara' ); ?></span>
			<span class="vp-quality__title-text"><?php echo esc_html__( 'KOJI SE PREPOZNAJE', 'valjevska-pivara' ); ?></span>
		</h2>

		<ul class="vp-quality__grid">
			<?php foreach ( $vp_quality_items as $vp_quality_item ) : ?>
				<li class="vp-quality__item">
					<div class="vp-quality__lead">
						<span class="vp-quality__mark">
							<?php if ( '' !== $vp_quality_item['number'] ) : ?>
								<span class="vp-quality__number"><?php echo esc_html( $vp_quality_item['number'] ); ?></span>
							<?php elseif ( isset( $vp_quality_svg[ $vp_quality_item['icon'] ] ) ) : ?>
								<span class="vp-quality__icon">
									<?php echo $vp_quality_svg[ $vp_quality_item['icon'] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized theme SVG markup. ?>
								</span>
							<?php endif; ?>
						</span>
						<h3 class="vp-quality__item-title">
							<?php foreach ( $vp_quality_item['title'] as $vp_quality_line ) : ?>
								<span class="vp-quality__item-title-line"><?php echo esc_html( $vp_quality_line ); ?></span>
							<?php endforeach; ?>
						</h3>
					</div>
					<p class="vp-quality__item-text"><?php echo esc_html( $vp_quality_item['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

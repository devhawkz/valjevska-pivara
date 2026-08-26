<?php
/**
 * Homepage V1 traditional method section: image column and content column.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vp_tm_dir  = get_stylesheet_directory() . '/assets/images/';
$vp_tm_uri  = get_stylesheet_directory_uri() . '/assets/images/';
$vp_tm_base = 'traditional-method-hops';
$vp_tm_webp = $vp_tm_dir . $vp_tm_base . '-699.webp';
$vp_tm_set  = array(
	'avif' => array(),
	'webp' => array(),
);

if ( ! is_readable( $vp_tm_webp ) ) {
	return;
}

foreach ( array( 320, 480, 640, 699 ) as $vp_tm_width ) {
	foreach ( array( 'avif', 'webp' ) as $vp_tm_type ) {
		$vp_tm_file = $vp_tm_dir . $vp_tm_base . '-' . $vp_tm_width . '.' . $vp_tm_type;

		if ( ! is_readable( $vp_tm_file ) ) {
			continue;
		}

		$vp_tm_set[ $vp_tm_type ][] = esc_url( $vp_tm_uri . $vp_tm_base . '-' . $vp_tm_width . '.' . $vp_tm_type ) . ' ' . $vp_tm_width . 'w';
	}
}

$vp_tm_cta_url  = '';
$vp_tm_home_url = untrailingslashit( home_url( '/' ) );
$vp_tm_slugs    = array(
	'o-nama',
	'o-pivari',
	'tradicija',
	'tradicionalna-metoda',
	'istorija',
	'about',
	'about-us',
);

foreach ( $vp_tm_slugs as $vp_tm_slug ) {
	$vp_tm_page = get_page_by_path( $vp_tm_slug );

	if ( ! $vp_tm_page instanceof WP_Post || 'publish' !== get_post_status( $vp_tm_page ) ) {
		continue;
	}

	$vp_tm_permalink = get_permalink( $vp_tm_page );

	if ( ! is_string( $vp_tm_permalink ) || '' === $vp_tm_permalink ) {
		continue;
	}

	if ( untrailingslashit( $vp_tm_permalink ) === $vp_tm_home_url ) {
		continue;
	}

	$vp_tm_cta_url = $vp_tm_permalink;
	break;
}

if ( '' === $vp_tm_cta_url ) {
	$vp_tm_title_query = new WP_Query(
		array(
			'post_type'              => 'page',
			'post_status'            => 'publish',
			'title'                  => 'O nama',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! empty( $vp_tm_title_query->posts ) && $vp_tm_title_query->posts[0] instanceof WP_Post ) {
		$vp_tm_permalink = get_permalink( $vp_tm_title_query->posts[0] );

		if ( is_string( $vp_tm_permalink ) && '' !== $vp_tm_permalink && untrailingslashit( $vp_tm_permalink ) !== $vp_tm_home_url ) {
			$vp_tm_cta_url = $vp_tm_permalink;
		}
	}

	wp_reset_postdata();
}

if ( '' === $vp_tm_cta_url ) {
	$vp_tm_locations = get_nav_menu_locations();

	if ( is_array( $vp_tm_locations ) ) {
		foreach ( $vp_tm_locations as $vp_tm_menu_id ) {
			$vp_tm_menu_id = absint( $vp_tm_menu_id );

			if ( $vp_tm_menu_id < 1 ) {
				continue;
			}

			$vp_tm_items = wp_get_nav_menu_items( $vp_tm_menu_id );

			if ( empty( $vp_tm_items ) || ! is_array( $vp_tm_items ) ) {
				continue;
			}

			foreach ( $vp_tm_items as $vp_tm_item ) {
				if ( ! $vp_tm_item instanceof WP_Post ) {
					continue;
				}

				$vp_tm_item_title = trim( wp_strip_all_tags( $vp_tm_item->title ) );

				if ( 0 !== strcasecmp( $vp_tm_item_title, 'O nama' ) ) {
					continue;
				}

				$vp_tm_item_url = isset( $vp_tm_item->url ) ? $vp_tm_item->url : '';

				if ( ! is_string( $vp_tm_item_url ) || '' === $vp_tm_item_url ) {
					continue;
				}

				$vp_tm_cta_url = $vp_tm_item_url;
				break 2;
			}
		}
	}
}

if ( '' === $vp_tm_cta_url ) {
	$vp_tm_cta_url = $vp_tm_home_url . '/';
}
?>
<section class="vp-traditional" aria-labelledby="vp-traditional-title">
	<div class="vp-traditional__layout">
		<div class="vp-traditional__media">
			<picture>
				<?php if ( ! empty( $vp_tm_set['avif'] ) ) : ?>
					<source
						type="image/avif"
						srcset="<?php echo esc_attr( implode( ', ', $vp_tm_set['avif'] ) ); ?>"
						sizes="(min-width: 64em) 50vw, 100vw"
					>
				<?php endif; ?>
				<?php if ( ! empty( $vp_tm_set['webp'] ) ) : ?>
					<source
						type="image/webp"
						srcset="<?php echo esc_attr( implode( ', ', $vp_tm_set['webp'] ) ); ?>"
						sizes="(min-width: 64em) 50vw, 100vw"
					>
				<?php endif; ?>
				<img
					class="vp-traditional__image"
					src="<?php echo esc_url( $vp_tm_uri . $vp_tm_base . '-699.webp' ); ?>"
					width="699"
					height="995"
					alt="<?php echo esc_attr__( 'Fresh hops in a burlap sack beside a wooden barrel at Valjevska pivara', 'valjevska-pivara' ); ?>"
					loading="lazy"
					decoding="async"
				>
			</picture>
		</div>

		<div class="vp-traditional__body">
			<div class="vp-traditional__header">
				<div class="vp-traditional__eyebrow-row">
					<p class="vp-traditional__eyebrow"><?php echo esc_html__( 'Kvalitet i tradicija', 'valjevska-pivara' ); ?></p>
					<span class="vp-traditional__eyebrow-line" aria-hidden="true"></span>
				</div>
				<p class="vp-traditional__accent"><?php echo esc_html__( 'Od 1860.', 'valjevska-pivara' ); ?></p>
				<h2 id="vp-traditional-title" class="vp-traditional__title">
					<span class="vp-traditional__title-line"><?php echo esc_html__( 'TRADICIONALNA', 'valjevska-pivara' ); ?></span>
					<span class="vp-traditional__title-line"><?php echo esc_html__( 'METODA', 'valjevska-pivara' ); ?></span>
				</h2>
				<p class="vp-traditional__intro">
					<?php
					echo esc_html__(
						'Naša piva nastaju sporim procesom fermentacije i odležavanja, uz pažljivo kontrolisane uslove proizvodnje. Korišćenjem klasičnih pivarskih tehnika i prirodnih sastojaka, čuva se autentičan karakter lager piva.',
						'valjevska-pivara'
					);
					?>
				</p>
			</div>

			<div class="vp-traditional__features">
				<div class="vp-traditional__feature">
					<h3 class="vp-traditional__feature-title"><?php echo esc_html__( 'IZVORSKA VODA', 'valjevska-pivara' ); ?></h3>
					<p class="vp-traditional__feature-text">
						<?php
						echo esc_html__(
							'Voda je osnovna sirovina u proizvodnji piva i značajno utiče na njegov ukus. Valjevska pivara koristi vodu sa izvora iz valjevskih planina, čija čistoća i mineralni sastav doprinose kvalitetu lager piva. Ona predstavlja deo identiteta Valjevskog piva i povezuje ga sa krajem iz kojeg potiče.',
							'valjevska-pivara'
						);
						?>
					</p>
				</div>
				<div class="vp-traditional__feature">
					<h3 class="vp-traditional__feature-title"><?php echo esc_html__( 'NAŠ KVASAC', 'valjevska-pivara' ); ?></h3>
					<p class="vp-traditional__feature-text">
						<?php
						echo esc_html__(
							'Kvasac ima ključnu ulogu u proizvodnji piva jer pretvara šećere u alkohol i ugljen-dioksid i utiče na aromu i ukus. Posebnost Valjevske pivare je sopstvena kultura kvasca koja se održava više od 50 godina i predstavlja važan deo njenog pivarskog identiteta.',
							'valjevska-pivara'
						);
						?>
					</p>
				</div>
			</div>

			<div class="vp-traditional__actions">
				<a class="vp-button vp-button--primary vp-traditional__cta" href="<?php echo esc_url( $vp_tm_cta_url ); ?>">
					<span><?php echo esc_html__( 'Saznaj više', 'valjevska-pivara' ); ?></span>
					<svg class="vp-traditional__arrow" width="22" height="10" viewBox="0 0 22 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<path d="M0 5h16" stroke="currentColor" stroke-width="1.25"/>
						<path d="M13.5 1.25 19.5 5l-6 3.75" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</a>
			</div>
		</div>
	</div>
</section>

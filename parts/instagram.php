<?php
/**
 * Homepage V1 Instagram posts section.
 *
 * Renders selected locally cached posts, or placeholder cards until
 * Instagram is synchronized. Does not call the Instagram API.
 *
 * @package Valjevska_Pivara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! valjevska_pivara_is_instagram_section_enabled() ) {
	return;
}

$vp_instagram_cards = array();
$vp_instagram_ids   = valjevska_pivara_get_instagram_selected_post_ids();

if ( ! empty( $vp_instagram_ids ) && function_exists( 'vp_instagram_feed_get_display_posts' ) ) {
	$vp_instagram_posts = vp_instagram_feed_get_display_posts( $vp_instagram_ids );

	foreach ( $vp_instagram_posts as $vp_instagram_post ) {
		$vp_instagram_image = wp_get_attachment_image(
			$vp_instagram_post['attachment_id'],
			'medium_large',
			false,
			array(
				'class'    => 'vp-instagram__image',
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => '(min-width: 64em) 28rem, (min-width: 30em) 44vw, 92vw',
				'alt'      => '',
			)
		);

		if ( '' === $vp_instagram_image ) {
			continue;
		}

		$vp_instagram_avatar = '';

		if ( $vp_instagram_post['profile_attachment_id'] > 0 ) {
			$vp_instagram_avatar = wp_get_attachment_image(
				$vp_instagram_post['profile_attachment_id'],
				'thumbnail',
				false,
				array(
					'class'    => 'vp-instagram__avatar',
					'loading'  => 'lazy',
					'decoding' => 'async',
					'alt'      => '',
				)
			);
		}

		$vp_instagram_cards[] = array(
			'permalink' => $vp_instagram_post['permalink'],
			'username'  => $vp_instagram_post['username'],
			'image'     => $vp_instagram_image,
			'avatar'    => $vp_instagram_avatar,
			'label'     => sprintf(
				/* translators: %s: Instagram username */
				__( 'Instagram post by %s (opens in a new tab)', 'valjevska-pivara' ),
				$vp_instagram_post['username']
			),
		);
	}
}

if ( empty( $vp_instagram_cards ) ) {
	$vp_instagram_cards = valjevska_pivara_get_instagram_placeholder_cards();
}

if ( empty( $vp_instagram_cards ) ) {
	return;
}
?>
<section class="vp-instagram" aria-labelledby="vp-instagram-title">
	<div class="vp-instagram__inner vp-container">
		<h2 id="vp-instagram-title" class="vp-instagram__title">
			<?php echo esc_html__( 'Instagram', 'valjevska-pivara' ); ?>
		</h2>
		<ul class="vp-instagram__grid">
			<?php foreach ( $vp_instagram_cards as $vp_instagram_card ) : ?>
				<li class="vp-instagram__item">
					<a
						class="vp-instagram__card"
						href="<?php echo esc_url( $vp_instagram_card['permalink'] ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( $vp_instagram_card['label'] ); ?>"
					>
						<span class="vp-instagram__media">
							<?php echo $vp_instagram_card['image']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() or escaped theme markup. ?>
						</span>
						<span class="vp-instagram__author">
							<?php if ( '' !== $vp_instagram_card['avatar'] ) : ?>
								<?php echo $vp_instagram_card['avatar']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() or escaped theme markup. ?>
							<?php endif; ?>
							<span class="vp-instagram__username"><?php echo esc_html( $vp_instagram_card['username'] ); ?></span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

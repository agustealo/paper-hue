<?php
/**
 * Featured-image helpers and fallback-image support.
 *
 * @package Paper_Hue
 */

/**
 * Add Paper Hue image sizes to the Media Library size selector.
 *
 * @param array<string,string> $sizes Registered image sizes.
 * @return array<string,string>
 */
function paper_hue_custom_image_sizes( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'medium-width'    => esc_html__( 'Medium Width', 'paper-hue' ),
			'medium-height'   => esc_html__( 'Medium Height', 'paper-hue' ),
			'thumbnail-large' => esc_html__( 'Large Thumbnail', 'paper-hue' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'paper_hue_custom_image_sizes' );

/**
 * Return the configured fallback featured-image URL.
 *
 * @return string
 */
function paper_hue_get_fallback_image_url() {
	$attachment_id = absint( get_theme_mod( 'theme_feat_image', 0 ) );

	if ( $attachment_id ) {
		$url = wp_get_attachment_image_url( $attachment_id, 'full' );

		if ( $url ) {
			return $url;
		}
	}

	return get_template_directory_uri() . '/client-side/img/paper-hue-fallback.svg';
}

/**
 * Return a featured-image URL for a post, falling back to the theme image.
 *
 * @param string $image_size Image size name.
 * @param int    $post_id    Optional post ID. Defaults to the current post.
 * @return string
 */
function paper_hue_get_image_url( $image_size = 'featured-post-image', $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	if ( $post_id && has_post_thumbnail( $post_id ) ) {
		$url = get_the_post_thumbnail_url( $post_id, $image_size );

		if ( $url ) {
			return $url;
		}
	}

	return paper_hue_get_fallback_image_url();
}

/**
 * Return featured-image HTML for a post, falling back to the theme image.
 *
 * @param string $image_size Image size name.
 * @param int    $post_id    Optional post ID. Defaults to the current post.
 * @return string
 */
function paper_hue_get_image_html( $image_size = 'featured-post-image', $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	if ( $post_id && has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail(
			$post_id,
			$image_size,
			array(
				'class' => 'attachment-post-thumbnail size-post-thumbnail wp-post-image',
			)
		);
	}

	return sprintf(
		'<img src="%1$s" class="attachment-post-thumbnail size-post-thumbnail wp-post-image fallback-image" alt="" loading="lazy" decoding="async" />',
		esc_url( paper_hue_get_fallback_image_url() )
	);
}

/**
 * Backward-compatible alias for the original fallback helper.
 *
 * Child themes may already call this function, so keep its public contract.
 *
 * @return string
 */
function static_fallback_img() {
	return paper_hue_get_fallback_image_url();
}

/**
 * Backward-compatible image renderer used by existing Paper Hue templates.
 *
 * @param string $image_type wrapped, noWrap, or url.
 * @param string $image_size Registered image size.
 * @return void
 */
function get_hue_image( $image_type = 'noWrap', $image_size = 'featured-post-image' ) {
	$post_id = get_the_ID();

	if ( 'url' === $image_type ) {
		echo esc_url( paper_hue_get_image_url( $image_size, $post_id ) );
		return;
	}

	$image = paper_hue_get_image_html( $image_size, $post_id );

	if ( 'wrapped' === $image_type ) {
		printf(
			'<div class="hue_img_wrapper posts_image featured_image%1$s"><a href="%2$s">%3$s</a></div>',
			has_post_thumbnail( $post_id ) ? '' : ' fallback-img',
			esc_url( get_permalink( $post_id ) ),
			wp_kses_post( $image )
		);
		return;
	}

	echo wp_kses_post( $image );
}

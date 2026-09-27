<?php
/**
 * Custom template tags for Paper Hue.
 *
 * @package Paper_Hue
 */

if ( ! function_exists( 'paper_hue_posted_on' ) ) :
	/**
	 * Print post date/time metadata.
	 *
	 * @return void
	 */
	function paper_hue_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: linked post date. */
			esc_html_x( 'Posted on %s', 'post date', 'paper-hue' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . wp_kses_post( $posted_on ) . '</span>';
	}
endif;

if ( ! function_exists( 'paper_hue_posted_by' ) ) :
	/**
	 * Print author metadata.
	 *
	 * @return void
	 */
	function paper_hue_posted_by() {
		$byline = sprintf(
			/* translators: %s: linked post author. */
			esc_html_x( 'by %s', 'post author', 'paper-hue' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . wp_kses_post( $byline ) . '</span>';
	}
endif;

if ( ! function_exists( 'paper_hue_entry_footer' ) ) :
	/**
	 * Print categories, tags, comments, and edit links.
	 *
	 * @return void
	 */
	function paper_hue_entry_footer() {
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( esc_html__( ', ', 'paper-hue' ) );
			if ( $categories_list ) {
				printf(
					'<span class="cat-links">%s</span>',
					wp_kses_post(
						sprintf(
							/* translators: %s: linked list of post categories. */
							esc_html__( 'Posted in %s', 'paper-hue' ),
							$categories_list
						)
					)
				);
			}

			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'paper-hue' ) );
			if ( $tags_list ) {
				printf(
					'<span class="tags-links">%s</span>',
					wp_kses_post(
						sprintf(
							/* translators: %s: linked list of post tags. */
							esc_html__( 'Tagged %s', 'paper-hue' ),
							$tags_list
						)
					)
				);
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title. */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'paper-hue' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					esc_html( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: current post title, visible only to screen readers. */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'paper-hue' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				esc_html( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'paper_hue_post_thumbnail' ) ) :
	/**
	 * Display an optional post thumbnail.
	 *
	 * @return void
	 */
	function paper_hue_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>
			<?php
		else :
			?>
			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
			</a>
			<?php
		endif;
	}
endif;

<?php
/**
 * For displaying comments.
 *
 * Template for displaying the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paper_Hue
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$paper_hue_comment_count = get_comments_number();
			$paper_hue_post_title    = get_the_title();

			if ( 1 === $paper_hue_comment_count ) {
				printf(
					/* translators: %s: post title. */
					esc_html__( 'One thought on “%s”', 'paper-hue' ),
					esc_html( $paper_hue_post_title )
				);
			} else {
				printf(
					/* translators: 1: comment count, 2: post title. */
					esc_html( _nx( '%1$s thought on “%2$s”', '%1$s thoughts on “%2$s”', $paper_hue_comment_count, 'comments title', 'paper-hue' ) ),
					esc_html( number_format_i18n( $paper_hue_comment_count ) ),
					esc_html( $paper_hue_post_title )
				);
			}
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation();

		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'paper-hue' ); ?></p>
			<?php
		endif;
	endif;

	comment_form();
	?>

</div>

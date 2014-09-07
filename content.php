<?php
/**
 * @package jc
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="row">
		<div class="small-12 columns">

			<header class="entry-header">

				<?php
				global $output_content_anchor;
				if ($output_content_anchor) {
					echo '<p><a href="#begin-content" class="content-anchor" name="begin-content"></a></p>';
					$output_content_anchor = false;
				}
				?>

				<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

				<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php jc_posted_on(); ?>
				</div><!-- .entry-meta -->
				<?php endif; ?>

			</header><!-- .entry-header -->

			<?php if ( is_search() ) : // Only display Excerpts for Search ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'jc' ) ); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'jc' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->
			<?php endif; ?>

			<footer class="entry-footer">
				<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
					<?php
						/* translators: used between list items, there is a space after the comma */
						$categories_list = get_the_category_list( __( ', ', 'jc' ) );
						if ( $categories_list && jc_categorized_blog() ) :
					?>
					<span class="cat-links">
						<?php printf( __( 'Posted in %1$s', 'jc' ), $categories_list ); ?>
					</span>
					<?php endif; // End if categories ?>

					<?php
						/* translators: used between list items, there is a space after the comma */
						$tags_list = get_the_tag_list( '', __( ', ', 'jc' ) );
						if ( $tags_list ) :
					?>
					<span class="tags-links">
						<?php printf( __( 'Tagged %1$s', 'jc' ), $tags_list ); ?>
					</span>
					<?php endif; // End if $tags_list ?>
				<?php endif; // End if 'post' == get_post_type() ?>

				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'jc' ), __( '1 Comment', 'jc' ), __( '% Comments', 'jc' ) ); ?></span>
				<?php endif; ?>

				<?php edit_post_link( __( 'Edit', 'jc' ), '<span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-footer -->

		</div>
	</div>
</article>

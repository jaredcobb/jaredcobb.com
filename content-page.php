<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package jc
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="row">

		<div class="small-12 columns">

			<?php the_content(); ?>

		</div>

	</div>

</article>

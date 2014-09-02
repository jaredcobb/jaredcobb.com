<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package jc
 */
?>

		<footer class="site-footer" role="contentinfo">

			<div class="row">

				<div class="site-info small-12 columns">

					<a href="https://twitter.com/jaredcobb" target="_blank"><img class="social" src="<?php echo get_template_directory_uri(); ?>/images/twitter.svg" /></a>
					<a href="https://github.com/jaredcobb" target="_blank"><img class="social" src="<?php echo get_template_directory_uri(); ?>/images/github.svg" /></a>
					<p>It&#39;s currently <?php echo date('Y'); ?></p>

				</div>

			</div>

		</footer>

		</div><!-- .content -->

	</div><!-- .wrapper -->

<?php wp_footer(); ?>

</body>
</html>

<?php
/**
 * an object to handle various views and things for the template itself
 *
 * @package jc
 */

class Template {

	public function __construct() {
	}

	public function get_favicon_meta() {

		// icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/)
		$template_dir = get_template_directory_uri();
		$meta = <<<HTML
			<link rel="apple-touch-icon" href="{$template_dir}/images/apple-icon-touch.png">
			<link rel="icon" href="{$template_dir}/favicon.png">
			<!--[if IE]>
				<link rel="shortcut icon" href="{$template_dir}/favicon.ico">
			<![endif]-->
			<meta name="msapplication-TileColor" content="#f01d4f">
			<meta name="msapplication-TileImage" content="{$template_dir}/images/win8-tile-icon.png">
HTML;

		return $meta;

	}

	public function display_primary_menu() {
		echo <<<HTML
				<div class="sticky">
					<nav class="top-bar" role="navigation" data-topbar >
					<ul class="title-area">
						<li class="name"></li>
						<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
					</ul>

					<section class="top-bar-section">
HTML;

		// display the wp3 menu
		wp_nav_menu(array(
			'theme_location' => 'primary',
			'container' => false,
			'menu' => 'Primary Menu',
			'menu_class' => '',
			'depth' => 0,
			'walker' => new Foundation_Topbar_Walker(),
		));

		echo <<<HTML
					</section>
					</nav>
				</div>
HTML;

	}

	public function get_dynamic_css() {

		$current_ID = 0;
		// by defualt, get the subpage size of the background images
		$default_thumbnail_slug = 'main-feature-subpage';

		if ( is_front_page() && is_home() ) {
			// default homepage, not currently setup this way in my theme
		}
		elseif ( is_front_page() ) {
			// static homepage
			$current_ID = get_the_ID();
			$default_thumbnail_slug = 'main-feature';
		}
		elseif ( is_home() ) {
			// blog page
			$current_page = get_page_by_title('blog');
			$current_ID = $current_page->ID;
		}
		elseif (is_page() || is_single()) {
			$current_ID = get_the_ID();
		}
		elseif (is_404()) {
			// blog page
			$current_page = get_page_by_title('404');
			$current_ID = $current_page->ID;
		}
		else {
			// everything else
			// home page by default
			$current_page = get_page_by_title('home');
			$current_ID = $current_page->ID;
		}

		$feature_image = wp_get_attachment_image_src(get_post_thumbnail_id($current_ID), $default_thumbnail_slug);
		return <<<HTML
			<style>
			.header-image-normal {
				background-image:url({$feature_image[0]});
			}
			</style>
HTML;

	}

	public function get_header_skrollr_atts() {
		$atts = array();
		if (is_front_page()) {
			$atts['normal'] = 'data-anchor-target=".nav-wrapper" data-center-bottom="transform: translateY(-40px);" data-bottom-bottom="transform:translateY(0px);"';
			$atts['logo'] = 'data-anchor-target=".nav-wrapper" data-top-bottom="transform: translateY(-140px);" data-bottom-bottom="transform:translateY(0px);"';
		}
		else {
			$atts['normal'] = 'data-anchor-target=".nav-wrapper" data-top-bottom="transform: translateY(-60px);" data--35p-bottom="transform:translateY(0px);"';
			$atts['logo'] = 'data-anchor-target=".nav-wrapper" data-top-bottom="transform: translateY(-140px);" data--35p-bottom="transform:translateY(0px);"';
		}

		return $atts;
	}

}
?>

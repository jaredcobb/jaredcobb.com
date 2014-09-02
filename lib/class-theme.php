<?php
/**
 * jc custom theme properties and methods
 *
 * @package jc
 */

class Theme {

	public function __construct() {
	}

	public function setup() {
		$this->cleanup_head();
		$this->add_more_theme_support();

		// cleaning up random code around images
		add_filter( 'the_content', array($this, 'filter_ptags_on_images') );

		// create a grayscale featured image
		add_filter('wp_generate_attachment_metadata', array($this, 'create_effect_image'));

		// add editor css
		add_editor_style( 'css/style.css' );
	}

	public function load_resources() {

		$revision = $this->get_resources_version();
		$minified = ($revision != 'dev' ? '.min' : '');

		wp_enqueue_style( 'open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400italic,400,700,300', null, $revision );
		wp_enqueue_style( 'jc-style', get_template_directory_uri() . '/css/style.css', null, $revision );

		// use google for possible caching / cdn benefits
		wp_deregister_script('jquery');
		wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', array(), '', true);

		wp_register_script( 'jc-site', get_template_directory_uri() . "/js/jc{$minified}.js", array('jquery'), $revision, true );
		wp_localize_script( 'jc-site', 'SITE_JSON',
			array(
				'ajaxURL' => admin_url( 'admin-ajax.php' ),
				'ajaxNonce' => wp_create_nonce( 'ajax_nonce' ),
				'isFrontPage' => (is_front_page() ? 'true' : 'false'),
				'isPage' => (is_page() ? 'true' : 'false'),
				'isSingle' => (is_single() ? 'true' : 'false'),
				'postNameInit' => Utility::get_post_name_init_by_post_name()
			)
		);
		wp_enqueue_script('jc-site');

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// modernizr can be loaded via CDN in dev mode
		$modernizr_src = ($revision == 'dev' ? '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js' : get_template_directory_uri() . '/js/modernizr.custom.js');
		wp_enqueue_script('modernizr', $modernizr_src, null, $revision);
	}

	public function remove_wp_ver_css_js($src) {

		global $wp_version;
		if ( strpos( $src, "ver={$wp_version}") ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;

	}

	public function filter_ptags_on_images($content) {
		return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}

	protected function cleanup_head() {
		// category feeds
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		// post and comment feeds
		remove_action( 'wp_head', 'feed_links', 2 );
		// EditURI link
		remove_action( 'wp_head', 'rsd_link' );
		// windows live writer
		remove_action( 'wp_head', 'wlwmanifest_link' );
		// index link
		remove_action( 'wp_head', 'index_rel_link' );
		// previous link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		// start link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		// links for adjacent posts
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		// WP version
		remove_action( 'wp_head', 'wp_generator' );
		// remove wp version from css
		add_filter( 'style_loader_src', array($this, 'remove_wp_ver_css_js'), 9999 );
		// remove wp version from scripts
		add_filter( 'script_loader_src', array($this, 'remove_wp_ver_css_js'), 9999 );
	}

	protected function add_more_theme_support() {
		// default thumb size
		set_post_thumbnail_size(125, 125, true);
		add_image_size('main-feature', 1680, 900, array( 'center', 'center' ));
		add_image_size('main-feature-effect', 1680, 899, array( 'center', 'center' ));
	}

	public function create_effect_image($meta) {
		$file = wp_upload_dir();
		$file = trailingslashit($file['path']).$meta['sizes']['main-feature-effect']['file'];
		list($orig_w, $orig_h, $orig_type) = @getimagesize($file);

		if (!empty($orig_type)) {
			$image = wp_load_image($file);

			$image = $this->apply_image_effect($image, IMG_FILTER_GAUSSIAN_BLUR, 12);

			switch ($orig_type) {
				case IMAGETYPE_GIF:
					imagegif( $image, $file );
					break;
				case IMAGETYPE_PNG:
					imagepng( $image, $file );
					break;
				case IMAGETYPE_JPEG:
					imagejpeg( $image, $file );
					break;
			}
		}
		return $meta;
	}

	protected function apply_image_effect($image, $filter, $times) {
		for ($i=0; $i<$times; $i++) {
			imagefilter($image, $filter);
		}
		return $image;
	}

	protected function get_resources_version() {

		// use the transients api to get/set the git revision hash
		if (false === ($commit_hash = get_transient('commit_hash'))) {

			if (file_exists(get_stylesheet_directory() . '/.revision')) {
				$file_handle = fopen(get_stylesheet_directory() . '/.revision', 'r');
				$commit_hash = substr(fgets($file_handle), 0, 8);
				fclose($file_handle);
			}
			else {
				$commit_hash = 'dev';
			}

			set_transient('commit_hash', $commit_hash, (MINUTE_IN_SECONDS * 5));
		}

		return $commit_hash;
	}

}
 ?>

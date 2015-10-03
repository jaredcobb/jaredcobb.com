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
		$this->register_shortcodes();

		// cleaning up random code around images
		add_filter( 'the_content', array($this, 'filter_ptags_on_images') );

		// add editor css
		add_editor_style( 'css/style-editor.css' );

		// tell gravity forms to load resources in the footer
		add_filter('gform_init_scripts_footer', '__return_true');
		// wrap the dirty inline javascript that gravity forms produces in a dom ready listener
		add_filter( 'gform_cdata_open', array($this, 'wrap_gform_cdata_open') );
		add_filter( 'gform_cdata_close', array($this, 'wrap_gform_cdata_close') );

		// build an interchange image tag for all uploaded images in the content
		add_filter( 'the_content', array($this, 'replace_images_with_interchange') );

		// get rid of the anchor in the read more link
		add_filter('the_content_more_link', array($this, 'remove_more_anchor'));

		// remove toolbar on frontend
		add_filter('show_admin_bar', '__return_false');

		// configure auto-updates
		add_filter( 'allow_major_auto_core_updates', '__return_true' );
		add_filter( 'auto_update_plugin', '__return_true' );
		add_filter( 'auto_update_theme', '__return_true' );

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
		add_image_size('main-feature-subpage', 1680, 600, array( 'center', 'center' ));
		add_image_size('mobile-small', 640, 480);
		add_image_size('mobile-medium', 1024, 768);
	}

	protected function register_shortcodes() {
		add_shortcode('script', array($this, 'shortcode_insert_script_tag'));
	}

	public function shortcode_insert_script_tag($atts) {
		$a = shortcode_atts( array(
			'src' => ''
		), $atts );

		return '<script src="' . $a['src'] . '"></script>';
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

	public function wrap_gform_cdata_open( $content = '' ) {
		$content = 'document.addEventListener( "DOMContentLoaded", function() { ';
		return $content;
	}

	public function wrap_gform_cdata_close( $content = '' ) {
		$content = ' }, false );';
		return $content;
	}

	public function replace_images_with_interchange($content) {
		$image_matches = array();
		if (preg_match_all("/<img[^>]*./", $content, $image_matches, PREG_OFFSET_CAPTURE)) {

			if (is_array($image_matches[0]) && !empty($image_matches[0])) {

				// setup helper values
				$image_matches[0] = array_reverse($image_matches[0]);
				$uploads_path = '/wp-content/uploads/';

				foreach ($image_matches[0] as $image_collection) {

					if (is_array($image_collection) && !empty($image_collection)) {

						$image_tag = $image_collection[0];
						$image_tag_start_pos = $image_collection[1];

						if (strpos($image_tag, $uploads_path)) {
							$src_full_content_start_pos = strpos($content, 'src="', intval($image_tag_start_pos));
							$src_start_pos = strpos($image_tag, 'src="');
							$src_end_pos = strpos($image_tag, '"', ($src_start_pos + 5));
							$src_value = substr($image_tag, ($src_start_pos + 5), ($src_end_pos - $src_start_pos - 5));

							$attachment_id = $this->get_attachment_id($src_value);

							if ($attachment_id) {

								// get mobile and original size
								$mobile_image = wp_get_attachment_image_src($attachment_id, 'mobile-small');
								$standard_image = wp_get_attachment_url($attachment_id);

								if ($mobile_image && $standard_image) {
									$interchange_tag = ' data-interchange="[' . $mobile_image[0] . ', (default)], [' . $standard_image . ', (medium)]"';
									$content = substr_replace($content, 'data-old-', $src_full_content_start_pos, 0);
									$content = substr_replace($content, $interchange_tag, $image_tag_start_pos + 4, 0);
								}

							}

						}


					}

				}

			}

		}

		return $content;
	}

	protected function get_attachment_id( $url ) {

		$dir = wp_upload_dir();

		// baseurl never has a trailing slash
		if ( false === strpos( $url, $dir['baseurl'] . '/' ) ) {
			// URL points to a place outside of upload directory
			return false;
		}

		$file  = basename( $url );
		$query = array(
				'post_type'  => 'attachment',
				'fields'     => 'ids',
				'meta_query' => array(
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						),
					)
				);

		$query['meta_query'][0]['key'] = '_wp_attached_file';

		// query attachments
		$ids = get_posts( $query );

		if ( ! empty( $ids ) ) {

			foreach ( $ids as $id ) {

				// first entry of returned array is the URL
				if ( $url === array_shift( wp_get_attachment_image_src( $id, 'full' ) ) )
					return $id;
			}
		}

		$query['meta_query'][0]['key'] = '_wp_attachment_metadata';

		// query attachments again
		$ids = get_posts( $query );

		if ( empty( $ids) )
			return false;

		foreach ( $ids as $id ) {

			$meta = wp_get_attachment_metadata( $id );

			foreach ( $meta['sizes'] as $size => $values ) {

				if ( $values['file'] === $file && $url === array_shift( wp_get_attachment_image_src( $id, $size ) ) )
					return $id;
			}
		}

		return false;
	}

	public function remove_more_anchor($link) {
		$offset = strpos($link, '#more-');
		if ($offset) {
			$end = strpos($link, '"',$offset);
		}
		if ($end) {
			$link = substr_replace($link, '', $offset, $end-$offset);
		}
		return $link;
	}

}
 ?>

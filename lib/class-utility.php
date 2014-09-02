<?php
/**
 * a collection of useful utilities (this is a junk drawer of tools)
 *
 * @package jc
 */

class Utility {

	public function __construct() {
	}

	public static function get_post_name_init_by_post_name() {

		global $post;

		if (is_single() || is_page()) {
			// break the name (dashes and underscores) into an array
			$post_name_pieces = preg_split("/(-|_)/", $post->post_name);
			// camelcase the pieces back together
			$function_name = '';
			foreach ($post_name_pieces as $key=>$value){
				if ($key > 0) {
					$value = ucwords($value);
				}
				$function_name .= $value;
			}
			$function_name .= 'Init';
			return $function_name;
		}
		else {
			return '';
		}
	}
}
?>

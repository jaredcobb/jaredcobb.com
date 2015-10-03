<?php
/**
 * a walker for the foundation topbar
 *
 * @package jc
 */

class Foundation_Topbar_Walker extends Walker_Nav_Menu {
	// setting the children to true or false.. if there are child elements then we are going to
	// call the method below and make sure to add class of has-dropdown
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
		$GLOBALS['dd_children'] = ( isset($children_elements[$element->ID]) )? 1:0;
		$GLOBALS['dd_depth'] = (int) $depth;
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
	// add the class of dropdown to sub-level ul
	function start_lvl(&$output, $depth=0, $args=array()) {
		$indent = str_repeat("\t", $depth);
		$output .= $indent . '<ul class="dropdown">';
	}
}
?>

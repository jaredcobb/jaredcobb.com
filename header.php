<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package jc
 */

$template = new Template();

?><!DOCTYPE html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">

<?php // mobile meta ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">

<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php echo $template->get_favicon_meta(); ?>
<?php
echo $template->get_dynamic_css();
?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div class="wrapper">

		<header class="site-header" role="banner">

			<div class="feature">

			  <div class="header-image-normal"></div>

			  <div class="header-image-effect"></div>

			  <div class="title">

				<h1>
					<?php echo esc_attr( get_bloginfo( 'title' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ); ?>
				</h1>

			  </div>

			</div>

			<div class="nav-wrapper">

				<?php $template->display_primary_menu(); ?>

			</div>

		</header>

		<div class="content">

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
$skrollr_atts = $template->get_header_skrollr_atts();
?>
<?php wp_head(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-54375392-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body <?php body_class(); ?>>
<div class="header-image-wrapper">
	<div class="header-image-normal" <?php echo $skrollr_atts['normal']; ?>></div>
	<div class="header-image-effect" <?php echo $skrollr_atts['effect']; ?>></div>
</div>

	<div class="wrapper">

		<header class="site-header" role="banner">

			<div class="feature">

			  <div class="title">

				<h1 <?php echo $skrollr_atts['logo']; ?>>
					<?php echo esc_attr( get_bloginfo( 'title' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ); ?>
				</h1>

			  </div>

			</div>

		</header>

		<div class="content">

			<div class="nav-wrapper">

				<?php $template->display_primary_menu(); ?>

			</div>


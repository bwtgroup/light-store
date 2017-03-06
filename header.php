<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<div id="page" class="hfeed site">

		<?php do_action( 'ls_before_header' ); ?>

		<header class="header-default header-fixed-top" role="banner">

			<div <?php ls_get_content_classes();?>>

				<?php
					/**
					 * Functions hooked in to ls_header
					 *
					 */
					do_action( 'ls_header' );
				?>

			</div>

		</header>

		<?php
		/**
		 * Functions hooked in to ls_before_content
		 *
		 */
		do_action( 'ls_before_content' );?>

		<div <?php ls_get_content_classes();?> tabindex="-1">
			<?php
			/**
			 * Functions hooked in to ls_content_top
			 *
			 */
			do_action( 'ls_content_top' );

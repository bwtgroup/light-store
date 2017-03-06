<?php
/**
 * The template used for displaying page content in page.php
 *
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to ls_page add_action
	 *
	 * @hooked ls_page_header          - 10
	 * @hooked ls_page_content         - 20
	 * @hooked ls_init_structured_data - 30
	 */
	do_action( 'ls_page' );
	?>
</div><!-- #post-## -->

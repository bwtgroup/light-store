<?php
/**
 * Template used to display post content.
 *
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked in to ls_loop_post action.
	 *
	 * @hooked ls_post_header          - 10
	 * @hooked ls_post_meta            - 20
	 * @hooked ls_post_content         - 30
	 * @hooked ls_init_structured_data - 40
	 */
	do_action( 'ls_loop_post' );
	?>

</article><!-- #post-## -->

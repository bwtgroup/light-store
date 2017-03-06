<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 */

?>

	</div><!-- .content -->

	<?php do_action( 'ls_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div <?php ls_get_content_classes();?>>

			<?php
			/**
			 * Functions hooked in to ls_footer action
			 *
			 * @hooked ls_footer_widgets - 10
			 */
			do_action( 'ls_footer' ); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'ls_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

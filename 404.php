<?php
/**
 * The template for displaying 404 pages (not found).
 *
 */

get_header(); ?>

	<div id="primary" >

		<main id="main" class="site-main" role="main">

			<div class="error-404 not-found">

				<div class="page-content">

					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'light-store' ); ?></h1>
					</header><!-- .page-header -->

					<p><?php esc_html_e( 'Nothing was found at this location. Try searching, or check out the links below.', 'light-store' ); ?></p>

					<section aria-label="Search">
						<?php
							get_search_form();
						?>
					</section>

				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer();

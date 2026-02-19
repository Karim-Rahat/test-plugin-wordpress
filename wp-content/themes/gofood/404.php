<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package goFood
 */

get_header();
?>
	<div class="section-spacer"></div>

	<main id="primary" class="site-main">

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<section class="error-404 not-found text-center">
						<header class="page-header">
							<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'gofood' ); ?></h1>
						</header><!-- .page-header -->

						<div class="page-content">
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'gofood' ); ?></p>
						</div><!-- .page-content -->
					</section><!-- .error-404 -->
				</div>
			</div>
		</div>

	</main><!-- #main -->

	<div class="section-spacer"></div>

<?php
get_footer();

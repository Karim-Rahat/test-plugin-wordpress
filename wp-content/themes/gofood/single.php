<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );

				endwhile; // End of the loop.
				?>
				</div>
			</div>
		</div>

	</main><!-- #main -->

	<div class="section-spacer"></div>

<?php
get_footer();

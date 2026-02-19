<?php
/**
 * The homepage template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package goFood
 */

get_header();

// Get all product categories.
$categories = gf_get_product_categories( true, true );

// Get the current category ID.
$current_category_id = get_query_var( 'gf_filter_category_id' );

// Determine the current page.
$current_page = max( 1, get_query_var( 'gf_page', 1 ) );

// Set up query arguments.
$args = array(
	'post_type'      => 'product',
	'posts_per_page' => 8,
	'paged'          => $current_page,
);

// If a category ID is provided, add tax_query to filter products.
if ( ! empty( $current_category_id ) ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'product_category',
			'field'    => 'term_id',
			'terms'    => $current_category_id,
		),
	);
}

// Execute the query.
$products_query = new WP_Query( $args );

$hero_title = get_theme_mod( 'gofood_hero_title', 'Fast, Fresh & Right To Your Door' );
$hero_title = format_hero_title( $hero_title );
?>

<main id="primary" class="site-main">

	<section id="hero-banner">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 p-5 pb-4 ps-lg-0 text-center text-lg-start d-flex flex-column justify-content-center align-items-center align-items-lg-start">
					<h1 class="display-4 fw-semibold hero-title"><?php echo wp_kses( $hero_title, true ); ?></h1>
					<p class="fs-4 fw-light hero-description"><?php echo wp_kses( get_theme_mod( 'gofood_hero_description', 'Order dishes from favorite restaurants near you.' ), true ); ?></p>
					<div class="mt-3">
						<a href="<?php echo esc_html( get_theme_mod( 'gofood_hero_button_url', '#' ) ); ?>" class="btn btn-primary-red d-inline-block px-4 fs-5 hero-button"><?php echo esc_html( get_theme_mod( 'gofood_hero_button_text', 'Shop Now' ) ); ?></a>
					</div>
				</div>
				<div class="col-lg-6 d-flex justify-content-center justify-content-lg-end">
					<img id="hero-banner-img" class="mx-auto me-lg-0 hero-image" src="<?php echo esc_url( get_theme_mod( 'gofood_hero_image', get_template_directory_uri() . '/assets/images/hero-banner.webp' ) ); ?>" alt="Hero Banner">
				</div>
			</div>
		</div>
	</section>

	<div class="section-spacer"></div>

	<section id="features-products">
		<div class="container">
			<div class="row">
				<div class="col-12 pb-4 mb-2 d-flex align-items-center justify-content-between">
					<h3 class="fw-bold">Featured Products</h3>
					<?php if ( $current_category_id ) : ?>
						<a href="<?php echo esc_url( gf_get_category_url( 0, '#features-products' ) ); ?>" class="text-decoration-none text-dark-red fw-semibold"><?php echo esc_html__( 'View All' ); ?></a>
					<?php endif ?>
				</div>
				<div class="col-12 mb-4 pb-2">
					<div id="food-categories">
						<?php
						foreach ( $categories as $category ) :
							if ( ! $category['image_url'] ) {
								$category['image_url'] = get_template_directory_uri() . '/assets/images/food-empty-category.png';
							}

							?>
							<a href="<?php echo esc_url( gf_get_category_url( $category['id'], '#features-products' ) ); ?>" class="category-item <?php echo esc_attr( intval( $current_category_id ) === intval( $category['id'] ) ? 'active' : '' ); ?>">
								<img src="<?php echo esc_url( $category['image_url'] ); ?>" alt="<?php echo esc_html( $category['title'] ); ?>">
								<span><?php echo esc_html( $category['title'] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php if ( $products_query->have_posts() ) : ?>
				<div class="row">
					<?php
					while ( $products_query->have_posts() ) :
						$products_query->the_post();
						?>
						<div class="col-6 col-md-4 col-lg-3">
							<?php get_template_part( 'template-parts/content', 'product' ); ?>
						</div>	
					<?php endwhile ?>
				</div>
				<div class="row">
					<div class="col-12">
						<nav aria-label="Product pagination" class="mt-4">
							<?php
							echo wp_kses(
								gf_get_paginate_links(
									array(
										'total'        => $products_query->max_num_pages,
										'current'      => $current_page,
										'type'         => 'list',
										'mid_size'     => 2,
										'prev_text'    => '<i class="fas fa-angle-left"></i>',
										'next_text'    => '<i class="fas fa-angle-right"></i>',
										'add_args'     => array( 'gf_filter_category_id' => $current_category_id ),
										'add_fragment' => '#features-products',
										'format'       => '?gf_page=%#%',
									),
									array(
										'ul' => 'pagination justify-content-center',
									)
								),
								true
							);
							?>
						</nav>
					</div>
				</div>
				<?php
				wp_reset_postdata();
			else :
				?>
				<div class="row">
					<div class="col-12">
						<div class="text-center p-4 alert alert-warning">
							<i class="fas fa-utensils fs-1 mb-2"></i>
							<p class="mb-0"><?php echo esc_html__( 'No products found', 'gofood' ); ?></p>
						</div>
					</div>
				</div>				
				<?php
			endif;
			?>
		</div>
	</section>

	<div class="section-spacer"></div>
	
</main>

<?php
get_footer();

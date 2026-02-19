<?php
/**
 * The template for displaying single product posts
 *
 * @package goFood
 */

get_header();

// Get product data.
$product_image    = get_the_post_thumbnail_url( get_the_ID(), 'large' );
$price            = get_post_meta( get_the_ID(), '_product_price', true );
$discounted_price = get_post_meta( get_the_ID(), '_product_discount_price', true );
$stock_quantity   = get_post_meta( get_the_ID(), '_product_stock_quantity', true );
$is_out_of_stock  = empty( $stock_quantity ) || intval( $stock_quantity ) === 0;
$discount_percent = gf_get_discount_percentage( $price, $discounted_price );


if ( ! $product_image ) {
	$product_image = get_template_directory_uri() . '/assets/images/food-empty.webp';
}

?>

<div class="section-spacer"></div>

<main id="primary" class="site-main">
	<div class="container">
		<div class="row g-4">
			<!-- Product Image Column -->
			<div class="col-lg-5">
				<div class="product-image-container bg-white rounded-3 shadow-sm p-3 sticky-top" style="top: 80px;">
					<?php if ( $is_out_of_stock ) : ?>
						<span class="badge bg-danger fs-6 position-absolute top-0 start-0 m-3"><?php esc_html_e( 'Out of Stock' ); ?></span>
					<?php elseif ( $discount_percent > 0 ) : ?>
						<span class="badge bg-danger fs-6 position-absolute top-0 start-0 m-3"><?php echo esc_html( $discount_percent . '% Off' ); ?></span>
					<?php endif; ?>
					
					<img src="<?php echo esc_url( $product_image ); ?>" alt="<?php the_title(); ?>" class="img-fluid rounded-3 w-100">
				</div>
			</div>
			
			<!-- Product Details Column -->
			<div class="col-lg-7">
				<div class="product-details-container bg-white rounded-3 shadow-sm p-4">
					<header class="product-header mb-4">
						<h1 class="product-title fw-bold mb-3"><?php the_title(); ?></h1>
						
						<div class="product-meta d-flex align-items-center gap-3 mb-3">
							<?php if ( $discounted_price && $discounted_price < $price ) : ?>
								<div class="product-price">
									<span class="text-danger fw-bold fs-3"><?php echo wp_kses( gf_format_price( $discounted_price ), true ); ?></span>
									<del class="text-muted ms-2 fs-5"><?php echo wp_kses( gf_format_price( $price ), true ); ?></del>
								</div>
								<?php if ( $discount_percent > 0 ) : ?>
									<span class="badge bg-danger rounded-pill">Save <?php echo esc_html( $discount_percent ); ?>%</span>
								<?php endif; ?>
							<?php else : ?>
								<div class="product-price">
									<span class="text-danger fw-bold fs-3"><?php echo wp_kses( gf_format_price( $price ), true ); ?></span>
								</div>
							<?php endif; ?>
							
							<?php if ( ! $is_out_of_stock ) : ?>
								<span class="badge bg-success rounded-pill">
									<i class="fas fa-check-circle me-1"></i> <?php echo esc_html__( 'In Stock', 'gofood' ); ?>
								</span>
							<?php endif; ?>
						</div>
						
						<?php if ( $is_out_of_stock ) : ?>
							<div class="alert alert-warning mb-4">
								<i class="fas fa-exclamation-circle me-2"></i> <?php echo esc_html__( 'This item is currently unavailable', 'gofood' ); ?>
							</div>
						<?php endif; ?>
					</header>
					
					<div class="product-content mb-4">
						<?php the_excerpt(); ?>
					</div>
					
					<div class="product-actions">
						<?php if ( ! $is_out_of_stock ) : ?>
							<div class="d-flex align-items-center gap-3">
								<div class="quantity-selector">
									<button class="btn btn-outline-danger quantity-minus">-</button>
									<input type="number" disabled class="form-control quantity-input" value="1" min="1" max="<?php echo esc_attr( $stock_quantity ); ?>">
									<button class="btn btn-outline-danger quantity-plus">+</button>
								</div>
								
								<button class="btn btn-danger flex-grow-1 py-3 fw-bold add-to-cart-btn" 
										onclick="addToCart(<?php echo esc_attr( get_the_ID() ); ?>, event)">
									<i class="fas fa-shopping-cart me-2"></i> <?php echo esc_html__( 'Add to Cart', 'gofood' ); ?>
								</button>
							</div>
						<?php endif; ?>
						
						<div class="product-stock mt-3 text-muted small">
							<?php if ( ! $is_out_of_stock ) : ?>
								<i class="fas fa-box-open me-1"></i> <?php echo esc_html( $stock_quantity ); ?> <?php echo esc_html__( 'available', 'gofood' ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				
				<!-- Related Products -->
				<?php
					$categories = get_the_terms( get_the_ID(), 'product_category' );
				if ( $categories && ! is_wp_error( $categories ) ) :
					$category_ids = wp_list_pluck( $categories, 'term_id' );

					$related_args = array(
						'post_type'      => 'product',
						'posts_per_page' => 4,
						'post__not_in'   => array( get_the_ID() ),
						'tax_query'      => array(
							array(
								'taxonomy' => 'product_category',
								'field'    => 'term_id',
								'terms'    => $category_ids,
							),
						),
					);

					$related_products = new WP_Query( $related_args );

					if ( $related_products->have_posts() ) :
						?>
						<div class="related-products mt-5">
							<h4 class="fw-bold mb-4 pb-2 border-bottom"><?php echo esc_html__( 'You may also like', 'gofood' ); ?></h4>
							<div class="row g-3">
							<?php
							while ( $related_products->have_posts() ) :
								$related_products->the_post();
								?>
									<div class="col-md-6">
									<?php get_template_part( 'template-parts/content', 'product' ); ?>
									</div>
									<?php
								endwhile;
							wp_reset_postdata();
							?>
							</div>
						</div>
						<?php
					endif;
				endif;
				?>
			</div>
		</div>
	</div>
</main>

<div class="section-spacer"></div>

<?php
get_footer();

<?php
/**
 * Template part for displaying product content in home.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package goFood
 */

$product_image    = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
$price            = get_post_meta( get_the_ID(), '_product_price', true );
$discounted_price = get_post_meta( get_the_ID(), '_product_discount_price', true );
$stock_quantity   = get_post_meta( get_the_ID(), '_product_stock_quantity', true );
$stock_quantity   = ! empty( $stock_quantity ) ? intval( $stock_quantity ) : 0;
$discount_percent = gf_get_discount_percentage( $price, $discounted_price );
$is_out_of_stock  = 0 === $stock_quantity;

if ( ! $product_image ) {
	$product_image = get_template_directory_uri() . '/assets/images/food-empty.webp';
}

?>

<article id="product-<?php the_ID(); ?>" class="product-item shadow-sm" style="<?php echo esc_attr( $is_out_of_stock ? 'opacity: 0.65; pointer-events: none; user-select:none;' : '' ); ?>">
	<a href="<?php the_permalink(); ?>" class="product-item-img">
		<?php if ( $is_out_of_stock ) : ?>
			<span class="product-item-discount"><?php echo esc_html__( 'Out of Stock' ); ?></span>
		<?php elseif ( $discount_percent > 0 ) : ?>
			<span class="product-item-discount"><?php echo esc_html( $discount_percent . '% Off' ); ?></span>
		<?php endif ?>
		<img src="<?php echo esc_url( $product_image ); ?>" alt="<?php the_title(); ?>">
	</a>
	<div class="product-item-description">
		<header class="product-item-header">
			<a href="<?php the_permalink(); ?>"><h4 class="product-item-title"><?php the_title(); ?></h4></a>
		</header>
		<div class="product-action">
			<?php echo wp_kses( gf_price( $price, $discounted_price ), true ); ?>
			<button class="btn btn-outline-danger btn-sm" onclick="addToCart(<?php echo esc_attr( get_the_ID() ); ?>, event)">
				<i class="fas fa-shopping-cart fs-5"></i>
			</button>
		</div>
	</div>
</article>

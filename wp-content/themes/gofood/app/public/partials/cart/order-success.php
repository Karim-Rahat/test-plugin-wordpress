<?php
/**
 * Render My Account Page.
 *
 * @package goFood
 */

$args = wp_parse_args( $args, array( 'order_id' => 0 ) );

?>

<div class="order-success-container py-5">
	<div class="section-spacer"></div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 col-lg-6 text-center">
				<!-- Success Icon -->
				<div class="success-icon mb-4">
					<div class="icon-circle bg-primary-red text-white rounded-circle d-inline-flex align-items-center justify-content-center">
						<i class="fas fa-check fa-3x"></i>
					</div>
				</div>
				
				<!-- Success Message -->
				<h1 class="mb-3 fw-bold text-dark"><?php echo esc_html__( 'Order Confirmed', 'gofood' ); ?>!</h1>
				<p class="lead text-muted mb-4"><?php echo esc_html__( 'Thank you for your order. Your food is on the way!', 'gofood' ); ?></p>
				
				<!-- Order Summary -->
				<div class="card border-0 shadow-sm mb-4">
					<div class="card-body p-4">
						<div class="d-flex justify-content-between mb-3">
							<span class="text-muted"><?php echo esc_html__( 'Order Number', 'gofood' ); ?>:</span>
							<span class="fw-bold">#FX-<?php echo esc_html( $args['order_id'] ); ?></span>
						</div>
						<div class="d-flex justify-content-between mb-3">
							<span class="text-muted"><?php echo esc_html__( 'Estimated Delivery', 'gofood' ); ?>:</span>
							<span class="fw-bold">30-45 minutes</span>
						</div>
						<div class="d-flex justify-content-between">
							<span class="text-muted"><?php echo esc_html__( 'Payment Method', 'gofood' ); ?>:</span>
							<span class="fw-bold"><?php echo esc_html__( 'Cash on Delivery', 'gofood' ); ?></span>
						</div>
					</div>
				</div>
				
				<!-- Action Buttons -->
				<div class="d-grid gap-3 d-sm-flex justify-content-sm-center mt-4">
					<a href="#" class="btn btn-primary-red px-4 py-3 fw-bold">
						<i class="fas fa-map-marker-alt me-2"></i> <?php echo esc_html__( 'Track Order', 'gofood' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-outline-dark px-4 py-3 fw-bold">
						<i class="fas fa-utensils me-2"></i> <?php echo esc_html__( 'Order Again', 'gofood' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="section-spacer"></div>
</div>

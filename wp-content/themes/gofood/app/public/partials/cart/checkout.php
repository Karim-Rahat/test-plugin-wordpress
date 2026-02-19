<?php
/**
 * Render My Account Page.
 *
 * @package goFood
 */

$order_id = get_query_var( 'gf_order_success' );

if ( ! empty( $order_id ) ) {
	$order_id = json_decode(
		base64_decode( sanitize_text_field( $order_id ) ),
		true
	);

	if ( isset( $order_id['order_id'], $order_id['time'] ) && intval( $order_id['time'] ) > strtotime( '-30 minutes' ) ) {
		$order_id = $order_id['order_id'];
	} else {
		$order_id = null;
	}
}

if ( $order_id ) :

	get_template_part( gf_partials_path( 'cart/order-success' ), null, array( 'order_id' => $order_id ) );

else :

	$cart = GoFood_Cart::get_cart();

	$total_items = array_reduce(
		$cart,
		function ( $total, $item ) {
			return $total + $item['quantity'];
		},
		0
	);

	$subtotal = array_reduce(
		$cart,
		function ( $total, $item ) {
			return $total + ( $item['product']['discounted_price'] ?? $item['product']['price'] ) * $item['quantity'];
		},
		0
	);
	?>
	<div class="section-spacer-small"></div>

	<main id="primary" class="site-main">
		<section id="cart-checkout">
			<div class="container">
				<div id="checkout-form" style="display: <?php echo esc_attr( ! empty( $cart ) ? 'block' : 'none' ); ?>;">
					<div class="row">	
						<div class="col-md-7">
							<?php if ( ! is_user_logged_in() ) : ?>
								<p class="alert alert-warning">
									<i class="fas "></i>
									Please <a href="<?php echo esc_url( gf_get_my_account_url() . '?auth_active_tab=login' ); ?>" class="text-decoration-underline text-dark-red fw-semibold">Click Here</a> to login for faster Checkout Process. 
								</p>
							<?php endif ?>
							<div class="shadow-sm border rounded py-3 px-4">
								<h4><?php echo esc_html__( 'Checkout', 'gofood' ); ?></h4>
								<hr>
								<form onsubmit="proceedCheckout(event)">
									<h5 class="mb-3"><?php echo esc_html__( 'Billing details', 'gofood' ); ?></h5>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group mb-4">
												<label for="first_name" class="form-label"><?php echo esc_html__( 'First Name', 'gofood' ); ?> <span class="text-danger">*</span></label>
												<input type="text" required class="form-control form-control-lg fs-6 rounded-1" name="first_name" id="first_name" placeholder="John">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group mb-4">
												<label for="last_name" class="form-label"><?php echo esc_html__( 'Last Name', 'gofood' ); ?> <span class="text-danger">*</span></label>
												<input type="text" required class="form-control form-control-lg fs-6 rounded-1" name="last_name" id="last_name" placeholder="Doe">
											</div>
										</div>
										<div class="col-12">
											<div class="form-group mb-4">
												<label for="address" class="form-label"><?php echo esc_html__( 'Street Address', 'gofood' ); ?> <span class="text-danger">*</span></label>
												<textarea required class="form-control form-control-lg fs-6 rounded-1" name="address" placeholder="1234 Main St" id="address"></textarea>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group mb-4">
												<label for="city" class="form-label"><?php echo esc_html__( 'City', 'gofood' ); ?> <span class="text-danger">*</span></label>
												<input type="text" required class="form-control form-control-lg fs-6 rounded-1" name="city" id="city" placeholder="Rampura">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group mb-4">
												<label for="state" class="form-label"><?php echo esc_html__( 'State', 'gofood' ); ?> <span class="text-danger">*</span></label>
												<input type="text" required class="form-control form-control-lg fs-6 rounded-1" name="state" id="state" placeholder="Chittagong">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group mb-4">
												<label for="zipcode" class="form-label"><?php echo esc_html__( 'Zipcode', 'gofood' ); ?> <span class="text-danger">*</span></label>
												<input type="text" required class="form-control form-control-lg fs-6 rounded-1" name="zipcode" id="zipcode" placeholder="12345">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group mb-4">
												<label for="phone" class="form-label"><?php echo esc_html__( 'Phone', 'gofood' ); ?> <span class="text-danger">*</span></label>
												<input type="text" required class="form-control form-control-lg fs-6 rounded-1" name="phone" id="phone" placeholder="+1 234-567-8900">
											</div>
										</div>
										<div class="col-12">
											<div class="form-group mb-4">
												<label for="email" class="form-label"><?php echo esc_html__( 'Email', 'gofood' ); ?> <span class="text-danger">*</span></label>
												<input type="email" required class="form-control form-control-lg fs-6 rounded-1" name="email" id="email" placeholder="john.doe@hotmail.com" value="<?php echo esc_attr( wp_get_current_user()->user_email ?? '' ); ?>">
											</div>
										</div>
										<div class="col-12 mt-2">
											<button type="submit" class="btn btn-primary-red w-100 py-3 fs-5 fw-bold"><i class="fas fa-lock me-2"></i> <?php echo esc_html__( 'Place Order', 'gofood' ); ?></button>
											<div class="text-center mt-3">
												<small class="text-muted">
													<i class="fas fa-shield-alt me-1"></i> <?php echo esc_html__( 'Secure checkout. Your information is protected.', 'gofood' ); ?>
												</small>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="col-md-5">
							<div class="shadow-sm border rounded p-3">
								<div class="d-flex justify-content-between align-items-center mb-3">
									<h4 class="mb-0">
										<i class="fas fa-shopping-cart me-2 text-primary-red"></i> <?php echo esc_html__( 'Your Order', 'gofood' ); ?>
									</h4>
									<span class="badge bg-primary-red rounded-pill"><?php echo esc_html( $total_items . ' ' . __( 'items', 'gofood' ) ); ?> </span>
								</div>
								<hr>
								<table class="table border">
									<thead class="table-light">
										<tr>
											<th scope="col"><?php echo esc_html__( 'Item', 'gofood' ); ?></th>
											<th scope="col"><?php echo esc_html__( 'Subtotal', 'gofood' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $cart as $item ) : ?>
											<tr>
												<td>
													<a href="<?php echo esc_url( $item['product']['permalink'] ); ?>" class="fw-light text-decoration-none text-dark-red"><?php echo esc_html( $item['product']['title'] ); ?></a>
													<b>x<?php echo esc_html( $item['quantity'] ); ?></b>
												</td>
												<td><?php echo wp_kses( gf_format_price( ( $item['product']['discounted_price'] ?? $item['product']['price'] ) * $item['quantity'] ), true ); ?></td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
								<!-- Order Summary -->
								<div class="bg-light-red p-3 rounded-3 mb-3">
									<h6 class="fw-bold mb-3"><?php echo esc_html__( 'Order Summary', 'gofood' ); ?></h6>
									<div class="d-flex justify-content-between mb-2">
										<span class="text-muted"><?php echo esc_html__( 'Subtotal', 'gofood' ); ?> (<?php echo esc_html( $total_items ); ?> <?php echo esc_html__( 'items', 'gofood' ); ?>)</span>
										<span><?php echo wp_kses( gf_format_price( $subtotal ), true ); ?></span>
									</div>
									<div class="d-flex justify-content-between mb-2">
										<span class="text-muted"><?php echo esc_html__( 'Delivery Fee', 'gofood' ); ?></span>
										<span><?php echo wp_kses( gf_format_price( 0 ), true ); ?></span>
									</div>
									<div class="d-flex justify-content-between mb-2">
										<span class="text-muted"><?php echo esc_html__( 'Tax', 'gofood' ); ?></span>
										<span><?php echo wp_kses( gf_format_price( 0 ), true ); ?></span>
									</div>
									<hr class="my-2">
									<div class="d-flex justify-content-between fw-bold fs-5">
										<span><?php echo esc_html__( 'Total', 'gofood' ); ?></span>
										<span class="text-dark-red"><?php echo wp_kses( gf_format_price( $subtotal ), true ); ?></span>
									</div>
								</div>
								<h5><i class="fas fa-credit-card me-2"></i> <?php echo esc_html__( 'Payment', 'gofood' ); ?></h5>
								<hr>
								<p class="text-muted mb-0"><?php echo esc_html__( 'Pay with cash on delivery.', 'gofood' ); ?></p>
							</div>
						</div>
					</div>
				</div>
				<div id="checkout-empty" style="display: <?php echo esc_attr( empty( $cart ) ? 'block' : 'none' ); ?>;">
					<div class="row">
						<div class="col-12">
							<div class="text-center text-muted p-4">
								<i class="fa-solid fs-1 fa-face-sad-tear mb-2"></i>
								<p><?php echo esc_html__( 'Your cart is empty.', 'gofood' ); ?></p>
								<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-sm px-3 btn-primary-red"><?php echo esc_html__( 'Continue Shopping', 'gofood' ); ?></a>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</section>
	</main>

	<div class="section-spacer-small"></div>
<?php endif; ?>

<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package goFood
 */

$is_home_page = is_front_page() || is_home();

$is_logged_text = is_user_logged_in() ? __( 'Account' ) : __( 'Sign in' );

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'gofood' ); ?></a>

	<!-- Header Section -->
	<header class="sticky-top">

		<?php if ( $is_home_page ) : ?>
			<!-- Top Bar -->
			<div class="bg-light text-dark py-2 d-none d-lg-block" id="top-bar">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-md-6">
							<span class="me-3 top-bar-delivery-text"><?php echo esc_html( get_theme_mod( 'gofood_delivery_text', 'Free delivery on orders over $20' ) ); ?></span>
						</div>
						<div class="col-md-6 text-end">
							<div class="d-flex justify-content-end align-items-center">
								<i class="fas fa-phone-alt me-2"></i>
								<span class="me-3 top-bar-phone"><?php echo esc_html( get_theme_mod( 'gofood_phone_number', '+1 234-567-8900' ) ); ?></span>
								<i class="fas fa-envelope me-2"></i>
								<span class="top-bar-email"><?php echo esc_html( get_theme_mod( 'gofood_email_address', 'help@gofood.com' ) ); ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif ?>
		
		<!-- Main Navigation -->
		<nav class="navbar border-bottom shadow-sm navbar-expand-lg navbar-light bg-white">
			<div class="container">
				<!-- Logo -->
				<div class="site-branding navbar-brand">
					<?php
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
						?>
						<h2 class="site-title m-0"><a class="text-dark-red text-decoration-none" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><i class="fas fa-utensils me-2"></i><?php bloginfo( 'name' ); ?></a></h2>
					<?php endif; ?>
				</div>

				<?php if ( $is_home_page ) : ?>
					<!-- Navigation Links -->
					<div class="collapse navbar-collapse" id="navbarMain">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => has_nav_menu( 'primary' ) ? 'primary' : null,
								'container'      => false,
								'menu_class'     => '',
								'fallback_cb'    => '__return_false',
								'items_wrap'     => '<ul id="%1$s" class="navbar-nav me-auto mb-2 mb-lg-0 %2$s">%3$s</ul>',
								'depth'          => 2,
								'walker'         => new bootstrap_5_wp_nav_menu_walker(),
							)
						);
						?>
						<?php if ( ! gf_if_my_account_page() ) : ?>
							<div class="mb-4">
								<a href="<?php echo esc_url( gf_get_my_account_url() ); ?>" class="d-lg-none btn btn-primary-red px-3 btn-sm"><?php echo esc_html( $is_logged_text ); ?></a>
							</div>
						<?php endif ?>
					</div>
				<?php endif ?>

				<!-- Search and Cart -->
				<div class="d-flex align-items-center">
					
					<?php if ( ! gf_if_checkout_page() ) : ?>
						<button onclick="openCart()" class="text-dark position-relative me-4 btn-reset" id="cart-btn">
							<i class="fas fa-shopping-cart fa-lg"></i>
							<span style="display: none;" class="cart-count gf_cart_count_label"></span>
						</button>
					<?php endif ?>

					<?php if ( ! gf_if_my_account_page() ) : ?>
						<a href="<?php echo esc_url( gf_get_my_account_url() ); ?>" class="<?php echo esc_attr( $is_home_page ? 'd-none d-lg-block' : '' ); ?> me-4 btn btn-primary-red px-3 btn-sm"><?php echo esc_html( $is_logged_text ); ?></a>
					<?php endif ?>
						
					<?php if ( $is_home_page ) : ?>
						<!-- Mobile Toggle -->
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
							<span class="navbar-toggler-icon"></span>
						</button>
					<?php endif ?>
				</div>
			</div>
		</nav>
	</header>

	<?php if ( ! gf_if_checkout_page() ) : ?>
		<aside id="cartDrawer">
			<div class="wrapper">

				<div class="loading" style="display: none;">
					<i class="fas fa-spinner fa-spin-pulse fs-5"></i>
				</div>

				<div class="cart-header" style="margin-top: <?php echo esc_attr( is_admin_bar_showing() ? '45px' : '0px' ); ?>">
					<h5 class="fw-bold mb-0">Your Cart</h5>
					<button class="btn-reset" onclick="closeCart()">
						<i class="fas fs-5 fa-times" id="closeCart"></i>
					</button>
				</div>

				<div class="cart-items" id="gf_cart_items_box"></div>

				<div class="cart-actions">
					<div class="my-3">
						<div class="d-flex justify-content-between">
							<span>Subtotal</span>
							<span id="gf_cart_subtotal"></span>
						</div>
						<div class="d-flex justify-content-between">
							<span>Delivery Fee</span>
							<span>$0.00</span>
						</div>
						<div class="d-flex justify-content-between">
							<span>Total</span>
							<span id="gf_cart_total" class="fw-bold fs-5 text-dark-red"></span>
						</div>
					</div>
					<a href="<?php echo esc_url( gf_get_checkout_url() ); ?>" class="btn btn-primary-red w-100">Checkout</a>
				</div>
			</div>

		</aside>

		<div id="cartDrawerOverlay" onclick="closeCart()" style="display: none;"></div>
	<?php endif ?>

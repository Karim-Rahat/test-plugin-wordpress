<?php
/**
 * Render Login/Register Page.
 *
 * @package goFood
 */

$active_tab = get_query_var( 'auth_active_tab', 'login' );

?>

<section id="my-account-login" class="bg-light">
	<div class="section-spacer"></div>

	<div class="container">
		<div class="row">
			<div class="d-none d-lg-flex align-items-center col-lg-6 col-xl-5 offset-xl-1">
				<img class="img-fluid" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/login-banner.webp" alt="Login Banner">
			</div>

			<div class="col-lg-6 col-xl-5">
				<div class="bg-white p-5 rounded shadow-sm">
					<div class="text-center mb-4">
						<?php if ( has_site_icon() ) : ?>
							<img style="width: 50px;" class="mb-3" src="<?php echo esc_url( get_site_icon_url() ); ?>" alt="">
						<?php endif ?>
						<?php if ( 'register' === $active_tab ) : ?>
							<h2><?php echo esc_html__( 'Register', 'gofood' ); ?></h2>
							<p><?php echo esc_html__( 'If you don\'t have an account with us, please register.', 'gofood' ); ?></p>
						<?php else : ?>
							<h2><?php echo esc_html__( 'Login', 'gofood' ); ?></h2>
							<p><?php echo esc_html__( 'If you have an account with us, please log in.', 'gofood' ); ?></p>
						<?php endif ?>
					</div>

					<?php get_template_part( gf_partials_path( 'my-account/form/auth' ), $active_tab ); ?>

					<div class="text-center mt-4 pt-2">
						<?php if ( 'login' === $active_tab ) : ?>
							<p class="mb-0"><?php echo esc_html__( 'Don\'t have an account yet?', 'gofood' ); ?> <a href="<?php echo esc_url( gf_get_my_account_url() . '?auth_active_tab=register' ); ?>" class="text-decoration-none text-dark-red fw-semibold"><?php echo esc_html__( 'Register Now', 'gofood' ); ?></a></p>
						<?php else : ?>
							<p class="mb-0"><?php echo esc_html__( 'Already have an account?', 'gofood' ); ?> <a href="<?php echo esc_url( gf_get_my_account_url() . '?auth_active_tab=login' ); ?>" class="text-decoration-none text-dark-red fw-semibold"><?php echo esc_html__( 'Login', 'gofood' ); ?></a></p>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="section-spacer"></div>
</section>

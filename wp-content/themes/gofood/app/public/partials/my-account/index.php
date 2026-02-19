<?php
/**
 * Render My Account Page.
 *
 * @package goFood
 */

if ( ! is_user_logged_in() ) :
	get_template_part( gf_partials_path( 'my-account/login' ) );
else :
	$active_tab = get_query_var( 'ma_active_tab', 'orders' );
	?>
	
	<section id="my-account">
		<div class="container py-5">
			<div class="row mb-4">
				<div class="col-12">
					<h2 class="fw-semibold"><i class="fas fa-user-circle me-2 text-primary-red"></i> My Account</h2>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a class="text-decoration-none text-dark-red" href="<?php echo esc_url( home_url() ); ?>">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">My Account</li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 mb-5 mb-lg-0">
					<?php get_template_part( gf_partials_path( 'my-account/dashboard/sidebar' ) ); ?>
				</div>
				<div class="col-lg-8">
					<?php get_template_part( gf_partials_path( 'my-account/dashboard/tab' ), $active_tab ); ?>
				</div>
			</div>
		</div>
	</section>

	<?php
	endif;

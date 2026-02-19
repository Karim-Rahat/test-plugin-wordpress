<?php
/**
 * Render the Register Form
 *
 * @package goFood
 */

?>

<form id="user_register_form" method="POST">

	<p class="alert alert-warning mb-4" style="display: none;" id="user_register_error"></p>

	<div class="form-group mb-4">
		<label for="name" class="form-label"><?php echo esc_html__( 'Full Name', 'gofood' ); ?> <span class="text-danger">*</span></label>
		<input type="text" required name="full_name" class="form-control form-control-lg fs-6 rounded-1" id="name" placeholder="John Doe">
	</div>
	<div class="form-group mb-4">
		<label for="email" class="form-label"><?php echo esc_html__( 'Email Address', 'gofood' ); ?> <span class="text-danger">*</span></label>
		<input type="email" required name="email" class="form-control form-control-lg fs-6 rounded-1" id="email" placeholder="john.doe@hotmail.com">
	</div>
	<div class="form-group mb-4">
		<label for="password" class="form-label"><?php echo esc_html__( 'Password', 'gofood' ); ?> <span class="text-danger">*</span></label>
		<input type="password" required name="password" class="form-control form-control-lg fs-6 rounded-1" id="password" placeholder="*******">
		<div class="form-text"><?php echo esc_html__( 'Minimum 8 characters with at least one number', 'gofood' ); ?></div>
	</div>
	
	<div class="form-group mb-4">
		<label for="confirm_password" class="form-label"><?php echo esc_html__( 'Confirm Password', 'gofood' ); ?> <span class="text-danger">*</span></label>
		<input type="password" required name="confirm_password" class="form-control form-control-lg fs-6 rounded-1" id="confirm_password" placeholder="*******">
	</div>

	<button type="submit" class="btn btn-primary-red w-100 py-2 fw-semibold text-uppercase"><?php echo esc_html__( 'Register', 'gofood' ); ?></button>
</form>

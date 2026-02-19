<?php
/**
 * Render the login Form.
 *
 * @package goFood
 */

?>

<form id="user_login_form" method="POST">

	<p class="alert alert-warning mb-4" style="display: none;" id="user_login_error"></p>

	<div class="form-group mb-4">
		<label for="user" class="form-label"><?php echo esc_html__( 'Email Address / Username', 'gofood' ); ?> <span class="text-danger">*</span></label>
		<input required name="user" type="text" class="form-control form-control-lg fs-6 rounded-1" id="user" placeholder="john.doe@hotmail.com">
	</div>
	<div class="form-group mb-4">
		<label for="password" class="form-label"><?php echo esc_html__( 'Password', 'gofood' ); ?> <span class="text-danger">*</span></label>
		<input required name="password" type="password" class="form-control form-control-lg fs-6 rounded-1" id="password" placeholder="*******">
	</div>

	<button type="submit" class="btn btn-primary-red w-100 py-2 fw-semibold text-uppercase"><?php echo esc_html__( 'Login', 'gofood' ); ?></button>
</form>

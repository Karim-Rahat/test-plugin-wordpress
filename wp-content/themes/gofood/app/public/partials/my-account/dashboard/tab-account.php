<?php
/**
 * Render User Orders Tab.
 *
 * @package goFood
 */

$user = wp_get_current_user();

?>
<div class="bg-white rounded-3 shadow-sm border p-4 p-lg-5">
	<form id="update_ma_profile_account" method="POST">
		<!-- Personal Information Section -->
		<div class="mb-5">
			<h5 class="fw-bold mb-4 pb-2 border-bottom">
				<i class="fas fa-id-card me-2 text-primary-red"></i> <?php echo esc_html__( 'Personal Information', 'gofood' ); ?>
			</h5>
			
			<div class="form-group mb-4">
				<label for="fullname" class="form-label"><?php echo esc_html__( 'Full Name', 'gofood' ); ?> <span class="text-danger">*</span></label>
				<input type="text" name="full_name" class="form-control form-control-lg fs-6 rounded-1" id="fullname" value="<?php echo esc_attr( $user->display_name ); ?>">
			</div>
			
			<div class="form-group mb-4">
				<label for="user" class="form-label"><?php echo esc_html__( 'Email Address', 'gofood' ); ?> <span class="text-danger">*</span></label>
				<input type="email" name="email" class="form-control form-control-lg fs-6 rounded-1" id="user" value="<?php echo esc_attr( $user->user_email ); ?>">
			</div>
		</div>
		
		<!-- Password Update Section -->
		<div class="mb-5">
			<h5 class="fw-bold mb-4 pb-2 border-bottom">
				<i class="fas fa-lock me-2 text-primary-red"></i> <?php echo esc_html__( 'Update Password', 'gofood' ); ?>
			</h5>
			
			<div class="alert px-3 py-2 alert-warning mb-4">
				<i class="fas fa-exclamation-circle me-2"></i> <?php echo esc_html__( 'Leave these fields blank if you don\'t want to change your password.', 'gofood' ); ?>
			</div>
			
			<div class="form-group mb-4">
				<label for="currentPassword" class="form-label"><?php echo esc_html__( 'Current Password', 'gofood' ); ?></label>
				<input type="password" name="old_password" class="form-control form-control-lg fs-6 rounded-1" id="currentPassword">
			</div>
			
			<div class="form-group mb-4">
				<label for="newPassword" class="form-label"><?php echo esc_html__( 'New Password', 'gofood' ); ?></label>
				<input type="password" name="password" class="form-control form-control-lg fs-6 rounded-1" id="newPassword">
				<div class="form-text"><?php echo esc_html__( 'Minimum 8 characters with at least one number', 'gofood' ); ?></div>
			</div>
			
			<div class="form-group mb-4">
				<label for="confirmPassword" class="form-label"><?php echo esc_html__( 'Confirm New Password', 'gofood' ); ?></label>
				<input type="password" name="confirm_password" class="form-control form-control-lg fs-6 rounded-1" id="confirmPassword">
			</div>
		</div>

		<p class="alert alert-danger mb-4" style="display: none;" id="user_ma_profile_update_error"></p>
		
		<!-- Form Actions -->
		<div class="d-flex justify-content-end gap-3">
			<button type="reset" class="btn fs-6 btn-outline-secondary btn-lg px-4"><?php echo esc_html__( 'Cancel', 'gofood' ); ?></button>
			<button type="submit" class="btn fs-6 btn-primary-red btn-lg px-4"><?php echo esc_html__( 'Save Changes', 'gofood' ); ?></button>
		</div>
	</form>
</div>

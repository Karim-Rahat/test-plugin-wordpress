<?php
/**
 * Render User Orders Tab.
 *
 * @package goFood
 */

$user           = wp_get_current_user();
$my_account_url = gf_get_my_account_url();
$active_tab     = get_query_var( 'ma_active_tab', 'orders' );

?>

<div class="account-sidebar p-3 text-center mb-4">
	<img src="<?php echo esc_url( get_avatar_url( $user->ID ) ); ?>" alt="User" class="user-avatar mb-3">
	<h5 class="mb-1"><?php echo esc_html( $user->display_name ); ?></h5>
	<p class="text-muted small mb-3"><?php echo esc_html( sprintf( '%s %s', __( 'Member since', 'gofood' ), date_i18n( 'F Y', strtotime( $user->user_registered ) ) ) ); ?></p>
</div>

<div class="account-sidebar">
	<ul class="nav flex-column">
		<li class="nav-item">
			<a class="nav-link <?php echo esc_attr( 'orders' === $active_tab ? 'active' : '' ); ?>" href="<?php echo esc_url( $my_account_url . '?ma_active_tab=orders' ); ?>">
				<i class="fas fa-clipboard-list"></i> <?php echo esc_html__( 'My Orders', 'gofood' ); ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php echo esc_attr( 'account' === $active_tab ? 'active' : '' ); ?>" href="<?php echo esc_url( $my_account_url . '?ma_active_tab=account' ); ?>">
				<i class="fas fa-user"></i> <?php echo esc_html__( 'Account Details', 'gofood' ); ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="<?php echo esc_url( wp_logout_url( $my_account_url ) ); ?>">
				<i class="fas fa-sign-out-alt"></i> <?php echo esc_html__( 'Logout', 'gofood' ); ?>
			</a>
		</li>
	</ul>
</div>

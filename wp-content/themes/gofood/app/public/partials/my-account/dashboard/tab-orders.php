<?php
/**
 * Render User Orders Tab.
 *
 * @package goFood
 */

$total_orders         = GoFood_Query::total( 'gf_orders', array( 'where' => 'user_id = ' . get_current_user_id() ) );
$total_pending_orders = GoFood_Query::total( 'gf_orders', array( 'where' => 'status = "pending" AND user_id = ' . get_current_user_id() ) );

// Custom Pagination.
$current_page   = max( 1, get_query_var( 'gf_page', 1 ) );
$limit_per_page = 10;
$total_pages    = ceil( $total_orders / $limit_per_page );

$orders = GoFood_Query::results(
	'gf_orders',
	array(
		'select'   => 'id, created_at, total, status, (SELECT COUNT(*) FROM ' . GoFood_Query::table( 'gf_order_items' ) . ' WHERE order_id = ' . GoFood_Query::table( 'gf_orders' ) . '.id) as items',
		'where'    => 'user_id = ' . get_current_user_id(),
		'order_by' => 'id',
		'order'    => 'desc',
		'offset'   => ( $current_page - 1 ) * $limit_per_page,
		'limit'    => $limit_per_page,
	)
);

?>
<div class="row g-4 mb-4">
	<div class="col-md-6">
		<div class="account-card">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<h6 class="text-muted mb-2"><?php echo esc_html__( 'Total Orders', 'gofood' ); ?></h6>
					<div class="count"><?php echo esc_html( number_format( $total_orders ) ); ?></div>
				</div>
				<div class="bg-light-red p-3 rounded-circle">
					<i class="fas fa-shopping-bag text-primary-red" style="font-size: 1.5rem;"></i>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="account-card">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<h6 class="text-muted mb-2"><?php echo esc_html__( 'Pending Orders', 'gofood' ); ?></h6>
					<div class="count"><?php echo esc_html( number_format( $total_pending_orders ) ); ?></div>
				</div>
				<div class="bg-light-red p-3 rounded-circle">
					<i class="fas fa-clock text-primary-red" style="font-size: 1.5rem;"></i>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="order-table">
	<div class="p-3 border-bottom">
		<h5 class="mb-0 fw-bold"><?php echo esc_html__( 'Recent Orders', 'gofood' ); ?></h5>
	</div>
	
	<div class="table-responsive">
		<table class="table table-hover mb-0">
			<thead>
				<tr>
					<th><?php echo esc_html__( 'Order ID', 'gofood' ); ?></th>
					<th><?php echo esc_html__( 'Date', 'gofood' ); ?></th>
					<th><?php echo esc_html__( 'Items', 'gofood' ); ?></th>
					<th><?php echo esc_html__( 'Total', 'gofood' ); ?></th>
					<th><?php echo esc_html__( 'Status', 'gofood' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $orders as $item ) : ?>
					<tr>
						<td>#FX-<?php echo esc_html( $item['id'] ); ?></td>
						<td><?php echo esc_html( gmdate( 'd M, Y', strtotime( $item['created_at'] ) ) ); ?></td>
						<td><?php echo esc_html( $item['items'] ); ?></td>
						<td><?php echo wp_kses( gf_format_price( $item['total'] ), true ); ?></td>
						<td><span class="order-status status-<?php echo esc_attr( $item['status'] ); ?>"><?php echo esc_html( ucwords( $item['status'] ) ); ?></span></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	
	<div class="p-3 border-top d-flex justify-content-between align-items-center">
		<div class="text-muted small">Showing <?php echo esc_html( ( $current_page - 1 ) * $limit_per_page + 1 ); ?> to <?php echo esc_html( ( $current_page - 1 ) * $limit_per_page + count( $orders ) ); ?> of <?php echo esc_html( $total_orders ); ?> orders</div>
		<nav aria-label="Product pagination">
			<?php
			echo wp_kses(
				gf_get_paginate_links(
					array(
						'total'     => $total_pages,
						'current'   => $current_page,
						'type'      => 'list',
						'mid_size'  => 2,
						'prev_text' => '<i class="fas fa-angle-left"></i>',
						'next_text' => '<i class="fas fa-angle-right"></i>',
						'format'    => '?gf_page=%#%',
					),
					array(
						'ul' => 'pagination pagination-sm mb-0',
					)
				),
				true
			);
			?>
		</nav>
	</div>
</div>

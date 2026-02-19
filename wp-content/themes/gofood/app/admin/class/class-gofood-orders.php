<?php
/**
 * The admin-specific functionality of the theme.
 *
 * @link  https://moyshan.netlify.app
 * @since 1.0.0
 *
 * @package    GoFood
 * @subpackage GoFood/admin
 */

/**
 * The admin-specific functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    GoFood
 * @subpackage GoFood/admin
 * @author     Shahin Moyshan <shahin.moyshan2@gmail.com>
 */
class GoFood_Orders {

	/**
	 * Display the orders management page.
	 *
	 * Retrieve the orders management page. This page shows a list of all orders
	 * with their details, actions to view, edit, and delete them.
	 *
	 * @since 1.0.0
	 */
	public static function manage() {
		$bread = GoFood_Bread::setup(
			array(
				'table'           => 'gf_orders',
				'limit'           => 10,
				'columns'         => array(
					'id'         => 'Order Id',
					'user_id'    => 'Customer',
					'address'    => 'Street Address',
					'phone'      => 'Phone',
					'email'      => 'Email',
					'total'      => 'Total',
					'status'     => 'Status',
					'created_at' => 'Date & Time',
				),
				'sortable_column' => array(
					'id'     => array( 'id', true ),
					'email'  => array( 'email', false ),
					'total'  => array( 'total', false ),
					'status' => array( 'status', false ),
				),
				'search_field'    => 'email',
				'actions_column'  => 'Actions',
				'bread'           => array(
					'action' => 'Order',
					'table'  => array(
						'title'       => 'Manage Orders',
						'description' => 'Manage Recent Orders, and View Order Details.',
					),
					'edit'   => array(
						'title'       => 'Edit Order',
						'description' => 'Edit and Update Order Info',
						'fields'      => array(
							array(
								'type'  => 'number',
								'label' => 'Order Total',
								'name'  => 'total',
							),
							array(
								'type'  => 'textarea',
								'label' => 'Street Address',
								'name'  => 'address',
							),
							array(
								'type'  => 'text',
								'label' => 'Phone Number',
								'name'  => 'phone',
							),
							array(
								'type'  => 'email',
								'label' => 'Email Address',
								'name'  => 'email',
							),
							array(
								'type'    => 'select',
								'label'   => 'Order Status',
								'name'    => 'status',
								'options' => array(
									'pending'   => 'Pending',
									'completed' => 'Completed',
									'cancelled' => 'Cancelled',
								),
							),
						),
					),
					'view'   => array(
						'title'       => 'View Order Details',
						'description' => 'View Customer Personal Details and Order Information and Ordered Items.',
					),
				),
			)
		);

		$bread->filters(
			array(
				'columns'                => array(
					'user_id'    => fn ( $data ) => ! isset( $data['user_id'] ) ? 'Guest' : sprintf( '<a href="%s">User - #%d</a>', get_edit_user_link( $data['user_id'] ), $data['user_id'] ),
					'created_at' => fn ( $data ) => gmdate( 'd M, y g:i:a', strtotime( $data['created_at'] ) ),
					'total'      => fn ( $data ) => gf_format_price( $data['total'] ),
					'status'     => fn ( $data ) => ucfirst( $data['status'] ),
				),
				'bread_render_view_data' => function ( $data ) {
					if ( ! isset( $data['record']['id'] ) ) {
						return $data;
					}

					$data['record']['user_id'] = ! isset( $data['record']['user_id'] ) ? 'Guest' : sprintf( '<a href="%s">User - #%d</a>', get_edit_user_link( $data['record']['user_id'] ), $data['record']['user_id'] );
					$data['record']['total'] = gf_format_price( $data['record']['total'] );
					$data['record']['status'] = ucfirst( $data['record']['status'] );
					$data['record']['created_at'] = gmdate( 'd M, y g:i:a', strtotime( $data['record']['created_at'] ) );

					$ordered_items = GoFood_Query::results(
						'gf_order_items',
						array(
							'select' => 'quantity, product_id, (SELECT post_title FROM ' . GoFood_Query::table( 'posts' ) . ' WHERE ID = ' . GoFood_Query::table( 'gf_order_items' ) . '.product_id) as product_name',
							'where'  => 'order_id = ' . $data['record']['id'],
						)
					);

					$data['record']['Ordered_Items'] = join(
						'<br>',
						array_map(
							function ( $item ) {
								return sprintf( '<b>%s</b> x <a href="%s">%s</a>', $item['quantity'], get_edit_post_link( $item['product_id'] ), $item['product_name'] );
							},
							$ordered_items
						)
					);

					return $data;
				},
			)
		);

		$bread->render();
	}
}

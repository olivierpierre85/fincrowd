<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$html .= '<div class="wpneo-content">';
    $html .= '<div class="wpneo-form campaign-listing-page">';

	$args = array(
			'post_type' 		=> 'product',
			'tax_query' 		=> array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'crowdfunding',
				),
			),
			'posts_per_page'    => -1
		);
		$id_list = get_posts( $args );

		$id_array = array();
		foreach ($id_list as $value) {
			$id_array[] = $value->ID;
		}
		$order_ids = array( '9999999' );
		if( is_array( $id_array ) ){
		    if(!empty($id_array)){
		    	$id_array = implode( ', ', $id_array );
		    	global $wpdb;
				$prefix = $wpdb->prefix;
				$query = "SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items oi 
						LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta woim 
						ON woim.order_item_id = oi.order_item_id 
						WHERE woim.meta_key='_product_id' AND woim.meta_value IN ( {$id_array} )";
				$order_ids = $wpdb->get_col( $query );
		    }
		}

		$page_numb = max( 1, get_query_var('paged') );

		$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
			'order-number'  => __( 'Order', 'wp-crowdfunding' ),
			'order-date'    => __( 'Date', 'wp-crowdfunding' ),
			'order-status'  => __( 'Status', 'wp-crowdfunding' ),
			'order-total'   => __( 'Total', 'wp-crowdfunding' ),
			'order-actions' => '&nbsp;',
		) );

		$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
			'numberposts' => 10, // Chnage Number
			'paged'		  => $page_numb,
			'post__in'	  => $order_ids,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types( 'view-orders' ),
			'post_status' => array_keys( wc_get_order_statuses() )
		) ) );


		if ( $customer_orders ) :

			$html .='<table width="100%" class="stripe-table">';

				$html .='<thead>';
					$html .='<tr>';
						foreach ( $my_orders_columns as $column_id => $column_name ) :
							$html .='<th class="'.esc_attr( $column_id ).'">#<span class="nobr">'.esc_html( $column_name ).'</span></th>';
						endforeach;
					$html .='</tr>';
				$html .='</thead>';

				$html .='<tbody>';
					foreach ( $customer_orders as $customer_order ) :
						$order      = wc_get_order( $customer_order );
						$item_count = $order->get_item_count();
						
						$html .='<tr class="order">';
							foreach ( $my_orders_columns as $column_id => $column_name ) :
								$html .='<td class="'.esc_attr( $column_id ).'" data-title="'.esc_attr( $column_name ).'">';
									if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) :
										do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order );

									elseif ( 'order-number' === $column_id ) :
										$html .='<a href="'.esc_url( $order->get_view_order_url() ).'">';
											$html .= _x( '#', 'hash before order number', 'wp-crowdfunding' ) . $order->get_order_number().'
										</a>';

									elseif ( 'order-date' === $column_id ) :
										$html .='<time datetime="'.date( 'Y-m-d', strtotime( $order->order_date ) ).'" title="'.esc_attr( strtotime( $order->order_date ) ).'">'.date_i18n( get_option( "date_format" ), strtotime( $order->order_date ) ).'</time>';

									elseif ( 'order-status' === $column_id ) :
										$html .= wc_get_order_status_name( $order->get_status() );

									elseif ( 'order-total' === $column_id ) :
										$html .= sprintf( _n( '%s for %s item', '%s for %s items', $item_count, 'wp-crowdfunding' ), $order->get_formatted_order_total(), $item_count );

									elseif ( 'order-actions' === $column_id ) :
										
											$actions = array(
												'pay'    => array(
													'url'  => $order->get_checkout_payment_url(),
													'name' => __( 'Pay', 'wp-crowdfunding' )
												),
												'view'   => array(
													'url'  => $order->get_view_order_url(),
													'name' => __( 'View', 'wp-crowdfunding' )
												),
												'cancel' => array(
													'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
													'name' => __( 'Cancel', 'wp-crowdfunding' )
												)
											);

											if ( ! $order->needs_payment() ) {
												unset( $actions['pay'] );
											}

											if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
												unset( $actions['cancel'] );
											}

											if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
												foreach ( $actions as $key => $action ) {
													$html .='<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
											}
										
									endif;
								$html .='</td>';
							endforeach;
						$html .='</tr>';
					endforeach;
				$html .='</tbody>';
				$html .='<tfoot>';
					$html .='<tr>';
						foreach ( $my_orders_columns as $column_id => $column_name ) :
							$html .='<td class="'.esc_attr( $column_id ).'">#<span class="nobr">'.esc_html( $column_name ).'</span></td>';
						endforeach;
					$html .='</tr>';
				$html .='</tfoot>';
			$html .='</table>';
		else:
			$html .= "<p>".__( 'Sorry, No Backed Campaigns Data Found.','wp-crowdfunding' )."</p>";
		endif;

		$customer_order_all = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
			'numberposts' => -1,
			'post__in'	  => $order_ids,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types( 'view-orders' ),
			'post_status' => array_keys( wc_get_order_statuses() )
		) ) );

		$max_page = 1;
		if(!empty($customer_order_all)){
			$max_page = ceil( count($customer_order_all)/10 );
		}
		// Pagination
		$html .= wpneo_crowdfunding_pagination( $page_numb , $max_page );


    $html .='</div>';
$html .='</div>';
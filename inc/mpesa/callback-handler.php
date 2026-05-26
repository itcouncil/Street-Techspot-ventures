<?php
/**
 * M-Pesa callback handler.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register callback route.
 */
function register_mpesa_routes() {
	register_rest_route(
		'street-techspot/v1',
		'/mpesa/callback',
		array(
			'methods'             => 'POST',
			'callback'            => __NAMESPACE__ . '\\handle_mpesa_callback',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', __NAMESPACE__ . '\\register_mpesa_routes' );

/**
 * Handle Safaricom callback.
 *
 * @param \WP_REST_Request $request Request.
 * @return \WP_REST_Response
 */
function handle_mpesa_callback( \WP_REST_Request $request ) {
	$payload = $request->get_json_params();
	$stk     = $payload['Body']['stkCallback'] ?? array();
	$id      = isset( $stk['CheckoutRequestID'] ) ? sanitize_text_field( $stk['CheckoutRequestID'] ) : '';

	if ( empty( $id ) ) {
		return new \WP_REST_Response( array( 'ResultCode' => 1, 'ResultDesc' => 'Missing checkout ID' ), 400 );
	}

	$key     = 'stv_mpesa_' . sanitize_key( $id );
	$record  = get_transient( $key );
	$success = isset( $stk['ResultCode'] ) && 0 === absint( $stk['ResultCode'] );

	if ( ! is_array( $record ) ) {
		mpesa_log( 'Duplicate or unknown callback', array( 'checkout_request_id' => $id ) );
		return new \WP_REST_Response( array( 'ResultCode' => 0, 'ResultDesc' => 'Accepted' ), 200 );
	}

	$record['status'] = $success ? 'paid' : 'failed';
	$record['result'] = $stk;
	set_transient( $key, $record, DAY_IN_SECONDS );

	if ( ! empty( $record['order_id'] ) && function_exists( 'wc_get_order' ) ) {
		$order = wc_get_order( absint( $record['order_id'] ) );

		if ( $order && ! $order->is_paid() ) {
			if ( $success ) {
				$order->payment_complete( $id );
				$order->add_order_note( __( 'M-Pesa payment confirmed by callback.', 'street-techspot-ventures' ) );
			} else {
				$order->update_status( 'failed', __( 'M-Pesa payment failed or was cancelled.', 'street-techspot-ventures' ) );
			}
		}
	}

	mpesa_log( 'STK callback processed', array( 'checkout_request_id' => $id, 'success' => $success ) );

	return new \WP_REST_Response( array( 'ResultCode' => 0, 'ResultDesc' => 'Accepted' ), 200 );
}

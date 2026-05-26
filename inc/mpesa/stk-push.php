<?php
/**
 * M-Pesa STK push flow.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initiate STK push for a product quick checkout.
 *
 * @param string $phone Raw phone number.
 * @param int    $product_id Product ID.
 * @return array|\WP_Error
 */
function initiate_stk_push( $phone, $product_id ) {
	$phone = mpesa_normalize_phone( $phone );

	if ( is_wp_error( $phone ) ) {
		return $phone;
	}

	$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;

	if ( ! $product || ! function_exists( 'wc_create_order' ) ) {
		return new \WP_Error( 'stv_mpesa_product', __( 'Product not found.', 'street-techspot-ventures' ) );
	}

	$config = mpesa_config();
	$token  = mpesa_get_token();

	if ( is_wp_error( $token ) ) {
		return $token;
	}

	$timestamp = current_time( 'YmdHis' );
	$password  = base64_encode( $config['shortcode'] . $config['passkey'] . $timestamp );
	$amount    = max( 1, (int) ceil( (float) $product->get_price() ) );
	$reference = 'STV-' . $product_id . '-' . time();
	$order     = wc_create_order();

	if ( is_wp_error( $order ) ) {
		return $order;
	}

	$order->add_product( $product, 1 );
	$order->set_billing_phone( $phone );
	$order->set_payment_method( 'stv_mpesa' );
	$order->set_payment_method_title( __( 'M-Pesa STK Push', 'street-techspot-ventures' ) );
	$order->calculate_totals();
	$order->update_status( 'pending', __( 'Awaiting M-Pesa STK confirmation.', 'street-techspot-ventures' ) );
	$order->save();

	$amount = max( 1, (int) ceil( (float) $order->get_total() ) );

	$payload   = array(
		'BusinessShortCode' => $config['shortcode'],
		'Password'          => $password,
		'Timestamp'         => $timestamp,
		'TransactionType'   => 'CustomerPayBillOnline',
		'Amount'            => $amount,
		'PartyA'            => $phone,
		'PartyB'            => $config['shortcode'],
		'PhoneNumber'       => $phone,
		'CallBackURL'       => esc_url_raw( $config['callback_url'] ),
		'AccountReference'  => $reference,
		'TransactionDesc'   => $product->get_name(),
	);

	$response = wp_remote_post(
		mpesa_base_url() . '/mpesa/stkpush/v1/processrequest',
		array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
				'Content-Type'  => 'application/json',
			),
			'body'    => wp_json_encode( $payload ),
			'timeout' => 20,
		)
	);

	if ( is_wp_error( $response ) ) {
		mpesa_log( 'STK push failed', array( 'error' => $response->get_error_message() ) );
		return $response;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	mpesa_log( 'STK push response', array( 'response' => $body ) );

	if ( empty( $body['CheckoutRequestID'] ) ) {
		return new \WP_Error( 'stv_mpesa_stk', __( 'Unable to start M-Pesa prompt.', 'street-techspot-ventures' ) );
	}

	set_transient(
		'stv_mpesa_' . sanitize_key( $body['CheckoutRequestID'] ),
		array(
			'product_id' => $product_id,
			'phone'      => $phone,
			'amount'     => $amount,
			'order_id'   => $order->get_id(),
			'reference'  => $reference,
			'status'     => 'pending',
		),
		HOUR_IN_SECONDS
	);

	return array(
		'checkout_request_id' => sanitize_text_field( $body['CheckoutRequestID'] ),
		'order_id'            => $order->get_id(),
		'message'             => __( 'M-Pesa prompt sent.', 'street-techspot-ventures' ),
	);
}

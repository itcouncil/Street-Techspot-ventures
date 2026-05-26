<?php
/**
 * M-Pesa client abstraction.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get M-Pesa config from constants or options.
 *
 * @return array<string,string>
 */
function mpesa_config() {
	return array(
		'environment'    => defined( 'STV_MPESA_ENVIRONMENT' ) ? STV_MPESA_ENVIRONMENT : get_option( 'stv_mpesa_environment', 'sandbox' ),
		'consumer_key'   => defined( 'STV_MPESA_CONSUMER_KEY' ) ? STV_MPESA_CONSUMER_KEY : get_option( 'stv_mpesa_consumer_key', '' ),
		'consumer_secret' => defined( 'STV_MPESA_CONSUMER_SECRET' ) ? STV_MPESA_CONSUMER_SECRET : get_option( 'stv_mpesa_consumer_secret', '' ),
		'shortcode'      => defined( 'STV_MPESA_SHORTCODE' ) ? STV_MPESA_SHORTCODE : get_option( 'stv_mpesa_shortcode', '' ),
		'passkey'        => defined( 'STV_MPESA_PASSKEY' ) ? STV_MPESA_PASSKEY : get_option( 'stv_mpesa_passkey', '' ),
		'callback_url'   => esc_url_raw( rest_url( 'street-techspot/v1/mpesa/callback' ) ),
	);
}

/**
 * Return API base URL.
 *
 * @return string
 */
function mpesa_base_url() {
	$config = mpesa_config();

	return 'production' === $config['environment'] ? 'https://api.safaricom.co.ke' : 'https://sandbox.safaricom.co.ke';
}

/**
 * Log M-Pesa events safely.
 *
 * @param string $message Message.
 * @param array  $context Context.
 */
function mpesa_log( $message, $context = array() ) {
	if ( function_exists( 'wc_get_logger' ) ) {
		wc_get_logger()->info(
			$message,
			array(
				'source'  => 'street-techspot-mpesa',
				'context' => $context,
			)
		);
	}
}

/**
 * Normalize Kenyan phone numbers to 2547XXXXXXXX.
 *
 * @param string $phone Raw phone.
 * @return string|\WP_Error
 */
function mpesa_normalize_phone( $phone ) {
	$digits = preg_replace( '/\D+/', '', $phone );

	if ( 10 === strlen( $digits ) && 0 === strpos( $digits, '07' ) ) {
		$digits = '254' . substr( $digits, 1 );
	}

	if ( 12 === strlen( $digits ) && 0 === strpos( $digits, '2547' ) ) {
		return $digits;
	}

	return new \WP_Error( 'stv_mpesa_phone', __( 'Enter a valid Safaricom number.', 'street-techspot-ventures' ) );
}

/**
 * Get OAuth token with transient caching.
 *
 * @return string|\WP_Error
 */
function mpesa_get_token() {
	$token = get_transient( 'stv_mpesa_token' );

	if ( $token ) {
		return $token;
	}

	$config = mpesa_config();

	if ( empty( $config['consumer_key'] ) || empty( $config['consumer_secret'] ) ) {
		return new \WP_Error( 'stv_mpesa_config', __( 'M-Pesa credentials are not configured.', 'street-techspot-ventures' ) );
	}

	$response = wp_remote_get(
		mpesa_base_url() . '/oauth/v1/generate?grant_type=client_credentials',
		array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $config['consumer_key'] . ':' . $config['consumer_secret'] ),
			),
			'timeout' => 15,
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( empty( $body['access_token'] ) ) {
		return new \WP_Error( 'stv_mpesa_token', __( 'Could not generate M-Pesa token.', 'street-techspot-ventures' ) );
	}

	set_transient( 'stv_mpesa_token', sanitize_text_field( $body['access_token'] ), 50 * MINUTE_IN_SECONDS );

	return sanitize_text_field( $body['access_token'] );
}

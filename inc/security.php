<?php
/**
 * Security hardening.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable XML-RPC.
 *
 * @return bool
 */
function disable_xmlrpc() {
	return false;
}
add_filter( 'xmlrpc_enabled', __NAMESPACE__ . '\\disable_xmlrpc' );

/**
 * Add conservative security headers.
 */
function send_security_headers() {
	if ( headers_sent() ) {
		return;
	}

	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
}
add_action( 'send_headers', __NAMESPACE__ . '\\send_security_headers' );

/**
 * Hide user endpoints from public REST requests.
 *
 * @param mixed           $result REST result.
 * @param \WP_REST_Server $server REST server.
 * @param \WP_REST_Request $request REST request.
 * @return mixed
 */
function harden_rest_users( $result, $server, $request ) {
	if ( 0 === strpos( $request->get_route(), '/wp/v2/users' ) && ! current_user_can( 'list_users' ) ) {
		return new \WP_Error( 'stv_rest_forbidden', __( 'REST access denied.', 'street-techspot-ventures' ), array( 'status' => 403 ) );
	}

	return $result;
}
add_filter( 'rest_pre_dispatch', __NAMESPACE__ . '\\harden_rest_users', 10, 3 );

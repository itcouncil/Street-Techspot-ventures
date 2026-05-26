<?php
/**
 * Receipt and quotation helpers.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a receipt verification URL.
 *
 * @param int $order_id Order ID.
 * @return string
 */
function get_receipt_verification_url( $order_id ) {
	return add_query_arg( 'stv_receipt', absint( $order_id ), home_url( '/' ) );
}

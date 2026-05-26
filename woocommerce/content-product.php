<?php
/**
 * Minimal product loop card.
 *
 * @package Street_Techspot_Ventures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product || ! $product->is_visible() ) {
	return;
}

\STV\Theme\render_product_card( $product->get_id() );

<?php
/**
 * AJAX endpoints.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register localized AJAX data.
 */
function localize_ajax_data() {
	wp_localize_script(
		'street-techspot-ventures-app',
		'stvAjax',
		array(
			'url'      => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'stv_ajax_nonce' ),
			'checkout' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\localize_ajax_data', 20 );

/**
 * Rate-limit lightweight public endpoints.
 *
 * @param string $action Action name.
 * @param int    $limit Max attempts.
 * @return bool
 */
function is_rate_limited( $action, $limit = 20 ) {
	$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	$key  = 'stv_rate_' . sanitize_key( $action ) . '_' . md5( $ip );
	$hits = absint( get_transient( $key ) );

	if ( $hits >= $limit ) {
		return true;
	}

	set_transient( $key, $hits + 1, MINUTE_IN_SECONDS );

	return false;
}

/**
 * Verify AJAX nonce and rate limit.
 *
 * @param string $action Action name.
 */
function verify_ajax_request( $action ) {
	check_ajax_referer( 'stv_ajax_nonce', 'nonce' );

	if ( is_rate_limited( $action ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Too many requests. Please try again shortly.', 'street-techspot-ventures' ),
			),
			429
		);
	}
}

/**
 * Live product search.
 */
function ajax_live_search() {
	verify_ajax_request( 'live_search' );

	if ( ! function_exists( 'wc_get_product' ) ) {
		wp_send_json_success( array( 'results' => array() ) );
	}

	$term = isset( $_GET['term'] ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';

	if ( strlen( $term ) < 2 ) {
		wp_send_json_success( array( 'results' => array() ) );
	}

	$ids     = get_cached_product_ids(
		'search_' . $term,
		array(
			's'              => $term,
			'posts_per_page' => 6,
		),
		5 * MINUTE_IN_SECONDS
	);
	$sku_id  = wc_get_product_id_by_sku( $term );
	if ( $sku_id ) {
		array_unshift( $ids, $sku_id );
		$ids = array_values( array_unique( array_map( 'absint', $ids ) ) );
	}
	$results = array();

	foreach ( $ids as $id ) {
		$product = wc_get_product( $id );

		if ( ! $product ) {
			continue;
		}

		$results[] = array(
			'id'    => $id,
			'name'  => $product->get_name(),
			'url'   => get_permalink( $id ),
			'price' => wp_strip_all_tags( $product->get_price_html() ),
			'image' => wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ),
			'stock' => $product->get_stock_status(),
			'sku'   => $product->get_sku(),
		);
	}

	wp_send_json_success( array( 'results' => $results ) );
}

/**
 * AJAX add to cart.
 */
function ajax_add_to_cart() {
	verify_ajax_request( 'add_to_cart' );

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$quantity   = isset( $_POST['quantity'] ) ? max( 1, absint( $_POST['quantity'] ) ) : 1;

	if ( ! $product_id || ! function_exists( 'WC' ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product.', 'street-techspot-ventures' ) ), 400 );
	}

	$added = WC()->cart->add_to_cart( $product_id, $quantity );

	if ( ! $added ) {
		wp_send_json_error( array( 'message' => __( 'Unable to add product.', 'street-techspot-ventures' ) ), 400 );
	}

	wp_send_json_success(
		array(
			'message'    => __( 'Added to cart.', 'street-techspot-ventures' ),
			'cart_count' => WC()->cart->get_cart_contents_count(),
		)
	);
}

/**
 * Return product specs drawer content.
 */
function ajax_product_specs() {
	verify_ajax_request( 'product_specs' );

	if ( ! function_exists( 'wc_get_product' ) ) {
		wp_send_json_error( array( 'message' => __( 'WooCommerce is unavailable.', 'street-techspot-ventures' ) ), 503 );
	}

	$product_id = isset( $_GET['product_id'] ) ? absint( $_GET['product_id'] ) : 0;
	$product    = wc_get_product( $product_id );

	if ( ! $product ) {
		wp_send_json_error( array( 'message' => __( 'Product not found.', 'street-techspot-ventures' ) ), 404 );
	}

	$attributes = array();

	foreach ( $product->get_attributes() as $attribute ) {
		$attributes[] = wc_attribute_label( $attribute->get_name() ) . ': ' . $product->get_attribute( $attribute->get_name() );
	}

	$stored_specs = get_post_meta( $product_id, '_stv_specs', true );
	if ( $stored_specs ) {
		$decoded = json_decode( $stored_specs, true );
		if ( is_array( $decoded ) ) {
			foreach ( $decoded as $label => $value ) {
				$attributes[] = sanitize_text_field( $label ) . ': ' . sanitize_text_field( $value );
			}
		}
	}

	wp_send_json_success(
		array(
			'specs' => $attributes,
		)
	);
}

/**
 * Return current stock status.
 */
function ajax_stock_status() {
	verify_ajax_request( 'stock_status' );

	if ( ! function_exists( 'wc_get_product' ) ) {
		wp_send_json_error( array( 'message' => __( 'WooCommerce is unavailable.', 'street-techspot-ventures' ) ), 503 );
	}

	$product_id = isset( $_GET['product_id'] ) ? absint( $_GET['product_id'] ) : 0;
	$product    = wc_get_product( $product_id );

	if ( ! $product ) {
		wp_send_json_error( array( 'message' => __( 'Product not found.', 'street-techspot-ventures' ) ), 404 );
	}

	wp_send_json_success(
		array(
			'stock_status' => $product->get_stock_status(),
			'stock_left'   => $product->managing_stock() ? max( 0, (int) $product->get_stock_quantity() ) : null,
		)
	);
}

/**
 * Start M-Pesa quick checkout.
 */
function ajax_quick_checkout() {
	verify_ajax_request( 'quick_checkout' );

	$phone      = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	if ( ! function_exists( __NAMESPACE__ . '\\initiate_stk_push' ) ) {
		wp_send_json_error( array( 'message' => __( 'Payment service unavailable.', 'street-techspot-ventures' ) ), 503 );
	}

	$response = initiate_stk_push( $phone, $product_id );

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( array( 'message' => $response->get_error_message() ), 400 );
	}

	wp_send_json_success( $response );
}

/**
 * Check M-Pesa checkout status.
 */
function ajax_mpesa_status() {
	verify_ajax_request( 'mpesa_status' );

	$checkout_id = isset( $_GET['checkout_request_id'] ) ? sanitize_text_field( wp_unslash( $_GET['checkout_request_id'] ) ) : '';
	$record      = $checkout_id ? get_transient( 'stv_mpesa_' . sanitize_key( $checkout_id ) ) : false;

	if ( ! is_array( $record ) ) {
		wp_send_json_error( array( 'message' => __( 'Payment session not found.', 'street-techspot-ventures' ) ), 404 );
	}

	wp_send_json_success(
		array(
			'status'   => sanitize_text_field( $record['status'] ),
			'order_id' => isset( $record['order_id'] ) ? absint( $record['order_id'] ) : 0,
		)
	);
}

$ajax_actions = array(
	'stv_live_search'    => 'ajax_live_search',
	'stv_add_to_cart'    => 'ajax_add_to_cart',
	'stv_product_specs'  => 'ajax_product_specs',
	'stv_stock_status'   => 'ajax_stock_status',
	'stv_quick_checkout' => 'ajax_quick_checkout',
	'stv_mpesa_status'   => 'ajax_mpesa_status',
);

foreach ( $ajax_actions as $hook => $callback ) {
	add_action( 'wp_ajax_' . $hook, __NAMESPACE__ . '\\' . $callback );
	add_action( 'wp_ajax_nopriv_' . $hook, __NAMESPACE__ . '\\' . $callback );
}

/**
 * Store newsletter signup locally for launch operations.
 */
function handle_newsletter_signup() {
	check_admin_referer( 'stv_newsletter_signup' );

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( is_email( $email ) ) {
		$emails = get_option( 'stv_newsletter_emails', array() );
		$emails = is_array( $emails ) ? $emails : array();
		$emails[] = $email;
		update_option( 'stv_newsletter_emails', array_values( array_unique( $emails ) ), false );
	}

	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : home_url( '/' ) );
	exit;
}
add_action( 'admin_post_stv_newsletter_signup', __NAMESPACE__ . '\\handle_newsletter_signup' );
add_action( 'admin_post_nopriv_stv_newsletter_signup', __NAMESPACE__ . '\\handle_newsletter_signup' );

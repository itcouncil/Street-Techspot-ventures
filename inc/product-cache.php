<?php
/**
 * Product query cache.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const PRODUCT_CACHE_GROUP = 'stv_products';

/**
 * Build a stable transient key.
 *
 * @param string $type Cache type.
 * @param array  $args Cache args.
 * @return string
 */
function product_cache_key( $type, $args = array() ) {
	return 'stv_' . sanitize_key( $type ) . '_' . md5( wp_json_encode( $args ) );
}

/**
 * Get cached product IDs.
 *
 * @param string $type Cache type.
 * @param array  $query_args Query args.
 * @param int    $ttl Cache time.
 * @return int[]
 */
function get_cached_product_ids( $type, $query_args, $ttl = HOUR_IN_SECONDS ) {
	$key = product_cache_key( $type, $query_args );
	$ids = get_transient( $key );

	if ( is_array( $ids ) ) {
		return array_map( 'absint', $ids );
	}

	$defaults = array(
		'post_type'              => 'product',
		'post_status'            => 'publish',
		'fields'                 => 'ids',
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'ignore_sticky_posts'    => true,
	);

	$ids = get_posts( wp_parse_args( $query_args, $defaults ) );
	$ids = array_map( 'absint', $ids );

	set_transient( $key, $ids, absint( $ttl ) );

	return $ids;
}

/**
 * Get featured product IDs.
 *
 * @param int $limit Number of products.
 * @return int[]
 */
function get_featured_product_ids( $limit = 8 ) {
	return get_cached_product_ids(
		'featured',
		array(
			'posts_per_page' => absint( $limit ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				),
			),
		)
	);
}

/**
 * Get recent product IDs.
 *
 * @param int $limit Number of products.
 * @return int[]
 */
function get_recent_product_ids( $limit = 12 ) {
	return get_cached_product_ids(
		'recent',
		array(
			'posts_per_page' => absint( $limit ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
}

/**
 * Get trending products by sales.
 *
 * @param int $limit Number of products.
 * @return int[]
 */
function get_trending_product_ids( $limit = 8 ) {
	return get_cached_product_ids(
		'trending',
		array(
			'posts_per_page' => absint( $limit ),
			'meta_key'       => 'total_sales',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		)
	);
}

/**
 * Get sale products.
 *
 * @param int $limit Number of products.
 * @return int[]
 */
function get_sale_product_ids( $limit = 8 ) {
	$sale_ids = function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : array();

	if ( empty( $sale_ids ) ) {
		return array();
	}

	return get_cached_product_ids(
		'sale',
		array(
			'post__in'       => array_map( 'absint', $sale_ids ),
			'posts_per_page' => absint( $limit ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
}

/**
 * Get cached product categories.
 *
 * @param int $limit Number of categories.
 * @return array
 */
function get_cached_product_categories( $limit = 8 ) {
	$key        = product_cache_key( 'categories', array( 'limit' => absint( $limit ) ) );
	$categories = get_transient( $key );

	if ( is_array( $categories ) ) {
		return $categories;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => absint( $limit ),
		)
	);

	$categories = is_wp_error( $terms ) ? array() : $terms;
	set_transient( $key, $categories, HOUR_IN_SECONDS );

	return $categories;
}

/**
 * Clear product transients on product changes.
 */
function flush_product_cache() {
	global $wpdb;

	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
			$wpdb->esc_like( '_transient_stv_' ) . '%',
			$wpdb->esc_like( '_transient_timeout_stv_' ) . '%'
		)
	);
}
add_action( 'save_post_product', __NAMESPACE__ . '\\flush_product_cache' );
add_action( 'woocommerce_product_set_stock', __NAMESPACE__ . '\\flush_product_cache' );
add_action( 'woocommerce_variation_set_stock', __NAMESPACE__ . '\\flush_product_cache' );

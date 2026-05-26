<?php
/**
 * Theme functions for Street Techspot Ventures.
 *
 * @package Street_Techspot_Ventures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stv_includes = array(
	'inc/setup.php',
	'inc/enqueue.php',
	'inc/woocommerce.php',
	'inc/performance.php',
	'inc/security.php',
	'inc/schema.php',
	'inc/product-cache.php',
	'inc/demo-data.php',
	'inc/receipts.php',
	'inc/admin.php',
	'inc/mobile-optimization.php',
	'inc/mpesa/custom-mpesa.php',
	'inc/mpesa/stk-push.php',
	'inc/mpesa/callback-handler.php',
	'inc/ajax.php',
);

foreach ( $stv_includes as $stv_include ) {
	$stv_file = get_template_directory() . '/' . $stv_include;

	if ( file_exists( $stv_file ) ) {
		require_once $stv_file;
	}
}

/**
 * Return premium starter products for the technology catalog.
 *
 * These are realistic starter products, not verified supplier inventory.
 *
 * @return array<int,array<string,mixed>>
 */
function stv_visual_seed_products() {
	$images = array(
		'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1200&h=900&fit=crop&auto=format',
		'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200&h=900&fit=crop&auto=format',
		'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=1200&h=900&fit=crop&auto=format',
		'https://images.unsplash.com/photo-1612198188060-c7c2a3b66eae?w=1200&h=900&fit=crop&auto=format',
		'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=1200&h=900&fit=crop&auto=format',
		'https://images.unsplash.com/photo-1611174797136-5e1675f37627?w=1200&h=900&fit=crop&auto=format',
		'https://images.unsplash.com/photo-1601524909162-ae8725290836?w=1200&h=900&fit=crop&auto=format',
		'https://images.unsplash.com/photo-1629429407756-8a573a7f1f6f?w=1200&h=900&fit=crop&auto=format',
	);
	$items  = array(
		array( 'M2 MacBook Pro Replacement Keyboard', 'Laptop Parts', 18500, 16400 ),
		array( 'Intel Core i9 RTX 4080 Creator Build', 'Custom PCs', 385000, 359000 ),
		array( 'OLED Samsung S23 Ultra Display Assembly', 'Phone Repair Parts', 28500, 26400 ),
		array( 'USB-C 10Gbps Multiport Docking Hub', 'Accessories', 9500, 8200 ),
		array( 'Logitech MX Mechanical Keyboard', 'Gaming', 22500, 19900 ),
		array( 'NVMe Gen4 2TB SSD', 'Storage Devices', 24500, 21900 ),
		array( 'Gaming RGB DDR5 32GB RAM Kit', 'Custom PCs', 18500, 16900 ),
		array( 'Laptop Cooling Vacuum Fan', 'Accessories', 4200, 3600 ),
		array( 'iPhone 14 Pro Charging Flex Cable', 'Phone Repair Parts', 6500, 5900 ),
		array( 'Mini Precision Screwdriver Toolkit', 'Repair Tools', 3200, 2800 ),
		array( 'WiFi 6 Mesh Router System', 'Networking', 28500, 24900 ),
		array( 'USB-C GaN Fast Charging Brick', 'Accessories', 5500, 4900 ),
		array( 'RTX 4090 Liquid-Cooled Workstation', 'Custom PCs', 685000, 649000 ),
		array( 'ThinkPad Replacement Trackpad Assembly', 'Laptop Parts', 7800, 6900 ),
		array( '4K Portable OLED Monitor', 'Accessories', 46500, 42900 ),
		array( 'MacBook Air M1 Battery Pack', 'Laptop Parts', 14500, 12900 ),
		array( 'Dell XPS 13 Palmrest Assembly', 'Laptop Parts', 16500, 14900 ),
		array( 'Samsung A54 AMOLED Screen', 'Phone Repair Parts', 13500, 11900 ),
		array( 'iPhone 13 Pro Max OLED Display', 'Phone Repair Parts', 32500, 29900 ),
		array( 'PCIe WiFi 6E Bluetooth Card', 'Networking', 6200, 5400 ),
		array( '2.5GbE USB-C Network Adapter', 'Networking', 4800, 4200 ),
		array( 'Mechanical Hot-Swap Keyboard Barebone', 'Gaming', 11500, 9900 ),
		array( 'RGB Wireless Gaming Mouse', 'Gaming', 7200, 6400 ),
		array( '1TB Rugged Portable SSD', 'Storage Devices', 15800, 14200 ),
		array( 'USB 3.2 256GB Metal Flash Drive', 'Storage Devices', 3900, 3400 ),
		array( 'Thermal Paste Pro Kit', 'Repair Tools', 1800, 1500 ),
		array( 'ESD Anti-Static Repair Mat', 'Repair Tools', 4500, 3900 ),
		array( 'Laptop Hinge Repair Set Universal', 'Laptop Parts', 3600, 3200 ),
		array( 'MacBook USB-C Port Board', 'Laptop Parts', 9800, 8800 ),
		array( 'Samsung S22 Charging Board', 'Phone Repair Parts', 5200, 4700 ),
		array( 'iPhone Back Glass Repair Kit', 'Phone Repair Parts', 6800, 5900 ),
		array( 'Creator Mini ITX Ryzen Build', 'Custom PCs', 225000, 209000 ),
		array( 'Streaming PC Ryzen 7 RTX 4070', 'Custom PCs', 285000, 269000 ),
		array( 'Triple-Fan GPU Cooling Bracket', 'Custom PCs', 7400, 6600 ),
		array( 'Thunderbolt 4 Pro Cable 2M', 'Accessories', 8900, 7800 ),
		array( 'Aluminium Laptop Stand Pro', 'Accessories', 5200, 4600 ),
		array( 'USB-C 140W Power Meter Cable', 'Repair Tools', 3400, 2900 ),
		array( 'AX3000 Gigabit Router', 'Networking', 12500, 10900 ),
		array( '2MP Smart CCTV WiFi Camera', 'Electronics', 6800, 5900 ),
		array( 'Smart UPS 1000VA Backup', 'Electronics', 18500, 16900 ),
		array( 'Portable Soldering Station Kit', 'Repair Tools', 12800, 11500 ),
		array( 'Phone Screen Separator Machine', 'Repair Tools', 25500, 22900 ),
	);

	foreach ( $items as $index => $item ) {
		$items[ $index ] = array(
			'title'      => $item[0],
			'category'   => $item[1],
			'regular'    => (string) $item[2],
			'sale'       => (string) $item[3],
			'sku'        => 'STV-PREMIUM-' . str_pad( (string) ( $index + 1 ), 3, '0', STR_PAD_LEFT ),
			'stock'      => 4 + ( $index % 18 ),
			'image'      => $images[ $index % count( $images ) ],
			'tags'       => array( 'premium tech', 'kenya ecommerce', sanitize_title( $item[1] ) ),
		);
	}

	return $items;
}

/**
 * Sideload a product image once and return the attachment ID.
 *
 * @param string $url Image URL.
 * @param int    $product_id Product ID.
 * @return int
 */
function stv_sideload_product_image( $url, $product_id ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'meta_key'       => '_stv_source_image_url',
			'meta_value'     => esc_url_raw( $url ),
			'fields'         => 'ids',
			'posts_per_page' => 1,
		)
	);

	if ( ! empty( $existing ) ) {
		return absint( $existing[0] );
	}

	if ( ! function_exists( 'media_sideload_image' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$attachment_id = media_sideload_image( esc_url_raw( $url ), $product_id, null, 'id' );

	if ( is_wp_error( $attachment_id ) ) {
		update_post_meta( $product_id, '_stv_external_image_url', esc_url_raw( $url ) );
		return 0;
	}

	update_post_meta( $attachment_id, '_stv_source_image_url', esc_url_raw( $url ) );

	return absint( $attachment_id );
}

/**
 * Generate a compact premium WooCommerce catalog.
 *
 * @param bool $force Force regeneration.
 * @return int Number of products processed.
 */
function stv_generate_premium_catalog( $force = false ) {
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
		return 0;
	}

	if ( get_option( 'stv_premium_catalog_seeded' ) && ! $force ) {
		return 0;
	}

	$processed = 0;

	foreach ( stv_visual_seed_products() as $data ) {
		$term = term_exists( $data['category'], 'product_cat' );
		if ( ! $term ) {
			$term = wp_insert_term( $data['category'], 'product_cat', array( 'slug' => sanitize_title( $data['category'] ) ) );
		}
		$term_id = is_wp_error( $term ) ? 0 : absint( is_array( $term ) ? $term['term_id'] : $term );

		$product_id = wc_get_product_id_by_sku( $data['sku'] );
		$product    = $product_id ? wc_get_product( $product_id ) : new WC_Product_Simple();

		if ( ! $product ) {
			continue;
		}

		$product->set_name( $data['title'] );
		$product->set_slug( sanitize_title( $data['title'] ) );
		$product->set_sku( $data['sku'] );
		$product->set_regular_price( $data['regular'] );
		$product->set_sale_price( $data['sale'] );
		$product->set_manage_stock( true );
		$product->set_stock_quantity( absint( $data['stock'] ) );
		$product->set_stock_status( 'instock' );
		$product->set_category_ids( $term_id ? array( $term_id ) : array() );
		$product->set_short_description( sprintf( __( '%s engineered for premium repair, performance, and creator-grade workflows.', 'street-techspot-ventures' ), $data['title'] ) );
		$product->set_description( '<p>' . esc_html__( 'Built for serious technology users who need speed, precision and reliable performance.', 'street-techspot-ventures' ) . '</p><ul><li>' . esc_html__( 'High-speed data throughput', 'street-techspot-ventures' ) . '</li><li>' . esc_html__( 'Precision-engineered components', 'street-techspot-ventures' ) . '</li><li>' . esc_html__( 'Low thermal footprint', 'street-techspot-ventures' ) . '</li><li>' . esc_html__( 'Enterprise-grade durability', 'street-techspot-ventures' ) . '</li></ul>' );

		$saved_id = $product->save();
		$image_id = stv_sideload_product_image( $data['image'], $saved_id );

		if ( $image_id ) {
			$product->set_image_id( $image_id );
			$product->set_gallery_image_ids( array( $image_id ) );
			$product->save();
		}

		wp_set_object_terms( $saved_id, $data['tags'], 'product_tag', false );

		if ( 0 === $processed % 4 ) {
			wp_set_object_terms( $saved_id, 'featured', 'product_visibility', true );
		}

		$processed++;
	}

	update_option( 'stv_premium_catalog_seeded', time(), false );

	return $processed;
}

/**
 * Seed products once when an administrator opens WordPress.
 */
function stv_maybe_generate_premium_catalog() {
	if ( is_admin() && current_user_can( 'manage_woocommerce' ) ) {
		stv_generate_premium_catalog();
	}
}
add_action( 'admin_init', 'stv_maybe_generate_premium_catalog' );

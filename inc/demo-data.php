<?php
/**
 * Store data seeding for a live starter catalog.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return starter category definitions.
 *
 * @return array<int,array<string,string>>
 */
function seed_categories() {
	$names = array(
		'Laptops', 'Gaming Laptops', 'Desktop PCs', 'Monitors', 'Keyboards', 'Mice', 'Storage Devices', 'SSDs', 'Flash Drives', 'Networking', 'Routers', 'CCTV',
		'Smart Watches', 'Earbuds', 'Power Banks', 'Chargers', 'Cables', 'Adapters', 'Office Electronics', 'Printers', 'Accessories', 'Refurbished Devices', 'Clearance Deals',
	);
	$icons = array( 'laptop', 'gamepad', 'cpu', 'monitor', 'keyboard', 'mouse', 'database', 'drive', 'usb', 'network', 'wifi', 'camera', 'watch', 'audio', 'battery', 'plug', 'cable', 'adapter', 'briefcase', 'printer', 'spark', 'refresh', 'tag' );
	$out   = array();

	foreach ( $names as $index => $name ) {
		$out[] = array(
			'name'        => $name,
			'slug'        => sanitize_title( $name ),
			'icon'        => $icons[ $index ],
			'description' => sprintf(
				/* translators: %s: Category name. */
				__( 'Shop %s selected for Kenyan tech buyers who want dependable products, fast support, warranty confidence, and M-Pesa-first checkout.', 'street-techspot-ventures' ),
				$name
			),
		);
	}

	return $out;
}

/**
 * Return product model map.
 *
 * @return array<string,array<int,string>>
 */
function seed_product_models() {
	return array(
		'Laptops'             => array( 'EliteBook 840 G8', 'ThinkPad T14', 'Latitude 5420', 'ZenBook 14', 'Aspire 5', 'ProBook 450 G9' ),
		'Gaming Laptops'      => array( 'TUF F15 RTX', 'Legion 5 Pro', 'Victus 16', 'Nitro 5', 'ROG Strix G16', 'Katana 15' ),
		'Desktop PCs'         => array( 'ProDesk 600', 'OptiPlex 7080', 'ThinkCentre M70q', 'Core i5 Tower', 'Mini PC Pro', 'Workstation SFF' ),
		'Monitors'            => array( '24-inch IPS', '27-inch FHD', 'Curved 75Hz', 'Gaming 144Hz', 'USB-C Office', 'Borderless Display' ),
		'Keyboards'           => array( 'Wireless Keyboard', 'Mechanical RGB', 'Slim Office', 'Bluetooth Compact', 'Combo Keyboard', 'Creator Keys' ),
		'Mice'                => array( 'Wireless Mouse', 'Gaming Mouse', 'Silent Mouse', 'Bluetooth Mouse', 'Ergonomic Mouse', 'Travel Mouse' ),
		'Storage Devices'     => array( '1TB External HDD', '2TB Backup Drive', 'Type-C Drive', 'Rugged Drive', 'Memory Reader', 'Portable Backup' ),
		'SSDs'                => array( '256GB SATA SSD', '512GB NVMe SSD', '1TB NVMe SSD', '500GB SATA SSD', '2TB Gen4 SSD', 'Portable SSD' ),
		'Flash Drives'        => array( '32GB USB 3.0', '64GB Type-C', '128GB Dual Drive', '256GB Ultra', 'Metal USB', 'OTG Flash' ),
		'Networking'          => array( '8-Port Switch', 'Outdoor Access Point', 'CAT6 Kit', 'Wi-Fi Extender', 'PoE Injector', 'Patch Panel' ),
		'Routers'             => array( 'AC1200 Router', '4G LTE Router', 'Mesh Wi-Fi Kit', 'Wi-Fi 6 Router', 'Portable MiFi', 'Dual Band Router' ),
		'CCTV'                => array( '2MP Dome Camera', '4 Channel DVR Kit', 'Outdoor Bullet Camera', 'Smart Wi-Fi Camera', 'CCTV PSU', 'NVR Kit' ),
		'Smart Watches'       => array( 'Fitness Watch', 'AMOLED Watch', 'Calling Watch', 'Kids GPS Watch', 'Sport Watch', 'Health Watch' ),
		'Earbuds'             => array( 'True Wireless', 'Noise Cancelling', 'Gaming Earbuds', 'Bass Earbuds', 'Business Calls', 'Pocket Buds' ),
		'Power Banks'         => array( '10000mAh Bank', '20000mAh Fast', 'Mini Magnetic', 'Laptop Power Bank', 'Solar Bank', 'Slim Bank' ),
		'Chargers'            => array( '25W USB-C', '65W GaN', 'Laptop Charger', 'Dual USB', 'Fast Car Charger', 'Travel Charger' ),
		'Cables'              => array( 'USB-C Cable', 'Lightning Cable', 'HDMI Cable', 'CAT6 Cable', 'USB-C to C', 'Display Cable' ),
		'Adapters'            => array( 'USB-C Hub', 'HDMI to VGA', 'Type-C Ethernet', 'Laptop Dock', 'Card Reader', 'Multiport Adapter' ),
		'Office Electronics'  => array( 'Document Scanner', 'Barcode Scanner', 'Laminator', 'UPS Backup', 'Paper Shredder', 'POS Drawer' ),
		'Printers'            => array( 'EcoTank Printer', 'LaserJet Printer', 'All-in-One', 'Photo Printer', 'Thermal Printer', 'Office Printer' ),
		'Accessories'         => array( 'Laptop Stand', 'Full HD Webcam', 'Cooling Pad', 'Sleeve Bag', 'Cleaner Kit', 'Desk Mat' ),
		'Refurbished Devices' => array( 'Refurb Core i5', 'Refurb Core i7', 'Refurb Mini PC', 'Refurb Monitor', 'Refurb Workstation', 'Refurb Chromebook' ),
		'Clearance Deals'     => array( 'Open Box Keyboard', 'Clearance Earbuds', 'Display Monitor', 'Last Stock Charger', 'Markdown Bag', 'Flash Bundle' ),
	);
}

/**
 * Create generated SVG attachment.
 *
 * @param string $title Title.
 * @param string $accent Accent color.
 * @return int
 */
function seed_svg_attachment( $title, $accent = '#00FFD1' ) {
	$upload_dir = wp_upload_dir();
	$dir        = trailingslashit( $upload_dir['basedir'] ) . 'street-techspot-generated';

	if ( ! wp_mkdir_p( $dir ) ) {
		return 0;
	}

	$slug          = sanitize_title( $title );
	$file          = trailingslashit( $dir ) . $slug . '.svg';
	$relative_url  = trailingslashit( $upload_dir['baseurl'] ) . 'street-techspot-generated/' . $slug . '.svg';
	$attachment_id = attachment_url_to_postid( $relative_url );

	if ( $attachment_id ) {
		return absint( $attachment_id );
	}

	$initials = strtoupper( substr( preg_replace( '/[^A-Za-z0-9]/', '', $title ), 0, 3 ) );
	$svg      = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="900" viewBox="0 0 1200 900"><rect width="1200" height="900" fill="#050505"/><circle cx="960" cy="140" r="280" fill="' . esc_attr( $accent ) . '" opacity=".18"/><circle cx="180" cy="760" r="260" fill="#4DA3FF" opacity=".14"/><path d="M120 220h960M120 450h960M120 680h960M260 100v700M600 100v700M940 100v700" stroke="#fff" stroke-opacity=".07" stroke-width="2"/><text x="600" y="430" fill="#F9FAFB" font-family="Inter,Arial,sans-serif" font-size="116" font-weight="800" text-anchor="middle">' . esc_html( $initials ) . '</text><text x="600" y="520" fill="#D1D5DB" font-family="Inter,Arial,sans-serif" font-size="32" font-weight="700" text-anchor="middle">' . esc_html( $title ) . '</text><text x="600" y="575" fill="#9CA3AF" font-family="Inter,Arial,sans-serif" font-size="22" text-anchor="middle">Street Techspot Ventures</text></svg>';

	if ( false === file_put_contents( $file, $svg ) ) {
		return 0;
	}

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/svg+xml',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$file
	);

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );

	return absint( $attachment_id );
}

/**
 * Generate product blueprints.
 *
 * @return array<int,array<string,mixed>>
 */
function seed_product_blueprints() {
	$models = seed_product_models();
	$brands = array( 'HP', 'Lenovo', 'Dell', 'Logitech', 'Samsung', 'Asus', 'JBL', 'Sony', 'Epson', 'Anker', 'TP-Link', 'Kingston', 'Oraimo' );
	$bases  = array( 'Laptops' => 68500, 'Gaming Laptops' => 138000, 'Desktop PCs' => 42000, 'Monitors' => 18500, 'Keyboards' => 2500, 'Mice' => 1500, 'Storage Devices' => 8500, 'SSDs' => 4200, 'Flash Drives' => 1200, 'Networking' => 5800, 'Routers' => 4500, 'CCTV' => 7500, 'Smart Watches' => 4200, 'Earbuds' => 2200, 'Power Banks' => 2600, 'Chargers' => 1800, 'Cables' => 650, 'Adapters' => 1200, 'Office Electronics' => 10500, 'Printers' => 18500, 'Accessories' => 1200, 'Refurbished Devices' => 28500, 'Clearance Deals' => 950 );
	$out    = array();

	foreach ( $models as $category => $items ) {
		foreach ( $items as $index => $model ) {
			$brand         = $brands[ ( $index + strlen( $category ) ) % count( $brands ) ];
			$regular_price = ( $bases[ $category ] ?? 2500 ) + ( $index * 1450 );
			$sale_price    = 0 === $index % 2 ? $regular_price - max( 250, (int) round( $regular_price * 0.08 ) ) : '';
			$out[]         = array(
				'title'      => $brand . ' ' . $model,
				'category'   => $category,
				'brand'      => $brand,
				'sku'        => 'STV-' . strtoupper( sanitize_title( $category . '-' . $brand . '-' . $index ) ),
				'regular'    => (string) $regular_price,
				'sale'       => (string) $sale_price,
				'stock'      => 3 + ( ( $index + strlen( $brand ) ) % 25 ),
				'warranty'   => in_array( $category, array( 'Laptops', 'Gaming Laptops', 'Desktop PCs', 'Printers', 'Monitors' ), true ) ? '12 months service warranty' : '6 months limited warranty',
				'tags'       => array( strtolower( $brand ), 'kenya tech', 'm-pesa checkout', sanitize_title( $category ) ),
			);
		}
	}

	return $out;
}

/**
 * Seed categories, products, pages, and navigation.
 *
 * @param bool $force Force reseed.
 * @return array<string,int|string>
 */
function seed_store_data( $force = false ) {
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
		return array( 'status' => 'woocommerce_missing' );
	}

	if ( get_option( 'stv_seed_complete' ) && ! $force ) {
		return array( 'status' => 'already_seeded' );
	}

	$accents      = array( '#00FFD1', '#FF6B00', '#4DA3FF', '#00FF99' );
	$category_ids = array();

	foreach ( seed_categories() as $index => $category ) {
		$term = term_exists( $category['slug'], 'product_cat' );

		if ( ! $term ) {
			$term = wp_insert_term( $category['name'], 'product_cat', array( 'slug' => $category['slug'], 'description' => $category['description'] ) );
		}

		if ( is_wp_error( $term ) ) {
			continue;
		}

		$term_id = absint( is_array( $term ) ? $term['term_id'] : $term );
		update_term_meta( $term_id, 'stv_icon', sanitize_key( $category['icon'] ) );
		update_term_meta( $term_id, 'stv_banner_color', $accents[ $index % count( $accents ) ] );
		update_term_meta( $term_id, 'thumbnail_id', seed_svg_attachment( $category['name'], $accents[ $index % count( $accents ) ] ) );
		$category_ids[ $category['name'] ] = $term_id;
	}

	$shipping = term_exists( 'standard-delivery', 'product_shipping_class' );
	if ( ! $shipping ) {
		$shipping = wp_insert_term( 'Standard Delivery', 'product_shipping_class', array( 'slug' => 'standard-delivery' ) );
	}
	$shipping_id = is_wp_error( $shipping ) ? 0 : absint( is_array( $shipping ) ? $shipping['term_id'] : $shipping );
	$count       = 0;

	foreach ( seed_product_blueprints() as $product_data ) {
		$product_id = wc_get_product_id_by_sku( $product_data['sku'] );
		$product    = $product_id ? wc_get_product( $product_id ) : new \WC_Product_Simple();

		if ( ! $product ) {
			continue;
		}

		$title = $product_data['title'];
		$product->set_name( $title );
		$product->set_sku( $product_data['sku'] );
		$product->set_regular_price( $product_data['regular'] );
		$product->set_sale_price( $product_data['sale'] );
		$product->set_stock_quantity( absint( $product_data['stock'] ) );
		$product->set_manage_stock( true );
		$product->set_stock_status( 'instock' );
		$product->set_short_description( sprintf( __( '%1$s with dependable support, fast local checkout, and practical warranty coverage.', 'street-techspot-ventures' ), $title ) );
		$product->set_description( sprintf( __( '%1$s is prepared for Kenyan buyers who need dependable technology, fast fulfillment, clear warranty support, and mobile-first M-Pesa checkout. Specifications are organized for quick comparison and future ERP/POS sync.', 'street-techspot-ventures' ), $title ) );
		$product->set_category_ids( isset( $category_ids[ $product_data['category'] ] ) ? array( $category_ids[ $product_data['category'] ] ) : array() );
		$product->set_shipping_class_id( $shipping_id );
		$product->set_image_id( seed_svg_attachment( $title, $accents[ $count % count( $accents ) ] ) );
		$product->set_gallery_image_ids( array_filter( array( seed_svg_attachment( $title . ' detail', $accents[ ( $count + 1 ) % count( $accents ) ] ) ) ) );
		$saved_id = $product->save();

		wp_set_object_terms( $saved_id, $product_data['tags'], 'product_tag', false );
		update_post_meta( $saved_id, '_stv_brand', sanitize_text_field( $product_data['brand'] ) );
		update_post_meta( $saved_id, '_stv_warranty', sanitize_text_field( $product_data['warranty'] ) );
		update_post_meta( $saved_id, '_stv_delivery_eta', 'Nairobi same-day eligible; upcountry dispatch by courier.' );
		update_post_meta( $saved_id, '_stv_specs', wp_json_encode( array( 'Brand' => $product_data['brand'], 'Category' => $product_data['category'], 'Warranty' => $product_data['warranty'], 'Checkout' => 'M-Pesa STK ready' ) ) );

		if ( 0 === $count % 5 ) {
			wp_set_object_terms( $saved_id, 'featured', 'product_visibility', true );
		}

		$count++;
	}

	$pages = array(
		'about'    => 'Street Techspot Ventures is a modern Kenyan technology commerce brand built for buyers who want premium devices, dependable support, transparent pricing, and fast M-Pesa-first checkout.',
		'contact'  => 'Reach Street Techspot Ventures for product inquiries, WhatsApp support, quotations, delivery questions, and warranty guidance.',
		'delivery' => 'Delivery is optimized for Nairobi coverage, courier dispatch, clear order communication, and practical timelines for tech buyers across Kenya.',
		'warranty' => 'Warranty coverage depends on product category and condition. Each product includes warranty metadata, service guidance, and support expectations.',
		'privacy-policy' => 'Street Techspot Ventures protects customer information used for orders, M-Pesa checkout, delivery coordination, warranty support, and account service.',
		'faq'      => 'Common questions about M-Pesa checkout, same-day delivery, refurbished devices, warranty support, order tracking, and stock availability.',
	);

	foreach ( $pages as $slug => $content ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post( array( 'post_title' => ucwords( str_replace( '-', ' ', $slug ) ), 'post_name' => $slug, 'post_content' => $content, 'post_status' => 'publish', 'post_type' => 'page' ) );
		}
	}

	$menu_id = wp_get_nav_menu_object( 'Street Techspot Primary' );
	$menu_id = $menu_id ? $menu_id->term_id : wp_create_nav_menu( 'Street Techspot Primary' );

	if ( ! is_wp_error( $menu_id ) && ! wp_get_nav_menu_items( $menu_id ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Home', 'menu-item-url' => home_url( '/' ), 'menu-item-status' => 'publish' ) );
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Shop', 'menu-item-url' => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ), 'menu-item-status' => 'publish' ) );
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Laptops', 'menu-item-url' => get_term_link( $category_ids['Laptops'], 'product_cat' ), 'menu-item-status' => 'publish' ) );
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Clearance', 'menu-item-url' => get_term_link( $category_ids['Clearance Deals'], 'product_cat' ), 'menu-item-status' => 'publish' ) );
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Contact', 'menu-item-url' => home_url( '/contact/' ), 'menu-item-status' => 'publish' ) );
		set_theme_mod( 'nav_menu_locations', array_merge( (array) get_theme_mod( 'nav_menu_locations', array() ), array( 'primary' => $menu_id ) ) );
	}

	update_option( 'stv_seed_complete', time(), false );
	flush_product_cache();

	return array( 'status' => 'seeded', 'categories' => count( $category_ids ), 'products' => $count );
}

/**
 * Seed once when an administrator opens the dashboard.
 */
function maybe_seed_store_data() {
	if ( is_admin() && current_user_can( 'manage_options' ) && ! get_option( 'stv_seed_complete' ) ) {
		seed_store_data();
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\\maybe_seed_store_data' );

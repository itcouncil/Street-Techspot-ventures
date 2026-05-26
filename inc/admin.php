<?php
/**
 * Lightweight admin utilities.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add a stock signal column.
 *
 * @param array<string,string> $columns Columns.
 * @return array<string,string>
 */
function add_product_stock_column( $columns ) {
	$columns['stv_stock_signal'] = __( 'Stock Signal', 'street-techspot-ventures' );

	return $columns;
}
add_filter( 'manage_edit-product_columns', __NAMESPACE__ . '\\add_product_stock_column' );

/**
 * Render stock signal column.
 *
 * @param string $column Column key.
 * @param int    $post_id Product ID.
 */
function render_product_stock_column( $column, $post_id ) {
	if ( 'stv_stock_signal' !== $column || ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	$product = wc_get_product( $post_id );

	if ( $product ) {
		echo esc_html( $product->get_stock_status() );
	}
}
add_action( 'manage_product_posts_custom_column', __NAMESPACE__ . '\\render_product_stock_column', 10, 2 );

/**
 * Register commerce operations screen.
 */
function register_commerce_ops_page() {
	add_submenu_page(
		'woocommerce',
		__( 'Street Techspot Ops', 'street-techspot-ventures' ),
		__( 'Street Techspot Ops', 'street-techspot-ventures' ),
		'manage_woocommerce',
		'stv-commerce-ops',
		__NAMESPACE__ . '\\render_commerce_ops_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\\register_commerce_ops_page' );

/**
 * Render commerce operations page.
 */
function render_commerce_ops_page() {
	$seed_status = get_option( 'stv_seed_complete' ) ? __( 'Catalog seed has run.', 'street-techspot-ventures' ) : __( 'Catalog seed has not run yet.', 'street-techspot-ventures' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Street Techspot Commerce Ops', 'street-techspot-ventures' ); ?></h1>
		<p><strong><?php echo esc_html( $seed_status ); ?></strong></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'stv_seed_catalog' ); ?>
			<input type="hidden" name="action" value="stv_seed_catalog">
			<p><?php echo esc_html__( 'Create or refresh starter categories, products, pages, generated images, and navigation.', 'street-techspot-ventures' ); ?></p>
			<?php submit_button( __( 'Seed Live Catalog', 'street-techspot-ventures' ) ); ?>
		</form>

		<hr>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
			<?php wp_nonce_field( 'stv_import_products' ); ?>
			<input type="hidden" name="action" value="stv_import_products">
			<p><?php echo esc_html__( 'Import CSV columns: title, sku, price, stock, category, brand.', 'street-techspot-ventures' ); ?></p>
			<input type="file" name="stv_csv" accept=".csv">
			<?php submit_button( __( 'Import Products', 'street-techspot-ventures' ) ); ?>
		</form>

		<hr>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'stv_bulk_price' ); ?>
			<input type="hidden" name="action" value="stv_bulk_price">
			<label for="stv_price_percent"><?php echo esc_html__( 'Bulk sale discount percentage', 'street-techspot-ventures' ); ?></label>
			<input id="stv_price_percent" type="number" name="percent" min="1" max="70" value="10">
			<?php submit_button( __( 'Apply Sale Pricing', 'street-techspot-ventures' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Handle catalog seeding request.
 */
function handle_seed_catalog() {
	check_admin_referer( 'stv_seed_catalog' );

	if ( current_user_can( 'manage_woocommerce' ) ) {
		seed_store_data( true );
	}

	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=stv-commerce-ops' ) );
	exit;
}
add_action( 'admin_post_stv_seed_catalog', __NAMESPACE__ . '\\handle_seed_catalog' );

/**
 * Handle bulk price update.
 */
function handle_bulk_price() {
	check_admin_referer( 'stv_bulk_price' );

	if ( ! current_user_can( 'manage_woocommerce' ) || ! function_exists( 'wc_get_product' ) ) {
		wp_die( esc_html__( 'Permission denied.', 'street-techspot-ventures' ) );
	}

	$percent = isset( $_POST['percent'] ) ? min( 70, max( 1, absint( $_POST['percent'] ) ) ) : 10;
	$ids     = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => 300,
			'no_found_rows'  => true,
		)
	);

	foreach ( $ids as $id ) {
		$product = wc_get_product( $id );
		if ( ! $product ) {
			continue;
		}

		$regular = (float) $product->get_regular_price();
		if ( $regular > 0 ) {
			$product->set_sale_price( (string) round( $regular * ( ( 100 - $percent ) / 100 ) ) );
			$product->save();
		}
	}

	flush_product_cache();
	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=stv-commerce-ops' ) );
	exit;
}
add_action( 'admin_post_stv_bulk_price', __NAMESPACE__ . '\\handle_bulk_price' );

/**
 * Handle CSV import.
 */
function handle_import_products() {
	check_admin_referer( 'stv_import_products' );

	if ( ! current_user_can( 'manage_woocommerce' ) || empty( $_FILES['stv_csv']['tmp_name'] ) || ! function_exists( 'wc_get_product' ) ) {
		wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=stv-commerce-ops' ) );
		exit;
	}

	$handle = fopen( sanitize_text_field( wp_unslash( $_FILES['stv_csv']['tmp_name'] ) ), 'r' );
	if ( ! $handle ) {
		wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=stv-commerce-ops' ) );
		exit;
	}

	$headers = fgetcsv( $handle );
	while ( ( $row = fgetcsv( $handle ) ) !== false ) {
		$data = array_combine( $headers, $row );
		if ( empty( $data['title'] ) || empty( $data['sku'] ) ) {
			continue;
		}

		$product = wc_get_product_id_by_sku( $data['sku'] ) ? wc_get_product( wc_get_product_id_by_sku( $data['sku'] ) ) : new \WC_Product_Simple();
		$product->set_name( sanitize_text_field( $data['title'] ) );
		$product->set_sku( sanitize_text_field( $data['sku'] ) );
		$product->set_regular_price( isset( $data['price'] ) ? (string) absint( $data['price'] ) : '0' );
		$product->set_stock_quantity( isset( $data['stock'] ) ? absint( $data['stock'] ) : 1 );
		$product->set_manage_stock( true );
		$product_id = $product->save();

		if ( ! empty( $data['category'] ) ) {
			wp_set_object_terms( $product_id, sanitize_text_field( $data['category'] ), 'product_cat', false );
		}

		if ( ! empty( $data['brand'] ) ) {
			update_post_meta( $product_id, '_stv_brand', sanitize_text_field( $data['brand'] ) );
		}
	}
	fclose( $handle );
	flush_product_cache();
	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=stv-commerce-ops' ) );
	exit;
}
add_action( 'admin_post_stv_import_products', __NAMESPACE__ . '\\handle_import_products' );

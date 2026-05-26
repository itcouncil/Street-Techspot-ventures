<?php
/**
 * Dynamic product grid partial.
 *
 * @package Street_Techspot_Ventures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'title'       => __( 'Featured Products', 'street-techspot-ventures' ),
		'product_ids' => array(),
		'limit'       => 8,
		'view_all'    => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
	)
);

$product_ids = array_map( 'absint', (array) $args['product_ids'] );

if ( empty( $product_ids ) && function_exists( 'wc_get_products' ) ) {
	$product_ids = wc_get_products(
		array(
			'status' => 'publish',
			'limit'  => absint( $args['limit'] ),
			'return' => 'ids',
		)
	);
}
?>

<section class="stv-section" aria-labelledby="<?php echo esc_attr( sanitize_title( $args['title'] ) ); ?>">
	<div class="stv-shell">
		<div class="stv-section-head">
			<div>
				<p class="stv-kicker"><?php echo esc_html__( 'Live WooCommerce Feed', 'street-techspot-ventures' ); ?></p>
				<h2 id="<?php echo esc_attr( sanitize_title( $args['title'] ) ); ?>"><?php echo esc_html( $args['title'] ); ?></h2>
			</div>
			<a class="stv-btn" href="<?php echo esc_url( $args['view_all'] ); ?>"><?php echo esc_html__( 'View all', 'street-techspot-ventures' ); ?></a>
		</div>

		<?php if ( $product_ids ) : ?>
			<div class="stv-product-grid">
				<?php foreach ( $product_ids as $product_id ) : ?>
					<?php
					$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;

					if ( ! $product ) {
						continue;
					}

					$image_id         = $product->get_image_id();
					$image            = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : get_post_meta( $product_id, '_stv_external_image_url', true );
					$category_names   = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'names' ) );
					$category_name    = ! empty( $category_names ) && ! is_wp_error( $category_names ) ? $category_names[0] : __( 'Tech', 'street-techspot-ventures' );
					$product_initials = preg_replace( '/[^A-Z0-9]/', '', strtoupper( substr( $product->get_name(), 0, 2 ) ) );
					$product_initials = $product_initials ? $product_initials : 'ST';
					?>
					<article class="stv-product-card" data-product-id="<?php echo esc_attr( $product_id ); ?>">
						<a class="stv-product-image<?php echo $image ? '' : ' stv-product-image--fallback'; ?>" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
							<?php if ( $image ) : ?>
								<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" width="480" height="360" loading="lazy" decoding="async">
							<?php else : ?>
								<span class="stv-product-fallback-mark" aria-hidden="true"><?php echo esc_html( $product_initials ); ?></span>
								<span class="stv-product-fallback-category"><?php echo esc_html( $category_name ); ?></span>
							<?php endif; ?>
						</a>
						<div class="stv-product-body">
							<p class="stv-product-category"><?php echo esc_html( $category_name ); ?></p>
							<h3 class="stv-product-title">
								<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
							</h3>
							<p class="stv-product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
							<div class="stv-product-actions">
								<button class="stv-add-to-cart" type="button" data-product-id="<?php echo esc_attr( $product_id ); ?>">
									<?php echo esc_html( $product->add_to_cart_text() ); ?>
								</button>
								<button class="stv-save-product" type="button" data-stv-wishlist="<?php echo esc_attr( $product_id ); ?>" data-product-name="<?php echo esc_attr( $product->get_name() ); ?>">
									<?php echo esc_html__( 'Save', 'street-techspot-ventures' ); ?>
								</button>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="stv-card stv-trust">
				<p><?php echo esc_html__( 'Products will appear here as soon as WooCommerce catalog data is available.', 'street-techspot-ventures' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>

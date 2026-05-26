<?php
/**
 * WooCommerce integration.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add WooCommerce theme support.
 */
function woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 480,
			'single_image_width'    => 720,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 2,
				'max_rows'        => 6,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 4,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\woocommerce_setup' );

/**
 * Render a minimal product card.
 *
 * @param int $product_id Product ID.
 */
function render_product_card( $product_id ) {
	$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;

	if ( ! $product ) {
		return;
	}

	$image_id  = $product->get_image_id();
	$image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : $product->get_name();
	?>
	<article class="stv-product-card group" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
		<a class="block" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
			<?php
			if ( $image_id ) {
				echo wp_get_attachment_image(
					$image_id,
					'woocommerce_thumbnail',
					false,
					array(
						'alt'      => esc_attr( $image_alt ),
						'class'    => 'aspect-square w-full object-cover',
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
			} else {
				echo wp_kses_post( wc_placeholder_img( 'woocommerce_thumbnail', array( 'class' => 'aspect-square w-full object-cover' ) ) );
			}
			?>
		</a>
		<div class="p-4">
			<h3 class="line-clamp-2 text-sm font-semibold leading-snug text-white">
				<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
					<?php echo esc_html( $product->get_name() ); ?>
				</a>
			</h3>
			<div class="mt-3 flex items-center justify-between gap-3">
				<p class="text-sm font-bold text-stv-teal">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
				</p>
				<button class="stv-add-to-cart rounded-full bg-white px-3 py-2 text-xs font-bold text-black" type="button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
					<?php echo esc_html( $product->add_to_cart_text() ); ?>
				</button>
			</div>
			<button class="stv-spec-trigger mt-3 text-xs font-semibold text-slate-300 hover:text-stv-teal" type="button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-expanded="false">
				<?php echo esc_html__( 'Specs', 'street-techspot-ventures' ); ?>
			</button>
			<div class="stv-spec-drawer mt-3 hidden text-xs text-slate-300" data-specs-for="<?php echo esc_attr( $product->get_id() ); ?>"></div>
		</div>
	</article>
	<?php
}

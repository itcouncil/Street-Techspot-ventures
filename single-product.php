<?php
/**
 * Single product template.
 *
 * @package Street_Techspot_Ventures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="min-h-screen bg-[#050505] py-10 text-[#F9FAFB]">
	<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
		<?php woocommerce_content(); ?>

		<?php if ( function_exists( 'wc_get_product' ) ) : ?>
			<?php $product = wc_get_product( get_the_ID() ); ?>
			<?php if ( $product ) : ?>
				<section class="stv-floating-buy stv-glass-card mt-8 p-4" aria-label="<?php echo esc_attr__( 'Quick buy', 'street-techspot-ventures' ); ?>">
					<div class="grid gap-3 sm:grid-cols-[1fr_auto_auto] sm:items-center">
						<div>
							<p class="text-sm font-bold text-white"><?php echo esc_html( $product->get_name() ); ?></p>
							<p class="text-sm font-bold text-[#00FFD1]"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
						</div>
						<label class="sr-only" for="stv-mpesa-phone"><?php echo esc_html__( 'M-Pesa phone', 'street-techspot-ventures' ); ?></label>
						<input id="stv-mpesa-phone" class="rounded-full border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none placeholder:text-[#9CA3AF]" type="tel" inputmode="numeric" autocomplete="tel" data-stv-mpesa-status="#stv-mpesa-status" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" placeholder="<?php echo esc_attr__( '07XXXXXXXX', 'street-techspot-ventures' ); ?>">
						<p id="stv-mpesa-status" class="text-xs font-bold text-[#00FFD1]"><?php echo esc_html__( 'Type phone for STK push', 'street-techspot-ventures' ); ?></p>
					</div>
				</section>

				<section class="mt-8 grid gap-4 lg:grid-cols-3" aria-label="<?php echo esc_attr__( 'Product confidence details', 'street-techspot-ventures' ); ?>">
					<div class="stv-glass-card p-5">
						<h2 class="text-sm font-black text-white"><?php echo esc_html__( 'Specifications', 'street-techspot-ventures' ); ?></h2>
						<?php $specs = json_decode( (string) get_post_meta( $product->get_id(), '_stv_specs', true ), true ); ?>
						<?php if ( is_array( $specs ) ) : ?>
							<ul class="mt-3 space-y-2 text-sm text-[#D1D5DB]">
								<?php foreach ( $specs as $label => $value ) : ?>
									<li><?php echo esc_html( $label . ': ' . $value ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php else : ?>
							<p class="mt-3 text-sm text-[#D1D5DB]"><?php echo esc_html__( 'Core specifications load from product attributes and admin metadata.', 'street-techspot-ventures' ); ?></p>
						<?php endif; ?>
					</div>
					<div class="stv-glass-card p-5">
						<h2 class="text-sm font-black text-white"><?php echo esc_html__( 'Warranty', 'street-techspot-ventures' ); ?></h2>
						<p class="mt-3 text-sm text-[#D1D5DB]"><?php echo esc_html( get_post_meta( $product->get_id(), '_stv_warranty', true ) ?: __( 'Warranty support available based on product category.', 'street-techspot-ventures' ) ); ?></p>
					</div>
					<div class="stv-glass-card p-5">
						<h2 class="text-sm font-black text-white"><?php echo esc_html__( 'Delivery ETA', 'street-techspot-ventures' ); ?></h2>
						<p class="mt-3 text-sm text-[#D1D5DB]"><?php echo esc_html( get_post_meta( $product->get_id(), '_stv_delivery_eta', true ) ?: __( 'Nairobi same-day eligibility with courier options for upcountry orders.', 'street-techspot-ventures' ) ); ?></p>
					</div>
				</section>

				<?php $related = function_exists( 'wc_get_related_products' ) ? wc_get_related_products( $product->get_id(), 4 ) : array(); ?>
				<?php if ( $related ) : ?>
					<section class="mt-10" aria-labelledby="stv-related-title">
						<h2 id="stv-related-title" class="text-xl font-extrabold leading-tight"><?php echo esc_html__( 'Instant related picks', 'street-techspot-ventures' ); ?></h2>
						<div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
							<?php foreach ( $related as $related_id ) : ?>
								<?php \STV\Theme\render_product_card( $related_id ); ?>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();

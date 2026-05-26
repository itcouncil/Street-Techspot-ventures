<?php
/**
 * Premium dynamic homepage and fallback template.
 *
 * @package Street_Techspot_Ventures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$has_woocommerce = class_exists( 'WooCommerce' );
$shop_url        = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$categories      = $has_woocommerce ? get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'number'     => 8,
	)
) : array();
$featured_ids    = $has_woocommerce && function_exists( 'wc_get_featured_product_ids' ) ? wc_get_featured_product_ids() : array();
$featured_ids    = array_slice( array_map( 'absint', $featured_ids ), 0, 8 );
$latest_ids      = $has_woocommerce && function_exists( 'wc_get_products' ) ? wc_get_products(
	array(
		'status'  => 'publish',
		'limit'   => 8,
		'orderby' => 'date',
		'order'   => 'DESC',
		'return'  => 'ids',
	)
) : array();
?>

<main id="primary">
	<section class="stv-hero">
		<div class="stv-shell stv-hero-grid">
			<div>
				<p class="stv-kicker"><?php echo esc_html__( 'Premium Kenyan technology commerce', 'street-techspot-ventures' ); ?></p>
				<h1><?php echo esc_html__( 'Next-Level Tech Infrastructure. Built For Performance.', 'street-techspot-ventures' ); ?></h1>
				<p class="stv-lead">
					<?php echo esc_html__( 'Premium electronics, laptop parts, custom PCs and accessories engineered for creators, gamers, repair professionals and performance-focused businesses.', 'street-techspot-ventures' ); ?>
				</p>
				<div class="stv-actions">
					<a class="stv-btn stv-btn-primary" href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html__( 'Shop Live Catalog', 'street-techspot-ventures' ); ?></a>
					<a class="stv-btn" href="#stv-featured"><?php echo esc_html__( 'Explore Builds', 'street-techspot-ventures' ); ?></a>
				</div>
			</div>

			<div class="stv-device-stack" aria-label="<?php echo esc_attr__( 'Technology showcase', 'street-techspot-ventures' ); ?>">
				<div class="stv-hero-orbit" aria-hidden="true">
					<span></span>
					<span></span>
					<span></span>
				</div>
				<div class="stv-device-card">
					<p class="stv-kicker"><?php echo esc_html__( 'Repair Grade', 'street-techspot-ventures' ); ?></p>
					<strong><?php echo esc_html__( 'OLED assemblies, flex cables, keyboards and trackpads.', 'street-techspot-ventures' ); ?></strong>
				</div>
				<div class="stv-device-card">
					<p class="stv-kicker"><?php echo esc_html__( 'Performance Builds', 'street-techspot-ventures' ); ?></p>
					<strong><?php echo esc_html__( 'RTX workstations, creator PCs and gaming rigs.', 'street-techspot-ventures' ); ?></strong>
				</div>
				<div class="stv-device-card">
					<p class="stv-kicker"><?php echo esc_html__( 'Fast Checkout', 'street-techspot-ventures' ); ?></p>
					<strong><?php echo esc_html__( 'WooCommerce cart, M-Pesa flow and mobile-first buying.', 'street-techspot-ventures' ); ?></strong>
				</div>
			</div>
		</div>
	</section>

	<section class="stv-section" aria-labelledby="stv-categories">
		<div class="stv-shell">
			<div class="stv-section-head">
				<div>
					<p class="stv-kicker"><?php echo esc_html__( 'Engineered Navigation', 'street-techspot-ventures' ); ?></p>
					<h2 id="stv-categories"><?php echo esc_html__( 'Featured categories', 'street-techspot-ventures' ); ?></h2>
				</div>
				<a class="stv-btn" href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html__( 'View Store', 'street-techspot-ventures' ); ?></a>
			</div>

			<div class="stv-category-grid">
				<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
					<?php foreach ( $categories as $category ) : ?>
						<a class="stv-card stv-category-card" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
							<span class="stv-category-icon"><?php echo esc_html( strtoupper( substr( $category->name, 0, 1 ) ) ); ?></span>
							<strong><?php echo esc_html( $category->name ); ?></strong>
							<p><?php echo esc_html( wp_trim_words( $category->description, 12 ) ); ?></p>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<?php foreach ( array( 'Laptop Parts', 'Custom PCs', 'Phone Repair Parts', 'Accessories' ) as $fallback_category ) : ?>
						<div class="stv-card stv-category-card">
							<span class="stv-category-icon"><?php echo esc_html( strtoupper( substr( $fallback_category, 0, 1 ) ) ); ?></span>
							<strong><?php echo esc_html( $fallback_category ); ?></strong>
							<p><?php echo esc_html__( 'This category appears once WooCommerce data is seeded.', 'street-techspot-ventures' ); ?></p>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<div id="stv-featured">
		<?php
		get_template_part(
			'template-parts/product-grid',
			null,
			array(
				'title'       => __( 'Featured performance products', 'street-techspot-ventures' ),
				'product_ids' => $featured_ids,
				'limit'       => 8,
				'view_all'    => $shop_url,
			)
		);
		?>
	</div>

	<?php
	get_template_part(
		'template-parts/product-grid',
		null,
		array(
			'title'       => __( 'Impulse-ready essentials', 'street-techspot-ventures' ),
			'product_ids' => $latest_ids,
			'limit'       => 8,
			'view_all'    => $shop_url,
		)
	);
	?>

	<section class="stv-section" aria-labelledby="stv-trust">
		<div class="stv-shell">
			<h2 id="stv-trust"><?php echo esc_html__( 'Built for serious buyers', 'street-techspot-ventures' ); ?></h2>
			<div class="stv-trust-grid">
				<?php foreach ( array( 'M-Pesa checkout', 'Warranty support', 'Repair-ready parts', 'Custom PC guidance', 'Fast mobile ordering' ) as $trust_item ) : ?>
					<div class="stv-card stv-trust">
						<strong><?php echo esc_html( $trust_item ); ?></strong>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();

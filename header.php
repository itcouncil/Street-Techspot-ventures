<?php
/**
 * Theme header.
 *
 * @package Street_Techspot_Ventures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stv_whatsapp_number = preg_replace( '/\D+/', '', get_option( 'stv_whatsapp_number', '254700000000' ) );
$stv_whatsapp_url    = 'https://wa.me/' . $stv_whatsapp_number;
?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-[#050505] text-[#F9FAFB] antialiased' ); ?>>
<?php wp_body_open(); ?>

<header class="stv-site-header sticky top-0 z-50 border-b border-white/10 bg-[#050505]/85 backdrop-blur-xl">
	<nav class="stv-nav mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8" aria-label="<?php echo esc_attr__( 'Primary navigation', 'street-techspot-ventures' ); ?>">
		<a class="stv-brand-link flex min-w-0 items-center gap-3 text-[#F9FAFB]" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img class="stv-logo h-9 w-9 shrink-0" src="<?php echo esc_url( get_template_directory_uri() . '/assets/svg/logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="36" height="36">
			<span class="stv-site-title truncate text-sm font-bold leading-none sm:text-base">
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
			</span>
		</a>

		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => false,
					'menu_class'     => 'stv-menu hidden items-center gap-6 text-sm font-semibold text-[#D1D5DB] md:flex',
					'depth'          => 1,
				)
			);
		} else {
			?>
			<div class="stv-menu hidden items-center gap-6 text-sm font-semibold text-[#D1D5DB] md:flex">
				<a class="hover:text-[#00FFD1]" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php echo esc_html__( 'Home', 'street-techspot-ventures' ); ?>
				</a>
				<a class="hover:text-[#00FFD1]" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>">
					<?php echo esc_html__( 'Shop', 'street-techspot-ventures' ); ?>
				</a>
				<a class="hover:text-[#00FFD1]" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<?php echo esc_html__( 'Support', 'street-techspot-ventures' ); ?>
				</a>
			</div>
			<?php
		}
		?>

		<div class="stv-search-wrap hidden min-w-64 max-w-sm flex-1 lg:block">
			<label class="sr-only" for="stv-live-search"><?php echo esc_html__( 'Search products', 'street-techspot-ventures' ); ?></label>
			<input id="stv-live-search" class="stv-search-input w-full rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white outline-none placeholder:text-[#9CA3AF]" type="search" placeholder="<?php echo esc_attr__( 'Search phones, accessories, deals', 'street-techspot-ventures' ); ?>">
			<div class="stv-live-results absolute mt-2 hidden w-80 rounded-2xl border border-white/10 bg-[#0B0F14] p-2 shadow-[0_8px_32px_rgba(0,0,0,0.4)]"></div>
		</div>

		<div class="stv-header-actions flex items-center gap-2">
			<a class="stv-header-link hidden rounded-full border border-white/10 px-4 py-2 text-xs font-bold text-[#00FFD1] md:inline-flex" href="<?php echo esc_url( $stv_whatsapp_url ); ?>">
				<?php echo esc_html__( 'WhatsApp', 'street-techspot-ventures' ); ?>
			</a>
			<a class="stv-cart-pill rounded-full bg-[#00FFD1] px-4 py-2 text-xs font-black text-black" href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' ) ); ?>">
				<?php echo esc_html__( 'Cart', 'street-techspot-ventures' ); ?>
			</a>
		</div>
	</nav>
</header>

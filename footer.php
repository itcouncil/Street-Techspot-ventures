<?php
/**
 * Theme footer.
 *
 * @package Street_Techspot_Ventures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stv_whatsapp_number = preg_replace( '/\D+/', '', get_option( 'stv_whatsapp_number', '254700000000' ) );
$stv_whatsapp_url    = 'https://wa.me/' . $stv_whatsapp_number;
$stv_privacy_url     = function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' );
?>

<footer class="stv-site-footer border-t border-white/10 bg-[#050505] px-4 py-10 text-sm text-[#9CA3AF] sm:px-6 lg:px-8" itemscope itemtype="https://schema.org/Organization">
	<div class="stv-footer-inner mx-auto grid max-w-7xl gap-8 md:grid-cols-[1fr_auto] md:items-end">
		<div>
			<p class="text-base font-bold text-[#F9FAFB]" itemprop="name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
			<form class="stv-newsletter mt-4 flex max-w-md gap-2" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<?php wp_nonce_field( 'stv_newsletter_signup' ); ?>
				<input type="hidden" name="action" value="stv_newsletter_signup">
				<label class="sr-only" for="stv-newsletter-email"><?php echo esc_html__( 'Email address', 'street-techspot-ventures' ); ?></label>
				<input id="stv-newsletter-email" class="stv-newsletter-input min-w-0 flex-1 rounded-full border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none placeholder:text-[#9CA3AF]" type="email" name="email" placeholder="<?php echo esc_attr__( 'Email for drops and offers', 'street-techspot-ventures' ); ?>" required>
				<button class="stv-newsletter-button rounded-full bg-[#FF6B00] px-5 py-3 text-xs font-black text-white" type="submit">
					<?php echo esc_html__( 'Join', 'street-techspot-ventures' ); ?>
				</button>
			</form>
		</div>
		<div class="stv-footer-links flex flex-wrap gap-4 md:justify-end">
			<a class="hover:text-[#00FFD1]" href="<?php echo esc_url( $stv_whatsapp_url ); ?>"><?php echo esc_html__( 'WhatsApp', 'street-techspot-ventures' ); ?></a>
			<a class="hover:text-[#00FFD1]" href="<?php echo esc_url( $stv_privacy_url ); ?>"><?php echo esc_html__( 'Privacy', 'street-techspot-ventures' ); ?></a>
			<a class="hover:text-[#00FFD1]" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html__( 'Support', 'street-techspot-ventures' ); ?></a>
		</div>
	</div>
	<p class="mx-auto mt-8 max-w-7xl text-xs">
		<?php
		printf(
			/* translators: %s: Current year. */
			esc_html__( 'Copyright %s Street Techspot Ventures. All rights reserved.', 'street-techspot-ventures' ),
			esc_html( gmdate( 'Y' ) )
		);
		?>
	</p>
</footer>

<a class="stv-floating-whatsapp fixed bottom-24 right-4 z-50 rounded-full bg-[#00FF99] px-4 py-3 text-xs font-black text-black shadow-[0_0_28px_rgba(0,255,153,0.25)]" href="<?php echo esc_url( $stv_whatsapp_url ); ?>">
	<?php echo esc_html__( 'WhatsApp', 'street-techspot-ventures' ); ?>
</a>

<nav class="stv-mobile-bottom-nav fixed inset-x-3 bottom-3 z-50 grid grid-cols-5 rounded-2xl border border-white/10 bg-[#0B0F14]/95 p-2 text-center text-[11px] font-bold text-[#D1D5DB] shadow-[0_8px_32px_rgba(0,0,0,0.4)] backdrop-blur-xl md:hidden" aria-label="<?php echo esc_attr__( 'Mobile commerce navigation', 'street-techspot-ventures' ); ?>">
	<a class="rounded-xl px-2 py-2 hover:bg-white/5 hover:text-[#00FFD1]" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'Home', 'street-techspot-ventures' ); ?></a>
	<a class="rounded-xl px-2 py-2 hover:bg-white/5 hover:text-[#00FFD1]" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>"><?php echo esc_html__( 'Shop', 'street-techspot-ventures' ); ?></a>
	<a class="rounded-xl px-2 py-2 hover:bg-white/5 hover:text-[#00FFD1]" href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' ) ); ?>"><?php echo esc_html__( 'Cart', 'street-techspot-ventures' ); ?></a>
	<button class="stv-wishlist-open rounded-xl px-2 py-2 hover:bg-white/5 hover:text-[#00FFD1]" type="button"><?php echo esc_html__( 'Saved', 'street-techspot-ventures' ); ?></button>
	<a class="rounded-xl px-2 py-2 hover:bg-white/5 hover:text-[#00FFD1]" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ); ?>"><?php echo esc_html__( 'Account', 'street-techspot-ventures' ); ?></a>
</nav>

<?php wp_footer(); ?>
</body>
</html>

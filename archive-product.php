<?php
/**
 * Product archive template.
 *
 * @package Street_Techspot_Ventures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="stv-shop-main">
	<div class="stv-shop-shell">
		<?php woocommerce_content(); ?>
	</div>
</main>

<?php
get_footer();

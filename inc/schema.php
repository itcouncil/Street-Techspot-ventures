<?php
/**
 * Structured data helpers.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print minimal store schema.
 */
function print_organization_schema() {
	if ( ! is_front_page() ) {
		return;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Store',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
		'areaServed' => array(
			'@type' => 'Country',
			'name'  => 'Kenya',
		),
		'paymentAccepted' => array(
			'M-Pesa',
			'Cards',
			'Cash',
		),
	);

	printf(
		'<script type="application/ld+json">%s</script>',
		wp_json_encode( $schema )
	);
}
add_action( 'wp_head', __NAMESPACE__ . '\\print_organization_schema' );

<?php
/**
 * Mobile optimization hooks.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add mobile-first body classes.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function mobile_body_class( $classes ) {
	$classes[] = 'stv-mobile-first';

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\\mobile_body_class' );

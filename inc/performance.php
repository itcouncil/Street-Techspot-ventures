<?php
/**
 * Performance helpers.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Optimize image attributes.
 *
 * @param array<string,string> $attr Image attributes.
 * @return array<string,string>
 */
function optimize_attachment_image_attributes( $attr ) {
	$attr['loading']  = $attr['loading'] ?? 'lazy';
	$attr['decoding'] = 'async';

	if ( empty( $attr['width'] ) ) {
		$attr['width'] = '480';
	}

	if ( empty( $attr['height'] ) ) {
		$attr['height'] = '480';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\optimize_attachment_image_attributes' );

/**
 * Remove avoidable WordPress front-end hints.
 */
function remove_unneeded_resource_hints() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
}
add_action( 'init', __NAMESPACE__ . '\\remove_unneeded_resource_hints' );

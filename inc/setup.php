<?php
/**
 * Theme setup.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set up theme defaults and WordPress feature support.
 */
function setup_theme() {
	load_theme_textdomain( 'street-techspot-ventures', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array(
			'caption',
			'comment-form',
			'comment-list',
			'gallery',
			'search-form',
			'script',
			'style',
		)
	);

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'street-techspot-ventures' ),
			'footer'  => esc_html__( 'Footer Menu', 'street-techspot-ventures' ),
		)
	);
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup_theme' );

/**
 * Allow SVG uploads for administrators.
 *
 * @param array<string,string> $mime_types Registered mime types.
 * @return array<string,string>
 */
function allow_svg_uploads( $mime_types ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mime_types['svg'] = 'image/svg+xml';
	}

	return $mime_types;
}
add_filter( 'upload_mimes', __NAMESPACE__ . '\\allow_svg_uploads' );

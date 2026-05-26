<?php
/**
 * Asset loading.
 *
 * @package Street_Techspot_Ventures
 */

namespace STV\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return a file version based on mtime with theme version fallback.
 *
 * @param string $relative_path Relative theme path.
 * @return string
 */
function asset_version( $relative_path ) {
	$file = get_template_directory() . '/' . ltrim( $relative_path, '/' );

	return file_exists( $file ) ? (string) filemtime( $file ) : (string) wp_get_theme()->get( 'Version' );
}

/**
 * Enqueue theme assets.
 */
function enqueue_assets() {
	$tailwind_config = array(
		'theme' => array(
			'extend' => array(
				'colors' => array(
					'stv-black'  => '#050505',
					'stv-carbon' => '#0B0F14',
					'stv-graph'  => '#111827',
					'stv-teal'   => '#00FFD1',
					'stv-orange' => '#FF6B00',
					'stv-blue'   => '#4DA3FF',
					'stv-green'  => '#00FF99',
				),
				'fontFamily' => array(
					'sans' => array(
						'Inter',
						'ui-sans-serif',
						'system-ui',
						'sans-serif',
					),
				),
			),
		),
	);

	wp_enqueue_style(
		'street-techspot-ventures-style',
		get_stylesheet_uri(),
		array(),
		asset_version( 'style.css' )
	);

	wp_enqueue_style(
		'street-techspot-ventures-app',
		get_template_directory_uri() . '/assets/css/app.css',
		array( 'street-techspot-ventures-style' ),
		asset_version( 'assets/css/app.css' )
	);

	wp_enqueue_script(
		'street-techspot-ventures-tailwind',
		'https://cdn.tailwindcss.com',
		array(),
		'3.4.17',
		false
	);

	wp_add_inline_script(
		'street-techspot-ventures-tailwind',
		'tailwind.config = ' . wp_json_encode( $tailwind_config ) . ';',
		'before'
	);

	$scripts = array(
		'app',
		'cart',
		'search',
		'mobile-ui',
		'impulse-slider',
		'mpesa-live',
	);

	foreach ( $scripts as $script ) {
		$handle = 'street-techspot-ventures-' . $script;
		$path   = 'assets/js/' . $script . '.js';

		wp_enqueue_script(
			$handle,
			get_template_directory_uri() . '/' . $path,
			array(),
			asset_version( $path ),
			true
		);
		wp_script_add_data( $handle, 'defer', true );
	}

	wp_localize_script(
		'street-techspot-ventures-cart',
		'stvAjax',
		array(
			'url'      => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'stv_ajax_nonce' ),
			'currency' => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '',
			'shop'     => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

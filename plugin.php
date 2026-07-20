<?php
/**
 * Plugin Name: Goodshelves
 * Description: Display your Goodreads bookshelves.
 * Version: 1.0.0
 * Update URI: https://updates.wpelevator.com/wp-json/update-pilot/v1/plugins
 * Requires PHP: 7.4
 */

namespace Preseto\Goodshelves;

if ( ! function_exists( 'add_action' ) ) {
	return; // Ensure WP core is loading the plugin.
}

// Only if global project-wide autoload is not available.
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) && ! class_exists( Plugin::class ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

$plugin = new Plugin( __FILE__ );
$goodshelve = new GoodshelvesPlugin( $plugin );

add_action( 'plugins_loaded', [ $goodshelve, 'init' ] );

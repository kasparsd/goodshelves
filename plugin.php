<?php
/**
 * Plugin Name: Goodshelves
 */

namespace Preseto\Goodshelves;

if ( ! function_exists( 'add_action' ) ) {
	return; // Ensure WP core is loading the plugin.
}

// Only if global project-wide autoload is not present.
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

$plugin = new Plugin( __FILE__ );
$goodshelve = new GoodshelvesPlugin( $plugin );

$goodshelve->init();

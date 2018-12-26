<?php
/**
 * Plugin Name: Goodshelves
 */

namespace Preseto\Goodshelves;

include __DIR__ . '/vendor/autoload.php';

$plugin = new Plugin( __FILE__ );
$goodshelve = new GoodshelvesPlugin( $plugin );

$goodshelve->init();

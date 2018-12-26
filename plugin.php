<?php
/**
 * Plugin Name: Goodshelves
 */

namespace Preseto\Goodshelves;

$plugin = new Plugin( __FILE__ );
$goodshelve = new GoodshelvePlugin( $plugin );

$goodshelve->init();

<?php

namespace Preseto\Goodshelves;

class GoodshelvesPlugin {

	protected $plugin;

	protected $api;

	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->api = new GoodreadsApi( new \WP_Http() );
	}

	public function init() {
		add_shortcode( 'goodshelves', [ $this, 'shortcode' ] );
	}

	public function shortcode( $attributes ) {
		$this->api->set_key( '12345' );

		$attributes = shortcode_atts( array(
			'shelf' => '',
			'user' => '',
		), $attributes );

		// TODO Sanitize user ID/name?
		$user_id = $attributes['user'];

		// TODO Show an error for logged-in users?
		if ( empty( $user_id ) ) {
			return;
		}

		$books = $this->api->user_review_list( $user_id, $attributes['shelf'] );

		print_r( $books );
	}

}

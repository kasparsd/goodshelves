<?php

namespace Preseto\Goodshelves;

class GoodshelvesPlugin {

	protected $plugin;

	protected $api;

	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->api = new Goodreads\FeedApi();
	}

	public function init() {
		add_shortcode( 'goodshelves', [ $this, 'shortcode' ] );
	}

	public function shortcode( $attributes ) {
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

		if ( ! is_wp_error( $books ) ) {
			return $this->render_feed( $books );
		}
	}

	public function render_feed( $feed ) {
		$items = $feed->get_items();
		$html = [];

		foreach ( $items as $item ) {
			$url = strtok( $item->link, '?' );

			$html[] = sprintf(
				'<li class="goodshelves-book">
					<a href="%s" class="goodshelves-book__link">
						<img src="%s" class="goodshelves-book__image" alt="%s" />
					</a>
				</li>',
				esc_url( $url ),
				esc_url( $item->book_image_url ),
				esc_attr( $item->title )
			);
		}

		return sprintf( '<ul>%s</ul>', implode( '', $html ) );
	}

}

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
			$image_url = $item->get_item_tags( '', 'book_large_image_url' );
			$link = strtok( $item->get_link(), '?' );

			$html[] = sprintf(
				'<li class="blocks-gallery-item goodshelves-books__item">
					<a href="%s" class="goodshelves-books__link">
						<img src="%s" class="goodshelves-books__image" alt="%s" />
					</a>
				</li>',
				esc_url( $link ),
				esc_url( $image_url[0]['data'] ),
				esc_attr( $item->get_title() )
			);
		}

		return sprintf(
			'<ul class="wp-block-gallery columns-5 goodshelves-books">
				%s
			</ul>',
			implode( '', $html )
		);
	}

}

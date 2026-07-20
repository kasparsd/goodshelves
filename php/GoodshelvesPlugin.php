<?php

namespace Preseto\Goodshelves;

use Preseto\Goodshelves\Goodreads\FeedApi;
use SimplePie\SimplePie;

class GoodshelvesPlugin {

	protected Plugin $plugin;

	protected FeedApi $api;

	const STYLE_HANDLE = 'goodshelves';

	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		$this->api = new FeedApi();
	}

	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_scripts' ] );

		add_shortcode( 'goodshelves', [ $this, 'shortcode' ] );
	}

	public function action_enqueue_scripts() {
		wp_register_style(
			self::STYLE_HANDLE,
			false,
			[
				'wp-block-gallery', // inherit the styles.
			],
			null
		);
	}

	public function shortcode( array $attributes ) {
		$attributes = shortcode_atts( array(
			'shelf' => '',
			'user' => '',
		), $attributes );

		// TODO Sanitize user ID/name?
		$user_id = (int) $attributes['user'];
		$shelf_id = (string) $attributes['shelf'];

		// TODO Show an error for logged-in users?
		if ( empty( $user_id ) || empty( $shelf_id ) ) {
			return null;
		}

		wp_enqueue_style( self::STYLE_HANDLE );

		try {
			$books = $this->api->user_review_list( $user_id, $shelf_id );

			return $this->render_feed( $books );
		} catch ( \RuntimeException $e ) {
			// TODO: Implement this.
		}

		return null;
	}

	public function render_feed( SimplePie $feed ) {
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

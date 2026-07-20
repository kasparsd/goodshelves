<?php

namespace Preseto\Goodshelves\Goodreads;

use SimplePie\SimplePie;

class Feed_Api {

	public function user_review_list( int $user_id, ?string $shelf = null ): SimplePie {
		$args = [
			'shelf' => $shelf,
		];

		$url = $this->url(
			sprintf( 'review/list_rss/%d', absint( $user_id ) ),
			array_filter( $args )
		);

		return $this->get( $url );
	}

	protected function get( string $url ): SimplePie {
		$feed = fetch_feed( $url ); // Rely on 12h cache by WP core around this.

		if ( is_wp_error( $feed ) ) {
			throw new \RuntimeException( sprintf( 'Failed to fetch: %s', $feed->get_error_message() ) );
		}

		return $feed;
	}

	protected function url( string $path, array $params = [] ): string {
		$url = sprintf(
			'https://www.goodreads.com/%s',
			trim( $path, '/' )
		);

		return add_query_arg( $params, $url );
	}
}

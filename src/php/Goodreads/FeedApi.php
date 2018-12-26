<?php

namespace Preseto\Goodshelves\Goodreads;

class FeedApi {

	public function user_review_list( $user_id, $shelf ) {
		$url = $this->url(
			sprintf( 'review/list_rss/%d', intval( $user_id ) ),
			[
				'shelf' => $shelf,
			]
		);

		return $this->get( $url );
	}

	protected function get( $url ) {
		return fetch_feed( $url );
	}

	protected function url( $path, $params = [] ) {
		// Remove empty attributes.
		$params = array_filter( $params );

		$url = sprintf(
			'https://www.goodreads.com/%s',
			trim( $path, '/' )
		);

		return add_query_arg( $params, $url );
	}

}

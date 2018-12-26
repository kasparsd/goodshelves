<?php

namespace Preseto\Goodshelves\Goodreads;

class Api {

	/**
	 * Instance of the HTTP request instance.
	 *
	 * @var \WP_Http
	 */
	protected $http;

	/**
	 * API key.
	 *
	 * @var string
	 */
	protected $api_key;

	public function __construct( $http ) {
		$this->http = $http;
	}

	public function set_key( $api_key ) {
		$this->api_key = $api_key;
	}

	public function user_review_list( $user_id, $shelf ) {
		$url = $this->url(
			'review/list',
			[
				'id' => intval( $user_id ),
				'shelf' => $shelf,
			]
		);

		return $this->get( $url );
	}

	protected function get( $url ) {
		// TODO Check if the API key has been set.
		$response = $this->http->get( $url );

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	protected function url( $path, $params = [] ) {
		$params['key'] = $this->api_key;
		$params['v'] = 2;

		$url = sprintf(
			'https://www.goodreads.com/%s',
			trim( $path, '/' )
		);

		return add_query_arg( $params, $url );
	}

}

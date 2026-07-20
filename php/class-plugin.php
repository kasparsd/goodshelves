<?php

namespace Preseto\Goodshelves;

/**
 * WordPress plugin abstraction.
 */
class Plugin {

	/**
	 * Absolute path to the main plugin file.
	 *
	 * @var string
	 */
	protected string $file;

	/**
	 * Absolute path to the root directory of this plugin.
	 *
	 * @var string
	 */
	protected string $dir;

	/**
	 * Store the WP uploads dir object.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_upload_dir/
	 * @var array
	 */
	protected array $uploads_dir;

	/**
	 * Setup the plugin.
	 *
	 * @param string $plugin_file_path Absolute path to the main plugin file.
	 */
	public function __construct( string $plugin_file_path ) {
		$this->file = $plugin_file_path;
		$this->dir = dirname( $plugin_file_path );
		$this->uploads_dir = wp_upload_dir( null, false ); // Don't create the time-based directory.
	}

	/**
	 * Return the absolute path to the plugin directory.
	 *
	 * @return string
	 */
	public function dir() {
		return $this->dir;
	}

	/**
	 * Return the absolute path to the plugin file.
	 *
	 * @return string
	 */
	public function file() {
		return $this->file;
	}

	public function basename( ?string $file_path = null ): string {
		if ( ! isset( $file_path ) ) {
			$file_path = $this->file();
		}

		return plugin_basename( $file_path );
	}

	public function get_asset_meta( string $relative_path ): array {
		$meta = [
			'url' => $this->asset_url( $relative_path ),
			'path' => $this->asset_path( $relative_path ),
			'dependencies' => [],
			'version' => null,
		];

		$meta_path = $this->asset_path(
			sprintf(
				'%s/%s.asset.php',
				dirname( $relative_path ),
				pathinfo( $relative_path, PATHINFO_FILENAME )
			)
		);

		if ( is_readable( $meta_path ) ) {
			$build_meta = include $meta_path;

			return array_merge( $meta, $build_meta );
		} elseif ( is_readable( $meta['path'] ) ) {
			$meta['version'] = filemtime( $meta['path'] );
		}

		return $meta;
	}

	public function asset_url( ?string $asset_path_relative = null ): string {
		if ( isset( $asset_path_relative ) ) {
			return plugins_url( $this->asset_path( $asset_path_relative ) );
		}

		return plugins_url( $this->dir() );
	}

	public function asset_path( ?string $asset_path_relative = null ): string {
		if ( isset( $asset_path_relative ) ) {
			return sprintf( '%s/%s', $this->dir(), ltrim( $asset_path_relative, '/' ) );
		}

		return $this->dir();
	}

	public function uploads_dir( ?string $path_relative = null ) {
		if ( isset( $path_relative ) ) {
			return sprintf( '%s/%s', $this->uploads_dir['basedir'], $path_relative );
		}

		return $this->uploads_dir['basedir'];
	}

	public function uploads_dir_url( ?string $path_relative = null ) {
		if ( isset( $path_relative ) ) {
			return sprintf( '%s/%s', $this->uploads_dir['baseurl'], $path_relative );
		}

		return $this->uploads_dir['baseurl'];
	}

	public function version(): ?string {
		return $this->meta( 'Version' );
	}

	public function meta( ?string $field = null ) {
		static $meta;

		if ( ! isset( $meta ) ) {
			$meta = get_plugin_data( $this->file );
		}

		if ( isset( $field ) ) {
			if ( isset( $meta[ $field ] ) ) {
				return $meta[ $field ];
			}

			return null;
		}

		return $meta;
	}
}

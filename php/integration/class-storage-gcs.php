<?php
/**
 * This file is responsible for getting files from Google Cloud Storage.
 *
 * @package WP_Tide_API
 */

namespace WP_Tide_API\Integration;

use Google\Cloud\Storage\StorageClient;
use WP_Tide_API\Base;

/**
 * Class Storage_GCS
 */
class Storage_GCS extends Base {

	/**
	 * Get temporary URL to object.
	 *
	 * @param array $meta Object data to retrieve.
	 *
	 * @return mixed Valid URL or \WP_Error.
	 */
	public function get_url( $meta ) {
		// Catch all failures.
		try {
			$storage = $this->get_client_instance();

			$bucket = $storage->bucket( $meta['path'] );
			$object = $bucket->object( $meta['filename'] );
			$url    = $object->signedUrl( time() + ( 60 * 5 ) );

			// A temporary pre-signed url.
			return (string) $url;
		} catch ( \Exception $e ) {

			return new \WP_Error( 'gcs_get_url_fail', $e->getMessage(), $e );
		}
	}

	/**
	 * Get new StorageClient instance.
	 *
	 * @return StorageClient
	 */
	public function get_client_instance() {
		return new StorageClient();
	}
}

<?php

namespace Queue\Lib;

class Config {

	/**
	 * Maybe move the settings to a JSON file?
	 *
	 * @var array
	 */
	private static $default = array(
		'TABLE_PREFIX'  => 'ndevr_',
		'DB_VERSION'    => '1.0',
		'ELEMENT_TYPES' => [
			'script',
		],
		'ELEMENT_STATUS' => [
			'pending',
			'in_progress',
			'failed',
			'disabled',
			'success',
		],
	);

	private static $test = array();

	private static $dev = array();

	private static $production = array();

	/**
	 * Get the config value for a given key
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public static function get( $key ) {

		$environment = 'dev'; //ToDo: Implement real env variable

		$values = self::$default;
		switch ( $environment ) {
			case 'production':
				$values = array_merge( $values, self::$production );
				break;

			case 'staging':
				$values = array_merge( $values, self::$dev );
				break;

			case 'dev':
				$values = array_merge( $values, self::$test );
				break;
		}

		if ( isset( $values[ $key ] ) ) {
			return $values[ $key ];
		}

		return null;

	}
}

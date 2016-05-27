<?php

namespace Queue\Lib;

class Database {
	public static function init() {
		self::create_tables();
	}

	private static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		//Config::get( 'TABLE_PREFIX' )
		$queue_table_name = $wpdb->prefix . 'queue'; //Should we use our own prefix?
		$sql_queue        = "CREATE TABLE $queue_table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			created_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
			updated_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
			wp_user VARCHAR(50) NOT NULL,
			active TINYINT(1) DEFAULT 1,
			UNIQUE KEY id (id)
		) $charset_collate;";

		$element_table_name = $wpdb->prefix . 'element'; //Should we use our own prefix?
		$element_types      = "'" . implode( "','", Config::get( 'ELEMENT_TYPES' ) ) . "'";
		$element_status     = "'" . implode( "','", Config::get( 'ELEMENT_STATUS' ) ) . "'";
		$sql_element        = "CREATE TABLE $element_table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			`type` ENUM($element_types) NOT NULL,
			priority INT DEFAULT 0 NOT NULL,
			status ENUM($element_status) NOT NULL,
			`data` VARCHAR(255),
			wp_user VARCHAR(50) NOT NULL,
			queue_id mediumint(9) NOT NULL,
			active TINYINT(1) DEFAULT 1,
			created_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
			updated_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( [ $sql_queue, $sql_element ] );

	}

	private static function get_current_datetime() {

		$tz_object = new \DateTimeZone( 'America/New_York' );

		$datetime = new \DateTime( "now" );
		$datetime->setTimezone( $tz_object );

		return $datetime->format( 'Y-m-d H:i:s' );
	}

	public static function insert( $table_name, $args ) {
		global $wpdb;

		$queue_id = $wpdb->insert(
			$table_name,
			$args
		);

		return $queue_id;
	}
}
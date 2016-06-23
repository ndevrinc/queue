<?php

namespace Queue\Lib;

class Database {
	private static $table_name;
	private static $element_table_name;
	private static $wpdb;

	public static function init() {
		global $wpdb;
		self::$wpdb       = $wpdb;
		self::$table_name = self::$wpdb->prefix . 'queue'; //Should we use our own prefix?
		self::$element_table_name = self::$wpdb->prefix . 'element'; //Should we use our own prefix?

		self::create_tables();
	}

	private static function create_tables() {
		$charset_collate = self::$wpdb->get_charset_collate();

		//Config::get( 'TABLE_PREFIX' )
		$sql_queue = "CREATE TABLE IF NOT EXISTS " . self::$table_name . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			created_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
			updated_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
			wp_user VARCHAR(50) NOT NULL,
			active TINYINT(1) DEFAULT 1,
			UNIQUE KEY id (id)
		) $charset_collate;";

		$element_types      = "'" . implode( "','", Config::get( 'ELEMENT_TYPES' ) ) . "'";
		$element_status     = "'" . implode( "','", Config::get( 'ELEMENT_STATUS' ) ) . "'";
		$sql_element        = "CREATE TABLE IF NOT EXISTS " . self::$element_table_name . "(
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

	public static function insert( $args ) {
		if ( self::$wpdb->insert( self::$table_name, $args ) ) {
			return self::$wpdb->insert_id;
		}

		return false;
	}

	public static function delete( $row_id ) {
		if ( self::$wpdb->delete( self::$table_name, array( 'id' => $row_id ) ) ) {
			return true;
		}

		return false;
	}

	public static function get_results( $query ) {
		$results = self::$wpdb->get_results( $query );

		return $results;
	}

	public static function get_queues() {
		$results = self::$wpdb->get_results(
			"SELECT `id`, `updated_date`
			FROM " . self::$table_name . "
			WHERE active = '1';" );

		return $results;
	}

	public static function is_empty($queue_id) {
		if ( self::$wpdb->get_col( self::$wpdb->prepare( "SELECT * FROM " . self::$element_table_name . " WHERE `queue_id`=%d LIMIT %d", array($queue_id, 1 ) ) ) ) {
			return false;
		}

		return true;

	}
}
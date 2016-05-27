<?php

namespace Queue\Lib;

//ToDo make class abstract
class Queue {

	protected $persistent;

	public function is_empty() {
		global $wpdb;
		$queue_table_name = $wpdb->prefix . 'queue'; //Should we use our own prefix?
		$empty            = $wpdb->get_col( $wpdb->prepare( "SELECT * FROM $queue_table_name LIMIT %d", array(1) ) );
		if ( empty( $empty ) ) {
			return true;
		}

		return false;

	}

	public function is_persistent() {
		return $this->persistent;
	}

	public function insert() {
	}

	public function peek() {
		return $this->get_high_priority();
	}

	public function pop() {
		return $this->get_high_priority( true );
	}

	public function update() {
	}

	public function delete() {
	}

	public function disable() {
	}

	/**
	 * Maybe implement this
	 */
	public function skip_element() {
	}

	/**
	 * Creates a new Queue
	 *
	 * We will start using a new table for the moment, future plans include use of current WP table
	 *
	 * @param bool $is_persistent Option to decide whether Queue will be saved to DB (Persistent) or not (memory) / At the moment persistent is the only option
	 *
	 * @return int|bool returns Queue ID if valid or false
	 */
	public function create( $is_persistent = true ) {
		global $wpdb;
		$queue_table_name = $wpdb->prefix . 'queue'; //Should we use our own prefix?

		$this->persistent = $is_persistent;
		if ( $this->persistent ) {
			Database::init();

			$args = array(
				'created_date' => current_time( 'mysql' ),
				'updated_date' => current_time( 'mysql' ),
				'wp_user'      => get_current_user_id(),
				'active'       => '1',
			);

			$id = Database::insert( $queue_table_name, $args );

			return $id;
		}

		return false;
	}

	private function get_high_priority( $remove = false ) {
		return true;
	}

	private function init_tables() {

	}

}
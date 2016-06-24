<?php

namespace Queue\Lib;

class Queue {

	protected $persistent;

	public function __construct() {
		Database::init();
	}

	public function is_empty( $queue_id ) {
		return Database::is_empty( $queue_id );
	}

	public function is_persistent() {
		return $this->persistent;
	}

	public function insert( $args ) {
		$args = array(
			'type'         => $args['type'],
			'name'         => $args['name'],
			'priority'     => $args['priority'],
			'status'       => Element_Status::PENDING,
			'data'         => $args['data'],
			'wp_user'      => get_current_user_id(),
			'queue_id'     => $args['queue_id'],
			'active'       => '1',
			'created_date' => current_time( 'mysql' ),
			'updated_date' => current_time( 'mysql' ),
		);

		$id = Database::insert( 'wp_element', $args );

		return $id;
	}

	public function edit_element( $args ) {
		$args['update'] = array(
			'type'         => $args['type'],
			'name'         => $args['name'],
			'priority'     => $args['priority'],
			'data'         => $args['data'],
			'wp_user'      => get_current_user_id(),
			'queue_id'     => $args['queue_id'],
			'updated_date' => current_time( 'mysql' ),
		);
		$args['where']  = array(
			'id' => $args['id'],
		);

		return Database::update( 'wp_element', $args );
	}

	public function delete_element( $element_id ) {
		return Database::delete( 'wp_element', $element_id );
	}

	public function get_all() {
		return Database::get_queues();
	}

	public function peek( $queue_id ) {
		return $this->get_high_priority( $queue_id );
	}

	public function pop( $queue_id ) {
		return $this->get_high_priority( $queue_id, true );
	}

	public function delete( $queue_id ) {
		return Database::delete( 'wp_queue', $queue_id );
	}

	public function disable( $queue_id ) {
		$args['update'] = array(
			'active' => 0,
		);
		$args['where']  = array(
			'id' => $queue_id,
		);

		return Database::update( 'wp_queue', $args );
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
		$this->persistent = $is_persistent;
		if ( $this->persistent ) {
			$args = array(
				'created_date' => current_time( 'mysql' ),
				'updated_date' => current_time( 'mysql' ),
				'wp_user'      => get_current_user_id(),
				'active'       => '1',
			);

			$id = Database::insert( 'wp_queue', $args );

			return $id;
		}

		return false;
	}

	private function get_high_priority( $queue_id, $remove = false ) {
		return Database::get_highest_priority( $queue_id, $remove );
	}

	public static function get_all_elements( $queue_id ) {
		return Database::get_elements( $queue_id );
	}

}
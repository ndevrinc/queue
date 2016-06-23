<?php

namespace Queue\Lib;

//ToDo make class abstract
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
			'type'         => $args['type'],//Element_Type::SCRIPT,
			'priority'     => $args['priority'],
			'status'       => Element_Status::PENDING,
			'data'         => $args['data'],//$data,
			'wp_user'      => get_current_user_id(),
			'queue_id'     => $args['queue_id'],//$queue_id,
			'active'       => '1',
			'created_date' => current_time( 'mysql' ),
			'updated_date' => current_time( 'mysql' ),
		);

		$id = Database::insert( 'wp_element', $args );

		return $id;
	}

	public function peek() {
		return $this->get_high_priority();
	}

	public function pop() {
		return $this->get_high_priority( true );
	}

	public function update() {
	}

	public function delete( $queue_id ) {
		return Database::delete( $queue_id );
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

	private function get_high_priority( $remove = false ) {
		return true;
	}

	private function init_tables() {

	}

}
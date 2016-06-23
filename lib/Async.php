<?php
namespace Queue\Lib;

class Async {
	public function init() {
		$this->set_actions();
	}

	public function set_actions() {
		add_action( 'wp_ajax_add_queue', array( $this, 'add_new_queue' ) );
		add_action( 'wp_ajax_delete_queue', array( $this, 'delete_queue' ) );
		add_action( 'wp_ajax_is_empty_queue', array( $this, 'is_empty' ) );
		add_action( 'wp_ajax_insert_element', array( $this, 'insert_element' ) );
	}

	public function add_new_queue() {
		$queue        = new Queue();
		$new_queue_id = $queue->create();

		if ( $new_queue_id ) {
			$response = [
				'status'  => 200,
				'message' => 'Queue added',
				'queue'   => [
					'id' => $new_queue_id,
				],
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error creating a queue',
				'queue'   => [
					'id' => '',
				],
			];
		}

		wp_send_json( $response );
	}

	public function delete_queue() {
		if ( empty( $queue_id = $_POST['data']['queue_id'] ) ) {
			$response = [
				'status'  => 500,
				'message' => 'Empty row id sent',
				'queue'   => [
					'id' => '',
				],
			];
			wp_send_json( $response );
		}

		$queue   = new Queue();
		$deleted = $queue->delete( $queue_id );

		if ( $deleted ) {
			$response = [
				'status'  => 200,
				'message' => 'Queue ' . $queue_id . ' deleted',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error deleting a queue',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		}

		wp_send_json( $response );
	}

	public function is_empty() {
		if ( empty( $queue_id = $_POST['data']['queue_id'] ) ) {
			$response = [
				'status'  => 500,
				'message' => 'Empty queue id sent',
				'queue'   => [
					'id' => '',
				],
			];
			wp_send_json( $response );
		}

		$queue = new Queue();
		if ( $queue->is_empty( $queue_id ) ) {
			$response = [
				'status'  => 200,
				'message' => 'Queue ' . $queue_id . ' is empty',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		} else {
			$response = [
				'status'  => 200,
				'message' => 'Queue ' . $queue_id . ' is not empty',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		}

		wp_send_json( $response );
	}

	public function insert_element() {
		if ( empty( $queue_id = $_POST['data']['queue_id'] ) ) {
			$response = [
				'status'  => 500,
				'message' => 'Empty queue id sent',
				'queue'   => [
					'id' => '',
				],
			];
			wp_send_json( $response );
		}

		$queue = new Queue();
		if ( $queue->insert( $_POST['data'] ) ) {
			$response = [
				'status'  => 200,
				'message' => 'Queue ' . $queue_id . ' is empty',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		} else {
			$response = [
				'status'  => 200,
				'message' => 'Queue ' . $queue_id . ' is not empty',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		}

		wp_send_json( $response );
	}
}
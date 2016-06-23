<?php
namespace Queue\Lib;

class Async {
	public function init() {
		$this->set_actions();
	}

	public function set_actions() {
		add_action( 'wp_ajax_add_queue', array( $this, 'add_new_queue' ) );
		add_action( 'wp_ajax_delete_queue', array( $this, 'delete_queue' ) );
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
		if ( empty( $row_id = $_POST['data']['row_id'] ) ) {
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
		$deleted = $queue->delete( $row_id );

		if ( $deleted ) {
			$response = [
				'status'  => 200,
				'message' => 'Queue ' . $row_id . ' deleted',
				'queue'   => [
					'id' => $row_id,
				],
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error deleting a queue',
				'queue'   => [
					'id' => $row_id,
				],
			];
		}

		wp_send_json( $response );
	}
}
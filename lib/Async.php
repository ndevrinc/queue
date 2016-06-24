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
		add_action( 'wp_ajax_disable_queue', array( $this, 'disable_queue' ) );
		add_action( 'wp_ajax_get_all_elements', array( $this, 'get_all_elements' ) );
		add_action( 'wp_ajax_edit_element', array( $this, 'edit_element' ) );
		add_action( 'wp_ajax_delete_element', array( $this, 'delete_element' ) );
		add_action( 'wp_ajax_peek_element', array( $this, 'peek' ) );
		add_action( 'wp_ajax_pop_element', array( $this, 'pop' ) );
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

	public function delete_element() {
		if ( empty( $element_id = $_POST['data']['element_id'] ) ) {
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
		$deleted = $queue->delete_element( $element_id );

		if ( $deleted ) {
			$response = [
				'status'  => 200,
				'message' => 'Element ' . $element_id . ' deleted',
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error deleting the element',
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
		if ( $element_id = $queue->insert( $_POST['data'] ) ) {
			$response = [
				'status'  => 200,
				'message' => 'New element with id ' . $element_id . ' created in queue',
				'queue'   => [
					'id'         => $queue_id,
					'element_id' => $element_id,
				],
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error while inserting new element',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		}

		wp_send_json( $response );
	}

	public function edit_element() {
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
		if ( $element_id = $queue->edit_element( $_POST['data'] ) ) {
			$response = [
				'status'  => 200,
				'message' => 'Element with id ' . $element_id . ' modified',
				'queue'   => [
					'id'         => $queue_id,
					'element_id' => $element_id,
				],
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error while modifying element',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		}

		wp_send_json( $response );
	}

	public function disable_queue() {
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
		$deleted = $queue->disable( $queue_id );

		if ( $deleted ) {
			$response = [
				'status'  => 200,
				'message' => 'Queue ' . $queue_id . ' disabled',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error disabling a queue',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		}

		wp_send_json( $response );
	}

	public function get_all_elements() {
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

		$queue    = new Queue();
		$elements = $queue->get_all_elements( $queue_id );

		if ( $elements ) {
			$response = [
				'status'  => 200,
				'message' => 'Elements retrieved',
				'queue'   => [
					'id'       => $queue_id,
					'elements' => json_encode( $elements )
				],
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error retrieving elements',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		}

		wp_send_json( $response );
	}

	public function peek() {
		wp_send_json( $this->get_priority( false ) );
	}

	public function pop() {
		wp_send_json( $this->get_priority( true ) );
	}

	public function get_priority( $remove ) {
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

		$queue = new Queue();
		if ( $remove ) {
			$element = $queue->pop( $queue_id );

		} else {
			$element = $queue->peek( $queue_id );
		}

		if ( $element ) {
			$response = [
				'status'  => 200,
				'message' => 'Highest priority retrieved',
				'queue'   => [
					'id'      => $queue_id,
					'element' => json_encode( $element )
				],
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Error retrieving the highest priority',
				'queue'   => [
					'id' => $queue_id,
				],
			];
		}

		return $response;
	}
}
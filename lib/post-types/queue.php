<?php

namespace Queue\Lib\PostTypes;

if ( ! class_exists( '\Queue\Lib\PostTypes\Queue' ) ) {

	/**
	 * Class Queue
	 * @package Queue\Lib\PostTypes
	 */
	class Queue {

		public static $types = [
			'action'
		];

		/**
		 * Constructor
		 */
		public function __construct() {
			/**
			 * Attaching the custom post type callback to the init action hook
			 */
			add_action( 'init', [ $this, 'init_post_type' ] );

			/**
			 * Add metaboxes
			 */
			add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );

			/**
			 * Adds the default template folder
			 */
			add_filter( 'queue_template_folder', [
				$this,
				'default_template_folder'
			] );

			/**
			 * Saves the post data
			 */
			add_action( 'save_post_queue', [
				$this,
				'save_queue_data'
			], 10, 3 );

		}

		/**
		 * Init the custom post type
		 */
		public function init_post_type() {
			$labels = [
				'name'               => _x( 'Queue', 'post type general name', 'Queue' ),
				'singular_name'      => _x( 'Queue', 'post type singular name', 'Queue' ),
				'menu_name'          => _x( 'Queue', 'admin menu', 'Queue' ),
				'name_admin_bar'     => _x( 'Queue', 'add new on admin bar', 'Queue' ),
				'add_new'            => _x( 'Add New', 'industry resource', 'Queue' ),
				'add_new_item'       => __( 'Add New Queue', 'Queue' ),
				'new_item'           => __( 'New Queue', 'Queue' ),
				'edit_item'          => __( 'Edit Queue', 'Queue' ),
				'view_item'          => __( 'View Queue', 'Queue' ),
				'all_items'          => __( 'All Queues', 'Queue' ),
				'search_items'       => __( 'Search Queues', 'Queue' ),
				'parent_item_colon'  => __( 'Parent Queues:', 'Queue' ),
				'not_found'          => __( 'No queues found.', 'Queue' ),
				'not_found_in_trash' => __( 'No queues found in Trash.', 'Queue' )
			];

			$args = [
				'labels'              => $labels,
				'description'         => __( 'Contains queues', 'Queue' ),
				'public'              => TRUE,
				'has_archive'         => FALSE,
				'publicly_queryable'  => FALSE,
				'exclude_from_search' => TRUE,
				'show_in_rest'        => TRUE,
				'hierarchical'        => TRUE,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-list-view',
				'supports'            => [ 'title', 'page-attributes' ],
			];

			register_post_type( 'queue', $args );
		}

		/**
		 * Register meta box(es).
		 */
		public function register_meta_boxes() {
			add_meta_box( 'queue_mb_specs', __( 'Element specs', 'textdomain' ), [
				$this,
				'my_display_callback'
			], 'queue', 'normal', 'high' );
		}

		/**
		 * Meta box display callback.
		 *
		 * @param \WP_Post $post Current post object.
		 */
		public function my_display_callback( $post ) {
			include( __DIR__ . '/../../templates/metaboxes/specs.php' );
		}

		/**
		 * Adds a new Queue post.
		 *
		 * @param $queue_title
		 *
		 * @return int|\WP_Error
		 */
		public static function createQueue( $queue_title ) {
			if ( is_string( $queue_title ) ) {
				return wp_insert_post( [
					'post_title'  => wp_strip_all_tags( $queue_title ),
					'post_status' => 'publish',
					'post_type'   => 'queue',
				], TRUE );
			} else {
				return new \WP_Error( 'empty', __( 'Empty title or not a string', 'Queue' ) );
			}
		}

		/**
		 * Deletes a queue and all its children
		 *
		 * @Todo: add a "reassign children to other queue" option
		 *
		 * @param $queue
		 *
		 * @return array|false|mixed|\WP_Post
		 */
		public static function deleteQueue( $queue, $bypass_trash = FALSE ) {
			if ( is_object( $queue ) ) {
				$id = $queue->ID;
			} else {
				$id = $queue;
			}

			$args     = array(
				'post_parent' => $id,
				'post_type'   => 'queue',
				'numberposts' => - 1,
				'post_status' => 'any'
			);
			$children = get_children( $args );

			if ( ! empty( $children ) ) {
				foreach ( $children as $child_id => $child ) {
					wp_delete_post( $child_id, $bypass_trash );
				}
			}

			return wp_delete_post( $id, $bypass_trash );

		}

		/**
		 * @return \WP_Query|\WP_Error
		 */
		public static function getQueues() {
			$args = [
				'post_parent'         => 0,
				'posts_per_page'      => - 1,
				'order'               => 'ASC',
				'order_by'            => 'date',
				'post_type'           => 'queue',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => TRUE,
			];

			$query = new \WP_Query( $args );

			if ( $query->have_posts() ) {
				return $query;
			}

			return new \WP_Error( 'not found', 'No queues found' );
		}

		public static function insert( $queue, $args ) {
			if ( is_object( $queue ) ) {
				$parent_id = $queue->ID;
			} else {
				$parent_id = $queue;
			}

			if ( empty( $parent_id ) ) {
				return new \WP_Error( 'empty', 'No parent queue found.' );
			}

			$args = wp_parse_args( $args, [
				'type'     => 'action',
				'title'    => 'New element',
				'content'  => '',
				'priority' => 9999
			] );

			if ( $new_element = wp_insert_post( [
				'post_title'   => wp_strip_all_tags( $args['title'] ),
				'post_excerpt' => esc_html( $args['content'] ),
				'post_status'  => 'publish',
				'post_type'    => 'queue',
				'post_parent'  => $parent_id,
				'menu_order'   => $args['priority'],
			], TRUE )
			) {
				update_post_meta( $new_element, 'queue_element_type', $args['type'] );
				update_post_meta( $new_element, $args['type'] . '_queue_type_content', $args['content'] );
			} else {
				return new \WP_Error( 'system', 'Error while inserting new element' );
			}

			return $new_element;
		}

		/**
		 * Returns the highest priority for a specific queue
		 *
		 * Priority is defined as follow:
		 * The menu order defines the order of priority, the lower the number the higher the priority. Defaults to 9999
		 * In case two elements have the same priority, the published date is used as a factor.
		 *
		 * @param int|\WP_Post $queue
		 *
		 * @return \WP_Post|\WP_Error
		 */
		public static function peek( $queue ) {
			if ( is_object( $queue ) ) {
				$id = $queue->ID;
			} else {
				$id = $queue;
			}

			$args = [
				'post_parent'         => $id,
				'posts_per_page'      => 1,
				'order'               => 'ASC',
				'order_by'            => 'menu_order date',
				'post_type'           => 'queue',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => TRUE,
			];

			$query = new \WP_Query( $args );

			if ( $query->have_posts() ) {
				return $query->posts[0];
			}

			return new \WP_Error( 'not found', 'Queue element not found' );
		}

		/**
		 * Returns the highest priority for a specific queue and deletes the post
		 *
		 * @param $queue
		 *
		 * @return \WP_Error|\WP_Post
		 */
		public static function pop( $queue ) {
			/**
			 * \WP_Post|\WP_Error $high_priority_post
			 */
			$high_priority_post = self::peek( $queue );

			if ( ! is_wp_error( $high_priority_post ) ) {
				return wp_delete_post( $high_priority_post->ID );
			}

			return $high_priority_post;

		}

		public static function delete( $element, $bypass_trash = FALSE ) {
			if ( is_object( $element ) ) {
				$element_id = $element->ID;
			} else {
				$element_id = $element;
			}

			return wp_delete_post( $element_id, $bypass_trash );
		}

		/**
		 * Returns the default template folder location
		 *
		 * @return string
		 */
		public function default_template_folder() {
			return __DIR__ . '/../../templates';
		}

		/**
		 * Saves the data on the queue
		 *
		 * Loops through the registered types and save the relevant data, if set
		 *
		 * @param int $post_id
		 * @param \WP_Post $post
		 */
		public function save_queue_data( $post_id, $post, $update ) {
			if ( ! current_user_can( 'edit_posts' ) || ! isset( $_POST['save_queue_nonce'] ) || ! wp_verify_nonce( $_POST['save_queue_nonce'], 'save_queue_' . $post_id ) ) {
				return;
			}

			/**
			 * Check the menu_order of the post
			 * If it's 0 we change it to 9999
			 */
			if ( 0 === $post->menu_order ) {
				$my_post = array(
					'ID'         => $post_id,
					'menu_order' => 9999,
				);

				/***
				 * Un-hook the action to avoid infinite loops
				 */
				remove_action( 'save_post_queue', [
					$this,
					'save_queue_data'
				], 10, 3 );
				wp_update_post( $my_post );
				add_action( 'save_post_queue', [
					$this,
					'save_queue_data'
				], 10, 3 );
			}

			//Saves the dropdown value
			update_post_meta( $post_id, 'queue_element_type', $_POST['queue_element_type'] );

			//Saves content for different types
			$types = apply_filters( 'queue_filter_types', \Queue\Lib\PostTypes\Queue::$types );
			if ( ! empty( $types ) ) {
				foreach ( $types as $key => $type ) {
					if ( isset( $_POST[ $type . '_queue_type_content' ] ) ) {
						update_post_meta( $post_id, $type . '_queue_type_content', $_POST[ $type . '_queue_type_content' ] );
					}
				}
			}

			//LOG IT!
			$current_user = wp_get_current_user();
			Log::add( [
				'title'   => $current_user->user_login . ': queue #' . $post_id . ( ( $update ) ? ' updated' : ' created' ),
				'content' => 'Queue with id ' . $post_id . ' was ' . ( ( $update ) ? 'updated' : 'created' ) . ' by ' . $current_user->user_login
			] );
		}

	}
}

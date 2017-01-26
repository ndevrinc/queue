<?php

namespace Queue\Lib\PostTypes;

if ( ! class_exists( 'Log' ) ) {

	/**
	 * Class Log
	 * @package Queue\Lib\PostTypes
	 */
	class Log {

		/**
		 * Constructor
		 */
		public function __construct() {
			/**
			 * Attaching the custom post type callback to the init action hook
			 */
			add_action( 'init', [ $this, 'init_post_type' ] );

			/**
			 * Add capabilities
			 */
			add_action( 'admin_init', [ $this, 'add_queue_caps' ] );
		}

		/**
		 * Init the custom post type
		 */
		public function init_post_type() {
			$labels = [
				'name'               => _x( 'Log', 'post type general name', 'Queue' ),
				'singular_name'      => _x( 'Log', 'post type singular name', 'Queue' ),
				'menu_name'          => _x( 'Log', 'admin menu', 'Queue' ),
				'name_admin_bar'     => _x( 'Log', 'add new on admin bar', 'Queue' ),
				'add_new'            => _x( 'Add New', 'industry resource', 'Queue' ),
				'add_new_item'       => __( 'Add New Log', 'Queue' ),
				'new_item'           => __( 'New Log', 'Queue' ),
				'edit_item'          => __( 'Edit Log', 'Queue' ),
				'view_item'          => __( 'View Log', 'Queue' ),
				'all_items'          => __( 'All Logs', 'Queue' ),
				'search_items'       => __( 'Search Logs', 'Queue' ),
				'parent_item_colon'  => __( 'Parent Logs:', 'Queue' ),
				'not_found'          => __( 'No logs found.', 'Queue' ),
				'not_found_in_trash' => __( 'No logs found in Trash.', 'Queue' )
			];

			$args = [
				'labels'              => $labels,
				'description'         => __( 'Contains logs', 'Queue' ),
				'public'              => TRUE,
				'has_archive'         => FALSE,
				'publicly_queryable'  => FALSE,
				'exclude_from_search' => TRUE,
				'show_in_rest'        => TRUE,
				'hierarchical'        => FALSE,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-portfolio',
				'supports'            => [ 'title', 'author', 'excerpt' ],
				'capabilities'        => [
					'edit_post'          => 'edit_log',
					'edit_posts'         => 'edit_logs',
					'edit_others_posts'  => 'edit_other_logs',
					'publish_posts'      => 'publish_logs',
					'read_post'          => 'read_log',
					'read_private_posts' => 'read_private_logs',
					'delete_post'        => 'delete_log',
					'delete_posts'       => 'delete_logs',
					'create_posts'       => 'edit_log',
				],
				// as pointed out by iEmanuele, adding map_meta_cap will map the meta correctly
				'map_meta_cap'        => FALSE,
			];

			register_post_type( 'log', $args );
		}

		/**
		 * Insert a new log
		 *
		 * @param string|array $message
		 * @param string $type Can be of type info, error, success.
		 *
		 * @return int|\WP_Error
		 */
		public static function add( $message, $type = 'info' ) {
			if ( ! empty( $message ) && \is_user_logged_in() ) {
				if ( is_array( $message ) ) {
					$title   = isset( $message['title'] ) ? substr( $message['title'], 0, 40 ) : ''; //Only 40 chars
					$content = isset( $message['content'] ) ? $type . ': ' . $message['content'] : '';

				} else {
					$title   = substr( $message, 0, 40 ); //Only 40 chars
					$content = $type . ': ' . $message;
				}

				return wp_insert_post( [
					'post_title'   => wp_strip_all_tags( $title ),
					'post_excerpt' => esc_html( $content ),
					'post_status'  => 'publish',
					'post_type'    => 'log',
				], TRUE );
			} else {
				return new \WP_Error( 'empty', __( 'Empty message', 'Queue' ) );
			}
		}

		/**
		 * Returns a log object
		 *
		 * @param $id
		 *
		 * @return \WP_Query
		 */
		public static function get( $id ) {
			return new \WP_Query( [
				'p'                   => $id,
				'post_type'           => 'log',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => TRUE,
			] );
		}

		/**
		 * Adding capabilities for log post type (only admin)
		 */
		public function add_queue_caps() {
			// gets the administrator role
			$admins = get_role( 'administrator' );

			$admins->add_cap( 'edit_log' );
			$admins->add_cap( 'edit_logs' );
			$admins->add_cap( 'edit_other_logs' );
			$admins->add_cap( 'publish_logs' );
			$admins->add_cap( 'read_log' );
			$admins->add_cap( 'read_private_logs' );
			$admins->add_cap( 'delete_log' );
			$admins->add_cap( 'delete_logs' );
		}
	}
}

<?php

//Define Dirpath for hooks
define( 'DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin Name: Queue
 * Version: 1.0
 * Description: This plugin will serve as a base for a solid queue system on a WordPress installation
 * Author: Andrea Fuggetta <afuggetta@ndevr.io>
 * Author URI: https://ndevr.io
 * Plugin URI: https://ndevr.io
 * Text Domain: queue
 * Domain Path: /languages
 * @package Queue
 */


if ( ! class_exists( 'Queue' ) ) {
	/**
	 * Class Queue
	 * ToDo: add logs
	 * ToDo: change status of element while working with it
	 * ToDo: we could have a release time on peek and pop, if status has not changed when next peek or pop is requested, release is reset
	 */
	class Queue {
		public $queue;

		public function __construct() {
			$this->register_autoloader();
			$this->init();
		}

		/**
		 * Activate callback
		 */
		public static function activate() {
			//Activation code in here
		}

		/**
		 * Deactivate callback
		 */
		public static function deactivate() {
			//Deactivation code in here
			//Todo: Remove all Logs and Queue items
		}

		protected function register_autoloader() {
			/**
			 * Registering PSR-4 compliant namespaces
			 *
			 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
			 *
			 * @param string $class The fully-qualified class name.
			 *
			 * @return void
			 */
			spl_autoload_register( function ( $class ) {

				// project-specific namespace prefix
				$prefix = 'Queue\\Lib\\';

				// base directory for the namespace prefix
				$base_dir = __DIR__ . '/lib/';

				// does the class use the namespace prefix?
				$len = strlen( $prefix );
				if ( strncmp( $prefix, $class, $len ) !== 0 ) {
					// no, move to the next registered autoloader
					return;
				}

				// get the relative class name
				$relative_class = ltrim( strtolower( preg_replace( '/\B[A-Z]/', '-$0', substr( $class, $len ) ) ), '-' );

				// replace the namespace prefix with the base directory, replace namespace
				// separators with directory separators in the relative class name, append
				// with .php
				$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

				// if the file exists, require it
				if ( file_exists( $file ) ) {
					require $file;
				}

				return;
			} );
		}

		protected function init() {
			$this->set_actions();
			$this->setup_content_model();
		}

		protected function set_actions() {
			add_action( 'admin_menu', function () {
				add_options_page( 'Queue Options', 'Queue Plugin', 'manage_options', 'queue', [
					$this,
					'queue_admin_options'
				] );
			} );

			add_action( 'admin_notices', function () {
				$class = 'queue-notice notice is-dismissible hidden';
				printf( '<div class="%1$s"><p></p></div>', $class );
			} );

			add_action( 'admin_enqueue_scripts', function () {

				wp_register_script( 'queue-js', plugins_url( 'queue/assets/js', dirname( __FILE__ ) ) . '/build/queue.min.js' );
				wp_localize_script( 'queue-js', 'wpApiSettings', [ 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ) ] );
				wp_enqueue_script( 'queue-js' );

				wp_enqueue_style( 'custom-admin-css', plugins_url( 'queue/assets/css', dirname( __FILE__ ) ) . '/custom-admin.css' );
			} );

			//Main plugin hooks
			register_activation_hook( DIR_PATH, [
				'Queue',
				'activate'
			] );
			register_deactivation_hook( DIR_PATH, [
				'Queue',
				'deactivate'
			] );
		}

		/**
		 * Fetches content model and initializes the app
		 */
		public function setup_content_model() {
			$content_model = new \Queue\Lib\ContentModel();

			$objects_to_load = array_merge(
				$content_model->getPostTypes(),
				$content_model->getHelper()
			);

			foreach ( $objects_to_load as $object ) {
				new $object;
			}
		}

		/**
		 * Load view for the settings page.
		 */
		public function queue_admin_options() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.', 'queue' ) );
			}

			$template_folder = apply_filters( 'queue_template_folder', NULL );
			include( $template_folder . '/admin/options_page.php' );

		}
	}

	new Queue();
}

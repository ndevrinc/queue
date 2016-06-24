<?php

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

class Queue {
	public $queue;

	public function __construct() {
		$this->register_autoloader();
		$this->init();
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
			$relative_class = substr( $class, $len );

			// replace the namespace prefix with the base directory, replace namespace
			// separators with directory separators in the relative class name, append
			// with .php
			$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

			// if the file exists, require it
			if ( file_exists( $file ) ) {
				require $file;
			}
		} );
	}

	protected function init() {
		$async = new \Queue\Lib\Async();
		$async->init();
		$this->set_actions();
		$this->queue = new \Queue\Lib\Queue(); //Initializing, we could use this object instead of AJAX
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
			wp_enqueue_script( 'queue', plugins_url( 'queue/assets/js', dirname( __FILE__ ) ) . '/build/queue.min.js', [ ], false, true );
		} );
	}

	public function queue_admin_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'queue' ) );
		}

		?>
		<div class="wrap">
			<h2>Queue Plugin</h2>
			<form method="post" action="options.php">
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Queues</th>
						<?php
						$queues = $this->queue->get_all();
						?>
						<td>
							<select name="queues" id="queues">
								<option value="0" selected>Select queue</option>
								<?php
								if ( ! empty( $queues ) ):
									foreach ( $queues as $queue ) {
										?>
										<option
											value="<?php echo absint( $queue->id ); ?>"><?php echo esc_html( 'Queue #' . $queue->id ); ?></option>
										<?php
									}
								endif;
								?>
							</select>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">Create a new queue</th>
						<td>
							<input type="button" id="add-queue" class="button button-primary" value="Add Queue">
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">Delete a queue</th>
						<td>
							<input type="button" id="delete-queue" class="button button-primary" value="Delete">
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">Check if empty queue</th>
						<td>
							<input type="button" id="is-empty-queue" class="button button-primary" value="Is Empty?">
						</td>
					</tr>

					<!--TBD-->
					<!--<tr valign="top">
						<th scope="row">Disable queue</th>
						<td>
							<input type="button" id="disable-queue" class="button button-primary" value="Disable">
						</td>
					</tr>-->
				</table>

				<h3>- Insert/Edit element -</h3>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">ID (Read only)</th>
						<td>
							<input type="text" name="element_id" id="element_id" value="" disabled/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Type</th>
						<td>
							<select name="element_types" id="element_types">
								<option value="0" selected>Select element type</option>
								<?php
								$elements_type = new \Queue\Lib\Element_Type();
								foreach ( $elements_type->get_types() as $name => $id ) {
									?>
									<option
										value="<?php echo absint( $id ); ?>"><?php echo esc_html( $name ); ?></option>
									<?php
								}
								?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Name</th>
						<td>
							<input type="text" name="element_name" id="element_name" value="Default name"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Priority</th>
						<td>
							<input type="number" name="element_priority" id="element_priority" value="0"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Data (select type to show)</th>
						<td>
							<textarea name="element_data[script]" id="element_data[script]" cols="50" rows="3"
							          class="hidden"></textarea>
							<input type="text" name="element_data[text]" id="element_data[text]" class="hidden">
						</td>
					</tr>
					<tr valign="top">
						<th>
							<input type="button" id="peek-element" class="button button-primary" value="Peek element">
						</th>
					</tr>
					<tr valign="top">
						<th>
							<input type="button" id="pop-element" class="button button-primary" value="Pop element">
						</th>
					</tr>
					<tr valign="top">
						<th>
							<input type="button" id="insert-element" class="button button-primary"
							       value="Insert element">
						</th>
					</tr>
					<tr valign="top">
						<th>
							<input type="button" id="edit-element" class="button button-primary" value="Edit element"
							       disabled>
						</th>
					</tr>
					<tr valign="top">
						<th>
							<input type="button" id="delete-element" class="button button-primary"
							       value="Delete element" disabled>
						</th>
					</tr>
				</table>
				<h3>- Elements in selected queue -</h3>
				<table class="form-table">
					<tr valign="top">
						<th>
							<select name="queue_elements" id="queue_elements" multiple="multiple" size="5"
							        style="min-width: 200px;">
							</select>
						</th>
					</tr>
				</table>
			</form>
		</div>
		<?php
	}
}

new Queue();
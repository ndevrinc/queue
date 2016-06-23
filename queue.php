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


/**
 * Registering PSR-4 compliant namespaces
 * TODO: Move this into a class?
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

$async = new \Queue\Lib\Async();
$async->init();

add_action( 'admin_menu', function () {
	add_options_page( 'Queue Options', 'Queue Plugin', 'manage_options', 'queue', 'queue_admin_options' );
	add_action( 'admin_init', function() {
		register_setting( 'queue-option-group', 'new_option_name' );
		register_setting( 'queue-option-group', 'some_other_option' );
		register_setting( 'queue-option-group', 'option_etc' );
		register_setting( 'queue-option-group', 'option_etc' );
	} );
	add_action( 'admin_notices', function() {
		$class = 'queue-notice notice is-dismissible hidden';
		printf( '<div class="%1$s"><p></p></div>', $class );
	} );
} );

add_action( 'admin_enqueue_scripts', function() {
	wp_enqueue_script( 'api', plugins_url( 'queue/assets/js', dirname(__FILE__) ) . '/src/api.js', [], false, true );
} );

//add_action( 'wp_ajax_add_queue', 'my_action_callback' );

function my_action_callback() {
	global $wpdb; // this is how you get access to the database

	$whatever = intval( $_POST['whatever'] );

	$whatever += 10;

	echo $whatever;

	wp_die(); // this is required to terminate immediately and return a proper response
}

function queue_admin_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	\Queue\Lib\Database::init();

	?>
	<div class="wrap">
		<h2>Queue Plugin</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'queue-option-group' ); ?>
			<?php do_settings_sections( 'queue-option-group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Queues</th>
					<?php
					$queues = \Queue\Lib\Database::get_queues();
					?>
					<td>
						<select name="queues" id="queues">
							<option value="0" selected>Select queue</option>
							<?php
							if(!empty($queues)):
								foreach ( $queues as $queue ) {
									?>
									<option value="<?php echo absint( $queue->id ); ?>"><?php echo esc_html( 'Queue #' . $queue->id ); ?></option>
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
						<input type="button" id="is-empty-queue" class="button button-primary" value="Delete">
					</td>
				</tr>

				<!--<tr valign="top">
					<th scope="row">New Option Name</th>
					<td><input type="text" name="new_option_name" value="<?php /*echo esc_attr( get_option('new_option_name') ); */?>" /></td>
				</tr>

				<tr valign="top">
					<th scope="row">Some Other Option</th>
					<td><input type="text" name="some_other_option" value="<?php /*echo esc_attr( get_option('some_other_option') ); */?>" /></td>
				</tr>

				<tr valign="top">
					<th scope="row">Options, Etc.</th>
					<td><input type="text" name="option_etc" value="<?php /*echo esc_attr( get_option('option_etc') ); */?>" /></td>
				</tr>-->
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
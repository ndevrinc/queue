<?php
$types = apply_filters( 'queue_filter_types', \Queue\Lib\PostTypes\Queue::$types );

$type_selected = get_post_meta( $post->ID, 'queue_element_type', TRUE );
?>
    <label class="screen-reader-text" for="queue_element_type">Type</label>
    <select name="queue_element_type" id="queue_element_type" class="">
        <option value="">Select element type</option>
		<?php
		if ( ! empty( $types ) ) :
			foreach ( $types as $key => $type ) :
				?>
                <option value="<?php echo esc_html( $type ); ?>" <?php selected( $type_selected, esc_html( $type ) ); ?>><?php echo esc_html( ucfirst( esc_html( $type ) ) ); ?></option>
				<?php
			endforeach;
		endif;
		?>
    </select>

<?php
/**
 * - Get the default template folder
 * - For each type we try to show the relative content template
 */
$template_folder = apply_filters( 'queue_template_folder', NULL );
foreach ( $types as $key => $type ) :
	?>
    <div id="<?php echo strtolower( esc_html( $type ) ); ?>-queue-type">
		<?php include( $template_folder . '/' . strtolower( esc_html( $type ) ) . '.php' ); ?>
    </div>
	<?php
endforeach;

wp_nonce_field( 'save_queue_' . $post->ID, 'save_queue_nonce' );

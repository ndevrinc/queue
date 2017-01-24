<?php

/**
 * Template page for the settings
 */

?>

<div class="wrap">
    <h2>Queue Plugin</h2>
    <form method="post" action="options-general.php?page=queue">
		<?php wp_nonce_field( 'queue_option_page_nonce', 'options_queue' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Queues</th>
				<?php
				$queues = \Queue\Lib\PostTypes\Queue::getQueues();
				if ( ! is_wp_error( $queues ) ) :
					?>
                    <td>
                        <select name="queues" id="queues">
                            <option value="0" selected>Select queue
                            </option>
							<?php
							while ( $queues->have_posts() ) : $queues->the_post();
								?>
                                <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>
								<?php
							endwhile;
							wp_reset_postdata();
							?>
                        </select>
                    </td>
                    <td>
                        <input type="submit" name="submit" id="submit"
                               class="button button-primary button-large"
                               value="Retrieve">
                    </td>
					<?php
				endif;
				?>
            </tr>

			<?php
			if ( ! empty( $_POST['queues'] ) && !empty ( $_POST['options_queue'] ) && wp_verify_nonce( $_POST['options_queue'], 'queue_option_page_nonce' ) ) :
				$peek = \Queue\Lib\PostTypes\Queue::peek( $_POST['queues'] );
				if ( ! is_wp_error( $peek ) ) :
					?>
                    <tr valign="top">
                        <th scope="row">Highest priority element</th>
                        <th>
                            ID
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Priority
                        </th>
                        <th>
                            Link
                        </th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo $peek->ID; ?></td>
                        <td><?php echo get_post_meta( $peek->ID, 'queue_element_type', TRUE ); ?></td>
                        <td><?php echo $peek->menu_order; ?></td>
                        <td><?php edit_post_link( 'Edit element', '', '', $peek->ID ); ?></td>
                    </tr>
					<?php
				endif;
			endif;
			?>
    </form>
</div>

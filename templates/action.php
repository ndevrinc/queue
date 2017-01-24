<?php
/**
 * Template for the action queue type.
 *
 * This only needs the input html since the content will be wrapped automatically \
 * in a specific div which will show and hide depending on what's selected in the dropdown
 */


//Getting the value, if previously set
$action = get_post_meta( $post->ID, 'action_queue_type_content', TRUE );
?>
<input type="text" name="action_queue_type_content"
       value="<?php echo esc_html( $action ); ?>"
       id="action_queue_type_content"
       spellcheck="true" autocomplete="off">

<?php
/**
 * Removes category selection from post edit form, for default category see {@link [activate.php]}.
 */
function bcb_remove_category_metabox(){
    remove_meta_box('categorydiv', 'post', 'side');
}

add_action( 'edit_form_after_title', 'bcb_remove_category_metabox' );
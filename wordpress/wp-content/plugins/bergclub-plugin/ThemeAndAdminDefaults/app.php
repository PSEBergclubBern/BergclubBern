<?php
//remove category selection from post edit form, default category: see activate.php
function bcb_remove_category_metabox(){
    remove_meta_box('categorydiv', 'post', 'side');
}

add_action( 'edit_form_after_title', 'bcb_remove_category_metabox' );

function bcb_impressum(){
    return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'page');
}

\BergclubPlugin\TagHelper::addTag('bcb_impressum', 'bcb_impressum');
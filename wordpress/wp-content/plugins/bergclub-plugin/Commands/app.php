<?php

//disable outgoing mails when called from command line (prevent error message because SERVER_NAME is not set.
add_filter( 'wp_mail', 'bcb_mail_filter' );
function bcb_mail_filter( $args ) {
    if(!isset($_SERVER['SERVER_NAME'])) {
        return array(
            'to' => '',
            'subject' => $args['subject'],
            'message' => $args['message'],
            'headers' => $args['headers'],
            'attachments' => $args['attachments'],
        );
    }

    return $args;
}
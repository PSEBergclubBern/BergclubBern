<?php

/**
 * @param $type
 * @param $message
 * @param bool $closeable
 */
function bcb_add_notice($type, $message, $closeable = false, $admin = false)
{
    $_SESSION['bcb_template_flashmessages'][] = [
        'type' => $type,
        'message' => $message,
        'closeable' => $closeable,
        'admin' => $admin,
    ];
}

/**
 *
 */
function bcb_show_notice()
{
    $html = "";
    if (isset($_SESSION['bcb_template_flashmessages'])) {
        foreach ($_SESSION['bcb_template_flashmessages'] as $flashMessage) {
            if ($flashMessage['admin']) {
                if ($flashMessage['type'] == 'danger') {
                    $flashMessage['type'] = 'error';
                }
                $html .= '<div class="notice notice-' . $flashMessage['type'];
                if ($flashMessage['closeable']) {
                    $html .= ' is-dismissible';
                }
                $html .= '"><p>' . $flashMessage['message'] . '</p></div>';
            } else {
                $html .= '<div class="alert alert-' . $flashMessage['type'];
                if ($flashMessage['closeable']) {
                    $html .= ' alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                } else {
                    $html .= '">';
                }
                $html .= '<p>' . $flashMessage['message'] . '</p></div>';
            }
        }
    }

    unset($_SESSION['bcb_template_flashmessages']);

    echo $html;
}
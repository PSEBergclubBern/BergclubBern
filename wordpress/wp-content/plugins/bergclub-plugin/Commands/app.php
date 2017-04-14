<?php

//disable email notification when called from command line (prevent error message because SERVER_NAME is not set.
if(!isset($_SERVER['SERVER_NAME'])) {
    function wp_mail()
    {

    }
}
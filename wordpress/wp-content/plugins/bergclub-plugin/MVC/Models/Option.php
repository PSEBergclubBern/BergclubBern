<?php
namespace BergclubPlugin\MVC\Models;

/**
 * A wrapper for the WordPress option.
 *
 * Class Option
 * @package BergclubPlugin\MVC\Models
 */
class Option extends AbstractKeyValuePair
{
    protected static $wpUpdateMethod = "update_option";
    protected static $wpDeleteMethod = "delete_option";
    protected static $wpGetMethod = "get_option";
}
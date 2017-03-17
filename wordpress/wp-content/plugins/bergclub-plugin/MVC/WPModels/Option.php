<?php
namespace BergclubPlugin\MVC\WPModels;

/**
 * A wrapper for the WordPress option.
 *
 * Class Option
 * @package BergclubPlugin\MVC\WPModels
 */
class Option extends AbstractKeyValuePair
{
    protected static $wpUpdateMethod = "update_option";
    protected static $wpDeleteMethod = "delete_option";
    protected static $wpGetMethod = "get_option";
}
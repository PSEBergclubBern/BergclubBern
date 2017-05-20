<?php

/**
 * Returns true if the first part of the hostname is 'jugend'.
 * @return bool true if website is jugend, false otherwise
 */
function bcb_is_jugend()
{
    $hostParts = explode('.', $_SERVER['SERVER_NAME']);
    return $hostParts[0] == 'jugend';
}

/**
 * Returns the url for the jugend homepage.
 * @return string url of the jugend homepage.
 */
function bcb_jugend_home()
{
    return '//' . bcb_jugend_host() . '/';
}

/**
 * Determines if the current hostname has not already 'jugend' as first part.
 * If not it adds 'jugend' as firts part.
 * @return string the jugend hostname.
 */
function bcb_jugend_host()
{
    if (!bcb_is_jugend()) {
        return 'jugend.' . $_SERVER['SERVER_NAME'];
    }
    return $_SERVER['SERVER_NAME'];
}

/**
 * Determines if the current hostname has 'jugend' as first part.
 * If so, it removes 'jugend' as first part.
 * @return string the bcb hostname.
 */
function bcb_host()
{
    $hostParts = explode('.', $_SERVER['SERVER_NAME']);
    if ($hostParts[0] == 'jugend') {
        unset($hostParts[0]);
        return join('.', $hostParts);
    }

    return $_SERVER['SERVER_NAME'];
}

/**
 * Determines if the given url is a jugend url or not
 * @param string $url url to check
 * @return bool true if the given url is a jugend url, false otherwise
 */
function bcb_is_jugend_url($url)
{
    $parsedURL = parse_url($url);
    if (isset($parsedURL['host'])) {
        $arr = explode('.', $parsedURL['host']);
        if ($arr[0] == 'jugend') {
            return true;
        }
    }
    return false;
}

/**
 * Checks if the given url is a jugend url.
 * If not it adds jugend as the first part of the hostname and returns the modified url.
 * @param string $url the url which should be cheanged to be a jugend url
 * @param boolean $onlyIfJugend If set to true, the url will only be modified if the actual viewed page is a jugend page
 * @return string the modified url (if the given url is not already a jugend url)
 */
function bcb_add_jugend_to_url($url, $onlyIfJugend = false)
{
    if (!bcb_is_jugend_url($url) && (!$onlyIfJugend || bcb_is_jugend())) {
        $parsedURL = parse_url($url);
        if (!empty($parsedURL)) {
            if (!isset($parsedURL['path'])) {
                $parsedURL['path'] = '/';
            }
            $url = '//' . bcb_jugend_host() . $parsedURL['path'];

            if (isset($parsedURL['query'])) {
                $url .= '?' . $parseURL['query'];
            }
        } else {
            $url = '//' . bcb_jugend_host() . '/';
        }
    }

    return $url;
}

function bcb_permalink($url)
{
    return bcb_add_jugend_to_url($url, true);
}

add_filter('the_permalink', 'bcb_permalink', 10, 1);

/**
 * Ensures that the menu has jugend urls if the current visited page is a jugend page.
 * @param array $atts see {@link [https://codex.wordpress.org/Plugin_API/Filter_Reference/nav_menu_link_attributes] [Plugin API/Filter Reference/nav menu link attributes]}
 */
function bcb_nav_menu_link_attributes($atts)
{
    if (bcb_is_jugend()) {
        $atts['href'] = bcb_add_jugend_to_url($atts['href']);
    }
    return $atts;
}

add_filter('nav_menu_link_attributes', 'bcb_nav_menu_link_attributes', 10, 1);

<?php
use BergclubPlugin\MVC\Models\Option;

/**
 *  remove all the entries from WP options table
 */

Option::remove('mitgliederbeitraege');
$tourenarten = Option::get('tourenarten');
foreach ($tourenarten as $key => $value) {
    Option::remove($key);
}

Option::remove('tourenarten');

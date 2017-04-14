<?php
use BergclubPlugin\MVC\Models\Option;


Option::remove('mitgliederbeitraege');
$tourenarten = Option::get('tourenarten');
foreach($tourenarten as $key => $value){
    Option::remove($key);
}

Option::remove('tourenarten');

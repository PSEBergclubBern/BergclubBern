<?php
delete_option('bcb_mitgliederbeitraege');

$tourenarten = get_option('bcb_tourenarten');
foreach($tourenarten as $key => $value){
    delete_option($key);
}

delete_option('bcb_tourenarten');
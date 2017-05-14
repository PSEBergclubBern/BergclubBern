<?php
use BergclubPlugin\MVC\Models\Role;

require_once 'activate_functionary_roles.php';

foreach($functionaryRoles as $slug => $item){
    remove_role($slug);
}
?>
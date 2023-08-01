<?php

require_once 'dbquery.php';

$admins = "SELECT * FROM administrator;";

switch($query)
{
    case "admins":
        DBquery("SELECT SLEEP(3);");
        print_r(DBquery($admins));
    default:
        print_r(json_encode((object) null));
}
?>

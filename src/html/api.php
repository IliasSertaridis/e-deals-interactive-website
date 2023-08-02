<?php

require_once 'dbquery.php';

$admins = "SELECT * FROM administrator;";

switch($query)
{
    case "admins":
        DBquery("SELECT SLEEP(3);");
        $result = DBquery($admins);
        break;
    default:
        $result = json_encode((object) null);
        break;
}
$json = json_encode($result);
echo $json;
?>

<?php
session_start();
require_once 'dbquery.php';
$result = array();
if(isset($_SESSION['user_type']))
{
    $result = DBQuery("SELECT uuid, name FROM category;");
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>

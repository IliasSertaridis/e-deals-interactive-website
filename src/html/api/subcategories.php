<?php
session_start();
require_once 'dbquery.php';

$result = array();
if(isset($_SESSION['user_type']))
{
    if (isset($_GET['category']))
    {
        $result = DBQuery("SELECT subcategory.name AS name FROM subcategory INNER JOIN category ON subcategory.belongs_to = category.uuid WHERE category.name = '" . $_GET['category'] . "';");
    }
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>

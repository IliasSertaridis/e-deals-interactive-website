<?php
session_start();
require_once 'dbquery.php';
$result = array();
if(isset($_SESSION['user_type']))
{
    $query = "SELECT item.item_id, item.name, subcategory.name AS subcategory, category.name AS category FROM item INNER JOIN subcategory ON item.belongs_to = subcategory.uuid INNER JOIN category ON subcategory.belongs_to = category.uuid;";
    if (isset($_GET['name'])) {
        $query = substr_replace($query, " WHERE item.name LIKE '" . $_GET['name'] . "%';", -1); 
    }
    $result = DBQuery($query);
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>

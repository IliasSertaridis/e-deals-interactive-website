<?php

header("Cache-Control: no-store");
session_start();
require_once 'dbquery.php';

if (isset($_SESSION['username']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'administrator')
{
    if ($_SERVER['REQUEST_URI'] == "/api/admin/items/delete") {
        $response1 = DBQuery("DELETE FROM item;");
        $response2 = DBQuery("DELETE FROM category;");
        $response3 = DBQuery("DELETE FROM subcategory;");
        $response4 = DBQuery("DELETE FROM price;");
        if($response1 && $response2 && $response3) {
            echo "SUCCESS";
        }
        else {
            echo "ERROR";
        }
    }
    else if($_SERVER['REQUEST_URI'] == "/api/admin/stores/delete") {
        $response = DBQuery("DELETE FROM store;");
        if($response) {
            echo "SUCCESS";
        }
        else {
            echo "ERROR";
        }
    }
}
?> 

<?php

require_once 'dbquery.php';

if ($_SERVER['REQUEST_URI'] == "/admin/items/delete") {
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
else if($_SERVER['REQUEST_URI'] == "/admin/stores/delete") {
    $response = DBQuery("DELETE FROM store;");
    if($response) {
        echo "SUCCESS";
    }
    else {
        echo "ERROR";
    }
}
?> 

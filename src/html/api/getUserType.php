<?php
session_start();	
$response = array();
require_once 'dbquery.php';
try {
    if (isset($_SESSION['user_type']))
    {
        $response['user_type'] = $_SESSION['user_type'];
    }
}
catch (Exception $e) {
    $status = 0;
}
echo json_encode($response);
?>

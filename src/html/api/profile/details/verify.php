<?php
header("Cache-Control: no-store");
session_start();
$response = array();
$status = 0;
require_once 'dbquery.php';
    $result = DBQuery("SELECT password FROM user WHERE email = '" . $_SESSION['email'] . "';");
    if (!isset($result[0]['password']) || $result[0]['password'] != $_POST['password'])
    {
        $status = 0;
    }
    else
    {
        $status = 1;
    }
$response['status'] = $status;
echo json_encode($response);
?>

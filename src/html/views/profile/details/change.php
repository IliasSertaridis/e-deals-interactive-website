<?php
session_start();
$response = array();
$status = 0;
$username = 0;
$password = 0;
require_once 'dbquery.php';
try {
    $count = 0;
    if($_POST['username'] != '') {
        $username = DBQuery("UPDATE user SET username = '" . $_POST['username'] . "' WHERE email = '" . $_SESSION['email'] . "';");
        $count += 1;
    }
    if($_POST['password'] != '') {
        $password = DBQuery("UPDATE user SET password = '" . $_POST['password'] . "' WHERE email = '" . $_SESSION['email'] . "';");
        $count += 1;
    }
    if($username + $password == $count)
    {
        $status = 1;
    }
}
catch (Exception $e) {
    $status = 0;
}
$response['status'] = $status;
echo json_encode($response);
?>

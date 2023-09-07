<?php
header("Cache-Control: no-store");
session_start();	
$response = array();
$status = 0;
require_once 'dbquery.php';
try {
    $result = DBQuery("SELECT username, email, password, user_type FROM user WHERE username = '" . $_POST['username'] . "';");
    if (!isset($result[0]['password']) || $result[0]['password'] != $_POST['password'])
    {
        $status = 0;
    }
    else 
    {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['email'] = $result[0]['email'];
        $_SESSION['user_type'] = $result[0]['user_type'];
        if($result[0]['user_type'] == 'user') {
            $status = 1;
        }
        else {
            $status = 2;
        }
    }
}
catch (Exception $e) {
    $status = 0;
}
$response['status'] = $status;
echo json_encode($response);
?>

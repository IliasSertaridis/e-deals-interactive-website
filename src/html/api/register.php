<?php

require_once 'dbquery.php';
$response = array();
$status = 0;
try {
    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        if(DBQuery("INSERT INTO user VALUES ('" . $_POST['email'] . "','" . $_POST['username'] . "','" . $_POST['password'] . "',0,0,0,0,'user');")) {
            $status = 1;
        }
    }
}
catch (Exception $e) {
    $status = 0;
}
$response['status'] = $status;
echo json_encode($response);
?>

<?php
session_start();	
if($_SERVER['REQUEST_METHOD'] == "GET") {
    require_once __DIR__.'/login.html';
    if (isset($_SESSION['username']) && isset($_SESSION['user_type']))
    {
        if ($_SESSION['user_type'] == 'user') {
            header("Location: profile");
        }
        else {
            header("Location: admin");
        }
    }
}
else {
    $response = array();
    $status = 0;
    require_once 'dbquery.php';
    try {
        $result = DBQuery("SELECT username, email, password, 'user' AS user_type FROM user WHERE username = '" . $_POST['username'] . "' UNION SELECT username, email, password, 'administrator' AS user_type FROM administrator WHERE username = '" . $_POST['username'] . "';");
        if (!isset($result[0]['password']) || $result[0]['password'] != $_POST['password'])
        {
            $status = 0;
        }
        else if($result[0]['user_type'] == 'user')
        {
            $status = 1;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['user_type'] = $result[0]['user_type'];
        }
        else //user_type == administrator
        {
            $status = 2;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['user_type'] = $result[0]['user_type'];
        }
    }
    catch (Exception $e) {
        $status = 0;
    }
    $response['status'] = $status;
    echo json_encode($response);
}
?>

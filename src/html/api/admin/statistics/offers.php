<?php
session_start();
require_once 'dbquery.php';

$result = array();
if($_SESSION['user_type'] == 'administrator') {
    try {
        $result = DBQuery("SELECT count(*) as count, registration_date FROM offer GROUP BY registration_date ORDER BY registration_date DESC;");
    }
    catch (Exception $e) {
        $result = ((object) null);
    }
}
$response = json_encode($result, JSON_UNESCAPED_UNICODE);
echo $response;
?>

<?php
header("Cache-Control: no-store");
session_start();
require_once 'dbquery.php';
$result = array();
try {
    $result = DBQuery("SELECT current_score, total_score, last_month_tokens, total_tokens FROM user WHERE username = '" . $_SESSION['username'] . "';");
}
catch (Exception $e) {
    $result = ((object) null);
}
$response = json_encode($result, JSON_UNESCAPED_UNICODE);
echo $response;
?>

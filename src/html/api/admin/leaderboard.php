<?php
header("Cache-Control: no-store");
session_start();
require_once 'dbquery.php';
if($_SESSION['user_type'] == 'administrator') {
    $result = array();
    try {
        $result = DBQuery("SELECT ROW_NUMBER() OVER() AS position, username, total_score, current_score, total_tokens, last_month_tokens FROM user ORDER BY total_score DESC;");
    }
    catch (Exception $e) {
        $result = ((object) null);
    }
}
$response = json_encode($result, JSON_UNESCAPED_UNICODE);
echo $response;
?>

<?php
require_once 'dbquery.php';
$response = array();
if($_SESSION['user_type'] == 'administrator' && isset($_GET['offer_id'])) {
    DBQuery("DELETE FROM offer WHERE offer_id = " . $_GET['offer_id'] . ";");
    $response['status'] = 1;
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>


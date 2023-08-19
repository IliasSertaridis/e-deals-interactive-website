<?php
session_start();
require_once 'dbquery.php';
$response = array();
try
{
    if($_SESSION['user_type'] == 'administrator' && isset($_POST['offer_id']))
    {
        DBQuery("DELETE FROM offer WHERE offer_id = " . $_POST['offer_id'] . ";");
        $response['status'] = 1;
    }
}
catch (Exception $e)
{
    $response['status'] = 0;
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>

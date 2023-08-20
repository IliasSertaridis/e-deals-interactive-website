<?php
header("Cache-Control: no-store");
session_start();
require_once 'dbquery.php';
$result = array();
try {
    $result = DBQuery("SELECT review.review_id, offer.uploader_username, item.name AS item_name, review.rating FROM review INNER JOIN offer on review.offer_id = offer.offer_id INNER JOIN item on offer.item_id = item.item_id WHERE review.user_username = '" . $_SESSION['username'] . "';");
}
catch (Exception $e) {
    $result = ((object) null);
}
$response = json_encode($result, JSON_UNESCAPED_UNICODE);
echo $response;
?>

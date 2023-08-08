<?php
session_start();
require_once 'dbquery.php';
$result = array();
try {
    $result = DBQuery("SELECT offer.offer_id, offer.item_name, offer.price, store.name as store_name, offer.number_of_likes, offer.number_of_dislikes, offer.registration_date, offer.expiration_date FROM offer INNER JOIN store on offer.store_id = store.store_id WHERE offer.uploader_username = '" . $_SESSION['username'] . "';");
}
catch (Exception $e) {
    $result = ((object) null);
}
$response = json_encode($result, JSON_UNESCAPED_UNICODE);
echo $response;
?>

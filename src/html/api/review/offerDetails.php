<?php
require_once 'dbquery.php';
require_once 'offerEvaluation.php';

header("Cache-Control: no-store");

if(isset($_GET['store_id']) && isset($_GET['name'])) {
    $results = DBQuery("SELECT item.item_id AS item_id, item.name AS name, item.photo AS photo, offer.offer_id AS offer_id, offer.price AS price, offer.registration_date AS registered, offer.expiration_date AS expires, offer.number_of_likes AS likes, offer.number_of_dislikes AS dislikes, offer.in_stock AS in_stock, user.username AS username, user.total_score AS total_score FROM item, offer, user WHERE offer.store_id = '" .$_GET['store_id']. "' AND offer.item_id = item.item_id AND item.name = '" .$_GET['name']. "' AND offer.uploader_username = user.username;");
    foreach($results as &$result) {
        $result["hot_daily"] = last_days_over_20_discount($result['price'],$result['item_id']);
        $result["hot_weekly"] = last_weeks_over_20_disc($result['price'],$result['item_id']);
    }
    $json = json_encode($results, JSON_UNESCAPED_UNICODE);
    echo $json;
} else{
    $json = json_encode((object) null);
    echo $json;
}
?>

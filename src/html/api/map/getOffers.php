<?php
header("Cache-Control: no-store");
require_once 'dbquery.php';
require_once 'offerEvaluation.php';

if(isset($_GET['store_id'])) {
    $results = DBQuery("SELECT item.item_id AS item_id, offer.offer_id AS offer_id, item.name AS name, offer.price AS price, offer.registration_date AS registered, offer.expiration_date AS expires, offer.number_of_likes AS likes, offer.number_of_dislikes AS dislikes, offer.in_stock AS in_stock FROM item, offer WHERE offer.store_id = '" .$_GET['store_id']. "' AND offer.item_id = item.item_id;");
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

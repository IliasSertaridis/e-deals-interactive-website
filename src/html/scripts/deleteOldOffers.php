<?php
require_once '/var/www/html/dbquery.php';
require_once '/var/www/html/offerEvaluation.php';

$responses=DBQuery("SELECT offer_id,item_id,price FROM offer WHERE expiration_date < CURRENT_DATE;");
$delete_ids = array();
$update_ids = array();
foreach($responses as $response){
    if(offer_eval($response['price'],$response['item_id'])==0){
        array_push($delete_ids, $response['offer_id']);
    }
    else {
        array_push($update_ids, $response['offer_id']);
    }
}
$deleteQuery = "DELETE FROM offer WHERE offer_id = ";
$updateQuery = "UPDATE offer SET expiration_date = DATE_ADD(CURRENT_DATE, INTERVAL 1 week) WHERE offer_id = ";
foreach($delete_ids as $id) {
    $deleteQuery += $id . " OR ";
}
foreach($update_ids as $id) {
    $updateQuery += $id . " OR ";
}
$deleteQuery = substr_replace($deleteQuery,";",-4);
$updateQuery = substr_replace($updateQuery,";",-4);
if ($deleteQuery != "DELETE FROM offer WHERE offer_i;")
{
    DBQuery($deleteQuery);
}
if ($updateQuery != "UPDATE offer SET expiration_date = DATE_ADD(CURRENT_DATE, INTERVAL 1 week) WHERE offer_i;")
{
    DBQuery($updateQuery);
}
?>

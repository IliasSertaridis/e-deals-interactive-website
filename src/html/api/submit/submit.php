<?php
require_once 'dbquery.php';
session_start();

$result = array();

if(isset($_SESSION) && isset($_POST['store_id']) && isset($_POST['item_id']) && isset($_POST['price'])) {
    $result = SubmitOffer($_POST['store_id'], $_POST['item_id'], $_POST['price']);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
#$result = SubmitOffer(1, 1, 2.0);
#echo json_encode($result, JSON_UNESCAPED_UNICODE);

function last_days_over_20_discount($price,$itemID){
    $r=DBQuery("SELECT price.price AS average_daily_price FROM item INNER JOIN price ON item.name = price.item_name WHERE item.item_id = " . $itemID ." and price.date = CURRENT_DATE;");
    if($r == 0) {
        return true;
    }
    $last_days_mid_price=$r[0]['average_daily_price'];
    if($last_days_mid_price-($last_days_mid_price*2/10)>$price){
        return true;
    }
    else {
        return false;
    }
}
function last_weeks_over_20_disc($price,$itemID){
    $r=DBQuery("SELECT WEEK(price.date) AS week, AVG(price.price) AS average_weekly_price FROM item INNER JOIN price ON item.name = price.item_name WHERE item.item_id = " . $itemID . " GROUP BY week ORDER BY week limit 1;");
    if($r == 0) {
        return true;
    }
    $last_weeks_mid_price= $r[0]['average_weekly_price'];
    if($last_weeks_mid_price-($last_weeks_mid_price*2/10)>$price){
        return true;
    }
    else{
        return false;
    }
}

function offer_eval($price,$itemID){
    if(last_days_over_20_discount($price,$itemID) && last_weeks_over_20_disc($price,$itemID)){
        return 70;
    }
    else if(last_days_over_20_discount($price,$itemID)){
        return 50;
    }
    else if(last_weeks_over_20_disc($price,$itemID)){
        return 20;
    }
    else {
        return 0;
    }
}

function offer_existance_check($price,$itemID,$storeID){
    $response=DBQuery("SELECT price,store_id from offer where item_id=".$itemID." and store_id=" . $storeID . ";");
    if(sizeof($response)==0)
        return true;
    foreach ($response as $r){
        if($r['store_id']==$storeID){
            if($price>($r['price']-($r['price']*2/10)))
                return false;
            else
                continue;
        }
    }
    return true;
}

function SubmitOffer($storeID,$itemID,$price){
    $response['status'] = 0;
    try {
        $email=$_SESSION['email'];
        $name=$_SESSION['username'];
        $submit_offer_query="INSERT INTO offer VALUES (null, " . $storeID. "," . $itemID. "," .$price. ",CURRENT_DATE,DATE_ADD(CURRENT_DATE, INTERVAL 1 week), 0, 0, true, '" .$email."','".$name."')";
        if(offer_existance_check($price,$itemID,$storeID)) {
            DBQuery($submit_offer_query);
            //give score based on the offers price
            DBQuery("UPDATE user SET current_score=current_score + ".offer_eval($price,$itemID)." where username = '".$name . "';");
            $response['status'] = 1;
        }
    }
    catch (Exception $e) {
        $response['status'] = 0;
    }
    return $response;

}
?>

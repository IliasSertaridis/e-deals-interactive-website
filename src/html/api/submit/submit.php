<?php
require_once 'dbquery.php';
session_start();
SubmitOffer(58,1298,4);

function last_days_over_20_discount($price,$itemID){
    $r=DBQuery("SELECT price.price AS average_daily_price FROM item INNER JOIN price ON item.name = price.item_name WHERE item.item_id = " . $itemID ."and price.date = CURRENT_DATE;");
    $last_days_mid_price=$r['average_daily_price'];
    if($last_days_mid_price-($last_days_mid_price*2/10)>$price){

        return true;
    }
}
function last_weeks_over_20_disc($price,$itemID){
    $r=DBQuery("SELECT  WEEK(price.date)) AS week, AVG(price.price) AS average_weekly_price FROM item INNER JOIN price ON item.name = price.item_name WHERE item.item_id = " . $itemID . " GROUP BY week ODER BY week limit 1;");
    $last_weeks_mid_price= $r['average_weekly_price'];
    if($last_weeks_mid_price-($last_weeks_mid_price*2/10)>$price)
        return true;
}

function offer_eval($price,$itemID){
    if(last_days_over_20_discount($price,$itemID)){
        return 50;
    }
    elseif(last_weeks_over_20_disc($price,$itemID))
        return 20;
    else
        return 0;
}

function offer_existance_check($price,$itemID,$storeID){
    $responce=DBQuery("select price,store_id from offer where itemID=".$itemID.";");
    if(count($responce)==0)
        return true;
    foreach ($responce as $r){
        if($r['store_id']==$storeID){
            if($price<($r['price']-($r['price']*2/10)))
                continue;
            else
                return false;
        }
    }
    return true;
}

function SubmitOffer($storeID,$itemID,$price){

    try {

        $email=$_SESSION['email'];
        $name=$_SESSION['username'];
        $submit_offer_query="INSERT INTO offer VALUES (null, " . $storeID. "," . $itemID. "," .$price. ",CURRENT_DATE,DATE_ADD(CURRENT_DATE, INTERVAL 1 week), 0, 0, true, '" .$email."','".$name."')";
        $response=DBQuery($submit_offer_query);
        //give score based on the offers price
        DBQuery("UPDATE user SET current_score=current_score + ".offer_eval($price,$itemID)."where username =".$name);
    }
    catch (Exception $e) {
        $status = 0;
        $response=$e;
    }
    print_r($response);

}
function deleteOffer($offerID){
    $response=DBQuery("Select item_id,price from offer where offer_id=".$offerID.";");
    if(offer_eval($response['price'],$response['item_id'])==0){
        DBQuery("delete from offer where offer_id=".$offerID.";");
    }
}


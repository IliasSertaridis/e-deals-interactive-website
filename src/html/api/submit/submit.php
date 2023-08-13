<?php
require_once 'dbquery.php';
session_start();
SubmitOffer(58,1298,4);
function SubmitOffer($storeID,$itemID,$price){
    $response=[];
    try {

        $email=$_SESSION['email'];
        $name=$_SESSION['username'];
        $submit_offer_query="INSERT INTO offer VALUES (null, " . $storeID. "," . $itemID. "," .$price. ",CURRENT_DATE,DATE_ADD(CURRENT_DATE, INTERVAL 1 week), 0, 0, true, '" .$email."','".$name."')";
        $response=DBQuery($submit_offer_query);

        //give score based on the offers price
        $r=DBQuery("SELECT price.price AS average_daily_price FROM item INNER JOIN price ON item.name = price.item_name WHERE item.item_id = " . $itemID ."and price.date = CURRENT_DATE;");
        $last_days_mid_price=$r['average_daily_price'];
        if($last_days_mid_price-($last_days_mid_price*2/10)>$price){
            DBQuery("UPDATE user SET current_score=current_score + 50 where username =".$name);

        }
        else{
            $r=DBQuery("SELECT  WEEK(price.date)) AS week, AVG(price.price) AS average_weekly_price FROM item INNER JOIN price ON item.name = price.item_name WHERE item.item_id = " . $itemID . " GROUP BY week ODER BY week limit 1;");
            $last_weeks_mid_price= $r['average_weekly_price'];
            if($last_weeks_mid_price-($last_weeks_mid_price*2/10)>$price)
                DBQuery("UPDATE user SET current_score=current_score + 20 where username =".$name);
        }

       $response=1;
    }
    catch (Exception $e) {
        $status = 0;
        $response=$e;
    }
    print_r($response);

}

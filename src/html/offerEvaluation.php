<?php

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

function last_days_over_20_discount($price,$itemID){
    $r=DBQuery("SELECT price.price AS average_daily_price FROM item INNER JOIN price ON item.name = price.item_name WHERE item.item_id = " . $itemID ." and price.date = CURRENT_DATE;");
    if(sizeof($r) == 0) {
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
    if(sizeof($r) == 0) {
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
?>

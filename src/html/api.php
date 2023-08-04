<?php

require_once 'dbquery.php';


switch($query)
{
    case "admins":
        $result = DBQuery("SELECT * FROM administrator;");
        $json = json_encode($result, JSON_UNESCAPED_UNICODE);
        echo $json;
        break;
    case "stores":
        if (isset($type)) {
            switch($type)
            {
                case "supermarket":
                    $result = DBGeoQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_type = 'supermarket';");
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                    break;
                case "convenience":
                    $result = DBGeoQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_type = 'convenience';");
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                    break;
                default:
                    $json = json_encode((object) null);
                    echo $json;
                    break;
            }
        }
        else {
            $result = DBGeoQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store;");
            echo json_encode($result);
        }
        break;
    case "prices":
        if (isset($item) && isset($timeframe)) {
            switch($timeframe)
            { 
                case 'daily':
                    $result = DBQuery("SELECT item.item_id, item.name, price.date, price.price FROM item INNER JOIN price AS average_daily_price ON item.name = price.item_name WHERE item.item_id = " . $item . ";");
                    $json = json_encode($result, JSON_UNESCAPED_UNICODE);
                    echo $json;
                    break;
                    echo $json;
                case 'weekly':
                    $result = DBQuery("SELECT item.item_id, item.name, CONCAT(YEAR(price.date), '/', WEEK(price.date)) AS week, AVG(price.price) AS average_weekly_price FROM item INNER JOIN price ON item.name = price.item_name WHERE item.item_id = " . $item . " GROUP BY week;");
                    $json = json_encode($result, JSON_UNESCAPED_UNICODE);
                    echo $json;
                    break;
                default:
                    $json = json_encode((object) null);
                    echo $json;
                    break;
            }
        }
        else {
            $result = DBQuery("SELECT item.item_id AS 'item_id', item.name AS 'name', price.date AS 'date', price.price AS 'price' FROM item INNER JOIN price ON item.name = price.item_name;");
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        break;
    default:
        $json = json_encode((object) null);
        echo $json;
        break;
}
?>

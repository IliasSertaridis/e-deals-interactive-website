<?php
header("Cache-Control: no-store");
session_start();
require_once 'dbquery.php';

function getQuery($type, $search)
{
    switch($type) 
    {
        case 'category':
            $where = "WHERE b.category = '" . $search . "'";
            break;
        case 'subcategory':
            $where = "WHERE b.subcategory = '" . $search . "'";
            break;
        default:
            $where = "WHERE b.subcategory = 'Χυμός τομάτας'";
    }
    return "SELECT a.registration_date AS date, AVG((b.average_weekly_price - a.price) * -100) AS mean_discount FROM
(
    SELECT item.item_id, item.name, price.price, offer.registration_date, CONCAT(YEAR(offer.registration_date), '/', WEEK(offer.registration_date)) AS registration_week, category.name as category, subcategory.name as subcategory
    FROM offer
    INNER JOIN item ON offer.item_id = item.item_id
    INNER JOIN price ON item.name = price.item_name
    INNER JOIN subcategory ON item.belongs_to = subcategory.uuid
    INNER JOIN category ON subcategory.belongs_to = category.uuid
    ORDER BY offer.registration_date DESC
) a
LEFT JOIN 
(
    SELECT item.item_id, item.name, CONCAT(YEAR(price.date), '/', WEEK(price.date)) AS week, AVG(price.price) AS average_weekly_price, subcategory.name as subcategory, category.name as category
    FROM item 
    INNER JOIN price ON item.name = price.item_name 
    INNER JOIN subcategory ON item.belongs_to = subcategory.uuid
    INNER JOIN category ON subcategory.belongs_to = category.uuid
    GROUP BY item.item_id, week
) b
ON a.item_id = b.item_id AND a.registration_week = b.week " . $where . " 
GROUP BY a.registration_date
ORDER BY a.registration_date;";
}

$result = array();
if($_SESSION['user_type'] == 'administrator') {
    try {
        if(isset($_GET['subcategory'])) {
            $result = DBQuery(getQuery('subcategory', $_GET['subcategory']));
        }
        else if(isset($_GET['category'])) {
            $result = DBQuery(getQuery('category', $_GET['category']));
        }
        else {
            $result = DBQuery(getQuery('none', ''));
        }
    }
    catch (Exception $e) {
        $result = ((object) null);
    }
}
$response = json_encode($result, JSON_UNESCAPED_UNICODE);
echo $response;
?>

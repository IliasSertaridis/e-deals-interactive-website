<?php
require_once 'dbquery.php';

$supermarketQuery = "SELECT store_id, name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_type = 'supermarket' AND store_id NOT IN(SELECT store_id FROM offer);";
$convenienceQuery = "SELECT store_id, name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_type = 'convenience' AND store_id NOT IN(SELECT store_id FROM offer);";
$dealStoresQuery = "SELECT store_id, name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_id IN(SELECT store_id FROM offer);";
$allQuery = "SELECT store_id, name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store;";

if(isset($_GET['name'])) {
    $supermarketQuery = substr_replace($supermarketQuery, " AND name LIKE '" . $_GET['name'] . "%';", -1); 
    $convenienceQuery = substr_replace($convenienceQuery, " AND name LIKE '" . $_GET['name'] . "%';", -1); 
    $dealStoresQuery = substr_replace($dealStoresQuery, " AND name LIKE '" . $_GET['name'] . "%';", -1); 
    $allQuery = substr_replace($allQuery, " WHERE name LIKE '" . $_GET['name'] . "%';", -1); 
}
if(isset($type) && fnmatch("dealStores", $type) && isset($_GET['category'])) {
    $result = DBQuery("SELECT store.store_id, store.name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store.store_type FROM store INNER JOIN offer ON store.store_id = offer.store_id INNER JOIN item ON offer.item_id = item.item_id INNER JOIN subcategory ON item.belongs_to = subcategory.uuid INNER JOIN category ON subcategory.belongs_to = category.uuid WHERE category.name = '" . $_GET['category'] . "';");
}
else if (isset($type)) {
    if (fnmatch("supermarket", $type)) {
        $result = DBQuery($supermarketQuery);
    }
    else if(fnmatch("convenience", $type)) {
        $result = DBQuery($convenienceQuery);
    }
    else if(fnmatch("dealStores", $type)) {
        header("Cache-Control: no-store");
        $result = DBQuery($dealStoresQuery);
    }
    else {
        $json = json_encode((object) null, JSON_UNESCAPED_UNICODE);
        echo $json;
    }
}
else {
    $result = DBQuery($allQuery);

}
$storesarray = array(
    'type' => 'FeatureCollection',
    'features' => array()
);
foreach($result as $row) {
    $store = array(
        'type' => 'Feature',
        'properties' => array(
        'store_id' => $row['store_id'],
            'name' => $row['name'],
            'shop' => $row['store_type']),
        'geometry' => array(
            'type' => 'Point',
            'coordinates' => array(
                floatval($row['longtitude']),
                floatval($row['latitude'])))
    );
    array_push($storesarray['features'], $store);
}
echo json_encode($storesarray, JSON_UNESCAPED_UNICODE);
?>

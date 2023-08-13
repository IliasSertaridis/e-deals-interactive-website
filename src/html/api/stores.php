<?php
require_once 'dbquery.php';

if (isset($type)) {
    switch($type)
    {
        case "supermarket":
            $result = DBQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_type = 'supermarket' AND store_id NOT IN(SELECT store_id FROM offer);");
            break;
        case "convenience":
            $result = DBQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_type = 'convenience' AND store_id NOT IN(SELECT store_id FROM offer);");
            break;
        case "dealStores":
            $result = DBQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_id IN(SELECT store_id FROM offer);");
            break;
        default:
        $json = json_encode((object) null, JSON_UNESCAPED_UNICODE);
        echo $json;
        exit;
    }
}
else {
    $result = DBQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store;");

}
$storesarray = array(
    'type' => 'FeatureCollection',
    'features' => array()
);
foreach($result as $row) {
    $store = array(
        'type' => 'Feature',
        'properties' => array(
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

<?php
require_once 'dbquery.php';

if (isset($type)) {
    switch($type)
    {
        case "supermarket":
            $result = DBQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_type = 'supermarket';");
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            break;
        case "convenience":
            $result = DBQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store WHERE store_type = 'convenience';");
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            break;
        default:
        $json = json_encode((object) null);
        echo $json;
        break;
    }
}
else {
    $result = DBQuery("SELECT name, ST_x(coordinates) AS longtitude, ST_y(coordinates) AS latitude, store_type FROM store;");

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
    echo json_encode($storesarray);
}
?>

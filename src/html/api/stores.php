<?php
require_once 'dbquery.php';

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
?>

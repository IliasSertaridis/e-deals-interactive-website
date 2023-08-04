<?php

function DBQuery($sqlQuery){
    $servername = "db";
    $username = "root";
    $password = "root";
    $dbname = "edeals";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query($sqlQuery);

    if (gettype($result) == 'boolean') {
        return $result;
    }

    $return = array();
    while ($row = $result->fetch_assoc()) {
        array_push($return, $row);
    }

    $conn->close();
    return $return;
}

function DBGeoQuery($sqlQuery){
    $servername = "db";
    $username = "root";
    $password = "root";
    $dbname = "edeals";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query($sqlQuery);

    $storesarray = array(
        'type' => 'FeatureCollection',
        'features' => array()
    );

    while($row = $result->fetch_assoc()) {
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

    $conn->close();
    return $storesarray;
}
?>

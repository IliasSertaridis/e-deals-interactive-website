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
?>

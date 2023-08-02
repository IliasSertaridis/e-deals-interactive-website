<?php

function DBquery($sqlQuery){
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

    $sql = $sqlQuery;
    $result = $conn->query($sql);

    $rows = mysqli_fetch_all($result);

    $conn->close();
    return $rows;
}
?>

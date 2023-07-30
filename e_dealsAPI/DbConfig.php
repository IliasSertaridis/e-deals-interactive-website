<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "e_deals1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "select * from test1;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["t0"]. " - Name: " . $row["t1"]. " " . $row["t2"]. "<br>";
  }
} else {
  echo "0 results";
}
$conn->close();
?>
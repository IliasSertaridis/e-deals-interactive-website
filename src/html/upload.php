<?php

echo "Upload: " . $_FILES["file"]["name"] . "<br>";
echo "Type: " . $_FILES["file"]["type"] . "<br>";
echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

if ($_SERVER['REQUEST_URI'] == "/admin/items/upload" && strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)) == "json")
{
	move_uploaded_file($_FILES["file"]["tmp_name"],"/tmp/".$_FILES["file"]["name"]);
    $path = '/tmp/' . $_FILES["file"]["name"];
    echo "Stored in: ". $path . "<br>";
    $jsonString = file_get_contents($path);
    $jsonData = json_decode($jsonString, true);
    if (sizeof($jsonData) == 3)
    {
        echo "Uploaded Item / Categories Data File<br>";
    }
    else if (sizeof($jsonData) == 2)
    {
        echo "Uploaded Item Price Data File<br>";
    }
    else
    {
        echo "Malformed Data File";
    }
    echo '<pre>';
    echo sizeof($jsonData) . "<br>";
    print_r($jsonData);
#    foreach($jsonData['categories'] as $item) {
#        echo $item['name'] . "<br>";
#    }
}
else if ($_SERVER['REQUEST_URI'] == "/admin/stores/upload" && strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)) == "geojson")
{
	move_uploaded_file($_FILES["file"]["tmp_name"],"/tmp/".$_FILES["file"]["name"]);
    $path = '/tmp/' . $_FILES["file"]["name"];
    echo "Stored in: ". $path . "<br>";
    $jsonString = file_get_contents($path);
    $jsonData = json_decode($jsonString, true);
    if (sizeof($jsonData) == 5)
    {
        echo "Uploaded Stores Data File<br>";
    }
    else 
    {
        echo "Malformed Data File<br>";
    }
    echo '<pre>';
    echo sizeof($jsonData) . "<br>";
    print_r($jsonData);
}
else
{
    echo "The file type you uploaded is invalid!";
}
?> 

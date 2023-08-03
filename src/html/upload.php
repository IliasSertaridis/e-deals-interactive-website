<?php

require_once 'dbquery.php';

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
#        print_r($jsonData);
        $categoriesQuery = "INSERT INTO category VALUES";
        $subcategoriesQuery = "INSERT INTO subcategory VALUES";
        $itemsQuery = "INSERT INTO item VALUES";
        foreach($jsonData['categories'] as $category) {
            $categoriesQuery = $categoriesQuery . " ('" . $category['id'] . "','" . $category['name'] . "'),";
            foreach($category['subcategories'] as $subcategory) {
                $subcategoriesQuery = $subcategoriesQuery . " ('" . $subcategory['uuid'] . "','" . $subcategory['name'] . "','" . $category['id'] . "'),";
            }
        }
        foreach($jsonData['products'] as $item) {
            if(!in_array($item['id'], array(200, 1340))) { //Handling duplicate data from provided file
                $itemsQuery = $itemsQuery . " (\"" . $item['name'] . "\"," . "NULL" . "," . "NULL" . "," . "NULL" . ",\"" . $item['subcategory'] . "\"),";
            }
        }
        $categoriesQuery = substr_replace($categoriesQuery,";",-1);
        $subcategoriesQuery = substr_replace($subcategoriesQuery,";",-1);
        $itemsQuery = substr_replace($itemsQuery,";",-1);
        DBquery("DELETE FROM category;");
        DBquery("DELETE FROM subcategory;");
        DBquery("DELETE FROM item;");
        DBquery($categoriesQuery);
        DBquery($subcategoriesQuery);
        DBquery($itemsQuery);
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

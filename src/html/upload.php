<?php

require_once 'dbquery.php';

#echo "Upload: " . $_FILES["file"]["name"] . "<br>";
#echo "Type: " . $_FILES["file"]["type"] . "<br>";
#echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
#echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

$return = array();
$status = 0;

try {
        if ($_SERVER['REQUEST_URI'] == "/admin/items/upload" && strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)) == "json")
        {
            move_uploaded_file($_FILES["file"]["tmp_name"],"/tmp/".$_FILES["file"]["name"]);
            $path = '/tmp/' . $_FILES["file"]["name"];
            #echo "Stored in: ". $path . "<br>";
            $jsonString = file_get_contents($path);
            $jsonData = json_decode($jsonString, true);
            if (sizeof($jsonData) == 3)
            {
                #echo "Uploaded Item / Categories Data File<br>";
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
                        $itemsQuery = $itemsQuery . " (NULL,\"" . $item['name'] . "\"," . "NULL" .  ",\"" . $item['subcategory'] . "\"),";
                    }
                }
                DBQuery("DELETE FROM category;");
                DBQuery("DELETE FROM subcategory;");
                DBQuery("DELETE FROM item;");
                if($categoriesQuery != "INSERT INTO category VALUES" && $subcategoriesQuery != "INSERT INTO subcategory VALUES" && $itemsQuery != "INSERT INTO item VALUES") {
                    $categoriesQuery = substr_replace($categoriesQuery,";",-1);
                    $subcategoriesQuery = substr_replace($subcategoriesQuery,";",-1);
                    $itemsQuery = substr_replace($itemsQuery,";",-1);
                    $response1 = DBQuery($categoriesQuery);
                    $response2 = DBQuery($subcategoriesQuery);
                    $response3 = DBQuery($itemsQuery);
                    if($response1 && $response2 && $response3) {
                        $status = 1;
                    }
                }
            }
            else if (sizeof($jsonData) == 2)
            {
                #echo "Uploaded Item Price Data File<br>";
                $pricesQuery = "INSERT INTO price VALUES";
                foreach($jsonData['data'] as $item){
                    foreach($item['prices'] as $price) {
                        $pricesQuery = $pricesQuery . " ('" . $item['name'] . "','" . $price['date'] . "'," . $price['price'] . "),";
                    }
                }
                if ($pricesQuery != "INSERT INTO price VALUES") {
                    $pricesQuery = substr_replace($pricesQuery,";",-1);
                    $response = DBQuery($pricesQuery);
                    if ($response) {
                        $status = 2;
                    }
                }
            }
        }
        else if ($_SERVER['REQUEST_URI'] == "/admin/stores/upload" && strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)) == "geojson")
        {
            move_uploaded_file($_FILES["file"]["tmp_name"],"/tmp/".$_FILES["file"]["name"]);
            $path = '/tmp/' . $_FILES["file"]["name"];
            ##echo "Stored in: ". $path . "<br>";
            $jsonString = file_get_contents($path);
            $jsonData = json_decode($jsonString, true);
            if (sizeof($jsonData) == 5)
            {
                #echo "Uploaded Stores Data File<br>";
                #echo '<pre>';
                #print_r($jsonData);
                $storesQuery = "INSERT INTO store VALUES";
                foreach($jsonData['features'] as $store) {
                    if (array_key_exists("name",$store['properties']) && array_key_exists("shop",$store['properties']) && array_key_exists("coordinates",$store['geometry'])) {
                        $storesQuery = $storesQuery . " (NULL,'" . $store['properties']['name'] . "',ST_GeomFromText('POINT(" . $store['geometry']['coordinates'][0] . " " . $store['geometry']['coordinates'][1] . ")'),'" . $store['properties']['shop'] . "'),";
                    }
                }
                DBQuery("DELETE FROM store;");
                if ($storesQuery != "INSERT INTO store VALUES") {
                    $storesQuery = substr_replace($storesQuery,";",-1);
                    $response = DBQuery($storesQuery);
                    if($response) {
                        $status = 3;
                    }
                }
            }
        }
    }
catch (Exception $e) {
    $status = 0;
}
$return['status'] = $status;
echo json_encode($return);
?> 

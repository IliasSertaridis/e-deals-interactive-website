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
        $todaysQuery = "UPDATE item SET mean_daily_price = (case ";
        $weeksQuery = "UPDATE item SET mean_weekly_price = (case ";
        $weeksTrigger = false;
        $weeksCounter = 0;
        $weeksPrice = 0;
#        print_r($jsonData);
        foreach($jsonData['data'] as $item){
            foreach(array_reverse($item['prices']) as $price) {
                if($price['date'] == date("Y-m-d")) {
                   $todaysQuery = $todaysQuery . "when name = \"" . $item['name'] . "\" then " . $price['price'] . " ";
                }
                else if(in_array($price['date'], array(date('Y-m-d',strtotime("-1 days")),date('Y-m-d',strtotime("-2 days")),date('Y-m-d',strtotime("-3 days")),date('Y-m-d',strtotime("-4 days")),date('Y-m-d',strtotime("-5 days")),date('Y-m-d',strtotime("-6 days")),date('Y-m-d',strtotime("-7 days"))))) {
                    $weeksTrigger = True;
                    $weeksPrice = $weeksPrice + $price['price'];
                    $weeksCounter++;
                }
                else {
                    break;
                }
            }
            if($weeksTrigger == True) {
                $weeksPrice = $weeksPrice / $weeksCounter;
                $weeksQuery = $weeksQuery . "when name = \"" . $item['name'] . "\" then " . $weeksPrice . " ";
                $weeksPrice = 0;
                $weeksCounter = 0;
                $weeksTrigger = false;
            }
        }
        if ($todaysQuery != "UPDATE item SET mean_daily_price = (case ") {
            $todaysQuery = $todaysQuery . "end);";
            DBquery($todaysQuery);
        }
        if ($weeksQuery != "UPDATE item SET mean_weekly_price = (case ") {
            $weeksQuery = $weeksQuery . "end);";
            DBquery($weeksQuery);
        }
        echo $todaysQuery . "</br>";
        echo $weeksQuery . "</br>";
    }
    else
    {
        echo "Malformed Data File";
    }
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

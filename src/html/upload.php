<?php

echo "Upload: " . $_FILES["file"]["name"] . "<br>";
echo "Type: " . $_FILES["file"]["type"] . "<br>";
echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

if (strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)) != "json")
{
  echo "Sorry, only JSON files are allowed!";
}
else if (file_exists("/tmp/".$_FILES["file"]["name"]))
{
	echo $_FILES["file"]["name"] . " already exists. ";
}
else
{
	move_uploaded_file($_FILES["file"]["tmp_name"],"/tmp/".$_FILES["file"]["name"]);
    echo "Stored in: /tmp/" . $_FILES["file"]["name"];
}
?> 

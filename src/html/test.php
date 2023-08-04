<?php
require_once __DIR__.'/test.html';
require_once 'dbquery.php';
echo "Through PHP " . "</br>";
$result=DBQuery("SELECT * FROM administrator;");
echo "<pre>";
var_dump($result);
?>

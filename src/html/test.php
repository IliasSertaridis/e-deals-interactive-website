<?php
require_once __DIR__.'/test.html';
require_once 'dbquery.php';
echo "Through PHP " . "</br>";
$result=DBquery("SELECT * FROM administrator;");
var_dump($result);
?>

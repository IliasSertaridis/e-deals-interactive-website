<?php
session_start();	
$response = array();
$status = 5;
require_once 'dbquery.php';
try{
    if(isset($_SESSION['username']) && isset($_SESSION['email'])){
        $name = $_SESSION['username'];
        $email = $_SESSION['email'];
        $status=1;
        if(isset($_POST['in_stock']) && isset($_POST['offer_id']) ){
            $offer_id = $_POST['offer_id'];
            $in_stock = $_POST['in_stock'];
            $status=2;
            if($in_stock == 'yes'){
                $result=DBQuery("UPDATE offer SET in_stock = 1 WHERE offer_id = '" .$offer_id. "';");
                $status=3;
            } else if($in_stock == 'no'){
                $result=DBQuery("UPDATE offer SET in_stock = 0 WHERE offer_id = '" .$offer_id. "';");
                $status=4;
            }
            
        }
    }
} catch(Exception $e){
    $status=0;
}
$response['status'] = $status;
echo json_encode($response);
?>
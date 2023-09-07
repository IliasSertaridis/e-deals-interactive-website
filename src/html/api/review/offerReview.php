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
        if(isset($_POST['review']) && isset($_POST['offer_id']) ){
            $review = $_POST['review'];
            $offer_id = $_POST['offer_id'];
            $status=2;
            if($review == 'like' || $review == 'dislike'){
                $q = "INSERT INTO review VALUES(null, '" .$email. "', '" .$name. "', " .$offer_id. ", '" .$review. "') ON DUPLICATE KEY UPDATE rating='" .$review. "';";
                $result = DBQuery($q);
                $status = 3;
            } 
        }
    }
} catch(Exception $e){
    $status=0;
}
$response['status'] = $status;
echo json_encode($response);
?>
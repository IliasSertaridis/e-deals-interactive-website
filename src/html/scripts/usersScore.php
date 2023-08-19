<?php

require_once '/var/www/html/dbquery.php';
try {

    $users_score_query = "SELECT username,current_score,total_tokens,last_month_tokens,total_score FROM user;";
    $responce=DBQuery($users_score_query);
    print_r ($responce);
    $score_sum=0;
    $user_number=count($responce);
    $tokens=$user_number*100;
    foreach ($responce as $r){
        $score_sum=$r['current_score']+$score_sum;
    }
    unset($r);
    foreach ($responce as $r){
        $percental=$r["current_score"]/$score_sum;
        $r['total_tokens']=round($r['total_tokens']+ $tokens*$percental);
        $r['last_month_tokens']=$tokens*$percental;
        $r['total_score']+=$r['current_score'];
        $r['current_score']=0;
        DBQuery("update user set total_tokens = ".$r['total_tokens']." where username ='".$r['username']."';");
        DBQuery("update user set last_month_tokens = ".$r['last_month_tokens']." where username ='".$r['username']."';");
        DBQuery("update user set total_score = ".$r['total_score']." where username ='".$r['username']."';");
        DBQuery("update user set current_score = ".$r['current_score']." where username ='".$r['username']."';");
    }

} catch (Exception $e) {
    $status = 0;
}


<?php
session_start();	
require_once __DIR__.'/../startnavbar.html';
require_once __DIR__.'/login.html';
if (isset($_SESSION['username']) && isset($_SESSION['user_type']))
{
    if ($_SESSION['user_type'] == 'user') {
        header("Location: profile");
    }
    else {
        header("Location: admin");
    }
}
?>

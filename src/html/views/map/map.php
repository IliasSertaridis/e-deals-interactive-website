<?php
require_once 'views/checkSession.php';
$type = checkSession('','','login');
switch ($type)
{
    case 'user':
        require_once __DIR__.'/../usernavbar.html';
        require_once __DIR__.'/map.html';
        break;
    case 'administrator':
        require_once __DIR__.'/../admin/adminnavbar.html';
        require_once __DIR__.'/map.html';
        break;
}
?>

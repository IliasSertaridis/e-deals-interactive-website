<?php
require_once 'views/checkSession.php';
if (checkSession('map','','login') == 'administrator')
{
    require_once __DIR__.'/../adminnavbar.html';
    require_once __DIR__.'/itemsData.html';
}
?>

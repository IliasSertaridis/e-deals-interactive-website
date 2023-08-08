<?php
require_once 'views/checkSession.php';
if (checkSession('profile','','login') == 'administrator')
{
    require_once __DIR__.'/../adminnavbar.html';
    require_once __DIR__.'/storesData.html';
}
?>

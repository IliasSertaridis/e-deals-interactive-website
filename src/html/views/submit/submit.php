<?php
require_once 'views/checkSession.php';
if (checkSession('','admin','login') == 'user')
{
    require_once __DIR__.'/../usernavbar.html';
    require_once __DIR__.'/submit.html';
}
?>

<?php
require_once 'views/checkSession.php';
if (checkSession('','','login') == 'user' || 'administrator')
{
    require_once __DIR__.'/map.html';
}
?>
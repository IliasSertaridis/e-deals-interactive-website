<?php
require_once 'views/checkSession.php';
$user_type = checkSession('','','login');
if ($user_type == 'user')
{
    require_once __DIR__.'/../usernavbar.html';
    require_once __DIR__.'/submit.html';
}
else if ($user_type == 'administrator')
{
    require_once __DIR__.'/../admin/adminnavbar.html';
    require_once __DIR__.'/submit.html';
}
?>

<?php
session_start();	
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
    </body>
</html>
<?php
if (isset($_SESSION['session_username']))
{
    require_once __DIR__.'/map.html';
}
else
{
    header("Location: /login");
}
?>

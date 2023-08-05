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
if (isset($_SESSION['username']) && isset($_SESSION['user_type']))
{
    if ($_SESSION['user_type'] == 'administrator') {
        require_once __DIR__.'/statistics.html';
    }
    else{
        header("Location: profile");
    }
}
else {
    header("Location: login");
}
?>

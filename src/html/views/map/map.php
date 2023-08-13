<?php
session_start();
require_once 'api/submit/submit.php'
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
    require_once __DIR__.'/map.html';
}
else {
    header("Location: login");
}

SubmitOffer(1,2,3);
?>

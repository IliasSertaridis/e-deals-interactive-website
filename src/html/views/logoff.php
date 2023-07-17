<?php
	session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<?php
	session_destroy();
?>
<p>Ευχαριστούμε που μας επισκεφτήκατε!</p>
<a href="/login">
	<input type="submit" value="Επιστροφή" action="/login">
</a>
</html>

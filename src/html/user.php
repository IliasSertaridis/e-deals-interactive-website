<?php
	session_start();	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"
</head>
</html>
<?php
	if (isset($_SESSION['session_username']))
	{
		echo "Έχεις ήδη κάνει login <b>".$_SESSION['session_username']."</b>! Μια φορά αρκεί.";
		echo "<br><a href='/logoff'>Log Off</a>";
	}
	else
	{
		if (strlen($_POST['username']) < 6 || strlen($_POST['password']) < 6)
		{
			echo "<h2>Δεν έχετε δώσει τα σωστά δεδομένα!</h2>";
			echo "<a href='/login'><input type='submit' value='Επιστροφή'></a>";
		}
		else
		{
			$_SESSION['session_username'] = $_POST['username'];
			echo "Καλωσορίσατε <b>".$_SESSION['session_username'];
			echo "<br><a href='/logoff'>Log Off</a>";
		}
	}

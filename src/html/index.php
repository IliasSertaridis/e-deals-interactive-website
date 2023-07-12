<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<?php

require_once "router.php";

route('/', function()
{
	require __DIR__ . '/login.php';
});

route('', function()
{
	require __DIR__ . '/login.php';
});

route('/login', function()
{
	require __DIR__ . '/login.php';
});

route('/logoff', function()
{
	require __DIR__ . '/logoff.php';
});

route('/user', function()
{
	require __DIR__ . '/user.php';
});

route('/map', function()
{
	require __DIR__ . '/map/map.php';
});

route('/company/{companyName}', function($companyName)
{
	return "The company is {$companyName}";
});

route('/customers/{username}/city/{city}', function($username, $city)
{
	return "{$username} lives in {$city}";

});

route('/(.+)/?' , function()
{
	http_response_code(404);
	require __DIR__ . '/404.html';
});

$action = $_SERVER['REQUEST_URI'];
dispatch($action);
?>

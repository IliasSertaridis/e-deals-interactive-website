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
	require __DIR__ . '/404.php';
});

$action = $_SERVER['REQUEST_URI'];
dispatch($action);
?>

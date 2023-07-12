<?php

$routes = [];

function route($action, $callback)
{
	global $routes;
	$action = trim($action, '/');
	$action = preg_replace('/{[^}]+}/', '(.+)', $action);
	$routes[$action] = $callback;
}

function dispatch($action)
{
	global $routes;
	$action = trim($action, '/');
	$callback = NULL;
	$params = [];
	foreach ($routes as $route => $handler)
	{
        $action = str_replace('.php', '', $action);
        $action = str_replace('.html', '', $action);
		if(preg_match("%^{$route}$%", $action, $matches) === 1)
		{
			$callback = $handler;
			unset($matches[0]);
			$params = $matches;
			break;
		}
	}
	//$callback = $routes[$action];
	if($callback != NULL)
		echo call_user_func($callback, ...$params);
}

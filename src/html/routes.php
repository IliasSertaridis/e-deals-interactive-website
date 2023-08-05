<?php

require_once __DIR__.'/router.php';

// ##################################################
// ##################################################
// ##################################################

// Static GET
// In the URL -> http://localhost
// The output -> Index
get('/', 'views/index.php');
get('/index', 'views/index.php');
get('/login', 'views/login/login.php');
get('/register', 'views/register/register.php');
get('/logout', 'views/logout.php');
get('/profile', 'views/profile/profile.php');
get('/submit', 'views/submit/submit.php');
get('/map', 'views/map/map.php');
get('/admin', 'views/admin/admin.php');
get('/admin/items', 'views/admin/itemsData/itemsData.php');
get('/admin/stores', 'views/admin/storesData/storesData.php');
get('/admin/statistics', 'views/admin/statistics/statistics.php');
get('/admin/leaderboard', 'views/admin/leaderboard/leaderboard.php');
get('/admin/items/delete', 'delete.php');
get('/admin/stores/delete', 'delete.php');
get('/test', 'test.php');

// Dynamic GET. Example with 1 variable
// The $id will be available in user.php
get('/api/$query', 'api.php');
get('/api/$query/$type', 'api.php');
get('/api/$query/$item/$timeframe', 'api.php');

// Dynamic GET. Example with 2 variables
// The $name will be available in full_name.php
// The $last_name will be available in full_name.php
// In the browser point to: localhost/user/X/Y
#get('/user/$name/$last_name', 'views/full_name.php');

// Dynamic GET. Example with 2 variables with static
// In the URL -> http://localhost/product/shoes/color/blue
// The $type will be available in product.php
// The $color will be available in product.php
#get('/product/$type/color/$color', 'product.php');

// A route with a callback
#get('/callback', function(){
#  echo 'Callback executed';
#});

// A route with a callback passing a variable
// To run this route, in the browser type:
// http://localhost/user/A
#get('/callback/$name', function($name){
#  echo "Callback executed. The name is $name";
#});

// Route where the query string happends right after a forward slash
#get('/product', '');

// A route with a callback passing 2 variables
// To run this route, in the browser type:
// http://localhost/callback/A/B
#get('/callback/$name/$last_name', function($name, $last_name){
#  echo "Callback executed. The full name is $name $last_name";
#});

// ##################################################
// ##################################################
// ##################################################
// Route that will use POST data
post('/login', 'views/login/login.php');
post('/register', 'views/register/register.php');
post('/admin/items/upload', 'upload.php');
post('/admin/stores/upload', 'upload.php');



// ##################################################
// ##################################################
// ##################################################
// any can be used for GETs or POSTs

// For GET or POST
// The 404.php which is inside the views folder will be called
// The 404.php has access to $_GET and $_POST
any('/404','views/404.php');

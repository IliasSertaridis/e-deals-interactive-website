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
get('/test', 'views/test/test.php');
// API
get('/api/stores', 'api/stores.php');
get('/api/prices', 'api/prices.php');
get('/api/categories', 'api/categories.php');
get('/api/subcategories', 'api/subcategories.php');
get('/api/userScore','api/users/usersScore.php');
get('/api/admin/items/delete', 'api/admin/delete.php');
get('/api/admin/stores/delete', 'api/admin/delete.php');
get('/api/admin/leaderboard', 'api/admin/leaderboard.php');
get('/api/admin/statistics/offers', 'api/admin/statistics/offers.php');
get('/api/admin/statistics/discount', 'api/admin/statistics/discount.php');
get('/api/profile/dealsHistory', 'api/profile/dealsHistory.php');
get('/api/profile/reviewsHistory', 'api/profile/reviewsHistory.php');
get('/api/profile/scoreHistory', 'api/profile/scoreHistory.php');
get('/api/profile/dealsHistory', 'api/profile/dealsHistory/dealsHistory.php');
get('/api/profile/reviewsHistory', 'api/profile/reviewsHistory/reviewsHistory.php');
get('/api/submit/items','api/submit/items.php');

// Dynamic GET. Example with 1 variable
// The $id will be available in user.php
// API
get('/api/stores/$type', 'api/stores.php');
get('/api/prices/$item/$timeframe', 'api/prices.php');

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
// API
post('/api/login', 'api/login.php');
post('/api/register', 'api/register.php');
post('/api/admin/items/upload', 'api/admin/upload.php');
post('/api/admin/stores/upload', 'api/admin/upload.php');
post('/api/profile/details/verify', 'api/profile/details/verify.php');
post('/api/profile/details/change', 'api/profile/details/change.php');
post('/api/submit/submit','api/submit/submit.php');
get('/api/submit/submit','api/submit/submit.php');



// ##################################################
// ##################################################
// ##################################################
// any can be used for GETs or POSTs

// For GET or POST
// The 404.php which is inside the views folder will be called
// The 404.php has access to $_GET and $_POST
any('/api/404','api/empty.php');
any('/404','views/404.php');

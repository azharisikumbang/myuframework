<?php
// Descibe your app router here

$router->get('/', 'HomeController@index');

$router->get('/user', 'HomeController@index', "User\Seller");

$router->handler(404, function() {
	echo "404";
});


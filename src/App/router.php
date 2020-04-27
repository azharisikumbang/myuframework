<?php
// Descibe your app router here

$router->get('/', 'HomeController@index');

$router->handler(404, function() {
	echo "404";
});


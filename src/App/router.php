<?php
// Descibe your app router here

$router->get('/', 'HomeController@index');

$router->get('/user/{app}', function($req) {
	echo $req->getArg('app');
});

$router->handler(404, function() {
	echo "404";
});


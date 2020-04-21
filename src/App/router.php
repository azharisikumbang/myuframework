<?php
// Descibe your app router here

$router->get('/', 'HomeController@index');

$router->get('/user/{app}', function($req) {
	echo $req->getArg('app');
});


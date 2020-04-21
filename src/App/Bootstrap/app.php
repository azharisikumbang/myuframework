<?php
require_once __DIR__ . "/../../../vendor/autoload.php";

$app = new Myu\App(dirname(__DIR__, 3));

$app->route(function($router) {
	require_once __DIR__ . "/../router.php";
}, 'Myu\App\Controllers');

return $app;
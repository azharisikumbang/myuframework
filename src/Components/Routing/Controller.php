<?php
namespace Myu\Components\Routing;
/**
 * Controller
 */
class Controller
{
	private $config;

	public function render(string $view, $data = null){
		require_once "./" .$view;
	}

}
<?php
namespace Myu\Components\Routing;

use Myu\Config\Config;


/**
 * Controller
 */
class Controller
{
	private $config;

	private $basePath;

	public function __construct()
	{
		$this->basePath = Config::getBasePath();
	}

	public function render(string $view, $data = null){
		require_once $this->basePath . "/src/App/Views/" .$view;
	}

}
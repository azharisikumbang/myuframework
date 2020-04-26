<?php
namespace Myu\Components\Routing;

use Myu\Handler\Config;


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
		$this->config = Config::getConfig("structure");
	}

	public function render(string $view, $data = null){
		$viewFile = (!strpos($view, '.php')) ? $view . ".php" : $view; 		
		require_once $this->basePath . "/src/App/" . $this->config['views_folder'] . "/" .$viewFile;
	}

}
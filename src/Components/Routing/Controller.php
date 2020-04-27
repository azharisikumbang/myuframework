<?php
namespace Myu\Components\Routing;

use Myu\Handler\Config;


/**
 * Controller Class
 */
class Controller
{

	/**
	 * @var instance of Config::getConfig("structure")
	 */
	private $config;

	/**
	 * @var Config::getBasePath()
	 */
	private $basePath;

	/**
	 * Set up Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->basePath = Config::getBasePath();
		$this->config = Config::getConfig("structure");
	}


	/**
	 * Get the string contents of the view
	 * 
	 * @param string @view view file
	 * @param array|null @data
	 */ 
	public function render(string $view, $data = null){
		$viewFile = (!strpos($view, '.php')) ? $view . ".php" : $view; 		
		require_once $this->basePath . "/src/App/" . $this->config['views_folder'] . "/" .$viewFile;
	}

}
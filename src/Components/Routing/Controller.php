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
	}

	/**
	 * Get the string contents of the view
	 * 
	 * @param string @view view file
	 * @param array|null @data
	 */ 
	public function render(string $view, $data = null){
		$view_file = (!strpos($view, '.php')) ? $view . ".php" : $view; 
		$view_path = (Config::getConfig('views_folder')) ? Config::getConfig('views_folder') . "/" : null;
		require_once $this->basePath . "/src/" . $view_path .$view_file;
	}

}
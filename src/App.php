<?php
namespace Myu;

use Myu\Bootstrap\Environment;
use Myu\Components\Routing\Router;
use Myu\Handler\Config;

/**
 * App Class
 */
class App
{

	/**
     * @var instance of use Myu\Components\Routing\Router
     */	
	protected $router;

	/**
     * @var app namespace
     */	
	private $namespace;

	/**
     * @var app base path
     */	
	private $basePath;

	/**
	 * Build Application
	 * its will be set environment, router and configuration
	 *
     * @param string $path envrontment file path
     * @return void
     */	
	public function __construct($path)
	{
		
		Environment::load($path);
		
		$this->basePath = $path;
		$this->router = new Router($this->basePath);
	}

	/**
	 * Run the app
	 *
     * @return void
     */	
	public function run(){

		// Setup enviroment
		$this->setupEnvironment();

		// Config set base path and autolaod
		Config::setBasePath($this->basePath);
		Config::setAutoload();

		/**
		 * active controller will be run
		 * active controller contains array, with values are controller name, method name, and bool is callable
		 * if controller callable it doesnt need to instance the class
		 *
		 * @return void
		 */
		$active = $this->router->getActiveController();
		if (!isset($active[2])) {
			$controller = "\\" . $this->namespace . "\\" . $active[0][0];
			$controller = new $controller();
			$method = $active[0][1];

			$handler = [$controller, $method];
		} else {
			$handler = $active[0];
		}

		call_user_func_array($handler, $active[1]);
	}

	/**
	 * routes caller, all routes will colect by this method
	 *
	 * @param callable $calback get the routes
	 * @param string $namespace app namespace
	 * @return void
	 */
	public function route($callback, $namespace){
		$this->namespace = $namespace;
		call_user_func_array($callback, [$this->router]);
	}

	/**
	 * check and set the environment status
	 * 
	 * @return void
	 */
	private function setupEnvironment()
	{
		$envs = ["development", "production", "staging"];
		$env = strtolower(env("APP_ENV"));

		switch ($env) {
			case 'development':
			case 'staging':
            	error_reporting(E_ALL);
	            break;

	        case 'production':
	            ini_set('display_errors', 0);
				ini_set('log_errors', 1);
				error_reporting(E_ERROR | E_WARNING | E_PARSE);
	            break;
			
			default:
				// return error if environment doesnt valid
				http_response_code(500);
				throw new \Myu\Handler\Error("Unknown Environment! Please check on your .env file", 500, ".env", 3);
				break;
		}

	}

}
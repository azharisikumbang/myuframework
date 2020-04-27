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
     * @var will contain run requirement
     */	
	private $run;

	/**
     * @var environment file
     */	
	private $hasEnvironment = true;

	/**
	 * Build Application
	 * its will be set environment, router and configuration
	 *
     * @param string $path envrontment file path
     * @return void
     */	
	public function __construct($path = false)
	{
		if ($path) {
			Environment::load($path);
			$this->basePath = $path;
		} else {
			$this->hasEnvironment = false;
			$this->basePath = dirname(__DIR__, 4);
		} 

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

		/**
		 * will be run
		 * this will contains array, with values are controller name, method name, and bool is callable
		 * if controller callable it doesnt need to instance the class
		 *
		 * @return void
		 */
		$this->run = $this->router->getActiveController();

		// Validate Route
		$this->validate('routes');

		if (!isset($this->run[2])) {
			$controller_path = (Config::getConfig("controllers_folder")) ? Config::getConfig("controllers_folder") . "\\" : null ;
			$controller = "\\" . $this->namespace . "\\" . $controller_path . $this->run[0][0];

			$this->validate('controller', $controller);

			$controller = new $controller();

			$method = $this->run[0][1];

			$this->validate('method', [$controller, $method]);

			$handler = [$controller, $method];
		} else {
			$handler = $this->run[0];
		}

		call_user_func_array($handler, $this->run[1]);
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
		if ($this->hasEnvironment) {
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
					throw new \Myu\Handler\Error("Unknown Environment! Please check on your .env file", 500, ".env", 3);
					break;
			}
		}

		
	}

	public function config($name, $value)
	{
		Config::setConfig($name, $value);	
	}

	private function validate($name, $validate = null)
	{

		switch ($name) {
			case 'routes':
				if (is_null($this->run)) {
					throw new \Myu\Handler\Error("No Route defined, please check your router", 500, __FILE__, __LINE__);
				}
				break;

			case 'controller':
				if (!class_exists($validate)) {
					throw new \Myu\Handler\Error("Class '{$validate}' not found", 500, __FILE__, __LINE__);
				}
				break;

			case 'method':
				if (!method_exists($validate[0], $validate[1])) {
					throw new \Myu\Handler\Error("class " . get_class($validate[0]) . " does not have a method '{$validate[1]}' ", 500);
				}
				break;
			
			default:
				# code...
				break;
		}

	}
}
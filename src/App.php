<?php
namespace Myu;

use Myu\Bootstrap\Environment;
use Myu\Components\Routing\Router;
use Myu\Components\Routing\Request;
use Myu\Components\Routing\Response;
use Myu\Handler\Config;

/**
 * Application
 */
class App
{
	private $env;
	private $namespace;
	private $call;

	protected $router;

	private $basePath;


	public function __construct($file)
	{
		$this->basePath = $file;
		$this->env = Environment::load($file);
		$this->router = new Router(new Request, new Response, $this->basePath);
	}

	public function run(){

		$this->setupEnvironment();

		Config::setBasePath($this->basePath);
		Config::setAutoload();

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

	public function route($callback, $namespace){
		$this->namespace = $namespace;
		call_user_func_array($callback, [$this->router]);
	}

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
				http_response_code(500);
				throw new \Myu\Handler\Error("Unknown Environment! Please check on your .env file", 500, ".env", 3);
				break;
		}

	}

}
<?php
namespace Myu;

use Myu\Bootstrap\Environment;
use Myu\Components\Routing\Router;
use Myu\Components\Routing\Request;
use Myu\Components\Routing\Response;

/**
 * Application
 */
class App
{
	private $env;
	private $namespace;
	private $call;

	protected $router;
	protected $path;


	public function __construct($file)
	{
		$this->path = $file;
		$this->env = Environment::load($file);
		$this->router = new Router(new Request, new Response, $this->path);
	}

	public function run(){
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

}
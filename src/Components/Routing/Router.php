<?php
namespace Myu\Components\Routing;

use Myu\Components\Routing\Request;
use Myu\Components\Routing\Response;

/**
 * Route Class
 *
 */
class Router
{
	/**
	 * @var Instance of Myu\Components\Routing\Request
	 */
	private $request;
	/**
	 * @var Instance of Myu\Components\Routing\Response
	 */
	private $response;

	/**
	 * @var Controller name
	 */
	private $controller;

	/**
	 * @var method name
	 */
	private $method = 'index';

	/**
	 * @var parameters
	 */
	private $params = [];

	/**
	 * @var page found status 
	 */
	private $found = [];

	/**
	 * @var allowed HTTP request Method
	 */
	private $requestMethod = ["GET", "POST", "PUT", "PATCH", "DELETE"];

	/**
	 * @var app base path
	 */
	private $path;

	/**
	 * @var Controller will be excuted
	 */
	private $activeController;

	/**
	 * Constructor for Router Class
	 *
	 * @param string $path app base path
	 * @return void
	 */
	public function __construct($path = __DIR__)
	{
		$this->request = new Request;
		$this->response = new Response;
		$this->path = $path;
	}

	/**
	 * GET HTTP Handler Method
	 *
     * @param string $request Request Uri
     * @param string|callable $handler Callback Handler
     * @param string|null $path use for folder on class controller
     * @return void
    */
	public function get($request, $handler, $path = null) : void
	{
		$this->map('GET', $request, $handler, $path);
	}

	/**
	 * POST HTTP Handler Method
	 *
     * @param string $request Request Uri
     * @param string|callable $handler Callback Handler
     * @param string|null $path Controler's path
     * @return void
    */	
	public function post($request, $handler, $path = null) : void
	{
		$this->map('POST', $request, $handler, $path);
	}

	/**
	 * PUT HTTP Handler Method
	 *
     * @param string $request Request Uri
     * @param string|callable $handler Callback Handler
     * @param string|null $path Controler's path
     * @return void
    */	
	public function put($request, $handler, $path = null) : void
	{
		$this->map('PUT', $request, $handler, $path);
	}

	/**
	 * PATCH HTTP Handler Method
	 *
     * @param string $request Request Uri
     * @param string|callable $handler Callback Handler
     * @param string|null $path Controler's path
     * @return void
    */	
	public function patch($request, $handler, $path = null) : void
	{
		$this->map('PATCH', $request, $handler, $path);
	}

	/**
	 * DELETE HTTP Handler Method
	 *
     * @param string $request Request Uri
     * @param string|callable $handler Callback Handler
     * @param string|null $path Controler's path
     * @return void
    */	
	public function delete($request, $handler, $path = null) : void
	{
		$this->map('DELETE', $request, $handler, $path);
	}

	/**
	 * Manual set of HTTP Handler Method
	 *
     * @param string $method Method Name
     * @param string $request Request Uri
     * @param string|callable $handler Callback Handler
     * @param string|null $path Controler's path
     * @return void
    */	
	public function set($method, $request, $handler, $path = null) : void
	{

		$this->map($method, $request, $handler, $path);

	}

	/**
	 * get uri and arguments
	 *
     * @param string $request
     * @return array
    */	

	private function findRouteAndSetArgs($request){
		$args = []; // arguments
		$found = [false]; // found status

		if ($request == '/' && $this->request->requestUri == '/') {
			// Found root web
			$found[0] = true;
		} else {
			// Parse Params
			$params = $this->parseParams($request);
			// Add Argumenrs
			if (is_array($params)) {
				$args = $params[0];
				// Check for all params
				if (!(in_array(false, $params[1]))) {
					// Set found if params are right
					$found[0] = true;
				}
			}
		}

		array_push($this->found, $found[0]);

		return [$found[0], $args];
	}

	/**
	 * Call to active controller
	 *
     * @param string|callable $handler 
     * @param string|null $path handler path
     * @return void
    */	
	public function call($handler, $path = null) : void
	{

		// Set Params
		$this->params = [$this->request, $this->response];

		if (is_string($handler)) {

			// find controller and methid
			$controllerAndMethod = $this->parseControllerAndMethod($handler);

			// Load controller
			$this->controller = (!is_null($path)) ? $path . "\\" . $controllerAndMethod[0] : $controllerAndMethod[0];

				// Load method
			$this->method = isset($controllerAndMethod[1]) ? $controllerAndMethod[1] : $this->method;
		
			// Set handler
			$handler = [$this->controller, $this->method];	

		}

		$this->activeController = [$handler, $this->params];

		if (is_object($this->activeController[0])) {
			array_push($this->activeController, true);
		}


	}

	/**
	 * Mapping the request
	 *
     * @param string $method Method Name
     * @param string $request Request Uri
     * @param string|callable $handler Callback Handler
     * @param string|null $path Controler's path
     * @return void
    */	
	public function map($method, $request, $handler, $path = null)
	{

		// Check the Server Request Mothod
		if (isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], $this->requestMethod)) {
			// Filter Request Method
			if ($_SERVER['REQUEST_METHOD'] === $method) {
				
				// Passing to $route
				$route = $this->findRouteAndSetArgs($request);
				// Set page status 
				$found = $route[0];
				// Set Argument
				$args = $route[1];

				// Execute if page found
				if ($found) {

					// Set argument to controller
					$this->request->setArgs($args);
					$this->response->setArgs($args);

					// Call handler
					$this->call($handler, $path);

				} 

			}
		} 
	}

	/**
	 * Get active controller
	 *
     * @return string active controller
    */	
	public function getActiveController()
	{
		return $this->activeController;
	}

	/**
	 * HTTP code handler
	 *
     * @param string $method http response code
     * @param string|callable  
     * @param string|null $path controller's path
     * @return void
    */	
	public function handler($errorCode, $callback, $path = null){

		switch ($errorCode) {
			case '404':
				if (!in_array(true, $this->found)) {
					$this->call($callback, $path);
				}
				break;
			
			default:
				// Will be update for an other error response code
				break;
		}

		return;

	}

	/**
	 * Parse parameters
	 *
     * @param string $pattern uri pattern
     * @return array
    */	
	public function parseParams($pattern) 
	{	
		if (is_int($pattern)) {
			return false;	
		}

		$params = [];
		$rightUri = [false];
		
		$patternArray = explode("/", trim($pattern, "/"));
		$requestUriArray = explode("/", trim($this->request->requestUri, "/"));

		if (count($patternArray) === count($requestUriArray)) {
			for ($i=0; $i < count($patternArray); $i++) {
				$rightUri[$i] = false;
				if ($patternArray[$i] !== $requestUriArray[$i]) {
					$match = preg_match("/\{(.*?)\}/", $patternArray[$i], $matched);
					if ($match && $requestUriArray[$i] != '') {
						$params[$matched[1]] = $requestUriArray[$i];
						$rightUri[$i] = true;
					} else {

					}

				} else { 
					$rightUri[$i] = true;
				}
			}
		}

		return [$params, $rightUri];

	}

	/**
	 * Get controller name and method name
	 *
     * @param string $pattern
     * @return array
    */	
	private function parseControllerAndMethod($pattern) : array 
	{
		if (strpos($pattern, '@')) {
			return explode("@", $pattern);
		}

		return [$pattern];
	}
}
		
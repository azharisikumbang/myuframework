<?php
namespace Myu\Components\Routing;

/**
 * Route Class
 *
 */
class Router
{
	private $app;

	private $request;

	private $controller = 'ErrorController';

	private $method = 'pageNotFound';

	private $params = [];

	private $found = [];

	private $requestMethod = ["GET", "POST", "PUT", "PATCH", "DELETE"];

	private $path;

	private $activeController;

	public function __construct(Request $request, Response $response, $path = null)
	{
		$this->request = $request;
		$this->response = $response;
		$this->path = $path;
	}
	
	/**
     * @param string $request Request Uri
     * @param string $handler Callback Handler
     * @param string $path use for folder on class controller
     * @return void
     */
	public function get($request, $handler, $path = null) : void
	{

		$requestMethod = 'GET';

		$this->map($requestMethod, $request, $handler, $path);

	}

	public function post($request, $handler, $path = null) : void
	{

		$requestMethod = 'POST';

		$this->map($requestMethod, $request, $handler, $path);

	}

	public function put($request, $handler, $path = null) : void
	{

		$requestMethod = 'PUT';

		$this->map($requestMethod, $request, $handler, $path);

	}

	public function patch($request, $handler, $path = null) : void
	{

		$requestMethod = 'PATCH';

		$this->map($requestMethod, $request, $handler, $path);

	}

	public function delete($request, $handler, $path = null) : void
	{

		$requestMethod = 'DELETE';

		$this->map($requestMethod, $request, $handler, $path);

	}

	public function set($method, $request, $handler, $path = null) : void
	{

		$this->map($method, $request, $handler, $path);

	}

	private function findRouteAndSetArgs($request){
		$args = [];
		$found = [false];

		

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

					// Call handler
					$this->call($handler, $path);

				} 

			}
		} 

		return;

	}

	public function getActiveController()
	{
		return $this->activeController;
	}

	// For custom handler
	public function handler(int $errorCode, $callback){

		switch ($errorCode) {
			case 404:
				if (!in_array(true, $this->found)) {
					$this->call($callback);
				}
				break;
			
			default:
				// Will be update for an other error response code
				break;
		}

		return;

	}

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

	private function parseControllerAndMethod($pattern) : array 
	{
		if (strpos($pattern, '@')) {
			return explode("@", $pattern);
		}

		return [$pattern];
	}

	private function loadController($controller)
	{
		require $this->path . '/src/App/Controllers/'. $controller .'.php';
	}

}
		
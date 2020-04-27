<?php
namespace Myu\Components\Routing;

/**
 * Request Handler
 */
class Request
{
	/**
	 * @var get http request method
	 */
	private $requestMethod;

	/**
	 * @var array of arguments
	 */
	private $args = [];

	/**
	 * @var http header
	 */
	private $headers = [];

	/**
	 * Constructor for setup class variabel 
	 */
	public function __construct()
	{
		// get http header
		foreach ($_SERVER as $key => $value) {
			$key = strtolower(str_replace("_", "-", $key));
			$this->headers[$key] = $value;
		}
		
		/**
		 * Get header of server software
		 * this configuration for apache webserver because apache doesnt set query string if we access root project
		 * the param route was config on .htaccess file 
		 */
		$serverName = strtolower(explode('/', $this->getHeader('server-software'))[0]);
		if (preg_match('/apache/', $serverName)) {
			$this->requestUri = isset($_GET['route']) ? $_GET['route'] : '/';
		} else {
			$this->requestUri = $_SERVER['REQUEST_URI'];
		}

		$this->requestUri = trim($this->requestUri, '/');

		
	}

	/**
	 * get http header
	 *
	 * @param string $header header name
	 * @return string
	 */
	public function getHeader($header)
	{
		return $this->headers[$header];
	}

	/**
	 * get body contents
	 *
	 * @return array|bool
	 */
	public function getBody()
	{
		if ($this->getHeader('request-method') == 'POST') 
		{
			return $_POST;
		}

		return;
	}

	/**
	 * get single http request arguments
	 *
	 * @param string $arg argument name
	 * @return mixed
	 */
	public function getArg($arg){
		return $this->args[$arg];
	}

	/**
	 * set single the argument
	 *
	 * @param string $arg argument name
	 * @param mixed $value argument value
	 * @return void
	 */
	public function setArg($arg, $value){
		$this->args[$arg] = $value;
	}

	/**
	 * get all arguments
	 *
	 * @return array
	 */
	public function getArgs(){
		return $this->args;
	}

	/**
	 * set arguments
	 *
	 * @param mixed $args
	 * @return void
	 */
	public function setArgs($args){
		$this->args = $args;
	}

}	 
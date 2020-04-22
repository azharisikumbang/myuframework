<?php
namespace Myu\Components\Routing;


/**
 * Request Handler
 */
class Request 
{
	private $requestMethod;

	private $args = [];

	private $headers = [];

	public function __construct()

	{

		foreach ($_SERVER as $key => $value) {
			$key = strtolower(str_replace("_", "-", $key));
			$this->headers[$key] = $value;
		}

		$serverName = strtolower(explode('/', $this->getHeader('server-software'))[0]);

		if (preg_match('/apache/', $serverName)) {
			$this->requestUri = isset($_GET['route']) ? $_GET['route'] : '/';
		} else {
			$this->requestUri = $_SERVER['REQUEST_URI'];
		}

		$this->requestUri = trim($this->requestUri, '/');

		
	}

	public function getHeader($header)
	{
		return $this->headers[$header];
	}

	public function getBody()
	{
		if ($this->getHeader('request-method') == 'GET') 
		{
			return;
		}

		if ($this->getHeader('request-method') == 'POST') 
		{
			return $_POST;
		}
	}

	public function getArg($arg){
		return $this->args[$arg];
	}

	public function setArg($arg, $value){
		$this->args[$arg] = $value;
		return;
	}

	public function getArgs(){
		return $this->args;
	}

	public function setArgs($args){
		$this->args = $args;
	}

	public function getJSON(){
		return json_encode($this->args);
	}

}
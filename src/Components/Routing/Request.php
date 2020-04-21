<?php
namespace Myu\Components\Routing;


/**
 * Request Handler
 */
class Request 
{
	private $requestMethod;

	private $args = [];

	public function __construct()

	{
		$this->requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
		$this->requestUri = trim(isset($_GET['route']) ? $_GET['route'] : $_SERVER['REQUEST_URI'], "/");
	}

	public function getBody()
	{
		if ($this->requestMethod == 'GET') 
		{
			return;
		}

		if ($this->requestMethod == 'POST') 
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
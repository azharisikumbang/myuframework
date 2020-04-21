<?php
namespace Myu\Components\Routing;

/**
 * Request Handler
 */
class Response 
{

	private $args = [];

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

	public function getJson(){
		echo json_encode($this->args);
	}

	public function getArg($arg){
		return $this->args[$arg];
	}

	public function getArgs(){
		return $this->params;
	}

}
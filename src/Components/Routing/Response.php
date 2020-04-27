<?php
namespace Myu\Components\Routing;

/**
 * Request Handler
 */
class Response 
{
	/**
	 * @var arguments
	 */
	private $args = [];

	/**
	 * set all arguments
	 *
	 * @param mixed $args
	 * @return void
	 */
	public function setArgs($args)
	{
		$this->args = $args;
	}

	/**
	 * get body contents
	 *
	 * @return array|bool
	 */
	public function getBody()
	{
		if ($this->requestMethod == 'POST') 
		{
			return $_POST;
		}

		return;
	}

	/**
	 * get arguments by json data type
	 *
	 * @return aplication/json
	 */
	public function getJson(){
		header('Content-type: application/json');
		return json_encode($this->args);
	}

}
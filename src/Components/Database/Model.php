<?php
namespace Myu\Components\Database;

use Myu\Components\Database\Database;

/**
* Model Handler
*
*/
class Model
{
	/**
	* @var instance of Myu\Components\Database\Database
	*/
	protected $db;

	/**
	* Setup instance
	*
	* @return void
	*/
	public function __construct()
	{
		$this->db = new Database($this->table);
	}
}


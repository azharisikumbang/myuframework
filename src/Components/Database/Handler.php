<?php
namespace Myu\Components\Database;

use PDO;
use Myu\Components\Database\Connector;

/**
 * Database Handler
 */
class Handler
{
	/**
	* @var instance of Myu\Components\Database\Connector
	*/
	protected $connector;

	/**
	* @var PDO statement
	*/
	protected $stmt;

	/**
	* Create instance for connector
	*/
	public function __construct(){
		$this->connector = new Connector;
	}

	/**
	* Set Params for query statement
	*
	* @param array $data
	* @return array
	*/
	public function setParams($data)
	{
		$bindParams = [];
		$keyString = "";
		$keyBindString = "";
		$equalString = "";

		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$bindParams[":". $key] = $value;
				$keyString .= $key . ", ";
				$keyBindString .= ":". $key . ", ";
				$equalString .= $key . " = :" . $key . ", ";
			}
		}

		$keyString = rtrim(trim($keyString), ",");
		$keyBindString = rtrim(trim($keyBindString), ",");
		$equalString = rtrim(trim($equalString), ",");

		$params = [
			'keyString' => $keyString,
			'keyBindString' => $keyBindString,
			'bindParams' => $bindParams,
			'equalString' => $equalString
		];

		return $params;
	}

	/**
	* Prepare statement
	*
	* @param string $query
	* @return void
	*/
	public function prepare($query) : void
	{
		$this->stmt = $this->connector->dbh->prepare($query);
	}

	/**
	* Binding param and value
	*
	* @param string $param
	* @param string value
	* @param PDO CONSTANT|null type
	* @return void
	*/
	public function bind($param, $value, $type = null) : void
	{
		if (is_null($type)) {
			switch (true) {
				case is_int($value) :
		          $type = PDO::PARAM_INT;
		          break;
		        case is_bool($value) :
		          $type = PDO::PARAM_BOOL;
		          break;
		        case is_null($value) :
		          $type = PDO::PARAM_NULL;
		          break;
		        default :
		          $type = PDO::PARAM_STR;
			}
		}

		$this->stmt->bindValue($param, $value, $type);
	}

	/**
	* Executing statement
	*
	* @return void
	*/
	public function execute() 
	{
		$this->stmt->execute();
	}

	/**
	* Fetching data by type of fetching (single|count|null) 
	* types are base on PDO CONSTANT
	*
	* @param string $fetch
	* @param PDO CONSTANT|null type
	* @return array|int|null
	*/
	public function fetch($fetch)
	{	

		switch ($fetch) {
			case 'single':
				$action = 'fetch';
				break;
			case 'count':
				$action = 'fetchColumn';
				break;
			default:
				$action = 'fetchAll';
				break;
		}

		$params = ($fetch != '') ? PDO::FETCH_ASSOC : 0; 
		return $this->stmt->$action($params);
	}

	/**
	* Count data
	*
	* @return int
	*/
	public function count(){
		return $this->stmt->fetchColumn();
	}
}

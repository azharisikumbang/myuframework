<?php
namespace Myu\Components\Database;

use PDO;
use Myu\Components\Database\Connector;
/**
 * Database Handler
 */
class Handler
{
	protected $connector;

	protected $stmt;


	public function __construct(){
		$this->connector = new Connector;
	}

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

	public function prepare($query) : void
	{
		$this->stmt = $this->connector->dbh->prepare($query);
	}

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

	public function execute() 
	{
		return $this->stmt->execute();
	}

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

	public function count(){
		return $this->stmt->fetchColumn();
	}
}

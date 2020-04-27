<?php
namespace Myu\Components\Database;

use Myu\Components\Database\Handler;
/**
 * Database Handler
 */
class Database
{
	/**
	* @var Myu\Components\Database\Handler
	*/
	protected $handler;

	/**
	* @var string table name
	*/
	protected $table;	

	/**
	* Constructor for Database class
	* @param string $table will provide table name of database when class build
	* @return void
	*/
	public function __construct(string $table = null)
	{
		$this->table = $table;
		$this->handler = new Handler;
	}

	/**
	* Querying to database
	* @param string $query string of query statemant
	* @return void
	*/
	public function query($query)
	{
		$this->handler->prepare($query);
	}

	/**
	* Get single data from database
	* @param mixed $value value for selecting data
	* @param string $key key for selecting data 
	* @return array|null
	*/
	public function single($value, $key = "id")
	{
		$this->query("SELECT * FROM {$this->table} WHERE {$key} = :{$key}");
		$this->handler->bind($key, $value);
		$this->handler->execute();
		return $this->handler->fetch('single');
	}

	/**
	* Get all rows
	* @return array|null
	*/
	public function all()
	{
		$this->query("SELECT * FROM {$this->table}");
		$this->handler->execute();
		return $this->handler->fetch('all');
	}

	/**
	* Count row of data
	* @param mixed $value value for selecting data
	* @param string $key key for selecting data 
	* @return int
	*/
	public function count($value, $key = "id")
	{
		$query = "SELECT COUNT(*) FROM {$this->table}";

		if (!is_null($value)) {
			$query .= " WHERE {$key} = :{$key}";
			$this->handler->prepare($query);
			$this->handler->bind(":". $key, $value);
		} else {
			$this->handler->prepare($query);
		}

		$this->handler->execute();

		return $this->handler->fetch('count');

	}

	/**
	* Insert data handler
	* @param array $data
	* @return bool
	*/
	public function insert($data)
	{
		
		$params = $this->handler->setParams($data);

		$query = "INSERT INTO {$this->table} ({$params['keyString']}) VALUES ({$params['keyBindString']})";

		$this->handler->prepare($query);

		foreach ($params['bindParams'] as $param => $value) {
			$this->handler->bind($param, $value);
		}

		$status = $this->handler->execute();

		return $status;

	}

	/**
	* Update data handler
	* @param array $data value for selecting data
	* @param string $key key for selecting data 
	* @return bool
	*/
	public function update($data, $value, $key = "id")
	{
		$params = $this->handler->setParams($data);

		$query = "UPDATE {$this->table} SET {$params['equalString']} WHERE {$key} = :{$key}_key";

		$this->handler->prepare($query);


		foreach ($params['bindParams'] as $param => $paramValue) {
			$this->handler->bind($param, $paramValue);
		}

		$this->handler->bind(":" . $key . "_key", $value);

		$status = $this->handler->execute();
		return $status;
	}

	/**
	* delete data handler
	* @param mixed $value value for selecting data
	* @param string $key key for selecting data 
	* @return bool
	*/	
	public function delete($value, $key = "id")
	{
		$query = "DELETE FROM {$this->table} WHERE {$key} = :{$key}";
		$this->handler->prepare($query);
		$this->handler->bind(":". $key, $value);
		$status = $this->handler->execute();
		return $status;
	}
}


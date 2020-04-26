<?php
namespace Myu\Components\Database;

use Myu\Components\Database\Handler;
/**
 * Database Handler
 */
class Database
{
	protected $handler;
	protected $table;	

	public function __construct(string $table)
	{
		$this->table = $table;
		$this->handler = new Handler;
	}

	public function query($query)
	{
		$this->handler->prepare($query);
	}

	public function single($value, $key = "id")
	{
		$this->query("SELECT * FROM {$this->table} WHERE {$key} = :{$key}");
		$this->handler->bind($key, $value);
		$this->handler->execute();
		return $this->handler->fetch('single');
	}

	public function all()
	{
		$this->query("SELECT * FROM {$this->table}");
		$this->handler->execute();
		return $this->handler->fetch('all');
	}

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

	public function delete($value, $key = "id")
	{
		$query = "DELETE FROM {$this->table} WHERE {$key} = :{$key}";
		$this->handler->prepare($query);
		$this->handler->bind(":". $key, $value);
		$status = $this->handler->execute();
		return $status;
	}
}


<?php
namespace Myu\Components\Database;

use PDO;

/**
 * Database connecter
 */
class Connector 
{
	private $host;
	private $user; 
	private $pass;
	private $db;
	private $port;
	private $driver;

	public $dbh;

	public function __construct()
	{
		try {
			$this->config();
			$this->dbh = new PDO(
				"{$this->driver}:host={$this->host};dbname={$this->db};port={$this->port}", $this->user, $this->pass, [
					PDO::ATTR_PERSISTENT => true,
      				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				]
			);

		} catch (Exception $e) {
			echo "Error : 	" . $e->getMessage();
			die;
		}
	}

	private function config()
	{
		// Set database config
		$this->driver = env("DB_DRIVER");
		$this->host = env("DB_HOST");
		$this->port = env("DB_PORT");
		$this->user = env("DB_USER");
		$this->pass = env("DB_PASS");
		$this->db = env("DB_NAME");
	}
}
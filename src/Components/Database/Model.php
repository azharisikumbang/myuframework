<?php
namespace Myu\Components\Database;

use Myu\Components\Database\Database;


class Model
{
	public function __construct()
	{
		$this->db = new Database($this->table);
	}
}


<?php
namespace Myu\Bootstrap;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;

/**
 * Load Environment 
 */
class Environment
{
	
	public static function load($path)
	{
		try {
			return Dotenv::createImmutable($path)->load();
		} catch (Exception $e) {
			echo "Error " . $e->getMessage();
			die;
		}
	}
}
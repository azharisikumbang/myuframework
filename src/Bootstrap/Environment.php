<?php
namespace Myu\Bootstrap;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;

/**
 * Environment Handler 
 */
class Environment
{
	/**
	* This static function will be load a environtment file
	* @param string @path for file path
	* @return void; 
	*/
	public static function load($path)
	{
		// Try catch for environment file
		try {
			return Dotenv::createImmutable($path)->load();
		} catch (Exception $e) {
			http_response_code(500);
			throw new \Myu\Handler\Error("Your environtment files doesnt exitst", 500);
		}
	}
}
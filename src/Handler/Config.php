<?php
namespace Myu\Handler;

/**
 * Configuration Class
 */
class Config
{
	private static $basePath;
	private static $config;

	public static function getBasePath()
	{
		return self::$basePath;
	}

	public static function setBasePath($basePath) 
	{
		self::$basePath = $basePath;
	}

	public static function setAutoload()
	{
		self::$config['autoload'] = require self::$basePath . "/src/App/Config/autoload.php";
		self::setConfigFile();
	}

	public static function setConfigFile()
	{
		foreach (self::$config['autoload'] as $key => $value) {
			self::$config[$value] = require self::$basePath . "/src/App/Config/" . $value  . ".php";
		}
	}

	public static function getConfig($name)
	{
		if (!array_key_exists($name, self::$config)) {
			return false;
		}

		return self::$config[$name];
	}

}
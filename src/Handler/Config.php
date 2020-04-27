<?php
namespace Myu\Handler;

/**
 * Configuration Class
 */
class Config
{
	/**
     * @var app base path
     */	
	private static $basePath;
	
	/**
     * @var all configuration
     */	
	private static $config;

	/**
     * Get app base path
	 * 
	 * @return string
     */
	public static function getBasePath()
	{
		return self::$basePath;
	}

	/**
     * Set app base path
	 * 
	 * @return void
     */
	public static function setBasePath($basePath) 
	{
		self::$basePath = $basePath;
	}

	/**
     * Set autoload 
	 * 
	 * @return void
     */
	public static function setAutoload()
	{
		self::$config['autoload'] = require self::$basePath . "/src/App/Config/autoload.php";
		self::setConfigFile();
	}

	/**
     * set configuration file and load it
	 * 
	 * @return void
     */
	public static function setConfigFile()
	{
		foreach (self::$config['autoload'] as $key => $value) {
			self::$config[$value] = require self::$basePath . "/src/App/Config/" . $value  . ".php";
		}
	}

	/**
     * Get configuration
	 * 
	 * @param string $name config index name
	 * @return mixed
     */
	public static function getConfig($name)
	{
		if (!array_key_exists($name, self::$config)) {
			return false;
		}

		return self::$config[$name];
	}

}
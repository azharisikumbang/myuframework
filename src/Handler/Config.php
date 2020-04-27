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
	private static $config = [];

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

	public static function setConfig($name, $value)
	{
		if (array_key_exists($name, self::$config)) {	
			throw new \Myu\Handler\Error("Falied to set config, please check your configuration", 500);
		}

		self::$config[$name] = $value;
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
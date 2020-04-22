<?php
namespace Myu\Config;

/**
 * Configuration Class
 */
class Config
{
	private static $basePath;

	public static function getBasePath()
	{
		return self::$basePath;
	}

	public static function setBasePath($basePath) 
	{
		self::$basePath = $basePath;
	}

}
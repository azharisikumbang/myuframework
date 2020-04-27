<?php
if (!function_exists('baseUrl')) 
{	
	/**
	* Get base url 
	* generate from environtment file
	*
	* @return string
	*/
	function baseUrl()
	{
		return getenv("APP_URL");
	}
}

if (!function_exists('env')) 
{
	/**
	* Get environment configuration 
	*
	* @return string
	*/
	function env($env)
	{
		return getenv($env);
	}
}
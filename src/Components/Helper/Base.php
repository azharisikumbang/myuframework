<?php

if (!function_exists('baseUrl')) 
{
	function baseUrl()
	{
		return "app";
	}
}

if (!function_exists('env')) 
{
	function env($env)
	{
		return getenv($env);
	}
}
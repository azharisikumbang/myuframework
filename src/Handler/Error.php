<?php
namespace Myu\Handler;

use Exception;

/**
 * Error Handler
 */
class Error extends Exception
{

	public function __construct($message, $error_code, $file = null, $line = 0, Exception $previous = null)
	{
		$this->file = (!is_null($file)) ? $file : __FILE__;
		$this->line = (!is_null($line)) ? $line : __LINE__;
		parent::__construct($message, $error_code, $previous);
	}

	public function __toString() {
        return __CLASS__ . " [{$this->code}]: {$this->message}\n";
    }

	
}
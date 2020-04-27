<?php
namespace Myu\Handler;

use Exception;

/**
 * Error Handler
 */
class Error extends Exception
{
	/**
     * Error Constructor
	 * 
	 * @param string $message displayed message
	 * @param int $code error code
	 * @param string $file
	 * @param string $line
	 * @param constant|null $previous
	 * @return void
     */

	public function __construct($message, $code, $file = null, $line = 0, Exception $previous = null)
	{
		$this->file = (!is_null($file)) ? $file : __FILE__;
		$this->line = (!is_null($line)) ? $line : __LINE__;
		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
        return __CLASS__ . " [{$this->code}]: {$this->message}\n";
    }

	
}
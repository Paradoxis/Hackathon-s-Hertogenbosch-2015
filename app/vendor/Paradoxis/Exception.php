<?php

/**
 * Namespace
 * @copyright (c) 2014 - 2015 | Paradoxis
 */
namespace Paradoxis;

/**
 * Class Exception
 * @package Paradoxis
 */
class Exception extends \Exception {

	/**
	 * Redefine the exception so message isn't optional
	 * @param string $message
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct($message, $code = 0, Exception $previous = null) {
		// We can add additional code here
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Custom string representation of object
	 * @return string
	 */
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}
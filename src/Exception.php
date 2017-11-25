<?php
/**
 * @class
 * Base class for template view
 */

namespace ChronosWP;

class Exception extends \Exception {

	/**
	 * AZ Exception constructor
	 *
	 * @param string $message
	 * @param int $code
	 * @param Exception $previous
	 *
	 * @access public
	 */
	public function __construct( $message, $code = 0, Exception $previous = null ) {
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * Define __toString() method
	 *
	 * @return string
	 * @access public
	 */
	public function __toString() {
		echo __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        die();
	}

}